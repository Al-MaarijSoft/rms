<?php

namespace Administration\Form;

use Zend\Form\Form;

class BranchForm extends Form
{

    public function __construct($arrSelectData = array())
    {
        // we want to ignore the name passed
        parent::__construct('branch');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'name',
            'type' => 'Text',
            'options' => array(
                'label' => 'Name:',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'branch_type',
            'options' => array(
                'label' => 'Brach Type:',
                'empty_option' => 'Select Branch Type',
                'value_options' => array(
                    \Administration\Entity\Branch::MAIN => 'Main',
                    \Administration\Entity\Branch::FRANCHISE => 'Franchise',
                    \Administration\Entity\Branch::SIMPLE => 'Simple',
                ),
            ),
        ));
        $this->add(array(
            'type' => 'Checkbox',
            'name' => 'status',
            'options' => array(
                'label' => 'Status:',
                'use_hidden_element' => true,
                'checked_value' => \Administration\Entity\Branch::ACTIVE,
                'unchecked_value' => \Administration\Entity\Branch::INACTIVE,
            )
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'company',
            'options' => array(
                'label' => 'Company:',
                'required'=> true,
                'empty_option' => 'Select Company',
                'value_options' => @$arrSelectData['Company'],
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'country',
            'options' => array(
                'label' => 'Country:',
                'empty_option' => 'Select Country',
                'value_options' => @$arrSelectData['Country'],
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'state',
            'options' => array(
                'label' => 'State:',
                'empty_option' => 'Select State',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'city',
            'options' => array(
                'label' => 'City:',
                'empty_option' => 'Select City',
            ),
        ));

        $this->add(array(
            'name' => 'zip_code',
            'type' => 'Text',
            'options' => array(
                'label' => 'Zip Code:',
            ),
        ));
        $this->add(array(
            'name' => 'address',
            'type' => 'TextArea',
            'options' => array(
                'label' => 'Address:',
            ),
        ));
        $this->add(array(
            'name' => 'email',
            'type' => 'Email',
            'options' => array(
                'label' => 'Email:',
            ),
        ));
        $this->add(array(
            'name' => 'cell',
            'type' => 'Text',
            'options' => array(
                'label' => 'Cell:',
            ),
        ));
        $this->add(array(
            'name' => 'phone1',
            'type' => 'Text',
            'options' => array(
                'label' => 'Phone1:',
            ),
        ));
        $this->add(array(
            'name' => 'phone2',
            'type' => 'Text',
            'options' => array(
                'label' => 'Phone2:',
            ),
        ));
        $this->add(array(
            'name' => 'fax',
            'type' => 'Text',
            'options' => array(
                'label' => 'Fax:',
            ),
        ));
        $this->add(array(
            'name' => 'description',
            'type' => 'TextArea',
            'options' => array(
                'label' => 'Description:',
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