<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Modelo_masivo extends CI_Model {

    var $table = 'masivo';
    var $column_order = array('id_masivo', 'id_caja', null);
    var $column_search = array('id_masivo', 'id_caja');
    var $order = array('id_masivo' => 'desc');

// Con el constructor carga la BD
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    private function obtener_masivo_caja($id_caja, $id_familia) {
        $this->db->from($this->table);
        $this->db->where('id_caja', $id_caja);
        $this->db->where('id_familia', $id_familia);
        $i = 0;
        foreach ($this->column_search as $item) { // Columna
            if ($_POST['search']['value']) { // Si el datatable envia Post para busqueda
                if ($i === 0) { // Primer Ciclo
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']); // Si la busqueda  completa 
                } else {
                    $this->db->or_like($item, $_POST['search']['value']); // Si la busqueda por fragmento
                }
                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //cierre bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) { // Aqui se procesa la orden
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function listar_masivo_caja($id_caja, $id_familia) {
        $this->obtener_masivo_caja($id_caja, $id_familia);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function contar_filtrado_caja($id_caja, $id_familia) {
        $this->obtener_masivo_caja($id_caja, $id_familia);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function contar_todo_caja($id_caja, $id_familia) {
        $this->db->from($this->table);
        $this->db->where('id_caja', $id_caja);
        $this->db->where('id_familia', $id_familia);
        return $this->db->count_all_results();
    }

    public function obtener_por_id($id) {
        $this->db->from($this->table);
        $this->db->where('id_masivo', $id);
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
        $this->db->where('id_masivo', $id);
        $this->db->delete($this->table);
    }

    function total_cierre_deposito($id_caja, $id_cierre) {
        $this->db->select_sum('monto');
        $this->db->from($this->table);
        $this->db->where('id_caja', $id_caja);
        $this->db->where('id_cierre', $id_cierre);
        $query = $this->db->get();
        $result = $query->result();
        return $result[0]->monto;
    }

    public function check_producto_masivo($id_cierre, $id_caja, $id_producto, $id_familia) {
        $this->db->from($this->table);
        $this->db->where('id_cierre', $id_cierre);
        $this->db->where('id_caja', $id_caja);
        $this->db->where('id_producto', $id_producto);
        $this->db->where('id_familia', $id_familia);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 0) {
            return true;
        } else {
            return false;
        }
    }

    // Si el cierre y el producto se encuentra en el detalle uso el de abajo
    public function obtener_detalle_masivo($id_cierre, $id_caja, $id_producto, $id_familia) {
        $this->db->from($this->table);
        $this->db->where('id_cierre', $id_cierre);
        $this->db->where('id_caja', $id_caja);
        $this->db->where('id_producto', $id_producto);
        $this->db->where('id_familia', $id_familia);
        $query = $this->db->get();
        return $query->result(); // Ojo que lo envio como result no row();
    }

    public function obtener_masivo($id_caja, $id_producto, $id_familia) {
        $this->db->from($this->table);
        $this->db->where('id_caja', $id_caja);
        $this->db->where('id_producto', $id_producto);
        $this->db->where('id_familia', $id_familia);
        $this->db->order_by("id_cierre", "asc");
        $query = $this->db->get();
        return $query->result(); // Ojo que lo envio como result no row();
    }

    public function obtener_id_masivo($id_cierre, $id_caja, $id_producto) {
        $this->db->from($this->table);
        $this->db->where('id_cierre', $id_cierre);
        $this->db->where('id_caja', $id_caja);
        $this->db->where('id_producto', $id_producto);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $salida = '';
        foreach ($resultado as $person) {
            $salida = $person->id_masivo;
        }
        return $salida;
    }

    public function obtener_cantidad($id_masivo) {
        $this->db->from($this->table);
        $this->db->where('id_masivo', $id_masivo);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $salida = '';
        foreach ($resultado as $person) {
            $salida = $person->cantidad;
        }
        return $salida;
    }

}
