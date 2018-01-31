<?php

/*
 Controlador para funcionalidades de control de ComboBox
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Controlador_ajax_cajero extends CI_Controller {

    public function __construct() {
        parent::__construct();
      //  $this->load->model('/Model_utilidades', 'utilidades');
         $this->load->model('/Administrador/Modelo_sucursales', 'sucursales');
           $this->load->model('/Administrador/Modelo_cajas', 'cajas');
    }
     public function Mostrar_sucursal() {
        $data = $this->sucursales->obtener_all_sucursal();
        echo json_encode($data);
    }
     public function Mostrar_cajas() {
        $sucursal = $this->input->get('sucursal'); 
        $caja = array();
        $id = array();
     
        $dato = $this->cajas->obtener_caja_por_sucursal($sucursal);
        foreach ($dato as $dato) { //Recorro lo que traigo de la base de datos
            $caja[] = $dato->nombre;
            $id[] = $dato->id_caja;
        }
        $xml = "<?xml version=\"1.0\"?>\n";
        $xml.="<cajas >\n"; 
        if ($caja == null) {
            $xml.="<caja>Sin Cajas.</caja>\n";
            $xml.="<id>0</id>\n";
        }
        for ($f = 0; $f < count($caja); $f++) {
            $xml.="<caja>" . ($caja[$f]) . "</caja>\n"; //  utf8_encode aplicar en caso de error 
            $xml.="<id>" . ($id[$f]) . "</id>\n";
        }
        $xml.="</cajas>\n";
        header('Content-Type: text/xml');
        echo $xml;
    }
    
    
    
    
    
    

 

}
