<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<title>Catering Pick-Up</title>
<meta name="author" content="Denison Enterprises"/>
<meta name="Description" content="Get food."/>
<link href="style.css" rel="stylesheet"/>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

<?php
    include("includes/connection.php");
?>

<!--link rel="shortcut icon" href="favicon.ico"/-->

</head>

<!-- PAGE DESIGN -->

<body>

<div id="headerMine">
<h1>Catering Pick-Up</h1>
<h4>Student Admin Portal</h4>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div id="formMine">
                <h1>Add Student:</h1><br>
                <form class="form-horizontal" name="insertForm" action="insertStudent.php" method="get">
                    <div class="form-group">
                        <label for="inputFName" class="col-md-4 control-label">First Name</label>
                        <div class="col-lg-8">
                            <input name="first_name" type="text" class="form-control" id="inputText" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputLName" class="col-md-4 control-label">Last Name</label>
                        <div class="col-lg-8">
                            <input name="second_name" type="text" class="form-control" id="inputText" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputDnum" class="col-md-4 control-label">D Number</label>
                        <div class="col-lg-8">
                            <input name="d_num" type="text" class="form-control" id="inputText" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputDnum" class="col-md-4 control-label">Email</label>
                        <div class="col-lg-8">
                            <input name="email" type="email" class="form-control" id="inputEmail" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputDnum" class="col-md-4 control-label">Class Year</label>
                        <div class="col-lg-8">
                            <input name="year" type="number" min="2014" class="form-control" id="inputEmail" required>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <div class="col-lg-10 col-lg-offset-10">
                            <button name="submit" type="submit" class="btn btn-primary">Submit</button>
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
                        $sql_statement = "SELECT event_id, food_type, attendance, date, end_time FROM event ORDER BY DATE(date)";
                        
                        $sth = $connect->prepare($sql_statement);
                        $sth->execute();
						$sth->bind_result($event_id, $food_type, $attendance, $date, $start_time);
                        $sth->store_result();
                        
                        while ($sth->fetch()) {
                            if ( strtotime( "now" ) <= strtotime($date) ) {
                                $newDate = date("l, F j", strtotime($date));
                                $newTime = date("h:i A", strtotime($start_time));
                                echo "<li><h3>$food_type</h3><h4 style=\"color:gray\">$newDate at $newTime</li></h4>";
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

</body>
</html>