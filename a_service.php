<?
ini_set('date.timezone','Asia/Taipei');
$ini_path = "include/service_config.ini";
$config = parse_ini_file($ini_path, true);

function write_php_ini($array, $file)
{
    $res = array();
    foreach($array as $key => $val)
    {
        if(is_array($val))
        {
            $res[] = "[$key]";
            foreach($val as $skey => $sval) $res[] = "$skey = ".(is_numeric($sval) ? $sval : '"'.$sval.'"');
        }
        else $res[] = "$key = ".(is_numeric($val) ? $val : '"'.$val.'"');
    }
	$fp = fopen($file, "w");
	if($fp) {
		fwrite($fp,implode("\r\n", $res));
		fclose($fp);
		return 1;
	}
}

//echo $config['refresh_vip']['enabled'];
if ( $config['refresh_vip']['enabled'] == 1 ) {
	if ( strtotime($NOW_DATETIME) > strtotime($config['refresh_vip']['interval'], strtotime( $config['refresh_vip']['last_time'] ) ) )
		include_once("f_refresh_vip.php");
}
?>