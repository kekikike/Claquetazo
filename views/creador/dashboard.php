<?php 
$nombre = $_SESSION['nombre'] ?? 'Creador';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Claquetazo</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8f1e9; color: #5c4033; margin:0; }
        header { background: #c9a97e; padding:15px; display:flex; justify-content:space-between; align-items:center; color:white; }
        .logo { height:60px; }
        .grid { display:grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap:20px; padding:30px; }
        .card { background:white; border-radius:12px; overflow:hidden; box-shadow:0 4px 12px rgba(0,0,0,0.1); cursor:pointer; transition:0.3s; }
        .card:hover { transform:scale(1.05); }
        .card img { width:100%; height:320px; object-fit:cover; }
        .info { padding:12px; }
        button { background:#c9a97e; color:white; border:none; padding:12px 20px; border-radius:8px; cursor:pointer; }
    </style>
</head>
<body>
<header>
    <img src="../../media/logo.png" alt="Claquetazo" class="logo">
    <h1>Bienvenido, <?= htmlspecialchars($nombre) ?></h1>
    <a href="../../logout.php"><button style="background:#8d6e4f;">Cerrar Sesión</button></a>
</header>

<div style="padding:20px;">
    <a href="dashboard.php?action=nueva_pelicula"><button>➕ Enviar Nueva Película</button></a>
</div>

<div class="grid">
    <?php foreach ($peliculas as $p): ?>
        <div class="card" onclick="verReporte(<?= $p['id_pelicula'] ?>)">
            <img src="<?= htmlspecialchars($p['poster']) ?>" alt="<?= htmlspecialchars($p['titulo']) ?>">
            <div class="info">
                <h3><?= htmlspecialchars($p['titulo']) ?> (<?= $p['anio'] ?>)</h3>
                <p><strong>Estado:</strong> <?= $p['estado_peticion'] ?></p>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
function verReporte(id) {
    window.location.href = `modal_reporte.php?id=${id}`;
}
</script>
</body>
</html>