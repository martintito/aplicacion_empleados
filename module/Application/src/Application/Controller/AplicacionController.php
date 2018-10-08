<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
//Incluir formularios
use Application\Form\FormularioPruebas;
use Application\Form\FormularioSalario;

class AplicacionController extends AbstractActionController {

    public function indexAction() {
        $all = $this->callWebService();
        //Creamos el objeto del formulario
        $form = new FormularioPruebas("form");
        $formSalario = new FormularioSalario("form");
        //if ($this->request->getPost("submit")) {
        if ($this->request->getPost("email") != "") {
            $datos = $this->request->getPost();
            $salida = array();
            foreach ($all as $item) {
                if ($item['email'] == $datos['email']) {
                    $salida[] = $item;
                }
            }

            //Le pasamos a la vista el formulario y la url base
            return new ViewModel(array(
                "datos" => $datos,
                "resultado" => $salida,
                "titulo" => "Resultado de la búsqueda",
                "form" => $form,
                "formSalario" => $formSalario,
                'url' => $this->getRequest()->getBaseUrl())
            );
        } else {

            //Le pasamos a la vista el formulario y la url base
            return new ViewModel(array(
                "resultado" => $all,
                "titulo" => "Lista de empleados",
                "form" => $form,
                "formSalario" => $formSalario,
                'url' => $this->getRequest()->getBaseUrl())
            );
        }
    }

    public function callWebService() {
        return json_decode(file_get_contents(getcwd() . '/public/recursos/employees.json'), true);
    }

    public function formularioAction() {
        //Creamos el objeto del formulario
        $form = new FormularioPruebas("form");

        //Le pasamos a la vista el formulario y la url base
        return new ViewModel(array(
            "titulo" => "Formularios en ZF2",
            "form" => $form,
            'url' => $this->getRequest()->getBaseUrl())
        );
    }

    public function recibirFormularioAction() {
        /* Este metodo se encarga de recojer los datos de el formulario
         * si a sido enviado y si redirecciona al formulario
         */
        if ($this->request->getPost("submit")) {
            $datos = $this->request->getPost();
            return new ViewModel(array("titulo" => "Recibir datos via POST en ZF2", "datos" => $datos));
        } else {
            return $this->redirect()->toUrl(
                            $this->getRequest()->getBaseUrl() . '/application'
            );
        }
    }

    public function detalleAction() {
        $all = $this->callWebService();
        //Recogemos el valor de la ruta
        $id = $this->params()->fromRoute("id", null);
        foreach ($all as $item) {
            if ($item["id"] == $id) {
                $detalle = $item;
                break;
            }
        }

        //Le pasamos a la vista el formulario y la url base
        return new ViewModel(array(
            "titulo" => "Detalle del empleado",
            "detalle" => $detalle,
            'url' => $this->getRequest()->getBaseUrl() . '/detalle/' . $id)
        );
    }

    public function convertir2Action() {
        //print($this->jsonToXML());exit;

        $array = json_decode(json_encode((array) $this->jsonToXML()), true);
        $array_data = $array;

        //var_dump($array_data);exit;
        //Le pasamos a la vista el formulario y la url base
        return new ViewModel(array(
            "titulo" => "Detalle del empleado",
            "array" => $array_data,)
                //'url' => $this->getRequest()->getBaseUrl() . '/detalle/' . $id)
        );
    }

    public function Parse($url) {
        $fileContents = file_get_contents($url);
        $fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
        $fileContents = trim(str_replace('"', "'", $fileContents));
        $simpleXml = simplexml_load_string($fileContents);
        $json = json_encode($simpleXml);
        return $json;
    }

    public function jsonToXMLaaaaaaaaaaaAction() {
        //read the JSON file
        $jsonFile = file_get_contents('http://localhost/zend2/public/recursos/employees.json');

//decode the data
        $jsonFile_decoded = json_decode($jsonFile);
        //var_dump($jsonFile_decoded);exit;
//create a new xml object
        $xml = new \SimpleXMLElement('<employees/>');

//loop through the data, and add each record to the xml object
        foreach ($jsonFile_decoded AS $members) {
            //foreach ($members AS $memberDetails) {
            $member = $xml->addChild('employee');
            $member->addChild('name', $members->name);
            $member->addChild('email', $members->email);
            $member->addChild('position', $members->position);
            $member->addChild('phone', $members->phone);

            //var_dump($memberDetails);    
            //}
        }

//set header content type
        //Header('Content-type: text/xml');
//output the xml file
        //print($xml->asXML());exit;
        //Le pasamos a la vista el formulario y la url base
        return $xml->asXML();
    }

    //$app->get('/api/salary', function ($request, $response, $args) {
    public function jsontoxmlAction() {
        //$str = file_get_contents(__DIR__ . '/../public/employees.json');
        $str = file_get_contents(getcwd() . '/public/recursos/employees.json');
        $all = json_decode($str, true);
        
        $min = floatval($this->request->getPost("salario1"));
        $max = floatval($this->request->getPost("salario2"));
        
        //$min = floatval(1000);
        //$max = floatval(1500);
        $filtered = [];
        if ($min && $max) {
            foreach ($all as $employee) {
                $salary = str_replace(',', '', $employee['salary']);
                $salary = str_replace('$', '', $salary);
                $salary = floatval($salary);
                if ($min <= $salary && $salary <= $max) {
                    $filtered[] = $employee;
                }
            }
        } else
            $filtered = $all;
        try {
            // check request content type
            // format and return response body in specified format
//            $mediaType = $request->getMediaType();
//            if ($mediaType == 'application/xml') {
                //$response->withHeader('Content-Type', 'application/xml');
                $xml = new \SimpleXMLElement('<root/>');
                foreach ($filtered as $employee) {
                    $item = $xml->addChild('employee');
                    $skills = $employee['skills'];
                    unset($employee['skills']);
                    array_walk_recursive($employee, function($value, $key) use ($item) {
                        $item->addChild($key, $value);
                    });
                    $skills_node = $item->addChild('skills');
                    array_walk_recursive($skills, function($value, $key) use ($skills_node) {
                        $skills_node->addChild($key, $value);
                    });
                }
                //echo $xml->asXml();exit;
                return new ViewModel(array(
                    "titulo" => "xml",
                    "xml" => $xml,)                        
                );
//            } else if (($mediaType == 'application/json')) {
//                $response->withHeader('Content-type', 'application/json');
//                echo json_encode($filtered);
//            }
        } catch (Exception $e) {
            $app->response()->status(400);
            $app->response()->header('X-Status-Reason', $e->getMessage());
        }
    }

}
