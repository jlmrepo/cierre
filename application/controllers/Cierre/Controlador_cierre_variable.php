<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Controlador_cierre_variable extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('/Cierre/Modelo_detalle_cierre', 'detalle_cierre');
        $this->load->model('/Cierre/Modelo_descuento_cierre', 'descuento_cierre');
        $this->load->model("/Utilidades/Modelo_utilidades", 'utilidades');
    }

    public function Listar_productos_cierre() {
        $id_familia = $this->session->userdata("id_familia");
        $id_cierre = $this->session->userdata("id_cierre");
        $list = $this->detalle_cierre->obtener_familia_cierre($id_familia, $id_cierre);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $variable) {
            $no++;
            $row = array();  //lista de datos que van a la tabla 
            //     $row[] = $variable->id_cierre;
            //   $row[] = $variable->id_familia;
            $row[] = $this->utilidades->nombre_producto($variable->id_producto);
            $row[] = $variable->total;
            $row[] = '<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="eliminar(' . "'" . $variable->id_detalle_cierre . "'" . ')"><i class="glyphicon glyphicon-trash"></i> Eliminar</a>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->detalle_cierre->contar_filtrado_familia($id_familia, $id_cierre),
            "recordsFiltered" => $this->detalle_cierre->contar_todo_familia($id_familia, $id_cierre),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function Agregar_datos_Variable() {
        //$this->_validate();
        //Variables a Insertar
        $id_cierre = $this->session->userdata("id_cierre");
        $id_familia = $this->session->userdata("id_familia");
        $id_producto = $this->input->post('producto');
        $total = $this->input->post('total');
        // Revisar metodos mas abajo comparar si se aplicacion los cambios y son los que corresponde
        $id_proveedor = $this->utilidades->obtener_proveedor_familia($id_familia);
        $id_categoria = $this->utilidades->obtener_categoria_proveedor($id_proveedor);

        $data = array(
            'id_cierre' => $id_cierre,
            'id_proveedor' => $id_proveedor,
            'id_categoria' => $id_categoria,
            'id_familia' => $id_familia,
            'id_producto' => $id_producto,
            'cantidad' => 0, // No posee cantidad variable
            'monto' => 0, // No posee precio variable
            'total' => $total,
        );

        $validar = $this->detalle_cierre->check_producto_cierre($id_cierre, $id_producto, $id_familia);
        if ($validar) {

            $this->detalle_cierre->Guardar($data);
        } else {

            $lista = $this->detalle_cierre->obtener_detalle_cierre($id_cierre, $id_producto, $id_familia);
            $id_detalle_cierre = "";
            $cantidad_detalle = "";
            $total_detalle = "";
            foreach ($lista as $variable) {
                $id_detalle_cierre = $variable->id_detalle_cierre;
                $cantidad_detalle = $variable->cantidad;
                $total_detalle = $variable->total;
            }
            $actualizar = array(
                'total' => $total_detalle + $total,
            );
            $this->detalle_cierre->Actualizar(array('id_detalle_cierre' => $id_detalle_cierre), $actualizar);
        }
        echo json_encode(array("status" => TRUE));
    }

    // Manejo de Metodos descuentos


    public function Listar_descuentos_cierre() {
        $id_familia = $this->session->userdata("id_familia");
        $id_proveedor = $this->utilidades->obtener_proveedor_familia($id_familia); // Con la familia Obtengo el proveedor
        $id_cierre = $this->session->userdata("id_cierre");

        $list = $this->descuento_cierre->obtener_proveedor_cierre($id_proveedor, $id_cierre);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $variable) {
            $no++;
            $row = array();  //lista de datos que van a la tabla 
            //    $row[] = $variable->id_descuento;
            $row[] = $this->utilidades->nombre_detalle_descuento($variable->id_detalle_descuento);
            // $row[] = $variable->id_cierre;
            //   $row[] = $variable->id_descuento;
            $row[] = $variable->monto;
            $row[] = '<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="eliminar_descuento(' . "'" . $variable->id_descuento . "'" . ')"><i class="glyphicon glyphicon-trash"></i> Eliminar</a>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->descuento_cierre->contar_filtrado_proveedor($id_proveedor, $id_cierre),
            "recordsFiltered" => $this->descuento_cierre->contar_todo_proveedor($id_proveedor, $id_cierre),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function Agregar_descuento() {
        //$this->_validate();
        //Variables a Insertar
        $id_cierre = $this->session->userdata("id_cierre");
        $id_familia = $this->session->userdata("id_familia");
        $monto = $this->input->post('monto');
        $id_detalle_descuento = $this->input->post('descuento');
        // Revisar metodos mas abajo comparar si se aplicacion los cambios y son los que corresponde
        $id_proveedor = $this->utilidades->obtener_proveedor_familia($id_familia);


        $data = array(
            'id_detalle_descuento' => $id_detalle_descuento,
            'id_cierre' => $id_cierre,
            'id_proveedor' => $id_proveedor,
            'monto' => $monto,
        );
        //Checkea que el el tipo de descuento se encuentra ingresado si esta ingresado solo actualiza el monto
        $validar = $this->descuento_cierre->check_descuento_cierre($id_cierre, $id_detalle_descuento);
        if ($validar) {
            $this->descuento_cierre->Guardar($data);
        } else {

            $lista = $this->descuento_cierre->obtener_descuento_cierre($id_cierre, $id_detalle_descuento);
            $id_descuento = "";
            $monto_descuento = ""; // Monto que ya se encuentra en la tabla

            foreach ($lista as $variable) {
                $id_descuento = $variable->id_descuento;
                $monto_descuento = $variable->monto;
            }

            $actualizar = array(
                'monto' => $monto + $monto_descuento,
            );

            $this->descuento_cierre->actualizar(array('id_descuento' => $id_descuento), $actualizar);
        }

        echo json_encode(array("status" => TRUE));
    }

    public function Eliminar_descuento($id) {

        $this->descuento_cierre->Eliminar_por_id($id);
        echo json_encode(array("status" => TRUE));
    }

    public function Eliminar_datos($id) {

        $this->detalle_cierre->Eliminar_por_id($id);
        echo json_encode(array("status" => TRUE));
    }

    /*
      public function suma_volumen() {
      $id_cierre = $this->session->userdata("id_cierre");
      $id_familia = $this->session->userdata("id_familia");
      $suma_volumen = $this->detalle_cierre->total_cierre_familia($id_cierre, 23);
      $suma_volumen_subagente = $this->detalle_cierre->total_cierre_familia($id_cierre, 24);
      if ($id_familia == 18) {

      $data = array(
      "suma_volumen" => $suma_volumen,
      "suma_volumen_subagente" => $suma_subagente,
      );
      }
      echo json_encode($data);
      }

     */

    // Metodo para la suma de lo que tiene agregado la familia en el cierre
    public function suma_total() {
        $id_cierre = $this->session->userdata("id_cierre");
        $id_familia = $this->session->userdata("id_familia");
        $suma_total = $this->detalle_cierre->total_cierre_familia($id_cierre, $id_familia);

        $data = array(
            "suma_total" => $suma_total,
        );
        echo json_encode($data);
    }

    public function cuadre() {
        $id_cierre = $this->session->userdata("id_cierre");
        $id_familia = $this->session->userdata("id_familia");
        $suma_total = $this->detalle_cierre->total_cierre_familia($id_cierre, $id_familia);
        $id_proveedor = $this->utilidades->obtener_proveedor_familia($id_familia);
        $suma_descuentos = $this->detalle_cierre->total_descuento_proveedor($id_cierre,$id_proveedor,'+')-$this->detalle_cierre->total_descuento_proveedor($id_cierre,$id_proveedor,'-');
        $saldo_verificador = $this->input->post("saldo_verificador");
        $total_cuadre = $suma_total+$suma_descuentos;
        if ($total_cuadre == $saldo_verificador) {
            $cuadre = "Sin Descuadre " . ($total_cuadre-$saldo_verificador);
        } else {
            $cuadre = "Descuadre: " . ($total_cuadre-$saldo_verificador);
        }

        $data = array(
            "cuadre" => $cuadre,
        );
        echo json_encode($data);
    }
    
    public function agregar_digito() {
        $id_cierre = $this->session->userdata("id_cierre");
        $id_familia = $this->session->userdata("id_familia");
        $suma_total = $this->detalle_cierre->total_cierre_familia($id_cierre, $id_familia);
        $id_proveedor = $this->utilidades->obtener_proveedor_familia($id_familia);
        $suma_descuentos = $this->detalle_cierre->total_descuento_proveedor($id_cierre,$id_proveedor,'+')-$this->detalle_cierre->total_descuento_proveedor($id_cierre,$id_proveedor,'-');
        $saldo_verificador = $this->input->post("saldo_verificador");
        $total_cuadre = $suma_total+$suma_descuentos;
        if ($total_cuadre == $saldo_verificador) {
            $cuadre = "Sin Descuadre " . ($total_cuadre-$saldo_verificador);
        } else {
            $cuadre = "Descuadre: " . ($total_cuadre-$saldo_verificador);
        }

        $data = array(
            "cuadre" => $cuadre,
        );
        
        
        $dato = array(
            'id_cierre' => $id_cierre,
            'id_familia' => $id_familia,
            'monto' => $saldo_verificador,
        );
        $validar = $this->detalle_cierre->check_digito($id_cierre, $id_familia);
        if ($validar) {
            $this->detalle_cierre->guardar_digito($dato);
        } else {

            $lista = $this->detalle_cierre->obtener_digito($id_cierre, $id_familia);
            $id_verificador = "";
          

            foreach ($lista as $variable) {
                $id_verificador = $variable->id_verificador;
                
            }

            $actualizar = array(
                'monto' => $saldo_verificador,
            );

            $this->detalle_cierre->actualizar_digito(array('id_verificador' => $id_verificador), $actualizar);
        }
        echo json_encode($data);
    }

    private function _validate() {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('correo') == '') {
            $data['inputerror'][] = 'correo';
            $data['error_string'][] = 'Debes indicar un correo';
            $data['status'] = FALSE;
        }

        if ($this->input->post('nombre') == '') {
            $data['inputerror'][] = 'nombre';
            $data['error_string'][] = 'Nombre requerido';
            $data['status'] = FALSE;
        }
        if ($this->input->post('rut') == '') {
            $data['inputerror'][] = 'rut';
            $data['error_string'][] = 'Debes Indicar un Rut';
            $data['status'] = FALSE;
        }
        if ($this->input->post('clave') == '') {
            $data['inputerror'][] = 'clave';
            $data['error_string'][] = 'Clave Requerida';
            $data['status'] = FALSE;
        }



        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

}
