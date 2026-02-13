<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10"><?= $this->pageTitle ?></h5>
        </div>
    </div>
</div>

<div class="main-content">
    <!-- Notifikasi Success -->
    <?php if (isset($_SESSION['notif_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="feather-check-circle me-2"></i><?= $_SESSION['notif_success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['notif_success']); ?>
    <?php endif; ?>

    <!-- Notifikasi Error Standard -->
    <?php if (isset($_SESSION['notif_error']) && !isset($_SESSION['upload_log_detail'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="feather-alert-circle me-2"></i>
            <div><?= $_SESSION['notif_error'] ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['notif_error']); ?>
    <?php endif; ?>

    <!-- Notifikasi Upload dengan Detail Error yang bisa di-collapse -->
    <?php if (isset($_SESSION['upload_log_detail'])): ?>
        <?php
        $has_errors = isset($_SESSION['upload_has_errors']) && $_SESSION['upload_has_errors'];
        $success_count = $_SESSION['upload_success_count'] ?? 0;
        $error_count = $_SESSION['upload_error_count'] ?? 0;
        ?>

        <!-- Success notification -->
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="feather-check-circle me-2"></i>Proses upload selesai.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <!-- Detail Error Box (collapsible) -->
        <?php if ($has_errors): ?>
            <div class="card mb-3">
                <div class="card-header" style="background-color: #ff9800; cursor: pointer;"
                    data-bs-toggle="collapse" data-bs-target="#errorDetail" aria-expanded="true">
                    <h6 class="mb-0 text-white">
                        <i class="feather-alert-triangle me-2"></i>Detail Error Upload
                        <span class="float-end">
                            <small>(<?= $error_count ?> error, <?= $success_count ?> sukses)</small>
                        </span>
                    </h6>
                </div>
                <div id="errorDetail" class="collapse show">
                    <div class="card-body" style="background-color: #fff3e0; max-height: 400px; overflow-y: auto;">
                        <?= $_SESSION['upload_log_detail'] ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php
        unset($_SESSION['upload_log_detail']);
        unset($_SESSION['upload_has_errors']);
        unset($_SESSION['upload_success_count']);
        unset($_SESSION['upload_error_count']);
        if (isset($_SESSION['notif_error'])) unset($_SESSION['notif_error']);
        ?>
    <?php endif; ?>

    <!-- Tab Navigation -->
    <ul class="nav nav-tabs" id="uploadTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload" type="button" role="tab">
                <i class="feather-upload me-2"></i>Upload CSV
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="template-tab" data-bs-toggle="tab" data-bs-target="#template" type="button" role="tab">
                <i class="feather-download me-2"></i>Template
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="panduan-tab" data-bs-toggle="tab" data-bs-target="#panduan" type="button" role="tab">
                <i class="feather-help-circle me-2"></i>Panduan
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="uploadTabContent">

        <!-- ================================================================ -->
        <!-- TAB UPLOAD                                                        -->
        <!-- ================================================================ -->
        <div class="tab-pane fade show active" id="upload" role="tabpanel">
            <div class="card stretch stretch-full" style="border-top-left-radius:0">
                <div class="card-header">
                    <h5 class="card-title">Upload File CSV</h5>
                </div>
                <div class="card-body">
                    <form method="post" enctype="multipart/form-data" id="uploadForm">

                        <!-- Info Mode Upload -->
                        <div class="alert alert-info mb-3">
                            <h6 class="alert-heading"><strong>📋 Format Upload:</strong></h6>
                            <p class="mb-2">Sistem hanya menerima file CSV dengan format <strong>5 kolom</strong> (Template 2):</p>
                            <ul class="mb-0">
                                <li><strong>Kolom 1:</strong> NIK (wajib)</li>
                                <li><strong>Kolom 2:</strong> NIK Baru (opsional, bisa dikosongkan)</li>
                                <li><strong>Kolom 3:</strong> Nama Karyawan (wajib)</li>
                                <li><strong>Kolom 4:</strong> Level Karyawan (wajib)</li>
                                <li><strong>Kolom 5:</strong> Status - Aktif/Nonaktif (wajib)</li>
                                <li><strong>Delimiter:</strong> Pilih secara manual (Comma atau Semicolon)</li>
                                <li><strong>Logika:</strong> NIK sudah ada → <em>Update</em> | NIK belum ada → <em>Insert baru</em></li>
                            </ul>
                        </div>

                        <!-- Entitas Target -->
                        <div class="form-group row mb-3">
                            <label class="col-sm-2 col-form-label">Entitas Target <span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                                <select class="form-control" name="target_group_id" required>
                                    <option value="">-- Pilih Entitas --</option>
                                    <?php foreach ($listGroup as $lg): ?>
                                        <option value="<?= htmlspecialchars($lg->group_id, ENT_QUOTES) ?>">
                                            <?= htmlspecialchars($lg->group_name, ENT_QUOTES) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="text-muted d-block mt-2">Semua data yang diupload akan masuk ke entitas ini</small>
                            </div>
                        </div>

                        <!-- Delimiter (Manual) -->
                        <div class="form-group row mb-3">
                            <label class="col-sm-2 col-form-label">Delimiter <span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                                <select class="form-control" name="delimiter" required>
                                    <option value="">-- Pilih Delimiter --</option>
                                    <option value=",">Comma (,)</option>
                                    <option value=";">Semicolon (;)</option>
                                </select>
                                <small class="text-muted d-block mt-2">
                                    <i class="feather-info me-1"></i>
                                    <strong>Comma (,)</strong> untuk Excel Internasional &nbsp;|&nbsp;
                                    <strong>Semicolon (;)</strong> untuk Excel Indonesia/Regional
                                </small>
                            </div>
                        </div>

                        <!-- File Upload -->
                        <div class="form-group row mb-3">
                            <label class="col-sm-2 col-form-label">File CSV <span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                                <input type="file" class="form-control" name="csv_file" id="csvFile" accept=".csv" required>
                                <small class="text-muted d-block mt-2">
                                    <i class="feather-info me-1"></i>
                                    Ukuran file: Max 5MB | Format: CSV | Baris: Max 10.000
                                </small>
                            </div>
                        </div>

                        <!-- Button Group -->
                        <div class="form-group row">
                            <div class="col-sm-offset-2 col-sm-10">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary" style="width: 150px;" id="btnSubmit">
                                        <i class="feather-upload me-2"></i>Upload & Proses
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

        <!-- ================================================================ -->
        <!-- TAB TEMPLATE                                                      -->
        <!-- ================================================================ -->
        <div class="tab-pane fade" id="template" role="tabpanel">
            <div class="card stretch stretch-full" style="border-top-left-radius:0">
                <div class="card-header">
                    <h5 class="card-title">Download Template CSV</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        <h6 class="alert-heading"><strong>📋 Template Upload (5 Kolom):</strong></h6>
                        <p class="mb-0">Tersedia dalam 2 format delimiter (comma dan semicolon). Pilih yang sesuai dengan regional setting Excel Anda.</p>
                    </div>

                    <!-- Template 2: UPDATE Dengan Opsi Ganti NIK -->
                    <div class="mb-3">
                        <h6 class="fw-bold mb-2">Template: UPDATE Data (Dengan Opsi Ganti NIK)</h6>
                        <p class="text-muted mb-2">Gunakan template ini untuk update karyawan yang sudah ada atau menambah karyawan baru. Password tidak akan berubah saat update. Kolom "NIK Baru" bisa kosong jika tidak ada perubahan NIK.</p>
                        <table class="table table-sm table-bordered mb-3">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">NIK</th>
                                    <th class="text-center">NIK Baru <small>(opsional)</small></th>
                                    <th class="text-center">Nama Karyawan</th>
                                    <th class="text-center">Level Karyawan</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>70612</td>
                                    <td>10644</td>
                                    <td>Audy Kusuma</td>
                                    <td>BOD-3</td>
                                    <td>Aktif</td>
                                </tr>
                                <tr>
                                    <td>70613</td>
                                    <td></td>
                                    <td>Budi Santoso - Updated</td>
                                    <td>BOD-2</td>
                                    <td>Nonaktif</td>
                                </tr>
                                <tr>
                                    <td>70661</td>
                                    <td></td>
                                    <td>Citra Wijaya</td>
                                    <td>BOD-1</td>
                                    <td>Aktif</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-primary" onclick="downloadTemplate('comma')">
                                <i class="feather-download me-2"></i>Download (Comma)
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="downloadTemplate('semicolon')">
                                <i class="feather-download me-2"></i>Download (Semicolon)
                            </button>
                        </div>
                    </div>

                    <div class="alert alert-warning mt-3">
                        <h6 class="alert-heading"><strong>⚠️ Penjelasan Template & Format:</strong></h6>
                        <ul class="mb-0">
                            <li><strong>Format Comma (,):</strong> Cocok untuk Excel versi Internasional</li>
                            <li><strong>Format Semicolon (;):</strong> Cocok untuk Excel versi Indonesia/Regional</li>
                            <li><strong>Delimiter:</strong> Harus dipilih secara manual saat upload, pastikan sesuai dengan template yang Anda download</li>
                            <li><strong>Template Format:</strong> Header = NIK, NIK Baru, Nama Karyawan, Level Karyawan, Status (5 kolom)</li>
                            <li><strong>NIK Baru:</strong> Boleh dikosongkan jika tidak ada perubahan NIK</li>
                            <li><strong>Password:</strong> Tidak akan berubah saat UPDATE. Untuk data baru, password otomatis sama dengan NIK</li>
                            <li><strong>Status valid:</strong> "Aktif" atau "Nonaktif"</li>
                            <li><strong>Level Karyawan:</strong> BOD-0, BOD-1, BOD-2, BOD-3, BOD-4, BOD-5, BOD-6</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================================================================ -->
        <!-- TAB PANDUAN                                                       -->
        <!-- ================================================================ -->
        <div class="tab-pane fade" id="panduan" role="tabpanel">
            <div class="card stretch stretch-full" style="border-top-left-radius:0">
                <div class="card-header">
                    <h5 class="card-title">Panduan Upload Data Massal</h5>
                </div>
                <div class="card-body">

                    <h6 class="mb-3"><strong>1. Format File CSV</strong></h6>
                    <ul class="mb-3">
                        <li>File harus berformat CSV (.csv)</li>
                        <li><strong>Pemisah kolom:</strong> Comma (,) atau Semicolon (;) — dipilih secara manual saat upload</li>
                        <li>Baris pertama adalah header (nama kolom)</li>
                        <li><strong>Wajib 5 kolom:</strong> NIK, NIK Baru, Nama Karyawan, Level Karyawan, Status</li>
                        <li>Maksimal 10.000 baris data per upload</li>
                    </ul>

                    <h6 class="mb-3"><strong>2. Memilih Format Delimiter</strong></h6>
                    <ul class="mb-3">
                        <li><strong>Comma (,):</strong> Untuk Excel versi Internasional atau Google Sheets</li>
                        <li><strong>Semicolon (;):</strong> Untuk Excel versi Indonesia/Regional</li>
                        <li><strong>Tips:</strong> Jika Excel Anda menggunakan regional setting Indonesia, gunakan Semicolon</li>
                        <li><strong>Penting:</strong> Pilih delimiter yang sesuai dengan template yang Anda download saat upload</li>
                    </ul>

                    <h6 class="mb-3"><strong>3. Penjelasan Kolom Template (5 Kolom)</strong></h6>
                    <ul class="mb-3">
                        <li><strong>Kolom 1 - NIK:</strong> NIK karyawan yang sudah ada (untuk update) atau NIK baru (untuk insert). Wajib diisi.</li>
                        <li><strong>Kolom 2 - NIK Baru:</strong> Opsional. Diisi jika ingin mengganti NIK lama dengan NIK baru. Bisa dikosongkan jika tidak ada perubahan.</li>
                        <li><strong>Kolom 3 - Nama Karyawan:</strong> Nama lengkap karyawan. Wajib diisi.</li>
                        <li><strong>Kolom 4 - Level Karyawan:</strong> Level jabatan (BOD-0 sampai BOD-6). Wajib diisi.</li>
                        <li><strong>Kolom 5 - Status:</strong> Status karyawan, hanya "Aktif" atau "Nonaktif". Wajib diisi.</li>
                    </ul>

                    <h6 class="mb-3"><strong>4. Contoh Format CSV</strong></h6>
                    <ul class="mb-3">
                        <li><strong>Contoh dengan ganti NIK (Comma):</strong>
                            <code style="display: block; margin: 5px 0; white-space: pre;">NIK,NIK Baru,Nama Karyawan,Level Karyawan,Status
                                70612,10644,Audy Kusuma,BOD-3,Aktif</code>
                        </li>
                        <li><strong>Contoh tanpa ganti NIK (Semicolon):</strong>
                            <code style="display: block; margin: 5px 0; white-space: pre;">NIK;NIK Baru;Nama Karyawan;Level Karyawan;Status
                                70613;;Budi Santoso Updated;BOD-2;Nonaktif
                                70661;;Citra Wijaya;BOD-1;Aktif</code>
                        </li>
                    </ul>

                    <h6 class="mb-3"><strong>5. Cara Menggunakan</strong></h6>
                    <ol class="mb-3">
                        <li>Download template dengan format delimiter yang Anda butuhkan (Comma atau Semicolon)</li>
                        <li>Buka file dengan Excel atau LibreOffice</li>
                        <li>Isikan data sesuai format 5 kolom</li>
                        <li>Kolom "NIK Baru" boleh dikosongkan jika tidak ada perubahan NIK</li>
                        <li>Simpan file dengan format CSV (encoding UTF-8)</li>
                        <li>Pilih <strong>Entitas target</strong></li>
                        <li>Pilih <strong>Delimiter</strong> yang sesuai dengan template yang Anda download</li>
                        <li>Upload file CSV</li>
                        <li>Klik <strong>"Upload & Proses"</strong></li>
                    </ol>

                    <h6 class="mb-3"><strong>6. Logika Update vs Insert</strong></h6>
                    <ul class="mb-3">
                        <li><strong>Jika NIK (kolom 1) sudah ada di database:</strong> Data akan di-UPDATE
                            <ul>
                                <li>Jika "NIK Baru" diisi → NIK akan diganti</li>
                                <li>Jika "NIK Baru" kosong → NIK tetap sama</li>
                                <li>Password TIDAK berubah</li>
                            </ul>
                        </li>
                        <li><strong>Jika NIK (kolom 1) belum ada di database:</strong> Data akan di-INSERT sebagai karyawan baru
                            <ul>
                                <li>Jika "NIK Baru" diisi → akan menggunakan NIK Baru</li>
                                <li>Jika "NIK Baru" kosong → akan menggunakan NIK dari kolom 1</li>
                                <li>Password otomatis sama dengan NIK</li>
                            </ul>
                        </li>
                    </ul>

                    <h6 class="mb-3"><strong>7. Tips & Trik</strong></h6>
                    <ul>
                        <li>Tidak boleh ada spasi di awal/akhir setiap cell</li>
                        <li>Simpan dengan encoding UTF-8</li>
                        <li>Pastikan delimiter yang dipilih sesuai dengan template yang Anda download</li>
                        <li>Cek duplikasi NIK sebelum upload</li>
                        <li>NIK harus persis sama dengan yang ada di database untuk mode update</li>
                        <li>Jika ada error, perbaiki di spreadsheet dan upload ulang</li>
                        <li>Maksimal 10.000 baris data per upload</li>
                        <li><strong>Validasi format:</strong> Sistem akan menolak file yang tidak memiliki tepat 5 kolom</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Loading state saat submit form
    document.getElementById('uploadForm').addEventListener('submit', function(e) {
        var btn = document.getElementById('btnSubmit');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Processing...';
    });

    function downloadTemplate(format) {
        let csv = '';
        let filename = '';
        let delimiter = (format === 'semicolon') ? ';' : ',';

        csv = 'NIK' + delimiter + 'NIK Baru' + delimiter + 'Nama Karyawan' + delimiter + 'Level Karyawan' + delimiter + 'Status\n' +
            '70612' + delimiter + '10644' + delimiter + 'Audy Kusuma' + delimiter + 'BOD-3' + delimiter + 'Aktif\n' +
            '70613' + delimiter + '' + delimiter + 'Budi Santoso' + delimiter + 'BOD-2' + delimiter + 'Nonaktif\n' +
            '70661' + delimiter + '' + delimiter + 'Citra Wijaya' + delimiter + 'BOD-1' + delimiter + 'Aktif\n';
        filename = 'template_sdm_upload_' + format + '_' + new Date().getTime() + '.csv';

        const blob = new Blob([csv], {
            type: 'text/csv;charset=utf-8;'
        });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);

        link.setAttribute('href', url);
        link.setAttribute('download', filename);
        link.style.visibility = 'hidden';

        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>