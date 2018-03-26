<?php

$server = "mysql.webzdarma.cz";
$name   = "zeding";
$pass   = "";
$db     = "zeding";

include("sql/mysql4.php");
$sql = new sql_db();
$sql->sql_connect($server,$name,$pass,$db);

$sql->sql_query("set character_set_client=utf8");
$sql->sql_query("set character_set_connection=utf8");
$sql->sql_query("set character_set_database=utf8");
$sql->sql_query("set character_set_results=utf8");
$sql->sql_query("set character_set_server=utf8");
$sql->sql_query("set collation_connection=utf8_czech_ci");
$sql->sql_query("set collation_database=utf8_czech_ci");
$sql->sql_query("set collation_server=utf8_czech_ci");

?>
