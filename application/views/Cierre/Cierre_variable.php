<div class="col-md-8"> 
    <!-- Inicio Pantalla principal 
    Esta es donde se gestionan los contenidos es la unica que cambia del sitema 
    -->
    <div class=""> 
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h5>Cierre de Caja IDº <small> <?php echo $this->session->userdata('id_cierre'); ?></small></h5>
                        <h3>Cierre <?php echo $this->session->userdata('nombre_familia'); ?></h3>
                        <h4 class="text-primary text-right" >Fecha: <small> <?php echo $this->session->userdata('fecha'); ?></small></h4>
                        <form action="#" id="operaciones" class="form-horizontal">    
                            <?php
                            $ci = &get_instance();
                            $ci->load->model('/Cierre/Modelo_detalle_cierre');
                            if ($this->session->userdata('id_familia') == 18) {


                            $suma_volumen = $ci->Modelo_detalle_cierre->total_cierre_familia($this->session->userdata('id_cierre'), 23);
                            $suma_volumen_subagente = $ci->Modelo_detalle_cierre->total_cierre_familia($this->session->userdata('id_cierre'), 24);
                            $impresion_masiva = $ci->Modelo_detalle_cierre->total_impresion_masiva($this->session->userdata('id_cierre'));
                            if ($impresion_masiva > 0) {
                            echo "<p><h5>Impresion Volumen: " . $impresion_masiva . " </h5";
                            }
                            if ($suma_volumen > 0) {
                            echo "<p><h5>Ventas Volumen: " . $suma_volumen . " </h5";
                            }
                            if ($suma_volumen_subagente > 0) {
                            echo "<p><h5>Ventas Volumen Sub-Agente: " . $suma_volumen_subagente . " </h5";
                            }
                            }
                            ?>
                            <div class="col-xs-4">

                                <div class="input-group">
                                    <span class="input-group-addon">Saldo Verificador</span>
                                    <input id="saldo_verificador" type="text" class="form-control" name="saldo_verificador" onClick="ver_cuadre()" value="<?php printf($ci->Modelo_detalle_cierre->saldo_verificador($this->session->userdata('id_cierre'), $this->session->userdata('id_familia'))); ?>" placeholder="Saldo Voucher">

                                </div>
                                <button class="btn btn-success" onclick="agregar_digito()"><i class="glyphicon glyphicon-plus"></i>Agregar Verificador</button>

                            </div>
                            <p class="text-muted font-13 m-b-30">

                            <h3 class="text-primary text-center">TOTAL VENTA : <input type="text" name="suma_total" style="border:none" disabled="true"/></h3>
                            </p>
                    </div>
                    <div class="col-xs-4">

                        <div class="input-group">
                            <span class="input-group-addon">Cuadre</span>
                            <input id="cuadre" type="text" class="form-control" name="cuadre" placeholder="">
                        </div>
                    </div>

                    </form>

                    <button class="btn btn-success" onclick="agregar_guia()"><i class="glyphicon glyphicon-plus"></i>Agregar Producto</button>


                    <button class="btn btn-info" onclick="location.href = 'http://cierrecaja.cl/index.php/rutas/vista_detalle_cierre';"><i class="glyphicon glyphicon-share-alt"></i> Volver a Cierre</button>

                    <div class="table-responsive">
                        <table id="productos_cierre" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                  <!--  <th>Id_Cierre</th>
                                    <th>Id_Familia</th> -->
                                    <th>Tipo de Venta</th>
                                    <th>Total</th>
                                    <th style="width:125px;">Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>


                    <div class="x_content">

                        <button class="btn btn-success" onclick="agregar_descuento()"><i class="glyphicon glyphicon-plus"></i>Agregar Descuento</button>    

                        <div class="table-responsive">
                            <table id="descuentos_cierre" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>

                                        <th>Tipo de Descuento</th>


                                        <th>Monto</th>
                                        <th style="width:125px;">Accion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <!--
                          <button style="text-align:center;" class="btn btn-danger" onclick="location.href = 'http://cierrecaja.cl/index.php/rutas/vista_detalle_cierre';"><i class="glyphicon glyphicon-alert"></i> SALDO VERIFICADOR</button>
                           <span class="input-group-addon">Dato Verificador</span> <input type="text" name="suma_total" style="border:none" />
                        -->

                    </div>


                    <script type="text/javascript">
                        var guardar_metodo;
                        var table;
                        var table2;
                        $(document).ready(function () {
                            // Carga de DataTable Nº1 Productos
                            table = $('#productos_cierre').DataTable({
                                "processing": true,
                                "serverSide": true,
                                "order": [],
                                "ajax": {
                                    "url": "<?php echo site_url('Cierre/Controlador_cierre_variable/Listar_productos_cierre') ?>",
                                    "type": "POST"
                                },
                                "columnDefs": [
                                    {
                                        "targets": [-1],
                                        "orderable": false,
                                    },
                                ],
                                "language": {
                                    "sProcessing": "Procesando...",
                                    "sLengthMenu": "Mostrar _MENU_  Detalle",
                                    "sZeroRecords": "No se encontraron resultados",
                                    "sEmptyTable": "Ningun dato disponible en esta tabla",
                                    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                                    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                                    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                                    "sInfoPostFix": "",
                                    "sSearch": "Buscar Detalle:  ",
                                    "sUrl": "",
                                    "sInfoThousands": ",",
                                    "sLoadingRecords": "Cargando...",
                                    "oPaginate": {
                                        "sFirst": "Primero",
                                        "sLast": "Ultimo",
                                        "sNext": "Siguiente",
                                        "sPrevious": "Anterior"
                                    },
                                    "oAria": {
                                        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                                    }
                                }

                            });

                            // Carga de DataTable Nº2 Descuentos

                            table2 = $('#descuentos_cierre').DataTable({
                                "processing": true,
                                "serverSide": true,
                                "order": [],
                                "ajax": {
                                    "url": "<?php echo site_url('Cierre/Controlador_cierre_variable/Listar_descuentos_cierre') ?>",
                                    "type": "POST"
                                },
                                "columnDefs": [
                                    {
                                        "targets": [-1],
                                        "orderable": false,
                                    },
                                ],
                                "language": {
                                    "sProcessing": "Procesando...",
                                    "sLengthMenu": "Mostrar _MENU_  Detalle",
                                    "sZeroRecords": "No se encontraron resultados",
                                    "sEmptyTable": "Ningun dato disponible en esta tabla",
                                    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                                    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                                    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                                    "sInfoPostFix": "",
                                    "sSearch": "Buscar Detalle:  ",
                                    "sUrl": "",
                                    "sInfoThousands": ",",
                                    "sLoadingRecords": "Cargando...",
                                    "oPaginate": {
                                        "sFirst": "Primero",
                                        "sLast": "Ultimo",
                                        "sNext": "Siguiente",
                                        "sPrevious": "Anterior"
                                    },
                                    "oAria": {
                                        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                                    }
                                }

                            });



                            ver_cuadre()


                            //set input/textarea/select event when change value, remove class error and remove text help block 
                            $("input").change(function () {
                                $(this).parent().parent().removeClass('has-error');
                                $(this).next().empty();
                            });
                            $("textarea").change(function () {
                                $(this).parent().parent().removeClass('has-error');
                                $(this).next().empty();
                            });
                            $("select").change(function () {
                                $(this).parent().parent().removeClass('has-error');
                                $(this).next().empty();
                            });
                            obtener_montos();

                        });
                        function obtener_montos() {
                            $.ajax({
                                "url": "<?php echo site_url('Cierre/Controlador_cierre_estandar/suma_total') ?>",
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

                        function ver_cuadre() {
                            $.ajax({
                                "url": "<?php echo site_url('Cierre/Controlador_cierre_variable/cuadre') ?>",
                                type: "POST",
                                data: $('#operaciones').serialize(),
                                dataType: "JSON",
                                success: function (data)
                                {
                                    $('[name="cuadre"]').val(data.cuadre);


                                },
                                error: function (jqXHR, textStatus, errorThrown)
                                {
                                    alert('Error get data from ajax');
                                }
                            });
                        }
                        function agregar_digito() {
                            $.ajax({
                                "url": "<?php echo site_url('Cierre/Controlador_cierre_variable/agregar_digito') ?>",
                                type: "POST",
                                data: $('#operaciones').serialize(),
                                dataType: "JSON",
                                success: function (data)
                                {
                                    $('[name="cuadre"]').val(data.cuadre);
  location.reload();

                                },
                                error: function (jqXHR, textStatus, errorThrown)
                                {
                                    alert('Error get data from ajax');
                                }
                            });
                        }


                        function agregar_guia() // Solo es para desplegar el modal
                        {
                            guardar_metodo = 'add';
                            $('#form')[0].reset();
                            $('.form-group').removeClass('has-error');
                            $('.help-block').empty();
                            $('#modal_form').modal('show');
                            $('.modal-title').text('Añadir Producto');
                        }
                        function agregar_descuento() // Solo es para desplegar el modal
                        {
                            guardar_metodo = 'agregar_descuento';
                            $('#form')[0].reset();
                            $('.form-group').removeClass('has-error');
                            $('.help-block').empty();
                            $('#modal_descuento').modal('show');
                            $('.modal-title').text('Añadir Descuento');
                        }

                        function editar(id)
                        {
                            guardar_metodo = 'update';
                            $('#form')[0].reset(); // reset form on modals
                            $('.form-group').removeClass('has-error'); // clear error class
                            $('.help-block').empty(); // clear error string

                            //Ajax Load data from ajax
                            $.ajax({
                                url: "<?php echo site_url('Cierre/Controlador_cierre_estandar/Editar_datos/') ?>/" + id,
                                type: "GET",
                                dataType: "JSON",
                                success: function (data)
                                {

                                    $('[name="id"]').val(id);
                                    $('[name="cantidad"]').val(data.cantidad);
                                    $('[name="producto"]').val(data.id_producto);
                                    $('[name="total"]').val(data.total);

                                    $('#modal_form').modal('show'); // 
                                    $('.modal-title').text('Editar Producto'); // Titulo del Modal

                                },
                                error: function (jqXHR, textStatus, errorThrown)
                                {
                                    alert('Error en Obtener los Datos con AJAX');
                                }
                            });
                        }

                        function reload_table()
                        {
                            table.ajax.reload(null, false); //reload datatable ajax 
                            table2.ajax.reload(null, false); //reload datatable ajax 
                            obtener_montos();
                        }

                        function Guardar()
                        {
                            $('#btnSave').text('Guardando...'); //change button text
                            $('#btnSave').attr('disabled', true); //set button disable 
                            var url;

                            if (guardar_metodo == 'add') {
                                url = "<?php echo site_url('Cierre/Controlador_cierre_variable/Agregar_datos_Variable') ?>";
                            } else {
                                url = "<?php echo site_url('Cierre/Controlador_cierre_estandar/Actualizar_datos') ?>";
                            }

                            // ajax adding data to database
                            $.ajax({
                                url: url,
                                type: "POST",
                                data: $('#form').serialize(),
                                dataType: "JSON",
                                success: function (data)
                                {

                                    if (data.status) //if success close modal and reload ajax table
                                    {
                                        $('#modal_form').modal('hide');
                                        reload_table();

                                    } else
                                    {
                                        for (var i = 0; i < data.inputerror.length; i++)
                                        {
                                            $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                                            $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]); //select span help-block class set text error string
                                        }
                                    }
                                    $('#btnSave').text('Guardar'); //change button text
                                    $('#btnSave').attr('disabled', false); //set button enable 


                                },
                                error: function (jqXHR, textStatus, errorThrown)
                                {
                                    alert('Error Al Agregar / Actualizar Datos');
                                    $('#btnSave').text('Guardar'); //change button text
                                    $('#btnSave').attr('disabled', false); //set button enable 

                                }
                            });
                        }

                        //Guardar Descuento

                        function Guardar_descuento()
                        {
                            $('#btnSave').text('Guardando...'); //change button text
                            $('#btnSave').attr('disabled', true); //set button disable 
                            var url;

                            if (guardar_metodo == 'agregar_descuento') {
                                url = "<?php echo site_url('Cierre/Controlador_cierre_variable/Agregar_descuento') ?>";
                            } else {
                                url = "<?php echo site_url('Cierre/Controlador_cierre_variable/Actualizar_datos') ?>";
                            }

                            // ajax adding data to database
                            $.ajax({
                                url: url,
                                type: "POST",
                                data: $('#formulario_descuento').serialize(),
                                dataType: "JSON",
                                success: function (data)
                                {

                                    if (data.status) //if success close modal and reload ajax table
                                    {
                                        $('#modal_descuento').modal('hide');
                                        reload_table();

                                    } else
                                    {
                                        for (var i = 0; i < data.inputerror.length; i++)
                                        {
                                            $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                                            $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]); //select span help-block class set text error string
                                        }
                                    }
                                    $('#btnSave').text('Guardar'); //change button text
                                    $('#btnSave').attr('disabled', false); //set button enable 


                                },
                                error: function (jqXHR, textStatus, errorThrown)
                                {
                                    alert('Error Al Agregar / Actualizar Datos');
                                    $('#btnSave').text('Guardar'); //change button text
                                    $('#btnSave').attr('disabled', false); //set button enable 

                                }
                            });
                        }

                        function eliminar(id)
                        {
                            if (confirm('Esta seguro de eliminar los datos?'))
                            {
                                // ajax delete data to database
                                $.ajax({
                                    url: "<?php echo site_url('Cierre/Controlador_cierre_variable/Eliminar_datos') ?>/" + id,
                                    type: "POST",
                                    dataType: "JSON",
                                    success: function (data)
                                    {
                                        //if success reload ajax table
                                        $('#modal_form').modal('hide');
                                        reload_table();
                                    },
                                    error: function (jqXHR, textStatus, errorThrown)
                                    {
                                        alert('Error al eliminar datos');
                                    }
                                });

                            }
                        }


                        function eliminar_descuento(id)
                        {
                            if (confirm('Esta seguro de eliminar el Descuento?'))
                            {
                                // ajax delete data to database
                                $.ajax({
                                    url: "<?php echo site_url('Cierre/Controlador_cierre_variable/Eliminar_descuento') ?>/" + id,
                                    type: "POST",
                                    dataType: "JSON",
                                    success: function (data)
                                    {
                                        //if success reload ajax table
                                        $('#modal_descuento').modal('hide');
                                        reload_table();
                                    },
                                    error: function (jqXHR, textStatus, errorThrown)
                                    {
                                        alert('Error al eliminar datos');
                                    }
                                });

                            }
                        }
                        function total_producto() {
                            var cantidad = document.getElementsByName("cantidad")[0].value;
                            if (cantidad == "") {
                                alert("Debes ingresar la cantidad del Producto");
                                $('[name="producto"]').val(0);
                            } else {
                                $.ajax({
                                    url: "<?php echo site_url('Cierre/Controlador_cierre_estandar/total_producto/') ?>",

                                    type: "POST",
                                    data: $('#form').serialize(),
                                    dataType: "JSON",
                                    success: function (data)
                                    {

                                        $('[name="total"]').val(data.total);

                                    },
                                    error: function (jqXHR, textStatus, errorThrown)
                                    {
                                        alert('Error get data from ajax');
                                    }
                                });

                            }
                        }




                    </script>



                    <!-- Bootstrap modal
                    Modal a desplegar
                    -->
                    <div class="modal fade" id="modal_form" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h3 class="modal-title">Formulario Ingreso Productos Cierre</h3>
                                </div>
                                <div class="modal-body form">
                                    <form action="#" id="form" class="form-horizontal">
                                        <input type="hidden" value="" name="id"/> <!-- Dato Oculto para manejar Id -->
                                        <div class="form-body">
                                            <div class="x_panel">






                                                <div class="form-group">
                                                    <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Producto<span class="required">*</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <select name="producto" id="categoria" class="form-control"   required>
                                                            <option value="0">Seleccione Producto</option>
                                                            <?php foreach ($producto as $producto): ?>
                                                                <option  value="<?php print($producto->id_producto); ?>"><?php print($producto->nombre); ?></option>
