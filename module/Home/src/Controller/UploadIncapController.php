<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Home\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Home\Form\FormIncapacidad;
use Home\Module;
use Home\Model\TipoIncapacidad;
use Home\Model\TipoIncapacidadTable;
//use Home\Model\Sucursal;
use Home\Model\SucursalTable;
use Home\Model\EpsTable;
use Home\Model\DiagnosticoTable;
use Home\Model\AsociadoTable;
use Home\Model\Diagnostico;
use Home\Form\FilterFormIncapacidad;
use Home\Model\Incapacidad;
use Home\Model\IncapacidadTable;
use Home\Model\CausalesTable;

class UploadIncapController extends AbstractActionController
{
    
    private $tableTipoIncap;
    private $tableSucursales;
    private $tableEps;
    private $tableCausal;
    private $tableDiagnostico;
    private $tableAsociado;
    private $tableIncapacidad;
    
    public function __construct(
                                    TipoIncapacidadTable $tableTipoIncap,
                                    SucursalTable $sucursales,
                                    EpsTable $epss,
                                    DiagnosticoTable $diag,
                                    AsociadoTable $aso,
                                    IncapacidadTable $incap, 
                                    CausalesTable $causal
        ){
        
        $this->tableTipoIncap = $tableTipoIncap;
        $this->tableSucursales = $sucursales;
        $this->tableEps = $epss;
        $this->tableDiagnostico = $diag;
        $this->tableAsociado = $aso;
        $this->tableIncapacidad = $incap;
        $this->tableCausal = $causal;
        
    }
    
    public function indexAction()
    {
        $container = new Container('incapacidades');
        
        $id=$this->params()->fromRoute("id",null);
        
        //$tipoIncap = $this->tipoIncap->getTipIncap();
        
        //$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        
        $parametrosFormulario = array();
        
        $parametrosFormulario["tipoIncapacidad"] = $this->tableTipoIncap->fetchAll();
        $parametrosFormulario["sucursales"] = $this->tableSucursales->fetchAll();
        $parametrosFormulario["epss"] = $this->tableEps->fetchAll();
        $parametrosFormulario["diagnosticos"] = $this->tableDiagnostico->fetchAll();
        $parametrosFormulario["causales"] = $this->tableCausal->fetchAll();
        
        $incapacidadLoad = null;
        
        if ( $id != null ) {
        
            $incapacidad = $this->tableIncapacidad->consultarIncapacidades(["id"=>$id]);
            foreach ($incapacidad as $datosInc) {
                $parametrosFormulario["asociadoDefault"] = [$datosInc->id_asociado => $datosInc->cedula." - ".$datosInc->nombre." ".$datosInc->apellido1." ".$datosInc->apellido2];
                $parametrosFormulario["diagnosticoDefault"] = [$datosInc->id_diagnostico => $datosInc->codigo." - ".$datosInc->diagnostico];
                $incapacidadLoad["tipoincap"] = $datosInc->id_tipoincapacidad;
                $incapacidadLoad["sucursal"] = $datosInc->id_sucursal;
                $incapacidadLoad["eps"] = $datosInc->id_eps;
                $incapacidadLoad["noincap"] = $datosInc->no_incapacidad;
                $incapacidadLoad["fecini"] = $datosInc->fecha_ini;
                $incapacidadLoad["fecfin"] = $datosInc->fecha_fin;
                $incapacidadLoad["diastot"] = $datosInc->n_dias_emp + $datosInc->n_dias_eps + $datosInc->n_dias_arl;
                $incapacidadLoad["diasemp"] = $datosInc->n_dias_emp;
                $incapacidadLoad["diaseps"] = $datosInc->n_dias_eps;
                $incapacidadLoad["diasarl"] = $datosInc->n_dias_arl;
                
                $incapacidadLoad["valorfacturado"] = $datosInc->valor_facturado;
                $incapacidadLoad["valorpagoeps"] = $datosInc->valor_pago_eps;
                $incapacidadLoad["codtesoreria"] = $datosInc->cod_tesoreria;
                $incapacidadLoad["fechapago"] = $datosInc->fecha_pago;
                $incapacidadLoad["fecharad"] = $datosInc->fecha_rad;
                $incapacidadLoad["causal"] = $datosInc->id_causal;
                
            }
            //var_export($varTipoIncap);
            
        }
        
        
        $form = new FormIncapacidad('form_add_incap', $parametrosFormulario);
        
        
        
        $viewModel = new ViewModel(["form"=>$form, 'url'=> $this->getRequest()->getBaseUrl(), 'tipoincap' => $this->tableTipoIncap->fetchAll(), 'incapacidad' => $incapacidadLoad, 'id_incapacidad' => $id ]);
        if (isset( $container->usuario )){
            //$this->redirect()->toRoute('home');
            $this->layout()->logueado = true;
        } else {
            $this->layout()->logueado = false;
            $this->redirect()->toRoute('home');
        }
        
       
        
        return $viewModel;
    }
    
