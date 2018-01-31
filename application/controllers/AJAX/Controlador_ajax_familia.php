<?php

/*
  Controlador para funcionalidades de control de ComboBox
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Controlador_ajax_familia extends CI_Controller {

    public function __construct() {
        parent::__construct();
        //  $this->load->model('/Model_utilidades', 'utilidades');
        $this->load->model('/Administrador/Clasificacion/Modelo_familia', 'familia');
         $this->load->model('/Administrador/Clasificacion/Modelo_proveedor', 'proveedor');
    }

    public function Mostrar_proveedor() {
        $data = $this->proveedor->obtener_Todo();
        echo json_encode($data);
    }

    public function Mostrar() {
        $entrada = $this->input->get('variable');
        $variable = array();
        $id = array();

        $dato = $this->familia->obtener_familia_por_proveedor($entrada);
        foreach ($dato as $dato) { //Recorro lo que traigo de la base de datos
            $variable[] = $dato->nombre;
            $id[] = $dato->id_familia;
        }
        $xml = "<?xml version=\"1.0\"?>\n";
        $xml .= "<familias >\n";
        if ($variable == null) {
            $xml .= "<familia>Sin Datos.</familia>\n";
            $xml .= "<id>0</id>\n";
        }
        for ($f = 0; $f < count($variable); $f++) {
            $xml .= "<familia>" . ($variable[$f]) . "</familia>\n"; //  utf8_encode aplicar en caso de error 
            $xml .= "<id>" . ($id[$f]) . "</id>\n";
        }
        $xml .= "</familias>\n";
        header('Content-Type: text/xml');
        echo $xml;
    }

}
