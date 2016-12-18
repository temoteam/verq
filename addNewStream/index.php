<?php
//addNewStreams
header('Content-Type: application/json');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
error_reporting(false);
define("MYSQL_SERVER","localhost");
define("MYSQL_USER","admin");
define("MYSQL_PASSWORD","pass");

define("MYSQL_DATABASE","verq");

define("MYSQL_TABLE_STREAMS","streams");
		define("MYSQL_COLOUM_ID_STREAM","id_stream");
		define("MYSQL_COLOUM_NAME","name");
		define("MYSQL_COLOUM_ID_USER","id_user");
		define("MYSQL_COLOUM_TIME_START","time_start");
		define("MYSQL_COLOUM_DESCRIPTION","description");
		define("MYSQL_COLOUM_DURATION", "duration");
		
define("MYSQL_TABLE_AUTH","auth");
		define("MYSQL_COLOUM_AUTH_ID","id");
		define("MYSQL_COLOUM_TOKEN","token");
		define("MYSQL_COLOUM_AUTH_TYPE", "type");
		
$E1 = json_encode( array( "error" => "user authentification failed"));
$E2 = json_encode( array( "error" => "field can not be null"));
$E3 = json_encode( array( "error" => "stream already exists"));
$E4 = json_encode( array( "error" => "access denied"));
		
$id_stream = time();
$name = htmlspecialchars($_POST["name"]);
$time_start = htmlspecialchars($_POST["time_start"]);
$description = htmlspecialchars($_POST["description"]);
$duration = htmlspecialchars($_POST["duration"]);
$token = htmlspecialchars($_POST["token"]);

if(!isset($_POST)){
	die($E2);
}

mysql_connect(MYSQL_SERVER,MYSQL_USER,MYSQL_PASSWORD);
mysql_select_db(MYSQL_DATABASE);
mysql_set_charset('utf8');
mysql_query("SET NAMES 'utf8'");

if (mysql_num_rows(mysql_query("SELECT * FROM ".MYSQL_TABLE_AUTH." WHERE ".MYSQL_COLOUM_TOKEN."= '$token'"))!=1){
	die($E1);
}

$data = mysql_fetch_array(mysql_query("SELECT * FROM ".MYSQL_TABLE_AUTH." WHERE ".MYSQL_COLOUM_TOKEN."= '$token'"));

$user_id = $data["id"];
$type = $data["type"];

if ($type != 1){
	die($E4);
}

if (mysql_num_rows(mysql_query("SELECT * FROM ".MYSQL_TABLE_STREAMS." WHERE ".MYSQL_COLOUM_ID_USER."= '$user_id' AND ".MYSQL_COLOUM_TIME_START."= '$time_start'"))===1){
	die($E3);
}

mysql_query("INSERT INTO ".MYSQL_TABLE_STREAMS." (".MYSQL_COLOUM_ID_STREAM.", ".MYSQL_COLOUM_NAME.", ".MYSQL_COLOUM_ID_USER.", ".MYSQL_COLOUM_TIME_START.", ".MYSQL_COLOUM_DESCRIPTION.", ".MYSQL_COLOUM_DURATION.") VALUES('$id_stream', '$name', '$user_id', '$time_start', '$description', '$duration')");


$info = array(
	"stream_id" => $id_stream
	);
	
echo json_encode($info);

?>