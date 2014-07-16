<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PlotController
 *
 * @author Muhammad Rashid
 */

namespace Reporting\Controller;

use Application\Library\Application;
use Application\Library\WkHtmlToPdf;
use Reporting\Form\VoucherReportingForm;
use Reporting\Form\AccountLedgerReportingForm;
use Zend\Http\Client;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

//use Zend\Http\Client\Adapter\Curl;

class ReportingController extends AbstractActionController
{

    const SIMPLE_REPORT_ACTION = 'Simple Report';
    const PDF_REPORT_ACTION = 'Pdf Report';

    public function indexAction()
    {
        
    }

    public function filterVoucherReportAction()
    {
        $vrRptSession = new Container('voucherRpt'); //zend-Session
        $vrRptSession->vrStartDate = '';
        $vrRptSession->vrEndDate = '';
        $vrRptSession->vrNumber = '';
        $vrRptSession->vrType = '';
        $Request = $this->getRequest();
        $ViewModel = new ViewModel();
        if ($Request->isXmlHttpRequest())
        {
            $ViewModel->setTerminal(true);
        }
        $populateData['type'] = $this->fetchVoucherTypes();
        $populateData['vrNumber'] = $this->fetchVoucherNumbers();
        $Form = new VoucherReportingForm($populateData);
        if ($Request->isPost())
        {
            $Form->get('start_date')->setAttribute('value', $Request->getPost('start_date'));
            $Form->get('end_date')->setAttribute('value', $Request->getPost('end_date'));
            $Form->get('voucher_number')->setAttribute('value', $Request->getPost('voucher_number'));
            $Form->get('voucher_type')->setAttributes(array('value' => $Request->getPost('voucher_type'), 'selected' => true));

            $vrRptSession->vrStartDate = Application::makeDateObjectForDB($Request->getPost('start_date'));
            $vrRptSession->vrEndDate = Application::makeDateObjectForDB($Request->getPost('end_date'));
            $vrRptSession->vrNumber = $Request->getPost('voucher_number');
            $vrRptSession->vrType = $Request->getPost('voucher_type');
        }
        $ViewModel->setVariable('Form', $Form);
        return $ViewModel;
    }

    public function filterAccountsLedgerReportAction()
    {
        $errorMessages = null;
        $alRptSession = new Container('AccountsLedgerRpt'); //zend-Session
        $alRptSession->alStartDate = '';
        $alRptSession->alEndDate = '';
        $alRptSession->fromAccount = '';
        $alRptSession->toAccount = '';
        $Request = $this->getRequest();
        $ViewModel = new ViewModel();
        if ($Request->isXmlHttpRequest())
        {
            $ViewModel->setTerminal(true);
        }
        $populateData['account'] = $this->fetchDetailedAccounts();
        $Form = new AccountLedgerReportingForm($populateData);

        if ($Request->isPost())
        {
            $ReportingFilter = new \Reporting\Filter\ReportingFilter();
            $Form->setInputFilter($ReportingFilter->getInputFilter());
            $Form->setData($Request->getPost());
            if ($Form->isValid())
            {
                $Form->get('start_date')->setAttribute('value', $Request->getPost('start_date'));
                $Form->get('end_date')->setAttribute('value', $Request->getPost('end_date'));
                $Form->get('from_account')->setAttributes(array('value' => $Request->getPost('from_account'), 'selected' => true));
                $Form->get('to_account')->setAttributes(array('value' => $Request->getPost('to_account'), 'selected' => true));
            }
            else
            {
                $errorMessages = Application::parseFormErrorMessages($Form->getMessages());
            }
            $alRptSession->alStartDate = Application::makeDateObjectForDB($Request->getPost('start_date'));
            $alRptSession->alEndDate = Application::makeDateObjectForDB($Request->getPost('end_date'));
            $alRptSession->fromAccount = $Request->getPost('from_account');
            $alRptSession->toAccount = $Request->getPost('to_account');
        }
        $ViewModel->setVariable('Form', $Form);
        $ViewModel->setVariable('errorMessages', $errorMessages);
        return $ViewModel;
    }

//    public function showSimpleVoucherReportAction()
//    {
//        $ViewModel = new ViewModel();
//        $ViewModel->setTerminal(true);
//        $QryResult = $this->loadVoucherReportResult();
//        $ViewModel->setVariable('Vouchers', $QryResult);
//        $ViewModel->setVariable('baseUrl', $this->getBaseUrl());
//        $ViewModel->setVariable('Pdf', $this->getWkHtmltoPdfObj());
//        return $ViewModel;
//    }

