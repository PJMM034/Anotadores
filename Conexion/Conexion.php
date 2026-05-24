<?php
$Hostname = "localhost";
$User = "root";
$Pass = "Ramirez034";
$DB = "Anotadores";
$Connection = new mysqli($Hostname,$User,$Pass,$DB);

if ($Connection->connect_errno) {
	echo "Error";
	exit();
}
$Connection->select_db($DB);
$Connection->set_charset('utf8mb4');
?>