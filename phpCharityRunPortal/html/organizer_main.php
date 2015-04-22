<?php if(DEBUGMODE == 1) echo '<p>'.__FILE__.'</p>'; ?>
<?php
	call(function(){
		$headline = 'Organisator';
		$type = 'organizer';
		require 'components/header.php';
	});
?>
<h2>Hauptseite</h2>
<p>Sie befinden sich im Organisationsbereich!</p>
<?php
	call(function(){
		$inset = true;
		require 'components/organizer_menu.php';
	});
?>
<?php require 'components/footer.php'; ?>
