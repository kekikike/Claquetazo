<?php

class Pelicula {
    public int      $id_pelicula;
    public int      $id_director;
    public string   $titulo;
    public ?string  $tipo_proyeccion = null;
    public ?string  $sinopsis = null;
    public ?string  $poster = null;
    public ?int     $anio = null;
    public string   $estado_peticion = 'pendiente'; // pendiente, aceptado, rechazado
    public ?string  $fecha_aceptado = null;
    public string   $fecha_registro;
    public bool     $estado = true;

    // Campos extras (JOIN)
    public string   $nombre_director = '';
    public array    $generos = [];           // array de nombres de géneros

    public function getNombreCompletoDirector(): string {
        return $this->nombre_director;
    }

    public static function fromArray(array $data): self {
        $p = new self();
        foreach ($data as $key => $value) {
            if (property_exists($p, $key)) {
                $p->$key = $value;
            }
        }
        return $p;
    }
}