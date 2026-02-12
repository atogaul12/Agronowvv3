<?php
class SDM extends db {
	
	var $recData;
	var $beginRec,$endRec;
	var $lastInsertId;
	
    function __construct() {
        $this->connect();
    }
	
	function get_akun_hashed($id_user) {
		$sql = "SELECT u.password_hash from sdm_user u, sdm_user_detail d where u.id_user=d.id_user and d.id_user='".$id_user."' ";
		$data = $this->doQuery($sql,0,'object');
		return $data[0]->password_hash;
	}
}
?>