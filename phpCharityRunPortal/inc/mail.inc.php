<?php
require_once 'functions.inc.php';
$header =   'From: webmaster@falconit.de' . "\r\n" .
            "MIME-Version: 1.0\r\n" .
            "Content-type: text/html; charset=UTF-8\r\n" .
            'Return-Path: webmaster@falconit.de' . "\r\n" .
            'Reply-To: webmaster@falconit.de' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
// function sendmail
function getActivationText($name, $group, $id, $activationcode) {
    $mailtext_one = "Wilkommen im Charityrun Portal, ".$name."!<br /><br />"
            . "<a href=\"https://www.falconit.de/dbprojekt/index.php?site=activate&g=".$group."&u=".$id."&c=".$activationcode."\">"
            . "Bitte klicken Sie hier, um ihren Account zu aktivieren.</a><br />Sie können sich danach auf der Seite einloggen."
            . "<br /><br />"
            . "(Sollten Sie den Link nicht anklicken können, kopieren sie bitte den untenstehenden Link und öffnen Sie ihn in Ihrem Browser.)<br />"
            . "https://www.falconit.de/dbprojekt/index.php?site=activate&g=".$group."&u=".$id."&c=".$activationcode;
    return $mailtext_one;
}
function sendActivationMail($mail, $group) {
    global $header;
    $dataArray = fetchResults(getActivationData($mail, $group));
    $text = getActivationText($dataArray[0]['fullname'], $group, $dataArray[0]['id'], $dataArray[0]['activationcode']);
    mail($mail, "Charityrun Portal - Aktivierung", $text, $header, "-fwebmaster@falconit.de");
}

function sendPasswortResetMail($mail, $pass) {
	global $header;
	$text = "Ihr neues Passwort für das Charityrun Portal lautet: $pass - bitte ändern Sie dieses direkt nach dem Login.";
	mail($mail, "Charityrun Portal - Neues Passwort", $text, $header, "-fwebmaster@falconit.de");

}
