<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 2) header("Location: ../login.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registros Claquetazo</title>
    <style>
        body { background-color: #f5f5dc; font-family: sans-serif; padding: 2rem; }
        .flex { display: flex; gap: 40px; }
        .form-reg { background: white; padding: 20px; border-radius: 10px; border: 1px solid #d2b48c; width: 50%; }
        input { width: 100%; padding: 10px; margin: 5px 0; box-sizing: border-box; }
        .btn { background: #8b4513; color: white; border: none; padding: 10px; width: 100%; cursor: pointer; }
    </style>
</head>
<body>
    <a href="organizador_dashboard.php">⬅ Volver</a>
    <div class="flex">
        <div class="form-reg">
            <h3>Registrar Director</h3>
            <form action="../Controlador/OrganizadorController.php" method="POST">
                <input type="text" name="n1" placeholder="Primer Nombre" required>
                <input type="text" name="n2" placeholder="Segundo Nombre">
                <input type="text" name="ap" placeholder="Apellido Paterno" required>
                <input type="text" name="am" placeholder="Apellido Materno">
                <input type="email" name="correo" placeholder="Correo" required>
                <input type="password" name="pass" placeholder="Contraseña" required>
                <input type="text" name="tel" placeholder="Teléfono">
                <button type="submit" name="registrar_director" class="btn">Registrar Director</button>
            </form>
        </div>
        <div class="form-reg">
            <h3>Registrar Espectador</h3>
            <form action="../Controlador/OrganizadorController.php" method="POST">
                <input type="text" name="n1" placeholder="Primer Nombre" required>
                <input type="text" name="n2" placeholder="Segundo Nombre">
                <input type="text" name="ap" placeholder="Apellido Paterno" required>
                <input type="text" name="am" placeholder="Apellido Materno">
                <input type="email" name="correo" placeholder="Correo" required>
                <input type="password" name="pass" placeholder="Contraseña" required>
                <input type="text" name="tel" placeholder="Teléfono">
                <button type="submit" name="registrar_espectador" class="btn">Registrar Espectador</button>
            </form>
        </div>
    </div>
</body>
</html>