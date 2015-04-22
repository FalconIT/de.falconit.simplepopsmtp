<?php if(DEBUGMODE == 1) echo '<p>'.__FILE__.'</p>'; ?>
<?php
	if($submit == 'register'){
		$runner = array();
		$runner['email'] = post_output('email');
		$runner['name'] = post_output('name');
		$runner['vorname'] = post_output('forename');
		$runner['verein'] = post_output('club');
		$runner['geschlecht'] = post_output('gender') == 'male' ? 'M' : 'F';
		$runner['geburtsdatum'] = post_output('birth');
	}
?>
<form action="<?=$submit == 'register' ? site('runner_register') : site('organizer_runner', 'id='.$id);?>" method="post">
	<label for="email">E-Mail</label>
	<input data-clear-btn="false" name="email" id="email" value="<?=$runner['email']?>" type="email" required oninput="check_mail()" />
	<label for="email2">E-Mail wiederholen</label>
	<input data-clear-btn="false" name="email2" id="email2" value="<?=post_output('email2', $runner['email'])?>" type="email" required oninput="check_mail()" />
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
	<h3>Persönliches</h3>
	<label for="forename">Vorname</label>
	<input data-clear-btn="false" name="forename" id="forename" value="<?=$runner['vorname']?>" type="text" required />
	<label for="name">Nachname</label>
	<input data-clear-btn="false" name="name" id="name" value="<?=$runner['name']?>" type="text" required />
	<label for="club">Verein (optional)</label>
	<input data-clear-btn="false" name="club" id="club" value="<?=$runner['verein']?>" type="text" />
	<label>Geschlecht</label>
	<fieldset data-role="controlgroup">
		<?php
			call(function() use ($submit, $runner){
				$isset = isset($_POST['gender']) || $submit != 'register';
				$male = $isset && $runner['geschlecht'] == 'M';
				echo '<input required name="gender" id="m-gender" value="male" type="radio" '.($isset && $male ? ' checked' : '').'><label for="m-gender">Männlich</label>';
				echo '<input required name="gender" id="f-gender" value="female" type="radio" '.($isset && !$male ? ' checked' : '').'><label for="f-gender">Weiblich</label>';
			});
		?>
	</fieldset>
	<h3>Geburtsdatum</h3>
	<input type="date" id="birth" name="birth" value="<?=$runner['geburtsdatum']?>" data-role="datebox" data-options="{'minYear': 1900, 'maxYear': <?=date('Y')?>}" required />
	<p>Wir freuen uns Sie als Läufer begrüßen zu dürfen!</p>
	<input id="<?=$submit;?>" name="<?=$submit;?>" value="<?=$submit == 'register' ? 'Registrieren' : 'Neue Werte eintragen';?>" type="submit" data-icon="user" data-iconpos="right" />
</form>
<?php require 'check_script.php'; ?>