<?php

namespace Website\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class SignUpForm extends Form
{

    public function __construct($arrSelectData = array())
    {
        // we want to ignore the name passed
        parent::__construct('frmSignUp');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        
        $this->add(array(
            'name' => 'name',
            'type' => 'Text',
            'options' => array(
                'label' => 'Company Name *:',
                'required' => true
            ),
        ));
        $this->add(array(
            'name' => 'address',
            'type' => 'Textarea',
            'options' => array(
                'label' => 'Company Address *:',
                'required' => true
            ),
        ));
        $this->add(array(
            'name' => 'email',
            'type' => 'Text',
            'options' => array(
                'label' => 'Email *:',
                'required' => true
            ),
        ));
        $this->add(array(
            'name' => 'website',
            'type' => 'Text',
            'options' => array(
                'label' => 'Website *:',
                'required' => true
            ),
        ));
        $this->add(array(
            'name' => 'contact_person',
            'type' => 'Text',
            'options' => array(
                'label' => 'Contact Person *:',
                'required' => true
            ),
        ));
        $this->add(array(
            'name' => 'contact_number',
            'type' => 'Text',
            'options' => array(
                'label' => 'Contact Number *:',
                'required' => true
            ),
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'community',
            'options' => array(
                'label' => 'Community *:',
                'empty_option' => 'Select Community',
                'value_options' => array('Education'=>'Education', 'Production'=>'Production'),
                'required' => true
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
