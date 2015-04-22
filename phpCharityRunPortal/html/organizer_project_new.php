<?php if(DEBUGMODE == 1) echo '<p>'.__FILE__.'</p>'; ?>
<?php
	call(function(){
		$headline = 'Neues Projekt';
		$type = 'organizer';
		require 'components/header.php';
	});
?>
<a href="<?=site('organizer_projects')?>" class="ui-btn ui-shadow ui-corner-all ui-icon-carat-u ui-btn-icon-right">Zur Projektliste</a>
<h2>Projektdaten</h2>
<p>Hier können Sie die Eintragungen zum aktuellen Projekt ändern.</p>
<?php
call(function(){
	$submit = 'new';
	require 'components/organizer_project_form.php';
});
?>
<?php require 'components/footer.php'; ?>
