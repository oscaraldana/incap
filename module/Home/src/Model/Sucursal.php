<?php
namespace Home\Model;

class Sucursal
{
    public $id_sucursal;
    public $sucursal;
    
    public function exchangeArray(array $data){
        
        $this->id_sucursal       = !empty($data["id_sucursal"]) ? $data["id_sucursal"] : null;
        $this->sucursal   = !empty($data["sucursal"]) ? $data["sucursal"] : null;
        
    }
}