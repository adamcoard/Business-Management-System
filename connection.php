<?php

// DATABASE CONNECTION
define('HOST', 'localhost');
define('USERNAME', 'root');
define('PASSWORD', '');
define('DATABASE_NAME', 'project_calendar');

// Connect and select database
$db = new mysqli(HOST, USERNAME, PASSWORD, DATABASE_NAME);

if($db->connect_error)
{
    die("Connection failed: " . $db->connect_error);
}

?>