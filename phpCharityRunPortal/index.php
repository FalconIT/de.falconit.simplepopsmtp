<?php
        
	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);
	const DEBUGMODE = 0;

	// @Browser: Do not cache!!!
	header("Cache-Control: no-cache, must-revalidate");
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

	require_once("inc/config.inc.php");
	require_once("inc/functions.inc.php");
	require_once("inc/security.inc.php");
	require_once("inc/mail.inc.php");
?>
<!DOCTYPE html>
<html lang="de">
<head>
	<title>Charityrun Portal</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />

	<!-- @Browser: Do not cache!!! -->
	<meta http-equiv="cache-control" content="max-age=0" />
	<meta http-equiv="cache-control" content="no-cache" />
	<meta http-equiv="expires" content="0" />
	<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
	<meta http-equiv="pragma" content="no-cache" />

	<link rel="stylesheet" href="js/jquery.mobile-1.4.2.min.css" />
	<link rel="stylesheet" href="js/jqm-datebox.min.css" />
	<script src="js/jquery-2.1.1.min.js"></script>

	<script src="js/jquery.mobile-1.4.2.min.js"></script>
	<script src="js/jquery.mousewheel.min.js"></script>
	<script src="js/jqm-datebox.core.min.js"></script>
	<script src="js/jqm-datebox.mode.datebox.min.js"></script>
	<script src="js/jquery.mobile.datebox.i18n.de.utf8.js"></script>

	<script src="js/tableExport.js"></script>
	<script src="js/jquery.base64.js"></script>
	<script src="js/jspdf/libs/sprintf.js"></script>
	<script src="js/jspdf/jspdf.js"></script>
	<script src="js/jspdf/libs/base64.js"></script>

	<script>
		jQuery.extend(jQuery.mobile.datebox.prototype.options, {
			useModal: true,
			useNewStyle: true,
			usePlaceholder: true,
			mode: 'datebox',
			overrideDateFormat: '%Y-%m-%d',
			useFocus: true,
			useButton: false,
			useLang: 'de'
		});
		$(function(){
			$.ajaxSetup({cache: false});
			$.mobile.ajaxEnabled = false; // alternative: disable caching, but how?
		});
	</script>
	<style>
		.ui-header .ui-title, .ui-footer .ui-title { margin:0 2.5em; }

		#content input:invalid, #content fieldset:invalid { 
			border-color: #e88;
			-webkit-box-shadow: 0 0 5px rgba(255, 0, 0, .8);
			-moz-box-shadow: 0 0 5px rbba(255, 0, 0, .8);
			-o-box-shadow: 0 0 5px rbba(255, 0, 0, .8);
			-ms-box-shadow: 0 0 5px rbba(255, 0, 0, .8);
			box-shadow:0 0 5px rgba(255, 0, 0, .8);
		}

		dt{
			font-weight:bold;
		}

		h2, h3, h4, h5, h6 {
			text-shadow:1px 1px 1px #DDD, 2px 2px 0 #BBB;
		}

		#menu_header{
			display:none;
		}

		.error_msg{
			color:#FF0000;
		}

		/* First breakpoint at 576px */
		/* Inherits mobile styles, but floats containers to make columns */
		@media all and (min-width: 80em){
			#menu {
				position:relative;
				float:left;
				width:20%;
				-webkit-transform: none;
				-moz-transform: none;
				-ms-transform: none;
				-o-transform: none;
				transform: none;
				left:0;
				max-height:none;
				overflow: visible;
				visibility: visible;
				box-shadow: none;
				background-color:#E9E9E9;
				z-index:400;
			}

			#menu>.ui-panel-inner{
				border-right:1px solid #DDD;
			}

			#menu_button{
				display:none;
			}

			#content{
				position:relative;
				float:right;
				width:80%;
			}

			#footer {
				clear:both;
			}

			#menu_header{
				display:block;
				margin-top:0em;
				margin-bottom:1em;
			}

			#content .navigation{
				display:none;
			}
		}
	</style>
