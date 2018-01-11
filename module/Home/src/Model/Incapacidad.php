<?php
namespace Home\Model;

class Incapacidad
{
    public $id_incapacidad;
    public $no_incapacidad;
    public $id_asociado;
    public $id_diagnostico;
    public $fecha_ini;
    public $fecha_fin;
    public $n_dias_emp;
    public $n_dias_eps;
    public $n_dias_arl;
    public $valor_facturado;
    public $valor_pago_eps;
    public $cod_tesoreria;
    public $fecha_pago;
    public $id_causal;
    public $id_inc_prorroga;
    public $id_eps;
    public $id_sucursal;
    public $id_tipoincapacidad;
    public $fecha_rad;
    public $nombre;
    public $apellido1;
    public $apellido2;
    
    
    public function exchangeArray(array $data){
        
        $this->id_incapacidad       = !empty($data["id_incapacidad"]) ? $data["id_incapacidad"] : null;
        $this->no_incapacidad   = !empty($data["no_incapacidad"]) ? $data["no_incapacidad"] : null;
        $this->id_asociado   = !empty($data["id_asociado"]) ? $data["id_asociado"] : null;
        $this->id_diagnostico   = !empty($data["id_diagnostico"]) ? $data["id_diagnostico"] : null;
        $this->fecha_ini   = !empty($data["fecha_ini"]) ? $data["fecha_ini"] : null;
        $this->fecha_fin   = !empty($data["fecha_fin"]) ? $data["fecha_fin"] : null;
        $this->n_dias_emp   = !empty($data["n_dias_emp"]) ? $data["n_dias_emp"] : null;
        $this->n_dias_eps   = !empty($data["n_dias_eps"]) ? $data["n_dias_eps"] : null;
        $this->n_dias_arl   = !empty($data["n_dias_arl"]) ? $data["n_dias_arl"] : null;
        $this->valor_facturado   = !empty($data["valor_facturado"]) ? $data["valor_facturado"] : null;
        $this->valor_pago_eps   = !empty($data["valor_pago_eps"]) ? $data["valor_pago_eps"] : null;
        $this->cod_tesoreria   = !empty($data["cod_tesoreria"]) ? $data["cod_tesoreria"] : null;
        $this->fecha_pago   = !empty($data["fecha_pago"]) ? $data["fecha_pago"] : null;
        
        $this->id_causal   = !empty($data["id_causal"]) ? $data["id_causal"] : null;
        $this->id_inc_prorroga   = !empty($data["id_inc_prorroga"]) ? $data["id_inc_prorroga"] : null;
        $this->id_eps   = !empty($data["id_eps"]) ? $data["id_eps"] : null;
       
        $this->id_sucursal   = !empty($data["id_sucursal"]) ? $data["id_sucursal"] : null;
        $this->id_tipoincapacidad   = !empty($data["id_tipoincapacidad"]) ? $data["id_tipoincapacidad"] : null;
        $this->fecha_rad   = !empty($data["fecha_rad"]) ? $data["fecha_rad"] : null;
        
        $this->nombre   = (isset($data["nombre_asociado"]) && !empty($data["nombre_asociado"])) ? $data["nombre_asociado"] : null;
        $this->apellido1   = !empty($data["apellido1_asociado"]) ? $data["apellido1_asociado"] : null;
        $this->apellido2   = !empty($data["apellido2_asociado"]) ? $data["apellido2_asociado"] : null;
        $this->eps   = (isset($data["nombre_eps"]) && !empty($data["nombre_eps"])) ? $data["nombre_eps"] : null;
        $this->sucursal   = (isset($data["sucursal"]) && !empty($data["sucursal"])) ? $data["sucursal"] : null;
        $this->diagnostico   = (isset($data["nombre_diagnostico"]) && !empty($data["nombre_diagnostico"])) ? $data["nombre_diagnostico"] : null;
        $this->cod_diagnostico   = (isset($data["codigo_diagnostico"]) && !empty($data["codigo_diagnostico"])) ? $data["codigo_diagnostico"] : null;
        $this->tipoincap   = (isset($data["tipoincapacidad"]) && !empty($data["tipoincapacidad"])) ? $data["tipoincapacidad"] : null;
        $this->cedula   = (isset($data["identificacion"]) && !empty($data["identificacion"])) ? $data["identificacion"] : null;
        $this->codigo   = (isset($data["codigo_diagnostico"]) && !empty($data["codigo_diagnostico"])) ? $data["codigo_diagnostico"] : null;
        
        //var_export($data); echo "<hr>";
    }
    
