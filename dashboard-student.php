<?php
include("mainfile/connection.php");
include("mainfile/header.php");
// error_reporting(0);
// url
$x_main = explode("/",$_SERVER['REQUEST_URI']);
$url_main="/".$x_main[1];

if($_SERVER['REQUEST_URI'] !=$url_main.'/dashboard-student?na='.$_GET['na']){
echo "<script type='text/javascript'>window.location.href = '$url_main/login';</script>";
}

session_start();
$redirection="false";
if($_GET['na']==""){$redirection="true";}
$sql=mysqli_query($conn,"SELECT * FROM std_login WHERE code='".$_GET['na']."'");
$count=mysqli_num_rows($sql);
if($count<=0){$redirection="true";}
else{
$result_login = mysqli_fetch_assoc($sql);
if($result_login['ip_address'] != $_SERVER['REMOTE_ADDR'] or $result_login['login_date']!=(date("d-m-Y",time())) or $result_login['login_status']!='Active')
{$sql=mysqli_query($conn,"UPDATE std_login SET login_status='offline' where id='".$result_login['id']."' and code='".$_GET['na']."'");
$redirection="true";}}

if($redirection=="true"){
$sql=mysqli_query($conn,"UPDATE std_login SET login_status='offline' where id='".$result_login['id']."' and code='".$_GET['na']."'");
echo "<script>window.location.replace('$url_main/login');</script>";
}
else{
$name=$result_login['name'];

$sql2=mysqli_query($conn,"SELECT * FROM std_detail WHERE code='".$_GET['na']."'");
$result_detail_std = mysqli_fetch_assoc($sql2);

$sql3=mysqli_query($conn,"SELECT * FROM std_overalldata WHERE code='".$_GET['na']."'");
$result_std_overalldata = mysqli_fetch_assoc($sql3);
parse_str($result_std_overalldata['total_test_number']);
$below_35=$result_std_overalldata['below_35'];
$n35_60=$result_std_overalldata['n35_60'];
$n60_75=$result_std_overalldata['n60_75'];
$over_75=$result_std_overalldata['over_75'];
}
echo "<script> 
document.getElementById('header').innerHTML='Welcome $name (student panel)';
document.getElementById('title').innerHTML='Crackers way: $name (student panel)';
</script>";
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
<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <a class="nav-link active" id="Dashboard-tab" data-toggle="tab" href="#Dashboard" role="tab" aria-controls="Dashboard" aria-selected="true">Dashboard</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="logintest-tab" data-toggle="tab" href="#logintest" role="tab" aria-controls="logintest" aria-selected="false">Login in Test</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Contact</a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
<div class="tab-pane fade show active" id="Dashboard" role="tabpanel" aria-labelledby="Dashboard-tab">
  
<h6>Your status <?php echo $name;?></h6>
<hr>
<div class="row">
<div class="col-sm-7">
<div class="card mb-3" style="max-width:540px;">
  <div class="row no-gutters">
    <div class="col-4" data-toggle='modal' data-target='#modal_photo'>
      <img src="user_image/candidate/<?php echo $result_detail_std['photo'];?>" class="img-fluid img-thumbnail card-img" alt="click to upload" style="max-height: 240px;">
	  <span></span>
    </div>
    <div class="col-8">
      <div class="card-body">
        <p class="card-text">Father Name: <?php echo htmlspecialchars($result_detail_std['fname']);?></p> 
		<p class="card-text">Email: <?php echo htmlspecialchars($result_detail_std['email']);?></p>
		<p class="card-text">Mobile: <?php echo htmlspecialchars($result_detail_std['mobile']);?></p>
        <p class="card-text"><small class="text-muted"><?php echo date('d-m-Y, H:i')." (Time Zone >".$timezone.")";?></small></p>
		<p data-toggle="modal" data-target="#edit_std" class="card-text"><small class="text-muted">Edit</small></p>
      </div>
    </div>
  </div>
</div>
</div>
<div class="col-sm-5">
<div class="card border-success mb-3" style="max-width: 18rem;">
  <div class="card-header">Total test attend</div>
  <div class="card-body">
    <p class="card-title">Total Test: <?php echo $t;?></p>
	<p class="card-title">Total Number: <?php echo $n;?></p>
	<p class="card-title">Cracker way Number: <?php  if($n==0 and $t==0){$t=1;}$c_r = number_format(($n/$t),2);
	$sql=mysqli_query($conn,"UPDATE std_overalldata SET crackers_rank='$c_r' where code='".$_GET['na']."'");if($sql){echo $c_r;}?></p>
	<p>Your one time rollno: <span id="rollno"></span></p>
  </div>
