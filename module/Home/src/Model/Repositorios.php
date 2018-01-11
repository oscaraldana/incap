<?php
namespace  Home\Model;

class Repositorios {
    
    function __construct(IdentityMapInterface $identityMap, DataMapperInterface $dataMapper){
        
    }
    
 
    public function getTipIncap ($id = null){
        return new tipIncap($id);
    } 
    
}