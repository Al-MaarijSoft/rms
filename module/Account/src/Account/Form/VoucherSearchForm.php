<?php

namespace Account\Form;

use Zend\Form\Form;
use Account\Entity\Account;

class VoucherSearchForm extends Form
{

    public function __construct($arrSelectData = array())
    {
        // we want to ignore the name passed
        parent::__construct('voucherSearch');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'voucher_date',
            'options' => array(
                'label' => 'Voucher Date:',
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
        ));

//        $this->add(array(
//            'type' => 'Select',
//            'name' => 'voucher_number',
//            'options' => array(
//                'label' => 'Voucher Number:',
//                'empty_option' => 'Select Voucher Number',
//                'value_options' => @$arrSelectData['branch'],
//            ),
//        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'voucher_number',
            'options' => array(
                'label' => 'Voucher Number:',
            ),
//            'attributes' => array('readonly', true),
        ));

        $this->add(array(
            'type' => 'Select',
            'name' => 'account_code',
            'options' => array(
                'label' => 'Account Code:',
                'empty_option' => 'Select Account Code',
                'value_options' => @$arrSelectData['account_code'],
            ),
//            'attributes' => array('id'=>'account_code'),
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Search',
                'id' => 'submitbutton',
            ),
        ));
    }

}