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

class ListIncapController extends AbstractActionController
{
    
    private $tableTipoIncap;
    private $tableSucursales;
    private $tableEps;
    private $tableDiagnostico;
    private $tableAsociado;
    private $tableIncapacidad;
    
    public function __construct(
                                    TipoIncapacidadTable $tableTipoIncap,
                                    SucursalTable $sucursales,
                                    EpsTable $epss,
                                    DiagnosticoTable $diag,
                                    AsociadoTable $aso,
                                    IncapacidadTable $incap
        ){
        
        $this->tableTipoIncap = $tableTipoIncap;
        $this->tableSucursales = $sucursales;
        $this->tableEps = $epss;
        $this->tableDiagnostico = $diag;
        $this->tableAsociado = $aso;
        $this->tableIncapacidad = $incap;
        
    }
    
    public function indexAction()
    {
        $container = new Container('incapacidades');
        
        
        $parametrosFormulario = array();
        
        $parametrosFormulario["tipoIncapacidad"] = $this->tableTipoIncap->fetchAll();
        $parametrosFormulario["sucursales"] = $this->tableSucursales->fetchAll();
        $parametrosFormulario["epss"] = $this->tableEps->fetchAll();
        //$parametrosFormulario["diagnosticos"] = $this->tableDiagnostico->fetchAll();
        
        //$form = new FormIncapacidad('form_add_incap', $parametrosFormulario);
        
        
        $viewModel = new ViewModel(["datosform"=>$parametrosFormulario, 'url'=> $this->getRequest()->getBaseUrl()]);
        if (isset( $container->usuario )){
            //$this->redirect()->toRoute('home');
            $this->layout()->logueado = true;
        } else {
            $this->layout()->logueado = false;
            $this->redirect()->toRoute('home');
        }
        
       
        
        return $viewModel;
    }
    
    
    public function saveIncapAction ()
    { 
        $form = new FormIncapacidad();
        $request = $this->getRequest();
        
        if(!$request->isPost()){
            
            return $this->response;
        }else{
            $incap = new Incapacidad();
            $formValidator = new FilterFormIncapacidad();
            $form->setInputFilter($formValidator->getInputFilter());
            $form->setData($request->getPost());
            if($form->isValid()){
              /*  
                var_export($request->getPost());
                var_export( $startDate = \DateTime::createFromFormat('d/m/Y', '01/06/2017') );
                var_export( $endDate = \DateTime::createFromFormat('Y/m/d', '2017/02/01') );
            */
                $incap->exchangeArray($form->getData());
                //$incapTable = new IncapacidadTable($this->tableIncapacidad);
                if ( $this->tableIncapacidad->saveIncapacidad($incap) ){
                    //$this->layout('layout/ajax_layout.phtml');
                    $script = '<script>
                            swal({
                              title: "Incapacidad Guardada",
                              //text: "Submit to run ajax request",
                              type: "success",
                              //showCancelButton: true,
                              closeOnConfirm: false,
                              showLoaderOnConfirm: true,
                            },
                            function(){
                              setTimeout(function(){
                                var url = "/add_incap"; 
                                $(location).attr("href",url);
                              }, 200);
                            }); 
                        </script>';
                    return new ViewModel(array('script' => $script));
                } 
            }
            
            return new ViewModel(array('form' => $form));
        }
    }

    public function buscarDiagnosticosAction()
    {
        $this->layout('layout/ajax_layout.phtml');
        $postData = $this->getRequest()->getPost();
    
        $diag = null;
    
        if ( isset($postData["term"]) && !empty($postData["term"]) ) {
            $diag = $this->tableDiagnostico->searchDiagnosticos($postData["term"]);
        }
    
        $respuesta = array();
        //var_export($diag);
        if ( $diag != null) {
            foreach ($diag as $datos) {
                array_push($respuesta, ["id" => $datos->id_diagnostico, "completeName" => $datos->codigo." - ".$datos->nombre]);
                //$varDiagn[$datos->id_diagnostico] = $datos->nombre;
            }
        }
    
        echo json_encode($respuesta);
    }


    public function buscarAsociadosAction()
    {
        $this->layout('layout/ajax_layout.phtml');
        $postData = $this->getRequest()->getPost();
    
        $aso = null;
    
        if ( isset($postData["term"]) && !empty($postData["term"]) ) {
            $aso = $this->tableAsociado->searchAsociados($postData["term"]);
        }
    
        $respuesta = array();
        
        if ( $aso != null) {
            foreach ($aso as $datos) {
                array_push($respuesta, ["id" => $datos->id_asociado, "completeName" => $datos->cedula." - ".$datos->nombre." ".$datos->apellido1." ".$datos->apellido2]);
            }
        }
    
        echo json_encode($respuesta);
    }
    
    
    public function buscarIncapacidadesAction()
    {
        
        $container = new Container('incapacidades');
        
        if (isset( $container->usuario )){
            //$this->redirect()->toRoute('home');
            $this->layout()->logueado = true;
        } else {
            $this->layout()->logueado = false;
            $this->redirect()->toRoute('home');
        }
        
        
        $this->layout('layout/ajax_layout.phtml');
        $postData = $this->getRequest()->getPost();
        //var_export($postData);
        
        $listaIncap=$this->tableIncapacidad->consultarIncapacidades($postData);
        
        return new ViewModel(
            array(
                "incapacidades"=>$listaIncap
            ));
    }
    
