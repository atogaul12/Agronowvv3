<?php
//frameworkv2/template/be/sdm/form-karyawan.php
$mode = (isset($dataMember)) ? 'edit' : 'add';
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
            <h4 class="card-title"><?= ($mode == 'add') ? 'Tambah' : 'Edit' ?> Data Karyawan</h4>
        </div>
        <div class="card-body">
            <form method="post" action="<?= BE_MAIN_HOST ?>/sdm/form-karyawan<?= ($mode == 'edit') ? '?id=' . $dataMember->member_id : '' ?>" id="formKaryawan">

                <!-- NIK Lama (Hidden untuk Edit) -->
                <?php if ($mode == 'edit'): ?>
                    <input type="hidden" name="nik_lama" value="<?= htmlspecialchars($dataMember->member_nip, ENT_QUOTES) ?>">
                <?php endif; ?>

                <!-- NIK -->
                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label">NIK <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <?php if ($mode == 'add'): ?>
                            <input type="text" class="form-control" name="nik_lama" placeholder="Masukkan NIK (akan menjadi password)" required>
                            <small class="text-muted d-block mt-2">Password akan otomatis sama dengan NIK yang diinput</small>
                        <?php else: ?>
                            <div class="input-group">
                                <input type="text" class="form-control" value="<?= htmlspecialchars($dataMember->member_nip, ENT_QUOTES) ?>" disabled>
                                <span class="input-group-text">→</span>
                                <input type="text" class="form-control" name="nik_baru" placeholder="NIK baru (opsional)">
                            </div>
                            <small class="text-muted d-block mt-2">Kosongkan field kanan jika tidak ingin mengubah NIK</small>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Nama Karyawan -->
                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label">Nama Karyawan <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="nama" placeholder="Masukkan nama lengkap"
                            value="<?= htmlspecialchars($dataMember->member_name ?? '', ENT_QUOTES) ?>" required>
                    </div>
                </div>

                <!-- Entitas -->
                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label">Entitas <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <select class="form-control" name="group_id" required>
                            <option value="">-- Pilih Entitas --</option>
                            <?php foreach ($listGroup as $lg): ?>
                                <option value="<?= htmlspecialchars($lg->group_id, ENT_QUOTES) ?>"
                                    <?= (isset($dataMember) && $dataMember->group_id == $lg->group_id) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($lg->group_name, ENT_QUOTES) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Level Karyawan -->
                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label">Level Karyawan <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <select class="form-control" name="level_id" required>
                            <option value="">-- Pilih Level --</option>
                            <?php foreach ($listLevel as $ll): ?>
                                <option value="<?= htmlspecialchars($ll->id, ENT_QUOTES) ?>"
                                    <?= (isset($dataMember) && $dataMember->id_level_karyawan == $ll->id) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($ll->nama, ENT_QUOTES) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Status -->
                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label">Status <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <select class="form-control" name="status" required>
                            <option value="active" <?= (isset($dataMember) && $dataMember->member_status == 'active') ? 'selected' : (($mode == 'add') ? 'selected' : '') ?>>
                                Aktif
                            </option>
                            <option value="block" <?= (isset($dataMember) && $dataMember->member_status == 'block') ? 'selected' : '' ?>>
                                Nonaktif
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Password (HANYA UNTUK NON-AGHRIS - EDIT ONLY) -->
                <?php
                $is_aghris = false;
                if ($mode == 'edit' && isset($dataMember)) {
                    $group_info = $akses->doQuery(
                        "SELECT aghris_company_code FROM _group WHERE group_id = '" . $dataMember->group_id . "' LIMIT 1",
                        0,
                        'object'
                    );
                    if (!empty($group_info) && !empty($group_info[0]->aghris_company_code)) {
                        $is_aghris = true;
                    }
                }
                ?>

                <!-- Hanya tampilkan password field untuk NON-AGHRIS saat EDIT -->
                <?php if (!$is_aghris && $mode == 'edit'): ?>
                    <div class="form-group row mb-3">
                        <label class="col-sm-2 col-form-label">Update Password</label>
                        <div class="col-sm-6">
                            <input type="password" class="form-control" name="new_password" placeholder="Masukkan password baru">
                            <small class="text-muted d-block mt-2">
                                <i class="feather-info me-1"></i>Kosongkan untuk mempertahankan password lama
                            </small>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Button Group -->
                <div class="form-group row">
                    <div class="col-sm-offset-2 col-sm-10">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" style="width: 150px;">
                                <i class="feather-save me-2"></i>Simpan
                            </button>
                            <a href="<?= BE_MAIN_HOST ?>/sdm/daftar-karyawan" class="btn btn-secondary" style="width: 120px;">
                                <i class="feather-x me-2"></i>Batal
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>