<?php

namespace Reporting;

return array(
    'controllers' => array(
        'invokables' => array(
            'Reporting\Controller\Reporting' => 'Reporting\Controller\ReportingController',
        ),
    ),
    'router' => array(
        'routes' => array(
            //---- Reporting Routes
            'reporting' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/reporting[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Reporting\Controller\Reporting',
                        'action' => 'index',
                    ),
                ),
            ),
            
        //---- Another Routes
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'reporting' => __DIR__ . '/../view',
        ),
    ),
    // --------- Doctrine Settings For the Module
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    ), //------End Doctrine Setting
);