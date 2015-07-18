<?php

require_once ('core/connection.php');

$query = "select name from readers ";
// $query = "select name from readers where readdate > DATE_SUB(CURDATE(),INTERVAL DAYOFWEEK(CURDATE())-2 DAY) ";
$result = mysqli_query($con, $query);

$data = "<ul data-role='listview' data-inset='true' data-filter='true' data-input='filterBasic-input'  id='reads'>";
if ($result) {
	while ($a = mysqli_fetch_assoc($result)) {
		$name = $a['name'];

		$data .= '<li>' . $name . '</li>';
	}
} else {
	echo "fail" . mysqli_error($con);
}

$data .= "</ul>";
echo $data;
?>