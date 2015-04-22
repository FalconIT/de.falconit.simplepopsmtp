<?php if(DEBUGMODE == 1) echo '<p>'.__FILE__.'</p>'; ?>
<div data-role="panel" data-display="overlay" id="menu">
	<h2 id="menu_header">Navigation</h2>
	<?php
		call(function() use ($type){
			$show_main = true;
			require $type.'_menu.php';
		});
	?>
</div>