<?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="total">Total <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" name ="total" id="total"  required="required" class="form-control col-md-7 col-xs-12">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" id="btnSave" onclick="Guardar()" class="btn btn-primary">Guardar</button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                </div>
                            </div><!-- /.Modal-Contenidos -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->

                    <!-- Modal dos de Descuento -->

                    <div class="modal fade" id="modal_descuento" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h3 class="modal-title">Formulario Ingreso Usuarios</h3>
                                </div>
                                <div class="modal-body form">
                                    <form action="#" id="formulario_descuento" class="form-horizontal">
                                        <input type="hidden" value="" name="id"/> <!-- Dato Oculto para manejar Id -->
                                        <div class="form-body">
                                            <div class="x_panel">






                                                <div class="form-group">
                                                    <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Tipo de Descuento<span class="required">*</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <select name="descuento" id="descuento" class="form-control"   required>
                                                            <option value="0">Seleccione Tipo de Descuento</option>
                                                            <?php foreach ($descuento as $descuento): ?>
                                                                <option  value="<?php print($descuento->id_detalle_descuento); ?>"><?php print($descuento->nombre); ?></option>
<?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="monto">Monto<span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" name ="monto" id="monto"  required="required" class="form-control col-md-7 col-xs-12">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" id="btnSave" onclick="Guardar_descuento()" class="btn btn-primary">Guardar</button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                </div>
                            </div><!-- /.Modal-Contenidos -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->


                    <!-- Fin Bootstrap modal -->
                </div>   </div>
        </div>   </div>

</div>


<!-- Librerias DataTable -->
<script src="<?php echo base_url('assets/dataTables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/dataTables/js/dataTables.bootstrap.js') ?>"></script>