<?php

namespace Account\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Doctrine\ORM\EntityManager,
    Zend\View\Model\ViewModel,
    Account\Entity\Account,
    Account\Filter\AccountFilter,
    Account\Form\AccountForm,
    Account\Form\AccountSearchForm,
    DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter,
    Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator,
    Zend\Paginator\Paginator
//    \PHPExcel_Autoloader,
//    \PHPExcel,
//    \PHPExcel_IOFactory
;
use DOMPDFModule\View\Model\PdfModel;

class AccountController extends AbstractActionController
{

    private $codeSaparator = '-';
    public $errorMsgs = array();

    public function __construct()
    {
        
    }

    public function indexAction()
    {
        $arrSelectData = null;
        $successMsg = null;
        $Request = $this->getRequest();
        $ViewModel = new ViewModel();
        if ($Request->isXmlHttpRequest())
        {
            $ViewModel->setTerminal(true);
        }
        $arrSelectData['type'] = $this->fetchTypeSelectData();
        $arrSelectData['code'] = $this->fetchAccontCodes();
        $arrSelectData['name'] = $this->fetchAccontNames();
        $arrSelectData['account'] = $this->fetchAcconts();
        $Form = new AccountSearchForm($arrSelectData);
        $Form->get('submit')->setValue('Search');
        $Accounts = $this->searchResults();
        if ($Request->isPost())
        {
            $Form->get('code')->setAttributes(array('value' => $Request->getPost('code')));
            $Form->get('name')->setAttributes(array('value' => $Request->getPost('name')));
            $Form->get('level')->setAttributes(array('value' => $Request->getPost('level')));
            $Form->get('account_type')->setAttributes(array('value' => $Request->getPost('account_type'), 'selected' => true));
            $Form->get('parent')->setAttributes(array('value' => $Request->getPost('parent'), 'selected' => true));
            $Form->get('class')->setAttributes(array('value' => $Request->getPost('class'), 'selected' => true));


            $code = $Request->getPost('code');
            $name = $Request->getPost('name');
            $level = $Request->getPost('level');
            $parent = $Request->getPost('parent');
            $type = $Request->getPost('account_type');
            $class = $Request->getPost('class');
            $Accounts = $this->searchResults($code, $name, $level, $class, $type, $parent);
        }
        $vals = array(
            'Form' => $Form,
            'Accounts' => $Accounts,
            'errorMsgs' => $this->errorMsgs,
            'successMsg' => $successMsg,
        );
        $ViewModel->setVariables($vals);
        return $ViewModel;
    }

    public function printPreviewAction()
    {
        $layout = $this->layout();
        $layout->setTemplate('print-preview-layout/print-preview-layout.phtml');
        $View = new ViewModel();
        $Accounts = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\Account')->findBy(array(), array('code' => 'asc'));
        $View->setVariable('Accounts', $Accounts);
        return $View;
    }

    public function pdfReportAction()
    {
        $Pdf = new PdfModel();
        $Accounts = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\Account')->findBy(array(), array('code' => 'asc'));
        $Pdf->setVariable('Accounts', $Accounts);
        return $Pdf;
    }

    public function excelReportAction()
    {
        $phpExcel = new PHPExcel();
        $phpExcel->getActiveSheet()->setTitle("My Sheet");
        $phpExcel->setActiveSheetIndex(0);
        $objWriter = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel2007');
    }

