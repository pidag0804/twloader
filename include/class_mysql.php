<?php
class mysqlclass {
	var $db_id;
	var $querynum = 0;

	function connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect = 0) {
		if($pconnect) {
			if(!$this->db_id=@mysql_pconnect($dbhost, $dbuser, $dbpw)) {
				$this->mysql_errormsg('Can not connect to MySQL server');
			}
		} else {
			if(!$this->db_id=@mysql_connect($dbhost, $dbuser, $dbpw)) {
				$this->mysql_errormsg('Can not connect to MySQL server');
			}
		}
		@mysql_query("set names 'utf8'");
                @mysql_query("SET character_set_results=big5");
		@mysql_select_db($dbname);
	}

	function select_db($dbname) {
		return @mysql_select_db($dbname,$this->db_id);
	}

	function query($sql) {		
		if(!($query = @mysql_query($sql,$this->db_id))) {
				$this->mysql_errormsg('MySQL Query Error', $sql);
			}		
		$this->querynum++;		
		return $query;
	}
	
	function fetch_array($query, $result_type = MYSQL_ASSOC) {
		return @mysql_fetch_array($query, $result_type);
	}

	function fetch_row($query) {
		$query = @mysql_fetch_row($query);
		return $query;
	}
	
	function num_rows($query) {
		$query = @mysql_num_rows($query);
		return $query;
	}

	function insert_id() {
		$id = @mysql_insert_id();
		return $id;
	}
	
	function free_result($query) {
		return @mysql_free_result($query);
	}
	
	function close() {
		return @mysql_close();
	}
	
	function mysql_errormsg($message = '', $sql = '') {
		//echo $message."<br>".$sql."<br>";
		//echo $this->errno()." ".$this->error();
                echo "sql error";
		exit;
	}

	function affected_rows() {
		return @mysql_affected_rows();
		//		�Ǧ^�̫�d�߬�INSERT�BUPDATE��DELETE�Ҽv�T���C�ƥ�		
	}

	function error() {
		return @mysql_error();
		//�q���eMySQL�ާ@�Ǧ^���~�T��
	}

	function errno() {
		return @intval(mysql_errno());
		//�q���eMySQL�ާ@�Ǧ^���~�T���N��
	}

	function result($query, $row) {
		$query = @mysql_result($query, $row);
		return $query;
		//�qMySQL���G�Ǧ^�@��(cell)�����e,�Ĳv�C.
	}

	function num_fields($query) {
		return @mysql_num_fields($query);
		//�Ǧ^���G����쪺�ƥ�
	}	
}


$dbhost = 'localhost';			// �ƾڮw�A�Ⱦ�
$dbuser = 'i7f2_tbupus_loa';			// �ƾڮw�Τ�W
$dbpw =   'LJBC9T0Rxdk';				// �ƾڮw�K�X
$dbname = 'i7f2_tbupus_loader';	


$db = new mysqlclass;
$db->connect($dbhost,$dbuser,$dbpw,$dbname);
unset($dbhost, $dbuser, $dbpw, $dbname, $pconnect);

?>
