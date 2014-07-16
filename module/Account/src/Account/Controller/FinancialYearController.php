<?php

namespace Account\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Doctrine\ORM\EntityManager,
    Zend\View\Model\ViewModel,
    Account\Entity\FinancialYear,
    Account\Filter\FinancialYearFilter,
    Account\Form\FinancialYearForm,
    DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter,
    Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator,
    Zend\Paginator\Paginator,
    Zend\Session\Container;

class FinancialYearController extends AbstractActionController
{

    public $errorMsgs = array();
    private $recordPerPage = 5;

    /**
     * @var Doctrine\ORM\EntityManager
     */
    public function __construct()
    {
        
    }

    /**
     * Return list of resources
     *
     * @return array
     */
    public function indexAction()
    {
        //===============================================================================================
        $fySession = new Container('financialYear'); //zend-Session
        $fySession->fyName = '';
        $fySession->fyStartDate = '';
        $fySession->fyEndDate = '';
        $Request = $this->getRequest();
        $ViewModel = new ViewModel();
        if ($Request->isXmlHttpRequest())
        {
            $ViewModel->setTerminal(true);
        }
        $Form = new \Account\Form\FinancialYearSearchForm();
        $Repository = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\FinancialYear');
        $QueryBuilder = $Repository->createQueryBuilder('F');
        //==============================START SEARCH
        if ($Request->isPost() && $Request->getPost('submit') === 'Search')
        {
            $Form->get('voucher_number')->setAttribute('value', $Request->getPost('name'));
            $Form->get('start_date')->setAttribute('value', $Request->getPost('start_date'));
            $Form->get('end_date')->setAttribute('value', $Request->getPost('end_date'));
            $fySession->fyName = $Request->getPost('name');
            $fySession->fyStartDate = $Request->getPost('start_date');
            $fySession->fyEndDate = $Request->getPost('end_date');
            $arrSearchParams = array(
                'name' => $Request->getPost('name'),
                'start_date' => $Request->getPost('start_date'),
                'end_date' => $Request->getPost('end_date'),
            );
            $QueryBuilder = $this->searchResults($arrSearchParams, $QueryBuilder);
        }
        else
        {
            $QueryBuilder->select('F')
                    ->addOrderBy('F.name', 'DESC');
//                    ->addOrderBy('F.VoucherType', 'ASC');
        }
        //==============================END SEARCH
        $ORMPaginator = new ORMPaginator($QueryBuilder);
        $Adapter = new DoctrineAdapter($ORMPaginator);
        $Paginator = new Paginator($Adapter);
        $Paginator->setDefaultItemCountPerPage($this->recordPerPage);
        $page = (int) $this->params()->fromQuery('page', 1);
        $fySession->listPageNo = $page;
        $Paginator->setCurrentPageNumber($page);
        $ViewModel->setVariable('Paginator', $Paginator);
        $ViewModel->setVariable('Form', $Form);
        return $ViewModel;
    }