    public function addAction()
    {
//        $translator = $this->getServiceLocator()->get('translator');
//        $translator->setLocale('ar_SY');
        $arrSelectData = null;
        $successMsg = null;
        $Request = $this->getRequest();
        $ViewModel = new ViewModel();
        if ($Request->isXmlHttpRequest())
        {
            $ViewModel->setTerminal(true);
        }
        $arrSelectData['type'] = $this->fetchTypeSelectData();
        $arrSelectData['branch'] = $this->getBranchSelectData();
        $Form = new AccountForm($arrSelectData);
        $Form->get('submit')->setValue('Add');
        $Request = $this->getRequest();
        if ($Request->isPost())
        {
            $idCategory = (int) $Request->getPost('category');
            $idClass = $Request->getPost('class');
            $idBranch = $Request->getPost('branch');
            $idAccountType = $Request->getPost('account_type');
            $idParentAccount = $Request->getPost('parent');
            $name = $Request->getPost('name');
            $code = $Request->getPost('code');
            //================= For populate Parent Account during save 
            $arrData = $this->getParentAccountSelectData($idClass);
            $Form->get('parent')->setValueOptions($arrData);
            //*****
            $Account = new Account($this->getServiceLocator());
            $EM = $this->getServiceLocator()->get('EntityManager');
            $AccountFilter = new AccountFilter($EM);
            $Form->setInputFilter($AccountFilter->getInputFilter());
            $Filter = $Form->getInputFilter();
            switch ($idCategory)
            {
                case Account::SUPER_CONTROL:
                    $Filter->get('account_type')->setRequired(false);
                    $Filter->get('branch')->setRequired(false);
                    $Filter->get('parent')->setRequired(false);
                    break;
                case Account::CONTROL:
                    $Filter->get('account_type')->setRequired(false);
                    $Filter->get('branch')->setRequired(false);
                    break;
                case Account::DETAILED:
                    $Filter->get('account_type')->setRequired(true);
                    $Filter->get('branch')->setRequired(true);
                    $Filter->get('parent')->setRequired(true);
                    break;
                default :
                    $Filter->get('account_type')->setRequired(false);
                    $Filter->get('branch')->setRequired(false);
                    $Filter->get('parent')->setRequired(false);
                    break;
            }
            $Form->setData($Request->getPost());
            if ($Form->isValid())
            {
                $AccountRepo = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\Account');
                $params = array(
                    'editId' => null,
                    'fields' => array('name' => $name, 'Company' => \Administration\Entity\Company::DEFAULTCOMPANY),
                    'context' => $this,
                    'errorArrayKey' => 'name',
                    'fieldLabel' => 'Account Name',
                    'Repository' => $AccountRepo,
                );
                $uniqueName = $this->GlobalResourcePlugin()->isUniqueObject($params);
                $params = array(
                    'editId' => null,
                    'fields' => array('code' => $code, 'Company' => \Administration\Entity\Company::DEFAULTCOMPANY),
                    'context' => $this,
                    'errorArrayKey' => 'code',
                    'fieldLabel' => 'Account Code',
                    'Repository' => $AccountRepo,
                );
                $uniqueCode = $this->GlobalResourcePlugin()->isUniqueObject($params);
                if ($uniqueName && $uniqueCode)
                {
                    $Account->exchangeArray($Form->getData());
                    $Compnay = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Company')->find(\Administration\Entity\Company::DEFAULTCOMPANY);
                    $Account->setCompany($Compnay);
                    if ($idBranch)
                    {
                        $Branch = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Branch')->find($idBranch);
                        $Account->setBranch($Branch);
                    }
                    if ($idAccountType)
                    {
                        $AccountType = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\AccountType')->find($idAccountType);
                        $Account->setAccountType($AccountType);
                    }
                    if ($idParentAccount)
                    {
                        $ParentAccount = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\Account')->find($idParentAccount);
                        $Account->setParentAccount($ParentAccount);
                    }
                    $level = $this->makeAccountLevelValue($code);
                    $serial = $this->makeLAccountSerialValue($idParentAccount);
                    $Account->setLevel($level);
                    $Account->setSerial($serial);
                    $this->getServiceLocator()->get('EntityManager')->persist($Account);
                    $this->getServiceLocator()->get('EntityManager')->flush();
                    //==============New Instance Of Form
                    $successMsg = 'Data has been saved successfully';
                    $Form = null;
                    $Form = new AccountForm($arrSelectData);
                    //=================
                }
            }
        }
        $vals = array(
            'Form' => $Form,
            'errorMsgs' => $this->errorMsgs,
            'successMsg' => $successMsg,
        );
        $ViewModel->setVariables($vals);
        return $ViewModel;
    }

