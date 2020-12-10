<?php
include("mainfile/connection.php");
include("mainfile/header.php");
// url
$x_main = explode("/",$_SERVER['REQUEST_URI']);
$url_main="/".$x_main[1];

if($_SERVER['REQUEST_URI'] !=$url_main.'/dashboard-institute?na='.$_GET['na']){
echo "<script type='text/javascript'>window.location.href = '$url_main/login';</script>";
}

$redirection="false";
if($_GET['na']==""){$redirection="true";}

$sql1=mysqli_query($conn,"SELECT * FROM institute_login WHERE code='".$_GET['na']."'");
$count_sql1=mysqli_num_rows($sql1);
if($count_sql1<1){$redirection="true";}
else{
$result_login_sql1 = mysqli_fetch_assoc($sql1);
if($result_login_sql1['ip_address'] != $_SERVER['REMOTE_ADDR'] or $result_login_sql1['login_date']!=(date("d-m-Y",time())) or $result_login_sql1['login_status']!='Active')
{$sql=mysqli_query($conn,"UPDATE institute_login SET login_status='offline' where id='".$result_login_sql1['id']."' and code='".$_GET['na']."'");
$redirection="true";}
}

if($redirection=="true"){echo "<script>window.location.replace('$url_main/login');</script>";}
else{
$name=$result_login_sql1['name'];
$test_code=$result_login_sql1['current_test_code'];

$sql2=mysqli_query($conn,"SELECT * FROM inst_overalldata WHERE code='".$_GET['na']."'");
$result_inst_overalldata = mysqli_fetch_assoc($sql2);
$total_inst_overalldata=$result_inst_overalldata['total'];
$std_attendinst_overalldata=$result_inst_overalldata['std_attend'];
$opeansrcinst_overalldata=$result_inst_overalldata['opean_source'];
$registereinst_overalldata=$result_inst_overalldata['registered'];

 
$sql2=mysqli_query($conn,"SELECT * FROM institute_detail WHERE code='".$_GET['na']."'");
$result_detail_inst = mysqli_fetch_assoc($sql2);


echo "<script> 
document.getElementById('header').innerHTML='Welcome $name (institute panel)';
document.getElementById('title').innerHTML='Crackers way: $name (institute panel)';</script>";

// redirection else
}
?>
<style>
.img{width:400px;
	height:auto;
}
.cropper-crop{
	display:none;
}
.cropper-bg{
	background:none;	
}
</style>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>document.getElementById('button').innerHTML='<a class="logout btn btn-outline-danger btn-sm">Logout</a>';</script>
<body>
<div class="container">
<div class="row">
<div class="col-sm-10" style="border:1px solid blue;">
<h4>Your status <?php echo $name;?></h4>

<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <a class="nav-link active" id="Dashboard-tab" data-toggle="tab" href="#Dashboard" role="tab" aria-controls="Dashboard" aria-selected="true">Dashboard</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="Upload-tab" data-toggle="tab" href="#Upload" role="tab" aria-controls="Upload" aria-selected="false">Upload</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="live_data-tab" data-toggle="tab" href="#live_data" role="tab" aria-controls="live_data" aria-selected="false">Live data
	<span class="live1 spinner-grow theam-bg-color" role="status" aria-hidden="true" style="width:10px;height:10px;vertical-align:inherit;display:none;"></span></a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
<div class="tab-pane fade show active" id="Dashboard" role="tabpanel" aria-labelledby="Dashboard-tab">  
<div class="row">
<div class="col-sm-8">
  <div class="card">
      <div class="card-body" data-toggle="modal" data-target="#modal_photo">
        <img src="user_image/institute/<?php echo $result_detail_inst['photo'];?>" class="img-fluid img-thumbnail" alt="Click to upload Banner image" style="width:100%;height:350px;">
      </div>
    </div>
  </div>
<div class="col-sm-4">
<div class="card"><small><?php echo $r_time." (Time Zone >".$timezone.")";?></small>
      <div class="card-body">
      <h5 class="card-title" id="name"></h5>
	  <p class="card-text" id="director"></p>
	  <hr>
      <span class="card-text" id="address"></span>
	  <span class="card-text" id="city"></span>
	  <hr>
	  <span class="card-text" id="email"></span>
	  <span class="card-text" id="contact"></span>
	  <hr>
	  <p data-toggle="modal" data-target="#edit_std" class="card-text"><small class="text-muted">Edit</small></p>
      </div>
    </div>
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Message Box</h5>
        <p class="card-text">Coming Soon...</p>
      </div>
    </div>
	
  </div>
</div>
<div class="row">
<div class="col-sm-8">
    <div class="card">
      <div class="card-body">
        <div id="piechart_3d"></div>
      </div>
    </div>
  </div>
<div class="col-sm-4">
    <div class="card">
      <div class="card-body">
       <p>Total Test: <?php echo $total_inst_overalldata;?></p>
	   <p>Total student attend: <?php echo $std_attendinst_overalldata;?></p>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title"id="cus_text1">Students attend in last 5 Tests </h5>
        <div id="chart_div" style="width:100%; height:250px;"></div>
      </div>
    </div>
  </div>
