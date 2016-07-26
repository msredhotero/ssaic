<?php

session_start();

if (!isset($_SESSION['usua_predio']))
{
	header('Location: ../../error.php');
} else {


include ('../../includes/funcionesUsuarios.php');
include ('../../includes/funciones.php');
include ('../../includes/funcionesHTML.php');
include ('../../includes/funcionesJugadores.php');
include ('../../includes/funcionesEquipos.php');
include ('../../includes/funcionesGrupos.php');
include ('../../includes/funcionesZonasEquipos.php');
include ('../../includes/funcionesDATOS.php');

$serviciosUsuario 	= new ServiciosUsuarios();
$serviciosHTML 		= new ServiciosHTML();
$serviciosFunciones = new Servicios();
$serviciosJugadores = new ServiciosJ();
$serviciosEquipos	= new ServiciosE();
$serviciosGrupos	= new ServiciosG();
$serviciosZonasEquipos	= new ServiciosZonasEquipos();
$serviciosDatos		= new ServiciosDatos();

$fecha = date('Y-m-d');

//$resProductos = $serviciosProductos->traerProductosLimite(6);
$resMenu = $serviciosHTML->menu($_SESSION['nombre_predio'],"Fixture",$_SESSION['refroll_predio'],utf8_encode($_SESSION['torneo_predio']),0,1,0);





/////////////////////// Opciones para la creacion del formulario  /////////////////////
$tabla 			= "dbfixture";

$lblCambio	 	= array("reftorneoge_a","resultado_a","reftorneoge_b","resultado_b","fechajuego","refFecha","cancha");
$lblreemplazo	= array("Zona-Equipo 1","Resultado 1","Zona-Equipo 2","Resultado 2","Fecha Juego","Fecha","Cancha");

$resZonasEquipos 	= $serviciosZonasEquipos->TraerEquiposZonas();

$cadRef = '';
while ($rowTT = mysql_fetch_array($resZonasEquipos)) {
	$cadRef = $cadRef.'<option value="'.$rowTT[0].'">'.$rowTT[1].' - '.$rowTT[2].'</option>';
	
}


$resFechas 	= $serviciosFunciones->TraerFecha();

$cadRef2 = '';
while ($rowZ = mysql_fetch_array($resFechas)) {
	$cadRef2 = $cadRef2.'<option value="'.$rowZ[0].'">'.$rowZ[1].'</option>';
	
}

$resCanchas 	= $serviciosFunciones->TraerCanchas();

$cadRef3 = '';
while ($rowC = mysql_fetch_array($resCanchas)) {
	$cadRef3 = $cadRef3.'<option value="'.$rowC[0].'">'.$rowC[1].'</option>';
	
}


$resHorarios 	= $serviciosFunciones->TraerHorarios($_SESSION['torneo_predio']);

$cadRef4 = '';
while ($rowH = mysql_fetch_array($resHorarios)) {
	$cadRef4 = $cadRef4.'<option value="'.$rowH[0].'">'.$rowH[1].'</option>';
	
}


$refdescripcion = array(0 => $cadRef,1=>$cadRef,2=>$cadRef2,3=>$cadRef3,4=>$cadRef4);
$refCampo	 	= array("reftorneoge_a","reftorneoge_b","refFecha","cancha","Hora"); 
//////////////////////////////////////////////  FIN de los opciones //////////////////////////




/////////////////////// Opciones para la creacion del view  /////////////////////
$cabeceras 		= "	<th>Equipo 1</th>
				<th>Resultado 1</th>
				<th>Resultado 2</th>
				<th>Equipo 2</th>
				<th>Zona</th>
				<th>Fecha Juego</th>
				<th>Fecha</th>
				<th>Hora</th>
				<th>Chequeado</th>";

//////////////////////////////////////////////  FIN de los opciones //////////////////////////




$formulario 	= $serviciosFunciones->camposTabla("insertarFixture",$tabla,$lblCambio,$lblreemplazo,$refdescripcion,$refCampo);
//die($serviciosZonasEquipos->TraerTodoFixture());
$lstCargados 	= $serviciosFunciones->camposTablaView($cabeceras,$serviciosZonasEquipos->TraerTodoFixture(),9);

$resZonasTorneos = $serviciosDatos->traerZonasPorTorneo($_SESSION['idtorneo_predio']);


?>

<!DOCTYPE HTML>
<html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">



<title>Gesti&oacute;n: AIF</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">


<link href="../../css/estiloDash.css" rel="stylesheet" type="text/css">
    

    
    <script type="text/javascript" src="../../js/jquery-1.8.3.min.js"></script>
    <link rel="stylesheet" href="../../css/jquery-ui.css">

    <script src="../../js/jquery-ui.js"></script>
    
	<!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css"/>
	<link href='http://fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <!-- Latest compiled and minified JavaScript -->
    <script src="../../bootstrap/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="../../css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="../../css/bootstrap-timepicker.css">
    <script src="../../js/bootstrap-timepicker.min.js"></script>
	<style type="text/css">
		
  
		
	</style>
    
   
   <link href="../../css/perfect-scrollbar.css" rel="stylesheet">
      <!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>-->
      <script src="../../js/jquery.mousewheel.js"></script>
      <script src="../../js/perfect-scrollbar.js"></script>
      <script>
      jQuery(document).ready(function ($) {
        "use strict";
        $('#navigation').perfectScrollbar();
      });
    </script>
</head>

<body>

 
<?php echo $resMenu; ?>

<div id="content">

<h3>Fixture</h3>

    <div class="boxInfoLargo tile-stats tile-white stat-tile">
        <div id="headBoxInfo">
        	<p style="color: #fff; font-size:18px; height:16px;">Carga del Fixture</p>
        	
        </div>
    	<div class="cuerpoBox">
    		<form class="form-inline formulario" role="form">
            <div class="row" style="margin-left:25px; margin-right:25px;">
    		<?php echo $formulario; ?>
            </div>
            
            <div class="row" style="margin-left:25px; margin-right:25px;">
                <div class="alert"> </div>
                <div id="load"> </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                <ul class="list-inline" style="margin-top:15px;">
                    <li>
                        <button type="button" class="btn btn-primary" id="cargar" style="margin-left:0px;">Guardar</button>
                    </li>
                    <li>
                        <button type="button" class="btn btn-success" id="chequearF" style="margin-left:0px;">Chequear Fixture</button>
                    </li>
                    <li>
                        <button type="button" class="btn btn-success" id="conductaF" style="margin-left:0px;">Cargar Conducta al Fixture</button>
                    </li>
                </ul>
                </div>
            </div>
            
            <div class="row" align="center">
            	<ul class="list-inline">
                	<li>
                    	Seleccione una zona para cargar datos de los jugadores y los equipos
                    </li>
                </ul>
            </div>
            <div class="row" align="center">
                <ul class="list-inline">
                	<?php while ($row = mysql_fetch_array($resZonasTorneos)) { ?>
                	<li>
                    	<a href="generarfixture.php?idtorneo=<?php echo $row[2]; ?>&idzona=<?php echo $row[0]; ?>"><button type="button" class="btn btn-primary" style="margin-left:0px;"><?php echo $row[1]; ?> Generar Fixture</button></a>
                    </li>
					<?php } ?>
                    <li>
                        <button type="button" class="btn btn-primary" id="fixtureM" style="margin-left:0px;">Fixture Manual</button>
                    </li>
                </ul>
            </div>
            <hr>
            <div class="row" align="center">
            	<ul class="list-inline">
                	<li>
                    	Seleccione una fecha y un nuevo dia de juego
                    </li>
                </ul>
            </div>
            
            <div class="row" align="center">
                <ul class="list-inline">

                    
                    <div class="form-group col-md-4">
                        <label for="nuevafecha" class="control-label" style="text-align:left">Nueva Fecha</label>
                        <div class="input-group col-md-6">
                            <input class="form-control" type="text" name="nuevafecha" id="nuevafecha" value="Date"/>
                        </div>
                        
                    </div>
                    
                    <div class="form-group col-md-4">
                        <label class="control-label" style="text-align:left" for="reffecha">Fecha a asignar</label>
                        <div class="input-group col-md-12">
                            <select id="reffechan" class="form-control" name="reffechan">
                                <option value="23">Fecha 1</option>
                                <option value="24">Fecha 2</option>
                                <option value="25">Fecha 3</option>
                                <option value="26">Fecha 4</option>
                                <option value="27">Fecha 5</option>
                                <option value="28">Fecha 6</option>
                                <option value="29">Fecha 7</option>
                                <option value="30">Fecha 8</option>
                                <option value="31">Fecha 9</option>
                                <option value="32">Fecha 10</option>
                                <option value="33">Fecha 11</option>
                                <option value="34">Fecha 12</option>
                                <option value="35">Fecha 13</option>
                                <option value="36">Fecha 14</option>
                                <option value="37">Fecha 15</option>
                                <option value="38">Fecha 16</option>
                                <option value="39">Fecha 17</option>
                                <option value="40">Fecha 18</option>
                                <option value="41">Fecha 19</option>
                                <option value="42">Fecha 20</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group col-md-4">
                        <label class="control-label" style="text-align:left" for="reffecha">Accion</label>
                        <div class="input-group col-md-12">
                        	<div align="center">
                            <button style="margin-left:0px;" id="modificarnuevafecha" class="btn btn-warning" type="button">Modificar</button>
                            </div>
                        </div>
                    </div>

                </ul>
            </div>
            </form>
    	</div>
    </div>

    <div class="boxInfoLargo tile-stats tile-white stat-tile">
        <div id="headBoxInfo">
        	<p style="color: #fff; font-size:18px; height:16px;">Fixture Cargados</p>
        	
        </div>
    	<div class="cuerpoBox">
        	<?php echo $lstCargados; ?>
    	</div>
    </div>
    
   
</div>


</div>
<div id="dialog2" title="Eliminar Fixture">
    	<p>
        	<span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
            ¿Esta seguro que desea eliminar el fixture?.<span id="proveedorEli"></span>
        </p>
        <p><strong>Importante: </strong>Si elimina el fixture se perderan todos los datos de este</p>
        <input type="hidden" value="" id="idEliminar" name="idEliminar">
</div>

<script src="../../js/bootstrap-datetimepicker.min.js"></script>
<script src="../../js/bootstrap-datetimepicker.es.js"></script>
<script type="text/javascript" src="../../js/jquery.dataTables.min.js"></script>
<script src="../../bootstrap/js/dataTables.bootstrap.js"></script>


<script type="text/javascript">
$(document).ready(function(){
	
	$('#example').dataTable({
		"order": [[ 7, "desc" ]],
		"language": {
			"emptyTable":     "No hay datos cargados",
			"info":           "Mostrar _START_ hasta _END_ del total de _TOTAL_ filas",
			"infoEmpty":      "Mostrar 0 hasta 0 del total de 0 filas",
			"infoFiltered":   "(filtrados del total de _MAX_ filas)",
			"infoPostFix":    "",
			"thousands":      ",",
			"lengthMenu":     "Mostrar _MENU_ filas",
			"loadingRecords": "Cargando...",
			"processing":     "Procesando...",
			"search":         "Buscar:",
			"zeroRecords":    "No se encontraron resultados",
			"paginate": {
				"first":      "Primero",
				"last":       "Ultimo",
				"next":       "Siguiente",
				"previous":   "Anterior"
			},
			"aria": {
				"sortAscending":  ": activate to sort column ascending",
				"sortDescending": ": activate to sort column descending"
			}
		  }
	} );
	
	
	$('#timepicker2').timepicker({
		minuteStep: 15,
		showSeconds: false,
		showMeridian: false,
		defaultTime: false
		});
	 <?php 
		echo $serviciosHTML->validacion($tabla);
	
	?>
	
	$('#fixtureM').click( function() {
		url = "fixturemanual.php";
		$(location).attr('href',url);
	});
	
	$('#chequearF').click( function() {
		url = "chequear.php";
		$(location).attr('href',url);
	});
	
	$('#generar').click( function() {
		url = "generarfixture.php";
		$(location).attr('href',url);
	});
	
	$('#conductaF').click( function() {
		url = "conductafixture.php";
		$(location).attr('href',url);
	});
	
	$('.varborrar').click(function(event){
		  usersid =  $(this).attr("id");
		  if (!isNaN(usersid)) {
			$("#idEliminar").val(usersid);
			$("#dialog2").dialog("open");

			
			//url = "../clienteseleccionado/index.php?idcliente=" + usersid;
			//$(location).attr('href',url);
		  } else {
			alert("Error, vuelva a realizar la acción.");	
		  }
	});//fin del boton eliminar
	
	$('#modificarnuevafecha').click(function(event){
		  $.ajax({
				data:  {reffecha: $('#reffechan').val(), 
						fechajuego: $( "#nuevafecha" ).datepicker().val(),
						idtipotorneo: <?php echo $_SESSION['idtorneo_predio']; ?>,
						accion: 'modificarnuevafecha'},
				url:   '../../ajax/ajax.php',
				type:  'post',
				beforeSend: function () {
						
				},
				success:  function (response) {
						url = "index.php";
						$(location).attr('href',url);
						//$(".alert").html('<strong>Error!</strong> '+response);
						
				}
		});
	});//fin del boton eliminar
	
	
	$('.estadistica').click(function(event){
		  usersid =  $(this).attr("id");
		  if (!isNaN(usersid)) {
			url = "../estadisticas/estadisticas.php?id="+usersid;
			$(location).attr('href',url);

			
			//url = "../clienteseleccionado/index.php?idcliente=" + usersid;
			//$(location).attr('href',url);
		  } else {
			alert("Error, vuelva a realizar la acción.");	
		  }
	});//fin del boton estadisticas
	

	$("#example").on("click",'.varmodificar', function(){
		  usersid =  $(this).attr("id");
		  if (!isNaN(usersid)) {
			
			url = "modificar.php?id=" + usersid;
			$(location).attr('href',url);
		  } else {
			alert("Error, vuelva a realizar la acción.");	
		  }
	});//fin del boton modificar

	 $( "#dialog2" ).dialog({
		 	
			    autoOpen: false,
			 	resizable: false,
				width:600,
				height:240,
				modal: true,
				buttons: {
				    "Eliminar": function() {
	
						$.ajax({
									data:  {id: $('#idEliminar').val(), accion: 'eliminarFixture'},
									url:   '../../ajax/ajax.php',
									type:  'post',
									beforeSend: function () {
											
									},
									success:  function (response) {
											url = "index.php";
											$(location).attr('href',url);
											
									}
							});
						$( this ).dialog( "close" );
						$( this ).dialog( "close" );
							$('html, body').animate({
	           					scrollTop: '1000px'
	       					},
	       					1500);
				    },
				    Cancelar: function() {
						$( this ).dialog( "close" );
				    }
				}
		 
		 
	 		}); //fin del dialogo para eliminar
	
	
	//al enviar el formulario
    $('#cargar').click(function(){
		
		if (validador() == "")
        {
			//información del formulario
			var formData = new FormData($(".formulario")[0]);
			var message = "";
			//hacemos la petición ajax  
			$.ajax({
				url: '../../ajax/ajax.php',  
				type: 'POST',
				// Form data
				//datos del formulario
				data: formData,
				//necesario para subir archivos via ajax
				cache: false,
				contentType: false,
				processData: false,
				//mientras enviamos el archivo
				beforeSend: function(){
					$("#load").html('<img src="../../imagenes/load13.gif" width="50" height="50" />');       
				},
				//una vez finalizado correctamente
				success: function(data){

					if (data == '') {
                                            $(".alert").removeClass("alert-danger");
											$(".alert").removeClass("alert-info");
                                            $(".alert").addClass("alert-success");
                                            $(".alert").html('<strong>Ok!</strong> Se cargo exitosamente el <strong>Fixture</strong>. ');
											$(".alert").delay(3000).queue(function(){
												/*aca lo que quiero hacer 
												  después de los 2 segundos de retraso*/
												$(this).dequeue(); //continúo con el siguiente ítem en la cola
												
											});
											$("#load").html('');
											//url = "index.php";
											var a = $('#reftorneoge_a option:selected').html();
											var b = $('#reftorneoge_b option:selected').html();
											/*alert(b);*/
											a = a.split(' - ');
											b = b.split(' - ');
											
											$('#resultados').prepend('<tr><td>' + a[1] + '</td><td></td><td></td><td>' + 
																		b[1] + '</td><td>' + 
																		a[0] + '</td><td>' + 
																		$('#fechajuego').html() + '</td><td>' + 
																		$('#reffecha option:selected').html() + '</td><td>' + 
																		$('#hora option:selected').html() + '</td><td style="color:#f00;">Nuevo</td></tr>').fadeIn(300);
											
											//$(location).attr('href',url);
                                            
											
                                        } else {
                                        	$(".alert").removeClass("alert-danger");
                                            $(".alert").addClass("alert-danger");
                                            $(".alert").html('<strong>Error!</strong> '+data);
                                            $("#load").html('');
                                        }
				},
				//si ha ocurrido un error
				error: function(){
					$(".alert").html('<strong>Error!</strong> Actualice la pagina');
                    $("#load").html('');
				}
			});
		}
    });
	

});
</script>
<script type="text/javascript">
$('.form_date').datetimepicker({
	language:  'es',
	weekStart: 1,
	todayBtn:  1,
	autoclose: 1,
	todayHighlight: 1,
	startView: 2,
	minView: 2,
	forceParse: 0,
	format: 'dd/mm/yyyy'
});
</script>
<script>
  $(function() {
	  $.datepicker.regional['es'] = {
 closeText: 'Cerrar',
 prevText: '<Ant',
 nextText: 'Sig>',
 currentText: 'Hoy',
 monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
 monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
 dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
 dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
 dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
 weekHeader: 'Sm',
 dateFormat: 'dd/mm/yy',
 firstDay: 1,
 isRTL: false,
 showMonthAfterYear: false,
 yearSuffix: ''
 };
 $.datepicker.setDefaults($.datepicker.regional['es']);
 
    $( "#nuevafecha" ).datepicker();

    $( "#nuevafecha" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
  });
  </script>

<?php } ?>
</body>
</html>
