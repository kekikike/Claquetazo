<?php
require_once '../Config/db.php';
require_once '../Repository/OrganizadorRepository.php';
require_once '../Repository/PeliculaRepository.php';
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 2) header("Location: ../login.php");

$repoOrg = new OrganizadorRepository($pdo);
$repoPel = new PeliculaRepository($pdo);
$reembolsos = $repoOrg->verificarReembolsos();
$peliculas = $pdo->query("SELECT * FROM Peliculas WHERE estado_peticion = 'aceptado'")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Claquetazo - Organizador</title>
    <style>
        body { background-color: #f5f5dc; font-family: 'Segoe UI', sans-serif; margin: 0; color: #3e2723; }
        nav { background: #8b4513; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; color: white; }
        .container { padding: 2rem; }
        .section { background: #fffaf0; padding: 1.5rem; border-radius: 10px; border: 1px solid #d2b48c; margin-bottom: 2rem; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #d2b48c; padding: 10px; text-align: left; }
        th { background: #8b4513; color: white; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; }
        .card { background: white; padding: 10px; border-radius: 10px; border: 1px solid #d2b48c; text-align: center; }
        .card img { width: 100%; height: 250px; object-fit: cover; border-radius: 5px; }
        .btn { background: #8b4513; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; display: inline-block; }
    </style>
</head>
<body>
    <nav>
        <img src="../media/logo.png" style="height:40px;">
        <div>
            <a href="registrar_entidades.php" style="color:white; margin-right:15px;">Registros</a>
            <a href="gestionar_pendientes.php" style="color:white; margin-right:15px;">Pendientes</a>
            <a href="../logout.php" style="color:white;">Cerrar Sesión</a>
        </div>
    </nav>
    <div class="container">
        <?php if (!empty($reembolsos)): ?>
            <div class="section">
                <h2 style="color:#d32f2f;">⚠️ Reembolsos Pendientes (Funding Fallido)</h2>
                <table>
                    <tr><th>Película</th><th>Espectador</th><th>Monto</th><th>Nro Cuenta</th><th>Fecha Limite</th></tr>
                    <?php foreach ($reembolsos as $r): ?>
                        <tr>
                            <td><?php echo $r['titulo']; ?></td>
                            <td><?php echo $r['nombre1']." ".$r['apellido_paterno']; ?></td>
                            <td><?php echo $r['monto_aporte']; ?> BS</td>
                            <td><?php echo $r['no_cuenta'] ?? 'N/A'; ?></td>
                            <td><?php echo $r['fecha_funding']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        <?php endif; ?>

        <div class="section">
            <h2>Películas en Cartelera / Aceptadas</h2>
            <div class="grid">
                <?php foreach ($peliculas as $p): ?>
                    <div class="card">
                        <img src="<?php echo $p['poster']; ?>">
                        <h4><?php echo $p['titulo']; ?></h4>
                        <p>Estado: <?php echo $p['estado_peticion']; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>