<?php
date_default_timezone_set("Asia/Dhaka");
//SET GLOBAL time_zone = '+6:00';
$con = new mysqli("localhost","radiususr","src@54321","radiusdb");
$sql_details = array(
    'user' => 'radiususr', 
    'pass' => 'src@54321',
    'db'   => 'radiusdb',
    'host' => 'localhost'
);

// Check connection
if (mysqli_connect_errno()) {
 echo "Failed to connect to MySQL: " . mysqli_connect_error();
 exit();
}




?>