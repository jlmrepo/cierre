<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Controlador_bodega_caja extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('/Sucursal/Modelo_bodega_caja', 'bodega_caja'); // le pongo el nombre de la tabla asi se con que asunto trabajo
        $this->load->model("/Utilidades/Modelo_utilidades", 'utilidades');
        $this->load->model('/Administrador/Modelo_cajas', 'caja');
        $this->load->model('/Sucursal/Modelo_bodega', 'bodega');
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
            $row[] = $this->utilidades->nombre_caja($user->id_caja);
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
    
    //Este metodo lo usa otro perfil el cajero
    public function Bodega_caja_cajero_principal() {
        $id_caja = $this->session->userdata("id_caja");
        $list = $this->bodega_caja->Obtener_bodega_caja($id_caja); //Nombre la tabla que le mande al constructor
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $user) {
            $no++;
            $row = array();  //lista de datos que van a la tabla 
 
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

    public function Listar_Bodega_caja() {
        $id_caja = $this->session->userdata("id_caja");
        $list = $this->bodega_caja->Obtener_bodega_caja($id_caja); //Nombre la tabla que le mande al constructor
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $user) {
            $no++;
            $row = array();  //lista de datos que van a la tabla 
            $row[] = $this->utilidades->nombre_caja($user->id_caja);
            $row[] = $user->id_bodega;
            $row[] = $this->utilidades->nombre_producto($user->id_producto);
            $row[] = $user->cantidad;
            $row[] = '<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="eliminar(' . "'" . $user->id_bodega_caja . "'" . ')"><i class="glyphicon glyphicon-trash"></i> Eliminar</a>';
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

    public function obtener_stock() {
        $stock = $this->utilidades->stock_producto_bodega($this->input->post('producto'), $this->session->userdata("id_sucursal"));
        $bodega = $this->utilidades->bodega_sucursal_producto($this->session->userdata("id_sucursal"), $this->input->post('producto'));
        /* Obtengo el Id de la bodega por la sucursal y el producto y se los paso a una variable oculta en la vista
         * 
         */

        $data = array(
            "stock_bodega" => $stock,
            "id_bodega" => $bodega,
        );
        echo json_encode($data);
    }

    public function editar($id) {
        $data = $this->bodega_caja->Obtener_por_id($id);
        echo json_encode($data);
    }

    public function Guardar() {
        $this->_validar();
        //Variables a Insertar
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


        if ($this->bodega_caja->check_bodega_caja($id_caja, $id_producto)) { //Check de que esten los datos en bodega
            $this->bodega_caja->Guardar($data);
        } else {
            $id_bodega_caja = $this->bodega_caja->obtener_id($id_caja, $id_producto);

            $producto_bodega_caja = $this->bodega_caja->obtener_cantidad($id_bodega_caja);
            $total_producto = $producto_bodega_caja + $cantidad;
            $this->bodega_caja->actualizar_bodega(array('id_bodega_caja' => $id_bodega_caja), $total_producto);
        }

        // Metodos para descontar el stock de la Bodega de la Sucucursal
        $cantidad_bodega = $this->utilidades->stock_producto_bodega($id_producto, $this->session->userdata("id_sucursal"));
        $stock = $cantidad_bodega - $cantidad;
        $actualizar_stock = array(
            'cantidad' => $stock,
        );
        $this->bodega->Actualizar(array('id_bodega' => $id_bodega), $actualizar_stock);
        // Fin de Descuento de Bodega

        echo json_encode(array("status" => TRUE));
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

    public function Eliminar($id) {

//Obtengo la Sucursal
        $id_sucursal = $this->session->userdata("id_sucursal");
//Obtengo el id del producto en bodega
        $id_producto = $this->bodega_caja->obtener_id_producto($id);
// Obtengo el id a la cual le voy a devolver productos;
        $id_bodega = $this->bodega_caja->obtener_id_bodega($id);
//Obtengo la cantidad del producto que tiene la bodega
        $cantidad_bodega = $this->utilidades->stock_producto_bodega($id_producto, $this->session->userdata("id_sucursal"));
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

    private function _validar() {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('producto') == 0) {
            $data['inputerror'][] = 'producto';
            $data['error_string'][] = 'Debes selecciona un Producto';
            $data['status'] = FALSE;
        }
        if ($this->input->post('cantidad') == '') {
            $data['inputerror'][] = 'cantidad';
            $data['error_string'][] = 'Debes selecciona una cantidad';
            $data['status'] = FALSE;
        }
         $id_producto = $this->input->post('producto');
        $stock_bodega = $this->utilidades->stock_producto_bodega($id_producto, $this->session->userdata("id_sucursal"));
        $cantidad = $this->input->post('cantidad');
        if ($stock_bodega < $cantidad) {
            $data['inputerror'][] = 'stock';
            $data['error_string'][] = 'No posee stock ';
            $data['status'] = FALSE;
        }



        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

}
