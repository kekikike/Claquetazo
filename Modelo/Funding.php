<?php

class Funding {
    public int     $id_funding;
    public int     $id_pelicula;
    public float   $meta;
    public float   $acumulado = 0.00;
    public bool    $estado_funding = false;
    public ?string $fecha_funding = null;
    public ?string $fecha_sin_costo = null;
    public string  $fecha_registro;
    public bool    $estado = true;

    // Extras
    public string  $titulo_pelicula = '';

    public function getPorcentajeAvance(): float {
        if ($this->meta == 0) return 0;
        return min(100, ($this->acumulado / $this->meta) * 100);
    }

    public static function fromArray(array $data): self {
        $f = new self();
        foreach ($data as $key => $value) {
            if (property_exists($f, $key)) {
                $f->$key = $value;
            }
        }
        return $f;
    }
}