<?php

namespace Administration;

return array(
    'controllers' => array(
        'invokables' => array(
            'Administration\Controller\Administration' => 'Administration\Controller\AdministrationController',
            'Administration\Controller\Company' => 'Administration\Controller\CompanyController',
            'Administration\Controller\Branch' => 'Administration\Controller\BranchController',
            'Administration\Controller\User' => 'Administration\Controller\UserController',
            'Administration\Controller\Role' => 'Administration\Controller\RoleController',
            'Administration\Controller\Resource' => 'Administration\Controller\ResourceController',
            'Administration\Controller\Allocation' => 'Administration\Controller\AllocationController',
            'Administration\Controller\Country' => 'Administration\Controller\CountryController',
        ),
    ),
    'router' => array(
        'routes' => array(
            //---- Administration Routes
            'administration' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/administration[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Administration\Controller\Administration',
                        'action' => 'login',
                    ),
                ),
            ),
            //---- Company Routes
            'company' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/administration/company[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Administration\Controller\Company',
                        'action' => 'index',
                    ),
                ),
            ),
            //---- Branch Routes
            'branch' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/administration/branch[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Administration\Controller\Branch',
                        'action' => 'index',
                    ),
                ),
            ),
            //---- Role Routes
            'role' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/administration/role[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Administration\Controller\Role',
                        'action' => 'index',
                    ),
                ),
            ),
            //---- Role Routes
            'resource' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/administration/resource[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Administration\Controller\Resource',
                        'action' => 'index',
                    ),
                ),
            ),
            //-----------------------------
            //---- Allocation Routes
            'allocation' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/administration/allocation[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Administration\Controller\Allocation',
                        'action' => 'index',
                    ),
                ),
            ),
            //-----------------------------
            //---- User Routes
            'user' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/administration/user[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Administration\Controller\User',
                        'action' => 'index',
                    ),
                ),
            ),
            //---- Country Routes
            'country' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/administration/country[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Administration\Controller\Country',
                        'action' => 'index',
                    ),
                ),
            ),
        //-----------------------------
        ),
    ),
    // --------- Doctrine Settings For the Module
    'doctrine' => array(
        'authentication' => array(
            'orm_default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
                'identity_class' => 'Administration\Entity\User',
                'identity_property' => 'username',
                'credential_property' => 'password',
                'credential_callable' => function(\Administration\Entity\User $User, $passwordGiven) {
                    return \Administration\Entity\User::verifyHashedPassword($User, $passwordGiven);
                },
            ),
        ),
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
    ),
    //........... Helper setting for Pagination
    'view_helpers' => array(
        'factories' => array(
            'Requesthelper' => function($sm) {
                $helper = new \Administration\View\Helper\Requesthelper();
                $request = $sm->getServiceLocator()->get('Request');
                $helper->setRequest($request);
                return $helper;
            }
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'administration' => __DIR__ . '/../view',
        ),
    ),
);