</div>
<!--Dashboard--> 
</div>
  <div class="tab-pane fade" id="Upload" role="tabpanel" aria-labelledby="Upload-tab">
  <div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Add new test</h5>
		<hr>
<form action="" method="post" enctype="multipart/form-data">
<div style="border:1px solid red; padding:4px; margin-top:5px;"><p>Upload CSV file</p>
<label for="exampleInputFile">File Upload</label>
<input type="file" name="file" id="file" value="">
<p class="help-block" style="color:red;">Only .CSV File Import. (<a href="#">Download demo File</a>)</p>
<p>Excelsheet must contain these points</p>
<ul>		
	<li>question</li>
	<li>Option1</li><li>Option2</li><li>Option3</li><li>Option4</li>
	<li>answer(only number like 1,2,3,4)</li>
	<small style="color:blue;">If option 3 is correct then write in answer coloum is 3</small>
	<li>Paper set(A,B,C,D...,)</li>
	<small style="color:blue;">Blank or empty paperset coloum take value 'A' by default</small>
	</ul>
	</p>Please download our demo Excelsheet for better understanding</p>
</div>
<div style="border:1px solid blue; padding:4px; margin-top:5px;">
<select name="paper_set">
<option value="paper1" class="" id="shift1">Shift 1</option>
<option value="paper2" class="" id="shift2">Shift 2</option>
</select>
<select name="access_catagery" required>
<option value="opean_source">Opean source</option>
<option value="registered">Registered only</option>
</select>
<hr>
<small style="color:blue;">
	  if select (Opean source) then,
	  <ul>
	  <li>If student share test key with someone then they also join the test series.</li>
	  </ul>
</small>
<small style="color:blue;">
	  if select (Registered only) then,
	  <ul>
	  <li>If student share test key with someone then they can't join the test series without your permission.</li>
	  <li>You have to registered each student manually.</li>
	  </ul>
</small></div>
		<hr>
<div style="border:1px solid green; padding:4px; margin-top:5px;">
		<p>Timings</p>
		<label style="color:green;">Start at</label>
		<input style="color:green;" type="datetime-local" id="" name="strt_time" required>
		<label style="color:red;">End at</label>
		<input style="color:red;" type="datetime-local" id="" name="end_time" required>
</div>		
<hr>
<div>
<textarea name="instruction" placeholder="Any Instruction for student"></textarea>
</div>
<hr>
<input type="submit" class="btn theam-button theam-text-color cus_but1" name="file_upload" onclick="progress_show()">
<hr>
</form>
      </div>
    </div>
  </div>
</div>
<?php
$sql_paper1=mysqli_query($conn, "SELECT * FROM paper1 WHERE code_inst='".$_GET['na']."'");
$count1=mysqli_num_rows($sql_paper1);

$sql_paper2=mysqli_query($conn, "SELECT * FROM paper2 WHERE code_inst='".$_GET['na']."'");
$count2=mysqli_num_rows($sql_paper2);
?>
<div class="row">
<div class="col-sm-6">
<div class="card">
<div class="card-body">
<h6 class="card-title">Preview (Shift 1 questions) Total question=<?php echo $count1;?></h6>
<small style="color:red;" id="counter1">Live in 00h:00m:00s</small>
<div style="height:400px;overflow:scroll;">
<table class="table table-striped" >
<?php 
$srno=1; while($result = mysqli_fetch_assoc($sql_paper1)){
	
$docode_qus=(explode("</cw-cat/>",html_entity_decode($result['qus'])));
if(empty($result['qus'])){$qus_prnt="<span style='color:red;'>Click edit button to enter this question</span>";}
elseif(empty($docode_qus[1])){$qus_prnt=htmlentities($docode_qus[0]);}else{$qus_prnt="Image";}

echo "<tr><td>$srno</td><td>$qus_prnt</td><td>
<button type='button' data-toggle='modal' data-target='#staticBackdrop' class='paper1 edit_qus btn btn-danger btn-sm' id='".$result['id']."<//>paper1'>Edit</button>
</td></tr>";
$srno++;$test_code1=$result['test_code'];};
if($count1>0){
$sql_time1=mysqli_query($conn, "SELECT * FROM timing WHERE code_inst='".$_GET['na']."' and test_code='$test_code1'");
$result_time1 = mysqli_fetch_assoc($sql_time1);
$timesrt1 = $result_time1['start_at'];$paper_shift1=$result_time1['shift'];
echo "Test Entry key ".octdec(hexdec($test_code1))."";}else{echo "<script>document.getElementById('counter1').style.display='none'</script>";$timesrt1=0;}
?>
</table>
</div>
<button class="add_new add_new1 btn theam-bg-color theam-text-color" style="display:none;" id="<?php echo $test_code1."<//>".$paper_shift1;?>">Add new question</button>
</div>
</div>
</div>
  <div class="col-sm-6">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Preview (Shift 2 questions),Total question=<?php echo $count2;?></h6>
		<small style="color:red;" id="counter2">Live in 00h:00m:00s</small>
		<div style="height:400px;overflow:scroll;">
		<table class="table table-striped" >
        <?php $srno=1;
		while($result = mysqli_fetch_assoc($sql_paper2)){
		
$docode_qus=(explode("</cw-cat/>",html_entity_decode($result['qus'])));
if(empty($result['qus'])){$qus_prnt="<span style='color:red;'>Click edit button to enter this question</span>";}
elseif(empty($docode_qus[1])){$qus_prnt=htmlentities($docode_qus[0]);}else{$qus_prnt="Image";}

echo "<tr><td>$srno</td><td>$qus_prnt</td><td>
<button type='button' data-toggle='modal' data-target='#staticBackdrop' class='paper2 edit_qus btn btn-danger btn-sm' id='".$result['id']."<//>paper2'>Edit</button></td></tr>";		
		$srno++;
		$test_code2=$result['test_code'];};
		if($count2>0){
		$sql_time=mysqli_query($conn, "SELECT * FROM timing WHERE code_inst='".$_GET['na']."' and test_code='$test_code2'");
        $result_time2 = mysqli_fetch_assoc($sql_time);
		$timesrt2 = $result_time2['start_at'];$paper_shift2=$result_time2['shift'];
		echo "Test Entry key ".octdec(hexdec($test_code2))."";}else{echo "<script>document.getElementById('counter2').style.display='none'</script>";$timesrt2=0;}
		?>
		</table>
		</div>
		<button class="add_new add_new2 btn theam-bg-color theam-text-color" style="display:none;" id="<?php echo $test_code2."<//>".$paper_shift2;?>">Add new question</button>
      </div>
    </div>
  </div>
