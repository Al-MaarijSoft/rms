<?php

namespace Account\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Doctrine\ORM\EntityManager,
    Zend\View\Model\ViewModel,
    Account\Entity\OpeningBalance,
    Account\Filter\OpeningBalanceFilter,
    Account\Form\OpeningBalanceForm,
    DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter,
    Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator,
    Zend\Paginator\Paginator;

class OpeningBalanceController extends AbstractActionController
{

    public $errorMsgs = array();
    private $recordPerPage = 15;

    public function indexAction()
    {
//        $arrSelectData = array();
//        $Request = $this->getRequest();
//        $ViewModel = new ViewModel();
//        $arrSelectData['Account'] = $this->fetchAccountSelect();
//        $arrSelectData['financialYear'] = $this->fetchFinancialYearSelect();
//        $Form = new \Account\Form\OpeningBalanceSearchForm($arrSelectData);
//        if ($Request->isXmlHttpRequest())
//        {
//            $ViewModel->setTerminal(true);
//        }
//        $Repo = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\OpeningBalance');
//        $ORMPaginator = new ORMPaginator($Repo->createQueryBuilder('opening_balance'));
//        $Adapter = new DoctrineAdapter($ORMPaginator);
//        $Paginator = new Paginator($Adapter);
//        $Paginator->setDefaultItemCountPerPage(5);
//        $page = (int) $this->params()->fromQuery('page', 1);
//        $Paginator->setCurrentPageNumber($page);
//        $ViewModel->setVariable('Form', $Form);
//        $ViewModel->setVariable('Paginator', $Paginator);
//        return $ViewModel;
        //===============================================================================================================================
        $obSession = new \Zend\Session\Container('openingBalance'); //zend-Session
        $obSession->obFinancialYear = '';
        $obSession->obAccount = '';
        $Request = $this->getRequest();
        $ViewModel = new ViewModel();
        $arrSelectData['Account'] = $this->fetchAccountSelect();
        $arrSelectData['financialYear'] = $this->fetchFinancialYearSelect();
        $Form = new \Account\Form\OpeningBalanceSearchForm($arrSelectData);
        if ($Request->isXmlHttpRequest())
        {
            $ViewModel->setTerminal(true);
        }
        $Repository = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\OpeningBalance');
        $QueryBuilder = $Repository->createQueryBuilder('OB');
        //==============================START SEARCH
        if ($Request->isPost() && $Request->getPost('submit') === 'Search')
        {
            $Form->get('financial_year')->setAttribute('value', $Request->getPost('financial_year'));
            $Form->get('account')->setAttribute('value', $Request->getPost('account'));

            $obSession->obFinancialYear = $Request->getPost('financial_year');
            $obSession->obAccount = $Request->getPost('account');
            $arrSearchParams = array(
                'financial_year' => $Request->getPost('financial_year'),
                'account' => $Request->getPost('account'),
            );
            $QueryBuilder = $this->searchResults($arrSearchParams, $QueryBuilder);
        }
        else
        {
            $QueryBuilder->select('OB')
                    ->addOrderBy('OB.FinancialYear', 'ASC');
//                    ->addOrderBy('F.VoucherType', 'ASC');
        }
        //==============================END SEARCH
        $ORMPaginator = new ORMPaginator($QueryBuilder);
        $Adapter = new DoctrineAdapter($ORMPaginator);
        $Paginator = new Paginator($Adapter);
        $Paginator->setDefaultItemCountPerPage($this->recordPerPage);
        $page = (int) $this->params()->fromQuery('page', 1);
        $obSession->listPageNo = $page;
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
        $arrSelectData['Account'] = $this->fetchAccountSelect();
        $Form = new OpeningBalanceForm($arrSelectData);
        $Form->get('submit')->setValue('Save');

        $arrFinancialYearSelectData = $this->fetchFinancialYearSelect();
        $Form->get('financial_year')->setValueOptions($arrFinancialYearSelectData);
        if ($Request->isPost())
        {
            $OpeningBalance = new OpeningBalance();
            $OpeningBalanceFilter = new OpeningBalanceFilter();
            $Form->setInputFilter($OpeningBalanceFilter->getInputFilter());
            $Form->setData($Request->getPost());
            $name = $Request->getPost('name');
            $debit = $Request->getPost('debit');
            $credit = $Request->getPost('credit');
            $financialYearId = $Request->getPost('financial_year');
            $accountId = $Request->getPost('account');
            $FinancialYear = $this->fetchFinancialYearById($financialYearId);
            $Account = $this->fetchAccountById($accountId);
            if ($Form->isValid())
            {
                $OpeningBalanceRepo = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\OpeningBalance');
                $params = array(
                    'editId' => null,
                    'fields' => array('Account' => $Account->getId(), 'FinancialYear' => $FinancialYear->getId()),
                    'context' => $this,
                    'errorArrayKey' => 'financial_year',
                    'fieldLabel' => 'Financial Year',
                    'Repository' => $OpeningBalanceRepo,
                );

                $uniqueFyAndAccountId = $this->GlobalResourcePlugin()->isUniqueObject($params);
                if ($uniqueFyAndAccountId)
                {
                    $OpeningBalance->setAccount($Account);
                    $OpeningBalance->setFinancialYear($FinancialYear);
                    $OpeningBalance->exchangeArray($Form->getData());
                    $this->getServiceLocator()->get('EntityManager')->persist($OpeningBalance);
                    $this->getServiceLocator()->get('EntityManager')->flush();
                    return $this->redirect()->toRoute('opening_balance', array(
                                'action' => 'index'
                    ));
                }
                else
                {
                    $this->errorMsgs['financial_year'] = $Account->getName() . ' is already in use with Financial Year ' . $FinancialYear->getName();
                }
            }
        }

        $ViewModel->setVariable('errorMsgs', $this->errorMsgs);
        $ViewModel->setVariable('Form', $Form);
        return $ViewModel;
    }

