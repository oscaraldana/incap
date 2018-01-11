 <?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return [
    
    'db' => [
        'username' => 'root',
    
        'password' => '',
    
        'driver' => 'Pdo',
    
        'dsn' => 'mysql:dbname=incapacidades;host=localhost',
    
        'driver_options' => [
    
            PDO::ATTR_PERSISTENT => false
        ],
    ],
    /*'db' => [
        'username' => 'postgres',
    
        'password' => 'movilaction',
    
        'driver' => 'Pdo',
    
        'dsn' => 'pgsql:dbname=incapacidades;host=localhost',
    
        'driver_options' => [
    
            PDO::ATTR_PERSISTENT => false
        ],
    ],*/
    
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    ),
];
