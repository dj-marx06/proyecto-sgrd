<?php
// controlador/InicioControlador.php

class InicioControlador {
    
    public function renderizar() {
        // Aquí podrías definir variables que la vista usará
        $titulo_pagina = "Panel de Inicio";
        
        // Llamamos a la vista
        require_once RAIZ . 'vista/inicio.php';
    }
}