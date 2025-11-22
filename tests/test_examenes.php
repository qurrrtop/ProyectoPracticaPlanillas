<?php
declare(strict_types=1);

use app\config\ConnectionDB;
use app\dao\ExamenDAO;


require_once __DIR__ . '/../app/config/ConnectionDB.php';
require_once __DIR__ . '/../app/DAO/ExamenDAO.php';

if (session_status() === PHP_SESSION_NONE) session_start();

try {

    $idAlumno = 41;
    $idMateria = 1;

    // Obtener conexión e instanciar DAO
    $connection = ConnectionDB::getInstancia();
    $dao = new ExamenDAO($connection);

    $rows = $dao->readExamenByAlumno( $idAlumno, $idMateria );

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>