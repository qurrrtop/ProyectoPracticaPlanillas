<?php
// Conexión a la BD (ajusta según tu config)
$host = "localhost";
$dbname = "appwebplanilla";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Usuario y contraseña inicial
    $username = "coordinador";// 
    $passwordPlano = "coordinador2025";  // Contraseña en texto plano inicial, se guardará en hash
    $hash = password_hash($passwordPlano, PASSWORD_BCRYPT);//aca se guardara la contraseña ya encriptada

    // Insertar
    $sql = "INSERT INTO usuarios (userName, passwordHash, rol) VALUES (:userName, :passwordHash, :rol)";// esto para colocar ya en la base de datos
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":userName" => $username,
        ":passwordHash" => $hash,//se mostrara la contraseña encriptada en sql
        ":rol" => "coordinador"
    ]);

    echo "✅ Usuario coordinador insertado correctamente con hash.";
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}