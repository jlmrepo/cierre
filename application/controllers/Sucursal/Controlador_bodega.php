<?php

/* Creacion 
 * Jorge Luis Maureira
 * Morrigan SPA
 * Desarrollo Sistema de Control de Cierre e Inventario Agencia Metropolitana S.A.
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Controlador_bodega extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('/Sucursal/Modelo_bodega', 'bodega'); // le pongo el nombre de la tabla asi se con que asunto trabajo
   $this->load->model("/Utilidades/Modelo_utilidades", 'utilidades');
        }

    public function Listar_Bodega_sucursal() {
        $id_sucursal = $this->session->userdata("id_sucursal");
        $list = $this->bodega->Obtener_bodega_sucursal($id_sucursal); //Nombre la tabla que le mande al constructor
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $user) {
            $no++;
            $row = array();  //lista de datos que van a la tabla 
            $row[] = $this->utilidades->nombre_sucursal($user->id_sucursal);
            $row[] = $this->utilidades->nombre_producto($user->id_producto);
            $row[] = $user->cantidad;
        
         //   $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="editar(' . "'" . $user->id_bodega . "'" . ')"><i class="glyphicon glyphicon-pencil"></i> Editar</a>
	//			  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="eliminar(' . "'" . $user->id_bodega . "'" . ')"><i class="glyphicon glyphicon-trash"></i> Eliminar</a>';
            $data[] = $row;
        }

       $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->bodega->contar_todo_sucursal($id_sucursal),
            "recordsFiltered" => $this->bodega->contar_filtrado($id_sucursal),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function editar($id) {
        $data = $this->bodega->Obtener_por_id($id);
        echo json_encode($data);
    }

    public function Guardar() {
         $this->_validate();
        $data = array(
            'rut' => $this->input->post('rut'),
            'nombre' => $this->input->post('nombre'),
            'correo' => $this->input->post('correo'),
            'clave' => $this->input->post('clave'), // Mismo nombre de la Bd se le Asigna el post
            'id_rol' => '4', // 5 Corresponde a un Usuario si Asignar
        );
        $this->usuario->Guardar($data);
        echo json_encode(array("status" => TRUE));
    }

    public function Actualizar() {
      $this->_validate();
        $data = array(
            'rut' => $this->input->post('rut'),
            'nombre' => $this->input->post('nombre'),
            'correo' => $this->input->post('correo'),
            'clave' => $this->input->post('clave'),
        );
        $this->usuario->Actualizar(array('id_usuario' => $this->input->post('id')), $data);
        echo json_encode(array("status" => TRUE));
    }

    public function Eliminar($id) {
        $this->bodega->Eliminar_por_id($id);
        echo json_encode(array("status" => TRUE));
    }

    private function _validate() {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('correo') == '') {
            $data['inputerror'][] = 'correo';
            $data['error_string'][] = 'Debes indicar un correo';
            $data['status'] = FALSE;
        }

        if ($this->input->post('nombre') == '') {
            $data['inputerror'][] = 'nombre';
            $data['error_string'][] = 'Nombre requerido';
            $data['status'] = FALSE;
        }
        if ($this->input->post('rut') == '') {
            $data['inputerror'][] = 'rut';
            $data['error_string'][] = 'Debes Indicar un Rut';
            $data['status'] = FALSE;
        }
        if ($this->input->post('clave') == '') {
            $data['inputerror'][] = 'clave';
            $data['error_string'][] = 'Clave Requerida';
            $data['status'] = FALSE;
        }
      


        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

}
