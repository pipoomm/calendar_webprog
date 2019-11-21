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
<html>
	<head>
		<title>Calendar Project</title>
		<link rel="stylesheet" href="css/css.css" type="text/css">
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<link href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">  
        <script src="https://code.jquery.com/jquery-1.12.3.js"></script>  
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <link rel="stylesheet" href="js/bootstrap.min.css" />
		<link rel="stylesheet" href="js/colorPick.css">		
		<script src="js/colorPick.js"></script>
		

	</head>
	<script type='text/javascript'>
	window.onload = function() {
    document.getElementById('delete').onclick = function() {myFunction()};

    function myFunction() {
        Swal.fire({
  text: 'Do you want to logout',
  type: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Yes, logout!'
}).then((result) => {
  if (result.value) {
    Swal.fire({
  type: 'success',
  text: 'Logout successful !',
}).then(function() {
   		window.location = 'includes/logout.php';
	})
  }
})
    }
};

			

$(document).ready(function() {
			$(".appo").click(function(){
		    	
		    	var my_id = $(this).attr('id');
		    	$(".appo_info[for_event='" + my_id + "']").dialog('open');
		    });

		    $( ".appo_info" ).dialog({
		    	modal: true,
		    	autoOpen: false,
		    	position: { at: "center top" }
		    });
    $(".draggable").draggable();
    $(".date.droppable").droppable({
      drop: function(event,ui) 
      { var dropped_id = ui.draggable.attr('id');
      	var drop = $(this).children(".date_num").text();

      	var month = $("#month_val").val();
      	var year = $("#year_val").val();
      	var to_send = year + "-" + month + "-" + drop;
          //  console.log(event.clientX);
    	 // console.log(event.clientY);

      	//console.log("item: " + dropped_id + " placed on --> " + to_send);
      	$.ajax({
					url:"ajax_upload.php",
					method:"POST",
					data:{date:to_send,event:dropped_id},
					success:function(data)
					{
					 if(data){
					 	document.getElementById(ui.draggable.attr('id')).style.position = "relative";
					 	document.getElementById(ui.draggable.attr('id')).style.left="0px";
					 }else{
					 	alert('fail');
					 }
					}
				});
      }
    });

  });

