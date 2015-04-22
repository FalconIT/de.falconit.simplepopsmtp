<?php if(DEBUGMODE == 1) echo '<p>'.__FILE__.'</p>'; ?>
<?php
	call(function(){
		$headline = 'Läufer';
		$type = 'main';
		require 'components/header.php';
	});
?>
<h2>Registrierung</h2>
<?php
if(isset($_POST['register'])){
	call(function(){
		$name = post_output('name');
		$vorname = post_output('forename');
		$verein = post_output('club');
		$geschlecht = post_output('gender') == 'male' ? 'M' : 'F';
		$geburtsdatum = post_output('birth');
		$email = post_output('email');
		$passwort = post_output('pass');

		$query = addLaeufer($name, $vorname, $verein, $geschlecht, $geburtsdatum, $email, $passwort);
		switch($query){
		case '-1':
			echo '<p class="error_msg">Ihre E-Mail-Adresse ist ungültig!</p>';
			call(function(){
				$submit = 'register';
				require 'components/runner_form.php';
			});
			break;
		case '-2':
			echo '<p class="error_msg">Sie haben nicht alle Pflichtfelder ausgefüllt!</p>';
			call(function(){
				$submit = 'register';
				require 'components/runner_form.php';
			});
			break;
		case '-3':
			echo '<p class="error_msg">Es existiert bereits ein Benutzer mit dieser E-Mail-Adresse!</p>';
			call(function(){
				$submit = 'register';
				require 'components/runner_form.php';
			});
			break;
		default:
			fetchResults($query);
			sendActivationMail($email, 3);
			require 'components/main_register.php';
		}
	});
}else{
	echo '<p>Hier können Sie sich als Läufer registrieren.</p>';
			call(function(){
				$submit = 'register';
				require 'components/runner_form.php';
			});
}
?>
<?php require 'components/footer.php'; ?>
