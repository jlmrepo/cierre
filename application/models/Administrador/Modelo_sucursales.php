<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Modelo_sucursales extends CI_Model {

    var $table = 'sucursal';
    var $column_order = array('id_sucursal', 'nombre', null);
    var $column_search = array('id_sucural', 'nombre');
    var $order = array('id_sucursal' => 'desc');

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
/*
    private function _get_datatables_query() {

        $this->db->from($this->table);

        $i = 0;

        foreach ($this->column_search as $item) { // loop column 
            if ($_POST['search']['value']) { // if datatable send POST for search

                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
 
    function obtener_tablacompleta() {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
*/
    
    
    private function query_estado($estado) {
        $this->db->from($this->table);
        $this->db->where('id_estado', $estado);
        $i = 0;
        foreach ($this->column_search as $item) { 
            if ($_POST['search']['value']) { 
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->column_search) - 1 == $i) 
                    $this->db->group_end();
            }
            $i++;
        }
        if (isset($_POST['order'])) { 
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function obtener_datos($estado) {
        $this->query_estado($estado);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }


    function count_filtered() { // Este solo cuenta los activos crear uno mas para inactivos
        $this->query_estado(1);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function contar_todo() {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function obtener_por_id($id) {
        $this->db->from($this->table);
        $this->db->where('id_sucursal', $id);
        $query = $this->db->get();

        return $query->row();
    }

    public function obtener_sucursal($id_sucursal) {
        $this->db->from($this->table);
        $this->db->where('id_sucursal', $id_sucursal);

        $query = $this->db->get();
        return $query->result(); //envio como result no row();
    }
      public function obtener_all_sucursal() {
        $this->db->from($this->table);

        $query = $this->db->get();
        return $query->result(); //envio como result no row();
    }

    public function guardar($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function actualizar($where, $data) {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }

    public function eliminar_por_id($id) {
        $this->db->where('id_sucursal', $id);
        $this->db->delete($this->table);
    }

    public function cambiar_estado($id,$estado) {
        $data = array(
            'id_estado' => $estado //, Solo seteo el estado
        );
        $this->db->where('id_sucursal', $id);
        $this->db->update($this->table, $data);
    }
    public function obtener_usuario_sucursal($id_sucursal) {
    $this->db->from($this->table);
        $this->db->where('id_sucursal', $id_sucursal);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $usuario= '';
        foreach ($resultado as $person) {
            $usuario = $person->id_usuario;
        }
        return $usuario;
    }
      public function obtener_por_usuario($id_usuario) {
        $this->db->from($this->table);
        $this->db->where('id_usuario', $id_usuario);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $nombre = '';
        foreach ($resultado as $person) {
            $nombre = $person->id_sucursal;
        }
        return $nombre;
    }

}
