<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/* Modelo Detalle Cierre
 * Morrigan SpA
 * 17-12-2017
 * Gestionar cambios en cuanto a estado o generar limpieza de tablas de origen temporal
 * Observaciones
 *
 */

class Modelo_deposito extends CI_Model {

    var $table = 'deposito';
    var $column_order = array('id_deposito', 'id_cierre', null);
    var $column_search = array('id_deposito', 'id_cierre');
    var $order = array('id_deposito' => 'desc');

// Con el constructor carga la BD
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

// Consulta de toda la tabla
    private function obtener_consulta_datatable() {
        $this->db->from($this->table);
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

    // Este llama la tabla completa que la procesamos en la funcion "obtener_consulta_datatable"
    function obtener_tablacompleta() {
        $this->obtener_consulta_datatable();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    // Metodos para contar todo y este manipula la paguinacion
    function contar_todo_filtrado() {
        $this->obtener_consulta_datatable();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function contar_todo() {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // Ahora la Consulta Datatable separado por cierre segun el requerimiento

    private function obtener_deposito_cierre($id_categoria, $id_cierre) {
        $this->db->from($this->table);
        $this->db->where('id_categoria', $id_categoria); // Con solo esto se filtra :D
        $this->db->where('id_cierre', $id_cierre); // Con solo esto se filtra :D
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

    // Metodo Accesador para implementar el consulta obtener_deposito_cierre
    function listar_cierre_categoria($id_categoria, $id_cierre) {
        $this->obtener_deposito_cierre($id_categoria, $id_cierre);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    // Metodos para paguinacion con filtrado de categoria y cierre

    function contar_filtrado_categoria($id_categoria, $id_cierre) {
        $this->obtener_deposito_cierre($id_categoria, $id_cierre);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function contar_todo_categoria($id_categoria, $id_cierre) {
        $this->db->from($this->table);
        $this->db->where('id_categoria', $id_categoria);
        $this->db->where('id_cierre', $id_cierre);

        return $this->db->count_all_results();
    }

    public function obtener_por_id($id) {
        $this->db->from($this->table);
        $this->db->where('id_deposito', $id);
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
        $this->db->where('id_deposito', $id);
        $this->db->delete($this->table);
    }
// Suma los depositos del cierre por categoria y cierre
    function total_cierre_deposito($id_cierre, $id_categoria) {
        $this->db->select_sum('monto');
        $this->db->from($this->table);
        $this->db->where('id_cierre', $id_cierre);
        $this->db->where('id_categoria', $id_categoria);
        $query = $this->db->get();
        $result = $query->result();
        return $result[0]->monto;
    }
    

    

}
