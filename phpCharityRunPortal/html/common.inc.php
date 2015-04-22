<?php

function page($id, $headline, $content, $backid = NULL){
	echo "
<div data-role='page' id='$id'>
	<div data-role='header'>".
	($backid === NULL ?
		"" :
		"<a data-transition='slide' data-direction='reverse' href='#$backid' class='ui-btn ui-shadow ui-corner-all ui-icon-carat-l ui-btn-icon-notext'>Zurück</a>"
	)."<h1>$headline</h1></div>
	<div role='main' class='ui-content'>$content</div>
	<div data-role='footer'><h4><a href='http://www.brocken-challenge.de/'>Zurück zur Hauptseite</a></h4></div>
</div>
	";
}

function error_page($headline, $content){
	page('error', $headline, "
		$content
		<a href='{$_SERVER['PHP_SELF']}' class='ui-btn ui-shadow ui-icon-home ui-btn-icon-right' rel='external'>Startseite</a>
	");
}

?>