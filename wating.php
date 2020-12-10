<?php
include("mainfile/connection.php");
include("mainfile/header.php");

// url
$x_main = explode("/",$_SERVER['REQUEST_URI']);
$url_main="/".$x_main[1];

if($_SERVER['REQUEST_URI'] !=$url_main.'/'.$_GET['testcode'].'/'.$_GET['rolno'].'/'.$_GET['na']){
echo "<script type='text/javascript'>window.location.href = '$url_main/dashboard-student?na='".$_GET['na'].";</script>";
}

$return="false";
$return2="false";
$reason="";

$e0=hexdec(substr($_GET['testcode'],0,-4));
$e1_test = decoct($e0);
$e2_test = dechex($e1_test);



if($_GET['testcode']=="" or $_GET['rolno']==""){$return="true";$reason="Url Error";}

$sql1=mysqli_query($conn, "SELECT * FROM timing where test_code='".$e2_test."'");
$count_sql1=mysqli_num_rows($sql1);
if($count_sql1==0){$return="true";$reason="Wrong Test Key";}

$sql2=mysqli_query($conn, "SELECT * FROM std_login where code='".$_GET['na']."'");
$count_sql2=mysqli_num_rows($sql2);
if($count_sql2==0){$return="true";$reason="No student found";}

if($return == "true"){echo "<script>alert('$reason');window.location.replace('$url_main/dashboard-student?na=".$_GET['na']."')</script>";}
else{
$result_sql2 = mysqli_fetch_assoc($sql2);
if(octdec(hexdec($result_sql2['rollno']))!=hexdec(substr($_GET['rolno'],0,-4))){$return2="true";$reason="Wrong Rollno";}



$result_sql1 = mysqli_fetch_assoc($sql1);
if($result_sql1["test_catagery"]=="registered"){
$e0=hexdec(substr($_GET['rolno'],0,-4));
$e1 = decoct($e0);
$e2 = dechex($e1);

$sql3=mysqli_query($conn, "SELECT * FROM registered_table where test_code='".$e2_test."' and rollno='$e2'");
$count_sql3=mysqli_num_rows($sql3);
if($count_sql3!=1){$return2="true";$reason="You are not registered";}	
	
}
}

if($return2=="true" or $return=="true"){echo "<script>alert('$reason');window.location.replace('$url_main/dashboard-student?na=".$_GET['na']."')</script>";}
else{

$code_inst=$result_sql1["code_inst"];
$paper_set=$result_sql1["shift"];	
$time = $result_sql1["start_at"];
$timeend=$result_sql1["end_at"];

$rolno = $result_sql2['rollno'].rand(1000,9999);

$sql4=mysqli_query($conn, "SELECT * FROM ".$paper_set." where test_code='".$e2_test."'");
$count_sql4=mysqli_num_rows($sql4);
}

echo "<script> document.getElementById('header').innerHTML='Please wait';
document.getElementById('title').innerHTML='Crackers way: wating for test';</script>";
?>


<body>
<div class="container">
<div class="row">
<div class="col-sm-10" style="border:1px solid blue;">

<div class="progress">
<div class="progress-bar progress-bar-striped bg-danger" role="progressbar" id="cus_width" style="width:200px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">Please wait</div>
</div>
<div class="table-responsive">
<table class="table">
  <thead>
    <tr>
      <th scope="col" colspan="5"><p id='counter1'></p>

	  </div></th>
      
    </tr>
  </thead>
  <tbody>
    <tr>
      <td scope="row">Total Students</td>
      <td>Total Questions</td>
      <td>Start on</td>
      <td>End on</td>
	  <td>Test key</td>
    </tr>
    <tr>
      <td id="total_student"></td>
      <td><?php echo $count_sql4;?></td>
      <td><?php echo $time;?></td>
      <td><?php echo $timeend;?></td>
	  <td id="test_key"><?php echo $e0;?></td>
    </tr>
    <tr>
    <td scope="row" colspan="4">Mentor Instruction: <?php echo htmlspecialchars($result_sql1["instruction"]);?></td>
    </tr>
	<tr>
    <th scope="row" colspan="4">Auto redirect to the test</th>
    </tr>
  </tbody>
</table>
</div>
<!--col sm 10-->
</div>
<?php echo'
<script>
var countDownDate1 = new Date("'.$time.'").getTime();
var x1 = setInterval(function() {
  var now1 = new Date().getTime();
  var distance1 = countDownDate1 - now1;
  var days = Math.floor(distance1 / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance1 % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance1 % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance1 % (1000 * 60)) / 1000);
  
  var count_time=(distance1)/10000;
  if(count_time<359){document.getElementById("cus_width").className= "progress-bar progress-bar-striped bg-danger";}
  if(count_time<720 && count_time>=359){document.getElementById("cus_width").className= "progress-bar progress-bar-striped bg-warning";}
  if(count_time>720){document.getElementById("cus_width").className= "progress-bar progress-bar-striped bg-success";}
  document.getElementById("cus_width").style.width=count_time+"px";
  document.getElementById("counter1").innerHTML ="Live in "+ days + "d " + hours + "h "
  + minutes + "m " + seconds + "s";
  if (distance1 < 0) {
    clearInterval(x1);
    document.getElementById("counter1").innerHTML = "Test is live";
	alert("'.htmlspecialchars($result_sql1["instruction"]).'");
	window.location.replace("'.$url_main.'/test?tc='.$e2_test.'&rn='.$_GET['rolno'].'&na='.$_GET['na'].'&code_inst='.$code_inst.'")
	}}, 1000);</script>';?>


<div class="col-sm-2" style="border:1px solid red;">

</div>
<!--row,container-->
</div></div>

<script>
$(document).ready(function(){

$.ajax({
    url: 'detailcommon.php',
    type: 'post',
	dataType: 'JSON',
    data: {query_select:"start_quiz",test_code:$('#test_key').html()},
    success: function(response){
	$("#total_student").html(response[2]);
}
});
// dom
});
</script>
</body>