<?php

if($this->pageLevel1=="dev"){
	$butuh_login = true; // harus login dl
	
	if($this->pageLevel2=="hak_akses"){
		$akses->isBolehAkses('DEV_TOOLKIT',true);
		$this->setView("Master Hak Akses","mha","");
		
		if($_GET) {
			$label = $security->teksEncode($_GET['label']);
		}
		
		// pencarian
		if(!empty($label)) { $addSql .= " and (label like '%".$label."%') "; }
		
		// paging
		$limit = 20;
		$page = 1;
		if(isset($_GET['page'])) $page = (int) $_GET['page'];
		$targetpage = BE_MAIN_HOST.'/'.$this->pageLevel1.'/'.$this->pageLevel2;
		$params = "label=".$label."&page=";
		$pagestring = "?".$params;
		$link = $targetpage.$pagestring.$page;
		
		$sql =
			"select *
			 from akses
			 where 1 ".$addSql." order by id desc";
		$sql_count =
			"select count(id) as total_data
			 from akses
			 where 1 ".$addSql." ";
		$arrPage = $umum->setupPaginationUI($sql,$akses->con,$limit,$page,$targetpage,$pagestring,"R",true,false,$sql_count);
		$data = $akses->doQuery($arrPage['sql'],0,'object');
	}
	else if($this->pageLevel2=="blank"){
		$akses->isBolehAkses('DEV_TOOLKIT',true);
		$this->setView("Template Blank Page","blank","");
	}
	else if($this->pageLevel2=="chart"){
		$akses->isBolehAkses('DEV_TOOLKIT',true);
		$this->setView("Contoh Chart (1)","chart","");
		
		$this->pageJS =
			'<script>$(".data-attributes span").peity("donut")</script>
			<script src="'.BE_ASET_HOST.'/assets/js/dashboard1.js"></script>
			<script>new PerfectScrollbar(".user-list")</script>';
	}
	else if($this->pageLevel2=="chart2"){
		$akses->isBolehAkses('DEV_TOOLKIT',true);
		$this->setView("Contoh Chart (2)","chart2","");
		
		$this->pageJS =
			'<script src="'.BE_ASET_HOST.'/assets/js/data-widgets.js"></script>';
	}
}
?>