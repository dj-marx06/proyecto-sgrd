<?php
require_once('modelo/conexion.php');

class Atleta extends Conexion {
    public function __construct() {
        parent::__construct();
    }

    // ESTA ES LA FUNCIÓN QUE TE DA EL ERROR PORQUE FALTA
    public function listarAtletas() {
        $conex = $this->getConex1();
        try {
            // Seleccionamos todo y calculamos la edad al vuelo
            $sql = "SELECT *, TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) AS edad FROM atleta";
            $stmt = $conex->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function registrarAtleta($datos) {
        $conex = $this->getConex1();
        try {
            $sql = "INSERT INTO atleta (cedula, nombres, apellidos, fecha_nacimiento, genero, fichaje_federativo, lateralidad) 
                    VALUES (:cedula, :nombres, :apellidos, :fecha_nac, :genero, :fichaje, :lateralidad)";
            
            $stmt = $conex->prepare($sql);
            return $stmt->execute([
                ':cedula'    => $datos['cedula'],
                ':nombres'   => $datos['nombres'],
                ':apellidos' => $datos['apellidos'],
                ':fecha_nac' => $datos['fecha_nacimiento'],
                ':genero'    => $datos['genero'],
                ':fichaje'   => $datos['fichaje_federativo'],
                ':lateralidad' => $datos['lateralidad']
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function obtenerPorId($id) {
        $conex = $this->getConex1();
        try {
            $sql = "SELECT * FROM atleta WHERE id_atleta = :id";
            $stmt = $conex->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return null; }
    }

    public function editarAtleta($datos) {
        $conex = $this->getConex1();
        try {
            $sql = "UPDATE atleta SET cedula = :cedula, nombres = :nombres, apellidos = :apellidos, 
                    fecha_nacimiento = :fecha_nac, genero = :genero, fichaje_federativo = :fichaje, 
                    lateralidad = :lateralidad WHERE id_atleta = :id";
            $stmt = $conex->prepare($sql);
            return $stmt->execute([
                ':cedula'    => $datos['cedula'],
                ':nombres'   => $datos['nombres'],
                ':apellidos' => $datos['apellidos'],
                ':fecha_nac' => $datos['fecha_nacimiento'],
                ':genero'    => $datos['genero'],
                ':fichaje'   => $datos['fichaje_federativo'],
                ':lateralidad' => $datos['lateralidad'],
                ':id'        => $datos['id_atleta']
            ]);
        } catch (PDOException $e) { return false; }
    }

    public function eliminarAtleta($id) {
        $conex = $this->getConex1();
        try {
            $sql = "DELETE FROM atleta WHERE id_atleta = :id";
            $stmt = $conex->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) { return false; }
    }
}