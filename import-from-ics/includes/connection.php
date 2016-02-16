<?php
	$host="localhost";
	$uname="*********";
	$pass="**********";
	$database = "************";	

	$connection=mysql_connect($host,$uname,$pass) 
	or die("Database Connection Failed");
	$selectdb=mysql_select_db($database) or die("Database could not be selected");	
	$result=mysql_select_db($database)
	or die("database cannot be selected <br>");
	
	session_start();
?>