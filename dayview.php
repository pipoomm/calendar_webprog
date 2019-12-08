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
if(!isset($_GET['date']))
{
  $_GET['date'] = date("Y-m-d");
}
$dateparemeter = date('Y-m-d', strtotime($_GET['date']));
$week = date('W', strtotime($_GET['date']));
$year = date('Y', strtotime($_GET['date']));
?>
<html>
	<head>
		<title>Calendar Project | Day</title>
		<link rel="stylesheet" href="css/css.css" type="text/css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">  
    <script src="https://code.jquery.com/jquery-1.12.3.js"></script>  
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="js/bootstrap.min.css" />
    <link rel="stylesheet" href="js/colorPick.css">   
    <script src="js/colorPick.js"></script>

    <?php if (login_check($mysqli) == true) : ?>
		<?php 
		if (!isset($_GET['date'])) {
			$day = date('Y-m-d', strtotime($_GET['date']));
		}
		else
			$day = $_GET['date'];

		$get_appo = "SELECT * FROM `appo` WHERE (`date` = '$day') AND user = '". $_SESSION['user_id'] ."' OR (`status`='Public' AND `date` = '$day')";
		$run_appo = mysqli_query($conn, $get_appo);
		?>
	</head>
	<script>
window.onload = function() {
    document.getElementById('delete').onclick = function() {
        myFunction()
    };

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
    $(".appo").click(function() {

        var my_id = $(this).attr('id');
        $(".appo_info[for_event='" + my_id + "']").dialog('open');
    });

    $( ".appo_info" ).dialog({
          buttons: {
          'Edit': function(e) {
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
            },
            'Delete': function(e) {
            var id = $(this).attr("id");
          $('#delete_confirmation').data('id', id).dialog('open');
            }
        },
          modal: true,
          autoOpen: false,
          position: { at: "center top" }
        });


    $('#delete_confirmation').dialog({
    autoOpen:false,
    modal: true,
    buttons:{
      Ok : function(){
        var id = $(this).data('id');
        var action = 'delete';
        $.ajax({
          url:"action.php",
          method:"POST",
          data:{id:id, action:action},
          success:function(data)
          {
            $('#delete_confirmation').dialog('close');
            //$('#action_alert').html(data);
            //$('#action_alert').dialog('open');
            //load_data();
            $('.appo_info').dialog('close');
            location.reload();
          }
        });
      },
      Cancel : function(){
        $(this).dialog('close');
      }
    } 
  });


    $(".draggable").draggable();
    $(".droppable").droppable({
        drop: function(event, ui) {
            //console.log("placed on --> " + $(this).children(".date_num").text());
            var date = $("#date_val").val();
            var dropped_id = ui.draggable.attr('id');
            var drop = $(this).children(".date_num").text();
            var to_send = "0" + drop + ":00";
            console.log(date);
            $.ajax({
                url: "ajax_upload_day.php",
                method: "POST",
                data: {
                    time: to_send,
                    id: dropped_id,
                    date: date
                },
                success: function(data) {
                    if (data) {
                    	document.getElementById(ui.draggable.attr('id')).style.position = "relative";
                    	document.getElementById(ui.draggable.attr('id')).style.left = "0px";
                        //location.reload();
                    } else {
                        alert('Fail to connect database');
                    }
                }
            });
        }
    });

});
$(document).ready(function() {

    load_data();

    function load_data() {
        $.ajax({
            url: "fetch.php",
            method: "POST",
            success: function(data) {
                $('#user_data').html(data);
            }
        });
    }

    $("#user_dialog").dialog({
        autoOpen: false,
        width: 400
    });

    $('#add').click(function() {
        $('#user_dialog').attr('title', 'Add Data');
        $('#action').val('insert');
        $('#form_action').val('Insert');
        $('#user_form')[0].reset();
        $('#form_action').attr('disabled', false);
        $("#user_dialog").dialog('open');
    });

    $('#user_form').on('submit', function(event) {
        event.preventDefault();
        var error_first_name = '';
        var error_last_name = '';

        $('#form_action').attr('disabled', 'disabled');
        var form_data = $(this).serialize();
        $.ajax({
            url: "action.php",
            method: "POST",
            data: form_data,
            success: function(data) {
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
        autoOpen: false
    });

    $(document).on('click', '.edit', function() {
        var id = $(this).attr('id');
        var action = 'fetch_single';
        $.ajax({
            url: "action.php",
            method: "POST",
            data: {
                id: id,
                action: action
            },
            dataType: "json",
            success: function(data) {
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
        'initialColor': '#03A9F4',
        'palette': ["#f44336", "#E91E63", "#9C27B0", "#673AB7", "#3F51B5", "#2196F3", "#03A9F4", "#00BCD4", "#009688", "#4CAF50", "#8BC34A", "#CDDC39", "#FFEB3B", "#FFC107", "#FF9800", "#FF5722", "#795548", "#9E9E9E", "#9E9E9E", "607D8B"],
        'onColorSelected': function() {
            console.log("The user has selected the color: " + this.color)
            document.getElementsByName('color')[0].value = this.color;
            this.element.css({
                'backgroundColor': this.color,
                'color': this.color
            });
        }
    });
});
	</script>
	<body>
		<div class="container">
		<form class="indexbox" action="dayview.php" method="get">
			<div class="row">
				<div class="">
					<label for="date">Date</label>
					<input type="date" name="date" value="<?php echo $_GET['date'];?>">
					<button class="btncal btnsubmit">Goto</button>
					<button type="button" name="add" id="add" class="btnadd">+ Event</button>
					<?php
					echo '<a class="nrm_btn" href="?date=' . date("Y-m-d", strtotime("+1 day", strtotime($_GET['date']))) . '"> >> </a>';
					
					echo '<a class="nrm_btn" href="?date=' . date("Y-m-d", strtotime("-1 day", strtotime($_GET['date']))) . '"> << </a>'; ?>
					<a class="nrm_btn" href="weekview.php?week=<?php echo $week;?>&year=<?php echo $year;?>&date=<?php echo $dateparemeter; ?>">WEEK</a>
			<?php	
					echo '<a class="nrm_btn" href="calendar.php?date=' . date("Y-m-d", strtotime($_GET['date'])) . '"> MONTH </a>';
					
					$day = date('l', strtotime($_GET['date']));
					$day_digit = date('d', strtotime($_GET['date']));
					$month = date('M', strtotime($_GET['date']));
					$year = date('Y', strtotime($_GET['date']));
					$firstday = date('w', strtotime('01-' . $month . '-' . $year));
					$days = date('t', strtotime($_GET['date']));
					$today = date('d');
					$todaymonth = date('m');
					$todayyear = date('Y');
					echo '<input type="hidden" id="month_val" value="' . $month . '">';
					echo '<input type="hidden" id="year_val" value="' . $year . '">';
					echo '<div style="color: #001270;  font-size: 19px;  padding: 14px;">Welcome ' .htmlentities($_SESSION['username']). ' ! | <b>'.$day. ' '.$day_digit. ' ' .$month.', '.$year.'</b> <a class="btn btn-success btn-sm" href="dayview.php?date='.$todayyear.'-'.$todaymonth.'-'.$today.'" id="todayjump">Today</a> <a class="btn btn-danger btn-sm" href="#" id="delete">Logout</a></div>';
					//echo "<center><h3>Day: ".$day. " Month: " .$month." Year: ".$year."</h3></center>";
				?>

				</div>
			</div>
			</form>
				
			
			<?php

					echo '<input type="hidden" id="date_val" value="' .$_GET['date']. '">';

			for($i=0; $i<24; $i++)
				{
					echo "<div class='time_day'>";
					echo '<div class="droppable dayview"><span class="date_num">' . $i .':00</span><br>';
				if (mysqli_num_rows($run_appo) != 0) 
				{
					while($row_appo = mysqli_fetch_array($run_appo))
					{
						$events[] = $row_appo;
					}
					foreach ($events as $one_event)
					{
					if($i == $one_event['time'])
						{
							if($one_event['color'] == '#9E9E9E')
                            {
                                $fontcolor = 'black';
                            }
                            else
                                $fontcolor = 'white';
						 	echo '<span id='.$one_event['id'].' class="draggable appo" style="color:'.$fontcolor.';font-size: 17px;background-color: '.$one_event['color'].';">'.$one_event['title'].'
<div class="appo_info" id='.$one_event['id'].' for_event="'.$one_event['id'].'" title="'.$one_event['title'].'" style="z-index: 10;"><b>Detail : </b>
  '.$one_event['detail'].'<br><b>User : </b>'.$one_event['username'].'<br><b>Start : </b>'.$one_event['time'].'<br><b>End : </b>'.$one_event['timeend'].'
</div>
						 	</span><br>';
						}
					else
						{
							echo "";
						}
					}
					echo "</div><br>";
				}
				else
					echo "</div><br>";
				}
				?>
					
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
					<input type="date" name="date" id="date" class="form-control" value="<?php echo $_GET['date'];?>" />
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
					<!--<input type="color" name="color" id="color" class="form-control" />-->
					<div class="picker" id="picker1" data-initialcolor="#3998DB"></div>
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
		
		<div id="action_alert" title="Action">
		</div>
    <div id="delete_confirmation" title="Confirmation">
    <p>Are you sure you want to Delete this data?</p>
    </div>
    <?php else : 
    echo "<script>window.location = 'loginrequest.php';</script>";
    ?>
  <?php endif; ?>
	</body>
</html>