    private function searchResults()
    {
        $filterFields = array();
        $argList = func_get_args();
        if (count($argList))
        {
            $code = $argList[0];
            $name = $argList[1];
            $level = $argList[2];
            $class = $argList[3];
            $accountTypeId = $argList[4];
            $parentId = $argList[5];

            if ($code != "" && $name != "" && $level != "" && $class != '' && $accountTypeId != '' && $parentId != '')
            {
                $filterFields = array(
                    'code' => $code,
                    'name' => $name,
                    'level' => $level,
                    'class' => $class,
                    'account_type_id' => $accountTypeId,
                    'parent_id' => $parentId,
                );
            }
            elseif ($code != "" && $name != "" && $level != "" && $class != '' && $accountTypeId != '')
            {
                $filterFields = array(
                    'code' => $code,
                    'name' => $name,
                    'level' => $level,
                    'class' => $class,
                    'account_type_id' => $accountTypeId,
                );
            }
            elseif ($code != "" && $name != "" && $level != "" && $class != '' && $parentId != '')
            {
                $filterFields = array(
                    'code' => $code,
                    'name' => $name,
                    'level' => $level,
                    'class' => $class,
                    'parent_id' => $parentId,
                );
            }
            elseif ($code != "" && $name != "" && $level != "" && $class != '')
            {
                $filterFields = array(
                    'code' => $code,
                    'name' => $name,
                    'level' => $level,
                    'class' => $class,
                );
            }
            elseif ($code != "" && $name != "" && $level != "" && $accountTypeId != '')
            {
                $filterFields = array(
                    'code' => $code,
                    'name' => $name,
                    'level' => $level,
                    'account_type_id' => $accountTypeId,
                );
            }
            elseif ($code != "" && $name != "" && $level != "" && $parentId != '')
            {
                $filterFields = array(
                    'code' => $code,
                    'name' => $name,
                    'level' => $level,
                    'parent_id' => $parentId,
                );
            }
            elseif ($code != "" && $name != "" && $level != "")
            {
                $filterFields = array(
                    'code' => $code,
                    'name' => $name,
                    'level' => $level,
                );
            }
            elseif ($code != "" && $name != "" && $class != "")
            {
                $filterFields = array(
                    'code' => $code,
                    'name' => $name,
                    'class' => $class,
                );
            }
            elseif ($code != "" && $name != "" && $accountTypeId != "")
            {
                $filterFields = array(
                    'code' => $code,
                    'name' => $name,
                    'account_type_id' => $accountTypeId,
                );
            }
            elseif ($code != "" && $name != "" && $parentId != "")
            {
                $filterFields = array(
                    'code' => $code,
                    'name' => $name,
                    'parent_id' => $parentId,
                );
            }
            elseif ($code != "" && $name != "")
            {
                $filterFields = array(
                    'code' => $code,
                    'name' => $name,
                );
            }
            elseif ($code != "" && $level != "")
            {
                $filterFields = array(
                    'code' => $code,
                    'level' => $level,
                );
            }
            elseif ($code != "" && $class != "")
            {
                $filterFields = array(
                    'code' => $code,
                    'class' => $class,
                );
            }
            elseif ($code != "" && $accountTypeId != "")
            {
                $filterFields = array(
                    'code' => $code,
                    'account_type_id' => $accountTypeId,
                );
            }
            elseif ($code != "" && $parentId != "")
            {
                $filterFields = array(
                    'code' => $code,
                    'parent_id' => $parentId,
                );
            }
            elseif ($code != "")
            {
                $filterFields = array(
                    'code' => $code,
                );
            }
            elseif ($name != "")
            {
                $filterFields = array(
                    'name' => $name,
                );
            }
            elseif ($level != "")
            {
                $filterFields = array(
                    'level' => $level,
                );
            }
            elseif ($class != "")
            {
                $filterFields = array(
                    'class' => $class,
                );
            }
            elseif ($accountTypeId != "")
            {
                $filterFields = array(
                    'account_type_id' => $accountTypeId,
                );
            }
            elseif ($parentId != "")
            {
                $filterFields = array(
                    'parent_id' => $parentId,
                );
            }
        }
        $Accounts = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\Account')->findBy($filterFields, array('code' => 'asc'));
        return $Accounts;
    }

