<?php if(DEBUGMODE == 1) echo '<p>'.__FILE__.'</p>'; ?>
<?php
	call(function(){
		$headline = 'Charity-Projekte';
		$type = 'organizer';
		require 'components/header.php';
	});

	if(isset($_POST['new'])){
		$bezeichnung = post_output('bezeichnung');
		$beschreibung = post_output('beschreibung');
		$url = post_output('url');
		$spenden_start = post_output('spenden_start');
		$spenden_ende = post_output('spenden_ende');

		fetchResults(addCharityProjekt($bezeichnung, $beschreibung, $url, $spenden_start, $spenden_ende, $_SESSION['id']));
	}elseif(isset($_POST['delete'])){
		fetchResults(removeCharityProjekt($_POST['delete_id']));
	}

	if(!isset($_GET['count'])) $_GET['count'] = 20;
	if(!isset($_GET['offset'])) $_GET['offset'] = 0;
	$count  = $_GET['count'];
	$offset = $_GET['offset'];

	$projects = fetchResults(getCharityProjekte($count, $offset));
?>
<h2>Charity-Projekte</h2>
<p>Dies ist die Liste mit allen Charity-Projekte. Klicken Sie auf die ID um eine Charity-Projekte zu bearbeiten.</p>
<?php
	if($projects == null){
		echo '<p class="error_msg">Es wurden noch kein Charity-Projekte eingetragen.</p>';
	}else{
?>
	<table data-role="table" id="projects" data-mode="columntoggle" class="ui-responsive table-stroke">
		<thead>
			<tr>
				<th>ID</th>
				<th>Bezeichnung</th>
				<th data-priority="3">Beschreibung</th>
				<th data-priority="4">URL</th>
				<th data-priority="1">Start</th>
				<th data-priority="2">Ende</th>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach($projects as $project){
					echo "<tr>
							<th><a class='ui-btn ui-shadow ui-corner-all ui-icon-edit ui-btn-icon-right' href='".site('organizer_project', 'id='.$project['id'])."'>{$project['id']}</a></th>
							<td>{$project['bezeichnung']}</td>
							<td>{$project['beschreibung']}</td>
							<td><a href='{$project['url']}'>{$project['url']}</a></td>
							<td>{$project['spenden_start']}</td>
							<td>{$project['spenden_ende']}</td>
						</tr>";
				}
			?>
		</tbody>
	</table>
<?php
	}
?>
<a href="<?=site('organizer_project_new')?>" class="ui-btn ui-shadow ui-corner-all ui-icon-plus ui-btn-icon-right">Neues Charity-Projekt</a>
<?php require 'components/footer.php'; ?>
