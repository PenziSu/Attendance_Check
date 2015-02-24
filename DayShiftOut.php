<html>
<head></head>
<body>
<?php
	$serverName = "localhost";
	$connectionInfo = array("Database"=>"ZKTeco_AC_DB", "UID"=>"sa", "PWD"=>"P@55w0rd");
	$conn = sqlsrv_connect($serverName, $connectionInfo);

	if ($conn === false ) {
		echo "Connection could not be established.<br />";
		die (print_r(sqlsrv_errors(), true));
	}

	$sql = "SELECT A1.USERID, A2.NAME, A2.BADGENUMBER,CONVERT(char(19),A1.CHECKTIME,120)  'CHECKTIME'
FROM CHECKINOUT A1,USERINFO A2
WHERE
(
	DATEPART(yy,A1.checktime)=datepart(yy,convert(char(10),GETDATE(),120)) and
	DATEPART(mm,A1.checktime)=datepart(mm,convert(char(10),GETDATE(),120)) and
	DATEPART(dd,A1.checktime)=datepart(DD,convert(char(10),GETDATE(),120)) and
	DATEPART(HH,A1.checktime) between  17 and 19 and
	DATEPART(MI,A1.checktime)  between 00 and  59 
) and A1.USERID = A2.USERID
order by A1.CHECKTIME";
	$stmt = sqlsrv_query($conn, $sql);
	if (!$stmt){
			die(print_r(sqlsrv_errors(),true));
	}
?>
<table border="1">
<tr>
<td><b>Employee ID</b></td>
<td><b>Employee  NAME</b></td>
<td><b>Clock In</b></td>
</tr>
<tr>
<?php while ($row = sqlsrv_fetch_array($stmt)){ ?>
		<td><?php echo $row['BADGENUMBER']; ?> </td>
		<td><?php echo $row['NAME'] ; ?></td>
		<td><?php echo $row['CHECKTIME'] ; ?></td>
</tr>		
<?php } ?>
<?php
	sqlsrv_free_stmt($stmt);
?>
</table>
</body>
</html>