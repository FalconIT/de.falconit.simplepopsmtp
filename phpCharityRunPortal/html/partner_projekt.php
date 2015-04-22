<?php if(DEBUGMODE == 1) echo '<p>'.__FILE__.'</p>'; ?>
<?php
	call(function(){
		$headline = 'Charity-Projekte';
		$type = 'partner';
		require 'components/header.php';
	});

	if(isset($_POST['spenden'])){
		$id = post_output('id');
		$betrag =(float)post_output('betrag');
		fetchResults(addSponsoringPartnerProjekt($id, $_SESSION['id'], $betrag));
	}
?>
<h2>Spendenzusagen</h2>
<p>Dies sind die Projekte denen Sie bereits eine Pauschale Spende zugesagt haben.</p>
<?php
	$projects = fetchResults(getCharityProjekte(20, 0));
        $projects_gesp = fetchResults(getSponsoringPartnerProjekt($_SESSION['id']));
?>
	<table data-role="table" id="partner" data-mode="columntoggle" class="ui-responsive table-stroke">
		<thead>
			<tr>
				<th>Bezeichnung</th>
				<th data-priority="3">Beschreibung</th>
				<th data-priority="4">URL</th>
				<th>Spendenbetrag</th>
                                <th>Spende eingegangen</th>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach($projects_gesp as $projec){
                                    $projec['pauschale'] = str_replace(".", ",",$projec['pauschale']);
                                        if($projec['bezahlt']=="t") {
                                            $bez = "Ja";
                                        }
                                        else {
                                            $bez = "Nein";
                                        }
					echo "<tr>
							<td>{$projec['bezeichnung']}</td>
							<td>{$projec['beschreibung']}</td>
							<td><a href='{$projec['url']}'>{$projec['url']}</a></td>
							<td>{$projec['pauschale']}€</td>
                                                        <td>{$bez}</td>
						</tr>";
				}
			?>
		</tbody>
	</table>
<h2>Charity-Projekte</h2>
<p>Hier können Sie Pauschal für Charity-Projekte spenden.</p>
<?php
	if($projects == null){
		echo '<p class="error_msg">Es wurden noch kein Charity-Projekte eingetragen.</p>';
	}else{
?>
	<table data-role="table" id="partner" data-mode="columntoggle" class="ui-responsive table-stroke">
		<thead>
			<tr>
				<th>Bezeichnung</th>
				<th data-priority="3">Beschreibung</th>
				<th data-priority="4">URL</th>
				<th>Spenden</th>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach($projects as $project){
					echo "<tr>
							<td>{$project['bezeichnung']}</td>
							<td>{$project['beschreibung']}</td>
							<td><a href='{$project['url']}'>{$project['url']}</a></td>
							<td>
							<form action=".site('partner_projekt')." method='post'>
								<input name='id' id='id{$project['id']}' value='{$project['id']}' type='hidden' />
								<input data-clear-btn='false' name='betrag' id='betrag{$project['id']}' value='' type='number' step='0.01' required autocomplete='off' />
								<input name='spenden' id='spenden{$project['id']}' value='Spenden' type='submit' data-icon='action' data-iconpos='right' />
							</form>
							</td>
						</tr>";
				}
			?>
		</tbody>
	</table>
<?php
	}
?>

<?php require 'components/footer.php'; ?>
