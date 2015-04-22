<?php if(DEBUGMODE == 1) echo '<p>'.__FILE__.'</p>'; ?>
<?php
	call(function(){
		$headline = 'Neuer Wettkampf';
		$type = 'organizer';
		require 'components/header.php';
	});
?>
<a href="<?=site('organizer_events')?>" class="ui-btn ui-shadow ui-corner-all ui-icon-carat-u ui-btn-icon-right">Zur Wettkampfliste</a>
<h2>Neuer Wettkampf</h2>
<p>Organisieren Sie einen neuen Wettkampf.</p>
<?php
call(function(){
	$submit = 'new';
	require 'components/organizer_event_form.php';
});
?><?php require 'components/footer.php'; ?>
