<?php
class PeliculaRepository {
    private $pdo;
    public function __construct($pdo) { $this->pdo = $pdo; }

    public function registrar($id_director, $titulo, $sinopsis, $poster, $anio) {
        $sql = "INSERT INTO Peliculas (id_director, titulo, sinopsis, poster, anio, estado_peticion, tipo_proyeccion, fecha_aceptado) 
                VALUES (?, ?, ?, ?, ?, 'p', NULL, NULL)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id_director, $titulo, $sinopsis, $poster, $anio]);
    }

    public function obtenerPorDirector($id_director) {
        $stmt = $this->pdo->prepare("SELECT * FROM Peliculas WHERE id_director = ? AND estado = 1");
        $stmt->execute([$id_director]);
        return $stmt->fetchAll();
    }

    public function obtenerReporte($id_pelicula) {
        $res = ['sesion' => null, 'funding' => null];
        $stmt = $this->pdo->prepare("SELECT s.capacidad, (SELECT COUNT(*) FROM EspectadorSesion es WHERE es.id_sesion = ses.id_sesion) as ocupados 
                                    FROM Sesiones ses JOIN Salas s ON ses.id_sala = s.id_sala WHERE ses.id_pelicula = ?");
        $stmt->execute([$id_pelicula]);
        $res['sesion'] = $stmt->fetch();

        $stmt = $this->pdo->prepare("SELECT meta, acumulado FROM Funding WHERE id_pelicula = ?");
        $stmt->execute([$id_pelicula]);
        $res['funding'] = $stmt->fetch();
        return $res;
    }
}