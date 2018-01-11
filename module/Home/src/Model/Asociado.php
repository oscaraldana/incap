<?php
namespace Home\Model;

class Asociado
{
    public $id_asociado;
    public $nombre;
    public $apellido1;
    public $apellido2;
    public $cedula;
    
    public function exchangeArray(array $data){
        
        $this->id_asociado  = !empty($data["id_asociado"]) ? $data["id_asociado"] : null;
        $this->nombre_asociado       = !empty($data["nombre_asociado"]) ? $data["nombre_asociado"] : null;
        $this->apellido1_asociado    = !empty($data["apellido1_asociado"]) ? $data["apellido1_asociado"] : null;
        $this->apellido2_asociado   = !empty($data["apellido2_asociado"]) ? $data["apellido2_asociado"] : null;
        $this->identificacion       = !empty($data["identificacion"]) ? $data["identificacion"] : null;
        
    }
}