<?php
require_once '../Config/db.php';
require_once '../Repository/OrganizadorRepository.php';
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 2) header("Location: ../login.php");

$repo = new OrganizadorRepository($pdo);
$repo->procesarPendientes();
$pendientes = $repo->obtenerPendientes();
$salas = $repo->obtenerSalas();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Claquetazo - Pendientes</title>
    <style>
        :root { --cafe: #5d4037; --beige: #d7ccc8; --fondo: #efebe9; --crema: #fffaf0; --verde: #827717; --rojo: #bf360c; }
        body { background: var(--fondo); font-family: 'Arial', sans-serif; margin: 0; color: var(--cafe); }
        .nav { background: var(--cafe); padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; color: white; }
        .grid-posters { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 30px; padding: 40px; }
        .poster-card { background: black; border-radius: 12px; overflow: hidden; cursor: pointer; position: relative; transition: 0.3s; box-shadow: 0 10px 20px rgba(0,0,0,0.2); aspect-ratio: 2/3; }
        .poster-card:hover { transform: scale(1.05); }
        .poster-card img { width: 100%; height: 100%; object-fit: cover; }
        .poster-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(93, 64, 55, 0.4); opacity: 0; display: flex; justify-content: center; align-items: center; transition: 0.3s; }
        .poster-card:hover .poster-overlay { opacity: 1; }
        .poster-overlay span { color: white; font-weight: bold; font-size: 1.2rem; border: 2px solid white; padding: 10px 20px; border-radius: 30px; background: rgba(0,0,0,0.3); }

        #modalEdit { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 1000; justify-content: center; align-items: center; }
        .modal-content { background: var(--crema); width: 90%; max-width: 800px; border-radius: 20px; display: flex; overflow: hidden; position: relative; animation: slideIn 0.4s ease; }
        @keyframes slideIn { from { transform: translateY(50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        
        .modal-left { width: 40%; background: #000; }
        .modal-left img { width: 100%; height: 100%; object-fit: cover; }
        .modal-right { width: 60%; padding: 30px; overflow-y: auto; max-height: 85vh; }
        
        input, select, textarea { width: 100%; padding: 12px; margin: 8px 0; border: 1px solid var(--beige); border-radius: 8px; box-sizing: border-box; background: white; color: var(--cafe); font-size: 14px; }
        .btn-group { display: flex; gap: 15px; margin-top: 20px; }
        .btn { flex: 1; padding: 15px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; color: white; transition: 0.3s; }
        .btn-ok { background: var(--verde); }
        .btn-no { background: var(--rojo); }
        .btn-close { position: absolute; top: 15px; right: 20px; font-size: 24px; cursor: pointer; color: var(--cafe); z-index: 10; }
        
        .info-cap { background: var(--beige); padding: 8px; border-radius: 5px; font-size: 13px; margin-bottom: 10px; display: inline-block; }
        .funding-box { border: 2px dashed var(--beige); padding: 15px; border-radius: 10px; margin-top: 15px; display: none; }
    </style>
</head>
<body>

    <div class="nav">
        <img src="../media/logo.png" height="50">
        <a href="organizador_dashboard.php" style="color:white; text-decoration:none; font-weight:bold;">VOLVER AL PANEL</a>
    </div>

    <div class="grid-posters">
        <?php foreach ($pendientes as $p): ?>
            <div class="poster-card" onclick="openModal(<?php echo htmlspecialchars(json_encode($p)); ?>)">
                <img src="<?php echo $p['poster']; ?>">
                <div class="poster-overlay"><span>CONFIGURAR</span></div>
            </div>
        <?php endforeach; ?>
    </div>

    <div id="modalEdit">
        <div class="modal-content">
            <span class="btn-close" onclick="closeModal()">&times;</span>
            <div class="modal-left">
                <img id="m-img" src="">
            </div>
            <div class="modal-right">
                <h2 id="m-tit-h" style="margin-top:0; color:var(--cafe);"></h2>
                <form action="../Controlador/OrganizadorController.php" method="POST">
                    <input type="hidden" name="id_pelicula" id="m-id">
                    
                    <label>Título:</label>
                    <input type="text" name="titulo" id="m-titulo" required>
                    
                    <label>Año:</label>
                    <input type="number" name="anio" id="m-anio" required>
                    
                    <label>Sinopsis:</label>
                    <textarea name="sinopsis" id="m-sinopsis" rows="4" required></textarea>

                    <label>Sala:</label>
                    <select name="id_sala" onchange="checkCap(this)" required>
                        <option value="">Seleccione Sala...</option>
                        <?php foreach($salas as $s): ?>
                            <option value="<?php echo $s['id_sala']; ?>" data-cap="<?php echo $s['capacidad']; ?>">
                                <?php echo $s['nombre_sala']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="info-cap">Capacidad: <span id="cap-val">0</span> personas</div>

                    <div style="display:flex; gap:10px;">
                        <div style="flex:1;"><label>Fecha:</label><input type="date" name="fecha_s" required></div>
                        <div style="flex:1;"><label>Hora:</label><input type="time" name="hora_s" required></div>
                    </div>
                    
                    <label>Costo Entrada (BS):</label>
                    <input type="number" name="costo_s" required>

                    <label>Tipo Proyección:</label>
                    <select name="tipo" onchange="toggleF(this)" required>
                        <option value="tradicional">Tradicional</option>
                        <option value="funding">Funding (Crowdticketing)</option>
                    </select>

                    <div id="f-box" class="funding-box">
                        <label>Meta Requerida:</label>
                        <input type="number" name="meta">
                        <label>Fecha Límite Funding:</label>
                        <input type="date" name="fecha_f">
                        <label>Fecha Estreno Gratuito:</label>
                        <input type="date" name="fecha_sc">
                    </div>

                    <div class="btn-group">
                        <button type="submit" name="btn_accion" value="aceptar" class="btn btn-ok">APROBAR PELÍCULA</button>
                        <button type="submit" name="btn_accion" value="rechazar" class="btn btn-no">RECHAZAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modalEdit');

        function openModal(pelicula) {
            document.getElementById('m-id').value = pelicula.id_pelicula;
            document.getElementById('m-img').src = pelicula.poster;
            document.getElementById('m-tit-h').innerText = pelicula.titulo;
            document.getElementById('m-titulo').value = pelicula.titulo;
            document.getElementById('m-anio').value = pelicula.anio;
            document.getElementById('m-sinopsis').value = pelicula.sinopsis;
            modal.style.display = 'flex';
        }

        function closeModal() {
            modal.style.display = 'none';
        }

        function checkCap(sel) {
            const cap = sel.options[sel.selectedIndex].getAttribute('data-cap') || 0;
            document.getElementById('cap-val').innerText = cap;
        }

        function toggleF(sel) {
            document.getElementById('f-box').style.display = (sel.value === 'funding') ? 'block' : 'none';
        }

        window.onclick = function(event) {
            if (event.target == modal) closeModal();
        }
    </script>
</body>
</html>