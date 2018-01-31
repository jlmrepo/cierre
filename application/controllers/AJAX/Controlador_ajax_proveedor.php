<?php

/*
  Controlador para funcionalidades de control de ComboBox
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Controlador_ajax_proveedor extends CI_Controller {

    public function __construct() {
        parent::__construct();
        //  $this->load->model('/Model_utilidades', 'utilidades');
        $this->load->model('/Administrador/Clasificacion/Modelo_categoria', 'categoria');
         $this->load->model('/Administrador/Clasificacion/Modelo_proveedor', 'proveedor');
    }

    public function Mostrar_categoria() {
        $data = $this->categoria->obtener_Todo();
        echo json_encode($data);
    }

    public function Mostrar() {
        $entrada = $this->input->get('variable');
        $variable = array();
        $id = array();

        $dato = $this->proveedor->obtener_proveedor_por_categoria($entrada);
        foreach ($dato as $dato) { //Recorro lo que traigo de la base de datos
            $variable[] = $dato->nombre;
            $id[] = $dato->id_proveedor;
        }
        $xml = "<?xml version=\"1.0\"?>\n";
        $xml .= "<proveedores >\n";
        if ($variable == null) {
            $xml .= "<proveedor>Sin Datos.</proveedor>\n";
            $xml .= "<id>0</id>\n";
        }
        for ($f = 0; $f < count($variable); $f++) {
            $xml .= "<proveedor>" . ($variable[$f]) . "</proveedor>\n"; //  utf8_encode aplicar en caso de error 
            $xml .= "<id>" . ($id[$f]) . "</id>\n";
        }
        $xml .= "</proveedores>\n";
        header('Content-Type: text/xml');
        echo $xml;
    }

}
