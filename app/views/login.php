<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/login-style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Teachers:ital,wght@0,400..800;1,400..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>AppWebPlanilla</title>
</head>
<body>
    <div class="form-card">
        <div class="presentation">
            <img class="logo-isfd" src="/appwebplanilla/public/assets/img/login/logo-isfd.png">

            <p class="message">Inicie sesión para utilizar la Aplicación Web de Planillas de notas y asistencias!</p>
        </div>

        <div class="form">
            <form action="index.php?controller=login&action=login" method="POST">

                <p class="title">Bienvenido!</p>

                <i class="fa-regular fa-id-card"></i>

                <input required class="inp-dni" type="text" name="userName" placeholder="Ingrese su usuario">

                <br>

                <i class="fa-solid fa-lock"></i>
                
                
                <input required class="inp-pass" type="password" name="password" placeholder="Ingrese su contraseña">

                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                
                <br>

                <button type="button" id="togglePassword">
                    <i class="fa-solid fa-eye"></i>
                </button>

                <button class="btn-submit" type="submit">Acceder</button>

                <br>

                <a class="link-pass" href="">Recuperar contraseña</a>

            </form>
        </div>
    </div>

    <?php if (!empty($error)): ?> 
        <div class="error-message">
            <p><?php echo htmlspecialchars($error); ?></p>
        </div>
    <?php endif; ?> 

    
    <script src="assets/js/password-login.js"></script>
</body>
</html>