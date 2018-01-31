<?php

if (!defined('BASEPATH')) {
    exit('No Direct Scrip');
}

class Modelo_utilidades extends CI_Model {
    /*
     * Observacion
     * Distribuir a al modelo correspondiente los accesadores por seguridad
     */

    public function obtener_regiones() {
        $this->db->select('*');
        $this->db->from('region');

        $consulta = $this->db->get();
        $resultado = $consulta->result();
        return $resultado;
    }

    public function obtener_familia() {
        $this->db->select('*');
        $this->db->from('familia');

        $consulta = $this->db->get();
        $resultado = $consulta->result();
        return $resultado;
    }

    public function obtener_tipo() {
        $this->db->select('*');
        $this->db->from('tipo');

        $consulta = $this->db->get();
        $resultado = $consulta->result();
        return $resultado;
    }

    public function obtener_empresas() {
        $this->db->select('*');
        $this->db->from('empresa');
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        return $resultado;
    }

    public function obtener_usuarios() {
        $this->db->select('*');
        $this->db->from('usuario');
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        return $resultado;
    }

    public function obtener_sucursales() {
        $this->db->select('*');
        $this->db->from('sucursal');
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        return $resultado;
    }

    public function obtener_categorias() {
        $this->db->select('*');
        $this->db->from('categoria');
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        return $resultado;
    }

    public function obtener_proveedor() {
        $this->db->select('*');
        $this->db->from('proveedor');
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        return $resultado;
    }

    public function nombre_proveedor($id) {
        $this->db->from('proveedor');
        $this->db->where('id_proveedor', $id);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $salida = '';
        foreach ($resultado as $person) {
            $salida = $person->nombre;
        }
        return $salida;
    }

    public function nombre_tipo($id) {
        $this->db->from('tipo');
        $this->db->where('id_tipo', $id);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $salida = '';
        foreach ($resultado as $person) {
            $salida = $person->nombre;
        }
        return $salida;
    }

    public function nombre_producto($id) {
        $this->db->from('producto');
        $this->db->where('id_producto', $id);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $salida = '';
        foreach ($resultado as $person) {
            $salida = $person->nombre;
        }
        return $salida;
    }

    public function obtener_comuna($cod_region) {
        $this->db->select('*');
        $this->db->from('comuna');
        $this->db->where('id_region', $cod_region);
        $this->db->order_by('id_comuna', 'asc');
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        return $resultado;
    }

    public function nombre_comuna($id_comuna) {
        $this->db->select('*');
        $this->db->from('comuna');
        $this->db->where('id_comuna', $id_comuna);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $nombre_comuna = '';
        foreach ($resultado as $person) {
            $nombre_comuna = $person->nombre;
        }
        return $nombre_comuna;
    }

    public function nombre_sucursal($id_sucursal) {

        $this->db->from('sucursal');
        $this->db->where('id_sucursal', $id_sucursal);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $nombre = '';
        foreach ($resultado as $variable) {
            $nombre = $variable->nombre;
        }
        return $nombre;
    }

    public function nombre_empresa($id_empresa) {
        $this->db->select('*');
        $this->db->from('empresa');
        $this->db->where('id_empresa', $id_empresa);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $nombre_empresa = '';
        foreach ($resultado as $person) {
            $nombre_empresa = $person->nombre;
        }
        return $nombre_empresa;
    }

    public function nombre_usuario($id_usuario) {
        $this->db->select('*');
        $this->db->from('usuario');
        $this->db->where('id_usuario', $id_usuario);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $nombre = '';
        foreach ($resultado as $person) {
            $nombre = $person->nombre;
        }
        return $nombre;
    }

    public function nombre_categoria($id_categoria) {
        $this->db->select('*');
        $this->db->from('categoria');
        $this->db->where('id_categoria', $id_categoria);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $nombre = '';
        foreach ($resultado as $person) {
            $nombre = $person->nombre;
        }
        return $nombre;
    }

    public function nombre_subcategoria($id_subcategoria) {
        $this->db->select('*');
        $this->db->from('sub_categoria');
        $this->db->where('id_sub_categ', $id_subcategoria);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $nombre = '';
        foreach ($resultado as $person) {
            $nombre = $person->nom_sub_categ;
        }
        return $nombre;
    }

