<?php

/*
 *
 * tempat untuk mengatur konfigurasi terkait aplikasi
 *
 */

// required classes
$backClasses = array(
	'Akses',
	'SDM'
);
$frontClasses = array(
	'Akses'
);

// konfig localhost
define("DEV_HTTP_PREFIX", 'http');
define("DEV_SQL_HOST", 'localhost');
define("DEV_SQL_PORT", '3306');
define("DEV_SQL_USER", 'root');
define("DEV_SQL_PASS", '');
define("DEV_SQL_DB", 'lpp_agronow');
define("DEV_BASE_NUMBER_ARRURL", 1); // jalankan file z.php dari browser untuk mengetahui value DEV_BASE_NUMBER_ARRURL
define("DEV_ERROR_REPORTING_LV", 1);
define("DEV_MYSQL_DUMP_WIN_LOC", '"D:\wamp64\bin\mysql\mysql5.7.26\bin\mysqldump.exe"');

// server yg di atas server live apa server dev?
$force_dev_mode =  false;
define("LIVE_HTTP_PREFIX", 'https');
if ($_SERVER['HTTP_HOST'] == 'serverdev.co.id') { // settingan untuk server dev
	define("LIVE_SQL_HOST", 'localhost');
	define("LIVE_SQL_PORT", '3306');
	define("LIVE_SQL_USER", 'XXXXX');
	define("LIVE_SQL_PASS", 'XXXXX');
	define("LIVE_SQL_DB", 'XXXXX');
	$force_dev_mode =  true;
} else {
	define("LIVE_SQL_HOST", 'localhost');
	define("LIVE_SQL_PORT", ''); // 3306
	define("LIVE_SQL_USER", 'lppy5623_uagro25min');
	define("LIVE_SQL_PASS", 'Bx=pby0VZ06v1GF+');
	define("LIVE_SQL_DB", 'lppy5623_dbagro25min');
}
define("LIVE_ERROR_REPORTING_LV", 0);
define("LIVE_MYSQL_DUMP_WIN_LOC", '');

// setting timezone
define("TIMEZONE_PHP", "Asia/Jakarta");
define("TIMEZONE_MYSQL", "+07:00");
date_default_timezone_set(TIMEZONE_PHP);
define("KUERI_CUR_TIMEZONE", "SELECT IF(@@session.time_zone = 'SYSTEM', @@system_time_zone, @@session.time_zone) as tz");

// misc
define("APP_NAME", "AgroNow 3.0");
define("COPYRIGHT", "Copyright &copy; " . date('Y') . ", PT LPP Agro Nusantara");
define("PASSWORD_MIN_CHARS", 6);
define("PASSWORD_DEFAULT2", "12345");
define("FILE_PERMISSION_CODE", 0755);
define("DOK_FILESIZE", 7168000);

// aplikasi
define("APP_VERSION", 1);
define("URL_DEV_MAIN", 'https://serverdev.co.id/');
define("URL_LIVE_MAIN", 'https://serverlive.co.id/');
