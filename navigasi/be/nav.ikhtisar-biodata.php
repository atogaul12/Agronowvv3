<?php
//agronow/agronow_insight/navigasi/be/nav.ikhtisar.biodata.php
session_start();

// AJAX Endpoint untuk autocomplete pencarian pelatihan
if (
    isset($_GET['ajax']) &&
    $_GET['ajax'] === 'pelatihan' &&
    isset($_GET['q'])
) {
    header('Content-Type: application/json');

    $keyword = trim($_GET['q']);
    $result  = [];

    if (strlen($keyword) >= 2) {
        $sql = "
            SELECT cr_id, cr_name
            FROM _classroom
            WHERE cr_name LIKE ?
            ORDER BY cr_name ASC
            LIMIT 10
        ";

        $stmt = $akses->con->prepare($sql);
        $like = "%{$keyword}%";
        $stmt->bind_param("s", $like);
        $stmt->execute();

        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $result[] = $row;
        }
    }

    echo json_encode($result);
    exit;
}

if ($this->pageLevel1 == "ikhtisar-biodata") {
    $butuh_login = true;

    $akses->isBolehAkses('BIODATA_PESERTA', true);

    if ($this->pageLevel2 == "biodata-peserta") {

        $this->setView("Biodata Peserta", "biodata-peserta", "");

        $filter_akses = $umum->getFilterIkhtisarDataPelatihan();

        // ===== HANDLE GET PARAMETER NIK DARI LINK LANGSUNG =====
        // Ini untuk handle link dari menu SDM "Lihat Ikhtisar Pelatihan"
        if (isset($_GET['nik']) && !empty($_GET['nik'])) {
            // Reset semua filter dan set NIK dengan periode yang luas
            $_SESSION['biodata_filter'] = [
                'tgl_awal'          => '2020-01-01', // Periode luas dari 2020
                'tgl_akhir'         => date('Y-12-31'), // Sampai akhir tahun ini
                'cat_id'            => '',
                'group_id'          => '',
                'entitas_saat_ini'  => '',
                'entitas_saat_ikut' => '',
                'cr_id'             => '',
                'nik'               => $security->teksEncode($_GET['nik'])
            ];

            // Redirect ke URL bersih (tanpa query string)
            header('Location: ' . BE_MAIN_HOST . '/ikhtisar-biodata/biodata-peserta');
            exit;
        }

        // ===== RESET FILTER =====
        if (isset($_GET['reset']) && $_GET['reset'] == '1') {
            unset($_SESSION['biodata_filter']);
            header('Location: ' . BE_MAIN_HOST . '/ikhtisar-biodata/biodata-peserta');
            exit;
        }

        // ===== SAVE FILTER KE SESSION (METHOD POST) =====
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['biodata_filter'] = [
                'tgl_awal'          => isset($_POST['tgl_awal']) && $_POST['tgl_awal'] !== '' ? $_POST['tgl_awal'] : date('Y-m-01'),
                'tgl_akhir'         => isset($_POST['tgl_akhir']) && $_POST['tgl_akhir'] !== '' ? $_POST['tgl_akhir'] : date('Y-12-31'),
                'cat_id'            => isset($_POST['cat_id']) ? $_POST['cat_id'] : '',
                'group_id'          => isset($_POST['group_id']) ? $_POST['group_id'] : '',
                'entitas_saat_ini'  => isset($_POST['entitas_saat_ini']) ? $_POST['entitas_saat_ini'] : '',
                'entitas_saat_ikut' => isset($_POST['entitas_saat_ikut']) ? $_POST['entitas_saat_ikut'] : '',
                'cr_id'             => isset($_POST['cr_id']) ? $_POST['cr_id'] : '',
                'nik'               => isset($_POST['nik']) ? $security->teksEncode($_POST['nik']) : ''
            ];

            // Redirect ke URL bersih (tanpa query string)
            header('Location: ' . BE_MAIN_HOST . '/ikhtisar-biodata/biodata-peserta');
            exit;
        }

        // ===== INITIALIZE SESSION JIKA BELUM ADA =====
        if (!isset($_SESSION['biodata_filter'])) {
            $_SESSION['biodata_filter'] = [
                'tgl_awal'          => date('Y-m-01'),
                'tgl_akhir'         => date('Y-12-31'),
                'cat_id'            => '',
                'group_id'          => '',
                'entitas_saat_ini'  => '',
                'entitas_saat_ikut' => '',
                'cr_id'             => '',
                'nik'               => ''
            ];
        }

        // ===== AMBIL FILTER DARI SESSION =====
        $tgl_awal          = $_SESSION['biodata_filter']['tgl_awal'];
        $tgl_akhir         = $_SESSION['biodata_filter']['tgl_akhir'];
        $cat_id            = $_SESSION['biodata_filter']['cat_id'];
        $group_id          = $_SESSION['biodata_filter']['group_id'];
        $entitas_saat_ini  = $_SESSION['biodata_filter']['entitas_saat_ini'];
        $entitas_saat_ikut = $_SESSION['biodata_filter']['entitas_saat_ikut'];
        $cr_id             = $_SESSION['biodata_filter']['cr_id'];
        $nik               = $_SESSION['biodata_filter']['nik'];

        $listKategori = $akses->doQuery(
            "SELECT cat_id, cat_name 
             FROM _category 
             WHERE section_id = 30 AND cat_status='1' 
             ORDER BY cat_name ASC",
            0,
            'object'
        );

        $listGroup = $umum->getListGroupByAkses($akses);

        // ===== BUILD SQL CONDITIONS =====
        $addSql = "
        AND _classroom.cr_date_start
        BETWEEN '$tgl_awal 00:00:00'
        AND '$tgl_akhir 23:59:59'
        ";

        // Filter kategori
        if ($cat_id !== '') {
            $addSql .= " AND _classroom.cat_id = '$cat_id' ";
        }

        if ($group_id !== '') {
            $addSql .= " AND _classroom_member.id_group = '$group_id' ";
        }

        // Filter entitas saat ini
        if ($entitas_saat_ini !== '') {
            $addSql .= " AND group_saat_ini.group_id = '$entitas_saat_ini' ";
        }

        // Filter entitas saat ikut pelatihan
        if ($entitas_saat_ikut !== '') {
            $addSql .= " AND group_saat_ikut.group_id = '$entitas_saat_ikut' ";
        }

        if ($cr_id !== '') {
            $addSql .= " AND _classroom.cr_id = '{$cr_id}' ";
        }

        if (!empty($nik)) {
            $addSql .= " AND _member.member_nip LIKE '%$nik%' ";
        }

        // ===== MAIN QUERY =====
        $sql = "
            SELECT
                _classroom.cr_name AS judul_pelatihan,
                _classroom.cr_date_start AS tanggal_mulai,
                _classroom.cr_date_end AS tanggal_selesai,
                _member.member_nip AS nik,
                _member.member_name AS nama,
                group_saat_ini.group_name AS entitas_saat_ini,
                group_saat_ikut.group_name AS entitas_saat_ikut_pelatihan,
                _member.member_email AS email,
                _member.member_phone AS nomor_telepon
            FROM _classroom_member
            INNER JOIN _classroom
                ON _classroom.cr_id = _classroom_member.cr_id
            INNER JOIN _member
                ON _member.member_id = _classroom_member.member_id
            LEFT JOIN _group AS group_saat_ini
                ON group_saat_ini.group_id = _member.group_id
            LEFT JOIN _group AS group_saat_ikut
                ON group_saat_ikut.group_id = _classroom_member.id_group
            WHERE 1
                $addSql $filter_akses
            ORDER BY _classroom.cr_date_start DESC
        ";

        // ===== COUNT QUERY =====
        $sql_count = "
            SELECT COUNT(*) AS total_data
            FROM _classroom_member
            INNER JOIN _classroom
                ON _classroom.cr_id = _classroom_member.cr_id
            INNER JOIN _member
                ON _member.member_id = _classroom_member.member_id
            LEFT JOIN _group AS group_saat_ini
                ON group_saat_ini.group_id = _member.group_id
            LEFT JOIN _group AS group_saat_ikut
                ON group_saat_ikut.group_id = _classroom_member.id_group
            WHERE 1
                {$addSql}
                {$filter_akses}
        ";

        // ===== EXPORT EXCEL =====
        if (isset($_GET['export']) && $_GET['export'] == 'excel') {

            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=biodata_peserta.xls");

            echo "<table border='1'>
                <tr>
                    <th>Judul Pelatihan</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>Entitas Saat Ini</th>
                    <th>Entitas Saat Ikut Pelatihan</th>
                    <th>Email</th>
                    <th>Nomor Telepon</th>
                </tr>";

            $exportData = $akses->doQuery($sql, 0, 'object');

            foreach ($exportData as $row) {
                echo "<tr>
                    <td>{$row->judul_pelatihan}</td>
                    <td>{$umum->date_indo($row->tanggal_mulai, 'dd FF YYYY')}</td>
                    <td>{$umum->date_indo($row->tanggal_selesai, 'dd FF YYYY')}</td>
                    <td>{$row->nik}</td>
                    <td>{$row->nama}</td>
                    <td>{$row->entitas_saat_ini}</td>
                    <td>{$row->entitas_saat_ikut_pelatihan}</td>
                    <td>{$row->email}</td>
                    <td>{$row->nomor_telepon}</td>
                </tr>";
            }

            echo "</table>";
            exit;
        }

        // ===== PAGINATION =====
        $limit      = 10;
        $page       = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $targetpage = BE_MAIN_HOST . '/' . $this->pageLevel1 . '/' . $this->pageLevel2;

        // URL pagination hanya dengan parameter page
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
}
