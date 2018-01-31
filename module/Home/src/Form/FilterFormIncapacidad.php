<?php
/**
 * @author Oscar Aldana
 * @copyright 2017
 */

namespace Home\Form;

use Zend\InputFilter\Factory as InputFactory; 
use Zend\InputFilter\InputFilter; 
use Zend\InputFilter\InputFilterAwareInterface; 
use Zend\InputFilter\InputFilterInterface; 

class FilterFormIncapacidad implements InputFilterAwareInterface
{
    
    public function __construct() {
        
        /*$cedula = new Input('cedula');
        $cedula->setRequired(true)
                ->getFilterChain()
                ->attach(new StringTrim())
                ->attach(new StripTags());
        $cedula->getValidatorChain()->attach(new NotEmpty());
        $this->add($cedula);
        */
    }
    
    protected $inputFilter;
    
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }
    
    public function getInputFilter()
    {
        if (!$this->inputFilter)
        {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();
    
    
            $inputFilter->add($factory->createInput(array(
                'name' => 'cedula',
                'required' => true,
                'filters' => array(
                    //array('name' => 'Strip Tags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array (
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => '1',
                            'max' => '15',
                        ),
                    ),
                ),
            )));
            

            

            $inputFilter->add($factory->createInput(array(
                'name' => 'valorpagoeps',
                'required' => false,
                'allowEmpty' => true,
            
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'valorfacturado',
                'required' => false,
                'allowEmpty' => true,
            
            )));
            
            $inputFilter->add($factory->createInput(array(
                'name' => 'fechapago',
                'required' => false,
                'allowEmpty' => true,
            
            )));
            
            $inputFilter->add($factory->createInput(array(
                'name' => 'fecharadicacion',
                'required' => false,
                'allowEmpty' => true,
                
            )));
            
            $inputFilter->add($factory->createInput(array(
                'name' => 'causal',
                'required' => false,
                'allowEmpty' => true,
            
            )));
            
            $inputFilter->add($factory->createInput(array(
                'name' => 'prorroga',
                'required' => false,
                'allowEmpty' => true,
            
            )));
            
            
            
            $inputFilter->add($factory->createInput(array(
                'name' => 'fechafinal',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Callback',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\Callback::INVALID_VALUE => 'The end date should be greater than start date',
                            ),
                            'callback' => function($value, $context = array()) {
                                $startDate = \DateTime::createFromFormat('Y-m-d', $context['fechainicial']);
                                $endDate = \DateTime::createFromFormat('Y-m-d', $value);
                                return $endDate >= $startDate;
                            },
                         ),
                    ),
                ),
           )));
    
           /* $inputFilter->add($factory->createInput(array(
                'name' => 'content',
    
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array (
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => '8',
                            'max' => '300',
                        ),
                    ),
                ),
    
    
            )));
    
            $inputFilter->add($factory->createInput(array(
                'name' => 'date_creation',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array (
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => '5',
                            'max' => '15',
                        ),
                    ),
                ),
            )));*/
    
            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }
    
}