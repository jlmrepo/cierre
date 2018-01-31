<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Controlador_masivo extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('/Cierre/Modelo_masivo', 'masivo');
        $this->load->model('/Cierre/Modelo_detalle_cierre', 'detalle_cierre');
        $this->load->model("/Utilidades/Modelo_utilidades", 'utilidades');
    }

    public function Listar_masivo_caja() {
        $id_familia = $this->session->userdata("id_familia");
        $id_caja = $this->session->userdata("id_caja");
        $list = $this->masivo->listar_masivo_caja($id_caja, $id_familia);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $variable) {
            $no++;
            $row = array();  //lista de datos que van a la tabla 
            if ($variable->cantidad != 0) {
                $row[] = $variable->sorteo;
                //     $row[] = $variable->id_caja;
                //   $row[] = $variable->id_cierre;
                //     $row[] = $variable->id_familia;
                $row[] = $this->utilidades->nombre_producto($variable->id_producto);
                $row[] = $variable->cantidad;
                $row[] = $variable->monto;
                $row[] = $variable->total;
                if ($variable->id_cierre == $this->session->userdata("id_cierre")) {
                    $row[] = '<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="eliminar(' . "'" . $variable->id_masivo . "'" . ')"><i class="glyphicon glyphicon-trash"></i> Eliminar</a>';
                } else {
                    $row[] = '<a class="btn btn-sm btn-primary" style=width:250px; height:35px; text-align: left; href="javascript:void(0)" title="Edit" disabled="true"><i class="glyphicon glyphicon-floppy-saved"></i>Stock Cierre Anterior</a>';
                }

                $data[] = $row;
            }
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->masivo->contar_filtrado_caja($id_caja, $id_familia),
            "recordsFiltered" => $this->masivo->contar_todo_caja($id_caja, $id_familia),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function obtener_stock() {
        $id_caja = $this->session->userdata("id_caja");

        $stock = $this->utilidades->stock_producto_masivo_caja($this->input->post('producto'), $id_caja);

        $data = array(
            "stock_bodega" => $stock,
                // "id_bodega" => $bodega,
        );
        echo json_encode($data);
    }

    // Agregar datos Insercion Masiva
    public function Agregar_datos() {
        $this->_validacion();

        //Variables a Insertar
        $sorteo = $this->input->post('sorteo');
        $id_cierre = $this->session->userdata("id_cierre");
        $id_caja = $this->session->userdata("id_caja");
        $id_familia = $this->session->userdata("id_familia");
        $id_producto = $this->input->post('producto');
        $cantidad = $this->input->post('cantidad');
        $monto = $this->input->post('monto');
        $total = $this->input->post('total');

// Datos a Insertar:
        $data = array(
            'id_caja' => $id_caja,
            'id_cierre' => $id_cierre,
            'id_familia' => $id_familia,
            'id_producto' => $id_producto,
            'sorteo' => $sorteo,
            'cantidad' => $cantidad,
            'monto' => $monto,
            'total' => $total,
        );
        // metodo para revisar si esta el producto dentro del masivo del cierre

        $validar = $this->masivo->check_producto_masivo($id_cierre, $id_caja, $id_producto, $id_familia);
        if ($validar) {

            $this->masivo->Guardar($data);
        } else {

            $lista = $this->masivo->obtener_detalle_masivo($id_cierre, $id_caja, $id_producto, $id_familia);
            $id_masivo = "";
            $cantidad_detalle = "";
            $total_detalle = "";
            foreach ($lista as $variable) {
                $id_masivo = $variable->id_masivo;
                $cantidad_detalle = $variable->cantidad;
                $total_detalle = $variable->total;
            }
            $actualizar = array(
                'cantidad' => $cantidad_detalle + $cantidad,
                'total' => $total_detalle + $total,
                'sorteo' => $sorteo,
            );
            $this->masivo->Actualizar(array('id_masivo' => $id_masivo), $actualizar);
        }

        echo json_encode(array("status" => TRUE));
    }

    // Agregar Datos Cierre Masivo
    public function Agregar_datos_cierre_masivo() {
        $this->_validacion_masivo();
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
                'cantidad' => $cantidad_detalle + $cantidad,
                'total' => $total_detalle + $total,
            );
            $this->detalle_cierre->Actualizar(array('id_detalle_cierre' => $id_detalle_cierre), $actualizar);
        }
        $id_caja = $this->session->userdata("id_caja");

        $lista = $this->masivo->obtener_masivo($id_caja, $id_producto, $id_familia);
        $cantidad_descuento = $cantidad;
        $temporal = 0;
        $descuento = 0;

        foreach ($lista as $variable) {
            $id_masivo = $variable->id_masivo;
            //      echo "<p>" . $id_cierre_masivo;
            //    echo "<p>" . $variable->cantidad;
            //    echo "Salida " . $cantidad_descuento;

            if ($variable->cantidad > 0 && $cantidad_descuento > 0) {
                //    $cantidad_descuento = $cantidad_descuento - $variable->cantidad;

                if ($variable->cantidad > $cantidad_descuento) {
                    $descuento = $variable->cantidad - $cantidad_descuento;
                    $cantidad_descuento = 0;
                    $actualizar_stock = array(
                        'cantidad' => $descuento,
                    );
                    $this->masivo->Actualizar(array('id_masivo' => $id_masivo), $actualizar_stock);
                }
                if ($variable->cantidad == $cantidad_descuento) {
                    $descuento = 0;
                    $cantidad_descuento = 0;
                    $actualizar_stock = array(
                        'cantidad' => $descuento,
                    );
                    $this->masivo->Actualizar(array('id_masivo' => $id_masivo), $actualizar_stock);
                }
                if ($variable->cantidad < $cantidad_descuento) {
                    $descuento = 0;
                    $cantidad_descuento = $cantidad_descuento - $variable->cantidad;
                    $actualizar_stock = array(
                        'cantidad' => $descuento,
                    );
                    $this->masivo->Actualizar(array('id_masivo' => $id_masivo), $actualizar_stock);
                }



                /*
                  if ($variable->cantidad > 0 && $cantidad_descuento > 0) {
                  $cantidad_descuento = $cantidad_descuento - $variable->cantidad;

                  if ($cantidad_descuento == 0) {
                  $descuento =$cantidad_descuento;  //Funciona
                  $temporal = 0;
                  //       echo "Igual a Cero";
                  }
                  if ($cantidad_descuento < 0) {
                  echo "Entro Menor: ".$cantidad_descuento;
                  $descuento = $variable->cantidad;
                  $temporal = $cantidad_descuento*-1; //Funciona
                  }
                  if ($cantidad_descuento > 0) {
                  echo "entro mayor: ".$cantidad_descuento;
                  $descuento = $cantidad_descuento;  //// NO Funciona
                  $temporal = 0;
                  }
                  //        echo "Descuento :" . $descuento;
                  //       echo "Temporal:" . $temporal;


                  $actualizar_stock = array(
                  'cantidad' => $descuento,
                  );
                  $this->masivo->Actualizar(array('id_masivo' => $id_masivo), $actualizar_stock);
                  $cantidad_descuento = $temporal;
                 * */
            }
        }





        /*
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
         */
        echo json_encode(array("status" => TRUE));
    }

    // Agregar Datos Cierre Masivo SubAgente
    public function Agregar_datos_cierre_masivo_subagente() {
        // $this->_validacion_masivo();
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
                'cantidad' => $cantidad_detalle + $cantidad,
                'total' => $total_detalle + $total,
            );
            $this->detalle_cierre->Actualizar(array('id_detalle_cierre' => $id_detalle_cierre), $actualizar);
        }




        echo json_encode(array("status" => TRUE));
    }

    // Eliminacion Cierre Masivo SubAgente
    public function Eliminar_cierre_masivo_subagente($id) {
        $this->detalle_cierre->Eliminar_por_id($id);
        echo json_encode(array("status" => TRUE));
    }

    // Eliminacion Insercion Masivo
    public function Eliminar_insercion_masivo($id) {
        $this->masivo->Eliminar_por_id($id);
        echo json_encode(array("status" => TRUE));
    }

    // Eliminacion Cierre masivo
    public function Eliminar_cierre_masivo($id) {

        /* La funcion principal de este metodo es: Eliminar el Producto que se encuentra tomado
         *  por el cierre masivo Devolver la Cantidad de Productos a la Bodega Masivo
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
        $id_masivo = $this->masivo->obtener_id_masivo($id_cierre, $id_caja, $id_producto);
        $cantidad = $this->masivo->obtener_cantidad($id_masivo);
        $total = $cantidad + $cantidad_detalle;

        $actualizar_stock = array(
            'cantidad' => $total,
        );

        $this->masivo->actualizar(array('id_masivo' => $id_masivo), $actualizar_stock);

        $this->detalle_cierre->Eliminar_por_id($id);
        echo json_encode(array("status" => TRUE));
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

    public function editar($id) {
        $data = $this->bodega_caja->Obtener_por_id($id);
        echo json_encode($data);
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
        if ($this->input->post('sorteo') == '') {
            $data['inputerror'][] = 'sorteo';
            $data['error_string'][] = 'Falta ingresar un Sorteo';
            $data['status'] = FALSE;
        }
        if ($this->input->post('producto') == 0) {
            $data['inputerror'][] = 'producto';
            $data['error_string'][] = 'Debes Seleccionar un Producto';
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

    private function _validacion_masivo() {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('producto') == 0) {
            $data['inputerror'][] = 'producto';
            $data['error_string'][] = 'Debes Seleccionar un Producto';
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

        $id_caja = $this->session->userdata("id_caja");
        $stock = $this->utilidades->stock_producto_masivo_caja($this->input->post('producto'), $id_caja);

        $cantidad = $this->input->post('cantidad');
        if ($stock < $cantidad) {

            $data['inputerror'][] = 'stock';
            $data['error_string'][] = 'No tienes ' . $cantidad . ' Productos en Stock ';
            $data['status'] = FALSE;
        }


        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

}
