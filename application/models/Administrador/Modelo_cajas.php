<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Modelo_cajas extends CI_Model {

    var $table = 'caja';
    var $column_order = array('id_caja', null); // ver como filtrar por sucursal 
    var $column_search = array('id_caja');
    var $order = array('id_caja' => 'desc');

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

    private function Query_caja_sucursal($id_sucursal) {
        $this->db->from($this->table);
        $this->db->where('id_sucursal', $id_sucursal);

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

    function obtener_tabla_caja_sucursal($id_sucursal) {
        $this->Query_caja_sucursal($id_sucursal);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

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

    function count_filtered() {
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
        $this->db->where('id_caja', $id);
        $query = $this->db->get();

        return $query->row();
    }

    public function obtener_caja_por_sucursal($id_sucursal) {
        $this->db->from($this->table);
        $this->db->where('id_sucursal', $id_sucursal);

        $query = $this->db->get();
        return $query->result(); //envio como result no row();
    }

    public function obtener_sucursal_caja($id_caja) {
        $this->db->from($this->table);
        $this->db->where('id_caja', $id_caja);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $variable = '';
        foreach ($resultado as $person) {
            $variable = $person->id_sucursal;
        }
        return $variable;
    }

    // Revisar la wea de abajo si alguien lo usa
    public function obtener_sucursal($id_sucursal) {
        $this->db->from($this->table);
        $this->db->where('id_caja', $id_sucursal);

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
        $this->db->where('id_caja', $id);
        $this->db->delete($this->table);
    }

    public function cambiar_estado($id, $estado) {
        $data = array(
            'id_estado' => $estado //, Solo seteo el estado
        );
        $this->db->where('id_caja', $id);
        $this->db->update($this->table, $data);
    }

}
