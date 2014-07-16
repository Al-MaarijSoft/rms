<?php

namespace Account;

return array(
    'controllers' => array(
        'invokables' => array(
            'Account\Controller\Account' => 'Account\Controller\AccountController',
            'Account\Controller\Voucher' => 'Account\Controller\VoucherController',
            'Account\Controller\FinancialYear' => 'Account\Controller\FinancialYearController',
            'Account\Controller\OpeningBalance' => 'Account\Controller\OpeningBalanceController',
        ),
    ),
    'router' => array(
        'routes' => array(
            //---- Account Routes
            'account' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/account[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Account\Controller\Account',
                        'action' => 'index',
                    ),
                ),
            ),
            //---- Voucher Routes
            'voucher' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/account/voucher[/][:action][/:id][/page/:page]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'page' => '[1-9]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Account\Controller\Voucher',
                        'action' => 'index',
                    ),
                ),
            ),
            //---- financial_year Routes
            'financial_year' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/account/financial_year[/][:action][/:id][/page/:page]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'page' => '[1-9]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Account\Controller\FinancialYear',
                        'action' => 'index',
                    ),
                ),
            ),
            //---- opening_balance Routes
            'opening_balance' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/account/opening_balance[/][:action][/:id][/page/:page]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'page' => '[1-9]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Account\Controller\OpeningBalance',
                        'action' => 'index',
                    ),
                ),
            ),
        //---- Another Routes
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'account' => __DIR__ . '/../view',
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


