<?php

class Usuario {
    public int     $id_usuario;
    public int     $id_rol;
    public string  $nombre1;
    public string  $nombre2 = '';
    public string  $apellido_paterno;
    public string  $apellido_materno = '';
    public string  $correo;
    public string  $contrasena;
    public ?string $fecha_nacimiento = null;
    public string  $telefono = '';
    public string  $fecha_registro;
    public bool    $estado = true;

    // Campos extras útiles al hacer JOIN
    public string  $nombre_rol = '';

    public function getNombreCompleto(): string {
        $partes = [$this->nombre1, $this->nombre2, $this->apellido_paterno, $this->apellido_materno];
        return trim(implode(' ', array_filter($partes)));
    }

    public static function fromArray(array $data): self {
        $u = new self();
        foreach ($data as $key => $value) {
            if (property_exists($u, $key)) {
                $u->$key = $value;
            }
        }
        return $u;
    }
}