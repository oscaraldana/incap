<?php
namespace Home\Model;

class Diagnostico
{
    public $id_diagnostico;
    public $nombre;
    public $codigo;
    
    public function exchangeArray(array $data){
        
        $this->id_diagnostico       = !empty($data["id_diagnostico"]) ? $data["id_diagnostico"] : null;
        $this->nombre_diagnostico   = !empty($data["nombre_diagnostico"]) ? $data["nombre_diagnostico"] : null;
        $this->codigo_diagnostico   = !empty($data["codigo_diagnostico"]) ? $data["codigo_diagnostico"] : null;
        
    }
}