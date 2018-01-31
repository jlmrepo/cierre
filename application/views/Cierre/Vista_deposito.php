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
                        <h4>Cierre de Caja IDº <small> <?php echo $this->session->userdata('id_cierre'); ?></small></h4>
                        <h4>Categoria:<small> <?php echo $this->session->userdata('id_categoria'); ?></small></h4>
                        <h4 class="text-primary text-right" >Fecha: <small> <?php echo $this->session->userdata('fecha'); ?></small></h4>
                        <p class="text-muted font-13 m-b-30">

                        <h3 class="text-primary text-center">TOTAL DEPOSITOS : <input type="text" name="suma_total" style="border:none" disabled="true"/></h3>
                        </p>
                    </div>


                    <button class="btn btn-success" onclick="agregar_deposito()"><i class="glyphicon glyphicon-plus"></i>Agregar Deposito</button>


                    <button class="btn btn-info" onclick="location.href = 'http://cierrecaja.cl/index.php/rutas/vista_detalle_cierre';"><i class="glyphicon glyphicon-share-alt"></i> Volver a Cierre</button>

                    <div class="table-responsive">
                        <table id="depositos" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr> 
                                    <th>Tipo Deposito</th>
                                    <th> Banco</th>
                                    <th> Cierre</th>
                                    <th> Categoria </th>
                                    <th> Fecha </th>
                                    <th> Boleta Deposito</th>
                                    <th> Cheque</th>
                                    <th> Monto </th>

                                    <th style="width:125px;">Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>




                    <script type="text/javascript">
                        var guardar_metodo;
                        var table;
                        $(document).ready(function () {
                            // Carga de DataTable Nº1 Productos
                            table = $('#depositos').DataTable({
                                "processing": true,
                                "serverSide": true,
                                "order": [],
                                "ajax": {
                                    "url": "<?php echo site_url('Cierre/Controlador_deposito/Listar_deposito') ?>",
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
                        function tipo() {
                            var tipo = document.getElementById('tipo_deposito').value;
                            if (tipo == 1) {
                                document.getElementById('cheque').style.display = 'none';
                                $('[name="nro_cheque"]').val(null);
                            } else {
                                document.getElementById('cheque').style.display = 'block';
                            }
                        }
                        function obtener_montos() {
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



                        function agregar_deposito() // Solo es para desplegar el modal
                        {
                            guardar_metodo = 'add';
                            $('#form')[0].reset();
                            $('.form-group').removeClass('has-error');
                            $('.help-block').empty();
                            $('#modal_form').modal('show');
                            $('.modal-title').text('Añadir Deposito');
                        }
                   

                        function editar(id)
                        {
                            guardar_metodo = 'update';
                            $('#form')[0].reset(); // reset form on modals
                            $('.form-group').removeClass('has-error'); // clear error class
                            $('.help-block').empty(); // clear error string

                            //Ajax Load data from ajax
                            $.ajax({
                                url: "<?php echo site_url('Cierre/Controlador_deposito/Editar_datos/') ?>/" + id,
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

                     
                     
                        //Guardar Deposito

                        function Guardar_deposito()
                        {
                            $('#btnSave').text('Guardando...'); //change button text
                            $('#btnSave').attr('disabled', true); //set button disable 
                            var url;

                            if (guardar_metodo == 'add') {
                                url = "<?php echo site_url('Cierre/Controlador_deposito/Agregar_deposito') ?>";
                            } else {
                                url = "<?php echo site_url('Cierre/Controlador_deposito/Actualizar_datos') ?>";
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
                                    url: "<?php echo site_url('Cierre/Controlador_deposito/Eliminar') ?>/" + id,
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
                                    url: "<?php echo site_url('Cierre/Controlador_deposito/total_producto/') ?>",

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
                                                    <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Tipo Deposito<span class="required">*</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <select name="tipo_deposito" id="tipo_deposito" class="form-control"  onchange="tipo();" required>
                                                            <option value="0">Seleccione Tipo de Deposito</option>
                                                            <?php foreach ($tipo_deposito as $tipo): ?>
                                                                <option  value="<?php print($tipo->id_tipo_deposito); ?>"><?php print($tipo->nombre); ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Banco<span class="required">*</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <select name="banco" id="banco" class="form-control"  required>
                                                            <option value="0">Seleccione Banco</option>
                                                            <?php foreach ($banco as $banco): ?>
                                                                <option  value="<?php print($banco->id_banco); ?>"><?php print($banco->nombre); ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="boleta_deposito">Boleta Deposito<span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" name ="boleta_deposito" id="boleta_deposito"  required="required" class="form-control col-md-7 col-xs-12">
                                                    </div>
                                                </div>
                                                <div class="form-group" id="cheque">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nro_cheque">Nro Cheque <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" name ="nro_cheque" id="nro_cheque"  required="required" class="form-control col-md-7 col-xs-12">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="monto">Monto <span class="required">*</span>
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
                                    <button type="button" id="btnSave" onclick="Guardar_deposito()" class="btn btn-primary">Guardar</button>
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
                                    <button type="button" id="btnSave" onclick="Guardar_deposito()" class="btn btn-primary">Guardar</button>
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