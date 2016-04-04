<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<title>Catering Pick-Up</title>
<meta name="author" content="Denison Enterprises"/>
<meta name="Description" content="Get food."/>
<meta http-equiv="refresh" content="2; URL='catering_control.php'" />
<link href="style.css" rel="stylesheet"/>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

<!--link rel="shortcut icon" href="favicon.ico"/-->

</head>

<!-- PAGE DESIGN -->

<body>

<div id="headerMine">
<h1>Catering Pick-Up</h1><h4>Redirecting Back To Portal...</h4>
</div>

<?php 
    include("includes/connection.php");
    
    $food = $_REQUEST['food'];
    $location = $_REQUEST['location'];
    $date = $_REQUEST['date'];
    $begin_time = $_REQUEST['begin'];
    $end_time = $_REQUEST['end'];
    $notes = $_REQUEST['notes'];
    $attendance = $_REQUEST['attendance'];
    
    $newDate = date("Y-m-d", strtotime($date));
    
    $sql_statement = "INSERT INTO event (food_type, location, date, start_time, end_time, notes, attendance) VALUES (?,?,?,?,?,?,?)";
    $sth = $connect->prepare($sql_statement);
    $sth->bind_param('sssssss', $food, $location, $newDate, $begin_time, $end_time, $notes, $attendance);
    $sth->execute();
    
    if ($sth->affected_rows > 0)
    {
        echo "<br><div class=\"alert alert-success\"><strong>Great!</strong> You've added a new event to the Catering Calendar!</div>";
    }
    else
    {	
        echo "<br><div class=\"alert alert-danger\"><strong>Uh-Oh!</strong> There was a connection error. Please try again.</div>";
    }
    $sth->close();
?>