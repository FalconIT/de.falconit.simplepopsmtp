<?php if(DEBUGMODE == 1) echo '<p>'.__FILE__.'</p>'; ?>
<?php

	if(!isset($_GET['event_id'])) $_GET['event_id'] = 0;
	if(!isset($_GET['track_id'])) $_GET['track_id'] = 0;
	$event_id = $_GET['event_id'];
	$track_id = $_GET['track_id'];

	if(isset($_POST['update'])){
		$laenge = post_output('laenge');
		$bezeichnung = post_output('bezeichnung');
		$beschreibung = post_output('beschreibung');
		$url = post_output('streckenverlauf_url');
		$termin = post_output('termin');
		fetchResults(editTrack($track_id, $event_id, $laenge, $bezeichnung, $beschreibung, $url, $termin));
	}

	$event = fetchResults(getSingleEvent($event_id))[0];
	$track = fetchResults(getTrackInformation($event_id, $track_id))[0];

	call(function() use ($track){
		$headline = 'Strecke: '.$track['bezeichnung'];
		$type = 'organizer';
		require 'components/header.php';
	});
?>
<a href="<?=site('organizer_event', 'id='.$event_id)?>" class="ui-btn ui-shadow ui-corner-all ui-icon-carat-u ui-btn-icon-right">Zum Wettkampf</a>
<h2>Streckendaten</h2>
<p>Hier können Sie die Eintragungen zur aktuellen Strecke ändern.</p>
<?php
call(function() use ($event_id, $track_id, $event, $track){
	$submit = 'update';
	require 'components/organizer_track_form.php';
});
?>
<?php
	$runners = fetchResults(getParticipantsByTrack($track_id));
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
				<!--<th data-priority="1">Vorname</th>-->
				<th>Name</th>
				<th data-priority="3">Altersklasse</th>
				<th data-priority="5">Verein</th>
                                <th>Gezahlt</th>
				<th data-priority="2">Geschlecht</th>
                                
				<th data-priority="4">Anmeldung</th>
                                
			</tr>
		</thead>
		<tbody>
			<?php /*<td>{$runner['vorname']}</td>*/ 
				foreach($runners as $runner){
					echo "<tr>
							
							<td>{$runner['name']}, {$runner['vorname']}</td>
							<td>{$runner['altersklasse']}</td>
							<td>{$runner['verein']}</td>
                                                        <td>".($runner['cnt'] != 0 ? 'ja' : 'nein')."</td>
							<td>".($runner['geschlecht'] == 'M' ? 'männlich' : 'weiblich')."</td>
							<td>{$runner['anmeldezeitpunkt']}</td>
                                   
						</tr>";
				}
			?>
		</tbody>
	</table>
<a href="#" class="ui-btn ui-shadow ui-corner-all ui-icon-action ui-btn-icon-right" onClick ="$('#runner').tableExport({type:'pdf',escape:'false',pdfFontSize:12,tableName:'Teilnehmer für den Lauf »<?=$track['bezeichnung']?>«'});">PDF-Export</a>
<?php
	}
?>
<h2>Löschen</h2>
<a href="#deleteDialog" data-rel="popup" data-position-to="window" data-transition="pop" class="ui-btn ui-shadow ui-corner-all ui-icon-delete ui-btn-icon-right">Diese Stecke löschen</a>
<div data-role="popup" id="deleteDialog" data-dismissible="false" style="max-width:400px;">
	<div data-role="header">
		<h1>Strecke löschen?</h1>
	</div>
	<div role="main" class="ui-content">
		<h3 class="ui-title">Sind Sie sicher dass Sie diese Strecke löschen möchten?</h3>
		<p>Diese Aktion kann nicht rückgängig gemacht werden.</p>
		<form action="<?=site('organizer_event', 'id='.$event_id)?>" method="post">
			<input type="hidden" name="delete_id" value="<?=$track_id?>" />
			<a href="#" class="ui-btn ui-corner-all ui-shadow ui-btn-inline" data-rel="back">Abbrechen</a>
			<input id="delete" name="delete" value="Löschen" type="submit" data-icon="delete" data-inline="true" data-iconpos="right" />
		</form>
	</div>
</div>
<?php require 'components/footer.php'; ?>