</div>
</div>
</div>
<div class="row">
<div class="col-sm-8">
    <div class="card">
      <div class="card-body">
        <div id="piechart_3d" style="width:100%; height:200px;"></div>
      </div>
    </div>
  </div>
<div class="col-sm-4">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Message Box</h5>
        <p class="card-text">Coming Soon...</p>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title"id="cus_text1">Performance last 5 Tests </h5>
        <div id="chart_div" style="width: 100%; height:250px;"></div>
      </div>
    </div>
  </div>
</div>
<div class="row">
</div>  
<!--Dahsboard--> 
</div>
<div class="tab-pane fade" id="logintest" role="tabpanel" aria-labelledby="logintest-tab">
<form action='' method="post">
  <div class="row" style="margin-bottom:20px;">
  <span>login in the test Enter Detail</span>
  <hr>
    <div class="col-sm-12">
      <input type="text" name="entrytest_email" class="form-control" placeholder="Institute Email/Name" list="inst_name" required>
	  <datalist id="inst_name">
	  <?php 
	   $sql=mysqli_query($conn, "SELECT * FROM institute_login");
	   while($result = mysqli_fetch_assoc($sql)){echo "<option value='".$result['email']."'></option><option value='".$result['name']."'></option>";}
	  ?>
	  </datalist>
    </div>
    <div class="col-sm-12">
      <input type="text" name="entrytest_key" class="form-control" placeholder="Entry Key" required>
    </div>
	<div class="col-sm-12">
      <input type="text" name="entrytest_rolno" class="form-control" placeholder="Your Rollno" required>
    </div>
  </div>
  <input type="submit" name="enter_test" class="btn theam-text-color theam-bg-color" value="Enter">
</form> 
<!--logintest-->  
</div>
<div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">Coming Soon...
<!--contact-->
</div>
</div>

<!--col-8-->
</div>
<div class="col-sm-2" style="border:1px solid green;">
<span>This week live Test</span>
<hr>
<div class="progress">
  <div class="theam-bg-color progress-bar"id="total_test" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width:100%">Test</div>
</div>
<br>
<!--alert-->
<div style="max-height:300px;overflow:;">
<marquee direction="up" height="300px" onMouseOver="this.stop()" onMouseOut="this.start()" scrollamount="2">
<div class="alert alert-primary" role="alert">
  Add
</div>
<div id="week_test">
<?php 
$sno=0;
$sql=mysqli_query($conn, "SELECT * FROM timing");
while($result = mysqli_fetch_assoc($sql))
{
// Start date week
$date_week = date('Y-m-d',strtotime('last sunday'));
// End date week
$end_date_week = date('Y-m-d',strtotime('next monday'));
while (strtotime($date_week) <= strtotime($end_date_week)){
if(date('Y-m-d',strtotime($result['start_at']))==$date_week){
$sno++;
echo "<script>document.getElementById('total_test').innerHTML=$sno+' Tests';
document.getElementById('total_test').style.width=($sno*10)+'%';</script>";
$sql2=mysqli_query($conn,"SELECT * FROM institute_login WHERE code='".$result["code_inst"]."'");
$result_detail = mysqli_fetch_assoc($sql2);
	
echo '<div class="alert alert-primary" role="alert">'.$result_detail["name"].' '.$result['status'].'<hr>'.$result['start_at'].' to '.$result['end_at'].'
<hr>
<button type="button" id="'.$result_detail["code"].'" class="btn view_inst theam-text-color theam-bg-color btn-sm" data-toggle="modal" data-target="#view_inst">View detail</button></div>';
}
$date_week = date ("Y-m-d", strtotime("+1 day", strtotime($date_week)));
}}
?>
</div>
</marquee>
</div>
<?php 
// echo $_SERVER['REQUEST_URI']."</br>";
// $x = explode("/",$_SERVER['REQUEST_URI']);
// echo $x[1];
?>
<!--col-2-->
</div>

<!--container,row-->
</div>
</div>
<p id="code_val" style="display:none;"><?php echo $_GET['na'];?></p>

<?php
$sql2=mysqli_query($conn,"select * from std_all_time_data where test='".$_GET['na']."' order by id desc limit 5");
$c=mysqli_num_rows($sql2);
while($result_detail = mysqli_fetch_assoc($sql2)){
$date[]=$result_detail['date'];
$number[]=$result_detail['number'];}
?>
<script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Test data', ''],
          ['Number > 75%',<?php echo $over_75; ?>],
          ['60%< Number < 75%',<?php echo $n60_75; ?>],
          ['35% < Number < 60%',<?php echo $n35_60; ?>],
          ['Number < 35%', <?php echo $below_35; ?>]
        ]);

        var options = {'title':'Your performance in <?php echo $t;?> tests',
                     'height':200,
					 legend: {position: 'bottom'},
					 is3D: false,
					 colors: ['#7c3ee0', '#007bff','#862695', '#921521']};
        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
      }
