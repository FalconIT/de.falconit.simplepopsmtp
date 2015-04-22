<?php if(DEBUGMODE == 1) echo '<p>'.__FILE__.'</p>'; ?>
<?php
	call(function(){
		$headline = 'Aktivierung abgeschlossen';
		$type = 'main';
		require 'components/header.php';
	});

	if(activateUserAccount($_GET['u'], $_GET['g'], $_GET['c']) == 1){
?>
		<h2>Aktivierung abgeschlossen</h2>
		<p>
			Ihr Account wurde aktiviert. Sie können sich nun einloggen.
		</p>
<?php
	}else{
?>
		<h2>Fehler bei der Aktivierung</h2>
		<p>
			Leider war ihr Aktivierungscode ungültig. Möglicherweise haben Sie die Aktivierung schon zu einem frühren
			Zeitpunkt abgeschlossen oder Sie haben den Aktivierungs-Link nicht vollständig aus der E-Mail kopiert.
		</p>
<?php
	}
	require 'components/footer.php';
?>
