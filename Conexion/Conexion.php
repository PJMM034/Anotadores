<?php
$Hostname = "localhost";
$User = "root";
$Pass = "Ramirez034";
$DB = "anotadores";
$Connection = new mysqli($Hostname,$User,$Pass,$DB,3306);

if ($Connection->connect_errno) {
	echo "Error";
	exit();
}
$Connection->select_db($DB);
$Connection->set_charset('utf8mb4');
?>