<?php
/*
 * Dies ist sozusagen der API-Kern mit sämtlichen Datenbank-Funktionen
 * Dokumentation in ../doc/functions.txt
 */
// connect to the database
$connection = pg_connect("host=$host dbname=$db user=$username password=$pw") or die("Error while connecting to the Database\n");

// function getParticipantsByTrack($track), returns participant data for $track-id
function getParticipantsByTrack($track) {
    //$query = "SELECT name, vorname, verein, geschlecht, altersklasse, anmeldezeitpunkt FROM main.anmeldung INNER JOIN main.laeufer ON (anmeldung.laeufer_id = laeufer.id) WHERE anmeldung.strecke_id =".pg_escape_literal($track)." ORDER BY altersklasse, name;";
    $query = "SELECT name, vorname, verein, geschlecht, altersklasse, anmeldezeitpunkt, count(zahlungen.laeufer_id) as cnt FROM main.anmeldung INNER JOIN main.laeufer ON (anmeldung.laeufer_id = laeufer.id) LEFT OUTER JOIN zahlungen ON (anmeldung.laeufer_id = zahlungen.laeufer_id AND zahlungen.wettkampf_id = (SELECT wettkampf_id FROM strecke WHERE id = ".pg_escape_literal($track).")) WHERE anmeldung.strecke_id =". pg_escape_literal($track) ." GROUP BY name, vorname, verein, geschlecht, altersklasse, anmeldezeitpunkt ORDER BY altersklasse, name;";
    return $query;
}

// function getParticipantsByEvent($event)
function getParticipantsByEvent($event) {
    $query = "SELECT DISTINCT name, vorname, verein, geschlecht, altersklasse FROM laeufer JOIN anmeldung on laeufer.id=anmeldung.laeufer_id JOIN strecke ON anmeldung.strecke_id = strecke.id WHERE strecke.wettkampf_id = $event ORDER BY altersklasse, name;";
    return $query;
}

// function activateHelper($id, $g)
function activateHelper($id, $g) {
    $query = "SELECT activationcode as ac FROM main." . rtt($g) . " WHERE id=".pg_escape_literal($id).";";
    return $query;
}

// function activateAccount($id, $g)
function activateAccount($id, $g) {
    $query = "UPDATE main." . rtt($g) . " SET activationcode=NULL WHERE id=".pg_escape_literal($id).";";
    return $query;
}

// function getParticipants($count, $offset)
function getParticipants($count, $offset) {
    $query = "SELECT * FROM main.laeufer ORDER BY id DESC LIMIT ".pg_escape_literal($count)." OFFSET ".pg_escape_literal($offset).";";
    return $query;
}

// function getSingleParticipant($id), returns information on a single participant
function getSingleParticipant($id) {
    $query = "SELECT name, vorname, verein, geschlecht, geburtsdatum, email, registrierdatum FROM main.laeufer WHERE id = ".pg_escape_literal($id).";";
    return $query;
}

// function getActivationData($group, $mail)
function getActivationData($mail, $group) {
    if ($group === 2) {
        $query = "SELECT name as fullname, id, activationcode FROM main." . rtt($group) . " WHERE email=".pg_escape_literal($mail).";";
    } else {
        $query = "SELECT vorname || ' ' || name as fullname, id, activationcode FROM main." . rtt($group) . " WHERE email=".pg_escape_literal($mail).";";
    }
    return $query;
}

// function getIdByEmail($g, $email), returns id of user $email in group $g
function getIdByEmail($g, $email) {
    $query = "SELECT id FROM main." . rtt($g) . " WHERE email = ".pg_escape_literal($email).";";
    return $query;
}

// administrative 


function editLaeuferAdmin($id, $name, $vorname, $verein, $geschlecht, $geburtsdatum, $email) {
    if (fetchResults("SELECT COUNT(*) FROM main.laeufer WHERE email=".pg_escape_literal($email).";")[0]['count'] != 0) {
        return -1;
    } else {
        $query = "UPDATE main.laeufer SET name = ".pg_escape_literal($name).", vorname = ".pg_escape_literal($vorname).", verein = ".pg_escape_literal($verein).", geschlecht = ".pg_escape_literal($geschlecht).", geburtsdatum = ".pg_escape_literal($geburtsdatum).","
                . " email = ".pg_escape_literal($email)." WHERE id = ".pg_escape_literal($id).";";
        return $query;
    }
}

