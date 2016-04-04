<?php
	$host = "localhost";
	$user = "root";
	$pw = "";
	$db = "test";	

    $connect = new mysqli($host, $user, $pw, $db);
    
    if (mysqli_connect_errno()) {
        printf("The connection failed...you should try again.\n");
        exit();
    }

?>