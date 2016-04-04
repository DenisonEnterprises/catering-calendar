<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<title>Catering Pick-Up</title>
<meta name="author" content="Denison Enterprises"/>
<meta name="Description" content="Get food."/>
<meta http-equiv="refresh" content="2; URL='student_control.php'" />
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
    
    $first_name = $_REQUEST['first_name'];
    $last_name = $_REQUEST['second_name'];
    $d_num = $_REQUEST['d_num'];
    $email = $_REQUEST['email'];
    $class_year = $_REQUEST['year'];
    
    $sql_statement = "INSERT INTO people (f_name, l_name, d_number, email, class_year) VALUES (?,?,?,?,?)";
    $sth = $connect->prepare($sql_statement);
    $sth->bind_param('sssss', $first_name, $last_name, $d_num, $email, $class_year);
    $sth->execute();
    
    if ($sth->affected_rows > 0)
    {
        echo "<br><div class=\"alert alert-success\"><strong>Great!</strong> You've added $first_name $last_name to the Catering Calendar!</div>";
    }
    else
    {	
        echo "<br><div class=\"alert alert-danger\"><strong>Uh-Oh!</strong> There was a connection error. Please try again.</div>";
    }
    $sth->close();
?>