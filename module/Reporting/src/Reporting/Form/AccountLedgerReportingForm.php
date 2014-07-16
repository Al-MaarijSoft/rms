<?php

namespace Reporting\Form;

use Zend\Form\Form;

class AccountLedgerReportingForm extends Form
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
            'name' => 'from_account',
            'options' => array(
                'label' => 'From Accounts:',
                'empty_option' => 'Select Accounts',
                'value_options' => @$arrSelectData['account'],
            ),
        ));
        
        $this->add(array(
            'type' => 'Select',
            'name' => 'to_account',
            'options' => array(
                'label' => 'To Accounts:',
                'empty_option' => 'Select Accounts',
                'value_options' => @$arrSelectData['account'],
            ),
        ));

        $this->add(array(
            'name' => 'accountLedgerPdfReport',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Pdf Report',
                'id' => 'accountLedgerPdfReport',
            ),
        ));
    }
}