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

//fetch.php

include("database_connection.php");

$query = "SELECT * FROM `appo` WHERE user = '". $_SESSION['user_id'] ."'";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$total_row = $statement->rowCount();
$output = '
<table class="table table-striped table-bordered">
	<tr>
		<th>#</th>
		<th>Title</th>
		<th>Detail</th>
		<th>Date</th>
		<th>Start</th>
		<th>End</th>
		<th>Color</th>
		<th>Status</th>
		<th>Edit</th>
		<th>Delete</th>
	</tr>
';
if($total_row > 0)
{
	foreach($result as $row)
	{
		$output .= '
		<tr>
			<td width="10%">'.$row["id"].'</td>
			<td width="10%">'.$row["title"].'</td>
			<td width="10%">'.$row["detail"].'</td>
			<td width="10%">'.$row["date"].'</td>
			<td width="10%">'.$row["time"].'</td>
			<td width="10%">'.$row["timeend"].'</td>
			<td width="10%" style="color: '.$row["color"].' " >'.$row["color"].'</td>
			<td width="10%">'.$row["status"].'</td>
			<td width="10%">
				<button type="button" name="edit" class="btn btn-warning btn-xs edit" id="'.$row["id"].'">Edit</button>
			</td>
			<td width="10%">
				<button type="button" name="delete" class="btn btn-danger btn-xs delete" id="'.$row["id"].'">Delete</button>
			</td>
		</tr>
		';
	}
}
else
{
	$output .= '
	<tr>
		<td colspan="4" align="center">Data not found</td>
	</tr>
	';
}
$output .= '</table>';
echo $output;
?>