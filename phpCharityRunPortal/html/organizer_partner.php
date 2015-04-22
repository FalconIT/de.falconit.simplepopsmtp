<?php if(DEBUGMODE == 1) echo '<p>'.__FILE__.'</p>'; ?>
<?php
	if(!isset($_GET['id'])) $_GET['id'] = 0;
	$id = $_GET['id'];

	if(isset($_POST['update'])){
// 		$name = post_output('name');
		$adresse2 = post_output('address2');
		$strasse = post_output('street');
		$plz = post_output('plz');
		$ort = post_output('city');
		$land = post_output('country');
		$telefon = post_output('phone');
		$adresse1 = post_output('address1');
		$email = post_output('email');
		$transparenz = post_output('transparency') == 'yes' ? 'true' : 'false';
// 		$passwort = post_output('pass');

		fetchResults(editCharityPartnerAdmin($id, $adresse2, $strasse, $plz, $ort, $land, $telefon, $adresse1, $email, $transparenz));
	}

	$partner = fetchResults(getSingleCharityPartner($id))[0];

	call(function() use ($partner){
		$headline = 'Partner: '.$partner['name'];
		$type = 'organizer';
		require 'components/header.php';
	});
?>
<a href="<?=site('organizer_partners')?>" class="ui-btn ui-shadow ui-corner-all ui-icon-carat-u ui-btn-icon-right">Zur Partnerliste</a>
<h2>Partnerdaten</h2>
<p>Hier können Sie die Eintragungen zum aktuellen Charity-Partner ändern.</p>
<?php
call(function() use ($id, $partner){
	$submit = 'update';
	require 'components/partner_form.php';
});
?>
<h2>Löschen</h2>
<a href="#deleteDialog" data-rel="popup" data-position-to="window" data-transition="pop" class="ui-btn ui-shadow ui-corner-all ui-icon-delete ui-btn-icon-right">Diesen Partner löschen</a>
<div data-role="popup" id="deleteDialog" data-dismissible="false" style="max-width:400px;">
	<div data-role="header">
		<h1>Charity-Partner löschen?</h1>
	</div>
	<div role="main" class="ui-content">
		<h3 class="ui-title">Sind Sie sicher dass Sie diesen Charity-Partner löschen möchten?</h3>
		<p>Diese Aktion kann nicht rückgängig gemacht werden.</p>
		<form action="<?=site('organizer_partners')?>" method="post">
			<input type="hidden" name="delete_id" value="<?=$id?>" />
			<a href="#" class="ui-btn ui-corner-all ui-shadow ui-btn-inline" data-rel="back">Abbrechen</a>
			<input id="delete" name="delete" value="Löschen" type="submit" data-icon="delete" data-inline="true" data-iconpos="right" />
		</form>
	</div>
</div>
<?php require 'components/footer.php'; ?>
