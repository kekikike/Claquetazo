<?php

class Genero {
    public int     $id_genero;
    public string  $nombre;
    public ?string $descripcion = null;
    public string  $fecha_registro;
    public bool    $estado = true;

    public static function fromArray(array $data): self {
        $g = new self();
        foreach ($data as $key => $value) {
            if (property_exists($g, $key)) {
                $g->$key = $value;
            }
        }
        return $g;
    }
}