$(document).ready(function(){  

	load_data();
    
	function load_data()
	{
		$.ajax({
			url:"fetch.php",
			method:"POST",
			success:function(data)
			{
				$('#user_data').html(data);
			}
		});
	}
	
	$("#user_dialog").dialog({
		autoOpen:false,
		width:400
	});
	
	$('#add').click(function(){
		$('#user_dialog').attr('title', 'Add Data');
		$('#action').val('insert');
		$('#form_action').val('Insert');
		$('#user_form')[0].reset();
		$('#form_action').attr('disabled', false);
		$("#user_dialog").dialog('open');
	});
	
	$('#user_form').on('submit', function(event){
		event.preventDefault();
		var error_first_name = '';
		var error_last_name = '';
		
			$('#form_action').attr('disabled', 'disabled');
			var form_data = $(this).serialize();
			$.ajax({
				url:"action.php",
				method:"POST",
				data:form_data,
				success:function(data)
				{
					$('#user_dialog').dialog('close');
					$('#action_alert').html(data);
					$('#action_alert').dialog('open');
					load_data();
					$('#form_action').attr('disabled', false);
					location.reload();
				}
			});
		
	});
	
	$('#action_alert').dialog({
		autoOpen:false
	});
	
	$(document).on('click', '.edit', function(){
		var id = $(this).attr('id');
		var action = 'fetch_single';
		$.ajax({
			url:"action.php",
			method:"POST",
			data:{id:id, action:action},
			dataType:"json",
			success:function(data)
			{
				$('#title').val(data.title);
				$('#title').val(data.title);
				$('#detail').val(data.detail);
				$('#date').val(data.date);
				$('#color').val(data.color);
				$('#status').val(data.status);
				$('#user_dialog').attr('title', 'Edit Data');
				$('#action').val('update');
				$('#hidden_id').val(id);
				$('#form_action').val('Update');
				$('#user_dialog').dialog('open');
			}
		});
	});


	$("#picker1").colorPick({
			'initialColor' : '#03A9F4',
			'palette': ["#f44336", "#E91E63", "#9C27B0", "#673AB7", "#3F51B5", "#2196F3", "#03A9F4", "#00BCD4", "#009688", "#4CAF50", "#8BC34A", "#CDDC39", "#FFEB3B", "#FFC107", "#FF9800", "#FF5722", "#795548","#9E9E9E","#9E9E9E","607D8B"],
			'onColorSelected': function() {
				//console.log("The user has selected the color: " + this.color)
				document.getElementsByName('color')[0].value = this.color;
				this.element.css({'backgroundColor': this.color, 'color': this.color});
			}
		});
});  
</script>
	<body>
		<div class="container">
		<?php if (login_check($mysqli) == true) : ?>
		
		<form class="indexbox" action="calendar.php" method="get">
			<div class="">
				<div class="">
					<label for="date">Date</label>
					<input type="date" name="date" value="<?php echo $_GET['date'];?>">
					<button class="btncal btnsubmit">Goto</button>
					<button type="button" name="add" id="add" class="btncal btnadd">+ Event</button>
				</div>
			</form>
			
			
			<?php
			echo '<a class="nrm_btn" href="?date=' . date("Y-m-d", strtotime("+1 month", strtotime($_GET['date']))) . '"> >> </a>';
			echo '<a class="nrm_btn" href="?date=' . date("Y-m-d", strtotime("-1 month", strtotime($_GET['date']))) . '"> << </a>';
			?>
			<a class="nrm_btn" href="list2.php?date=<?php echo $_GET['date']; ?>">Appointment Manager</a>
			<!--<a class="addevent" href="addevent.php"> Add Event</a>-->
			

			<div>
				<?php
					$day = date('d', strtotime($_GET['date']));
					$month = date('m', strtotime($_GET['date']));
					$month_display = date('F', strtotime($_GET['date']));
					$year = date('Y', strtotime($_GET['date']));
					$week = date('W', strtotime($_GET['date']));
					$firstday = date('w', strtotime('01-' . $month . '-' . $year));
					$firstday_date = date('Y-m-d', strtotime('01-' . $month . '-' . $year));
					$last_day_date = date('Y-m-d',strtotime("-1 day", strtotime("+1 month", strtotime($firstday_date))));
					$days = date('t', strtotime($_GET['date']));
					$dateparemeter = date('Y-m-d', strtotime($_GET['date']));
				echo '<a class="nrm_btn" href="dayview.php?date=' . $_GET['date'] . '">DAY</a>';
				?>
				<a class="nrm_btn" href="weekview.php?week=<?php echo $week;?>&year=<?php echo $year;?>&date=<?php echo $dateparemeter; ?>">WEEK</a> <!--Previous week-->
				<?php
					if(isset($_GET['title']))
					{
						$title = $_GET['title'];
					}
					$today = date('d');
					$todaymonth = date('m');
					$todayyear = date('Y');
					if(isset($_GET['detail']))
					{
						$detail = $_GET['detail'];
					}
					date_default_timezone_set("Asia/Bangkok");
					$time = "";
					$color = "";
				echo '<input type="hidden" id="month_val" value="' . $month . '">';
				echo '<input type="hidden" id="year_val" value="' . $year . '">';
				echo '<div style="color: #001270;  font-size: 19px;  padding: 14px;">Welcome ' .htmlentities($_SESSION['username']). ' ! | <b>'.$month_display.', '.$year.'</b> <a class="btn btn-success btn-sm" href="calendar.php?date='.$todayyear.'-'.$todaymonth.'-'.$today.'" id="todayjump">Today</a> <a class="btn btn-danger btn-sm" href="#" id="delete">Logout</a></div>';
					$sql = "SELECT * FROM `appo` WHERE (`date` BETWEEN '" . $firstday_date . "' AND '" . $last_day_date . "') AND user = '". $_SESSION['user_id'] ."' OR status='Public'";
					$result = $conn->query($sql);

					$events = array();
					if ($result !== false && $result->num_rows > 0) 
					{
					// output data of each row
						while($row = $result->fetch_assoc()) {
				//echo "id: " . $row["id"]. " - Title: " . $row["title"]. " " . $row["detail"]. "<br>";
					$events[] = $row;
					}
				//print_r($events);
					} else {
					//echo "0 results";
					//echo $sql;
					}
					$conn->close();
				?>
			</div><br>
			<div class="calendar">
				<div class="days1">Sunday</div>
				<div class="days2">Monday</div>
				<div class="days3">Tuesday</div>
				<div class="days4">Wednesday</div>
				<div class="days5">Thursday</div>
				<div class="days6">Friday</div>
				<div class="days7">Saturday</div>
				<?php
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
							//echo '<span id=" "'.$one_event['title'].<br>"';
							echo '<span id='.$one_event['id'].' class="draggable appo" style="color:'.$fontcolor.';font-size: 17px;background-color: '.$one_event['color'].';">'.$one_event['title'].'
