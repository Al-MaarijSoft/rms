<?php

namespace Reporting\Form;

use Zend\Form\Form;

class VoucherReportingForm extends Form
{

    public function __construct($arrSelectData = array())
    {
        // we want to ignore the name passed
        parent::__construct('voucherReporting');
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
            'name' => 'voucher_type',
            'options' => array(
                'label' => 'Voucher Type:',
                'empty_option' => 'Select type',
                'value_options' => @$arrSelectData['type'],
            ),
        ));$this->add(array(
            'type' => 'Select',
            'name' => 'voucher_number',
            'options' => array(
                'label' => 'Voucher Number:',
                'empty_option' => 'Select Vr Number',
                'value_options' => @$arrSelectData['vrNumber'],
            ),
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
            'name' => 'voucherSimpleReport',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Simple Report',
                'id' => 'voucherSimpleReport',
            ),
        ));
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