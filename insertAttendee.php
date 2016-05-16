<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<title>Catering Pick-Up</title>
<meta name="author" content="Denison Enterprises"/>
<meta name="Description" content="Get food."/>
<meta http-equiv="refresh" content="2; URL='index.php'" />
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
                        $message = $first_name.',
                        
Thanks for signing up for the Catering Calendar! There\'s only one more step before you\'re confirmed for the event!
                        
Please click this link to confirm your intention to attend the event.
http://127.0.0.1/verify.php?hash='.$hash.'&d_num='.$d_num.'&event='.$event_id.'

Thanks a ton!

Denison Catering
                      
'; // Our message above including the link
                                            
                        $headers = 'From:confirmation@cateringcalendar.com' . "\r\n"; // Set from headers
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