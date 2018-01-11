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
use Home\Model\Usuarios;
use Home\Model\UsuariosTable;

class LoginController extends AbstractActionController
{
    protected $usuariosTable;
    protected $userRepository;
    
    public function __construct(UsuariosTable $usuario)
    {
        $this->userRepository = $usuario;
        //error_log("COMO ASI!!!???");
    }
        
    public function indexAction()
    {
        $container = new Container('incapacidades');

        $viewModel = new ViewModel();
        if (isset( $container->usuario )){
            //$this->redirect()->toRoute('home');
            $this->layout()->logueado = true;
        } else {
            $this->layout()->logueado = false;
        }


        return $viewModel;
    }
    
    public function authenticateAction()
    {
        
        $this->layout('layout/ajax_layout.phtml');
        $postData = $this->getRequest()->getPost();
        $respuesta = array("acceso" => false);
        
        $resp = $this->userRepository->validateUsuario($postData['usuario'], $postData['clave']);
        
        // Login satisfactorio
        if ($resp && isset($resp->id_usuario) && (isset($resp->id_usuario) && !empty($resp->id_usuario))) {
            $container = new Container('incapacidades');
            $container->usuario = $resp->login;
            $respuesta["acceso"] = true;
        }
        
        echo json_encode($respuesta);
        
    }
    
    
    public function logoutAction()
    {
        $this->layout('layout/ajax_layout.phtml');
        
        $postData = $this->getRequest()->getPost();
        
        if (isset($postData["logout"]) && $postData["logout"] == "salir") {
            
            $container = new Container('incapacidades');
            
            unset($container->usuario);
            
            error_log(var_export($this->getRequest()->getPost(), true));
            
        }
        
        $respuesta = array("logout" => false);
        
        echo json_encode($respuesta);
    }
    
    
    public function getUsersTable()
    {
        if (!$this->albumTable) {
            $sm = $this->getServiceLocator();
            $this->albumTable = $sm->get('Album\Model\AlbumTable');
        }
        return $this->albumTable;
    }
    
    
    
    public function getAdapter()
    {
        if (!isset($this->adapter)) {
            $sm = $this->getServiceLocator();
            $this->adapter = $sm->get('Zend\Db\Adapter\Adapter');
        }
        return $this->adapter;
    }
}
