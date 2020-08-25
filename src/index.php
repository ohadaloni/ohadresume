<?php
/*------------------------------------------------------------*/
error_reporting(E_ALL | E_NOTICE | E_STRICT );
/*------------------------------------------------------------*/
date_default_timezone_set("Asia/Jerusalem");
/*------------------------------------------------------------*/
define('TOP_DIR', dirname(dirname(__FILE__)));
define('M_DIR', "/var/www/vhosts/M.theora.com");
define('TAS_DIR', "/var/www/vhosts/tas.theora.com");
require_once(TAS_DIR."/conf/dbCredentials.php");
define('M_DBNAME', 'tas');
require_once(M_DIR."/mfiles.php");
require_once("OhadResume.class.php");
/*------------------------------------------------------------*/
global $Mview;
global $Mmodel;
$Mview = new Mview;
$Mmodel = new Mmodel;
/*------------------------------------------------------------*/
$ohadResume = new OhadResume;
$ohadResume->control();
/*------------------------------------------------------------*/