    public function exchangeArrayForm(array $data){
    
        $this->id_incapacidad       = !empty($data["id_incapacidad"]) ? $data["id_incapacidad"] : null;
        $this->no_incapacidad   = !empty($data["nincapacidad"]) ? $data["nincapacidad"] : null;
        $this->id_asociado   = !empty($data["cedula"]) ? $data["cedula"] : null;
        $this->id_diagnostico   = !empty($data["diagnostico"]) ? $data["diagnostico"] : null;
        $this->fecha_ini   = !empty($data["fechainicial"]) ? $data["fechainicial"] : null;
        $this->fecha_fin   = !empty($data["fechafinal"]) ? $data["fechafinal"] : null;
        $this->n_dias_emp   = !empty($data["diasempresa"]) ? $data["diasempresa"] : null;
        $this->n_dias_eps   = !empty($data["diaseps"]) ? $data["diaseps"] : null;
        $this->n_dias_arl   = !empty($data["diasarl"]) ? $data["diasarl"] : null;
        //$this->valor_facturado   = !empty($data["valor_facturado"]) ? $data["valor_facturado"] : null;
        //$this->valor_pago_eps   = !empty($data["valor_pago_eps"]) ? $data["valor_pago_eps"] : null;
        //$this->cod_tesoreria   = !empty($data["cod_tesoreria"]) ? $data["cod_tesoreria"] : null;
        //$this->fecha_pago   = !empty($data["fecha_pago"]) ? $data["fecha_pago"] : null;
    
        $this->id_causal   = !empty($data["causal"]) ? $data["causal"] : null;
        $this->id_inc_prorroga   = !empty($data["id_inc_prorroga"]) ? $data["id_inc_prorroga"] : null;
        $this->id_eps   = !empty($data["eps"]) ? $data["eps"] : null;
         
        $this->id_sucursal   = !empty($data["sucursal"]) ? $data["sucursal"] : null;
        $this->id_tipoincapacidad   = !empty($data["tipoIncap"]) ? $data["tipoIncap"] : null;
        
        $this->valor_facturado = (!empty($data["valorfacturado"])) ? $data["valorfacturado"] : null;
        $this->valor_pago_eps = (!empty($data["valorpagoeps"])) ? $data["valorpagoeps"] : null;
        $this->cod_tesoreria = (!empty($data["codtesoreria"])) ? $data["codtesoreria"] : null;
        $this->fecha_pago   = (!empty($data["fechapago"])) ? $data["fechapago"] : null;
        $this->fecha_rad   = (!empty($data["fecharadicacion"])) ? $data["fecharadicacion"] : null;
        
    }
    
    
    public function exchangeArrayFile(array $data){
        
        
        $this->id_incapacidad       = !empty($data["id_incapacidad"]) ? $data["id_incapacidad"] : null;
        $this->no_incapacidad   = !empty($data["numIncap"]) ? $data["numIncap"] : null;
        $this->id_asociado   = !empty($data["persona"]) ? $data["persona"] : null;
        $this->id_diagnostico   = !empty($data["diagnostico"]) ? $data["diagnostico"] : null;
        $this->fecha_ini   = !empty($data["fechaInicio"]) ? $data["fechaInicio"] : null;
        $this->fecha_fin   = !empty($data["fechaFin"]) ? $data["fechaFin"] : null;
        $this->n_dias_emp   = !empty($data["diasEmpresa"]) ? $data["diasEmpresa"] : null;
        $this->n_dias_eps   = !empty($data["diasEps"]) ? $data["diasEps"] : null;
        $this->n_dias_arl   = !empty($data["diasArl"]) ? $data["diasArl"] : null;
        $this->valor_facturado   = !empty($data["valorFacturado"]) ? $data["valorFacturado"] : null;
        $this->valor_pago_eps   = !empty($data["valorPagado"]) ? $data["valorPagado"] : null;
        $this->cod_tesoreria   = !empty($data["codTesoreria"]) ? $data["codTesoreria"] : null;
        $this->fecha_pago   = !empty($data["fechaPago"]) ? $data["fechaPago"] : null;
        $this->id_causal   = !empty($data["causal"]) ? $data["causal"] : null;
        $this->id_inc_prorroga   = !empty($data["prorroga"]) ? $data["prorroga"] : null;
        $this->id_eps   = !empty($data["eps"]) ? $data["eps"] : null;
        $this->id_sucursal   = !empty($data["sucursal"]) ? $data["sucursal"] : null;
        $this->id_tipoincapacidad   = !empty($data["tipoIncap"]) ? $data["tipoIncap"] : null;
        $this->fecha_rad   = !empty($data["fechaRadicacion"]) ? $data["fechaRadicacion"] : null;
           
        //var_export($data); echo "<hr>";
    }
}