<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Controlador_detalle_guia extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('/Sucursal/Modelo_detalle_guia', 'detalle_guia');
        $this->load->model("/Utilidades/Modelo_utilidades", 'utilidades');
      
    }

    public function Listar_datos_guia() {
        $id_guia = $this->session->userdata("id_guia");
        $list = $this->detalle_guia->Obtener_guia_detalle($id_guia);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $variable) {
            $no++;
            $row = array();
            $row[] = $variable->id_guia;
            $row[] =$this->utilidades->nombre_producto($variable->id_producto);
            $row[] = $variable->cantidad;
            $row[] = $variable->total;
            $row[] = '<a class="btn btn-sm btn-primary" style=width:250px; height:35px; text-align: left; href="javascript:void(0)" title="Edit" onclick="editar(' . "'" . $variable->id_detalle_guia . "'" . ')"><i class="glyphicon glyphicon-pencil"></i> Editar Detalle</a>
		      <a class="btn btn-sm btn-danger" style=width:250px; height:35px; text-align: left; href="javascript:void(0)" title="Hapus" onclick="eliminar(' . "'" . $variable->id_detalle_guia . "'" . ')"><i class="glyphicon glyphicon-trash"></i>Eliminar Detalle </a>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "monto_guia" => $this->utilidades->total_guia($id_guia), //Trae el monto de la guia
            "monto_detalle" => $this->detalle_guia->monto_guia($id_guia), //Trae el monto de la guia
            "recordsTotal" => $this->detalle_guia->contar_todo_sucursal($id_guia),
            "recordsFiltered" => $this->detalle_guia->contar_filtrado($id_guia),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function obtener_montos() {
        $id_guia = $this->session->userdata("id_guia");
        $total_guia = $this->utilidades->total_guia($id_guia);
        $total_detalle = $this->detalle_guia->monto_guia($id_guia);
        $estado;
        if($total_detalle==$total_guia){
            $estado="Guia Cuadrada";
        }else{
            $estado="Descuadre en ".($id_guia-$total_detalle);
        }
        $data = array(
            "monto_guia" => $total_guia,
            "monto_detalle" => $total_detalle,
            "estado" => $estado, 
        );
        echo json_encode($data);
    }

    public function Editar_datos($id) {


        $data = $this->detalle_guia->obtener_por_id($id);

        echo json_encode($data);
    }

    public function total_producto() {
        $precio = $this->utilidades->precio_producto($this->input->post('producto'));
        $cantidad = $this->input->post('cantidad');
        $total;
        if ($precio == null && $cantidad == "") {
            $total = 0;
        } else {
            $total = $precio * $cantidad;
        }
        $data = array(
            'total' => $total,
        );
        echo json_encode($data);
    }

    public function Agregar_datos() {
        //    $this->_validar(); Activar Luego
        $data = array(
            'id_guia' => $this->session->userdata("id_guia"),
            'id_producto' => $this->input->post('producto'),
            'cantidad' => $this->input->post('cantidad'),
            'total' => $this->input->post('total'),
        );
        $this->detalle_guia->guardar($data);
        echo json_encode(array("status" => TRUE));
    }

    public function Actualizar_datos() {
        //  $this->_validar();
        $data = array(
           
            'id_producto' => $this->input->post('producto'),
            'cantidad' => $this->input->post('cantidad'),
            'total' => $this->input->post('total'),
        );
  $this->detalle_guia->actualizar(array('id_detalle_guia' => $this->input->post('id')), $data);
        echo json_encode(array("status" => TRUE));
    }

    public function desactivar_personal($id) {
        $this->obras->cambiar_EstadoObra($id, 2); //Seteo el Estado 2 Desactivado
        echo json_encode(array("status" => TRUE));
    }

    public function activar_personal($id) {
        $this->obras->cambiar_EstadoObra($id, 1); //Seteo el Estado 1 Activado
        echo json_encode(array("status" => TRUE));
    }

    public function Eliminar_datos($id) {
        $this->detalle_guia->eliminar_por_id($id);
        echo json_encode(array("status" => TRUE));
    }

    private function _validar() {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('nombres') == '') {
            $data['inputerror'][] = 'nombres';
            $data['error_string'][] = 'Nombres requerido';
            $data['status'] = FALSE;
        }
 
        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

}
