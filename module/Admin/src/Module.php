<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;


class Module implements ConfigProviderInterface
{
    const VERSION = '3.0.2dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
    
    public function onBootstrap($e)
    {
        // (...) Other code
    
        $application = $e->getParam('application');
        $viewModel = $application->getMvcEvent()->getViewModel();
    
        // Parsing URI to get controller name
        $viewModel->controllerName = trim($_SERVER['REQUEST_URI'],'/');
        if (substr_count($viewModel->controllerName, '/')) {
            $viewModel->controllerName = substr($viewModel->controllerName, 0, strpos($viewModel->controllerName, '/'));
        }
    }
    
    
    
    public function getServiceConfig()
    {
        return [
            
            'factories' => [
                \HomeModel\CausalesTable::class => function($container) {
                    $tableGateway = $container->get(\Home\Model\CausalesTableGateway::class);
                    return new \Home\Model\CausalesTable($tableGateway);
                },
                \HomeModel\CausalesTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new \Home\Model\Causales());
                    return new TableGateway('causales', $dbAdapter, null, $resultSetPrototype);
                },
                \HomeModel\DiagnosticoTable::class => function($container) {
                    $tableGateway = $container->get(\Home\Model\DiagnosticoTableGateway::class);
                    return new \Home\Model\CausalesTable($tableGateway);
                },
                \HomeModel\DiagnosticoTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new \Home\Model\Diagnostico());
                    return new TableGateway('diagnostico', $dbAdapter, null, $resultSetPrototype);
                },
                /*
                Model\SucursalTable::class => function($container) {
                    $tableGateway = $container->get(Model\SucursalTableGateway::class);
                    return new Model\SucursalTable($tableGateway);
                },
                Model\SucursalTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Sucursal());
                    return new TableGateway('sucursal', $dbAdapter, null, $resultSetPrototype);
                },

                Model\EpsTable::class => function($container) {
                    $tableGateway = $container->get(Model\EpsTableGateway::class);
                    return new Model\EpsTable($tableGateway);
                },
                Model\EpsTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Eps());
                    return new TableGateway('eps', $dbAdapter, null, $resultSetPrototype);
                },
            
                Model\DiagnosticoTable::class => function($container) {
                    $tableGateway = $container->get(Model\DiagnosticoTableGateway::class);
                    return new Model\DiagnosticoTable($tableGateway);
                },
                Model\DiagnosticoTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Diagnostico());
                    return new TableGateway('diagnostico', $dbAdapter, null, $resultSetPrototype);
                },
                

                Model\UsuariosTable::class => function($container) {
                    $tableGateway = $container->get(Model\UsuariosTableGateway::class);
                    return new Model\UsuariosTable($tableGateway);
                },
                Model\UsuariosTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Usuarios());
                    return new TableGateway('usuarios', $dbAdapter, null, $resultSetPrototype);
                },

                Model\AsociadoTable::class => function($container) {
                    $tableGateway = $container->get(Model\AsociadoTableGateway::class);
                    return new Model\AsociadoTable($tableGateway);
                },
                Model\AsociadoTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Asociado());
                    return new TableGateway('asociado', $dbAdapter, null, $resultSetPrototype);
                },
                
                Model\IncapacidadTable::class => function($container) {
                    $tableGateway = $container->get(Model\IncapacidadTableGateway::class);
                    return new Model\IncapacidadTable($tableGateway);
                },
                Model\IncapacidadTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Incapacidad());
                    return new TableGateway('incapacidad', $dbAdapter, null, $resultSetPrototype);
                },
                
                Model\CausalesTable::class => function($container) {
                    $tableGateway = $container->get(Model\CausalesTableGateway::class);
                    return new Model\CausalesTable($tableGateway);
                },
                Model\CausalesTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Causales());
                    return new TableGateway('causales', $dbAdapter, null, $resultSetPrototype);
                },
                */
            ],
        ];
    }
    
    
    
    
    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\CausalesController::class => function($container) {
                    return new Controller\CausalesController(
                            $container->get(\Home\Model\CausalesTable::class)
                        );
                },
                Controller\DiagnosticosController::class => function($container) {
                    return new Controller\DiagnosticosController(
                            $container->get(\Home\Model\DiagnosticoTable::class)
                        );
                },
                /*
                Controller\ListIncapController::class => function($container) {
                    return new Controller\ListIncapController(
                        $container->get(Model\TipoIncapacidadTable::class),
                        $container->get(Model\SucursalTable::class),
                        $container->get(Model\EpsTable::class),
                        $container->get(Model\DiagnosticoTable::class),
                        $container->get(Model\AsociadoTable::class),
                        $container->get(Model\IncapacidadTable::class)
                        );
                },
                Controller\LoginController::class => function($container) {
                    return new Controller\LoginController(
                        $container->get(Model\UsuariosTable::class)
                        );
                },
                */
           ],
       ];
    }
    
    
}
