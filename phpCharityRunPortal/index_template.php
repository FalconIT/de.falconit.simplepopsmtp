<?php
define("DEBUGMODE", 0);                     // enable debug messages for several functions. 0 = off, 1 = on
error_reporting(E_ALL);                     // error reporting for development purposes
ini_set("display_errors", 1);               // -"-
require_once("inc/config.inc.php");         // includes
require_once("inc/functions.inc.php");
require_once("inc/security.inc.php");

?>
