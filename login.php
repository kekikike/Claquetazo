<?php
session_start();
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo']);
    $contrasena = trim($_POST['contrasena']);

    $stmt = $pdo->prepare("SELECT * FROM Usuarios WHERE correo = ? AND estado = TRUE");
    $stmt->execute([$correo]);
    $usuario = $stmt->fetch();

    if ($usuario && $contrasena === $usuario['contrasena']) {  // En producción usa password_hash
        session_regenerate_id(true);
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['id_rol']     = $usuario['id_rol'];
        $_SESSION['nombre']     = $usuario['nombre1'] . ' ' . $usuario['apellido_paterno'];

        if ($usuario['id_rol'] == 1) {        // Creador
            header("Location: views/creador/dashboard.php");
        } else if ($usuario['id_rol'] == 2) { // Organizador
            header("Location: views/organizador/dashboard.php");
        }
        exit;
    } else {
        $error = "Credenciales incorrectas";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Claquetazo</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5e8d3; display:flex; justify-content:center; align-items:center; height:100vh; margin:0; }
        .login-box { background:white; padding:40px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.1); width:320px; }
        input { width:100%; padding:10px; margin:10px 0; border:1px solid #c9a97e; border-radius:6px; }
        button { width:100%; padding:12px; background:#c9a97e; color:white; border:none; border-radius:6px; cursor:pointer; }
    </style>
</head>
<body>
<div class="login-box">
    <img src="../media/logo.png" alt="Claquetazo" style="width:100%; margin-bottom:20px;">
    <h2 style="text-align:center; color:#5c4033;">Iniciar Sesión</h2>
    <?php if(isset($error)) echo "<p style='color:red;text-align:center;'>$error</p>"; ?>
    <form method="POST">
        <input type="email" name="correo" placeholder="Correo" required>
        <input type="password" name="contrasena" placeholder="Contraseña" required>
        <button type="submit">Entrar</button>
    </form>
</div>
</body>
</html>