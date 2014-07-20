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
        $Request = $this->getRequest();
        $ViewModel = new ViewModel();
        if ($Request->isXmlHttpRequest())
        {
            $ViewModel->setTerminal(true);
        }
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
