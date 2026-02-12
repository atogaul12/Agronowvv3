<?php
// udah login?
if($butuh_login) { // harus login?
	if(empty($_SESSION['be']['id_user'])) {
		header("location:".BE_MAIN_HOST."/user/login");
		exit;
	}
	
	if($akses->isLoggedIn()) { // sudah login?
		if($this->pageBase=="login") {
			header("location:".BE_MAIN_HOST."");
			exit;
		}
	}
}

// developer mode?
if(APP_MODE=="dev") {
	$this->pageTitle = "(versi DEMO) ".$this->pageTitle."";
}
?>


<!doctype html>
<html lang="en" data-bs-theme="blue-theme">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?=APP_NAME?></title>
  <!--favicon-->
  <link rel="icon" href="<?=MEDIA_HOST?>/aset/ico/favicon-32x32.png" type="image/png">
  <!-- loader-->
  <link href="<?=BE_ASET_HOST?>/assets/css/pace.min.css" rel="stylesheet">
  <script src="<?=BE_ASET_HOST?>/assets/js/pace.min.js"></script>

  <!--plugins-->
  <link href="<?=BE_ASET_HOST?>/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="<?=BE_ASET_HOST?>/assets/plugins/metismenu/metisMenu.min.css">
  <link rel="stylesheet" type="text/css" href="<?=BE_ASET_HOST?>/assets/plugins/metismenu/mm-vertical.css">
  <link rel="stylesheet" type="text/css" href="<?=BE_ASET_HOST?>/assets/plugins/simplebar/css/simplebar.css">
  <!--bootstrap css-->
  <link href="<?=BE_ASET_HOST?>/assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">
  <!--main css-->
  <link href="<?=BE_ASET_HOST?>/assets/css/bootstrap-extended.css" rel="stylesheet">
  <link href="<?=BE_ASET_HOST?>/sass/main.css" rel="stylesheet">
  <link href="<?=BE_ASET_HOST?>/sass/dark-theme.css" rel="stylesheet">
  <link href="<?=BE_ASET_HOST?>/sass/blue-theme.css" rel="stylesheet">
  <link href="<?=BE_ASET_HOST?>/sass/semi-dark.css" rel="stylesheet">
  <link href="<?=BE_ASET_HOST?>/sass/bordered-theme.css" rel="stylesheet">
  <link href="<?=BE_ASET_HOST?>/sass/responsive.css" rel="stylesheet">

</head>

<body>

<?php
require_once(BE_TEMPLATE_PATH."/_sidebar".EXT);
require_once(BE_TEMPLATE_PATH."/_header".EXT);
?>

  <!--start main wrapper-->
  <main class="main-wrapper">
	<?php
	$dfile_template_path = BE_TEMPLATE_PATH."/".$this->pageLevel1."/".$this->pageLevel1.'-'.$this->pageName.EXT;
	if(file_exists($dfile_template_path)){
		require_once($dfile_template_path);
	}else{
		if(APP_MODE=="dev") {
			$_SESSION['404'] = $dfile_template_path;
		}
		require_once(BE_TEMPLATE_PATH."/404".EXT);
	}
	?>
  </main>
  <!--end main wrapper-->

  <!--bootstrap js-->
  <script src="<?=BE_ASET_HOST?>/assets/js/bootstrap.bundle.min.js"></script>

  <!--plugins-->
  <script src="<?=ASET_HOST;?>/both/jquery/jquery.min.js"></script>
  <!--plugins-->
  <script src="<?=BE_ASET_HOST?>/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
  <script src="<?=BE_ASET_HOST?>/assets/plugins/metismenu/metisMenu.min.js"></script>
  <script src="<?=BE_ASET_HOST?>/assets/plugins/simplebar/js/simplebar.min.js"></script>
  <script src="<?=BE_ASET_HOST?>/assets/plugins/apexchart/apexcharts.min.js"></script>
  <script src="<?=BE_ASET_HOST?>/assets/plugins/simplebar/js/simplebar.min.js"></script>
  <script src="<?=BE_ASET_HOST?>/assets/plugins/peity/jquery.peity.min.js"></script>
  <script src="<?=BE_ASET_HOST?>/assets/js/main.js"></script>
  
  <?=$this->pageJS;?>
</body>

</html>