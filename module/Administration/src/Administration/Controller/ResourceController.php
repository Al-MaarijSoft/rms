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
    Administration\Form\ResourceForm,
    Administration\Entity\Resource,
    DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter,
    Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator,
    Zend\Paginator\Paginator;

class ResourceController extends AbstractActionController
{

    public $errorMsgs;
    private $codeSaparator = '-';

    public function indexAction()
    {
        $ViewModel = new ViewModel();
        $Resources = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Resource')->findBy(array(), array('code' => 'asc'));
   
        $vars = array(
            'Resources' => $Resources,
        );
        $ViewModel->setVariables($vars);
        return $ViewModel;
    }

    public function addAction()
    {
        $arrSelectData = null;
        $ViewModel = new ViewModel();
//        $arrSelectData['Company'] = $this->getCompanySelectData();
        $id = (int) $this->params()->fromRoute('id', null);
        if (null === $id)
        {
            return $this->redirect()->toRoute('resource', array(
                        'action' => 'index'
            ));
        }
        $arrSelectData = $this->fetchParentResources();
        $Form = new ResourceForm($arrSelectData);
        $Request = $this->getRequest();
        if ($Request->isPost())
        {
            //==========================Post Values
            $parentId = $Request->getPost('parent');
            $name = $Request->getPost('name');
            /* End */

            $Resource = new Resource();
            if ($parentId == '')
            {
                $Filter = $Form->getInputFilter();
                $parentElement = $Filter->get('parent');
                $parentElement->setRequired(false);
            }
            else
            {
                $Parent = $this->fetchParent($parentId);
                $Resource->setParent($Parent);
            }
            $Form->setData($Request->getPost());
            if ($Form->isValid())
            {
                $ResoucreRepo = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Resource');
                $params = array(
                    'editId' => null,
                    'fields' => array('Parent' => $parentId, 'name' => $name),
                    'context' => $this,
                    'errorArrayKey' => 'resource',
                    'fieldLabel' => 'Resource',
                    'Repository' => $ResoucreRepo,
                );
                $uniqueNameWithParent = $this->GlobalResourcePlugin()->isUniqueObject($params);
//                $params = array(
//                    'editId' => null,
//                    'fields' => array('name' => $name),
//                    'context' => $this,
//                    'errorArrayKey' => 'resource',
//                    'fieldLabel' => 'Resource',
//                    'Repository' => $ResoucreRepo,
//                );
//                $uniqueName = $this->GlobalResourcePlugin()->isUniqueObject($params);

                if ($uniqueNameWithParent)
                {
//                    $code = $this->generateCode($parentId);
                    $code = $Request->getPost('code');
                    if (!$parentId && $code == '')
                    {
                        $code = $this->generateCodeIfNoParentExists();
                    }
                    else
                    {
                        $code = $this->generateCode($parentId);
                    }
                    $level = $this->makeResourceLevelValue($code);
                    $serial = $this->makeResourceSerialValue($parentId);
                    $data = array('name' => $name,
                        'code' => $code,
                        'level' => $level,
                        'serial' => $serial,
                    );
                    $Resource->exchangeArray($data);
                    $this->getServiceLocator()->get('EntityManager')->persist($Resource);
                }
            }
//            }
            $this->getServiceLocator()->get('EntityManager')->flush();
            return $this->redirect()->toRoute('resource', array(
                        'action' => 'index'
            ));
        }
        //=================
        $ViewModel->setVariable('Form', $Form);
        $ViewModel->setVariable('errorMsgs', $this->errorMsgs);
        return $ViewModel;
    }

