<?php
$Hostname = "localhost";
$User = "root";//victorteran
$Pass = "mysql";
$DB = "anotadores";
$Connection = new mysqli($Hostname,$User,$Pass,$DB);

if ($Connection->connect_errno) {
	echo "Error";
	exit();
}
$Connection->select_db($DB);
$Connection->set_charset('utf8mb4');
?>