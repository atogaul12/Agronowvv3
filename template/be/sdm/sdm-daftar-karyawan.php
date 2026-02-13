<?php
// agronow/agronow_insight/template/be/sdm/daftar-karyawan.php
?>

<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10"><?= $this->pageTitle ?></h5>
        </div>
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

    <!-- Card Filter -->
    <div class="card stretch stretch-full">
        <div class="card-header">
            <h4 class="card-title">Filter Data</h4>
        </div>
        <div class="card-body">
            <form method="post" action="<?= BE_MAIN_HOST ?>/sdm/daftar-karyawan" id="filterForm">

                <!-- NIK -->
                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label">NIK</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="nik" value="<?= htmlspecialchars($nik ?? '', ENT_QUOTES) ?>" placeholder="Cari berdasarkan NIK">
                    </div>
                </div>

                <!-- Nama -->
                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label">Nama Karyawan</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="nama" value="<?= htmlspecialchars($nama ?? '', ENT_QUOTES) ?>" placeholder="Cari berdasarkan nama">
                    </div>
                </div>

                <!-- Entitas -->
                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label">Entitas</label>
                    <div class="col-sm-4">
                        <select class="form-control" name="group_id">
                            <option value="">-- Semua Entitas --</option>
                            <?php foreach ($listGroup as $lg): ?>
                                <option value="<?= htmlspecialchars($lg->group_id, ENT_QUOTES) ?>"
                                    <?= ($group_id == $lg->group_id) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($lg->group_name, ENT_QUOTES) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Status Karyawan -->
                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label">Status</label>
                    <div class="col-sm-4">
                        <select class="form-control" name="status">
                            <option value="">-- Semua Status --</option>
                            <option value="active" <?= ($status == 'active') ? 'selected' : '' ?>>Aktif</option>
                            <option value="block" <?= ($status == 'block') ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                    </div>
                </div>

                <!-- Button Group -->
                <div class="form-group row mb-3">
                    <div class="col-sm-offset-2 col-sm-10">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" style="width: 120px;">
                                <i class="feather-search me-2"></i>Cari
                            </button>
                            <a href="<?= BE_MAIN_HOST ?>/sdm/daftar-karyawan?reset=1" class="btn btn-secondary" style="width: 120px;">
                                <i class="feather-x me-2"></i>Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tab Navigation -->
    <ul class="nav nav-tabs mt-3" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="data-tab" data-bs-toggle="tab" data-bs-target="#data" type="button" role="tab">
                <i class="feather-list me-2"></i>Daftar Data
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="download-tab" data-bs-toggle="tab" data-bs-target="#download" type="button" role="tab">
                <i class="feather-download me-2"></i>Download Excel
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="myTabContent">
        <!-- Tab Daftar Data -->
        <div class="tab-pane fade show active" id="data" role="tabpanel">
            <div class="card stretch stretch-full" style="border-top-left-radius:0">
                <div class="card-header">
                    <h5 class="card-title">Daftar Karyawan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-center" style="width: 50px;">No</th>
                                    <th class="text-center">NIK</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Level</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Entitas</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Telepon</th>
                                    <th class="text-center" style="width: 100px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($data)) {
                                    $i = $arrPage['num'];
                                    foreach ($data as $row) {
                                        $i++;
                                        $status_badge = ($row->member_status == 'active')
                                            ? '<span class="badge bg-success">Aktif</span>'
                                            : '<span class="badge bg-danger">Nonaktif</span>';
                                ?>
                                        <tr>
                                            <td class="text-center"><?= $i ?></td>
                                            <td><?= htmlspecialchars($row->member_nip, ENT_QUOTES) ?></td>
                                            <td><?= htmlspecialchars($row->member_name, ENT_QUOTES) ?></td>
                                            <td class="text-center"><?= htmlspecialchars($row->level_karyawan ?? '-', ENT_QUOTES) ?></td>
                                            <td class="text-center"><?= $status_badge ?></td>
                                            <td><?= htmlspecialchars($row->group_name, ENT_QUOTES) ?></td>
                                            <td><?= htmlspecialchars($row->member_email ?? '-', ENT_QUOTES) ?></td>
                                            <td><?= htmlspecialchars($row->member_phone ?? '-', ENT_QUOTES) ?></td>
                                            <td class="text-center">
                                                <!-- Bootstrap Dropdown -->
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        Aksi
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="<?= BE_MAIN_HOST ?>/sdm/form-karyawan?id=<?= $row->member_id ?>">
                                                                <i class="feather-edit-2 me-2"></i>Edit Data
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="<?= BE_MAIN_HOST ?>/sdm/update-password?id=<?= $row->member_id ?>">
                                                                <i class="feather-key me-2"></i>Update Password
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="<?= BE_MAIN_HOST ?>/sdm/reset-password?id=<?= $row->member_id ?>">
                                                                <i class="feather-refresh-cw me-2"></i>Reset Password
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="<?= BE_MAIN_HOST ?>/ikhtisar-biodata/biodata-peserta?nik=<?= urlencode($row->member_nip) ?>">
                                                                <i class="feather-book-open me-2"></i>Lihat Ikhtisar Pelatihan
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">
                                            <i class="feather-inbox me-2"></i>Data tidak ditemukan.
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <?= $arrPage['bar'] ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Download Excel -->
        <div class="tab-pane fade" id="download" role="tabpanel">
            <div class="card stretch stretch-full" style="border-top-left-radius:0">
                <div class="card-header">
                    <h5 class="card-title">Download Data Excel</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        <h6 class="alert-heading"><strong>📋 Informasi Penting:</strong></h6>
                        <p class="mb-2">Ikuti langkah-langkah berikut untuk mengunduh dan membuka file Excel dengan benar:</p>
                        <ol class="mb-0">
                            <li>Klik tombol <strong>"Download Excel"</strong> di bawah untuk mengunduh file.</li>
                            <li>Jika Anda mendapatkan notifikasi format file saat membuka, ikuti langkah selanjutnya.</li>
                        </ol>
                    </div>

                    <div class="alert alert-danger mb-3 p-0" style="border: none; box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4); background-color: #f8d7da;">
                        <img src="/frameworkV2/media/aset/Notifikasi.png" alt="Notifikasi Format Excel" style="width: 100%; height: auto; display: block;">
                    </div>

                    <div class="alert alert-info mb-3">
                        <p class="mb-2"><strong>✅ Cara mengatasi notifikasi tersebut:</strong></p>
                        <ol class="mb-0">
                            <li>Klik tombol <strong>"Yes"</strong> untuk melanjutkan membuka file.</li>
                            <li>Setelah file terbuka, lakukan <strong>Save As</strong> dengan format Excel terbaru (.xlsx).</li>
                            <li>Simpan file dengan nama yang Anda inginkan.</li>
                        </ol>
                    </div>

                    <div class="alert alert-info mb-3">
                        <p class="mb-0">
                            <strong>💡 Tips:</strong> Gunakan format .xlsx (Excel terbaru) untuk menghilangkan notifikasi ini di masa depan.
                        </p>
                    </div>

                    <p class="text-muted mb-3"><strong>Catatan:</strong> File Excel akan diunduh dengan semua filter yang telah Anda terapkan.</p>

                    <a href="<?= BE_MAIN_HOST ?>/sdm/daftar-karyawan?export=excel" class="btn btn-primary btn-block w-100">
                        <i class="feather-download me-2"></i>Download Excel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>