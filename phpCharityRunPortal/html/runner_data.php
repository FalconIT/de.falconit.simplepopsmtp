<?php if(DEBUGMODE == 1) echo '<p>'.__FILE__.'</p>'; ?>
<?php
	call(function(){
		$headline = 'Persönliche Daten';
		$type = 'runner';
		require 'components/header.php';
	});

	if(isset($_POST['change'])){
		fetchResults(editLaeufer($_SESSION['id'], post_output('club')));
	}elseif(isset($_POST['change_pass'])){
		fetchResults(editPasswort($_SESSION['id'], 3, post_output('pass')));
	}elseif(isset($_POST['change_email'])){
		fetchResults(editEmail($_SESSION['id'], 3, post_output('email')));
	}

	$user = fetchResults(getSingleParticipant($_SESSION['id']))[0];
?>
<h2>Persönliche Daten</h2>
<p>Hier finden Sie die Daten, die Sie bei der Registrierung angegeben haben. Passwort, E-Mail-Adresse und ihren ggf. Ihren Verein können Sie ändern.</p>
<form action="<?=site('runner_data')?>" method="post">
	<dl>
		<dt>Vorname</dt>
		<dd><?=$user['vorname']?></dd>
		<dt>Name</dt>
		<dd><?=$user['name']?></dd>
		<dt>Verein</dt>
		<dd><input data-clear-btn="false" name="club" id="club" value="<?=$user['verein']?>" type="text" /></dd>
		<dt>Geschlecht</dt>
		<dd><?=$user['geschlecht'] == 'M' ? 'Männlich' : 'Weiblich'?></dd>
		<dt>Geburtsdatum</dt>
		<dd><?=date("d.m.Y", strtotime($user['geburtsdatum']))?></dd>
		<dt>Registriert seit</dt>
		<dd><?=date("d.m.Y", strtotime($user['registrierdatum']))?></dd>
	</dl>
	<input id="change" name="change" value="Daten ändern" type="submit" data-icon="action" data-iconpos="right" />
</form>
<h2>E-Mail-Adresse</h2>
<form action="<?=site('runner_data')?>" method="post">
	<label for="email">E-Mail</label>
	<input data-clear-btn="false" name="email" id="email" value="<?=$user['email']?>" type="email" required oninput="check_mail()" />
	<label for="email2">E-Mail wiederholen</label>
	<input data-clear-btn="false" name="email2" id="email2" value="<?=$user['email']?>" type="email" required oninput="check_mail()" />
	<input id="change_email" name="change_email" value="E-Mail-Adresse ändern" type="submit" data-icon="action" data-iconpos="right" />
</form>
<h2>Passwort</h2>
<form action="<?=site('runner_data')?>" method="post">
	<label for="pass">Passwort</label>
	<input data-clear-btn="false" name="pass" id="pass" value="" type="password" required oninput="check_pass()" />
	<label for="pass2">Passwort wiederholen</label>
	<input data-clear-btn="false" name="pass2" id="pass2" value="" type="password" required oninput="check_pass()" />
	<input id="change_pass" name="change_pass" value="Passwort ändern" type="submit" data-icon="action" data-iconpos="right" />
</form>
<?php require 'components/check_script.php'; ?>
<?php require 'components/footer.php'; ?>
