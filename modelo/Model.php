<?php
abstract class Model {
    protected $conn;
    protected $table;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function save() { // Solo Guardado
        // 1. Extraer los atributos de la clase hija (Representante)
        $atributos = get_object_vars($this);
        unset($atributos['conn'], $atributos['table']);

        // 2. Armar SQL Dinámico
        $columnas = array_keys($atributos);
        $nombresColumnas = implode(", ", $columnas);
        $placeholders = ":" . implode(", :", $columnas);

        $query = "INSERT INTO " . $this->table . " ($nombresColumnas) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($query);

        // 3. Bind de datos seguro
        foreach ($atributos as $key => $value) {
            $stmt->bindValue(":$key", htmlspecialchars(strip_tags((string)$value)));
        }

        // 4. Ejecutar y registrar en Bitácora (¡Esto resuelve la Fase 1!)
        if ($stmt->execute()) {
            // $this->registrarBitacora('INSERT', json_encode($atributos));
            return true;
        }
        return false;
    }

    public function saveReturnID() { // Guardado y returna ID
        // 1. Extraer los atributos de la clase hija (Representante)
        $atributos = get_object_vars($this);
        unset($atributos['conn'], $atributos['table']);

        // 2. Armar SQL Dinámico
        $columnas = array_keys($atributos);
        $nombresColumnas = implode(", ", $columnas);
        $placeholders = ":" . implode(", :", $columnas);

        $query = "INSERT INTO " . $this->table . " ($nombresColumnas) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($query);

        // 3. Bind de datos seguro
        foreach ($atributos as $key => $value) {
            $stmt->bindValue(":$key", htmlspecialchars(strip_tags((string)$value)));
        }

        // 4. Ejecutar y registrar en Bitácora (¡Esto resuelve la Fase 1!)
        if ($stmt->execute()) {
            // $this->registrarBitacora('INSERT', json_encode($atributos));
            return $this->conn->lastInsertId();
        }
        return false;
    }

    /**
     * Obtiene todos los registros de la tabla asociada al modelo.
     */
    public function getAll($orden = null) {
        $sql = "SELECT * FROM {$this->table}";
        
        if ($orden) {
            $sql .= " ORDER BY " . $orden;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        
        // Retornamos un array asociativo (ideal para JSON)
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca un registro específico por una columna y un valor.
     * Útil para buscar por Cédula, ID o cualquier campo único.
     */
    public function find($valor, $columna = 'cedula') {
        $sql = "SELECT * FROM {$this->table} WHERE {$columna} = ? LIMIT 1";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$valor]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Ejecuta una consulta SQL personalizada con parámetros seguros.
     * Útil para JOINs complejos o filtros específicos.
     */
    public function where($condiciones, $params = []) {
        $sql = "SELECT * FROM {$this->table} WHERE " . $condiciones;
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Método encapsulado para la Fase 1
    protected function registrarBitacora($accion, $detalles) {
        // Asume usuario 1 por ahora, luego lo cambias por $_SESSION['id']
        $usuario_id = $_SESSION['id'] ?? 1; 
        $query = "INSERT INTO bitacora (usuario_id, modulo, accion, detalle_json) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$usuario_id, $this->table, $accion, $detalles]);
    }
}
?>