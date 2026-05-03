<?php
require_once '../Config/db.php';
require_once '../Repository/PeliculaRepository.php';
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 1) header("Location: ../login.php");

$repo = new PeliculaRepository($pdo);
$peliculas = $repo->obtenerPorDirector($_SESSION['usuario']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Claquetazo - Panel Creador</title>
    <style>
        body { background-color: #f5f5dc; font-family: 'Segoe UI', sans-serif; margin: 0; color: #3e2723; }
        nav { background: #8b4513; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; color: white; }
        .logo-nav { height: 40px; }
        .container { padding: 2rem; }
        .btn { background: #8b4513; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; margin-top: 2rem; }
        .card { background: white; padding: 10px; border-radius: 10px; border: 1px solid #d2b48c; cursor: pointer; text-align: center; transition: 0.3s; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .card img { width: 100%; border-radius: 5px; height: 250px; object-fit: cover; }
        #modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; }
        .modal-content { background: #fffaf0; padding: 2rem; border-radius: 15px; width: 400px; position: relative; border: 2px solid #8b4513; }
        .progress-bar { background: #e0e0e0; border-radius: 10px; height: 20px; margin: 10px 0; overflow: hidden; }
        .progress-fill { background: #8b4513; height: 100%; transition: 0.5s; }
    </style>
</head>
<body>
    <nav>
        <img src="../media/logo.png" class="logo-nav">
        <div>
            <span>Bienvenido, <?php echo $_SESSION['nombre']; ?></span>
            <a href="../logout.php" style="color:white; margin-left:20px;">Cerrar Sesión</a>
        </div>
    </nav>
    <div class="container">
        <a href="enviar_obra.php" class="btn">Enviar Nueva Película</a>
        <div class="grid">
            <?php foreach ($peliculas as $p): ?>
                <div class="card" onclick="verReporte(<?php echo $p['id_pelicula']; ?>, '<?php echo $p['titulo']; ?>')">
                    <img src="<?php echo $p['poster']; ?>">
                    <h4><?php echo $p['titulo']; ?></h4>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div id="modal">
        <div class="modal-content">
            <h3 id="m-titulo"></h3>
            <div id="m-reporte"></div>
            <button onclick="document.getElementById('modal').style.display='none'" class="btn" style="width:100%; margin-top:15px;">Cerrar</button>
        </div>
    </div>

    <script>
        function verReporte(id, titulo) {
            fetch('../Controlador/PeliculaController.php?ajax_reporte=1&id_pelicula=' + id)
                .then(r => r.json())
                .then(data => {
                    document.getElementById('m-titulo').innerText = titulo;
                    let html = "";
                    if(data.sesion) {
                        let p = (data.sesion.ocupados / data.sesion.capacidad * 100).toFixed(1);
                        html += `<h4>Asistencia Proyección: ${p}%</h4><div class="progress-bar"><div class="progress-fill" style="width:${p}%"></div></div>`;
                    }
                    if(data.funding) {
                        let p = (data.funding.acumulado / data.funding.meta * 100).toFixed(1);
                        html += `<h4>Meta Funding: ${p}%</h4><div class="progress-bar"><div class="progress-fill" style="width:${p}%"></div></div>`;
                    }
                    if(!data.sesion && !data.funding) html = "<p>Sin datos de reportes disponibles aún.</p>";
                    document.getElementById('m-reporte').innerHTML = html;
                    document.getElementById('modal').style.display = 'flex';
                });
        }
    </script>
</body>
</html>