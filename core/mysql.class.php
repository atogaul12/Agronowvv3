<?php

class db{
	var $db_host = DB_HOST;
	var $db_port = DB_PORT;
	var $db_uid = DB_USERNAME;
	var $db_pwd = DB_PASSWORD;
	var $db_name = DB_NAME;
	
	var $con;
	var $result;
	var $pagesize = 3;
	
	function __construct() {
		$this->connect();
		
	}
	
	function db($db_host="", $db_port="", $db_uid="", $db_pwd="", $db_name="") {
		if($db_host<>"") $this->db_host=$db_host;
		if($db_port<>"") $this->db_port=$db_port;
		if($db_uid<>"") $this->db_uid=$db_uid;
		if($db_pwd<>"") $this->db_pwd=$db_pwd;
		if($db_name<>"") $this->db_name=$db_name;
		$this->connect();
	}

	/**
	 * @return bool|resource
	 */
	function connect(){
		$host_port = $this->db_host;
		if(!empty($this->db_port)) $host_port .= ':'.$this->db_port;
		$this->con = (mysqli_connect($host_port, $this->db_uid, $this->db_pwd));
		if($this->con == false)
			return false;

		if(mysqli_select_db($this->con, $this->db_name) == false)
			return false;

		return $this->con;
	}
	
	function execute($varsql, $connection = 0) {
		if($connection == 0)
			$connection = $this->con;

		$this->result = mysqli_query($this->con, $varsql );

		if($this->result === false) {
			// if(isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST']!='localhost') exit;

			$error = mysqli_error($this->con);
			// die('Invalid SQL command : ' . $varsql . '<br>' . $error);
			die('Invalid SQL command : '.$error);
		}
		
		//$this->close();
		return $this->result;
	}

	function doQuery($varsql, $connection = 0, $resultType='array') {
		$link = $this->execute($varsql, $connection);

		if($link === false)
			return null;

		$resultSet = null;

		$i = 0;
		if($resultType=="array") {
			while ($row = @mysqli_fetch_assoc($link)){
				$resultSet[$i]=$row;
				$i++;
			}
		} else if($resultType=="object") {
			while ($row = @mysqli_fetch_object($link)){
				$resultSet[$i]=$row;
				$i++;
			}
		}

		return $resultSet;
	}

	function countQuery($varsql, $connection = 0) {
		$link = $this->execute($varsql, $connection);

		$result = mysqli_num_rows($link);

		return $result;
	}

	function close(){
		mysqli_close($this->con);
	}

    function securityMysql($var) {

        $ret = mysqli_escape_string($this->con, $var);

        return $ret;
    }

	function get_last_id() {
        return mysqli_insert_id($this->con);
    }
	
	function insertLogFromApp($kategori,$query,$query_err) {
		$id_user = $_SESSION['fe']['id_user'];
		
		$kategori = $GLOBALS['security']->teksEncode($kategori);
		$query = $GLOBALS['security']->teksEncode($query);
		$query_err = $GLOBALS['security']->teksEncode($query_err);
		
		$sql = "insert into _member_activity set  member_id='".$id_user."', member_activity_desc='".$kategori."',   ip_address='".$_SERVER['REMOTE_ADDR']."', member_activity_create_date=now() ";
		return $this->doQuery($sql);
	}
	
	function insertLog($kategori,$query,$query_err,$fromCron=false) {
		if($fromCron==false) {
			$id_user = $_SESSION['be']['id_user'];
		} else {
			$id_user = '1';
		}		
		
		$kategori =$GLOBALS['security']->teksEncode($kategori);
		$query = $GLOBALS['security']->teksEncode($query);
		$query_err = $GLOBALS['security']->teksEncode($query_err);
		
		$sql = "insert into _member_activity set  member_id='".$id_user."', member_activity_desc='".$kategori."',  ip_address='".$_SERVER['REMOTE_ADDR']."', member_activity_create_date=now() ";
		return $this->doQuery($sql);
	}

}
