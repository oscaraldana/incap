<?php
namespace Home\Model;

class Eps
{
    public $id_eps;
    public $nombre_eps;
    public $direccion_eps;
    public $telefono_eps;
    
    public function exchangeArray(array $data){
        
        $this->id_eps       = !empty($data["id_eps"]) ? $data["id_eps"] : null;
        $this->nombre_eps   = !empty($data["nombre_eps"]) ? $data["nombre_eps"] : null;
        $this->direccion_eps   = !empty($data["direccion_eps"]) ? $data["direccion_eps"] : null;
        $this->telefono_eps   = !empty($data["telefono_eps"]) ? $data["telefono_eps"] : null;
        
    }
}