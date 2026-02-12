<?php
//frameworkv2/class/umum.class.php
/*
 *
 * tempat untuk menambahkan fungsi2 baru yg belum ada di class func
 *
 */

class Umum extends func
{
	function __construct() {}

	function getKategori($tipe)
	{
		$arr = array();
		$arr[''] = "";
		if ($tipe == "ya_tidak") {
			$arr['1'] = "Ya";
			$arr['0'] = "Tidak";
		} else if ($tipe == "status_data") {
			$arr['draft'] = "Draft";
			$arr['publish'] = "Publish";
		}

		return $arr;
	}

	function reformatArrayFromVT($arr)
	{
		$arrH = array();
		$arrH[''] = '';
		foreach ($arr as $key => $id_user) {
			$sql = "select concat('[',d.nik,'] ',d.nama) as nama from sdm_user_detail d, sdm_user s where s.id_user=d.id_user and s.level=50 and d.id_user='" . $id_user . "' ";
			$res = mysqli_query($GLOBALS['notif']->con, $sql);
			$row = mysqli_fetch_object($res);
			$arrH[$id_user] = $row->nama;
		}
		return $arrH;
	}

	function getThisPageURL()
	{
		return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	}

	function cekFile($dFile, $kat, $ket, $fileWajibAda, $target_w = 0, $target_h = 0, $target_fsize = 0, $target_ext = '')
	{
		$strError = "";
		$tmp_name = $dFile['tmp_name'];
		$filetype = $dFile['type'];
		$filesize = $dFile['size'];
		$filename = $dFile['name'];
		$path_parts = pathinfo($filename);
		if (is_uploaded_file($tmp_name)) {
			// check 1
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$mime = finfo_file($finfo, $tmp_name);
			finfo_close($finfo);

			// check 2
			$size2 = @getimagesize($tmp_name);

			if ($kat == "logo") {
				if ($filesize > LOGO_FILESIZE) $strError .= "<li>Ukuran file " . $ket . " maksimal " . (LOGO_FILESIZE / 1024) . " KB! Ukuran file yg hendak diupload: " . round($filesize / 1024) . " KB.</li>";
				if ($size2[2] != 3 || $mime != "image/png") $strError .=  "<li>Tipe file " . $ket . " harus PNG.</li>";
			} else if ($kat == "avatar") {
				if ($filesize > FOTO_FILESIZE) $strError .= "<li>Ukuran file " . $ket . " maksimal " . (FOTO_FILESIZE / 1024) . " KB! Ukuran file yg hendak diupload: " . round($filesize / 1024) . " KB.</li>";
				if ($size2[2] != 2 || $mime != "image/jpeg") $strError .= "<li>Tipe file " . $ket . " harus JPG.</li>";
			} else if ($kat == "dok_file") {
				if ($filesize > DOK_FILESIZE) $strError .= "<li>Ukuran file " . $ket . " maksimal " . (DOK_FILESIZE / 1024) . " KB! Ukuran file yg hendak diupload: " . round($filesize / 1024) . " KB.</li>";
				if (strtolower($path_parts['extension']) != strtolower('pdf') || $mime != "application/pdf") $strError .= "<li>Tipe file " . $ket . " harus PDF.</li>";
			} else if ($kat == "csv") {
				$e = 0;
				if ($mime != "text/csv" && $mime != "text/plain") $e++;
				if (strtolower($path_parts['extension']) != strtolower('csv')) $e++;
				if ($e > 0) $strError .= "<li>Tipe file " . $ket . " harus CSV.</li>";
			}
		} else {
			if ($fileWajibAda == true) $strError .=  "<li>Silahkan memilih file " . $ket . " yang akan diupload.</li>";
		}
		return $strError;
	}

	function date_indo($data, $format = "")
	{
		if (substr($data, 0, 10) == "0000-00-00") {
			$newDate = "-";
		} else {
			$newDate = "";
			$arrMonth = $this->arrMonths("id");

			$bulan2 = (int) substr($data, 5, 2);

			$day = substr($data, 8, 2);
			$month = $arrMonth[$bulan2];
			$year = substr($data, 0, 4);
			$hour = substr($data, 11, 2);
			$minute = substr($data, 14, 2);
			$second = substr($data, 17, 2);

			$newDate = $day . " " . substr($month, 0, 3) . " " . $year;
			if ($format == "dd FF YYYY") {
				$newDate = $day . " " . $month . " " . $year;
			} elseif ($format == "datetime") {
				$newDate = $day . " " . substr($month, 0, 3) . " " . $year . " " . $hour . ":" . $minute . ":" . $second;
			} elseif ($format == "time") {
				$newDate = $hour . ":" . $minute . ":" . $second;
			} elseif ($format == "dd-mm-YYYY") {
				$newDate = $day . "-" . substr($data, 5, 2) . "-" . $year;
			}
		}
		return $newDate;
	}