    public function editAction()
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
            return $this->redirect()->toRoute('opening_balance', array(
                        'action' => 'index'
            ));
        }
        $arrSelectData['Account'] = $this->fetchAccountSelect();
        $arrSelectData['financialYear'] = $this->fetchFinancialYearSelect();

        $Form = new OpeningBalanceForm($arrSelectData);
        $Form->get('submit')->setValue('Edit');
        if ($id)
            $OpeningBalance = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\OpeningBalance')->find($id);
        else
        {
            $id = $Request->getPost('id');
            $OpeningBalance = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\OpeningBalance')->find($id);
        }
        $OpeningBalanceFilter = new OpeningBalanceFilter();
        $Form->get('financial_year')->setAttributes(array('value' => (is_object($OpeningBalance->getFinancialYear()) ? $OpeningBalance->getFinancialYear()->getId() : ''), 'selected' => true));
        $Form->get('account')->setAttributes(array('value' => (is_object($OpeningBalance->getAccount()) ? $OpeningBalance->getAccount()->getId() : ''), 'selected' => true));
        $FinancialYearBeforeEdit = $OpeningBalance->getFinancialYear();
        $financialYearBeforeEditId = $OpeningBalance->getFinancialYear()->getId();
        $AccountBeforeEdit = $OpeningBalance->getAccount();
        $accountBeforeEditId = $OpeningBalance->getAccount()->getId();

        $Form->bind($OpeningBalance);
        if ($Request->isPost())
        {
            $OpeningBalanceFilter = new OpeningBalanceFilter();
            $Form->setInputFilter($OpeningBalanceFilter->getInputFilter());

            $Form->setData($Request->getPost());
            $financialYearId = $Request->getPost('financial_year');
            $accountId = $Request->getPost('account');
            if ($Form->isValid())
            {
                $FinancialYear = $this->fetchFinancialYearById($financialYearId);
                $Account = $this->fetchAccountById($accountId);

                $OpeningBalanceRepo = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\OpeningBalance');
                $params = array(
                    'editId' => $id,
                    'fields' => array('Account' => $Account->getId(), 'FinancialYear' => $FinancialYear->getId()),
                    'context' => $this,
                    'errorArrayKey' => 'financial_year',
                    'valBeforePost' => $FinancialYear->getId(),
                    'valAfterPost' => $financialYearBeforeEditId,
                    'fieldLabel' => 'Financial Year',
                    'Repository' => $OpeningBalanceRepo,
                );
                $uniqueFyAndAccountId = $this->GlobalResourcePlugin()->isUniqueObject($params);
                if ($uniqueFyAndAccountId)
                {
                    $OpeningBalance->setAccount($Account);
                    $OpeningBalance->setFinancialYear($FinancialYear);
                    $this->getServiceLocator()->get('EntityManager')->persist($OpeningBalance);
                    $this->getServiceLocator()->get('EntityManager')->flush();
                    return $this->redirect()->toRoute('opening_balance', array(
                                'action' => 'index'
                    ));
                }
                else
                {
                    $this->errorMsgs['financial_year'] = $OpeningBalance->getAccount()->getName() . ' is already in use with ' . $OpeningBalance->getFinancialYear()->getName();
                }
            }
        }
