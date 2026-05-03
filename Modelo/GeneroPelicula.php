<?php

class GeneroPelicula {
    public int    $id_genero_pelicula;
    public int    $id_genero;
    public int    $id_pelicula;
    public string $fecha_registro;
    public bool   $estado = true;

    public static function fromArray(array $data): self {
        $gp = new self();
        foreach ($data as $key => $value) {
            if (property_exists($gp, $key)) {
                $gp->$key = $value;
            }
        }
        return $gp;
    }
}