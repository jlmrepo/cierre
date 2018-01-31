<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class rutas extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("Utilidades/Modelo_utilidades", 'utilidades');
    }

    public function index() {
        $this->load->view('index');
    }

    public function usuario() { // Privilegio solo del Administrador
        if ($this->session->userdata("rol") == 1) {

            $this->load->view('Componentes/header.php');
            $this->load->view('Componentes/lateral.php');
            $this->load->view('Administrador/Vista_usuario.php');
            $this->load->view('Componentes/footer.php');
        } else {
            echo "Acceso NO AUTORIZADO";
        }
    }

    public function vista_empr() {
        if ($this->session->userdata("rol") == 1) {

            $data['region'] = $this->utilidades->obtener_regiones();
            $this->load->view('Componentes/header.php');
            $this->load->view('Componentes/lateral.php');
            $this->load->view('Administrador/Vista_empresas.php', $data);
            $this->load->view('Componentes/footer.php');
        } else {
            echo "Acceso NO AUTORIZADO";
        }
    }

    public function vista_csucursal() {
        if ($this->session->userdata("rol") == 1) {

            $data['region'] = $this->utilidades->obtener_regiones();
            $data['empresa'] = $this->utilidades->obtener_empresas();
            $data['usuario'] = $this->utilidades->obtener_usuarios();
            $this->load->view('Componentes/header.php');
            $this->load->view('Componentes/lateral.php');
            $this->load->view('Administrador/Vista_sucursales.php', $data);
            $this->load->view('Componentes/footer.php');
        } else {
            echo "Acceso NO AUTORIZADO";
        }
    }

    public function vista_cajas() {
        if ($this->session->userdata("rol") == 1) {


            $data['sucursal'] = $this->utilidades->obtener_sucursales();
            $data['usuario'] = $this->utilidades->obtener_usuarios();
            $this->load->view('Componentes/header.php');
            $this->load->view('Componentes/lateral.php');
            $this->load->view('Administrador/Vista_cajas.php', $data);
            $this->load->view('Componentes/footer.php');
        } else {
            echo "Acceso NO AUTORIZADO";
        }
    }

    public function vista_cajeros() {
        if ($this->session->userdata("rol") == 1) {


            $data['sucursal'] = $this->utilidades->obtener_sucursales();
            $data['usuario'] = $this->utilidades->obtener_usuarios();
            $this->load->view('Componentes/header.php');
            $this->load->view('Componentes/lateral.php');
            $this->load->view('Administrador/Vista_cajeros.php', $data);
            $this->load->view('Componentes/footer.php');
        } else {
            echo "Acceso NO AUTORIZADO";
        }
    }

    public function vista_categoria() {
        if ($this->session->userdata("rol") == 1) {

            $this->load->view('Componentes/header.php');
            $this->load->view('Componentes/lateral.php');
            $this->load->view('Administrador/Clasificacion/Vista_categoria.php');
            $this->load->view('Componentes/footer.php');
        } else {
            echo "Acceso NO AUTORIZADO";
        }
    }

    public function vista_proveedor() {
        if ($this->session->userdata("rol") == 1) {
            $datos['categoria'] = $this->utilidades->obtener_categorias();
            $datos['region'] = $this->utilidades->obtener_regiones();


            $this->load->view('Componentes/header.php');
            $this->load->view('Componentes/lateral.php');
            $this->load->view('Administrador/Clasificacion/Vista_proveedor.php', $datos);
            $this->load->view('Componentes/footer.php');
        } else {
            echo "Acceso NO AUTORIZADO";
        }
    }

    public function vista_familia() {
        if ($this->session->userdata("rol") == 1) {
            $datos['categoria'] = $this->utilidades->obtener_categorias();
            $datos['tipo'] = $this->utilidades->obtener_tipo();
// El proveedor no lo cargaremos aqui por que sera dependiente de la categoria
//Por ende tengo que crear una combo box con ajax

            $this->load->view('Componentes/header.php');
            $this->load->view('Componentes/lateral.php');
            $this->load->view('Administrador/Clasificacion/Vista_familia.php', $datos);
            $this->load->view('Componentes/footer.php');
        } else {
            echo "Acceso NO AUTORIZADO";
        }
    }

    public function Vista_subFamilia() {
        if ($this->session->userdata("rol") == 1) {
            $datos['categoria'] = $this->utilidades->obtener_categorias();
            $datos['tipo'] = $this->utilidades->obtener_tipo();
// El proveedor no lo cargaremos aqui por que sera dependiente de la categoria
//Por ende tengo que crear una combo box con ajax

            $this->load->view('Componentes/header.php');
            $this->load->view('Componentes/lateral.php');
            $this->load->view('Administrador/Clasificacion/Vista_subFamilia.php', $datos);
            $this->load->view('Componentes/footer.php');
        } else {
            echo "Acceso NO AUTORIZADO";
        }
    }

    public function Vista_producto() {
        if ($this->session->userdata("rol") == 1) {
            $datos['categoria'] = $this->utilidades->obtener_categorias();
            $datos['tipo'] = $this->utilidades->obtener_tipo();
// El proveedor no lo cargaremos aqui por que sera dependiente de la categoria
//Por ende tengo que crear una combo box con ajax

            $this->load->view('Componentes/header.php');
            $this->load->view('Componentes/lateral.php');
            $this->load->view('Administrador/Clasificacion/Vista_producto.php', $datos);
            $this->load->view('Componentes/footer.php');
        } else {
            echo "Acceso NO AUTORIZADO";
        }
    }

