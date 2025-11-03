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
        <h2>📑 Ver Planillas</h2>

        <?php if (!empty($_SESSION['mensaje'])): ?>
            <div class="mensaje"><?= htmlspecialchars($_SESSION['mensaje']) ?></div>
            <?php unset($_SESSION['mensaje']); ?>
        <?php endif; ?>

        <div class="form-container">
            <form method="get" action="index.php">
                <input type="hidden" name="controller" value="Coordinador">
                <input type="hidden" name="action" value="verPlanillas">

                <label for="anio">Año</label>
                <select id="anio" name="anio">
                    <option value="">-- Seleccione un año --</option>
                    <?php
                    //verifica que la variable exista y obtener los datos ordenados
                    $materiasPorAnio = $materiasPorAnio ?? [];
                    $años = array_keys( $materiasPorAnio );
                    sort( $años );
                    foreach ( $años as $anioKey ): ?>
                        <option value="<?= htmlspecialchars( $anioKey ) ?>" <?= (isset( $anio ) && $anio == $anioKey ) ? 'selected' : '' ?>>
                            <?= htmlspecialchars( $anioKey ) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

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

                <button type="submit" class="btn">Buscar</button>
            </form>
        </div>

        <?php if (!empty( $planillas ) ): ?>
            <section class="resultados">
                <h3>Planillas (<?= count( $planillas ) ?>)</h3>
                <table class="tabla-planillas" border="1" cellpadding="6">
                    <thead>
                        <tr><th>ID</th><th>Fecha</th><th>Descripción</th><th>Acciones</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ( $planillas as $p ): ?>
                            <tr>
                                <td><?= htmlspecialchars( $p['idPlanilla'] ?? $p['id'] ?? '' ) ?></td>
                                <td><?= htmlspecialchars( $p['fecha'] ?? '' ) ?></td>
                                <td><?= htmlspecialchars( $p['descripcion'] ?? $p['detalle'] ?? '' ) ?></td>
                                <td>
                                    <a href="index.php?controller=Coordinador&action=verPlanillaDetalle&idPlanilla=<?= urlencode( $p['idPlanilla'] ?? $p['id'] ?? '' ) ?>">Ver</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        <?php elseif (isset( $idMateria ) && empty( $planillas ) ): ?>
            <p>No se encontraron planillas para la materia seleccionada.</p>
        <?php endif; ?>

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