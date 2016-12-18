<?php
//getUserInfo
header('Content-Type: application/json');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
error_reporting(false);
define("MYSQL_SERVER","localhost");
define("MYSQL_USER","admin");
define("MYSQL_PASSWORD","pass");

define("MYSQL_DATABASE","verq");

define("MYSQL_TABLE_USERS","users");
		define("MYSQL_COLOUM_ID","id");
		define("MYSQL_COLOUM_NAME","name");
		define("MYSQL_COLOUM_CHILDREN","children");
		define("MYSQL_COLOUM_HASH","hash");
		define("MYSQL_COLOUM_TYPE","type");
		define("MYSQL_COLOUM_MAIL","mail");
		define("MYSQL_COLOUM_VERIFIED","verified");
		
define("MYSQL_TABLE_AUTH","auth");
		define("MYSQL_COLOUM_AUTH_ID","id");
		define("MYSQL_COLOUM_TOKEN","token");
		define("MYSQL_COLOUM_AUTH_TYPE", "type");
		
$E1 = json_encode( array( "error" => "user authentification failed"));
$E2 = json_encode( array( "error" => "field can not be null"));
		
$id = htmlspecialchars($_POST["id"]);
$token = htmlspecialchars($_POST["token"]);

if((!isset($_POST["id"])) or (!isset($_POST["token"]))){
	die($E2);
}



mysql_connect(MYSQL_SERVER,MYSQL_USER,MYSQL_PASSWORD);
mysql_select_db(MYSQL_DATABASE);
mysql_set_charset('utf8');
mysql_query("SET NAMES 'utf8'");

		
if (mysql_num_rows(mysql_query("SELECT * FROM ".MYSQL_TABLE_AUTH." WHERE ".MYSQL_COLOUM_TOKEN."= '$token'"))!=1){
	die($E1);
}

$data = mysql_fetch_array(mysql_query("SELECT * FROM ".MYSQL_TABLE_USERS." WHERE ".MYSQL_COLOUM_ID."= '$id'"));


$user_info = array(
	"name" => $data["name"],
	"id" => $data["id"],
	"mail" => $data["mail"],
	"status" =>  $data["status"],
	"type" => $data["type"],
	"children" => $data["children"],
);
echo json_encode($user_info);




?>