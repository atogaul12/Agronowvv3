<?php

if($this->pageLevel1=="user"){
	$butuh_login = true; // harus login dl
	
	if($this->pageLevel2=="login") {
		$butuh_login = false; // override
		
		$s = isset($_GET['s']);
		if($s=="f") {
			$_SESSION['result_jenis'] = 'warning';
			$_SESSION['result_info'] = 'Anda terlogout otomatis karena login pada browser/device yang berbeda.';
		}
		
		// udah login?
		if(isset($_SESSION['be'])) {
			header("location:".BE_MAIN_HOST."");
			exit;
		}
		
		require_once(BE_TEMPLATE_PATH.'/login.php');
		exit;
	}
	else if($this->pageLevel2=="do_login") {
		if($_POST) {
			$username = $security->teksEncode($_POST['username']);
			$password = $security->teksEncode($_POST['password']);
			$arrH = $akses->cekAkun($username, $password);
			
			
			if($arrH['status']=="-1") {
				$_SESSION['result_jenis'] = 'warning';
				$_SESSION['result_info'] = $arrH['pesan'];
				header("location:".BE_MAIN_HOST."/user/login");
				exit;
			} else {
				$akses->insertLog('berhasil login ('.$_SESSION['be']['kode_session'].')','','');
				header("location:".BE_MAIN_HOST."");
				exit;
			}
		}
	}
	else if($this->pageLevel2=="logout") {
		$akses->insertLog('berhasil logout ('.$_SESSION['be']['kode_session'].')','','');
		$akses->doLogout();
		header("location:".BE_MAIN_HOST."/");
		exit;
	}
	else if($this->pageLevel2=="update_password") {
		$akses->isBolehAkses('LAINLAIN_UPDATE_PASSWORD_SELF',true);
		$this->setView("Update Password","update_password","");
		
		$id_user = $_SESSION['be']['id_user'];
		$strError = '';
		$pass_hash = $sdm->get_akun_hashed($id_user);
		
		if($_POST) {
			$oldPass = $security->teksEncode($_POST['OldPass']);
			$pass1 = $security->teksEncode($_POST['Pass1']);
			$pass2 = $security->teksEncode($_POST['Pass2']);
			
			$xpass = md5($pass1);
			$xpass_lama = md5($oldPass);
			
			if($xpass_lama != $pass_hash){
				$strError .= "<li>Kesalahan input password lama.</li>";
			}
			if($pass1==""){
				$strError .= "<li>Password baru tidak boleh kosong.</li>";
			}
			if($pass1!=$pass2){
				$strError .= "<li>Ulangi Password baru tidak cocok.</li>";
			}
			if(strlen($pass1)<PASSWORD_MIN_CHARS){
				$strError .= "<li>Password minimal 6 karakter.</li>";
			}
			if($oldPass==$pass1){
				$strError .= "<li>Password baru tidak boleh sama dengan password lama.</li>";
			}
			
			if(strlen($strError)>0) {
				$_SESSION['result_jenis'] = 'warning';
				$_SESSION['result_info'] = '<b>Tidak dapat memproses data</b>:<br/>'.$strError.'';
			} else {
				//$new_pass = password_hash($pass1, PASSWORD_BCRYPT);
				$sql = "update sdm_user set password_hash='".$xpass."' where id_user='".$id_user."' ";
				mysqli_query($sdm->con,$sql);
				
				$akses->insertLog('berhasil update password ('.$id_user.')','','');
				$_SESSION['result_jenis'] = 'info';
				$_SESSION['result_info'] = 'Password berhasil diubah.';
				
				header("location:".$umum->getThisPageURL());
				exit;
			}
		}
	}
	else if($this->pageLevel2=="panduan") {
		$butuh_login = false; // override
		
		require_once(BE_TEMPLATE_PATH.'/no_login.php');
		exit;
	}
}
?>