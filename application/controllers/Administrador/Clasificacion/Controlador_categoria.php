<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Controlador_categoria extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('/Administrador/Clasificacion/Modelo_categoria', 'categoria');
    }
    public function Listar_datos() {
        $list = $this->categoria->obtener_tablacompleta();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $variable) {
            $no++;
            $row = array();  
       //     $row[] = $variable->id_categoria;
            $row[] = $variable->nombre;
            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="editar(' . "'" . $variable->id_categoria . "'" . ')"><i class="glyphicon glyphicon-pencil"></i> Editar</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="eliminar(' . "'" . $variable->id_categoria . "'" . ')"><i class="glyphicon glyphicon-trash"></i>Eliminar</a>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->categoria->contar_todo(),
            "recordsFiltered" => $this->categoria->contar_filtrado(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function Editar_datos($id) {
        $data = $this->categoria->obtener_por_id($id);
echo json_encode($data);
    }

    public function Agregar_datos() {
      $this->_validar();
        $data = array(
            'nombre' => $this->input->post('nombre'),
        );
        $this->categoria->guardar($data);
        echo json_encode(array("status" => TRUE));
    }

    public function Actualizar_datos() {
     $this->_validar(); // Activado el Validar
        $data = array(      
            'nombre' => $this->input->post('nombre'),

        );
        $this->categoria->actualizar(array('id_categoria' => $this->input->post('id')), $data);

        echo json_encode(array("status" => TRUE));
    }

    public function Eliminar_datos($id) {
        $this->categoria->eliminar_por_id($id);
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
