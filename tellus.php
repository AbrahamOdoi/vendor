<?php

$question=$_POST['view'];
echo $question; 
// exit; 
require_once ('core/connection7.php');
$result = mysqli_query($con, "INSERT INTO presponse (question)VALUE('$question')");
if ($result) {
	// echo "Your question has been heard";
} else {
	// echo "Please try again";
}



?>