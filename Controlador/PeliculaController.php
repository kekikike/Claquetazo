<?php
require_once '../Config/db.php';
require_once '../Repository/PeliculaRepository.php';
session_start();

$repo = new PeliculaRepository($pdo);

if (isset($_POST['registrar_obra'])) {
    $titulo = $_POST['titulo'];
    $anio = $_POST['anio'];
    $ext = pathinfo($_FILES['poster']['name'], PATHINFO_EXTENSION);
    $nombre_archivo = strtolower(str_replace(' ', '', $titulo)) . $anio . "." . $ext;
    $ruta_destino = "../media/" . $nombre_archivo;

    if (move_uploaded_file($_FILES['poster']['tmp_name'], $ruta_destino)) {
        $repo->registrar($_SESSION['usuario'], $titulo, $_POST['sinopsis'], $ruta_destino, $anio);
        header("Location: ../Vista/creador_dashboard.php?success=1");
    }
}

if (isset($_GET['ajax_reporte'])) {
    echo json_encode($repo->obtenerReporte($_GET['id_pelicula']));
    exit;
}