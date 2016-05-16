<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<title>Catering Pick-Up</title>
<meta name="author" content="Denison Enterprises"/>
<meta name="Description" content="Get food."/>
<link href="style.css" rel="stylesheet"/>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<script type="text/javascript">setTimeout("window.close();", 2000);</script>

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
        $row3 = $connect->query("SELECT attendance, food_type, location, notes, end_time, date FROM event WHERE $event_id = event_id")->fetch_row();
        $attendance = $row3[0];
        $food_type = $row3[1];
        $location = $row3[2];
        $notes = $row3[3];
        $arrival_time = date("h:i A", strtotime($row3[4]));
        $date = date("F j", strtotime($row3[5]));
        $count = $row2[0];
        if ( $confirmed ) {
            echo "<br><div class=\"alert alert-warning\"><strong>Huh.</strong> It seems you've already confirmed your spot. Check your email for details.</div>";
        }
        else if ($count < $attendance) {
            $connect->query("UPDATE attendee SET confirmed = '1' WHERE \"$hash\" = hash");
            $row4 = $connect->query("SELECT email, f_name FROM people WHERE \"$d_number\" = d_number")->fetch_row();
            $email = $row4[0];
            $first_name = $row4[1];
            echo "<br><div class=\"alert alert-success\"><strong>Great!</strong> You've been added to the specified event in the Catering Calendar!</div>";
            // send email
            $to      = $email; // Send email to our user
            $subject = 'Catering Calendar Confirmation'; // Give the email a subject 
            $message = $first_name.',
                        
Thanks for confirming your event for the Catering Calendar! Here\'s the info you\'ll need:
        - What\'s on the menu?
            '. $food_type.'
        - Where\'s the event?
            '. $location.'
        - When should you arrive?
            On '.$date.' at '.$arrival_time.'
        - What else does catering want me to know?
            '. $notes.'
    
Thanks a ton for participating in the program!

Denison Catering
                      
'; // Our message above including the link
                                            
            $headers = 'From:confirmation@cateringcalendar.com' . "\r\n"; // Set from headers
            mail($to, $subject, $message, $headers); // Send our email
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