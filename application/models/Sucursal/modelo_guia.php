<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Modelo_guia extends CI_Model {

    var $table = 'guia';
    var $column_order = array('numero_guia', 'fecha_guia', null);
    var $column_search = array('numero_guia', 'fecha_guia');
    var $order = array('id_guia' => 'desc');

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

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

    private function Query_Sucursal($id_sucursal) {
        $this->db->from($this->table);
        $this->db->where('id_sucursal', $id_sucursal);
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

    function Obtener_guias_sucursal($id_sucursal) {
        $this->Query_Sucursal($id_sucursal);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function obtener_tablacompleta() {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function contar_filtrado($id_sucursal) {
        $this->Query_Sucursal($id_sucursal);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function contar_todo_sucursal($id_sucursal) {
        $this->db->from($this->table);
        $this->db->where('id_sucursal', $id_sucursal);
        return $this->db->count_all_results();
    }

    function count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function contar_todo() {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function obtener_por_id($id) {
        $this->db->from($this->table);
        $this->db->where('id_guia', $id);
        $query = $this->db->get();

        return $query->row();
    }

    public function obtener_guia($id_guia) {
        $this->db->from($this->table);
        $this->db->where('id_guia', $id_guia);

        $query = $this->db->get();

        return $query->result(); // Ojo que lo envio como result no row();
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
        $this->db->where('id_guia', $id);
        $this->db->delete($this->table);
    }

    public function cambiar_estado($id, $estado) {
        $data = array(
            'id_estado' => $estado //, Solo seteo el estado
        );
        $this->db->where('id_guia', $id);
        $this->db->update($this->table, $data);
    }
    
    public function id_proveedor($id) {

        $this->db->where('id_guia', $id);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $salida = '';
        foreach ($resultado as $variable) {
            $salida = $variable->id_proveedor;
        }
        return $salida;
    }

}
