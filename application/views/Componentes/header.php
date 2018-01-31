<!DOCTYPE html>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->helper('url'); // Hay que implementar esto en todos los formularios para tomar BaseURL
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Control Cierre Caja</title>

    <meta name="description" content="Codigo generado por Morrigan.cl">
    <meta name="author" content="Morrigan SpA">

<!-- Librerias CSS BootStrap y Style -->
    <link href="<?php echo base_url(""); ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(""); ?>assets/css/style.css" rel="stylesheet">
        <link href="<?php echo base_url(""); ?>assets/css/menu.css" rel="stylesheet">
 <!-- Librerias JS BootStrap y Jquery -->
    <script src="<?php echo base_url(""); ?>assets/js/jquery.min.js"></script>
    <script src="<?php echo base_url(""); ?>assets/js/bootstrap.min.js"></script>
  <!--  <script src="<?php echo base_url(""); ?>assets/js/scripts.js"></script>-->
  </head>

  </head>
  <body>

    <div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<h3 class="text-danger text-center">
				Bienvenido <?php printf($this->session->userdata("nombre")); ?>
			</h3>
		</div>
	</div>


  </body>
