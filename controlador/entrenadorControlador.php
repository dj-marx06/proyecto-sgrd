<?php
session_start();
require_once 'modelo/entrenador.php';

if (empty($_SESSION['id'])) { 
    header('Location: ?p=login'); 
    exit; 
}

$objEntrenador = new Entrenador();
$mensaje = ""; 

if (isset($_GET['eliminar'])) {
    $objEntrenador->eliminarEntrenador($_GET['eliminar']);
    header('Location: ?p=entrenador&m=eliminado'); 
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (!empty($_POST['id_entrenador']) && !empty($_POST['cedula'])) {
        $resultado = $objEntrenador->editarEntrenador($_POST);
        header('Location: ?p=entrenador&m=editado');
        exit;
    } 
    
    else if (!empty($_POST['cedula'])) {
        $resultado = $objEntrenador->registrarEntrenador($_POST);
        header('Location: ?p=entrenador&m=registrado');
        exit;
    }
}

if (isset($_GET['m'])) {
    $mensaje = $_GET['m'];
}

$entrenador = $objEntrenador->listarEntrenador();

require_once 'vista/entrenador.php';