<?php if(DEBUGMODE == 1) echo '<p>'.__FILE__.'</p>'; ?>
<?php
	call(function(){
		$headline = 'Wettkampf Daten';
		$type = 'runner';
		require 'components/header.php';
	});
        if(isset($_POST['apply'])) {
            foreach($_POST as $key => $value) {
            $pos = strpos($key , "select-");
                if ($pos === 0){
                    fetchResults(addParticipant(str_replace("select-", "", $key), $_SESSION['id']));
            }
}
        }
	if(!isset($_GET['id'])) $_GET['id'] = 20;
	$id = $_GET['id'];

	$event = fetchResults(getSingleEvent($id))[0];
        $teilnahme = fetchResults("SELECT count(*) FROM wettkampfteilnahme WHERE wettkampf_id=".pg_escape_literal($id)." AND laeufer_id = $_SESSION[id];");
	$tracks = fetchResults(getTracksByEvent($event['id']));
        $event['von'] = date("d.m.Y", strtotime($event['von']));
        $event['bis'] = date("d.m.Y", strtotime($event['bis']));
?>
<a href="<?=site('runner_events')?>" class="ui-btn ui-shadow ui-corner-all ui-icon-carat-u ui-btn-icon-right">Zu den Wettkämpfen</a>
<h2>Wettkampf</h2>
<p>Die folgenden Informationen stehen über den Wettkampf zur Verfügung:</p>
<dl>
	<dt>Bezeichnung</dt>
	<dd><?=$event['bezeichnung']?></dd>
	<dt>Termin</dt>
	<dd><?=$event['von'].' – '.$event['bis']?></dd>
	<dt>Ort</dt>
	<dd><?=$event['ort']?></dd>
	<dt>Website</dt>
	<dd><a href="<?=$event['website']?>"><?=$event['website']?></a></dd>
	<dt>Maximale Teilnehmerzahl</dt>
	<dd><?=$event['max_tn']?></dd>
	<dt>Beschreibung</dt>
	<dd><?=$event['beschreibung']?></dd>
</dl>
<?php if (isset($teilnahme[0]['count']) && $teilnahme[0]['count'] > 0) {
    $tracksA = fetchResults(getTracksByEventByParticipation($event['id'], $_SESSION['id']));
    echo "<h2>Ihre Teilnahme</h2>";
    echo "Sie sind bereits für diesen Wettkampf angemeldet:";
    		echo '<table data-role="table" id="tracks" data-mode="columntoggle" class="ui-responsive table-stroke">
			<thead>
				<tr>
					<th>Bezeichnung</th>
					<th data-priority="1">Termin</th>
					<th data-priority="2">Länge</th>
					<th data-priority="3">Beschreibung</th>
					<th data-priority="4">Verlauf</th>
				</tr>
			</thead>
			<tbody>';
			
				foreach($tracks as $track){
                                    $track['termin'] = date("d.m.Y, H:i", strtotime($track['termin']));
					echo "<tr>
							<td>{$track['bezeichnung']}</td>
							<td>{$track['termin']}</td>
							<td>{$track['laenge']}</td>
							<td>{$track['beschreibung']}</td>
							<td><a href='{$track['streckenverlauf_url']}'>Verlauf</a></td>
						</tr>";
				}
			 echo "
			</tbody>
		</table><br />";
    if($event['gebuehrenpflichtig']==="t") {
        if(fetchResults("SELECT count(*) FROM zahlungen WHERE wettkampf_id = ".  pg_escape_literal($id) ." AND laeufer_id = ".pg_escape_literal($_SESSION['id']).";")[0]['count'] > 0) {
            echo "Wir haben die Zahlung der Teilnahmegebühr bereits erhalten, vielen Dank!";
        }
            else {
        echo "Für diese Veranstaltung ist eine Teilnahmegebühr fällig. Bitte überweisen sie den Betrag von <b>{$event['betrag']}€</b> an folgendes Konto:<br /><br />"
        . "<table class=\"ui-responsive table-stroke\"><tr><td>Kontoinhaber:</td><td>{$event['zahlungsempfaenger']}</td></tr>"
        . "<tr><td>IBAN:</td><td>{$event['iban']}</td></tr>"
        . "<tr><td>BIC:</td><td>{$event['bic']}</td></tr></table>";
        
    }
    }
    else {
        echo "Diese Veranstaltung hat keine Teilnahmegebühr.";
    }
}
?>
<h2>Strecken</h2>
<p>Wählen Sie die Strecke aus, auf denen Sie gerne mitlaufen würden.</p>
<?php
	if($tracks == null){
		echo '<p class="error_msg">Es wurden noch keine Stecken eingetragen.</p>';
	}else{
?>
	<form action="" method="post">
		<table data-role="table" id="tracks" data-mode="columntoggle" class="ui-responsive table-stroke">
			<thead>
				<tr>
					<th>Bezeichnung</th>
					<th data-priority="1">Termin</th>
					<th data-priority="2">Länge</th>
					<th data-priority="3">Beschreibung</th>
					<th data-priority="4">Verlauf</th>
					<th>Teilnahmewunsch</th>
				</tr>
			</thead>
			<tbody>
			<?php
				foreach($tracks as $track){
                                    $track['termin'] = date("d.m.Y, H:i", strtotime($track['termin']));
					echo "<tr>
							<td>{$track['bezeichnung']}</td>
							<td>{$track['termin']}</td>
							<td>{$track['laenge']}</td>
							<td>{$track['beschreibung']}</td>
							<td><a href='{$track['streckenverlauf_url']}'>Verlauf</a></td>
							<td><input id='select-{$track['id']}' name='select-{$track['id']}' type='checkbox'data-mini='true' /><label for='select-{$track['id']}'>Anmelden</label></td>
						</tr>";
				}
			?>
			</tbody>
		</table>
            <input type="hidden" name="eventid" value="<?=$id?>" />
		<input id="apply" name="apply" value="Anmelden" type="submit" data-icon="action" data-iconpos="right" />
	</form>
<?php
	}
?>
<?php require 'components/footer.php'; ?>
