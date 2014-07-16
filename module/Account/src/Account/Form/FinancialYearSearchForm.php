<?php

namespace Account\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class FinancialYearSearchForm extends Form
{

    public function __construct($arrSelectData = array())
    {
        // we want to ignore the name passed
        parent::__construct('financialYearSearch');
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
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Search',
                'id' => 'submitbutton',
            ),
        ));
    }

}
