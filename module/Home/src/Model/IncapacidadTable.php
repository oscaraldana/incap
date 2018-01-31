<?php
namespace Home\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
//use Zend\Db\Adapter\Driver\ConnectionInterface;

class IncapacidadTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function beginTransaction(){
        
        $connection = $this->adapter->getDriver()->getConnection();
        $connection->beginTransaction();
        //$this->tableGateway->beginTransaction();
        
    }
    
    public function commitTransaction(){
        
        $this->tableGateway->commit();
        
    }
    
    public function rollbackTransaction(){
        
        $this->tableGateway->rollback();
        
    }
    
    
    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function getIncapacidad($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id_incapacidad' => $id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
                ));
        }

        return $row;
    }

    public function saveIncapacidad(Incapacidad $incapacidad)
    {
        
        $data = [
            'no_incapacidad' => $incapacidad->no_incapacidad,
            'id_asociado' => $incapacidad->id_asociado,
            'id_diagnostico' => $incapacidad->id_diagnostico,
            'fecha_ini' => $incapacidad->fecha_ini,
            'fecha_fin' => $incapacidad->fecha_fin,
            'n_dias_emp' => $incapacidad->n_dias_emp,
            'n_dias_eps' => $incapacidad->n_dias_eps,
            'n_dias_arl' => $incapacidad->n_dias_arl,
            'valor_facturado' => $incapacidad->valor_facturado,
            'valor_pago_eps' => $incapacidad->valor_pago_eps,
            'cod_tesoreria' => $incapacidad->cod_tesoreria,
            'fecha_pago' => $incapacidad->fecha_pago,
            
            'id_causal' => $incapacidad->id_causal,
            'id_inc_prorroga' => $incapacidad->id_inc_prorroga,
            'id_eps' => $incapacidad->id_eps,
            
            'id_sucursal' => $incapacidad->id_sucursal,
            'id_tipoincapacidad' => $incapacidad->id_tipoincapacidad,
            'fecha_rad' => $incapacidad->fecha_rad,
        ];

        $id = (int) $incapacidad->id_incapacidad;
        
        if ($id === 0) {
            if ( $this->tableGateway->insert($data) ){
                return true;
            }
            
        }
        
        if (!empty($id) && !$this->getIncapacidad($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update tipoincap with identifier %d; does not exist',
                $id
                ));
        }

        
        if ( $this->tableGateway->update($data, ['id_incapacidad' => $id]) !== false ){
            return true;
        }
        
        //echo "ppp".$this->tableGateway->getMessage();
        return false;
    }

    public function deleteTipoIncapacidad($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
    
    
    // Metodo que consulta las incapacidades
    public function consultarIncapacidades($criterios) {
       
        
        $params = [];
        
        foreach ( $criterios as $kc => $vc ) {
            
            // Criterio diagnostico 
            if ( strpos($kc, 'diagnostico') !== false ) {
                $params["diagnostico"][] = $vc;
            }
            
            // Criterio Dias Arl
            if ( strpos($kc, 'diaarl') !== false ) {
                $n = (isset($params["diaarl"]))?count($params["diaarl"]):0;
                $params["diaarl"][$n]["value"] = $vc;
                $params["diaarl"][$n]["operator"] = $criterios[str_replace('diaarl', 'condicion', $kc)];
            }
            
            // Criterio Dias Empresa
            if ( strpos($kc, 'diaemp') !== false ) {
                $n = (isset($params["diaemp"]))?count($params["diaemp"]):0;
                $params["diaemp"][$n]["value"] = $vc;
                $params["diaemp"][$n]["operator"] = $criterios[str_replace('diaemp', 'condicion', $kc)];
            }

            // Criterio Dias Eps
            if ( strpos($kc, 'diaeps') !== false ) {
                $n = (isset($params["diaeps"]))?count($params["diaeps"]):0;
                $params["diaeps"][$n]["value"] = $vc;
                $params["diaeps"][$n]["operator"] = $criterios[str_replace('diaeps', 'condicion', $kc)];
            }
            
            // Criterio EPS
            if ( strpos($kc, 'eps') !== false && strpos($kc, 'diaeps') === false ) {
                $params["eps"][] = $vc;
            }

            // Criterio Fecha Inicial
            if ( strpos($kc, 'fecini') !== false ) {
                $n = (isset($params["fecini"]))?count($params["fecini"]):0;
                $params["fecini"][$n]["value"] = $vc;
                $params["fecini"][$n]["operator"] = $criterios[str_replace('fecini', 'condicion', $kc)];
            }
            

            // Criterio Fecha Final
            if ( strpos($kc, 'fecfin') !== false ) {
                $n = (isset($params["fecfin"]))?count($params["fecfin"]):0;
                $params["fecfin"][$n]["value"] = $vc;
                $params["fecfin"][$n]["operator"] = $criterios[str_replace('fecfin', 'condicion', $kc)];
            }
            
            // Criterio Numero Incapacidad
            if ( strpos($kc, 'noincap') !== false ) {
                $params["noincap"][] = $vc;
            }

            // Criterio Persona
            if ( strpos($kc, 'cedula') !== false ) {
                $params["cedula"][] = $vc;
            }

            // Criterio Sucursal
            if ( strpos($kc, 'sucursal') !== false ) {
                $params["sucursal"][] = $vc;
            }

            // Criterio Tipo Incapacidad
            if ( strpos($kc, 'tipoincap') !== false ) {
                $params["tipoincap"][] = $vc;
            }
            
        }
        
        
        $GLOBALS["where"] = "";
        
        
        // Id incapacidad
        if ( isset($criterios["id"]) && $criterios["id"] != '' ) {
            if ( !empty($GLOBALS["where"]) ) { $GLOBALS["where"] .= " AND "; }
            $GLOBALS["where"] .= " id_incapacidad = ".$criterios["id"]." ";
        }
        
        
        
        // Criterio diagnostico
        if ( isset($params["diagnostico"]) && count($params["diagnostico"]) > 0 ) {
            
            if ( !empty($GLOBALS["where"]) ) { $GLOBALS["where"] .= " AND "; }
            $GLOBALS["where"] .= "( ";
            foreach ( $params["diagnostico"] as $kp => $val ) {
                $GLOBALS["where"] .= " diag.id_diagnostico = $val ";
                if ( count($params["diagnostico"]) > 1 && $kp !== (count($params["diagnostico"]) - 1) ) {
                    $GLOBALS["where"] .= " OR ";
                }
            }
            $GLOBALS["where"] .= " ) ";
            
        }
        
        // Criterio dias Arl
        if ( isset($params["diaarl"]) && count($params["diaarl"]) > 0 ) {
        
            if ( !empty($GLOBALS["where"]) ) { $GLOBALS["where"] .= " AND "; }
            $GLOBALS["where"] .= "( ";
            foreach ( $params["diaarl"] as $kp =>  $val ) {
                $GLOBALS["where"] .= " n_dias_arl ".$val["operator"]." ".$val["value"]." ";
                if ( count($params["diaarl"]) > 1 && $kp !== (count($params["diaarl"]) - 1) ) {
                    $GLOBALS["where"] .= " OR ";
                }
            }
            $GLOBALS["where"] .= " ) ";
           
        }
        
        // Criterio dias Empresa
        if ( isset($params["diaemp"]) && count($params["diaemp"]) > 0 ) {
        
            if ( !empty($GLOBALS["where"]) ) { $GLOBALS["where"] .= " AND "; }
            $GLOBALS["where"] .= "( ";
            foreach ( $params["diaemp"] as $kp =>  $val ) {
                $GLOBALS["where"] .= " n_dias_emp ".$val["operator"]." ".$val["value"]." ";
                if ( count($params["diaemp"]) > 1 && $kp !== (count($params["diaemp"]) - 1) ) {
                    $GLOBALS["where"] .= " OR ";
                }
            }
            $GLOBALS["where"] .= " ) ";
            
        }
        
        // Criterio dias EPS
        if ( isset($params["diaeps"]) && count($params["diaeps"]) > 0 ) {
        
            if ( !empty($GLOBALS["where"]) ) { $GLOBALS["where"] .= " AND "; }
            $GLOBALS["where"] .= "( ";
            foreach ( $params["diaeps"] as $kp =>  $val ) {
                $GLOBALS["where"] .= " n_dias_eps ".$val["operator"]." ".$val["value"]." ";
                if ( count($params["diaeps"]) > 1 && $kp !== (count($params["diaeps"]) - 1) ) {
                    $GLOBALS["where"] .= " OR ";
                }
            }
            $GLOBALS["where"] .= " ) ";
            
        }
        

        // Criterio EPS
        if ( isset($params["eps"]) && count($params["eps"]) > 0 ) {
        
            if ( !empty($GLOBALS["where"]) ) { $GLOBALS["where"] .= " AND "; }
            $GLOBALS["where"] .= "( ";
            foreach ( $params["eps"] as $kp => $val ) {
                $GLOBALS["where"] .= " eps.id_eps = $val ";
                if ( count($params["eps"]) > 1 &&  $kp !== (count($params["eps"]) - 1) ) {
                    $GLOBALS["where"] .= " OR ";
                }
            }
            $GLOBALS["where"] .= " ) ";
            
        }
        

        // Criterio Fecha Inicial
        if ( isset($params["fecini"]) && count($params["fecini"]) > 0 ) {
        
            if ( !empty($GLOBALS["where"]) ) { $GLOBALS["where"] .= " AND "; }
            $GLOBALS["where"] .= "( ";
            foreach ( $params["fecini"] as $kp => $val ) {
                $GLOBALS["where"] .= " incapacidad.fecha_ini ".$val["operator"]." '".str_replace('/', '-', implode('/', array_reverse(explode('/', $val["value"]))) )."' ";
                if ( count($params["fecini"]) > 1 &&  $kp !== (count($params["fecini"]) - 1) ) {
                    $GLOBALS["where"] .= " OR ";
                }
            }
            $GLOBALS["where"] .= " ) ";
             
        }
        
    
        // Criterio Fecha Final
        if ( isset($params["fecfin"]) && count($params["fecfin"]) > 0 ) {
        
            if ( !empty($GLOBALS["where"]) ) { $GLOBALS["where"] .= " AND "; }
            $GLOBALS["where"] .= "( ";
            foreach ( $params["fecfin"] as $kp => $val ) {
                $GLOBALS["where"] .= " incapacidad.fecha_fin ".$val["operator"]." '".str_replace('/', '-', implode('/', array_reverse(explode('/', $val["value"]))) )."' ";
                if ( count($params["fecfin"]) > 1 &&  $kp !== (count($params["fecfin"]) - 1) ) {
                    $GLOBALS["where"] .= " OR ";
                }
            }
            $GLOBALS["where"] .= " ) ";
           
        }

        // Criterio Numero Incapacidad
        if ( isset($params["noincap"]) && count($params["noincap"]) > 0 ) {
        
            if ( !empty($GLOBALS["where"]) ) { $GLOBALS["where"] .= " AND "; }
            $GLOBALS["where"] .= "( ";
            foreach ( $params["noincap"] as $kp => $val ) {
                $GLOBALS["where"] .= " incapacidad.no_incapacidad like UPPER('%$val%')";
                if ( count($params["noincap"]) > 1 &&  $kp !== (count($params["noincap"]) - 1) ) {
                    $GLOBALS["where"] .= " OR ";
                }
            }
            $GLOBALS["where"] .= " ) ";
        
        }

        // Criterio Persona
        if ( isset($params["cedula"]) && count($params["cedula"]) > 0 ) {
        
            if ( !empty($GLOBALS["where"]) ) { $GLOBALS["where"] .= " AND "; }
            $GLOBALS["where"] .= "( ";
            foreach ( $params["cedula"] as $kp => $val ) {
                $GLOBALS["where"] .= " aso.id_asociado = $val ";
                if ( count($params["cedula"]) > 1 &&  $kp !== (count($params["cedula"]) - 1) ) {
                    $GLOBALS["where"] .= " OR ";
                }
            }
            $GLOBALS["where"] .= " ) ";
        
        }
        

        // Criterio Sucursal
        if ( isset($params["sucursal"]) && count($params["sucursal"]) > 0 ) {
        
            if ( !empty($GLOBALS["where"]) ) { $GLOBALS["where"] .= " AND "; }
            $GLOBALS["where"] .= "( ";
            foreach ( $params["sucursal"] as $kp => $val ) {
                $GLOBALS["where"] .= " suc.id_sucursal = $val ";
                if ( count($params["sucursal"]) > 1 &&  $kp !== (count($params["sucursal"]) - 1) ) {
                    $GLOBALS["where"] .= " OR ";
                }
            }
            $GLOBALS["where"] .= " ) ";
        
        }

        // Criterio Tipo Incapacidad
        if ( isset($params["tipoincap"]) && count($params["tipoincap"]) > 0 ) {
        
            if ( !empty($GLOBALS["where"]) ) { $GLOBALS["where"] .= " AND "; }
            $GLOBALS["where"] .= "( ";
            foreach ( $params["tipoincap"] as $kp => $val ) {
                $GLOBALS["where"] .= " tip.id = $val ";
                if ( count($params["tipoincap"]) > 1 &&  $kp !== (count($params["tipoincap"]) - 1) ) {
                    $GLOBALS["where"] .= " OR ";
                }
            }
            $GLOBALS["where"] .= " ) ";
        
        }
        
        
        
        //echo "<br>PERRO<hr>";
        //echo $GLOBALS["where"];
        //var_export($params);
        
        $select = new Select();
        $select->from('incapacidad');
        //$select->columns(['incapacidad.id_incapacidad']);
        $select->join(['aso' => 'asociado'],'incapacidad.id_asociado = aso.id_asociado');
        $select->join(['diag' => 'diagnostico'],'incapacidad.id_diagnostico = diag.id_diagnostico');
        $select->join(['eps' => 'eps'],'incapacidad.id_eps = eps.id_eps');
        $select->join(['suc' => 'sucursal'],'incapacidad.id_sucursal = suc.id_sucursal');
        $select->join(['tip' => 'tipoincapacidad'],'incapacidad.id_tipoincapacidad = tip.id_tipoincapacidad');
        $select->join(['prorr' => 'incapacidad'],'incapacidad.id_incapacidad = prorr.id_incapacidad', $select::JOIN_OUTER);
        $select->where($GLOBALS["where"]);
        
        // GROUP BY
        if ( isset($criterios["group_by"]) && $criterios["group_by"] != '' ) {
            $select->group($criterios["group_by"]);
        }
        
        
            //$select->where->like('id_asociado', '43');
            //$select->order('name ASC')->limit(2);
        
        //echo $select->getSqlString();
        
        $rows = $this->tableGateway->selectWith($select);
        
        
        unset($GLOBALS["where"]);
        return $rows;
        
        /*
        ->from(['inc' => 'incapacidad'])     // base table
        ->join(
            ['aso' => 'asociado'],        // join table with alias
            'inc.id_asociado = aso.id_asociado'  // join expression
            );
        
        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        */
    }
}