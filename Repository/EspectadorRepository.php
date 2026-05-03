<?php
class EspectadorRepository {
    private $pdo;
    public function __construct($pdo) { $this->pdo = $pdo; }

    public function registrar($d) {
        $sql = "INSERT INTO Espectador (nombre1, nombre2, apellido_paterno, apellido_materno, correo, contrasena, telefono) VALUES (?,?,?,?,?,?,?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$d['n1'], $d['n2'], $d['ap'], $d['am'], $d['correo'], $d['pass'], $d['tel']]);
    }
}