    public function getfileAction() {
        
        $this->layout('layout/ajax_layout.phtml');
        $msg_error = "";
        $dataToUpload = [];
        $dataToPrint = [];
        $cabeceras = ["CEDULA","TIPO_INCAPACIDAD","NUM_INCAPACIDAD","SUCURSAL","EPS","COD_DIAGNOSTICO","FECHA_INICIAL","FECHA_FINAL","FECHA_RADICA","DIAS_EMPRESA","DIAS_EPS","DIAS_ARL","VALOR_FACTURADO","VALOR_PAGO_EPS","FECHA_PAGO","COD_TESORERIA","CAUSAL","PRORROGA"];
        
        
        if ($this->getRequest()->isPost()) {
            
            
            
            if ( isset($_FILES["file-0"]) && $_FILES["file-0"]["type"] == "text/csv" && $_FILES["file-0"]["error"] == 0 ){
                
                
                $datosTabla = $this->tableTipoIncap->fetchAll();
                foreach ($datosTabla as $dato) {
                    $tipoIncap[$dato->id_tipoincapacidad] = strtolower($dato->tipoincapacidad);
                }
                $datosTabla = $this->tableSucursales->fetchAll();
                foreach ($datosTabla as $dato) {
                    $sucursales[$dato->id_sucursal] = strtolower($dato->sucursal);
                }
                $datosTabla = $this->tableEps->fetchAll();
                foreach ($datosTabla as $dato) {
                    $epss[$dato->id_eps] = strtolower($dato->nombre_eps);
                }
                
                $error = false;
                
                
                $numCab = count($cabeceras);
                $linea = 0;
                //Abrimos nuestro archivo
                $archivo = fopen($_FILES["file-0"]["tmp_name"], "r");
                //var_export($archivo);
                //Lo recorremos
                while (($datos = fgetcsv($archivo, ",")) == true && !$error) {
                    
                    
                    $num = count($datos);
                    
                    if ($num != $numCab) {
                        $msg_error = "CANTIDAD DE DATOS INCOMPATIBLE HAY $num Y SE ESPERABAN $numCab";
                        $error = true;
                    } else {
                    
                        if ( $linea == 0 ) { //Validar cabeceras
                            for ($columna = 0; $columna < $num; $columna++) {
                                if ( $datos[$columna] != $cabeceras[$columna] ) {
                                    $msg_error = "CABECERA ".$datos[$columna]." NO COINCIDE CON PLANTILLA, SE ESPERABA ".$cabeceras[$columna].".";
                                    $error = true;
                                    break;
                                }
                            }
                        } else {
                        
                            if(!$error) {
                                for ($columna = 0; $columna < $num; $columna++) {
                                    
                                    if ($error){ break; }
                                    
                                    switch ($columna) {
                                        case 0 : $aso = $this->tableAsociado->searchAsociados($datos[$columna]);
                                                if ( count($aso) == 1 ) {
                                                    foreach ($aso as $dato) {
                                                        $cedula = $dato->identificacion;
                                                        $idP = $dato->id_asociado;
                                                    }
                                                    // OK
                                                    if ($cedula == $datos[$columna]) {
                                                        $dataToUpload[$linea]["persona"] = $idP;
                                                        $dataToPrint[$linea]["persona"] = $cedula;
                                                    } else {
                                                        $msg_error = "IDENTIFICACION ".$datos[$columna]." NO PUDO SER RECONOCIDA COMO IDENTIFICACION VALIDA.";
                                                        $error = true;
                                                    }
                                                    
                                                } else {
                                                    $msg_error = "IDENTIFICACION ".$datos[$columna]." NO PUDO SER RECONOCIDA COMO IDENTIFICACION VALIDA.";
                                                    $error = true;
                                                }
                                                break;
                                        # Validar Tipos de incapacidad
                                        case 1 : $key = array_search(strtolower($datos[$columna]), $tipoIncap);
                                                 if ( $key !== false ){
                                                        //echo " - ".$datos[$columna]."($key)";
                                                        $dataToUpload[$linea]["tipoIncap"] = $key;
                                                        $dataToPrint[$linea]["tipoIncap"] = $datos[$columna];
                                                } else {
                                                        $msg_error = "TIPO DE INCAPACIDAD ".$datos[$columna]." NO PUDO SER RECONOCIDO COMO TIPO VALIDO.";
                                                        $error = true;
                                                    }
                                                    break;
                                        # Validar Num Incap
                                        case 2 :    $dataToUpload[$linea]["numIncap"] = $datos[$columna];
                                                    $dataToPrint[$linea]["numIncap"] = $datos[$columna];
                                                    break;
                                        # Validar Sucursales
                                        case 3 : $key = array_search(strtolower($datos[$columna]), $sucursales);
                                                if ( $key !== false ){
                                                        //echo " - ".$datos[$columna]."($key)";
                                                        $dataToUpload[$linea]["sucursal"] = $key;
                                                        $dataToPrint[$linea]["sucursal"] = $datos[$columna];
                                                    } else {
                                                        $msg_error = "SUCURSAL ".$datos[$columna]." NO PUDO SER RECONOCIDA COMO SUCURSAL VALIDA.";
                                                        $error = true;
                                                    }
                                                    break;
                                        # Validar EPS
                                        case 4 : $key = array_search(strtolower($datos[$columna]), $epss);
                                                 if ( $key !== false ){
                                                        //echo " - ".$datos[$columna]."($key)";
                                                        $dataToUpload[$linea]["eps"] = $key;
                                                        $dataToPrint[$linea]["eps"] = $datos[$columna];
                                        } else {
                                                        $msg_error = "LA EPS ".$datos[$columna]." NO PUDO SER RECONOCIDA COMO UNA EPS VALIDA.";
                                                        $error = true;
                                                    }
                                                    break;
                                        # Validar Diagnostico
                                        case 5 : $diag = $this->tableDiagnostico->searchDiagnosticos($datos[$columna]);
                                                if ( count($diag) == 1 ) {
                                                    foreach ($diag as $dato) {
                                                        $codDiag = $dato->codigo_diagnostico;
                                                        $idD = $dato->id_diagnostico;
                                                    }
                                                    // OK
                                                    if ($codDiag == $datos[$columna]) {
                                                        //echo $codDiag;
                                                        $dataToUpload[$linea]["diagnostico"] = $idD;
                                                        $dataToPrint[$linea]["diagnostico"] = $datos[$columna];
                                                    } else {
                                                        $msg_error = "DIAGNOSTICO ".$datos[$columna]." NO PUDO SER RECONOCIDO COMO DIAGNOSTICO VALIDO.";
                                                        $error = true;
                                                    }
                                                    
                                                } else {
                                                    $msg_error = "DIAGNOSTICO ".$datos[$columna]." NO PUDO SER RECONOCIDO COMO DIAGNOSTICO VALIDO.";
                                                    $error = true;
                                                }
                                                break;
                                        # Validar Fecha Desde
                                        case 6 : if ( $this->check_date($datos[$columna]) ){
                                                    //echo " - ".$datos[$columna];
                                                    $dataToUpload[$linea]["fechaInicio"] = $datos[$columna];
                                                    $dataToPrint[$linea]["fechaInicio"] = $datos[$columna];
                                        } else {
                                                    $msg_error = "LA FECHA DE INICIO ".$datos[$columna]." NO PUDO SER RECONOCIDA COMO UNA FECHA VALIDA (aaaa-mm-dd). Linea ".($linea+1);
                                                    $error = true;
                                                }
                                                break;

                                        # Validar Fecha Hasta
                                        case 7 : if ( $this->check_date($datos[$columna]) ){
                                                    if ( $this->fechaMayor($datos[$columna], $datos[$columna-1]) == 1 ) {
                                                        //echo " - ".$datos[$columna];
                                                        $dataToUpload[$linea]["fechaFin"] = $datos[$columna];
                                                        $dataToPrint[$linea]["fechaFin"] = $datos[$columna];
                                                    } else {
                                                        $msg_error = "LA FECHA DE FIN ".$datos[$columna]." NO PUEDO SER MENOR A LA FECHA DE INICIO ".$datos[$columna-1].". Linea ".($linea+1);
                                                        $error = true;
                                                    }
                                                    
                                                } else {
                                                    $msg_error = "LA FECHA DE FIN ".$datos[$columna]." NO PUDO SER RECONOCIDA COMO UNA FECHA VALIDA (aaaa-mm-dd). Linea ".($linea+1);
                                                    $error = true;
                                                }
                                                break;
                                        # Validar Fecha RADICACION
                                        case 8 : if ( $this->check_date($datos[$columna]) ){
                                                    if ( $this->fechaMayor($datos[$columna], $datos[$columna-2]) >= 0 ) {
                                                        //echo " - ".$datos[$columna];
                                                        $dataToUpload[$linea]["fechaRadicacion"] = $datos[$columna];
                                                        $dataToPrint[$linea]["fechaRadicacion"] = $datos[$columna];
                                                    } else {
                                                        $msg_error = "LA FECHA DE RADICACION ".$datos[$columna]." NO PUEDO SER MENOR A LA FECHA DE INICIO ".$datos[$columna-2].". Linea ".($linea+1);
                                                        $error = true;
                                                    }
                                                } else {
                                                    $msg_error = "LA FECHA DE RADICACION ".$datos[$columna]." NO PUDO SER RECONOCIDA COMO UNA FECHA VALIDA (aaaa-mm-dd). Linea ".($linea+1);
                                                    $error = true;
                                                }
                                                break;
                                        # Validar Dias Empresa
                                        case 9 : if ( is_numeric($datos[$columna]) && $datos[$columna] >= 0 && $datos[$columna] <= 999){
                                                    //echo " - ".$datos[$columna];
                                                        $dataToUpload[$linea]["diasEmpresa"] = $datos[$columna];
                                                        $dataToPrint[$linea]["diasEmpresa"] = $datos[$columna];
                                                    } else {
                                                    $msg_error = "LOS DIAS EMPRESA ".$datos[$columna]." NO SON UN NUMERO DE DIAS VALIDO. Linea ".($linea+1);
                                                    $error = true;
                                                }
                                                break;
                                        
                                        # Validar Dias EPS
                                        case 10 : if ( is_numeric($datos[$columna]) && $datos[$columna] >= 0 && $datos[$columna] <= 999){
                                                    //echo " - ".$datos[$columna];
                                                    $dataToUpload[$linea]["diasEps"] = $datos[$columna];
                                                    $dataToPrint[$linea]["diasEps"] = $datos[$columna];
                                                } else {
                                                    $msg_error = "LOS DIAS EPS ".$datos[$columna]." NO SON UN NUMERO DE DIAS VALIDO. Linea ".($linea+1);
                                                    $error = true;
                                                }
                                                break;
                                        
                                        # Validar Dias ARL
                                        case 11 : if ( is_numeric($datos[$columna]) && $datos[$columna] >= 0 && $datos[$columna] <= 999){
                                            
                                                    $idTipoIncap = array_search(strtolower($datos[$columna-10]), $tipoIncap);
                                                    $res = $this->validarDias($datos[$columna-5], $datos[$columna-4], $datos[$columna-2], $datos[$columna-1], $datos[$columna], $idTipoIncap);
                                                    if ( $res === true) {
                                                        //echo " - ".$datos[$columna];
                                                        $dataToUpload[$linea]["diasArl"] = $datos[$columna];
                                                        $dataToPrint[$linea]["diasArl"] = $datos[$columna];
                                                    } else {
                                                        $msg_error = "$res Linea ".($linea+1);
                                                        $error = true;
                                                    }
                                                } else {
                                                    $msg_error = "LOS DIAS ARL ".$datos[$columna]." NO SON UN NUMERO DE DIAS VALIDO. Linea ".($linea+1);
                                                    $error = true;
                                                }
                                                break;
                                                
                                        # Validar Valor Facturado
                                        case 12 : if ( is_numeric($datos[$columna]) ){
                                                    //echo " - ".$datos[$columna];
                                                    $dataToUpload[$linea]["valorFacturado"] = $datos[$columna];
                                                    $dataToPrint[$linea]["valorFacturado"] = $datos[$columna];
                                                } else {
                                                    $msg_error = "EL VALOR FACTURADO ".$datos[$columna]." NO ES UN NUMERO VALIDO, (PUEDE PONER CERO). Linea ".($linea+1);
                                                    $error = true;
                                                }
                                                break;
                                        
                                        # Validar Valor Pagado
                                        case 13 : if ( is_numeric($datos[$columna]) ){
                                                    //echo " - ".$datos[$columna];
                                                    $dataToUpload[$linea]["valorPagado"] = $datos[$columna];
                                                    $dataToPrint[$linea]["valorPagado"] = $datos[$columna];
                                                  } else {
                                                    $msg_error = "EL VALOR PAGADO ".$datos[$columna]." NO ES UN NUMERO VALIDO, (PUEDE PONER CERO). Linea ".($linea+1);
                                                    $error = true;
                                                }
                                                break;
                                        # Validar Fecha PAGO
                                        case 14 : if ( empty($datos[$columna]) ){
                                                        $dataToUpload[$linea]["fechaPago"] = null;
                                                        $dataToPrint[$linea]["fechaPago"] = null;
                                                    } else {
                                                    
                                                    if ( $this->check_date($datos[$columna]) ){
                                                        //echo " - ".$datos[$columna];
                                                        $dataToUpload[$linea]["fechaPago"] = $datos[$columna];
                                                        $dataToPrint[$linea]["fechaPago"] = $datos[$columna];
                                                    } else {
                                                        $msg_error = "LA FECHA DE PAGO ".$datos[$columna]." NO PUDO SER RECONOCIDA COMO UNA FECHA VALIDA (aaaa-mm-dd). Linea ".($linea+1);
                                                        $error = true;
                                                    }
                                                  }
                                                break;
                                        # Validar Cod Tesoreria
                                        case 15 :    $dataToUpload[$linea]["codTesoreria"] = $datos[$columna];
                                                    $dataToPrint[$linea]["codTesoreria"] = $datos[$columna];
                                                break;
                                        # Validar Causal
                                        case 16 :    $dataToUpload[$linea]["causal"] = $datos[$columna];
                                                     $dataToPrint[$linea]["causal"] = $datos[$columna];
                                                  break;
                                        # Validar Prorroga
                                        case 17 :    $dataToUpload[$linea]["prorroga"] = $datos[$columna];
                                                    $dataToPrint[$linea]["prorroga"] = $datos[$columna];
                                                  break;
                                        
                                        
                                        
                                        
                                    }
                                    
                                }
                            }
                        }
                    }
                    
                    $linea++;
                }
                //Cerramos el archivo
                fclose($archivo);
            }
            
            
        }
        
        if ( $error ) {
            echo json_encode(["error" => $msg_error]);
        } else {
            
            $html = '<div style="width: 100%;
                                max-height: 200px !important;
                                overflow: scroll;"><table style="border: 1px solid #000;">';
            
            $html .= '<tr>';
            foreach ( $cabeceras as $data ){
                $html .= '<th style="border: 1px solid #000; background: #eee;
                                       border-collapse: collapse;
                                       padding: 0.3em;">'.$data.'</th>';
            }
            $html .= '</tr>';
            
            
            foreach ($dataToPrint as $data) {
                $html .= '<tr>';
                foreach ( $data as $datos ){
                    $html .= '<th style="border: 1px solid #000;
                                       border-collapse: collapse;
                                       padding: 0.3em;">'.$datos.'</th>';
                }
                $html .= '</tr>';
            }
            
            $html .= '</table></div>';
            //var_export($dataToUpload);
            echo json_encode(["datosUpload" => $dataToUpload, "html" => $html]);
        }
        
    }
    
    
   
