<?php
include("mainfile/connection.php");
$x_main = explode("/",$_SERVER['REQUEST_URI']);
$url_main="/".$x_main[1];

$sql1 = mysqli_query($conn,"SELECT * FROM result WHERE  code='".$_GET['na']."' and staus !='done'");
$count=mysqli_num_rows($sql1);

if($count>0){
$sql2 = mysqli_query($conn,"UPDATE result SET staus='done',date='".(date("d-m-Y",time()))."' where code='".$_GET['na']."'");
$sql2_0 = mysqli_query($conn,"UPDATE startquiz SET value='' where code='".$_GET['na']."'");
if($sql2){
$result2 = mysqli_fetch_assoc($sql1);
$correct=$result2['correct'];
$wrong=$result2['wrong'];
$attempt=$wrong+$correct;
$total=$result2['total'];
$cut=$wrong/4;
$final_marks=$correct-$cut;
$test_code=$result2['test_code'];


$sql3 = mysqli_query($conn,"select * from std_overalldata where code='".$_GET['na']."'");
$result3 = mysqli_fetch_assoc($sql3);
parse_str($result3['total_test_number']);
$t="t=".($t+1)."&n=".($n+$final_marks);

if($final_marks <= 35){$marks_insert = 'below_35';}
else if(35<$final_marks or $final_marks<=60){$marks_insert = 'n35_60';}
else if(60<$final_marks or $final_marks<=75){$marks_insert = 'n60_75';}
else{$marks_insert = 'over_75';}



$sql4 = mysqli_query($conn,"UPDATE std_overalldata SET total_test_number='$t',$marks_insert=$marks_insert+1 where code='".$_GET['na']."'");
if($sql4){
$sql_temp = mysqli_query($conn,"UPDATE std_login set rollno='' where code='".$_GET['na']."'");
if($sql_temp){$other_query=1;}
}

if($other_query==1){
$sql5 = mysqli_query($conn,"insert into std_all_time_data (date,number,test) values ('".(date("d-m-Y",time()))."','$final_marks','".$_GET['na']."')");
if($sql5){$other_query=2;}	
}
// if($other_query==2){
// $sql6 = mysqli_query($conn,"UPDATE inst_overalldata SET total=total+1 where code='".$_GET['codeinst']."'");
// if($sql6){$other_query=3;}	
// }

if($other_query==2){
echo "<script>window.location.replace('$url_main/result?na=".$_GET['na']."');</script>";
// $sql7 = mysqli_query($conn,"SELECT * FROM $shift WHERE test_code='$test_code' and code_inst='".$_GET['code_inst']."'");
// while($result2 = mysqli_fetch_assoc($sql7)){	
// $qus[]=$result2['qus'];
// $opt1[]=$result2['opt1'];$opt2[]=$result2['opt2'];
// $opt3[]=$result2['opt3'];$opt4[]=$result2['opt4'];
// $ans[]=$result2['ans'];$code_inst[]=$_GET['code_inst'];
// };
// for($i=0;$i<count($qus);$i++){	
// $q=mysqli_query($conn,"insert into site_ques (ques,opt1,opt2,opt3,opt4,ans,code_inst) values ('$qus[$i]','$opt1[$i]','$opt2[$i]','$opt3[$i]','$opt4[$i]','$ans[$i]','$code_inst[$i]')");
// if($q){$other_query=3;}
// }
}
if($other_query==3){
// $q0=mysqli_query($conn,"update inst_all_time_data set test_code='' where test_code=$test_code");
// $q1=mysqli_query($conn,"delete from $shift where test_code='$test_code'");
// $q2=mysqli_query($conn,"delete from registered_table where test_code='$test_code'");
// $q3=mysqli_query($conn,"delete from timing where test_code='$test_code'");
// $q4=mysqli_query($conn,"delete from startquiz where test_code='$test_code'");

}
}
}else{echo "<script>window.location.replace('$url_main/result?na=".$_GET['na']."');</script>";}
?>