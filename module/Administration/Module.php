<?php

namespace Administration;
use Zend\Authentication\AuthenticationService;

class Module
{

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

//    public function getServiceConfig()
//    {
//        return array(
//            'factories' => array(
//                'DoctrineAuthAdapter' => function($SM) {
////                    $EM = $SM->get('doctrine.entitymanager.orm_default');
//////                    =$EM->getRepository('Administration\Entity\User'),
////                    $Adapter = new \DoctrineModule\Authentication\Adapter\ObjectRepository(array(
////                        'objectManager' => $EM,
////                        'identityClass' => $EM->getRepository('Administration\Entity\User'),
////                        'identityProperty' => 'username',
////                        'credentialProperty' => 'password',
////                    ));
////                    return $Adapter;
////                    
////                    
//                    // If you are using DoctrineORMModule:
////                    return $serviceManager->get('doctrine.authenticationservice.orm_default');
//                    return $SM->get('doctrine.authentication.orm_default');
//                    // If you are using DoctrineODMModule:
////                    return $serviceManager->get('doctrine.authenticationservice.odm_default');
//                }
//            )
//        );
//    }
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Zend\Authentication\AuthenticationService' => function($serviceManager) {
                    return $serviceManager->get('doctrine.authenticationservice.orm_default');
                }
            )
        );
    }

}