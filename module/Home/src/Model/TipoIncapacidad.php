<?php
namespace Home\Model;

class TipoIncapacidad
{
    public $id_tipoincapacidad;
    public $tipoincapacidad;
    public $dias_empresa;
    public $es_arl;
    
    public function exchangeArray(array $data){
        
        $this->id_tipoincapacidad       = !empty($data["id_tipoincapacidad"]) ? $data["id_tipoincapacidad"] : null;
        $this->tipoincapacidad   = !empty($data["tipoincapacidad"]) ? $data["tipoincapacidad"] : null;
        $this->dias_empresa= !empty($data["dias_empresa"]) ? $data["dias_empresa"] : 0;
        $this->es_arl= !empty($data["es_arl"]) ? $data["es_arl"] : 0;
        
    }
}