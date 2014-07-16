<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BranchController
 *
 * @author rashid
 */

namespace Administration\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    \Administration\Form\BranchForm,
    Administration\Filter\BranchFilter,
    Administration\Entity\Branch,
    Administration\Entity\BioInfo,
    DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter,
    Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator,
    Zend\Paginator\Paginator;

class BranchController extends AbstractActionController
{

    public function indexAction()
    {
        $View = new ViewModel();
        $Repo = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Branch');
        $ORMPaginator = new ORMPaginator($Repo->createQueryBuilder('branches'));
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
        $arrSelectData['Company'] = $this->getCompanySelectData();
        $arrSelectData['Country'] = $this->getCountrySelectData();
        //************
        $Form = new BranchForm($arrSelectData);
        $Form->get('submit')->setValue('Add');
        //*********** Code For Insertion
        $Request = $this->getRequest();
        if ($Request->isPost())
        {
            //================ For Company-Object
            $idCompany = $Request->getPost('company');
            $Company = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Company')->find($idCompany);
            //*****/
            //================ For populate State during save 
            $arrData = array();
            $idCountry = $Request->getPost('country');
            $arrData = $this->getStateSelectData($idCountry);
            $Form->get('state')->setValueOptions($arrData);
            //================ For populate City during save
            $arrData = array();
            $idState = $Request->getPost('state');
            $arrData = $this->getCitySelectData($idState);
            $Form->get('city')->setValueOptions($arrData);
            //*****/
            //================ For City-Object
            $idCity = $Request->getPost('city');
            $City = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\City')->find($idCity);
            //*****/
            $Branch = new Branch();
            $BioInfo = new BioInfo();
            $BranchFilter = new BranchFilter();
            $Form->setInputFilter($BranchFilter->getInputFilter());
            $Form->setData($Request->getPost());
            if ($Form->isValid())
            {
                $Branch->setCompany($Company);
                $Branch->exchangeArray($Form->getData());
                $this->getServiceLocator()->get('EntityManager')->persist($Branch);
                //*****Set BioInfo Data
                $BioInfo->exchangeArray($Form->getData());
                $BioInfo->setBranch($Branch);
                $BioInfo->setCity($City);
                $this->getServiceLocator()->get('EntityManager')->persist($BioInfo);
                $this->getServiceLocator()->get('EntityManager')->flush();
                //==============New Instance Of Form
                $Form = null;
                $Form = new BranchForm($arrSelectData);
                //=================
            }
        }
        $ViewModel->setVariable('Form', $Form);
        return $ViewModel;
    }

