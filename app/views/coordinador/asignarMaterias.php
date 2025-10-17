<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/appwebplanilla/public/assets/css/coordinador-estilos/asignarMaterias-style.css">
    <title>panel Coordinador</title>
</head>
<body>
    <?php require_once __DIR__."/../template/sidebar.php"; ?>

    <main>
        <?php if (!empty($_SESSION['mensaje'])): ?>
            <?php if (strpos($_SESSION['mensaje'], 'Error') === 0): ?>
                <div class="mensaje-error"><?php echo $_SESSION['mensaje']; ?></div>
            <?php else: ?>
                <div class="mensaje-exito"><?php echo $_SESSION['mensaje']; ?></div>
            <?php endif; ?>
            <?php unset($_SESSION['mensaje']); ?>
        <?php endif; ?>

        <div class="card">
            <h2>Asignar Materias al usuario recién creado</h2>
            <form action="index.php?controller=Coordinador&action=guardarAsignacionMaterias" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="idPersona" value="<?= htmlspecialchars($idPersona) ?>">

                <div class="materias-grid">
                    <?php for ($anio = 1; $anio <= 4; $anio++): ?>
                        <div class="columna">
                            <h3><?= $anio ?>° Año</h3>
                            <div class="checkbox-grid">
                                <?php if (isset($materiasPorAnio[$anio])): ?>
                                    <?php foreach ($materiasPorAnio[$anio] as $materia): ?>
                                        <?php 
                                            $idMateria = $materia->getIdMateria();
                                            $checked = in_array($idMateria, $materiasAsignadas) ? 'checked' : '';
                                        ?>
                                        <div class="materia">
                                            <input type="checkbox" 
                                                id="materia-<?= $idMateria ?>" 
                                                name="materias[]" 
                                                value="<?= $idMateria ?>" 
                                                <?= $checked ?>>
                                            <label for="materia-<?= $idMateria ?>"><?= $materia->getNombre() ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="materia">No hay materias</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>

                <div class="botones">
                    <a href="index.php?controller=Coordinador&action=panelCoord" class="btn-volver">
                        <i class="fa-solid fa-arrow-left"></i> Volver
                    </a>
                    <button type="submit" class="btn-guardar">Guardar Cambios</button>
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