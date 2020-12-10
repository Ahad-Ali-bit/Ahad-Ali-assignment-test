<?php
include("mainfile/connection.php");
include("mainfile/header.php");
$x_main = explode("/",$_SERVER['REQUEST_URI']);
$url_main="/".$x_main[1];
if($_SERVER['REQUEST_URI'] !='/Onlinetestproject/login'){echo "<script type='text/javascript'>window.location.href = '$url_main';</script>";}

?>
<script>document.getElementById('button').innerHTML='<a href="<?php echo $url_main;?>" class="logout btn btn-outline-danger btn-sm">Back</a>';</script>
<!-- Code by Ahad ALi -->
<html lang="en">
<style>
body{background-image:url("images/4.jpg");background-repeat: no-repeat;background-attachment: fixed;
background-size:cover;}
</style>
<script>$('#header').html('Login');$('#title').html('Crackers way: Login');</script>
<div class="container" style="">
<div class='row'>
<div class='col-sm-2'></div>
<div class='col-sm-8'style='border:1px solid #5f07fd;margin-top:8%;background:#1110108f;padding:25px;'>
<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <a class="nav-link active" id="Student-tab" data-toggle="tab" href="#Student" role="tab" aria-controls="Student" aria-selected="true">Student login</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="Management-tab" data-toggle="tab" href="#Management" role="tab" aria-controls="Management" aria-selected="false">Institute login</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Create account</a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
<div class="tab-pane fade show active" id="Student" role="tabpanel" aria-labelledby="home-tab">
<hr>
<form action="" method="post">
  <div class="form-group">
    <input type="text" name="username_std" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Email / Name">
  </div>
  <div class="form-group">
    <input type="password" name="pass_std" class="form-control" id="exampleInputPassword1" placeholder="password">
  </div>
  <input type="submit" name="login_student" class="theam-text-color theam-button btn btn-outline-info" value="Submit">
</form>
</div>
<!--management tab-->
<div class="tab-pane fade" id="Management" role="tabpanel" aria-labelledby="Management-tab">
<hr>
<form action="" method="post">
<div class="form-group">
    <input type="text" name="email_inst" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Email / Institute name">
  </div>
  <div class="form-group">
    <input type="password" name="pass_inst" class="form-control" id="exampleInputPassword1" placeholder="password">
  </div>
  <input type="submit" name="login_management" class="theam-text-color theam-button btn btn-outline-info" value="Submit">
</form>

</div>
<div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
<hr>
<div class="accordion" id="accordionExample">
  <div class="card">
 <a type="button" class="theam-text-color theam-bg-color btn btn-outline-danger" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="">
  New Institute</a>
    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
      <div class="card-body theam-text-color theam-bg-color">
       
<form action='' method="post">
  <div class="row" style="margin-bottom:20px;">
    <div class="col-sm-4">
	<span>Institute name</span>
      <input type="text" name="institute_name" class="form-control" placeholder="name" required>
    </div>
    <div class="col-sm-4"><span>Institute login name</span>
      <input type="text" name="inst_login_name" class="form-control" placeholder="login name" required>
    </div>
	<div class="col-sm-4"><span>Institute Director</span>
      <input type="text" name="director_name" class="form-control" placeholder="Director name" required>
    </div>
  </div>
<div class="row" style="margin-bottom:20px;">
    <div class="col-sm-4"><span>Institute Address</span>
      <input type="text" name="inst_address"class="form-control" placeholder="Address" required>
    </div>
    <div class="col-sm-4"><span>Institute City</span>
      <input type="text" name="inst_city" class="form-control" placeholder="city" required>
    </div>
	<div class="col-sm-4"><span>Institute Pincode</span>
      <input type="text" name="inst_pincode" class="form-control" placeholder="Pincode" required>
    </div>
  </div>
  <div class="row" style="margin-bottom:20px;">
    <div class="col-sm-4"><span>Institute Contact</span>
      <input type="number" name="inst_contact" class="form-control" placeholder="Contact Number" required>
    </div>
    <div class="col-sm-4"><span>Institute Email</span>
      <input type="email" name="inst_email" class="form-control" placeholder="Email" required>
    </div>
	<div class="col-sm-4"><span>Institute Password</span>
      <input type="password" name="inst_pass" class="form-control" placeholder="Password" required>
    </div>
  </div>
  <input type="submit" name="insert_inst" class="theam-text-color theam-button btn btn-outline-success" value="Submit">