    public function addAction()
    {
        $Request = $this->getRequest();
        $ViewModel = new ViewModel();
        if ($Request->isXmlHttpRequest())
        {
            $ViewModel->setTerminal(true);
        }
        $id = (int) $this->params()->fromRoute('id', null);
        if (null === $id)
        {
            return $this->redirect()->toRoute('financial_year', array(
                        'action' => 'index'
            ));
        }
        $Form = new FinancialYearForm();
//        $Form->get('submit')->setValue('Add');
//        $Request = $this->getRequest();
        if ($Request->isPost())
        {
            $FinancialYear = new FinancialYear($this->getServiceLocator());
            $FinancialYearFilter = new FinancialYearFilter();
            $Form->setInputFilter($FinancialYearFilter->getInputFilter());
            $Company = $this->fetchCompany(\Account\Entity\FinancialYear::COMPANY);


            $FinancialYear->setCompany($Company);
            $Form->setData($Request->getPost());
            $name = $Request->getPost('name');
            $start_date = $Request->getPost('start_date');
            $end_date = $Request->getPost('end_date');
            if ($Form->isValid())
            {
                $AccountRepo = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\FinancialYear');
                $params = array(
                    'editId' => null,
                    'fields' => array('name' => $name, 'Company' => \Administration\Entity\Company::DEFAULTCOMPANY),
                    'context' => $this,
                    'errorArrayKey' => 'name',
                    'fieldLabel' => 'Financial Year',
                    'Repository' => $AccountRepo,
                );
                $uniqueName = $this->GlobalResourcePlugin()->isUniqueObject($params);
                if (strtotime($start_date) < strtotime($end_date))
                {
                    if ($uniqueName)
                    {

                        $FinancialYear->setStartDate($start_date);
                        $FinancialYear->setEndDate($end_date);
                        $FinancialYear->exchangeArray($Form->getData());
                        $this->getServiceLocator()->get('EntityManager')->persist($FinancialYear);
                        $this->getServiceLocator()->get('EntityManager')->flush();
                        return $this->redirect()->toRoute('financial_year', array(
                                    'action' => 'index'
                        ));
                    }
                }
                else
                {
                    $this->errorMsgs['start_date'] = 'Please select correct Start Date';
                }
            }
        }
        $ViewModel->setVariable('errorMsgs', $this->errorMsgs);
        $ViewModel->setVariable('Form', $Form);
        return $ViewModel;
    }

    public function editAction()
    {
//        $ViewModel = new ViewModel();
        $Request = $this->getRequest();
        $ViewModel = new ViewModel();
        if ($Request->isXmlHttpRequest())
        {
            $ViewModel->setTerminal(true);
        }
        $id = (int) $this->params()->fromRoute('id', null);
        if (null === $id)
        {
            return $this->redirect()->toRoute('financial_year', array(
                        'action' => 'index'
            ));
        }
        $Form = new FinancialYearForm();
//        $Form->get('submit')->setValue('Edit');
//        $Request = $this->getRequest();
        $FinancialYear = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\FinancialYear')->find($id);
        $FinancialYearFilter = new FinancialYearFilter();
        $startDate = $FinancialYear->getStartDate()->format(\Application\Library\Application::DATE_FORMAT_FOR_DISPLAY);
        $endDate = $FinancialYear->getEndDate()->format(\Application\Library\Application::DATE_FORMAT_FOR_DISPLAY);
//        $startDate = $startDateObj->format('Y-m-d');
        $Form->bind($FinancialYear);
        $Form->get('start_date')->setValue($startDate);
        $Form->get('end_date')->setValue($endDate);
        if ($Request->isPost())
        {
            $Form->setInputFilter($FinancialYearFilter->getInputFilter());
            $Company = $this->fetchCompany(\Account\Entity\FinancialYear::COMPANY);
            $FinancialYear->setCompany($Company);
            $FinancialYearNameBeforeEdit = $FinancialYear->getName();

            $Form->setData($Request->getPost());
            $name = $Request->getPost('name');
            $startDate = $Request->getPost('start_date');
            $endDate = $Request->getPost('end_date');

            if ($Form->isValid())
            {
                $AccountRepo = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\FinancialYear');
                $params = array(
                    'editId' => $id,
                    'fields' => array('name' => $name, 'Company' => \Administration\Entity\Company::DEFAULTCOMPANY),
                    'context' => $this,
                    'errorArrayKey' => 'name',
                    'valBeforePost' => $FinancialYearNameBeforeEdit,
                    'valAfterPost' => $name,
                    'fieldLabel' => 'Account Name',
                    'Repository' => $AccountRepo,
                );
                $uniqueName = $this->GlobalResourcePlugin()->isUniqueObject($params);
                if (strtotime($startDate) < strtotime($endDate))
                {
                    if ($uniqueName)
                    {
                        $start_date = $Request->getPost('start_date');
                        $end_date = $Request->getPost('end_date');
                        $FinancialYear->setStartDate($start_date);
                        $FinancialYear->setEndDate($end_date);
                        //===============
                        $userAuth = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
                        $User = $userAuth->getIdentity();
                        $FinancialYear->setModifier($User);
                        $FinancialYear->setModificationDate("now");
                        //===============
                        $this->getServiceLocator()->get('EntityManager')->persist($FinancialYear);
                        $this->getServiceLocator()->get('EntityManager')->flush();
                        return $this->redirect()->toRoute('financial_year', array(
                                    'action' => 'index'
                        ));
                    }
                }
                else
                {
                    $this->errorMsgs['start_date'] = 'Please select correct Start Date';
                }
            }
        }
        $ViewModel->setVariable('errorMsgs', $this->errorMsgs);
        $ViewModel->setVariable('id', $id);
        $ViewModel->setVariable('Form', $Form);
        return $ViewModel;
    }

