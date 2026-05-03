<?php
require_once '../Config/db.php';
require_once '../Repository/UsuarioRepository.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $repo = new UsuarioRepository($pdo);
    $user = $repo->validarUsuario($_POST['correo'], $_POST['contrasena']);

    if ($user) {
        $_SESSION['usuario'] = $user['id_usuario'];
        $_SESSION['nombre'] = $user['nombre1'] . " " . $user['apellido_paterno'];
        $_SESSION['rol'] = $user['id_rol'];
        
        if ($user['id_rol'] == 1) header("Location: ../Vista/creador_dashboard.php");
        else header("Location: ../Vista/organizador_dashboard.php");
    } else {
        header("Location: ../login.php?error=1");
    }
}