<?php
// Obtiene el valor del parámetro 'error' de la URL, o asigna una cadena vacía si no está presente.
$error = $_GET['error'] ?? '';
if  ($error === '1') {
    // Si el error es '1', muestra un mensaje de alerta indicando que el usuario o la contraseña son incorrectos.
    echo '<div class="alert alert-danger text-center" role="alert">
    Usuario o contraseña incorrectos.</div>';
} if ($error === '2') {
    echo '<div class="alert alert-warning text-center" role="alert">
    Error al iniciar sesión.</div>';
} if ($error === '3') {
    echo '<div class="alert alert-warning text-center" role="alert">
    Error, no tiene permisos.</div>';
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>---LOGIN---</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="CSS/login.css">
</head>
<body class="hg-light">
    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-7 col-lg-5">
                <div class="card contenedorlogin border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                     <form action="logins/login.php" method="post">
                        <div class="d-flex justify-content-center ">
                            <img src="imagenes/AgroBitacora-logo.png" alt="AgroBitacora" style="height: 150px;"></img>
                        </div>
                          <div class="mb-3">
                         
                            <label class="form-label" for="usuario">Usuario</label>
                            <input class="form-control" type="text" id="usuario" name="usuario" required placeholder="Introduce tu Usuario">
                          </div>
                          <div class="mb-3">
                            <label class="form-label" for="password">Contraseña</label>
                            <input class="form-control" type="password" id="password" name="password" required placeholder="Introduce tu Contraseña">
                          </div>
                          <div class="d-grid gap-2 text-center">
                             <button  class="btn btn-login" type="submit">Iniciar Sesión</button>
                          </div>
                     </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
</body>
</html>