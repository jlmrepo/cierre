<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Controlador_deposito extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('/Cierre/Modelo_deposito', 'deposito');

        $this->load->model("/Utilidades/Modelo_utilidades", 'utilidades');
    }

    public function Listar_deposito() {
     
        $id_cierre = $this->session->userdata("id_cierre");
        $id_categoria = $this->session->userdata('id_categoria');
        $list = $this->deposito->listar_cierre_categoria($id_categoria, $id_cierre);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $variable) {
            $no++;
            $row = array();  //lista de datos que van a la tabla 
            $row[] = $variable->id_tipo_deposito;
            $row[] = $variable->id_banco;
            $row[] = $variable->id_cierre; // para desarrollo no requiere mostrarlo en el sistema
            $row[] = $variable->id_categoria;
            $row[] = $variable->fecha;
            $row[] = $variable->boleta_deposito;
            $row[] = $variable->nro_cheque;
            $row[] = $variable->monto;
            $row[] = '<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="eliminar(' . "'" . $variable->id_deposito . "'" . ')"><i class="glyphicon glyphicon-trash"></i> Eliminar</a>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->deposito->contar_filtrado_categoria($id_categoria, $id_cierre),
            "recordsFiltered" => $this->deposito->contar_todo_categoria($id_categoria, $id_cierre),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function Agregar_deposito() {
        //$this->_validate();
        //Variables a Insertar
        $id_banco = $this->input->post('banco');
        $id_tipo = $this->input->post('tipo_deposito');
        $id_cierre = $this->session->userdata("id_cierre");
       
        $id_categoria = $this->session->userdata('id_categoria');
        $fecha = $this->session->userdata('fecha');
        $boleta_deposito = $this->input->post('boleta_deposito');
        $nro_cheque = $this->input->post('nro_cheque');
        $monto = $this->input->post('monto');

        $data = array(
            'id_banco' => $id_banco,
            'id_tipo_deposito' => $id_tipo,
            'id_cierre' => $id_cierre,
            'id_categoria' => $id_categoria,
            'fecha' => $fecha,
            'boleta_deposito' => $boleta_deposito,
            'nro_cheque' => $nro_cheque,
            'monto' => $monto,
        );

        $this->deposito->guardar($data);
        echo json_encode(array("status" => TRUE));
    }

    // Metodo para la suma de los depositos de las categorias
    public function suma_total() {
        $id_cierre = $this->session->userdata("id_cierre");
        $id_categoria = $this->session->userdata("id_categoria");
        $suma_total = $this->deposito->total_cierre_deposito($id_cierre, $id_categoria);
 // Eliminar en caso de falla
        if ($suma_total == null) {
            $suma_total = 0;
        }
//
        $data = array(
            "suma_total" => $suma_total,
        );
        echo json_encode($data);
    }

    public function Eliminar($id) {
        $this->deposito->Eliminar_por_id($id);
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
