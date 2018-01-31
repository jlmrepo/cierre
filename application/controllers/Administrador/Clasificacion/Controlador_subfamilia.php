<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Controlador_subfamilia extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('/Administrador/Clasificacion/Modelo_subfamilia', 'subfamilia');
        $this->load->model("/Utilidades/Modelo_utilidades", 'utilidades');
    }

    public function Listar_datos() {
        $list = $this->subfamilia->obtener_tablacompleta();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $variable) {
            $no++;
            $row = array();
            $row[] = $variable->nombre;
            $row[] = $this->utilidades->nombre_proveedor($variable->id_proveedor);
            $row[] = $this->utilidades->nombre_familia($variable->id_familia);
            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="editar(' . "'" . $variable->id_sub_familia . "'" . ')"><i class="glyphicon glyphicon-pencil"></i> Editar</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="eliminar(' . "'" . $variable->id_sub_familia . "'" . ')"><i class="glyphicon glyphicon-trash"></i>Eliminar</a>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->subfamilia->contar_todo(),
            "recordsFiltered" => $this->subfamilia->contar_filtrado(),
            "data" => $data,
        );
        echo json_encode($output);
    }
    public function Editar_datos($id) {
        $data = $this->subfamilia->obtener_por_id($id);
        $datos = (array) $data;
        $nombre_proveedor = array('nombre_proveedor' => $this->utilidades->nombre_proveedor($datos['id_proveedor']));
        $nombre_familia = array('nombre_familia' => $this->utilidades->nombre_familia($datos['id_familia']));
        //   $nombre_categoria = array('nombre_categoria' => $this->utilidades->nombre_categoria($datos['id_proveedor']));
        $salida = array_merge($datos, $nombre_proveedor, $nombre_familia);
        echo json_encode($salida);
    }
    public function Agregar_datos() {
        //  $this->_validar();
        $data = array(
            'id_proveedor' => $this->input->post('proveedores'),
            'id_familia' => $this->input->post('familias'),
            'nombre' => $this->input->post('nombre'),
        );
        $this->subfamilia->guardar($data);
        echo json_encode(array("status" => TRUE));
    }

    public function Actualizar_datos() {
        //  $this->_validar(); // Activado el Validar
        $data = array(
             'id_proveedor' => $this->input->post('proveedores'),
            'id_familia' => $this->input->post('familias'),
            'nombre' => $this->input->post('nombre'),
        );
        $this->subfamilia->actualizar(array('id_sub_familia' => $this->input->post('id')), $data);

        echo json_encode(array("status" => TRUE));
    }

    public function Eliminar_datos($id) {
        $this->subfamilia->eliminar_por_id($id);
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
