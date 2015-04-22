<?php if(DEBUGMODE == 1) echo '<p>'.__FILE__.'</p>'; ?>
<form action="<?=$submit == 'new' ? site('organizer_projects') : site('organizer_project', 'id='.$id)?>" method="post">
	<dl>
<?php
	if($submit == 'update'){
?>
		<dt>Projekt-ID</dt>
		<dd><?=$id?></dd>
<?php
	}else{
		$project = array();
		$project['bezeichnung'] = '';
		$project['beschreibung'] = '';
		$project['url'] = '';
		$project['spenden_start'] = '';
		$project['spenden_ende'] = '';
	}
?>
		<dt>Projektbezeichnung</dt>
		<dd><input data-clear-btn="false" name="bezeichnung" id="bezeichnung" value="<?=$project['bezeichnung']?>" type="text" required autocomplete="off" /></dd>
		<dt>Beschreibung</dt>
		<dd><input data-clear-btn="false" name="beschreibung" id="beschreibung" value="<?=$project['beschreibung']?>" type="text" required autocomplete="off" /></dd>
		<dt>URL</dt>
		<dd><input data-clear-btn="false" name="url" id="url" value="<?=$project['url']?>" type="url" required autocomplete="off" /></dd>
		<dt>Start</dt>
		<dd><input data-clear-btn="false" name="spenden_start" id="spenden_start" value="<?=$project['spenden_start']?>" type="date" data-role="datebox" autocomplete="off" data-options="{'defaultValue': <?=$event['termin']?>, 'showInitialValue': true}" required /></dd>
		<dt>Ende</dt>
		<dd><input data-clear-btn="false" name="spenden_ende" id="spenden_ende" value="<?=$project['spenden_ende']?>" type="date" data-role="datebox" autocomplete="off" data-options="{'defaultValue': <?=$event['termin']?>, 'showInitialValue': true, useClearButton: true}" /></dd>
	</dl>
	<input id="<?=$submit;?>" name="<?=$submit;?>" value="Neue Werte eintragen" type="submit" data-icon="action" data-iconpos="right" />
</form>
