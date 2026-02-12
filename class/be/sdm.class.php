<?php
class SDM extends db {
	
	var $recData;
	var $beginRec,$endRec;
	var $lastInsertId;
	
    function __construct() {
        $this->connect();
    }
	
	function get_akun_hashed($id_user) {
		//$sql = "SELECT u.password_hash from sdm_user u, sdm_user_detail d where u.id_user=d.id_user and d.id_user='".$id_user."' ";
		$sql = "SELECT password from _member where member_id='".$id_user."' ";
		$data = $this->doQuery($sql,0,'object');
		return $data[0]->password;
	}
	
	function getData($kategori, $extraParams="") {
		$sql = "";
		$hasil = "";
		
		if(!empty($extraParams) && !is_array($extraParams)) {
			return 'extra param harus array';
		}
		
		if($kategori=="daftar_nik_nama_karyawan_by_nik_nama") {
			$keyword = $GLOBALS['security']->teksEncode($extraParams['keyword']);
			$m = $GLOBALS['security']->teksEncode($extraParams['m']);
			$s = $GLOBALS['security']->teksEncode($extraParams['s']);
			
			$addSql = "";
			
			if($m=="self_n_bawahan") {
				$dparam['id_user'] = $_SESSION['be']['id_user'];
				$hasil = $this->getData('self_n_bawahan',$dparam);
				$addSql .= " and d.id_user in (".$hasil.") ";
			} else if($m=="wo_atasan") {
				$lvl = $this->getData('level_karyawan_by_id',array('id_user'=>$_SESSION['be']['id_user']));
				$addSql .= " and d.level_karyawan>'".$lvl."' ";
			} else if($m=="all") {
				// do nothing
			}
			
			if($s=="all") {
				// do nothing
			} else {
				$addSql .= " and s.status='aktif' ";
			}
			
			$sql = "select d.id_user, concat('[',d.nik,'] ',d.nama) as nama from sdm_user_detail d, sdm_user s where s.id_user=d.id_user and s.level='50' and (d.id_user like '%".$keyword."%' or d.nik like '%".$keyword."%' or d.nama like '%".$keyword."%'  ) ".$addSql;
			$hasil = $this->doQuery($sql,0,'object');
		}
		else if($kategori=="nik_nama_karyawan_by_id") {
			$id_user = (int) $extraParams['id_user'];
			$all_level = (int) $extraParams['all_level'];
			
			$addSql = "";
			if($all_level=="1") {
				// do nothing
			} else {
				$addSql .= " and s.level='50' ";
			}
			
			$sql = "select concat('[',d.nik,'] ',d.nama) as nama from sdm_user_detail d, sdm_user s where s.id_user=d.id_user ".$addSql." and d.id_user='".$id_user."' ";
			$data = $this->doQuery($sql,0,'object');
			$hasil = $data[0]->nama;
		}
		
		return $hasil;
	}
	
	function getDataMember($idmember=0){
		$sql = "";
		$hasil = "";
		
		$sql = "SELECT member_name, member_nip from _member WHERE member_id='".$idmember."' ";
		$data = $this->doQuery($sql,0,'object');
		
		return $data;
	}
	
	function getDataGrup($idgrup=0){
		$sql = "";
		$hasil = "";
		
		$sql = "SELECT group_name, group_alias from _group WHERE group_id='".$idgrup."' "; 
		$data = $this->doQuery($sql,0,'object');
		
		return $data;
	}
	
	function cekRekap($idmember=0,$tahun=0){
		$sql = "SELECT COUNT(id) as jml from _learning_mp_rekap WHERE member_id='".$idmember."' AND tahun='".$tahun."' "; 
		$data = $this->doQuery($sql,0,'object');
		
		return $data[0]->jml;
	}
	
	function getRekap($idmember=0,$tahun=0){
		$sql = "SELECT id  from _learning_mp_rekap WHERE member_id='".$idmember."' AND tahun='".$tahun."' "; 
		$data = $this->doQuery($sql,0,'object');
		
		return $data[0]->id;
	}
	
	function hitungHari($tipe="",$iddata=0,$tgl_end){
		if($tipe == 'external'){
			$sql = "SELECT tgl_buat from _learning_formal_external WHERE id='".$iddata."' "; 
		}else if($tipe == 'social'){
			$sql = "SELECT tgl_buat from _learning_from_other WHERE id='".$iddata."' "; 	
		}else if($tipe == 'experience'){		
			$sql = "SELECT tgl_buat from _learning_from_experience WHERE id='".$iddata."' "; 
		}
		$data = $this->doQuery($sql,0,'array');
				
		$sqlx = "SELECT DATEDIFF('".$data[0]['tgl_buat']."', '".$tgl_end."') as hari;";
		$datax = $this->doQuery($sqlx,0,'array');
		
		$hari = abs($datax[0]['hari']); 
		
		return $hari;		
	}
	
