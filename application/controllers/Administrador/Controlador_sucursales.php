<?php

/* Creacion 
 * Jorge Luis Maureira
 * Morrigan SPA
 * Desarrollo Agencia Metropolitana S.A.
 * 05-11-2017
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Controlador_sucursales extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('/Administrador/Modelo_sucursales', 'sucursal');
        $this->load->model("/Utilidades/Modelo_utilidades", 'utilidades');
        $this->load->model('/Administrador/Modelo_usuario', 'usuario');
        //   $this->load->model('/Mantenedores/Model_personal', 'personal');
        //  // Para la comuna , empresa, mandante
    }

    public function Listar_datos() {
        $list = $this->sucursal->obtener_datos(1); //Nombre la tabla que le mande al constructor
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $variable) {
            $no++;
            $row = array();  //lista de datos que van a la tabla 
            $row[] = $variable->nombre;
            $row[] = $variable->direccion;
            $row[] = $this->utilidades->nombre_comuna($variable->id_comuna);
            $row[] = $this->utilidades->nombre_usuario($variable->id_usuario);
            $row[] = $this->utilidades->nombre_empresa($variable->id_empresa);
            //   $row[] = $person->id_estado;
            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_person(' . "'" . $variable->id_sucursal . "'" . ')"><i class="glyphicon glyphicon-pencil"></i> Editar</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="desactivar(' . "'" . $variable->id_sucursal . "'" . ')"><i class="glyphicon glyphicon-trash"></i>Desactivar</a>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->sucursal->contar_todo(),
            "recordsFiltered" => $this->sucursal->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function Listar_datos_gestion() {
        $list = $this->sucursal->obtener_datos(1); //Nombre la tabla que le mande al constructor
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $variable) {
            $no++;
            $row = array();  //lista de datos que van a la tabla 
            $row[] = $variable->nombre;
            $row[] = $variable->direccion;
            $row[] = $this->utilidades->nombre_comuna($variable->id_comuna);
            $row[] = $this->utilidades->nombre_usuario($variable->id_usuario);
            $row[] = $this->utilidades->nombre_empresa($variable->id_empresa);
            //   $row[] = $person->id_estado;
            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="administrar(' . "'" . $variable->id_sucursal . "'" . ')"><i class="glyphicon glyphicon-wrench"></i> ADMINISTRAR</a>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->sucursal->contar_todo(),
            "recordsFiltered" => $this->sucursal->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function Listar_inactivos() {
        $list = $this->sucursal->obtener_datos(2); //Nombre la tabla que le mande al constructor
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $variable) {
            $no++;
            $row = array();  //lista de datos que van a la tabla 
            $row[] = $variable->nombre;
            $row[] = $variable->direccion;
            $row[] = $variable->utilidades->nombre_comuna($variable->id_comuna);
            $row[] = $variable->utilidades->nombre_usuario($variable->id_usuario);
            $row[] = $variable->utilidades->nombre_empresa($variable->id_empresa);
            //   $row[] = $person->id_estado;
            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_person(' . "'" . $variable->id_sucursal . "'" . ')"><i class="glyphicon glyphicon-pencil"></i> Editar</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="desactivar(' . "'" . $variable->id_sucursal . "'" . ')"><i class="glyphicon glyphicon-trash"></i>Desactivar</a>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->sucursal->contar_todo(),
            "recordsFiltered" => $this->sucursal->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function Editar_datos($id) {
        $data = $this->sucursal->obtener_por_id($id);
        $datos = (array) $data;
        $nombre_comuna = array('nombre_comuna' => $this->utilidades->nombre_comuna($datos['id_comuna']));
        $salida = array_merge($datos, $nombre_comuna);

        echo json_encode($salida);
    }

    public function Agregar_datos() {
        $this->_validar();
        $id_usuario = $this->input->post('usuario');
        $data = array(
            'nombre' => $this->input->post('nombre'),
            'id_comuna' => $this->input->post('comunas'),
            'id_usuario' => $id_usuario,
            'direccion' => $this->input->post('direccion'),
            'id_empresa' => $this->input->post('empresa'),
            'id_estado' => "1",
        );

        $this->sucursal->guardar($data);
        $this->usuario->cambiar_rol($id_usuario, 2); //2 es Rol de una Sucursal
        echo json_encode(array("status" => TRUE));
    }

    public function Actualizar_datos() {
        $this->_validar();
        $id_usuario = $this->input->post('usuario');
        $data = array(
            'nombre' => $this->input->post('nombre'),
            'id_comuna' => $this->input->post('comunas'),
            'id_usuario' => $id_usuario,
            'direccion' => $this->input->post('direccion'),
            'id_empresa' => $this->input->post('empresa'),
            'id_estado' => "1",
        );
        $usuario_remplazar = $this->sucursal->obtener_usuario_sucursal($this->input->post('id'));


        //metodo que con el id del local devuelva el anterior id_usuario para quitarle su rol;

        $this->sucursal->actualizar(array('id_sucursal' => $this->input->post('id')), $data);
        $this->usuario->cambiar_rol($usuario_remplazar, 4); // 4 Sin Asignar.
        $this->usuario->cambiar_rol($id_usuario, 2); // Ahora al usuario nuevo le doy los privilegios

        echo json_encode(array("status" => TRUE));
    }

    public function desactivar($id) {
        $this->sucursal->cambiar_estado($id, 2); //Seteo el Estado 2 Desactivado
        echo json_encode(array("status" => TRUE));
    }

    public function activar($id) {
        $this->sucursal->cambiar_estado($id, 1); //Seteo el Estado 1 Activado
        echo json_encode(array("status" => TRUE));
    }

    public function Eliminar_datos($id) {
        $this->sucursal->eliminar_por_id($id);
        echo json_encode(array("status" => TRUE));
    }

    private function _validar() {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;



        if ($this->input->post('nombre') == '') {
            $data['inputerror'][] = 'nombre';
            $data['error_string'][] = 'Falta Ingresar Nombre Sucursal';
            $data['status'] = FALSE;
        }
        if ($this->input->post('direccion') == '') {
            $data['inputerror'][] = 'direccion';
            $data['error_string'][] = 'Falta Ingresar Direccion';
            $data['status'] = FALSE;
        }
        if ($this->input->post('comunas') == 0) {
            $data['inputerror'][] = 'comunas';
            $data['error_string'][] = 'Debe Selecciona una Comuna';
            $data['status'] = FALSE;
        }
        if ($this->input->post('empresa') == 0) {
            $data['inputerror'][] = 'empresa';
            $data['error_string'][] = 'Debe Seleccionar una Empresa';
            $data['status'] = FALSE;
        }
        if ($this->input->post('usuario') == 0) {
            $data['inputerror'][] = 'usuario';
            $data['error_string'][] = 'Deber Asignarle un Usuario para operar como Jefe de Sucursal';
            $data['status'] = FALSE;
        }


        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

}
