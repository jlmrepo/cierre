<div class="col-md-8"> 

    <div class=""> 
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2 style="text-align:center;">Resumen Diario de Caja <small></small></h2>
                        <p>  Fecha : <?php print_r($this->session->userdata("fecha")); ?> </p>
                        <p>   Nombre Sucursal  <?php printf($this->session->userdata("nombre_sucursal")); ?></p>
                        <p><?php printf($this->session->userdata("nombre_caja")); ?> - <?php printf($this->session->userdata("nombre")); ?> </p>
                        <button class="btn btn-info" onclick="location.href = 'http://cierrecaja.cl/index.php/rutas/vista_detalle_cierre';"><i class="glyphicon glyphicon-share-alt"></i> Volver a Cierre</button>
                    </div>
                    <?php $total_recaudacion_diaria = 0;
                    $total_depositos_diaria=0;
                    ?>
                    <div class="x_content">


<?php foreach ($categoria as $categoria): ?>

                            <div class="panel panel-primary">
                                <div style="text-align:center;" class="panel-heading"><?php print("RESUMEN DE VENTAS  " . $categoria->nombre); ?></div>

    <?php
    $contador_total_recaudacion = 0;
    if (!empty($proveedor)) {  // Este lo puse de adorno
        $variable = $proveedor;  // ojo que si no cargo el objeto en una variable genera duplicidad de datos
        foreach ($variable as $variable):
            if ($categoria->id_categoria === $variable->id_categoria) {
                ?>
                                            <table class="table">


                                                <tr>
                                                    <td>Total Ventas <?php print($variable->nombre); ?></td>
                                                    <td><?php
                            $id_cierre = $this->session->userdata("id_cierre");
                            $id_proveedor = $variable->id_proveedor;

                            // Metodos para Instanciar el Modelo
                            $ci = &get_instance();
                            $ci->load->model('/Cierre/Modelo_detalle_cierre');

                            $suma_total = $ci->Modelo_detalle_cierre->total_cierre_proveedor($id_cierre, $id_proveedor);

                            $contador_total_recaudacion = $contador_total_recaudacion + $suma_total;
                            print($suma_total);
                ?></td>
                                                    <!--
                                                    <td onload="printDiv(1);">Total Ventas <?php print($variable->nombre); ?></td>
                                                    <td><div id="areaImprimir">
                                                            <center>$</center>
                                                        </div>
                                                    </td>
                                                    
                                                    -->
                                                </tr>

                                                <tr>
                                                    <td>Descuentos a la Venta <?php print($variable->nombre); ?></td>
                                                    <td><?php
                                        $descuento_total = $ci->Modelo_detalle_cierre->total_descuento_proveedor($id_cierre, $id_proveedor, '+') - $ci->Modelo_detalle_cierre->total_descuento_proveedor($id_cierre, $id_proveedor, '-');

                                        $contador_total_recaudacion = $contador_total_recaudacion + $descuento_total;
                                        print($descuento_total);
                ?></td>
                                                </tr>
                                                        <?php if ($variable->nombre == 'LOTERIA') { ?>
                                                    <tr>
                                                        <td>Total Volumen Sobrante <?php print($variable->nombre);
                                        ?></td>
                                                        <td><?php
                                                            $volumen_total = $ci->Modelo_detalle_cierre->total_cierre_familia($id_cierre, 23) - $ci->Modelo_detalle_cierre->total_impresion_masiva($id_cierre);

                                                            $contador_total_recaudacion = $contador_total_recaudacion + $volumen_total;
                                                            print($volumen_total);
                                                            ?></td>
                                                    </tr>
                                                        <?php } ?>


            <?php
            }endforeach;
        $total_recaudacion_diaria = $total_recaudacion_diaria + $contador_total_recaudacion;
        ?>
                                        <tr>
                                            <td></td>
                                            <td>Total Recaudacion: <?php printf($contador_total_recaudacion); ?> </td>
                                        </tr>
                                    </table>

                                    <table class="table">
                                        <tr>
                                            <td><strong>Nº Deposito</strong></td>
                                            <td><strong>Tipo</strong></td>
                                            <td><strong>Monto</strong></td>
                                        </tr>

                                        <tr>
                                            <td>Nº</td>
                                            <td>Efectivo</td>
                                            <td><?php 
                                            $total_depositos_diaria=$total_depositos_diaria+$ci->Modelo_detalle_cierre->suma_deposito($this->session->userdata("id_cierre"), $categoria->id_categoria, 1, 1);
                                            printf($ci->Modelo_detalle_cierre->suma_deposito($this->session->userdata("id_cierre"), $categoria->id_categoria, 1, 1)); ?></td>
                                        </tr>

                                        <tr>
                                            <td>Nº</td>
                                            <td>CH Santander Dia</td>
                                            <td><?php 
                                            $total_depositos_diaria=$total_depositos_diaria+$ci->Modelo_detalle_cierre->suma_deposito($this->session->userdata("id_cierre"), $categoria->id_categoria, 1, 2);
                                            printf($ci->Modelo_detalle_cierre->suma_deposito($this->session->userdata("id_cierre"), $categoria->id_categoria, 1, 2)); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Nº</td>
                                            <td>CH Santander Fecha</td>
                                            <td><?php 
                                            $total_depositos_diaria=$total_depositos_diaria+$ci->Modelo_detalle_cierre->suma_deposito($this->session->userdata("id_cierre"), $categoria->id_categoria, 1, 3);
                                            printf($ci->Modelo_detalle_cierre->suma_deposito($this->session->userdata("id_cierre"), $categoria->id_categoria, 1, 3)); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Nº</td>
                                            <td>CH Otros Bancos Dia</td>
                                            <td><?php
                                            $total_depositos_diaria=$total_depositos_diaria+$ci->Modelo_detalle_cierre->suma_deposito_otros_bancos($this->session->userdata("id_cierre"), $categoria->id_categoria, 2);
                                            printf($ci->Modelo_detalle_cierre->suma_deposito_otros_bancos($this->session->userdata("id_cierre"), $categoria->id_categoria, 2)); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Nº</td>
                                            <td>CH Otros Bancos a Fecha</td>
                                            <td><?php
                                               $total_depositos_diaria=$total_depositos_diaria+$ci->Modelo_detalle_cierre->suma_deposito_otros_bancos($this->session->userdata("id_cierre"), $categoria->id_categoria, 3); 
                                            printf($ci->Modelo_detalle_cierre->suma_deposito_otros_bancos($this->session->userdata("id_cierre"), $categoria->id_categoria, 3)); ?></td>
                                        </tr>
                                    </table>

                                </div> 

                            <?php } endforeach; ?>
                        <table class="table">
                            <tr>
                                <td><strong>Total Venta Diaria</strong></td>
                                <td><strong>Total Deposito</strong></td>
                                <td><strong>Diferencia</strong></td>
                            </tr>


                            <tr>
                                <td><?php printf($total_recaudacion_diaria); ?></td>
                                <td><?php printf($total_depositos_diaria); ?></td>
                                <td><?php printf($total_recaudacion_diaria-$total_depositos_diaria); ?></td>
                            </tr>
                        </table>

                        Observaciones :
                    </div>

                    <script type="text/javascript">

                        var guardar_metodo; //Cadena para para guardar la funcion que voy procesar
                        var table;

                        $(document).ready(function () {




                        });

                        function printDiv(nombreDiv) {
                            var contenido = document.getElementById(nombreDiv).innerHTML;
                            var contenidoOriginal = document.body.innerHTML;

                            document.body.innerHTML = contenido;

                            window.print();

                            document.body.innerHTML = contenidoOriginal;
                        }

                        function obtener_monto(id) {
                            $.ajax({
                                "url": "<?php echo site_url('Cierre/Controlador_deposito/suma_total') ?>",
                                type: "POST",
                                data: $('#form').serialize(),
                                dataType: "JSON",
                                success: function (data)
                                {
                                    $('[name="suma_total"]').val(data.suma_total);


                                },
                                error: function (jqXHR, textStatus, errorThrown)
                                {
                                    alert('Error get data from ajax');
                                }
                            });
                        }



                    </script>




                </div>   </div>
        </div>   </div>

</div>


<!-- Librerias DataTable -->
<script src="<?php echo base_url('assets/dataTables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/dataTables/js/dataTables.bootstrap.js') ?>"></script>