    public function editAction()
    {
        $arrSelectData = null;
        $ViewModel = new ViewModel();
//        $arrSelectData['Company'] = $this->getCompanySelectData();
        $id = (int) $this->params()->fromRoute('id', null);
        if (null === $id)
        {
            return $this->redirect()->toRoute('resource', array(
                        'action' => 'index'
            ));
        }

        $arrSelectData = $this->fetchParentResources();
        $Form = new ResourceForm($arrSelectData);
        $Request = $this->getRequest();
        if ($id)
            $Resource = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Resource')->find($id);
        else
        {
            $id = $Request->getPost('id');
            $Resource = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Resource')->find($id);
        }
        $Form->bind($Resource);
        if ($Request->isPost())
        {
            //==========================Post Values
            $parentId = $Request->getPost('parent');
            $name = $Request->getPost('name');
            /* End */
            if ($parentId == '')
            {
                $Filter = $Form->getInputFilter();
                $parentElement = $Filter->get('parent');
                $parentElement->setRequired(false);
            }
            else
            {
                $Parent = $this->fetchParent($parentId);
                $Resource->setParent($Parent);
            }
            $Form->setData($Request->getPost());
            if ($Form->isValid())
            {
                $ResoucreRepo = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Resource');
//                $params = array(
//                    'editId' => null,
//                    'fields' => array('Parent' => $parentId, 'name' => $name),
//                    'context' => $this,
//                    'errorArrayKey' => 'resource',
//                    'fieldLabel' => 'Resource',
//                    'Repository' => $ResoucreRepo,
//                );
//                $uniqueName = $this->GlobalResourcePlugin()->isUniqueObject($params);
//
//                if ($uniqueName)
//                {
                $params = array(
                    'editId' => null,
                    'fields' => array('Parent' => $parentId, 'name' => $name),
                    'context' => $this,
                    'errorArrayKey' => 'resource',
                    'fieldLabel' => 'Resource',
                    'Repository' => $ResoucreRepo,
                );
                $uniqueNameWithParent = $this->GlobalResourcePlugin()->isUniqueObject($params);
                $params = array(
                    'editId' => null,
                    'fields' => array('name' => $name),
                    'context' => $this,
                    'errorArrayKey' => 'resource',
                    'fieldLabel' => 'Resource',
                    'Repository' => $ResoucreRepo,
                );
                $uniqueName = $this->GlobalResourcePlugin()->isUniqueObject($params);

                if ($uniqueNameWithParent && $uniqueName)
                {
//                    
//                    $code = $this->generateCode($parentId);
//                    $level = $this->makeResourceLevelValue($code);
//                    $data = array('name' => $name,
//                        'code' => $code,
//                        'level' => $level,
//                    );
                    $code = $Request->getPost('code');
                    if (!$parentId && $code == '')
                    {
                        $code = $this->generateCodeIfNoParentExists();
                    }
                    else
                    {
                        $code = $this->generateCode($parentId);
                    }
                    $level = $this->makeResourceLevelValue($code);
                    $serial = $this->makeResourceSerialValue($parentId);
                    $data = array('name' => $name,
                        'code' => $code,
                        'level' => $level,
                        'serial' => $serial,
                    );
                    $Resource->exchangeArray($data);
                    $this->getServiceLocator()->get('EntityManager')->persist($Resource);
                }
            }
//            }
            $this->getServiceLocator()->get('EntityManager')->flush();
            return $this->redirect()->toRoute('resource', array(
                        'action' => 'index'
            ));
        }
        //=================
        $ViewModel->setVariable('Form', $Form);
        $ViewModel->setVariable('errorMsgs', $this->errorMsgs);
        return $ViewModel;
    }

    private function isValid(\Zend\Http\Request $Request)
    {
        $isValid = true;
        $idAction = $Request->getPost('action');
        $controller = $Request->getPost('controller');
        if (isset($controller) && $controller == '')
        {
            $this->errorMsgs['controller'] = 'Please enter Controller';
            $isValid = false;
        }
        foreach ($idAction as $index => $idVal)
        {
            if ($idVal == '')
            {
                $this->errorMsgs['action'][$index] = 'Please select Action at row number ' . $index;
                $isValid = false;
            }
        }
        return $isValid;
    }

    private function fetchParentResources()
    {
        $arrSelectData = array();
        $qb = $this->getServiceLocator()->get('EntityManager')->createQueryBuilder();
        //*****Make Code on Serial Max

        $qb->select('v')
                ->from('Administration\Entity\Resource', 'v');
//        $qb->where('v.level is null');
        $qb->where($qb->expr()->not($qb->expr()->eq('v.level', ':identifier')));
        $qb->setParameter('identifier', 3);
        $resourcesQry = $qb->getQuery();
        $ParentResources = $resourcesQry->getResult();

        if (count($ParentResources) > 0)
        {
            foreach ($ParentResources as $ParentResource)
            {
                $arrSelectData['Name'][$ParentResource->getId()] = $ParentResource->getName();
            }
        }
        return $arrSelectData;
    }

    private function fetchParent($id)
    {
        $Parent = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Resource')->findOneBy(array('id' => $id));

        return $Parent;
    }

