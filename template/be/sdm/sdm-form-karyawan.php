<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10"><?= $this->pageTitle ?></h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BE_MAIN_HOST ?>/sdm/daftar-karyawan">Daftar Karyawan</a></li>
            <li class="breadcrumb-item"><?= $mode == 'add' ? 'Tambah' : 'Edit' ?></li>
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
            <i class="feather-alert-circle me-2"></i>
            <div><?= $_SESSION['notif_error'] ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['notif_error']); ?>
    <?php endif; ?>

    <!-- Info Khusus Jika Edit Data Sendiri -->
    <?php if (isset($is_editing_self) && $is_editing_self): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="feather-info me-2"></i>
            <strong>Info:</strong> Anda sedang mengedit data profil Anda sendiri. Beberapa field tidak dapat diubah (NIK, Entitas, Level, Status). Untuk mengubah password, isi field "Password Baru" di bawah.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card stretch stretch-full">
        <div class="card-header">
            <h5 class="card-title"><?= $mode == 'add' ? 'Tambah Karyawan Baru' : 'Edit Data Karyawan' ?></h5>
        </div>
        <div class="card-body">
            <form method="post">

                <!-- NIK Lama (Read-only saat edit) -->
                <?php if ($mode == 'edit'): ?>
                    <div class="form-group row mb-3">
                        <label class="col-sm-2 col-form-label">NIK <span class="text-danger">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="nik_lama"
                                value="<?= htmlspecialchars($dataMember->member_nip ?? '', ENT_QUOTES) ?>" readonly>
                            <small class="text-muted">NIK tidak dapat diubah langsung. Gunakan field "NIK Baru" jika ingin mengganti NIK.</small>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="form-group row mb-3">
                        <label class="col-sm-2 col-form-label">NIK <span class="text-danger">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="nik_lama" placeholder="Masukkan NIK" required>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- NIK Baru (Hanya saat edit dan bukan edit sendiri) -->
                <?php if ($mode == 'edit' && !$is_editing_self): ?>
                    <div class="form-group row mb-3">
                        <label class="col-sm-2 col-form-label">NIK Baru <small>(opsional)</small></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="nik_baru" placeholder="Kosongkan jika tidak ingin mengganti NIK">
                            <small class="text-muted">Isi field ini hanya jika ingin mengganti NIK karyawan.</small>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Nama Karyawan -->
                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label">Nama Karyawan <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="nama"
                            value="<?= htmlspecialchars($dataMember->member_name ?? '', ENT_QUOTES) ?>"
                            placeholder="Masukkan nama lengkap" required>
                    </div>
                </div>

                <!-- Entitas (Disabled jika edit sendiri) -->
                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label">Entitas <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <select class="form-control" name="group_id" <?= $is_editing_self ? 'disabled' : '' ?> required>
                            <option value="">-- Pilih Entitas --</option>
                            <?php foreach ($listGroup as $lg): ?>
                                <option value="<?= $lg->group_id ?>"
                                    <?= (isset($dataMember) && $dataMember->group_id == $lg->group_id) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($lg->group_name, ENT_QUOTES) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if ($is_editing_self): ?>
                            <small class="text-muted">Entitas tidak dapat diubah sendiri.</small>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Level Karyawan (Disabled jika edit sendiri) -->
                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label">Level Karyawan <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <select class="form-control" name="level_id" <?= $is_editing_self ? 'disabled' : '' ?> required>
                            <option value="">-- Pilih Level --</option>
                            <?php foreach ($listLevel as $lv): ?>
                                <option value="<?= $lv->id ?>"
                                    <?= (isset($dataMember) && $dataMember->id_level_karyawan == $lv->id) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($lv->nama, ENT_QUOTES) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if ($is_editing_self): ?>
                            <small class="text-muted">Level karyawan tidak dapat diubah sendiri.</small>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Status (Disabled jika edit sendiri) -->
                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label">Status <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <select class="form-control" name="status" <?= $is_editing_self ? 'disabled' : '' ?> required>
                            <option value="active" <?= (isset($dataMember) && $dataMember->member_status == 'active') ? 'selected' : '' ?>>Aktif</option>
                            <option value="block" <?= (isset($dataMember) && $dataMember->member_status == 'block') ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                        <?php if ($is_editing_self): ?>
                            <small class="text-muted">Status tidak dapat diubah sendiri.</small>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($mode == 'edit'): ?>
                    <?php
                    $getGroup = $akses->doQuery(
                        "SELECT aghris_company_code FROM _group WHERE group_id = " . (int)$dataMember->group_id . " LIMIT 1",
                        0,
                        'object'
                    );
                    $is_aghris = (isset($getGroup[0]) && !empty($getGroup[0]->aghris_company_code));
                    ?>

                    <!-- Password Baru -->
                    <div class="form-group row mb-3">
                        <label class="col-sm-2 col-form-label">Password Baru <small>(opsional)</small></label>
                        <div class="col-sm-6">
                            <?php if ($is_aghris): ?>
                                <input type="password" class="form-control" disabled placeholder="Password tidak bisa diubah (Akun AGHRIS)">
                                <small class="text-danger">
                                    <i class="feather-alert-triangle me-1"></i>
                                    Akun ini terhubung dengan AGHRIS. Password hanya bisa diubah melalui aplikasi AGHRIS.
                                </small>
                            <?php else: ?>
                                <input type="password" class="form-control" name="new_password" id="newPassword"
                                    placeholder="Kosongkan jika tidak ingin mengubah password">
                                <small class="text-muted">
                                    <i class="feather-info me-1"></i>
                                    <?= $is_editing_self ? 'Isi jika ingin mengubah password Anda. Minimal 6 karakter.' : 'Isi jika ingin mengubah password karyawan. Kosongkan jika tidak ingin mengubah.' ?>
                                </small>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Button Group -->
                <div class="form-group row">
                    <div class="col-sm-offset-2 col-sm-10">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" style="width: 150px;">
                                <i class="feather-save me-2"></i><?= $mode == 'add' ? 'Simpan' : 'Update' ?>
                            </button>
                            <a href="<?= BE_MAIN_HOST ?>/sdm/daftar-karyawan" class="btn btn-secondary" style="width: 120px;">
                                <i class="feather-arrow-left me-2"></i>Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>