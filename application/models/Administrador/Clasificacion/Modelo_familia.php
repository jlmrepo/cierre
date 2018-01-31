<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Modelo_familia extends CI_Model {

    var $table = 'familia';
    var $column_order = array('id_familia', null); 
    var $column_search = array('id_familia','nombre');
    var $order = array('id_familia' => 'desc');

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    private function get_query() {
        $this->db->from($this->table);
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

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function obtener_tablacompleta() {
        $this->get_query();
        if ($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
        }
      


    function contar_filtrado() { 
        $this->get_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function contar_todo() {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function obtener_por_id($id) {
        $this->db->from($this->table);
        $this->db->where('id_familia', $id);
        $query = $this->db->get();

        return $query->row();
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
        $this->db->where('id_familia', $id);
        $this->db->delete($this->table);
    }
      public function obtener_familia_por_proveedor($id_proveedor) {
        $this->db->from($this->table);
        $this->db->where('id_proveedor', $id_proveedor);

        $query = $this->db->get();
        return $query->result(); //envio como result no row();
    }

}
