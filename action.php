<?php
/**
* Copyright (C) 2013 peredur.net
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
sec_session_start();
?>
<?php

//action.php

include('database_connection.php');

if(isset($_POST["action"]))
{
	if($_POST["action"] == "insert")
	{
		/*$query = "
		INSERT INTO `appo` (first_name, last_name) VALUES ('".$_POST["first_name"]."', '".$_POST["last_name"]."')
		";*/
	$status  = $_POST['status'];
	$title  = $_POST['title'];
    $detail = $_POST['detail'];
    $date   = $_POST['date'];
    $time   = $_POST['time'];
    $time_end = $_POST['timeend'];
    $color  = $_POST['color'];
    //echo '<a class="gotomonth" href="calendar.php?date=' . $_POST['date'] . '"> Calendar </a>';
    $query = "INSERT INTO `appo` (`id`, `user`, `title`, `detail`, `date`, `time`, `timeend`, `color`, `status`, `username`) VALUES (NULL, '" . $_SESSION['user_id'] . "', '" . $title . "', '" . $detail . "', '" . $date . "', '" . $time . "', '" . $time_end . "', '" . $color . "', '" . $status . "', '" . $_SESSION['username'] . "')";
		$statement = $connect->prepare($query);
		$statement->execute();
		echo '<p>Data Inserted...</p>';
	}
	if($_POST["action"] == "fetch_single")
	{
		$query = "
		SELECT * FROM `appo` WHERE id = '".$_POST["id"]."'
		";
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			$output['id'] = $row['id'];
			$output['title'] = $row['title'];
			$output['detail'] = $row['detail'];
			$output['date'] = $row['date'];
			$output['time'] = $row['time'];
			$output['timeend'] = $row['timeend'];
			$output['color'] = $row['color'];
			$output['status'] = $row['status'];
		}
		echo json_encode($output);
	}
	if($_POST["action"] == "update")
	{
		$query = "UPDATE `appo` SET title='".$_POST["title"]."', detail='".$_POST["detail"]."', `date`='".$_POST["date"]."', `time`='".$_POST["time"]."', timeend='".$_POST["timeend"]."', color='".$_POST["color"]."', status='".$_POST["status"]."' WHERE id = '". $_POST['hidden_id'] ."' ";
		$statement = $connect->prepare($query);
		$statement->execute();
		echo '<p>Data Updated</p>';
	}
	if($_POST["action"] == "delete")
	{
		$query = "DELETE FROM `appo` WHERE id = '".$_POST["id"]."'";
		//$sql = "DELETE FROM `appo` WHERE `appo`.`id` = $id";
		$statement = $connect->prepare($query);
		$statement->execute();
		echo '<p>Data Deleted</p>';
	}

	if($_POST["action"] == "clear")
	{
		$query = "DELETE FROM `appo` WHERE `user` = '".$_POST["user"]."'";
		$statement = $connect->prepare($query);
		$statement->execute();
		echo '<p>Data Deleted</p>';
	}

}

?>