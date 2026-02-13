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
		
		if(isset($_SESSION['be'])) {
			$kode_session = $_SESSION['be']['kode_session'];
			// cek kode session sama ga dg di db?
			$sql = "select member_id from _member where member_id='".$_SESSION['be']['id_user']."' and kode_session='".$kode_session."' ";
			$data = $this->doQuery($sql,0,'object');
			if(!empty($data[0]->member_id)) {
				$is_logged_in = true;
			} else {
				// force logged out
				$this->insertLog('force logout ('.$kode_session.')','','');
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
		
		$sql = 
			"SELECT 
				a.member_id, a.member_password, a.member_status, a.member_name, 
				b.tipe_akses_member, 
				c.user_level_name, c.user_level_id, 
				d.group_id, d.silsilah, d.id_klien
			 FROM 
				_member a, _member_x_user b, _user_level c, _group d 
			 WHERE 
				a.member_id = b.id_member AND a.group_id = d.group_id AND b.user_level_id = c.user_level_id AND c.user_level_status='1'
				AND a.member_nip = '".$username."' ";
		
		$data = $this->doQuery($sql,0,'object');
		$id_user = $data[0]->member_id;
		$password_hash = $data[0]->member_password;
		$level = $data[0]->user_level_name;
		$id_akses_peran = $data[0]->user_level_id;
		$tipe_akses_member = $data[0]->tipe_akses_member;
		$status = $data[0]->member_status;
		$name = $data[0]->member_name;
		$id_group = $data[0]->group_id;
		$silsilah = $data[0]->silsilah;
		$id_klien = $data[0]->id_klien;
		
		$is_valid = false;
		if($status!='active') {
			$is_valid = false; 
		} else {
			if (md5(trim($password)) == $password_hash) {
				$is_valid = true; 
			} else {
				$is_valid = false; 
			}
		}
		
		if($is_valid==true) {
			$_SESSION['be'] = array();
			
			// update session code
			$kode_session = uniqid($id_user."LGN");
			$sql = "update _member set kode_session='".$kode_session."' where member_id='".$id_user."' ";
			mysqli_query($this->con, $sql);
			$_SESSION['be']['kode_session'] = $kode_session;
			
			// user detail
			$_SESSION['be']['id_user'] = $id_user;
			$_SESSION['be']['nama'] = $name;
			$_SESSION['be']['nik'] = $username;
			$_SESSION['be']['id_group'] = $id_group;
			$_SESSION['be']['id_klien'] = $id_klien;
			$_SESSION['be']['silsilah'] = $silsilah;
			
			// role
			$_SESSION['be']['level'] = $level;
			$_SESSION['be']['id_akses_peran'] = $id_akses_peran;
			$_SESSION['be']['tipe_akses_member'] = $tipe_akses_member;
			
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
			header("location:".BE_MAIN_HOST."/user/login?s=f");
			exit;
		}
	}
	
	// cek apakah super admin
	function isSA() {
		$flag = false;
		
		$id_user = $_SESSION['be']['id_user'];
		
		$sql = "select user_level_id from _member_x_user where id_member='".$id_user."' ";
		$data = $this->doQuery($sql,0,'object');
		if($data[0]->user_level_id==1) {
			$flag=true;
		}
		return $flag;
	}
	
	// cek hak akses; per aplikasi
	function isBolehAkses($app_name,$redirectIfFalse=false) {
		$flag = false;
		
		$id_user = $_SESSION['be']['id_user'];
		
		if($this->isSA()) {
			$flag = true;
		} else { 
			$sql = "select access_id from _access where access_code='".$app_name."' ";
			$data = $this->doQuery($sql,0,'object');
			$app_id = $data[0]->access_id;
			
			$sql = "SELECT id_member FROM _member_x_user a, _user_level b, _user_level_access c WHERE a.user_level_id = b.user_level_id AND b.user_level_id = c.user_level_id AND c.access_id like '%\"".$app_id."\"%' ";
			$data = $this->doQuery($sql,0,'object');
			if(!empty($data[0]->id_user)) {
				$flag = true;
			} else {
				$flag = false;
			}
		}
		
		if($redirectIfFalse==true && $flag==false) {
			header("location:".BE_MAIN_HOST."/fe/pesan?code=4");exit;
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