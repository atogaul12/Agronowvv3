<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10"><?= $this->pageTitle ?></h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BE_MAIN_HOST ?>/sdm/daftar-karyawan">Master Karyawan</a></li>
            <li class="breadcrumb-item active">Reset Password</li>
        </ul>
    </div>
</div>

<div class="main-content">
    <!-- Notifikasi -->
    <?php if (isset($_SESSION['notif_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="feather-check-circle me-2"></i><?= $_SESSION['notif_success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['notif_success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['notif_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="feather-alert-circle me-2"></i><?= $_SESSION['notif_error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['notif_error']); ?>
    <?php endif; ?>

    <div class="card stretch stretch-full">
        <div class="card-header">
            <h5 class="card-title">Reset Password Karyawan</h5>
        </div>
        <div class="card-body">
            <!-- Info Karyawan -->
            <div class="alert alert-info mb-4">
                <h6 class="alert-heading"><i class="feather-user me-2"></i>Informasi Karyawan</h6>
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td width="150"><strong>NIK</strong></td>
                        <td>: <?= htmlspecialchars($dataMember->member_nip, ENT_QUOTES) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Nama</strong></td>
                        <td>: <?= htmlspecialchars($dataMember->member_name, ENT_QUOTES) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Entitas</strong></td>
                        <td>: <?= htmlspecialchars($dataMember->group_name, ENT_QUOTES) ?></td>
                    </tr>
                </table>
            </div>

            <?php if ($is_aghris): ?>
                <!-- Warning untuk akun AGHRIS -->
                <div class="alert alert-warning">
                    <h6 class="alert-heading"><i class="feather-alert-triangle me-2"></i>Akun Terhubung dengan AGHRIS</h6>
                    <p class="mb-0">Akun ini terhubung dengan sistem AGHRIS. Reset password hanya bisa dilakukan melalui aplikasi AGHRIS. Silakan hubungi administrator AGHRIS untuk bantuan lebih lanjut.</p>
                </div>

                <div class="d-flex gap-2">
                    <a href="<?= BE_MAIN_HOST ?>/sdm/daftar-karyawan" class="btn btn-secondary">
                        <i class="feather-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            <?php else: ?>
                <!-- Form Reset Password -->
                <form method="post" onsubmit="return confirm('Apakah Anda yakin ingin mereset password ke NIK?');">
                    <input type="hidden" name="confirm_reset" value="1">

                    <div class="alert alert-warning">
                        <h6 class="alert-heading"><i class="feather-info me-2"></i>Informasi Reset Password</h6>
                        <ul class="mb-0">
                            <li>Password akan direset ke <strong>NIK karyawan</strong></li>
                            <li>Password baru: <strong><?= htmlspecialchars($dataMember->member_nip, ENT_QUOTES) ?></strong></li>
                            <li>Karyawan dapat login menggunakan NIK sebagai password</li>
                            <li>Disarankan untuk mengganti password setelah login</li>
                        </ul>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-danger" style="width: 150px;">
                            <i class="feather-refresh-cw me-2"></i>Reset Password
                        </button>
                        <a href="<?= BE_MAIN_HOST ?>/sdm/daftar-karyawan" class="btn btn-secondary" style="width: 120px;">
                            <i class="feather-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>