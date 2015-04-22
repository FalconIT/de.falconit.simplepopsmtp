<?php if(DEBUGMODE == 1) echo '<p>'.__FILE__.'</p>'; ?>
<?php
	call(function(){
		$headline = 'Neue Strecke';
		$type = 'organizer';
		require 'components/header.php';
	});

	if(!isset($_GET['event_id'])) $_GET['event_id'] = 0;
	$event_id = $_GET['event_id'];
	$event = fetchResults(getSingleEvent($event_id))[0];
?>
<a href="<?=site('organizer_event', 'id='.$event['id'])?>" class="ui-btn ui-shadow ui-corner-all ui-icon-carat-u ui-btn-icon-right">Zum Wettkampf</a>
<h2>Neue Strecke</h2>
<p>Organisieren Sie eine neue Strecke.</p>
<?php
call(function() use ($event_id, $event){
	$submit = 'new';
	require 'components/organizer_track_form.php';
});
?><?php require 'components/footer.php'; ?>
