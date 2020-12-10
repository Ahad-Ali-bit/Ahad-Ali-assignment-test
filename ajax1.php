<?php
include("mainfile/connection.php");

if($_POST['query_select']=='search_paper_qus'){

$explode_id=explode('<//>',$_POST['search_by_']);
$data = mysqli_query($conn, "SELECT * FROM ".$explode_id[1]." where id='".$explode_id[0]."' and code_inst='".$_POST['code']."'");
$result = mysqli_fetch_assoc($data);

$docode_qus=(explode("</cw-cat/>",html_entity_decode($result['qus'])));
if(empty($docode_qus[1])){$qus_final='<textarea type="text" class="form-control" id="qus">'.$docode_qus[0].'</textarea>';}
elseif($docode_qus[1]=="imgqus"){$qus_final="<img src='que_image/".$docode_qus[0]."' class='zoom' style='height:140px;width:100%;'>
<input type='hidden' id='qus' value='".$result['qus']."'>";}

$data_array =array($qus_final,html_entity_decode($result['opt1']),html_entity_decode($result['opt2']),html_entity_decode($result['opt3']),html_entity_decode($result['opt4']),html_entity_decode($result['ans']),
html_entity_decode($result['paper_set']),html_entity_decode($explode_id[1]),$result['id']);
echo json_encode($data_array);
exit;}

else if($_POST['query_select']=='update_paper_qus'){
$explode_data=explode('<//>',$_POST['all_data']);
$ans=$explode_data[$explode_data[5]];
$sql = mysqli_query($conn,"UPDATE $explode_data[6] set qus='".addslashes($explode_data[0])."',opt1='$explode_data[1]',
opt1='$explode_data[1]',opt2='$explode_data[2]',opt3='$explode_data[3]',opt4='$explode_data[4]',ans='$ans',paper_set='".ucfirst($explode_data[8])."' where id='$explode_data[7]'");
echo "Updated refresh page to see update";
}
else if($_POST['query_select']=='delete_paper_qus'){
	$sql = mysqli_query($conn,"Delete from ".$_POST['paperset']." where id='".$_POST['paper_id']."'");
	if($sql){
	$docode_qus=(explode("</cw-cat/>",$_POST['qus']));
	if(!empty($docode_qus[1])){unlink("que_image/".$docode_qus[0]);}
	echo $docode_qus[0];}
}
else if($_POST['query_select']=='addnew_paper_qus'){
	$explode_data=explode('<//>',$_POST['test_code']);
	$sql = mysqli_query($conn,"INSERT INTO $explode_data[1] (test_code,code_inst) value ('$explode_data[0]','".$_POST['code']."')");
	if($sql){echo "Done refresh page";}
	
}
elseif($_POST['query_select']=='rollno_insert'){
$rollno=rand(111111,999999);
$sql1=mysqli_query($conn, "SELECT rollno FROM std_login where code='".$_POST['code']."'");
$data=mysqli_fetch_assoc($sql1);
if($data['rollno'] !=0){echo octdec(hexdec($data['rollno']));}
else{
$sql2=mysqli_query($conn, "SELECT rollno FROM std_login where rollno='$rollno'");	
$count=mysqli_num_rows($sql2);
$e1 = decoct($rollno);
$e2 = dechex($e1);
if($count==0){$sql = mysqli_query($conn,"update std_login set rollno='$e2' where code='".$_POST['code']."'");}
elseif($count==1){echo "refresh_comd";}}}



elseif($_POST['query_select']=='img_user'){
if($_FILES['img']['name'] != ''){
    $test = explode('.', $_FILES['img']['name']);
    $extension = end($test);    
    $name = md5(rand(1000,9999)).'std.'.$extension;
    $location = 'user_image/candidate/'.$name;
	$sql = mysqli_query($conn,"UPDATE ".$_POST['table_name']." set photo='$name' where code='".$_POST['code']."'");
	if($sql){
    move_uploaded_file($_FILES['img']['tmp_name'], $location);
    echo 'Done';}}}
elseif($_POST['query_select']=='img_qus'){
$continue=0;
// Getting file name
$test = explode('.', $_FILES['img']['name']);
$extension = end($test);    
$filename = $_POST['paper_set'].'_'.$_POST['paper_id'].'.'.$extension;
if(file_exists("que_image/".$filename)){unlink("que_image/".$filename);$continue=1;}else{$continue=1;}
if($continue==1){
// Valid extension
$valid_ext = array('png','jpeg','jpg');
// Location
$location = "que_image/".$filename;
// file extension
$file_extension = pathinfo($location, PATHINFO_EXTENSION);
$file_extension = strtolower($file_extension);
// Check extension
if(in_array($file_extension,$valid_ext)){
// Compress Image
$sql = mysqli_query($conn,"UPDATE ".$_POST['paper_set']." set qus='$filename</cw-cat/>imgqus' where id='".$_POST['paper_id']."'");	
compressImage($_FILES['img']['tmp_name'],$location,60);}else{echo "Invalid file type.";}
echo "Done";}}
// Compress image
function compressImage($source, $destination, $quality){
  $info = getimagesize($source);
  if ($info['mime'] == 'image/jpeg') 
    $image = imagecreatefromjpeg($source);
  elseif ($info['mime'] == 'image/gif') 
    $image = imagecreatefromgif($source);
  elseif ($info['mime'] == 'image/png') 
    $image = imagecreatefrompng($source);
  imagejpeg($image, $destination, $quality);
}
?>