<?php

class Sesion {
    public int     $id_sesion;
    public int     $id_pelicula;
    public int     $id_sala;
    public string  $fecha;           // DATE
    public string  $hora;            // TIME
    public float   $costo;
    public string  $fecha_registro;
    public bool    $estado = true;

    // Campos extras útiles
    public string  $titulo_pelicula = '';
    public string  $nombre_sala = '';
    public int     $capacidad_sala = 0;

    public function getFechaHora(): string {
        return $this->fecha . ' ' . $this->hora;
    }

    public static function fromArray(array $data): self {
        $s = new self();
        foreach ($data as $key => $value) {
            if (property_exists($s, $key)) {
                $s->$key = $value;
            }
        }
        return $s;
    }
}