// function rtt($role) ROLE-TO-TABLE
function rtt($role) {
    switch ($role) {
        case 1: return "organisator";
        case 2: return "charity_partner";
        case 3: return "laeufer";
        default: die("Invalid role id");
    }
}

// function editPasswort($id $role, $passwort)
function editPasswort($id, $role, $passwort) {
    $query = "UPDATE main." . rtt($role) . " SET passwort = '" . password_hash($passwort, PASSWORD_BCRYPT) . "' WHERE id = ".pg_escape_literal($id).";";
    return $query;
}

// function addLaeufer($name, $vorname, $verein, $geschlecht, $geburtsdatum, $email, $passwort)
// $geschlecht = M oder F
// $geburtsdatum pattern: YYYY-MM-DD  !!! WICHTIG !!! Postgresql nimmt zwar DD.MM.YYYY an, inter-
// pretiert aber z.B. 01.02.2012 als MM.DD.YYYY und 22.10.2012 als DD.MM.YYYY
// $verein bei leerer Eingabe als leeren String übergeben!
function addLaeufer($name, $vorname, $verein, $geschlecht, $geburtsdatum, $email, $passwort) {

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "-1";
    } elseif (empty($name) || empty($vorname) || empty($geschlecht) || empty($geburtsdatum) || empty($passwort)) {
        return "-2";
    } elseif (fetchResults("SELECT COUNT(*) FROM main.laeufer WHERE email=".pg_escape_literal($email).";")[0]['count'] != 0) {

        return "-3";
    } else {
        $query = "INSERT INTO main.laeufer VALUES (".pg_escape_literal($name).", ".pg_escape_literal($vorname).", ".pg_escape_literal($verein).", ".pg_escape_literal($geschlecht).", ".pg_escape_literal($geburtsdatum).", ".pg_escape_literal($email).", DEFAULT, '" . password_hash($passwort, PASSWORD_BCRYPT) . "', DEFAULT, '" . substr(md5(rand()), 0, 20) . "');";
        return $query;
    }
}

// function editEmail($id, $role, $email)
function editEmail($id, $role, $email) {
    if (fetchResults("SELECT COUNT(*) FROM main." . rtt($role) . " WHERE email=".pg_escape_literal($email).";")[0]['count'] != 0) {
        return -1;
    } else {
        $query = "UPDATE main." . rtt($role) . " SET email = ".pg_escape_literal($email)." WHERE id = ".pg_escape_literal($id).";";
        return $query;
    }
}

// function editLaeufer($id, $verein), edit your own detail
function editLaeufer($id, $verein) {
    $query = "UPDATE main.laeufer SET verein = ".pg_escape_literal($verein)." WHERE id = ".pg_escape_literal($id).";";
    return $query;
}

// function getTracksByEvent($event), returns information on the tracks of a specified event
function getTracksByEvent($event) {
    $query = "SELECT id, laenge, bezeichnung, beschreibung, streckenverlauf_url, termin FROM main.strecke WHERE wettkampf_id = ".pg_escape_literal($event)." ORDER BY id ASC;";
    return $query;
}
// function getTracksByEventByParticipation($event), returns information on the tracks of a specified event
function getTracksByEventByParticipation($event, $runner) {
    $query = "SELECT id, laenge, bezeichnung, beschreibung, streckenverlauf_url, termin FROM main.strecke JOIN anmeldung ON (strecke.id = anmeldung.strecke_id) WHERE wettkampf_id = ".pg_escape_literal($event)." AND anmeldung.laeufer_id = ".  pg_escape_literal($runner)." ORDER BY id ASC;";
    return $query;
}


// function getTracksByEventForRunner($event, $runner), returns information on the tracks of a specified event
//    function getTracksByEventForRunner($event, $runner) {
//        $query = "SELECT id, laenge, bezeichnung, beschreibung, streckenverlauf_url, termin, anmeldezeitpunkt FROM main.strecke LEFT OUTER JOIN main.anmeldung ON strecke.id = anmeldung.strecke_id WHERE wettkampf_id = ".pg_escape_literal($event)." AND laeufer_id = ". pg_escape_literal($runner) ." ORDER BY id ASC;";
//        return $query;
//    }


