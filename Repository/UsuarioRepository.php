<?php
class UsuarioRepository {
    private $pdo;
    public function __construct($pdo) { $this->pdo = $pdo; }
    public function validarUsuario($correo, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM Usuarios WHERE correo = ? AND contrasena = ? AND estado = 1");
        $stmt->execute([$correo, $password]);
        return $stmt->fetch();
    }
}