</form>   
      </div>
    </div>
  </div>


  <div class="card">
        <a class="theam-text-color theam-bg-color btn btn-outline-info" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="">
          New Student
        </a>
<form action="" method="post">
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
      <div class="card-body theam-text-color theam-bg-color">
       
<div class="row" style="margin-bottom:20px;">
    <div class="col-sm-4">
	<span>Student Name</span>
      <input type="text" name="std_name" class="form-control" placeholder="name" required>
    </div>
    <div class="col-sm-4"><span>Student login name</span>
      <input type="text" name="std_login_name" class="form-control" placeholder="login name" required>
    </div>
	<div class="col-sm-4"><span>Father Name</span>
      <input type="text" name="std_fname" class="form-control" placeholder="Father name" required>
    </div>
  </div>
<div class="row" style="margin-bottom:20px;">
    <div class="col-sm-4">
	<span>Currently doing</span>
      <input type="text" name="std_current" class="form-control" placeholder="10,10+2,B.tech,preparing for competition" list="curt_doing" required>
	  <datalist id="curt_doing">
	  <option>High school</option>
	  <option>Intermediate</option>
	  <option>Intermediate+preparing for IIT</option>
	  <option>Intermediate+preparing for Government job</option>
	  <option>preparing for IIT</option>
	  </datalist>
    </div>
    <div class="col-sm-4"><span>Institute/Coaching</span>
      <input type="text" name="std_university_name" class="form-control" placeholder="Institute/Coaching name" required>
    </div>
	<div class="col-sm-4"><span>Year of passing Intermediate</span>
      <input type="date" name="std_year_passing" class="form-control" placeholder="passing Intermediate" required>
    </div>
  </div>
<div class="row" style="margin-bottom:20px;">
    <div class="col-sm-4"><span>Student Address</span>
      <input type="text" name="std_paddress"class="form-control" placeholder="Address" required>
    </div>
    <div class="col-sm-4"><span>Student City</span>
      <input type="text" name="student_city" class="form-control" placeholder="city" required>
    </div>
	<div class="col-sm-4"><span>Student Pincode</span>
      <input type="text" name="std_pincode" class="form-control" placeholder="Pincode">
    </div>
  </div>
  <div class="row" style="margin-bottom:20px;">
    <div class="col-sm-4"><span>Student Contact</span>
      <input type="number" name="std_contact" class="form-control" placeholder="Contact Number" required>
    </div>
    <div class="col-sm-4"><span>Student Email</span>
      <input type="email" name="std_email" class="form-control" placeholder="Email" required>
    </div>
	<div class="col-sm-4"><span>Student Password</span>
      <input type="password" name="std_pass" class="form-control" placeholder="Password" required>
    </div>
  </div>
  <input type="submit" name="insert_std" class="theam-text-color theam-button btn btn-outline-success" value="Submit">
	 </div>
    </div>
  </div> 
</form>
</div>
<!--tab-->
</div>
</div>