// function getEvents($count, $offset), returns $count Events, starting at Event $offset+1
function getEvents($count, $offset) {
    $query = "SELECT *, lower(termin)::date AS von, upper(termin)::date AS bis, wettkampf.anzahl_strecken FROM main.wettkampf ORDER BY id DESC LIMIT ".pg_escape_literal($count)." OFFSET ".pg_escape_literal($offset).";";
    return $query;
}
// function getEventsByRunner($count, $offset, $id), returns $count Events, starting at Event $offset+1
function getEventsByRunner($count, $offset, $id) {
    $query = "SELECT *, lower(termin)::date AS von, upper(termin)::date AS bis, wettkampf.anzahl_strecken FROM main.wettkampf JOIN wettkampfteilnahme ON wettkampf.id = wettkampfteilnahme.wettkampf_id WHERE wettkampfteilnahme.laeufer_id = ".pg_escape_literal($id)." ORDER BY id DESC LIMIT ".pg_escape_literal($count)." OFFSET ".pg_escape_literal($offset).";";
    return $query;
}
// function getFutureEvents($count, $offset), returns $count Events, starting at Event $offset+1
function getFutureEvents($count, $offset) {
    $query = "SELECT *, lower(termin)::date AS von, upper(termin)::date AS bis, wettkampf.anzahl_strecken FROM main.wettkampf WHERE (now() BETWEEN lower(termin) AND upper(termin)) OR now() < lower(termin)  ORDER BY termin ASC LIMIT ".pg_escape_literal($count)." OFFSET ".pg_escape_literal($offset).";";
    return $query;
}

// function addEvent($bezeichnung, $termin, $ort, $website, $max_tn, $beschreibung)
function addEvent($bezeichnung, $termin, $ort, $website, $max_tn, $beschreibung, $geb, $iban, $bic, $betrag, $empfaenger) {
    $query = "INSERT INTO main.wettkampf VALUES (DEFAULT, ".pg_escape_literal($bezeichnung).", ".pg_escape_literal($termin).", ".pg_escape_literal($ort).", ".pg_escape_literal($website).", ".pg_escape_literal($max_tn).", ".pg_escape_literal($beschreibung).", ".pg_escape_literal($geb).", ".pg_escape_literal($iban).", ".pg_escape_literal($bic).", ".pg_escape_literal($betrag).", ".pg_escape_literal($empfaenger).");";
    return $query;
}

function getCharityByWettkampf($wett) {
    $query = "SELECT charity_projekt.id, bezeichnung FROM charity_projekt JOIN sponsoring_charity_wettkampf ON charity_projekt.id = sponsoring_charity_wettkampf.charity_projekt_id WHERE wettkampf_id=".pg_escape_literal($wett).";";
        return $query;
}
function addSponsoringProjektWettkampf($proj, $wett) {
    $query = "INSERT INTO sponsoring_charity_wettkampf VALUES (".pg_escape_literal($proj).", ".pg_escape_literal($wett).", DEFAULT);";
    return $query;
}
function removeSponsoringProjektwettkampf($proj, $wett) {
    $query = "DELETE FROM sponsoring_charity_wettkampf WHERE charity_projekt_id = ".pg_escape_literal($proj)." AND wettkampf_id = ".pg_escape_literal($wett).";";
    return $query;
}
function selectCharityProj($laeufer, $wett, $proj) {
    fetchResults("DELETE FROM sponsoring_laeufer_charityprojekt WHERE laeufer_id = ".pg_escape_literal($laeufer)." AND wettkampf_id = ".pg_escape_literal($wett).";");
    $query = "INSERT INTO sponsoring_laeufer_charityprojekt VALUES (".pg_escape_literal($laeufer).", ".pg_escape_literal($wett).", ".pg_escape_literal($proj).";";
    return $query;
}
function addSponsoringPartnerProjekt($proj, $part, $pausch) {
    fetchResults("DELETE FROM sponsoring_charityprojekt WHERE charity_projekt_id=".pg_escape_literal($proj)." AND charity_partner_id=".pg_escape_literal($part).";");
    $query ="INSERT INTO sponsoring_charityprojekt VALUES (".pg_escape_literal($proj).", ".pg_escape_literal($part).", ".pg_escape_literal($pausch).", DEFAULT);";
    return $query;
}
function getSponsoringPartnerProjekt($part) {
    $query ="SELECT bezeichnung, beschreibung, url, pauschale, bezahlt FROM sponsoring_charityprojekt as a JOIN charity_projekt as b ON a.charity_projekt_id = b.id WHERE a.charity_partner_id=".  pg_escape_literal($part) .";";
    return $query;
}
// function editEvent($id, $bezeichnung, $termin, $ort, $website, $max_tn, $beschreibung)
function editEvent($id, $bezeichnung, $termin, $ort, $website, $max_tn, $beschreibung, $geb, $iban, $bic, $betrag, $empfaenger) {
    $query = "UPDATE main.wettkampf SET bezeichnung = ".pg_escape_literal($bezeichnung).", termin = ".pg_escape_literal($termin).", ort = ".pg_escape_literal($ort).", website = ".pg_escape_literal($website).", max_tn = ".pg_escape_literal($max_tn).", beschreibung = ".pg_escape_literal($beschreibung).","
            . "gebuehrenpflichtig=".pg_escape_literal($geb).", iban=".pg_escape_literal($iban).", bic=".pg_escape_literal($bic).", betrag=".pg_escape_literal($betrag).", zahlungsempfaenger=".pg_escape_literal($empfaenger)." WHERE id = ".pg_escape_literal($id).";";
    return $query;
}

