<?php if(DEBUGMODE == 1) echo '<p>'.__FILE__.'</p>'; ?>
<?php
	call(function(){
		$headline = 'Wettkämpfe';
		$type = 'organizer';
		require 'components/header.php';
	});

	if(isset($_POST['new'])){
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
		fetchResults(addEvent($bezeichnung, $termin, $ort, $website, $max_tn, $beschreibung, $gebuehrenpflichtig, $iban, $bic, $betrag, $zahlungsempfaenger));
	}elseif(isset($_POST['delete'])){
		fetchResults(removeEvent($_POST['delete_id']));
	}

	if(!isset($_GET['count'])) $_GET['count'] = 20;
	if(!isset($_GET['offset'])) $_GET['offset'] = 0;
	$count  = $_GET['count'];
	$offset = $_GET['offset'];

	$events = fetchResults(getEvents($count, $offset));
?>
<h2>Wettkampfliste</h2>
<p>Dies ist die Liste mit allen Wettkämpfen. Klicken Sie auf die ID um einen Wettkampf zu bearbeiten.</p>
<?php
	if($events == null){
		echo '<p class="error_msg">Es wurden noch keine Wettkämpfe eingetragen.</p>';
	}else{
?>
	<table data-role="table" id="events" data-mode="columntoggle" class="ui-responsive table-stroke">
		<thead>
			<tr>
				<th>ID</th>
				<th>Bezeichnung</th>
				<th data-priority="2">Termin</th>
				<th data-priority="1">Ort</th>
				<th data-priority="5">Website</th>
				<th data-priority="3">Maximale Teilnehmerzahl</th>
				<th data-priority="6">Beschreibung</th>
				<th data-priority="4">Strecken</th>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach($events as $event){
					echo "<tr>
							<th><a class='ui-btn ui-shadow ui-corner-all ui-icon-edit ui-btn-icon-right' href='".site('organizer_event', 'id='.$event['id'])."'>{$event['id']}</a></th>
							<td>{$event['bezeichnung']}</td>
							<td>{$event['von']} – {$event['bis']}</td>
							<td>{$event['ort']}</td>
							<td>".($event['website'] != null ? "<a href='{$event['website']}'>{$event['website']}</a>" : '')."</td>
							<td>{$event['max_tn']}</td>
							<td>{$event['beschreibung']}</td>
							<td>{$event['anzahl_strecken']}</td>
						</tr>";
				}
			?>
		</tbody>
	</table>
<?php
	}
?>
<a href="<?=site('organizer_event_new')?>" class="ui-btn ui-shadow ui-corner-all ui-icon-plus ui-btn-icon-right">Neuer Wettkampf</a>
<?php require 'components/footer.php'; ?>
