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

        <!-- <table id="tablaAlumnos">
            <thead>
                <tr>
                <th>N°</th><th>Nombre</th><th>Apellido</th><th>DNI</th><th>Cohorte</th><th>Condición</th>
                </tr>
            </thead>
            <tbody>
                <tr data-id="1"><td>1</td><td>Javier José</td><td>Maidana</td><td>46.074.909</td><td>2024</td><td>Regular</td></tr>
                <tr class="card-finales" id="finales-1">
                <td colspan="7">
                    <strong>Detalles de exámenes finales - Javier Maidana</strong>
                    <table>
                    <tr><th>Intento</th><th>Nota</th><th>Fecha</th></tr>
                    <tr><td>1</td><td>5</td><td>2025-07-15</td></tr>
                    <tr><td>2</td><td>7</td><td>2025-12-18</td></tr>
                    </table>
                </td>
                </tr>

                <tr data-id="2"><td>2</td><td>Román Alberto</td><td>Maidana</td><td>46.074.909</td><td>2024</td><td>Regular</td></tr>
                <tr class="card-finales" id="finales-2">
                <td colspan="7">
                    <h4>Detalles de exámenes finales - Román Maidana</h4>
                    <table>
                    <tr><th>Intento</th><th>Nota</th><th>Fecha</th></tr>
                    <tr><td>1</td><td>7</td><td>2025-08-30</td></tr>

                    </table>
                </td>
                </tr>
            </tbody>
        </table> -->


    </main>

<!-- pasar datos PHP a JS de forma segura y cargar el script -->
<script>
window.MATERIAS_POR_ANIO = <?= json_encode( $materiasPorAnio ?? [], JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT ) ?>;
window.SELECTED_ANIO = <?= json_encode( $anio ?? null ) ?>;
window.SELECTED_IDMATERIA = <?= json_encode( $idMateria ?? null ) ?>;
</script>
<script src="/ProyectoPracticaPlanillas/public/assets/js/verPlanillas.js"></script>

</body>
</html>