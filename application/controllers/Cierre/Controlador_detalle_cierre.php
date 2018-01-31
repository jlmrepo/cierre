<?php

/* Codigo de caracteristicas estandar para gestionar Detalle Productos del cierre en Curso

 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Controlador_detalle_cierre extends CI_Controller {

// Constructor que pone instancia el modelo que implementare
    public function __construct() {
        parent::__construct();
        $this->load->model('/Cierre/Modelo_detalle_cierre', 'detalle_cierre');
        $this->load->model("/Utilidades/Modelo_utilidades", 'utilidades');
    }

// Al ser estandar el codigo deber poseer un listar por cada vista de detalle
// Vista 1 Pre-Impreso Loteria
    
    public function Listar_PreImpreso_loteria() {
        $id_cierre = $this->session->userdata("id_cierre");
        
        
        
        $list = $this->guia->obtener_guias_sucursal($id_sucursal);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $variable) {
            $no++;
            $row = array();

            $row[] = $this->utilidades->nombre_proveedor($variable->id_proveedor);
            $row[] = $this->utilidades->nombre_estado($variable->id_estado);
            $row[] = $variable->fecha_guia;
            $row[] = $variable->numero_guia;
            $row[] = $variable->total;
            if ($variable->id_estado == 2) {
                $row[] = '<a class="btn btn-sm btn-primary" style=width:250px; height:35px; text-align: left; href="javascript:void(0)" title="Edit" disabled="true"><i class="glyphicon glyphicon-floppy-saved"></i>Guia en Bodega</a>';
            } else {
                $row[] = '<a class="btn btn-sm btn-primary" style=width:250px; height:35px; text-align: left; href="javascript:void(0)" title="Edit" onclick="edit_person(' . "'" . $variable->id_guia . "'" . ')"><i class="glyphicon glyphicon-pencil"></i> Editar Guia</a>
				  <a class="btn btn-sm btn-danger" style=width:250px; height:35px; text-align: left; href="javascript:void(0)" title="Hapus" onclick="delete_person(' . "'" . $variable->id_guia . "'" . ')"><i class="glyphicon glyphicon-trash"></i>Eliminar Guia </a>'
                        . '<a class="btn btn-success" style=width:250px; height:35px; text-align: left; href="javascript:void(0)" title="Hapus" onclick="enviar_bodega(' . "'" . $variable->id_guia . "'" . ')"><i class="glyphicon glyphicon-plus"></i>Enviar a Guia a Bodega </a>';
            }
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->guia->contar_todo_sucursal($id_sucursal),
            "recordsFiltered" => $this->guia->contar_filtrado($id_sucursal),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function Editar_datos($id) {


        $data = $this->guia->obtener_por_id($id);
        $datos = (array) $data;
        $variable = array('nombre_proveedor' => $this->utilidades->nombre_proveedor($datos['id_proveedor']));
        $salida = array_merge($datos, $variable);
        echo json_encode($salida);
    }

    public function Agregar_datos() {
        //    $this->_validar(); Activar Luego
        $data = array(
            'id_sucursal' => $this->session->userdata("id_sucursal"),
            'id_proveedor' => $this->input->post('proveedores'),
            'fecha_guia' => $this->input->post('fecha_guia'),
            'numero_guia' => $this->input->post('numero_guia'),
            'total' => $this->input->post('total_guia'),
            'id_estado' => 1,
        );
        $id_nuevo = $this->guia->guardar($data);
        echo json_encode(array("status" => TRUE));

        $datos = $this->session->get_userdata(); //Obtengo todos los datos de la session
//Ahora paso el dato de session de la guia 
        $session_guia = array(
            'id_guia' => '' . $id_nuevo,
            'id_proveedor' => '' . $this->input->post('proveedores'),
        );
        $salida = array_merge($datos, $session_guia); //Junto los datos
        $this->session->set_userdata($salida); // Seteo la session 
    }

    public function Actualizar_datos() {
        //  $this->_validar();
        $data = array(
            'id_sucursal' => $this->session->userdata("id_sucursal"),
            'id_proveedor' => $this->input->post('proveedores'),
            'fecha_guia' => $this->input->post('fecha_guia'),
            'numero_guia' => $this->input->post('numero_guia'),
            'total' => $this->input->post('total_guia'),
            'id_estado' => 1,
        );
        $this->guia->actualizar(array('id_guia' => $this->input->post('id')), $data);
        echo json_encode(array("status" => TRUE));
        $this->session->set_userdata('id_guia', $this->input->post('id'));
        $this->session->set_userdata('id_proveedor', $this->input->post('proveedores'));

        //    redirect('rutas/vista_detalle_guia');
    }

    public function Eliminar_datos($id) {
        $this->guia->eliminar_por_id($id);
        echo json_encode(array("status" => TRUE));
    }

    public function Enviar_Bodega($id) {
        $id_guia = $id;
        $id_sucursal = $this->session->userdata("id_sucursal");
        $list = $this->detalle_guia->obtener_detalle_por_guia($id_guia); // Recorres el datalle de la guia
        foreach ($list as $variable) {


            $data = array(
                'id_sucursal' => $id_sucursal,
                'id_producto' => $variable->id_producto,
                'cantidad' => $variable->cantidad,
            );

            if ($this->bodega->ckeck_bodega($id_sucursal, $variable->id_producto)) { //Check de que esten los datos en bodega
                $this->bodega->guardar($data);
                $this->guia->cambiar_estado($id_guia, 2);
            } else {
                $id_bodega = $this->bodega->obtener_id($id_sucursal, $variable->id_producto);
                $cantidad_bodega = $this->bodega->obtener_cantidad($id_bodega);
                $total = $cantidad_bodega + $variable->cantidad;
                $this->bodega->actualizar_bodega(array('id_bodega' => $id_bodega), $total);
                $this->guia->cambiar_estado($id_guia, 2);
                //  echo "Con datos Controlador";
                // Quere decir que no es un insert sino un update
            }
        }

        echo json_encode(array("status" => TRUE));




        //  $id_sucursal= $this->session->userdata("id_sucursal");//$this->guia->id_proveedor($id_guia);
        // Agregar los datos del detalle de la guia a la bodega
        //$this->bodega->check_bodega($id_guia, $id_sucursal);
    }

    public function desactivar_personal($id) {
        $this->obras->cambiar_EstadoObra($id, 2); //Seteo el Estado 2 Desactivado
        echo json_encode(array("status" => TRUE));
    }

    public function activar_personal($id) {
        $this->obras->cambiar_EstadoObra($id, 1); //Seteo el Estado 1 Activado
        echo json_encode(array("status" => TRUE));
    }

    private function _validar() {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('nombres') == '') {
            $data['inputerror'][] = 'nombres';
            $data['error_string'][] = 'Nombres requerido';
            $data['status'] = FALSE;
        }
        if ($this->input->post('apellidos') == '') {
            $data['inputerror'][] = 'apellidos';
            $data['error_string'][] = 'Apellidos requerido';
            $data['status'] = FALSE;
        }
        if ($this->input->post('rut') == '') {
            $data['inputerror'][] = 'rut';
            $data['error_string'][] = 'rut requerido';
            $data['status'] = FALSE;
        }
        if ($this->input->post('direccion') == '') {
            $data['inputerror'][] = 'direccion';
            $data['error_string'][] = 'direccion requerido';
            $data['status'] = FALSE;
        }
        if ($this->input->post('empresa') == '0') {
            $data['inputerror'][] = 'empresa';
            $data['error_string'][] = 'Debe seleccionar una empresa';
            $data['status'] = FALSE;
        }


        if ($this->input->post('obras') == '0') {
            $data['inputerror'][] = 'obras';
            $data['error_string'][] = 'Debe seleccionar una Obra';
            $data['status'] = FALSE;
        }
        if ($this->input->post('regiones') == '0') {
            $data['inputerror'][] = 'regiones';
            $data['error_string'][] = 'Debe seleccionar una Region';
            $data['status'] = FALSE;
        }
        if ($this->input->post('comunas') == '0') {
            $data['inputerror'][] = 'comunas';
            $data['error_string'][] = 'Debe seleccionar una Comuna';
            $data['status'] = FALSE;
        }
        if ($this->input->post('civil') == '0') {
            $data['inputerror'][] = 'civil';
            $data['error_string'][] = 'Debe seleccionar una Estado Civil';
            $data['status'] = FALSE;
        }
        if ($this->input->post('nacionalidad') == '0') {
            $data['inputerror'][] = 'nacionalidad';
            $data['error_string'][] = 'Debe seleccionar una Nacionalidad';
            $data['status'] = FALSE;
        }
        /*
          if ($this->input->post('fecha_ingreso') == '') {
          $data['inputerror'][] = 'fecha_ingreso';
          $data['error_string'][] = 'Debe seleccionar una empresa';
          $data['status'] = FALSE;
          }

         */
        if ($this->input->post('cargo') == '0') {
            $data['inputerror'][] = 'cargo';
            $data['error_string'][] = 'Debe seleccionar un Cargo';
            $data['status'] = FALSE;
        }
        if ($this->input->post('afp') == '0') {
            $data['inputerror'][] = 'afp';
            $data['error_string'][] = 'Debe seleccionar una AfP';
            $data['status'] = FALSE;
        }
        if ($this->input->post('salud') == '0') {
            $data['inputerror'][] = 'salud';
            $data['error_string'][] = 'Debe seleccionar una Entidad Salud o Isapre';
            $data['status'] = FALSE;
        }
        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

}
