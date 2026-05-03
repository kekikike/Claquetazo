<?php 
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['id_rol'] != 1) {
    header("Location: ../../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Enviar Nueva Película - Claquetazo</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f8f1e9;
            color: #5c4033;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 700px;
            margin: 40px auto;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }
        h1 { text-align: center; color: #8d6e4f; }
        label { display: block; margin: 15px 0 5px; font-weight: bold; }
        input, textarea, select {
            width: 100%;
            padding: 12px;
            border: 2px solid #d4b89e;
            border-radius: 8px;
            font-size: 16px;
        }
        button {
            margin-top: 25px;
            width: 100%;
            padding: 14px;
            background: #c9a97e;
            color: white;
            font-size: 18px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        button:hover { background: #b38a5c; }
        .logo { height: 70px; display: block; margin: 0 auto 20px; }
    </style>
</head>
<body>
<div class="container">
    <img src="../../media/logo.png" alt="Claquetazo" class="logo">
    <h1>Enviar Nueva Película</h1>

    <form action="../controllers/CreadorController.php?action=guardar_pelicula" method="POST" enctype="multipart/form-data">
        
        <label>Título de la película</label>
        <input type="text" name="titulo" required>

        <label>Año</label>
        <input type="number" name="anio" min="2000" max="2030" value="2026" required>

        <label>Sinopsis</label>
        <textarea name="sinopsis" rows="6" required></textarea>

        <label>Poster (Imagen)</label>
        <input type="file" name="poster" accept="image/jpeg,image/png,image/webp" required>

        <button type="submit">🚀 Enviar Película para Revisión</button>
    </form>

    <br>
    <a href="dashboard.php" style="color:#8d6e4f; text-decoration:none;">← Volver al Dashboard</a>
</div>
</body>
</html>