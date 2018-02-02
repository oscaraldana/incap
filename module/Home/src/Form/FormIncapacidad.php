<?php

/**
 * @author Oscar Aldana
 * @copyright 2018
 */

namespace Home\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Form\Factory;
use Zend\Form\Fieldset;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Adapter;
use Zend\InputFilter\InputFilter;
use Zend\Validator;
use Zend\I18n\Validator as I18nValidator;


class FormIncapacidad  extends Form 
{
    public function __construct($name = null, $parametrosSelects = null){
        parent::__construct($name);
        
        $this->setAttribute('method', 'post');
        
        $this->setAttribute('class', 'form-horizontal');
        /**
         * @type Select
         * @var Cedula
         */
        if ( isset($parametrosSelects["asociadoDefault"]) && is_array($parametrosSelects["asociadoDefault"]) && count($parametrosSelects["asociadoDefault"]) > 0 ) {
         
            $varAsoc = $parametrosSelects["asociadoDefault"];
            
        } else {
            $varAsoc = array( "" => "Seleccione..." );
        }
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'cedula',
            'required' => true,
            'filters' => array(
                //Cuidado con StripTags y HtmlEntities puede ser que no nos validen texto con tildes o eñes
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ), 
            'options' => array(
                'label' => 'Asociado: ',
                'value_options' => $varAsoc,
                'label_attributes' => array(
                    'class' => 'control-label col-xs-3',
                ),
                'disable_inarray_validator' => true,
        
            ),
            'attributes' => [ 
                                'id' => 'cedula', 
                                'style' => 'width:100%;', 
                                'placeholder' => 'Asociado',
                                'required'=>'required',
            ]
        ));
        
        
        //De esta forma añadimos restricciones a los campos del formulario
        $this->add(array(
            'name' => 'id_incapacidad',
            'required' => true,
            'filters' => array(
            //Cuidado con StripTags y HtmlEntities puede ser que no nos validen texto con tildes o eñes
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            
        ));
        
        
        
        /**
         * @type Select
         * @var Tipo Incapacidad
         */
        $varTipoIncap = array( "" => "Seleccione..." );
        if (isset($parametrosSelects["tipoIncapacidad"]) && !empty($parametrosSelects["tipoIncapacidad"])){
            foreach ($parametrosSelects["tipoIncapacidad"] as $datos) {
                $varTipoIncap[$datos->id_tipoincapacidad] = $datos->tipoincapacidad;
            }
        }
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'tipoIncap',
            'options' => array(
                'label' => 'Tipo de Incapacidad: ',
                'value_options' => $varTipoIncap,
                'disable_inarray_validator' => true,
                
            ),
            'attributes' => [ 'id' => 'tipoIncap', 'class' => 'form-control','required'=>'required','onchange' => 'analizarFechas()' ]
            
        ));
        
        
        /**
         * @type Select
         * @var Sucursales
         */
        $varSucursales = array( "" => "Seleccione..." );
        
        if (isset($parametrosSelects["sucursales"]) && !empty($parametrosSelects["sucursales"])){
            foreach ($parametrosSelects["sucursales"] as $datos) {
                $varSucursales[$datos->id_sucursal] = $datos->sucursal;
            }
        }
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'sucursal',
            'options' => array(
                'label' => 'Sucursal: ',
                'value_options' => $varSucursales,
                'disable_inarray_validator' => true,
        
            ),
            'attributes' => [ 'id' => 'sucursal', 'class' => 'form-control','required'=>'required' ]
        ));
        
        
        /**
         * @type Select
         * @var Eps
         */
        $varEps = array( "" => "Seleccione..." );
        
        if (isset($parametrosSelects["epss"]) && !empty($parametrosSelects["epss"])){
            foreach ($parametrosSelects["epss"] as $datos) {
                $varEps[$datos->id_eps] = $datos->nombre_eps;
            }
        }
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'eps',
            'options' => array(
                'label' => 'Eps: ',
                'value_options' => $varEps,
                'disable_inarray_validator' => true,
        
            ),
            'attributes' => [ 'id' => 'eps', 'class' => 'form-control','required'=>'required']
        ));
        
        
        /**
         * @type text
         * @var # Incapacidad
         */
        $this->add([
            'name' => 'nincapacidad',
            'options' => [
                'label' => 'Número Incapacidad',
            ],
            'attributes' => [
                'id' => 'nincapacidad', 'type' => 'text', 'class' => 'form-control','required'=>'required'
            ],
        ]);
        
        
        /**
         * @type Select
         * @var Diagnostico
         */
        
        if ( isset($parametrosSelects["asociadoDefault"]) && is_array($parametrosSelects["asociadoDefault"]) && count($parametrosSelects["asociadoDefault"]) > 0 ) {
         
            $varDiagn = $parametrosSelects["diagnosticoDefault"];
            
        } else {
            $varDiagn = array( "" => "Seleccione..." );
        }
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'diagnostico',
            'options' => array(
                'label' => 'Diagnostico: ',
                'value_options' => $varDiagn,
                'disable_inarray_validator' => true,
        
            ),
            'attributes' => [ 
                                'id' => 'diagnostico', 
                                'class' => 'form-control','required'=>'required'
            ]
        ));

        /**
         * @type Date
         * @var Fecha  Inicial
         */
        $this->add(array(
            //'type' => 'Zend\Form\Element\Date',
            'name' => 'fechainicial',
            'options' => array(
                'label' => 'Fecha Inicial: ',
                //'format' => 'Y-m-d'
            ),
            'attributes' => array(
                'id' => 'fechainicial',
                //'min' => '2012-01-01',
                //'max' => '2020-01-01',
                //'step' => '1', // days; default step interval is 1 day
                'class' => 'form-control','required'=>'required',
                //'pattern' => '(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))',
                'onchange' => 'analizarFechas();'
            ),
            
        ));

        /**
         * @type text
         * @var Fecha  Final
         */
        $this->add(array(
            //'type' => 'Zend\Form\Element\Date',
            'name' => 'fechafinal',
            'options' => array(
                'label' => 'Fecha Final: ',
                //'format' => 'Y-m-d'
            ),
            'attributes' => array(
                'id' => 'fechafinal',
                'type' => 'text',
                //'min' => '2012-01-01',
                //'max' => '2020-01-01',
                //'step' => '1', // days; default step interval is 1 day
                'class' => 'form-control','requ ired'=>'required',
                'onchange' => 'analizarFechas()'
            )
        ));
        
        /**
         * @type Date
         * @var Dias Totales
         */
        $this->add([
            'name' => 'diastotales',
            'type' => 'Zend\Form\Element\Number',   
            'options' => [
                'label' => 'Dias Totales',
            ],
            'attributes' => [
                'id' => 'diastotales',
                'min' => '0',
                 'max' => '1000',
                 'step' => '1', // default step interval is 1
                'class' => 'form-control','required'=>'required',
                'readonly' => true
            ],
        ]);
        
        /**
         * @type Number
         * @var Dias Empresa
         */
        $this->add([
            'name' => 'diasempresa',
            'type' => 'Zend\Form\Element\Number',
            'options' => [
                'label' => 'Dias Empresa',
            ],
            'attributes' => [
                'id' => 'diasempresa',
                'min' => '0',
                'max' => '1000',
                'step' => '1', // default step interval is 1
                'class' => 'form-control','required'=>'required',
                'readonly' => true
            ],
        ]);
        

        /**
         * @type Number
         * @var Dias Eps
         */
        $this->add([
            'name' => 'diaseps',
            'type' => 'Zend\Form\Element\Number',
            'options' => [
                'label' => 'Dias Eps',
            ],
            'attributes' => [
                'id' => 'diaseps',
                'min' => '0',
                'max' => '1000',
                'step' => '1', // default step interval is 1
                'class' => 'form-control','required'=>'required','readonly' => true
            ],
        ]);

        /**
         * @type Number
         * @var Dias Arl
         */
        $this->add([
            'name' => 'diasarl',
            'type' => 'Zend\Form\Element\Number',
            'options' => [
                'label' => 'Dias Arl',
            ],
            'attributes' => [
                'id' => 'diasarl',
                'min' => '0',
                'max' => '1000',
                'step' => '1', // default step interval is 1
                'class' => 'form-control','required'=>'required',
                'readonly' => true
            ],
        ]);
        

        /**
         * @type Select
         * @var Prorroga
         */
        if ( isset($parametrosSelects["prorrogaDefault"]) && is_array($parametrosSelects["prorrogaDefault"]) && count($parametrosSelects["prorrogaDefault"]) > 0 ) {
         
            $varIncap = $parametrosSelects["prorrogaDefault"];
            
        } else {
            $varIncap = array( "" => "Seleccione..." );
        }
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'prorroga',
            'options' => array(
                'label' => 'Prorroga: ',
                'value_options' => $varIncap,
                'disable_inarray_validator' => true,
        
            ),
            'attributes' => [
                'id' => 'prorroga', 'class' => 'form-control'
            ],
        ));
        
        
        
        
        
        
        
        
        
        /**
         * @type number
         * @var Valor Facturado
         */
        $this->add([
            'name' => 'valorfacturado',
            'type' => 'Zend\Form\Element\Number',
            'options' => [
                'label' => 'Valor Facturado',
            ],
            'attributes' => [
                'id' => 'valorfacturado',
                'min' => '0',
                'max' => '90000000',
                'step' => '1', // default step interval is 1
                'class' => 'form-control',
                //'readonly' => true
            ],
        ]);
        

        /**
         * @type number
         * @var Valor Pago Eps
         */
        $this->add([
            'name' => 'valorpagoeps',
            'type' => 'Zend\Form\Element\Number',
            'options' => [
                'label' => 'Valor Pago Eps',
            ],
            'attributes' => [
                'id' => 'valorpagoeps',
                'min' => '0',
                'max' => '90000000',
                'step' => '1', // default step interval is 1
                'class' => 'form-control',
                //'readonly' => true
            ],
        ]);
        
        
        /**
         * @type text
         * @var # Incapacidad
         */
        $this->add([
            'name' => 'codtesoreria',
            'options' => [
                'label' => 'Codigo Tesoreria',
            ],
            'attributes' => [
                'id' => 'codtesoreria', 'type' => 'text', 'class' => 'form-control'
            ],
        ]);
        
        
        /**
         * @type Date
         * @var Fecha Pago
         */
        $this->add(array(
            'type' => 'Zend\Form\Element\Date',
            'name' => 'fechapago',
            'required' => false,
            'allowEmpty' => true,
            'options' => array(
                'label' => 'Fecha Pago: ',
                'format' => 'Y-m-d'
            ),
            'attributes' => array(
                'id' => 'fechapago',
                'class' => 'form-control',
                'required' => false,
                'allowEmpty' => true,
                //'onchange' => 'analizarFechas()'
            )
        ));
        
        /**
         * @type Date
         * @var Fecha Radicacion
         */
        $this->add(array(
            'type' => 'Zend\Form\Element\Date',
            'name' => 'fecharadicacion',
            'options' => array(
                'label' => 'Fecha Radicacion: ',
                'format' => 'Y-m-d'
            ),
            'attributes' => array(
                'id' => 'fecharadicacion',
                'min' => '2012-01-01',
                'max' => '2030-01-01',
                'step' => '1', // days; default step interval is 1 day
                'class' => 'form-control',
                'onchange' => 'analizarFechas()'
            )
        ));
        
        
        
        /**
         * @type Select
         * @var Causales
         */
        $varCausal = array( "" => "Seleccione..." );
        
        if (isset($parametrosSelects["causales"]) && !empty($parametrosSelects["causales"])){
            foreach ($parametrosSelects["causales"] as $datos) {
                $varCausal[$datos->id_causal] = $datos->causal;
            }
        }
       
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'causal',
            'options' => array(
                'label' => 'Causal: ',
                'value_options' => $varCausal,
                'disable_inarray_validator' => true,
        
            ),
            'attributes' => [ 'id' => 'causal', 'class' => 'form-control']
        ));
        
        
        
        
        /**
         * @type Submit
         * @var Guardar
         */
        $this->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'class' => 'btn btn-primary',
                'id' => 'btn_submit',
                'value'=>'Guardar'
            ),
        ));
        
        
        
    }
    
    /*public function getOptionsForSelect()
    {
        $dbAdapter = $this->adapter;
        $sql       = 'SELECT id,name  FROM newsauthor where active=1 ORDER BY sortorder ASC';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
    
        $selectData = array();
    
        foreach ($result as $res) {
            $selectData[$res['id']] = $res['name'];
        }
        return $selectData;
    }*/
    
}