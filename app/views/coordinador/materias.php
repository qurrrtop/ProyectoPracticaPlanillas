<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/ProyectoPracticaPlanillas/public/assets/css/coordinador-estilos/materias-style.css">
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

        <div class="color-ayuda">
            <span>Materia ya asignada a un docente.</span>
        </div>

        <div class="card">
            <h2>Lista de materias</h2>
            <form action="index.php?controller=Coordinador&action=seleccionarMaterias" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <div class="materias-grid">
                    <?php
                    for ($anio = 1; $anio <= 4; $anio++) {
                        echo '<div class="columna">';
                        echo '<h3>' . $anio . '° Año</h3>';

                        echo '<div class="checkbox-grid">'; // Grid interno para los checkboxes
                        
                        if (isset($materiasPorAnio[$anio])) {
                            foreach ($materiasPorAnio[$anio] as $materia) {
                                // ID
                                $idMateria = is_array($materia) ? $materia['idMateria'] : $materia->getIdMateria();
                                $nombreMateria = is_array($materia) ? $materia['nombre'] : $materia->getNombre();

                                // Si la materia está ocupada por un docente
                                $esOcupada = in_array($idMateria, $materiasOcupadas);

                                // Checkbox deshabilitado si está ocupada
                                $disabled = $esOcupada ? 'disabled' : '';
                                // 'checked' sirve para marcar con un check las materias que
                                // anteriormente se seleccionó
                                $checked = in_array($idMateria, $materiasSeleccionadasIds) ? 'checked' : '';

                                echo '<div class="materia ' . ($esOcupada ? "ocupada" : "") . '">';

                                echo '<input type="checkbox" id="materia-' . $idMateria . '" 
                                        name="materias[]" 
                                        value="' . $idMateria . '" 
                                        ' . $disabled . '
                                        ' . $checked  . '>';

                                echo '<label for="materia-' . $idMateria . '">' . $nombreMateria . '</label>';

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