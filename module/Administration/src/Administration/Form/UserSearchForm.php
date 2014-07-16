<?php

namespace Administration\Form;

use Zend\Form\Form;
use Account\Entity\Account;

class UserSearchForm extends Form
{

    public function __construct($arrSelectData = array())
    {
        // we want to ignore the name passed
        parent::__construct('userSearch');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

        $this->add(array(
            'type' => 'Select',
            'name' => 'role',
            'options' => array(
                'label' => 'Role:',
                'empty_option' => 'Select Role',
                'value_options' => @$arrSelectData['role'],
            ),
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'username',
            'options' => array(
                'label' => 'User Name:',
            ),
//            'attributes' => array('readonly', true),
        ));
        
        $this->add(array(
            'type' => 'text',
            'name' => 'name',
            'options' => array(
                'label' => 'Real Name:',
            ),
//            'attributes' => array('readonly', true),
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