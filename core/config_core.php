<?php
$http_prefix = "";
$ArrUrl   = "";
$host_uri = "";
$host	  = "";
$userSQL  = "";
$passSQL  = "";
$dbSQL	  = "";

if($_SERVER['HTTP_HOST']=='localhost') { // dev
	$modeApp = 'dev';
	$http_prefix = DEV_HTTP_PREFIX;
	$ArrUrl = explode('/',$_SERVER['REQUEST_URI']);
	
	for($i=1;$i<=DEV_BASE_NUMBER_ARRURL;$i++) {
		$host_uri .= "/".$ArrUrl[$i];
	}
	
	$base_number_arrURL = DEV_BASE_NUMBER_ARRURL;
	
	$hostSQL = DEV_SQL_HOST;
	$portSQL = DEV_SQL_PORT;
	$userSQL = DEV_SQL_USER;
	$passSQL = DEV_SQL_PASS;
	$dbSQL	 = DEV_SQL_DB;
	$mysql_dump_loc = DEV_MYSQL_DUMP_WIN_LOC;
	
	error_reporting(DEV_ERROR_REPORTING_LV);
	ini_set('display_errors', 1);
} else { // live
	$modeApp = 'live';
	$http_prefix = LIVE_HTTP_PREFIX;
	$ArrUrl = explode('/',$_SERVER['REQUEST_URI']);
	$host_uri = "";
	$base_number_arrURL = 0;
	
	$hostSQL = LIVE_SQL_HOST;
	$portSQL = LIVE_SQL_PORT;
	$userSQL = LIVE_SQL_USER;
	$passSQL = LIVE_SQL_PASS;
	$dbSQL	 = LIVE_SQL_DB;
	$mysql_dump_loc = LIVE_MYSQL_DUMP_WIN_LOC;
	
	error_reporting(LIVE_ERROR_REPORTING_LV);
	ini_set('display_errors', 1);
}

array_splice($ArrUrl, 0, 1);

$base_site = $_SERVER['HTTP_HOST'];
$pos = strrpos($base_site,'/');
if($pos===false) {
} else {
	$len = strlen($base_site);
	if(($pos+1)==$len) $base_site = substr_replace($base_site, "", -1);
}

$base_dok = $_SERVER['DOCUMENT_ROOT'];
$pos = strrpos($base_dok,'/');
if($pos===false) {
} else {
	$len = strlen($base_dok);
	if(($pos+1)==$len) $base_dok = substr_replace($base_dok, "", -1);
}

define("FRAMEWORK_VERSION","1.0.0");

if($force_dev_mode) $modeApp = "dev";
define("APP_MODE",$modeApp);
define("MAINTENANCE","maintenance");
define("BASE_NUMBER_ARR_URL",$base_number_arrURL);

define("HOST_URI",$host_uri);
define("DB_HOST",$hostSQL);
define("DB_PORT",$portSQL);
define("DB_USERNAME",$userSQL);
define("DB_PASSWORD",$passSQL);
define("DB_NAME",$dbSQL);
define("MYSQL_DUMP_LOC",$mysql_dump_loc);

define("CORE_URI","core");
define("SITE_CONFIG_URI","config_site");
define("CLASS_URI","class");
define("NAVIGASI_URI","navigasi");
define("MEDIA_URI","media");
define("TEMPLATE_URI","template");
define("ASET_URI","vendors");

define("SITE_HOST", $http_prefix."://".$base_site.HOST_URI);
define("NAVIGASI_HOST",SITE_HOST."/".NAVIGASI_URI);
define("MEDIA_HOST",SITE_HOST."/".MEDIA_URI);
define("TEMPLATE_HOST",SITE_HOST."/".TEMPLATE_URI);
define("ASET_HOST",SITE_HOST."/".ASET_URI);

define("SITE_PATH",$base_dok.HOST_URI);
define("NAVIGASI_PATH",SITE_PATH."/".NAVIGASI_URI);
define("MEDIA_PATH",SITE_PATH."/".MEDIA_URI);
define("TEMPLATE_PATH",SITE_PATH."/".TEMPLATE_URI);
define("ASET_PATH",SITE_PATH."/".ASET_URI);

// core/config/class
define("CORE_PATH",SITE_PATH."/".CORE_URI);
define("SITE_CONFIG_PATH",SITE_PATH."/".SITE_CONFIG_URI);
define("CLASS_PATH",SITE_PATH."/".CLASS_URI);

// back end
define("BE_MAIN_HOST",SITE_HOST."/be");
define("BE_ASET_HOST",ASET_HOST."/be");
define("BE_ASET_PATH",ASET_PATH."/be");
define("BE_TEMPLATE_HOST",TEMPLATE_HOST."/be");
define("BE_TEMPLATE_PATH",TEMPLATE_PATH."/be");
// front end
define("FE_MAIN_HOST",SITE_HOST);
define("FE_ASET_HOST",ASET_HOST."/fe");
define("FE_ASET_PATH",ASET_PATH."/fe");
define("FE_TEMPLATE_HOST",TEMPLATE_HOST."/fe");
define("FE_TEMPLATE_PATH",TEMPLATE_PATH."/fe");

define("NAVIGASI","nav.");
define("CLASSES",".class.php");
define("EXT",".php");
?>