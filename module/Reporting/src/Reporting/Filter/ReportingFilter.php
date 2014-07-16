<?php

/**
 * Description of AccountFilter
 *
 * @author umair
 */

namespace Reporting\Filter;

use Zend\InputFilter\Factory as InputFactory,
    Zend\InputFilter\InputFilter,
    Zend\InputFilter\InputFilterAwareInterface,
    Zend\InputFilter\InputFilterInterface;

class ReportingFilter implements InputFilterAwareInterface
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
                        'name' => 'start_date',
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
                        ),
            )));
            $InputFilter->add($Factory->createInput(array(
                        'name' => 'end_date',
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
                            ),
                        ),
            )));

             $InputFilter->add($Factory->createInput(array(
                        'name' => 'from_account',
                        'required' => false,
                        'filters' => array(
                            array('name' => 'Int'),
                        ),
            )));
             $InputFilter->add($Factory->createInput(array(
                        'name' => 'to_account',
                        'required' => false,
                        'filters' => array(
                            array('name' => 'Int'),
                        ),
            )));
            $this->InputFilter = $InputFilter;
        }
        return $this->InputFilter;
    }

}

