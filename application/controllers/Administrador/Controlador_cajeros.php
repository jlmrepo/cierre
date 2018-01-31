<?php

/* Creacion 
 * Jorge Luis Maureira
 * Morrigan SPA
 * Desarrollo Agencia Metropolitana S.A.
 * 23-11-2017
 * Revisar si al editar el usuario cambiar el rol a cajero
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Controlador_cajeros extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('/Administrador/Modelo_cajero', 'cajeros');
        $this->load->model("/Utilidades/Modelo_utilidades", 'utilidades');
        $this->load->model('/Administrador/Modelo_usuario', 'usuario');
    }

    //Tabla usuario_caja es la tabla cajeros

    public function Listar_datos() {
        $list = $this->cajeros->obtener_tablacompleta();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $variable) {
            $no++;

            $row = array();  //lista de datos que van a la tabla 
            $row[] = $this->utilidades->nombre_caja($variable->id_caja);
            $row[] = $this->utilidades->obtener_nombre_sucursal($variable->id_caja);
            $row[] = $this->utilidades->nombre_usuario($variable->id_usuario);
            $row[] = $this->utilidades->nombre_estado($variable->id_estado);



            //   $row[] = $this->utilidades->nombre_sucursal($variable->id_sucursal);
            //    $row[] = $this->utilidades->nombre_usuario($variable->id_usuario);
            //   $row[] = $person->id_estado;
            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_person(' . "'" . $variable->id_usuario_caja . "'" . ')"><i class="glyphicon glyphicon-pencil"></i> Editar</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="desactivar(' . "'" . $variable->id_usuario_caja . "'" . ')"><i class="glyphicon glyphicon-trash"></i>Desactivar</a>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->cajeros->contar_todo(),
            "recordsFiltered" => $this->cajeros->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function Listar_inactivos() {
        $list = $this->cajero->obtener_datos(2); //Nombre la tabla que le mande al constructor
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
            "recordsTotal" => $this->cajero->contar_todo(),
            "recordsFiltered" => $this->cajero->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function Editar_datos($id) {
        $data = $this->cajero->obtener_por_id($id);
        //   $datos = (array) $data;
        // $nombre_comuna = array('nombre_comuna' => $this->utilidades->nombre_comuna($datos['id_comuna']));
        //   $salida = array_merge($data);

        echo json_encode($data);
    }

    public function Agregar_datos() {
       $this->_validar();
        $caja = $this->input->post('cajas');
        $usuario = $this->input->post('usuario');
        $data = array(
            'id_caja' => $caja,
            'id_usuario' => $usuario,
            'id_estado' => "1",
        );
        $this->cajeros->guardar($data);

        $this->usuario->cambiar_rol($usuario, 3); //3 es Rol de un Cajero
        echo json_encode(array("status" => TRUE));
    }

    public function Actualizar_datos() {
       $this->_validar();
        $data = array(
            'id_caja' => $this->input->post('cajas'),
            'id_usuario' => $this->input->post('usuario'),
            'id_estado' => "1",
        );
        $this->cajero->actualizar(array('id_usuario_caja' => $this->input->post('id')), $data);

        echo json_encode(array("status" => TRUE));
    }

    public function desactivar($id) {
        $this->cajero->cambiar_estado($id, 2); //Seteo el Estado 2 Desactivado
        echo json_encode(array("status" => TRUE));
    }

    public function activar($id) {
        $this->cajero->cambiar_estado($id, 1); //Seteo el Estado 1 Activado
        echo json_encode(array("status" => TRUE));
    }

    public function Eliminar_datos($id) {
        $this->cajero->eliminar_por_id($id);
        echo json_encode(array("status" => TRUE));
    }

    private function _validar() {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;



        if ($this->input->post('sucursal') == 0) {
            $data['inputerror'][] = 'sucursal';
            $data['error_string'][] = 'Debe Seleccionar un Sucursal';
            $data['status'] = FALSE;
        }
        if ($this->input->post('cajas') == 0) {
            $data['inputerror'][] = 'cajas';
            $data['error_string'][] = 'Debe Seleccionar una Caja para Operar';
            $data['status'] = FALSE;
        }
         if ($this->input->post('usuario') == 0) {
            $data['inputerror'][] = 'usuario';
            $data['error_string'][] = 'Debe Asignarle un Usuario';
            $data['status'] = FALSE;
        }



        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

}
