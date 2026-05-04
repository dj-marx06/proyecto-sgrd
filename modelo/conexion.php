<?php
// modelo/conexion.php

class Conexion {
    private $host = "localhost";
    private $user = "root";       // Tu usuario de base de datos
    private $pass = "";           // Tu contraseña de base de datos
    private $db   = "prueba"; // El nombre de tu base de datos
    private $pdo;

    public function __construct() {
        try {
            // Configuración de la conexión PDO
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db . ";charset=utf8";
            $this->pdo = new PDO($dsn, $this->user, $this->pass);
            
            // Configurar para que lance excepciones en caso de error
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    /**
     * Método para obtener la conexión activa, 
     * tal como lo requiere tu modelo de ejemplo.
     */
    public function getConex1() {
        return $this->pdo;
    }
}
?>