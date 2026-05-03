<?php

class Rol {
    public int     $id_rol;
    public string  $nombre;
    public ?string $descripcion = null;
    public string  $fecha_registro;
    public bool    $estado = true;

    public static function fromArray(array $data): self {
        $r = new self();
        foreach ($data as $key => $value) {
            if (property_exists($r, $key)) {
                $r->$key = $value;
            }
        }
        return $r;
    }
}