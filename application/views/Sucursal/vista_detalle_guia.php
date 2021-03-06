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
                        <h2>Productos Guia Nº <small> <?php echo $this->session->userdata('id_guia'); ?></small></h2>

                    </div>
                    <div class="x_content">
                        <p class="text-muted font-13 m-b-30">
                            Guia de despacho.
                        <p>Monto de la Guia: <input type="text" name="monto_guia" disabled="true"/></p>
                        <p>Monto del Detalle: <input type="text" name="monto_detalle" disabled="true"/></p>
                        <p>Estado: <input type="text" name="estado" value="Descuadre $200.000" disabled="true"/> </p>
                        </p>
                        <button class="btn btn-success" onclick="agregar_guia()"><i class="glyphicon glyphicon-plus"></i>Agregar Producto</button>
                        <button class="btn btn-info" onclick="location.href = 'http://cierrecaja.cl/index.php/rutas/vista_guia';"><i class="glyphicon glyphicon-share-alt"></i> Volver a Guia</button>
                       
                        <div class="table-responsive">
                            <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Guia</th>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Total</th>
                                        <th style="width:125px;">Accion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>


                            </table>
                        </div>
                    </div>


                    <script type="text/javascript">
                        var guardar_metodo;
                        var table;
                        $(document).ready(function () {
                            /*
                             $.get('<?php echo site_url('Sucursal/Controlador_detalle_guia/Listar_datos_guia') ?>',function(data){
                             var content = JSON.parse(data);
                             
                             });
                             */
                            //datatables
                            /*   $.ajax({
                             url: "<?php echo site_url('Sucursal/Controlador_detalle_guia/Listar_datos_guia') ?>",
                             type: "POST",
                             
                             success: function (data)
                             {
                             $('[name="monto_guia"]').val(data.monto_guia);
                             
                             },
                             error: function (jqXHR, textStatus, errorThrown)
                             {
                             alert('Error get data from ajax');
                             }
                             });
                             */


                            table = $('#table').DataTable({
                                "processing": true,
                                "serverSide": true,
                                "order": [],
                                "ajax": {
                                    "url": "<?php echo site_url('Sucursal/Controlador_detalle_guia/Listar_datos_guia') ?>",
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
                                "url": "<?php echo site_url('Sucursal/Controlador_detalle_guia/obtener_montos') ?>",
                                type: "POST",
                                data: $('#form').serialize(),
                                dataType: "JSON",
                                success: function (data)
                                {
                                    $('[name="monto_guia"]').val(data.monto_guia);
                                    $('[name="monto_detalle"]').val(data.monto_detalle);
                                    $('[name="estado"]').val(data.estado);

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

                        function editar(id)
                        {
                            guardar_metodo = 'update';
                            $('#form')[0].reset(); // reset form on modals
                            $('.form-group').removeClass('has-error'); // clear error class
                            $('.help-block').empty(); // clear error string

                            //Ajax Load data from ajax
                            $.ajax({
                                url: "<?php echo site_url('Sucursal/Controlador_detalle_guia/Editar_datos/') ?>/" + id,
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
                            obtener_montos();
                        }

                        function Guardar()
                        {
                            $('#btnSave').text('Guardando...'); //change button text
                            $('#btnSave').attr('disabled', true); //set button disable 
                            var url;

                            if (guardar_metodo == 'add') {
                                url = "<?php echo site_url('Sucursal/Controlador_detalle_guia/Agregar_datos') ?>";
                            } else {
                                url = "<?php echo site_url('Sucursal/Controlador_detalle_guia/Actualizar_datos') ?>";
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

                        function eliminar(id)
                        {
                            if (confirm('Esta seguro de eliminar los datos?'))
                            {
                                // ajax delete data to database
                                $.ajax({
                                    url: "<?php echo site_url('Sucursal/Controlador_detalle_guia/Eliminar_datos') ?>/" + id,
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
                        function total_producto() {
                            var cantidad = document.getElementsByName("cantidad")[0].value;
                            if (cantidad == "") {
                                alert("Debes ingresar la cantidad del Producto");
                                $('[name="producto"]').val(0);
                            } else {
                                $.ajax({
                                    url: "<?php echo site_url('Sucursal/Controlador_detalle_guia/total_producto/') ?>",

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
                                    <h3 class="modal-title">Formulario Ingreso Usuarios</h3>
                                </div>
                                <div class="modal-body form">
                                    <form action="#" id="form" class="form-horizontal">
                                        <input type="hidden" value="" name="id"/> <!-- Dato Oculto para manejar Id -->
                                        <div class="form-body">
                                            <div class="x_panel">


                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cantidad">Cantidad<span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" name="cantidad" id="cantidad" required="required" onchange="total_producto();" class="form-control col-md-7 col-xs-12">
                                                    </div>
                                                </div>



                                                <div class="form-group">
                                                    <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Producto<span class="required">*</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <select name="producto" id="categoria" class="form-control" onchange="total_producto();"  required>
                                                            <option value="0">Seleccione Producto</option>
                                                            <?php foreach ($producto as $producto): ?>
                                                                <option  value="<?php print($producto->id_producto); ?>"><?php print($producto->nombre_producto); ?></option>
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
                    <!-- Fin Bootstrap modal -->
                </div>   </div>
        </div>   </div>

</div>


<!-- Librerias DataTable -->
<script src="<?php echo base_url('assets/dataTables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/dataTables/js/dataTables.bootstrap.js') ?>"></script>