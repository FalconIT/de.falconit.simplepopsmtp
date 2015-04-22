<?php if(DEBUGMODE == 1) echo '<p>'.__FILE__.'</p>'; ?>
<?php
	if(!isset($_GET['id'])) $_GET['id'] = 0;
	$id = $_GET['id'];
        if(isset($_POST['ent'])) {
            $idarray = explode(",", $_POST['ids']);
            if(!empty($idarray)) {
                foreach ($idarray as $value) {
                    fetchResults(addPayment($id, $value));
                }
            } else {}
        } else{}
	if(isset($_POST['update'])){
		$bezeichnung = post_output('bezeichnung');
		$termin = '['.post_output('von').', '.post_output('bis').')';
		$ort = post_output('ort');
		$website = post_output('website');
		$max_tn = post_output('max_tn');
		$beschreibung = post_output('beschreibung');
		$gebuehrenpflichtig = post_output('gebuehrenpflichtig') == 'yes' ? 'true' : 'false';
		$iban = post_output('iban');
		$bic = post_output('bic');
		$betrag = (float)post_output('betrag');
		$zahlungsempfaenger = post_output('zahlungsempfaenger');
		fetchResults(editEvent($id, $bezeichnung, $termin, $ort, $website, $max_tn, $beschreibung, $gebuehrenpflichtig, $iban, $bic, $betrag, $zahlungsempfaenger));
	}elseif(isset($_POST['new'])){
		$bezeichnung = post_output('bezeichnung');
		$termin = post_output('termin');
		$laenge = post_output('laenge');
		$url = post_output('streckenverlauf_url');
		$beschreibung = post_output('beschreibung');
		fetchResults(addTrack($id, $laenge, $bezeichnung, $beschreibung, $url, $termin));
	}elseif(isset($_POST['delete'])){
		fetchResults(removeTrack($_POST['delete_id']));
	}

	$event = fetchResults(getSingleEvent($id))[0];
	$tracks = fetchResults(getTracksByEvent($event['id']));

	call(function() use ($event){
		$headline = 'Wettkampf: '.$event['bezeichnung'];
		$type = 'organizer';
		require 'components/header.php';
	});
?>
<a href="<?=site('organizer_events')?>" class="ui-btn ui-shadow ui-corner-all ui-icon-carat-u ui-btn-icon-right">Zur Wettkampfliste</a>
<h2>Wettkampfdaten</h2>
<p>Hier können Sie die Eintragungen zum aktuellen Wettkampf ändern oder den gesamten Wettkampf löschen.</p>
<?php
call(function() use ($id, $event){
	$submit = 'update';
	require 'components/organizer_event_form.php';
});
?>
<h2>Strecken</h2>
<?php
	if($tracks == null){
		echo '<p class="error_msg">Es wurden noch keine Stecken eingetragen.</p>';
	}else{
?>
<p>Klicken Sie auf die ID um eine Strecke zu bearbeiten.</p>
<table data-role="table" id="tracks" data-mode="columntoggle" class="ui-responsive table-stroke">
	<thead>
		<tr>
			<th>ID</th>
			<th>Bezeichnung</th>
			<th data-priority="1">Termin</th>
			<th data-priority="2">Länge</th>
			<th data-priority="3">Beschreibung</th>
			<th data-priority="4">Verlauf</th>
		</tr>
	</thead>
	<tbody>
	<?php
		foreach($tracks as $track){
			echo "<tr>
					<td><a class='ui-btn ui-shadow ui-corner-all ui-icon-edit ui-btn-icon-right' href='".site('organizer_track', 'event_id='.$event['id'].'&track_id='.$track['id'])."'>{$track['id']}</a></td>
					<td>{$track['bezeichnung']}</td>
					<td>{$track['termin']}</td>
					<td>{$track['laenge']}</td>
					<td>{$track['beschreibung']}</td>
					<td><a href='{$track['streckenverlauf_url']}'>Verlauf</a></td>
				</tr>";
		}
	?>
	</tbody>
</table>
<?php
	}
?>
<a href="<?=site('organizer_track_new', 'event_id='.$id)?>" class="ui-btn ui-shadow ui-corner-all ui-icon-plus ui-btn-icon-right">Neue Strecke</a>
<?php
	$runners = fetchResults(getParticipantsByEvent($event['id']));
