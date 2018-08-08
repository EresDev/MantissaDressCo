<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/21/14
 * Time: 12:33 AM
 */
//config/autoload/doctrine.global.php
return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host' => 'localhost',
                    'port' => '3306',
                    'dbname' => 'arslvsec_mantissadb',
                ),
                'doctrine_type_mappings' => array(
                    'enum' => 'string'
                ),
            ),
        )
    ));