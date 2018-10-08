<?php
namespace Application\Form;
 
use Zend\Captcha\AdapterInterface as CaptchaAdapter;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Captcha;
use Zend\Form\Factory;
 
class FormularioPruebas extends Form{                  
                //Le ponemos un nombre al formulario
    public function __construct($name = null){
        parent::__construct($name);
         
        //Podemos añadir campos al formulario de esta forma
        $this->add(array(
            'name' => 'nombre',
            'options' => array(
                'label' => 'Nombre: ',
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'input'
            ),
        ));
         
        //También podemos utilizar factory
        $factory = new Factory();
         
        //Creando elementos de una manera similar a la anterior
        $email = $factory->createElement(array(
            'type' => 'Zend\Form\Element\Email',
            'name' => 'email',
            'options' => array(
                'label' => 'Email: ',
            ),
            'attributes' => array(
                'class' => 'input_email',
                'id' => 'input_email'
            ),
          ));
        $this->add($email);
        
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