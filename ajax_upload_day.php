<?php
include_once 'includes/db_connect.php';
$query = "UPDATE `appo` SET `time` = '" . $_POST['time'] . "' , `date` = '" . $_POST['date'] . "' WHERE `id` = '" . $_POST['id'] . "'";
	 if(mysqli_query($mysqli, $query))
	 {
	 		echo true;
	 }
	 else
	 	echo false;

?>