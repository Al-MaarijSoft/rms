<?php

namespace Reporting\Form;

use Zend\Form\Form;

class TrailBalanceReportingForm extends Form
{

    public function __construct($arrSelectData = array())
    {
        // we want to ignore the name passed
        parent::__construct('AccountLedgerReporting');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'start_date',
            'options' => array(
                'label' => 'Start Date:',
            ),
//            'attributes' => array('readonly', true),
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'end_date',
            'options' => array(
                'label' => 'End Date:',
            ),
//            'attributes' => array('readonly', true),
        ));

        $this->add(array(
            'type' => 'Select',
            'name' => 'acc_categories',
            'options' => array(
                'label' => 'Accounts Category:',
                'empty_option' => 'Select Accounts Category',
                'value_options' => @$arrSelectData['acc_categories'],
            ),
        ));
        $this->add(array(
            'type' => 'Select',
            'name' => 'acc_class',
            'options' => array(
                'label' => 'Accounts Class:',
                'empty_option' => 'Select Accounts Class',
                'value_options' => @$arrSelectData['acc_class'],
            ),
        ));

        $this->add(array(
            'type' => 'Select',
            'name' => 'from_account',
            'options' => array(
                'label' => 'Accounts:',
                'empty_option' => 'Select Accounts',
                'value_options' => @$arrSelectData['account'],
            ),
        ));
        
        $this->add(array(
            'type' => 'Select',
            'name' => 'from_account',
            'options' => array(
                'label' => 'Accounts:',
                'empty_option' => 'Select Accounts',
                'value_options' => @$arrSelectData['account'],
            ),
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'without_opening',
            'attributes' => array(
                'id' => 'without_opening',
            ),
            'options' => array(
                'label' => 'Status:',
                'value_options' => array(
                    1 => 'Yes',
                    0 => 'No',
                ),
            )
        ));
//        $this->add(array(
//            'type' => 'Select',
//            'name' => 'account_code',
//            'options' => array(
//                'label' => 'Account Code:',
//                'empty_option' => 'Select Account Code',
//                'value_options' => @$arrSelectData['account_code'],
//            ),
////            'attributes' => array('id'=>'account_code'),
//        ));

        $this->add(array(
            'name' => 'voucherPdfReport',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Pdf Report',
                'id' => 'voucherPdfReport',
            ),
        ));
    }

}