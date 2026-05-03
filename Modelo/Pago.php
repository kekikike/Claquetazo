<?php

class Pago {
    public int     $id_pago;
    public int     $id_espectador;
    public int     $id_sesion;
    public ?string $metodo_pago = null;
    public float   $monto;
    public ?int    $no_cuenta = null;
    public string  $fecha_registro;
    public bool    $estado = true;

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