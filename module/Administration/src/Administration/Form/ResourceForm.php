<?php

namespace Administration\Form;

use Zend\Form\Form;

class ResourceForm extends Form
{

    public function __construct($arrSelectData = array())
    {
        // we want to ignore the name passed
        parent::__construct('resource');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'name',
            'type' => 'Text',
            'options' => array(
                'label' => 'Resource Name:',
            ),
        ));
        $this->add(array(
            'name' => 'code',
            'type' => 'Text',
            'options' => array(
                'label' => 'Code:',
            ),
            'attributes' => array(
                'readonly' => 'readonly',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'parent',
            'options' => array(
                'label' => 'Parent Resource:',
                'empty_option' => 'Select Controller',
                'value_options' => @$arrSelectData['Name'],
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

//        $this->get('country')->setValueOptions($arrSelectData['Country']);
//        $this->get('country')->setValueOptions($Country);
    }

}