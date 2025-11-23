<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/ProyectoPracticaPlanillas/public/assets/css/coordinador-estilos/panelCoord-style.css">
    <title>panel Coordinador</title>
</head>
<body>
    <?php require_once __DIR__."/../template/sidebar.php"; ?>
    
    <main>
        <?php if (!empty($_SESSION['mensaje_exito']) || !empty($_SESSION['mensaje_error'])): ?>
            <div id="modalMensaje" class="modal">
                <div class="modal-contenido">

                    <?php if (!empty($_SESSION['mensaje_exito'])): ?>
                        <i class="fa-solid fa-check icono-exito"></i>
                        <p><?= $_SESSION['mensaje_exito'] ?></p>

                    <?php else: ?>
                        <i class="fa-solid fa-xmark icono-error"></i>
                        <p><?= $_SESSION['mensaje_error'] ?></p>
                    <?php endif; ?>

                    <button id="cerrarModal">Aceptar</button>
                </div>
            </div>

            <?php 
                unset($_SESSION['mensaje_exito']);
                unset($_SESSION['mensaje_error']);
            ?>
        <?php endif; ?>
        
        <h2>Alta de Usuarios</h2>

        <div class="card card-materias">
            <div class="box-title">
                <p class="title-card">Lista de materias a asignar al docente</p>
            </div>

            <?php if (empty($nombreMateriasSeleccionadas)) : ?>
                <p class="mensaje-no-materias">No hay materias seleccionadas.</p>
            <?php else : ?>
                <ul class="lista-materias">
                    <?php foreach ($nombreMateriasSeleccionadas as $materia) : ?>
                        <li class="materia-item"><?= htmlspecialchars($materia['nombre']) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <div class="acciones">
                <a href="index.php?controller=Coordinador&action=materias" class="btn-seleccionar">
                    Seleccionar
                </a>
            </div>
        </div>


        <div class="card card-alta">
            <div class="box-title">
                <p class="title-card">Crear nuevo usuario</p>
            </div>
            <form action="index.php?controller=Coordinador&action=darAltaUsuario" method="POST" class="form-alta">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                
                <!-- Primera fila -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="userName">Usuario Inicial</label>
                        <input type="text" id="userName" name="userName" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Contraseña Inicial</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                </div>
                
                <!-- Segunda fila -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="apellido">Apellido</label>
                        <input type="text" id="apellido" name="apellido" required>
                    </div>
                </div>
                
                <!-- Tercera fila -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="materias">Cantidad de materias a asignar</label>
                        <input type="number" id="materias" name="materias" readonly value="<?= $cantidadMateriasSeleccionadas ?>">
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-alta">Dar de Alta</button>
                </div>
            </form>
        </div>


        <h2>Lista de usuarios</h2>

        <div class="card card-list-users">

        </div>

    </main>


    <script src="assets/js/panelCoord.js"></script>
</body>
</html>