</script><script>
var i='<?php echo count($date);?>';
if(i==5){
google.charts.setOnLoadCallback(drawChart2)
function drawChart2() {

        var data = google.visualization.arrayToDataTable([
          ['month','Number'],
		  ['<?php echo $date[4];?>',<?php echo $number[4];?>],
		  ['<?php echo $date[3];?>',<?php echo $number[3];?>],
		  ['<?php echo $date[2];?>',<?php echo $number[2];?>],
		  ['<?php echo $date[1];?>',<?php echo $number[1];?>],
		  ['<?php echo $date[0];?>',<?php echo $number[0];?>]
        ]);

        var options2 = {
          title: 'Average in last 5 test: <?php echo ($number[4]+$number[3]+$number[2]+$number[1]+$number[1]/count($date))?>',
		  legend: {position: 'top'},
          hAxis: {title: 'Date of tests',  titleTextStyle: {color:'red'}},
          vAxis: {minValue: 0},
		  chartArea:{width:"90%",height:"70%"},
		  colors: ['#7c3ee0', '#007bff']
        };

        var chart2 = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart2.draw(data, options2);
      }
}
	
	// $.ajax({
    // url: 'ajax1.php',
    // type: 'post',
    // data: {query_select:"rollno_insert",code:$('#code_val').html()},
    // success: function(response){
	// if(response==''){location.reload();}
	// if(response=='refresh_comd'){location.reload();}else{
	// $('#rollno').html(response);}
	
	// }})

</script>
<script>
var h_s='<?php echo (5-count($date));?>';
if(h_s==0){document.getElementById('cus_text1').innerHTML='Performance last 5 tests'}
else{document.getElementById('cus_text1').innerHTML='Need <?php echo (5-count($date));?> test more to opean chart'}
</script>
<script>
$(document).ready(function(){
code="<?php echo $_GET['na'];?>";	
	$.ajax({
    url: 'ajax1.php',
    type: 'post',
    data: {query_select:"rollno_insert",code:$('#code_val').html()},
    success: function(response){
	if(response==''){location.reload();}
	if(response=='refresh_comd'){location.reload();}else{
	$('#rollno').html(response);}
	}});
	
$('.view_inst').click(function(){
// alert(this.id);	
$.ajax({
    url: 'detailcommon',
    type: 'post',
	dataType: 'JSON',
    data: {query_select:"inst_data",code:this.id},
    success: function(response){
		$('#director').html(response[1][2]);
		$('#name').html(response[1][0]);
		$('#email').html(response[1][5]);
		$('#contact').html("("+response[1][4]+")");
		$('#address').html(response[1][1]);
		$('#city').html(","+response[1][3]);
		}
});
});
$('.logout').click(function(){
$.ajax({
    url: 'detailcommon',
    type: 'post',
    data: {query_select:"logout",code:code,table:"std_login"},
    success: function(response){if(response=="Done"){window.location.replace("<?php echo $url_main;?>/dashboard-student?na="+code)};}
});
// alert("Done");
});
// Dom
});
</script>
<?php
if(isset($_POST['enter_test']))
{
// echo "<script>window.location.replace('$url_main/wating?testcode=".$_POST['entrytest_key']."&rolno=".dechex($_POST['entrytest_rolno']).rand(1000,9999)."&na=".$_GET['na']."')</script>";
echo "<script>window.location.replace('$url_main/".dechex($_POST['entrytest_key']).rand(1000,9999)."/".dechex($_POST['entrytest_rolno']).rand(1000,9999)."/".$_GET['na']."')</script>";
}?>

<!-- Modal view inst -->
<div class="modal fade" id="view_inst" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <p>Director Name: <span id="director"></span></p>
	   <p>Name: <span id="name"></span></p>
	   <p>Contact Detail: <span id="email"></span> <span id="contact"></span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal edit -->
<div class="modal fade" id="edit_std" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="background:#c9c1d2;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">	   
<form action="" method="post" enctype="multipart/form-data">   
<div class="row" style="margin-bottom:20px;">
    <div class="col-sm-4">
	<small>Name</small>
      <input type="text" name="std_name" class="form-control" value="<?php echo $result_detail_std['name'];?>" required>
    </div>
    <div class="col-sm-4"><small>login name</small>
      <input type="text" name="std_login_name" class="form-control" placeholder="login name" required>
    </div>
	<div class="col-sm-4"><small>Father Name</small>
      <input type="text" name="std_fname" class="form-control" value="<?php echo $result_detail_std['fname'];?>" required>
    </div>
  </div>
