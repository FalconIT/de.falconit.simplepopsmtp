<?php if(DEBUGMODE == 1) echo '<p>'.__FILE__.'</p>'; ?>
<?php
	call(function(){
		$headline = 'Charity-Partner';
		$type = 'main';
		require 'components/header.php';
	});
?>
	<h2>Login</h2>
	<?php
		if(isset($login_error)){
			switch($login_error){
				case -1:
					echo '<p class="error_msg">Bitte tragen Sie Ihre E-Mail-Adresse und Ihr Passwort ein.</p>';
					break;
				case -2:
					echo '<p class="error_msg">Es ist kein Benutzer mit dieser E-Mail-Adresse als Charity-Partner registriert.</p>';
					break;
				case -3:
					echo '<p class="error_msg">Ihr Passwort war ungültig.</p>';
					break;
				case -4:
					echo '<p class="error_msg">Ihr Account wurde noch nicht aktiviert.</p>';
					break;
				default:
					echo '<p class="error_msg">Programmierfehler.</p>';
			}
		}else{
			echo '<p>Hier können Sie sich als Charity-Partner anmelden.</p>';
		}
	?>
	<form action="<?=site('partner_login')?>" method="post">
		<label for="email">E-Mail</label>
		<input data-clear-btn="false" name="email" id="email" value="<?=post_output('email')?>" type="email" required />
		<label for="pass">Passwort</label>
		<input data-clear-btn="false" name="pass" id="pass" value="" type="password" required />
		<input id="login" name="login" value="Login" type="submit" data-icon="user" data-iconpos="right" />
	</form>
<?php require 'components/footer.php'; ?>
