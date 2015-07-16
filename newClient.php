<?php
session_start();
include_once 'core/connection.php';

$clientName = $_POST['clientName'];
$clientGender = $_POST['clientGender'];
$clientMobile = $_POST['clientMobile'];
$clientHseNumber = $_POST['clientHseNumber'];
$clientService = $_POST['clientService'];
$clientBins = $_POST['clientBins'];
$clientAmount = $_POST['clientAmount'];
$clientLocation = $_POST['clientLocation'];
$clientCluster = $_POST['clientCluster'];
$clientId = $_POST['id'];
$createdBy = $_SESSION['agent'];

$msg = "Dear $clientName, You have been successfully registered for Zoomlion services. Your clientID is $clientId";
$message = urlencode($msg);

$result_verify = mysqli_query($con, "select * from clients where clientid='$clientId';");
if (mysqli_num_rows($result_verify) > 0) {
	$clientId = $clientId . 'a';

	$result_newclient = mysqli_query($con, "insert into clients (name,gender,mobile,hsenumber,bins,amount,cluster,clientid,servicetype,location,createdby)values('$clientName','$clientGender','$clientMobile','$clientHseNumber','$clientBins','$clientAmount','$clientCluster','$clientId','$clientService','$clientLocation','$createdBy');");
	if ($result_newclient) {

		 $filename = "http://216.224.161.207/api/bulksms/?username=zoomlionmobile&password=zoomie&type=0&dlr=1&destination=$clientMobile&source=ZOOMLION&message=$message";
		file_get_contents($filename);
		echo "Client Added Successfully";
	} else {
		echo "Operation Unsuccessful. Try again" . mysqli_error($con);
	}

} else {
	$result_newclient = mysqli_query($con, "insert into clients (name,gender,mobile,hsenumber,bins,amount,cluster,clientid,servicetype,location,createdby)values('$clientName','$clientGender','$clientMobile','$clientHseNumber','$clientBins','$clientAmount','$clientCluster','$clientId','$clientService','$clientLocation','$createdBy');");
	if ($result_newclient) {

		 $filename = "http://216.224.161.207/api/bulksms/?username=zoomlionmobile&password=zoomie&type=0&dlr=1&destination=$clientMobile&source=ZOOMLION&message=$message";
		 file_get_contents($filename);

		echo "1";
	} else {
		echo "0" . mysqli_error($con);
	}
}
mysqli_close($con);
?>