</head>
<body>
<?php
	// generate a link
	function site($name, $params = null){
		$result = $_SERVER['SCRIPT_NAME'].'?site='.$name;
		if($params != null) $result .= '&'.$params;
		return $result;
	}

	if(!isset($_GET['site'])) $_GET['site'] = 'main';

	function post_output($name, $default = ''){
		return isset($_POST[$name]) ? htmlspecialchars($_POST[$name]) : $default;
	}

	/// Call the parameter as function
	///
	/// Used when a temporary variable is needed, that should not contaminate the global namespace.
	function call($function){
		return $function();
	}

	$role_number = call(function(){
		switch($_GET['site']){
			case 'organizer_login': return 1;
			case 'partner_login': return 2;
			case 'runner_login': return 3;
		}
		return 0;
	});

	if(isset($_POST['login']) && isset($_POST['email']) && isset($_POST['pass']) && $role_number > 0){
		$login_error = login_main($_POST['email'], $role_number, $_POST['pass']);
	}

	// Prüfen ob Seite prinzipell bekannt
	if(!in_array($_GET['site'], array(
		'main',
		'activate',
		'logout',
		'organizer_event',
		'organizer_event_new',
		'organizer_events',
		'organizer_login',
		'organizer_main',
		'organizer_partner',
		'organizer_partners',
		'organizer_project',
		'organizer_project_new',
		'organizer_projects',
		'organizer_runner',
		'organizer_runners',
		'organizer_track',
		'organizer_track_new',
		'partner_data',
		'partner_login',
		'partner_main',
		'partner_register',
		'partner_projekt',
		'runner_data',
		'runner_event',
		'runner_events',
		'runner_login',
		'runner_main',
		'runner_register'
	))){
		require 'html/error.php';
	}
	else // Wenn ja, die zum aktuellen Login-Status passende Seite anzeigen
	{
		if(isset($_SESSION['role'])){
			switch($_SESSION['role']){
				case 1: // organizer
					if(DEBUGMODE == 1) echo '<p>organizer</p>';
					switch($_GET['site']){
						case 'organizer_event': require 'html/organizer_event.php'; break;
						case 'organizer_event_new': require 'html/organizer_event_new.php'; break;
						case 'organizer_events': require 'html/organizer_events.php'; break;
						case 'organizer_track': require 'html/organizer_track.php'; break;
						case 'organizer_track_new': require 'html/organizer_track_new.php'; break;
						case 'organizer_partner': require 'html/organizer_partner.php'; break;
						case 'organizer_partners': require 'html/organizer_partners.php'; break;
						case 'organizer_runner': require 'html/organizer_runner.php'; break;
						case 'organizer_runners': require 'html/organizer_runners.php'; break;
						case 'organizer_project': require 'html/organizer_project.php'; break;
						case 'organizer_project_new': require 'html/organizer_project_new.php'; break;
						case 'organizer_projects': require 'html/organizer_projects.php'; break;
						case 'logout': logout(); require 'html/main_page.php'; break;
						default: require 'html/organizer_main.php';
					}
				break;
				case 2: // partner
					if(DEBUGMODE == 1) echo '<p>partner</p>';
					switch($_GET['site']){
						case 'partner_data': require 'html/partner_data.php'; break;
						case 'partner_projekt': require 'html/partner_projekt.php'; break;
						case 'logout': logout(); require 'html/main_page.php'; break;
						default: require 'html/partner_main.php';
					}
				break;
				case 3: // runner
					if(DEBUGMODE == 1) echo '<p>runner</p>';
					switch($_GET['site']){
						case 'runner_data': require 'html/runner_data.php'; break;
						case 'runner_event': require 'html/runner_event.php'; break;
						case 'runner_events': require 'html/runner_events.php'; break;
						case 'logout': logout(); require 'html/main_page.php'; break;
						default: require 'html/runner_main.php';
					}
				break;
			}
		}else{
			if(DEBUGMODE == 1) echo '<p>startsite</p>';
			switch($_GET['site']){
				case 'runner_login': require 'html/runner_login.php'; break;
				case 'runner_register': require 'html/runner_register.php'; break;
				case 'partner_login': require 'html/partner_login.php'; break;
				case 'partner_register': require 'html/partner_register.php'; break;
				case 'organizer_login': require 'html/organizer_login.php'; break;
				case 'activate': require 'html/activate.php'; break;
				case 'logout': logout();
				default: require 'html/main_page.php';
			}
		}
	}
?>
</body>
</html>