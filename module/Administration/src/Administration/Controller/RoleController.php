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
    Administration\Form\RoleForm,
    Administration\Filter\RoleFilter,
    Administration\Entity\Role,
    DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter,
    Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator,
    Zend\Paginator\Paginator;

class RoleController extends AbstractActionController
{

    private $errorMsgs;

    public function indexAction()
    {
        $View = new ViewModel();
        $Repo = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Role');
        $ORMPaginator = new ORMPaginator($Repo->createQueryBuilder('roles'));
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
//        $arrSelectData['Company'] = $this->getCompanySelectData();

        $Form = new RoleForm();
        $Form->get('submit')->setValue('Add');
        $Request = $this->getRequest();
        //==========================Post Values
        $companyId = $Request->getPost('company');
        $name = $Request->getPost('name');
        /* End */
        $Form->setData($Request->getPost());
        if ($Request->isPost())
        {
            $Role = new Role();
            $RoleFilter = new RoleFilter();
            $Form->setInputFilter($RoleFilter->getInputFilter());
            //================ For populate Categories during save
            $Company = $this->fetchCompany(\Administration\Entity\Company::DEFAULTCOMPANY);
            /* END */
            if ($Form->isValid())
            {
                $uniqueName = $this->isUniqueObject(array('name' => $name, 'Company' => \Administration\Entity\Company::DEFAULTCOMPANY), 'name', 'Role Name');
                if ($uniqueName)
                {
                    $Role->setCompany($Company);
                    $Role->exchangeArray($Form->getData());
                    $this->getServiceLocator()->get('EntityManager')->persist($Role);
                    $this->getServiceLocator()->get('EntityManager')->flush();
                    return $this->redirect()->toRoute('role', array(
                                'action' => 'index'
                    ));
                }
            }
        }

        //=================
        $ViewModel->setVariable('errorMsgs', $this->errorMsgs);
        $ViewModel->setVariable('Form', $Form);
        return $ViewModel;
    }

    public function editAction()
    {
        $ViewModel = new ViewModel();
        $id = (int) $this->params()->fromRoute('id', null);
        $Request = $this->getRequest();
        $name = $Request->getPost('name');
        $Form = new RoleForm();
        if (null === $id)
        {
            return $this->redirect()->toRoute('category', array(
                        'action' => 'edit'
            ));
        }
        if ($id)
            $Role = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Role')->find($id);
        else
            $Role = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Role')->find($Request->getPost('id'));
        $Form->bind($Role);
        if ($Request->isPost())
        {

            $RoleFilter = new RoleFilter();
            $Form->setInputFilter($RoleFilter->getInputFilter());
            $Form->setData($Request->getPost());
            if ($Form->isValid())
            {
                $uniqueName = $this->isUniqueObject(array('name' => $name, 'Company' => \Administration\Entity\Company::DEFAULTCOMPANY), 'name', 'Role Name');
                if ($uniqueName)
                {
                    $this->getServiceLocator()->get('EntityManager')->persist($Role);
                    $this->getServiceLocator()->get('EntityManager')->flush();
                    return $this->redirect()->toRoute('role', array('action' => 'index'));
                }
            }
        }
        $ViewModel->setVariable('Form', $Form);
        $ViewModel->setVariable('errorMsgs', $this->errorMsgs);
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

    private function fetchCompany($id)
    {
        $Company = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Company')->find($id);
        return $Company;
    }

    private function isUniqueObject(array $findBy = null, $field = '', $fieldLabel = '')
    {
        $Obj = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Role')->findBy($findBy);
        if (count($Obj))
        {
            $this->errorMsgs[$field] = $fieldLabel . ' already exists';
            return false;
        }
        else
            return true;
    }

}

?>
