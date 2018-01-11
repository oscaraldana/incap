<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Home;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Home;
use Home\Controller\AddIncapController;
use Home\Controller\ListIncapController;
use Zend\ServiceManager\ServiceManager;
use Home\Model;
use Interop\Container\ContainerInterface;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'home1' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/home[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            
            'add_incap' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/add_incap[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\AddIncapController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' =>[
                    'save_incap' =>[
                        'type'      =>'Segment',
                        'options'   =>[
                            'route'         => '/save_incap[/:action[/:id]]',
                            'constraints' => [
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*'
                            ],
                            'defaults'=> [
                                'controller' => Controller\AddIncapController::class,
                                'action'     => 'saveIncap',
                            ],
                        ],
                    ],
                ],
            ],
            
            'upload_incap' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/upload_incap[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\UploadIncapController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' =>[
                    'upload_incap' =>[
                        'type'      =>'Segment',
                        'options'   =>[
                            'route'         => '/upload_incap[/:action[/:id]]',
                            'constraints' => [
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*'
                            ],
                            'defaults'=> [
                                'controller' => Controller\UploadIncapController::class,
                                'action'     => 'uploadIncap',
                            ],
                        ],
                    ],
                ],
            ],
            
            
            'list_incap' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/list_incap[/:action]',
                    'defaults' => [
                        'controller' => Controller\ListIncapController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' =>[
                    /*'save_incap' =>[
                        'type'      =>'Segment',
                        'options'   =>[
                            'route'         => '/save_incap[/:action[/:id]]',
                            'constraints' => [
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*'
                            ],
                            'defaults'=> [
                                'controller' => Controller\AddIncapController::class,
                                'action'     => 'saveIncap',
                            ],
                        ],
                    ],*/
                ],
            ],
            
            'login' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/login[/:action]',
                    'defaults' => [
                        'controller' => Controller\LoginController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            /*Controller\LoginController::class => function(ContainerInterface $serviceManager, $controller){
                $repository = $serviceManager->get(Model\UsuariosTable::class);
                return new LoginController($repository);
            },*/
            //Controller\AddIncapController::class => InvokableFactory::class,
            /*Controller\AddIncapController::class => function(ContainerInterface $serviceManager, $controller){
                $repository = new $serviceManager->get(Repositorios::class);
                return new AddIncapController($repository);
            },*/
        ],
    ],
    'service_manager' => [
        'factories' => [
            'Home\Mapper\PostMapperInterface'   => 'Home\Factory\ZendDbSqlMapperFactory',
            'Home\Service\PostServiceInterface' => 'Home\Factory\PostServiceFactory',
            'Zend\Db\Adapter\Adapter'           => 'Zend\Db\Adapter\AdapterServiceFactory'
        ]
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
