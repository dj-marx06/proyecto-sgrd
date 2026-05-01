<?php
/**
 * ARCHIVO: config.php
 * Configuración global del sistema SGRD
 */

// 1. Configuración de la Base de Datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'sgrd_db'); // <--- Cambia esto por el nombre real de tu BD
define('DB_USER', 'root');           // <--- Tu usuario de MySQL
define('DB_PASS', '');               // <--- Tu contraseña de MySQL
define('DB_CHARSET', 'utf8');

// 2. Rutas del Sistema
// Detecta automáticamente si estás en localhost o en un servidor real
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$domain = $_SERVER['HTTP_HOST'];
$path = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);

define('URL_BASE', $protocol . $domain . $path);

// 3. Ajustes de Zona Horaria
date_default_timezone_set('America/Caracas'); // Ajustado a tu ubicación actual