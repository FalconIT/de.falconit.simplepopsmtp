<?php if(DEBUGMODE == 1) echo '<p>'.__FILE__.'</p>'; ?>
<ul data-role="listview" class="navigation" <?=isset($inset) ? 'data-inset="true"' : ''?>>
	<?=isset($show_main) ? '<li data-icon="home"><a href="'.site('runner_main').'">Übersicht</a></li>' : ''?>
	<li data-role="list-divider">Seiten</li>
	<li><a href="<?=site('runner_data')?>">Persönliche Daten</a></li>
	<li><a href="<?=site('runner_events')?>">Wettkampf Daten</a></li>
	<li data-role="list-divider">Aktionen</li>
	<li data-icon="delete"><a href="<?=site('logout')?>">Abmelden</a></li>
</ul>