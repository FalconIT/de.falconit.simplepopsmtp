<?php if(DEBUGMODE == 1) echo '<p>'.__FILE__.'</p>'; ?>
<?php
	call(function(){
		$headline = 'Persönliche Daten';
		$type = 'partner';
		require 'components/header.php';
	});

	if(isset($_POST['change'])){
		$adresse2 = post_output('address2');
		$strasse = post_output('street');
		$plz = post_output('plz');
		$ort = post_output('city');
		$land = post_output('country');
		$telefon = post_output('phone');
		$adresse1 = post_output('address1');
		$transparenz = post_output('transparency') == 'yes' ? 'true' : 'false';

		fetchResults(editCharityPartner($_SESSION['id'], $adresse2, $strasse, $plz, $ort, $land, $telefon, $adresse1, $transparenz));
	}elseif(isset($_POST['change_pass'])){
		fetchResults(editPasswort($_SESSION['id'], 2, post_output('pass')));
	}elseif(isset($_POST['change_email'])){
		fetchResults(editEmail($_SESSION['id'], 2, post_output('email')));
	}

	$user = fetchResults(getSingleCharityPartner($_SESSION['id']))[0];
?>
<h2>Persönliche Daten</h2>
<p>Hier finden Sie die Daten, die Sie bei der Registrierung angegeben haben. Passwort, E-Mail-Adresse und ihren ggf. Ihren Verein können Sie ändern.</p>
<form action="<?=site('partner_data')?>" method="post">
	<dl>
		<dt>Name</dt>
		<dd><input data-clear-btn="false" name="name" id="name" value="<?=$user['name']?>" type="text" disabled /></dd>
		<dt>Adressezeile 1</dt>
		<dd><input data-clear-btn="false" name="address1" id="address1" value="<?=$user['adresse1']?>" type="text" required /></dd>
		<dt>Adressezeile 2</dt>
		<dd><input data-clear-btn="false" name="address2" id="address1" value="<?=$user['adresse2']?>" type="text" /></dd>
		<dt>Straße</dt>
		<dd><input data-clear-btn="false" name="street" id="street" value="<?=$user['strasse']?>" type="text" required /></dd>
		<dt>Postleitzahl</dt>
		<dd><input data-clear-btn="false" name="plz" id="plz" value="<?=$user['plz']?>" type="text" pattern="[0-9]{5}" required /></dd>
		<dt>Ort</dt>
		<dd><input data-clear-btn="false" name="city" id="city" value="<?=$user['ort']?>" type="text" required /></dd>
		<dt>Land</dt>
		<dd><input data-clear-btn="false" name="country" id="country" value="<?=$user['land']?>" type="text" required /></dd>
		<dt>Telefon</dt>
		<dd><input data-clear-btn="false" name="phone" id="phone" value="<?=$user['telefon']?>" type="tel" /></dd>
		<dt>Spenden Öffentlich auflisten</dt>
		<dd>
			<select name="transparency" id="transparency" data-role="slider">
				<?php
					echo '<option value="no"'.($user['transparenzkennzeichen'] == 't' ? '' : ' selected').'>Nein</option>';
					echo '<option value="yes"'.($user['transparenzkennzeichen'] == 't' ? ' selected' : '').'>Ja</option>';
				?>
			</select>
		</dd>
	</dl>
	<input id="change" name="change" value="Daten ändern" type="submit" data-icon="action" data-iconpos="right" />
</form>
<h2>E-Mail-Adresse</h2>
<form action="<?=site('partner_data')?>" method="post">
	<label for="email">E-Mail</label>
	<input data-clear-btn="false" name="email" id="email" value="<?=$user['email']?>" type="email" required oninput="check_mail()" />
	<label for="email2">E-Mail wiederholen</label>
	<input data-clear-btn="false" name="email2" id="email2" value="<?=$user['email']?>" type="email" required oninput="check_mail()" />
	<input id="change_email" name="change_email" value="E-Mail-Adresse ändern" type="submit" data-icon="action" data-iconpos="right" />
</form>
<h2>Passwort</h2>
<form action="<?=site('partner_data')?>" method="post">
	<label for="pass">Passwort</label>
	<input data-clear-btn="false" name="pass" id="pass" value="" type="password" required oninput="check_pass()" />
	<label for="pass2">Passwort wiederholen</label>
	<input data-clear-btn="false" name="pass2" id="pass2" value="" type="password" required oninput="check_pass()" />
	<input id="change_pass" name="change_pass" value="Passwort ändern" type="submit" data-icon="action" data-iconpos="right" />
</form>
<?php require 'components/check_script.php'; ?>
<?php require 'components/footer.php'; ?>