// function getSingleEvent($id)
function getSingleEvent($id) {
    $query = "SELECT *, lower(termin)::date AS von, upper(termin)::date AS bis, wettkampf.anzahl_strecken FROM main.wettkampf WHERE id = ".pg_escape_literal($id).";";
    return $query;
}

// function addTrack($wettkampf_id, $laenge, $bezeichnung, $beschreibung, $url, $termin)
function addTrack($wettkampf_id, $laenge, $bezeichnung, $beschreibung, $url, $termin) {
    $query = "INSERT INTO main.strecke VALUES (DEFAULT, ".pg_escape_literal($wettkampf_id).", ".pg_escape_literal($laenge).", ".pg_escape_literal($bezeichnung).", ".pg_escape_literal($beschreibung).", ".pg_escape_literal($url).", ".pg_escape_literal($termin).");";
    return $query;
}

// function editTrack($id, $wettkampf_id, $laenge, $bezeichnung, $beschreibung, $url, $termin)
function editTrack($id, $wettkampf_id, $laenge, $bezeichnung, $beschreibung, $url, $termin) {
    $query = "UPDATE main.strecke SET laenge = ".pg_escape_literal($laenge).", bezeichnung =".pg_escape_literal($bezeichnung).", beschreibung = ".pg_escape_literal($beschreibung).", streckenverlauf_url = ".pg_escape_literal($url).", termin = ".pg_escape_literal($termin)." WHERE id = ".pg_escape_literal($id)." AND wettkampf_id = ".pg_escape_literal($wettkampf_id).";";
    return $query;
}

// function getSingleCharityProjekt($id)
function getSingleCharityProjekt($id) {
    $query = "SELECT bezeichnung, beschreibung, url, spenden_start::date, spenden_ende::date FROM main.charity_projekt WHERE id = ".pg_escape_literal($id).";";
    return $query;
}

// function getCharityProjekte($count, $offset)
function getCharityProjekte($count, $offset) {
    $query = "SELECT *, spenden_start::date, spenden_ende::date FROM main.charity_projekt ORDER BY id DESC LIMIT ".pg_escape_literal($count)." OFFSET ".pg_escape_literal($offset).";";
    return $query;
}

// function getCharityPartner($count, $offset)
function getCharityPartner($count, $offset) {
    $query = "SELECT * FROM main.charity_partner ORDER BY id DESC LIMIT ".pg_escape_literal($count)." OFFSET ".pg_escape_literal($offset).";";
    return $query;
}

// function getSingleCharityPartner($id)
function getSingleCharityPartner($id) {
    $query = "SELECT * FROM main.charity_partner WHERE id = ".pg_escape_literal($id).";";
    return $query;
}

