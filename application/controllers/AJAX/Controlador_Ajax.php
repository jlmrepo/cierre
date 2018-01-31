<?php

/*
 Controlador para funcionalidades de control de ComboBox
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Controllers_utilidades extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('/Model_utilidades', 'utilidades');
    }
    public function Mostrar_empresas() {
        $data = $this->utilidades->obtener_empresas();
        echo json_encode($data);
    }
    public function Mostrar_comunas() {
        $region = $this->input->get('region'); //Capturo la region del formulario
        $comunas = array();
        $id = array();
        $this->load->model("Model_utilidades");
        $dato = $this->Model_utilidades->obtener_comuna($region);//Traigo la comuna por Id Region
        foreach ($dato as $dato) { //Recorro lo que traigo de la base de datos
            $comunas[] = $dato->nombre;
            $id[] = $dato->id_comuna;
        }
        $xml = "<?xml version=\"1.0\"?>\n";
        $xml.="<comunas >\n";
          //   $xml.="<cod_comuna>Seleccione una Comuna</cod_comuna>\n";  //Activar Par mostrar por defecto
        if ($comunas == null) {
            $xml.="<comuna>No hay comunas en la region.</comuna>\n";
            $xml.="<id>0</id>\n";
        }
        for ($f = 0; $f < count($comunas); $f++) {
            $xml.="<comuna>" . ($comunas[$f]) . "</comuna>\n"; //  utf8_encode aplicar en caso de error
          
            $xml.="<id>" . ($id[$f]) . "</id>\n";
        }
        $xml.="</comunas>\n";
        header('Content-Type: text/xml');
        echo $xml;
    }

   
public function Mostrar_obras() {
        $empresa = $this->input->get('empresa'); //Capturo la region del formulario
        $obras = array();
        $id = array();
        $this->load->model("Model_utilidades");
        $dato = $this->Model_utilidades->obtener_obra($empresa);//Traigo la obra por Id empresa
        foreach ($dato as $dato) { //Recorro lo que traigo de la base de datos
            $obras[] = $dato->nombre;
            $id[] = $dato->id_obras;
        }
        $xml = "<?xml version=\"1.0\"?>\n";
        $xml.="<obras >\n";
          //   $xml.="<cod_comuna>Seleccione una Comuna</cod_comuna>\n";  //Activar Par mostrar por defecto
        if ($obras== null) {
            $xml.="<obra>No hay obras en la region.</obra>\n";
            $xml.="<id>0</id>\n";
        }
        for ($f = 0; $f < count($obras); $f++) {
            $xml.="<obra>" . ($obras[$f]) . "</obra>\n"; //  utf8_encode aplicar en caso de error
          
            $xml.="<id>" . ($id[$f]) . "</id>\n";
        }
        $xml.="</obras>\n";
        header('Content-Type: text/xml');
        echo $xml;
    }

 

}
