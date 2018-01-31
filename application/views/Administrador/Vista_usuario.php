<div class="col-md-9"> 
    <!-- Inicio Pantalla principal 
    Esta es donde se gestionan los contenidos es la unica que cambia del sitema 
    -->
    <div class=""> 
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Listado Usuarios del Sistema <small>administrador</small></h2>
                     
                    </div>
                    <div class="x_content">
                        <p class="text-muted font-13 m-b-30">
                            Usuarios con privilegio de administrador, estos pueden modificar,eliminar y añadir .
                        </p>
                        <button class="btn btn-success" onclick="Agregar()"><i class="glyphicon glyphicon-plus"></i> Agregar Usuarios</button>
                        <div class="table-responsive">
                            <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>

                                        <th>Rut</th>
                                        <th>Nombre</th>
                                        <th>Correo</th>
                                        <th>Clave</th>
                                        <th style="width:125px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>


                            </table>
                        </div>
                    </div>

                    <script type="text/javascript">
                        var save_method; 
                        var table;
                        $(document).ready(function () {
                            table = $('#table').DataTable({
                                "processing": true, 
                                "serverSide": true, 
                                "order": [], 
                                "ajax": {
                                    "url": "<?php echo site_url('Administrador/Controlador_usuario/Listar_Activos') ?>",
                                    "type": "POST"
                                },
                                "columnDefs": [
                                    {
                                        "targets": [-1], //last column
                                        "orderable": false, //set not orderable
                                    },
                                ],
                                "language": {
                                    "sProcessing": "Procesando...",
                                    "sLengthMenu": "Mostrar _MENU_  Usuarios",
                                    "sZeroRecords": "No se encontraron resultados",
                                    "sEmptyTable": "Ningún dato disponible en esta tabla",
                                    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                                    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                                    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                                    "sInfoPostFix": "",
                                    "sSearch": "Buscar Usuarios:  ",
                                    "sUrl": "",
                                    "sInfoThousands": ",",
                                    "sLoadingRecords": "Cargando...",
                                    "oPaginate": {
                                        "sFirst": "Primero",
                                        "sLast": "Último",
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


                        function Agregar()
                        {
                            save_method = 'add';
                            $('#form')[0].reset(); // reset form on modals
                            $('.form-group').removeClass('has-error'); // clear error class
                            $('.help-block').empty(); // clear error string
                            $('#modal_form').modal('show'); // show bootstrap modal
                            $('.modal-title').text('Añadir Usuarios'); // Set Title to Bootstrap modal title
                        }

                        function editar(id)
                        {
                            save_method = 'update';
                            $('#form')[0].reset(); // reset form on modals
                            $('.form-group').removeClass('has-error'); // clear error class
                            $('.help-block').empty(); // clear error string

                            //Ajax Load data from ajax
                            $.ajax({
                                url: "<?php echo site_url('Administrador/Controlador_usuario/editar/') ?>/" + id,
                                type: "GET",
                                dataType: "JSON",
                                success: function (data)
                                {

                                    $('[name="id"]').val(id);
                                    $('[name="rut"]').val(data.rut);
                                    $('[name="nombre"]').val(data.nombre);
                                    $('[name="correo"]').val(data.correo);
                                    $('[name="clave"]').val(data.clave);// el data tiene que tener el mismo nombre del campo de la BD
                                    $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                                    $('.modal-title').text('Editar Usuarios'); // Set title to Bootstrap modal title

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

                            if (save_method == 'add') {
                                url = "<?php echo site_url('Administrador/Controlador_usuario/Guardar') ?>";
                            } else {
                                url = "<?php echo site_url('Administrador/Controlador_usuario/Actualizar') ?>";
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
                                    alert('Error adding / update data');
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
                                    url: "<?php echo site_url('Administrador/Controlador_usuario/Eliminar') ?>/" + id,
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
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="rut">Rut<span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" name="rut" id="rut" required="required" class="form-control col-md-7 col-xs-12">
                                                     <span class="help-block"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nombre">nombre <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" name ="nombre" id="nombre" required="required" class="form-control col-md-7 col-xs-12">
                                                     <span class="help-block"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="correo">Correo<span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" name ="correo" id="correo" required="required" class="form-control col-md-7 col-xs-12">
                                                    <span class="help-block"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="clave">clave <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" name ="clave" id="clave" required="required" class="form-control col-md-7 col-xs-12">
                                                     <span class="help-block"></span>
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


<script src="<?php echo base_url('assets/dataTables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/dataTables/js/dataTables.bootstrap.js') ?>"></script>

