<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Controlador_cierre extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('/Cierre/Modelo_cierre', 'cierre');
        $this->load->model("/Utilidades/Modelo_utilidades", 'utilidades');
    }

    public function Listar_datos() {
        $list = $this->cierre->obtener_tablacompleta();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $variable) {
            $no++;

            $row = array();
            $row[] = $variable->fecha;

            $row[] = $this->utilidades->nombre_sucursal($variable->id_sucursal);
            $row[] = $this->utilidades->nombre_caja($variable->id_caja);
           
            $row[] = $this->utilidades-> nombre_usuario($variable->id_cajero);
            //$row[] = $variable->total;
             $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_person(' . "'" . $variable->id_cierre . "'" . ')"><i class="glyphicon glyphicon-pencil"></i>Modificar</a>';
                 

            /* Desactivado por ahora
            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_person(' . "'" . $variable->id_cierre . "'" . ')"><i class="glyphicon glyphicon-pencil"></i> Editar</a>
                  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="desactivar(' . "'" . $variable->id_cierre . "'" . ')"><i class="glyphicon glyphicon-trash"></i>Eliminar</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="desactivar(' . "'" . $variable->id_cierre . "'" . ')"><i class="glyphicon glyphicon-ok"></i>Finalizar</a>';
*/
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->cierre->contar_todo(),
            "recordsFiltered" => $this->cierre->contar_filtrado(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function Editar_datos($id) {
        $data = $this->cierre->obtener_por_id($id);

        echo json_encode($data);
    }

    public function Agregar_datos() {
        //$this->_validate();
        $fecha = $this->input->post('fecha');
        $id_sucursal = $this->session->userdata("id_sucursal");
        $id_cajero = $this->session->userdata("id_usuarios");
        $id_caja = $this->session->userdata("id_caja");

        $data = array(
            'fecha' => $fecha,
            'id_sucursal' => $id_sucursal,
            'id_caja' => $id_caja,
            'id_cajero' => $id_cajero,
            'total' => "0",
        );
        $id_datos = $this->cierre->guardar($data);
        echo json_encode(array("status" => TRUE));
//Session del Cierre Temporal
        $session_cierre = array(
            'id_cierre' => '' . $id_datos,
            'fecha' => '' . $this->input->post('fecha'),
        );
        $datos = $this->session->get_userdata(); // Obtengo los datos de la session
        $salida = array_merge($datos, $session_cierre); //Junto los datos
        $this->session->set_userdata($salida); // Seteo la session 
    }


    public function Actualizar_datos() {
        //$this->_validate();
        $fecha = $this->input->post('fecha');

        $data = array(
            'fecha' => $fecha,
        );
        $this->cierre->actualizar(array('id_cierre' => $this->input->post('id')), $data);

        echo json_encode(array("status" => TRUE));
        // Ojo en Q.A Revisar bien esto de abajo
        // Manejo de datos session
        $id_datos = $this->input->post('id');
        $session_cierre = array(
            'id_cierre' => '' . $id_datos,
            'fecha' => '' . $this->input->post('fecha'),
        );
        $datos = $this->session->get_userdata(); // Obtengo los datos de la session
        $salida = array_merge($datos, $session_cierre); //Junto los datos
        $this->session->set_userdata($salida); // Seteo la session 
    }

    public function Eliminar_datos($id) {
        $this->cierre->eliminar_por_id($id);
        echo json_encode(array("status" => TRUE));
    }
    
     public function suma_ventas() {
         
         echo "sumando loco";
         
     /*
         $id_cierre = $this->session->userdata("id_cierre");
        $id_familia = $this->session->userdata("id_familia");
        $suma_total = $this->detalle_cierre_familia->total_cierre_familia($id_cierre, $id_familia);

        $data = array(
            "suma_total" => $suma_total,
        );
        echo json_encode($data);
      * */
     
    }
    
    

    private function _validar() {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;



        if ($this->input->post('nombre') == '') {
            $data['inputerror'][] = 'nombre';
            $data['error_string'][] = 'Nombre requerido';
            $data['status'] = FALSE;
        }



        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

}
