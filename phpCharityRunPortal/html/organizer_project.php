<?php if(DEBUGMODE == 1) echo '<p>'.__FILE__.'</p>'; ?>
<?php
	if(!isset($_GET['id'])) $_GET['id'] = 0;
	$id = $_GET['id'];

	if(isset($_POST['update'])){
		$bezeichnung = post_output('bezeichnung');
		$beschreibung = post_output('beschreibung');
		$url = post_output('url');
		$spenden_start = post_output('spenden_start');
		$spenden_ende = post_output('spenden_ende');

		fetchResults(editCharityProjekt($id, $bezeichnung, $beschreibung, $url, $spenden_start, $spenden_ende, $_SESSION['id']));
	}

	$project = fetchResults(getSingleCharityProjekt($id))[0];

	call(function() use ($project){
		$headline = 'Projekt: '.$project['bezeichnung'];
		$type = 'organizer';
		require 'components/header.php';
	});
?>
<a href="<?=site('organizer_projects')?>" class="ui-btn ui-shadow ui-corner-all ui-icon-carat-u ui-btn-icon-right">Zur Projektliste</a>
<h2>Projektdaten</h2>
<p>Hier können Sie die Eintragungen zum aktuellen Projekt ändern.</p>
<?php
call(function() use ($id, $project){
	$submit = 'update';
	require 'components/organizer_project_form.php';
});
?>
<h2>Löschen</h2>
<a href="#deleteDialog" data-rel="popup" data-position-to="window" data-transition="pop" class="ui-btn ui-shadow ui-corner-all ui-icon-delete ui-btn-icon-right">Dieses Charity-Projekt löschen</a>
<div data-role="popup" id="deleteDialog" data-dismissible="false" style="max-width:400px;">
	<div data-role="header">
		<h1>Charity-Projekt löschen?</h1>
	</div>
	<div role="main" class="ui-content">
		<h3 class="ui-title">Sind Sie sicher dass Sie dieses Charity-Projekt löschen möchten?</h3>
		<p>Diese Aktion kann nicht rückgängig gemacht werden.</p>
		<form action="<?=site('organizer_projects')?>" method="post">
			<input type="hidden" name="delete_id" value="<?=$id?>" />
			<a href="#" class="ui-btn ui-corner-all ui-shadow ui-btn-inline" data-rel="back">Abbrechen</a>
			<input id="delete" name="delete" value="Löschen" type="submit" data-icon="delete" data-inline="true" data-iconpos="right" />
		</form>
	</div>
</div>
<?php require 'components/footer.php'; ?>