    public function editAction()
    {
        $arrSelectData = null;
        $successMsg = null;
        $arrData = null;
        $Request = $this->getRequest();
        $ViewModel = new ViewModel();
        if ($Request->isXmlHttpRequest())
        {
            $ViewModel->setTerminal(true);
        }
        $id = (int) $this->params()->fromRoute('id', null);
        if (null === $id)
        {
            return $this->redirect()->toRoute('account', array(
                        'action' => 'add'
            ));
        }
        $Account = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\Account')->find($id);
        $arrSelectData['type'] = $this->fetchTypeSelectData();
        $arrSelectData['branch'] = $this->getBranchSelectData();
        $Form = new AccountForm($arrSelectData);
        if (count($Account))
        {
            $accountCategoryBeforeEdit = $Account->getCategory();
            $accountClassBeforeEdit = $Account->getClass();
            $accountNameBeforeEdit = $Account->getName();
            $accountCodeBeforeEdit = $Account->getCode();

            if ($Account->getParentAccount())
                $accountParentIdBeforeEdit = $Account->getParentAccount()->getId();
            else
                $accountParentIdBeforeEdit = null;
            $accountSerialIdBeforeEdit = $Account->getSerial();
            $idClass = $Account->getClass();
            $Form->get('class')->setAttributes(array('value' => $Account->getClass(), 'selected' => true));
            if ($Account->getAccountType())
                $Form->get('account_type')->setAttributes(array('value' => $Account->getAccountType()->getId(), 'selected' => true));
            if ($Account->getAccountType())
                $Form->get('branch')->setAttributes(array('value' => $Account->getBranch()->getId(), 'selected' => true));
            if ($Account->getParentAccount())
            {
                //================= For populate Parent Account during save 
                $arrData = $this->getParentAccountSelectData($idClass);
                $Form->get('parent')->setValueOptions($arrData);
                $Form->get('parent')->setAttributes(array('value' => $Account->getParentAccount()->getId(), 'selected' => true));
            }
            $Form->bind($Account);
        }
        if ($Request->isPost())
        {
            $idCategory = (int) $Request->getPost('category');
            $idClass = $Request->getPost('class');
            $idParentAccount = $Request->getPost('parent');
            $name = $Request->getPost('name');
            $code = $Request->getPost('code');
//            //================= For populate Parent Account during save 
            $arrData = $this->getParentAccountSelectData($idClass);
            $Form->get('parent')->setValueOptions($arrData);
//            //*****
//            $Account = new Account();
            $AccountFilter = new AccountFilter(null);
            $Form->setInputFilter($AccountFilter->getInputFilter());
            $Filter = $Form->getInputFilter();
            //for edit only
            $Filter->get('category')->setRequired(false);
            $Filter->get('class')->setRequired(false);
            $Filter->get('code')->setRequired(false);
            //***
            switch ($idCategory)
            {
                case Account::SUPER_CONTROL:
                    $Filter->get('account_type')->setRequired(false);
                    $Filter->get('branch')->setRequired(false);
                    $Filter->get('parent')->setRequired(false);
                    break;
                case Account::CONTROL:
                    $Filter->get('account_type')->setRequired(false);
                    $Filter->get('branch')->setRequired(false);
                    break;
                case Account::DETAILED:
                    $Filter->get('account_type')->setRequired(true);
                    $Filter->get('branch')->setRequired(true);
                    $Filter->get('parent')->setRequired(true);
                    break;
                default :
                    $Filter->get('account_type')->setRequired(false);
                    $Filter->get('branch')->setRequired(false);
                    $Filter->get('parent')->setRequired(false);
                    break;
            }
            $Form->setData($Request->getPost());
            if ($Form->isValid())
            {
                $AccountRepo = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\Account');
                $params = array(
                    'editId' => $id,
                    'fields' => array('name' => $name, 'Company' => \Administration\Entity\Company::DEFAULTCOMPANY),
                    'context' => $this,
                    'errorArrayKey' => 'name',
                    'valBeforePost' => $accountNameBeforeEdit,
                    'valAfterPost' => $name,
                    'fieldLabel' => 'Account Name',
                    'Repository' => $AccountRepo,
                );
                $uniqueName = $this->GlobalResourcePlugin()->isUniqueObject($params);
                $params = array(
                    'editId' => $id,
                    'fields' => array('code' => $code, 'Company' => \Administration\Entity\Company::DEFAULTCOMPANY),
                    'context' => $this,
                    'errorArrayKey' => 'code',
                    'valBeforePost' => $accountCodeBeforeEdit,
                    'valAfterPost' => $code,
                    'fieldLabel' => 'Account Code',
                    'Repository' => $AccountRepo,
                );
                $uniqueCode = $this->GlobalResourcePlugin()->isUniqueObject($params);
                if ($uniqueName && $uniqueCode)
                {
                    $Compnay = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Company')->find(\Administration\Entity\Company::DEFAULTCOMPANY);
                    $Account->setCompany($Compnay);
                    if ($idParentAccount)
                    {
                        $ParentAccount = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\Account')->find($idParentAccount);
                        $Account->setParentAccount($ParentAccount);
                    }
                    $level = $this->makeAccountLevelValue($code);
                    if ($accountParentIdBeforeEdit == $idParentAccount)
                        $serial = $accountSerialIdBeforeEdit;
                    else
                        $serial = $this->makeLAccountSerialValue($idParentAccount);

                    $Account->setCategory($accountCategoryBeforeEdit);
                    $Account->setClass($accountClassBeforeEdit);
                    $Account->setLevel($level);
                    $Account->setSerial($serial);

                    $Account->setCode($accountCodeBeforeEdit);
                    $this->getServiceLocator()->get('EntityManager')->persist($Account);
                    $this->getServiceLocator()->get('EntityManager')->flush();
                    //==============New Instance Of Form
                    $successMsg = 'Data has been update successfully';
                    return $this->redirect()->toRoute('account', array(
                                'action' => 'index'
                    ));
                    //=================
                }
            }
        }
        $vals = array(
            'Form' => $Form,
            'id' => $id,
            'errorMsgs' => $this->errorMsgs,
            'successMsg' => $successMsg,
        );
        $ViewModel->setVariables($vals);
        return $ViewModel;
    }

