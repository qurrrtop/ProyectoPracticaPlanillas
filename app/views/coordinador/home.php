<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/ProyectoPracticaPlanillas/public/assets/css/coordinador-estilos/home-style.css">
    <title>prueba</title>
</head>
<body>
    <?php require_once __DIR__."/../template/sidebar.php"; ?>

    <main>
        <div class="box-buttons">
            <button class="btn btn-ver">Ver planillas</button>
        </div>


        <div class="stats-grid">
            <div class="stat-card green" role="group" aria-label="Materias">
                <div>
                    <p class="stat-title">MÍS Materias</p>
                    <p class="stat-number" id="materias-count">6</p>
                </div>
                <div class="stat-hint">Última actualización: hoy</div>
            </div>

            <div class="stat-card blue" role="group" aria-label="Alumnos">
                <div>
                    <p class="stat-title">Alumnos</p>
                    <p class="stat-number" id="alumnos-count">124</p>
                    <div class="stat-hint">Última actualización: hoy</div>
                </div>
            </div>

            <div class="stat-card orange" role="group" aria-label="Planillas">
                <div>
                    <p class="stat-title">Planillas</p>
                    <p class="stat-number" id="planillas-count">18</p>
                    <div class="stat-hint">Última actualización: hoy</div>
                </div>
            </div>

            <div class="stat-card purple" role="group" aria-label="Docentes">
                <div>
                    <p class="stat-title">Docentes</p>
                    <p class="stat-number" id="docentes-count">8</p>
                    <div class="stat-hint">Última actualización: hoy</div>
                </div>
            </div>
        </div>
    </main>

</body>
</html>