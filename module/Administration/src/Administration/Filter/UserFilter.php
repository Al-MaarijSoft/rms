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

class UserFilter implements InputFilterAwareInterface
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
                        'name' => 'username',
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
                                    'min' => 5,
                                    'max' => 50,
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
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min' => 3,
                                    'max' => 50,
                                ),
                            ),
                        ),
            )));
            $InputFilter->add($Factory->createInput(array(
                        'name' => 'password',
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
                                    'min' => 6,
                                    'max' => 20,
                                ),
                            ),
                        ),
            )));
            $InputFilter->add($Factory->createInput(array(
                        'name' => 'password',
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
                                    'min' => 6,
                                    'max' => 20,
                                ),
                            ),
                        ),
            )));
             $InputFilter->add($Factory->createInput(array(
                        'name' => 'repeat_password',
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
                                    'min' => 6,
                                    'max' => 20,
                                ),
                            ),
                            array(
                                'name' => '\Zend\Validator\Identical',
                                'options' => array(
                                    'token' => 'password',
                                    'message' => "The input is not same as Password",
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
                            array('name' => '\Zend\Validator\EmailAddress',
                                'options' => array('encoding' => 'UTF-8',
                                    'messages' => array(\Zend\Validator\EmailAddress::INVALID => 'Please enter valid email address')
                                ),
                            ),
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
                                    'min' => 10,
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
                                    'min' => 10,
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
                                    'min' => 10,
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
