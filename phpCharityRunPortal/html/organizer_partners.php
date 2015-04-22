<?php if(DEBUGMODE == 1) echo '<p>'.__FILE__.'</p>'; ?>
<?php
	call(function(){
		$headline = 'Charity-Partner';
		$type = 'organizer';
		require 'components/header.php';
	});

	if(isset($_POST['delete'])){
		fetchResults(removeCharityPartner($_POST['delete_id']));
	}

	if(!isset($_GET['count'])) $_GET['count'] = 20;
	if(!isset($_GET['offset'])) $_GET['offset'] = 0;
	$count  = $_GET['count'];
	$offset = $_GET['offset'];

	$partners = fetchResults(getCharityPartner($count, $offset));
?>
<h2>Charity-Partner</h2>
<p>Dies ist die Liste mit allen Charity-Partnern. Klicken Sie auf die ID um einen Charity-Partner zu bearbeiten.</p>
<?php
	if($partners == null){
		echo '<p class="error_msg">Es wurde noch kein Charity-Partner eingetragen.</p>';
	}else{
?>
	<table data-role="table" id="partner" data-mode="columntoggle" class="ui-responsive table-stroke">
		<thead>
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th data-priority="1">E-Mail</th>
				<th data-priority="3">Adresse 1</th>
				<th data-priority="4">Adresse 2</th>
				<th data-priority="5">Straße</th>
				<th data-priority="6">Plz.</th>
				<th data-priority="7">Ort</th>
				<th data-priority="8">Land</th>
				<th data-priority="9">Telefon</th>
				<th data-priority="2">Tkz.</th>
				<th data-priority="10">Aktiv</th>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach($partners as $partner){
					echo "<tr>
							<th><a class='ui-btn ui-shadow ui-corner-all ui-icon-edit ui-btn-icon-right' href='".site('organizer_partner', 'id='.$partner['id'])."'>{$partner['id']}</a></th>
							<td>{$partner['name']}</td>
							<td>{$partner['email']}</td>
							<td>{$partner['adresse1']}</td>
							<td>{$partner['adresse2']}</td>
							<td>{$partner['strasse']}</td>
							<td>{$partner['plz']}</td>
							<td>{$partner['ort']}</td>
							<td>{$partner['land']}</td>
							<td>{$partner['telefon']}</td>
							<td>".($partner['transparenzkennzeichen'] == 't' ? 'Ja' : 'Nein')."</td>
							<td>".($partner['activationcode'] == null ? 'Ja' : 'Nein')."</td>
						</tr>";
				}
			?>
		</tbody>
	</table>
<?php
	}
?>
<?php require 'components/footer.php'; ?>