<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Controlador_acceso extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Administrador/Modelo_usuario', 'usuario'); // le pongo el nombre de la tabla asi se con que asunto trabajo
        $this->load->model('Administrador/Modelo_sucursales', 'sucursal');
        $this->load->model("/Utilidades/Modelo_utilidades", 'utilidades');
        $this->load->model("Administrador/Modelo_cajeros", "cajeros");
    }

    public function conectar() {
        $data = $this->usuario->Login($this->input->post('email'), $this->input->post('password')); //$this->usuarios->Login($this->input->post('email'),$this->input->post('password'));
        if ($data) { // Si data no devuelve nada salta este if
            $id_usuario = "";
            foreach ($data as $valor) {
                $id_usuario = $valor->id_usuario;
                $nombre = $valor->nombre;
                $rut = $valor->rut;
                $rol = $valor->id_rol;
            }
            //inicio Ahora creo variables de session ya que son validos los datos
            $datos_Session = array(
                'id_usuarios' => '' . $id_usuario,
                'nombre' => '' . $nombre,
                'rut' => '' . $rut,
                'rol' => '' . $rol,
                'logged_in' => TRUE
            );
            //fin de creacion de datos session
            $this->session->set_userdata($datos_Session); //aqui paso el array de session
            //Generando Paguina Principal

            switch ($rol) {
                case 1:// Perfil Administrador
                    if ($this->session->userdata("rol") == 1) {

                        $this->load->view('Componentes/header.php');
                        $this->load->view('Componentes/lateral.php');
                        $this->load->view('Perfiles/Administrador.php');
                        $this->load->view('Componentes/footer.php');
                    } else {
                        echo "Acceso NO AUTORIZADO usted  no es Administrador";
                    }

                    break;
                case 2:// Perfil Sucursal
                    if ($this->session->userdata("rol") == 2) {

                        $id_sucursal = $this->sucursal->obtener_por_usuario($id_usuario);
                        if ($id_sucursal != null) {
                            $datos = $this->session->get_userdata(); //Obtengo todos los datos de la session

                            $session_sucursal = array(
                                'id_sucursal' => '' . $id_sucursal,
                                'nombre_sucursal' => $this->utilidades->nombre_sucursal($id_sucursal),
                            );
                            $salida = array_merge($datos, $session_sucursal); //Junto los datos
                            $this->session->set_userdata($salida); // Seteo la session 

                            $this->load->view('Componentes/header.php');
                            $this->load->view('Componentes/lateral_sucursal.php');
                            $this->load->view('Perfiles/Sucursal.php');
                            $this->load->view('Componentes/footer.php');
                        } else {
                            echo "No tienes Sucursal Activa Contacte al Administrador";
                        }
                    } else {

                        echo "Acceso NO AUTORIZADO usted  no es Administrador";
                    }

                    break;
                case 3:// Perfil Sucursal
                 
              echo "<h1>Salida CTM";      
                    
                    
                    
                    if ($this->session->userdata("rol") == 3) {

                    //    $activo= $this->cajeros->cajero_activo($this->session->userdata("id_usuarios"));
echo "UOSSSSS";

/*
                        if ($activo) {  // Si no esta activo salta el if
                            $this->load->view('Componentes/header.php');
                            $this->load->view('Componentes/lateral.php');
                            $this->load->view('Perfiles/Cajero.php');
                            $this->load->view('Componentes/footer.php');
                        } else {
                            echo "Usted se encuentra desactivado contacte a su administrador";
                        }
                        
                     */   
                        
                        
                    } else {
                        echo "Acceso NO AUTORIZADO usted  no es Administrador";
                    }

                    break;
            }
        } else {
            $data['error'] = "El usuario no se encuentra registrado";
            $this->load->view('index.php', $data);
        }
    }

    private function _validate() {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('email') == '') {
            $data['inputerror'][] = 'email';
            $data['error_string'][] = 'Nombre requerido';
            $data['status'] = FALSE;
        }
        if ($this->input->post('password') == '') {
            $data['inputerror'][] = 'clave';
            $data['error_string'][] = 'Clave requerida';
            $data['status'] = FALSE;
        }
        if ($data['status'] === FALSE) {
            echo json_encode($data);
            echo "Capura Error";

            exit();
        }
    }

}
