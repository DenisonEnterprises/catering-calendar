<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<title>Catering Pick-Up</title>
<meta name="author" content="Denison Enterprises"/>
<meta name="Description" content="Get food."/>

<link href="style.css" rel="stylesheet"/>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<link rel="stylesheet" href="bootstrap-timepicker.min.css">
<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="dist/bootstrap-clockpicker.min.css">
<link rel="stylesheet" type="text/css" href="assets/css/github.min.css">

<?php
    include("includes/connection.php");
?>

<!--link rel="shortcut icon" href="favicon.ico"/-->

</head>

<!-- PAGE DESIGN -->

<body>

<div id="headerMine">
<h1>Catering Pick-Up</h1>
<h4>Catering Admin Portal</h4>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div id="formMine">
                <h1>Add Event:</h1><br>
                <form class="form-horizontal" name="insertForm" action="insertEvent.php" method="get">
                    <div class="form-group">
                        <label for="inputFName" class="col-md-4 control-label">Food Type</label>
                        <div class="col-lg-8">
                            <input name="food" type="text" class="form-control" id="inputText" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputLName" class="col-md-4 control-label">Location</label>
                        <div class="col-lg-8">
                            <input name="location" type="text" class="form-control" id="inputText" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputDnum" class="col-md-4 control-label">Begin Time</label>
                        <div class="col-lg-8">
                            <div class="input-group clockpicker">
                                <input type="text" name="begin" class="form-control" value="09:30" required>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputDnum" class="col-md-4 control-label">Time To Arrive</label>
                        <div class="col-lg-8">
                            <div class="input-group clockpicker">
                                <input type="text" name="end" class="form-control" value="09:30" required>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputDnum" class="col-md-4 control-label">Date</label>
                        <div class="col-lg-8">
                            <input name="date" type="text" class="form-control" id="datepicker" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputLName" class="col-md-4 control-label">Students To Attend</label>
                        <div class="col-lg-8">
                            <input name="attendance" min="0" type="number" class="form-control" id="inputText" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputDnum" class="col-md-4 control-label">Notes</label>
                        <div class="col-lg-8">
                            <textarea name="notes" class="form-control" rows="5" id="comment"></textarea>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <div class="col-lg-10 col-lg-offset-10">
                            <button name="submit" type="submit" id="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-6">
            <div id="formMine2">
                <h1>Catering Schedule:</h1>
                <hr>
                <ul class="no-bullets">
                    <?php
                        $sql_statement = "SELECT event_id, food_type, attendance, date, end_time, location, notes FROM event ORDER BY DATE(date)";
                        
                        $sth = $connect->prepare($sql_statement);
                        $sth->execute();
						$sth->bind_result($event_id, $food_type, $attendance, $date, $start_time, $location, $notes);
                        $sth->store_result();
                        
                        while ($sth->fetch()) {
                            if ( strtotime( "yesterday" ) <= strtotime($date) ) {
                                $newDate = date("l, F j", strtotime($date));
                                $newTime = date("h:i A", strtotime($start_time));
                                echo "<li><h3>$food_type</h3><h4 style=\"color:gray\">$newDate at $newTime</h4><h4>Location: $location</h4>";
                                if ( $notes )
                                    echo "<h5>Notes: $notes</h5></li>";
                                else
                                    echo "</li>";
                                echo    "<ul style=\"padding-left: 50px;\">";
                                $sql_statement2 = "SELECT p.f_name, p.l_name FROM attendee a JOIN people p ON a.d_number = p.d_number WHERE $event_id = a.event_id and a.confirmed = \"1\"";
                                $sth2 = $connect->prepare($sql_statement2);
                                $sth2->execute();
                                $sth2->bind_result($first_name, $last_name);
                                $sth2->store_result();
                                $counter = 0;
                                while ($sth2->fetch()) {
                                    echo "<li style=\"color: red\">$first_name $last_name</li>";
                                    $counter++;
                                }
                                for ($y = $attendance - $counter; $y > 0; $y--) {
                                    echo "<li>Vacant Spot</li>";
                                }
                                echo    "</ul>";
                                echo "<hr>";
                            }
                        }
                        
                        $sth->close();
                        $connect->close();
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="assets/js/jquery.min.js"></script>
<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="dist/bootstrap-clockpicker.min.js"></script>
<script type="text/javascript">
$('.clockpicker').clockpicker()
	.find('input').change(function(){
		console.log(this.value);
	});
var input = $('#single-input').clockpicker({
	placement: 'bottom',
	align: 'left',
	autoclose: true,
	'default': 'now'
});

$('.clockpicker-with-callbacks').clockpicker({
		donetext: 'Done',
		init: function() { 
			console.log("colorpicker initiated");
		},
		beforeShow: function() {
			console.log("before show");
		},
		afterShow: function() {
			console.log("after show");
		},
		beforeHide: function() {
			console.log("before hide");
		},
		afterHide: function() {
			console.log("after hide");
		},
		beforeHourSelect: function() {
			console.log("before hour selected");
		},
		afterHourSelect: function() {
			console.log("after hour selected");
		},
		beforeDone: function() {
			console.log("before done");
		},
		afterDone: function() {
			console.log("after done");
		}
	})
	.find('input').change(function(){
		console.log(this.value);
	});

// Manually toggle to the minutes view
$('#check-minutes').click(function(e){
	// Have to stop propagation here
	e.stopPropagation();
	input.clockpicker('show')
			.clockpicker('toggleView', 'minutes');
});
if (/mobile/i.test(navigator.userAgent)) {
	$('input').prop('readOnly', true);
}
</script>
<script type="text/javascript" src="bootstrap-timepicker.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-1.10.2.js"></script>
<script type="text/javascript" src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
$(function() {
    $( "#datepicker" ).datepicker();
});
</script>

</body>
</html>