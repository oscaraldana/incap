<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Home\Model\Diagnostico;

class DiagnosticosController extends AbstractActionController
{
    private $tableDiagnosticos;
    
    public function __construct(
                                \Home\Model\DiagnosticoTable $tableDiagnosticos
                                ){
            
        $this->tableDiagnosticos = $tableDiagnosticos;
            
    }
    
    public function indexAction()
    {
        $container = new Container('incapacidades');
        
        $viewModel = new ViewModel();
        if ( isset( $container->usuario )){
            //$this->redirect()->toRoute('home');
            $this->layout()->logueado = true;
        } else {
            $this->layout()->logueado = false;
            $this->redirect()->toRoute('home');
        }
        
        if(isset($container->paramBuscar) && !empty($container->paramBuscar)){
            $listaDiag=$this->tableDiagnosticos->searchDiagnosticos($container->paramBuscar);
        }
        
         
        if(isset($listaDiag)){
            return new ViewModel(
            array(
                "diagnosticos"=>$listaDiag, "paramBuscar" => $container->paramBuscar
            ));
        } else {
            return new ViewModel();
        }
        
        //return $viewModel;
    }
    
    public function nuevoDiagnosticoAction(){
        
        $container = new Container('incapacidades');
        $this->layout('layout/ajax_layout.phtml');
        $viewModel = new ViewModel();
        if ( isset( $container->usuario )){
            //$this->redirect()->toRoute('home');
            $this->layout()->logueado = true;
        } else {
            $this->layout()->logueado = false;
            $this->redirect()->toRoute('home');
        }
        
        return $viewModel;
    }
    
    
    public function buscarAction()
    {
        $container = new Container('incapacidades');
        
        $this->layout('layout/ajax_layout.phtml');
        
        $parametro = $this->params()->fromPost('diagnostico', '');
        
        $viewModel = new ViewModel();
        if ( isset( $container->usuario )){
            
            $this->layout()->logueado = true;
        } else {
            $this->layout()->logueado = false;
            $this->redirect()->toRoute('home');
        }
        
        if ( !empty($parametro) ){
            $listaDiag=$this->tableDiagnosticos->searchDiagnosticos($parametro);
            $container->paramBuscar = $parametro;
        }
        
         
        if(isset($listaDiag)){
            return new ViewModel(
            array(
                "diagnosticos"=>$listaDiag, "paramBuscar" => $parametro
            ));
        } else {
            return new ViewModel();
        }
        
        //return $viewModel;
    }
}
