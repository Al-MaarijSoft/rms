<?php

namespace Account\Controller;

use Account\Entity\Voucher;
use Account\Entity\VoucherDetail;
use Account\Entity\VoucherType;
use Account\Form\VoucherSearchForm;
use Administration\Entity\Company;
use Administration\Entity\Currency;
use Application\Library\Application;
use DateTime;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use DOMPDFModule\View\Model\PdfModel;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

class VoucherController extends AbstractActionController
{

    private $errorMsgs = array();
    private $debitVal = 0;
    private $creditVal = 0;
    private $recordPerPage = 25;
    protected $em;

    public function indexAction()
    {
        $vSession = new Container('voucher'); //zend-Session
        $vSession->vrDate = '';
        $vSession->vrNumber = '';
        $vSession->vrType = '';
        $vSession->accountCode = '';
        $Request = $this->getRequest();
        $ViewModel = new ViewModel();
        if ($Request->isXmlHttpRequest())
        {
            $ViewModel->setTerminal(true);
        }
        $populateData['account_code'] = $this->PopulateAccounts();
        $populateData['type'] = $this->fetchVoucherTypes();
        $Form = new VoucherSearchForm($populateData);
        $Repository = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\Voucher');
        $QueryBuilder = $Repository->createQueryBuilder('V');
        //==============================START SEARCH
        if ($Request->isPost() && $Request->getPost('submit') === 'Search')
        {
            $Form->get('voucher_number')->setAttribute('value', $Request->getPost('voucher_number'));
            $Form->get('voucher_date')->setAttribute('value', $Request->getPost('voucher_date'));
            $Form->get('voucher_type')->setAttributes(array('value' => $Request->getPost('voucher_type'), 'selected' => true));
            $Form->get('account_code')->setAttributes(array('value' => $Request->getPost('account_code'), 'selected' => true));
            $vSession->vrDate = Application::makeDateObjectForDB($Request->getPost('voucher_date'));
            $vSession->vrNumber = $Request->getPost('voucher_number');
            $vSession->vrType = $Request->getPost('voucher_type');
            $vSession->accountCode = $Request->getPost('account_code');
            $arrSearchParams = array(
                'voucher_date' => $vSession->vrDate,
                'voucher_number' => $vSession->vrNumber,
                'voucher_type' => $vSession->vrType,
                'account_code' => $vSession->accountCode,
            );
            $QueryBuilder = $this->searchResults($arrSearchParams, $QueryBuilder);
        }
        else
        {
            $QueryBuilder->select('V')
                    ->addOrderBy('V.voucher_date', 'DESC')
                    ->addOrderBy('V.VoucherType', 'ASC');
        }
        //==============================END SEARCH
        $ORMPaginator = new ORMPaginator($QueryBuilder);
        $Adapter = new DoctrineAdapter($ORMPaginator);
        $Paginator = new Paginator($Adapter);
        $Paginator->setDefaultItemCountPerPage($this->recordPerPage);
        $page = (int) $this->params()->fromQuery('page', 1);
//        $this->pageNo = $page;

        $vSession->listPageNo = $page;
        $Paginator->setCurrentPageNumber($page);
        $ViewModel->setVariable('Paginator', $Paginator);
        $ViewModel->setVariable('Form', $Form);

        return $ViewModel;
    }

    public function printPreviewAction()
    {
        $layout = $this->layout();
        $layout->setTemplate('layout/print-preview-layout.phtml');
        $vSession = new Container('voucher');
        $ViewModel = new ViewModel();
        //*****************************
        return $this->makeListForReportableView($ViewModel, $vSession);
    }

    public function pdfReportAction()
    {
        $vSession = new Container('voucher');
        $ViewModel = new PdfModel();
        $ViewModel->setOption('paperSize', 'A4');
//        $ViewModel->setOption('paperOrientation', 'landscape');
        //*****************************
        return $this->makeListForReportableView($ViewModel, $vSession);
    }

