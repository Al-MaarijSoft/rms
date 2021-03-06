<?php

namespace Account\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class OpeningBalanceForm extends Form
{

    public function __construct($arrSelectData = array())
    {
        // we want to ignore the name passed
        parent::__construct('frmOpeningBalance');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        
        $this->add(array(
            'name' => 'debit',
            'type' => 'Text',
            'options' => array(
                'label' => 'Debit:',
            ),
        ));
        $this->add(array(
            'name' => 'credit',
            'type' => 'Text',
            'options' => array(
                'label' => 'Credit:',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'account',
            'options' => array(
                'label' => 'Account:',
                'empty_option' => 'Select Account',
                'value_options' => @$arrSelectData['Account'],
                'required' => true,
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'financial_year',
            'options' => array(
                'label' => 'Financial Year:',
                'empty_option' => 'Select Account',
                'value_options' => @$arrSelectData['financialYear'],
                'required' => true,
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
    }

}
