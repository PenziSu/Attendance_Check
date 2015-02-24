<?php
$today=date('Y-M-d');
$serverName = "localhost";
$connectionInfo = array("Database"=>"ZKTeco_AC_DB", "UID"=>"sa", "PWD"=>"P@55w0rd");
$conn = sqlsrv_connect($serverName, $connectionInfo);
		if ($conn === false ) {
			echo "Connection could not be established.<br />";
			die (print_r(sqlsrv_errors(), true));
		}
$sql = "SELECT A1.USERID, A2.NAME, A2.BADGENUMBER,CONVERT(char(19),A1.CHECKTIME,120)  'CHECKTIME'
		FROM CHECKINOUT A1,USERINFO A2
		WHERE (
                        DATEPART(yy,A1.checktime)=datepart(yy,convert(char(10),GETDATE(),120)) and
                        DATEPART(mm,A1.checktime)=datepart(mm,convert(char(10),GETDATE(),120)) and
                        DATEPART(dd,A1.checktime)=datepart(DD,convert(char(10),GETDATE(),120)) and
                        DATEPART(HH,A1.checktime) between 07 and 08
                      ) and A1.USERID = A2.USERID
		ORDER by A1.CHECKTIME";

$stmt = sqlsrv_query($conn, $sql);
		if (!$stmt){ die(print_r(sqlsrv_errors(),true)); }
?>