//******

    public function vista_subcategoria() {
        if ($this->session->userdata("rol") == 1) {
            $data['categoria'] = $this->utilidades->obtener_categorias();


            $this->load->view('Componentes/header.php');
            $this->load->view('Componentes/lateral.php');
            $this->load->view('Administrador/Productos/Vista_subcategoria.php', $data);
            $this->load->view('Componentes/footer.php');
        } else {
            echo "Acceso NO AUTORIZADO";
        }
    }

    public function vista_ploteria() {
        if ($this->session->userdata("rol") == 1) {
            $this->load->model("Administrador/Productos/Modelo_subcategoria", 'subcategoria');
            $data['sub_categoria'] = $this->subcategoria->obtener_sub_categoria(1); // Uno categoria Loteria Dos Polla tres Retail y asi loco


            $this->load->view('Componentes/header.php');
            $this->load->view('Componentes/lateral.php');
            $this->load->view('Administrador/Productos/Vista_producto_loteria.php', $data);
            $this->load->view('Componentes/footer.php');
        } else {
            echo "Acceso NO AUTORIZADO";
        }
    }

    public function vista_ppolla() {
        if ($this->session->userdata("rol") == 1) {
            $this->load->model("Administrador/Productos/Modelo_subcategoria", 'subcategoria');
            $data['sub_categoria'] = $this->subcategoria->obtener_sub_categoria(2); // Uno categoria Loteria Dos Polla tres Retail y asi loco


            $this->load->view('Componentes/header.php');
            $this->load->view('Componentes/lateral.php');
            $this->load->view('Administrador/Productos/Vista_producto_polla.php', $data);
            $this->load->view('Componentes/footer.php');
        } else {
            echo "Acceso NO AUTORIZADO";
        }
    }

    public function vista_pretail() {
        if ($this->session->userdata("rol") == 1) {
            $this->load->model("Administrador/Productos/Modelo_subcategoria", 'subcategoria');
            $data['sub_categoria'] = $this->subcategoria->obtener_sub_categoria(3); // Uno categoria Loteria Dos Polla tres Retail y asi loco


            $this->load->view('Componentes/header.php');
            $this->load->view('Componentes/lateral.php');
            $this->load->view('Administrador/Productos/Vista_producto_retail.php', $data);
            $this->load->view('Componentes/footer.php');
        } else {
            echo "Acceso NO AUTORIZADO";
        }
    }

    public function vista_pmulticaja() {
        if ($this->session->userdata("rol") == 1) {
            $this->load->model("Administrador/Productos/Modelo_subcategoria", 'subcategoria');
            $data['sub_categoria'] = $this->subcategoria->obtener_sub_categoria(4); // Uno categoria Loteria Dos Polla tres Retail y asi loco


            $this->load->view('Componentes/header.php');
            $this->load->view('Componentes/lateral.php');
            $this->load->view('Administrador/Productos/Vista_producto_multicaja.php', $data);
            $this->load->view('Componentes/footer.php');
        } else {
            echo "Acceso NO AUTORIZADO";
        }
    }

