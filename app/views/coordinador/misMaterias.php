<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/ProyectoPracticaPlanillas/public/assets/css/coordinador-estilos/misMaterias-style.css">
    <title>panel Coordinador</title>
</head>
<body>
    <?php require_once __DIR__."/../template/sidebar.php"; ?>

    <main>
            <?php if (!empty($mensaje) && $mensaje == 'Materias actualizadas correctamente.'): ?>
                <div class="mensaje-exito"><?php echo $mensaje; ?></div>
            <?php elseif (!empty($mensaje) && strpos($mensaje, 'Error al actualizar materias: ') === 0): ?>
                <div class="mensaje-error"><?php echo $mensaje; ?></div>
            <?php endif; ?>

        <div class="card">
            <h2>Mis Materias</h2>
            <form action="index.php?controller=Coordinador&action=guardarMisMaterias" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <div class="materias-grid">
                    <?php
                    for ($anio = 1; $anio <= 4; $anio++) {
                        echo '<div class="columna">';
                        echo '<h3>' . $anio . '° Año</h3>';

                        echo '<div class="checkbox-grid">'; // Grid interno para los checkboxes
                        
                        if (isset($materiasPorAnio[$anio])) {
                            foreach ($materiasPorAnio[$anio] as $materia) {
                                $idMateria = $materia->getIdMateria(); // capturo el ID
                                $checked = in_array($idMateria, $materiasAsignadas) ? 'checked' : ''; // Paso 1: marcar si ya tiene asignada la materia
                                echo '<div class="materia">';
                                echo '<input type="checkbox" id="materia-' . $idMateria . '" name="materias[]" value="' . $idMateria . '" ' . $checked . '>';
                                echo '<label for="materia-' . $idMateria . '">' . $materia->getNombre() . '</label>';
                                echo '</div>';
                            }
                        } else {
                            echo '<div class="materia">No hay materias</div>';
                        }
                        echo '</div>'; // cierre checkbox-grid

                        echo '</div>'; // cierre columna
                    }
                    ?>
                </div>

                <div class="botones">
                    <a href="index.php?controller=Coordinador&action=panelCoord" class="btn-volver"><i class="fa-solid fa-arrow-left"></i> Volver</a>
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