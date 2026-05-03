<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 1) header("Location: ../login.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Claquetazo - Enviar Obra</title>
    <style>
        body { background-color: #f5f5dc; font-family: 'Segoe UI', sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .form-card { background: #fffaf0; padding: 2rem; border-radius: 15px; border: 1px solid #d2b48c; width: 500px; }
        input, textarea { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #d2b48c; border-radius: 5px; box-sizing: border-box; }
        .btn { width: 100%; padding: 12px; background: #8b4513; color: white; border: none; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="form-card">
        <h2 style="color:#8b4513; text-align:center;">Registrar Obra</h2>
        <form action="../Controlador/PeliculaController.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="titulo" placeholder="Título de la película" required>
            <input type="number" name="anio" placeholder="Año" required>
            <textarea name="sinopsis" placeholder="Sinopsis" rows="4" required></textarea>
            <label>Póster de la película:</label>
            <input type="file" name="poster" accept="image/*" required>
            <button type="submit" name="registrar_obra" class="btn">Enviar al Festival</button>
            <a href="creador_dashboard.php" style="display:block; text-align:center; margin-top:10px; color:#3e2723;">Cancelar</a>
        </form>
    </div>
</body>
</html>