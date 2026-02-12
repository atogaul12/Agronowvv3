<!--start sidebar-->
<aside class="sidebar-wrapper" data-simplebar="true">
  <div class="sidebar-header">
    <div class="logo-icon">
      <img src="<?= MEDIA_HOST ?>/aset/logo.png" class="logo-img" alt="">
    </div>
    <div class="logo-name flex-grow-1">
      <h5 class="mb-0">AgroNow 3.0</h5>
    </div>
    <div class="sidebar-close">
      <span class="material-icons-outlined">close</span>
    </div>
  </div>
  <div class="sidebar-nav">
    <!--navigation-->
    <ul class="metismenu" id="sidenav">
      <li>
        <a href="<?= BE_MAIN_HOST ?>">
          <div class="parent-icon"><i class="material-icons-outlined">home</i>
          </div>
          <div class="menu-title">Beranda</div>
        </a>
      </li>
      <li>
        <a href="javascript:;" class="has-arrow">
          <div class="parent-icon"><i class="material-icons-outlined">monitor</i></div>
          <div class="menu-title">Dashboard</div>
        </a>
        <ul>
          <li class="<?= $akses->setupCSSSidebar('DEV_UNCATEGORIES_YET') ?>"><a href="<?= BE_MAIN_HOST ?>/dashboard/insight_jpl"><i class="material-icons-outlined">arrow_right</i>Rekapitulasi JPL</a></li>
          <li class="<?= $akses->setupCSSSidebar('DEV_UNCATEGORIES_YET') ?>"><a href="<?= BE_MAIN_HOST ?>/dashboard/review_lap_learning"><i class="material-icons-outlined">arrow_right</i>Review Laporan Learning</a></li>
        </ul>
      </li>

      <!-- MENU BARU: Ikhtisar Data Pelatihan -->
      <li>
        <a href="javascript:;" class="has-arrow">
          <div class="parent-icon"><i class="material-icons-outlined">assessment</i></div>
          <div class="menu-title">Ikhtisar Data Pelatihan</div>
        </a>
        <ul>
          <li class="<?= $akses->setupCSSSidebar('BIODATA_PESERTA') ?>"><a href="<?= BE_MAIN_HOST ?>/ikhtisar-biodata/biodata-peserta"><i class="material-icons-outlined">arrow_right</i>Biodata Peserta</a></li>
          <li class="<?= $akses->setupCSSSidebar('NILAI_PESERTA') ?>"><a href="<?= BE_MAIN_HOST ?>/ikhtisar-nilai/peserta"><i class="material-icons-outlined">arrow_right</i>Nilai Peserta</a></li>
        </ul>
      </li>

      <!-- MENU BARU: Manajemen Karyawan -->
      <li>
        <a href="javascript:;" class="has-arrow">
          <div class="parent-icon"><i class="material-icons-outlined">people</i></div>
          <div class="menu-title">Manajemen Karyawan</div>
        </a>
        <ul>
          <li><a href="<?= BE_MAIN_HOST ?>/sdm/daftar-karyawan"><i class="material-icons-outlined">arrow_right</i>Daftar Karyawan</a></li>
          <li><a href="<?= BE_MAIN_HOST ?>/sdm/form-karyawan"><i class="material-icons-outlined">arrow_right</i>Tambah Karyawan</a></li>
          <li><a href="<?= BE_MAIN_HOST ?>/sdm/upload-massal"><i class="material-icons-outlined">arrow_right</i>Upload Massal</a></li>
        </ul>
      </li>

      <li>
        <a href="javascript:;" class="has-arrow">
          <div class="parent-icon"><i class="material-icons-outlined">person</i></div>
          <div class="menu-title">Menu Pribadi</div>
        </a>
        <ul>
          <li class="<?= $akses->setupCSSSidebar('LAINLAIN_UPDATE_PASSWORD_SELF') ?>"><a href="<?= BE_MAIN_HOST ?>/user/update_password"><i class="material-icons-outlined">arrow_right</i>Update Password</a></li>
        </ul>
      </li>
      <li>
        <a href="javascript:;" class="has-arrow">
          <div class="parent-icon"><i class="material-icons-outlined">settings</i></div>
          <div class="menu-title">Control Panel</div>
        </a>
        <ul>
          <li class="<?= $akses->setupCSSSidebar('DEV_TOOLKIT') ?>"><a href="<?= BE_MAIN_HOST ?>/dev/hak_akses"><i class="material-icons-outlined">arrow_right</i>Master Hak Akses</a></li>
          <li class="<?= $akses->setupCSSSidebar('CONTROL_PANEL_LOG') ?>"><a href="<?= BE_MAIN_HOST ?>/controlpanel/log"><i class="material-icons-outlined">arrow_right</i>Manajemen Log</a></li>
        </ul>
      </li>
      <li>
        <a href="javascript:;" class="has-arrow <?= $akses->setupCSSSidebar('DEV_TOOLKIT') ?>">
          <div class="parent-icon"><i class="material-icons-outlined">logo_dev</i></div>
          <div class="menu-title">Dev Only</div>
        </a>
        <ul>
          <li class="<?= $akses->setupCSSSidebar('DEV_TOOLKIT') ?>"><a href="<?= BE_MAIN_HOST ?>/dev/blank"><i class="material-icons-outlined">arrow_right</i>Contoh Blank Page</a></li>
          <li class="<?= $akses->setupCSSSidebar('DEV_TOOLKIT') ?>"><a href="<?= BE_MAIN_HOST ?>/dev/chart"><i class="material-icons-outlined">arrow_right</i>Contoh Chart (1)</a></li>
          <li class="<?= $akses->setupCSSSidebar('DEV_TOOLKIT') ?>"><a href="<?= BE_MAIN_HOST ?>/dev/chart2"><i class="material-icons-outlined">arrow_right</i>Contoh Chart (2)</a></li>
          <li class="<?= $akses->setupCSSSidebar('DEV_TOOLKIT') ?>"><a href="<?= BE_MAIN_HOST ?>/user/panduan"><i class="material-icons-outlined">arrow_right</i>Contoh Halaman yang Ga Perlu Login</a></li>
        </ul>
      </li>
    </ul>
    <!--end navigation-->
  </div>
</aside>
<!--end sidebar-->