    public function bodega_sucursal_producto($id_sucursal, $id_producto) {
        $this->db->select('id_bodega');
        $this->db->from('bodega');
        $this->db->where('id_sucursal', $id_sucursal);
        $this->db->where('id_producto', $id_producto);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $nombre = '';
        foreach ($resultado as $person) {
            $nombre = $person->id_bodega;
        }
        return $nombre;
    }

    /*
      public function stock_bodega($id_producto) {
      $this->db->select('cantidad');
      $this->db->from('bodega');
      $this->db->where('id_producto', $id_producto);

      $consulta = $this->db->get();
      $resultado = $consulta->result();
      $nombre = '';
      foreach ($resultado as $person) {
      $nombre = $person->cantidad;
      }
      return $nombre;
      }
     */

    public function nombre_caja($id_caja) {
        $this->db->select('*');
        $this->db->from('caja');
        $this->db->where('id_caja', $id_caja);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $nombre = '';
        foreach ($resultado as $person) {
            $nombre = $person->nombre;
        }
        return $nombre;
    }

    public function nombre_estado($id_estado) {
        $this->db->select('*');
        $this->db->from('estado');
        $this->db->where('id_estado', $id_estado);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $nombre = '';
        foreach ($resultado as $person) {
            $nombre = $person->nombre;
        }
        return $nombre;
    }

    public function total_guia($id_guia) {
        $this->db->select('*');
        $this->db->from('guia');
        $this->db->where('id_guia', $id_guia);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $dato = '';
        foreach ($resultado as $variable) {
            $dato = $variable->total;
        }
        return $dato;
    }

// Dato del modelo anterior borrar funcion mas abajo
    public function obtener_producto_categoria($id_categoria) {
        $this->db->from('producto');
        $this->db->where('id_categ', $id_categoria);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        return $resultado;
    }

    public function obtener_producto_proveedores($id) {
        $this->db->select("producto.nombre AS 'nombre_producto',producto.id_producto AS 'id_producto'");
        $this->db->from('proveedor');
        $this->db->from('sub_familia');
        $this->db->from('producto');
        $this->db->where('sub_familia.id_proveedor = proveedor.id_proveedor');
        $this->db->where('sub_familia.id_sub_familia = producto.id_sub_familia');
        $this->db->where('sub_familia.id_sub_familia = producto.id_sub_familia');
        $this->db->where('proveedor.id_proveedor', $id);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        return $resultado;
    }

    public function obtener_producto_familia($id_familia, $id_caja) {
        $this->db->select("producto.nombre AS 'nombre',producto.id_producto AS 'id_producto'");
        $this->db->from('familia');
        $this->db->from('sub_familia');
        $this->db->from('producto');
        $this->db->from('bodega_caja');
        $this->db->where('bodega_caja.id_producto = producto.id_producto');
        $this->db->where('sub_familia.id_familia = familia.id_familia');
        $this->db->where('sub_familia.id_sub_familia = producto.id_sub_familia');
        $this->db->where('sub_familia.id_sub_familia = producto.id_sub_familia');
        $this->db->where('familia.id_familia', $id_familia);
        $this->db->where('bodega_caja.id_caja', $id_caja);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        return $resultado;
    }

    public function obtener_producto_proveedor($id) {
        $this->db->select("Select producto.nombre AS 'nombre_producto',producto.id_producto AS 'id_producto'");
        $this->db->from('proveedor', 'sub_familia', 'producto');
        $this->db->where('sub_familia.id_proveedor = proveedor.id_proveedor');
        $this->db->where('sub_familia.id_sub_familia = producto.id_sub_familia');
        $this->db->where('sub_familia.id_sub_familia = producto.id_sub_familia');
        $this->db->where('proveedor.id_proveedor=1');

        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $salida = '';
        foreach ($resultado as $variable) {
            $salida = $variable->nombre_familia;
        }
        return $salida;
    }

    // Obtener producto Masivo
    public function obtener_producto_masivo($id_familia, $id_caja) {
        $this->db->select("producto.nombre AS 'nombre',producto.id_producto AS 'id_producto'");
        $this->db->from('masivo');
        $this->db->from('producto');
        $this->db->where('masivo.id_producto = producto.id_producto');
        $this->db->where('id_familia', $id_familia);
        $this->db->where('id_caja', $id_caja);
        $this->db->group_by('id_producto');  // Agrupo por Producto
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        return $resultado;
    }

