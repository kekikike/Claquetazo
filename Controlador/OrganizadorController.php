<?php
require_once '../Config/db.php';
require_once '../Repository/OrganizadorRepository.php';
session_start();

$repo = new OrganizadorRepository($pdo);

if (isset($_POST['btn_accion'])) {
    $id_p = $_POST['id_pelicula'];

    if ($_POST['btn_accion'] == 'rechazar') {
        $repo->rechazarPelicula($id_p);
        header("Location: ../Vista/gestionar_pendientes.php?msg=rechazada");
        exit;
    }

    if (!$repo->esSalaDisponible($_POST['id_sala'], $_POST['fecha_s'], $_POST['hora_s'])) {
        header("Location: ../Vista/gestionar_pendientes.php?error=sala_ocupada&id_error=$id_p");
        exit;
    }

    if ($repo->actualizarPeliculaYProyeccion($_POST)) {
        header("Location: ../Vista/gestionar_pendientes.php?success=1");
    } else {
        header("Location: ../Vista/gestionar_pendientes.php?error=db");
    }
}