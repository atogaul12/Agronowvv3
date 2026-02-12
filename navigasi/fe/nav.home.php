<?php
if($this->pageBase=="fe") {
	$butuh_login = false; // harus login dl
	
	if($this->pageLevel1=="home") { // default page to show
		$this->setView("","beranda","");
	}
}
?>