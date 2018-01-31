<?php

/* Creacion 
 * Jorge Luis Maureira
 * Morrigan SPA
 * Desarrollo Agencia Metropolitana S.A.
 * 05-11-2017
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Controlador_cajas extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('/Administrador/Modelo_cajas', 'caja');
        $this->load->model("/Utilidades/Modelo_utilidades", 'utilidades');
    }

    public function Listar_datos() {
        $list = $this->caja->obtener_tablacompleta();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $variable) {
            $no++;
            $row = array();  //lista de datos que van a la tabla 
            $row[] = $variable->nombre;
            $row[] = $this->utilidades->nombre_sucursal($variable->id_sucursal);
        //    $row[] = $this->utilidades->nombre_usuario($variable->id_usuario);
            $row[] =$this->utilidades->nombre_estado ($variable->id_estado);


            //   $row[] = $person->id_estado;
            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_person(' . "'" . $variable->id_caja . "'" . ')"><i class="glyphicon glyphicon-pencil"></i> Editar</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="desactivar(' . "'" . $variable->id_caja . "'" . ')"><i class="glyphicon glyphicon-trash"></i>Desactivar</a>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->caja->contar_todo(),
            "recordsFiltered" => $this->caja->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

     public function Listar_datos_gestion() {
        $list = $this->caja->obtener_tablacompleta();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $variable) {
            $no++;
            $row = array();  //lista de datos que van a la tabla 
            $row[] = $variable->nombre;
            $row[] = $this->utilidades->nombre_sucursal($variable->id_sucursal);
        //    $row[] = $this->utilidades->nombre_usuario($variable->id_usuario);
            $row[] =$this->utilidades->nombre_estado ($variable->id_estado);


            //   $row[] = $person->id_estado;
            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="administrar(' . "'" . $variable->id_caja . "'" . ')"><i class="glyphicon glyphicon-pencil"></i>Administrar</a>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->caja->contar_todo(),
            "recordsFiltered" => $this->caja->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    public function Listar_inactivos() {
        $list = $this->caja->obtener_datos(2); //Nombre la tabla que le mande al constructor
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $variable) {
            $no++;
            $row = array();  //lista de datos que van a la tabla 
            $row[] = $variable->nombre;
            $row[] = $variable->direccion;
            $row[] = $variable->utilidades->nombre_comuna($variable->id_comuna);
            $row[] = $variable->utilidades->nombre_usuario($variable->id_usuario);
            $row[] = $variable->utilidades->nombre_empresa($variable->id_empresas);
            //   $row[] = $person->id_estado;
            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_person(' . "'" . $variable->id_local . "'" . ')"><i class="glyphicon glyphicon-pencil"></i> Editar</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="desactivar(' . "'" . $variable->id_local . "'" . ')"><i class="glyphicon glyphicon-trash"></i>Desactivar</a>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->caja->contar_todo(),
            "recordsFiltered" => $this->caja->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function Editar_datos($id) {
        $data = $this->caja->obtener_por_id($id);
     //   $datos = (array) $data;
       // $nombre_comuna = array('nombre_comuna' => $this->utilidades->nombre_comuna($datos['id_comuna']));
     //   $salida = array_merge($data);

        echo json_encode($data);
    }

    public function Agregar_datos() {
        $this->_validar();
        $data = array(
            'id_sucursal' => $this->input->post('sucursal'),
          //  'id_usuario' => $this->input->post('usuario'),
            'nombre' => $this->input->post('nombre'),
            'id_estado' => "1",
        );
        $this->caja->guardar($data);
        echo json_encode(array("status" => TRUE));
    }

    public function Actualizar_datos() {
      $this->_validar();
        $data = array(
             'id_local' => $this->input->post('sucursal'),
            'id_usuario' => $this->input->post('usuario'),
            'nro_caja' => $this->input->post('ncaja'),
            'id_estado' => "1",
        );
        $this->caja->actualizar(array('id_caja' => $this->input->post('id')), $data);

        echo json_encode(array("status" => TRUE));
    }

    public function desactivar($id) {
        $this->caja->cambiar_estado($id, 2); //Seteo el Estado 2 Desactivado
        echo json_encode(array("status" => TRUE));
    }

    public function activar($id) {
        $this->caja->cambiar_estado($id, 1); //Seteo el Estado 1 Activado
        echo json_encode(array("status" => TRUE));
    }

    public function Eliminar_datos($id) {
        $this->caja->eliminar_por_id($id);
        echo json_encode(array("status" => TRUE));
    }

    private function _validar() {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;



        if ($this->input->post('nombre') == '') {
            $data['inputerror'][] = 'nombre';
            $data['error_string'][] = 'Falta Ingresar un Nombre a la Caja';
            $data['status'] = FALSE;
        }
        if ($this->input->post('sucursal') == 0) {
            $data['inputerror'][] = 'sucursal';
            $data['error_string'][] = 'Debe Seleccionar una Sucursal para Asignar esta Caja';
            $data['status'] = FALSE;
        }



        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

}
