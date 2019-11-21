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
<html>  
    <head>  
        <title>Calendar Project | Appointment Management</title>
		<link rel="stylesheet" href="css/css.css" type="text/css">
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">  
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>  
        <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
        <link rel="stylesheet" href="js/jquery-ui.css">
        <link rel="stylesheet" href="js/bootstrap.min.css" />
		<script src="js/jquery.min.js"></script>  
		<script src="js/jquery-ui.js"></script>
		<link rel="stylesheet" href="js/colorPick.css">
		<script src="js/colorPick.js"></script>
    </head>
    <script>
    	$(document).ready(function() {
    	$("#picker1").colorPick({
			'initialColor' : '#8e44ad',
			'palette': ["#f44336", "#E91E63", "#9C27B0", "#673AB7", "#3F51B5", "#2196F3", "#03A9F4", "#00BCD4", "#009688", "#4CAF50", "#8BC34A", "#CDDC39", "#FFEB3B", "#FFC107", "#FF9800", "#FF5722", "#795548","#9E9E9E","#9E9E9E","607D8B"],
			'onColorSelected': function() {
				console.log("The user has selected the color: " + this.color)
				document.getElementsByName('color')[0].value = this.color;
				this.element.css({'backgroundColor': this.color, 'color': this.color});
			}
		});
    });
    </script>
    <style>
.picker {
      border-radius: 5px;
      width: 36px;
      z-index: 102 ;
      height: 36px;
      cursor: pointer;
      -webkit-transition: all linear .2s;
      -moz-transition: all linear .2s;
      -ms-transition: all linear .2s;
      -o-transition: all linear .2s;
      transition: all linear .2s;
      border: thin solid #eee;
    }
.picker:hover {
    transform: scale(1.1);
    z-index: 102 ;
  }
.colorPickButton{
  z-index: 102 ;
}
#colorPick{
  z-index: 102 ;
}
.table-responsive {
background-color: #fff;
  box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
}
    </style> 
    <body>  
        <div class="container">
			<br />
			
			<h3 align="center">Appointment Management</a></h3><br />
			<br />
			<div align="right" style="margin-bottom:5px;">
			<button type="button" name="add" id="add" class="btn btn-success btn-xs">Add</button>
			</div>
			<div class="table-responsive" id="user_data" style="">
				
			</div>
			<br />
			<a style="color: blue;" href="calendar.php?date=<?php echo $_GET['date'];?>"> << Calendar</a>
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
		
    </body>  
</html>  




<script>  
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
				}
			});
		
	});
	
	$('#action_alert').dialog({
		autoOpen:false
	});
	
	$(document).on('click', '.edit', function(){
		var id = $(this).attr('id');
		console.log(id);
		var action = 'fetch_single';
		$.ajax({
			url:"action.php",
			method:"POST",
			data:{id:id, action:action},
			dataType:"json",
			success:function(data)
			{
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
						$('#action_alert').html(data);
						$('#action_alert').dialog('open');
						load_data();
					}
				});
			},
			Cancel : function(){
				$(this).dialog('close');
			}
		}	
	});
	
	$(document).on('click', '.delete', function(){
		var id = $(this).attr("id");
		$('#delete_confirmation').data('id', id).dialog('open');
	});
});  
</script>