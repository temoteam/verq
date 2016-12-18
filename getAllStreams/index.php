<?php
//getAllStreams
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

$result = mysql_query("SELECT * FROM ".MYSQL_TABLE_STREAMS);

$out = array();

while ($data = mysql_fetch_assoc($result)){

	$info = array(
    "id" => $data["id_stream"],
    "name" => $data["name"],
    "owner_id" => $data["id_user"],
    "time_start" => $data["time_start"],
    "description" => $data["description"],
	"duration" => $data["duration"]
  );
  $out[] = $info;

}

echo json_encode($out);
?>
