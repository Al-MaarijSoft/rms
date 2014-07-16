<?php

namespace Account\Form;

use Account\Entity\FinancialYear;
use Zend\Form\Form;

class FinancialYearForm extends Form
{

    public function __construct($arrSelectData = array())
    {
        // we want to ignore the name passed
        parent::__construct('financial_year');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'name',
            'type' => 'Text',
            'options' => array(
                'label' => 'Financial Year:',
                'disabled' => 'disabled',
            ),
        ));
        $this->add(array(
            'name' => 'start_date',
            'type' => 'Text',
            'options' => array(
                'label' => 'Start Date:',
            ),
        ));
        $this->add(array(
            'name' => 'end_date',
            'type' => 'Text',
            'options' => array(
                'label' => 'End Date:',
            ),
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'status',
            'attributes' => array(
                'id' => 'status',
            ),
            'options' => array(
                'label' => 'Status:',
                'value_options' => array(
                    FinancialYear::OPEN => 'Open',
                    FinancialYear::CLOSE => 'Close',
                ),
            )
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'is_current',
            'attributes' => array(
                'id' => 'is_current',
            ),
            'options' => array(
                'label' => 'Is Current Year?',
                'value_options' => array(
                    FinancialYear::YES => 'Yes',
                    FinancialYear::NO => 'No',
                ),
            )
        ));
        
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Save',
                'id' => 'submitbutton',
            ),
        ));
    }

}
