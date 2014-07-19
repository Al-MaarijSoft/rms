<?php

/**
 * Description of AdministrationController
 *
 * @author rashid
 */

namespace Administration\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel;
use Zend\Form\Annotation\AnnotationBuilder;

class AdministrationController extends AbstractActionController
{

    protected $Form;
    private $AuthService;
    private $errorMsgs = array();

    public function __construct()
    {
        
    }

    public function indexAction()
    {
        
    }

    public function getForm()
    {
        if (!$this->Form)
        {
            $user = new \Administration\Entity\User();
            $builder = new AnnotationBuilder();
            $this->Form = $builder->createForm($user);
        }

        return $this->Form;
    }

    public function loginAction()
    {
        $View = new ViewModel();
        $View->setTerminal(true);
        $Form = $this->getForm();
        $Request = $this->getRequest();
        if ($Request->isPost())
        {
            $data = $this->getRequest()->getPost();
            $AuthService = $this->getAuthService();
            $Adapter = $AuthService->getAdapter();
            $Adapter->setIdentityValue($data['username']);
            $Adapter->setCredentialValue($data['password']);
            $authResult = $AuthService->authenticate();
            if ($authResult->isValid())
            {
                $identity = $authResult->getIdentity();
                $AuthService->getStorage()->write($identity);
//                return $this->redirect()->toRoute('home');
                return $this->redirect()->toRoute('account');
            }
            else
            {
                $this->errorMsgs['auth'] = 'Your authentication credentials are not valid';
            }
        }
        $vars = array(
            'Form' => $Form,
            'errorMsgs' => $this->errorMsgs,
        );
        return $View->setVariables($vars);
    }

    public function logoutAction()
    {
        $this->getAuthService()->clearIdentity();
        session_destroy();
        $this->flashmessenger()->addMessage("You've been logged out");
//        die('I am here');
        return $this->redirect()->toRoute('login');
    }

    private function getAuthService()
    {
        if (!$this->AuthService)
        {
            $this->AuthService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
        }
        return $this->AuthService;
    }

}

