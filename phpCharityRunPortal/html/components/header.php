<?php if(DEBUGMODE == 1) echo '<p>'.__FILE__.'</p>'; ?>
<div data-role="page" id="page" data-dom-cache="false" data-cache="never">
	<div data-role="header">
		<a id="menu_button" href="#menu" class="ui-btn ui-shadow ui-corner-all ui-icon-bars ui-btn-icon-notext">Menü</a>
		<h1><?=$headline;?></h1>
	</div>
	<div data-role="main">
		<?php require 'panel.php'; ?>
		<div id="content" >
			<div class="ui-content">
<?php
	if(DEBUGMODE == 1){
		echo "<p>GET: ";print_r($_GET);echo "</p>";
		echo "<p>POST: ";print_r($_POST);echo "</p>";
		echo "<p>SESSION: ";print_r($_SESSION);echo "</p>";
	}
?>