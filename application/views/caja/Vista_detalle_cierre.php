

<div class="col-md-9">
    <!-- 
    <?php print_r($this->session->userdata()); ?> lo utilizo para ver toda la session 
    ojo que es Print_r ------
    -->

    <p style="text-align:right;">    Fecha Cierre : <?php print_r($this->session->userdata("fecha")); ?> </p>
<!--    <p> Id Cierre: <?php print_r($this->session->userdata("id_cierre")); ?>     ' Variable solo en entorno de Desarrollo
    -->   <div class="panel panel-default">

        <div style="text-align:center;" class="panel-heading"> <?php printf($this->session->userdata("nombre_sucursal")); ?>   - <?php printf($this->session->userdata("nombre_caja")); ?></div>
        <div class="panel-body">
            <p>Usuario Conectado : <?php printf($this->session->userdata("nombre")); ?> </p>
        </div>

        <table class="table">
            <p>RUT : <?php printf($this->session->userdata("rut")); ?> </p>
            <p>Caja: <?php
                printf($this->session->userdata("nombre_caja"));
                $CONTADOR = 0;
                ?> </p>
        </table>
        <p  style="text-align:center;">     <button class="btn btn-ttc" type="button" onclick="location.href = 'http://cierrecaja.cl/index.php/rutas/vista_resumen/<?php print($this->session->userdata("id_cierre")); ?>';" style="width:170px; height:50px; text-align: left;"><i class="glyphicon glyphicon-eye-open"></i>Resumen Cierre</button>
        </p>
    </div>

    <?php foreach ($categoria as $categoria): ?>

        <div class="panel panel-primary">
            <div style="text-align:center;" class="panel-heading"><?php print("CATEGORIA: " . $categoria->nombre); ?></div>

            <?php
            if (!empty($proveedor)) {  // Este lo puse de adorno
                $variable = $proveedor;  // ojo que si no cargo el objeto en una variable genera duplicidad de datos
                foreach ($variable as $variable):
                    if ($categoria->id_categoria === $variable->id_categoria) {
                        ?>
                        <div class="panel panel-info">

                            <div style="text-align:center;" class="panel-heading"><?php print("Proveedor: " . $variable->nombre); ?></div>
                            <div class="panel-body">
                                <p  style="text-align:center;">
                                    <?php
                                    $variable2 = $familia;

                                    foreach ($variable2 as $variable2):

                                        if ($variable->id_proveedor === $variable2->id_proveedor) {
                                            if ($variable2->nombre == "VOLUMEN") {// Numero de familia loteria
                                                $CONTADOR++;
                                                ?>
                                                <button class="btn btn-danger" type="button" onclick="location.href = 'http://cierrecaja.cl/index.php/rutas/vista_masivo/<?php print($variable2->id_familia); ?>';" style="width:170px; height:50px; text-align: right;"><i ></i> IMP POR VOLUMEN</button>  
                                            <?php } ?>

                                            <button class="btn btn-jorge" type="button" onclick="location.href = 'http://cierrecaja.cl/index.php/rutas/vista_cierre_familia/<?php print($variable2->id_familia); ?>';" style="width:170px; height:50px; text-align: left;"><i class="glyphicon glyphicon-list-alt"></i><?php print($variable2->nombre); ?></button> 

                                        <?php } ?>
                                    <?php endforeach; ?>

                            </div>
                            <!--<table class="table">
                                <tr>
                                    <th>Ventas</th>
                                    <th>Descuentos</th>
                                    <th>Total</th>
                                </tr>
                                <tr>
                                    <td><?php
                                        $detalle = $detalle_cierre;
                                        foreach ($detalle as $detalle):
                                            if ($detalle->id_proveedor === $variable->id_proveedor) {
                                                echo "$" . $detalle->total;
                                            } else {
                                                echo "$0";
                                            }
                                        endforeach;
                                        ?>
                                    </td>
                                    <td>$0</td>
                                    <td>$0</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </table> -->
                        </div>
                    <?php }endforeach;
                ?>
                <p  style="text-align:center;"><button class="btn btn-primary" type="button" onclick="location.href = 'http://cierrecaja.cl/index.php/rutas/vista_deposito/<?php print($categoria->id_categoria); ?>';" style="width:150px; height:50px; text-align: left;"><i class="glyphicon glyphicon-plus"></i> DEPOSITOS</button></p>
            </div> 

        <?php } endforeach; ?>



    <!--
    
    <div class="panel panel-primary">
        <div style="text-align:center;" class="panel-heading">Juegos de Azar</div>
        <div class="panel panel-info">

            <div style="text-align:center;" class="panel-heading">Loteria</div>
            <div class="panel-body">
    <?php foreach ($producto as $producto): ?>
                                                                        <option  value="<?php print($producto->id_producto); ?>"><?php print($producto->nombre); ?></option>
    <?php endforeach; ?>

                <td><button class="btn btn-success" type="button" onclick="location.href = 'http://cierrecaja.cl/index.php/rutas/vista_cierre_familia/1';" style="width:170px; height:50px; text-align: left;"><i class="glyphicon glyphicon-plus"></i>PRE - IMPRESOS</button> </td>
                <td><button class="btn btn-success" type="button" onclick="location.href = 'http://ruta';" style="width:150px; height:50px; text-align: left;"><i class="glyphicon glyphicon-plus"></i>SUB - AGENTE</button> </td>
                <td><button class="btn btn-success" type="button" onclick="location.href = 'http://ruta';" style="width:150px; height:50px; text-align: left;"><i class="glyphicon glyphicon-plus"></i> ONLINE</button> </td>
                <td><button class="btn btn-danger" type="button" onclick="location.href = 'http://ruta';" style="width:200px; height:50px; text-align: left;"><i class="glyphicon glyphicon-plus"></i> Impresion Masiva</button> </td>



            </div>

            <table class="table">

                <tr>
                    <th>Ventas</th>
                    <th>Descuentos</th>
                    <th>Total</th>
                </tr>
                <tr>
                    <td>$xxx.xxx</td>
                    <td>$xxx.xxx</td>
                    <td>$xxx.xxx</td>
                </tr>
                <tr>
                    <td>   <button class="btn btn-success" type="button" onclick="location.href = 'http://ruta';" style="width:150px; height:50px; text-align: left;"><i class="glyphicon glyphicon-plus"></i> DEPOSITOS</button> </td>
                    <td></td>
                    <td></td>
                </tr>


            </table>
        </div>
        <div class="panel-info">

            <div style="text-align:center;" class="panel-heading">Polla</div>
            <div class="panel-body">

                <td><button class="btn btn-success" type="button" onclick="location.href = 'http://ruta';" style="width:170px; height:50px; text-align: left;"><i class="glyphicon glyphicon-plus"></i>PRE - IMPRESOS</button> </td>
                <td><button class="btn btn-success" type="button" onclick="location.href = 'http://ruta';" style="width:150px; height:50px; text-align: left;"><i class="glyphicon glyphicon-plus"></i>SUB - AGENTE</button> </td>
                <td><button class="btn btn-success" type="button" onclick="location.href = 'http://ruta';" style="width:150px; height:50px; text-align: left;"><i class="glyphicon glyphicon-plus"></i> ONLINE</button> </td>

            </div>
            <table class="table">
                <tr>
                    <th>Ventas</th>
                    <th>Descuentos</th>
                    <th>Total</th>
                </tr>
                <tr>
                    <td>$xxx.xxx</td>
                    <td>$xxx.xxx</td>
                    <td>$xxx.xxx</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>   <button class="btn btn-success" type="button" onclick="location.href = 'http://ruta';" style="width:150px; height:50px; text-align: left;"><i class="glyphicon glyphicon-plus"></i> DEPOSITOS</button> </td>
                    <td></td>
                    <td></td>
                </tr>

            </table>

        </div>

    </div>

    <div class="panel panel-primary">
        <div style="text-align:center;" class="panel-heading">Retail</div>
        <div class="panel panel-info">

            <div style="text-align:center;" class="panel-heading">Multicaja</div>
            <div class="panel-body">


                <td><button class="btn btn-success" type="button" onclick="location.href = 'http://ruta';" style="width:170px; height:50px; text-align: left;"><i class="glyphicon glyphicon-plus"></i>Telefonia</button> </td>
                <td><button class="btn btn-success" type="button" onclick="location.href = 'http://ruta';" style="width:150px; height:50px; text-align: left;"><i class="glyphicon glyphicon-plus"></i>Depositos</button> </td>


            </div>

            <table class="table">

                <tr>
                    <th>Ventas</th>
                    <th>Descuentos</th>
                    <th>Total</th>
                </tr>
                <tr>
                    <td>$xxx.xxx</td>
                    <td>$xxx.xxx</td>
                    <td>$xxx.xxx</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>


            </table>
        </div>
        <div class="panel-info">

            <div style="text-align:center;" class="panel-heading">BAT</div>
            <div class="panel-body">
                <td><button class="btn btn-success" type="button" onclick="location.href = 'http://ruta';" style="width:170px; height:50px; text-align: left;"><i class="glyphicon glyphicon-plus"></i>Tabaqueria</button> </td>
                <td><button class="btn btn-success" type="button" onclick="location.href = 'http://ruta';" style="width:150px; height:50px; text-align: left;"><i class="glyphicon glyphicon-plus"></i>Depositos</button> </td>

            </div>
            <table class="table">
                <tr>
                    <th>Ventas</th>
                    <th>Descuentos</th>
                    <th>Total</th>
                </tr>
                <tr>
                    <td>$xxx.xxx</td>
                    <td>$xxx.xxx</td>
                    <td>$xxx.xxx</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>


            </table>

        </div>
    </div>     

    -->

