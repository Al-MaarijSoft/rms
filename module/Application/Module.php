<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use \Administration\Acl\Acl as MyAcl;

class Module
{

//*******Start Dont Remove below code
//    public function onBootstrap(MvcEvent $e)
//    {
//        $eventManager = $e->getApplication()->getEventManager();
//        $moduleRouteListener = new ModuleRouteListener();
//        $moduleRouteListener->attach($eventManager);
//    }
    //*******End Dont Remove Above code
    public function onBootstrap(MvcEvent $e)
    {
//        $this->initAcl($e);
        $e->getApplication()->getEventManager()->attach('route', array($this, 'checkAcl'));
    }

    public function checkAcl(MvcEvent $event)
    {
        $routeMatch = $event->getRouteMatch();
        $controller = $routeMatch->getParam('controller');
        $action = $routeMatch->getParam('action');
//        //@todo - Should we really use here and Controller Plugin?
//        $userAuth = $this->getUserAuthenticationPlugin();
        $userAuth = $event->getApplication()->getServiceManager()->get('Zend\Authentication\AuthenticationService');
        $acl = new MyAcl($this->getDbACLConfig($event));
        $role = MyAcl::DEFAULT_ROLE;
        if ($userAuth->hasIdentity())
        {
            $User = $userAuth->getIdentity();
            //*******************************
//            $role = 'Guest'; //@todo - Get role from user!
//            $role = 'Jnr. Data Operator'; //@todo - Get role from user!
//            $role = 'Assistant Accounts'; //@todo - Get role from user!
//            $role = $userAuth->getStorage()->read()->getRole()->getName();
            //*******************************
            $role = $User->getRole()->getName();
            $userName = $User->getName();


            $ViewModel = $event->getViewModel();
            $ViewModel->roleName = $role;
            $ViewModel->loginUserName = $userName;
            $ViewModel->currentFinancialYear = '';
            $ViewModel->currentFyStartDate = '';
            $ViewModel->currentFyEndDate = '';
            $ViewModel->ACLObj = $acl;
            $ViewModel->authorizationError = false;
            //=================FinancialYears Handling
            $FinancialYears = $User->getFinancialYears();
            if (count($FinancialYears))
            {
                foreach ($FinancialYears as $FinancialYear)
                {
                    if ($FinancialYear->getIsCurrent())
                    {
                        $ViewModel->currentFinancialYear = $FinancialYear->getName();
                        $ViewModel->currentFyStartDate = $FinancialYear->getStartDate();
                        $ViewModel->currentFyEndDate = $FinancialYear->getEndDate();
                        break;
                    }
                }
                if ($ViewModel->currentFinancialYear === '')
                {
//                    $this->getResponseWhenNotAllowed($event, $ViewModel);
                    die('Year is not allowed');
                }
            }
            else
            {
//                $this->getResponseWhenNotAllowed($event, $ViewModel);
                die('Year is not allowed');
            }
        }
        //==========================Check requested resource exist
        if (!$acl->hasResource($controller))
        {
            throw new \Exception('Resource ' . $controller . ' not defined');
        }
        //==========================Check requested resource isAllowed
        if (!$acl->isAllowed($role, $controller, $action))
        {
            $response = $event->getResponse();
            $Request = $event->getRequest();
//            $url404 = $Request->getBaseUrl() . '/404';
            if ($Request->isXmlHttpRequest())
            {
                $ViewModel->setTerminal(true);
                $ViewModel->authorizationError = true;
                $url = $Request->getHeader('Referer')->getUri();
                $authorizationErrorResponseHtml = '<h1 style="margin: 5px 0 0 30%">You are not authorized for the action that you tried to performed.</h1>
                      <hr /><a href="javascript:loadPage(\'' . $url . '\')">Go Back</a>';
                echo $authorizationErrorResponseHtml;
                exit;
            }
            $url = $event->getRouter()->assemble(array(), array('name' => 'login'));
            $response->getHeaders()->addHeaderLine('Location', $url);
            $response->setStatusCode(302);
            $response->sendHeaders();
            exit;
        }
    }

    private function getDbACLConfig(MvcEvent $e)
    {
        //@todo - Get ACL from DB!
        $aclConfigArray = include __DIR__ . '/config/module.acl.roles.php';
        return $aclConfigArray;
    }

    private function getResponseWhenNotAllowed(MvcEvent $event, $ViewModel)
    {
        $response = $event->getResponse();
        $Request = $event->getRequest();
        if ($Request->isXmlHttpRequest())
        {
            $ViewModel->setTerminal(true);
            $ViewModel->authorizationError = true;
            $url = $Request->getHeader('Referer')->getUri();
            $authorizationErrorResponseHtml = '<h1 style="margin: 5px 0 0 30%">You are not authorized for the action that you tried to performed.</h1>
                      <hr /><a href="javascript:loadPage(\'' . $url . '\')">Go Back</a>';
            echo $authorizationErrorResponseHtml;
            exit;
        }
        $url = $event->getRouter()->assemble(array(), array('name' => 'login'));
        $response->getHeaders()->addHeaderLine('Location', $url);
        $response->setStatusCode(302);
        $response->sendHeaders();
        exit;
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'EntityManager' => function(\Zend\ServiceManager\ServiceManager $SM) {
                    $EM = $SM->get('doctrine.entitymanager.orm_default');
                    return $EM;
                },
                'MailTransport' => function (\Zend\ServiceManager\ServiceManager $SM) {
                    $config = $SM->get('Config');
                    $transport = new \Zend\Mail\Transport\Smtp();
                    $transport->setOptions(new \Zend\Mail\Transport\SmtpOptions($config['mail']['transport']['options']));

                    return $transport;
                },
            ),
        );
    }