//        print_r($this->errorMsgs);
        $ViewModel->setVariable('errorMsgs', $this->errorMsgs);
        $ViewModel->setVariable('Form', $Form);
        $ViewModel->setVariable('id', $id);
        return $ViewModel;
    }

    private function fetchCompany($id)
    {
        $Company = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Company')->find($id);
        return $Company;
    }

    private function fetchFinancialYearById($id)
    {
        $FinancialYear = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\FinancialYear')->find($id);
        return $FinancialYear;
    }

    private function fetchAccountById($id)
    {
        $Account = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\Account')->find($id);
        return $Account;
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

    private function fetchAccountSelect()
    {
        $arrSelectData = array();
        $Accounts = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\Account')->findBy(
                array(
            'Company' => \Administration\Entity\Company::DEFAULTCOMPANY,
            'category' => \Account\Entity\Account::DETAILED,
            'class' => array(\Account\Entity\Account::ASSET, \Account\Entity\Account::LIABILITY, \Account\Entity\Account::CAPITAL)
                ), array(
            'code' => 'ASC',
                )
        );
        if (count($Accounts) > 0)
        {
            foreach ($Accounts as $Account)
            {
                $arrSelectData[$Account->getId()] = $Account->getName() . ' [' . $Account->getCode() . ']';
            }
        }
        return $arrSelectData;
    }

    private function fetchFinancialYearSelect()
    {
        $arrSelectData = array();
        $FinancialYears = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\FinancialYear')->findBy(array('Company' => \Administration\Entity\Company::DEFAULTCOMPANY));
        if (count($FinancialYears) > 0)
        {
            foreach ($FinancialYears as $FinancialYear)
            {
                $arrSelectData[$FinancialYear->getId()] = $FinancialYear->getName();
            }
        }
        return $arrSelectData;
    }

    private function searchResults($arrSearchParams, $QueryBuilder)
    {
        $financialYear = $arrSearchParams['financial_year'];
        $account = $arrSearchParams['account'];
        $whereClause = '';
        $parameters = null;

        if ($financialYear != "" && $account != "")
        {
            $whereClause = "
                OB.FinancialYear = :financial_year AND
                OB.Account = :account
                ";
            $parameters = array(
                'financial_year' => $financialYear,
                'account' => $account,
            );
        }
        elseif ($financialYear != "")
        {
            $whereClause = "
                OB.FinancialYear = :financial_year
                ";
            $parameters = array(
                'financial_year' => $financialYear,
            );
        }
        elseif ($account != "")
        {
            $whereClause = "
                OB.Account= :account
                ";
            $parameters = array(
                'account' => $account,
            );
        }
        if ($whereClause != '' && $parameters != null)
        {
            $QueryBuilder->select('OB')
                    ->where($whereClause)
                    ->setParameters($parameters)
                    ->addOrderBy('OB.FinancialYear', 'ASC');
        }
        return $QueryBuilder;
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
        $OpeningBalance = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\OpeningBalance')->findBy(array('id' => $id));
        try
        {
            $this->getServiceLocator()->get('EntityManager')->remove($OpeningBalance[0]);
            $this->getServiceLocator()->get('EntityManager')->flush();
        }
        catch (\Exception $Ex)
        {
            $errors = 'ErrorMessage: ' . $Ex->getMessage();
        }
        //****************End Delete Stuff
        //==============After deletion Redirect to Index
        return $this->redirect()->toRoute('opening_balance', array(
                    'action' => 'index',
        ));
        //=================
        return $ViewModel;
    }

}

