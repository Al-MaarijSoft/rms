<?php

namespace Administration\Controller;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
use Zend\Mvc\Controller\AbstractActionController,
    Administration\Entity\Country,
    Administration\Filter\CountryFilter,
    Administration\Form\CountryForm,
    Zend\View\Model\ViewModel,
    DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter,
    Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator,
    Zend\Paginator\Paginator;


class CountryController extends AbstractActionController
{

    public function __construct()
    {
        
    }
    
    public function indexAction()
    {
        $View = new ViewModel();
        $Repo = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Country');
        $ORMPaginator = new ORMPaginator($Repo->createQueryBuilder('countries'));
        $Adapter = new DoctrineAdapter($ORMPaginator);
        $Paginator = new Paginator($Adapter);
        $Paginator->setDefaultItemCountPerPage(5);
        $page = (int) $this->params()->fromQuery('page', 1);
        $Paginator->setCurrentPageNumber($page);
        $View->setVariable('Paginator', $Paginator);
        return $View;
    }
    
    public function addAction()
    {
        $arrSelectData = null;
        $ViewModel = new ViewModel();
        
        $Form = new CountryForm();
        $Form->get('submit')->setValue('Add');
//        $this->save($Form);
        //*********** Code For Insertion
        $Request = $this->getRequest();
        $countryName = $Request->getPost('name');
        
        if ($Request->isPost())
        {
            $Country = new Country();
            $CountryFilter = new CountryFilter();
            $Form->setInputFilter($CountryFilter->getInputFilter());
            $Form->setData($Request->getPost());
            if($Form->isValid())
            {
                //$Country->setName($countryName);
                $Country->exchangeArray($Form->getData());
                $this->getServiceLocator()->get('EntityManager')->persist($Country);
                $this->getServiceLocator()->get('EntityManager')->flush();
                return $this->redirect()->toRoute('country', array(
                            'action' => 'index'
                ));
            }
        }
        
        //$ViewModel->setVariable('errorMsgs', $this->errorMsgs);
        $ViewModel->setVariable('Form', $Form);
        return $ViewModel;
    }
    
    public function editAction()
    {
        
        $ViewModel = new ViewModel();
        $id = (int) $this->params()->fromRoute('id', null);
        $Request = $this->getRequest();
        $countryName = $Request->getPost('name');
        $Form = new CountryForm();
        if (null === $id)
        {
            return $this->redirect()->toRoute('country', array(
                        'action' => 'index'
            ));
        }
        if ($id)
            $Country = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Country')->find($id);
        else
            $Country = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Country')->find($Request->getPost('id'));
        $Form->bind($Country);
        
        if ($Request->isPost())
        {
          
            $CountryFilter = new CountryFilter();
            $Form->setInputFilter($CountryFilter->getInputFilter());
            $Form->setData($Request->getPost());
            if($Form->isValid())
            {
               
              //  $Country->exchangeArray($Form->getData());
                $this->getServiceLocator()->get('EntityManager')->persist($Country);
                $this->getServiceLocator()->get('EntityManager')->flush();
                return $this->redirect()->toRoute('country', array(
                            'action' => 'index'
                ));
            }
        }
        
        //$ViewModel->setVariable('errorMsgs', $this->errorMsgs);
        $ViewModel->setVariable('Form', $Form);
        return $ViewModel;
    }
    
    
    
}
?>