// Vistas de la Sucursal
    public function vista_guia() {
        if ($this->session->userdata("rol") == 2 && $this->session->userdata("id_sucursal") != null) {
            $data['categoria'] = $this->utilidades->obtener_categorias();
            $data['proveedor'] = $this->utilidades->obtener_proveedor();
            $this->load->view('Componentes/header.php');
            $this->load->view('Componentes/lateral_sucursal.php', $data);
            $this->load->view('Sucursal/Vista_guia.php');
            $this->load->view('Componentes/footer.php');
        } else {
            echo "Acceso NO AUTORIZADO";
        }
    }

    public function vista_detalle_guia() {
        if ($this->session->userdata("rol") == 2 && $this->session->userdata("id_sucursal") != null) {
            $data['categoria'] = $this->utilidades->obtener_categorias();
            $data['proveedor'] = $this->utilidades->obtener_proveedor();
            $data['producto'] = $this->utilidades->obtener_producto_proveedores($this->session->userdata("id_proveedor"));
            $this->load->view('Componentes/header.php');
            $this->load->view('Componentes/lateral_sucursal.php', $data);
            $this->load->view('Sucursal/Vista_detalle_guia.php');
            $this->load->view('Componentes/footer.php');
        } else {
            echo "Acceso NO AUTORIZADO";
        }
    }

    public function vista_bodega() {
        if ($this->session->userdata("rol") == 2 && $this->session->userdata("id_sucursal") != null) {

            $this->load->view('Componentes/header.php');
            $this->load->view('Componentes/lateral_sucursal.php');
            $this->load->view('Sucursal/Vista_bodega.php');
            $this->load->view('Componentes/footer.php');
        } else {
            echo "Acceso NO AUTORIZADO";
        }
    }

    public function vista_gestion_bodega_caja() {
        if ($this->session->userdata("rol") == 2 && $this->session->userdata("id_sucursal") != null) {
            $this->load->view('Componentes/header.php');
            $this->load->view('Componentes/lateral_sucursal.php');
            $this->load->view('Sucursal/Gestionar_bodega_caja.php');
            $this->load->view('Componentes/footer.php');
        } else {
            echo "Acceso NO AUTORIZADO";
        }
    }

    public function vista_bodega_caja() {
        if ($this->session->userdata("rol") == 2 && $this->session->userdata("id_sucursal") != null) {
            $data['producto'] = $this->utilidades->obtener_producto_bodega($this->session->userdata("id_sucursal"));
            $this->load->view('Componentes/header.php');
            $this->load->view('Componentes/lateral_sucursal.php');
            $this->load->view('Sucursal/Vista_bodega_caja.php', $data);
            $this->load->view('Componentes/footer.php');
        } else {
            echo "Acceso NO AUTORIZADO";
        }
    }

