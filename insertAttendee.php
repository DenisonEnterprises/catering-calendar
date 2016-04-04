<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<title>Catering Pick-Up</title>
<meta name="author" content="Denison Enterprises"/>
<meta name="Description" content="Get food."/>
<link href="style.css" rel="stylesheet"/>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

<!--link rel="shortcut icon" href="favicon.ico"/-->

</head>

<!-- PAGE DESIGN -->

<body>

<div id="headerMine">
<h1>Catering Pick-Up</h1>
</div>

<div class="container">
    <div class="row">
        <?php
            include("includes/connection.php");
            $first_name = $_REQUEST['first_name'];
            $second_name = $_REQUEST['second_name'];
            $d_num = $_REQUEST['d_num'];
            $event_id = $_REQUEST['event'];
            $hash = md5( rand(0,1000) );
            
            $row = $connect->query("SELECT COUNT(*) FROM people WHERE \"$d_num\" = d_number")->fetch_row();
            $count = $row[0];
            if ( $count == 0 ) echo "<br><div class=\"alert alert-danger\"><strong>Uh-Oh!</strong> You need to become an accepted Catering Pick-Up Member.</div>";
            
            if ( $count > 0 ) {
                $row = $connect->query("SELECT COUNT(*) FROM attendee WHERE \"$d_num\" = d_number and $event_id = event_id")->fetch_row();
                $count2 = $row[0];
                if ( $count2 != 0 ) {
                    echo "<br><div class=\"alert alert-danger\"><strong>Uh-Oh!</strong> You cannot sign-up for the same meal twice.</div>";
                }
                else {
                    $sql_statement = "INSERT INTO attendee (event_id, d_number, hash) VALUES (?,?,?)";
                    $sth = $connect->prepare($sql_statement);
                    $sth->bind_param('sss', $event_id, $d_num, $hash);
                    $sth->execute();

                    if ($sth->affected_rows > 0)
                    {
                        echo "<br><div class=\"alert alert-success\"><strong>Great!</strong> We've sent you an email, $first_name. Click on the link in that email to confirm your catering reservation.</div>";
                        
                        //query for email
                        $row2 = $connect->query("SELECT email FROM people WHERE \"$d_num\" = d_number")->fetch_row();
                        $email = $row2[0];
                        
                        if ( $count == 0 ) echo "<br><div class=\"alert alert-danger\"><strong>Uh-Oh!</strong> You need to become an accepted Catering Pick-Up Member.</div>";
                        
                        // send email
                        $to      = $email; // Send email to our user
                        $subject = 'Catering Calendar Confirmation'; // Give the email a subject 
                        $message = 'Thanks '.$first_name.' for signing up for the Catering Calendar!
                        
Please click this link to confirm your intention to attend the event.
http://127.0.0.1/verify.php?hash='.$hash.'&d_num='.$d_num.'&event='.$event_id.'
                        
'; // Our message above including the link
                                            
                        $headers = 'From:noreply@cateringcalendar.com' . "\r\n"; // Set from headers
                        mail($to, $subject, $message, $headers); // Send our email
                    }
                    else
                    {	
                        echo "<br><div class=\"alert alert-danger\"><strong>Uh-Oh!</strong> There was a connection error. Please try again.</div>";
                    }
                    $sth->close();
                }
            }
        ?>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div id="formMine">
                <h1>Sign-Up:</h1><br>
                <form class="form-horizontal" name="insertForm" action="insert.php" method="get">
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
                        <label for="inputFName" class="col-lg-4 control-label">Event</label>
                        <div class="col-lg-8">
                            <select name="event" class="form-control" id="sel1" required>
                            <?php
                                $sql_statement3 = "SELECT event_id, food_type, attendance, date, end_time FROM event ORDER BY DATE(date)";
                                
                                $sth3 = $connect->prepare($sql_statement3);
                                $sth3->execute();
                                $sth3->bind_result($event_id, $food_type, $attendance, $date, $start_time);
                                $sth3->store_result();
                                
                                while ($sth3->fetch()) {
                                    if ( strtotime( "now" ) <= strtotime($date) ) {
                                        $newDate = date("F j", strtotime($date));
                                        $result = $connect->query("SELECT COUNT(*) FROM attendee WHERE $event_id = event_id");
                                        $row = $result->fetch_row();
                                        $count = $row[0];
                                        if ( $count < $attendance ) {
                                            echo "<option value=\"$event_id\">$food_type on $newDate";
                                        }
                                    }
                                }
                                $sth3->close();
                            ?>
                            </select>
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
                                $result = $connect->query("SELECT COUNT(*) FROM attendee WHERE $event_id = event_id and confirmed = \"1\"");
                                $row = $result->fetch_row();
                                $count = $row[0];
                                for ($x = $count; $x > 0; $x--) {
                                    echo "<li style=\"color: red;\">Spot Taken</li>";
                                }
                                for ($y = $attendance - $count; $y > 0; $y--) {
                                    echo "<li>Vacant Spot</li>";;
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