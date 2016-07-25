<?php

/**
 * @author www.intercambiosvirtuales.org
 * @copyright 2013
 */

date_default_timezone_set('America/Buenos_Aires');

class ServiciosAdministrativos {
	
	
	///**********  PARA SUBIR ARCHIVOS  ***********************//////////////////////////
	function borrarDirecctorio($dir) {
		array_map('unlink', glob($dir."/*.*"));	
	
	}
	
	function borrarArchivo($id,$archivo) {
		$sql	=	"delete from dbfotos where idfoto =".$id;
		
		$res =  unlink("./../archivos/".$archivo);
		if ($res)
		{
			$this->query($sql,0);	
		}
		return $res;
	}
	
	
	function existeArchivo($refinmueble,$nombre,$type) {
		$sql		=	"select * from dbfotos where refnoticia =".$refinmueble." and imagen = '".$nombre."' and type = '".$type."'";
		$resultado  =   $this->query($sql,0);
			   
			   if(mysql_num_rows($resultado)>0){
	
				   return mysql_result($resultado,0,0);
	
			   }
	
			   return 0;	
	}


	function subirArchivo($file,$carpeta,$idInmueble) {
		$dir_destino = '../archivos/'.$carpeta.'/'.$idInmueble.'/';
		$imagen_subida = $dir_destino . utf8_decode(str_replace(' ','',basename($_FILES[$file]['name'])));
		
		$noentrar = '../imagenes/index.php';
		$nuevo_noentrar = '../archivos/'.$carpeta.'/'.$idInmueble.'/'.'index.php';
		
		if (!file_exists($dir_destino)) {
			mkdir($dir_destino, 0777);
		}
		
		 
		if(!is_writable($dir_destino)){
			
			echo "no tiene permisos";
			
		}	else	{
			if ($_FILES[$file]['tmp_name'] != '') {
				if(is_uploaded_file($_FILES[$file]['tmp_name'])){
					/*echo "Archivo ". $_FILES['foto']['name'] ." subido con éxtio.\n";
					echo "Mostrar contenido\n";
					echo $imagen_subida;*/
					if (move_uploaded_file($_FILES[$file]['tmp_name'], $imagen_subida)) {
						
						$archivo = utf8_decode($_FILES[$file]["name"]);
						$tipoarchivo = $_FILES[$file]["type"];
						
						if ($this->existeArchivo($idInmueble,$archivo,$tipoarchivo) == 0) {
							$sql	=	"insert into dbfotos(idfoto,refnoticia,imagen,type) values ('',".$idInmueble.",'".str_replace(' ','',$archivo)."','".$tipoarchivo."')";
							$this->query($sql,1);
						}
						echo "";
						
						copy($noentrar, $nuevo_noentrar);
		
					} else {
						echo "Posible ataque de carga de archivos!\n";
					}
				}else{
					echo "Posible ataque del archivo subido: ";
					echo "nombre del archivo '". $_FILES[$file]['tmp_name'] . "'.";
				}
			}
		}	
	}


	
	function TraerFotosNoticias($idNoticia) {
		$sql    =   "select 'galeria',i.idnoticia,f.imagen,f.idfoto
							from dbnoticias i
							
							inner
							join dbfotos f
							on	i.idnoticia = f.refnoticia

							where i.idnoticia = ".$idNoticia;
		$result =   $this->query($sql, 0);
		return $result;
	}
	
	
	function eliminarFoto($id)
	{
		
		$sql		=	"select concat('galeria','/',i.idnoticia,'/',f.imagen) as archivo
							from dbnoticias i
							
							inner
							join dbfotos f
							on	i.idnoticia = f.refnoticia

							where f.idfoto =".$id;
		$resImg		=	$this->query($sql,0);
		
		$res 		=	$this->borrarArchivo($id,mysql_result($resImg,0,0));
		
		if ($res == false) {
			return 'Error al eliminar datos';
		} else {
			return '';
		}
	}

/* fin archivos */
/*
CREATE TABLE `dbfotos` (
  `idfoto` int(11) NOT NULL AUTO_INCREMENT,
  `refnoticia` int(11) NOT NULL,
  `imagen` varchar(500) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  `principal` bit(1) DEFAULT NULL,
  PRIMARY KEY (`idfoto`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


*/

