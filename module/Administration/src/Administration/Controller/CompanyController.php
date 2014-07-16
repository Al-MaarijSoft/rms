<?php

namespace Administration\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Administration\Entity\Company,
    Administration\Filter\CompanyFilter,
    Administration\Entity\BioInfo,
    Administration\Form\CompanyForm,
    Zend\View\Model\ViewModel,
    DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter,
    Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator,
    Zend\Paginator\Paginator;

class CompanyController extends AbstractActionController
{

    public function __construct()
    {
        
    }

    public function indexAction()
    {
        $View = new ViewModel();
        $Repo = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Company');
        $ORMPaginator = new ORMPaginator($Repo->createQueryBuilder('companies'));
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
        $arrSelectData['Country'] = $this->getCountrySelectData();
        $Form = new CompanyForm($arrSelectData);
        $Form->get('submit')->setValue('Add');
//        $this->save($Form);
        //*********** Code For Insertion
        $Request = $this->getRequest();
        if ($Request->isPost())
        {
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
            $Company = new Company();
            $BioInfo = new BioInfo();
            $CompanyFilter = new CompanyFilter();
            $Form->setInputFilter($CompanyFilter->getInputFilter());
            $Form->setData($Request->getPost());
            if ($Form->isValid())
            {
                $Company->exchangeArray($Form->getData());
                $this->getServiceLocator()->get('EntityManager')->persist($Company);
                //*****Set BioInfo Data
                $BioInfo->exchangeArray($Form->getData());
                $BioInfo->setCompany($Company);
                $BioInfo->setCity($City);
                $this->getServiceLocator()->get('EntityManager')->persist($BioInfo);
                $this->getServiceLocator()->get('EntityManager')->flush();
                //==============New Instance Of Form
                $Form = null;
                $Form = new CompanyForm($arrSelectData);
                //=================
            }
        }
        $ViewModel->setVariable('Form', $Form);
        return $ViewModel;
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

    public function editAction()
    {
        $arrData = null;
        $ViewModel = new ViewModel();
        $id = (int) $this->params()->fromRoute('id', null);
        if (null === $id)
        {
            return $this->redirect()->toRoute('company', array(
                        'action' => 'add'
            ));
        }

        $arrSelectData['Country'] = $this->getCountrySelectData();
        $Form = new CompanyForm($arrSelectData);
        $Company = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Company')->find($id);



        if (count($Company))
        {
            $BioInfos = $Company->getBioInfos();
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
            $Form->bind($Company);
//            $Form->bind($BioInfo);
            //**** Start Updation
            $Request = $this->getRequest();
            if ($Request->isPost())
            {
                $CompanyFilter = new CompanyFilter();
                $Form->setInputFilter($CompanyFilter->getInputFilter());
                $Form->setData($Request->getPost());
                if ($Form->isValid())
                {
//                $Company->exchangeArray($Form->getData());
                    $this->getServiceLocator()->get('EntityManager')->persist($Company);
                    $data['id'] = $Form->getInputFilter()->getValue('id');
                    $data['name'] = $Form->getInputFilter()->getValue('name');
                    $data['zip_code'] = $Form->getInputFilter()->getValue('zip_code');
                    $data['address'] = $Form->getInputFilter()->getValue('address');
                    $data['email'] = $Form->getInputFilter()->getValue('email');
                    $data['cell'] = $Form->getInputFilter()->getValue('cell');
                    $data['phone1'] = $Form->getInputFilter()->getValue('phone1');
                    $data['phone2'] = $Form->getInputFilter()->getValue('phone2');
                    $data['fax'] = $Form->getInputFilter()->getValue('fax');
                    $BioInfo->exchangeArray($data);
                    $BioInfo->setCompany($Company);
                    $BioInfo->setCity($City);
                    $this->getServiceLocator()->get('EntityManager')->persist($BioInfo);
                    $this->getServiceLocator()->get('EntityManager')->flush();
                }
            }
        }
        else
        {
            return $this->redirect()->toRoute('company', array(
                        'action' => 'add'
            ));
        }
        $viewVars = array(
            'Form' => $Form,
            'id' => $id
        );
        $ViewModel->setVariables($viewVars);
        return $ViewModel;
    }

    public function deleteAction()
    {
        
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
