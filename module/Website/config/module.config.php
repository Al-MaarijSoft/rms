<?php

namespace Website;

return array(
    'controllers' => array(
        'invokables' => array(
            'Website\Controller\Website' => 'Website\Controller\WebsiteController',
        ),
    ),
    'router' => array(
        'routes' => array(
            //---- Website Routes
            'website' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/[:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Website\Controller\Website',
                        'action' => 'index',
                    ),
                ),
            ),
            
        //---- Another Routes
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'website' => __DIR__ . '/../view',
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