<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/ProyectoPracticaPlanillas/public/assets/css/coordinador-estilos/verPlanillas-style.css">
    <title>Ver Planillas</title>
</head>
<body>

    <?php require_once __DIR__ . "/../template/sidebar.php"; ?>

    <main>
        <!-- <h2>Ver Planillas</h2> -->

        <?php if (!empty($_SESSION['mensaje'])): ?>
            <div class="mensaje"><?= htmlspecialchars($_SESSION['mensaje']) ?></div>
            <?php unset($_SESSION['mensaje']); ?>
        <?php endif; ?>

        <div class="form-container">
            <form action="index.php?controller=coordinador&action=getDataPlanilla" method="POST">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
                <div class="form-row">
                    <div>
                        <label for="anio">Año</label>
                        <select id="anio" name="anio" class="form-select">
                            <option value="">-- seleccione un año --</option>
                            <?php if (!empty($anios)): ?>
                                <?php foreach ($anios as $anioItem): ?>
                                    <option value="<?= htmlspecialchars($anioItem) ?>">
                                        <?= htmlspecialchars(ucfirst($anioItem)) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">(No hay años disponibles)</option>
                            <?php endif; ?>
                        </select>

                    </div>

                    <div>
                        <label for="materia">Materia</label>
                        <select id="materia" name="idMateria">
                            <option value="">-- Seleccione una materia --</option>
                            <?php
                            $selectedAnio = $anio ?? null;
                            if ( $selectedAnio && isset( $materiasPorAnio[$selectedAnio] )) {
                                foreach ( $materiasPorAnio[$selectedAnio] as $m ) {
                                    // soporte para distinta forma de datos, modelo o array
                                    $val = $m['idMateria'] ?? $m['id'] ?? '';
                                    $label = $m['nombre'] ?? $m['materia'] ?? 'Materia';
                                    $sel = ( isset( $idMateria ) && $idMateria == $val ) ? 'selected' : '';
                                    echo '<option value="'.htmlspecialchars( $val ).'" '.$sel.'>'.htmlspecialchars( $label ).'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div>
                        <button type="submit" class="btn">Aplicar</button>
                    </div>
                </div>
            </form>
        </div>

        <?php
            function formatValue($valor) {
                return htmlspecialchars(ucwords(trim((string)$valor)));
            }
        ?>

        <!-- Encabezado informativo -->
        <?php if (!empty($datosMateria)) : ?>
            <div class="info-panel">
                <div class="info-box">
                    <div class="info-title">Ciclo lectivo</div>
                    <div class="info-value">2025</div>
                </div>
                <div class="info-box">
                    <div class="info-title">Unidad Curricular</div>
                    <div class="info-value"><?= formatValue($datosMateria['materia'] ?? '') ?></div>
                </div>
                <div class="info-box">
                    <div class="info-title">Docente</div>
                    <div class="info-value"><?= formatValue(($datosMateria['docente_nombre'] ?? '') . ' ' . ($datosMateria['docente_apellido'] ?? '')) ?></div>
                </div>
                <div class="info-box">
                    <div class="info-title">Duración</div>
                    <div class="info-value"><?= formatValue($datosMateria['duracion'] ?? '') ?></div>
                </div>
                <div class="info-box">
                    <div class="info-title">Formato</div>
                    <div class="info-value"><?= formatValue($datosMateria['formato'] ?? '') ?></div>
                </div>
                <div class="info-box">
                    <div class="info-title">Régimen</div>
                    <div class="info-value"><?= formatValue($datosMateria['regimen'] ?? '') ?></div>
                </div>
            </div>
        <?php else : ?>
            <!-- Mensaje opcional -->
            <p class="parrafo-mensaje">Seleccione un año y una materia para ver los datos.</p>
        <?php endif; ?>

            <?php if (!empty($alumnos)): ?>
                <div class="table-container">
                    <h3>Lista de Alumnos</h3>
                    <table id="tablaAlumnos" class="alumnos-table">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>DNI</th>
                                <th>Cohorte</th>
                                <th>Condición</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($alumnos as $index => $alumno): ?>
                                <tr data-id="<?= $alumno['idCursada'] ?? $index + 1 ?>">
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($alumno['nombre'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($alumno['apellido'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($alumno['dni'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($alumno['cohorte'] ?? '') ?></td>
                                    <td>
                                        <span class="condicion-badge <?= strtolower($alumno['condicion'] ?? '') ?>">
                                            <?= htmlspecialchars($alumno['condicion'] ?? 'Sin definir') ?>
                                        </span>
                                    </td>
                                </tr>
                                <!-- Fila expandible para detalles de finales (opcional) -->
                                <?php if (isset($alumno['mostrar_detalles']) && $alumno['mostrar_detalles']): ?>
                                    <tr class="card-finales" id="finales-<?= $alumno['idCursada'] ?? $index + 1 ?>">
                                        <td colspan="6">
                                            <div class="finales-container">
                                                <strong>Detalles de exámenes finales - <?= htmlspecialchars($alumno['nombre'] ?? '') ?> <?= htmlspecialchars($alumno['apellido'] ?? '') ?></strong>
                                                <table class="finales-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Intento</th>
                                                            <th>Nota</th>
                                                            <th>Fecha</th>
                                                            <th>Observaciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Aquí irían los datos de los finales si los tienes -->
                                                        <tr>
                                                            <td colspan="4" class="no-data">No hay exámenes finales registrados</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <!-- Resumen -->
                    <div class="resumen-alumnos">
                        <p>Total de alumnos: <strong><?= count($alumnos) ?></strong></p>
                    </div>
                </div>
            <?php else: ?>
                <div class="no-alumnos">
                    <p>No hay alumnos inscritos en esta materia para el año seleccionado.</p>
                </div>
            <?php endif; ?>

    </main>

<!-- pasar datos PHP a JS de forma segura y cargar el script -->
<script>
window.MATERIAS_POR_ANIO = <?= json_encode( $materiasPorAnio ?? [], JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT ) ?>;
window.SELECTED_ANIO = <?= json_encode( $anio ?? null ) ?>;
window.SELECTED_IDMATERIA = <?= json_encode( $idMateria ?? null ) ?>;
</script>
<script src="/ProyectoPracticaPlanillas/public/assets/js/verPlanillas.js"></script>
<script src="/ProyectoPracticaPlanillas/public/assets/js/verPlanillaDetail.js"></script>

</body>
</html>