<?php
session_start();// Inicia una nueva sesión o reanuda la existente.
session_unset(); // Elimina todas las variables de sesión
session_destroy(); // Destruye la sesión

header('Location: ../index.php');
exit();
?>