<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Controlador_proveedor extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('/Administrador/Clasificacion/Modelo_proveedor', 'proveedor');
        $this->load->model("/Utilidades/Modelo_utilidades", 'utilidades');
    }

    public function Listar_datos() {
        $list = $this->proveedor->obtener_tablacompleta();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $variable) {
            $no++;
            $row = array();
            $row[] = $this->utilidades->nombre_categoria($variable->id_categoria);
            $row[] = $variable->nombre;
            $row[] = $variable->razon_social;
            $row[] = $variable->rut_razon;
            $row[] = $variable->giro;
            $row[] = $variable->direccion;
            $row[] = $this->utilidades->nombre_comuna($variable->id_comuna);
            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="editar(' . "'" . $variable->id_proveedor . "'" . ')"><i class="glyphicon glyphicon-pencil"></i> Editar</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="eliminar(' . "'" . $variable->id_proveedor . "'" . ')"><i class="glyphicon glyphicon-trash"></i>Eliminar</a>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->proveedor->contar_todo(),
            "recordsFiltered" => $this->proveedor->contar_filtrado(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function Editar_datos($id) {
        $data = $this->proveedor->obtener_por_id($id);
         $datos = (array) $data;
        $nombre_comuna = array('nombre_comuna' => $this->utilidades->nombre_comuna($datos['id_comuna']));
        $salida = array_merge($datos, $nombre_comuna);

        echo json_encode($salida);
    }

    public function Agregar_datos() {
     //   $this->_validar(); Desactivado por que son muchos datos asi que luego se hace la validacion
        $data = array(
            'id_categoria' => $this->input->post('categoria'),
            'nombre' => $this->input->post('nombre'),
            'razon_social' => $this->input->post('razon_social'),
            'rut_razon' => $this->input->post('rut_razon'),
            'giro' => $this->input->post('giro'),
            'direccion' => $this->input->post('direccion'),
            'id_comuna' => $this->input->post('comunas'),
        );
        $this->proveedor->guardar($data);
        echo json_encode(array("status" => TRUE));
    }

    public function Actualizar_datos() {
      //  $this->_validar(); // Activado el Validar
        $data = array(
           'id_categoria' => $this->input->post('categoria'),
            'nombre' => $this->input->post('nombre'),
            'razon_social' => $this->input->post('razon_social'),
            'rut_razon' => $this->input->post('rut_razon'),
            'giro' => $this->input->post('giro'),
            'direccion' => $this->input->post('direccion'),
            'id_comuna' => $this->input->post('comunas'),
        );
        $this->proveedor->actualizar(array('id_proveedor' => $this->input->post('id')), $data);

        echo json_encode(array("status" => TRUE));
    }

    public function Eliminar_datos($id) {
        $this->proveedor->eliminar_por_id($id);
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