    public function viewAction()
    {
        $arrSelectData = null;
        $successMsg = null;
        $arrData = null;
        $Request = $this->getRequest();
        $ViewModel = new ViewModel();
        if ($Request->isXmlHttpRequest())
        {
            $ViewModel->setTerminal(true);
        }
        $id = (int) $this->params()->fromRoute('id', null);
        if (null === $id)
        {
            return $this->redirect()->toRoute('account', array(
                        'action' => 'add'
            ));
        }
        $Account = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\Account')->find($id);
        $arrSelectData['type'] = $this->fetchTypeSelectData();
        $arrSelectData['branch'] = $this->getBranchSelectData();
        $Form = new AccountForm($arrSelectData);
        if (count($Account))
        {
            $accountCategoryBeforeEdit = $Account->getCategory();
            $accountClassBeforeEdit = $Account->getClass();
            $accountNameBeforeEdit = $Account->getName();
            $accountCodeBeforeEdit = $Account->getCode();

            if ($Account->getParentAccount())
                $accountParentIdBeforeEdit = $Account->getParentAccount()->getId();
            else
                $accountParentIdBeforeEdit = null;
            $accountSerialIdBeforeEdit = $Account->getSerial();
            $idClass = $Account->getClass();
            $Form->get('class')->setAttributes(array('value' => $Account->getClass(), 'selected' => true));
            if ($Account->getAccountType())
                $Form->get('account_type')->setAttributes(array('value' => $Account->getAccountType()->getId(), 'selected' => true));
            if ($Account->getAccountType())
                $Form->get('branch')->setAttributes(array('value' => $Account->getBranch()->getId(), 'selected' => true));
            if ($Account->getParentAccount())
            {
                //================= For populate Parent Account during save 
                $arrData = $this->getParentAccountSelectData($idClass);
                $Form->get('parent')->setValueOptions($arrData);
                $Form->get('parent')->setAttributes(array('value' => $Account->getParentAccount()->getId(), 'selected' => true));
            }
            $Form->bind($Account);
        }
        $vals = array(
            'Form' => $Form,
            'id' => $id,
        );
        $ViewModel->setVariables($vals);
        return $ViewModel;
    }

