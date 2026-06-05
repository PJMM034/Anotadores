<?php
$Hostname = "localhost";
$User = "u131488081_root";
$Pass = "Rm!r3z034";
$DB = "u131488081_Anotadores";
$Connection = new mysqli($Hostname,$User,$Pass,$DB);

if ($Connection->connect_errno) {
	echo "Error";
	exit();
}
$Connection->select_db($DB);
$Connection->set_charset('utf8mb4');
?>
