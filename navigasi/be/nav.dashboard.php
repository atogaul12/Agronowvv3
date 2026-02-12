<?php

if($this->pageLevel1=="dashboard"){
	$butuh_login = true; // harus login dl
	
	if($this->pageLevel2=="insight_jpl"){
		$akses->isBolehAkses('DEV_UNCATEGORIES_YET',true);
		$this->setView("Rekapitulasi JPL","insight_jpl","");
		
		/* if($_GET) {
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
		$data = $akses->doQuery($arrPage['sql'],0,'object'); */
	}
	else if($this->pageLevel2=="review_lap_learning"){
		if($this->pageLevel3==""){
			$akses->isBolehAkses('DEV_UNCATEGORIES_YET',true);
			$this->setView("Review Laporan Learning Karyawan","review_lap_learning","");
		}
		else if($this->pageLevel3=="periksa"){
			$akses->isBolehAkses('DEV_UNCATEGORIES_YET',true);
			$this->setView("Periksa Laporan Learning Karyawan","review_lap_learning_update","");
		}
		else if($this->pageLevel3=="detail"){
			$akses->isBolehAkses('DEV_UNCATEGORIES_YET',true);
			$this->setView("Detail Laporan Learning Karyawan","review_lap_learning_detail","");
			
			$status_ui = '';
			$id = (int) $_GET['id'];
			
			if($id=="1") {
				$status_ui = '<div class="alert alert-success">Laporan ini disetujui oleh XXX pada YYYY-MM-DD HH:ii:ss.</div>';
			} else if($id=="2") {
				$status_ui = '<div class="alert alert-warning">Laporan ini dikembalikan ke karyawan oleh XXX pada YYYY-MM-DD HH:ii:ss, dengan alasan data belum lengkap.</div>';
			} else if($id=="3") {
				$status_ui = '<div class="alert alert-danger">Laporan ini ditolak oleh XXX pada YYYY-MM-DD HH:ii:ss, dengan alasan laporan abal-abal.</div>';
			}
		}
	}
}
?>