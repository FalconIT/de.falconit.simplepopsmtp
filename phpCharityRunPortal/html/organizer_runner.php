<?php if(DEBUGMODE == 1) echo '<p>'.__FILE__.'</p>'; ?>
<?php
	if(!isset($_GET['id'])) $_GET['id'] = 0;
	$id = $_GET['id'];

	if(isset($_POST['update'])){
		$email = post_output('email');
		$name = post_output('name');
		$vorname = post_output('forename');
		$verein = post_output('club');
		$geschlecht = post_output('gender') == 'male' ? 'M' : 'F';
		$geburtsdatum = post_output('birth');

		fetchResults(editLaeuferAdmin($id, $name, $vorname, $verein, $geschlecht, $geburtsdatum, $email));
	}

	$runner = fetchResults(getSingleParticipant($id))[0];

	call(function() use ($runner){
		$headline = 'Läufer: '.$runner['vorname'].' '.$runner['name'];
		$type = 'organizer';
		require 'components/header.php';
	});
?>
<a href="<?=site('organizer_runners')?>" class="ui-btn ui-shadow ui-corner-all ui-icon-carat-u ui-btn-icon-right">Zur Läuferliste</a>
<h2>Läuferdaten</h2>
<p>Hier können Sie die Eintragungen zum aktuellen Läufer ändern.</p>
<?php
call(function() use ($id, $runner){
	$submit = 'update';
	require 'components/runner_form.php';
});
?>
<h2>Löschen</h2>
<a href="#deleteDialog" data-rel="popup" data-position-to="window" data-transition="pop" class="ui-btn ui-shadow ui-corner-all ui-icon-delete ui-btn-icon-right">Diesen Läufer löschen</a>
<div data-role="popup" id="deleteDialog" data-dismissible="false" style="max-width:400px;">
	<div data-role="header">
		<h1>Läufer löschen?</h1>
	</div>
	<div role="main" class="ui-content">
		<h3 class="ui-title">Sind Sie sicher dass Sie diesen Läufer löschen möchten?</h3>
		<p>Diese Aktion kann nicht rückgängig gemacht werden.</p>
		<form action="<?=site('organizer_runners')?>" method="post">
			<input type="hidden" name="delete_id" value="<?=$id?>" />
			<a href="#" class="ui-btn ui-corner-all ui-shadow ui-btn-inline" data-rel="back">Abbrechen</a>
			<input id="delete" name="delete" value="Löschen" type="submit" data-icon="delete" data-inline="true" data-iconpos="right" />
		</form>
	</div>
</div>
<?php require 'components/footer.php'; ?>
