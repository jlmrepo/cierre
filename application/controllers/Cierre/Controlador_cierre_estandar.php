<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Controlador_cierre_estandar extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('/Cierre/Modelo_detalle_cierre', 'detalle_cierre');
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
          //  $row[] = $variable->id_cierre;
           // $row[] = $variable->id_familia;
            $row[] = $this->utilidades->nombre_producto($variable->id_producto);

            $row[] = $variable->cantidad;
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

    public function obtener_stock() {
        $id_caja = $this->session->userdata("id_caja");

        $stock = $this->utilidades->stock_producto_bodega_caja($this->input->post('producto'), $id_caja);
        //  $bodega = $this->utilidades->bodega_sucursal_producto($this->session->userdata("id_sucursal"), $this->input->post('producto'));
        /* Obtengo el Id de la bodega por la sucursal y el producto y se los paso a una variable oculta en la vista
         * 
         */

        $data = array(
            "stock_bodega" => $stock,
                // "id_bodega" => $bodega,
        );
        echo json_encode($data);
    }

    public function Agregar_datos() {
        $this->_validacion();
        //Variables a Insertar
        $id_cierre = $this->session->userdata("id_cierre");
        $id_familia = $this->session->userdata("id_familia");
        $id_producto = $this->input->post('producto');
        $cantidad = $this->input->post('cantidad');
        $total = $this->input->post('total');
        $monto = $this->input->post('monto');
        // Revisar metodos mas abajo comparar si se aplicacion los cambios y son los que corresponde
        $id_proveedor = $this->utilidades->obtener_proveedor_familia($id_familia);
        $id_categoria = $this->utilidades->obtener_categoria_proveedor($id_proveedor);
        $data = array(
            'id_cierre' => $id_cierre,
            'id_proveedor' => $id_proveedor,
            'id_categoria' => $id_categoria,
            'id_familia' => $id_familia,
            'id_producto' => $id_producto,
            'cantidad' => $cantidad,
            'monto' => $monto,
            'total' => $total,
        );
        // metodo para revisar si esta el producto

        $validar = $this->detalle_cierre->check_producto_cierre($id_cierre, $id_producto,$id_familia);
        if ($validar) {

            $this->detalle_cierre->Guardar($data);
        } else {
            // Obtener id Detalle_cierre
            /* Como ya se que se encuentra el producto dentro de este cierre,
              ahora obtengo los datos del detalle_cierre  y actualizo el registro.
             */
            $lista = $this->detalle_cierre->obtener_detalle_cierre($id_cierre, $id_producto,$id_familia);
            $id_detalle_cierre = "";
            $cantidad_detalle = "";
            $total_detalle = "";
            foreach ($lista as $variable) {
                $id_detalle_cierre = $variable->id_detalle_cierre;
                $cantidad_detalle = $variable->cantidad;
                $total_detalle = $variable->total;
            }
            $actualizar = array(
                'cantidad' => $cantidad_detalle + $cantidad,
                'total' => $total_detalle + $total,
            );
            $this->detalle_cierre->Actualizar(array('id_detalle_cierre' => $id_detalle_cierre), $actualizar);
        }
        // Ahora descuento de la Bodega de la Caja:
        $id_caja = $this->session->userdata("id_caja");
        $cantidad_bodega = $this->utilidades->stock_producto_bodega_caja($id_producto, $id_caja);
        $stock = $cantidad_bodega - $cantidad;
        // Observacion no puede llegar a < 0 
        $actualizar_stock = array(
            'cantidad' => $stock,
        );
        $id_bodega_caja = $this->utilidades->id_bodega_caja($id_producto, $id_caja);
        $this->load->model('/Sucursal/Modelo_bodega_caja', 'bodega_caja');
        $this->bodega_caja->Actualizar(array('id_bodega_caja' => $id_bodega_caja), $actualizar_stock);
       echo json_encode(array("status" => TRUE));
    }