    public function showPdfVoucherReportAction()
    {
        $ViewModel = new ViewModel();
        $ViewModel->setTerminal(true);
        $QryResult = $this->loadVoucherReportResult();
        $variables = array(
            'Vouchers' => $QryResult,
            'baseUrl' => $this->getBaseUrl(),
            'Pdf' => $this->getWkHtmltoPdfObj(),
        );
        $ViewModel->setVariables($variables);
        return $ViewModel;
    }

    public function showPdfAccountLedgerReportAction()
    {
        $alRptSession = new Container('AccountsLedgerRpt'); //zend-Session
//        $QryResult2 = $this->fetchOpeningsPlusPreviousDateBalance(array('start_date' => $alRptSession->alStartDate));
//        var_dump($QryResult2);
//        die;

        $ViewModel = new ViewModel();
        $ViewModel->setTerminal(true);
        $QryResult = $this->loadAccountLedgerReportResult();
        $QryResult2 = $this->fetchOpeningsPlusPreviousDateBalance(array('start_date' => $alRptSession->alStartDate));
        $variables = array(
            'VoucherDetails' => $QryResult,
            'oBalances' => $QryResult2,
            'fromDate' => $alRptSession->alStartDate,
            'toDate' => $alRptSession->alEndDate,
            'baseUrl' => $this->getBaseUrl(),
            'Pdf' => $this->getWkHtmltoPdfObj(),
        );
        $ViewModel->setVariables($variables);
        return $ViewModel;
    }

    private function fetchOpeningsPlusPreviousDateBalance($params)
    {
        $startDate = $params['start_date'];
        $whereClause = "V.voucher_date >= :voucher_date";
        $parameters = array('voucher_date' => $startDate);
        $Repository = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\Account');
        $QueryBuilder = $Repository->createQueryBuilder('A');
        $QueryBuilder->select('A.id,A.code, SUM(VD.debit - VD.credit) AS previousDatesOpening, (OB.debit - OB.credit) AS OBalance')
                ->leftJoin('A.OpeningBalances', 'OB', 'WITH')
                ->innerJoin('A.VoucherDetails', 'VD', 'WITH')
                ->innerJoin('VD.Voucher', 'V', 'WITH')
                ->where($whereClause)
                ->setParameters($parameters)
                ->groupBy('VD.Account')
                ->addOrderBy('A.code', 'ASC')
                ->addOrderBy('V.voucher_date', 'ASC')
                ->addOrderBy('V.VoucherType', 'ASC')
                ->addOrderBy('V.serial', 'ASC');
//                
//                ============================================================================================
//        $Repository = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\VoucherDetail');
//        $QueryBuilder = $Repository->createQueryBuilder('VD');
//        $QueryBuilder->select('A.code,SUM(VD.debit - VD.credit) AS previousDatesOpening')
//                ->innerJoin('VD.Voucher', 'V', 'WITH')
//                ->innerJoin('VD.Account', 'A', 'WITH')
//                ->where($whereClause)
//                ->setParameters($parameters)
//                ->groupBy('VD.Account')
//                ->addOrderBy('A.code', 'ASC')
//                ->addOrderBy('V.voucher_date', 'ASC')
//                ->addOrderBy('V.VoucherType', 'ASC')
//                ->addOrderBy('V.serial', 'ASC');
//        ====================================================================================================================
        return $QueryBuilder->getQuery()->getResult();
    }

    private function getBaseUrl()
    {
        $event = $this->getEvent();
        $request = $event->getRequest();
        $router = $event->getRouter();
        $uri = $router->getRequestUri();
        $baseUrl = sprintf('%s://%s%s', $uri->getScheme(), $uri->getHost(), $request->getBaseUrl());
        return $baseUrl;
    }

    private function loadVoucherReportResult()
    {
        $vrRptSession = new Container('voucherRpt');
        $Repository = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\Voucher');
        $QueryBuilder = $Repository->createQueryBuilder('V');
        $arrSearchParams = array(
            'start_date' => $vrRptSession->vrStartDate,
            'end_date' => $vrRptSession->vrEndDate,
            'voucher_number' => $vrRptSession->vrNumber,
            'voucher_type' => $vrRptSession->vrType,
        );
//        $QueryBuilder = $this->searchResults($arrSearchParams, $QueryBuilder);
//        $QryResult = $QueryBuilder->getQuery()->getResult();
        $QryResult = $this->searchResults($arrSearchParams, $QueryBuilder)->getQuery()->getResult();
//        var_dump($QryResult);
        return $QryResult;
    }

    private function loadAccountLedgerReportResult()
    {
        $alRptSession = new Container('AccountsLedgerRpt');
        $Repository = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\VoucherDetail');
        $QueryBuilder = $Repository->createQueryBuilder('VD');
        $arrSearchParams = array(
            'start_date' => $alRptSession->alStartDate,
            'end_date' => $alRptSession->alEndDate,
            'from_account' => $alRptSession->fromAccount,
            'to_account' => $alRptSession->toAccount,
        );
        $QryResult = $this->searchForAccountLedgerResults($arrSearchParams, $QueryBuilder)->getQuery()->getResult();
        return $QryResult;
    }