    public function addAction()
    {
        $counter = 0;
        $Request = $this->getRequest();
        $ViewModel = new ViewModel();
        if ($Request->isXmlHttpRequest())
        {
            $ViewModel->setTerminal(true);
        }
        $arrVoucherTypes = $this->fetchVoucherTypes();
        $arrCurrencies = $this->fetchCurrencies();
//        $Request = $this->getRequest();
        $selectAccounts = null;
        if ($Request->isPost())
        {
            $Voucher = new Voucher($this->getServiceLocator());
            $voucherDate = $Request->getPost('voucher_date');
//            var_dump($voucherDate);die;
            $idVoucherType = $Request->getPost('voucher_type');
            $voucherNumber = $Request->getPost('voucher_number');
            $idAccounts = $Request->getPost('account');
            $debits = $Request->getPost('debit');
            $credits = $Request->getPost('credit');
            $narrations = $Request->getPost('narration');
            $chequeDate = $Request->getPost('cheque_date');
            $chequeNumber = $Request->getPost('cheque_number');
            $idCurrency = $Request->getPost('currency');
            $exchangeRate = $Request->getPost('exchange_rate');
            if (($idVoucherType == VoucherType::CASH_PAYMENT_VOUCHER) ||
                        ($idVoucherType == VoucherType::BANK_PAYMENT_VOUCHER) ||
                        ($idVoucherType == VoucherType::BANK_TO_BANK_TRANSFER) ||
                        ($idVoucherType == VoucherType::CASH_TO_CASH_TRANSFER))
                    $this->creditVal = array_sum($debits);
            elseif ($idVoucherType == VoucherType::CASH_RECEIPT_VOUCHER || $idVoucherType == VoucherType::BANK_RECEIPT_VOUCHER)
                $this->debitVal = array_sum($credits);
            elseif ($idVoucherType == VoucherType::JOURNAL_VOUCHER)
            {
                $this->debitVal = 0;
                $this->creditVal = 0;
            }

            foreach ($idAccounts as $index => $idAccount)
            {
                $counter++;
                $arrData = array();
                $narration = $narrations[$index];
                //***** Set Debit & Credit Accordingly
                if ($this->creditVal)
                {
                    $debit = $debits[$index];
                    if ($index < 1)
                        $credit = $this->creditVal;
                    else
                        $credit = null;
                }
                elseif ($this->debitVal)
                {
                    $credit = $credits[$index];
                    if ($index < 1)
                        $debit = $this->debitVal;
                    else
                        $debit = null;
                }
                else
                {
                    $debit = $debits[$index];
                    $credit = $credits[$index];
                }
                //****** Account Populate Accordingly
                if ($index < 1)
                {
                    $selectAccounts[$index] = $this->fetchAccountsForZeroIndexElement($idVoucherType);
                }
                else
                {
                    $Accounts = $this->fetchAccountsNotByThisVoucherTypeId($idVoucherType);
                    if ($Accounts)
                    {
                        foreach ($Accounts as $Account)
                        {
                            $selectAccounts[$index][$Account->getId()] = $Account->getName() . '  [' . $Account->getCode() . ']';
                        }
                    }
                }

                //================ Set Data Again in form after Posting it
                $VoucherDetail = new VoucherDetail();
                //================ Set Name For Account START ==========
                if ($this->isValid($Request))
                {
                    //******Insert Main-Voucher Data For 
                    if ($index < 1)
                    {
                        $Voucher->exchangeArray($Request->getPost());
                        $Voucher->setSerial($this->makeLAccountSerialValue($voucherNumber));
                        $VoucherType = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\VoucherType')->find($idVoucherType);
                        if ($VoucherType)
                            $Voucher->setVoucherType($VoucherType);
                        $Currency = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Currency')->find($idCurrency);
                        if ($Currency)
                            $Voucher->setCurrency($Currency);
                        $Voucher->setVoucherDate($voucherDate);
                        if ($idVoucherType == VoucherType::BANK_PAYMENT_VOUCHER)
                            $Voucher->setChequeDate($chequeDate);
                        $this->getServiceLocator()->get('EntityManager')->persist($Voucher);
                    }
                    //***End
                    //When Voucher type will be JV then, zero index account will be empty and below condition will prevent the empty account insertion.
                    if ($idAccount != '')
                    {
                        $VoucherDetail->setVoucher($Voucher);
                        $data = array('debit' => $debit,
                            'credit' => $credit,
                            'narration' => $narration,
                        );
                        $VoucherDetail->exchangeArray($data);
                        $Account = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\Account')->find($idAccount);
                        if ($Account)
                            $VoucherDetail->setAccount($Account);
                        $this->getServiceLocator()->get('EntityManager')->persist($VoucherDetail);
                    }
                    if (count($idAccounts) === $counter)
                    {
                        $this->getServiceLocator()->get('EntityManager')->flush();
                        //===================================
                        return $this->redirect()->toRoute('voucher', array(
                                    'action' => 'index'
                        ));
                    }
                }
            }
        }
        $vars = array(
            'voucherTypes' => $arrVoucherTypes,
            'currencies' => $arrCurrencies,
            'selectAccounts' => $selectAccounts,
            'errorMsgs' => $this->errorMsgs,
        );
        $ViewModel->setVariables($vars);
        return $ViewModel;
    }

