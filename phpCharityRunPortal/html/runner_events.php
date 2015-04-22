<?php if(DEBUGMODE == 1) echo '<p>'.__FILE__.'</p>'; ?>
<?php
	call(function(){
		$headline = 'Wettkampf Daten';
		$type = 'runner';
		require 'components/header.php';
	});

	if(!isset($_GET['count'])) $_GET['count'] = 20;
	if(!isset($_GET['offset'])) $_GET['offset'] = 0;
	$count  = $_GET['count'];
	$offset = $_GET['offset'];

	$events = fetchResults(getFutureEvents($count, $offset));
?>
<h2>Wettkämpfe</h2>
<p>Hier können Sie sich für einzelne Wettkämpfe eintragen. Klicken Sie eine Wettkampfbezeichnung an, um sich den Wettkampf näher anzusehen.</p>
<?php
	if($events == null){
		echo '<p class="error_msg">Es wurden noch keine Wettkämpfe eingetragen.</p>';
	}else{
?>
	<table data-role="table" id="events" data-mode="columntoggle" class="ui-responsive table-stroke">
		<thead>
			<tr>
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
                                        $event['von'] = date("d.m.Y", strtotime($event['von']));
                                        $event['bis'] = date("d.m.Y", strtotime($event['bis']));
					echo "<tr>
							<td><a class='ui-btn ui-shadow ui-corner-all ui-icon-info ui-btn-icon-right' href='".site('runner_event', 'id='.$event['id'])."'>{$event['bezeichnung']}</a></td>
							<td>{$event['von']} – {$event['bis']}</td>
							<td>{$event['ort']}</td>
							<td><a href='{$event['website']}'>{$event['website']}</a></td>
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
<?php require 'components/footer.php'; ?>
