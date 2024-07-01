<?php
// Start Session
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "novelnest";

if(!$con = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname))
{

	die("failed to connect!");
}

