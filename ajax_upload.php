<?php

	include_once 'includes/db_connect.php';
	 $query = "UPDATE `appo` SET `date` = '" . $_POST['date'] . "'  WHERE `id` = '" . $_POST['event'] . "'";
	 if(mysqli_query($mysqli, $query))
	 {
	 		echo true;
	 }
	 else
	 	echo false;
	

?>