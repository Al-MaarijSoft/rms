<?php

namespace Xhtml\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class XhtmlController extends AbstractActionController
{
    public function __construct()
    {
        
    }

    public function indexAction()
    {
        $ViewModel = new ViewModel();
//        $ViewModel->setTerminal(true);
        return $ViewModel;
    }
    
    public function loginPageAction()
    {
        $ViewModel = new ViewModel();
        $ViewModel->setTerminal(true);
        return $ViewModel;
    }
    
    public function layoutPageAction()
    {
        $ViewModel = new ViewModel();
        return $ViewModel;
    }
    
    public function showFancyBoxAction()
    {
        $ViewModel = new ViewModel();
        return $ViewModel;
    }
    public function voucherListPageAction()
    {
        $ViewModel = new ViewModel();
        return $ViewModel;
    }
}