/*
    public function Agregar_datos_Variable() { // Este metodo revisar tiene que estar en cierre variable!!!!!!!!!!!!!!!!!!!
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

        $validar = $this->detalle_cierre->check_producto_cierre($id_cierre, $id_producto);
        if ($validar) {

            $this->detalle_cierre->Guardar($data);
        } else {

            $lista = $this->detalle_cierre->obtener_detalle_cierre($id_cierre, $id_producto);
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
       echo json_encode(array("status" => TRUE));
            }






        // $this->detalle_cierre->guardar($data);
        echo json_encode(array("status" => TRUE));
    }

    
    */
    
    
    
    
    public function Eliminar_estandar($id) {

        /* La funcion principal de este metodo es:
         * Eliminar el Producto que se encuentra tomado por el cierre
         * Devolver la Cantidad de Productos a la Bodega de La Caja
         * El id que entra el eliminar es el del detalle_cierre
         */

        $lista = $this->detalle_cierre->obtener_por_id($id);
        $id_producto = "";
        $cantidad_detalle = "";
        $id_cierre = "";
        foreach ($lista as $variable) {
            $id_producto = $variable->id_producto;
            $cantidad_detalle = $variable->cantidad;
            $id_cierre = $variable->id_cierre;
        }
        $id_caja = $this->utilidades->obtener_caja_cierre($id_cierre);
        $this->load->model('/Sucursal/Modelo_bodega_caja', 'bodega_caja');
        $id_bodega_caja = $this->bodega_caja->obtener_id($id_caja, $id_producto);
        $cantidad = $this->bodega_caja->obtener_cantidad($id_bodega_caja);
        $total = $cantidad + $cantidad_detalle;

        $actualizar_stock = array(
            'cantidad' => $total,
        );

        $this->bodega_caja->actualizar(array('id_bodega_caja' => $id_bodega_caja), $actualizar_stock);

        $this->detalle_cierre->Eliminar_por_id($id);
        echo json_encode(array("status" => TRUE));
        /*
          echo json_encode(array("PRODUCTO" => $id_producto));
          echo json_encode(array("cantidad_detalle" => $cantidad_detalle));
          echo json_encode(array("id_cierre" => $id_cierre));
          echo json_encode(array("id_caja" => $id_caja));
          echo json_encode(array("id_Bodega_caja" => $id_bodega_caja));
          echo json_encode(array("id_Bodega_caja" => $cantidad));
          echo json_encode(array("status" => TRUE));

         */
    }

    //Este metodo lo usa otro perfil el cajero
    public function Bodega_caja() {
        $id_caja = $this->session->userdata("id_caja");
        $list = $this->bodega_caja->Obtener_bodega_caja($id_caja); //Nombre la tabla que le mande al constructor
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $user) {
            $no++;
            $row = array();  //lista de datos que van a la tabla 
            $row[] = $user->id_caja;
            $row[] = $user->id_bodega;
            $row[] = $this->utilidades->nombre_producto($user->id_producto);
            $row[] = $user->cantidad;
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->bodega_caja->contar_todo_caja($id_caja),
            "recordsFiltered" => $this->bodega_caja->contar_filtrado($id_caja),
            "data" => $data,
        );
        echo json_encode($output);
    }

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

    // Metodos no implementados.
    public function Listar_caja() {
        $id_sucursal = $this->session->userdata("id_sucursal");
        $list = $this->caja->obtener_tabla_caja_sucursal($id_sucursal);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $variable) {
            $no++;
            $row = array();  //lista de datos que van a la tabla 
            $row[] = $variable->nombre;
            $row[] = $this->utilidades->nombre_sucursal($variable->id_sucursal);
            //    $row[] = $this->utilidades->nombre_usuario($variable->id_usuario);
            $row[] = $this->utilidades->nombre_estado($variable->id_estado);
            //   $row[] = $person->id_estado;
            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="ver_bodega_caja(' . "'" . $variable->id_caja . "'" . ')"><i class="glyphicon glyphicon-pencil"></i>Administrar Bodega</a>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->caja->contar_todo(),
            "recordsFiltered" => $this->caja->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function editar($id) {
        $data = $this->bodega_caja->Obtener_por_id($id);
        echo json_encode($data);
    }

    public function Actualizar() {
        //  $this->_validate();
        $id_caja = $this->session->userdata("id_caja");
        $id_bodega = $this->input->post('id_bodega');
        $id_producto = $this->input->post('producto');
        $cantidad = $this->input->post('cantidad');

        $data = array(
            'id_caja' => $id_caja,
            'id_bodega' => $id_bodega,
            'id_producto' => $id_producto,
            'cantidad' => $cantidad,
        );
        /*
          $id_bodega_caja = $this->bodega_caja->obtener_id($id_caja, $id_producto);
          $producto_bodega_caja = $this->bodega_caja->obtener_cantidad($id_bodega_caja);

          if($cantidad<$producto_bodega_caja){
          // Actualizar y devuelve a bodega
          }else{

          }

         */
        $this->bodega_caja->Actualizar(array('id_bodega_caja' => $this->input->post('id')), $data);
        echo json_encode(array("status" => TRUE));
    }

    public function total_producto() {
        $precio = $this->utilidades->precio_producto($this->input->post('producto'));
        $cantidad = $this->input->post('cantidad');
        $total;
        if ($precio == null && $cantidad == "") {
            $total = 0;
        } else {
            $total = $precio * $cantidad;
        }
        $data = array(
            'total' => $total,
            'monto' => $precio,
        );
        echo json_encode($data);
    }

    public function Eliminar($id) {

        /* La funcion principal de este metodo es:
         * Eliminar el Producto que se encuentra tomado por el cierre
         * Devolver la Cantidad de Productos a la Bodega de La Caja
         * El id que entra el eliminar es el del detalle_cierre
         */

//Obtengo la Caja
        $id_caja = $this->session->userdata("id_caja");
        /*
          $list = $this->detalle_cierre->obtener_familia_cierre();
          $data = array();
          $no = $_POST['start'];
          foreach ($list as $variable) {
          $no++;
          $row = array();  //lista de datos que van a la tabla
          $row[] = $variable->id_cierre;
          $row[] = $variable->id_familia;
          $row[] = $this->utilidades->nombre_producto($variable->id_producto);

          $row[] = $variable->cantidad;
          $row[] = $variable->total;
          $row[] = '<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="eliminar(' . "'" . $variable->id_detalle_cierre . "'" . ')"><i class="glyphicon glyphicon-trash"></i> Eliminar</a>';
          $data[] = $row;
          }


         */




//Obtengo el id del producto en bodega
        $id_producto = $this->bodega_caja->obtener_id_producto($id);
// Obtengo el id a la cual le voy a devolver productos;
        $id_bodega = $this->bodega_caja->obtener_id_bodega($id);
//Obtengo la cantidad del producto que tiene la bodega
        $cantidad_bodega = $this->utilidades->stock_producto_bodega($id_producto);
//Obtengo la cantidad del producto que tiene la caja bodega
        $producto_bodega_caja = $this->bodega_caja->obtener_cantidad($id);

        $stock = $cantidad_bodega + $producto_bodega_caja;
        $actualizar_stock = array(
            'cantidad' => $stock,
        );
        $this->bodega->Actualizar(array('id_bodega' => $id_bodega), $actualizar_stock);
        // Fin de Descuento de Bodega


        $this->bodega_caja->Eliminar_por_id($id);
        echo json_encode(array("status" => TRUE));
    }

    public function ver_caja_bodega($id) {
        $this->session->set_userdata('id_caja', $id);
        $nombre_caja = $this->utilidades->nombre_caja($id);
        $this->session->set_userdata('nombre_caja', $nombre_caja);
        echo json_encode(array("status" => TRUE));
    }

    private function _validacion() {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('producto') == '') {
            $data['inputerror'][] = 'producto';
            $data['error_string'][] = 'Debes Seleccionar un Producto';
            $data['status'] = FALSE;
        }
        $id_caja = $this->session->userdata("id_caja");
        $stock = $this->utilidades->stock_producto_bodega_caja($this->input->post('producto'), $id_caja);
        $cantidad = $this->input->post('cantidad');
        if ($stock < $cantidad) {

            $data['inputerror'][] = 'stock';
            $data['error_string'][] = 'No tienes '.$cantidad.' Productos en Stock ';
            $data['status'] = FALSE;
        }


        if ($this->input->post('cantidad') == '') {
            $data['inputerror'][] = 'cantidad';
            $data['error_string'][] = 'Debes ingresar una cantidad';
            $data['status'] = FALSE;
        }
        if ($this->input->post('total') == '') {
            $data['inputerror'][] = 'total';
            $data['error_string'][] = 'Debes tener un Total';
            $data['status'] = FALSE;
        }



        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

}
