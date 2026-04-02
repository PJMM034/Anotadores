<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>---LOGIN---</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</head>
<body class="hg-light">
    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-7 col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                     <form action="PHP/Login/login.php" method="post">
                          <div class="mb-3">
                            <label class="form-label" for="usuario">Usuario</label>
                            <input class="form-control" type="text" id="usuario" name="usuario" required placeholder="Introduce tu Usuario">
                          </div>
                          <div class="mb-3">
                            <label class="form-label" for="password">Contraseña</label>
                            <input class="form-control" type="password" id="password" name="password" required placeholder="Introduce tu Contraseña">
                          </div>
                          <div class="d-grid gap-2 text-center">
                             <button  class="btn btn-outline-primary" type="submit">Iniciar Sesión</button>
                          </div>
                          <p class="text-center text-secondary mt-3 mb-0 small">
                          para efecto de pruwba no se validad usuarios ni contraseñas
                        </p>
                     </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
</body>
</html>