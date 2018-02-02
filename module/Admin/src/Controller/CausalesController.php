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
use Home\Model\Causales;

class CausalesController extends AbstractActionController
{
    private $tableCausales;
    
    public function __construct(
                                \Home\Model\CausalesTable $tableCausales
                                ){
            
        $this->tableCausales = $tableCausales;
            
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
        }
        
        
        return $viewModel;
    }
}