// function getTrackInformation($event, $track), returns information on a single track of a specified event
function getTrackInformation($event, $track) {
    $query = "SELECT laenge, bezeichnung, beschreibung, streckenverlauf_url, termin FROM main.strecke WHERE wettkampf_id = ".pg_escape_literal($event)." AND id = ".pg_escape_literal($track).";";
    return $query;
}

// function getCredentials($email, $role)
function getPass($email, $role) {
    $query = "SELECT passwort, id, activationcode FROM main.$role WHERE email = ".pg_escape_literal($email).";";
    return $query;
}

// function addParticipant
function addParticipant($track, $runner) {
    if (empty(fetchResults("SELECT * FROM main.anmeldung where strecke_id = ".pg_escape_literal($track)." AND laeufer_id = ".pg_escape_literal($runner).";"))) {

        $query = "INSERT INTO main.anmeldung (strecke_id, laeufer_id) VALUES (".pg_escape_literal($track).",".pg_escape_literal($runner).");";
        return $query;
    } else {
        return "";
    }
}


// function removeParticipant
function removeParticipant($track, $runner) {
    $query = "DELETE FROM main.anmeldung WHERE strecke_id = ".pg_escape_literal($track)." AND laeufer_id = ".pg_escape_literal($runner).";";
    return $query;
}

// function addCharityProjekt($bezeichnung, $beschreibung, $url, $spenden_start, $spenden_ende, $organisator_id)
function addCharityProjekt($bezeichnung, $beschreibung, $url, $spenden_start, $spenden_ende, $organisator_id) {
    if (empty($spenden_ende)) {
        $spenden_ende = "NULL";
    } else {
        $spenden_ende = "'" . $spenden_ende . "'";
    }
    $query = "INSERT INTO main.charity_projekt VALUES (DEFAULT, ".pg_escape_literal($bezeichnung).", ".pg_escape_literal($beschreibung).", ".pg_escape_literal($url).", ".pg_escape_literal($spenden_start).", ".pg_escape_literal($spenden_ende).", ".pg_escape_literal($organisator_id).");";
    return $query;
}

// function editCharityProjekt($id, $bezeichnung, $beschreibung, $url, $spenden_start, $spenden_ende)
function editCharityProjekt($id, $bezeichnung, $beschreibung, $url, $spenden_start, $spenden_ende) {
    $query = "UPDATE main.charity_projekt SET bezeichnung=".pg_escape_literal($bezeichnung).", beschreibung=".pg_escape_literal($beschreibung).", url=".pg_escape_literal($url).", spenden_start=".pg_escape_literal($spenden_start).", spenden_ende=".pg_escape_literal($spenden_ende)." WHERE id=".pg_escape_literal($id).";";
    return $query;
}

// function addCharityPartner($name, $adresse2, $strasse, $plz, $ort, $land, $telefon, $adresse1, $email, $transparenz, $passwort)
function addCharityPartner($name, $adresse2, $strasse, $plz, $ort, $land, $telefon, $adresse1, $email, $transparenz, $passwort) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "-1";
    } elseif (empty($name) || empty($adresse1) || empty($strasse) || empty($land) || empty($plz) || empty($ort) || empty($passwort)) {
        return "-2";
    } elseif (fetchResults("SELECT COUNT(*) FROM main.charity_partner WHERE email=".pg_escape_literal($email).";")[0]['count'] != 0) {

        return "-3";
    } else {
        $query = "INSERT INTO main.charity_partner VALUES (DEFAULT, ".pg_escape_literal($name).", ".pg_escape_literal($adresse2).", ".pg_escape_literal($strasse).", ".pg_escape_literal($plz).", ".pg_escape_literal($ort).", ".pg_escape_literal($land).", ".pg_escape_literal($telefon).", ".pg_escape_literal($adresse1).", ".pg_escape_literal($email).", ".pg_escape_literal($transparenz).", '" . password_hash($passwort, PASSWORD_BCRYPT) . "', '" . substr(md5(rand()), 0, 20) . "');";
        return $query;
    }
}

