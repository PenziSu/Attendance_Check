<?php
//==============取得打卡紀錄===================	
require_once('DB_Connect.php');
$sql=the_Sql(date("d"),'02','04');
$yyyy=date('Y'); $mm=date('M'); $dd=date('d')-1; $today=$yyyy."-".$mm."-".$dd;
$serverName = "localhost";
$connectionInfo = array("Database"=>"ZKTeco_AC_DB", "UID"=>"sa", "PWD"=>"P@55w0rd");
$conn = sqlsrv_connect($serverName, $connectionInfo);
		if ($conn === false ) {
			echo "Connection could not be established.<br />";
			die (print_r(sqlsrv_errors(), true));
		}
$stmt = sqlsrv_query($conn, $sql);
		if (!$stmt){ die(print_r(sqlsrv_errors(),true)); }

$compareList=array();
while ($row = sqlsrv_fetch_array($stmt)){
	if (in_array($row['NAME'],$compareList) == False){
		$compareList[]=$row['NAME'];
		$clockList[] = array($row['BADGENUMBER'],$row['NAME'],$row['CHECKTIME']);//將打卡名單存入陣列
	}
}
sqlsrv_free_stmt($stmt); // 結束資料庫連線

//===取得未打卡名單====================================
$vacation=array();//休假名單
$Missman = getDutyList($vacation,$compareList,"N");
/*/===Show on the Command Line================================
print($today."晚班下班打卡通知");
print("\n");
for ($x=0;$x<count($clockList);$x++){
	print("EID: ".$clockList[$x][0]
		." ENAME==>".$clockList[$x][1]
		." Clock Time==>".$clockList[$x][2]);
	print("\n");
}
for ($m=0;$m<count($Missman);$m++){
 if ($Missman[$m] !== "Everyone are Clock In."){
	print ($Missman[$m]." Not Clock In");
	print("\r\n");
 }
}
//=============================================*/

//==============產生Email內容==================
$MyBody="<html><head></head><body>";
$MyBody.="<H1>".$today." 晚班下班打卡通知</H1>";
$MyBody.="<H4>以打卡順序排列</H4>";
$MyBody.='<table rules="all" style="border-color: #666;" cellpadding="10">';
$MyBody.= "<tr style='background: #eee;'>";
$MyBody.= "<td><strong><b>員工編號</b></td>";
$MyBody.= "<td><strong><b>員工姓名</b></td>";
$MyBody.= "<td><strong><b>下班時間</b></td>";
$MyBody.= "<td><strong><b>打卡狀態</b></td>";
$MyBody.= "</tr>";
for ($x=0;$x<count($clockList);$x++){
	$MyBody.= "<tr>";
	$MyBody.= "<td><strong>".$clockList[$x][0]."</td>";//將員工編號放入Email
	$MyBody.= "<td><strong>".$clockList[$x][1]."</td>";//將員工姓名放入Email
	$MyBody.= "<td><strong>".$clockList[$x][2]."</td>";//將打卡時間放入Email
	$MyBody.= "<td><strong>已打卡</td>";//將打卡狀態放入Email
	$MyBody.= "</tr>";
}
for ($m=0;$m<count($Missman);$m++){
 if ($Missman[$m] !== "Everyone are Clock In."){
	$MyBody.= "<tr>";
	$MyBody.= "<td><strong> </td>";
	$MyBody.= "<td><strong>".$Missman[$m]."</td>";
	$MyBody.= "<td><strong> </td>";
 	$MyBody.= "<td><strong>未打卡</td>";//將打卡狀態放入Email
	$MyBody.= "</tr>";
 }
}
//=============================================

//=======以下Email發送========
$MyBody.="</table></body></html>";
$filename = 'C:\inetpub\wwwroot\PHPMailer\PHPMailerAutoload.php';
if(file_exists ( $filename ) ){
	//require ('C:\inetpub\wwwroot\PHPMailer\class.phpmailer.php');
	require ('C:\inetpub\wwwroot\PHPMailer\PHPMailerAutoload.php');
	$mail = new PHPMailer;
	$mail->IsSMTP();
	$mail->CharSet = 'UTF-8';
	$mail->SMTPAuth=false;
	$mail->Username="sc.skycallzmis";
	$mail->Password="tj;4jo6jp6cj84";
	$mail->From="skycallzmis@skycallz.com";
	$mail->FromName="Skycallz IT";
	$mail->AddAddress("dennis.su@skycallz.com","Dennis Su");
	$mail->AddAddress("skycallzmis@skycallz.com","Skycallz IT");
	$mail->WordWrap=50;
	$mail->Subject="$today  晚班下班打卡通知";
	$mail->Body=$MyBody;
	$mail->IsHTML(true);
	if(!$mail->Send()){
		echo "Sending Email Error: ". $mail->ErrorInfo;
	}else{
		echo "Email Sending Successful!";
	}
	
}else{
	echo "The Files does not exist.";
}
?>