    function savefileAction () {
        
        $this->layout('layout/ajax_layout.phtml');
        $postData = $this->getRequest()->getPost();
        
        $error = false; $errorMsg = "";
        try {
            
            //$this->tableIncapacidad->beginTransaction();
            $incap = new Incapacidad();
            foreach ( $postData["datos"] as $infoFile ) {
                $incap->exchangeArrayFile($infoFile);
                
                if ( !$this->tableIncapacidad->saveIncapacidad($incap) ){
                    
                    $error = true;
                    $errorMsg = "Error al guardar incapacidad";
                    break;
                }
            }
            
        } catch (\Exception $e){
            $error = true;
            $errorMsg = "Error al guardar incapacidad".$e->getMessage()." -> ".$e->getFile()." -> ".$e->getLine();
        }
        
        if ( $error ){
            echo json_encode(["error" => $errorMsg ] );
        } else {
            echo json_encode(["OK" => "OK"]);
        }
        
        //echo json_encode(["datosUpload" => 1, "html" => 2]);
    }
    
    
    function check_date ( $fecha ) {
        
        
        trim($fecha);
        $trozos = explode ("-", $fecha);
        
        $dia = (isset($trozos[2])) ? intval($trozos[2]) : null;
        $mes = (isset($trozos[1])) ? intval($trozos[1]) : null;
        $ano = (isset($trozos[0])) ? intval($trozos[0]) : null;
   
       if ( !empty($dia) && !empty($mes) && !empty($ano) ) {
           
           if ( checkdate ($mes,$dia,$ano) ) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    function fechaMayor( $fecha1, $fecha2 ) {
        
        
        $datetime1 = date_create(''.trim($fecha1).' 00:00');
        $datetime2 = date_create(''.trim($fecha2).' 00:00');
        
        # Si fecha1 es menor que fecha2
        if($datetime1 < $datetime2) {
            return -1;
            
        } 
        # Si fecha1 es mayor que fecha2
        else if ($datetime1 > $datetime2) {
            return 1;
            
        } 
        # Si fecha1 es igual que fecha2
        else {
            return 0;
        }
    }
    
    function diasEntreFechas( $fecha1, $fecha2 ) {
        
        
        $datetime1 = date_create(''.trim($fecha1).' 00:00');
        $datetime2 = date_create(''.trim($fecha2).' 00:00');
        
        $interval = date_diff($datetime1, $datetime2);
        return ($interval->format('%R%a'))+1;
    }
    
    function validarDias( $fechaIni, $fechaFin, $diasEmpresa, $diasEps, $diasArl, $idTipoIncap ) {
        
        $totDias = $this->diasEntreFechas($fechaIni, $fechaFin);
        if ( ($diasEmpresa + $diasEps + $diasArl) != $totDias ){
            return "VERIFICAR DIAS, YA QUE DE ACUERDO A LAS FECHAS, LOS DIAS DE INCAPACIDAD SON $totDias SE ESTAN INTENTANDO REGISTRAR ".($diasEmpresa + $diasEps + $diasArl).".";
        } 
        $tipoIncap = $this->tableTipoIncap->getTipoIncapacidad($idTipoIncap);
        
        if ( $tipoIncap->dias_empresa > 0) {
            if ( $totDias >= $tipoIncap->dias_empresa ) {
                $d_e = $tipoIncap->dias_empresa;
                $d_n_e = $totDias - $tipoIncap->dias_empresa;
            } else {
                $d_e = $totDias;
                $d_n_e = 0;
            }
        } else {
            $d_e = 0;
            $d_n_e = $totDias;
        }
        
        if ( $d_e != $diasEmpresa ) {
            return "DE ACUERDO AL TIPO DE INCAPACIDAD (".$tipoIncap->tipoincapacidad."), DEBERIAN SER $d_e DIAS EMPRESA, NO $diasEmpresa.";
        }
        
        if ( $tipoIncap->es_arl == 1 && $diasArl != $d_n_e ) {
            return "VERIFICAR CANTIDAD DE DIAS ARL, NO COINCIDEN CON LA VALIDACION REALIZADA POR EL SISTEMA.";
        }
        
        if ( $tipoIncap->es_arl == 0 && $diasEps != $d_n_e ) {
            return "VERIFICAR CANTIDAD DE DIAS EPS, NO COINCIDEN CON LA VALIDACION REALIZADA POR EL SISTEMA.";
        }
        
        return true;
    }
    
}
