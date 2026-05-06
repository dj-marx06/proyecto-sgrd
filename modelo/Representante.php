<?php
require_once 'Model.php';

class Representante extends Model {
    
    protected $table = 'representantes'; // La tabla en tu BD

    // Atributos explícitos para el Diccionario de Datos
    public $cedula;
    public $nombres;
    public $apellidos;
    public $telefono;
    public $parentesco;
    public $email;

    // Aquí puedes agregar métodos específicos si los necesitas a futuro
    // ej: public function listarAtletasAsignados() { ... }
}
?>