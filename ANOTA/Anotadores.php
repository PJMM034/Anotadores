<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Cortes</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <style>
        .sidebar {
            background-color: #212529;
            min-height: 100vh;
        }
        .sidebar .nav-link {
            color: #adb5bd;
            padding: 15px 20px;
            font-size: 0.95rem;
        }
        .sidebar .nav-link:hover {
            color: #ffffff;
            background-color: rgba(255,255,255,0.1);
        }
        .brand-title {
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
            padding: 20px;
            border-bottom: 1px solid #343a40;
        }
    </style>
</head>
<body class="bg-light">

    <div class="container-fluid p-0">
        <div class="row g-0">
    
            <main class="col-md-9 col-lg-10 p-0">
                <div class="container py-4">
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h1 class="h4 mb-0 text-dark">Registros de Cortes</h1>
                        <div>
                            <button type="button" class="btn btn-outline-secondary btn-sm me-2">Cortes del día</button>
                            <button type="button" class="btn btn-outline-danger btn-sm">Cerrar Sesión</button>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <form>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold" for="producto">Producto *</label>
                                    <select class="form-select" id="producto" required>
                                        <option selected>Albahaca</option>
                                        <option value="1">Cilantro</option>
                                        <option value="2">Menta</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold" for="ubicacion">Ubicación *</label>
                                    <select class="form-select" id="ubicacion" required>
                                        <option selected>Invernadero 7</option>
                                        <option value="1">Invernadero 8</option>
                                        <option value="2">Campo Abierto 1</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold" for="cortador">Cortador *</label>
                                    <input class="form-control" type="text" id="cortador" required placeholder="Escriba el nombre del empleado">
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-semibold" for="cantidad">Cantidad *</label>
                                    <input class="form-control" type="number" id="cantidad" required placeholder="0" style="max-width: 150px;">
                                </div>

                                <button class="btn btn-primary" type="submit">Guardar Corte</button>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>