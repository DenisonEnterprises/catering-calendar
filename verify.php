<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<title>Catering Pick-Up</title>
<meta name="author" content="Denison Enterprises"/>
<meta name="Description" content="Get food."/>
<meta http-equiv="refresh" content="5; URL='index.php'" />
<link href="style.css" rel="stylesheet"/>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

</head>

<body>

<div id="headerMine">
<h1>Catering Pick-Up</h1><h4>Redirecting Back To Home...</h4>
</div>

<?php 
    include("includes/connection.php");
    
    $hash = $_REQUEST['hash'];
    $d_num = $_REQUEST['d_num'];
    $event_id = $_REQUEST['event'];
    
    $row = $connect->query("SELECT d_number, confirmed FROM attendee WHERE \"$hash\" = hash")->fetch_row();
    $d_number = $row[0];
    $confirmed = $row[1];
    
    if ($d_number == $d_num)
    {
        // Get total number of people signed up
        $row2 = $connect->query("SELECT COUNT(*) FROM attendee WHERE $event_id = event_id")->fetch_row();
        $row3 = $connect->query("SELECT attendance FROM event WHERE $event_id = event_id")->fetch_row();
        $attendance = $row3[0];
        $count = $row2[0];
        if ( $confirmed ) {
            echo "<br><div class=\"alert alert-warning\"><strong>Huh.</strong> It seems you've already confirmed your spot. Check your email for details.</div>";
        }
        else if ($count < $attendance) {
            echo "<br><div class=\"alert alert-success\"><strong>Great!</strong> You've been added to the specified event in the Catering Calendar!</div>";
            $connect->query("UPDATE attendee SET confirmed = '1' WHERE \"$hash\" = hash");
        }
        else {
            echo "<br><div class=\"alert alert-danger\"><strong>We apologize.</strong> It seems you waited too long to confirm your event and someone else got the spot.</div>";
        }
    }
    else
    {	
        echo "<br><div class=\"alert alert-danger\"><strong>Uh-Oh!</strong> There was an error. Please make sure that you're using the link from your email.</div>";
    }
    $connect->close();
?>