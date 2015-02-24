<?php
//連結資料庫
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

//假設是上班名單
	$dutyList=array("Lawrent Yin","Subrina Su","Dennis Su","Enjoy Tsai","Rick Chen","Jason Lin","Mars Chien");
//抓出打卡人員
	$name='x';
	while($row=sqlsrv_fetch_array($stmt)){
		if ($name == '' || $name !== $row['NAME'] ){
			$clockList[] = $row['NAME'];
			$name = $row['NAME'];
		}
	}
//print_r ($dutyList);
//print_r ($clockList);
foreach ($dutyList as $item){
	if (in_array($item,$clockList)){
		print 'Name: '.$item.' & bool code: '.in_array($item,$clockList).chr(10);
}else{print 'Name: '.$item.' & bool code: '.in_array($item,$clockList).chr(10);}
}

/*呼叫LINE程式	
	while($row=sqlsrv_fetch_array($stmt)){
		if (in_array($row['NAME'],$dutyList)){
			$para=$row['NAME'].' on duty';
			echo shell_exec('python C:\inetpub\wwwroot\Note_test.py '.escapeshellarg($para));
		}else{
			$para = $row['NAME'].' did not Clock IN/OUT.';
			echo shell_exec('python C:\inetpub\wwwroot\Note_test.py '.escapeshellarg($para));
	}
 }*/
?>