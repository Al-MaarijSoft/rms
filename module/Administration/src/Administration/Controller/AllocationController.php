<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Administration\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Administration\Entity\ResourceToRole,
    DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter,
    Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator,
    Zend\Paginator\Paginator;

class AllocationController extends AbstractActionController
{

    /**
     * Return list of resources
     *
     * @return array
     */
    public function indexAction()
    {
//        $View = new ViewModel();
//        $Roles = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Role')->findBy(array(), array('code' => 'asc'));
//        $selectRoleData = $this->fetchRoles();
//        $View->setVariable('Roles', $Roles);
//        $View->setVariable('selectRoleData', $selectRoleData);
//        return $View;
        $View = new ViewModel();
        $RolesRepo = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Role');
        $ORMPaginator = new ORMPaginator($RolesRepo->createQueryBuilder('roles'));
        $Adapter = new DoctrineAdapter($ORMPaginator);
        $Paginator = new Paginator($Adapter);
        $Paginator->setDefaultItemCountPerPage(5);
        $page = (int) $this->params()->fromQuery('page', 1);
        $Paginator->setCurrentPageNumber($page);
        $View->setVariable('Paginator', $Paginator);
        return $View;
    }

    public function allocateAction()
    {
        $ViewModel = new ViewModel();
        $View = new ViewModel();
        $selectRoleData = array();
        $Allocations = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Resource')->findBy(array(), array('code' => 'asc'));
        $Resources = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Resource')->findAll();
        $Request = $this->getRequest();
        $selectRoleData = $this->fetchRoles();

        if ($Request->isPost())
        {
            $arrStatusId = $Request->getPost('status');
            $roleId = $Request->getPost('role');
            $Role = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Role')->find($roleId);
            if ($roleId == '' || $roleId === null)
            {
                return $this->redirect()->toRoute('allocation', array(
                            'action' => 'allocate'
                ));
            }

            $Allocations = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\ResourceToRole')->findBy(array('Role' => $roleId));
            if (count($Allocations))
            {
                return $this->redirect()->toRoute('allocation', array(
                            'action' => 'index'
                ));
            }
            if (count($arrStatusId) > 0)
            {
                foreach ($Resources as $Resource)
                {

//                        $Resource = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Resource')->find($statusId);
//                        $ParentResource = $Resource->getParent();

                    if (count($Resource) > 0)
                    {
                        $Allocation = new ResourceToRole();
                        //=====================End======================
                        $Allocation->setResource($Resource);
                        $Allocation->setRole($Role);
                        ////////////////For Temporary Part 
                        foreach ($arrStatusId as $statusId)
                        {
                            if ($Resource->getId() == $statusId)
                            {
                                $status = 1;
                                break;
                            }
                            else
                            {
                                $status = 0;
                            }
                        }
                        ////////////////////////////////////////////
                        $data = array('status' => $status,);
                        $Allocation->exchangeArray($data);
                        $this->getServiceLocator()->get('EntityManager')->persist($Allocation);
                    }
                }
                $this->getServiceLocator()->get('EntityManager')->flush();
                return $this->redirect()->toRoute('allocation');
            }
        }
        $vars = array(
            'Allocations' => $Allocations,
            'selectRoleData' => $selectRoleData,
        );
        $ViewModel->setVariables($vars);
        return $ViewModel;
    }

    public function editAction()
    {
        $ViewModel = new ViewModel();
        $selectRoleData = array();
        $roleId = (int) $this->params()->fromRoute('id', null);
        if (null === $roleId)
        {
            return $this->redirect()->toRoute('allocation', array(
                        'action' => 'index'
            ));
        }
        $selectRoleData = $this->fetchRoles();
        $Roles = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Role')->findOneBy(array('id' => $roleId, 'Company' => \Administration\Entity\Company::DEFAULTCOMPANY));
        //***************************/Post Data
        $Request = $this->getRequest();
        $Role = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Role')->find($roleId);
        if ($Request->isPost())
        {
            $arrStatusId = $Request->getPost('status');

            $Allocations = $Role->getResourceToRole();
            foreach ($Allocations as $Allocation)
            {
                $status = 0;
                foreach ($arrStatusId as $id)
                {
                    if ($Allocation->getResource()->getId() == $id)
                    {
                        $status = 1;
                        break;
                    }
                }
                $data = array('status' => $status,);
                $Allocation->exchangeArray($data);
                $this->getServiceLocator()->get('EntityManager')->persist($Allocation);
            }
            $this->getServiceLocator()->get('EntityManager')->flush();
            return $this->redirect()->toRoute('allocation');
        }
        $vars = array(
            'Roles' => $Roles,
            'selectRoleData' => $selectRoleData,
        );
        $ViewModel->setVariables($vars);
        return $ViewModel;
    }

    private function fetchAllocations($id)
    {
        $Allocations = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\ResourceToRole')->findBy(array('Controller' => $id));
    }

    private function fetchRoles()
    {
        $selectRoleData = array();
        $Roles = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Role')->findAll();
        if (count($Roles) > 0)
        {
            foreach ($Roles as $Role)
            {
                if (!count($Role->getResourceToRole()))
                {
                    $selectRoleData[$Role->getId()] = $Role->getName();
                }
            }
        }
        return $selectRoleData;
    }

    public Function retrieveAllocatedResourcesAction()
    {
        $resourceArray = array();
        $Request = $this->getRequest();
        $roleId = $Request->getPost('roleId');
        $count = 0;
        if ($roleId != '')
        {

            $Allocations = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\ResourceToRole')->findBy(array('Role' => $roleId));
            foreach ($Allocations as $Allocation)
            {
                $count++;
                array_push($resourceArray, $Allocation->getResource()->getId());
            }
        }
        echo json_encode($resourceArray);
        exit;
    }

    public Function retrieveIfRolesExistAction()
    {
        $rolesArray = array();
        $Request = $this->getRequest();
        $roleId = $Request->getPost('roleId');
        $count = 0;
        if ($roleId != '')
        {

            $Allocations = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\ResourceToRole')->findBy(array('Role' => $roleId));
            if (count($Allocations))
                $count = 1;
        }
        echo $count;
        exit;
    }

}

?>
