<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/* Modelo Detalle Cierre
 * Morrigan SpA
 * 17-12-2017
 * Gestionar cambios en cuanto a estado o generar limpieza de tablas de origen temporal
 * Observaciones
 *
 */

class Modelo_descuento_cierre extends CI_Model {

    var $table = 'descuento';
    var $column_order = array('id_descuento', 'id_cierre', null);
    var $column_search = array('id_descuento', 'id_cierre');
    var $order = array('id_descuento' => 'desc');

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

    // Ahora la Consulta Datatable separado por familia segun el requerimiento

    private function obtener_consulta_proveedor_datatable($id_proveedor, $id_cierre) {
        $this->db->from($this->table);
        $this->db->where('id_proveedor', $id_proveedor); // Con solo esto se filtra :D
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

    // Metodo Accesador para implementar el consulta obtener_consulta_familia_datatable
    function obtener_proveedor_cierre($id_proveedor, $id_cierre) {
        $this->obtener_consulta_proveedor_datatable($id_proveedor, $id_cierre);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    // Metodos para paguinacion con filtrado de proveedor

    function contar_filtrado_proveedor($id_proveedor, $id_cierre) {
        $this->obtener_consulta_proveedor_datatable($id_proveedor, $id_cierre);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function contar_todo_proveedor($id_proveedor, $id_cierre) {
        $this->db->from($this->table);
        $this->db->where('id_proveedor', $id_proveedor);
        $this->db->where('id_cierre', $id_cierre);

        return $this->db->count_all_results();
    }

    public function obtener_por_id($id) {
        $this->db->from($this->table);
        $this->db->where('id_descuento', $id);
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
        $this->db->where('id_descuento', $id);
        $this->db->delete($this->table);
    }

// Suma lo que tiene actualmente el cierre clasificado por proveedor
    function total_cierre_familia($id_cierre, $id_proveedor) {
        $this->db->select_sum('monto');
        $this->db->from($this->table);
        $this->db->where('id_cierre', $id_cierre);
        $this->db->where('id_proveedor', $id_proveedor);
        $query = $this->db->get();
        $result = $query->result();
        return $result[0]->total;
    }

    // No creo que use el metodo de abajo pero lo defino igual
    public function obtener_detalle($id_detalle_cierre) {
        $this->db->from($this->table);
        $this->db->where('id_detalle_cierre', $id_detalle_cierre);
        $query = $this->db->get();
        return $query->result(); // Ojo que lo envio como result no row();
    }

    //  * Devuelve Verdadero si se encuentra el id_detalle_cierre
    public function check_descuento_cierre($id_cierre, $id_detalle_descuento) {
        $this->db->from($this->table);
        $this->db->where('id_cierre', $id_cierre);
        $this->db->where('id_detalle_descuento', $id_detalle_descuento);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 0) {
            return true;
        } else {
            return false;
        }
    }
    
    // Si el cierre y el id_detalle_descuento se encuentra en el descuento uso el de abajo
    public function obtener_descuento_cierre($id_cierre, $id_detalle_descuento) {
        $this->db->from($this->table);
        $this->db->where('id_cierre', $id_cierre);
        $this->db->where('id_detalle_descuento', $id_detalle_descuento);
        $query = $this->db->get();
        return $query->result(); // Ojo que lo envio como result no row();
    }

}
