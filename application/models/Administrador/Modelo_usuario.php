<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Modelo_usuario extends CI_Model {

    var $table = 'usuario';
    var $column_order = array('id_usuario', 'correo', null); // Cambio del orden de la tabla
    var $column_search = array('nombre', 'correo'); //datos por los que voy a buscar
    var $order = array('id_usuario' => 'desc'); // Orden por defecto

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function Login($correo, $clave) {
        $this->db->from($this->table);
        $this->db->where('correo', $correo);
        $this->db->where('clave', $clave);
        $query = $this->db->get();

        return $query->result(); // Ojo que lo envio como result no row();
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

    function Obtener_tablas() {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function Contar_filtrado() {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function Contar_todo() {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function Obtener_por_id($id) {
        $this->db->from($this->table);
        $this->db->where('id_usuario', $id);
        $query = $this->db->get();

        return $query->row();
    }

    public function Guardar($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function Actualizar($where, $data) {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }

    public function Eliminar_por_id($id) { // No se implementara
        $this->db->where('id_usuario', $id);
        $this->db->delete($this->table);
    }

    public function cambiar_rol($id, $id_rol) {
        $data = array(
            'id_rol' => $id_rol //, Solo seteo el estado
        );
        $this->db->where('id_usuario', $id);
        $this->db->update($this->table, $data);
    }

}
