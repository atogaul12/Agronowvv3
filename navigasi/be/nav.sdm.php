<?php
//agronow/agronow_insight/navigasi/be/nav.sdm.php
session_start();

if (isset($_GET['ajax']) && $_GET['ajax'] === 'cari_karyawan' && isset($_GET['q'])) {
    header('Content-Type: application/json');

    $keyword = trim($_GET['q']);
    $result  = [];

    $user_login_group_id   = $_SESSION['be']['id_group'] ?? null;
    $user_login_silsilah   = $_SESSION['be']['silsilah'] ?? '';
    $user_login_tipe_akses = $_SESSION['be']['tipe_akses_member'] ?? 'self';

    $filterGroup = "";
    if ($user_login_tipe_akses == "as_parent") {
        if (!empty($user_login_silsilah)) {
            $filterGroup = " AND g.silsilah LIKE '" . $user_login_silsilah . "%' ";
        }
    } else if ($user_login_tipe_akses == "self") {
        $filterGroup = " AND g.group_id = '" . $user_login_group_id . "' ";
    }

    if (strlen($keyword) >= 2) {
        $sql = "
            SELECT m.member_id, m.member_nip, m.member_name, g.group_name
            FROM _member m
            INNER JOIN _group g ON g.group_id = m.group_id
            WHERE (m.member_nip LIKE ? OR m.member_name LIKE ?)
            {$filterGroup}
            AND m.member_status = 'active'
            ORDER BY m.member_nip ASC
            LIMIT 15
        ";

        $stmt = $akses->con->prepare($sql);
        $like = "%{$keyword}%";
        $stmt->bind_param("ss", $like, $like);
        $stmt->execute();

        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $result[] = [
                'member_id'   => $row['member_id'],
                'member_nip'  => $row['member_nip'],
                'member_name' => $row['member_name'],
                'group_name'  => $row['group_name']
            ];
        }
        $stmt->close();
    }

    echo json_encode($result);
    exit;
}

