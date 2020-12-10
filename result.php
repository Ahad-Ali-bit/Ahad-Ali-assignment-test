<!-- Code by Ahad ALi -->
<?php
error_reporting(0);
include("mainfile/connection.php");
// url
$x_main = explode("/",$_SERVER['REQUEST_URI']);
$url_main="/".$x_main[1];

if($_SERVER['REQUEST_URI'] !=$url_main.'result?&na='.$_GET['na']){
echo "<script type='text/javascript'>window.location.href = '$url_main/dashboard-student?na='".$_GET['na'].";</script>";
}

$_GET['na'];
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <title>result</title>
  </head>
  <body>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

    <!-- Option 2: jQuery, Popper.js, and Bootstrap JS
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    -->
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	
<div class="container">
<div class="row">

<div class="col-md-2">
</div>

<div class="col-md-8">
<!--alert-->

<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <a class="nav-link active" id="Result-tab" data-toggle="tab" href="#Result" role="tab" aria-controls="Result" aria-selected="true">Result</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="Rank-tab" data-toggle="tab" href="#Rank" role="tab" aria-controls="Rank" aria-selected="false">Rank</a>
  </li>
</ul>
<div class="alert alert-warning alert-dismissible fade show" id="alert_css" role="alert" style="display:none;">
<span id="alert"></span>
</div>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="Result" role="tabpanel" aria-labelledby="Result-tab">
  <?php 
    $data = mysqli_query($conn,"SELECT * FROM std_detail WHERE code='".$_GET['na']."'");
    $total = mysqli_num_rows($data);
	if($total !=0)
	$result = mysqli_fetch_assoc($data);
     $name=htmlspecialchars($result['name']); 
	 $fname=htmlspecialchars($result['fname']);
	 $email=htmlspecialchars($result['email']);
	 $image="<img src='user_image/candidate/".$result['photo']."' style='width: 120px;height: 120px;float:;'>";
   ?>
 <hr>
<div id="print_page">
<table class="table table-border">
<tr><th>Name</th><td><?php echo $name; ?></td>
<td rowspan="3" colspan='' style="width: 168px;"><?php echo $image; ?></td></tr>
<tr><th>Father</th><td><?php echo $fname; ?></td></tr>
<tr><th>Email</th><td><?php echo $email; ?></td></tr>
</table>
 <table class="table table-bordered">
  <thead>
<tr>
     <th>Correct</th>
     <th>Wrong</th> 
	 <th>Total Attemp</th>
</tr>
</thead>
<tbody>
<?php
$data = mysqli_query($conn,"SELECT * FROM result WHERE  code='".$_GET['na']."'");
$total = mysqli_num_rows($data);
if($total !=0){
$result = mysqli_fetch_assoc($data);
$test_code=$result['test_code'];
$correct=$result['correct'];
$wrong=$result['wrong'];
$attempt=$wrong+$correct;
$total=$result['total'];
if($attempt>$result['total']){
echo"<script type='text/javascript'>
document.getElementById('alert_css').innerHTML='we found total attemp ($attempt) is more than total no. of questions ($total).so we cancle your exam';
document.getElementById('alert_css').style.display='block';
document.getElementById('piechart').style.display='none';</script>";
$run1 = mysqli_query($conn,"UPDATE login SET percent='0' WHERE code='".$_GET['na']."'");
$run2 = mysqli_query($conn,"UPDATE result SET percent='0' WHERE code='".$_GET['na']."'");
}else{


$cut=$wrong/4;
$final_marks=$correct-$cut;
$percent=($final_marks*100)/$total;

echo "<tr><td>$correct</td><td>$wrong</td><td>$attempt</td></tr>
<tr><th>Total out of</th><th>Total cut</th><th>Final Marks</th></tr>
<tr>
<td>$total</td><td>$cut</td><td>$final_marks</td></tr>
<tr><th>Percentage</th><th>Rank</th><th>Date</th></tr>
<tr><td>".number_format($percent,2)."%</td><td id='my_rank'></td><td>".date('d-m-Y')."</td>
</tr>
";
$run1 = mysqli_query($conn,"UPDATE login SET percent='$percent' WHERE code='".$_GET['na']."'");
$run2 = mysqli_query($conn,"UPDATE result SET percent='$percent' WHERE code='".$_GET['na']."'");}
}
?>
</tbody>
</table>
<table class="table table-bordered">
<tr>
<td rowspan=''><div id='piechart' style='width:200px; height:200px;'></div></td>
</tr>
</table>
</div>
<table class="table table-bordered">
<tr>
<td rowspan=''><a type="button" class="btn btn-danger" href="<?php echo $url_main;?>/login">Logout</a><a type="button" class="btn btn-success" onclick="createPDF()">Print</a></td>
</tr>
</table>

<script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
function drawChart() {
var data = google.visualization.arrayToDataTable([
          ['Task', ''],
          ['Correct',<?php echo $correct;?>],
          ['Wrong',<?php echo $wrong;?>],
        ]);
        var options = {'title':'Your performance attemp '+<?php echo $attempt ?>+" out of "+<?php echo $total ?>,
                     'width':300,
                     'height':200,
					 colors: ['#7c3ee0', '#007bff']};
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);}
    </script>
<!--result tab-->
  </div>
<div class="tab-pane fade" id="Rank" role="tabpanel" aria-labelledby="Rank-tab"><p></p>
<table class="table table-bordered table-striped">
<thead>
<tr><td>Rank</td><td>Name</td><td>Percent</td></tr>
</thead>  
<tbody id="data_rank">

</tbody> 
</table> 
<!--rank list-->
</div>
<!-- col-8-->
</div>
<div class="col-md-2">
</div>
<!--row-->
</div>
<!--conatiner-->
</div>	
<?php
$sql2=mysqli_query($conn,"SELECT * FROM result where test_code=$test_code ORDER BY percent DESC");
$sno=1;
while($result2 = mysqli_fetch_assoc($sql2)){
$name=$result2['name'];
$rank_percent=$result2['percent'];
echo "<script>document.getElementById('data_rank').innerHTML +='<tr><td>$sno</td><td>$name</td><td>$rank_percent</td></tr>';</script>";
if($_GET['na'] == $result2['code']){
echo "<script>document.getElementById('my_rank').innerHTML='<p>$sno</p>';</script>";}
$sno++;	
}
?>
<script>
    function createPDF() {
        var sTable = document.getElementById('print_page').innerHTML;

        var style = "<style>";
        style = style + "table {width: 100%;font:17px Calibri;}";
        style = style + "table, th, td {border: solid 1px #DDD; border-collapse: collapse;";
        style = style + "padding: 2px 3px;text-align:center;}";
        style = style + "</style>";
        
        // CREATE A WINDOW OBJECT.
        var win = window.open('', '', 'height=700,width=700');

        win.document.write('<html><head>');
        win.document.write('<title>Design by Ahad Ali</title>');
        win.document.write(style);
        win.document.write('</head>');
        win.document.write('<body>');
        win.document.write(sTable);
        win.document.write('</body></html>');

        win.document.close(); 	// CLOSE THE CURRENT WINDOW.

        win.print();    // PRINT THE CONTENTS.
    }
</script>
 </body>
</html>