?>
<h2>Läufer</h2>
<p>Dies ist die Liste aller teilnehmenden Läufer.</p>
<?php
	if($runners == null){
		echo '<p class="error_msg">Es hat sich noch kein Läufer eingetragen.</p>';
	}else{
?>
	<table data-role="table" id="runner" data-mode="columntoggle" class="ui-responsive table-stroke">
		<thead>
			<tr>
				<th data-priority="1">Vorname</th>
				<th>Name</th>
				<th data-priority="3">Altersklasse</th>
				<th data-priority="4">Verein</th>
				<th data-priority="2">Geschlecht</th>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach($runners as $runner){
					echo "<tr>
							<td>{$runner['vorname']}</td>
							<td>{$runner['name']}</td>
							<td>{$runner['altersklasse']}</td>
							<td>{$runner['verein']}</td>
							<td>".($runner['geschlecht'] == 'M' ? 'männlich' : 'weiblich')."</td>
						</tr>";
				}
			?>
		</tbody>
	</table>
<?php
	}
?>
<a href="#" class="ui-btn ui-shadow ui-corner-all ui-icon-action ui-btn-icon-right" onClick ="$('#runner').tableExport({type:'pdf',escape:'false',pdfFontSize:12,tableName:'Teilnehmer für den Wettkampf »<?=$event['bezeichnung']?>«'});">PDF-Export</a>
<h3>Zahlung eintragen (Kommagetrennt)</h3>
<form action="" method="post">
	<input type="hidden" name="ent" value="y" />
        <dd>
	<input data-clear-btn="false" name="ids" id="ids" value="" type="text" autocomplete="off" />
	</dd><input id="addz" name="addz" value="Eintragen" type="submit" data-icon="check" data-inline="true" data-iconpos="right" />
</form>
<h3>Offene Zahlungen</h3>
<p>Diese Liste zeigt die offenen Forderungen der Läufer.</p>
<?php
	$runners = fetchResults(getNotPaid($id));

	if($runners == null){
		echo '<p class="error_msg">Es sind keine Forderungen offen.</p>';
	}else{
?>
	<table data-role="table" id="paied" data-mode="columntoggle" class="ui-responsive table-stroke">
		<thead>
			<tr>
				<th>Vorname</th>
				<th>Name</th>
				<th data-priority="1">Verein</th>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach($runners as $runner){
					echo "<tr>
							<td>{$runner['vorname']}</td>
							<td>{$runner['name']}</td>
							<td>{$runner['verein']}</td>
						</tr>";
				}
			?>
		</tbody>
	</table>
<?php
	}
?>
<h3>Erfolgte Zahlungen</h3>
<p>Diese Liste zeigt die erfolgten Zahlungen der Läufer.</p>
<?php
	$runners = fetchResults(getPaid($id));

	if($runners == null){
		echo '<p class="error_msg">Es hat noch kein Läufer bezahlt.</p>';
	}else{
?>
	<table data-role="table" id="unpaied" data-mode="columntoggle" class="ui-responsive table-stroke">
		<thead>
			<tr>
				<th>Vorname</th>
				<th>Name</th>
				<th data-priority="1">Verein</th>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach($runners as $runner){
					echo "<tr>
							<td>{$runner['vorname']}</td>
							<td>{$runner['name']}</td>
							<td>{$runner['verein']}</td>
						</tr>";
				}
			?>
		</tbody>
	</table>
<?php
	}
?>
<h2>Löschen</h2>
<a href="#deleteDialog" data-rel="popup" data-position-to="window" data-transition="pop" class="ui-btn ui-shadow ui-corner-all ui-icon-delete ui-btn-icon-right">Diesen Wettkampf löschen</a>
<div data-role="popup" id="deleteDialog" data-dismissible="false" style="max-width:400px;">
	<div data-role="header">
		<h1>Wettkampf löschen?</h1>
	</div>
	<div role="main" class="ui-content">
		<h3 class="ui-title">Sind Sie sicher dass Sie diesen Wettkampf löschen möchten?</h3>
		<p>Diese Aktion kann nicht rückgängig gemacht werden.</p>
		<form action="<?=site('organizer_events')?>" method="post">
			<input type="hidden" name="delete_id" value="<?=$id?>" />
			<a href="#" class="ui-btn ui-corner-all ui-shadow ui-btn-inline" data-rel="back">Abbrechen</a>
			<input id="delete" name="delete" value="Löschen" type="submit" data-icon="delete" data-inline="true" data-iconpos="right" />
		</form>
	</div>
</div>
<?php require 'components/footer.php'; ?>