    private function fetchCompany($id)
    {
        $Company = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Company')->find($id);
        return $Company;
    }

    private function searchResults($arrSearchParams, $QueryBuilder)
    {
        $name = $arrSearchParams['name'];
        $startDate = $arrSearchParams['start_date'];
        $endDate = $arrSearchParams['end_date'];
        $whereClause = '';
        $parameters = null;
        $filterFields = array();
//        $argList = func_get_args();
//        if (count($argList))
//        {
//            $name = $argList[0];
//            $startDate = $argList[1];
//            $endDate = $argList[2];

        if ($name != "" && $startDate != "" && $endDate != '')
        {
            $whereClause = "
                F.name= :name AND
                F.start_date = :start_date AND
                F.end_date = :end_date
                ";
            $parameters = array(
                'name' => $name,
                'start_date' => $startDate,
                'end_date' => $endDate,
            );
        }
        elseif ($name != "" && $startDate != "")
        {
            $whereClause = "
                F.name= :name AND
                F.start_date = :start_date
                ";
            $parameters = array(
                'name' => $name,
                'start_date' => $startDate,
            );
        }
        elseif ($name != "" && $endDate != "")
        {
            $whereClause = "
                F.name= :name AND
                F.end_date = :end_date
                ";
            $parameters = array(
                'name' => $name,
                'end_date' => $endDate,
            );
        }
        elseif ($startDate != "" && $endDate != "")
        {
            $whereClause = "
                F.start_date= :start_date AND
                F.end_date = :end_date
                ";
            $parameters = array(
                'start_date' => $startDate,
                'end_date' => $endDate,
            );
        }
        elseif ($name != "")
        {
            $whereClause = "F.name = :name";
            $parameters = array('name' => $name);
        }
        elseif ($startDate != "")
        {
            $whereClause = "F.start_date = :start_date";
            $parameters = array('start_date' => $startDate);
        }
        elseif ($endDate != "")
        {
            $whereClause = "F.end_date = :end_date";
            $parameters = array('end_date' => $endDate);
        }
//        }
        if ($whereClause != '' && $parameters != null)
        {
            $QueryBuilder->select('F')
                    ->where($whereClause)
                    ->setParameters($parameters)
                    ->addOrderBy('F.name', 'DESC');
//                    ->addOrderBy('V.VoucherType', 'ASC');
        }
        return $QueryBuilder;
//        $FinancialYears = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\FinancialYear')->findBy($filterFields, array('name' => 'desc'));
//        return $FinancialYears;
    }

    private function isUniqueObject(array $findBy = null, $field = '', $fieldLabel = '', $action = '')
    {
        $Obj = $this->getServiceLocator()->get('EntityManager')->getRepository('Manufacturing\Entity\FinancialYear')->findBy($findBy);
//        if (count($Obj))
//        {
//            $this->errorMsgs[$field] = $fieldLabel . ' already exists';
//            return false;
//        }
//        else
//            return true;
        if ($action == 'Edit')
        {
            if (count($Obj) == 1)
            {

                return true;
            }
            else if (count($Obj))
            {
                $this->errorMsgs[$field] = $fieldLabel . ' already exists';
                return false;
            }
            else
                return true;
        }
        else if ($action == 'Add')
        {
            if (count($Obj))
            {
                $this->errorMsgs[$field] = $fieldLabel . ' already exists';
                return false;
            }
            else
                return true;
        }
    }

}

