<?php
session_start();
if (isset($_SESSION['usuario'])) {
    header("Location: Vista/" . ($_SESSION['rol'] == 1 ? "creador_dashboard.php" : "organizador.php"));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Claquetazo - Login</title>
    <style>
        body { background-color: #f5f5dc; font-family: 'Segoe UI', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; color: #3e2723; }
        .login-card { background: #fffaf0; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border: 1px solid #d2b48c; width: 350px; text-align: center; }
        img { width: 150px; margin-bottom: 1rem; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #d2b48c; border-radius: 8px; box-sizing: border-box; background: #fff; }
        button { width: 100%; padding: 12px; background: #8b4513; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 1rem; transition: 0.3s; }
        button:hover { background: #5d2e0a; }
        .error { color: #a94442; font-size: 0.9rem; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="login-card">
        <img src="media/logo.png" alt="Claquetazo">
        <h2>Iniciar Sesión</h2>
        <form action="Controlador/AuthController.php" method="POST">
            <input type="email" name="correo" placeholder="Correo Electrónico" required>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            <button type="submit">Ingresar</button>
        </form>
        <?php if (isset($_GET['error'])): ?>
            <p class="error">Credenciales incorrectas</p>
        <?php endif; ?>
    </div>
</body>
</html>