	/* PARA Countries */

function insertarCountries($nombre,$cuit,$coordenadas,$fechaalta,$fechabaja,$activo,$refposiciontributaria,$refcontacto) { 
$sql = "insert into dbcountries(idcountries,nombre,cuit,coordenadas,fechaalta,fechabaja,activo,refposiciontributaria,refcontacto) 
values ('','".utf8_decode($nombre)."','".utf8_decode($cuit)."',geomfromtext('point(".$coordenadas.")'),'".utf8_decode($fechaalta)."','".utf8_decode($fechabaja)."',".$activo.",".$refposiciontributaria.",".$refcontacto.")"; 
$res = $this->query($sql,1); 
return $res; 
} 


function modificarCountries($id,$nombre,$cuit,$coordenadas,$fechaalta,$fechabaja,$activo,$refposiciontributaria,$refcontacto) { 
$sql = "update dbcountries 
set 
nombre = '".utf8_decode($nombre)."',cuit = '".utf8_decode($cuit)."',coordenadas = geomfromtext('point(".$coordenadas.")'),fechaalta = '".utf8_decode($fechaalta)."',fechabaja = '".utf8_decode($fechabaja)."',activo = ".$activo.",refposiciontributaria = ".$refposiciontributaria.",refcontacto = ".$refcontacto." 
where idcountries =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function eliminarCountries($id) { 
$sql = "delete from dbcountries where idcountries =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerCountries() { 
$sql = "select idcountries,nombre,cuit,asText(coordenadas),fechaalta,fechabaja,activo,refposiciontributaria,refcontacto from dbcountries order by 1"; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerCountriesPorId($id) { 
$sql = "select idcountries,nombre,cuit,asText(coordenadas),fechaalta,fechabaja,activo,refposiciontributaria,refcontacto from dbcountries where idcountries =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 

/* Fin */


/* PARA PosicionTributaria */

function insertarPosicionTributaria($posiciontributaria,$activo) {
$sql = "insert into tbposiciontributaria(idposiciontributaria,posiciontributaria,activo)
values ('','".utf8_decode($posiciontributaria)."',".$activo.")";
$res = $this->query($sql,1);
return $res;
}


function modificarPosicionTributaria($id,$posiciontributaria,$activo) {
$sql = "update tbposiciontributaria
set
posiciontributaria = '".utf8_decode($posiciontributaria)."',activo = ".$activo."
where idposiciontributaria =".$id;
$res = $this->query($sql,0);
return $res;
}


function eliminarPosicionTributaria($id) {
$sql = "delete from tbposiciontributaria where idposiciontributaria =".$id;
$res = $this->query($sql,0);
return $res;
}


function traerPosicionTributaria() {
$sql = "select idposiciontributaria,posiciontributaria,activo from tbposiciontributaria order by 1";
$res = $this->query($sql,0);
return $res;
}


function traerPosicionTributariaPorId($id) {
$sql = "select idposiciontributaria,posiciontributaria,activo from tbposiciontributaria where idposiciontributaria =".$id;
$res = $this->query($sql,0);
return $res;
}

/* Fin */

/* PARA TipoContacto */

function insertarTipoContacto($tipocontacto,$activo) {
$sql = "insert into tbtipocontacto(idtipocontacto,tipocontacto,activo)
values ('','".utf8_decode($tipocontacto)."',".$activo.")";
$res = $this->query($sql,1);
return $res;
}


function modificarTipoContacto($id,$tipocontacto,$activo) {
$sql = "update tbtipocontacto
set
tipocontacto = '".utf8_decode($tipocontacto)."',activo = ".$activo."
where idtipocontacto =".$id;
$res = $this->query($sql,0);
return $res;
}


function eliminarTipoContacto($id) {
$sql = "delete from tbtipocontacto where idtipocontacto =".$id;
$res = $this->query($sql,0);
return $res;
}


function traerTipoContacto() {
$sql = "select idtipocontacto,tipocontacto,activo from tbtipocontacto order by 1";
$res = $this->query($sql,0);
return $res;
}


function traerTipoContactoPorId($id) {
$sql = "select idtipocontacto,tipocontacto,activo from tbtipocontacto where idtipocontacto =".$id;
$res = $this->query($sql,0);
return $res;
}

/* Fin */


/* PARA Contactos */

function insertarContactos($reftipocontacto,$nombre,$direccion,$localidad,$cp,$telefono,$celular,$fax,$email,$observaciones,$publico) {
$sql = "insert into dbcontactos(idcontacto,reftipocontacto,nombre,direccion,localidad,cp,telefono,celular,fax,email,observaciones,publico)
values ('',".$reftipocontacto.",'".utf8_decode($nombre)."','".utf8_decode($direccion)."','".utf8_decode($localidad)."','".utf8_decode($cp)."','".utf8_decode($telefono)."','".utf8_decode($celular)."','".utf8_decode($fax)."','".utf8_decode($email)."','".utf8_decode($observaciones)."',".$publico.")";
$res = $this->query($sql,1);
return $res;
}


function modificarContactos($id,$reftipocontacto,$nombre,$direccion,$localidad,$cp,$telefono,$celular,$fax,$email,$observaciones,$publico) {
$sql = "update dbcontactos
set
reftipocontacto = ".$reftipocontacto.",nombre = '".utf8_decode($nombre)."',direccion = '".utf8_decode($direccion)."',localidad = '".utf8_decode($localidad)."',cp = '".utf8_decode($cp)."',telefono = '".utf8_decode($telefono)."',celular = '".utf8_decode($celular)."',fax = '".utf8_decode($fax)."',email = '".utf8_decode($email)."',observaciones = '".utf8_decode($observaciones)."',publico = ".$publico."
where idcontacto =".$id;
$res = $this->query($sql,0);
return $res;
}


function eliminarContactos($id) {
$sql = "delete from dbcontactos where idcontacto =".$id;
$res = $this->query($sql,0);
return $res;
}


function traerContactos() {
$sql = "select idcontacto,tc.tipocontacto,nombre,direccion,localidad,cp,telefono,celular,fax,email,observaciones,(case when publico=1 then 'Si' else 'No' end) as publico 
		from dbcontactos c 
		inner join tbtipocontacto tc on tc.idtipocontacto = c.reftipocontacto
		order by 1";
$res = $this->query($sql,0);
return $res;
}


function traerContactosPorId($id) {
$sql = "select idcontacto,reftipocontacto,nombre,direccion,localidad,cp,telefono,celular,fax,email,observaciones,publico from dbcontactos where idcontacto =".$id;
$res = $this->query($sql,0);
return $res;
}

/* Fin */
	
	function query($sql,$accion) {
		
		
		require_once 'appconfig.php';

		$appconfig	= new appconfig();
		$datos		= $appconfig->conexion();
		$hostname	= $datos['hostname'];
		$database	= $datos['database'];
		$username	= $datos['username'];
		$password	= $datos['password'];
		
		$conex = mysql_connect($hostname,$username,$password) or die ("no se puede conectar".mysql_error());
		
		mysql_select_db($database);
		
		$result = mysql_query($sql,$conex);
		if ($accion && $result) {
			$result = mysql_insert_id();
		}
		mysql_close($conex);
		return $result;
		
	}
	}

?>