<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10"><?= htmlspecialchars($this->pageTitle ?? '', ENT_QUOTES) ?></h5>
        </div>
    </div>
</div>

<div class="main-content">
    <!-- Card Filter -->
    <div class="card stretch stretch-full">
        <div class="card-header">
            <h4 class="card-title">Filter data</h4>
        </div>
        <div class="card-body">
            <form method="get" action="<?= htmlspecialchars($targetpage ?? '', ENT_QUOTES) ?>" id="filterForm">

                <!-- Judul Pelatihan --->
                <div class="form-group row mb-2">
                    <label class="col-sm-2 col-form-label">Judul Pelatihan</label>
                    <div class="col-sm-5">
                        <div style="position: relative;">
                            <input type="text"
                                class="form-control"
                                id="pelatihanSearch"
                                placeholder="Ketik judul pelatihan">

                            <input type="hidden"
                                name="cr_id"
                                id="cr_id"
                                value="<?= htmlspecialchars($cr_id ?? '', ENT_QUOTES) ?>">

                            <div id="pelatihanResult"
                                style="position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #dee2e6; border-top: none; border-radius: 0 0 0.375rem 0.375rem; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); max-height: 300px; overflow-y: auto; z-index: 1000; display: none; font-size: 0.875rem;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- NIK -->
                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label">NIK</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="nik"
                            value="<?= htmlspecialchars($nik, ENT_QUOTES) ?>"
                            placeholder="Masukkan NIK">
                    </div>
                </div>

                <!-- Periode -->
                <div class="form-group row mb-2">
                    <label class="col-sm-2 col-form-label">Periode</label>
                    <div class="col-sm-5">
                        <div class="input-group">
                            <input type="date" class="form-control" name="tgl_awal" value="<?= htmlspecialchars($tgl_awal ?? '', ENT_QUOTES) ?>">
                            <span class="input-group-text" style="min-width:50px; justify-content:center;">s/d</span>
                            <input type="date" class="form-control" name="tgl_akhir" value="<?= htmlspecialchars($tgl_akhir ?? '', ENT_QUOTES) ?>">
                        </div>
                    </div>
                </div>

                <!-- Kategori -->
                <div class="form-group row mb-2">
                    <label class="col-sm-2 col-form-label">Kategori</label>
                    <div class="col-sm-5">
                        <select class="form-control" name="cat_id" id="catSelect">
                            <option value="">-- Semua Kategori --</option>
                            <?php if (!empty($listKategori)): ?>
                                <?php foreach ($listKategori as $lk): ?>
                                    <option value="<?= htmlspecialchars($lk->cat_id, ENT_QUOTES) ?>"
                                        <?= ($cat_id === $lk->cat_id) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($lk->cat_name, ENT_QUOTES) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <!-- Entitas Saat Ini -->
                <div class="form-group row mb-2">
                    <label class="col-sm-2 col-form-label">Entitas Saat Ini</label>
                    <div class="col-sm-5">
                        <select class="form-control" name="entitas_saat_ini" id="entitasSekarangSelect">
                            <option value="">-- Semua Entitas --</option>
                            <?php if (!empty($listGroup)): ?>
                                <?php foreach ($listGroup as $lg): ?>
                                    <option value="<?= htmlspecialchars($lg->group_id, ENT_QUOTES) ?>"
                                        <?= ($entitas_saat_ini === $lg->group_id) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($lg->group_name, ENT_QUOTES) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <!-- Entitas Saat Ikut Pelatihan -->
                <div class="form-group row mb-2">
                    <label class="col-sm-2 col-form-label">Entitas Saat Ikut Pelatihan</label>
                    <div class="col-sm-5">
                        <select class="form-control" name="entitas_saat_ikut" id="entitasPelatihanSelect">
                            <option value="">-- Semua Entitas --</option>
                            <?php if (!empty($listGroup)): ?>
                                <?php foreach ($listGroup as $lg): ?>
                                    <option value="<?= htmlspecialchars($lg->group_id, ENT_QUOTES) ?>"
                                        <?= ($entitas_saat_ikut === $lg->group_id) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($lg->group_name, ENT_QUOTES) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <!-- Button Group -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="feather-search me-2"></i>Cari
                    </button>
                    <a href="<?= htmlspecialchars(BE_MAIN_HOST, ENT_QUOTES) ?>/ikhtisar-nilai/peserta"
                        class="btn btn-secondary px-4">
                        <i class="feather-x me-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div id="resultContainer">
        <!-- Tab Navigation -->
        <ul class="nav nav-tabs mt-3" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="data-tab" data-bs-toggle="tab" data-bs-target="#data"
                    type="button" role="tab" aria-controls="data" aria-selected="true">
                    <i class="feather-list me-2"></i>Daftar Data
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="excel-tab" data-bs-toggle="tab" data-bs-target="#excel"
                    type="button" role="tab" aria-controls="excel" aria-selected="false">
                    <i class="feather-download me-2"></i>Download Excel
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="myTabContent">
            <!-- Tab Daftar Data -->
            <div class="tab-pane fade show active" id="data" role="tabpanel" aria-labelledby="data-tab">
                <div class="card stretch stretch-full" style="border-top-left-radius:0">
                    <div class="card-header">
                        <h5 class="card-title">Daftar Nilai Peserta</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="dataTable">
                                <thead>
                                    <tr class="border-b">
                                        <th style="width: 50px;"><b>No</b></th>
                                        <th><b>Judul Pelatihan</b></th>
                                        <th><b>Tanggal Mulai</b></th>
                                        <th><b>Tanggal Selesai</b></th>
                                        <th><b>NIK</b></th>
                                        <th><b>Nama</b></th>
                                        <th style="white-space:nowrap;"><b>Entitas Saat Ini</b></th>
                                        <th style="white-space:nowrap;"><b>Entitas Saat Ikut Pelatihan</b></th>
                                        <th style="white-space:nowrap;"><b>Status Pre Test</b></th>
                                        <th style="white-space:nowrap;"><b>Nilai Pre Test</b></th>
                                        <th style="white-space:nowrap;"><b>Status Post Test</b></th>
                                        <th style="white-space:nowrap;"><b>Nilai Post Test</b></th>
                                        <th><b>Sertifikat</b></th>
                                        <th style="white-space:nowrap;"><b>Berkas Sertifikat</b></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data)): ?>
                                        <?php
                                        $no = $arrPage['num'] ?? 0;
                                        foreach ($data as $row):
                                            $no++;
                                        ?>
                                            <tr>
                                                <td><strong><?= $no ?></strong></td>
                                                <td><?= htmlspecialchars($row->judul_pelatihan ?? '', ENT_QUOTES) ?></td>
                                                <td><?= $umum->date_indo($row->tanggal_mulai ?? '', "dd FF YYYY") ?></td>
                                                <td><?= $umum->date_indo($row->tanggal_selesai ?? '', "dd FF YYYY") ?></td>
                                                <td><?= htmlspecialchars($row->nik ?? '', ENT_QUOTES) ?></td>
                                                <td><?= htmlspecialchars($row->nama ?? '', ENT_QUOTES) ?></td>
                                                <td><?= htmlspecialchars($row->entitas_saat_ini ?? '', ENT_QUOTES) ?></td>
                                                <td><?= htmlspecialchars($row->entitas_saat_ikut_pelatihan ?? '', ENT_QUOTES) ?></td>
                                                <td>
                                                    <span class="badge <?= ($row->status_pre_test ?? '') === '1' ? 'bg-success' : 'bg-danger' ?>">
                                                        <?= ($row->status_pre_test ?? '') === '1' ? 'Ada' : 'Tidak' ?>
                                                    </span>
                                                </td>
                                                <td><?= htmlspecialchars($row->nilai_pre_test ?? '-', ENT_QUOTES) ?></td>
                                                <td>
                                                    <span class="badge <?= ($row->status_post_test ?? '') === '1' ? 'bg-success' : 'bg-danger' ?>">
                                                        <?= ($row->status_post_test ?? '') === '1' ? 'Ya' : 'Tidak' ?>
                                                    </span>
                                                </td>
                                                <td><?= htmlspecialchars($row->nilai_post_test ?? '-', ENT_QUOTES) ?></td>
                                                <td>
                                                    <span class="badge <?= ($row->sertifikat ?? '') === '1' ? 'bg-success' : 'bg-danger' ?>">
                                                        <?= ($row->sertifikat ?? '') === '1' ? 'Ya' : 'Tidak' ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if (!empty($row->berkas_sertifikat)): ?>
                                                        <a href="<?= htmlspecialchars($row->berkas_sertifikat, ENT_QUOTES) ?>" target="_blank" class="text-primary">
                                                            <i class="feather-file-text"></i> Lihat Sertifikat
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="14" class="text-center text-muted py-4">
                                                <i class="feather-inbox me-2"></i>Data tidak ditemukan.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>

                            <!-- Pagination -->
                            <?= $arrPage['bar'] ?? '' ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Download Excel -->
            <div class="tab-pane fade" id="excel" role="tabpanel" aria-labelledby="excel-tab">
                <div class="card stretch stretch-full" style="border-top-left-radius:0">
                    <div class="card-header">
                        <h5 class="card-title">Download Data Excel</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-3">
                            <h6 class="alert-heading"><strong>📋 Informasi Penting:</strong></h6>
                            <p class="mb-2">Ikuti langkah-langkah berikut untuk mengunduh dan membuka file Excel dengan benar:</p>
                            <ol class="mb-0">
                                <li>Klik tombol <strong>"Export Excel"</strong> di bawah untuk mengunduh file.</li>
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

                        <a href="<?= htmlspecialchars(BE_MAIN_HOST, ENT_QUOTES) ?>/ikhtisar-nilai/peserta?<?= http_build_query($_GET) ?>&export=excel"
                            class="btn btn-primary btn-block w-100">
                            <i class="feather-download me-2"></i> Download Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .pelatihan-item {
        padding: 10px 12px;
        cursor: pointer;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        transition: background-color 0.2s ease;
        color: #212529;
        font-size: 0.875rem;
    }

    .pelatihan-item:hover {
        background-color: #f0f0f0;
    }

    .pelatihan-item:active {
        background-color: #e9ecef;
    }

    .pelatihan-not-found {
        padding: 10px 12px;
        color: #6c757d;
        font-size: 0.875rem;
        text-align: center;
    }

    #pelatihanResult::-webkit-scrollbar {
        width: 6px;
    }

    #pelatihanResult::-webkit-scrollbar-track {
        background: transparent;
    }

    #pelatihanResult::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 3px;
    }

    #pelatihanResult::-webkit-scrollbar-thumb:hover {
        background: #999;
    }
