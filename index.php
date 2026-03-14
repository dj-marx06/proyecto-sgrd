<?php
/**
 * ARCHIVO: index.php
 * Punto de entrada principal (Front Controller)
 */

// 1. Normalizar la ruta raíz para evitar errores en Windows/XAMPP
// Esto convierte C:\xampp\htdocs\PROYECTO 2026 en C:/xampp/htdocs/PROYECTO 2026/
define('RAIZ', str_replace('\\', '/', __DIR__) . '/');

// 2. Capturar la página desde la URL (ejemplo: index.php?p=inicio)
$pagina = isset($_GET['p']) ? $_GET['p'] : 'inicio';

// 3. Enrutador Simple
switch ($pagina) {
    case 'inicio':
        // Verificamos si el archivo del controlador existe antes de llamarlo
        $archivoControlador = RAIZ . 'controlador/InicioControlador.php';
        
        if (file_exists($archivoControlador)) {
            require_once $archivoControlador;
            $controlador = new InicioControlador();
            $controlador->renderizar();
        } else {
            die("Error: No se encontró el archivo controlador en: " . $archivoControlador);
        }
        break;

    case 'analitica':
        echo "Página de analítica en desarrollo...";
        break;

    default:
        header("HTTP/1.0 404 Not Found");
        echo "<h1>404 - Página no encontrada</h1>";
        break;
}