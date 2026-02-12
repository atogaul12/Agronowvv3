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
      <!--bootstrap css-->
      <link href="<?=BE_ASET_HOST?>/assets/css/bootstrap.min.css" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">
      <!--main css-->
      <link href="<?=BE_ASET_HOST?>/assets/css/bootstrap-extended.css" rel="stylesheet">
      <link href="<?=BE_ASET_HOST?>/sass/main.css" rel="stylesheet">
      <link href="<?=BE_ASET_HOST?>/sass/dark-theme.css" rel="stylesheet">
      <link href="<?=BE_ASET_HOST?>/sass/blue-theme.css" rel="stylesheet">
      <link href="<?=BE_ASET_HOST?>/sass/responsive.css" rel="stylesheet">
   </head>
   <body>
      <div class="auth-basic-wrapper d-flex align-items-center justify-content-center">
         <div class="container-fluid my-5 my-lg-0">
            <div class="row">
               <div class="col-12 col-md-8 col-lg-6 col-xl-5 col-xxl-4 mx-auto">
                  <div class="card rounded-4 mb-0 border-top border-4 border-primary ">
                     <div class="card-body p-5">
                        <img src="<?=MEDIA_HOST?>/aset/logo.png" class="mb-4" width="50" alt="">
                        <?=$umum->sessionInfo()?>
                        <div class="form-body my-3">
                           Silahkan login dengan menggunakan password AgroNow (password AGHRIS untuk saat ini belum diimplementasikan)
                        </div>
                        <div class="mt-5">
                           <a href="<?=BE_MAIN_HOST?>/user/login" class="btn btn-primary w-100">kembali</a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!--plugins-->
      <script src="<?=BE_ASET_HOST?>/assets/js/jquery.min.js"></script>  
   </body>
</html>