    public function editAction()
    {
        $arrSelectData = null;
        $ViewModel = new ViewModel();
        $id = (int) $this->params()->fromRoute('id', null);
        if (null === $id)
        {
            return $this->redirect()->toRoute('company', array(
                        'action' => 'add'
            ));
        }
        $arrSelectData['Company'] = $this->getCompanySelectData();
        $arrSelectData['Country'] = $this->getCountrySelectData();
        $Form = new BranchForm($arrSelectData);
        $Branch = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Branch')->find($id);
        $Form->get('submit')->setValue('Add');
        if (count($Branch))
        {
            $BioInfos = $Branch->getBioInfos();
            $idCompany = $Branch->getCompany()->getId();
            if (count($BioInfos))
            {
                foreach ($BioInfos as $B)
                {
                    $BioInfo = $B;
                    $City = $B->getCity();
                }
            }
            else
                die('Child-Table has not Parent-Table id');

            //================ For Company-Object during retrieve
            $Form->get('company')->setAttributes(array('value' => $idCompany, 'selected' => true));
            //===============================Country select during retrive
            $Form->get('country')->setAttributes(array('value' => $City->getState()->getCountry()->getId(), 'selected' => true));
            //================ For populate State during edit load and set db selected value 
            $arrData = $this->getStateSelectData($City->getState()->getCountry()->getId());
            $Form->get('state')->setValueOptions($arrData);
            $Form->get('state')->setAttributes(array('value' => $City->getState()->getId(), 'selected' => true));
            //================ For populate City during save
            $arrData = $this->getCitySelectData($City->getState()->getId());
            $Form->get('city')->setValueOptions($arrData);
            $Form->get('city')->setValue($City->getId());
            //**************End Data Loading
            $Form->bind($BioInfo);
            $Form->bind($Branch);
            //**** Start Updation
            $Request = $this->getRequest();
            if ($Request->isPost())
            {
                //================ For Company-Object
                $idCompany = $Request->getPost('company');
                $Company = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Company')->find($idCompany);
                //*****/
                //================ For populate State during save 
                $arrData = array();
                $idCountry = $Request->getPost('country');
                $arrData = $this->getStateSelectData($idCountry);
                $Form->get('state')->setValueOptions($arrData);
                //================ For populate City during save
                $arrData = array();
                $idState = $Request->getPost('state');
                $arrData = $this->getCitySelectData($idState);
                $Form->get('city')->setValueOptions($arrData);
                //*****/
                //================ For City-Object
                $idCity = $Request->getPost('city');
                $City = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\City')->find($idCity);
                //*****/
                $BranchFilter = new BranchFilter();
                $Form->setInputFilter($BranchFilter->getInputFilter());
                $Form->setData($Request->getPost());
                if ($Form->isValid())
                {
                    $Branch->setCompany($Company);
                    $Branch->exchangeArray($Form->getData());
                    $this->getServiceLocator()->get('EntityManager')->persist($Branch);
                    //*****Set BioInfo Data
                    $BioInfo->exchangeArray($Form->getData());
                    $BioInfo->setBranch($Branch);
                    $BioInfo->setCity($City);
                    $this->getServiceLocator()->get('EntityManager')->persist($BioInfo);
                    $this->getServiceLocator()->get('EntityManager')->flush();
                    //==============After udpation Redirect to Index
                    return $this->redirect()->toRoute('branch', array(
                                'action' => 'index'
                    ));
                    //=================
                }
            }
        }
        else
        {
            return $this->redirect()->toRoute('branch', array(
                        'action' => 'add'
            ));
        }
        //************
        $ViewModel->setVariable('Form', $Form);
        $ViewModel->setVariable('id', $id);
        return $ViewModel;
    }

    /**
     * getCountrySelectData() used for data collection of Country from database for selectbox
     * @return type array
     */
    private function getCompanySelectData()
    {
        $arrSelectData = array();
        $Companies = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Company')->findAll();
        foreach ($Companies as $Company)
        {
            $arrSelectData[$Company->getId()] = $Company->getName();
        }
        return $arrSelectData;
//        var_dump($arrSelectData);exit;
    }

    /**
     * getCountrySelectData() used for data collection of Country from database for selectbox
     * @return type array
     */
    private function getCountrySelectData()
    {
        $arrSelectData = array();
        $Countries = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Country')->findAll();
        foreach ($Countries as $Country)
        {
            $arrSelectData[$Country->getId()] = $Country->getName();
        }
        return $arrSelectData;
    }

    /**
     * getStateSelectData() used for data collection of State of related country from database for selectbox
     * @return type array
     */
    private function getStateSelectData($idCountry)
    {
        $arrSelectData = array();
        $States = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\State')->findBy(array('Country' => $idCountry));
        foreach ($States as $State)
        {
            $arrSelectData[$State->getId()] = $State->getName();
        }
        return $arrSelectData;
    }

    /**
     * getCitySelectData() used for data collection of City of related State from database for selectbox
     * @return type array
     */
    private function getCitySelectData($idState)
    {
        $arrSelectData = array();
        $Cities = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\City')->findBy(array('State' => $idState));
        foreach ($Cities as $City)
        {
            $arrSelectData[$City->getId()] = $City->getName();
        }
        return $arrSelectData;
    }

    public function populateStateAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $arrData = array();
        $States = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\State')->findBy(array('Country' => $id));
        foreach ($States as $State)
        {
            $arrData[$State->getId()] = $State->getName();
        }
        if (count($arrData) > 0)
            echo json_encode($arrData);
        else
            echo '';

        exit;
    }

    public function populateCityAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $arrData = array();
        $Cities = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\City')->findBy(array('State' => $id));
        foreach ($Cities as $City)
        {
            $arrData[$City->getId()] = $City->getName();
        }
        if (count($arrData) > 0)
            echo json_encode($arrData);
        else
            echo '';

        exit;
    }

}

?>
