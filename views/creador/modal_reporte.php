<?php
session_start();
require_once __DIR__ . '/../../repository/PeliculaRepository.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['id_rol'] != 1) {
    header("Location: ../../login.php");
    exit;
}

$id_pelicula = (int)($_GET['id'] ?? 0);
$repo = new PeliculaRepository();
$pelicula = $repo->getPeliculaConDetalle($id_pelicula);

if (!$pelicula || $pelicula['id_director'] != $_SESSION['id_usuario']) {
    die("Acceso denegado");
}

// Cálculos
$porcentaje_funding = $pelicula['meta'] > 0 ? round(($pelicula['acumulado'] / $pelicula['meta']) * 100, 1) : 0;
$ocupacion_promedio = $pelicula['total_sesiones'] > 0 ? 
    round(($pelicula['boletos_vendidos'] / ($pelicula['total_sesiones'] * 150)) * 100, 1) : 0; // aproximando capacidad
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte - <?= htmlspecialchars($pelicula['titulo']) ?></title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f8f1e9;
            margin: 0;
            padding: 20px;
        }
        .report {
            max-width: 900px;
            margin: 30px auto;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .header {
            background: #c9a97e;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content { padding: 30px; }
        .poster {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 25px;
        }
        .stat-card {
            background: #f8f1e9;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
        }
        .progress {
            height: 25px;
            background: #e6d5b8;
            border-radius: 20px;
            overflow: hidden;
            margin: 10px 0;
        }
        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #c9a97e, #8d6e4f);
            text-align: center;
            color: white;
            line-height: 25px;
            font-weight: bold;
        }
        button {
            margin-top: 20px;
            padding: 12px 25px;
            background: #8d6e4f;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="report">
    <div class="header">
        <h1><?= htmlspecialchars($pelicula['titulo']) ?> (<?= $pelicula['anio'] ?>)</h1>
    </div>
    <div class="content">
        <img src="<?= htmlspecialchars($pelicula['poster']) ?>" class="poster" alt="Poster">

        <h2>Estado actual: <span style="color:#c9a97e;"><?= ucfirst($pelicula['estado_peticion']) ?></span></h2>

        <div class="stats">
            <!-- Funding -->
            <?php if ($pelicula['meta'] > 0): ?>
            <div class="stat-card">
                <h3>Funding</h3>
                <p><strong>Acumulado:</strong> Bs <?= number_format($pelicula['acumulado'], 2) ?> / <?= number_format($pelicula['meta'], 2) ?></p>
                <div class="progress">
                    <div class="progress-bar" style="width: <?= $porcentaje_funding ?>%;">
                        <?= $porcentaje_funding ?>%
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Sesiones -->
            <div class="stat-card">
                <h3>Sesiones</h3>
                <p><strong>Total sesiones:</strong> <?= $pelicula['total_sesiones'] ?></p>
                <p><strong>Boletos vendidos:</strong> <?= $pelicula['boletos_vendidos'] ?? 0 ?></p>
                <?php if ($pelicula['total_sesiones'] > 0): ?>
                <div class="progress">
                    <div class="progress-bar" style="width: <?= $ocupacion_promedio ?>%;">
                        <?= $ocupacion_promedio ?>% ocupación
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <center>
            <a href="dashboard.php"><button>← Volver al Dashboard</button></a>
        </center>
    </div>
</div>
</body>
</html>