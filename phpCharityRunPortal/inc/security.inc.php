<?php
/*
 * Hier finden sich die Sicherheits- und Sessionfunktionen
 * Es wird eine SSL Verbindung erzwungen, desweiteren werden 
 * cookies mit dem httponly flag erzwungen --> XSS Schutz
 */
if ((strpos($_SERVER['HTTP_HOST'],'www.')===false)) {
    header("Location: https://www." . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
if ($_SERVER["HTTPS"] != "on") {
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
session_set_cookie_params(0, "/", "www.falconit.de", TRUE, TRUE);
session_start();

function resetPasswort($email, $g) {
	$id = fetchResults(getIdByEmail($g, $email))[0]['id'];
	$pw = substr(md5(rand()), 0, 10);
	fetchResults(editPasswort($id, $g, $pw));
	sendPasswortResetMail($email, $pw);
}

function activateUserAccount($u, $g, $ac) {
	$check = fetchResults(activateHelper($u, $g))[0]['ac'];
	if($check === $ac) {
		fetchResults(activateAccount($u, $g));
		return 1;
	} else {
	return 0;
	}
}

function login_main($email, $role, $password) {
    session_unset();
    if (empty($email) || empty($password) || empty($role)) {
        return -1;
    }
    switch ($role) {
        case 1: $table = "organisator";
            break;
        case 2: $table = "charity_partner";
            break;
        case 3: $table = "laeufer";
            break;
        default: return -1;
    }
    $check = fetchResults(getPass($email, $table));
    if(!empty($check[0]['activationcode'])) {
        return -4;
    }
    $verifypass = $check[0]['passwort'];
    $verifyid = $check[0]['id'];
    if (empty($verifyid) || empty($verifypass)) {
        return -2;
    } elseif (password_verify($password, $verifypass)) {
        $_SESSION['id'] = $verifyid;
        $_SESSION['role'] = $role;
        session_write_close();
        return 1;
    } else {
        return -3;
    }
}

function logout() {
    session_unset();
    session_destroy();
}

?>
