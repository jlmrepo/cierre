<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Controlador_familia extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('/Administrador/Clasificacion/Modelo_familia', 'familia');
          $this->load->model("/Utilidades/Modelo_utilidades", 'utilidades');
    }

    public function Listar_datos() {
        $list = $this->familia->obtener_tablacompleta();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $variable) {
            $no++;

            $row = array();
            $row[] = $this->utilidades->nombre_proveedor($variable->id_proveedor);
            $row[] = $variable->nombre;
            $row[] = $this->utilidades->nombre_tipo($variable->id_tipo);

            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="editar(' . "'" . $variable->id_familia . "'" . ')"><i class="glyphicon glyphicon-pencil"></i> Editar</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="eliminar(' . "'" . $variable->id_familia . "'" . ')"><i class="glyphicon glyphicon-trash"></i>Eliminar</a>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->familia->contar_todo(),
            "recordsFiltered" => $this->familia->contar_filtrado(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function Editar_datos($id) {
        $data = $this->familia->obtener_por_id($id);
        $datos = (array) $data;
        $nombre_proveedor = array('nombre_proveedor' => $this->utilidades->nombre_proveedor($datos['id_proveedor']));
        $salida = array_merge($datos, $nombre_proveedor);
        echo json_encode($salida);
    }

    public function Agregar_datos() {
        //  $this->_validar();
        $data = array(
            'nombre' => $this->input->post('nombre'),
            'id_proveedor' => $this->input->post('proveedores'),
            'id_tipo' => $this->input->post('tipo'),
        );
        $this->familia->guardar($data);
        echo json_encode(array("status" => TRUE));
    }

    public function Actualizar_datos() {
        //  $this->_validar(); // Activado el Validar
        $data = array(
            'nombre' => $this->input->post('nombre'),
            'id_proveedor' => $this->input->post('proveedores'),
            'id_tipo' => $this->input->post('tipo'),
        );
        $this->familia->actualizar(array('id_familia' => $this->input->post('id')), $data);

        echo json_encode(array("status" => TRUE));
    }

    public function Eliminar_datos($id) {
        $this->familia->eliminar_por_id($id);
        echo json_encode(array("status" => TRUE));
    }

    private function _validar() {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

// Solo tengo un campo asi que ese valido

        if ($this->input->post('nombre') == '') {
            $data['inputerror'][] = 'nombre';
            $data['error_string'][] = 'Nombre Categoria Requerido';
            $data['status'] = FALSE;
        }



        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

}
