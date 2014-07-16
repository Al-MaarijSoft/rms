<?php

/**
 * Description of CompanyFilter
 *
 * @author rashid
 */

namespace Administration\Filter;

use Zend\InputFilter\Factory as InputFactory,
    Zend\InputFilter\InputFilter,
    Zend\InputFilter\InputFilterAwareInterface,
    Zend\InputFilter\InputFilterInterface;

class CompanyFilter implements InputFilterAwareInterface
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
                        'name' => 'name',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min' => 1,
                                    'max' => 50,
                                ),
                            ),
                        ),
            )));
            $InputFilter->add($Factory->createInput(array(
                        'name' => 'status',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'Int'),
                        ),
            )));
            $InputFilter->add($Factory->createInput(array(
                        'name' => 'description',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min' => 0,
                                    'max' => 500,
                                ),
                            ),
                        ),
            )));
            //============== Bio Info Table InputFilter
            $InputFilter->add($Factory->createInput(array(
                        'name' => 'zip_code',
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
                                    'min' => 0,
                                    'max' => 6,
                                ),
                            ),
                        ),
            )));
            $InputFilter->add($Factory->createInput(array(
                        'name' => 'address',
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
                                    'min' => 0,
                                    'max' => 255,
                                ),
                            ),
                        ),
            )));
            $InputFilter->add($Factory->createInput(array(
                        'name' => 'email',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min' => 0,
                                    'max' => 50,
                                ),
                            ),
                        ),
            )));
            $InputFilter->add($Factory->createInput(array(
                        'name' => 'cell',
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
                                    'min' => 0,
                                    'max' => 15,
                                ),
                            ),
                        ),
            )));
            $InputFilter->add($Factory->createInput(array(
                        'name' => 'phone1',
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
                                    'min' => 0,
                                    'max' => 15,
                                ),
                            ),
                        ),
            )));
            $InputFilter->add($Factory->createInput(array(
                        'name' => 'phone2',
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
                                    'min' => 0,
                                    'max' => 15,
                                ),
                            ),
                        ),
            )));
            $this->InputFilter = $InputFilter;
        }
        return $this->InputFilter;
    }
}