</div>

<?php
if(isset($_POST['registered_test'])){
$e1 = decoct($_POST['registered_rollno']);
$e2 = dechex($e1);

$e1_test = decoct($_POST['registered_key']);
$e2_test = dechex($e1_test);
$sql1=mysqli_query($conn,"insert into registered_table (name_std,rollno,test_code,shift,code_inst) values ('".$_POST['registered_name']."','".$e2."','".$e2_test."','".$_POST['register_shift']."','".$_GET['na']."')");	
if($sql1){echo "<script>alert('Registered');</script>";}
}
if(isset($_POST['file_upload'])){
if(!empty($_POST['paper_set'])){
	      $catagery=$_POST['access_catagery'];
	      $paper_set=$_POST['paper_set'];
          $target_file=$_FILES["file"]["name"];
		  $check=strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		  if($check =="csv"){$upload_permission=1;}else{$upload_permission=0;}
		  if($upload_permission==1){
		  $e1 = decoct(rand(111111,999999));
          $test_code = dechex($e1);
          $file = $_FILES['file']['tmp_name'];
          $handle = fopen($file, "r");
		  $c=0;
		  $stmt =mysqli_query($conn,"insert into timing (start_at,end_at,code_inst,test_code,shift,test_catagery,instruction) values 
		  ('".date("Y-m-d H:i", strtotime($_POST['strt_time']))."','".date("Y-m-d H:i", strtotime($_POST['end_time']))."','".$_GET['na']."','$test_code','$paper_set','".$_POST['access_catagery']."','".$_POST['instruction']."')");
          while(($filesop = fgetcsv($handle,1000, ",")) !== false)
         {
		  if(!empty($filesop[0]) and !empty($filesop[5]) and $filesop[5]<=4 and $filesop[5]>0){
          $qus = (addslashes($filesop[0]."</cw-cat/>"));
          $opt1 = (addslashes($filesop[1]));$opt2 = (addslashes($filesop[2])); 
		  $opt3 = (addslashes($filesop[3]));$opt4 = (addslashes($filesop[4]));
		  $ans=(addslashes($filesop[5]));
		  $final_ans=$filesop[$filesop[5]];
		  
		  if(empty($filesop[6])){$filesop6="A";}else{$filesop6=$filesop[6];}
		  $paperset=(addslashes(ucfirst($filesop6)));
          $sql2 = "insert into $paper_set (qus,opt1,opt2,opt3,opt4,ans,test_code,code_inst,paper_set) values ('$qus','$opt1','$opt2','$opt3','$opt4','$final_ans','$test_code','".$_GET['na']."','$paperset')";
          $stmt =mysqli_query($conn,$sql2);
		  if($sql2){$c=$c+1;}
         }
		 }
if($sql2){$sql3=mysqli_query($conn,"update timing set total_qus=$c where test_code=$test_code");
		  $sql4=mysqli_query($conn,"update inst_overalldata set total=total+1,$catagery=$catagery+1 where code='".$_GET['na']."'");
		  $sql4=mysqli_query($conn,"insert into inst_all_time_data (test_code,code,date) value ('$test_code','".$_GET['na']."','".(date("d-m-Y",time()))."')");
		  echo "<script>alert('Successfully uploaded $target_file, total ques = $c')
		  window.location.replace('$url_main/dashboard-institute?na=".$_GET['na']."');</script>";} 
	      else{echo "<script>alert('Sorry! Unable to import.')</script>";}
		  }else{echo "<script>alert('Only .csv file can be uploaded your file extension is .$check')</script>";}
}else{echo "<script>alert('Data is not uploaded');</script>";}}


if(!empty($timesrt1)){
echo'
<script>
var x1 = setInterval(function() {
  var distance1 = new Date("'.$timesrt1.'").getTime() - new Date().getTime();
  var days = Math.floor(distance1 / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance1 % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance1 % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance1 % (1000 * 60)) / 1000);
  document.getElementById("counter1").innerHTML ="Live in "+ days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";
  $(document).ready(function(){$("#shift1").remove();$(".add_new1").css("display", "");});
  if (distance1 < 0) {
    clearInterval(x1);
    document.getElementById("counter1").innerHTML = "Test is live";
	document.getElementById("title").innerHTML="Crackers way: '.octdec(hexdec($test_code1)).' Test is live";
	$(document).ready(function(){
		$(".live_test").html('.octdec(hexdec($test_code1)).');
		$(".paper1").remove();
		$("#spin_cus1,.live1").css("display", "");
		});}
}, 1000);</script>';
}
if(!empty($timesrt2)){
echo'<script>
var x2 = setInterval(function() {
  var distance2 = new Date("'.$timesrt2.'").getTime() - new Date().getTime();
  var days = Math.floor(distance2 / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance2 % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance2 % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance2 % (1000 * 60)) / 1000);
  document.getElementById("counter2").innerHTML ="Live in "+ days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";
  $(document).ready(function(){$("#shift2").remove();$(".add_new2").css("display", "");});
  if (distance2 < 0) {
    clearInterval(x2);
    document.getElementById("counter2").innerHTML = "Test is live (refresh page)";
	document.getElementById("title").innerHTML="Crackers way: '.octdec(hexdec($test_code2)).' Test is live";
	$(document).ready(function(){
		$("#spin_cus1,.live1").css("display", "");
		$(".paper2").remove();
		$(".live_test").html('.octdec(hexdec($test_code2)).');
		});}}, 1000);
</script>';}
?>
<!--Upload--> 
</div>
<div class="tab-pane fade" id="live_data" role="tabpanel" aria-labelledby="live_data-tab">
<div class="row">
  <div class="col-sm-6">
    <div class="card">
      <div class="card-body">
      <video id='video' width="100%" height="auto" playsinline autoplay></video>
      </div>
    </div>
  </div>
  <div class="col-sm-6">
<div class="card">
<div class="card-body">
<p id="test_val" style="display:none;">false</p>
<span id="spin_cus1" style="display:none;">
<span class="spinner-grow theam-bg-color" role="status" aria-hidden="true" style="width:10px;height:10px;vertical-align:inherit;"></span>
<span>Test is live: </span>
<span class="live_test"></span>
</span> 
<p class="card-text catagery_test_registered">live your test with our support any time.</p>
<p class="card-text live_std spinner-grow-cus1" style="">No Test live<p>
<button type="button" class="btn rank_list btn theam-bg-color theam-text-color btn-primary" data-toggle="modal" data-target="#ranklist">Rank list</button>
</div>
</div>


<div class="alert alert-warning" role="alert">
  Add div 
</div>
</div>
</div>
<div class="row">
  <div class="col-sm-6">
    <div class="card">
	<h6>Students that you are registered for test (<span id="livein_registry_total"></span>)</h6>
	<hr>
<div class="card-body">
<!--alert-->
<div style="max-height:300px;overflow:;">
<marquee direction="up" height="300px" onMouseOver="this.stop()" onMouseOut="this.start()" scrollamount="2">
<div class="registered_std_live">
</div>
</marquee>
</div>
</div>
</div>
</div>
  <div class="col-sm-6">
    <div class="card">
	<h6 class="card-title">Students who appare in the live test (<span id="livein_test_total"></span>)</h6>
	<hr>
      <div class="card-body">
<div style="max-height:300px;overflow:;">
<marquee direction="up" height="300px" onMouseOver="this.stop()" onMouseOut="this.start()" scrollamount="2">
<div class="alert alert-primary" role="alert">
 Add
</div>
<div class="aappear_std_live">
</div>
</marquee>
</div>
      </div>
    </div>
  </div>
</div>
<!--live_data-->
  </div>
</div>
<!--col-8-->
</div>
<div class="col-sm-2" style="border:1px solid green;">
<form action='' method="post">
  <div class="row" style="margin-bottom:20px;">
  <span style='margin:auto;'>Register student here for the test</span>
  <hr>
    <div class="col-sm-12">
      <input type="text" name="registered_name" class="form-control" placeholder="Stdent Name" required>
    </div>
    <div class="col-sm-12">
      <input type="text" name="registered_rollno" class="form-control" placeholder="Student Rollno" required>
    </div>
	<div class="col-sm-12">
      <input type="text" name="registered_key" class="form-control" placeholder="Your Test entry key" required>
    </div>
	<div class="col-sm-12">
    <select name="register_shift" required>
		<option value="paper1<//><?php echo $count1?>">Shift 1</option>
		<option value="paper2<//><?php echo $count2?>">Shift 2</option>
		</select>
    </div>
  </div>
  <input type="submit" name="registered_test" class="btn theam-text-color theam-bg-color" value="Submit">
</form>
<hr>
<select class="warning_select form-control">
<option>Warning from mentor</option>
<option>Mentor: We are going to exit you</option>
<option>Mentor: Keep fast</option>
</select>
<span id="cus_indivisual_data"></span>
<!--col-2-->	 
</div>

<!--container,row-->
</div>
</div>
<!-- Modal1 -->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="shift"></h5>
      </div>
      <div class="modal-body">	  
<div class="accordion" id="accordionExample">
  <div class="card">
    <div class="" id="headingTwo">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
         Upload image
        </button>
      </h2>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
    <div class="card-body">
<small>More images will slow down your test.Please Keep your questions in text as much as possible</small><hr>  
<input type="file" id="upload_img" onchange="document.getElementById('img').src = window.URL.createObjectURL(this.files[0])" accept="image/jpeg,image/png">
<button class="upload btn btn-success">Upload</button>
<img src="" id="img" class="img img-fluid" alt="">	  
	  </div>
    </div>
  </div>
</div>	  
  <div class="form-group">
    <label for="exampleFormControlInput1">Question</label>
	<span id="qus_div"></span>
  </div>
  <div class="form-group">
    <label>Options</label>
    <input type="text" class="form-control" id="opt1"><input type="text" class="form-control" id="opt2">
	<input type="text" class="form-control" id="opt3"><input type="text" class="form-control" id="opt4">
  </div>
  <div class="form-group">
    <label for="">Answer</label>
    <select type="text" id="ans" class="form-control" required>
	<option id="ans_val"></option>
	<option value="1">Option 1 is correct</option><option value="2">Option 2 is correct</option>
	<option value="3">Option 3 is correct</option><option value="4">Option 4 is correct</option>
	
	</select>
  </div>
  <div class="form-group">
    <label for="exampleFormControlTextarea1">Paper set</label>
    <input class="form-control" id="paper_set">
	<input type="hidden" id="paper_id">
  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="Update btn btn-success" id="">Update</button>
		<button type="button" class="delete btn btn-danger">Delete</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal ranklist  -->
<div class="modal fade" id="ranklist" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle"></h5>
      </div>
      <div class="modal-body">
<div id="ranklistprint">
<div class="table-responsive">  
<table class="table table-striped">
  <thead>
    <tr>
	  <th colspan="5">Test Code: <span class="test_code_rank">000000</span></th>
	</tr>
	<tr>
	  <th colspan="5"><?php echo date("d-m-Y",time());?></th>
	</tr>
	<tr>
      <th scope="col">Rank</th>
      <th scope="col">Name</th>
      <th scope="col">Rollno</th>
      <th scope="col">Percent</th>
	  <th scope="col">Marks/Out of</th>
    </tr>
  </thead>
  <tbody class="data_ranklist">
  </tbody>
  </table>
</div>
</div>
</div>
<div class="modal-footer">
<button type="button" class="close_rank btn btn-secondary">Close</button>
<button type="button" class="btn btn-primary" onclick="createPDF()">Print</button>
</div>
</div>
</div>
</div>
<span id="cus_video"></span>
<script>
$(document).ready(function(){
var cus_url1="ajax1";
var cus_url2='detailcommon';
var code = "<?php echo $_GET['na'];?>";
$('.edit_qus').click(function(){
	var search_by_ = this.id;
	$.ajax({
    url:cus_url1,
    type: 'post',
	dataType:'JSON',
    data: {search_by_:search_by_, query_select:"search_paper_qus",code:code},
    success: function(response){
		$('#qus_div').html(response[0]);
		$('#opt1,.opt1').val(response[1]);$('#opt2,.opt2').val(response[2]);
		$('#opt3,.opt3').val(response[3]);$('#opt4,.opt4').val(response[4]);
		$('#ans_val').html(response[5]);
		
		if(response[5]==response[1]){var ans_no=1;}else if(response[5]==response[2]){var ans_no=2;}
		else if(response[5]==response[3]){var ans_no=3;}else if(response[5]==response[4]){var ans_no=4;}
		$('#ans_val').val(ans_no);
		$('#paper_set').val(response[6]);$('#paper_id').val(response[8]);
		if(response[7]=='paper1'){$('#shift').html('Shift 1');}else if(response[7]=='paper2'){$('#shift').html('Shift 2');}
		}
});
// alert('');
});
$('.Update').click(function(){
	var qus=$('#qus').val();
	var opt1=$('#opt1').val();var opt2=$('#opt2').val();
	var opt3=$('#opt3').val();var opt4=$('#opt4').val();
	var ans=$('#ans').val();
	var shift=$('#shift').html();
	if(shift=='Shift 1'){var paperset='paper1';}else if(shift=='Shift 2'){paperset='paper2';}
	// alert(ans);
	var all_data=qus+"<//>"+opt1+"<//>"+opt2+"<//>"+opt3+"<//>"+opt4+"<//>"+ans+"<//>"+paperset+"<//>"+$('#paper_id').val()+"<//>"+$('#paper_set').val();
	$.ajax({
    url:cus_url1,
    type: 'post',
    data: {all_data:all_data,query_select:"update_paper_qus",code:code},
    success: function(response){alert(response); 
	window.location.replace('<?php echo $url_main;?>/dashboard-institute?na='+code)
	}
  });
});
$('.delete').click(function(){
	var shift=$('#shift').html();
	if(shift=='Shift 1'){var paperset='paper1';}else if(shift=='Shift 2'){paperset='paper2';}
	$.ajax({
    url:cus_url1,
    type: 'post',
    data: {paperset:paperset,query_select:"delete_paper_qus",code:code,paper_id:$('#paper_id').val(),qus:$('#qus').val()},
    success: function(response){alert(response);window.location.replace('<?php echo $url_main;?>/dashboard-institute?na='+code);}
  });
})
$('.add_new').click(function(){
	var test_code=this.id;
	$.ajax({
    url:cus_url1,
    type: 'post',
    data: {query_select:"addnew_paper_qus",code:code,test_code:test_code},
    success: function(response){alert(response);window.location.replace('<?php echo $url_main;?>/dashboard-institute?na='+code);}
  });
});
$.ajax({
    url: cus_url2,
    type: 'post',
	dataType: 'JSON',
    data: {query_select:"inst_data",code:code},
    success: function(response){
		$('#director').html(response[1][2]);
		$('#name').html(response[1][0]);
		$('#email').html(response[1][5]);
		$('#contact').html("("+response[1][4]+")");
		$('#address').html(response[1][1]);
		$('#city').html(","+response[1][3]);
		}
});
// live data
$('.rank_list,#cus_indivisual_data,.warning_select').hide();
setInterval(function(){
if($('.live_test').html() !=""){
$('.warning_select').show();
// alert(code);
$.ajax({
    url: cus_url2,
    type: 'post',
	dataType: 'JSON',
    data: {query_select:"start_quiz",test_code:$('.live_test').html(),code:code,warning:$('.warning_select').val()},
    success: function(response){
	$('.cus_but1').remove();
	// alert(response[0]);
    $('.aappear_std_live').html(response[0]);
	$('.registered_std_live').html(response[1]);
	$('#livein_registry_total').html(response[2]);
	$('#livein_test_total').html(response[3]);
	$('.test_code_rank').html(response[5]);
	$('.live_std').html("Students live: "+response[3]);
	$("#test_val").html(response[6]);
	$('#cus_indivisual_data').show();
	var cus_val=Math.floor((Math.random() *response[0].length));
	$('#cus_indivisual_data').html(response[0][cus_val]);
var distance = new Date(response[4]).getTime()-new Date().getTime();
// var days = Math.floor(distance / (1000 * 60 * 60 * 24));
var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
var seconds = Math.floor((distance % (1000 * 60)) / 1000);
$('.catagery_test_registered').html("End Test on: "+hours+"h "+minutes+"m "+seconds+"s");
if(distance < 0){
$('.rank_list').show();$("#spin_cus1").html("Test is over");$('.live_std').html("Print your rank  list, data will clear on refresh page");
$('.catagery_test_registered').html('00');
}
}
});}
},2000);  
$('.warning').click(function(){
	// var test_code=this.id;
	// $.ajax({
    // url:cus_url1,
    // type: 'post',
    // data: {query_select:"addnew_paper_qus",code:code,test_code:test_code},
    // success: function(response){alert(response);window.location.replace('<?php echo $url_main;?>/dashboard-institute?na='+code);}
  // });
  alert(this.id);
});
$('.rank_list').click(function(){
$.ajax({
url:cus_url2,
type: 'post',
data: {query_select:"rank_list",test_code:$('.test_code_rank').html(),code:code,paper_set:$("#test_val").html()},
success: function(response){$('.data_ranklist').html(response);}
});
});
$('.close_rank').click(function(){
var confir = confirm("After close it, data will clear. Make sure you have a print before close it");
if(confir==true){window.location.replace("<?php echo $url_main;?>/dashboard-institute?na="+code);}
});
$('.logout').click(function(){
$.ajax({
    url:cus_url2,
    type: 'post',
    data: {query_select:"logout",code:code,table:"institute_login"},
    success: function(response){if(response=="Done"){window.location.replace("<?php echo $url_main;?>/dashboard-institute?na="+code)};}
});});
// dom
});
// Grab elements, create settings, etc.
var video = document.getElementById('video');

// Get access to the camera!
if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
    // Not adding `{ audio: true }` since we only want video now
    navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream){
        //video.src = window.URL.createObjectURL(stream);
        video.srcObject = stream;
        video.play();
    });
}

 // Legacy code below: getUserMedia 
