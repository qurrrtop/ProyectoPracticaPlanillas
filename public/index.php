<?php

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
    $controllerClass = 'app\controllers\\' .ucfirst($controller) . 'Controller';

    // Ruta al archivo del controlador
    $controllerFilePath = CONTROLLER_FOLDER . ucfirst( $controller ) . 'Controller.php';

    //esto es una wea cosmica que no entiendo muy bien pero es para que carguen los namespaces o algo asi no se
    //Es un autoloader que automáticamente busca y carga el archivo PHP correspondiente a una clase del namespace app cuando la usás, sin tener que hacer require_once manualmente. (lo invente yo un saludo)
    spl_autoload_register(function (string $class) {
        $prefix = 'app\\';
        $baseDir = __DIR__ . '/../app/';
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) return;
        $relative = substr($class, $len);
        $file = $baseDir . str_replace('\\', '/', $relative) . '.php';
        if (file_exists($file)) require_once $file;
    });

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