function getNotPaid($id) {
    $query = "SELECT name, vorname, verein FROM ((SELECT * FROM wettkampfteilnahme WHERE wettkampf_id = ".pg_escape_literal($id)." EXCEPT SELECT * FROM zahlungen WHERE wettkampf_id = ".pg_escape_literal($id).") AS foo JOIN laeufer ON laeufer_id = laeufer.id) AS bar;";
    return $query;
}

function getPaid($id) {
    $query = "SELECT name, vorname, verein FROM zahlungen JOIN laeufer ON laeufer_id = laeufer.id WHERE wettkampf_id = ".pg_escape_literal($id).";";
    return $query;
}

function addPayment($wettkampf, $laeufer) {
    $query = "INSERT INTO zahlungen VALUES(".pg_escape_literal($wettkampf).",".pg_escape_literal($laeufer).");";
    return $query;
}

function addPaymentCharityPartner($proj, $part) {
    $query = "UPDATE sponsoring_charityprojekt SET bezahlt=TRUE WHERE charity_projekt_id=".pg_escape_literal($proj)." AND charity_partner_id=".pg_escape_literal($part).";";
    return $query;
}

// function editCharityPartner
function editCharityPartner($id, $adresse2, $strasse, $plz, $ort, $land, $telefon, $adresse1, $transparenz) {
    $query = "UPDATE main.charity_partner SET adresse2=".pg_escape_literal($adresse2).", strasse=".pg_escape_literal($strasse).", plz=".pg_escape_literal($plz).", ort=".pg_escape_literal($ort).", land=".pg_escape_literal($land).", telefon=".pg_escape_literal($telefon).", adresse1=".pg_escape_literal($adresse1).", transparenzkennzeichen=".pg_escape_literal($transparenz)." WHERE id = ".pg_escape_literal($id).";";
    return $query;
}

// function editCharityPartnerAdmin
function editCharityPartnerAdmin($id, $adresse2, $strasse, $plz, $ort, $land, $telefon, $adresse1, $email, $transparenz) {
    if (fetchResults("SELECT COUNT(*) FROM main.charity_partner WHERE email='$email';")[0]['count'] != 0) {
        return -1;
    } else {
        $query = "UPDATE main.charity_partner SET adresse2=".pg_escape_literal($adresse2).", strasse=".pg_escape_literal($strasse).", plz=".pg_escape_literal($plz).", ort=".pg_escape_literal($ort).", land=".pg_escape_literal($land).", telefon=".pg_escape_literal($telefon).", adresse1=".pg_escape_literal($adresse1).", email=".pg_escape_literal($email).", transparenzkennzeichen=".pg_escape_literal($transparenz)." WHERE id = ".pg_escape_literal($id).";";
        return $query;
    }
}

// function removeTrack($id)
function removeTrack($id) {
    $query = "DELETE FROM main.strecke WHERE id=".pg_escape_literal($id).";";
    return $query;
}

// function removeEvent($id)
function removeEvent($id) {
    $query = "DELETE FROM main.wettkampf WHERE id=".pg_escape_literal($id).";";
    return $query;
}

// function removeCharityProjekt($id)
function removeCharityProjekt($id) {
    $query = "DELETE FROM main.charity_projekt WHERE id=".pg_escape_literal($id).";";
    return $query;
}

// function removeCharityPartner($id)
function removeCharityPartner($id) {
    $query = "DELETE FROM main.charity_partner WHERE id=".pg_escape_literal($id).";";
    return $query;
}

// function removeSingleParticipant($id)
function removeSingleParticipant($id) {
    $query = "DELETE FROM main.laeufer WHERE id=".pg_escape_literal($id).";";
    return $query;
}

// function fetchResults() executes our query and returns a two dimensional array
function fetchResults($query) {
    global $connection;
    if (!empty($query)) {
        if (DEBUGMODE == 1) {
            echo "\n" . $query;
        }
        $resultset = pg_query($connection, $query) or die("Cannot execute query: $query\n");
        $resultarray = pg_fetch_all($resultset);
        if (DEBUGMODE == 1) {
            print_r($resultarray);
            echo "fetchResult successful";
        }
        $query = "";
        return $resultarray;
    } else {
        return "-1";
    }
}

function shutdown() {
    global $connection;
    pg_close($connection);
}

register_shutdown_function('shutdown');
?>