    private function fetchVoucherTypes()
    {
        $VoucherTypes = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\VoucherType')->findAll();
        $vTypes = array();
        if (count($VoucherTypes) > 0)
        {
            foreach ($VoucherTypes as $vType)
            {
                $vTypes[$vType->getId()] = $vType->getName() . ' [' . $vType->getCode() . ']';
            }
        }
        return $vTypes;
    }

    private function fetchAccountCategories()
    {

        $categories = array(
            \Account\Entity\Account::SUPER_CONTROL => 'Super Control',
            \Account\Entity\Account::CONTROL => 'Control',
            \Account\Entity\Account::DETAILED => 'Detailed',
        );
        return $categories;
    }

    private function fetchDetailedAccounts()
    {
        $Accounts = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\Account')->findAll(array('category_id' => \Account\Entity\Account::DETAILED));
        $list = array();
        if (count($Accounts) > 0)
        {
            foreach ($Accounts as $Account)
            {
                $list[$Account->getCode()] = $Account->getName() . ' [' . $Account->getCode() . ']';
            }
        }
        return $list;
    }

    private function fetchVoucherNumbers()
    {
        $Repository = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\Voucher');
        $QueryBuilder = $Repository->createQueryBuilder('V');
        $QueryBuilder->select('V, VD')
                ->innerJoin('V.VoucherDetails', 'VD', 'WITH')
                ->where('VD.status = :status')
                ->setParameter('status', \Account\Entity\VoucherDetail::OPEN);
        $Vouchers = $QueryBuilder->getQuery()->getResult();
        $vTypes = array();
        if (count($Vouchers) > 0)
        {
            foreach ($Vouchers as $Voucher)
            {
                $vTypes[$Voucher->getVoucherNumber()] = $Voucher->getVoucherNumber();
            }
        }
        return $vTypes;
    }

    private function searchResults($arrSearchParams, $QueryBuilder)
    {
//        var_dump($arrSearchParams['start_date']);
        $startDate = $arrSearchParams['start_date'];
        $endDate = $arrSearchParams['end_date'];

        if ($startDate !== null)
            $startDate->format(\Application\Library\Application::DATE_FORMAT_FOR_DB);
        if ($endDate !== null)
            $endDate->format(\Application\Library\Application::DATE_FORMAT_FOR_DB);

        $voucherNumber = $arrSearchParams['voucher_number'];
        $voucherType = $arrSearchParams['voucher_type'];
        $whereClause = '';
        $parameters = null;
        if ($startDate != "" && $endDate != '' && $voucherType != "" && $voucherNumber != "")
        {
            $whereClause = "
                V.voucher_date BETWEEN :start_date AND :end_date AND
                V.VoucherType = :voucher_type AND
                V.voucher_number = :voucher_number
                ";
            $parameters = array(
                'start_date' => $startDate,
                'end_date' => $endDate,
                'voucher_type' => $voucherType,
                'voucher_number' => $voucherNumber,
            );
        }
        elseif ($startDate != "" && $endDate != '' && $voucherType != "")
        {
            $whereClause = "
                V.voucher_date BETWEEN :start_date AND :end_date AND
                V.VoucherType = :voucher_type
                ";
            $parameters = array(
                'start_date' => $startDate,
                'end_date' => $endDate,
                'voucher_type' => $voucherType,
            );
        }
        elseif ($startDate != "" && $endDate != '' && $voucherNumber != "")
        {
            $whereClause = "
                V.voucher_date BETWEEN :start_date AND :end_date AND
                V.voucher_number = :voucher_number
                ";
            $parameters = array(
                'start_date' => $startDate,
                'end_date' => $endDate,
                'voucher_number' => $voucherNumber,
            );
        }
        elseif ($startDate != "" && $endDate != "")
        {
            $whereClause = "
                V.voucher_date BETWEEN :start_date AND :end_date
                ";
            $parameters = array(
                'start_date' => $startDate,
                'end_date' => $endDate,
            );
        }
        elseif ($voucherType != "" && $voucherNumber != "")
        {
            $whereClause = "
                V.VoucherType = :voucher_type AND
                V.voucher_number = :voucher_number
                ";
            $parameters = array(
                'voucher_type' => $voucherType,
                'voucher_number' => $voucherNumber,
            );
        }
        elseif ($startDate != "")
        {
            $whereClause = "V.voucher_date >= :voucher_date";
            $parameters = array('voucher_date' => $startDate);
        }
        elseif ($voucherType != "")
        {
            $whereClause = "V.VoucherType = :voucher_type";
            $parameters = array('voucher_type' => $voucherType);
        }
        elseif ($voucherNumber != "")
        {
            $whereClause = "V.voucher_number = :voucher_number";
            $parameters = array('voucher_number' => $voucherNumber);
        }
        if ($whereClause != '' && $parameters != null)
        {
            $QueryBuilder->select('V, VD, A')
                    ->innerJoin('V.VoucherDetails', 'VD', 'WITH')
                    ->innerJoin('VD.Account', 'A', 'WITH')
                    ->where($whereClause)
                    ->setParameters($parameters)
                    ->addOrderBy('V.voucher_date', 'ASC')
                    ->addOrderBy('V.VoucherType', 'ASC');
        }
        return $QueryBuilder;
    }

