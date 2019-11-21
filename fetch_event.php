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

include_once 'includes/connection.php';
sec_session_start();
?>
<?php

$day = date('d', strtotime($_POST['date']));
$month = date('m', strtotime($_POST['date']));
$month_display = date('F', strtotime($_POST['date']));
$year = date('Y', strtotime($_POST['date']));
$week = date('W', strtotime($_POST['date']));
$firstday = date('w', strtotime('01-' . $month . '-' . $year));
$firstday_date = date('Y-m-d', strtotime('01-' . $month . '-' . $year));
$last_day_date = date('Y-m-d',strtotime("-1 day", strtotime("+1 month", strtotime($firstday_date))));
$days = date('t', strtotime($_POST['date']));
$dateparemeter = date('Y-m-d', strtotime($_POST['date']));
$today = date('d');
$todaymonth = date('m');
$todayyear = date('Y');

//$query = "SELECT * FROM `appo` WHERE user = '". $_SESSION['user_id'] ."'";
$query = "SELECT * FROM `appo` WHERE (`date` BETWEEN '" . $firstday_date . "' AND '" . $last_day_date . "') AND user = '". $_SESSION['user_id'] ."'";

$result = $conn->query($query);
$events = array();
if ($result !== false && $result->num_rows > 0) 
	{
		while($row = $result->fetch_assoc()) 
		{
			$events[] = $row;
		}
	}
$conn->close();



for($i=1; $i<=$firstday; $i++)
				{
				echo '<div class="date blankday"></div>';
				}
				for($i=1; $i<=$days; $i++)
				{
				echo '<div value="'.$i.'" class="date';
						if ($today == $i && $todaymonth==$month && $todayyear == $year)
						{
						echo ' today';
						}
						echo ' droppable"> <span style="font-size: 25px;color: #1A237E;padding-right: 6px;" class="date_num"><b>' . $i . '</b></span> <br>';
						foreach ($events as $one_event)
						{
						if(date('d',strtotime($one_event['date'])) == $i)
						{
							$eventtitle = $one_event['title'];
							$eventdetail = $one_event['detail'];
							$color = $one_event['color'];
							if($one_event['color'] == '#9E9E9E')
                            {
                                $fontcolor = 'black';
                            }
                            else
                                $fontcolor = 'white';
							echo '<span id='.$one_event['id'].' class="draggable appo" style="color:'.$fontcolor.';font-size: 17px;background-color: '.$one_event['color'].';">'.$one_event['title'].'
<div class="appo_info" for_event="'.$one_event['id'].'" title="'.$one_event['title'].'" style="z-index: 10;"><b>Detail : </b>
  '.$one_event['detail'].'<br><b>Start : </b>'.$one_event['time'].'<br><b>End : </b>'.$one_event['timeend'].'
</div>
						 	</span><br>';
					 }

				}
						
				echo  '</div>';
				}
				$daysleft = 7-(($days + $firstday)%7);
				if($daysleft<7)
				{
				for($i=1; $i<=$daysleft; $i++)
				{
				echo '<div class="date blankday"></div>';
				}
				}
	echo "</div>";
	echo "</div>";

?>