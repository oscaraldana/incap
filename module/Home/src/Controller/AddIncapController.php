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

class AddIncapController extends AbstractActionController
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
                $parametrosFormulario["prorrogaDefault"] = [$datosInc->id_inc_prorroga => $datosInc->num_prorroga];
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
    
    
    public function saveIncapAction ()
    {
        $container = new Container('incapacidades');
        if (!isset( $container->usuario )){
          
            $this->layout()->logueado = false;
            $this->redirect()->toRoute('home');
        }
        
        $form = new FormIncapacidad();
        $request = $this->getRequest();
        $post = $request->getPost();
        
        if(!$request->isPost()){
            
            return $this->response;
        }else{
            $incap = new Incapacidad();
            $formValidator = new FilterFormIncapacidad();
            $form->setInputFilter($formValidator->getInputFilter());
            $form->setData($request->getPost());
            
            if($form->isValid()){
               /*
                var_export($request->getPost()); echo "<hr>";
                var_export($form->getData()); die;
                var_export( $startDate = \DateTime::createFromFormat('d/m/Y', '01/06/2017') );
                var_export( $endDate = \DateTime::createFromFormat('Y/m/d', '2017/02/01') );
            */
                $incap->exchangeArrayForm($form->getData());
                //$incapTable = new IncapacidadTable($this->tableIncapacidad);
                if ( $this->tableIncapacidad->saveIncapacidad($incap) ){
                    //$this->layout('layout/ajax_layout.phtml');
                    
                    if ( isset($post["id_incapacidad"]) && !empty($post["id_incapacidad"]) ) {
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
                                var url = "/add_incap/index/'.$post["id_incapacidad"].'";
                                $(location).attr("href",url);
                              }, 200);
                            });
                        </script>';
                    } else {
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
                    }
                    return new ViewModel(array('script' => $script));
                } 
                else{ echo "Error"; }
            }
            $messages = $form->getMessages();
            var_export($messages);
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
                array_push($respuesta, ["id" => $datos->id_diagnostico, "completeName" => $datos->codigo_diagnostico." - ".$datos->nombre_diagnostico]);
                //$varDiagn[$datos->id_diagnostico] = $datos->nombre;
            }
        }
    
        echo json_encode($respuesta);
    }

    
    
    public function buscarIncapacidadesAction()
    {
        $this->layout('layout/ajax_layout.phtml');
        $postData = $this->getRequest()->getPost();
    
        $incap = null;
    
        if ( isset($postData["asociado"]) && !empty($postData["asociado"]) ) {
            $incap = $this->tableIncapacidad->consultarIncapacidades(["cedula" => $postData["asociado"], "noincap" => $postData["term"]]);
        }
    
        $respuesta = array();
        //var_export($diag);
        if ( $incap != null) {
            foreach ($incap as $datos) {
                array_push($respuesta, ["id" => $datos->id_incapacidad, "completeName" => $datos->no_incapacidad]);
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
                array_push($respuesta, ["id" => $datos->id_asociado, "completeName" => $datos->identificacion." - ".$datos->nombre_asociado." ".$datos->apellido1_asociado." ".$datos->apellido2_asociado]);
            }
        }
    
        echo json_encode($respuesta);
    }
    
}
