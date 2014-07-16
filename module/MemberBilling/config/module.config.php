<?php

namespace MemberBilling;

return array(
    'controllers' => array(
        'invokables' => array(
            'MemberBilling\Controller\Plot' => 'MemberBilling\Controller\PlotController',
            'MemberBilling\Controller\Member' => 'MemberBilling\Controller\MemberController',
        ),
    ),
    'router' => array(
        'routes' => array(
            //---- Plot Routes
            'plot' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/member_billing/plot[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'MemberBilling\Controller\Plot',
                        'action' => 'index',
                    ),
                ),
            ),
            //---- Member Routes
            'member' => array(
                'type' => 'segment',
                'options' => array(
//                    'route' => '/account/voucher[/][:action][/:id][/page/:page]',
                    'route' => '/member_billing/member[/][:action][/:id][/page/:page]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'page' => '[1-9]*',
                    ),
                    'defaults' => array(
                        'controller' => 'MemberBilling\Controller\Member',
                        'action' => 'index',
                    ),
                ),
            ),
        //---- Another Routes
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'member-billing' => __DIR__ . '/../view',
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


