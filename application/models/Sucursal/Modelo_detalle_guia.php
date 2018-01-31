<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Modelo_detalle_guia extends CI_Model {

    var $table = 'detalle_guia';
    var $column_order = array('id_producto', null);//esta mierda es solo id_producto
    var $column_search = array('d_producto');
    var $order = array('id_detalle_guia' => 'desc');

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

 
    private function Query_guia($id_sucursal) {
        $this->db->from($this->table);
        $this->db->where('id_guia', $id_sucursal);
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

    function Obtener_guia_detalle($id_guia) {
        $this->Query_guia($id_guia);
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
        $this->Query_guia($id_sucursal);
        $query = $this->db->get();
        return $query->num_rows();
    }/*
     function monto_guia($id_guia) {
        $this->Query_guia($id_guia);
        $this->db->select_sum('total');
        $query = $this->db->get();
         return $query->result();
      /*
        if($query==null){
           return 0;
        }else{
            return $query->result();
        }
       * *
       
        
    }
    */
      function monto_guia($id_guia) {
     $this->db->select_sum('total');
     $this->db->from($this->table);
     $this->db->where('id_guia', $id_guia);
       $query = $this->db->get();
       $result = $query->result();
         return $result[0]->total;
      }
      // Forma de arriba optima
      
      /* Forma lenta
       *  function monto_guia($id_guia) {
        $this->db->select(' SUM(total)  AS suma ');
        $this->db->from($this->table);
        $this->db->where('id_guia', $id_guia);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $nombre = '';
        foreach ($resultado as $variable) {
            $nombre = $variable->suma;
        }
        return $nombre;
    }
       */
      

    public function contar_todo_sucursal($id_sucursal) {
        $this->db->from($this->table);
        $this->db->where('id_guia', $id_sucursal);
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
        $this->db->where('id_detalle_guia', $id);
        $query = $this->db->get();

        return $query->row();
    }

     public function obtener_detalle_por_guia($id) {
        $this->db->from($this->table);
        $this->db->where('id_guia', $id);
        $query = $this->db->get();

        return $query->result();
    }
    public function obtener_detalle_guia($id_guia) {
        $this->db->from($this->table);
        $this->db->where('id_detalle_guia', $id_guia);

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
        $this->db->where('id_detalle_guia', $id);
        $this->db->delete($this->table);
    }

  

}
