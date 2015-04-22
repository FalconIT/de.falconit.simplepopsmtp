<?php if(DEBUGMODE == 1) echo '<p>'.__FILE__.'</p>'; ?>
<?php
	call(function(){
		$headline = 'Charity-Partner';
		$type = 'partner';
		require 'components/header.php';
	});
?>
<h2>Hauptseite</h2>
<p>Sie sind als Charity-Partner eingeloggt.</p>
<?php
	call(function(){
		$inset = true;
		require 'components/partner_menu.php';
	});
?>
<?php require 'components/footer.php'; ?>
