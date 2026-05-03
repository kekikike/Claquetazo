<?php
session_start();
require_once __DIR__ . '/../repository/PeliculaRepository.php';

class CreadorController {
    private $repo;

    public function __construct() {
        if (!isset($_SESSION['id_usuario']) || $_SESSION['id_rol'] != 1) {
            header("Location: ../../login.php");
            exit;
        }
        $this->repo = new PeliculaRepository();
    }

    public function dashboard() {
        $peliculas = $this->repo->getPeliculasByDirector($_SESSION['id_usuario']);
        require_once __DIR__ . '/../views/creador/dashboard.php';
    }

    public function mostrarFormularioPelicula() {
        require_once __DIR__ . '/../views/creador/pelicula_form.php';
    }

    public function guardarPelicula() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titulo = trim($_POST['titulo']);
            $sinopsis = trim($_POST['sinopsis']);
            $anio = (int)$_POST['anio'];

            // Subida de imagen
            $posterPath = '';
            if (isset($_FILES['poster']) && $_FILES['poster']['error'] === 0) {
                $ext = pathinfo($_FILES['poster']['name'], PATHINFO_EXTENSION);
                $nombreArchivo = preg_replace('/[^A-Za-z0-9]/', '', $titulo) . $anio . '.' . strtolower($ext);
                $rutaDestino = __DIR__ . '/../media/' . $nombreArchivo;

                if (move_uploaded_file($_FILES['poster']['tmp_name'], $rutaDestino)) {
                    $posterPath = '../media/' . $nombreArchivo;
                }
            }

            $pelicula = new Pelicula();
            $pelicula->id_director = $_SESSION['id_usuario'];
            $pelicula->titulo = $titulo;
            $pelicula->sinopsis = $sinopsis;
            $pelicula->anio = $anio;
            $pelicula->poster = $posterPath;

            $this->repo->insertar($pelicula);

            header("Location: dashboard.php?success=1");
            exit;
        }
    }
}

// Router simple
$controller = new CreadorController();
$action = $_GET['action'] ?? 'dashboard';

if ($action === 'nueva_pelicula') {
    $controller->mostrarFormularioPelicula();
} elseif ($action === 'guardar_pelicula') {
    $controller->guardarPelicula();
} else {
    $controller->dashboard();
}