    private function searchForAccountLedgerResults($arrSearchParams, $QueryBuilder)
    {
        $startDate = $arrSearchParams['start_date'];
        $endDate = $arrSearchParams['end_date'];

        if ($startDate !== null)
            $startDate->format(\Application\Library\Application::DATE_FORMAT_FOR_DB);
        if ($endDate !== null)
            $endDate->format(\Application\Library\Application::DATE_FORMAT_FOR_DB);

        $fromAccount = $arrSearchParams['from_account'];
        $toAccount = $arrSearchParams['to_account'];
        $whereClause = '';
        $parameters = null;
        if ($startDate != "" && $endDate != '' && $fromAccount != "" && $toAccount != "")
        {
            $whereClause = "
                V.voucher_date BETWEEN :start_date AND :end_date AND
                V.Account BETWEEN :from_account AND to_account
                ";
            $parameters = array(
                'start_date' => $startDate,
                'end_date' => $endDate,
                'from_account' => $fromAccount,
                'to_account' => $toAccount,
            );
        }
        elseif ($startDate != "" && $endDate != '' && $fromAccount != "")
        {
            $whereClause = "
                V.voucher_date BETWEEN :start_date AND :end_date AND
                V.Account >= :from_account
                ";
            $parameters = array(
                'start_date' => $startDate,
                'end_date' => $endDate,
                'from_account' => $fromAccount,
            );
        }
        elseif ($startDate != "" && $endDate != '' && $toAccount != "")
        {
            $whereClause = "
                V.voucher_date BETWEEN :start_date AND :end_date AND
                V.Account >= :to_account
                ";
            $parameters = array(
                'start_date' => $startDate,
                'end_date' => $endDate,
                'to_account' => $fromAccount,
            );
        }
        elseif ($startDate != "" && $endDate != "")
        {
            $whereClause = "
                V.voucher_date BETWEEN :start_date AND :end_date
                ";
            $parameters = array(
                'start_date' => $startDate,
                'end_date' => $endDate,
            );
        }
        elseif ($startDate != "")
        {
            $whereClause = "V.voucher_date >= :voucher_date";
            $parameters = array('voucher_date' => $startDate);
        }

        if ($whereClause != '' && $parameters != null)
        {
            $QueryBuilder->select('VD, V, A')
                    ->innerJoin('VD.Voucher', 'V', 'WITH')
                    ->innerJoin('VD.Account', 'A', 'WITH')
                    ->where($whereClause)
                    ->setParameters($parameters)
                    ->addOrderBy('A.code', 'ASC')
                    ->addOrderBy('V.voucher_date', 'ASC')
                    ->addOrderBy('V.VoucherType', 'ASC')
                    ->addOrderBy('V.serial', 'ASC');
        }
        return $QueryBuilder;
    }

    private function getWkHtmltoPdfObj($url = '')
    {
        $options = array(
            'bin' => \Application\Library\Application::WK_BIN,
//            'bin' => 'wkhtmltopdf',
            'page-size' => 'A4',
            'orientation' => 'Landscape',
//            'options' => array(
//                'page-size' => 'A6',
//            ),
        );
        $Pdf = new WkHtmlToPdf($options);
//        $Pdf->addPage($url);
//        $Pdf->send();
        return $Pdf;
    }

    private function getZendCurlResponse($url, $postData = array())
    {
        $Client = new Client();
        $Client->setAdapter('Zend\Http\Client\Adapter\Curl');
        $Client->setUri($url);
        $Client->setMethod('POST');
        $Client->setParameterPOST($postData);
        $Response = $Client->send();
        if (!$Response->isSuccess())
        {
            // report failure
            $message = $Response->getStatusCode() . ': ' . $Response->getReasonPhrase();
            $Response = $this->getResponse();
            $Response->setContent($message);
            return $Response;
        }
        $body = $Response->getBody();

        $Response = $this->getResponse();
        $Response->setContent($body);

        return $Response;
    }

}
