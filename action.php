<?php
session_start();
$aa = $_GET['aa'];
if($aa==1){//正常功能
	unset($_SESSION['ok']);
	print '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
	$amount = $_POST['amount'];
	$exceptAmount = $_POST['exceptAmount'];
	$otherAmount = $_POST['otherAmount'];
	$top = $_POST['top'];
	$bottom = $_POST['bottom'];
	$_SESSION['amount1']=$amount;
	$_SESSION['exceptAmount1']=$exceptAmount;
	$_SESSION['otherAmount1']=$otherAmount;
	$_SESSION['top1']=$top;
	$_SESSION['bottom1']=$bottom;
	print "<script type='text/javascript'>";
	if($top<=$bottom || $top==null || $bottom==null){//1
		print "location.href='index.php?check=1';";
	}else if($amount>($top-$bottom+1) || $amount==null || $amount<=0){//2
		print "location.href='index.php?check=2';";
	}else if(($otherAmount+$Amount+$amount)>($top-$bottom+1) || $otherAmount==null || $otherAmount<0){//3
		print "location.href='index.php?check=3';";
	}else if(($exceptAmount+$amount+$otherAmount)>($top-$bottom+1) || $exceptAmount==null || $exceptAmount<0){//4
		print "location.href='index.php?check=4';";
	}else{
		for($i=0;$i<$exceptAmount;$i++){
			if($_POST['_'.$i]==null) print "location.href='index.php?check=5';";
		}
		putenv("TZ=Asia/Taipei");
		$_SESSION['ok']=1;
		$_SESSION['ok2']=1;
		$_SESSION['time']=date("Y/m/d H:i:s");
		for($i=0;$i<$exceptAmount;$i++){
			$_SESSION['_'.$i]=$_POST['_'.$i];
		}
	}
	print "</script>";
}
else if($aa==2){//pdf
	$amount=$_SESSION['amount1'];
	$exceptAmount=$_SESSION['exceptAmount1'];
	$otherAmount=$_SESSION['otherAmount1'];
	$top=$_SESSION['top1'];
	$bottom=$_SESSION['bottom1'];
	$time=$_SESSION['time'];

	require("fpdf.php");
	//左邊
	$pdf = new FPDF();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',16);
	$pdf->Cell(0,0,"MakeTime: ".$time);
	$pdf->Cell(0,15,"Range: ".$bottom." ~ ".$top);
	$pdf->Cell(0,30,"Except Numbers: (".$exceptAmount.")");
	if($exceptAmount>0){
		$pdf->Cell(2,42,"{");
		$h=57;
		for($i=0;$i<$exceptAmount;$h+=12){
			@$pdf->Cell(0,$h,$_SESSION['_'.$i++]."  ".$_SESSION['_'.$i++]."  ".$_SESSION['_'.$i++]);
		}
		$pdf->Cell(90,0,"");
		$pdf->Cell(0,$h,"}");
	}else{
		$pdf->Cell(90,0,"");
	}
	//右邊
	$pdf->Cell(5,0,"");
	$h=15;
	if($_SESSION['order1']=='order'){
		$pdf->Cell(0,0,"Result Numbers: (".$amount.",Increment)");
		$pdf->Cell(2,$h,"{");
		$h+=15;
		for($i=0;$i<$amount;$h+=12){
			@$pdf->Cell(0,$h,$_SESSION['order1'.$i++]."  ".$_SESSION['order1'.$i++]."  ".$_SESSION['order1'.$i++]);
		}
	}else if($_SESSION['order1']=='desc'){
		$pdf->Cell(0,0,"Result Numbers: (".$amount.",Desc)");
		$pdf->Cell(2,$h,"{");
		$h+=15;
		for($i=0;$i<$amount;$h+=12){
			@$pdf->Cell(0,$h,$_SESSION['desc'.$i++]."  ".$_SESSION['desc'.$i++]."  ".$_SESSION['desc'.$i++]);
		}
	}
	else{
		$pdf->Cell(0,0,"Result Numbers: (".$amount.")");
		$pdf->Cell(2,$h,"{");
		$h+=15;
		for($i=0;$i<$amount;$h+=12){
			@$pdf->Cell(0,$h,$_SESSION['number'.$i++]."  ".$_SESSION['number'.$i++]."  ".$_SESSION['number'.$i++]);
		}
	}
	$pdf->Cell(93,0,"");
	$pdf->Cell(0,$h,"}");
	$pdf->Cell(-95,0,"");
	//其他號
	if($otherAmount>0){
		$h+=15;
		$pdf->Cell(0,$h,"Other Numbers: (".$otherAmount.")");
		$h+=15;
		$pdf->Cell(2,$h,"{");
		$h+=15;
		for($i=0;$i<$otherAmount;$h+=12){
			@$pdf->Cell(0,$h,$_SESSION['other'.$i++]."  ".$_SESSION['other'.$i++]."  ".$_SESSION['other'.$i++]);
		}
		$pdf->Cell(93,0,"");
		$pdf->Cell(0,$h,"}");
	}
	$pdf->Output();
}
else if($aa == 3){//清空
	session_destroy();
}
else if($aa == 4){//排除個數
	@$_SESSION['exceptAmount1']=$_POST['exceptAmount'];
	print "<script type='text/javascript'> history.go(-1); </script>";
}
//-----------------------------------------
print "<script type='text/javascript'> location.href='index.php'; </script>";
?>