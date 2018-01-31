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

        <title>Cierre de Caja</title>

        <meta name="description" content="Source code generated using layoutit.com">
        <meta name="author" content="LayoutIt!">

        <link href="<?php echo base_url(""); ?>assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo base_url(""); ?>assets/css/login.css" rel="stylesheet">
        <link href="<?php echo base_url(""); ?>assets/css/style.css" rel="stylesheet">



    </head>
    <body>
        <div class="container">

            <div class="row" id="pwd-container">
                <div class="col-md-4"></div>

                <div class="col-md-4">
                    <section class="login-form">
                        <form action="<?php echo base_url(""); ?>index.php/Controlador_acceso/conectar" method="post" class="form-horizontal" role="login">
                      <!--   <img alt="Logo Agencia Metropolitana" src="<?php echo base_url(""); ?>assets/imagenes/logo.png">
                            -->     <div class="col-md-12">
                                <h4 class="text-center">
                                    Control de Cierre de Cajas
                                </h4>
                            </div>
<!--
                            <div class="form-group">
                              
                                </label>
                                <div >
                                    <input type="email" name="email" id="inputEmail3" placeholder="Correo Electronico"  class="form-control input-lg"/>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                              
                                </label>
                                <div >
                                     <input type="password" name="password"class="form-control input-lg" id="inputPassword3" placeholder="Clave"  />
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <!--
                             -->  
                                  <input type="email" name="email" id="inputEmail3" placeholder="Correo Electronico" required class="form-control input-lg"/>
                                   <span class="help-block"></span>
                                  <input type="password" name="password"class="form-control input-lg" id="inputPassword3" placeholder="Clave" required="" />
                                   <span class="help-block"></span>
                           



                            <button type="submit" class="btn btn-lg btn-primary btn-block">Entrar al Sistema</button>
                            <div>
                                <a href="#">Solicitar Contraseña</a> 
                            </div> 

                        </form>

                        <div class="form-links">
                            Tecnologia potenciada por Morrigan SpA.
                        </div>
                    </section>  
                </div>

                <div class="col-md-4"></div>


            </div>  
        </div>

        <script src="<?php echo base_url(""); ?>assets/js/jquery.min.js"></script>
        <script src="<?php echo base_url(""); ?>assets/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url(""); ?>assets/js/scripts.js"></script>
    </body>
</html>