	function tgl2detik($tanggal, $separator = "-")
	{
		// default >> format:D-M-Y; contoh: 01-01-2010
		$tanggal_a = explode($separator, $tanggal);
		return adodb_mktime(0, 0, 0, $tanggal_a['1'], $tanggal_a['0'], $tanggal_a['2']);
	}

	function tglJam2detik($tanggal, $separator = " ", $separator1 = "-", $separator2 = ":")
	{
		// default >> format:D-M-Y H:i:s; contoh: 01-01-2010 23:59:59
		$tanggal_a = explode($separator, $tanggal);
		if (empty($tanggal_a['0'])) $tanggal_a['0'] = adodb_date("d-m-Y");
		if (empty($tanggal_a['1'])) $tanggal_a['1'] = adodb_date("H:i:s");
		$tgl = explode($separator1, $tanggal_a['0']);
		$jam = explode($separator2, $tanggal_a['1']);
		return adodb_mktime($jam['0'], $jam['1'], $jam['2'], $tgl['1'], $tgl['0'], $tgl['2']);
		exit;
	}

	function filterTanggalFromListTanggal($list, $target_bulan, $target_tahun)
	{
		$list = $GLOBALS['security']->teksEncode($list);
		$target_bulan = (int) $target_bulan;
		$target_tahun = (int) $target_tahun;
		$arrL = explode(',', $list);
		$hasil = array();
		$i = 0;
		foreach ($arrL as $key => $val) {
			$arrD = explode('-', $val);
			$dtahun = (int) $arrD[0];
			$dbulan = (int) $arrD[1];
			$dtgl = (int) $arrD[2];
			if ($dtahun == $target_tahun && $dbulan == $target_bulan) {
				$hasil[$i]['tahun'] = $dtahun;
				$hasil[$i]['bulan'] = $dbulan;
				$hasil[$i]['tgl'] = $dtgl;
				$i++;
			}
		}
		return $hasil;
	}

	function reformatBaseNominalMH($number)
	{
		return number_format($number, DEF_MANHOUR_DIGIT_BELAKANG_KOMA, ',', '');
	}

	function prettifyPersen($persen)
	{
		$persen = $GLOBALS['umum']->reformatNilai($persen);
		$code = $persen;
		$is_negatif = false;
		$prefix = '';
		if ($persen < 0) {
			$is_negatif = true;
			$persen = abs($persen);
			$prefix = '-';
		}

		if ($persen < 10) {
			$code = $prefix . "00" . $persen;
		} else if ($persen < 100) {
			$code = $prefix . "0" . $persen;
		}
		return $code;
	}

	function prettifyID($id)
	{
		$code = $id;
		if ($id < 10) {
			$code = "0000" . $id;
		} else if ($id < 100) {
			$code = "000" . $id;
		} else if ($id < 1000) {
			$code = "00" . $id;
		} else if ($id < 10000) {
			$code = "0" . $id;
		}
		return $code;
	}

	/*
	 * pembulatan ke atas dengan pendekatan to
	 * misal: 
	 * to: 15; kalo nomor: 3, output: 15
	 * to: 15; kalo nomor: 16, output: 30
	 */
	function ceilTo($number, $to)
	{
		return ceil($number / $to) * $to;
	}

	function reformatJam4Chart($jam)
	{
		$arrT = explode(':', $jam);
		$arrT[0] = (int) $arrT[0];
		$arrT[1] = (int) $arrT[1];
		return $arrT[0] . '.' . $arrT[1];
	}

	function reformatTglDB($date_time, $format)
	{
		$hasil = $date_time;
		$arr = explode(" ", $date_time);
		if ($format == "d m H:i") {
			$arrMonth = $this->arrMonths("id");

			$arr1 = explode("-", $arr[0]);
			$arr2 = explode(":", $arr[1]);

			$month = substr($arrMonth[$arr1[1]], 0, 3);

			$hasil = $arr1[2] . ' ' . $month . ' ' . $arr2[0] . ':' . $arr2[1];
		}
		return $hasil;
	}

