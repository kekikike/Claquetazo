<?php

class FundingEspectador {
    public int   $id_funding_espectador;
    public int   $id_espectador;
    public int   $id_funding;
    public float $monto_aporte;
    public string $fecha_registro;
    public bool  $estado = true;

    public static function fromArray(array $data): self {
        $fe = new self();
        foreach ($data as $key => $value) {
            if (property_exists($fe, $key)) {
                $fe->$key = $value;
            }
        }
        return $fe;
    }
}