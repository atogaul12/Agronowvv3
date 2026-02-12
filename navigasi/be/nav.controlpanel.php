<?php

if($this->pageLevel1=="controlpanel"){
	$butuh_login = true; // harus login dl
	
	if($this->pageLevel2=="log"){
		$akses->isBolehAkses('CONTROL_PANEL_LOG',true);
		$this->setView("Manajemen Log","log","");
		
		if($_GET) {
			$idk = $security->teksEncode($_GET['idk']);
			$nk = $security->teksEncode($_GET['nk']);
			if(isset($_GET["kategori"])) $kategori = $security->teksEncode($_GET["kategori"]);
		}
		
		// hak akses
		$addSql = "";
		$limit_self_only = true;
		if($akses->isSA()) {
			$limit_self_only = false;
		}
		if($limit_self_only) {
			$addSql .= " and p.id_user='".$_SESSION['be']['id_user']."' ";
		}
		
		// pencarian
		if(!empty($idk)) {
			$arrP['id_user'] = $idk;
			$nk = $sdm->getData('nik_nama_karyawan_by_id',$arrP);
			$addSql .= " and p.id_user='".$idk."' ";
		}
		if(!empty($kategori)) { $addSql .= " and (p.kategori like '%".$kategori."%') "; }
		
		// paging
		$limit = 20;
		$page = 1;
		if(isset($_GET['page'])) $page = (int) $_GET['page'];
		$targetpage = BE_MAIN_HOST.'/'.$this->pageLevel1.'/'.$this->pageLevel2;
		$params = "idk=".$idk."&kategori=".$kategori."&page=";
		$pagestring = "?".$params;
		$link = $targetpage.$pagestring.$page;
		
		$sql =
			"select p.*
			 from _member_activity p
			 where 1 ".$addSql." order by p.tanggal desc";
		$sql_count =
			"select count(p.id) as total_data
			 from _member_activity p
			 where 1 ".$addSql." ";
		$arrPage = $umum->setupPaginationUI($sql,$akses->con,$limit,$page,$targetpage,$pagestring,"R",true,false,$sql_count);
		$data = $akses->doQuery($arrPage['sql'],0,'object');
	}
}
?>