    public function precio_producto($id_producto) {

        $this->db->from('producto');
        $this->db->where('id_producto', $id_producto);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $salida = '';
        foreach ($resultado as $variable) {
            $salida = $variable->precio;
        }
        return $salida;
    }

    public function nombre_familia($id) {

        $this->db->from('familia');
        $this->db->where('id_familia', $id);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $salida = '';
        foreach ($resultado as $variable) {
            $salida = $variable->nombre;
        }
        return $salida;
    }

    // Lo utiliza el listar productos
    public function nombre_familia_subfamilia($id) {
        $this->db->select("familia.nombre AS 'nombre_familia'");
        $this->db->from('sub_familia');
        $this->db->join('familia', 'sub_familia.id_familia = familia.id_familia', 'INNER');
        $this->db->where('id_sub_familia', $id);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $salida = '';
        foreach ($resultado as $variable) {
            $salida = $variable->nombre_familia;
        }
        return $salida;
    }

    /* Consultas mas complejas
     * SELECT sucursal.nombre AS “NOMBRE” FROM caja INNER JOIN sucursal WHERE caja.id_sucursal=sucursal.id_sucursal and caja.id_caja=1
     */

    public function obtener_nombre_sucursal($id_caja) {
        $this->db->select("sucursal.nombre AS 'nombre_sucursal'");
        $this->db->from('caja');
        $this->db->join(' sucursal', 'caja.id_sucursal = sucursal.id_sucursal', 'INNER');
        $this->db->where('id_caja', $id_caja);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $salida = '';
        foreach ($resultado as $variable) {
            $salida = $variable->nombre_sucursal;
        }
        return $salida;
    }

    public function nombre_proveedor_subfamilia($id) {
        $this->db->select("proveedor.nombre AS 'nombre_proveedor'");
        $this->db->from('sub_familia');
        $this->db->join('proveedor', 'sub_familia.id_proveedor = proveedor.id_proveedor', 'INNER');
        $this->db->where('id_sub_familia', $id);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $salida = '';
        foreach ($resultado as $variable) {
            $salida = $variable->nombre_proveedor;
        }
        return $salida;
    }

    public function obtener_proveedor_familia($id) {
        $this->db->from('familia');
        $this->db->where('id_familia', $id);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $salida = '';
        foreach ($resultado as $variable) {
            $salida = $variable->id_proveedor;
        }
        return $salida;
    }

// Metodo mas complejo borrar para Q.A
    public function obtener_proveedor_familia2($id) {
        $this->db->select("proveedor.id_proveedor AS 'id_proveedor'");
        $this->db->from('familia');
        $this->db->join('proveedor', 'familia.id_proveedor = proveedor.id_proveedor', 'INNER');
        $this->db->where('id_familia', $id);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $salida = '';
        foreach ($resultado as $variable) {
            $salida = $variable->id_proveedor;
        }
        return $salida;
    }

    public function obtener_categoria_proveedor($id) {

        $this->db->from('proveedor');

        $this->db->where('id_proveedor', $id);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $salida = '';
        foreach ($resultado as $variable) {
            $salida = $variable->id_categoria;
        }
        return $salida;
    }

    public function nombre_subfamilia($id) {

        $this->db->from('sub_familia');

        $this->db->where('id_sub_familia', $id);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $salida = '';
        foreach ($resultado as $variable) {
            $salida = $variable->nombre;
        }
        return $salida;
    }

    public function stock_producto_bodega($id, $id_sucursal) {

        $this->db->from('bodega');

        $this->db->where('id_producto', $id);
        $this->db->where('id_sucursal', $id_sucursal);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $salida = '';
        foreach ($resultado as $variable) {
            $salida = $variable->cantidad;
        }
        return $salida;
    }

    public function stock_producto_bodega_caja($id, $id_caja) {

        $this->db->from('bodega_caja');

        $this->db->where('id_producto', $id);
        $this->db->where('id_caja', $id_caja);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $salida = '';
        foreach ($resultado as $variable) {
            $salida = $variable->cantidad;
        }
        return $salida;
    }
    
        public function stock_producto_masivo_caja($id, $id_caja) {

        $this->db->from('masivo');

        $this->db->where('id_producto', $id);
        $this->db->where('id_caja', $id_caja);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $salida = 0;
        foreach ($resultado as $variable) {
            $salida =$salida+ $variable->cantidad;
        }
        return $salida;
    }

