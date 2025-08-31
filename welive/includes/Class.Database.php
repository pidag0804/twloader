<?php
// +---------------------------------------------+
// |     Copyright  2010 - 2028 WeLive           |
// |     http://www.weentech.com                 |
// |     This file may not be redistributed.     |
// +---------------------------------------------+

if(!defined('WELIVE')) die('File not found!');

/*
	使用原生SQL語句查詢:
	1. 取得select查詢結果集, 使用getAll($query)或getOne($query)函數
	2. 取得select查詢資源id, 使用query($query)函數, 使用fetch函數遍歷
	3. insert|delete|update|replace查詢使用exe($query)函數
 */

class MySQL{
	var $dbname = ''; //儲存目前資料庫名, 用於多資料操作時回選上一個資料庫為目前資料庫
	var $dbcharset = 'utf8';
	var $conn = 0; //目前連接資源id
	var $insert_id = 0; //insert|replace語句最後插入的id
	var $query_id = 0; //最後查詢id
	var $query_nums = 0; //總計查詢次數
	var $result_nums = 0; //查詢結果數或查詢影響的記錄數
	var $printerror = true; //是否打印查詢錯誤內容
	var $errno = 0; //資料庫訪問錯誤程式碼

	/*
	 * 構造函數 - 建立資料庫伺服器連接, 並選擇資料庫
	 */
	function MySQL($dbuser='', $dbpassword='', $dbname='', $dbhost='localhost', $pconnect=false, $printerror=true) {
		$this->printerror = $printerror;
		$this->conn = $pconnect ? @mysql_pconnect($dbhost, $dbuser, $dbpassword) : @mysql_connect($dbhost, $dbuser, $dbpassword, true);

		if (!$this->conn)	{
			$this->error('Connect database failed! The dbuser, dbpassoword or dbhost not correct.');
		}

		$dbVersion = @mysql_get_server_info($this->conn);
		if ($dbVersion >= "4.1") {
			@mysql_query("SET NAMES '".$this->dbcharset."'", $this->conn); //使用UTF8存取資料庫, mysql 4.1以上支援
		}
		
		if($dbVersion > '5.0.1'){
			@mysql_query("SET sql_mode=''", $this->conn); //設定sql_model
		}

		$this->select_db($dbname);
	}

	/*
	 * 選擇資料庫, 用於選擇不同的資料庫或未選擇資料庫進行多庫操作, 不需要任何返回值, 如果有錯誤, 在查詢語句中將輸出
	 */
	function select_db($dbname)	{
		$this->dbname = $dbname;
		@mysql_select_db($dbname, $this->conn);
	}

	/*
	 * 只能是"insert|delete|update|replace", select查詢使用getAll或getOne或query
	 * @return 返回受影響行數, 在"insert|replace"的情況下, 用 $this->insert_id 記錄新插入的ID
	 */
	function exe($query)	{
		$this->query_nums++;

		$this->query_id = @mysql_query($query, $this->conn);
		if (!$this->query_id){
			$this->error("Invalid SQL: ".$query); //查詢失敗輸出錯誤
		}

		if (preg_match("/^(insert|replace)\s+/i", $query)){
			$this->insert_id = @mysql_insert_id($this->conn); //記錄新插入的ID
		}

		$this->result_nums = @mysql_affected_rows($this->conn); //記錄影響的行數
		return $this->result_nums; //返回影響的行數
	}

	/*
	 * 只能是"select"查詢, 用$this->result_nums記錄查詢結果數
	 * @return  query_id
	 */
	function query($query)	{
		$this->query_nums++;

		$this->query_id = @mysql_query($query, $this->conn);
		if(!$this->query_id){
			$this->error("Invalid SQL: ".$query); //查詢失敗輸出錯誤
		}

		$this->result_nums = @mysql_num_rows($this->query_id); //記錄查詢結果數

		return $this->query_id; //返回查詢資源
	}

	/*
	 * 對查詢資源ID進行fetch
	 * @return  query_id
	 */
	function fetch($queryId)	{
		return @mysql_fetch_array($queryId, MYSQL_ASSOC); //返回二維陣列
	}

	/*
	 * 查詢結果集
	 * @return 預設返回物件陣列, $out_array=1時返回二維陣列
	 */
	function getAll($query){
		$results = array(); //沒有查詢記錄時返回空陣列, 使陣列遍歷時不產生錯誤
		$query_id = $this->query($query);
		while ($row = $this->fetch($query_id)){
			$results[] = $row;
		}

		return $results;
	}

	/*
	 * 查詢一條資料
	 * @return 物件或一維陣列
	 */
	function getOne($query){
		return @mysql_fetch_assoc($this->query($query));
	}

	/*
	 * 取得最後一次select查詢的字段數
	 * @return number
	 */
	function getFields(){
		return @mysql_num_fields($this->query_id);
	}

	/*
	 * 取得最後一次insert查詢插入的ID值
	 * @return number
	 */
	function insert_id(){
		return $this->insert_id;
	}

	/*
	 * 關閉目前資料庫連接, 一般無需使用. 連接會隨php腳本結束自動關閉
	 */
	function close(){
		return @mysql_close($this->conn);
	}

	/*
	 * 釋放查詢結果及內存, PHP程序會在結束時自動釋放, 一般不調用
	 */
	function free_result() {
		@mysql_free_result($this->query_id);
		$this->query_id = 0;
	}

	/*
	 * @return 錯誤程式碼
	 */
	function geterrno() {
		return $this->errno;
	}

	/*
	 * 輸出錯誤
	 */
	function error($msg = ''){
		$this->errno = @mysql_errno($this->conn);

		if($this->printerror){
			$error_desc = @mysql_error($this->conn);

			$message  = "Database Query Error Info:\r\n\r\n";
			$message .= $msg."\r\n\r\n";
			$message .= "Error: ". $error_desc ."\r\n";
			$message .= "Error No: ".$errno."\r\n";
			$message .= "File: ". $_SERVER['PHP_SELF'] . "\r\n";

			echo '<center><br /><br /><br /><br /><b>Database Query Error Info</b><br /><textarea rows="22" style="width:480px;font-size:12px;">'.$message.'</textarea></center>';

			exit();
		}
	}
}

?>