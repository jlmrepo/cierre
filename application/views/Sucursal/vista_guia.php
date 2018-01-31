
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
                        <h2>Guias de Despacho <small> Sucursal</small></h2>

                    </div>
                    <div class="x_content">
                        <p class="text-muted font-13 m-b-30">
                            Usuarios con privilegio de Sucursal, estos pueden modificar,eliminar y añadir Guias de Despacho.
                        </p>
                        <button class="btn btn-success" onclick="agregar_guia()"><i class="glyphicon glyphicon-plus"></i> Agregar Guia de Despacho</button>
                        <div class="table-responsive">
                            <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>

                                    <tr>
                                        <th>Proveedor</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                        <th>Numero Guia</th>
                                        <th>Total</th>   
                                        <th style="width:125px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>


                            </table>
                        </div>
                    </div>


                    <script type="text/javascript">

                        var guardar_metodo; //Cadena para para guardar la funcion que voy procesar
                        var table;

                        $(document).ready(function () {

                            //datatables
                            table = $('#table').DataTable({

                                "processing": true, //Feature control the processing indicator.
                                "serverSide": true, //Feature control DataTables' server-side processing mode.
                                "order": [], //Initial no order.

                                // Load data for the table's content from an Ajax source
                                "ajax": {
                                    "url": "<?php echo site_url('Sucursal/Controlador_guia/Listar_datos_sucursal') ?>",
                                    "type": "POST"
                                },

                                //Set column definition initialisation properties.
                                "columnDefs": [
                                    {
                                        "targets": [-1], //last column
                                        "orderable": false, //set not orderable
                                    },
                                ],
                                "language": {
                                    "sProcessing": "Procesando...",
                                    "sLengthMenu": "Mostrar _MENU_  Guia",
                                    "sZeroRecords": "No se encontraron resultados",
                                    "sEmptyTable": "Ningun dato disponible en esta tabla",
                                    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                                    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                                    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                                    "sInfoPostFix": "",
                                    "sSearch": "Buscar Guia:  ",
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

                        });



                        function agregar_guia() // Solo es para desplegar el modal
                        {
                            
                            guardar_metodo = 'add';
                            $('#form')[0].reset();
                            $('.form-group').removeClass('has-error');
                            $('.help-block').empty();
                            $('#modal_form').modal('show');
                            $('.modal-title').text('Añadir Guia');
                            document.getElementById('visual_categoria').style.display='block';
                        }

                        function edit_person(id)
                        {
                            //     document.location.href='<?php echo site_url('rutas/vista_detalle_guia') ?>';
                            guardar_metodo = 'update';
                            $('#form')[0].reset(); // reset form on modals
                            $('.form-group').removeClass('has-error'); // clear error class
                            $('.help-block').empty(); // clear error string

                            //Ajax Load data from ajax
                            $.ajax({
                                url: "<?php echo site_url('Sucursal/Controlador_guia//Editar_datos/') ?>/" + id,
                                type: "GET",
                                dataType: "JSON",
                                success: function (data)
                                {

                                    $('[name="id"]').val(id);
                                    $('[name="numero_guia"]').val(data.numero_guia);
                                    $('[name="fecha_guia"]').val(data.fecha_guia);
                                    $('[name="total_guia"]').val(data.total);
                                   
                                    $('[name="proveedores"]').find('option').remove();// Limpio el Select
                                    $('[name="proveedores"]').append('<option value="'+data.id_proveedor+'">'+data.nombre_proveedor+'</option>');

                                      document.getElementById('visual_categoria').style.display='none';
                                   
                                   
                                    $('#modal_form').modal('show'); // 
                                    $('.modal-title').text('Editar Guia'); // Titulo del Modal

                                },
                                error: function (jqXHR, textStatus, errorThrown)
                                {
                                    alert('Error get data from ajax');
                                }
                            });
                        }

                        function reload_table()
                        {
                            table.ajax.reload(null, false); //reload datatable ajax 
                        }

                        function Guardar()
                        {
                            $('#btnSave').text('Guardando...'); //change button text
                            $('#btnSave').attr('disabled', true); //set button disable 
                            var url;

                            if (guardar_metodo == 'add') {
                                url = "<?php echo site_url('Sucursal/Controlador_guia/Agregar_datos') ?>";
                            } else {
                                url = "<?php echo site_url('Sucursal/Controlador_guia/Actualizar_datos') ?>";
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
                                        //  reload_table();
                                        // No necesito recargar la tabla para ver los cambios sino que necesito crear el detalle
                                        document.location.href = '<?php echo site_url('rutas/vista_detalle_guia') ?>';
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

                        function delete_person(id)
                        {
                            if (confirm('Esta seguro de eliminar los datos?'))
                            {
                                // ajax delete data to database
                                $.ajax({
                                    url: "<?php echo site_url('Sucursal/Controlador_guia/Eliminar_datos') ?>/" + id,
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
                        
                         function enviar_bodega(id)
                        {
                
                            $.ajax({
                                url: "<?php echo site_url('Sucursal/Controlador_guia/Enviar_Bodega/') ?>/" + id,
                                type: "GET",
                                dataType: "JSON",
                                success: function (data)
                                {
                                    reload_table();
                                },
                                error: function (jqXHR, textStatus, errorThrown)
                                {
                                    alert('Error get data from ajax');
                                }
                            });
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
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="numero_guia">Numero Guia<span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" name="numero_guia" id="numero_guia" required="required" class="form-control col-md-7 col-xs-12">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="fecha_guia">Fecha Guia <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="date" name ="fecha_guia" id="fecha_guia" required="required" class="form-control col-md-7 col-xs-12">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="total_guia">Total <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" name ="total_guia" id="total_guia" required="required" class="form-control col-md-7 col-xs-12">
                                                    </div>
                                                </div>

                                                <div id="visual_categoria" class="form-group">
                                                    <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Categoria<span class="required">*</label>

                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <select name="categoria" id="categoria" class="form-control" required>
                                                            <option value="0">Seleccione una Categoria</option>
                                                            <?php foreach ($categoria as $categoria): ?>
                                                                <option value="<?php print($categoria->id_categoria); ?>"><?php print($categoria->nombre); ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>  
                                                <div class="form-group">
                                                    <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">proveedor<span class="required">*</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <span id="esperando"></span>
                                                        <select id="proveedores" name="proveedores" class="form-control" required>
                                                            <option value="0">Seleccione un Proveedor</option>

                                                        </select>
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

<!-- Script AJAX para cargar los proveedores-->
<script src="<?php echo base_url(""); ?>assets/js/Categoria_proveedor.js"></script>