    public function editAction()
    {
        $counter = 0;
        $arrData = null;
        $voucherDetailIds = array();
        $Request = $this->getRequest();
        $ViewModel = new ViewModel();
        if ($Request->isXmlHttpRequest())
        {
            $ViewModel->setTerminal(true);
        }
        $id = (int) $this->params()->fromRoute('id', null);
        if (null === $id)
        {
            return $this->redirect()->toRoute('voucher', array(
                        'action' => 'add'
            ));
        }
        $Voucher = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\Voucher')->find($id);
        if (count($Voucher))
        {
            $arrVoucherTypes = $this->fetchVoucherTypes();
            $arrCurrencies = $this->fetchCurrencies();
            $selectAccounts = null;
            $_POST['voucher_date'] = $Voucher->getVoucherDate()->format(Application::DATE_FORMAT);
            $_POST['voucher_type'] = $Voucher->getVoucherType()->getId();
            $_POST['voucher_number'] = $Voucher->getVoucherNumber();
            $_POST['currency'] = $Voucher->getCurrency()->getId();
            $_POST['cheque_date'] = is_object($Voucher->getChequeDate()) ? $Voucher->getChequeDate()->format(Application::DATE_FORMAT) : $Voucher->getChequeDate();
            $_POST['cheque_number'] = $Voucher->getChequeNumber();
            $_POST['exchange_rate'] = $Voucher->getExchangeRate();

            $VoucherDetails = $Voucher->getVoucherDetails();
//            $Request = $this->getRequest();
            if (count($VoucherDetails))
            {
                foreach ($VoucherDetails as $index => $VD)
                {
                    $voucherDetailIds[$index] = $VD->getId();
                    $_POST['account'][$index] = $VD->getAccount()->getId();
                    $_POST['narration'][$index] = $VD->getNarration();
                    $_POST['debit'][$index] = $VD->getDebit();
                    $_POST['credit'][$index] = $VD->getCredit();
                    $selectAccounts[$index] = $this->populateAccountSelectBoxesAccordingly($index, $Voucher->getVoucherType()->getId());
                }
            }
            else
                die('Child-Table has not Parent-Table id');
            $Request = $this->getRequest();
            if ($Request->isPost())
            {
//              $Voucher = new \Account\Entity\Voucher();
                $selectAccounts = null;
                $voucherDate = $Request->getPost('voucher_date');
//                $idVoucherType = $Request->getPost('voucher_type');
                $idVoucherType = $Voucher->getVoucherType()->getId();
                $voucherNumber = $Request->getPost('voucher_number');
                $idAccounts = $Request->getPost('account');
                $debits = $Request->getPost('debit');
                $credits = $Request->getPost('credit');
                $narrations = $Request->getPost('narration');
                $chequeDate = $Request->getPost('cheque_date');
                $chequeNumber = $Request->getPost('cheque_number');
//                $idCurrency = $Request->getPost('currency');
                $idCurrency = $Voucher->getCurrency()->getId();
                $exchangeRate = $Request->getPost('exchange_rate');
                if (($idVoucherType == VoucherType::CASH_PAYMENT_VOUCHER) ||
                        ($idVoucherType == VoucherType::BANK_PAYMENT_VOUCHER) ||
                        ($idVoucherType == VoucherType::BANK_TO_BANK_TRANSFER) ||
                        ($idVoucherType == VoucherType::CASH_TO_CASH_TRANSFER))
                    $this->creditVal = array_sum($debits);
                elseif ($idVoucherType == VoucherType::CASH_RECEIPT_VOUCHER || $idVoucherType == VoucherType::BANK_RECEIPT_VOUCHER)
                    $this->debitVal = array_sum($credits);
                elseif ($idVoucherType == VoucherType::JOURNAL_VOUCHER)
                {
                    $this->debitVal = 0;
                    $this->creditVal = 0;
                }

                foreach ($idAccounts as $index => $idAccount)
                {
                    $counter++;
                    $arrData = array();
                    unset($voucherDetailIds[$index]);
                    $VoucherDetail = $VoucherDetails[$index];
                    $narration = $narrations[$index];
                    //***** Set Debit & Credit Accordingly
                    if ($this->creditVal)
                    {
                        $debit = $debits[$index];
                        if ($index < 1)
                            $credit = $this->creditVal;
                        else
                            $credit = null;
                    }
                    elseif ($this->debitVal)
                    {
                        $credit = $credits[$index];
                        if ($index < 1)
                            $debit = $this->debitVal;
                        else
                            $debit = null;
                    }
                    else
                    {
                        $debit = $debits[$index];
                        $credit = $credits[$index];
                    }
                    //****** Account Populate Accordingly
                    if ($index < 1)
                    {
                        $selectAccounts[$index] = $this->fetchAccountsForZeroIndexElement($idVoucherType);
                    }
                    else
                    {
                        $Accounts = $this->fetchAccountsNotByThisVoucherTypeId($idVoucherType);
                        if ($Accounts)
                        {
                            foreach ($Accounts as $Account)
                            {
                                $selectAccounts[$index][$Account->getId()] = $Account->getName() . '  [' . $Account->getCode() . ']';
                            }
                        }
                    }

                    //================ Set Data Again in form after Posting it
                    //                    $VoucherDetail = new \Account\Entity\VoucherDetail();
                    //================ Set Name For Account START ==========
                    if ($this->isValid($Request))
                    {
                        //******Insert Main-Voucher Data For 
                        if ($index < 1)
                        {
                            $Voucher->exchangeArray($Request->getPost());
                            $Voucher->setSerial($this->makeLAccountSerialValue($voucherNumber));
                            $VoucherType = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\VoucherType')->find($idVoucherType);
                            if ($VoucherType)
                                $Voucher->setVoucherType($VoucherType);
                            $Currency = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Currency')->find($idCurrency);
                            if ($Currency)
                                $Voucher->setCurrency($Currency);
                            $Voucher->setVoucherDate($voucherDate);
                            if ($idVoucherType == VoucherType::BANK_PAYMENT_VOUCHER)
                                $Voucher->setChequeDate($chequeDate);
                            //===============
                            $userAuth = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
                            $User = $userAuth->getIdentity();
                            $Voucher->setModifier($User);
                            $Voucher->setModificationDate("now");
                            $this->getServiceLocator()->get('EntityManager')->persist($Voucher);
                        }
//***End
                        if (!is_object($VoucherDetail))
                            $VoucherDetail = new VoucherDetail();

                        $VoucherDetail->setVoucher($Voucher);
                        $data = array('debit' => $debit,
                            'credit' => $credit,
                            'narration' => $narration,
                        );
                        //Zero index only inserted in detail voucher table, when Voucher Type is not Journal_Voucher .
                        if ($index < 1 && $idVoucherType != VoucherType::JOURNAL_VOUCHER)
                        {
                            $VoucherDetail->exchangeArray($data);
                            $Account = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\Account')->find($idAccount);
                            if ($Account)
                                $VoucherDetail->setAccount($Account);
                            $this->getServiceLocator()->get('EntityManager')->persist($VoucherDetail);
                        }
                        //If Voucher-Detail rows deleted by user on front side then remove those unlink rows from table also.
                        if (count($idAccounts) < count($VoucherDetails))
                        {
                            foreach ($voucherDetailIds as $voucherDetailId)
                            {
                                $VoucherDetailRemovable = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\VoucherDetail')->find($voucherDetailId);
                                $this->getServiceLocator()->get('EntityManager')->remove($VoucherDetailRemovable);
                            }
                        }
                        if (count($idAccounts) === $counter)
                        {
                            $this->getServiceLocator()->get('EntityManager')->flush();
                            return $this->redirect()->toRoute('voucher', array(
                                        'action' => 'index'
                            ));
                        }
                    }
                }
            }
        }
        else
        {
//            return $this->redirect()->toRoute('voucher', array(
//                        'action' => 'add'
//            ));
            echo $this->errorMsgs['updateError'] = 'There is some error during Update your record.';
        }
        $vars = array(
            'id' => $id,
            'voucherTypes' => $arrVoucherTypes,
            'currencies' => $arrCurrencies,
            'selectAccounts' => $selectAccounts,
            'errorMsgs' => $this->errorMsgs,
        );
        $ViewModel->setVariables($vars);
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
        $Voucher = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\Voucher')->findBy(array('id' => $id));
//        echo $Voucher[0]->getVoucherNumber();exit;
        $this->getServiceLocator()->get('EntityManager')->remove($Voucher[0]);
        $this->getServiceLocator()->get('EntityManager')->flush();
        //****************End Delete Stuff
        //==============After deletion Redirect to Index
        return $this->redirect()->toRoute('voucher', array(
                    'action' => 'index',
        ));
        //=================
        return $ViewModel;
    }

