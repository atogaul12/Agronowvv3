<?php

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

if ($this->pageLevel1 !== "ikhtisar-nilai") {
    return;
}

$butuh_login = true;
$akses->isBolehAkses('NILAI_PESERTA', true);

if ($this->pageLevel2 !== "peserta") {
    return;
}

$this->setView("Daftar Nilai Peserta", "peserta", "");

$filter_akses = $umum->getFilterIkhtisarDataPelatihan();

$tgl_awal   = !empty($_GET['tgl_awal'])  ? $_GET['tgl_awal']  : '2025-01-01';
$tgl_akhir  = !empty($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : '2025-12-31';

$cat_id     = $_GET['cat_id']            ?? '';
$group_id   = $_GET['group_id']          ?? '';
$entitas_saat_ini  = $_GET['entitas_saat_ini']  ?? '';
$entitas_saat_ikut = $_GET['entitas_saat_ikut'] ?? '';
$cr_id      = $_GET['cr_id']             ?? '';
$nik        = !empty($_GET['nik']) ? $security->teksEncode($_GET['nik']) : '';

$listKategori = $akses->doQuery(
    "SELECT cat_id, cat_name 
     FROM _category 
     WHERE section_id = 30 AND cat_status = '1'
     ORDER BY cat_name ASC",
    0,
    'object'
);

$listGroup = $umum->getListGroupByAkses($akses);

$addSql = "
    AND _classroom.cr_date_start
    BETWEEN '{$tgl_awal} 00:00:00'
    AND '{$tgl_akhir} 23:59:59'
";

if ($cat_id !== '') {
    $addSql .= " AND _classroom.cat_id = '{$cat_id}' ";
}

if ($group_id !== '') {
    $addSql .= " AND _classroom_member.id_group = '{$group_id}' ";
}

if ($entitas_saat_ini !== '') {
    $addSql .= " AND group_saat_ini.group_id = '{$entitas_saat_ini}' ";
}

if ($entitas_saat_ikut !== '') {
    $addSql .= " AND group_saat_ikut.group_id = '{$entitas_saat_ikut}' ";
}

if ($cr_id !== '') {
    $addSql .= " AND _classroom.cr_id = '{$cr_id}' ";
}

if ($nik !== '') {
    $addSql .= " AND _member.member_nip LIKE '%{$nik}%' ";
}

$sql = "
    SELECT
        _classroom.cr_name AS judul_pelatihan,
        _classroom.cr_date_start AS tanggal_mulai,
        _classroom.cr_date_end AS tanggal_selesai,

        _classroom.cr_has_certificate AS sertifikat,
        _classroom.cr_has_pretest AS status_pre_test,
        _classroom.cr_has_kompetensi_test AS status_post_test,

        _member.member_nip AS nik,
        _member.member_name AS nama,

        group_saat_ini.group_name AS entitas_saat_ini,
        group_saat_ikut.group_name AS entitas_saat_ikut_pelatihan,

        _classroom_member.nilai_pre_test,
        _classroom_member.nilai_post_test,
        _classroom_member.berkas_sertifikat

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
    ORDER BY _classroom.cr_date_start DESC
";

$sql_count = "
    SELECT COUNT(*) AS total_data
    FROM _classroom_member
    INNER JOIN _classroom
        ON _classroom.cr_id = _classroom_member.cr_id
    INNER JOIN _member
        ON _member.member_id = _classroom_member.member_id
    LEFT JOIN _group AS group_saat_ikut
        ON group_saat_ikut.group_id = _classroom_member.id_group
    WHERE 1
        {$addSql}
        {$filter_akses}
";

if (isset($_GET['export']) && $_GET['export'] === 'excel') {

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=nilai_peserta.xls");

    echo "<table border='1'>
        <tr>
            <th>Judul Pelatihan</th>
            <th>Tanggal Mulai</th>
            <th>Tanggal Selesai</th>
            <th>NIK</th>
            <th>Nama</th>
            <th>Entitas Saat Ini</th>
            <th>Entitas Saat Ikut Pelatihan</th>
            <th>Status Pre Test</th>
            <th>Nilai Pre Test</th>
            <th>Status Post Test</th>
            <th>Nilai Post Test</th>
            <th>Sertifikat</th>
            <th>Berkas Sertifikat</th>
        </tr>";

    $rows = $akses->doQuery($sql, 0, 'object');

    foreach ($rows as $row) {
        echo "<tr>
            <td>{$row->judul_pelatihan}</td>
            <td>{$umum->date_indo($row->tanggal_mulai, 'dd FF YYYY')}</td>
            <td>{$umum->date_indo($row->tanggal_selesai, 'dd FF YYYY')}</td>
            <td>{$row->nik}</td>
            <td>{$row->nama}</td>
            <td>{$row->entitas_saat_ini}</td>
            <td>{$row->entitas_saat_ikut_pelatihan}</td>
            <td>" . ($row->status_pre_test ? 'Ada' : 'Tidak') . "</td>
            <td>{$row->nilai_pre_test}</td>
            <td>" . ($row->status_post_test ? 'Ya' : 'Tidak') . "</td>
            <td>{$row->nilai_post_test}</td>
            <td>" . ($row->sertifikat ? 'Ya' : 'Tidak') . "</td>
            <td>" . ($row->berkas_sertifikat ?: '-') . "</td>
        </tr>";
    }

    echo "</table>";
    exit;
}

$limit      = 10;
$page       = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$targetpage = BE_MAIN_HOST . '/' . $this->pageLevel1 . '/' . $this->pageLevel2;

$pagestring = "?" . http_build_query([
    'tgl_awal'          => $tgl_awal,
    'tgl_akhir'         => $tgl_akhir,
    'cat_id'            => $cat_id,
    'entitas_saat_ini'  => $entitas_saat_ini,
    'entitas_saat_ikut' => $entitas_saat_ikut,
    'cr_id'             => $cr_id,
    'nik'               => $nik,
    'page'              => ''
]);

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
