<?php if(DEBUGMODE == 1) echo '<p>'.__FILE__.'</p>'; ?>
<?php
	call(function(){
		$headline = 'Läufern';
		$type = 'organizer';
		require 'components/header.php';
	});

	if(isset($_POST['delete'])){
		fetchResults(removeSingleParticipant($_POST['delete_id']));
	}

	if(!isset($_GET['count'])) $_GET['count'] = 20;
	if(!isset($_GET['offset'])) $_GET['offset'] = 0;
	$count  = $_GET['count'];
	$offset = $_GET['offset'];

	$runners = fetchResults(getParticipants($count, $offset));
?>
<h2>Läufer</h2>
<p>Dies ist die Liste mit allen Läufern. Klicken Sie auf die ID um einen Läufern zu bearbeiten.</p>
<?php
	if($runners == null){
		echo '<p class="error_msg">Es wurde noch kein Läufer eingetragen.</p>';
	}else{
?>
	<table data-role="table" id="runner" data-mode="columntoggle" class="ui-responsive table-stroke">
		<thead>
			<tr>
				<th>ID</th>
				<th data-priority="2">Vorname</th>
				<th>Name</th>
				<th data-priority="1">E-Mail</th>
				<th data-priority="5">Verein</th>
				<th data-priority="3">Geschlecht</th>
				<th data-priority="4">Geburt</th>
				<th data-priority="6">Registriert</th>
				<th data-priority="7">Aktiv</th>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach($runners as $runner){
					echo "<tr>
							<th><a class='ui-btn ui-shadow ui-corner-all ui-icon-edit ui-btn-icon-right' href='".site('organizer_runner', 'id='.$runner['id'])."'>{$runner['id']}</a></th>
							<td>{$runner['vorname']}</td>
							<td>{$runner['name']}</td>
							<td>{$runner['email']}</td>
							<td>{$runner['verein']}</td>
							<td>".($runner['geschlecht'] == 'M' ? 'männlich' : 'weiblich')."</td>
							<td>{$runner['geburtsdatum']}</td>
							<td>{$runner['registrierdatum']}</td>
							<td>".($runner['activationcode'] == null ? 'Ja' : 'Nein')."</td>
						</tr>";
				}
			?>
		</tbody>
	</table>
<?php
	}
?>
<?php require 'components/footer.php'; ?>
