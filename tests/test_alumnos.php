<?php
declare(strict_types=1);

use app\config\ConnectionDB;
use app\dao\AlumnoDAO;

// Autoload (si usás composer). Si no, descomentá los require de abajo.
// require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/config/ConnectionDB.php';
require_once __DIR__ . '/../app/DAO/AlumnoDAO.php';

if (session_status() === PHP_SESSION_NONE) session_start();

try {
    // Ajustá estos valores al que comprobaste en phpMyAdmin
    $idMateria = 1;
    $anioCursada = 2025;

    // Obtener conexión e instanciar DAO
    $connection = ConnectionDB::getInstancia();
    $dao = new AlumnoDAO($connection);

    $rows = $dao->readAlumnosByMateria((int)$idMateria, (int)$anioCursada);

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>