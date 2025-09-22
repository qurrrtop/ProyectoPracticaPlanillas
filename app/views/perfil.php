<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/appwebplanilla/public/assets/css/perfil-style.css">
    <title>Mi perfil</title>
</head>
<body>
    <?php require_once __DIR__."/../views/template/sidebar.php"; ?>

    <main>
        <?php if (!empty($mensaje) && $mensaje == 'Datos actualizados correctamente.'): ?>
            <div class="mensaje-exito"><?php echo $mensaje; ?></div>
        <?php elseif (!empty($mensaje) && strpos($mensaje, 'Error al actualizar datos:') === 0): ?>
            <div class="mensaje-error"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        <div class="perfil-card">
        <h1>Mi Perfil</h1>

        <form action="index.php?controller=Usuario&action=perfil" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <!-- Datos NO editables -->
            <div class="fila">
                <div class="campo">
                    <label>ID Usuario</label>
                    <input type="text" name="idUsuario" value="<?php echo $usuarioDatos->getIdUsuario(); ?>" readonly>
                </div>
                <div class="campo">
                    <label>Rol</label>
                    <input type="text" name="rol" value="<?php echo $usuarioDatos->getRol(); ?>" readonly>
                </div>
            </div>

            <!-- Datos editables -->
            <div class="fila">
                <div class="campo">
                    <label>Usuario</label>
                    <input type="text" name="userName" value="<?php echo $usuarioDatos->getUserName(); ?>">
                </div>
                <div class="campo">
                    <label>Nombre</label>
                    <input type="text" name="nombre" value="<?php echo $usuarioDatos->getNombre(); ?>">
                </div>
            </div>

            <div class="fila">
                <div class="campo">
                    <label>Apellido</label>
                    <input type="text" name="apellido" value="<?php echo $usuarioDatos->getApellido(); ?>">
                </div>
                <div class="campo">
                    <label>DNI</label>
                    <input type="text" name="dni" value="<?php echo $usuarioDatos->getDni(); ?>">
                </div>
            </div>

            <div class="fila">
                <div class="campo">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo $usuarioDatos->getEmail(); ?>">
                </div>
                <div class="campo">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" value="<?php echo $usuarioDatos->getTelefono(); ?>">
                </div>
            </div>

            <div class="fila">
                <div class="campo">
                    <label>Dirección</label>
                    <input type="text" name="direccion" value="<?php echo $usuarioDatos->getDireccion(); ?>">
                </div>
                <div class="campo">
                    <label>Fecha de nacimiento</label>
                    <input type="date" name="fnacimiento" value="<?php echo $usuarioDatos->getFnacimiento(); ?>">
                </div>
            </div>

            <!-- Sección de contraseña -->
            <h2>Cambiar contraseña</h2>
            <div class="fila">
                <div class="campo">
                    <label>Contraseña actual</label>
                    <input type="password" name="password_actual" placeholder="********">
                </div>
                <div class="campo">
                    <label>Nueva contraseña</label>
                    <input type="password" name="password_nuevo" placeholder="Nueva contraseña">
                </div>
            </div>
            <div class="fila">
                <div class="campo">
                    <label>Confirmar contraseña</label>
                    <input type="password" name="password_nuevo_confirm" placeholder="Repite la nueva contraseña">
                </div>
                <div class="campo"></div> <!-- Para mantener simetría -->
            </div>

            <!-- Botones -->
            <div class="botones">
                <a href="index.php?controller=Coordinador&action=home" class="btn-cancelar">Cancelar</a>
                <button type="submit">Guardar cambios</button>
            </div>
        </form>
    </div>
    </main>

    <br>
    <br>
    <br>
    <br>

    
</body>
</html>