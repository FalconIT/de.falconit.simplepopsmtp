<?php

function main_page(){
	page("main", "Anmeldung", "
		<p>Willkommen bei der Anmeldung für die Brocken-Challenge!</p>
		<ul data-role='listview' data-inset='true'>
			<li data-role='list-divider'>Wer sind Sie?</li>
			<li><a href='#partner' data-transition='slide'>Charity-Partner</a></li>
			<li><a href='#runner' data-transition='slide'>Läufer</a></li>
			<li><a href='#organizer' data-transition='slide'>Organisator</a></li>
		</ul>
	");
}

function login($id, $headline, $addition_content = ''){
	page($id, $headline, "
		<form action='{$_SERVER['PHP_SELF']}?login=$id' method='post'>
			<p>Hier können Sie sich als $headline Anmelden oder Registrieren.</p>
			<label for='email-$id'>E-Mail</label>
			<input data-clear-btn='false' name='email-$id' id='email-$id' value='' type='email' />
			<label for='pass-$id'>Passwort</label>
			<input data-clear-btn='false' name='pass-$id' id='pass-$id' value='' type='password' />
			<input value='Login' type='submit' data-icon='user' data-iconpos='right' />
		</form>$addition_content
	", 'main');
}

function login_and_register($id, $headline){
	login($id, $headline, "
		<p>Sie sind noch nicht eingetragen?</p>
		<a href='#$id-register' class='ui-btn ui-shadow ui-icon-carat-r ui-btn-icon-right' data-transition='slide'>Registrieren</a>
	", 'main');
}

function register($id, $headline, $addition_content){
	login_and_register($id, $headline);
	page($id.'-register', $headline, "
		<form action='{$_SERVER['PHP_SELF']}?register=$id' method='post'>
			<p>Hier können Sie sich als $headline Registrieren.</p>
			<h2>Login-Daten</h2>
			<label for='email-$id'>E-Mail</label>
			<input data-clear-btn='false' name='email-$id' id='email-$id' value='' type='email' />
			<label for='pass-$id'>Passwort</label>
			<input data-clear-btn='false' name='pass-$id' id='pass-$id' value='' type='password' />
			<label for='pass2-$id'>Passwort wiederholen</label>
			<input data-clear-btn='false' name='pass2-$id' id='pass2-$id' value='' type='password' />$addition_content
			<input value='Registrieren' type='submit' data-icon='user' data-iconpos='right' />
		</form>
	", $id);
}

function register_runner(){
	$id = "runner";
	register($id, "Läufer", "
			<h2>Persönliches</h2>
			<label for='forename-$id'>Vorname</label>
			<input data-clear-btn='false' name='forename-$id' id='forename-$id' value='' type='text' />
			<label for='name-$id'>Nachname</label>
			<input data-clear-btn='false' name='name-$id' id='name-$id' value='' type='text' />
			<label for='club-$id'>Verein</label>
			<input data-clear-btn='false' name='club-$id' id='club-$id' value='' type='text' />
			<fieldset data-role='controlgroup'>
				<legend>Geschlecht</legend>
				<input name='gender-$id' id='m-gender-$id' value='male' type='radio'>
				<label for='m-gender-$id'>Männlich</label>
				<input name='gender-$id' id='f-gender-$id' value='female' type='radio'>
				<label for='f-gender-$id'>Weiblich</label>
			</fieldset>
			<h3>Geburtsdatum</h3>
			<label for='birth-day-$id'>Tag</label>
			<input data-clear-btn='false' name='birth-day-$id' id='birth-day-$id' value='1' min='1' max='31' type='Number' />
			<label for='birth-month-$id'>Monat</label>
			<input data-clear-btn='false' name='birth-month-$id' id='birth-month-$id' value='1' min='1' max='12' type='Number' />
			<label for='birth-year-$id'>Jahr</label>
			<input data-clear-btn='false' name='birth-year-$id' id='birth-year-$id' value='1980' min='1900' max='".date("Y")."' type='Number' />
			<p>Wir freuen uns Sie als Läufer begrüßen zu dürfen!</p>
	");
}

function register_partner(){
	$id = "partner";
	register($id, "Charity-Partner", "
			<h2>Persönliches</h2>
			<label for='name-$id'>Name</label>
			<input data-clear-btn='false' name='name-$id' id='name-$id' value='' type='text' />
			<label for='transparency-$id'>Öffentlich Auflisten</label>
			<select name='transparency-$id' id='transparency-$id' data-role='slider'>
				<option value='yes'>Ja</option>
				<option value='no' selected=''>Nein</option>
			</select>
			<h2>Anschrift</h2>
			<label for='company-$id'>Name für die Anschrift</label>
			<input data-clear-btn='false' name='company-$id' id='company-$id' value='' type='text' />
			<label for='street-$id'>Straße</label>
			<input data-clear-btn='false' name='street-$id' id='street-$id' value='' type='text' />
			<label for='plz-$id'>Postleitzahl</label>
			<input data-clear-btn='false' name='plz-$id' id='plz-$id' value='' type='text' />
			<label for='city-$id'>Ort</label>
			<input data-clear-btn='false' name='city-$id' id='city-$id' value='' type='text' />
			<label for='country-$id'>Land</label>
			<input data-clear-btn='false' name='country-$id' id='country-$id' value='' type='text' />
			<h2>Kontakt</h2>
			<label for='phone-$id'>Telefonnummer</label>
			<input data-clear-btn='false' name='phone-$id' id='phone-$id' value='' type='tel' />
			<label for='contact-$id'>Ansprechpartner</label>
			<input data-clear-btn='false' name='contact-$id' id='contact-$id' value='' type='text' />
			<p>Wir freuen uns Sie als Charity-Partner begrüßen zu dürfen!</p>
	");
}

main_page();
login("organizer", "Organisator");
register_runner();
register_partner();

?>