<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/appwebplanilla/public/assets/css/coordinador-estilos/panelCoord-style.css">
    <title>panel Coordinador</title>
</head>
<body>
    <?php require_once __DIR__."/../template/sidebar.php"; ?>

    <main>
        <?php if (!empty($mensaje)): ?>
            <div class="<?php echo strpos($mensaje, 'Error') === 0 ? 'mensaje-error' : 'mensaje-exito'; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <div class="panel-card">
            <h1>Alta de Docente</h1>

            <form action="index.php?controller=Coordinador&action=altaDocente" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <!-- Datos del docente -->
                <div class="fila">
                    <div class="campo">
                        <label>Usuario</label>
                        <input type="text" name="userName" required>
                    </div>
                    <div class="campo">
                        <label>Nombre</label>
                        <input type="text" name="nombre" required>
                    </div>
                </div>

                <div class="fila">
                    <div class="campo">
                        <label>Apellido</label>
                        <input type="text" name="apellido" required>
                    </div>
                    <div class="campo">
                        <label>DNI</label>
                        <input type="text" name="dni" maxlength="8" required>
                    </div>
                </div>

                <div class="fila">
                    <div class="campo">
                        <label>Email</label>
                        <input type="email" name="email">
                    </div>
                    <div class="campo">
                        <label>Teléfono</label>
                        <input type="text" name="telefono">
                    </div>
                </div>

                <div class="fila">
                    <div class="campo">
                        <label>Dirección</label>
                        <input type="text" name="direccion">
                    </div>
                    <div class="campo">
                        <label>Contraseña inicial</label>
                        <input type="password" name="password" required>
                    </div>
                </div>

                <!-- Asignar materias -->
                <div class="fila-materias">
                    <label>Materias asignadas</label>
                    <?php foreach($materias as $materia): ?>
                        <div class="materia-option">
                            <input type="checkbox" name="materias[]" value="<?php echo $materia->getId(); ?>">
                            <?php echo $materia->getNombre(); ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Botones -->
                <div class="botones">
                    <a href="index.php?controller=Coordinador&action=home" class="btn-cancelar">Cancelar</a>
                    <button type="submit">Dar de alta</button>
                </div>
            </form>
        </div>
    </main>

</body>
</html>