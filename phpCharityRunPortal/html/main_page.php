<?php if(DEBUGMODE == 1) echo '<p>'.__FILE__.'</p>'; ?>
<?php
	call(function(){
		$headline = 'Charityrun Portal';
		$type = 'main';
		require 'components/header.php';
	});
?>
<h2>Wer sind Sie?</h2>
<p>
	Als erstes müssen Sie sich bei Broken Challenge Anmelden, bzw. neu Registieren,
	falls Sie noch keinen Login haben. Bitte wählen Sie aus, ob Sie ein
	Charity-Partner, ein Läufer oder der Organisator sind.
</p>
<?php
	call(function(){
		$inset = true;
		require 'components/main_menu.php';
	});
?>
<?php require 'components/footer.php'; ?>
