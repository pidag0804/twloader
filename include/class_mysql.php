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
        @mysql_query("SET character_set_results=utf8"); // 修正為 utf8 以避免亂碼
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
    
    // --- START: 新增的函式 ---
    function real_escape_string($string) {
        // 檢查連線是否存在，再執行 mysql_real_escape_string
        if($this->db_id) {
            return @mysql_real_escape_string($string, $this->db_id);
        }
        // 如果沒有連線，也回傳一個經過基本處理的字串，避免錯誤
        return @mysql_real_escape_string($string);
    }
    // --- END: 新增的函式 ---

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
	}

	function error() {
		return @mysql_error();
	}

	function errno() {
		return @intval(mysql_errno());
	}

	function result($query, $row) {
		$query = @mysql_result($query, $row);
		return $query;
	}

	function num_fields($query) {
		return @mysql_num_fields($query);
	}	
}


$dbhost = 'localhost';
$dbuser = 'i7f2_tbupus_loa';
$dbpw =   'LJBC9T0Rxdk';
$dbname = 'i7f2_tbupus_loader';	


$db = new mysqlclass;
$db->connect($dbhost,$dbuser,$dbpw,$dbname);
unset($dbhost, $dbuser, $dbpw, $dbname, $pconnect);

?>