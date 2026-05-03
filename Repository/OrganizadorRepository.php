<?php
class OrganizadorRepository {
    private $pdo;
    public function __construct($pdo) { $this->pdo = $pdo; }

    public function verificarReembolsos() {
        $sql = "SELECT f.*, p.titulo, fe.monto_aporte, pa.no_cuenta 
                FROM Funding f 
                JOIN Peliculas p ON f.id_pelicula = p.id_pelicula
                JOIN FundingEspectador fe ON f.id_funding = fe.id_funding
                JOIN Espectador e ON fe.id_espectador = e.id_espectador
                LEFT JOIN Pagos pa ON e.id_espectador = pa.id_espectador
                WHERE f.acumulado < f.meta AND f.fecha_funding < CURDATE()";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function procesarPendientes() {
        $this->pdo->query("UPDATE Peliculas SET estado_peticion = 'e' WHERE estado_peticion = 'p'");
    }

    public function obtenerSalas() {
        return $this->pdo->query("SELECT * FROM Salas WHERE estado = 1")->fetchAll();
    }

    public function obtenerPendientes() {
        $stmt = $this->pdo->query("SELECT p.*, u.nombre1, u.apellido_paterno FROM Peliculas p 
                                  JOIN Usuarios u ON p.id_director = u.id_usuario 
                                  WHERE p.estado_peticion = 'e'");
        return $stmt->fetchAll();
    }

    public function esSalaDisponible($id_sala, $fecha, $hora) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM Sesiones WHERE id_sala = ? AND fecha = ? AND hora = ? AND estado = 1");
        $stmt->execute([$id_sala, $fecha, $hora]);
        return $stmt->fetchColumn() == 0;
    }

    public function actualizarPeliculaYProyeccion($data) {
        $this->pdo->beginTransaction();
        try {
            $stmtP = $this->pdo->prepare("UPDATE Peliculas SET titulo = ?, sinopsis = ?, anio = ?, estado_peticion = 'aceptado', tipo_proyeccion = ? WHERE id_pelicula = ?");
            $stmtP->execute([$data['titulo'], $data['sinopsis'], $data['anio'], $data['tipo'], $data['id_pelicula']]);

            $stmtS = $this->pdo->prepare("INSERT INTO Sesiones (id_pelicula, id_sala, fecha, hora, costo) VALUES (?, ?, ?, ?, ?)");
            $stmtS->execute([$data['id_pelicula'], $data['id_sala'], $data['fecha_s'], $data['hora_s'], $data['costo_s']]);

            if ($data['tipo'] == 'funding') {
                $stmtF = $this->pdo->prepare("INSERT INTO Funding (id_pelicula, meta, fecha_funding, fecha_sin_costo) VALUES (?, ?, ?, ?)");
                $stmtF->execute([$data['id_pelicula'], $data['meta'], $data['fecha_f'], $data['fecha_sc']]);
            }
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function rechazarPelicula($id) {
        $stmt = $this->pdo->prepare("UPDATE Peliculas SET estado_peticion = 'rechazada' WHERE id_pelicula = ?");
        return $stmt->execute([$id]);
    }
}