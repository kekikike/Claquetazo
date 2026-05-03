<?php

class Espectador {
    public int     $id_espectador;
    public string  $nombre1;
    public string  $nombre2 = '';
    public string  $apellido_paterno;
    public string  $apellido_materno = '';
    public string  $correo;
    public string  $contrasena;
    public string  $telefono = '';
    public string  $fecha_registro;
    public bool    $estado = true;

    public function getNombreCompleto(): string {
        $partes = [$this->nombre1, $this->nombre2, $this->apellido_paterno, $this->apellido_materno];
        return trim(implode(' ', array_filter($partes)));
    }

    public static function fromArray(array $data): self {
        $e = new self();
        foreach ($data as $key => $value) {
            if (property_exists($e, $key)) {
                $e->$key = $value;
            }
        }
        return $e;
    }
}