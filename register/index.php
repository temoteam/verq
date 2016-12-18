<?php
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
		define("MYSQL_COLOUM_STATUS", "status");
		define("MYSQL_COLOUM_VERIFIED","verified");
		
define("MYSQL_TABLE_AUTH","auth");
		define("MYSQL_COLOUM_AUTH_ID","id");
		define("MYSQL_COLOUM_TOKEN","token");
		define("MYSQL_COLOUM_AUTH_TYPE", "type");
		
$E1 = json_encode( array( "error" => "access denied"));
$E2 = json_encode( array( "error" => "field can not be null"));
$E3 = json_encode( array( "error" => "user already exist"));
		

$id = time();
$name = htmlspecialchars($_POST['name']);
$children = htmlspecialchars($_POST['children']);
$type = htmlspecialchars($_POST['type']);
$mail = htmlspecialchars($_POST['mail']);
$pass = htmlspecialchars($_POST['pass']);
$status = htmlspecialchars($_POST['status']);


$hash = password_hash($pass, PASSWORD_DEFAULT);

$verified = md5($time);


if (($name == "") or ($mail=="") or ($pass == "")) {
	die ($E2);
}

if (($type < 1)or ($type > 2)){
	die ($E1);
}


mysql_connect(MYSQL_SERVER,MYSQL_USER,MYSQL_PASSWORD);
mysql_select_db(MYSQL_DATABASE);
mysql_set_charset('utf8');
mysql_query("SET NAMES 'utf8'");

if (!(mysql_num_rows(mysql_query("SELECT * FROM ".MYSQL_TABLE_USERS." WHERE ".MYSQL_COLOUM_MAIL."= '$mail'"))===0)){
	
		die ($E3);
		
}


if (mysql_num_rows(mysql_query("SELECT * FROM ".MYSQL_TABLE_USERS." WHERE ".MYSQL_COLOUM_MAIL."= '$mail'"))===0){
	
    mysql_query("INSERT INTO ".MYSQL_TABLE_USERS." (".MYSQL_COLOUM_ID.", ".MYSQL_COLOUM_MAIL.", ".MYSQL_COLOUM_CHILDREN.", ".MYSQL_COLOUM_HASH.", ".MYSQL_COLOUM_TYPE.", ".MYSQL_COLOUM_NAME.", ".MYSQL_COLOUM_VERIFIED.", ".MYSQL_COLOUM_STATUS.") VALUES('$id', '$mail', '$children', '$hash', '$type', '$name', '$verified', '$status')");

}


$token = hash("sha256", $id.time());
mysql_query("INSERT INTO ".MYSQL_TABLE_AUTH." (".MYSQL_COLOUM_AUTH_ID.", ".MYSQL_COLOUM_TOKEN.", ".MYSQL_COLOUM_AUTH_TYPE.") VALUES('$id', '$token', '$type')");

$array =array(
	"id" => $id,
	"token" => $token
);

echo json_encode($array); 

?>