</div>
<div class='col-sm-2'></div>
<!--container,row-->
<?php 
// echo '<pre>';
// print_r ($_SERVER);
?>
</div></div>
</body>
<?php
// login queries
if(isset($_POST['login_student']))
{
if(!empty($_POST['username_std'])){
$username=htmlspecialchars($_POST['username_std']); 
$password=$_POST['pass_std'];
$sql=mysqli_query($conn, "SELECT * FROM std_login WHERE email='".$username."' or contact = '".$username."'");
$count=mysqli_num_rows($sql);
if($count==1){
$result = mysqli_fetch_assoc($sql);
if(password_verify($password, $result['password'])){
$sql=mysqli_query($conn,"UPDATE std_login SET ip_address='".$_SERVER['REMOTE_ADDR']."',login_date='".(date("d-m-Y",time()))."',login_status='Active' where id='".$result['id']."'");
echo "<script type='text/javascript'>window.location='$url_main/dashboard-student?na=$result[code]'</script>'";
}
else{echo "<script>alert('Wrong password or Number');</script>";}
}else if($count==0){echo "<script>alert('No detail found $username');</script>";}else{echo "<script>alert('Please use your alternate login detail insead of $username, we found more then 1 entry with same detail $username');</script>";}
}}
if(isset($_POST['login_management']))
{if(!empty($_POST['email_inst'])){
$email_inst=htmlspecialchars($_POST['email_inst']); 
$pass_inst=$_POST['pass_inst'];
$sql=mysqli_query($conn, "SELECT * FROM institute_login WHERE email='".$email_inst."' or contact = '".$email_inst."'");
$count=mysqli_num_rows($sql);
if($count==1){
$result = mysqli_fetch_assoc($sql);
if(password_verify($pass_inst, $result['password'])){
$sql=mysqli_query($conn,"UPDATE institute_login SET ip_address='".$_SERVER['REMOTE_ADDR']."',login_date='".(date("d-m-Y",time()))."',login_status='Active' where id='".$result['id']."'");
echo "<script type='text/javascript'>window.location='$url_main/dashboard-institute?na=$result[code]'</script>'";
}
else{echo "<script>alert('Wrong password or name');</script>";}
}else if($count==0){echo "<script>alert('No detail found $email_inst');</script>";}else{echo "<script>alert('Please use your alternate login detail insead of $email_inst, we found more then 1 entry with detail name $email_inst');</script>";}
}}
?>
<?php
// insert queryes
if(isset($_POST['insert_inst']))
{
$code=md5(rand());
$password = password_hash($_POST['inst_pass'], PASSWORD_BCRYPT);
$sql=mysqli_query($conn, "INSERT INTO `institute_detail` (code,institute_name,address,director_name,city,pincode,contact,email) 
VALUES ('$code','".$_POST['institute_name']."','".$_POST['inst_address']."','".$_POST['director_name']."','".$_POST['inst_city']."','".$_POST['inst_pincode']."','".$_POST['inst_contact']."','".$_POST['inst_email']."')");	
$sql=mysqli_query($conn, "INSERT INTO `institute_login` (code,name,password,email,contact) 
VALUES ('$code','".$_POST['inst_login_name']."','$password','".$_POST['inst_email']."','".$_POST['inst_contact']."')");
if($sql){echo "<script>alert('".$_POST['inst_login_name']." successfully submit');window.location.replace('$url_main');</script>";}

$sql2=mysqli_query($conn, "INSERT INTO `inst_overalldata` (code,total,std_attend,opean_source,registered) 
VALUES ('$code','0','0','0','0')");

if($sql2){echo "<script>alert('".$_POST['std_login_name']." successfully submit');window.location.replace('$url_main/login');</script>";}	
}
if(isset($_POST['insert_std']))
{
$code=md5(rand());
$password = password_hash($_POST['std_pass'], PASSWORD_BCRYPT);
$sql=mysqli_query($conn, "INSERT INTO `std_detail` (code,name,fname,current_doing,university_name,year_passing,paddress,pincode,mobile,email,city) 
VALUES ('$code','".$_POST['std_name']."','".$_POST['std_fname']."','".$_POST['std_current']."','".$_POST['std_university_name']."','".$_POST['std_year_passing']."','".$_POST['std_paddress']."','".$_POST['std_pincode']."','".$_POST['std_contact']."','".$_POST['std_email']."','".$_POST['student_city']."')");	

$sql=mysqli_query($conn, "INSERT INTO `std_login` (code,name,password,email,contact) 
VALUES ('$code','".$_POST['std_login_name']."','$password','".$_POST['std_email']."','".$_POST['std_contact']."')");

$sql2=mysqli_query($conn, "INSERT INTO `std_overalldata` (code,total_test_number,below_35,n35_60,n60_75,over_75) 
VALUES ('$code','t=0&n=0','0','0','0','0')");

if($sql2){echo "<script>alert('".$_POST['std_login_name']." successfully submit');window.location.replace('$url_main/login');</script>";}			
}
?>
</html>