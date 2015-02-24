<?php
//取得表單資料
$EmpID=$_POST['EmpID'];
$start_date=explode("-",$_POST['start_date']);
$end_date=explode("-",$_POST['end_date']);
$S_date=$start_date[0].$start_date[1].$start_date[2];
$E_date=$end_date[0].$end_date[1].($end_date[2]+1);

if ($S_date > $E_date){
	echo "<br />開始日期: ".$start_date[0].'-'.$start_date[1].'-'.$start_date[2];
	echo " <B>大於</B> ";
	echo "結束日期: ".$end_date[0].'-'.$end_date[1].'-'.$end_date[2];
	echo "<br />日期選擇錯誤，請返回上一頁";
}else{
//連線資料庫
$serverName = "localhost";
$connectionInfo = array("Database"=>"ZKTeco_AC_DB", "UID"=>"sa", "PWD"=>"P@55w0rd");
$conn = sqlsrv_connect($serverName, $connectionInfo);
//檢查連線是否成功
if ($conn === false ) {
    echo "Connection could not be established.<br />";
    die (print_r(sqlsrv_errors(), true));
}

if ( $EmpID !== '' ){
//包含USERID的SQL
	$sql="Select 
B1.DAY1 ,B1.EmpID ,B1.EmpName
,CASE WHEN MIN(B2.CHECKTIME) IS not null THEN CONVERT(varchar(5),MIN(B2.checktime),114) ELSE N'None' END as N'D_Clock_IN'
,case WHEN MAX(B3.CHECKTIME) IS NOT null then CONVERT(varchar(5),max(B3.checktime),114) else N'None' END as N'D_Clock_OUT'
,case WHEN MIN(B4.CHECKTIME) IS NOT null then CONVERT(varchar(5),MIN(B4.checktime),114) else N'None' END as N'N_Clock_IN'
,case WHEN MAX(B5.CHECKTIME) IS NOT null then CONVERT(varchar(5),max(B5.checktime),114) else N'None' END as N'N_Clock_OUT'
FROM (
	SELECT distinct convert(varchar,A2.CHECKTIME,112) DAY1,A1.USERID,(A1.BADGENUMBER) EmpID,(A1.NAME) EmpName 
	FROM USERINFO A1,CHECKINOUT A2
	WHERE  A2.CHECKTIME >= CONVERT(smalldatetime,?,112) and A2.CHECKTIME <= CONVERT(smalldatetime,?,112)
	and A1.BADGENUMBER=?
) B1
left join CHECKINOUT B2 on convert(varchar,B2.CHECKTIME,112) = B1.DAY1 and B2.USERID=B1.USERID
	and (DATEPART(hour,B2.checktime) BETWEEN 7 AND 10)
left join CHECKINOUT B3 on convert(varchar,B3.CHECKTIME,112) = B1.DAY1 and B3.USERID=B1.USERID 
	and (DATEPART(hour,B3.checktime) BETWEEN 16 AND 23 )
left join CHECKINOUT B4 on convert(varchar,B4.CHECKTIME,112) = B1.DAY1 and B4.USERID=B1.USERID 
	and (DATEPART(hour,B4.checktime) BETWEEN 15 and 18)
left join CHECKINOUT B5 on convert(varchar,B5.CHECKTIME,112) = DATEADD(dd,1,B1.DAY1) and B5.USERID=B1.USERID 
	and (DATEPART(hour,B5.checktime) BETWEEN 1 and 4)
GROUP by B1.DAY1,B1.EmpID,B1.EmpName";
}else{
//不包含USERID的SQL
	$sql="Select 
B1.DAY1 ,B1.EmpID ,B1.EmpName
,CASE WHEN MIN(B2.CHECKTIME) IS not null THEN CONVERT(varchar(5),MIN(B2.checktime),114) ELSE N'None' END as N'D_Clock_IN'
,case WHEN MAX(B3.CHECKTIME) IS NOT null then CONVERT(varchar(5),max(B3.checktime),114) else N'None' END as N'D_Clock_OUT'
,case WHEN MIN(B4.CHECKTIME) IS NOT null then CONVERT(varchar(5),MIN(B4.checktime),114) else N'None' END as N'N_Clock_IN'
,case WHEN MAX(B5.CHECKTIME) IS NOT null then CONVERT(varchar(5),max(B5.checktime),114) else N'None' END as N'N_Clock_OUT'
FROM (
	SELECT distinct convert(varchar,A2.CHECKTIME,112) DAY1,A1.USERID,(A1.BADGENUMBER) EmpID,(A1.NAME) EmpName 
	FROM USERINFO A1,CHECKINOUT A2
	WHERE  A2.CHECKTIME >= CONVERT(smalldatetime,?,112) and A2.CHECKTIME <= CONVERT(smalldatetime,?,112)
	
) B1
left join CHECKINOUT B2 on convert(varchar,B2.CHECKTIME,112) = B1.DAY1 and B2.USERID=B1.USERID
	and (DATEPART(hour,B2.checktime) BETWEEN 7 AND 10)
left join CHECKINOUT B3 on convert(varchar,B3.CHECKTIME,112) = B1.DAY1 and B3.USERID=B1.USERID 
	and (DATEPART(hour,B3.checktime) BETWEEN 16 AND 23 )
left join CHECKINOUT B4 on convert(varchar,B4.CHECKTIME,112) = B1.DAY1 and B4.USERID=B1.USERID 
	and (DATEPART(hour,B4.checktime) BETWEEN 15 and 18)
left join CHECKINOUT B5 on convert(varchar,B5.CHECKTIME,112) = DATEADD(dd,1,B1.DAY1) and B5.USERID=B1.USERID 
	and (DATEPART(hour,B5.checktime) BETWEEN 1 and 4)
GROUP by B1.DAY1,B1.EmpID,B1.EmpName";
}


//SQL語法
/*$sql="Select 
B1.DAY1 ,B1.EmpID ,B1.EmpName
,CASE WHEN MIN(B2.CHECKTIME) IS not null THEN CONVERT(varchar(16),MIN(B2.checktime),121) ELSE N'None' END as N'D_Clock_IN'
,case WHEN MAX(B3.CHECKTIME) IS NOT null then CONVERT(varchar(16),max(B3.checktime),121) else N'None' END as N'D_Clock_OUT'
,case WHEN MIN(B4.CHECKTIME) IS NOT null then CONVERT(varchar(16),MIN(B4.checktime),121) else N'None' END as N'N_Clock_IN'
,case WHEN MAX(B5.CHECKTIME) IS NOT null then CONVERT(varchar(16),max(B5.checktime),121) else N'None' END as N'N_Clock_OUT'
FROM (
	SELECT distinct convert(varchar,A2.CHECKTIME,112) DAY1,A1.USERID,(A1.BADGENUMBER) EmpID,(A1.NAME) EmpName 
	FROM USERINFO A1,CHECKINOUT A2
	WHERE  A2.CHECKTIME >= CONVERT(smalldatetime,?,112) and A2.CHECKTIME <= CONVERT(smalldatetime,?,112)
	and A1.BADGENUMBER=?
) B1
left join CHECKINOUT B2 on convert(varchar,B2.CHECKTIME,112) = B1.DAY1 and B2.USERID=B1.USERID
	and (DATEPART(hour,B2.checktime) BETWEEN 7 AND 10)
left join CHECKINOUT B3 on convert(varchar,B3.CHECKTIME,112) = B1.DAY1 and B3.USERID=B1.USERID 
	and (DATEPART(hour,B3.checktime) BETWEEN 16 AND 23 )
left join CHECKINOUT B4 on convert(varchar,B4.CHECKTIME,112) = B1.DAY1 and B4.USERID=B1.USERID 
	and (DATEPART(hour,B4.checktime) BETWEEN 15 and 18)
left join CHECKINOUT B5 on convert(varchar,B5.CHECKTIME,112) = DATEADD(dd,1,B1.DAY1) and B5.USERID=B1.USERID 
	and (DATEPART(hour,B5.checktime) BETWEEN 1 and 4)
GROUP by B1.DAY1,B1.EmpID,B1.EmpName";*/

//執行SQL語法
$stmt =sqlsrv_query($conn, $sql,array($S_date,$E_date,$EmpID));

//檢查SQL錯誤
if ($stmt === false){ die(print_r(sqlsrv_errors(),true));}

//HTML Begging
echo '<html><head>'
.'<meta http-equiv="Content-Type" content="text/html>'
.'<meta charset="UTF-8">'
. '</head>'
. '<body>'
.'</script>'
.'<style>'
.'.d01{ border:1px solid #000000; text-align:center;}'
.'.d01 thead td{ height:10px; background:#ffffff;border-bottom:1px solid #000000;}'
.'.d01 tbody td{ height:30px;width:130px; font-weight:normal; background:white;border-bottom:1px solid #000000;text-align:Center;}'
.'.d01 tbody th{ height:30px;width:150px; font-weight:normal; background:white;border-bottom:1px solid #000000;text-align:right;}'
.'.right{border-right:1px solid #000000;}'
.'</style>'
.'<table border="0" cellpadding="0" cellspacing="0" class="d01">'
.'<thead><tr>'
	.'<td class="right">姓名</td>'
	.'<td class="right">員工編號</td>'
	.'<td class="right">上班日</td>'
	.'<td class="right">上班打卡</td>'
	.'<td class="right">下班打卡</td>'
	//.'<td class="right">Night Clock In</td>'
	//.'<td class="right">Night Clock Out</td>'
.'</tr></thead>'
.'<tbody><tr>';

require("MemberList.php"); //匯入名單
while ($row=sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
	$empName=$row['EmpName'];
	$empID=$row['EmpID'];
	$theDate=substr($row['DAY1'],0,4)."-".substr($row['DAY1'],4,2)."-".substr($row['DAY1'],6,2);
	if(in_array($empID,$hiddenList) == False){
	//$D_clockIN=$row['D_Clock_IN'];
	//$D_clockOUT=$row['D_Clock_OUT'];
	//$N_clockIN=$row['N_Clock_IN'];
	//$N_clockOUT=$row['N_Clock_OUT'];
	
		if ($row['D_Clock_IN']==='None' & $row['D_Clock_OUT']==='None' & $row['N_Clock_IN']==='None' & $row['N_Clock_OUT']==='None'){
			echo'<td class="right">'.$empName.'</td>'
			.'<td class="right">'.$empID.'</td>'
			.'<td class="right">'.$theDate.'</td>'
			.'<td class="right">'.$clockIN='休假'.'</td>'
			.'<td class="right">'.$clockOUT='休假'.'</td>'
			.'</tr></tbody>';
		}elseif($row['N_Clock_OUT'] === 'None'){
			$clockIN=$row['D_Clock_IN'];
			 if (IN_ARRAY($empID,$shiftList) & $clockIN>'08:00' & $clockIN<>"None"){$clockIN="*".$row['D_Clock_IN'];}
			$clockOUT=$row['D_Clock_OUT'];
			 if ($clockOUT<'17:00' & $clockOUT<>"None"){$clockOUT="*".$row['D_Clock_OUT'];}
			echo'<td class="right">'.$empName.'</td>'
			.'<td class="right">'.$empID.'</td>'
			.'<td class="right">'.$theDate.'</td>'
			.'<td class="right">'.$clockIN.'</td>'
			.'<td class="right">'.$clockOUT.'</td>'
			.'</tr></tbody>';
		}elseif($row['D_Clock_OUT'] == $row['N_Clock_IN']){
			$clockIN=$row['N_Clock_IN'];
			 if ($clockIN>'17:00' & $clockIN<>"None"){$clockIN="*".$row['N_Clock_IN'];}
			$clockOUT=$row['N_Clock_OUT'];
			 if ($clockOUT<'02:00' & $clockOUT<>"None"){$clockOUT="*".$row['N_Clock_OUT'];}
			echo'<td class="right">'.$empName.'</td>'
			.'<td class="right">'.$empID.'</td>'
			.'<td class="right">'.$theDate.'</td>'
			.'<td class="right">'.$clockIN.'</td>'
			.'<td class="right">'.$clockOUT.'</td>'
			.'</tr></tbody>';
		}
	}	
}
echo '</table></body></html>';
//結束資料庫連線
sqlsrv_free_stmt($stmt);
}
?>