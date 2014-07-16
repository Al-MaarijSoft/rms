<?php

/**
 * Description of UserController
 *
 * @author Muhammad Rashid
 */

namespace Administration\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Administration\Entity\User,
    Administration\Entity\BioInfo,
    Administration\Form\UserForm,
    Administration\Form\UserSearchForm,
    \Administration\Filter\UserFilter,
    DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter,
    Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator,
    Zend\Paginator\Paginator;

class UserController extends AbstractActionController
{

    public function __construct()
    {
        
    }

    public function indexAction()
    {
        $Request = $this->getRequest();
        $ViewModel = new ViewModel();
        if ($Request->isXmlHttpRequest())
        {
            $ViewModel->setTerminal(true);
        }
        $arrListData['role'] = $this->fetchRoles();
        $Form = new UserSearchForm($arrListData);
        $Repository = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\User');
        $QueryBuilder = $Repository->createQueryBuilder('U');
        //==============================START SEARCH
        if ($Request->isPost() && $Request->getPost('submit') === 'Search')
        {
            $Form->get('role')->setAttributes(array('value', $Request->getPost('role'), 'selected' => true));
            $Form->get('username')->setAttribute('value', $Request->getPost('username'));
            $Form->get('name')->setAttribute('value', $Request->getPost('name'));
            $QueryBuilder = $this->searchResults($Request, $QueryBuilder);
        }
        else
        {
            $QueryBuilder->select('U')
                    ->addOrderBy('U.username', 'ASC')
                    ->addOrderBy('U.name', 'ASC');
        }
        //==============================END SEARCH
        $ORMPaginator = new ORMPaginator($QueryBuilder);
        $Adapter = new DoctrineAdapter($ORMPaginator);
        $Paginator = new Paginator($Adapter);
        $Paginator->setDefaultItemCountPerPage(50);
        $page = (int) $this->params()->fromQuery('page', 1);
        $Paginator->setCurrentPageNumber($page);
        $ViewModel->setVariable('Paginator', $Paginator);
        $ViewModel->setVariable('Form', $Form);

        return $ViewModel;
    }

    public function addAction()
    {
        $arrSelectData = null;
        $Request = $this->getRequest();
        $ViewModel = new ViewModel();
        if ($Request->isXmlHttpRequest())
        {
            $ViewModel->setTerminal(true);
        }
        $arrSelectData['Country'] = $this->getCountrySelectData();
        $arrSelectData['roles'] = $this->fetchRoles();
        $arrSelectData['financialYears'] = $this->fetchFinancialYears();
        $Form = new UserForm($arrSelectData);
        $Form->get('submit')->setValue('Save');
//        $this->save($Form);
        //*********** Code For Insertion

        if ($Request->isPost())
        {
            //================ For Company-Object
            $idCompany = \Administration\Entity\Company::DEFAULTCOMPANY;
            $Company = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Company')->find($idCompany);
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
            $User = new User();
            $BioInfo = new BioInfo();
            $UserFilter = new UserFilter();
            $Form->setInputFilter($UserFilter->getInputFilter());
            $Form->setData($Request->getPost());
            if ($Form->isValid())
            {
                $User->exchangeArray($Form->getData());
                $User->setCompany($Company);
                $idRole = $Request->getPost('role');
                $Role = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Role')->find($idRole);
                $User->setRole($Role);
                $idFinancialYears = $Request->getPost('financial_years');
                $FinancialYears = $this->getServiceLocator()->get('EntityManager')->getRepository('\Administration\Entity\FinancialYear')->findBy(array('id' => $idFinancialYears));
                $User->addFinancialYears($FinancialYears);
                $this->getServiceLocator()->get('EntityManager')->persist($User);
                //*****Set BioInfo Data
                $BioInfo->exchangeArray($Form->getData());
                $BioInfo->setUser($User);
                $BioInfo->setCity($City);
                $this->getServiceLocator()->get('EntityManager')->persist($BioInfo);
                $this->getServiceLocator()->get('EntityManager')->flush();

                //==============New Instance Of Form
                $Form = null;
                $Form = new UserForm($arrSelectData);
                //=================
            }
        }
        $ViewModel->setVariable('Form', $Form);
        return $ViewModel;
    }

