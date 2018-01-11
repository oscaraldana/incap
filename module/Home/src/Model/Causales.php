<?php
namespace Home\Model;

class Causales
{
    public $id_causal;
    public $causal;
    
    public function exchangeArray(array $data){
        
        $this->id_causal       = !empty($data["id_causal"]) ? $data["id_causal"] : null;
        $this->causal   = !empty($data["causal"]) ? $data["causal"] : null;
        
    }
}