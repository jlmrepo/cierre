<?php

/*
  Controlador para funcionalidades de control de ComboBox
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Controlador_ajax_subfamilia extends CI_Controller {

    public function __construct() {
        parent::__construct();
        //  $this->load->model('/Model_utilidades', 'utilidades');
        $this->load->model('/Administrador/Clasificacion/Modelo_subfamilia', 'subfamilia');
         $this->load->model('/Administrador/Clasificacion/Modelo_familia', 'familia');
    }

    public function Mostrar_familia() {
        $data = $this->familia->obtener_Todo();
        echo json_encode($data);
    }

    public function Mostrar() {
        $entrada = $this->input->get('variable');
        $variable = array();
        $id = array();

        $dato = $this->subfamilia->obtener_subfamilia_por_familia($entrada);
        foreach ($dato as $dato) { //Recorro lo que traigo de la base de datos
            $variable[] = $dato->nombre;
            $id[] = $dato->id_sub_familia;
        }
        $xml = "<?xml version=\"1.0\"?>\n";
        $xml .= "<subfamilias >\n";
        if ($variable == null) {
            $xml .= "<subfamilia>Sin Datos.</subfamilia>\n";
            $xml .= "<id>0</id>\n";
        }
        for ($f = 0; $f < count($variable); $f++) {
            $xml .= "<subfamilia>" . ($variable[$f]) . "</subfamilia>\n"; //  utf8_encode aplicar en caso de error 
            $xml .= "<id>" . ($id[$f]) . "</id>\n";
        }
        $xml .= "</subfamilias>\n";
        header('Content-Type: text/xml');
        echo $xml;
    }

}
