<?php

if($this->pageLevel1=="ikhtisar"){
	$butuh_login = true; // harus login dl
	
	if($this->pageLevel2=="biodata"){
		include_once("nav.ikhtisar-biodata.php");
	} else if($this->pageLevel2=="nilai"){
		include_once("nav.ikhtisar-nilai.php");
	}
}
?>