	function updateRekap($tipe="",$xtipe="",$id=0,$idmember=0,$tahun=0,$id_grup=0,$jpl_total=0,$jpl_total_diakui=0,$jpl_pending=0,$jpl_realisasi=0,$jpl_persen=0){
		
		if($xtipe == 'experience'){
			$target = 10;
			if($jpl_realisasi > $target) $jpl_realisasi_diakui = $target;
			else $jpl_realisasi_diakui = $jpl_realisasi;
			
			$jpl_persen = ($jpl_realisasi_diakui / $target) * 100;
			
			$sqlt = "jpl70_realisasi = '".$jpl_realisasi."',
					jpl70_realisasi_diakui = '".$jpl_realisasi_diakui."',
					jpl70_pending = '".$jpl_pending."',
					jpl70_persen = '".$jpl_persen."',	
				";
		}else if($xtipe == 'social'){
			$target = 8;
			if($jpl_realisasi > $target) $jpl_realisasi_diakui = $target;
			else $jpl_realisasi_diakui = $jpl_realisasi;
			
			$jpl_persen = ($jpl_realisasi_diakui / $target) * 100;
			
			$sqlt = "jpl20_realisasi = '".$jpl_realisasi."',
					jpl20_realisasi_diakui = '".$jpl_realisasi_diakui."',
					jpl20_pending = '".$jpl_pending."',
					jpl20_persen = '".$jpl_persen."',	
				";
		}else if($xtipe == 'external'){
			$target = 22;
			if($jpl_realisasi > $target) $jpl_realisasi_diakui = $target;
			else $jpl_realisasi_diakui = $jpl_realisasi;
			
			$jpl_persen = ($jpl_realisasi_diakui / $target) * 100;
			
			$sqlt = "jpl10_realisasi = '".$jpl_realisasi."',
					jpl10_realisasi_diakui = '".$jpl_realisasi_diakui."',
					jpl10_pending = '".$jpl_pending."',
					jpl10_persen = '".$jpl_persen."',	
				";
		}	
		
		if($tipe == 'update'){
			$sql = "UPDATE _learning_mp_rekap SET 
						".$sqlt."
						member_id = '".$idmember."',
						tahun = '".$tahun."',
						id_group = '".$id_grup."',
						jpl_total = '".$jpl_total."',
						jpl_total_diakui = '".$jpl_total_diakui."',
						tgl_update = now()
					WHERE id = '".$id."'
					";	
		}else if($tipe == 'insert'){
			$sql = "INSERT INTO _learning_mp_rekap SET 
						".$sqlt."
						member_id = '".$idmember."',
						tahun = '".$tahun."',
						id_group = '".$id_grup."',
						jpl_total = '".$jpl_total."',
						jpl_total_diakui = '".$jpl_total_diakui."',
						tgl_update = now()
					";	
		}		
		
		return $this->doQuery($sql);
	}
	
	function cekJPL($tipe="",$xtipe="",$idmember=0,$tahun=0){
		if($xtipe == 'experience'){
			$tabel = "_learning_from_experience";
			$stat_terima = 'diterima';
			$stat_pending = 'sedang diperiksa';
		}else if($xtipe == 'social'){	
			$tabel = "_learning_from_other";	
			$stat_terima = 'disetujui';
			$stat_pending = 'sedang direview';
		}else if($xtipe == 'external'){
			$tabel = "_learning_formal_external";
			$stat_terima = 'disetujui';
			$stat_pending = 'sedang direview';
		}	
		
		if($tipe == 'realisasi'){
			$sql = "SELECT SUM(jpl) as jml FROM ".$tabel." 
					WHERE status_laporan = '".$stat_terima."' 
							AND tahun = '".$tahun."' 
							AND member_id = '".$idmember."' 
				";	
		}else if($tipe == 'pending'){	
			$sql = "SELECT SUM(jpl) as jml FROM ".$tabel." 
					WHERE status_laporan = '".$stat_pending."' 
							AND tahun = '".$tahun."' 
							AND member_id = '".$idmember."' 
				";		
		}
		
		$data = $this->doQuery($sql,0,'object');
		return $data[0]->jml;
	}
	
	function getKategoriLearning($id=0){
		$sql = "";
		$hasil = "";
		
		$sql = "SELECT id, kategori, nama from _learning_kategori WHERE id='".$id."' "; 
		$data = $this->doQuery($sql,0,'object');
		
		return $data;
	}
	
	function totalJPL($idmember=0,$tahun=0){
		$sql_1 = "SELECT SUM(jpl) as jml FROM _learning_formal_external 
					WHERE status_laporan = 'disetujui' 
							AND tahun = '".$tahun."' 
							AND member_id = '".$idmember."' 
				";	
				
		$data_1 = $this->doQuery($sql_1,0,'object');	
		$jml_1 = $data_1[0]->jml;
		
		$sql_2 = "SELECT SUM(jpl) as jml FROM _learning_from_other 
					WHERE status_laporan = 'disetujui' 
							AND tahun = '".$tahun."' 
							AND member_id = '".$idmember."' 
				";	
				
		$data_2 = $this->doQuery($sql_2,0,'object');	
		$jml_2 = $data_2[0]->jml;
		
		$sql_3 = "SELECT SUM(jpl) as jml FROM _learning_from_experience 
					WHERE status_laporan = 'diterima' 
							AND tahun = '".$tahun."' 
							AND member_id = '".$idmember."' 
				";	
				
		$data_3 = $this->doQuery($sql_3,0,'object');	
		$jml_3 = $data_3[0]->jml;


		$total_all = $jml_1 + $jml_2 + $jml_3;	
		
		return $total_all;
	}
	
		
}
?>