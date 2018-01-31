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
                        <h2>Listado Sucursales <small>administrador</small></h2>
                       
                    </div>
                    <div class="x_content">
                        <p class="text-muted font-13 m-b-30">
                            Usuarios con privilegio de administrador, estos pueden modificar,eliminar y añadir Sucursales.
                        </p>
                      
                        <div class="table-responsive">
                            <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Direccion</th>
                                        <th>Comuna</th>
                                        <th>Jefe Local</th>
                                        <th>Empresa</th>
                                        
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
                                    "url": "<?php echo site_url('Administrador/Controlador_sucursales/Listar_datos_gestion') ?>",
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
                                    "sLengthMenu": "Mostrar _MENU_  Sucursales",
                                    "sZeroRecords": "No se encontraron resultados",
                                    "sEmptyTable": "Ningun dato disponible en esta tabla",
                                    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                                    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                                    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                                    "sInfoPostFix": "",
                                    "sSearch": "Buscar Sucursales:  ",
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



       
                        function reload_table()
                        {
                            table.ajax.reload(null, false); //reload datatable ajax 
                        }

        
                    </script>



                    <!-- Bootstrap modal
                    Modal a desplegar
                    -->
                     <th>Nombre</th>
                                        <th>Direccion</th>
                                        <th>Comuna</th>
                                        <th>Jefe Local</th>
                                        <th>Empresa</th>
                    <div class="modal fade" id="modal_form" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h3 class="modal-title">Formulario Ingreso Sucursales</h3>
                                </div>
                                <div class="modal-body form">
                                    <form action="#" id="form" class="form-horizontal">
                                        <input type="hidden" value="" name="id"/> <!-- Dato Oculto para manejar Id -->
                                        <div class="form-body">
                                            <div class="x_panel">


                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nombre">Nombre<span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" name="nombre" id="nombre" required="required" class="form-control col-md-7 col-xs-12">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="direccion">Direccion <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" name ="direccion" id="direccion" required="required" class="form-control col-md-7 col-xs-12">
                                                    </div>
                                                </div>
                                                
                                                 <div class="form-group">
                                                    <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Region<span class="required">*</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <select id="region" class="form-control" required>
                                                            <option value="0">Seleccione una Region</option>
                                                            <?php foreach ($region as $region): ?>
                                                                <option value="<?php print($region->id_region); ?>"><?php print($region->nombre); ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Comuna<span class="required">*</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <span id="esperando"></span>
                                                        <select id="comunas" name="comunas" class="form-control" required>
                                                            <option value="0">Seleccione una Comuna</option>

                                                        </select>
                                                    </div>

                                                </div>
                                                <div class="form-group">
                                                    <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Empresa<span class="required">*</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <select name="empresa" id="empresa" class="form-control" required>
                                                            <option value="0">Seleccione una Empresa</option>
                                                            <?php foreach ($empresa as $empresa): ?>
                                                                <option value="<?php print($empresa->id_empresa); ?>"><?php print($empresa->nombre); ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                 <div class="form-group">
                                                    <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Jefe Local<span class="required">*</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <select name="usuario" id="usuario" class="form-control" required>
                                                            <option value="0">Seleccione un Usuario</option>
                                                            <?php foreach ($usuario as $usuario): ?>
                                                                <option value="<?php print($usuario->id_usuario); ?>"><?php print($usuario->nombre); ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                         


                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Guardar</button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                </div>
                            </div><!-- /.Modal-Contenidos -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                    <!-- Fin Bootstrap modal -->
                </div>   </div>
        </div>   </div>

</div>
<!-- Script AJAX para cargar las comunas-->
<script src="<?php echo base_url(""); ?>assets/js/BuscarComuna.js"></script>

<!-- Librerias DataTable -->
<script src="<?php echo base_url('assets/dataTables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/dataTables/js/dataTables.bootstrap.js') ?>"></script>