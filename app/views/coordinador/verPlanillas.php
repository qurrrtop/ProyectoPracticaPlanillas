<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/ProyectoPracticaPlanillas/public/assets/css/coordinador-estilos/verPlanillas-style.css">
    <title>Ver Planillas</title>
</head>
<body>

    <?php require_once __DIR__."/../template/sidebar.php" ?>

    <main>
        <h2>📑 Ver Planillas</h2>
    
        <div class="form-container">
            <form>
                    <label for="anio">Año</label>
                    <select id="anio" name="anio">
                        <option value="">-- Seleccione un año --</option>
                        <option value="2023">1°</option>
                        <option value="2024">2°</option>
                        <option value="2025">3°</option>
                        <option value="2025">4°</option>
                    </select>
            
                    <label for="materia">Materia</label>
                    <select id="materia" name="materia">
                        <option value="">-- Seleccione una materia --</option>
                        <option value="1">Matemática</option>
                        <option value="2">Historia</option>
                        <option value="3">Programación</option>
                    </select>
        
                    <button type="submit" class="btn">Buscar</button>
            </form>
        </div>
    </main>

</body>
</html>