    private function generateCode($parentId)
    {
        $code = 0;
        $codeLeftPadLength = 2;
        $parentCode = '';
        $codeGenerated = null;
        $finalCode = '';
        $qb = $this->getServiceLocator()->get('EntityManager')->createQueryBuilder();
//        $idParentAccount = (int) $parentId;
        $Resource = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Resource')->find($parentId);
//        $accountClass = $Account->getClass();
//        switch ($category)
//        {
//            case Account::CONTROL:
//                $codeLeftPadLength = 3;
//                break;
//            case Account::DETAILED:
//                $codeLeftPadLength = 4;
//                break;
//        }
        //*****Make Code on Serial Max
        $qb->select('MAX(a.code) AS max_code_no')
                ->from('Administration\Entity\Resource', 'a')
                ->where('a.Parent= :parentId')
                ->setParameters(array(
                    'parentId' => $parentId,
        ));
        $result = $qb->getQuery()->getOneOrNullResult();
        if ($result['max_code_no'] !== null)
            $code = (int) $result['max_code_no'] + 1;
        else
            $code = 1;

        $codeGenerated = str_pad($code, $codeLeftPadLength, '0', STR_PAD_LEFT);
        //***End
        //*****Get ParentAccount-Code
        $qb = $this->getServiceLocator()->get('EntityManager')->createQueryBuilder();
        $ParentCode = '';
        if ($parentId)
        {
            $ParentResources = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Resource')->findOneBy(array("id" => $parentId));
            if (count($ParentResources) > 0)
            {
                $parentCode = $ParentResources->getCode();
            }
        }

        if ($parentCode != null && $parentCode != '')
            $finalCode = $parentCode . '-' . $codeGenerated;
        else
        {
            $codeGenerated = str_pad($code, 2, '0', STR_PAD_LEFT);
            $finalCode = $codeGenerated;
        }
        return $finalCode;
    }

    private function makeResourceLevelValue($code)
    {
        $level = 0;
        $arr = explode($this->codeSaparator, $code);
        if ($arr)
            $level = count($arr);
//            $level = count($arr) - 1;

        return $level;
    }

    private function generateCodeIfNoParentExists()
    {
        $qb = $this->getServiceLocator()->get('EntityManager')->createQueryBuilder();
        $qb->select('MAX(a.code) AS max_code_no')
                ->from('Administration\Entity\Resource', 'a');
        $result = $qb->getQuery()->getOneOrNullResult();
        if ($result['max_code_no'] !== null)
            $code = (int) $result['max_code_no'] + 1;
        else
            $code = 1;
        $code = '0' . $code;
        return $code;
    }

    public function generateResourceCodeAction()
    {
        $code = 0;
        $codeLeftPadLength = 9;
        $parentCode = '';
        $codeGenerated = null;
        $finalCode = '';
        $qb = $this->getServiceLocator()->get('EntityManager')->createQueryBuilder();
        $idParentResource = (int) $this->params()->fromRoute('id', 0);
        $Resource = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Resource')->find($idParentResource);

        if ($Resource->getLevel() == 1 || $Resource->getLevel() == 2)
        {
            $codeLeftPadLength = 2;
        }
        else if ($Resource->getLevel() == 3)
        {
            $codeLeftPadLength = 3;
        }
        //*****Make Code on Serial Max
        $qb->select('MAX(a.serial) AS max_code_no')
                ->from('Administration\Entity\Resource', 'a')
                ->where('a.Parent= :parentId')
                ->setParameters(array(
                    'parentId' => $idParentResource,
        ));
        $result = $qb->getQuery()->getOneOrNullResult();
        if ($result['max_code_no'] !== null)
            $code = (int) $result['max_code_no'] + 1;
        else
            $code = 1;
        $codeGenerated = str_pad($code, $codeLeftPadLength, '0', STR_PAD_LEFT);
        //***End
        //*****Get ParentAccount-Code
        $qb = $this->getServiceLocator()->get('EntityManager')->createQueryBuilder();
        $qb->select('a.code')
                ->from('Administration\Entity\Resource', 'a')
                ->where('a.id = :identifier')
                ->setParameters(array(
                    'identifier' => $idParentResource,
        ));
        $result = $qb->getQuery()->getOneOrNullResult();
        if ($result['code'] !== null)
            $parentCode = $result['code'];
        //***End
        $finalCode = $parentCode . $this->codeSaparator . $codeGenerated;
        echo json_encode($finalCode);
        exit;
    }

    private function makeResourceSerialValue($idParent = null)
    {
        $serial = 0;
        if ($idParent)
        {
            $queryBuilder = $this->getServiceLocator()->get('EntityManager')->createQueryBuilder();
            $result = $queryBuilder->select('MAX(a.serial) max_serial')
                            ->from('Administration\Entity\Resource', 'a')
                            ->where('a.Parent = :ParentId')
                            ->setParameter('ParentId', $idParent)
                            ->getQuery()->getOneOrNullResult();
            if ($result['max_serial'])
                $serial = $result['max_serial'] + 1;
            else
                $serial = $serial + 1;
        }
        return $serial;
    }

}

?>