    public function getExchangeRateAction()
    {
//        $View = new \Zend\View\Model\JsonModel();
        $idCurrency = (int) $this->params()->fromQuery('currency', 0);
        if ($idCurrency)
        {
            $Currency = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Currency')->find($idCurrency);
            if ($Currency)
            {
                $selectedCurrencyCode = $Currency->getCode();
                if ($Currency->getIsLocal())
                {
                    $jsonStr = json_encode(array('is_local' => 1, 'rate' => Application::LOCAL_RATE));
                    echo $jsonStr;
                }
                else
                {
                    $CurrencyLocal = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Currency')->findBy(array('is_local' => Currency::YES));
                    $localCurrencyCode = $CurrencyLocal[0]->getCode();
//                    $loginUrl = 'http://rate-exchange.appspot.com/currency?from=USD&to=PKR';
                    $loginUrl = 'http://rate-exchange.appspot.com/currency?from=' . $selectedCurrencyCode . '&to=' . $localCurrencyCode;

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_URL, $loginUrl);
                    $result = curl_exec($ch);
                    curl_close($ch);
                    $responseArr = (array) json_decode($result);
                    $newArrayData = array_merge($responseArr, array('is_local' => 0));
                    $jsonStr = json_encode($newArrayData);
                    echo $jsonStr;
                }
            }
        }
        exit;
//        return $View;
    }

    private function makeLAccountSerialValue($postedVrNumber = "ABC-1")
    {
        $serial = 0;
        $arrVrNm = explode('-', $postedVrNumber);
        $serial = (int) $arrVrNm[1];
        return $serial;
    }

    private function populateAccountSelectBoxesAccordingly($index, $idVoucherType)
    {
        if ($index < 1)
        {
            $selectedAccounts = $this->fetchAccountsForZeroIndexElement($idVoucherType);
        }
        else
        {
            $Accounts = $this->fetchAccountsNotByThisVoucherTypeId($idVoucherType);
            if ($Accounts)
            {
                foreach ($Accounts as $Account)
                {
                    $selectedAccounts[$Account->getId()] = $Account->getName() . '  [' . $Account->getCode() . ']';
                }
            }
        }
        return $selectedAccounts;
    }

    private function isValid(Request $Request)
    {
        $isValid = true;
        $voucherDate = $Request->getPost('voucher_date');
        $idVouchertype = $Request->getPost('voucher_type');
        $voucherNumber = $Request->getPost('voucher_number');
        $chequeDate = $Request->getPost('cheque_date');
        $chequeNumber = $Request->getPost('cheque_number');
        $idCurrency = $Request->getPost('currency');
        $exchangeRate = $Request->getPost('exchange_rate');
        $idAccounts = $Request->getPost('account');
        $debits = $Request->getPost('debit');
        $credits = $Request->getPost('credit');
        $narrations = $Request->getPost('narration');
        if ($idVouchertype == VoucherType::JOURNAL_VOUCHER)
        {
            $debitsWithoutZeroIndex = $debits;
            $creditsWithoutZeroIndex = $credits;
            unset($debitsWithoutZeroIndex[0]);
            unset($creditsWithoutZeroIndex[0]);
        }
        if (isset($voucherDate) && $voucherDate == '')
        {
            $this->errorMsgs['voucher_date'] = 'Please enter Voucher Date';
            $isValid = false;
        }
        if (isset($idVouchertype) && $idVouchertype == '')
        {
            $this->errorMsgs['voucher_type'] = 'Please select Voucher Type';
            $isValid = false;
        }
        if (isset($voucherNumber) && $voucherNumber == '')
        {
            $this->errorMsgs['voucher_number'] = 'Please select Voucher Number';
            $isValid = false;
        }
        if (($idVouchertype == VoucherType::BANK_PAYMENT_VOUCHER || $idVouchertype == VoucherType::BANK_TO_BANK_TRANSFER) && (isset($chequeDate) && $chequeDate == ''))
        {
            $this->errorMsgs['cheque_date'] = 'Please enter Cheque Date';
            $isValid = false;
        }
        if (($idVouchertype == VoucherType::BANK_PAYMENT_VOUCHER || $idVouchertype == VoucherType::BANK_TO_BANK_TRANSFER) && (isset($chequeNumber) && $chequeNumber == ''))
        {
            $this->errorMsgs['cheque_number'] = 'Please enter Cheque Number';
            $isValid = false;
        }
        if (isset($idCurrency) && $idCurrency == '')
        {
            $this->errorMsgs['currency'] = 'Please select Voucher Currency';
            $isValid = false;
        }
        if (isset($exchangeRate) && $exchangeRate == '')
        {
            $this->errorMsgs['exchange_rate'] = 'Please enter Exchange Rate';
            $isValid = false;
        }

        if ($idVouchertype != VoucherType::JOURNAL_VOUCHER)
        {
            if ($this->creditVal && $this->creditVal != array_sum($debits))
            {
                $this->errorMsgs['credit'][0] = 'Debit and Credit should be equal.';
                $isValid = false;
            }
            elseif ($this->debitVal && $this->debitVal != array_sum($credits))
            {
                $this->errorMsgs['debit'][0] = 'Debit and Credit should be equal.';
                $isValid = false;
            }
            elseif ($this->debitVal === 0 && $this->creditVal === 0)
            {
                $this->errorMsgs['debit'][0] = 'Debit and Credit both can not be zero.';
                $isValid = false;
            }
        }
        else // For Journal Voucher
        {
            if (intval(array_sum($debitsWithoutZeroIndex)) !== intval(array_sum($creditsWithoutZeroIndex)))
            {
                $this->errorMsgs['totalAmount'] = 'Debit and Credit amount should be equal.';
                $isValid = false;
            }
        }
        if (count($idAccounts))
        {
            foreach ($idAccounts as $index => $idVal)
            {
                $debit = @$debits[$index];
                $credit = @$credits[$index];
                $rowCountWhenJV = (int) (count($idAccounts) - (1));
                if ($index < 1)
                {
                    if ($idVal == '' && $idVouchertype != VoucherType::JOURNAL_VOUCHER)
                    {
                        $msg = 'Please select account';
                        if ($idVouchertype == VoucherType::CASH_PAYMENT_VOUCHER)
                            $msg = 'Please select Cash Credit Account';
                        elseif ($idVouchertype == VoucherType::CASH_RECEIPT_VOUCHER)
                            $msg = 'Please select Cash Debit Account';
                        elseif ($idVouchertype == VoucherType::BANK_PAYMENT_VOUCHER)
                            $msg = 'Please select Bank Credit Account';
                        elseif ($idVouchertype == VoucherType::BANK_RECEIPT_VOUCHER)
                            $msg = 'Please select Bank Debit Account';
                        elseif ($idVouchertype == VoucherType::TRANSFER)
                            $msg = 'Please select Debit Account';

                        $this->errorMsgs['account'][$index] = $msg;
                        $isValid = false;
                    }
                }
                else
                {
                    if ($idVal == '')
                    {
                        $this->errorMsgs['account'][$index] = 'Please select Account at row number ' . $index;
                        $isValid = false;
                    }
                    if ($idVouchertype != VoucherType::JOURNAL_VOUCHER)
                    {
                        if ($this->creditVal && $debit == '')
                        {
                            $this->errorMsgs['debit'][$index] = 'Please enter Debit Amount at row number ' . $index;
                            $isValid = false;
                        }
                        elseif ($this->debitVal && $credit == '')
                        {
                            $this->errorMsgs['credit'][$index] = 'Please enter Credit Amount at row number ' . $index;
                            $isValid = false;
                        }
                        elseif (!$this->debitVal && !$this->creditVal)
                        {
                            $this->errorMsgs['credit'][$index] = 'Please enter Credit Amount at row number ' . $index;
                            $this->errorMsgs['debit'][$index] = 'Please enter Debit Amount at row number ' . $index;
                        }
                    }
                    else // For Journal-Voucher
                    {
                        if (($debit == '' || $debit == 0) && ($credit == '' || $credit == 0))
                        {
                            $this->errorMsgs['credit'][$index] = 'Please enter Amount in Debit or Credit at row number ' . $index;
                            $isValid = false;
                        }
                        elseif ($debit == $credit)
                        {
                            $this->errorMsgs['credit'][$index] = 'Please enter Zero Amount in Debit or Credit at row number ' . $index;
                            $isValid = false;
                        }
//                        var_dump(($rowCountWhenJV % 2));exit;
                    }
                }
            }
            if (($rowCountWhenJV % 2) && $idVouchertype == VoucherType::JOURNAL_VOUCHER)
            {
                $this->errorMsgs['others'] = 'You are breaking double entry system rule' . $index;
                $isValid = false;
            }
        }
        return $isValid;
    }

    private function fetchCurrencies()
    {
        $arrSelectData = array();
        $Currencies = $this->getServiceLocator()->get('EntityManager')->getRepository('Administration\Entity\Currency')->findAll();
        foreach ($Currencies as $Currency)
        {
            $arrSelectData[$Currency->getId()] = $Currency->getName();
        }
        return $arrSelectData;
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

    public function generateVocuherNoAction()
    {
        $serial = 0;
        $increment = 1;
        $code = '';
        $SERIAL_LENGTH = 3;
        $VrNo = 0;
        $FinancialYear = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\FinancialYear')->findBy(array('is_current' => \Account\Entity\FinancialYear::YES));

        if (isset($_POST['voucherType']))
        {
            $idVoucherType = $_POST['voucherType'];
        }
        $emConfig = $this->getServiceLocator()->get('EntityManager')->getConfiguration();
        $emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
        $emConfig->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
        $emConfig->addCustomDatetimeFunction('DAY', 'DoctrineExtensions\Query\Mysql\Day');

        $qb = $this->getServiceLocator()->get('EntityManager')->createQueryBuilder();
        $queryBuilder = $this->getServiceLocator()->get('EntityManager')->createQueryBuilder();
        $result = $qb->select('MAX(v.serial) max_serial')
                ->from('Account\Entity\Voucher', 'v')
//                ->where('v.VoucherType = :idVoucherType AND YEAR(v.voucher_date) = YEAR(CURRENT_TIMESTAMP())')
                ->where('v.VoucherType = :idVoucherType AND (v.voucher_date BETWEEN :FinancialYearStartDate AND :FinancialYearEndDate)')
                ->setParameter('idVoucherType', $idVoucherType)
                ->setParameter('FinancialYearStartDate', $FinancialYear[0]->getStartDate()->format(Application::DATE_FORMAT_FOR_DB))
                ->setParameter('FinancialYearEndDate', $FinancialYear[0]->getEndDate()->format(Application::DATE_FORMAT_FOR_DB))
                ->getQuery()
                ->getOneOrNullResult();
        if ($result['max_serial'])
            $VrNo = $result['max_serial'] + 1;
        else
            $VrNo = 1;

        $result = $queryBuilder->select('v.code')
                ->from('Account\Entity\VoucherType', 'v')
                ->where('v.id = :identifier')
                ->setParameter('identifier', $idVoucherType)
                ->getQuery()
                ->getOneOrNullResult();
        if ($result['code'])
            $code = $result['code'];
        else
            $code = '';
        $VrNoWithPad = str_pad($VrNo, $SERIAL_LENGTH, "0", STR_PAD_LEFT);
        echo $code . '-' . $VrNoWithPad;
        exit;
    }

    private function PopulateAccounts()
    {
        $accountsArr = array();
        $criteria = array('Company' => Company::DEFAULTCOMPANY);
        $orderBy = array('code' => 'ASC');
        $Accounts = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\Account')->findBy($criteria, $orderBy);
        foreach ($Accounts as $Account)
        {
            $accountsArr[$Account->getId()] = $Account->getName() . '  [' . $Account->getCode() . ']';
        }
        return $accountsArr;
    }

    public function populateAccountSelectBoxesAction()
    {
        $accountsArr = array();
        $idVoucherType = (int) $this->params()->fromRoute('id', null);
        $VoucherType = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\VoucherType')->find($idVoucherType);
        $vrBehaviour = $VoucherType->getBehaviour();
        $AccountType = $VoucherType->getAccountType();
        $idAccountType = $AccountType->getId();
        if ($vrBehaviour === \Account\Entity\VoucherType::JOURNAL)
        {
            //****** for Grid zeroIndexAccount SelectBox
//            $Accounts = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\Account')->findBy(array('AccountType' => $idAccountType));
            $accountsArr['zeroIndexAccount'] = array();
            //****** for Grid SelectBoxes
            $queryBuilder = $this->getServiceLocator()->get('EntityManager')->createQueryBuilder();
            $Accounts = $queryBuilder->select('a')
                            ->from('Account\Entity\Account', 'a')
                            ->where('a.AccountType != :cash')
                            ->andWhere('a.AccountType != :bank')
                            ->andWhere('a.category = :detailed')
                            ->setParameter('cash', \Account\Entity\AccountType::CASH)
                            ->setParameter('bank', \Account\Entity\AccountType::BANK)
                            ->setParameter('detailed', \Account\Entity\Account::DETAILED)
                            ->getQuery()->getResult();
//            $Accounts = $this->fetchAccountsNotByThisVoucherTypeId($idVoucherType);
            foreach ($Accounts as $Account)
            {
                $accountsArr['gridSelectBoxAccount'][$Account->getId()] = $Account->getName() . '  [' . $Account->getCode() . ']';
            }
        }
        else if ($vrBehaviour === \Account\Entity\VoucherType::TRANSFER)
        {
            //****** for Grid zeroIndexAccount SelectBox
            $Accounts = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\Account')->findBy(array('AccountType' => $idAccountType));
//            $accountsArr['zeroIndexAccount'] = array();
            foreach ($Accounts as $Account)
            {
                $accountsArr['zeroIndexAccount'][$Account->getId()] = $Account->getName() . '  [' . $Account->getCode() . ']';
                $accountsArr['gridSelectBoxAccount'][$Account->getId()] = $Account->getName() . '  [' . $Account->getCode() . ']';
            }
//            //****** for Grid SelectBoxes
//            $queryBuilder = $this->getServiceLocator()->get('EntityManager')->createQueryBuilder();
//            $Accounts = $queryBuilder->select('a')
//                            ->from('Account\Entity\Account', 'a')
//                            ->where('a.AccountType != :cash')
//                            ->andWhere('a.AccountType != :bank')
//                            ->andWhere('a.category = :detailed')
//                            ->setParameter('cash', \Account\Entity\AccountType::CASH)
//                            ->setParameter('bank', \Account\Entity\AccountType::BANK)
//                            ->setParameter('detailed', \Account\Entity\Account::DETAILED)
//                            ->getQuery()->getResult();
////            $Accounts = $this->fetchAccountsNotByThisVoucherTypeId($idVoucherType);
//            foreach ($Accounts as $Account)
//            {
//                $accountsArr['gridSelectBoxAccount'][$Account->getId()] = $Account->getName() . '  [' . $Account->getCode() . ']';
//            }
        }
        else
        {
            //****** for Grid zeroIndexAccount SelectBox
            $Accounts = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\Account')->findBy(array('AccountType' => $idAccountType));
            foreach ($Accounts as $Account)
            {
                $accountsArr['zeroIndexAccount'][$Account->getId()] = $Account->getName() . '  [' . $Account->getCode() . ']';
            }
            //****** for Grid SelectBoxes
            $Accounts = $this->fetchAccountsNotByThisVoucherTypeId($idVoucherType);
            foreach ($Accounts as $Account)
            {
                $accountsArr['gridSelectBoxAccount'][$Account->getId()] = $Account->getName() . '  [' . $Account->getCode() . ']';
            }
        }
        echo json_encode($accountsArr);
        exit;
    }

    private function fetchAccountsForZeroIndexElement($idVoucherType)
    {
        $accountsArr = array();
        $VoucherType = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\VoucherType')->find($idVoucherType);
        if ($VoucherType)
        {
            $AccountType = $VoucherType->getAccountType();
            $idAccountType = $AccountType->getId();
//****** for Grid zeroIndexAccount SelectBox
            $Accounts = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\Account')->findBy(array('AccountType' => $idAccountType));
            foreach ($Accounts as $Account)
            {
                $accountsArr[$Account->getId()] = $Account->getName() . '  [' . $Account->getCode() . ']';
            }
        }
        return $accountsArr;
    }

    private function fetchNameForVocuher($idVoucherType)
    {
        $accountsArr = array();
        $VoucherType = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\VoucherType')->find($idVoucherType);
        $AccountType = $VoucherType->getAccountType();
        $idAccountType = $AccountType->getId();

        $Accounts = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\Account')->findBy(array('AccountType' => $idAccountType));
        if (count($Accounts) > 0)
        {
            foreach ($Accounts as $Account)
            {
                $accountsArr[$Account->getId()] = $Account->getName();
            }
        }
        return $accountsArr;
    }

    private function fetchAccountsNotByThisVoucherTypeId($idVoucherType)
    {
        $Accounts = null;
        $VoucherType = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\VoucherType')->find($idVoucherType);
        if ($VoucherType)
        {
            $AccountType = $VoucherType->getAccountType();
            $idAccountType = $AccountType->getId();

            $queryBuilder = $this->getServiceLocator()->get('EntityManager')->createQueryBuilder();
            $Accounts = $queryBuilder->select('a')
                            ->from('Account\Entity\Account', 'a')
//                            ->where('a.AccountType != :accountTypeId')
                            ->where('a.AccountType != :accountTypeId')
                            ->setParameter('accountTypeId', $idAccountType)
                            ->getQuery()->getResult();
        }
        return $Accounts;
    }

    private function searchResults($arrSearchParams, $QueryBuilder)
    {
        $voucherDate = $arrSearchParams['voucher_date'];
        $voucherNumber = $arrSearchParams['voucher_number'];
        $voucherType = $arrSearchParams['voucher_type'];
        $idAccount = $arrSearchParams['account_code'];
        $whereClause = '';
        $parameters = null;
        if ($voucherDate != "" && $voucherType != "" && $voucherNumber != "" && $idAccount != '')
        {
            $whereClause = "
                V.voucher_date= :voucher_date AND
                V.VoucherType = :voucher_type AND
                V.voucher_number = :voucher_number AND
                VD.Account = :Account
                ";
            $parameters = array(
                'voucher_date' => $voucherDate,
                'voucher_type' => $voucherType,
                'voucher_number' => $voucherNumber,
                'Account' => $idAccount,
            );
        }
        elseif ($voucherDate != "" && $voucherType != "" && $voucherNumber != "")
        {
            $whereClause = "
                V.voucher_date= :voucher_date AND
                V.VoucherType = :voucher_type AND
                V.voucher_number = :voucher_number
                ";
            $parameters = array(
                'voucher_date' => $voucherDate,
                'voucher_type' => $voucherType,
                'voucher_number' => $voucherNumber,
            );
        }
        elseif ($voucherDate != "" && $voucherType != "")
        {
            $whereClause = "
                V.voucher_date= :voucher_date AND
                V.VoucherType = :voucher_type
                ";
            $parameters = array(
                'voucher_date' => $voucherDate,
                'voucher_type' => $voucherType,
            );
        }
        elseif ($voucherDate != "" && $voucherNumber != "")
        {
            $whereClause = "
                V.voucher_date= :voucher_date AND
                V.voucher_number = :voucher_number
                ";
            $parameters = array(
                'voucher_date' => $voucherDate,
                'voucher_number' => $voucherNumber,
            );
        }
        elseif ($voucherDate != "" && $idAccount != "")
        {
            $whereClause = "
                V.voucher_date= :voucher_date AND
                VD.Account = :Account
                ";
            $parameters = array(
                'voucher_date' => $voucherDate,
                'Account' => $idAccount,
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
        elseif ($voucherType != "" && $idAccount != "")
        {
            $whereClause = "
                V.VoucherType = :voucher_type AND
                VD.Account = :Account
                ";
            $parameters = array(
                'voucher_type' => $voucherType,
                'Account' => $idAccount,
            );
        }
        elseif ($voucherNumber != "" && $idAccount != "")
        {
            $whereClause = "
                V.voucher_number = :voucher_number AND
                VD.Account = :Account
                ";
            $parameters = array(
                'voucher_number' => $voucherNumber,
                'Account' => $idAccount,
            );
        }
        elseif ($voucherDate != "")
        {
            $whereClause = "V.voucher_date = :voucher_date";
            $parameters = array('voucher_date' => $voucherDate);
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
        elseif ($idAccount != "")
        {
            $whereClause = "VD.Account = :Account";
            $parameters = array('Account' => $idAccount);
        }
        if ($whereClause != '' && $parameters != null)
        {
            $QueryBuilder->select('V, VD, A')
                    ->innerJoin('V.VoucherDetails', 'VD', 'WITH')
                    ->innerJoin('VD.Account', 'A', 'WITH')
                    ->where($whereClause)
                    ->setParameters($parameters)
                    ->addOrderBy('V.voucher_date', 'DESC')
                    ->addOrderBy('V.VoucherType', 'ASC');
        }
        return $QueryBuilder;
    }

    private function makeListForReportableView($ViewModel, $vSession)
    {
        $Repository = $this->getServiceLocator()->get('EntityManager')->getRepository('Account\Entity\Voucher');
        $QueryBuilder = $Repository->createQueryBuilder('V');
        $arrSearchParams = array(
            'voucher_date' => $vSession->voucherDate,
            'voucher_number' => $vSession->voucherNumber,
            'voucher_type' => $vSession->voucherType,
            'account_code' => $vSession->accountCode,
        );
        $QueryBuilder = $this->searchResults($arrSearchParams, $QueryBuilder);
        $QueryBuilder->addOrderBy('V.voucher_date', 'DESC')
                ->addOrderBy('V.VoucherType', 'ASC');

        $ORMPaginator = new ORMPaginator($QueryBuilder);
        $Adapter = new DoctrineAdapter($ORMPaginator);
        $Paginator = new Paginator($Adapter);
        $Paginator->setDefaultItemCountPerPage($this->recordPerPage);
        $page = $vSession->listPageNo;
        $Paginator->setCurrentPageNumber($page);
        $ViewModel->setVariable('Paginator', $Paginator);
        return $ViewModel;
    }

}

