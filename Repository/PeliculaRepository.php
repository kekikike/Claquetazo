<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Pelicula.php';
require_once __DIR__ . '/../models/Funding.php';
require_once __DIR__ . '/../models/Sesion.php';

class PeliculaRepository {

    public function insertar(Pelicula $p) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO Peliculas 
            (id_director, titulo, tipo_proyeccion, sinopsis, poster, anio, estado_peticion) 
            VALUES (?, ?, NULL, ?, ?, ?, 'pendiente')");
        
        $stmt->execute([
            $p->id_director,
            $p->titulo,
            $p->sinopsis,
            $p->poster,
            $p->anio
        ]);
        return $pdo->lastInsertId();
    }

    public function getPeliculasByDirector(int $id_director): array {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT p.*, 
                   COALESCE(f.acumulado, 0) as acumulado,
                   f.meta,
                   COUNT(s.id_sesion) as total_sesiones
            FROM Peliculas p
            LEFT JOIN Funding f ON f.id_pelicula = p.id_pelicula
            LEFT JOIN Sesiones s ON s.id_pelicula = p.id_pelicula
            WHERE p.id_director = ? AND p.estado = TRUE
            GROUP BY p.id_pelicula
        ");
        $stmt->execute([$id_director]);
        return $stmt->fetchAll();
    }

    public function getPeliculaConDetalle(int $id_pelicula) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT p.*, 
                   COALESCE(f.acumulado, 0) as acumulado,
                   f.meta,
                   f.estado_funding,
                   COUNT(s.id_sesion) as total_sesiones,
                   SUM(CASE WHEN es.id_espectador_sesion IS NOT NULL THEN 1 ELSE 0 END) as boletos_vendidos
            FROM Peliculas p
            LEFT JOIN Funding f ON f.id_pelicula = p.id_pelicula
            LEFT JOIN Sesiones s ON s.id_pelicula = p.id_pelicula
            LEFT JOIN EspectadorSesion es ON es.id_sesion = s.id_sesion
            WHERE p.id_pelicula = ?
            GROUP BY p.id_pelicula
        ");
        $stmt->execute([$id_pelicula]);
        return $stmt->fetch();
    }
}