<?php if (DEBUGMODE == 1) echo '<p>' . __FILE__ . '</p>'; ?>
<?php
call(function() {
    $headline = 'Läufer';
    $type = 'runner';
    require 'components/header.php';
});

$user = fetchResults(getSingleParticipant($_SESSION['id']))[0];
$events = fetchResults(getEventsByRunner(100, 0, $_SESSION['id']));
?>
<h2>Hauptseite</h2>
<p>Willkommen <?= $user["geschlecht"] === "M" ? "Herr" : "Frau"; ?> <?= $user["name"]; ?>!</p>
<h2>Ihre Wettkämpfe</h2>
<table data-role="table" id="events" data-mode="columntoggle" class="ui-responsive table-stroke">
    <thead>
        <tr>
            <th>Bezeichnung</th>
            <th data-priority="2">Termin</th>
            <th data-priority="1">Ort</th>
            <th data-priority="5">Website</th>
            <th data-priority="4">Strecken</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (!empty($events)) {
            foreach ($events as $event) {
                $event['von'] = date("d.m.Y", strtotime($event['von']));
                $event['bis'] = date("d.m.Y", strtotime($event['bis']));
                echo "<tr>
                                        
							<td><a class='ui-btn ui-shadow ui-corner-all ui-icon-info ui-btn-icon-right' href='" . site('runner_event', 'id=' . $event['id']) . "'>{$event['bezeichnung']}</a></td>
							<td>{$event['von']} – {$event['bis']}</td>
							<td>{$event['ort']}</td>
							<td><a href='{$event['website']}'>{$event['website']}</a></td>

							<td>{$event['anzahl_strecken']}</td>
						</tr>";
            }
        }
        ?> 
    </tbody>
</table>
<?php
call(function() {
    $inset = true;
    require 'components/runner_menu.php';
});
?>
<?php require 'components/footer.php'; ?>
