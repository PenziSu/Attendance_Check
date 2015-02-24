<?php

$serverName = "localhost";
$connectionInfo = array("Database"=>"ZKTeco_AC_DB", "UID"=>"sa", "PWD"=>"P@55w0rd");
$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn) {
	echo "Connection established.<br />";
}else{
	echo "Connection could not be established.<br />";
	die (print_r(sqlsrv_errors(), true));
}
?>
