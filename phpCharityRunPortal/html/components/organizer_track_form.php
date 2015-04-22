<?php if(DEBUGMODE == 1) echo '<p>'.__FILE__.'</p>'; ?>
<form action="<?=$submit == 'new' ? site('organizer_event', 'id='.$event_id) : site('organizer_track', 'event_id='.$event_id.'&track_id='.$track_id)?>" method="post">
	<dl>
		<dt>Wettkampf-ID</dt>
		<dd><?=$event_id?></dd>
		<dt>Wettkampfbezeichnung</dt>
		<dd><?=$event['bezeichnung']?></dd>
<?php
	if($submit == 'update'){
?>
		<dt>Strecken-ID</dt>
		<dd><?=$track_id?></dd>
<?php
	}else{
		$track = array();
		$track['bezeichnung'] = '';
		$track['termin'] = '';
		$track['laenge'] = '';
		$track['streckenverlauf_url'] = '';
		$track['beschreibung'] = '';
	}
?>
		<dt>Streckenbezeichnung</dt>
		<dd><input data-clear-btn="false" name="bezeichnung" id="bezeichnung" value="<?=$track['bezeichnung']?>" type="text" required autocomplete="off" /></dd>
		<dt>Termin</dt>
		<dd><input data-clear-btn="false" name="termin" id="termin" value="<?=$track['termin']?>" type="date" data-role="datebox" autocomplete="off" data-options="{'defaultValue': <?=$event['termin']?>, 'showInitialValue': true}" required /></dd>
		<dt>Länge</dt>
		<dd><input data-clear-btn="false" name="laenge" id="laenge" value="<?=$track['laenge']?>" type="text" required autocomplete="off" /></dd>
		<dt>Streckenverlauf URL</dt>
		<dd><input data-clear-btn="false" name="streckenverlauf_url" id="streckenverlauf_url" value="<?=$track['streckenverlauf_url']?>" type="url" required autocomplete="off" /></dd>
		<dt>Beschreibung</dt>
		<dd><input data-clear-btn="false" name="beschreibung" id="beschreibung" value="<?=$track['beschreibung']?>" type="text" required autocomplete="off" /></dd>
	</dl>
	<input id="<?=$submit;?>" name="<?=$submit;?>" value="Neue Werte eintragen" type="submit" data-icon="action" data-iconpos="right" />
</form>
