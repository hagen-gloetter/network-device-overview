<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<!--	<meta http-equiv="refresh" content="60" /> -->
    <title>Network devices</title>
    <meta name="description" content="Network Device Monitor">
    <meta name="author" content="LayoutIt!">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
	<style>
* {
  box-sizing: border-box;
}

h2 {
	padding: 40px 20px 12px 40px;
}

#myInput, .eingabe {
  background-image: url('css/searchicon.png');
  background-position: 10px 10px;
  background-repeat: no-repeat;
  width: 100%;
  font-size: 1rem;
  padding: 5px 20px 0px 40px;
  border: 1px solid #ddd;
  margin-bottom: 12px;
}

#myTable {
  border-collapse: collapse;
  width: 100%;
  border: 1px solid #ddd;
  font-size: 1.25rem;
}

#myTable th, #myTable td {
  text-align: left;
  padding: 5px;
}
#myTable th {
	font-size: 1rem;
}

#myTable tr {
  border-bottom: 1px solid #ddd;
}

#myTable tr.header, #myTable tr:hover {
  background-color: #f1f1f1;
}
</style>
<!-- Spinner --> 
<style>
/* Center the loader */
#loader {
  position: absolute;
  left: 50%;
  top: 50%;
  z-index: 1;
  width: 150px;
  height: 150px;
  margin: -75px 0 0 -75px;
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 2s linear infinite;
}

@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Add animation to "page content" */
.animate-bottom {
  position: relative;
  -webkit-animation-name: animatebottom;
  -webkit-animation-duration: 1s;
  animation-name: animatebottom;
  animation-duration: 1s
}

@-webkit-keyframes animatebottom {
  from { bottom:-100px; opacity:0 } 
  to { bottom:0px; opacity:1 }
}

@keyframes animatebottom { 
  from{ bottom:-100px; opacity:0 } 
  to{ bottom:0; opacity:1 }
}

#myDiv {
  display: none;
  text-align: center;
}
</style>

<script>
var myVar;

function mySpinner() {
  myVar = setTimeout(showPage, 2000);
}

function showPage() {
  document.getElementById("loader").style.display = "none";
  document.getElementById("myDiv").style.display = "block";
}
</script>

</head>
<?php
	$starttime=time();
?>
	<body onload="mySpinner()" style="margin:0;">	
    <div class="container-fluid">
	<div class="row">

	<div class="col-md-1">
	&nbsp;
	</div>
	<div class="col-md-10">
<!--			<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names.." title="Type in a name"> 
-->
<?php
if (defined('STDIN')) {
	$ip = $argv[1];
  } else {
	$ip = htmlspecialchars($_GET["ip"]);
  }

// Validate ip
if (filter_var($ip, FILTER_VALIDATE_IP)) {
#  echo("$ip is a valid IP address. Starting scan\n");
  get_nmapdata($ip);
} else {
  echo("$ip is not a valid IP address\n");
  exit;
}

function get_nmapdata($ip){
	print "	<div style='display:none;' id='myDiv' class='animate-bottom'>";
	$cmd="nmap -oG - -sV $ip";
	$cmd="nmap -Pn $ip -host-timeout 3s | grep 'open' | perl -pe 's#/tcp\s+open#\t#'";
	$output = shell_exec($cmd);
#	print "$cmd\n";
#	print ("<pre>$output</pre>");
#	$a_hosts = preg_split("/\n/",$output);
	$a_ports = preg_split("/\n/",$output);
#	print "a_hosts\n";
#	print_r($a_hosts);
#	print "<h2>$a_hosts[1]</h2>\n";
	print "<h2>$ip</h2>\n";
#	$ports = $a_hosts[2]; 
#	$a_ports = preg_split("/, /",$ports);
#	print "a_ports\n";
#	print_r($a_ports);
	print "	</div>\n";
	print "<table>\n";
	print "
	<table class='table table-hover table-sm' id='myTable'>
	<thead>
		<tr  class='header'>
			<th style='vertical-align: top; '>Port<br><div id='loader'></div></th>
			<th style='vertical-align: top; '>Service<br></th>
			<th style='vertical-align: top; '>Info</th>
		</tr>
	</thead>
	<tbody>
	\n";

	foreach ($a_ports as $zeile){
#		$items = preg_split("#/#",$zeile);
		$items = preg_split("#\t#",$zeile);
#		print_r($items);
			$port =  $items[0];
			if (strpos($port, 'Ports:')) {
				# Host: 192.168.4.1 (atlas.fritz.box)  Ports: 21
				$port = preg_replace('/.*Ports:/', "", $port);
			}
			print "<tr>";
			print "<td>$port</td>";
			print "<td>$items[1]</td>";
			print "<td>$items[6]</td>";
			print "</tr>\n";
	}
	print "</table>\n";
}
?>

<script>
$(document).ready(function(){
  $("button").click(function(){
    $.ajax({url: "demo_test.txt", success: function(result){
      $("#div1").html(result);
    }});
  });
});
</script>


<?php
	$mytime=time()-$starttime;
	print "<pre>done in $mytime seconds</pre>\n";
?>
	</div><!-- 4 -->

	<div class="col-md-1">
		&nbsp;
	</div>


	</div> <!-- row -->
	</div>
	<script>
		// https://www.w3schools.com/howto/howto_js_filter_table.asp
		function myFunction(fnr) {
		var input, filter, table, tr, td, i, txtValue;
		input = document.getElementById("myInput1");
		if (fnr > 1 ){
			input = document.getElementById("myInput2");
		}
		filter = input.value.toUpperCase();
		table = document.getElementById("myTable");
		tr = table.getElementsByTagName("tr");
		for (i = 0; i < tr.length; i++) {
			td = tr[i].getElementsByTagName("td")[fnr];
			if (td) {
			txtValue = td.textContent || td.innerText;
			if (txtValue.toUpperCase().indexOf(filter) > -1) {
				tr[i].style.display = "";
			} else {
				tr[i].style.display = "none";
			}
			}       
		}
		}
	</script>
    <script src="js/jquery.min.js"></script>
<!--	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>-->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
  </body>
</html>
