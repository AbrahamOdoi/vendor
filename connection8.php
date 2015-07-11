<?php
error_reporting(0);
//ob_start();
// $con=mysqli_connect("168.144.85.195","nalo","msyWE546","dlr");
    // $con = mysqli_connect("78.46.254.50", "nalo", "msyWE546", "dlr"); 
    $con = mysqli_connect("78.46.254.56", "nalo", "msyWE546", "dlr"); 
// $con=mysqli_connect("localhost","root","","dlr");

if (mysqli_connect_errno()) {
  // echo "Failed to connect to MySQL: " . mysqli_connect_error();
	echo "Error ";
}
?>