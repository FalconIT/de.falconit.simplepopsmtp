<?php if(DEBUGMODE == 1) echo '<p>'.__FILE__.'</p>'; ?>
<form action="<?=$submit == 'new' ? site('organizer_events') : site('organizer_event', 'id='.$id)?>" method="post">
	<dl>
<?php
	if(isset($event['id'])){
?>
		<dt>ID</dt>
		<dd><?=$event['id']?></dd>
<?php
	}else{
		$event = array();
		$event['bezeichnung'] = '';
		$event['von'] = '';
		$event['bis'] = '';
		$event['ort'] = '';
		$event['website'] = '';
		$event['max_tn'] = '';
		$event['beschreibung'] = '';
		$event['gebuehrenpflichtig'] = 'f';
		$event['iban'] = '';
		$event['bic'] = '';
		$event['betrag'] = '';
		$event['zahlungsempfaenger'] = '';
	}

	$event['betrag'] = (float)$event['betrag'];
?>
		<dt>Bezeichnung</dt>
		<dd><input data-clear-btn="false" name="bezeichnung" id="bezeichnung" value="<?=$event['bezeichnung']?>" type="text" required autocomplete="off" /></dd>
		<dt>Termin</dt>
		<dd>
			<label for="von">Von</label>
			<input data-clear-btn="false" name="von" id="von" value="<?=$event['von']?>" type="date" data-role="datebox" data-options="{'defaultValue': <?=$event['von']?>, 'showInitialValue': true}" required autocomplete="off" />
			<label for="bis">Bis</label>
			<input data-clear-btn="false" name="bis" id="bis" value="<?=$event['bis']?>" type="date" data-role="datebox" data-options="{'defaultValue': <?=$event['bis']?>, 'showInitialValue': true}" required autocomplete="off" />
		</dd>
		<dt>Ort</dt>
		<dd><input data-clear-btn="false" name="ort" id="ort" value="<?=$event['ort']?>" type="text" required autocomplete="off" /></dd>
		<dt>Website</dt>
		<dd><input data-clear-btn="false" name="website" id="website" value="<?=$event['website']?>" type="url" autocomplete="off" /></dd>
		<dt>Maximale Teilnehmerzahl</dt>
		<dd><input data-clear-btn="false" name="max_tn" id="max_tn" value="<?=$event['max_tn']?>" type="number" required autocomplete="off" /></dd>
		<dt>Beschreibung</dt>
		<dd><input data-clear-btn="false" name="beschreibung" id="beschreibung" value="<?=$event['beschreibung']?>" type="text" autocomplete="off" /></dd>
		<dt>gebuehrenpflichtig</dt>
		<dd>
			<select name="gebuehrenpflichtig" id="gebuehrenpflichtig" data-role="slider">
				<?php
					echo '<option value="no"'.($event['gebuehrenpflichtig'] == 't' ? '' : ' selected').'>Nein</option>';
					echo '<option value="yes"'.($event['gebuehrenpflichtig'] == 't' ? ' selected' : '').'>Ja</option>';
				?>
			</select>
		</dd>
		<dt class="gebuehrenpflichtig">IBAN</dt>
		<dd class="gebuehrenpflichtig"><input data-clear-btn="false" name="iban" id="iban" value="<?=$event['iban']?>" type="text" required autocomplete="off" /></dd>
		<dt class="gebuehrenpflichtig">BIC</dt>
		<dd class="gebuehrenpflichtig"><input data-clear-btn="false" name="bic" id="bic" value="<?=$event['bic']?>" type="text" required autocomplete="off" /></dd>
		<dt class="gebuehrenpflichtig">Betrag</dt>
		<dd class="gebuehrenpflichtig"><input data-clear-btn="false" name="betrag" id="betrag" value="<?=$event['betrag']?>" type="number" step="0.01" required autocomplete="off" /></dd>
		<dt class="gebuehrenpflichtig">Zahlungsempfänger</dt>
		<dd class="gebuehrenpflichtig"><input data-clear-btn="false" name="zahlungsempfaenger" id="zahlungsempfaenger" value="<?=$event['zahlungsempfaenger']?>" type="text" required autocomplete="off" /></dd>
	</dl>
	<input id="<?=$submit;?>" name="<?=$submit;?>" value="Neue Werte eintragen" type="submit" data-icon="action" data-iconpos="right" />
</form>
<script>
$(function(){
	$('#gebuehrenpflichtig').change(function(){
		if($('#gebuehrenpflichtig').val() == 'yes'){
			$('.gebuehrenpflichtig').css('display', 'block');
			$('.gebuehrenpflichtig input').attr('required', 'required');
		}else{
			$('.gebuehrenpflichtig').css('display', 'none');
			$('.gebuehrenpflichtig input').removeAttr('required').val('');
		}
	}).change();
// 	check();
});
</script>