// Gestion
    public function vista_gestion_sucursal() {
        if ($this->session->userdata("rol") == 1) {
            $this->load->view('Componentes/header.php');
            $this->load->view('Componentes/lateral.php');
            $this->load->view('Administrador/Gestion/Vista_sucursales.php');
            $this->load->view('Componentes/footer.php');
        } else {
            echo "Acceso NO AUTORIZADO";
        }
    }

    public function vista_gestion_caja() {
        if ($this->session->userdata("rol") == 1) {
            $this->load->view('Componentes/header.php');
            $this->load->view('Componentes/lateral.php');
            $this->load->view('Administrador/Gestion/Vista_cajas.php');
            $this->load->view('Componentes/footer.php');
        } else {
            echo "Acceso NO AUTORIZADO";
        }
    }

//Vistas Usuario Caja
    public function vista_administrar_cierre() {
        if ($this->session->userdata("rol") == 3 && $this->session->userdata("cajero_activo") == TRUE) {
            $this->load->view('Componentes/header.php');
            $this->load->view('Componentes/lateral_cajero.php');
            $this->load->view('Caja/Vista_administrar_cierre.php');
            $this->load->view('Componentes/footer.php');
        } else {
            echo "Acceso NO AUTORIZADO";
        }
    }

    public function vista_detalle_cierre() {
        if ($this->session->userdata("rol") == 3 && $this->session->userdata("cajero_activo") == TRUE) {
            $data['categoria'] = $this->utilidades->obtener_categorias();
            $data['proveedor'] = $this->utilidades->obtener_proveedor();
            $data['familia'] = $this->utilidades->obtener_familia();
            $data['detalle_cierre'] = $this->utilidades->obtener_detalle_cierre();
            $this->load->view('Componentes/header.php');
            $this->load->view('Componentes/lateral_cajero.php');
            $this->load->view('Caja/Vista_detalle_cierre.php', $data);
            $this->load->view('Componentes/footer.php');
        } else {
            echo "Acceso NO AUTORIZADO";
        }
    }

    public function vista_loteria_preimpreso() {
        if ($this->session->userdata("rol") == 3 && $this->session->userdata("cajero_activo") == TRUE) {
            $this->load->view('Componentes/header.php');
            $this->load->view('Componentes/lateral_cajero.php');
            $this->load->view('Cierre/Loteria_preImpreso.php');
            $this->load->view('Componentes/footer.php');
        } else {
            echo "Acceso NO AUTORIZADO";
        }
    }

// Cierre de Productos Online Preimpreso por Familia
    public function vista_cierre_familia($id_familia) {
        if ($this->session->userdata("rol") == 3 && $this->session->userdata("cajero_activo") == TRUE) {
// Guardo la familia en la session
            $this->session->set_userdata('id_familia', $id_familia);
            $this->session->set_userdata('nombre_familia', $this->utilidades->nombre_familia($id_familia));
            $tipo = $this->utilidades->Obtener_tipo_familia($id_familia);
            $this->load->view('Componentes/header.php');
            $this->load->view('Componentes/lateral_cajero.php');

            switch ($tipo) {
                case 1: // Tipo de Preimpreso
                    $data['producto'] = $this->utilidades->obtener_producto_familia($id_familia, $this->session->userdata("id_caja")); // Lo Obtiene de la Caja
                    $this->load->view('Cierre/Cierre_estandar.php', $data);
                    break;
                case 2:
                    $id_proveedor = $this->utilidades->obtener_proveedor_familia($id_familia);
                    $data['producto'] = $this->utilidades->obtener_producto_familia_variable($id_familia, $tipo);
                    $data['descuento'] = $this->utilidades->obtener_descuento($id_proveedor);
                    $this->load->view('Cierre/Cierre_variable.php', $data);
                    break;
                case 3:  // Sub Agente
                    $id_proveedor = $this->utilidades->obtener_proveedor_familia($id_familia);
                    if ($id_proveedor == 14) {
                        $data['producto'] = $this->utilidades->obtener_producto_familia(15, $this->session->userdata("id_caja"));
                    } else {
                        if ($id_proveedor == 13) {
                            $data['producto'] = $this->utilidades->obtener_producto_familia(17, $this->session->userdata("id_caja"));
                        }
                    }
                    $this->load->view('Cierre/Cierre_subagente.php', $data);
                    break;
                case 4:// Masivo
                    if ($id_familia == 24) {// 24 es el tipo de Familia Volumen Sub-Agente
                        $data['producto'] = $this->utilidades->obtener_producto_familia_variable($id_familia, $tipo);
                        $this->load->view('Cierre/Cierre_subagente_masivo.php', $data);
                    } else {

                        $data['producto'] = $this->utilidades->obtener_producto_masivo($id_familia, $this->session->userdata("id_caja"));
                        $this->load->view('Cierre/Cierre_masivo.php', $data);
                    }
                    break;
            }
            $this->load->view('Componentes/footer.php');
        } else {
            echo "Acceso NO AUTORIZADO";
        }
    }

