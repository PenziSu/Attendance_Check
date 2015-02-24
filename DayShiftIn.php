<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
</head>
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
	DATEPART(HH,A1.checktime) between 07 and 08
) and A1.USERID = A2.USERID
order by A1.CHECKTIME";
	$stmt = sqlsrv_query($conn, $sql);
	if (!$stmt){
			die(print_r(sqlsrv_errors(),true));
	}
?>
<style>
    .d01{ border:1px solid #000000; text-align:center;}
    .d01 thead td{ height:10px; background:#ffffff;border-bottom:1px solid #000000;}
    .d01 tbody td{ height:30px;width:120px; font-weight:normal; background:white;border-bottom:1px solid #000000;text-align:Center;}
    .d01 tbody th{ height:30px;width:150px; font-weight:normal; background:white;border-bottom:1px solid #000000;text-align:right;}
    .right{border-right:1px solid #000000;}
</style>

<table border="0" cellpadding="0" cellspacing="0" class="d01">
<thead><tr>
    <td class="right">Employee ID     </td>
    <td class="right">Employee  NAME  </td>
    <td class="right">Clock In        </td>
</tr></thead>
<tbody><tr>
<?php while ($row = sqlsrv_fetch_array($stmt)){ ?>
    <td class="right"><?php echo $row['BADGENUMBER']; ?></td>
    <td class="right"><?php echo $row['NAME'] ; ?>      </td>
    <th class="right"><?php echo $row['CHECKTIME'] ; ?> </th>
</tr></tbody>	
<?php }
	sqlsrv_free_stmt($stmt);
?>
</table>
</body>
</html>