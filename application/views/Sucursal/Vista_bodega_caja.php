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
                        <h2>Listado Productos en Bodega <small><?php printf($this->session->userdata("nombre_caja")); ?></small></h2>

                    </div>
                    <div class="x_content">
                        <p class="text-muted font-13 m-b-30">
                            Listado de Productos Actualmente en Bodega.
                        </p>
                        <button class="btn btn-success" onclick="Agregar()"><i class="glyphicon glyphicon-plus"></i> Agregar Stock a Caja</button>
                        <div class="table-responsive">
                            <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>

                                        <th>Id Caja</th>
                                        <th>Id Bodega</th>
                                        <th>Id Producto</th>
                                        <th>Cantidad</th>

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
                                    "url": "<?php echo site_url('Sucursal/Controlador_bodega_caja/Listar_Bodega_caja') ?>",
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
                                    "sLengthMenu": "Mostrar _MENU_  Productos",
                                    "sZeroRecords": "No se encontraron resultados",
                                    "sEmptyTable": "Ningún dato disponible en esta tabla",
                                    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                                    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                                    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                                    "sInfoPostFix": "",
                                    "sSearch": "Buscar Productos:  ",
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
                        function obtener_stock() {
                            $.ajax({
                                "url": "<?php echo site_url('Sucursal/Controlador_bodega_caja/obtener_stock') ?>",
                                type: "POST",
                                data: $('#form').serialize(),
                                dataType: "JSON",
                                success: function (data)
                                {

                                    $('[name="stock"]').val(data.stock_bodega);
                                    $('[name="id_bodega"]').val(data.id_bodega);


                                },
                                error: function (jqXHR, textStatus, errorThrown)
                                {
                                    alert('Error get data from ajax');
                                }
                            });
                        }
                        function comparar_stock() {
                            var cantidad = document.getElementById("cantidad").value;
                            var stock = document.getElementById("stock").value;
                            //     var producto= document.getElementById("producto").value;
                            //     if (stock === '') {
                            //       alert('Seleccione un Producto');
                            // } else {
                            if (stock < cantidad) {
                                alert('Error la cantidad no puede superar el stock de bodega');
                                $('[name="cantidad"]').val(0);
                            }
                            //    }


                        }
                        function Agregar()
                        {
                            save_method = 'add';
                            $('#form')[0].reset(); // reset form on modals
                            $('.form-group').removeClass('has-error'); // clear error class
                            $('.help-block').empty(); // clear error string
                            $('#modal_form').modal('show'); // show bootstrap modal
                            $('.modal-title').text('Añadir Stock'); // Set Title to Bootstrap modal title
                        }

                        function editar(id)
                        {
                            var cantidad = document.getElementById("cantidad").value;
                           // cantidad.style.pointerEvents = "none";
                           cantidad.onclick = false;

                            obtener_stock();
                            save_method = 'update';
                            $('#form')[0].reset(); // reset form on modals
                            $('.form-group').removeClass('has-error'); // clear error class
                            $('.help-block').empty(); // clear error string

                            //Ajax Load data from ajax
                            $.ajax({
                                url: "<?php echo site_url('Sucursal/Controlador_bodega_caja/editar/') ?>/" + id,
                                type: "GET",
                                dataType: "JSON",
                                success: function (data)
                                {

                                    $('[name="id"]').val(id);
                                    $('[name="cantidad"]').val(data.cantidad);
                                    $('[name="producto"]').val(data.id_producto);
                                    $('[name="correo"]').val(data.correo);
                                    $('[name="clave"]').val(data.pass);// el data tiene que tener el mismo nombre del campo de la BD
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
                                url = "<?php echo site_url('Sucursal/Controlador_bodega_caja/Guardar') ?>";
                            } else {
                                url = "<?php echo site_url('Sucursal/Controlador_bodega_caja/Actualizar') ?>";
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
                                    url: "<?php echo site_url('Sucursal/Controlador_bodega_caja/Eliminar') ?>/" + id,
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
                                        <input type="hidden" value="" name="id_bodega"/>

                                        <div class="form-body">
                                            <div class="x_panel">
                                                <div class="form-group">
                                                    <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Producto<span class="required">*</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <select name="producto" id="categoria" class="form-control" onchange="obtener_stock();"  required>
                                                            <option value="0">Seleccione Producto</option>
                                                            <?php foreach ($producto as $producto): ?>
                                                                <option  value="<?php print($producto->id_producto); ?>"><?php print($producto->nombre); ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                         <span class="help-block"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cantidad">Stock en Bodega<span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" name="stock" id="stock" required="required" class="form-control col-md-7 col-xs-12" readonly/>
                                                    <span class="help-block"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cantidad">Cantidad<span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12"> <!-- EN Q.A elimar el evento OnChange   -->
                                                        <input type="text" name="cantidad" id="cantidad" required="required" onchange="comparar_stock();" class="form-control col-md-7 col-xs-12">
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