//*****************************************************
//    public function initAcl(MvcEvent $e)
//    {
//
//        $acl = new \Zend\Permissions\Acl\Acl();
////        $roles = include __DIR__ . '/config/module.acl.roles.php';
//        //======For ACL Based on Database
//        $roles = $this->getDbRoles($e);
//        $allResources = array();
//        foreach ($roles as $role => $resources)
//        {
//
//            $role = new \Zend\Permissions\Acl\Role\GenericRole($role);
//            $acl->addRole($role);
//
//            $allResources = array_merge($resources, $allResources);
//
//            //adding resources
//            foreach ($resources as $resource)
//            {
//                // Edit 4
//                if (!$acl->hasResource($resource))
//                    $acl->addResource(new \Zend\Permissions\Acl\Resource\GenericResource($resource));
//            }
//            //adding restrictions
//            foreach ($allResources as $resource)
//            {
//                $acl->allow($role, $resource);
//            }
//        }
//        //testing
//        //var_dump($acl->isAllowed('admin','home'));
//        //true
//        //setting to view
//        $e->getViewModel()->acl = $acl;
//    }
//    
//********************************************************************
//    public function checkAcl1(MvcEvent $e)
//    {
//        $route = $e->getRouteMatch()->getMatchedRouteName();
//        //you set your role
////        $userRole = 'Guest';
////        ************************************************************************************************************************
//        $AuthService = $e->getApplication()->getServiceManager()->get('Zend\Authentication\AuthenticationService');
////        $AuthService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
////        $AuthService->hasIdentity();
////        if (!$AuthService->hasIdentity())
////        {
////            $userRole = 'Guest';
////            $response = $e->getResponse();
//////            die('i m here');
//////            return $e->reditect()->toRoute('administration', array(
//////                        'action' => 'login'
//////            ));
//////            $response->getHeaders()->addHeaderLine('Location', $e->getRequest()->getBaseUrl() . '/administration/login');
//////            return $response->getHeaders()->addHeaderLine('Location', $e->getRequest()->getBaseUrl());
//////            die('fdfdfdf');
//////            return $e->getApplication()->run();
//////            $url = $e->getRouter()->assemble(array(), array('name' => 'login'));
//////            $response->getHeaders()->addHeaderLine('Location', $url);
//////            $response->setStatusCode(302);
//////            $response->sendHeaders();
//////            exit;
////        }
//        $userId = (is_object($AuthService->getStorage()->read()) ? $AuthService->getStorage()->read()->getId() : null);
//        if (is_null($userId))
//        {
//            $userRole = 'Guest';
//        }
//        else
//        {
//            $userRole = $AuthService->getStorage()->read()->getRole()->getName();
//        }
////        var_dump($userRole);
////        exit;
////        var_dump($AuthService->getStorage()->read());
////        exit;
////        ************************************************************************************************************************        
//        if (!$e->getViewModel()->acl->isAllowed($userRole, $route))
//        {
//            $response = $e->getResponse();
////            $router = $e->getRouter();
//            //location to page or what ever
//            $response->getHeaders()->addHeaderLine('Location', $e->getRequest()->getBaseUrl() . '/404');
//            $response->setStatusCode(303);
//
//            ////****redirect to login route... 
//            //            $url = $router->assemble(array(), array('name' => 'administration'));
//            //            $response->getHeaders()->addHeaderLine('Location', $url);
//        }
//    }
//    public function getDbRoles(MvcEvent $e)
//    {
//        $EM = $e->getApplication()->getServiceManager()->get('doctrine.entitymanager.orm_default');
//        $RolesObj = $EM->getRepository('Administration\Entity\ResourceToRole')->findAll();
//        // making the roles array
//        $roles = array();
//        foreach ($RolesObj as $Rol)
//        {
//            $roles[$Rol->getRole()->getName()][] = $Rol->getResource()->getName();
//        }
//        return $roles;
//    }
//**********************************************
//    public function getViewHelperConfig()
//    {
//        return array(
//            'factories' => array(
//                //This will overwrite native navigation helper
//                'navigation' => function(\Zend\View\HelperPluginManager $PM) {
//                    $SM = $PM->getServiceLocator();
//                    $config = include __DIR__ . '/config/module.acl.roles.php';
//                    //Setup ACL
//                    $ACL = new \Administration\Acl\Acl($config);
//                    $userAuth = $SM->get('Zend\Authentication\AuthenticationService');
//                    $role = MyAcl::DEFAULT_ROLE;
//
//                    if ($userAuth->hasIdentity())
//                    {
//                        $User = $userAuth->getIdentity();
////            $role = 'member'; //@todo - Get role from user!
////            $role = $userAuth->getStorage()->read()->getRole()->getName();
//                        $role = $User->getRole()->getName();
//                        $userName = $User->getName();
//                    }
//                    $Navigation = $PM->get('Zend\View\Helper\Navigation');
//                    $Navigation->setAcl($ACL)
//                            ->setRole($role);
//                    return $Navigation;
//                }
//            ),
//        );
//    }
}
