<?php
error_reporting(0);

$id = uniqid('NS', true);
$dayt = date('Y-m-d H:i:s');
function checkPorted($msisdn) {
	// $con = mysqli_connect("168.144.85.195", "nalo", "msyWE546", "dlr");
	// $con = mysqli_connect("78.46.254.50", "nalo", "msyWE546", "dlr");
	// $con = mysqli_connect("78.46.254.56", "nalo", "msyWE546", "dlr");
	$con = mysqli_connect("78.46.254.56", "nalo", "nsgt293@s#wwq24!", "dlr");
	$result = mysqli_query($con, "SELECT COUNT(msisdn) FROM ported WHERE msisdn = '$msisdn'");

	$row = mysqli_fetch_array($result);

	if ($row[0] > 0) {
		return 1;
	} else {
		return 0;
	}

}

function which_network($msisdn) {
	$no = substr($msisdn, 3, 2);

	if ($no == '24' || $no == '54' || $no == '55') {
		$net = 'MTN';
	} elseif ($no == '20' || $no == '50') {
		$net = 'VODAFONE';
	} elseif ($no == '27' || $no == '57') {
		$net = 'TIGO';
	} elseif ($no == '26') {
		$net = 'AIRTEL';
	} elseif ($no == '23') {
		$net = 'GLO';
	} elseif ($no == '28') {
		$net = 'EXPRESSO';
	} else {
		$net = 'UNKNOWN';
	}

	return $net;
}

function no_pages($no_chars) {

	if ($no_chars <= 160) {
		$no_pages = 1;
	}
	if ($no_chars > 160 && $no_chars <= 306) {
		$no_pages = 2;
	}
	if ($no_chars > 306 && $no_chars <= 459) {
		$no_pages = 3;
	}
	if ($no_chars > 459 && $no_chars <= 621) {
		$no_pages = 4;
	}
	if ($no_chars > 621) {
		$no_pages = 5;
	}

	return $no_pages;
}

// include 'balance.php';
date_default_timezone_set('UTC');
$con = mysqli_connect("78.46.254.56", "nalo", "nsgt293@s#wwq24!", "dlr");
// $con = mysqli_connect("78.46.254.56", "nalo", "msyWE546", "dlr");
// $con = mysqli_connect("78.46.254.50", "nalo", "msyWE546", "dlr");
// $con = mysqli_connect("168.144.85.195", "nalo", "msyWE546", "dlr");
// $con = mysqli_connect("localhost", "ussd", "msyWE546", "dlr");
// $onnect = mysqli_connect("localhost", "ecobank", "123esgtw", "dbecobank");

