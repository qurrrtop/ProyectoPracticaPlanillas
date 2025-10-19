<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Teachers:ital,wght@0,400..800;1,400..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/ProyectoPracticaPlanillas/public/assets/css/sidebar-style.css">
    <title>AppWebPlanilla</title>
</head>
<body>
    <nav class="nav">
        <div class="backg-sidebar">
            <img draggable="false" class="logo-ingles" src="/ProyectoPracticaPlanillas/public/assets/img/logo-ingles.png" alt="">
        </div>

        <ul class="list-sidebar">
            <a href="index.php?controller=Coordinador&action=home"><li><i class="fa-solid fa-house"></i> Principal</li></a>
            <a href="index.php?controller=Coordinador&action=verPlanillas"><li><i class="fa-solid fa-eye"></i> Ver Planillas</li></a>
            <a href=""><li><i class="fa-solid fa-pencil"></i> Cargar Planilla</li></a>
            <a href="index.php?controller=Usuario&action=perfil"><li><i class="fa-solid fa-circle-user"></i> Mi perfil</li></a>
            <a href="index.php?controller=Coordinador&action=panelCoord"><li><i class="fa-solid fa-users-gear"></i> Panel Coord</li></a>
            <!-- <a href=""><li><i class="fa-solid fa-chart-column"></i> Estadísticas</li></a> -->
        </ul>
        <a class="cerrar-sesion" href="index.php?controller=Login&action=logout"><li><i class="fa-solid fa-arrow-right-to-bracket"></i> Cerrar Sesión</li></a>
    </nav>

    <a href="index.php?controller=Usuario&action=perfil" class="logo-user"><i class="fa-solid fa-circle-user"></i></a>

    <div class="header">
        <h1>Bienvenido, Coordinador</h1>
        <span>Profesorado de Ingles</span>
    </div>
    <hr>


</body>
</html>