else if(navigator.getUserMedia) { // Standard
    navigator.getUserMedia({ video: true }, function(stream) {
        video.src = stream;
        video.play();
    }, errBack);
} else if(navigator.webkitGetUserMedia) { // WebKit-prefixed
    navigator.webkitGetUserMedia({ video: true }, function(stream){
        video.src = window.webkitURL.createObjectURL(stream);
        video.play();
    }, errBack);
} else if(navigator.mozGetUserMedia) { // Mozilla-prefixed
    navigator.mozGetUserMedia({ video: true }, function(stream){
        video.srcObject = stream;
        video.play();
    }, errBack);
}
</script>
<script type="text/javascript">
google.charts.load("current", {packages:["corechart"]});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
var data = google.visualization.arrayToDataTable([
    ['Opean source between Registered', ''],
    ['Opean source test',<?php echo $opeansrcinst_overalldata;?>],
    ['Registered test',<?php echo $registereinst_overalldata;?>]
    ]);
	var options = {'title':'Your Opean source between Registered Tests',
                   'height':200,
					legend: {position: 'bottom'},
					is3D: false,
					colors: ['#7c3ee0', '#007bff','#862695', '#921521']};
    var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
    chart.draw(data, options);
    }

</script>
<?php 
$sql3=mysqli_query($conn,"SELECT * FROM inst_all_time_data WHERE code='".$_GET['na']."' order by id desc limit 5");
$count_sql3=mysqli_num_rows($sql3);
while($result_inst_all_time_data = mysqli_fetch_assoc($sql3)){
$date[]=$result_inst_all_time_data['date'];
$number[]=$result_inst_all_time_data['std_attend'];}
?>
<script>
var i='<?php echo count($date);?>';
if(i==5){
google.charts.setOnLoadCallback(drawChart2);	
function drawChart2() {
    var data2 = google.visualization.arrayToDataTable([
          ['month', 'Number of student'],
          ['<?php echo $date[4];?>',<?php echo $number[4];?>],
		  ['<?php echo $date[3];?>',<?php echo $number[3];?>],
		  ['<?php echo $date[2];?>',<?php echo $number[2];?>],
		  ['<?php echo $date[1];?>',<?php echo $number[1];?>],
		  ['<?php echo $date[0];?>',<?php echo $number[0];?>]
		  
		  // ['<?php echo $date[4];?>',4],
		  // ['<?php echo $date[3];?>',31],
		  // ['<?php echo $date[2];?>',13],
		  // ['<?php echo $date[1];?>',15],
		  // ['<?php echo $date[0];?>',20]
		  ]);

    var options2={
        title: 'Total Students (<?php echo $std_attendinst_overalldata;?>) in total test (<?php echo $total_inst_overalldata;?>)',
		legend:{position: 'top'},
		chartArea:{width:"90%",height:"70%"},
        hAxis:{title: 'Test Dates',  titleTextStyle: {color: '#7c3ee0'}},
        vAxis:{minValue: 0},
		colors:['#7c3ee0', '#7c3ee0']
        };

    var chart2 = new google.visualization.AreaChart(document.getElementById('chart_div'));
chart2.draw(data2, options2);}}
</script>
<script>
var h_s='<?php echo (5-count($date));?>';
if(h_s==0){document.getElementById('cus_text1').innerHTML='Students attend in last 5 Tests'}
else{document.getElementById('cus_text1').innerHTML='Need <?php echo (5-count($date));?> test more to opean chart'}
// print	  
function createPDF()
    {   var sTable = document.getElementById('ranklistprint').innerHTML;
        var style = "<style>";
        style = style + "table {width: 100%;font: 17px Calibri;}";
        style = style + "table, th, td {border: solid 1px #DDD; border-collapse: collapse;";
        style = style + "padding: 2px 3px;text-align: center;}";
        style = style + "</style>";
        // CREATE A WINDOW OBJECT.
        var win = window.open('','','height=700,width=700');
        win.document.write('<html><head>');
        win.document.write('<title>Crackers Way</title>');
        win.document.write(style);
        win.document.write('</head>');
        win.document.write('<body>');
        win.document.write(sTable);
        win.document.write('</body></html>');
        win.document.close();//CLOSE THE CURRENT WINDOW.
        win.print();//PRINT THE CONTENTS.
var link = sTable;
link.href = url;
link.download = 'file.pdf';
link.dispatchEvent(new MouseEvent('click'));
	}
    </script>
