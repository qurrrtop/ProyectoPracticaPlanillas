<?php
    // Detectar el entorno automáticamente
    if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
        // 💻 Si estás en local, podrías tener distintas rutas
        define('BASE_URL', '/ProyectoPracticaPlanillas/public/'); // para XAMPP
    } else {
        // 🌐 Si luego lo subís a un hosting o servidor real
        define('BASE_URL', '/');
    }

    // Carpeta donde están los controladores
    define("CONTROLLER_FOLDER", __DIR__ . "/../app/controllers/");

    // Controlador y acción por defecto
    define("DEFAULT_CONTROLLER", "Login");
    define("DEFAULT_ACTION", "login");

    // Obtener el controlador de la URL si viene por GET
    $controller = DEFAULT_CONTROLLER;
    if (!empty($_GET['controller'])) {
        $controller = $_GET['controller'];
    }

    // Obtener la acción de la URL si viene por GET
    $action = DEFAULT_ACTION;
    if (!empty($_GET['action'])) {
        $action = $_GET['action'];
    }

    // Formar el nombre de la clase del controlador
    $controllerClass = ucfirst($controller) . 'Controller';

    // Ruta al archivo del controlador
    $controllerFilePath = CONTROLLER_FOLDER . $controllerClass . '.php';

    try {
        // Verificar si existe el archivo
        if (is_file($controllerFilePath)) {
            require_once $controllerFilePath;

            // Verificar si la clase existe
            if (class_exists($controllerClass)) {
                // Instanciar el controlador
                $controllerInstance = new $controllerClass();

                // Verificar si el método existe
                if (method_exists($controllerInstance, $action)) {
                    // Ejecutar la acción
                    $controllerInstance->$action();
                } else {
                    throw new Exception("La acción '$action' no existe en $controllerClass");
                }
            } else {
                throw new Exception("La clase $controllerClass no existe");
            }
        } else {
            throw new Exception("El archivo del controlador '$controllerFilePath' no existe");
        }
    } catch (Exception $e) {
        // para ver si ocurrió un error.
        echo "Error: " . $e->getMessage();
    }
?>

