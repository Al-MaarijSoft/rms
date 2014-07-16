<?php

/**
 * Description of LoginForm
 *
 * @author rashid
 */

namespace Administration\Form;

use Zend\Form\Form;

class UserForm extends Form
{

    public function __construct($arrSelectData = array())
    {
        // we want to ignore the name passed
        parent::__construct('userForm');
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
                'value_options' => @$arrSelectData['roles'],
            ),
        ));
        $this->add(array(
            'type' => 'Select',
            'name' => 'financial_years',
            'attributes' => array(
                'multiple'=>'multiple',
            ),
            'options' => array(
                'label' => 'Financial Years:',
                'empty_option' => '',
                'value_options' => @$arrSelectData['financialYears'],
            ),
        ));
        $this->add(array(
            'name' => 'username',
            'type' => 'Text',
            'options' => array(
                'label' => 'User Name:',
            ),
        ));
        $this->add(array(
            'name' => 'password',
            'type' => 'Password',
            'options' => array(
                'label' => 'Password:',
            ),
        ));
        $this->add(array(
            'name' => 'repeat_password',
            'type' => 'Password',
            'options' => array(
                'label' => 'Password:',
            ),
        ));
        $this->add(array(
            'name' => 'name',
            'type' => 'Text',
            'options' => array(
                'label' => 'Display Name:',
            ),
        ));
//        $this->add(array(
//            'type' => 'Checkbox',
//            'name' => 'status',
//            'options' => array(
//                'label' => 'Is Active:',
//                'use_hidden_element' => true,
//                'checked_value' => \Administration\Entity\User::ACTIVE,
//                'unchecked_value' => \Administration\Entity\User::INACTIVE,
//            )
//        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'status',
            'attributes' => array(
                'id' => 'status',
            ),
            'options' => array(
                'label' => 'Status:',
                'value_options' => array(
                    \Administration\Entity\User::ACTIVE => 'Active',
                    \Administration\Entity\User::INACTIVE => 'Inactive',
                ),
            )
        ));
        //**************************** Bio Info
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
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Login',
            ),
        ));
    }

}