	function is_base64_string($string)
	{
		if (!preg_match('/^(?:[data]{4}:(text|image|application)\/[a-z]*)/', $string)) {
			return false;
		} else {
			return true;
		}
	}

	// banner push
	function setup_banner($img_url, $detail_url)
	{
		$arr = array();
		$arr['img'] = $img_url;
		$arr['url'] = $detail_url;
		return $arr;
	}

	function generateRandFileName($fromApp, $suffix, $ekstensi)
	{
		$suffix = $GLOBALS['security']->teksEncode($suffix);
		$ekstensi = $GLOBALS['security']->teksEncode($ekstensi);
		$ekstensi = strtolower($ekstensi);

		$nama_file = uniqid() . '_' . $suffix . '.' . $ekstensi;
		return $nama_file;
	}

	// $unique_prefix: diisi dengan id data dari database untuk memastikan kodenya 100% unik
	function generateRandCodeMySql($unique_prefix)
	{
		$prefix = $GLOBALS['security']->teksEncode($prefix);
		$sql = "select CONCAT('" . $unique_prefix . "','.',HEX(RAND()*0xFFF),'.',HEX(RAND()*0xFFF)) as dkode";
		$res = mysqli_query($GLOBALS['notif']->con, $sql);
		$row = mysqli_fetch_object($res);
		$dkode = $row->dkode;

		return $dkode;
	}

	function is_akses_readonly($kat, $mode)
	{
		$hasil = "";

		if ($kat == "manpro" && strtolower($_SESSION['sess_admin']['singkatan_unitkerja']) == "trs") {
			if ($mode == "error_message") {
				$hasil = '<li>Anda hanya memiliki akses readonly pada halaman ini.</li>';
			} else if ($mode == "true_false") {
				$hasil = "1";
			}
		}

		return $hasil;
	}
	
	// ========== FUNGSI BARU UNTUK SISTEM TERINTEGRASI ==========

	/**
	 * Get filter untuk ikhtisar data pelatihan berdasarkan level akses user
	 */
	function getFilterIkhtisarDataPelatihan()
	{
		$user_login_group_id   = $_SESSION['be']['id_group'] ?? null;
		$user_login_silsilah   = $_SESSION['be']['silsilah'] ?? '';
		$user_login_tipe_akses = $_SESSION['be']['tipe_akses_member'] ?? 'self';

		$filter = "";

		if ($user_login_tipe_akses == "super_admin") {
			// full access
			$filter = "";
		} else if ($user_login_tipe_akses == "as_parent") {
			if (!empty($user_login_silsilah)) {
				$filter = " AND group_saat_ikut.silsilah LIKE '" . $user_login_silsilah . "%' ";
			}
		} else {
			$filter = " AND group_saat_ikut.group_id = '" . $user_login_group_id . "' ";
		}

		return $filter;
	}

	/**
	 * Get list group berdasarkan akses user login
	 */
	function getListGroupByAkses($akses)
	{
		$user_login_group_id   = $_SESSION['be']['id_group'] ?? null;
		$user_login_silsilah   = $_SESSION['be']['silsilah'] ?? '';
		$user_login_tipe_akses = $_SESSION['be']['tipe_akses_member'] ?? 'self';

		if ($user_login_tipe_akses == "super_admin") {
			$sql = "
				SELECT group_id, group_name, silsilah
				FROM _group
				WHERE group_status='active'
				ORDER BY silsilah ASC
			";
		} else if ($user_login_tipe_akses == "as_parent") {
			if (!empty($user_login_silsilah)) {
				$sql = "
					SELECT group_id, group_name, silsilah
					FROM _group
					WHERE group_status='active'
					  AND silsilah LIKE '" . $user_login_silsilah . "%'
					ORDER BY silsilah ASC
				";
			} else {
				return array();
			}
		} else {
			$sql = "
				SELECT group_id, group_name, silsilah
				FROM _group
				WHERE group_status='active'
				  AND group_id = '" . $user_login_group_id . "'
				ORDER BY silsilah ASC
			";
		}

		return $akses->doQuery($sql, 0, 'object');
	}
}
