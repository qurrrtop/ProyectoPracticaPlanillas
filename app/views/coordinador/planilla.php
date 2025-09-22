<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/appwebplanilla/public/assets/css/coordinador-estilos/planilla-style.css">
    <title>Planilla</title>
</head>
<body>
    <?php require_once __DIR__."/../template/sidebar.php" ?>

    <main>
        
        <h2>📑 Planilla - Programación (1°)</h2>
    
        <table>
            <thead>
            <tr>
                <th>Legajo</th>
                <th>Alumno</th>
                <th>DNI</th>
                <th>Nota 1</th>
                <th>Nota 2</th>
                <th>Promedio</th>
                <th>Condición</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>1001</td>
                <td>Juan Pérez</td>
                <td>40123456</td>
                <td>8</td>
                <td>7</td>
                <td>7.5</td>
                <td>Regular</td>
            </tr>
            <tr>
                <td>1002</td>
                <td>María Gómez</td>
                <td>40987654</td>
                <td>10</td>
                <td>9</td>
                <td>9.5</td>
                <td>Promocionado</td>
            </tr>
            <tr>
                <td>1003</td>
                <td>Carlos Díaz</td>
                <td>39876543</td>
                <td>4</td>
                <td>5</td>
                <td>4.5</td>
                <td>Libre</td>
            </tr>
            <tr>
                <td>1004</td>
                <td>Lucía Fernández</td>
                <td>41234567</td>
                <td>6</td>
                <td>7</td>
                <td>6.5</td>
                <td>Regular</td>
            </tr>
            </tbody>
        </table>
    
        <a href="ver_planillas.html" class="btn-back">⬅ Volver</a>
    </main>


    
</body>
</html>