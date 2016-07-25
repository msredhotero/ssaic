<?php


require 'includes/funcionesUsuarios.php';
include ('includes/funciones.php');

$serviciosUsuarios = new ServiciosUsuarios();
$servicios = new Servicios();

$resTipoTorneos = $servicios->traerTipoTorneo();

?>
<!DOCTYPE HTML>
<html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">



<title>Acceso Restringido: AIF</title>



		<link rel="stylesheet" type="text/css" href="css/estilo.css"/>

<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>

         <link rel="stylesheet" href="css/jquery-ui.css">

    <script src="js/jquery-ui.js"></script>

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="bootstrap/css/bootstrap-theme.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="bootstrap/js/bootstrap.min.js"></script>
        
        <style>
		button::-moz-focus-inner { border: 0; } /* Para Firefox */

 .boton3d{
        border:0;
      color: #107295;
        font-size: 30px;
        font-family:sans-serif;
        padding: 10px 18px;
      background: -moz-linear-gradient(center top , #64c3e4 0%, #3599bc 100%) repeat scroll 0 0 transparent;
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#64c3e4), color-stop(100%,#3599bc));
      -moz-border-radius: 5px;
        -webkit-border-radius: 5px;
      text-shadow: 1px 1px 1px #aceaff;
      -moz-box-shadow: 0px 3px 0 #235d71;
        -webkit-box-shadow: 0px 3px 0 #235d71;

    }

 .boton3d:active{
    position:relative;
     top:3px;
    -moz-box-shadow:0px 3px 0 #819F45;
    -webkit-box-shadow:0px 3px 0 #819F45
    background: -moz-linear-gradient(center top , #A5C956 0%, #CDEB8E 100%) repeat scroll 0 0 transparent;
     background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#a5c956), color-stop(100%,#cdeb8e));
    }
	
	
	.boton3dt{
        border:0;
      color: #0f8c47;
        font-size: 30px;
        font-family:sans-serif;
        padding: 10px 18px;
      background: -moz-linear-gradient(center top , #61c98f 0%, #34a667 100%) repeat scroll 0 0 transparent;
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#61c98f), color-stop(100%,#34a667));
      -moz-border-radius: 5px;
        -webkit-border-radius: 5px;
      text-shadow: 1px 1px 1px #a0fdc9;
      -moz-box-shadow: 0px 3px 0 #257247;
        -webkit-box-shadow: 0px 3px 0 #257247;

    }
	
	.boton3dt:hover {
		color: #0f8c47;
	}

 .boton3dt:active{
    position:relative;
     top:3px;
    -moz-box-shadow:0px 3px 0 #819F45;
    -webkit-box-shadow:0px 3px 0 #819F45
    background: -moz-linear-gradient(center top , #A5C956 0%, #CDEB8E 100%) repeat scroll 0 0 transparent;
     background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#a5c956), color-stop(100%,#cdeb8e));
    }
	
	.boton3dr{
        border:0;
      color: #186b75;
        font-size: 30px;
        font-family:sans-serif;
        padding: 10px 18px;
      background: -moz-linear-gradient(center top , #6acad6 0%, #3ba5b2 100%) repeat scroll 0 0 transparent;
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#6acad6), color-stop(100%,#3ba5b2));
      -moz-border-radius: 5px;
        -webkit-border-radius: 5px;
      text-shadow: 1px 1px 1px #9ef4ff;
      -moz-box-shadow: 0px 3px 0 #19646d;
        -webkit-box-shadow: 0px 3px 0 #19646d;

    }
	
	.boton3dr:hover {
		color: #186b75;
	}

 .boton3dr:active{
    position:relative;
     top:3px;
    -moz-box-shadow:0px 3px 0 #819F45;
    -webkit-box-shadow:0px 3px 0 #819F45
    background: -moz-linear-gradient(center top , #A5C956 0%, #CDEB8E 100%) repeat scroll 0 0 transparent;
     background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#a5c956), color-stop(100%,#cdeb8e));
    }
		</style>
        
</head>



<body>


<div class="content">
	
    <div class="row">
    	<div align="center" style="margin-top:5%;">
		<div class="col-md-4">
        	<img src="imagenes/Administration.png" width="300"/>
            <h3><a href="administracion/" class="boton3d">Adminsitración</a></h3>
        </div>
        
        <div class="col-md-4">
        	<img src="imagenes/torneo.png" width="300"/>
            <h3><a href="dashboard/" class="boton3dt">Torneo</a></h3>
        </div>
        
        <div class="col-md-4">
        	<img src="imagenes/reportes.png" width="300"/>
            <h3><a href="reportes/" class="boton3dr">Reportes</a></h3>
        </div>  
        </div>  
    </div>



</div><!-- fin del content -->



</body>

</html>