<?php
class Akses extends db {
	
	var $recData;
	var $beginRec,$endRec;
	var $lastInsertId;
	
    function __construct() {
        $this->connect();
    }
	
	function isLoggedIn() {
		$is_logged_in = false;
		
		if(isset($_SESSION['fe'])) {
			$kode_session = $_SESSION['fe']['kode_session'];
			// cek kode session sama ga dg di db?
			$sql = "select id_user from sdm_user where id_user='".$_SESSION['fe']['id_user']."' and kode_session='".$kode_session."' ";
			$data = $this->doQuery($sql,0,'object');
			if(!empty($data[0]->id_user)) {
				$is_logged_in = true;
			} else {
				// force logged out
				$this->insertLogFromApp('force logout app ('.$kode_session.')','','');
				$this->doLogout(true);
			}
		} else {
			$is_logged_in = false;
		}
		return $is_logged_in;
	}
	
	function cekAkun($username, $password) {
		$arrH = array();
		$username = $GLOBALS['security']->teksEncode($username);
		$password = $GLOBALS['security']->teksEncode($password);
		
		$sql = "select u.id_user, u.password_hash, u.level, u.id_akses_peran, u.status from sdm_user u, sdm_user_detail d where u.id_user=d.id_user and u.level='50' and d.nik='".$username."' ";
		$data = $this->doQuery($sql,0,'object');
		$id_user = $data[0]->id_user;
		$password_hash = $data[0]->password_hash;
		$level = $data[0]->level;
		$id_akses_peran = $data[0]->id_akses_peran;
		$status = $data[0]->status;
		
		$is_valid = false;
		if($status!='aktif') {
			$is_valid = false;
		} else {
			if (password_verify($password, $password_hash)) {
				$is_valid = true;
			} else {
				$is_valid = false;
			}
		}
		
		if($is_valid==true) {
			$_SESSION['fe'] = array();
			
			// update session code
			$kode_session = uniqid($id_user."LGN");
			$sql = "update sdm_user set kode_session='".$kode_session."' where id_user='".$id_user."' ";
			mysqli_query($this->con, $sql);
			$_SESSION['fe']['kode_session'] = $kode_session;
			
			// user detail
			$sql = "select id_user, nama, nik from sdm_user_detail where id_user='".$id_user."' ";
			$data = $this->doQuery($sql,0,'object');
			$_SESSION['fe']['id_user'] = $data[0]->id_user;
			$_SESSION['fe']['nama'] = $data[0]->nama;
			$_SESSION['fe']['nik'] = $data[0]->nik;
			
			// role
			$_SESSION['fe']['level'] = $level;
			$_SESSION['fe']['id_akses_peran'] = $id_akses_peran;
		
			$arrH['status'] = 1;
			$arrH['pesan'] = 'Login berhasil.';
		} else {
			$arrH['status'] = -1;
			$arrH['pesan'] = 'Login gagal. Silakan cek kembali informasi akun Anda.';
		}
		
		return $arrH;
	}

	function doLogout($is_forced=false) {
		if(isset($_COOKIE['userId'])) {
			$time = time();
			setcookie("userId",-1,$time - 2592000,"/");
		}
		session_destroy();
		if($is_forced) {
			header("location:".FE_MAIN_HOST."/user/login?s=f");
			exit;
		}
	}
	
	// cek apakah super admin
	function isSA() {
		$flag = false;
		
		$id_user = $_SESSION['fe']['id_user'];
		
		$sql = "select level from sdm_user where id_user='".$id_user."' ";
		$data = $this->doQuery($sql,0,'object');
		if($data[0]->level==1001) {
			$flag=true;
		}
		return $flag;
	}
	
	// cek hak akses; per aplikasi
	function isBolehAkses($app_name,$redirectIfFalse=false) {
		$flag = false;
		
		$id_user = $_SESSION['fe']['id_user'];
		
		if($this->isSA()) {
			$flag = true;
		} else {
			$sql = "select id from akses where label='".$app_name."' ";
			$data = $this->doQuery($sql,0,'object');
			$app_id = $data[0]->id;
			
			$sql = "select u.id_user from sdm_user u, akses_peran p where u.id_user='".$id_user."' and u.id_akses_peran=p.id and p.hak_akses like '%\"".$app_id."\"%' ";
			$data = $this->doQuery($sql,0,'object');
			if(!empty($data[0]->id_user)) {
				$flag = true;
			} else {
				$flag = false;
			}
		}
		
		if($redirectIfFalse==true && $flag==false) {
			header("location:".FE_MAIN_HOST."/fe/pesan?code=4");exit;
		} else {
			return $flag;
		}
	}
	
	// setup css untuk menu di sidebar
	function setupCSSSidebar($app_name) {
		$css = "";
		$isAllowed = $this->isBolehAkses($app_name,false);
		if(!$isAllowed) $css = "d-none";
		return $css;
	}
}
?>