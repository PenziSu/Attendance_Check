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
WHERE (
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

<?php
$MyBody="<html><head></head><body>";
$MyBody.='<table rules="all" style="border-color: #666;" cellpadding="10">';
$MyBody.= "<tr style='background: #eee;'>";
$MyBody.= "<td><strong><b>Employee ID</b></td>";
$MyBody.= "<td><strong><b>Employee  NAME</b></td>";
$MyBody.= "<td><strong><b>Clock In</b></td></tr>";
$MyBody.= "<tr>";
		while ($row = sqlsrv_fetch_array($stmt)){ 
		$MyBody.= "<td><strong>".$row['BADGENUMBER'] ."</td>";
		$MyBody.= "<td><strong>".$row['NAME'] ."</td>";
		$MyBody.= "<td><strong>".$row['CHECKTIME'] ."</td>";
$MyBody.= "</tr>";		
 } 

$MyBody.="</table></body></html>";
sqlsrv_free_stmt($stmt);
?>


<?php
$filename = 'C:\inetpub\wwwroot\PHPMailer\PHPMailerAutoload.php';
if(file_exists ( $filename ) ){
	//require ('C:\inetpub\wwwroot\PHPMailer\class.phpmailer.php');
	require ('C:\inetpub\wwwroot\PHPMailer\PHPMailerAutoload.php');
	$mail = new PHPMailer;
	$mail->IsSMTP();
	$mail->SMTPAuth=false;
	$mail->Username="sc.skycallzmis";
	$mail->Password="tj;4jo6jp6cj84";
	$mail->From="skycallzmis@skycallz.com";
	$mail->FromName="Skycallz IT";
	$mail->AddAddress("dennis.su@skycallz.com","Dennis Su");
	$mail->WordWrap=50;
	$mail->Subject="Clock In/Out Notice Mail";
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