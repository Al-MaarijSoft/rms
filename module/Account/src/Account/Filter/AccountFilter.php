<?php

/**
 * Description of AccountFilter
 *
 * @author umair
 */

namespace Account\Filter;

use Zend\InputFilter\Factory as InputFactory,
    Zend\InputFilter\InputFilter,
    Zend\InputFilter\InputFilterAwareInterface,
    Zend\InputFilter\InputFilterInterface;

class AccountFilter implements InputFilterAwareInterface
{

    protected $InputFilter;
    protected $EntityManager;
    protected $Repository;

    public function __construct(\Doctrine\ORM\EntityManager $EM = null)
    {
        if (null !== $EM)
        {
            $this->EntityManager = $EM;
            $this->Repository = $EM->getRepository('Account\Entity\Account');
        }
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->InputFilter)
        {
            $InputFilter = new InputFilter();
            $Factory = new InputFactory();

            $InputFilter->add($Factory->createInput(array(
                        'name' => 'category',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'Int'),
                        ),
                        'validators' => array(
                            array('name' => '\Zend\Validator\NotEmpty',
                                'options' => array('encoding' => 'UTF-8',
                                    'messages' => array(\Zend\Validator\NotEmpty::IS_EMPTY => 'Please select account category')
                                ),
                            ),
                        ),
            )));

            $InputFilter->add($Factory->createInput(array(
                        'name' => 'class',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'Int'),
                        ),
                        'validators' => array(
                            array('name' => '\Zend\Validator\NotEmpty',
                                'options' => array('encoding' => 'UTF-8',
                                    'messages' => array(\Zend\Validator\NotEmpty::IS_EMPTY => 'Please enter account class')
                                ),
                            ),
                        ),
            )));

            $InputFilter->add($Factory->createInput(array(
                        'name' => 'account_type',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'Int'),
                        ),
                        'validators' => array(
                            array('name' => '\Zend\Validator\NotEmpty',
                                'options' => array('encoding' => 'UTF-8',
                                    'messages' => array(\Zend\Validator\NotEmpty::IS_EMPTY => 'Please enter account type')
                                ),
                            ),
                        ),
            )));

            $InputFilter->add($Factory->createInput(array(
                        'name' => 'branch',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'Int'),
                        ),
                        'validators' => array(
                            array('name' => '\Zend\Validator\NotEmpty',
                                'options' => array('encoding' => 'UTF-8',
                                    'messages' => array(\Zend\Validator\NotEmpty::IS_EMPTY => 'Please enter account branch')
                                ),
                            ),
                        ),
            )));

            $InputFilter->add($Factory->createInput(array(
                        'name' => 'parent',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'Int'),
                        ),
                        'validators' => array(
                            array('name' => '\Zend\Validator\NotEmpty',
                                'options' => array('encoding' => 'UTF-8',
                                    'messages' => array(\Zend\Validator\NotEmpty::IS_EMPTY => 'Please enter account parent')
                                ),
                            ),
                        ),
            )));

            $InputFilter->add($Factory->createInput(array(
                        'name' => 'name',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            array('name' => '\Zend\Validator\NotEmpty',
                                'options' => array('encoding' => 'UTF-8',
                                    'messages' => array(
                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'Please enter account name')),
                                'break_chain_on_failure' => true
                            ),
//                            array('name' => '\DoctrineModule\Validator\UniqueObject',
//                                'options' => array(
//                                    'object_manager' => $this->EntityManager,
//                                    'object_repository' => $this->Repository,
//                                    'fields' => array('name','company'),
//                                    'messages' => array(
//                                        \DoctrineModule\Validator\UniqueObject::ERROR_OBJECT_NOT_UNIQUE => 'account name already exists.')),
//                                'break_chain_on_failure' => true,
//                            ),
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min' => 3,
                                    'max' => 50,
                                    'messages' => array(
                                        \Zend\Validator\StringLength::TOO_LONG => 'Account Name can not be more than 50 characters long',
                                        \Zend\Validator\StringLength::TOO_SHORT => 'Account Name can not be less than 3 characters.')
                                ),
                            ),
                        ),
            )));

            $InputFilter->add($Factory->createInput(array(
                        'name' => 'code',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            array('name' => '\Zend\Validator\NotEmpty',
                                'options' => array('encoding' => 'UTF-8',
                                    'messages' => array(
                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'Please enter account code')),
                                'break_chain_on_failure' => true
                            ),
//                            array('name' => '\Zend\Validator\Db\RecordExists',
//                                'options' => array(
//                                    'table' => 'accounts',
//                                    'field' => 'code',
//                                    'messages' => array(
//                                        \Zend\Validator\Db\RecordExists::ERROR_RECORD_FOUND => 'account code already exists.')),
//                                'break_chain_on_failure' => true,
//                            ),
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min' => 2,
                                    'max' => 150,
                                    'messages' => array(
                                        \Zend\Validator\StringLength::TOO_LONG => 'Account-Code can not be more than 150 characters long',
                                        \Zend\Validator\StringLength::TOO_SHORT => 'Account-Code can not be less than 2 characters.')
                                ),
                            ),
                        ),
            )));

            $InputFilter->add($Factory->createInput(array(
                        'name' => 'description',
                        'required' => false,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min' => 5,
                                    'max' => 500,
                                    'messages' => array(
                                        \Zend\Validator\StringLength::TOO_LONG => 'Description can not be more than 500 characters long',
                                        \Zend\Validator\StringLength::TOO_SHORT => 'Description can not be less than 5 characters.')
                                ),
                            ),
                        ),
            )));

            $this->InputFilter = $InputFilter;
        }
        return $this->InputFilter;
    }

}

