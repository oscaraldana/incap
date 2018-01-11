<?php
namespace Home\Model;

class Usuarios
{
    public $id_usuario;
    public $login;
    public $passwd;
    public $estado;
    
    public function exchangeArray(array $data){
        
        $this->id_usuario       = !empty($data["id_usuario"]) ? $data["id_usuario"] : null;
        $this->login   = !empty($data["login"]) ? $data["login"] : null;
        $this->passwd   = !empty($data["passwd"]) ? $data["passwd"] : null;
        $this->estado   = !empty($data["estado"]) ? $data["estado"] : null;
        
    }
}