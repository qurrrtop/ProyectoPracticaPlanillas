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
        <div class="card card-materias">
            <h2>Mis Materias</h2>

            <?php if (empty($materiasAsignadas)) : ?>
                <p class="mensaje-no-materias">Aún no tienes materias asignadas.</p>
            <?php else : ?>
                <ul class="lista-materias">
                    <?php foreach ($materiasAsignadas as $materia) : ?>
                        <li class="materia-item"><?= htmlspecialchars($materia['nombre']) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <div class="acciones">
                <a href="index.php?controller=Coordinador&action=misMaterias" class="btn-modificar">
                    Modificar
                </a>
            </div>
        </div>

        <?php if (!empty($mensaje) && $mensaje == 'Usuario creado correctamente.'): ?>
            <div class="mensaje-exito"><?php echo $mensaje; ?></div>
        <?php elseif (!empty($mensaje) && strpos($mensaje, 'Error al crear usuario: ') === 0): ?>
            <div class="mensaje-error"><?php echo $mensaje; ?></div>
        <?php endif; ?>

        <div class="card card-alta">
            <h2>Alta de Usuarios</h2>
            <form action="index.php?controller=Coordinador&action=darAltaUsuario" method="POST" class="form-alta">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        
                <div class="form-group">
                    <label for="usuario">Usuario Inicial</label>
                    <input type="text" id="userName" name="userName" required>
                </div>
    
                <div class="form-group">
                    <label for="password">Contraseña Inicial</label>
                    <input type="password" id="password" name="password" required>
                </div>
    
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
    
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" required>
                </div>
    
                <div class="form-actions">
                    <button type="submit" class="btn-alta">Dar Alta</button>
                </div>
            </form>
        </div>
    </main>

    <br>
    <br>
    <br>
    <br>
    <br>

</body>
</html>