    public function deleteAction()
    {
        $Request = $this->getRequest();
        $ViewModel = new ViewModel();
        if ($Request->isXmlHttpRequest())
        {
            $ViewModel->setTerminal(true);
        }
        //****************Start Delete Stuff
        $id = (int) $this->params()->fromRoute('id', null);
        $Account = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\Account')->findBy(array('id' => $id));
        try
        {
            $this->getServiceLocator()->get('EntityManager')->remove($Account[0]);
            $this->getServiceLocator()->get('EntityManager')->flush();
        }
        catch (\Exception $Ex)
        {
            $errors = 'ErrorMessage: ' . $Ex->getMessage();
        }
        //****************End Delete Stuff
        //==============After deletion Redirect to Index
        return $this->redirect()->toRoute('account', array(
                    'action' => 'index',
        ));
        //=================
        return $ViewModel;
    }

    private function makeAccountLevelValue($code)
    {
        $level = 0;
        $arr = explode($this->codeSaparator, $code);
        if ($arr)
            $level = count($arr);
//            $level = count($arr) - 1;
        return $level;
    }

    private function makeLAccountSerialValue($idParent = null)
    {
        $serial = 0;
        if ($idParent)
        {
            $queryBuilder = $this->getServiceLocator()->get('EntityManager')->createQueryBuilder();
            $result = $queryBuilder->select('MAX(a.serial) max_serial')
                            ->from('Account\Entity\Account', 'a')
                            ->where('a.ParentAccount = :ParentId')
                            ->setParameter('ParentId', $idParent)
                            ->getQuery()->getOneOrNullResult();
//            echo $idParent.'     '.$result['max_serial'];die;
            if ($result['max_serial'])
                $serial = $result['max_serial'] + 1;
            else
                $serial = $serial + 1;
        }
        return $serial;
    }

    public function fetchAcconts()
    {
        $arrSelectData = array();
        $Accounts = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\Account')->findAll();
        foreach ($Accounts as $Account)
        {
            $arrSelectData[$Account->getId()] = $Account->getName() . '  [' . $Account->getCode() . ']';
        }
        return $arrSelectData;
    }

    public function fetchAccontCodes()
    {
        $arrSelectData = array();
        $Accounts = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\Account')->findAll();
        foreach ($Accounts as $Account)
        {
            $arrSelectData[$Account->getCode()] = $Account->getCode();
        }
        return $arrSelectData;
    }

