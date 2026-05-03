<?php

class Sala {
    public int    $id_sala;
    public string $nombre_sala;
    public int    $capacidad;
    public string $fecha_registro;
    public bool   $estado = true;

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