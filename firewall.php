<?php
$conn = new PDO('pgsql:host=localhost;dbname=firewall', 'postgres', 'test');

if ($_SERVER['REQUEST_METHOD']=="POST") {
		if($_POST['submit']=="Allow"){
			$ip = $_POST['ip'];
			$status	=	'allow';
			$sql = "INSERT INTO ip (ip, status) VALUES (?,?)";
			$stmt= $conn->prepare($sql);
			$stmt->execute([$ip, $status]);	
		}else if($_POST['submit']=="Block"){
			$ip1 = $_POST['ip1'];
			$status	=	'block';
			$sql = "INSERT INTO ip (ip, status) VALUES (?,?)";
			$stmt= $conn->prepare($sql);
			$stmt->execute([$ip1, $status]);	
		}
		if($_POST['submit']=="Allow Port"){
			$ip = $_POST['ip2'];
			$status	=	'allow';
			$sql = "INSERT INTO ports (ports, status) VALUES (?,?)";
			$stmt= $conn->prepare($sql);
			$stmt->execute([$ip, $status]);	
		}else if($_POST['submit']=="Block Port"){
			$ip1 = $_POST['ip3'];
			$status	=	'block';
			$sql = "INSERT INTO ports (ports, status) VALUES (?,?)";
			$stmt= $conn->prepare($sql);
			$stmt->execute([$ip1, $status]);	
		}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Firewall</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
  <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/modules/exporting.js"></script>
  <script src="https://code.highcharts.com/modules/export-data.js"></script>
  <script src="https://code.highcharts.com/modules/accessibility.js"></script>
</head>
<style>
	.highcharts-figure, .highcharts-data-table table {
    min-width: 320px; 
    max-width: 800px;
    margin: 1em auto;
}

.highcharts-data-table table {
	font-family: Verdana, sans-serif;
	border-collapse: collapse;
	border: 1px solid #EBEBEB;
	margin: 10px auto;
	text-align: center;
	width: 100%;
	max-width: 500px;
}
.highcharts-data-table caption {
    padding: 1em 0;
    font-size: 1.2em;
    color: #555;
}
.highcharts-data-table th {
	font-weight: 600;
    padding: 0.5em;
}
.highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
    padding: 0.5em;
}
.highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
    background: #f8f8f8;
}
.highcharts-data-table tr:hover {
    background: #f1f7ff;
}


input[type="number"] {
	min-width: 50px;
}
</style>
<body style="background-color:#f5edde;">
<nav class="navbar navbar-default" style="background-color: darkblue;">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" style="color: white;">Firewall</a>
    </div>
  </div>
</nav>
<div class="container-fluid row">
 <form id="register-form" method="POST" role="form" >
 <div class="col-lg-6">
 	Ip Address: &nbsp;
        <input type="text" name="ip" id="ip"  value="<?php echo $ip ?>">
        <input type="submit" name="submit" id="submit" value="Allow">
   
   	</div>
   	<div class="col-lg-6">
 		Ip Address: &nbsp;
        <input type="text" name="ip1" id="ip1"  value="<?php echo $ip1 ?>">
        <input type="submit" name="submit" id="submit" value="Block">
   	</div>
   	</form><br><br><hr>
 <form id="register-form" method="POST" role="form" >
 <div class="col-lg-6">
 	Port: &nbsp;
        <input type="text" name="ip2" id="ip2"  value="<?php echo $ip2 ?>">
        <input type="submit" name="submit" id="submit" value="Allow Port">
   
   	</div>
   	<div class="col-lg-6">
 		Port: &nbsp;
        <input type="text" name="ip3" id="ip3"  value="<?php echo $ip3 ?>">
        <input type="submit" name="submit" id="submit" value="Block Port">
   	</div>
   	</form><br><br><hr>
 </div>
<div class="row">
	<div class="col-lg-6">
		<?php 
			$host = "localhost"; 
			$user = "postgres"; 
			$pass = "test"; 
			$db = "firewall"; 
			$con = pg_connect("host=$host dbname=$db user=$user password=$pass")
			    or die ("Could not connect to server\n"); 
			$query = "SELECT * FROM ip where status='allow'"; 
			$rs = pg_query($con, $query) or die("Cannot execute query: $query\n");
			echo "<div class='container'>";
			echo "<h4 style='background-color:grey color:white'> Allow IP Address</h4>";
			echo "<table>";
			while ($row = pg_fetch_row($rs)) {
				echo "<tr style='border-style: solid; border-color: black;'>";
				echo "<td style='padding: 6px 6px 6px 6px'>$row[1]</td>";
				echo "</tr>";
			}
			echo "</table>";
			echo "</div>";
			pg_close($con); 
			?>
	</div>
	<div class="col-lg-6">
		<?php 
			$host = "localhost"; 
			$user = "postgres"; 
			$pass = "test"; 
			$db = "firewall"; 
			$con = pg_connect("host=$host dbname=$db user=$user password=$pass")
			    or die ("Could not connect to server\n"); 
			$query = "SELECT * FROM ip where status='block'"; 
			$rs = pg_query($con, $query) or die("Cannot execute query: $query\n");
			echo "<div class='container'>";
			echo "<h4 style='background-color:grey color:white'> Block IP Address</h4>";
			echo "<table>";
			while ($row = pg_fetch_row($rs)) {
				echo "<tr style='border-style: solid; border-color: black;'>";
				echo "<td style='padding: 6px 6px 6px 6px'>$row[1]</td>";
				echo "</tr>";
			}
			echo "</table>";
			echo "</div>";
			pg_close($con); 
			?>
	</div>