<?php 
if(isset($_POST['insert'])){
$messg = explode("_",$_POST['insert']);
if($messg[1]=='w'){$sql=mysqli_query($conn,"update startquiz set warning='$messg[2]' where id='".$messg[0]."'");}
elseif($messg[1]=='e'){$sql=mysqli_query($conn,"update startquiz set exit_status='true' where id='".$messg[0]."'");}
}
?>
<script>
$(document).ready(function(){
var code = "<?php echo $_GET['na'];?>";
$(document).on('change','#upload_img',function(){
var property = document.getElementById('upload_img').files[0];
var image_name = property.name;
var image_extension = image_name.split('.').pop().toLowerCase();
var size = property.size;		
alert(image_name);
if(size > 3145728){
alert("Image should less then 3 mb your image size is "+Math.floor((property.size/Math.pow(2, 20)))+".2");
}else if(jQuery.inArray(image_extension,['jpg','jpeg','png']) == -1){alert("Invalid image file");}
else{		
const image = document.getElementById('img');
const cropper = new Cropper(image, {
aspectRatio: 18/3,
});

$('.upload').click(function(){	
cropper.getCroppedCanvas().toBlob((blob) => {
const formData = new FormData();
var query_select='img_qus';

var shift=$('#shift').html();
if(shift=='Shift 1'){var paperset='paper1';}else if(shift=='Shift 2'){paperset='paper2';}

  // Pass the image file name as the third parameter if necessary.
  formData.append('img', blob,image_name);
  formData.append('query_select', query_select);
  formData.append('paper_set', paperset);
  formData.append('paper_id', $('#paper_id').val());
  // formData.append('test_code', $(".live_test").html());
  // Use `jQuery.ajax` method for example
  $.ajax('ajax1', {
    method: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: function(response) {
	  if(response=="Done"){window.location.replace("<?php echo $url_main;?>/dashboard-institute?na="+code)};
    },
    error() {
      alert('Upload error');
    },
  });
}/*, 'image/png' */);
});
}
});
// dom
});
</script>
<!-- Modal -->
<div class="modal fade" id="edit_std" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="background:#c9c1d2;">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
<div class="modal-body">
<form action="" method="post">
	  <div class="row" style="margin-bottom:20px;">
    <div class="col-sm-4">
	<span> name</span>
      <input type="text" name="institute_name" class="form-control" value="<?php echo $result_detail_inst['institute_name'];?>" required>
    </div>
    <div class="col-sm-4"><span> login name</span>
      <input type="text" name="inst_login_name" class="form-control" placeholder="login name" required>
    </div>
	<div class="col-sm-4"><span> Director</span>
      <input type="text" name="director_name" class="form-control" value="<?php echo $result_detail_inst['director_name'];?>" required>
    </div>
  </div>
