<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Admin;
//use Home\Controller\AddIncapController;
//use Home\Controller\ListIncapController;
use Zend\ServiceManager\ServiceManager;
//use Home\Model;
use Interop\Container\ContainerInterface;
use Home;
use Admin\Controller\CausalesController;

return [
    'router' => [
        'routes' => [
            /*'admin' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],*/
            'admin' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/admin[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            
            'causales' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/causales[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\CausalesController::class,
                        'action'     => 'index',
                    ],
                ],
                /*'may_terminate' => true,
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
                ],*/
            ],
            

            /*'list_incap' => [
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
            ],*/
            
            /*'login' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/login[/:action]',
                    'defaults' => [
                        'controller' => Controller\LoginController::class,
                        'action'     => 'index',
                    ],
                ],
            ],*/
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            //Controller\CausalesController::class => InvokableFactory::class,
            Controller\CausalesController::class => function(ContainerInterface $serviceManager, $controller){
                $repository = $serviceManager->get(\Home\Model\CausalesTable::class);
                return new CausalesController($repository);
            },
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
            //'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