</div>

</div>
</div>
<style type="text/css">

/* Cambiando Stylo de botones en 3 estados*/
    .btn-ttc {

        background-image: linear-gradient(to right, #000428 0%, #004e92 51%, #000428 100%);

    }
    .btn-ttc:hover {
        background-position: right center;
    }
    .btn-ttc,
    .btn-ttc:hover,
    .btn-ttc:active {
        color: white;
        text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
        background-color: #007da7;
    }

     .btn-jorge {

         -moz-box-shadow:inset 0px 1px 0px 0px #d9fbbe;
	-webkit-box-shadow:inset 0px 1px 0px 0px #d9fbbe;
	box-shadow:inset 0px 1px 0px 0px #d9fbbe;
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #b8e356), color-stop(1, #a5cc52) );
	background:-moz-linear-gradient( center top, #b8e356 5%, #a5cc52 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#b8e356', endColorstr='#a5cc52');
	background-color:#b8e356;
	-webkit-border-top-left-radius:37px;
	-moz-border-radius-topleft:37px;
	border-top-left-radius:37px;
	-webkit-border-top-right-radius:0px;
	-moz-border-radius-topright:0px;
	border-top-right-radius:0px;
	-webkit-border-bottom-right-radius:37px;
	-moz-border-radius-bottomright:37px;
	border-bottom-right-radius:37px;
	-webkit-border-bottom-left-radius:0px;
	-moz-border-radius-bottomleft:0px;
	border-bottom-left-radius:0px;
	text-indent:0;
	border:1px solid #83c41a;
	display:inline-block;
	color:#ffffff;
	font-family:Arial;
	font-size:13px;
	font-weight:bold;
	font-style:normal;
	height:40px;
	line-height:40px;
	width:100px;
	text-decoration:none;
	text-align:center;
	text-shadow:1px 1px 0px #86ae47;

    }
    .btn-jorge:hover {
        background-position: right center;
    }
    .btn-jorge,
    .btn-jorge:hover,
    .btn-jorge:active {
        color: white;
        text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
        background-color: #007da7;
    }
</style>