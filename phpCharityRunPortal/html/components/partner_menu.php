<?php if(DEBUGMODE == 1) echo '<p>'.__FILE__.'</p>'; ?>
<ul data-role="listview" class="navigation" <?=isset($inset) ? 'data-inset="true"' : ''?>>
	<?=isset($show_main) ? '<li data-icon="home"><a href="'.site('partner_main').'">Übersicht</a></li>' : ''?>
	<li data-role="list-divider">Seiten</li>
	<li><a href="<?=site('partner_data')?>">Persönliche Daten</a></li>
	<li><a href="<?=site('partner_projekt')?>">Charity-Projekte</a></li>
	<li data-role="list-divider">Aktionen</li>
	<li data-icon="delete"><a href="<?=site('logout')?>">Abmelden</a></li>
</ul>