<?php
if($this->pageBase=="be"){
	$butuh_login = true; // harus login dl
	
	if($this->pageLevel1=="home") { // default page to show
		$this->setView("","beranda","");
	}
	else if($this->pageLevel1=="pesan") {
		$this->setView("Informasi","pesan","");
		
		$code = (int) $_GET['code'];
		$kat = "info";
		$pesan = "";
			 if($code=="1") { $kat="warning";$pesan="Gagal menyimpan data. Lihat manajemen log untuk melihat detail."; }
		else if($code=="2") { $kat="warning";$pesan="Data tidak ditemukan/Anda tidak diijinkan untuk mengakses halaman ini."; }
		else if($code=="3") { $kat="info";$pesan="Data berhasil disimpan."; }
		else if($code=="4") { $kat="warning";$pesan="Anda tidak diijinkan untuk mengakses halaman ini."; }
		
		$_SESSION['result_jenis'] = $kat;
		$_SESSION['result_info'] = $pesan;
	}
	else if($this->pageLevel1=="maintenis") {
		$butuh_login = false;
	}
}
?>