<div class="appo_info" for_event="'.$one_event['id'].'" title="'.$one_event['title'].'" style="z-index: 10;"><b>Detail : </b>
  '.$one_event['detail'].'<br><b>User : </b>'.$one_event['username'].'<br><b>Start : </b>'.$one_event['time'].'<br><b>End : </b>'.$one_event['timeend'].'
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
				?>
			</div>

</div>
			<div id="user_dialog" title="Add Event">
			<form method="post" id="user_form">
				<div class="form-check form-check-inline">
				  <input class="form-check-input" type="radio" name="status" id="Private" value="Private" checked>
				  <label class="form-check-label" >Private Event</label>
				</div>
				<div class="form-check form-check-inline">
				  <input class="form-check-input" type="radio" name="status" id="Public" value="Public">
				  <label class="form-check-label">Public Event</label>
				</div>
				<div class="form-group">
					<label>Title</label>
					<input type="text" name="title" id="title" class="form-control" />
					<span id="error_first_name" class="text-danger"></span>
				</div>
				<div class="form-group">
					<label>Detail</label>
					<input type="text" name="detail" id="detail" class="form-control" />
					<span id="error_last_name" class="text-danger"></span>
				</div>
				<div class="form-group">
					<label>Date</label>
					<input type="date" name="date" id="date" class="form-control" />
					<span id="error_last_name" class="text-danger"></span>
				</div>
				<div class="form-group">
					<label>Start</label>
					<select name="time">
          <option value="000000">12:00 AM</option>
          <option value="010000">1:00 AM</option>
          <option value="020000">2:00 AM</option>
          <option value="030000">3:00 AM</option>
          <option value="040000">4:00 AM</option>
          <option value="050000">5:00 AM</option>
          <option value="060000">6:00 AM</option>
          <option value="070000">7:00 AM</option>
          <option value="080000">8:00 AM</option>
          <option value="090000">9:00 AM</option>
          <option value="100000">10:00 AM</option>
          <option value="110000">11:00 AM</option>
          <option value="120000">12:00 PM</option>
          <option value="130000">1:00 PM</option>
          <option value="140000">2:00 PM</option>
          <option value="150000">3:00 PM</option>
          <option value="160000">4:00 PM</option>
          <option value="170000">5:00 PM</option>
          <option value="180000">6:00 PM</option>
          <option value="190000">7:00 PM</option>
          <option value="200000">8:00 PM</option>
          <option value="210000">9:00 PM</option>
          <option value="220000">10:00 PM</option>
          <option value="230000">11:00 PM</option>
        </select>
				</div>
				<div class="form-group">
					<label>End</label>
					<select name="timeend">
          <option value="000000">12:00 AM</option>
          <option value="010000">1:00 AM</option>
          <option value="020000">2:00 AM</option>
          <option value="030000">3:00 AM</option>
          <option value="040000">4:00 AM</option>
          <option value="050000">5:00 AM</option>
          <option value="060000">6:00 AM</option>
          <option value="070000">7:00 AM</option>
          <option value="080000">8:00 AM</option>
          <option value="090000">9:00 AM</option>
          <option value="100000">10:00 AM</option>
          <option value="110000">11:00 AM</option>
          <option value="120000">12:00 PM</option>
          <option value="130000">1:00 PM</option>
          <option value="140000">2:00 PM</option>
          <option value="150000">3:00 PM</option>
          <option value="160000">4:00 PM</option>
          <option value="170000">5:00 PM</option>
          <option value="180000">6:00 PM</option>
          <option value="190000">7:00 PM</option>
          <option value="200000">8:00 PM</option>
          <option value="210000">9:00 PM</option>
          <option value="220000">10:00 PM</option>
          <option value="230000">11:00 PM</option>
        </select>
				</div>
				<div class="form-group">
					<label>Color</label>
					<div class="picker" id="picker1" data-initialcolor="#3998DB"></div>
					<!--<input type="color" name="color" id="color" class="form-control" />-->
					<input type="hidden" name="color" id="color"/>
					<span id="error_last_name" class="text-danger"></span>
				</div>
				<div class="form-group">
					<input type="hidden" name="action" id="action" value="insert" />
					<input type="hidden" name="hidden_id" id="hidden_id" />
					<input type="submit" name="form_action" id="form_action" class="btn btn-info" value="Insert" />
				</div>
			</form>
		</div>
		</body>
	</html>
	<?php else : 
		echo "<script>window.location = 'loginrequest.php';</script>";
    ?>
	<?php endif; ?>
</body>
</html>