<?php

/* Creacion 
 * Jorge Luis Maureira
 * Morrigan SPA
 * Desarrollo Sistema de Gestion Construcciones y Moldajes Maureira Ltda.
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Controllers_utilidades extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('/Utilidades/Modelo_utilidades', 'utilidades');
    }
    public function Mostrar_empresas() {
        $data = $this->utilidades->obtener_empresas();
        echo json_encode($data);
    }
    public function Mostrar_comunas() {
        $region = $this->input->get('region'); //Capturo la region del formulario
        $comunas = array();
        $id = array();
       // $this->load->model("Model_utilidades");
        $dato = $this->utilidades->obtener_comuna($region);//Traigo la comuna por Id Region
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

   


 

}
