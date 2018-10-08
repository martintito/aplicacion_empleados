<?php
namespace Application\Form;
 
use Zend\Captcha\AdapterInterface as CaptchaAdapter;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Captcha;
use Zend\Form\Factory;
 
class FormularioSalario extends Form{                  
                //Le ponemos un nombre al formulario
    public function __construct($name = null){
        parent::__construct($name);
         
        //Podemos añadir campos al formulario de esta forma
        $this->add(array(
            'name' => 'salario1',
            'options' => array(
                'label' => 'Salario 1: ',
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'input'
            ),
        ));
        $this->add(array(
            'name' => 'salario2',
            'options' => array(
                'label' => 'Salario 2: ',
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'input'
            ),
        ));
         
        
        
        //Creamos el boton de submit
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Buscar',
                'title' => 'Buscar'
            ),
        ));
     }
}
 
?>