// VALIDASI AKSES & ROUTING
if ($this->pageLevel1 == "sdm") {
    $butuh_login = true;
    $akses->isBolehAkses('user.devmode', true);

    // PAGE: DAFTAR DATA KARYAWAN
    if ($this->pageLevel2 == "daftar-karyawan") {
        $this->setView("Manajemen Master Karyawan", "daftar-karyawan", "");

        // --- Reset Filter ---
        if (isset($_GET['reset']) && $_GET['reset'] == '1') {
            unset($_SESSION['sdm_filter']);
            header('Location: ' . BE_MAIN_HOST . '/sdm/daftar-karyawan');
            exit;
        }

        // --- Save Filter ke Session (POST) ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['action'])) {
            $_SESSION['sdm_filter'] = [
                'nik'      => isset($_POST['nik'])      ? $security->teksEncode($_POST['nik'])      : '',
                'nama'     => isset($_POST['nama'])     ? $security->teksEncode($_POST['nama'])     : '',
                'group_id' => isset($_POST['group_id']) ? $_POST['group_id'] : '',
                'status'   => isset($_POST['status'])   ? $_POST['status']   : ''
            ];
            header('Location: ' . BE_MAIN_HOST . '/sdm/daftar-karyawan');
            exit;
        }

        // --- Initialize Filter Session ---
        if (!isset($_SESSION['sdm_filter'])) {
            $_SESSION['sdm_filter'] = [
                'nik' => '',
                'nama' => '',
                'group_id' => '',
                'status' => ''
            ];
        }

        // --- Get Filter Values ---
        $nik      = $_SESSION['sdm_filter']['nik'];
        $nama     = $_SESSION['sdm_filter']['nama'];
        $group_id = $_SESSION['sdm_filter']['group_id'];
        $status   = $_SESSION['sdm_filter']['status'];

        // --- Get List Group ---
        $listGroup = $umum->getListGroupByAkses($akses);

        // --- Build SQL Conditions ---
        $addSql = "";
        if (!empty($nik))      $addSql .= " AND m.member_nip   LIKE '%$nik%' ";
        if (!empty($nama))     $addSql .= " AND m.member_name  LIKE '%$nama%' ";
        if (!empty($group_id)) $addSql .= " AND m.group_id     = '$group_id' ";
        if (!empty($status))   $addSql .= " AND m.member_status = '$status' ";

        // --- Filter Akses ---
        $filter_akses = "";
        $user_login_group_id   = $_SESSION['be']['id_group'] ?? null;
        $user_login_member_id  = $_SESSION['be']['id_user'] ?? null;
        $user_login_silsilah   = $_SESSION['be']['silsilah'] ?? '';
        $user_login_tipe_akses = $_SESSION['be']['tipe_akses_member'] ?? 'self';

        if ($user_login_tipe_akses == "as_parent") {
            if (!empty($user_login_silsilah)) {
                $filter_akses = " AND g.silsilah LIKE '" . $user_login_silsilah . "%' ";
            }
        } else if ($user_login_tipe_akses == "super_admin") {
            // Super admin bisa lihat semua
            $filter_akses = "";
        } else {
            // Self atau user biasa: bisa lihat data se-entitas DAN data diri sendiri
            $filter_akses = " AND (g.group_id = '" . $user_login_group_id . "' OR m.member_id = '" . $user_login_member_id . "') ";
        }

        // --- Main Query ---
        $sql = "
            SELECT
                m.member_id,
                m.member_nip,
                m.member_name,
                m.id_level_karyawan,
                mlk.nama as level_karyawan,
                m.member_status,
                g.group_id,
                g.group_name,
                m.member_email,
                m.member_phone,
                m.member_jabatan,
                m.date_masuk_kerja,
                m.member_create_date,
                g.aghris_company_code
            FROM _member m
            INNER JOIN _group g ON g.group_id = m.group_id
            LEFT JOIN _member_level_karyawan mlk ON mlk.id = m.id_level_karyawan
            WHERE 1=1
            {$addSql}
            {$filter_akses}
            ORDER BY m.member_create_date DESC, m.member_id DESC
        ";

        // --- Count Query ---
        $sql_count = "
            SELECT COUNT(*) AS total_data
            FROM _member m
            INNER JOIN _group g ON g.group_id = m.group_id
            LEFT JOIN _member_level_karyawan mlk ON mlk.id = m.id_level_karyawan
            WHERE 1=1
            {$addSql}
            {$filter_akses}
        ";

        // --- Export Excel ---
        if (isset($_GET['export']) && $_GET['export'] == 'excel') {
            header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
            header("Content-Disposition: attachment; filename=master_karyawan_" . date('Y-m-d') . ".xls");

            echo "<meta charset='utf-8'>";
            echo "<table border='1'>
                <tr>
                    <th>NIK</th>
                    <th>Nama Karyawan</th>
                    <th>Level Karyawan</th>
                    <th>Status</th>
                    <th>Entitas</th>
                    <th>Email</th>
                    <th>Telepon</th>
                </tr>";

            $exportData = $akses->doQuery($sql, 0, 'object');

            foreach ($exportData as $row) {
                $status_label = ($row->member_status == 'active') ? 'Aktif' : 'Nonaktif';
                echo "<tr>
                    <td>" . htmlspecialchars($row->member_nip)        . "</td>
                    <td>" . htmlspecialchars($row->member_name)       . "</td>
                    <td>" . htmlspecialchars($row->level_karyawan)    . "</td>
                    <td>{$status_label}</td>
                    <td>" . htmlspecialchars($row->group_name)        . "</td>
                    <td>" . htmlspecialchars($row->member_email)      . "</td>
                    <td>" . htmlspecialchars($row->member_phone)      . "</td>
                </tr>";
            }

            echo "</table>";
            exit;
        }

        // --- Pagination ---
        $limit      = 10;
        $page       = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $targetpage = BE_MAIN_HOST . '/sdm/daftar-karyawan';
        $pagestring = "?page=";

        $arrPage = $umum->setupPaginationUI(
            $sql,
            $akses->con,
            $limit,
            $page,
            $targetpage,
            $pagestring,
            "R",
            true,
            false,
            $sql_count
        );

        $data = $akses->doQuery($arrPage['sql'], 0, 'object');
    }

    // PAGE: TAMBAH / EDIT KARYAWAN
    else if ($this->pageLevel2 == "form-karyawan") {
        $this->setView("Form Karyawan", "form-karyawan", "");

        // --- Get List Data ---
        $listGroup = $umum->getListGroupByAkses($akses);
        $listLevel = $akses->doQuery(
            "SELECT id, nama FROM _member_level_karyawan WHERE status='active' ORDER BY nama ASC",
            0,
            'object'
        );

        $member_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $mode = ($member_id > 0) ? 'edit' : 'add';

        $user_login_member_id  = $_SESSION['be']['id_user'] ?? 0;
        $user_login_group_id   = $_SESSION['be']['id_group'] ?? null;
        $user_login_tipe_akses = $_SESSION['be']['tipe_akses_member'] ?? 'self';

        $is_editing_self = ($member_id > 0 && $member_id == $user_login_member_id);

        // --- Get Data untuk Edit ---
        if ($mode == 'edit') {
            // Validasi akses: user harus punya akses ke data ini
            $check_access = "";
            if ($user_login_tipe_akses == "super_admin") {
                // Super admin bisa edit semua
                $check_access = "";
            } else if ($user_login_tipe_akses == "as_parent") {
                $user_login_silsilah = $_SESSION['be']['silsilah'] ?? '';
                if (!empty($user_login_silsilah)) {
                    $check_access = " AND g.silsilah LIKE '" . $user_login_silsilah . "%' ";
                }
            } else {
                // User biasa: hanya bisa edit data se-entitas atau data diri sendiri
                $check_access = " AND (m.group_id = '" . $user_login_group_id . "' OR m.member_id = '" . $user_login_member_id . "') ";
            }

            $dataMember = $akses->doQuery(
                "SELECT m.*, g.aghris_company_code 
                 FROM _member m 
                 INNER JOIN _group g ON g.group_id = m.group_id 
                 WHERE m.member_id = '$member_id' $check_access LIMIT 1",
                0,
                'object'
            );

            if (empty($dataMember)) {
                $_SESSION['notif_error'] = 'Data karyawan tidak ditemukan atau Anda tidak memiliki akses!';
                header('Location: ' . BE_MAIN_HOST . '/sdm/daftar-karyawan');
                exit;
            }
            $dataMember = $dataMember[0];
        }

        // --- Proses Form Submit ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nik_lama     = isset($_POST['nik_lama'])     ? trim($_POST['nik_lama'])     : '';
            $nik_baru     = isset($_POST['nik_baru'])     ? trim($_POST['nik_baru'])     : '';
            $nama         = isset($_POST['nama'])         ? trim($_POST['nama'])         : '';
            $group_id     = isset($_POST['group_id'])     ? (int)$_POST['group_id']      : 0;
            $level_id     = isset($_POST['level_id'])     ? (int)$_POST['level_id']      : 0;
            $status       = isset($_POST['status'])       ? $_POST['status']             : 'active';
            $new_password = isset($_POST['new_password']) ? $_POST['new_password']       : '';

            $strError = '';

            // Jika user edit data sendiri, beberapa field tidak bisa diubah
            if ($is_editing_self) {
                // Ambil data asli untuk field yang tidak boleh diubah
                $group_id = $dataMember->group_id;
                $level_id = $dataMember->id_level_karyawan;
                $status   = $dataMember->member_status;

                // Validasi khusus untuk edit sendiri
                if (empty($nama)) $strError .= '<li>Nama karyawan wajib diisi!</li>';
            } else {
                // Validasi untuk admin/user lain
                if (empty($nama))                        $strError .= '<li>Nama karyawan wajib diisi!</li>';
                if (empty($nik_lama) && $mode == 'edit') $strError .= '<li>NIK tidak valid!</li>';
                if (empty($group_id))                    $strError .= '<li>Entitas wajib dipilih!</li>';
                if (empty($level_id))                    $strError .= '<li>Level karyawan wajib dipilih!</li>';
            }

            // Tentukan NIK yang akan digunakan
            if ($mode == 'edit' && !empty($nik_baru) && $nik_baru != $nik_lama) {
                $use_nik = $nik_baru;
                // Cek duplikasi NIK baru
                $cek_nik = $akses->doQuery(
                    "SELECT member_id FROM _member WHERE group_id='$group_id' AND member_nip='$nik_baru' AND member_id != '$member_id' LIMIT 1",
                    0,
                    'object'
                );
                if (!empty($cek_nik)) {
                    $strError .= '<li>NIK sudah terdaftar pada entitas ini!</li>';
                }
            } else if ($mode == 'add') {
                if (empty($nik_lama)) {
                    $strError .= '<li>NIK wajib diisi!</li>';
                }
                $use_nik = $nik_lama;
                // Cek duplikasi NIK baru
                $cek_nik = $akses->doQuery(
                    "SELECT member_id FROM _member WHERE group_id='$group_id' AND member_nip='$nik_lama' LIMIT 1",
                    0,
                    'object'
                );
                if (!empty($cek_nik)) {
                    $strError .= '<li>NIK sudah terdaftar pada entitas ini!</li>';
                }
            } else {
                $use_nik = $nik_lama;
            }

            if (strlen($strError) <= 0) {
                if ($mode == 'add') {
                    // INSERT — password otomatis sama dengan NIK
                    $password = md5($use_nik);

                    $sql_insert = "
                        INSERT INTO _member (
                            group_id, group_id_aghris, member_name_aghris, mlevel_id,
                            member_name, member_nip, member_type,
                            member_login_web, member_login_apk, member_login_ipa,
                            member_reg_id, member_reg_channel, member_device,
                            member_desc, date_masuk_kerja, member_jabatan, member_kel_jabatan,
                            member_unit_kerja, member_image, member_gender, member_address,
                            member_city, member_province, member_postcode,
                            id_level_karyawan, member_status, member_password,
                            member_create_date, member_user_update_date
                        ) VALUES (
                            '$group_id', '0', '', '1',
                            '$nama', '$use_nik', 'general',
                            '0', '0', '0',
                            '', 'web', '',
                            '', '0000-00-00', '', '',
                            '', '', 'Pria', '',
                            '', '', '00000',
                            '$level_id', '$status', '$password',
                            NOW(), NOW()
                        )
                    ";

                    if ($akses->con->query($sql_insert)) {
                        $new_member_id = $akses->con->insert_id;

                        // Log aktivitas
                        $log_desc   = "Menambah karyawan baru: NIK=$use_nik, Nama=$nama";
                        $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
                        $user_id    = $_SESSION['be']['id_user'] ?? 0;

                        $akses->con->query("
                            INSERT INTO _member_activity (
                                member_id, section_id, data_id,
                                member_activity_type, member_activity_desc,
                                member_activity_create_date, ip_address
                            ) VALUES (
                                '$user_id', '0', '$new_member_id',
                                'SDM - Tambah Karyawan', '$log_desc',
                                NOW(), '$ip_address'
                            )
                        ");

                        $_SESSION['notif_success'] = "Data karyawan berhasil ditambahkan! (Password: $use_nik)";
                    } else {
                        $_SESSION['notif_error'] = 'Gagal menambah data: ' . $akses->con->error;
                    }
                } else {
                    // UPDATE
                    $getGroup = $akses->doQuery(
                        "SELECT aghris_company_code FROM _group WHERE group_id = " . (int)$dataMember->group_id . " LIMIT 1",
                        0,
                        'object'
                    );
                    $is_aghris = (isset($getGroup[0]) && !empty($getGroup[0]->aghris_company_code));

                    if ($is_aghris && !empty($new_password)) {
                        $_SESSION['notif_error'] = 'Data AGHRIS: Password tidak bisa diupdate di sistem ini. Hubungi administrator AGHRIS.';
                    } else {
                        $sql_update = "
                            UPDATE _member SET
                                member_name            = '$nama',
                                member_nip             = '$use_nik',
                                id_level_karyawan      = '$level_id',
                                member_status          = '$status',
                                member_user_update_date = NOW()
                            WHERE member_id = '$member_id'
                        ";

                        if ($akses->con->query($sql_update)) {
                            $log_desc   = "Mengupdate data karyawan: NIK=$use_nik, Nama=$nama";
                            $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
                            $user_id    = $_SESSION['be']['id_user'] ?? 0;

                            // Update password jika diisi dan non-AGHRIS
                            if (!empty($new_password) && !$is_aghris) {
                                $hash_pass = md5($new_password);
                                $akses->con->query("UPDATE _member SET member_password = '$hash_pass' WHERE member_id = '$member_id'");
                                $log_desc .= " (Password diubah)";
                                $_SESSION['notif_success'] = 'Data karyawan berhasil diupdate! (Password juga diubah)';
                            } else {
                                $_SESSION['notif_success'] = 'Data karyawan berhasil diupdate!';
                            }

                            $akses->con->query("
                                INSERT INTO _member_activity (
                                    member_id, section_id, data_id,
                                    member_activity_type, member_activity_desc,
                                    member_activity_create_date, ip_address
                                ) VALUES (
                                    '$user_id', '0', '$member_id',
                                    'SDM - Update Karyawan', '$log_desc',
                                    NOW(), '$ip_address'
                                )
                            ");
                        } else {
                            $_SESSION['notif_error'] = 'Gagal update data: ' . $akses->con->error;
                        }
                    }
                }

                header('Location: ' . BE_MAIN_HOST . '/sdm/daftar-karyawan');
                exit;
            } else {
                $_SESSION['notif_error'] = $strError;
            }
        }
    }

    // PAGE: UPDATE PASSWORD
    else if ($this->pageLevel2 == "update-password") {
        $this->setView("Update Password Karyawan", "update-password", "");

        $member_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($member_id <= 0) {
            $_SESSION['notif_error'] = 'ID Karyawan tidak valid!';
            header('Location: ' . BE_MAIN_HOST . '/sdm/daftar-karyawan');
            exit;
        }

        // Get Data Karyawan
        $dataMember = $akses->doQuery(
            "SELECT m.*, g.aghris_company_code, g.group_name
             FROM _member m
             INNER JOIN _group g ON g.group_id = m.group_id
             WHERE m.member_id = '$member_id' LIMIT 1",
            0,
            'object'
        );

        if (empty($dataMember)) {
            $_SESSION['notif_error'] = 'Data karyawan tidak ditemukan!';
            header('Location: ' . BE_MAIN_HOST . '/sdm/daftar-karyawan');
            exit;
        }

        $dataMember = $dataMember[0];
        $is_aghris  = !empty($dataMember->aghris_company_code);

        // Proses Update Password
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_password = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';

            if ($is_aghris) {
                $_SESSION['notif_error'] = 'Akun ini terhubung dengan AGHRIS, untuk reset password bisa dilakukan melalui aplikasi tersebut.';
            } else if (empty($new_password)) {
                $_SESSION['notif_error'] = 'Password baru wajib diisi!';
            } else {
                $hash_pass  = md5($new_password);
                $sql_update = "UPDATE _member SET member_password = '$hash_pass', member_user_update_date = NOW() WHERE member_id = '$member_id'";

                if ($akses->con->query($sql_update)) {
                    // Log aktivitas
                    $log_desc   = "Update password karyawan: NIK={$dataMember->member_nip}, Nama={$dataMember->member_name}";
                    $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
                    $user_id    = $_SESSION['be']['id_user'] ?? 0;

                    $akses->con->query("
                        INSERT INTO _member_activity (
                            member_id, section_id, data_id,
                            member_activity_type, member_activity_desc,
                            member_activity_create_date, ip_address
                        ) VALUES (
                            '$user_id', '0', '$member_id',
                            'SDM - Update Password', '$log_desc',
                            NOW(), '$ip_address'
                        )
                    ");

                    $_SESSION['notif_success'] = 'Password berhasil diupdate!';
                    header('Location: ' . BE_MAIN_HOST . '/sdm/daftar-karyawan');
                    exit;
                } else {
                    $_SESSION['notif_error'] = 'Gagal update password: ' . $akses->con->error;
                }
            }
        }
    }

    // PAGE: RESET PASSWORD
    else if ($this->pageLevel2 == "reset-password") {
        $this->setView("Reset Password Karyawan", "reset-password", "");

        $member_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($member_id <= 0) {
            $_SESSION['notif_error'] = 'ID Karyawan tidak valid!';
            header('Location: ' . BE_MAIN_HOST . '/sdm/daftar-karyawan');
            exit;
        }

        // Get Data Karyawan
        $dataMember = $akses->doQuery(
            "SELECT m.*, g.aghris_company_code, g.group_name
             FROM _member m
             INNER JOIN _group g ON g.group_id = m.group_id
             WHERE m.member_id = '$member_id' LIMIT 1",
            0,
            'object'
        );

        if (empty($dataMember)) {
            $_SESSION['notif_error'] = 'Data karyawan tidak ditemukan!';
            header('Location: ' . BE_MAIN_HOST . '/sdm/daftar-karyawan');
            exit;
        }

        $dataMember = $dataMember[0];
        $is_aghris  = !empty($dataMember->aghris_company_code);

        // Proses Reset Password
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_reset'])) {
            if ($is_aghris) {
                $_SESSION['notif_error'] = 'Akun ini terhubung dengan AGHRIS, untuk reset password bisa dilakukan melalui aplikasi tersebut.';
            } else {
                // Password = NIK
                $new_password = $dataMember->member_nip;
                $hash_pass    = md5($new_password);
                $sql_update   = "UPDATE _member SET member_password = '$hash_pass', member_user_update_date = NOW() WHERE member_id = '$member_id'";

                if ($akses->con->query($sql_update)) {
                    // Log aktivitas
                    $log_desc   = "Reset password karyawan ke NIK: NIK={$dataMember->member_nip}, Nama={$dataMember->member_name}";
                    $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
                    $user_id    = $_SESSION['be']['id_user'] ?? 0;

                    $akses->con->query("
                        INSERT INTO _member_activity (
                            member_id, section_id, data_id,
                            member_activity_type, member_activity_desc,
                            member_activity_create_date, ip_address
                        ) VALUES (
                            '$user_id', '0', '$member_id',
                            'SDM - Reset Password', '$log_desc',
                            NOW(), '$ip_address'
                        )
                    ");

                    $_SESSION['notif_success'] = 'Password berhasil direset ke NIK!';
                    header('Location: ' . BE_MAIN_HOST . '/sdm/daftar-karyawan');
                    exit;
                } else {
                    $_SESSION['notif_error'] = 'Gagal reset password: ' . $akses->con->error;
                }
            }
        }
    }

    // PAGE: UPLOAD MASSAL
    else if ($this->pageLevel2 == "upload-massal") {
        $this->setView("Upload Data Massal", "upload-massal", "");

        $listGroup = $umum->getListGroupByAkses($akses);
        $listLevel = $akses->doQuery(
            "SELECT id, nama FROM _member_level_karyawan WHERE status='active' ORDER BY nama ASC",
            0,
            'object'
        );

        $strError = '';

        // PROSES UPLOAD FILE (HANYA TEMPLATE 2: 5 KOLOM)
        if ($_POST) {
            $target_group_id = (int)$_POST['target_group_id'];
            $delimiter       = $security->teksEncode($_POST['delimiter']);

            // Validasi delimiter
            if ($delimiter == "," || $delimiter == ";") {
                // valid
            } else {
                $delimiter = '';
            }

            // Validasi awal
            $strError .= $umum->cekFile($_FILES['csv_file'], 'csv', '', true);
            if (empty($target_group_id)) $strError .= '<li>Entitas target wajib dipilih.</li>';
            if (empty($delimiter))       $strError .= '<li>Delimiter wajib dipilih.</li>';

            // --- Proses jika validasi awal lolos ---
            if (strlen($strError) <= 0) {
                $errors  = [];
                $success = [];

                // CACHE: Level karyawan → array [nama => id]
                $levelCache = [];
                foreach ($listLevel as $lv) {
                    $levelCache[strtolower(trim($lv->nama))] = $lv->id;
                }

                // TAMBAHAN: Validasi file bisa dibuka
                if (!file_exists($_FILES['csv_file']['tmp_name'])) {
                    $_SESSION['notif_error'] = '<li>File upload tidak ditemukan. Silakan coba lagi.</li>';
                    header("location:" . BE_MAIN_HOST . "/sdm/upload-massal");
                    exit;
                }

                $handle = @fopen($_FILES['csv_file']['tmp_name'], 'r');

                if ($handle === false) {
                    $_SESSION['notif_error'] = '<li>Gagal membuka file CSV. Pastikan file valid dan tidak corrupt.</li>';
                    header("location:" . BE_MAIN_HOST . "/sdm/upload-massal");
                    exit;
                }

                $row    = 0;
                $col    = [];

                while (($data = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                    $row++;

                    // ROW 1: Validasi header harus 5 kolom (Template 2)
                    if ($row == 1) {
                        $juml_kolom = count($data);

                        // Harus tepat 5 kolom
                        if ($juml_kolom != 5) {
                            $errors[] = 'Format file tidak sesuai! Sistem hanya menerima template dengan <b>5 kolom</b> '
                                . '(NIK, NIK Baru, Nama Karyawan, Level Karyawan, Status). '
                                . 'File Anda memiliki <b>' . $juml_kolom . ' kolom</b>. '
                                . 'Kemungkinan delimiter yang dipilih salah atau format template tidak sesuai.';
                            break;
                        }

                        // Mapping kolom untuk Template 2 (5 kolom)
                        $col = [
                            'nik_lama' => 0,
                            'nik_baru' => 1,
                            'nama'     => 2,
                            'level'    => 3,
                            'status'   => 4
                        ];
                        continue; // skip header
                    }

                    // ROW 2+: Validasi & proses per baris

                    $nik_lama = trim($data[$col['nik_lama']] ?? '');
                    $nik_baru = trim($data[$col['nik_baru']] ?? '');
                    $nama     = trim($data[$col['nama']]     ?? '');
                    $level    = trim($data[$col['level']]    ?? '');
                    $status   = trim($data[$col['status']]   ?? '');

                    // Validasi field wajib (NIK Baru boleh kosong)
                    if (empty($nik_lama) || empty($nama) || empty($level) || empty($status)) {
                        $errors[] = "Baris $row salah: data tidak lengkap (NIK, Nama, Level, Status wajib diisi).";
                        continue;
                    }

                    // Validasi status
                    if ($status == 'Aktif') {
                        $status_db = 'active';
                    } else if ($status == 'Nonaktif') {
                        $status_db = 'block';
                    } else {
                        $errors[] = "Baris $row salah: status hanya boleh 'Aktif' atau 'Nonaktif' (dapat: '$status').";
                        continue;
                    }

                    // Sanitize
                    $nik_lama = $security->teksEncode($nik_lama);
                    $nik_baru = $security->teksEncode($nik_baru);
                    $nama     = $security->teksEncode($nama);
                    $level    = $security->teksEncode($level);

                    // --- Cek Level Karyawan (dari cache) ---
                    $level_key = strtolower(trim($level));
                    if (!isset($levelCache[$level_key])) {
                        $errors[] = "Baris $row salah: level karyawan '$level' tidak ditemukan.";
                        continue;
                    }
                    $level_id = $levelCache[$level_key];

                    // --- Cek apakah NIK Lama sudah ada ---
                    $stmt_cek = $akses->con->prepare(
                        "SELECT member_id FROM _member WHERE group_id = ? AND member_nip = ? LIMIT 1"
                    );

                    if (!$stmt_cek) {
                        $errors[] = "Baris $row gagal: error prepare statement - " . $akses->con->error;
                        continue;
                    }

                    $stmt_cek->bind_param("is", $target_group_id, $nik_lama);

                    if (!$stmt_cek->execute()) {
                        $errors[] = "Baris $row gagal: error execute query - " . $stmt_cek->error;
                        $stmt_cek->close();
                        continue;
                    }

                    $res_cek = $stmt_cek->get_result();

                    if ($res_cek->num_rows > 0) {
                        // MODE: UPDATE (NIK Lama ditemukan)
                        $member_id = $res_cek->fetch_object()->member_id;
                        $stmt_cek->close();

                        $use_nik = !empty($nik_baru) ? $nik_baru : $nik_lama;

                        // Jika NIK Baru berbeda, cek duplikasi
                        if (!empty($nik_baru) && $nik_baru != $nik_lama) {
                            $stmt_dup = $akses->con->prepare(
                                "SELECT member_id FROM _member WHERE group_id = ? AND member_nip = ? AND member_id != ? LIMIT 1"
                            );

                            if (!$stmt_dup) {
                                $errors[] = "Baris $row gagal: error prepare statement duplikasi - " . $akses->con->error;
                                continue;
                            }

                            $stmt_dup->bind_param("isi", $target_group_id, $nik_baru, $member_id);

                            if (!$stmt_dup->execute()) {
                                $errors[] = "Baris $row gagal: error execute duplikasi - " . $stmt_dup->error;
                                $stmt_dup->close();
                                continue;
                            }

                            $res_dup = $stmt_dup->get_result();

                            if ($res_dup->num_rows > 0) {
                                $errors[] = "Baris $row salah: NIK baru '$nik_baru' sudah terdaftar pada entitas ini.";
                                $stmt_dup->close();
                                continue;
                            }
                            $stmt_dup->close();
                        }

                        // Eksekusi UPDATE
                        $stmt_update = $akses->con->prepare("
                            UPDATE _member SET
                                member_name             = ?,
                                member_nip              = ?,
                                id_level_karyawan       = ?,
                                member_status           = ?,
                                member_user_update_date = NOW()
                            WHERE member_id = ?
                        ");

                        if (!$stmt_update) {
                            $errors[] = "Baris $row gagal update: error prepare statement - " . $akses->con->error;
                            continue;
                        }

                        $stmt_update->bind_param("ssisi", $nama, $use_nik, $level_id, $status_db, $member_id);

                        if ($stmt_update->execute()) {
                            $success[] = "Baris $row berhasil update (NIK: $nik_lama → $use_nik).";
                        } else {
                            $errors[] = "Baris $row gagal update (NIK: $nik_lama): " . $stmt_update->error;
                        }
                        $stmt_update->close();
                    } else {
                        // MODE: INSERT (NIK Lama tidak ditemukan = data baru)
                        $stmt_cek->close();

                        $use_nik = !empty($nik_baru) ? $nik_baru : $nik_lama;

                        // Cek duplikasi NIK untuk data baru
                        $stmt_dup = $akses->con->prepare(
                            "SELECT member_id FROM _member WHERE group_id = ? AND member_nip = ? LIMIT 1"
                        );

                        if (!$stmt_dup) {
                            $errors[] = "Baris $row gagal: error prepare statement duplikasi insert - " . $akses->con->error;
                            continue;
                        }

                        $stmt_dup->bind_param("is", $target_group_id, $use_nik);

                        if (!$stmt_dup->execute()) {
                            $errors[] = "Baris $row gagal: error execute duplikasi insert - " . $stmt_dup->error;
                            $stmt_dup->close();
                            continue;
                        }

                        $res_dup = $stmt_dup->get_result();

                        if ($res_dup->num_rows > 0) {
                            $errors[] = "Baris $row salah: NIK '$use_nik' sudah terdaftar pada entitas ini.";
                            $stmt_dup->close();
                            continue;
                        }
                        $stmt_dup->close();

                        // Password = NIK
                        $password = md5($use_nik);

                        // Eksekusi INSERT
                        $stmt_insert = $akses->con->prepare("
                            INSERT INTO _member (
                                group_id, group_id_aghris, member_name_aghris, mlevel_id,
                                member_name, member_nip, member_type,
                                member_login_web, member_login_apk, member_login_ipa,
                                member_reg_id, member_reg_channel, member_device,
                                member_desc, date_masuk_kerja, member_jabatan, member_kel_jabatan,
                                member_unit_kerja, member_image, member_gender, member_address,
                                member_city, member_province, member_postcode,
                                id_level_karyawan, member_status, member_password,
                                member_create_date, member_user_update_date
                            ) VALUES (
                                ?, '0', '', '1',
                                ?, ?, 'general',
                                '0', '0', '0',
                                '', 'web', '',
                                '', '0000-00-00', '', '',
                                '', '', 'Pria', '',
                                '', '', '00000',
                                ?, ?, ?,
                                NOW(), NOW()
                            )
                        ");

                        if (!$stmt_insert) {
                            $errors[] = "Baris $row gagal insert: error prepare statement - " . $akses->con->error;
                            continue;
                        }

                        $stmt_insert->bind_param("ississ", $target_group_id, $nama, $use_nik, $level_id, $status_db, $password);

                        if ($stmt_insert->execute()) {
                            $success[] = "Baris $row berhasil tambah (NIK: $use_nik).";
                        } else {
                            $errors[] = "Baris $row gagal tambah (NIK: $use_nik): " . $stmt_insert->error;
                        }
                        $stmt_insert->close();
                    }
                }

                fclose($handle);

                // BUILD result_info untuk notifikasi
                $result_info = '';
                $juml_success = count($success);
                $juml_errors  = count($errors);

                if ($juml_errors > 0) {
                    // Ada error - tampilkan list error saja
                    $result_info .= '<ul class="mb-0">';
                    foreach ($errors as $e) {
                        $result_info .= "<li>$e</li>";
                    }
                    $result_info .= '</ul>';

                    // Set session untuk notifikasi
                    $_SESSION['upload_log_detail'] = $result_info;
                    $_SESSION['upload_has_errors'] = true;
                    $_SESSION['upload_success_count'] = $juml_success;
                    $_SESSION['upload_error_count'] = $juml_errors;
                } else {
                    // Semua sukses
                    $_SESSION['notif_success'] = "Upload selesai: <strong>$juml_success data berhasil</strong> disimpan.";
                }

                // LOGGING ke database
                $log_db = '';
                if ($juml_errors > 0) {
                    $log_db .= '<h6 class="text-danger"><strong>Data yang Gagal Disimpan:</strong></h6>';
                    $log_db .= '<ul class="text-danger">';
                    foreach ($errors as $e) $log_db .= "<li>$e</li>";
                    $log_db .= '</ul>';
                    $log_db .= '<hr>';
                    $log_db .= '<h6 class="text-success"><strong>Data yang Berhasil Disimpan:</strong></h6>';
                    $log_db .= '<ul class="text-success">';
                    if ($juml_success > 0) {
                        foreach ($success as $s) $log_db .= "<li>$s</li>";
                    } else {
                        $log_db .= "<li>Tidak ada data yang berhasil disimpan</li>";
                    }
                    $log_db .= '</ul>';
                } else {
                    $log_db = '<h6 class="text-success"><strong>Semua Data Berhasil Disimpan!</strong></h6>';
                    if ($juml_success > 0) {
                        $log_db .= '<ul class="text-success">';
                        foreach ($success as $s) $log_db .= "<li>$s</li>";
                        $log_db .= '</ul>';
                    }
                }

                $sql_log = "
                    INSERT INTO berkas_upload_log SET
                        nama_halaman = 'upload_data_sdm_massal',
                        catatan      = '" . $akses->con->real_escape_string($log_db) . "',
                        tgl_update   = NOW()
                    ON DUPLICATE KEY UPDATE
                        catatan      = '" . $akses->con->real_escape_string($log_db) . "',
                        tgl_update   = NOW()
                ";
                $akses->con->query($sql_log);

                $akses->insertLog("upload massal SDM: $juml_success berhasil, $juml_errors gagal", '', '');
                header("location:" . BE_MAIN_HOST . "/sdm/upload-massal");
                exit;
            } else {
                // Tampilkan error validasi awal
                $_SESSION['notif_error'] = '<ul>' . $strError . '</ul>';
                header("location:" . BE_MAIN_HOST . "/sdm/upload-massal");
                exit;
            }
        }
    }
}
