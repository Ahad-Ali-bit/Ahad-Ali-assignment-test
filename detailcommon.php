<?php
include("mainfile/connection.php");
if($_POST['query_select']=='std_data'){
$sql_std_login=mysqli_query($conn,"SELECT * FROM std_login WHERE code='".$_POST['code']."'");
$std_logincount=mysqli_num_rows($sql_std_login);
$result_std_login = mysqli_fetch_assoc($sql_std_login);

$sql_std_detail=mysqli_query($conn,"SELECT * FROM std_detail WHERE code='".$_POST['code']."'");
$std_detail_count=mysqli_num_rows($sql_std_detail);
$result_std_detail = mysqli_fetch_assoc($sql_std_detail);

$std_data_array=array(
array($result_std_login['name'],$result_std_login['email']),
array($result_std_detail['institute_name'],$result_std_detail['address'],$result_std_detail['director_name'],$result_std_detail['city'],$result_std_detail['contact'],$result_std_detail['email'])
);
$myJSON = json_encode($std_data_array);
echo $myJSON;
}

if($_POST['query_select']=='inst_data'){
// login
$sql_inst_login=mysqli_query($conn,"SELECT * FROM institute_login WHERE code='".$_POST['code']."'");
$result_inst_login = mysqli_fetch_assoc($sql_inst_login);
$inst_logincount=mysqli_num_rows($sql_inst_login);
// detail
$sql_inst_detail=mysqli_query($conn,"SELECT * FROM institute_detail WHERE code='".$_POST['code']."'");
$inst_detail_count=mysqli_num_rows($sql_inst_detail);
$result_inst_detail = mysqli_fetch_assoc($sql_inst_detail);
$inst_data_array=array(
array($result_inst_login['name'],$result_inst_login['email']),
array($result_inst_detail['institute_name'],$result_inst_detail['address'],$result_inst_detail['director_name'],$result_inst_detail['city'],$result_inst_detail['contact'],$result_inst_detail['email'])
);
$myJSON2 = json_encode($inst_data_array);
echo $myJSON2;
}
// new entry
if($_POST['query_select']=='start_quiz'){

$e1 = decoct($_POST['test_code']);
$e2 = dechex($e1);

$sql_time=mysqli_query($conn,"SELECT * FROM timing WHERE test_code='".$e2."'");
$result_time = mysqli_fetch_assoc($sql_time);
$time_end=$result_time['end_at'];
$test_code=$result_time['test_code'];
$paper_set=$result_time['shift'];
// stratquiz
$sql_stratquiz=mysqli_query($conn,"SELECT * FROM startquiz WHERE test_code='".$test_code."'");
$_startquiz1=mysqli_num_rows($sql_stratquiz);

if($_startquiz1==0){$arry1="No Student is live";$_startquiz1="No student is live";}
else{
$sno_array1=0;
while($result_startquiz = mysqli_fetch_assoc($sql_stratquiz)){
$alert_val="success";

if(empty($result_startquiz['value'])){$_startquiz1=$_startquiz1-1;$result_startquiz['value']="Exit from test";$result_startquiz1['rollno']='';$alert_val="danger";}
$sql_stratquiz1=mysqli_query($conn,"SELECT * FROM std_login WHERE code='".$result_startquiz['code']."'");
$result_startquiz1 = mysqli_fetch_assoc($sql_stratquiz1);
$arry1[]="<div class='alert alert-$alert_val' role='alert'>".$result_startquiz1['name']."<hr>Rollno: ".octdec(hexdec($result_startquiz1['rollno'])).", Active status: ".$result_startquiz['value']." 
<hr>Questions attend: ".$result_startquiz['qusno']."<hr>


<form action='' method='post'>
<div class='btn-group btn-group-sm' role='group' aria-label=''>
<button type='submit' value='".$result_startquiz['id']."_w_".$_POST['warning']."' name='insert' class='btn btn-outline-warning btn-sm'>Warning</button>
<button type='submit' value='".$result_startquiz['id']."_e' class='btn btn-outline-danger btn-sm' name='insert'>Exit</button>
</div>
</form>



</div>";
$sno_array1++;}}
// registered
$sql_registered=mysqli_query($conn,"SELECT * FROM registered_table WHERE test_code='".$test_code."'");
$_startquiz2=mysqli_num_rows($sql_registered);
if($_startquiz2==0){$arry2="opean source test";$_startquiz2="Can't count in opean Test";}
else{while($result_startquiz = mysqli_fetch_assoc($sql_registered)){
$arry2[]="<div class='alert alert-primary' role='alert'>".$result_startquiz['name_std']."<hr>Rollno: ".octdec(hexdec($result_startquiz['rollno'])).",Test code: ".$result_startquiz['test_code']."
</div>";}}
$std_data_array=array($arry1,$arry2,$_startquiz2,$_startquiz1,$time_end,$_POST['test_code'],$paper_set);
$myJSON1 = json_encode($std_data_array);
echo $myJSON1;}



// rank list
if($_POST['query_select']=='rank_list'){
$e1 = decoct($_POST['test_code']);
$e2 = dechex($e1);

$sql3=mysqli_query($conn,"update institute_login set current_test_code='none' where code='".$_POST['code']."'");
$sql7 = mysqli_query($conn,"SELECT * FROM ".$_POST['paper_set']." WHERE test_code='".$e2."' and code_inst='".$_POST['code']."'");
while($result2 = mysqli_fetch_assoc($sql7)){	
$qus[]=$result2['qus'];
$opt1[]=$result2['opt1'];$opt2[]=$result2['opt2'];
$opt3[]=$result2['opt3'];$opt4[]=$result2['opt4'];
$ans[]=$result2['ans'];$code_inst[]=$_POST['code'];};
for($i=0;$i<count($qus);$i++){
$docode_qus=(explode("</cw-cat/>",$qus[$i]));
if($docode_qus[1]=="imgqus"){
$rand=rand(1111,9999);
$movefile='que_image/'.$docode_qus[0];
$tomove='done_qus_img/'.$rand.'_'.$docode_qus[0];
rename($movefile,$tomove);
$qus_f=$rand.'_'.$qus[$i];
}else{$qus_f=$qus[$i];}	
$q=mysqli_query($conn,"insert into site_ques (ques,opt1,opt2,opt3,opt4,ans,code_inst) values ('$qus_f','$opt1[$i]','$opt2[$i]','$opt3[$i]','$opt4[$i]','$ans[$i]','$code_inst[$i]')");}
$sql2=mysqli_query($conn,"SELECT * FROM result where test_code='".$e2."' ORDER BY percent DESC");
$sno=1;
while($result2 = mysqli_fetch_assoc($sql2)){
$name=htmlspecialchars($result2['name']);
$rank_percent=htmlspecialchars($result2['percent']);
$cut=$result2['wrong']/4;
$final_marks=$result2['correct']-$cut;
echo "<tr><td>$sno</td><td>$name</td><td>".hexdec(substr($result2['rollno'],0,-4))."</td><td>$rank_percent</td><td>$final_marks/".$result2['total']."</td></tr>";
$sno++;}
if($sql2){	
$q0=mysqli_query($conn,"update inst_all_time_data set test_code='' where test_code='".$e2."'");
$q1=mysqli_query($conn,"delete from ".$_POST['paper_set']." where test_code='".$e2."'");
$q2=mysqli_query($conn,"delete from registered_table where test_code='".$e2."'");
$q3=mysqli_query($conn,"delete from timing where test_code='".$e2."'");
$q4=mysqli_query($conn,"delete from startquiz where test_code='".$e2."'");
$q5=mysqli_query($conn,"delete from result where test_code='".$e2."'");
}}
if($_POST['query_select']=='logout'){
$q0=mysqli_query($conn,"update ".$_POST['table']." set login_status='' where code='".$_POST['code']."'");
if($q0){echo "Done";}}

?>