    public function fetchAccontNames()
    {
        $arrSelectData = array();
        $Accounts = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\Account')->findAll();
        foreach ($Accounts as $Account)
        {
            $arrSelectData[$Account->getName()] = $Account->getName();
        }
        return $arrSelectData;
    }

    public function fetchTypeSelectData()
    {
        $arrSelectData = array();
        $Types = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\AccountType')->findAll();
        foreach ($Types as $Type)
        {
            $arrSelectData[$Type->getId()] = $Type->getName();
        }
        return $arrSelectData;
    }

    public function getBranchSelectData()
    {
        $arrSelectData = array();
        $Branches = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Branch')->findAll();
        foreach ($Branches as $Branch)
        {
            $arrSelectData[$Branch->getId()] = $Branch->getName();
        }
        return $arrSelectData;
    }

    public function getParentAccountSelectData($idClass)
    {
        $arrData = array();
        $queryBuilder = $this->getServiceLocator()->get('EntityManager')->createQueryBuilder();
        $Accounts = $queryBuilder->select('a')
                        ->from('Account\Entity\Account', 'a')
                        ->where('a.category != :category AND a.class = :class')
                        ->setParameter('category', Account::DETAILED)
                        ->setParameter('class', $idClass)
                        ->getQuery()->getResult();
        foreach ($Accounts as $Account)
        {
            $arrData[$Account->getId()] = $Account->getName();
        }
        return $arrData;
    }

    public function populateParentAccountAction()
    {
        $idClass = $this->params()->fromRoute('id', 0);
        $arrData = array();
        $queryBuilder = $this->getServiceLocator()->get('EntityManager')->createQueryBuilder();
        $Accounts = $queryBuilder->select('a')
                        ->from('Account\Entity\Account', 'a')
                        ->where('a.category != :category AND a.class = :class')
                        ->setParameter('category', Account::DETAILED)
                        ->setParameter('class', $idClass)
                        ->getQuery()->getResult();
        foreach ($Accounts as $Account)
        {
            $arrData[$Account->getId()] = $Account->getName();
        }
        if (count($arrData) > 0)
            echo json_encode($arrData);
        else
            echo '';
        exit;
    }

    public function generateAccountCodeAction()
    {
        $code = 0;
        $codeLeftPadLength = 9;
        $parentCode = '';
        $codeGenerated = null;
        $finalCode = '';
        $qb = $this->getServiceLocator()->get('EntityManager')->createQueryBuilder();
        $idParentAccount = (int) $this->params()->fromRoute('id', 0);
        $category = (int) $this->params()->fromQuery('category', 0);
        $Account = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\Account')->find($idParentAccount);
        $accountClass = $Account->getClass();
        switch ($category)
        {
            case Account::CONTROL:
                $codeLeftPadLength = 3;
                break;
            case Account::DETAILED:
                $codeLeftPadLength = 4;
                break;
        }
        //*****Make Code on Serial Max
        $qb->select('MAX(a.serial) AS max_code_no')
                ->from('Account\Entity\Account', 'a')
                ->where('a.class = :class AND a.ParentAccount= :parentId')
                ->setParameters(array(
                    'class' => $accountClass,
                    'parentId' => $idParentAccount,
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
                ->from('Account\Entity\Account', 'a')
                ->where('a.id = :identifier')
                ->setParameters(array(
                    'identifier' => $idParentAccount,
        ));
        $result = $qb->getQuery()->getOneOrNullResult();
        if ($result['code'] !== null)
            $parentCode = $result['code'];
        //***End
        $finalCode = $parentCode . $this->codeSaparator . $codeGenerated;
        echo json_encode($finalCode);
        exit;
    }

    public function populateParentAccountNextAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $arrData = array();
        $Accounts = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\Account')->findBy(array('Account' => $id));
        foreach ($Accounts as $Account)
        {
            $arrData[$Account->getId()] = $Account->getName();
        }
        if (count($arrData) > 0)
            echo json_encode($arrData);
        else
            echo '';

        exit;
    }

}
