<?
	$db = array(
		'host' => 'localhost',
		'user' => 'orange',
		'pass' => '',
		'base' => 'orange'
		);
	if (!mysql_connect($db['host'],$db['user'],$db['pass'])) {
		echo "error to connect DB";
		exit();
	}
	if (!mysql_select_db($db['base'])) {
		echo "error to connect DB";
		exit();
	}
?>
