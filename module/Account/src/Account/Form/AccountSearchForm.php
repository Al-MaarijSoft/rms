<?php

namespace Account\Form;

use Zend\Form\Form;
use Account\Entity\Account;

class AccountSearchForm extends Form
{

    public function __construct($arrSelectData = array())
    {
        // we want to ignore the name passed
        parent::__construct('accountSearch');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

//        $this->add(array(
//            'name' => 'category',
//            'type' => 'Select',
//            'options' => array(
//                'label' => 'Account Category:',
//                'empty_option' => 'Select',
//                'value_options' => array(
//                    Account::SUPER_CONTROL => 'Super Control',
//                    Account::CONTROL => 'Control',
//                    Account::DETAILED => 'Detailed'),
//            ),
//        ));

        $this->add(array(
            'name' => 'class',
            'type' => 'Select',
            'options' => array(
                'label' => 'Account Class:',
                'empty_option' => 'Select',
                'value_options' => array(
                    Account::ASSET => 'Asset',
                    Account::INCOME => 'Income',
                    Account::EXPENSE => 'Expense',
                    Account::LIABILITY => 'Libilities',
                    Account::CAPITAL => 'Capital'),
            ),
        ));

        $this->add(array(
            'type' => 'Select',
            'name' => 'account_type',
            'options' => array(
                'label' => 'Account Type:',
                'empty_option' => 'Select type',
                'value_options' => @$arrSelectData['type'],
                'disabled' => 'disabled',
            ),
        ));


        $this->add(array(
            'type' => 'Select',
            'name' => 'branch',
            'options' => array(
                'label' => 'Account Branch:',
                'value_options' => @$arrSelectData['branch'],
            ),
        ));


        $this->add(array(
            'type' => 'Select',
            'name' => 'code',
            'options' => array(
                'label' => 'Account Code:',
                'empty_option' => 'Select Account Code',
                'value_options' => @$arrSelectData['code'],
            ),
        ));

        $this->add(array(
            'type' => 'Select',
            'name' => 'parent',
//            'attributes' => array(
//                'id' => 'parent',
//            ),
            'options' => array(
                'label' => 'Parent Account:',
                'empty_option' => 'Select Account',
                'value_options' => @$arrSelectData['account'],
            ),
        ));

        $this->add(array(
            'name' => 'name',
            'type' => 'Select',
            'options' => array(
                'label' => 'Name:',
                'empty_option' => 'Select Account Name',
                'value_options' => @$arrSelectData['name'],
            ),
        ));



        $this->add(array(
            'name' => 'level',
            'type' => 'Text',
            'options' => array(
                'label' => 'Level:',
            )
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
                'value' => 'Search',
                'id' => 'submitbutton',
            ),
        ));

//        //***************************************
////        $serviceManager = new \Zend\ServiceManager\ServiceManager();
//         // Fetch any valid object manager from the Service manager (here, an entity manager)
////        $entityManager = $serviceManager->get('Doctrine\ORM\EntityManager');
//        $entityManager = @$arrSelectData['EM'];
//
//        // Now get the input filter of the form, and add the validator to the email input
//        $emailInput = $this->getInputFilter()->get(array('name','company'));
//
//        $noObjectExistsValidator = new NoObjectExistsValidator(array(
//            'object_repository' => $entityManager->getRepository('Application\Entity\User'),
//            'fields'            => array('name','company'),
//        ));
//
//        $emailInput->getValidatorChain()
//                      ->addValidator($noObjectExistsValidator);
    }

}