<div class="row" style="margin-bottom:20px;">
    <div class="col-sm-4"><span> Address</span>
      <input type="text" name="inst_address"class="form-control" value="<?php echo $result_detail_inst['address'];?>" required>
    </div>
    <div class="col-sm-4"><span> City</span>
      <input type="text" name="inst_city" class="form-control" value="<?php echo $result_detail_inst['city'];?>" required>
    </div>
	<div class="col-sm-4"><span> Pincode</span>
      <input type="text" name="inst_pincode" class="form-control" value="<?php echo $result_detail_inst['pincode'];?>" required>
    </div>
  </div>
  <div class="row" style="margin-bottom:20px;">
    <div class="col-sm-4"><span> Contact</span>
      <input type="number" name="inst_contact" class="form-control" value="<?php echo $result_detail_inst['contact'];?>" required>
    </div>
	<div class="col-sm-4"><span> Password</span>
      <input type="password" name="inst_pass" class="form-control" placeholder="Password" required>
    </div>
  </div> 
<!--modal body-->
</div>
<div class="modal-footer">
<input type="submit" name="update_inst" class="theam-text-color theam-button btn btn-outline-success" value="Submit">
</form>
      </div>
    </div>
  </div>
</div>

<!-- Modal photo -->
<div class="modal fade" id="modal_photo" tabindex="" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Image</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
<div class="modal-body">
<form action="" method="post" enctype="multipart/form-data">
<input type="file" name="inst_image" id="upload_img" onchange="document.getElementById('img_inst').src = window.URL.createObjectURL(this.files[0])" accept="image/jpeg,image/png">
<img src="" id="img_inst" class="img rounded mx-auto d-block" alt="">
</div>
<div class="modal-footer">
<button type="submit" name="inst_image_upload" class="upload btn btn-success">Upload</button>
</form>
</div>
</div>
</div>
</div>
<?php
if(isset($_POST['update_inst']))
{ 
if(password_verify($_POST['inst_pass'], $result_login_sql1['password'])){
$sql1=mysqli_query($conn, "update `institute_detail` set institute_name='".$_POST['institute_name']."',address='".$_POST['inst_address']."',director_name='".$_POST['director_name']."',city='".$_POST['inst_city']."',pincode='".$_POST['inst_pincode']."',contact='".$_POST['inst_contact']."' 
where code='".$_GET['na']."'");
$sql2=mysqli_query($conn, "update `institute_login` set name='".$_POST['inst_login_name']."' where code='".$_GET['na']."'");
}else{echo "<script>alert('Wrong password')</script>";}
}
if(isset($_POST['inst_image_upload'])){
	$continue=1;
	if($_FILES['inst_image']['name'] != ''){
    $test = explode('.', $_FILES['inst_image']['name']);
    $extension = end($test);
// Valid extension
$valid_ext = array('png','jpeg','jpg');
if(in_array(strtolower($extension),$valid_ext)){$continue=0;}else{$msg="File must be in jpeg,jpg,png";}
if($_FILES['inst_image']['size']<3145728){$continue=0;}else{$msg="File is to large in size";}
	if($continue==0){
    $name = md5(rand(1000,9999)).'inst.'.$extension;
    $location = 'user_image/institute/'.$name;
	$sql = mysqli_query($conn,"UPDATE institute_detail set photo='$name' where code='".$_GET['na']."'");
	if($sql){
    move_uploaded_file($_FILES['inst_image']['tmp_name'], $location);
    echo '<script>alert("Done");window.location.replace("'.$url_main.'/dashboard-institute?na='.$_GET['na'].'")</script>';}}else{echo "<script>alert('$msg');</script>";}
}}
?>

<?php include("mainfile/footer.php");?>
</body>