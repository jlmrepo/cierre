<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Controlador_producto extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('/Administrador/Clasificacion/Modelo_producto', 'producto');
        $this->load->model("/Utilidades/Modelo_utilidades", 'utilidades');
    }

    public function Listar_datos() {
        $list = $this->producto->obtener_tablacompleta();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $variable) {
            $no++;
            $row = array();
            $row[] = $variable->nombre;
            $row[] = $this->utilidades->nombre_proveedor_subfamilia($variable->id_sub_familia);
            $row[] = $this->utilidades->nombre_familia_subfamilia($variable->id_sub_familia);
            $row[] = $this->utilidades->nombre_subfamilia($variable->id_sub_familia);
            $row[] = $this->utilidades->nombre_tipo($variable->id_tipo);
            $row[] = $variable->precio;
            $row[] = $variable->codigo;
            $row[] = $this->utilidades->nombre_estado($variable->id_estado);
            $row[] = '<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="eliminar(' . "'" . $variable->id_producto . "'" . ')"><i class="glyphicon glyphicon-trash"></i>Eliminar</a>';
          /*  $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="editar(' . "'" . $variable->id_producto . "'" . ')"><i class="glyphicon glyphicon-pencil"></i> Editar</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="eliminar(' . "'" . $variable->id_producto . "'" . ')"><i class="glyphicon glyphicon-trash"></i>Eliminar</a>';
        
           */     $data[] = $row;
           
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->producto->contar_todo(),
            "recordsFiltered" => $this->producto->contar_filtrado(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function Agregar_datos() {
        //  $this->_validar();
        $data = array(
            'id_sub_familia' => $this->input->post('subfamilias'),
            'id_tipo' => $this->input->post('tipo'),
            'nombre' => $this->input->post('nombre'),
            'precio' => $this->input->post('precio'),
            'codigo' => $this->input->post('codigo'),
            'id_estado' => "1",
        );
        $this->producto->guardar($data);
        echo json_encode(array("status" => TRUE));
    }

    public function Editar_datos($id) {
        $data = $this->producto->obtener_por_id($id);
        $datos = (array) $data;
/*
        $nombre_categoria = array('nombre_proveedor' => $this->utilidades->nombre_proveedor($datos['id_proveedor']));
        $nombre_familia = array('nombre_familia' => $this->utilidades->nombre_familia($datos['id_familia']));
        $nombre_subFamilia = array('nombre_subfamilia' => $this->utilidades->nombre_familia($datos['id_familia']));

*/

        $nombre_proveedor = array('nombre_proveedor' => $this->utilidades->nombre_proveedor($datos['id_proveedor']));

        //   $nombre_categoria = array('nombre_categoria' => $this->utilidades->nombre_categoria($datos['id_proveedor']));
        $salida = array_merge($datos, $nombre_proveedor, $nombre_familia);
        echo json_encode($salida);
    }

    public function Actualizar_datos() {
        //  $this->_validar(); // Activado el Validar
        $data = array(
            'id_sub_familia' => $this->input->post('subfamilias'),
            'id_tipo' => $this->input->post('tipo'),
            'nombre' => $this->input->post('nombre'),
            'precio' => $this->input->post('precio'),
            'codigo' => $this->input->post('codigo'),
            'id_estado' => "1",
        );
        $this->producto->actualizar(array('id_producto' => $this->input->post('id')), $data);

        echo json_encode(array("status" => TRUE));
    }

    public function Eliminar_datos($id) {
        $this->producto->eliminar_por_id($id);
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
