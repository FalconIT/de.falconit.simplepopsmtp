<?php if(DEBUGMODE == 1) echo '<p>'.__FILE__.'</p>'; ?>
<?php
	if($submit == 'register'){
		$partner = array();
		$partner['name'] = post_output('name');
		$partner['adresse2'] = post_output('address2');
		$partner['strasse'] = post_output('street');
		$partner['plz'] = post_output('plz');
		$partner['ort'] = post_output('city');
		$partner['land'] = post_output('country');
		$partner['telefon'] = post_output('phone');
		$partner['adresse1'] = post_output('address1');
		$partner['email'] = post_output('email');
		$partner['transparenzkennzeichen'] = post_output('transparency') == 'yes' ? 't' : 'f';
	}
?>
<form action="<?=$submit == 'register' ? site('partner_register') : site('organizer_partner', 'id='.$id);?>" method="post">
	<label for="name">Name oder Firmenname</label>
	<input data-clear-btn="false" name="name" id="name" value="<?=$partner['name']?>" type="text" required />
	<h3>Login-Daten</h3>
	<label for="email">E-Mail</label>
	<input data-clear-btn="false" name="email" id="email" value="<?=$partner['email']?>" type="email" required oninput="check_mail()" />
	<label for="email2">E-Mail wiederholen</label>
	<input data-clear-btn="false" name="email2" id="email2" value="<?=post_output('email2', $partner['email'])?>" type="email" required oninput="check_mail()" />
<?php
	if($submit == 'register'){
?>
	<label for="pass">Passwort</label>
	<input data-clear-btn="false" name="pass" id="pass" value="<?=post_output('pass')?>" type="password" required oninput="check_pass()" />
	<label for="pass2">Passwort wiederholen</label>
	<input data-clear-btn="false" name="pass2" id="pass2" value="<?=post_output('pass2')?>" type="password" required oninput="check_pass()" />
<?php
	}
?>
	<h3>Anschrift</h3>
	<label for="address1">Adresszeile 1</label>
	<input data-clear-btn="false" name="address1" id="address1" value="<?=$partner['adresse1']?>" type="text" required />
	<label for="address2">Adresszeile 2</label>
	<input data-clear-btn="false" name="address2" id="address1" value="<?=$partner['adresse2']?>" type="text" />
	<label for="street">Straße</label>
	<input data-clear-btn="false" name="street" id="street" value="<?=$partner['strasse']?>" type="text" required />
	<label for="plz">Postleitzahl</label>
	<input data-clear-btn="false" name="plz" id="plz" value="<?=$partner['plz']?>" type="text" pattern="[0-9]{5}" required />
	<label for="city">Ort</label>
	<input data-clear-btn="false" name="city" id="city" value="<?=$partner['ort']?>" type="text" required />
	<label for="country">Land</label>
	<input data-clear-btn="false" name="country" id="country" value="<?=$partner['land']?>" type="text" required />
	<h3>Kontakt</h3>
	<label for="phone">Telefonnummer</label>
	<input data-clear-btn="false" name="phone" id="phone" value="<?=$partner['telefon']?>" type="tel" />
	<h3>Einstellungen</h3>
	<label for="transparency">Öffentlich Auflisten</label>
	<select name="transparency" id="transparency" data-role="slider">
		<?php
			echo '<option value="no"'.($partner['transparenzkennzeichen'] == 't' ? '' : ' selected').'>Nein</option>';
			echo '<option value="yes"'.($partner['transparenzkennzeichen'] == 't' ? ' selected' : '').'>Ja</option>';
		?>
	</select>
	<p>Wir freuen uns Sie als Charity-Partner begrüßen zu dürfen!</p>
	<input id="<?=$submit;?>" name="<?=$submit;?>" value="<?=$submit == 'register' ? 'Registrieren' : 'Neue Werte eintragen';?>" type="submit" data-icon="user" data-iconpos="right" />
</form>
<?php require 'check_script.php'; ?>