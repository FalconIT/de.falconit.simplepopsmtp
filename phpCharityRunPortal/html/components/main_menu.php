<?php if(DEBUGMODE == 1) echo '<p>'.__FILE__.'</p>'; ?>
<ul data-role="listview" class="navigation" <?=isset($inset) ? 'data-inset="true"' : ''?>>
	<?=isset($show_main) ? '<li data-icon="home"><a href="'.site('main').'">Startseite</a></li>' : ''?>
	<li data-role="list-divider">Charity-Partner</li>
	<li data-icon="check"><a href="<?=site('partner_login')?>">Anmelden</a></li>
	<li data-icon="user"><a href="<?=site('partner_register')?>">Neu Registieren</a></li>
	<li data-role="list-divider">Läufer</li>
	<li data-icon="check"><a href="<?=site('runner_login')?>">Anmelden</a></li>
	<li data-icon="user"><a href="<?=site('runner_register')?>">Neu Registieren</a></li>
	<li data-role="list-divider">Organisator</li>
	<li data-icon="check"><a href="<?=site('organizer_login')?>">Anmelden</a></li>
</ul>
