<?php
declare(strict_types=1);

use app\config\ConnectionDB;
use app\dao\AlumnoDAO;


require_once __DIR__ . '/../app/config/ConnectionDB.php';
require_once __DIR__ . '/../app/DAO/AlumnoDAO.php';

if (session_status() === PHP_SESSION_NONE) session_start();

try {

    $idAlumno = 1;

    // Obtener conexión e instanciar DAO
    $connection = ConnectionDB::getInstancia();
    $dao = new AlumnoDAO($connection);

    $rows = $dao->readExamenByAlumno( (int) $idAlumno );

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>