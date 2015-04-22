<?php

$login = fetchResults(getSingleParticipantByEMail($_POST['email-runner']));

if(count($login) == 0){
	error_page("Login-Fehler", "Es ist kein Läufer mit der E-Mail-Adresse {$_POST['email-runner']} registriert.");
	exit();
}

$login = $login[0];

function main_page(){
	global $login;
	page("main", "Übersicht Läufer", "
		<p>Willkommen ".($login['geschlecht'] == "m" ? "Herr" : "Frau")." {$login['name']}!</p>
		<ul data-role='listview' data-inset='true'>
			<li data-role='list-divider'>Was möchten Sie tun?</li>
			<li><a href='#data' data-transition='slide'>Persönliche Daten</a></li>
			<li><a href='#contest' data-transition='slide'>Wettkampf Daten</a></li>
			<li><a href='#partner' data-transition='slide'>Charity-Partner</a></li>
		</ul>
	");
}



main_page();

?>
