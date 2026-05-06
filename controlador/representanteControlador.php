<?php

require_once 'modelo/Representante.php';
require_once 'utils/Respuesta.php';

// 1. TU CLASE ORIENTADA A OBJETOS (Para el API REST)
class representanteControlador {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function ejecutar($accion) {
        switch ($accion) {
            case 'registrar':
                $this->registrar();
                break;
            default:
                Respuesta::enviar(404, "Acción no válida");
        }
    }

    private function registrar() {
        $data = json_decode(file_get_contents("php://input"));
        
        if (empty($data->cedula) || empty($data->nombres)) {
            Respuesta::enviar(400, "Cédula y nombres son obligatorios");
            return;
        }

        try {
            $this->conn->beginTransaction();

            $rep = new Representante($this->conn);
            $rep->cedula     = $data->cedula;
            $rep->nombres    = $data->nombres;
            $rep->apellidos  = $data->apellidos;
            $rep->telefono   = $data->telefono;
            $rep->parentesco = $data->parentesco;
            $rep->email      = $data->email;

            $nuevo_representante_id = $rep->save();

            if (!$nuevo_representante_id) {
                throw new Exception("No se pudo crear el representante.");
            }

            // Asociar atletas si enviaron IDs
            if (!empty($data->atletas_ids) && is_array($data->atletas_ids)) {
                $query = "UPDATE atleta SET representante_id = ? WHERE id_atleta = ?";
                $stmtAtleta = $this->conn->prepare($query);
                foreach ($data->atletas_ids as $id_atleta) {
                    $stmtAtleta->execute([$nuevo_representante_id, $id_atleta]);
                }
            }

            $this->conn->commit();
            Respuesta::enviar(201, "Representante registrado y atletas vinculados con éxito.");

        } catch (Exception $e) {
            $this->conn->rollBack();
            Respuesta::enviar(500, "Error en la transacción: " . $e->getMessage());
        }
    }
}

// =========================================================================
// 2. LA MAGIA PARA NO USAR ARCHIVOS PUENTE
// =========================================================================

if (isset($_GET['p']) && $_GET['p'] === 'representante') {
    
    // ¡AQUÍ ESTABA EL DETALLE! Iniciar la sesión antes de validarla
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Validamos que la sesión exista (Seguridad visual)
    if (empty($_SESSION['id'])) { 
        header('Location: ?p=login'); 
        exit; 
    }
    
    // Cargamos directamente la vista
    require_once 'vista/representante.php';
}
?>