    public function id_bodega_caja($id, $id_caja) {

        $this->db->from('bodega_caja');

        $this->db->where('id_producto', $id);
        $this->db->where('id_caja', $id_caja);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $salida = '';
        foreach ($resultado as $variable) {
            $salida = $variable->id_bodega_caja;
        }
        return $salida;
    }

    public function obtener_producto_bodega($id_sucursal) {
        $this->db->select("producto.nombre ,bodega.id_producto ");
        $this->db->from('bodega');
        $this->db->join('producto', 'producto.id_producto = bodega.id_producto', 'INNER');
        $this->db->where('id_sucursal', $id_sucursal);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        return $resultado;
    }

// PRODUCTOS BODEGA CAJA
    public function obtener_producto_bodega_caja($id_caja) {
        $this->db->select("producto.nombre ,bodega_caja.id_producto ");
        $this->db->from('bodega_caja');
        $this->db->join('producto', 'producto.id_producto = bodega_caja.id_producto', 'INNER');
        $this->db->where('id_caja', $id_caja);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        return $resultado;
    }

// 
    /*  /*
     *    function total_cierre_familia($id_cierre, $id_familia) {
      $this->db->select_sum('total');
      $this->db->from($this->table);
      $this->db->where('id_cierre', $id_cierre);
      $this->db->where('id_familia', $id_familia);
      $query = $this->db->get();
      $result = $query->result();
      return $result[0]->total;
      }
     *  $this->db->group_by('user_id'); 
     * 
     * 
     * SELECT nombre, sum(precio) as suma FROM producto group by id_producto
     */

    public function obtener_detalle_cierre() {

        $this->db->select('*');
        $this->db->select_sum('total');
        $this->db->from('detalle_cierre');
        $this->db->group_by('id_proveedor');
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        return $resultado;
    }

    public function Obtener_tipo_familia($id) {

        $this->db->from('familia');

        $this->db->where('id_familia', $id);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $salida = '';
        foreach ($resultado as $variable) {
            $salida = $variable->id_tipo;
        }
        return $salida;
    }

    public function obtener_producto_familia_variable($familia, $tipo) {
        $this->db->select('*');
        $this->db->from('familia');
        $this->db->from('sub_familia');
        $this->db->from('producto');
        $this->db->where('familia.id_familia = sub_familia.id_familia');
        $this->db->where('sub_familia.id_sub_familia = producto.id_sub_familia');
        $this->db->where('familia.id_familia', $familia);
        $this->db->where('familia.id_tipo', $tipo);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        return $resultado;
    }

    public function obtener_descuento($id_proveedor) {
        $this->db->select('*');
        $this->db->from('detalle_descuento');
        $this->db->where('id_proveedor', $id_proveedor);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        return $resultado;
    }

    public function nombre_detalle_descuento($id) {
        $this->db->from('detalle_descuento');
        $this->db->where('id_detalle_descuento', $id);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $salida = '';
        foreach ($resultado as $person) {
            $salida = $person->nombre;
        }
        return $salida;
    }

    public function obtener_categoria_familia($id) {
        $this->db->from('categoria');
        $this->db->from('proveedor');
        $this->db->from('familia');
        $this->db->where('categoria.id_categoria = proveedor.id_categoria');
        $this->db->where('proveedor.id_proveedor = familia.id_proveedor');
        $this->db->where('familia.id_familia', $id);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $salida = '';
        foreach ($resultado as $variable) {
            $salida = $variable->id_categoria;
        }
        return $salida;
    }

    public function obtener_banco() {
        // Si lo incluyo select codeigniter entiende que es un select *
        $this->db->from('banco');

        $consulta = $this->db->get();
        $resultado = $consulta->result();
        return $resultado;
    }

    public function obtener_tipo_deposito() {

        $this->db->from('tipo_deposito');

        $consulta = $this->db->get();
        $resultado = $consulta->result();
        return $resultado;
    }

    public function obtener_caja_cierre($id_cierre) {
        $this->db->select('*');
        $this->db->from('cierre');
        $this->db->where('id_cierre', $id_cierre);
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        $nombre = '';
        foreach ($resultado as $person) {
            $nombre = $person->id_caja;
        }
        return $nombre;
    }

}
