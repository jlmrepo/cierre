<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/* Modelo Detalle Cierre
 * Morrigan SpA
 * 17-12-2017
 * Gestionar cambios en cuanto a estado o generar limpieza de tablas de origen temporal
 * Observaciones
 *
 */

class Modelo_detalle_cierre extends CI_Model {

    var $table = 'detalle_cierre';
    var $column_order = array('detalle_cierre', 'id_cierre', null);
    var $column_search = array('detalle_cierre', 'id_cierre');
    var $order = array('id_detalle_cierre' => 'desc');

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

    private function obtener_consulta_familia_datatable($id_familia, $id_cierre) {
        $this->db->from($this->table);
        $this->db->where('id_familia', $id_familia); // Con solo esto se filtra :D
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
    function obtener_familia_cierre($id_familia, $id_cierre) {
        $this->obtener_consulta_familia_datatable($id_familia, $id_cierre);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    // Metodos para paguinacion con filtrado de familia

    function contar_filtrado_familia($id_familia, $id_cierre) {
        $this->obtener_consulta_familia_datatable($id_familia, $id_cierre);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function contar_todo_familia($id_familia, $id_cierre) {
        $this->db->from($this->table);
        $this->db->where('id_familia', $id_familia);
        $this->db->where('id_cierre', $id_cierre);

        return $this->db->count_all_results();
    }

    public function obtener_por_id($id) {
        $this->db->from($this->table);
        $this->db->where('id_detalle_cierre', $id);
        $query = $this->db->get();

        return $query->result(); // Espero no Joderla cambiado: $query->row();
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
        $this->db->where('id_detalle_cierre', $id);
        $this->db->delete($this->table);
    }

// Suma lo que tiene actualmente el cierre clasificado por familia
    function total_cierre_familia($id_cierre, $id_familia) {
        $this->db->select_sum('total');
        $this->db->from($this->table);
        $this->db->where('id_cierre', $id_cierre);
        $this->db->where('id_familia', $id_familia);
        $query = $this->db->get();
        $result = $query->result();
        return $result[0]->total;
    }

    // Suma lo que tiene actualmente el cierre clasificado por familia
    function total_cierre_proveedor($id_cierre, $id_proveedor) {
        $this->db->select_sum('total');
        $this->db->from($this->table);
        $this->db->where('id_cierre', $id_cierre);
        $this->db->where('id_proveedor', $id_proveedor);
        $this->db->where('id_familia !=', 23);// Estos dos codigos son de la familia que tiene volumen
        $this->db->where('id_familia !=', 24);// Optimizar vercion beta para que quede dinamico
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

// Metodos para el Manejo de Stock de Bodega caja VS Cierre.
    //  * Funcion no optimizada mejor a la Entrega de la Beta.
    //  * Devuelve Verdadero si se encuentra el producto
    public function check_producto_cierre($id_cierre, $id_producto, $id_familia) {
        $this->db->from($this->table);
        $this->db->where('id_cierre', $id_cierre);
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
    public function obtener_detalle_cierre($id_cierre, $id_producto, $id_familia) {
        $this->db->from($this->table);
        $this->db->where('id_cierre', $id_cierre);
        $this->db->where('id_producto', $id_producto);
        $this->db->where('id_familia', $id_familia);
        $query = $this->db->get();
        return $query->result(); // Ojo que lo envio como result no row();
    }

    // Suma lo que tiene actualmente el los descuentos clasificados por proveedor
    // Solucion no Optima mejorar
    function total_descuento_proveedor($id_cierre, $id_proveedor, $operacion) {
        $this->db->select_sum('descuento.monto');
        $this->db->from('descuento');
        $this->db->from('detalle_descuento');
        $this->db->where('descuento.id_detalle_descuento = detalle_descuento.id_detalle_descuento');
        $this->db->where('descuento.id_cierre', $id_cierre);
        $this->db->where('descuento.id_proveedor', $id_proveedor);
        $this->db->where('detalle_descuento.operacion', $operacion);
        $query = $this->db->get();
        $result = $query->result();
        return $result[0]->monto;
    }

    // Para resumen de depositos
    function suma_deposito($id_cierre, $id_categoria, $id_banco, $id_tipo_deposito) {
        $this->db->select_sum('monto');
        $this->db->from("deposito");
        //  $this->db->from("detalle_deposito");
        $this->db->where('id_cierre', $id_cierre);
        $this->db->where('id_categoria', $id_categoria);
        $this->db->where('id_banco', $id_banco);
        $this->db->where('id_tipo_deposito', $id_tipo_deposito);

        $query = $this->db->get();
        $result = $query->result();
        return $result[0]->monto;
    }

    // Para resumen de depositos
    function suma_deposito_otros_bancos($id_cierre, $id_categoria, $id_tipo_deposito) {
        $this->db->select_sum('monto');
        $this->db->from("deposito");
        //  $this->db->from("detalle_deposito");
        $this->db->where('id_cierre', $id_cierre);
        $this->db->where('id_categoria', $id_categoria);
        // $this->db->where('id_banco', $id_banco);
        $this->db->where('id_banco >=', 2);
        $this->db->where('id_banco <=', 9);

        $this->db->where('id_tipo_deposito', $id_tipo_deposito);

        $query = $this->db->get();
        $result = $query->result();
        return $result[0]->monto;
    }
   //  Suma lo que se a impreso masivamente en el cierre
    function total_impresion_masiva($id_cierre) {
        $this->db->select_sum('total');
        $this->db->from("masivo");
        $this->db->where('id_cierre', $id_cierre);
      
        $query = $this->db->get();
        $result = $query->result();
        return $result[0]->total;
    }
    // Funciones Digito Verificador.
    
     public function guardar_digito($data) {
         
        $this->db->insert('saldo_verificador', $data);
        return $this->db->insert_id();
    }
  public function obtener_digito($id_cierre,$id_familia) {
        $this->db->from('saldo_verificador');
        $this->db->where('id_cierre', $id_cierre);
        $this->db->where('id_familia', $id_familia);
        $query = $this->db->get();
        return $query->result(); // Ojo que lo envio como result no row();
    }
      public function check_digito($id_cierre, $id_familia) {
        $this->db->from('saldo_verificador');
        $this->db->where('id_cierre', $id_cierre);
        $this->db->where('id_familia', $id_familia);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 0) {
            return true;
        } else {
            return false;
        }
    }
    public function actualizar_digito($where, $data) {
        $this->db->update('saldo_verificador', $data, $where);
        return $this->db->affected_rows();
    }
     public function saldo_verificador($id_cierre,$id_familia) {

        $this->db->from('saldo_verificador');
        $this->db->where('id_cierre', $id_cierre);
         $this->db->where('id_familia', $id_familia);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $dato= '';
        foreach ($resultado as $variable) {
            $dato = $variable->monto;
        }
        return $dato;
    }
    
    // Fin funciones digito Verificador

}
