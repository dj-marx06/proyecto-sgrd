<?php
/**
 * ARCHIVO: index.php
 * Punto de entrada principal (Front Controller)
 */

// 1. Cargamos la configuración global (BD, URL_BASE, etc.)
if (is_file("config.php")) {
    require_once("config/config.php");
}

// 2. Definimos la ruta raíz física del servidor
define('RAIZ', str_replace('\\', '/', __DIR__) . '/');

// 3. Capturamos la página desde la URL (?p=...)
// Si está vacío, por defecto cargamos "inicio"
$pagina = "inicio"; 
if (!empty($_GET['p'])) {
    $pagina = $_GET['p'];
}

// 4. Cargamos el controlador correspondiente dinámicamente[cite: 4]
$archivoControlador = "controlador/" . $pagina . "Controlador.php";

if (is_file($archivoControlador)) {
    require_once($archivoControlador);
} else {
    // Si la página no existe, enviamos al login o a un error 404
    header("HTTP/1.0 404 Not Found");
    echo "<h1>404 - La sección que buscas no existe</h1>";
    echo "<p>No se encontró: " . htmlspecialchars($archivoControlador) . "</p>";
}