<div class="row" style="margin-bottom:20px;">
    <div class="col-sm-4">
	<small>Currently doing</small>
      <input type="text" name="std_current" class="form-control" value="<?php echo $result_detail_std['current_doing'];?>" list="curt_doing" required>
	  <datalist id="curt_doing">
	  <option>High school</option>
	  <option>Intermediate</option>
	  <option>Intermediate+preparing for IIT</option>
	  <option>Intermediate+preparing for Government job</option>
	  <option>preparing for IIT</option>
	  </datalist>
    </div>
    <div class="col-sm-4"><small>Institute/Coaching</small>
      <input type="text" name="std_university_name" class="form-control" value="<?php echo $result_detail_std['university_name'];?>" required>
    </div>
	<div class="col-sm-4"><small>Year of passing Intermediate</small>
      <input type="date" name="std_year_passing" class="form-control" value="<?php echo $result_detail_std['year_passing'];?>" required>
    </div>
  </div>
<div class="row" style="margin-bottom:20px;">
    <div class="col-sm-4"><small>Address</small>
      <input type="text" name="std_paddress"class="form-control" value="<?php echo $result_detail_std['paddress'];?>" required>
    </div>
    <div class="col-sm-4"><small>City</small>
      <input type="text" name="student_city" class="form-control" value="<?php echo $result_detail_std['city'];?>" required>
    </div>
	<div class="col-sm-4"><small>Pincode</small>
      <input type="text" name="std_pincode" class="form-control"  value="<?php echo $result_detail_std['pincode'];?>">
    </div>
  </div>
  <div class="row" style="margin-bottom:20px;">
    <div class="col-sm-4"><small>Contact</small>
      <input type="number" name="std_contact" class="form-control"  value="<?php echo $result_detail_std['mobile'];?>" required>
    </div>
    <div class="col-sm-4"><small>Email</small>
      <input type="email" name="std_email" class="form-control"  value="<?php echo $result_detail_std['email'];?>" readonly>
    </div>
	<div class="col-sm-4"><small>Password</small>
      <input type="password" name="std_pass" class="form-control" placeholder="Old Password" required>
    </div>
  </div>
<input type="hidden" name="code_std" class="form-control" value="<?php echo $result_detail_std['code'];?>" readonly>
</div>

<div class="modal-footer">
<input type="submit" name="update_std" class="theam-text-color theam-button btn btn-outline-success" value="Submit">
</form>
</div></div>
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
<input type="file" id="upload_img" onchange="document.getElementById('img').src = window.URL.createObjectURL(this.files[0])" accept="image/jpeg,image/png">
<img src="" id="img" class="img rounded mx-auto d-block" alt="">
      </div>
      <div class="modal-footer">
        <button class="upload btn btn-success">Upload</button>
      </div>
    </div>
  </div>
</div>
<?php 
if(isset($_POST['update_std']))
{
if(password_verify($_POST['std_pass'], $result_login['password'])){	
$sql1=mysqli_query($conn, "update `std_detail` set name='".$_POST['std_name']."',fname='".$_POST['std_fname']."',current_doing='".$_POST['std_current']."',university_name='".$_POST['std_university_name']."',year_passing='".$_POST['std_year_passing']."',paddress='".$_POST['std_paddress']."',pincode='".$_POST['std_pincode']."',mobile='".$_POST['std_contact']."',city='".$_POST['student_city']."' 
where code='".$_POST['code_std']."'");
$sql2=mysqli_query($conn, "update `std_login` set name='".$_POST['std_login_name']."' where code='".$_POST['code_std']."'");
if($sql1){echo "<script>window.location.replace('$url_main/dashboard-student?na=".$_POST['code_std']."');</script>";}
}else{echo "<script>alert('Wrong Password');</script>";}
}
?>
<script>
$(document).ready(function(){	
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
  aspectRatio: 8/13,
});

$('.upload').click(function(){	
cropper.getCroppedCanvas().toBlob((blob) => {
const formData = new FormData();
var query_select='img_user';
var table_name='std_detail';
var code = "<?php echo $_GET['na'];?>";

  // Pass the image file name as the third parameter if necessary.
  formData.append('img', blob,image_name);
  formData.append('query_select', query_select);
  formData.append('table_name', table_name);
  formData.append('code', code);
  // Use `jQuery.ajax` method for example
  $.ajax('ajax1.php', {
    method: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: function(response) {
	  if(response=="Done"){window.location.replace("<?php echo $url_main;?>/dashboard-student?na="+code)};
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
<?php include("mainfile/footer.php");?>
</body>