<?php if(DEBUGMODE == 1) echo '<p>'.__FILE__.'</p>'; ?>
<?php
	call(function(){
		$headline = 'Charity-Partner';
		$type = 'main';
		require 'components/header.php';
	});
?>
<h2>Registrierung</h2>
<?php
if(isset($_POST['register'])){
	call(function(){
		$name = post_output('name');
		$adresse2 = post_output('address2');
		$strasse = post_output('street');
		$plz = post_output('plz');
		$ort = post_output('city');
		$land = post_output('country');
		$telefon = post_output('phone');
		$adresse1 = post_output('address1');
		$email = post_output('email');
		$transparenz = post_output('transparency') == 'yes' ? 'true' : 'false';
		$passwort = post_output('pass');

		$query = addCharityPartner($name, $adresse2, $strasse, $plz, $ort, $land, $telefon, $adresse1, $email, $transparenz, $passwort);
		switch($query){
		case '-1':
			echo '<p class="error_msg">Ihre E-Mail-Adresse ist ungültig!</p>';
			call(function(){
				$submit = 'register';
				require 'components/partner_form.php';
			});
			break;
		case '-2':
			echo '<p class="error_msg">Sie haben nicht alle Pflichtfelder ausgefüllt!</p>';
			call(function(){
				$submit = 'register';
				require 'components/partner_form.php';
			});
			break;
		case '-3':
			echo '<p class="error_msg">Es existiert bereits ein Benutzer mit dieser E-Mail-Adresse!</p>';
			call(function(){
				$submit = 'register';
				require 'components/partner_form.php';
			});
			break;
		default:
			fetchResults($query);
			sendActivationMail($email, 2);
			require 'components/main_register.php';
		}
	});
}else{
	echo '<p>Hier können Sie sich als Charity-Partner registrieren.</p>';
	call(function(){
		$submit = 'register';
		require 'components/partner_form.php';
	});
}
?>
<?php require 'components/footer.php'; ?>