    public function generarReporteAction(){
        
        $container = new Container('incapacidades');
        
        if (isset( $container->usuario )){
            //$this->redirect()->toRoute('home');
            $this->layout()->logueado = true;
        } else {
            $this->layout()->logueado = false;
            $this->redirect()->toRoute('home');
        }
        
        
        $this->layout('layout/ajax_layout.phtml');
        $postData = $this->getRequest()->getPost();
        
        
        
        
        $datos = [];
        //foreach ( $listaIncap as $incap ) {
        //    $datos[] = $incap->nombre." ".$incap->apellido1." ".$incap->apellido2;
        //}
        
        
        set_time_limit( 0 );
        
        if ( isset($postData["reporte"]) && !empty($postData["reporte"]) ) {
            
            switch ( $postData["reporte"] ) {
                case "Fechas" :
                    
                                $listaIncap=$this->tableIncapacidad->consultarIncapacidades($postData);
                                
                                $buf = "<table>";
                                
                                $buf .= "<tr>";
                                $buf .="<th>CEDULA</th>";
                                $buf .="<th>NOMBRE</th>";
                                $buf .="<th>DIAGNOSTICO</th>";
                                $buf .="<th>VALOR FACTURADO</th>";
                                $buf .="<th>VALOR PAGADO</th>";
                                $buf .="<th>EPS</th>";
                                $buf .="</tr>";
                                
                                foreach ( $listaIncap AS $incap) {
                                    
                                    $buf .= "<tr>";
                                    $buf .="<td>".utf8_decode( $incap->cedula)."</td>";
                                    $buf .="<td>".utf8_decode( $incap->nombre)." ".utf8_decode( $incap->apellido1)." ".utf8_decode( $incap->apellido2)."</td>";
                                    $buf .="<td>".utf8_decode( $incap->cod_diagnostico )." - ".utf8_decode( $incap->diagnostico)."</td>";
                                    $buf .="<td>".utf8_decode( $incap->valor_facturado ? $incap->valor_facturado : 0)."</td>";
                                    $buf .="<td>".utf8_decode( $incap->valor_pago_eps)."</td>";
                                    $buf .="<td>".utf8_decode( $incap->eps)."</td>";
                                    $buf .="</tr>";
                                    
                                }
                                
                                $buf .= "</table>";
                                break;
                                
                                
                case "Dias" :
                    
                    $listaIncap=$this->tableIncapacidad->consultarIncapacidades($postData);
                    
                    $buf = "<table>";
                    
                    $buf .= "<tr>";
                    $buf .="<th>CEDULA</th>";
                    $buf .="<th>NOMBRE</th>";
                    $buf .="<th>DIAGNOSTICO</th>";
                    $buf .="<th>DIAS</th>";
                    $buf .="</tr>";
                    
                    foreach ( $listaIncap AS $incap) {
                        
                        $buf .= "<tr>";
                        $buf .="<td>".utf8_decode( $incap->cedula)."</td>";
                        $buf .="<td>".utf8_decode( $incap->nombre)." ".utf8_decode( $incap->apellido1)." ".utf8_decode( $incap->apellido2)."</td>";
                        $buf .="<td>".utf8_decode( $incap->cod_diagnostico )." - ".utf8_decode( $incap->diagnostico)."</td>";
                        $buf .="<td>".( ( $incap->n_dias_emp ? $incap->n_dias_emp : 0) + ( $incap->n_dias_eps ? $incap->n_dias_eps: 0) + ( $incap->n_dias_arl ? $incap->n_dias_arl : 0) )."</td>";
                        $buf .="</tr>";
                        
                    }
                    
                    $buf .= "</table>";
                    break;
                    
                    
                    
                case "Eps" :
                    
                    $postData["group_by"] = "incapacidad.id_eps";
                    
                    $listaIncap=$this->tableIncapacidad->consultarIncapacidades($postData);
                    
                    $buf = "<table>";
                    
                    $buf .= "<tr>";
                    $buf .="<th>CEDULA</th>";
                    $buf .="<th>NOMBRE</th>";
                    $buf .="<th>DIAGNOSTICO</th>";
                    $buf .="<th>DIAS</th>";
                    $buf .="</tr>";
                    
                    foreach ( $listaIncap AS $incap) {
                        
                        $buf .= "<tr>";
                        $buf .="<td>".utf8_decode( $incap->cedula)."</td>";
                        $buf .="<td>".utf8_decode( $incap->nombre)." ".utf8_decode( $incap->apellido1)." ".utf8_decode( $incap->apellido2)."</td>";
                        $buf .="<td>".utf8_decode( $incap->cod_diagnostico )." - ".utf8_decode( $incap->diagnostico)."</td>";
                        $buf .="<td>".( ( $incap->n_dias_emp ? $incap->n_dias_emp : 0) + ( $incap->n_dias_eps ? $incap->n_dias_eps: 0) + ( $incap->n_dias_arl ? $incap->n_dias_arl : 0) )."</td>";
                        $buf .="</tr>";
                        
                    }
                    
                    $buf .= "</table>";
                    break;
                    
            }
            
        }
        echo $buf;
            
    }
    
    public function verIncapacidadAction()
    {
    
        $container = new Container('incapacidades');
    
        if (isset( $container->usuario )){
            //$this->redirect()->toRoute('home');
            $this->layout()->logueado = true;
        } else {
            $this->layout()->logueado = false;
            $this->redirect()->toRoute('home');
        }
    
    
        $this->layout('layout/ajax_layout.phtml');
        $postData = $this->getRequest()->getPost();
        
    
        $listaIncap=$this->tableIncapacidad->consultarIncapacidades($postData);
    
        return new ViewModel(
            array(
                "incapacidad"=>$listaIncap
            ));
    }
    
}