    public function editAction()
    {
        $FinancialYearIds = array();
        $Request = $this->getRequest();
        $ViewModel = new ViewModel();
        if ($Request->isXmlHttpRequest())
        {
            $ViewModel->setTerminal(true);
        }
        $id = (int) $this->params()->fromRoute('id', null);
        if (null === $id)
        {
            return $this->redirect()->toRoute('user', array(
                        'action' => 'index'
            ));
        }
        $arrSelectData['Country'] = $this->getCountrySelectData();
        $arrSelectData['roles'] = $this->fetchRoles();
        $arrSelectData['financialYears'] = $this->fetchFinancialYears();
        $Form = new UserForm($arrSelectData);
        $User = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\User')->find($id);
        $UserFilter = new UserFilter();
        $Form->get('submit')->setValue('Save');
//        $Form->bind($User);
        if (count($User))
        {
            $BioInfos = $User->getBioInfos();
            $idCompany = $User->getCompany()->getId();
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
            $FinancialYearsOld = $User->getFinancialYears();
            foreach ($FinancialYearsOld as $FinancialYear)
            {
                $FinancialYearIds[] = $FinancialYear->getid();
            }
            $Form->bind($BioInfo);
            $Form->bind($User);
            $Form->get('financial_years')->setAttributes(array('value' => $FinancialYearIds, 'selected' => true));
            //**** Start Updation
            $Request = $this->getRequest();
            if ($Request->isPost())
            {
                //================ For Company-Object
                $idCompany = \Administration\Entity\Company::DEFAULTCOMPANY;
                $Company = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Company')->find($idCompany);
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
//                $User = new User();
//                $BioInfo = new BioInfo();
                $UserFilter = new UserFilter();
                $Form->setInputFilter($UserFilter->getInputFilter());
                $Form->setData($Request->getPost());
                if ($Form->isValid())
                {
//                    $User->exchangeArray($Form->getData());
                    $User->setCompany($Company);
                    $idRole = $Request->getPost('role');
                    $Role = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Role')->find($idRole);
                    $User->setRole($Role);
//                    $idFinancialYears = $Request->getPost('financial_years');
//                    $FinancialYears = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\FinancialYear')->find($idFinancialYears);
//                    $User->setFinancialYears($FinancialYears);
                    $idFinancialYears = $Request->getPost('financial_years');
                    $FinancialYearsNew = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\FinancialYear')->findBy(array('id' => $idFinancialYears));
                    $User->updateFinancialYears($FinancialYearsOld, $FinancialYearsNew);
                    $this->getServiceLocator()->get('EntityManager')->persist($User);
                    //*****Set BioInfo Data
//                    $BioInfo->exchangeArray($Form->getData());
                    $BioInfo->setUser($User);
                    $BioInfo->setCity($City);
                    $this->getServiceLocator()->get('EntityManager')->persist($BioInfo);
                    $this->getServiceLocator()->get('EntityManager')->flush();

                    //==============New Instance Of Form
                    $Form = null;
                    $Form = new UserForm($arrSelectData);
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

    private function fetchRoles()
    {
//        $Roles = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Role')->findBy(array('name'=>''));
        $Repository = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Role');
        $QueryBuilder = $Repository->createQueryBuilder('R');
        $Roles = $QueryBuilder->select('R')
                        ->where('R.name != :name')
                        ->setParameter('name', 'SuperAdministrator')->getQuery()->getResult();
//        print_r($Roles);
//        die;
        $vTypes = array();
        if (count($Roles))
        {
            foreach ($Roles as $Role)
            {
                $vTypes[$Role->getId()] = $Role->getName();
            }
        }
        return $vTypes;
    }

    private function fetchFinancialYears()
    {
        $FinancialYears = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\FinancialYear')->findAll();
        foreach ($FinancialYears as $FinancialYear)
        {
            $arrSelectData[$FinancialYear->getId()] = $FinancialYear->getName();
        }
        return $arrSelectData;
    }

    private function searchResults($Request, $QueryBuilder)
    {
        $role = $Request->getPost('role');
        $userName = $Request->getPost('username');
        $realName = $Request->getPost('name');

        if ($role != "" && $userName != "" && $realName != "")
        {
            $whereClause = "U.role = :role AND U.username = :username AND U.name= :name";
            $parameters = array('role' => $role, 'username' => $userName, 'name' => $realName);
        }
        elseif ($role != "" && $userName != "")
        {
            $whereClause = "U.role = :role AND U.username = :username";
            $parameters = array('role' => $role, 'username' => $userName);
        }
        elseif ($role != "" && $realName != "")
        {
            $whereClause = "U.role = :role AND U.name= :name";
            $parameters = array('role' => $role, 'name' => $realName);
        }
        elseif ($userName != "" && $realName != "")
        {
            $whereClause = "U.username = :username AND U.name= :name";
            $parameters = array('username' => $userName, 'name' => $realName);
        }
        elseif ($role != "")
        {
            $whereClause = "U.role = :role";
            $parameters = array('role' => $role);
        }
        elseif ($userName != "")
        {
            $whereClause = "U.username = :username";
            $parameters = array('username' => $userName);
        }
        elseif ($realName != "")
        {
            $whereClause = "U.name = :name";
            $parameters = array('name' => $realName);
        }
        if ($whereClause != '' && $parameters != '')
        {
            $QueryBuilder->select('U')
                    ->where($whereClause)
                    ->setParameters($parameters)
                    ->addOrderBy('U.name', 'ASC')
                    ->addOrderBy('U.username', 'ASC');
        }
        return $QueryBuilder;
    }

}
