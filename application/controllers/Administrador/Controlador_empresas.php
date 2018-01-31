<?php

/* Creacion 
 * Jorge Luis Maureira
 * Morrigan SPA
 * Desarrollo Agencia Metropolitana S.A.
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Controlador_empresas extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('/Administrador/Modelo_empresas', 'empresas'); // le pongo el nombre de la tabla asi se con que asunto trabajo
        $this->load->model("/Utilidades/Modelo_utilidades", 'utilidades');
    }

    public function Listar_datos() {
        $list = $this->empresas->get_datatables(); //Nombre la tabla que le mande al constructor
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $person) {
            $no++;
            $row = array();  //lista de datos que van a la tabla 
            $row[] = $person->rut_empresa;
            $row[] = $person->nombre;
            $row[] = $person->nombre_representante;
            $row[] = $person->rut_representante;

            $row[] = $person->direccion;
            $row[] = $this->utilidades->nombre_comuna($person->id_comuna);
//add html for action
            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_person(' . "'" . $person->id_empresa . "'" . ')"><i class="glyphicon glyphicon-pencil"></i> Editar</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_person(' . "'" . $person->id_empresa . "'" . ')"><i class="glyphicon glyphicon-trash"></i> Eliminar</a>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->empresas->count_all(),
            "recordsFiltered" => $this->empresas->count_filtered(),
            "data" => $data,
        );
//output to json format
        echo json_encode($output);
    }

    public function Editar_datos($id) {
        /* Se intento fusionar los datos en uno pero al no
         * poder cambie estructura
         * el tipo de objeto row no permitio o no se! mesclar el array
         * 
         * Solucion funcional pero poco elegante
         */
 $this->_validar();
        $data = $this->empresas->obtener_empresa($id);
        $datos = array();
        foreach ($data as $person) {
            $datos = array(
                'rut_empresa' => $person->rut_empresa,
                'nombre' => $person->nombre,
                'rut_representante' => $person->rut_representante,
                'nombre_representante' => $person->nombre_representante,
                'direccion' => $person->direccion,
                'id_comuna' => $person->id_comuna,
                'nombre_comuna' => $this->utilidades->nombre_comuna($person->id_comuna),
            );
        }

        /* Nota
         * $data= $this->empresas->get_by_id($id);
         * echo json_encode($datos);
         * echo gettype($data);// No va en el codigo pero sirver para ver que formato trae
         * array_merge($data, $data2) // No sirve
         * rray_push( $data,  array('jorge' => 'Hola'));// No Sirve
         * Probar con 
         *  $data= $this->empresas->get_by_id($id);
          $datos= json_encode($data);
          $datos.JSON_OBJECT_AS_ARRAY;
         * Para el otro codigo revisar este tema
         * toy seguro que el row hay aplicarle json_encode para poder pasarlo a un formato mas aceptable
         */

//$datos=json_encode($datos);
        echo json_encode($datos);
    }

    public function Agregar_datos() {
        $this->_validar();

        $data = array(
            'rut_empresa' => $this->input->post('rut_empresa'),
            'nombre' => $this->input->post('nombre'),
            'rut_representante' => $this->input->post('rut_representante'),
            'nombre_representante' => $this->input->post('nombre_representante'),
            'direccion' => $this->input->post('direccion'),
            'id_comuna' => $this->input->post('comunas'),
        );
        $this->empresas->save($data);
        echo json_encode(array("status" => TRUE));
    }

    public function Actualizar_datos() {
        $this->_validar();
        $data = array(
            'rut_empresa' => $this->input->post('rut_empresa'),
            'nombre' => $this->input->post('nombre'),
            'rut_representante' => $this->input->post('rut_representante'),
            'nombre_representante' => $this->input->post('nombre_representante'),
            'direccion' => $this->input->post('direccion'),
            'id_comuna' => $this->input->post('comunas'),
        );
        $this->empresas->update(array('id_empresa' => $this->input->post('id')), $data);
//   echo "PROBANDO ->".$this->input->post('id');
        echo json_encode(array("status" => TRUE));
    }

    public function Eliminar_datos($id) {
        $this->empresas->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }

    private function _validar() {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;


        if ($this->input->post('nombre_representante') == '') {
            $data['inputerror'][] = 'nombre_representante';
            $data['error_string'][] = 'Ingresar Nombre Representante';
            $data['status'] = FALSE;
        }
        if ($this->input->post('direccion') == '') {
            $data['inputerror'][] = 'direccion';
            $data['error_string'][] = 'Ingresar una Direccion';
            $data['status'] = FALSE;
        }
        if ($this->input->post('rut_representante') == '') {
            $data['inputerror'][] = 'rut_representante';
            $data['error_string'][] = 'Ingrese el Rut del Representante';
            $data['status'] = FALSE;
        }
        if ($this->input->post('rut_empresa') == '') {
            $data['inputerror'][] = 'rut_empresa';
            $data['error_string'][] = 'Ingrese el Rut Empresa';
            $data['status'] = FALSE;
        }
        if ($this->input->post('nombre') == '') {
            $data['inputerror'][] = 'nombre';
            $data['error_string'][] = 'Ingrese Nombre Empresa';
            $data['status'] = FALSE;
        }
        
          if ($this->input->post('comunas') == 0) {
          $data['inputerror'][] = 'comunas';
          $data['error_string'][] = 'Deber Seleccionar una Comuna';
          $data['status'] = FALSE;
          }
         


        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

}
