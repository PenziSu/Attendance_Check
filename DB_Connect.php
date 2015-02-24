<?php
function the_Sql($dd,$time1,$time2){
	if ($dd == '' & $time1 == '' & $time2 == ''){
		$sql = 'Please input para';
	}else{
		$sql = "SELECT A1.USERID, A2.NAME, A2.BADGENUMBER,CONVERT(char(19),A1.CHECKTIME,120) 'CHECKTIME'
				FROM CHECKINOUT A1,USERINFO A2
				WHERE (
					DATEPART(yy,A1.checktime)=datepart(yy,convert(char(10),GETDATE(),120)) and
					DATEPART(mm,A1.checktime)=datepart(mm,convert(char(10),GETDATE(),120)) and
					--DATEPART(dd,A1.checktime)=datepart(DD,convert(char(10),GETDATE(),120)) and
					DATEPART(dd,A1.checktime)=".$dd." and
					DATEPART(HH,A1.checktime) between ".$time1." and ".$time2."
					) and A1.USERID = A2.USERID
				ORDER by A1.CHECKTIME";
	}
	return $sql;
}

//=======以下參數提供休假名單、比對名單、選擇班別
function getDutyList($vacation,$compareList,$shift="O"){
	switch($shift){
		case "D":			//"D"表示早班
			$OnBoard=array("Dennis Su","Ken Wu");
		case "d":			//"D"表示早班
			$OnBoard=array("Dennis Su","Ken Wu");
		break;
		case "N":			//"N"表示小夜班
			$OnBoard=array("Mars Chien","William Lu");
		case "n":			//"N"表示小夜班
			$OnBoard=array("Mars Chien","William Lu");	
		break;
		case "O":			//"N"表示沒有班別
			$OnBoard=array("Mars Chien","William Lu","Dennis Su","Ken Wu");
		break;
		case "o":			//"N"表示沒有班別
			$OnBoard=array("Mars Chien","William Lu","Dennis Su","Ken Wu");
		break;
	}
	//====以下篩選請假人員==========
	for ($x=0;$x<count($OnBoard);$x++){
	  $who=$OnBoard[$x];
		IF(in_array($who,$vacation)==false){
			//print($who.". On Duty.");
			//print("\n");
			$MustOnDuty[]=$who;
		}
	}
	//====找出誰沒打卡====================
	$result=array();
	for ($x=0;$x<count($MustOnDuty);$x++){
		IF(in_array($MustOnDuty[$x],$compareList) == false){
			$result[]=$MustOnDuty[$x];
		}
	}
	if (count($result)<1){
		$result[]="Everyone are Clock In.";
	}
	return $result;
}

?>