</style>

<script>
    const inputPelatihan = document.getElementById('pelatihanSearch');
    const resultBox = document.getElementById('pelatihanResult');
    const crIdInput = document.getElementById('cr_id');

    let timer = null;

    inputPelatihan.addEventListener('keyup', function() {
        clearTimeout(timer);

        const keyword = this.value.trim();
        crIdInput.value = ''; // reset

        if (keyword.length < 2) {
            resultBox.style.display = 'none';
            return;
        }

        timer = setTimeout(() => {
            fetch('<?= $targetpage ?>?ajax=pelatihan&q=' + encodeURIComponent(keyword))
                .then(res => res.json())
                .then(data => {
                    let html = '';

                    if (data.length > 0) {
                        data.forEach(item => {
                            html += `
                            <button type="button"
                                class="pelatihan-item"
                                onclick="pilihPelatihan(${item.cr_id}, '${item.cr_name.replace(/'/g, "\\'")}')">
                                ${item.cr_name}
                            </button>`;
                        });
                    } else {
                        html = `<div class="pelatihan-not-found">Tidak ditemukan</div>`;
                    }

                    resultBox.innerHTML = html;
                    resultBox.style.display = 'block';
                });
        }, 300);
    });

    function pilihPelatihan(id, nama) {
        inputPelatihan.value = nama;
        crIdInput.value = id;
        resultBox.style.display = 'none';
    }

    // Tutup dropdown saat klik di luar
    document.addEventListener('click', function(event) {
        if (!inputPelatihan.contains(event.target) && !resultBox.contains(event.target)) {
            resultBox.style.display = 'none';
        }
    });

    function initFilterHandler() {
        const form = document.getElementById('filterForm');
        if (!form) return;

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const params = new URLSearchParams(new FormData(form));
            const url = form.action + '?' + params.toString();

            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.text())
                .then(html => {
                    const temp = document.createElement('div');
                    temp.innerHTML = html;

                    const newResult = temp.querySelector('#resultContainer');
                    const oldResult = document.querySelector('#resultContainer');

                    if (newResult && oldResult) {
                        oldResult.innerHTML = newResult.innerHTML;
                    }

                    // URL tetap bersih
                    window.history.replaceState({}, document.title, form.action);
                });
        });
    }

    document.addEventListener('DOMContentLoaded', initFilterHandler);
</script>