if (isset($_GET['username'])) {
	$username = $_GET['username'];
	$password = $_GET['password'];
	$numb = $_GET['destination'];
	$source = $_GET['source'];
	$message = $_GET['message'];
	$ref = $_GET['ref'];
} else {
	$username = $_POST['username'];
	$password = $_POST['password'];
	$numb = $_POST['destination'];
	$source = $_POST['source'];
	$message = $_POST['message'];
	$ref = $_POST['ref'];
}
$job_id = $username . date('YmdHis');
if ($username == 'na1-ubaghana' || $username == 'kopokopo') {

	$numb = trim($numb);
	$submitdt = date('Y-m-d H:i:s');

	function checkCode($code) {
		if ($code == '246') {
			$msisdnLeng = 6;
			return $msisdnLeng;
		} elseif ($code == '238' || $code == '241' || $code == '239' || $code == '220') {
			$msisdnLeng = 7;
			return $msisdnLeng;
		} elseif ($code == '229' || $code == '257' || $code == '237' || $code == '236' || $code == '235' || $code == '225' || $code == '243' || $code == '231' || $code == '223' || $code == '227' || $code == '228') {
			$msisdnLeng = 8;
			return $msisdnLeng;
		} elseif ($code == '242' || $code == '240' || $code == '254' || $code == '265' || $code == '250' || $code == '27' || $code == '221' || $code == '232' || $code == '211' || $code == '255' || $code == '256' || $code == '260' || $code == '233' || $code == '972') {
			$msisdnLeng = 9;
			return $msisdnLeng;
		} elseif ($code == '1' || $code == '234' || $code == '218' || $code == '263' || $code == '44' || $code == '96') {
			return $msisdnLeng = 10;
			return $msisdnLeng;
		} else {
			$msisdnLeng = 10;
			return $msisdnLeng;
		}

	}

	$trim_destination = explode(',', $numb);
	$desNum = count($trim_destination);
	$count = 0;
	while ($count !== $desNum) {
		$number = $trim_destination[$count];
		$count = $count + 1;

		if (substr($number, 0, 1) == '1') {
			$code = substr($number, 0, 1);
			$ccLength = strlen($code);
		} elseif (substr($number, 0, 2) == '44' || substr($number, 0, 2) == '96') {
			$code = substr($number, 0, 2);
			$ccLength = strlen($code);
		} elseif (substr($number, 0, 2) == '27') {
			$code = substr($number, 0, 2);
			$ccLength = strlen($code);
		} else {
			$code = substr($number, 0, 3);
			$ccLength = strlen($code);
		}

		checkCode($code);
		$msisdnLength = checkCode($code);
		$numberLength = $msisdnLength + $ccLength;

		$msg = urldecode($message);
		$mesg = urlencode($msg);

		$destLength = strlen($number);
		if (($destLength == $numberLength)) {

			$msgcount = strlen($message);

			$sms_count = no_pages($msgcount);

			$network = which_network($numb);

			if (!$run) {
				// echo "warning" . mysqli_error($con);
			} else {
				// echo "yes";
			}

			$dlr = "http://168.144.175.52/nalosms/sendsmsdlr3.php?source=%P&destination=%p&SVC=%n&msgID=%F&msg=%a&msglen=%L&timestamp=%t&status=%d&smsc=%i&smsid=%I&dlrv=%d&MDS=%D&DLRS=%A&osms=%b&mid=" . $id;
			$dlrEncode = urlencode($dlr);

			$url = "http://168.144.175.52:13013/cgi-bin/sendsms?username=apiuser&password=O14ns0&to=" . $number . "&from=" . $source . "&text=" . $mesg . "&dlr-mask=31&dlr-url=" . $dlrEncode;

			$urloutput = file_get_contents($url);

			$sql = "INSERT INTO logs(id, job_id, username, msisdn, sender, sms_count, network, message,submit_date, status,created_by,response,originated,refid)
VALUES ('$id', '$job_id', '$username', '$number', '$source','$sms_count','$network','$msg','$submitdt','PENDING','$username','$urloutput','apiuser','$ref')";
			$run = mysqli_query($con, $sql);

			if ($urloutput == '0: Accepted for delivery') {
				echo "1701|" . $number . '|' . $id;
				$wri = "1701|" . $number . '|' . $id;

				// $cmd = 'echo ' . $dayt.'|' . $wri .' >> /var/www/html/bulksms/access.log';
				// shell_exec($cmd);

				// $cmd = 'echo ' . $dayt . ' success_1701_' . $number . '_' . $id . '_' . $ref . ' >> fablogs.log';
				// shell_exec($cmd);
				//
				$data = $dayt . '|' . $wri . '|' . $username . PHP_EOL;
				file_put_contents('/var/www/html/bulksms/access.log', $data, FILE_APPEND);
			}

		} else {
			$sql = "INSERT INTO logs(id, job_id, username, msisdn, sender, message,submit_date, status,created_by,originated,refid)
VALUES ('$id', '$job_id', '$username', '$numb', '$source','$message','$submitdt','FAILED','$username','apiuser','$ref')";
			$run = mysqli_query($con, $sql);

			echo "1706|$numb|$id";
		}

	}
} else {

	require_once ('core/connection.php');
	require_once ('core/functions.php');

	$user_sess = $username;
	$nums = $numb; 
	$sender = $source;
	$msgs = $message;

	date_default_timezone_set('GMT');
	$submitdt = date('Y-m-d H:i:s');

	$rejected_arr = array();
	$destination = explode(',', $nums);

	$no_chars = strlen($msgs);
	//no of characters

	$no_pages = no_pages($no_chars);
	//function that counts number of pages

	$sms_count = $no_pages;

	$res = mysqli_query($con, "SELECT username, password,created_by, email FROM users WHERE username='$user_sess' AND password='$password' AND status='active' LIMIT 1");

	if (mysqli_num_rows($res) == 1) {
		// ===============created by query==========================
		$user_array = mysqli_fetch_array($res);
		$created_by = $user_array['created_by'];
		$email = $user_array['email'];

		$res2 = mysqli_query($con, "SELECT  email FROM admin WHERE username = '$created_by'");
		$admin_array = mysqli_fetch_array($res2);
		$ad_email = $admin_array['email'];

		// =================================================

		$price_res = mysqli_query($con, "SELECT price FROM credit WHERE username = '$user_sess'");

		$price_array = mysqli_fetch_array($price_res);

		$price = $price_array['price'];

		$cost = $no_pages * $price;
		// cost of sending one msg with this no_pages to one person

		$nummsisdn = count($destination);
		// no of recepients

		$tot_cost = $cost * $nummsisdn;

		$q1 = "SELECT new_bal, credit_used FROM credit WHERE username = '$user_sess'";

		$result = mysqli_query($con, $q1);

		$detail = mysqli_fetch_array($result);

		$curr_bal = $detail['new_bal'];
		$credit_used = $detail['credit_used'];

		$rem_sms = floor($curr_bal / $cost);
		//remaining sms user can send

		$counter = floor($rem_sms / $no_pages);

		$some_cost = $cost * $counter;
		//cost of sending one msg with this no_pages to one person * no of smses that can be sent

		if ($curr_bal >= $tot_cost) {

			$count = 0;
			$job_id = $user_sess . date('YmdHis');

			while ($count !== $nummsisdn) {
				$number = $destination[$count];

				$msisdn = preg_replace('/\D/', '', $number);

				// $msisdn = msisdn_prep($msisdn);
				//put msisdn in proper format

				if (is_numeric($msisdn)) {

					if (substr($msisdn, 0, 1) == '1') {
						$code = substr($msisdn, 0, 1);
						$ccLength = strlen($code);
					} elseif (substr($msisdn, 0, 2) == '44') {
						$code = substr($msisdn, 0, 2);
						$ccLength = strlen($code);
					} elseif (substr($msisdn, 0, 2) == '27') {
						$code = substr($msisdn, 0, 2);
						$ccLength = strlen($code);
					} else {
						$code = substr($msisdn, 0, 3);
						$ccLength = strlen($code);
					}

					$network = which_network($msisdn);

					$id = uniqid('NS', true);

					checkCode($code, $user_sess);

					$standard_len = $ccLength + checkCode($code);

					$new_len = strlen($msisdn);

					if ($standard_len !== $new_len) {

						array_push($rejected_arr, $msisdn);

						$query = "INSERT INTO rejected(id, username, msisdn, sender, sms_count, message,submit_date, created_by) 
				VALUES ('$id', '$user_sess', '$msisdn', '$sender','$sms_count','$msgs','$submitdt','$created_by')";
						mysqli_query($con, $query);

						insertRejected($id, $job_id, $user_sess, $msisdn, $sender, $sms_count, $network, $msgs, $submitdt, $created_by);

						echo "1706|Invalid Destination";

					} else {

						sendsms($id, $job_id, $user_sess, $msisdn, $sender, $sms_count, $network, $msgs, $submitdt, $created_by);

					}
				} else {
					array_push($rejected_arr, $msisdn);
					$nonnumeric = "INSERT INTO rejected(id, username, msisdn, sender, sms_count, message,submit_date, created_by) 
				VALUES ('$id', '$user_sess', '$msisdn', '$sender','$sms_count','$msgs','$submitdt','$created_by')";
					mysqli_query($con, $nonnumeric);

					insertRejected($id, $job_id, $user_sess, $msisdn, $sender, $sms_count, $network, $msgs, $submitdt, $created_by);

				}

				$no_rej_msisdn = count($rejected_arr);
				//counts number of rejected contacts

				$count++;

			}

			// 	sendsms($nummsisdn, $destination, $sender, $msgs, $user_sess, $sms_count);

			$rejected_cost = $cost * $no_rej_msisdn;
			$new_bal = $curr_bal - $tot_cost + $rejected_cost;
			//new balance

			$q2 = "UPDATE credit SET new_bal = '$new_bal' WHERE username = '$user_sess'";
			//updates the new balance
			mysqli_query($con, $q2);

			$new_credit_used = $credit_used + $tot_cost;

			mysqli_query($con, "UPDATE credit SET credit_used = '$new_credit_used' WHERE username = '$user_sess'");
			//updates credit used

			$logged = $nummsisdn - $no_rej_msisdn;

		} else {

			$count = 0;

			$job_id = $user_sess . date('YmdHis');

			while ($count < $counter) {
				$number = $destination[$count];
				$msisdn = preg_replace('/\D/', '', $number);

				$msisdn = msisdn_prep($msisdn);
				//put msisdn in proper format
				if (is_numeric($msisdn)) {
					if (substr($msisdn, 0, 1) == '1') {
						$code = substr($msisdn, 0, 1);
						$ccLength = strlen($code);
					} elseif (substr($msisdn, 0, 2) == '44') {
						$code = substr($msisdn, 0, 2);
						$ccLength = strlen($code);
					} else {
						$code = substr($msisdn, 0, 3);
						$ccLength = strlen($code);
					}

					$network = which_network($msisdn);

					$id = uniqid('NS', true);

					checkCode($code);
					$standard_len = $ccLength + checkCode($code);

					$new_len = strlen($msisdn);

					if ($standard_len !== $new_len) {

						array_push($rejected_arr, $msisdn);
						$query = "INSERT INTO rejected(id, username, msisdn, sender, sms_count, message,submit_date, created_by) 
				VALUES ('$id', '$user_sess', '$msisdn', '$sender','$sms_count','$msgs','$submitdt','$created_by')";
						mysqli_query($con, $query);

						insertRejected($id, $job_id, $user_sess, $msisdn, $sender, $sms_count, $network, $msgs, $submitdt, $created_by);
						$counter++;

					} else {

						sendsms($id, $job_id, $user_sess, $msisdn, $sender, $sms_count, $network, $msgs, $submitdt, $created_by);
					}

				} else {
					array_push($rejected_arr, $msisdn);
					$nonnumeric = "INSERT INTO rejected(id, username, msisdn, sender, sms_count, message,submit_date, created_by) 
				VALUES ('$id', '$user_sess', '$msisdn', '$sender','$sms_count','$msgs','$submitdt','$created_by')";
					mysqli_query($con, $nonnumeric);

					insertRejected($id, $job_id, $user_sess, $msisdn, $sender, $sms_count, $network, $msgs, $submitdt, $created_by);

					$counter++;

				}

				$count++;
			}
			$no_rej_msisdn = count($rejected_arr);

			$rem_start_point = $count;

			$rem_array = array_slice($destination, $rem_start_point);

			$msgs_left = count($rem_array);

			$msisdn_string = implode($rem_array, ',');

			$sql2 = "INSERT INTO q_msgs (username, msisdn, sender, msg, sms_count, status,created_by ) 
				VALUES ('$user_sess', '$msisdn_string', '$sender', '$msgs', '$sms_count','pending','$created_by' )";

			if (mysqli_query($con, $sql2)) {

				$no_rem = $msgs_left;
			}
			if ($msgs_left > 0) {
				// ===============send mail==============

				require 'PHPMailer/PHPMailerAutoload.php';

				$subject = 'Pending Messages';

				echo $mbody = '<b>Dear ' . $user_sess . '</b>,
						   <br />
						   You have <b>' . $msgs_left . ' pending messages</b> as a result of insufficient credit. <br />
						   Please top-up credit and manually process queued messages from the Job Managemnt menu. <br /><br />

						   Contact <b>' . $ad_email . '</b> to purchase credit or,<br />
						   Contact <b>support@nalosolutions.com</b> for further support.

						   
						   <br />
						   ---<br />
						   Regards, <br />
						   Nalo Solutions Limited <br />
						   House No. 1, Mahogany Close, Dansoman Last Stop <br />
						   M: +233 574928703, T: +233 246041258 <br />
						   E: info@nalosolutions.com, W: www.nalosolutions.com <br />
						   ';
				$altbody = 'Dear ' . $user_sess . ',
						   
						  You have ' . $msgs_left . ' pending messages as a result of insufficient credit.
						   Please top-up credit and manually process queued messages from the Job Managemnt menu.

						   Contact ' . $ad_email . ' to purchase credit or,
						   Contact support@nalosolutions.com for further support.
						   				   
						   Regards,
						   Nalo Solutions Limited 
						   House No. 1, Mahogany Close, Dansoman Last Stop 
						   M: +233 574928703, T: +233 246041258 <br />
						   E: info@nalosolutions.com, W: www.nalosolutions.com';

				// sendmail($user_sess, $subject, $email, $mbody, $altbody);

				//============end send mail=======================
			}
			$new_bal = $curr_bal - $some_cost;

			$q2 = "UPDATE credit SET new_bal = '$new_bal' WHERE username = '$user_sess'";
			//updates the new balance
			$result = mysqli_query($con, $q2);

			$new_credit_used = $credit_used + $some_cost;

			mysqli_query($con, "UPDATE credit SET credit_used = '$new_credit_used' WHERE username = '$user_sess'");

			$all = count($destination);
			$logged = $all - $no_rej_msisdn;

			if ($no_rem > 0) {
				$logged = $logged - $no_rem;
			}

		}
	} else {
		echo "1703|Invalid Credentials ";
	}
}
mysqli_close($con);
?>
