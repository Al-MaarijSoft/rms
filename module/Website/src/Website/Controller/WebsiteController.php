<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PlotController
 *
 * @author Muhammad Rashid
 */

namespace Website\Controller;

use Application\Library\Application;
use Application\Library\WkHtmlToPdf;
use Reporting\Form\VoucherReportingForm;
use Reporting\Form\AccountLedgerReportingForm;
use Zend\Http\Client;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use \Website\Form\SignUpForm;
//use Zend\Http\Client\Adapter\Curl;

class WebsiteController extends AbstractActionController
{
    public function indexAction()
    {
//        $layout = $this->layout();
//        $layout->setTemplate('layout/layout.phtml');
        $ViewModel = new ViewModel();
        $Request = $this->getRequest();
        if ($Request->isXmlHttpRequest())
        {
            $ViewModel->setTerminal(true);
        }
        return $ViewModel;
    }
    
    public function signupAction(){
        $Form = new SignUpForm();
        $Request = $this->getRequest();
        $ViewModel = new ViewModel();
        $Company = new \Administration\Entity\Company();
        if ($Request->isXmlHttpRequest())
        {
            $ViewModel->setTerminal(true);
        }
        $Form = new SignUpForm();
        if ($Request->isPost())
        {
             $Form->setData($Request->getPost());
             if ($Form->isValid())
            {
                 $Company->exchangeArray($Form->getData());
                 $this->getServiceLocator()->get('EntityManager')->persist($Company);
                 $this->getServiceLocator()->get('EntityManager')->flush();
             }
        }
        $ViewModel->setVariable('Form', $Form);
        return $ViewModel;
    }
    
    public function aboutUsAction(){
        $Request = $this->getRequest();
        $ViewModel = new ViewModel();
        if ($Request->isXmlHttpRequest())
        {
            $ViewModel->setTerminal(true);
        }
        return $ViewModel;
    }
    
    public function rmsFeatureAction(){
        $Request = $this->getRequest();
        $ViewModel = new ViewModel();
        if ($Request->isXmlHttpRequest())
        {
            $ViewModel->setTerminal(true);
        }
        return $ViewModel;
    }
    
    
}
