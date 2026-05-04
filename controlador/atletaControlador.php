<?php
session_start();
require_once 'modelo/atleta.php';

if (empty($_SESSION['id'])) { 
    header('Location: ?p=login'); 
    exit; 
}

$objAtleta = new Atleta();
$mensaje = ""; // Variable para las alertas de SweetAlert2

// 1. Lógica de Eliminación (Vía GET)
if (isset($_GET['eliminar'])) {
    $objAtleta->eliminarAtleta($_GET['eliminar']);
    // Redirigimos con un parámetro de mensaje 'm' para el JS
    header('Location: ?p=atleta&m=eliminado'); 
    exit;
}

// 2. Lógica de Procesamiento de Formulario (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // CASO: Edición
    if (!empty($_POST['id_atleta']) && !empty($_POST['cedula'])) {
        $resultado = $objAtleta->editarAtleta($_POST);
        // Redirigimos para limpiar el POST y mostrar éxito
        header('Location: ?p=atleta&m=editado');
        exit;
    } 
    
    // CASO: Registro Nuevo
    else if (!empty($_POST['cedula'])) {
        $resultado = $objAtleta->registrarAtleta($_POST);
        // Redirigimos para limpiar el POST y mostrar éxito
        header('Location: ?p=atleta&m=registrado');
        exit;
    }
}

// 3. Capturar mensajes de la URL (provenientes de las redirecciones anteriores)
if (isset($_GET['m'])) {
    $mensaje = $_GET['m'];
}

// 4. Carga de datos para la vista
$atletas = $objAtleta->listarAtletas();

// 5. Llamada a la vista
require_once 'vista/atleta.php';