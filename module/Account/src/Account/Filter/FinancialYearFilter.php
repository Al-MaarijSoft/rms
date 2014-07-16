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

class FinancialYearFilter implements InputFilterAwareInterface
{

    protected $InputFilter;
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
                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'Please enter Start Date')),
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
                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'Please enter End Date')),
                                'break_chain_on_failure' => true
                            ),
                        ),
            )));

            $this->InputFilter = $InputFilter;
        }
        return $this->InputFilter;
    }

}

