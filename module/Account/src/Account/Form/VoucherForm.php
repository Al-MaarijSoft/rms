<?php

namespace Account\Form;

use Zend\Form\Form,
    Zend\Form\Element;

class VoucherForm extends Form
{

    public function __construct($arrSelectData = array())
    {
        parent::__construct('Voucher');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

//        $this->add(array(
//            'name' => 'voucher_date',
//            'type' => 'text',
//            'options' => array(
//                'label' => 'Voucher Date:',
//                'autocomplete' => 'off',
//            )
//        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'voucher_type',
            'options' => array(
                'label' => 'Voucher Type:',
                'empty_option' => 'Select Type',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'account[0]',
            'options' => array(
                'label' => 'Account Name:',
                'empty_option' => 'Select Account',
            ),
            'attributes' => array('id' => 'account-0',),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'currency',
            'options' => array(
                'label' => 'Currencies:',
                'empty_option' => 'Select Currency',
                'value_options' => @$arrSelectData['Currency'],
            ),
        ));
        /////////////////////////////////////////Text Feilds and Text Areas here
        $this->add(array(
            'name' => 'voucher_number',
            'attributes' => array(
                'type' => 'text',
                'class' => 'eidt-clien-txt',
                'readonly' => 'readonly',
            ),
            'options' => array(
                'label' => "Voucher Number:",
                'required' => false,
            ),
        ));
        $this->add(array(
            'name' => 'narration',
            'attributes' => array(
                'type' => 'text',
                'class' => 'eidt-clien-txt',
            ),
            'options' => array(
                'label' => "Narration:",
                'required' => false,
            ),
        ));
        $this->add(array(
            'name' => 'debit',
            'attributes' => array(
                'type' => 'text',
                'class' => 'eidt-clien-txt',
            ),
            'options' => array(
                'label' => "Debit:",
                'required' => false,
            ),
        ));
        $this->add(array(
            'name' => 'credit',
            'attributes' => array(
                'type' => 'text',
                'class' => 'eidt-clien-txt',
            ),
            'options' => array(
                'label' => "Credit:",
                'required' => false,
            ),
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'cheque_date',
            'attributes' => array(
                'type' => 'text',
                'class' => 'eidt-clien-txt',
                'disabled' => 'disabled',
            ),
            'options' => array(
                'label' => "Cheque Date:",
                'required' => false,
            ),
        ));
        $this->add(array(
            'name' => 'cheque_number',
            'attributes' => array(
                'type' => 'text',
                'class' => 'eidt-clien-txt',
                'disabled' => 'disabled',
            ),
            'options' => array(
                'label' => "Cheque Number:",
                'required' => false,
            ),
        ));
        $this->add(array(
            'name' => 'exchange_rate',
            'attributes' => array(
                'type' => 'text',
                'class' => 'eidt-clien-txt',
            ),
            'options' => array(
                'label' => "Exchange Rates:",
                'required' => false,
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'behaviour',
            'attributes' => array(
                'readonly' => 'readonly',
            ),
            'options' => array(
                'label' => 'Voucher Behaviour',
                'value_options' => array(
                    \Account\Entity\VoucherType::PAYMENT => 'Payment',
                    \Account\Entity\VoucherType::RECEIPT => 'Receipt',
                    \Account\Entity\VoucherType::JOURNAL => 'Journal',
                    \Account\Entity\VoucherType::TRANSFER => 'Transfer',
                ),
                'required' => false,
            )
        ));
    }

}

?>