</div><br><br><hr>
<div class="row">
	<div class="col-lg-6">
		<?php 
			$host = "localhost"; 
			$user = "postgres"; 
			$pass = "test"; 
			$db = "firewall"; 
			$con = pg_connect("host=$host dbname=$db user=$user password=$pass")
			    or die ("Could not connect to server\n"); 
			$query = "SELECT * FROM ports where status='allow'"; 
			$rs = pg_query($con, $query) or die("Cannot execute query: $query\n");
			echo "<div class='container'>";
			echo "<h4 style='background-color:grey color:white'> Allow Port Address</h4>";
			echo "<table>";
			while ($row = pg_fetch_row($rs)) {
				echo "<tr style='border-style: solid; border-color: black;'>";
				echo "<td style='padding: 6px 6px 6px 6px'>$row[1]</td>";
				echo "</tr>";
			}
			echo "</table>";
			echo "</div>";
			pg_close($con); 
			?>
	</div>
	<div class="col-lg-6">
		<?php 
			$host = "localhost"; 
			$user = "postgres"; 
			$pass = "test"; 
			$db = "firewall"; 
			$con = pg_connect("host=$host dbname=$db user=$user password=$pass")
			    or die ("Could not connect to server\n"); 
			$query = "SELECT * FROM ports where status='block'"; 
			$rs = pg_query($con, $query) or die("Cannot execute query: $query\n");
			echo "<div class='container'>";
			echo "<h4 style='background-color:grey color:white'> Block Port Address</h4>";
			echo "<table>";
			while ($row = pg_fetch_row($rs)) {
				echo "<tr style='border-style: solid; border-color: black;'>";
				echo "<td style='padding: 6px 6px 6px 6px'>$row[1]</td>";
				echo "</tr>";
			}
			echo "</table>";
			echo "</div>";
			pg_close($con); 
			?>
	</div>
</div><br><br><hr>
<div class='row'>
	<div class="container col-lg-6">
		<figure class="highcharts-figure">
		    <div id="container"></div>
		    <p class="highcharts-description">
		    </p>
		</figure>
	</div>
	<div class="container col-lg-6">
		<figure class="highcharts-figure">
		    <div id="container1"></div>
		    <p class="highcharts-description">
		    </p>
		</figure>
	</div>
</div><br><br><hr>
<div class="row">
		<div class="container">
		<?php
			require "chart.php";
		?>
		</div>
</div>
 </body>
 <script type="text/javascript">
 	$.ajax({
				type: 'POST',
				url: "allowapi.php",
				datatype:'json',
				success: function(data) {
					var returnedData = JSON.parse(data);
					var alllow=[];
					console.log(returnedData)
					abc=JSON.parse(returnedData[0])
					console.log(abc[0].count)
					def=JSON.parse(returnedData[1])
					console.log(def[0].count)
							Highcharts.chart('container', {
					    chart: {
					        plotBackgroundColor: null,
					        plotBorderWidth: null,
					        plotShadow: false,
					        type: 'pie'
					    },
					    title: {
					        text: 'IP Address'
					    },
					    tooltip: {
					        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
					    },
					    credits: {
						    enabled: false
						  },
					    accessibility: {
					        point: {
					            valueSuffix: '%'
					        }
					    },
					    plotOptions: {
					        pie: {
					            allowPointSelect: true,
					            cursor: 'pointer',
					            dataLabels: {
					                enabled: true,
					                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
					            }
					        }
					    },
					    series: [{
					        name: 'IP Address',
					        colorByPoint: true,
					        data: [{
					            name: 'Allow',
					            y: abc[0].count,
					            sliced: true,
					            selected: true
					        }, {
					            name: 'Block',
					            y: def[0].count
					        }]
					    }]
					}); 
				}
			})
$.ajax({
				type: 'POST',
				url: "allowport.php",
				datatype:'json',
				success: function(data) {
					var returnedData = JSON.parse(data);
					var alllow=[];
					console.log(returnedData)
					abc=JSON.parse(returnedData[0])
					console.log(abc[0].count)
					def=JSON.parse(returnedData[1])
					console.log(def[0].count)
							Highcharts.chart('container1', {
					    chart: {
					        plotBackgroundColor: null,
					        plotBorderWidth: null,
					        plotShadow: false,
					        type: 'pie'
					    },
					    title: {
					        text: 'Port Address'
					    },
					    tooltip: {
					        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
					    },
					    credits: {
						    enabled: false
						  },
					    accessibility: {
					        point: {
					            valueSuffix: '%'
					        }
					    },
					    plotOptions: {
					        pie: {
					            allowPointSelect: true,
					            cursor: 'pointer',
					            dataLabels: {
					                enabled: true,
					                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
					            }
					        }
					    },
					    series: [{
					        name: 'Port Address',
					        colorByPoint: true,
					        data: [{
					            name: 'Allow',
					            y: abc[0].count,
					            sliced: true,
					            selected: true
					        }, {
					            name: 'Block',
					            y: def[0].count
					        }]
					    }]
					}); 
				}
			})
 </script>
</html>