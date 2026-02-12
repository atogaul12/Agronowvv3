<?php
//frameworkv2/template/be/sdm/update-password.php
?>

<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10"><?= $this->pageTitle ?></h5>
        </div>
    </div>
</div>

<div class="main-content">
    <!-- Notifikasi Error -->
    <?php if (isset($_SESSION['notif_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="feather-alert-circle me-2"></i><?= $_SESSION['notif_error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['notif_error']); ?>
    <?php endif; ?>

    <!-- Card Form -->
    <div class="card stretch stretch-full">
        <div class="card-header">
            <h4 class="card-title">Update Password Karyawan</h4>
        </div>
        <div class="card-body">
            <!-- Info Karyawan -->
            <div class="alert alert-info mb-4">
                <h6 class="alert-heading"><strong>Informasi Karyawan:</strong></h6>
                <div class="table-responsive">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td style="width: 150px;"><strong>NIK</strong></td>
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
            </div>

            <?php if ($is_aghris): ?>
                <!-- Peringatan AGHRIS -->
                <div class="alert alert-warning mb-4">
                    <h6 class="alert-heading"><strong><i class="feather-alert-triangle me-2"></i>Peringatan:</strong></h6>
                    <p class="mb-0">
                        Akun ini terhubung dengan <strong>AGHRIS</strong>, untuk reset password bisa dilakukan melalui aplikasi tersebut.
                    </p>
                </div>

                <a href="<?= BE_MAIN_HOST ?>/sdm/daftar-karyawan" class="btn btn-secondary" style="width: 120px;">
                    <i class="feather-arrow-left me-2"></i>Kembali
                </a>
            <?php else: ?>
                <!-- Form Update Password -->
                <form method="post" action="<?= BE_MAIN_HOST ?>/sdm/update-password?id=<?= $dataMember->member_id ?>" id="formUpdatePassword">

                    <!-- Password Baru -->
                    <div class="form-group row mb-3">
                        <label class="col-sm-2 col-form-label">Password Baru <span class="text-danger">*</span></label>
                        <div class="col-sm-6">
                            <input type="password" class="form-control" name="new_password" id="newPassword" placeholder="Masukkan password baru" required>
                            <small class="text-muted d-block mt-2">
                                <i class="feather-info me-1"></i>Minimal 6 karakter
                            </small>
                        </div>
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="form-group row mb-3">
                        <label class="col-sm-2 col-form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                        <div class="col-sm-6">
                            <input type="password" class="form-control" id="confirmPassword" placeholder="Ketik ulang password" required>
                            <small id="passwordMatch" class="text-muted d-block mt-2"></small>
                        </div>
                    </div>

                    <!-- Button Group -->
                    <div class="form-group row">
                        <div class="col-sm-offset-2 col-sm-10">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" id="btnSubmit" disabled style="width: 150px;">
                                    <i class="feather-save me-2"></i>Update
                                </button>
                                <a href="<?= BE_MAIN_HOST ?>/sdm/daftar-karyawan" class="btn btn-secondary" style="width: 120px;">
                                    <i class="feather-x me-2"></i>Batal
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Validasi password match
    document.addEventListener('DOMContentLoaded', function() {
        const newPassword = document.getElementById('newPassword');
        const confirmPassword = document.getElementById('confirmPassword');
        const passwordMatch = document.getElementById('passwordMatch');
        const btnSubmit = document.getElementById('btnSubmit');

        function checkPasswordMatch() {
            if (newPassword.value === '' || confirmPassword.value === '') {
                passwordMatch.textContent = '';
                passwordMatch.className = 'text-muted d-block mt-2';
                btnSubmit.disabled = true;
                return;
            }

            if (newPassword.value === confirmPassword.value) {
                if (newPassword.value.length >= 6) {
                    passwordMatch.innerHTML = '<i class="feather-check-circle me-1"></i>Password cocok';
                    passwordMatch.className = 'text-success d-block mt-2';
                    btnSubmit.disabled = false;
                } else {
                    passwordMatch.innerHTML = '<i class="feather-alert-circle me-1"></i>Password minimal 6 karakter';
                    passwordMatch.className = 'text-warning d-block mt-2';
                    btnSubmit.disabled = true;
                }
            } else {
                passwordMatch.innerHTML = '<i class="feather-x-circle me-1"></i>Password tidak cocok';
                passwordMatch.className = 'text-danger d-block mt-2';
                btnSubmit.disabled = true;
            }
        }

        newPassword.addEventListener('input', checkPasswordMatch);
        confirmPassword.addEventListener('input', checkPasswordMatch);

        // Validasi saat submit
        document.getElementById('formUpdatePassword')?.addEventListener('submit', function(e) {
            if (newPassword.value !== confirmPassword.value) {
                e.preventDefault();
                alert('Password tidak cocok! Silakan periksa kembali.');
                return false;
            }
            if (newPassword.value.length < 6) {
                e.preventDefault();
                alert('Password minimal 6 karakter!');
                return false;
            }
        });
    });
</script>