// Se utilizara practiamente la misma vista de la caja de sucursal

    public function bodega_caja() {
        if ($this->session->userdata("rol") == 3 && $this->session->userdata("cajero_activo") == TRUE) {
            $this->load->view('Componentes/header.php');
            $this->load->view('Componentes/lateral_cajero.php');
            $this->load->view('Caja/Bodega_caja.php');
            $this->load->view('Componentes/footer.php');
        } else {
            echo "Acceso NO AUTORIZADO";
        }
    }

// Vista Deposito

    public function vista_deposito($id_categoria) { // El boton le tranfiere el id de la categoria seleccionada
        if ($this->session->userdata("rol") == 3 && $this->session->userdata("cajero_activo") == TRUE) {
// Guardo la categoria en la session
            $this->session->set_userdata('id_categoria', $id_categoria);
            $data['banco'] = $this->utilidades->obtener_banco();
            $data['tipo_deposito'] = $this->utilidades->obtener_tipo_deposito();
            $this->load->view('Componentes/header.php');
            $this->load->view('Componentes/lateral_cajero.php');
            $this->load->view('Cierre/Vista_deposito.php', $data);

            $this->load->view('Componentes/footer.php');
        } else {
            echo "Acceso NO AUTORIZADO";
        }
    }

// Vista Resumen

    public function vista_resumen($id_cierre) { // El boton le tranfiere el id de Cierre
        if ($this->session->userdata("rol") == 3 && $this->session->userdata("cajero_activo") == TRUE) {
            $data['categoria'] = $this->utilidades->obtener_categorias();
            $data['proveedor'] = $this->utilidades->obtener_proveedor();
            $data['familia'] = $this->utilidades->obtener_familia();
            $data['detalle_cierre'] = $this->utilidades->obtener_detalle_cierre();
            $this->load->view('Componentes/header.php');
            $this->load->view('Componentes/lateral_cajero.php');
            $this->load->view('Cierre/Vista_resumen.php', $data);

            $this->load->view('Componentes/footer.php');
        } else {
            echo "Acceso NO AUTORIZADO";
        }
    }

    // Manejo de Masivo.
    public function vista_masivo($id_familia) { // El boton le tranfiere el id de la categoria seleccionada
        if ($this->session->userdata("rol") == 3 && $this->session->userdata("cajero_activo") == TRUE) {
// Guardo la categoria en la session
            $this->session->set_userdata('id_familia', $id_familia);
            $this->session->set_userdata('nombre_familia', $this->utilidades->nombre_familia($id_familia));
            //    $data['banco'] = $this->utilidades->obtener_banco();
            //  $data['tipo_deposito'] = $this->utilidades->obtener_tipo_deposito();

            $data['producto'] = $this->utilidades->obtener_producto_familia_variable($id_familia, 4);
            $this->load->view('Componentes/header.php');
            $this->load->view('Componentes/lateral_cajero.php');
            $this->load->view('Cierre/Vista_agregar_masivo.php', $data);

            $this->load->view('Componentes/footer.php');
        } else {
            echo "Acceso NO AUTORIZADO";
        }
    }

}
