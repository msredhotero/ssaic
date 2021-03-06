<?php

/**
 * @author www.intercambiosvirtuales.org
 * @copyright 2013
 */
date_default_timezone_set('America/Buenos_Aires');

class ServiciosJ {
	
	function buscarJugadores($tipobusqueda,$busqueda) {
		switch ($tipobusqueda) {
			case '1':
				$sql = "select j.idjugador,
								e.nombre,
								j.apyn,
								j.dni,
								(case when j.invitado = 1 then 'Si' else 'No' end) as invitado,
								(case when j.expulsado = 1 then 'Si' else 'No' end) as expulsado 
								
						from dbjugadores j
		        inner join dbequipos e
		        on j.idequipo = e.idequipo
				where e.nombre like '%".$busqueda."%'
				order by e.nombre,j.apyn";
				break;
			case '2':
				$sql = "select j.idjugador,
								e.nombre,
								j.apyn,
								j.dni,
								(case when j.invitado = 1 then 'Si' else 'No' end) as invitado,
								(case when j.expulsado = 1 then 'Si' else 'No' end) as expulsado 
								
						from dbjugadores j
		        inner join dbequipos e
		        on j.idequipo = e.idequipo
				where j.apyn like '%".$busqueda."%'
				order by e.nombre,j.apyn";
				break;
			case '3':
				$sql = "select j.idjugador,
								e.nombre,
								j.apyn,
								j.dni,
								(case when j.invitado = 1 then 'Si' else 'No' end) as invitado,
								(case when j.expulsado = 1 then 'Si' else 'No' end) as expulsado 
								
						from dbjugadores j
		        inner join dbequipos e
		        on j.idequipo = e.idequipo
				where j.dni like '%".$busqueda."%'
				order by e.nombre,j.apyn";
				break;
			case '4':
				$sql = "select j.idjugador,
								e.nombre,
								j.apyn,
								j.dni,
								(case when j.invitado = 1 then 'Si' else 'No' end) as invitado,
								(case when j.expulsado = 1 then 'Si' else 'No' end) as expulsado 
								
						from dbjugadores j
		        inner join dbequipos e
		        on j.idequipo = e.idequipo
				where j.invitado = ".$busqueda."
				order by e.nombre,j.apyn";
				break;
			case '5':
				$sql = "select j.idjugador,
								e.nombre,
								j.apyn,
								j.dni,
								(case when j.invitado = 1 then 'Si' else 'No' end) as invitado,
								(case when j.expulsado = 1 then 'Si' else 'No' end) as expulsado 
								
						from dbjugadores j
		        inner join dbequipos e
		        on j.idequipo = e.idequipo
				where j.expulsado = ".$busqueda."
				order by e.nombre,j.apyn";
				break;
		
		}
		return $this->query($sql,0);
	}
	
	function TraerJugadores() {
		$sql = "select idjugador,apyn,dni from dbjugadores order by apyn";
		return $this->query($sql,0);
	}
	
	function TraerJugadoresEquipos() {
		$sql = "select j.idjugador,j.apyn,j.dni,e.nombre,(case when j.invitado = 1 then 'Si' else 'No' end) as invitado from dbjugadores j
		        inner join dbequipos e
		        on j.idequipo = e.idequipo
				order by e.nombre,j.apyn";
		return $this->query($sql,0);
	}
	
	function TraerJugadoresPorId($id) {
		$sql = "select j.idjugador,j.apyn,j.dni,e.nombre,e.idequipo,j.invitado,j.expulsado from dbjugadores j
		        inner join dbequipos e
		        on j.idequipo = e.idequipo
				where idjugador = ".$id;
		return $this->query($sql,0);
	}
	
    function TraerJugadoresPorEquipo($idequipo) {
		$sql = "
					select
					*
					from
					(
					select j.idjugador,
						j.apyn,
						j.dni ,
						j.invitado,
						(case when s.refjugador is null then '0' else '1' end) as suspendido,
						j.expulsado
					from dbjugadores j 
					left join (select distinct refjugador from dbsuspendidosfechas) s on j.idjugador = s.refjugador
					where j.idequipo = ".$idequipo."
					
					
					) as f
					
					order by f.apyn";
		return $this->query($sql,0);
	}
	
	function TraerJugadoresGolesPorEquipo($idEquipo) {
		$sql = "select 
					f.idjugador, f.apyn, f.dni, f.invitado, f.expulsado, f.goles, f.amarillas, f.rojas
				from
					(select 
						j.idjugador,
						j.apyn,
						j.dni,
						(case
							when j.invitado = '0' then 'No'
							else 'Si'
						end) as invitado,
						j.invitado as invitado2,
						(case
							when j.expulsado = '0' then 'No'
							else 'Si'
						end) as expulsado,
						COALESCE(sum(g.goles), 0) as goles,
						COALESCE(aa.amarillas, 0) as amarillas,
						COALESCE(ss.rojas, 0) as rojas
					from
						dbjugadores j
						left join
						tbgoleadores g on j.idjugador = g.refjugador
						left join
						(select ta.refjugador, count(ta.amarillas) as amarillas
							from tbamonestados ta 
							where ta.refequipo = ".$idEquipo." and ta.amarillas <> 2
							group by ta.refjugador) aa on aa.refjugador = j.idjugador
						left join
						(select ts.refjugador, count(ts.idsuspendido) as rojas
							from tbsuspendidos ts 
							where ts.refequipo = ".$idEquipo."
							group by ts.refjugador) ss on ss.refjugador = j.idjugador
					where
						j.idequipo = ".$idEquipo." and j.expulsado = '0' and j.invitado = '0'
					group by j.idjugador, j.apyn, j.dni, j.invitado, j.expulsado,aa.amarillas,ss.rojas) as f
				order by f.goles desc,f.apyn";	
		return $this->query($sql,0);
	}
	
	
	function TraerJugadoresGolesPorEquipoFiltro($idEquipo, $invitado, $expulsado) {
		$sql = "select 
					f.idjugador, f.apyn, f.dni, f.invitado, f.expulsado, f.goles, f.amarillas, f.rojas
				from
					(select 
						j.idjugador,
						j.apyn,
						j.dni,
						(case
							when j.invitado = '0' then 'No'
							else 'Si'
						end) as invitado,
						j.invitado as invitado2,
						(case
							when j.expulsado = '0' then 'No'
							else 'Si'
						end) as expulsado,
						COALESCE(sum(g.goles), 0) as goles,
						COALESCE(aa.amarillas, 0) as amarillas,
						COALESCE(ss.rojas, 0) as rojas
					from
						dbjugadores j
						left join
						tbgoleadores g on j.idjugador = g.refjugador
						left join
						(select ta.refjugador, count(ta.amarillas) as amarillas
							from tbamonestados ta 
							where ta.refequipo = ".$idEquipo." and ta.amarillas <> 2
							group by ta.refjugador) aa on aa.refjugador = j.idjugador
						left join
						(select ts.refjugador, count(ts.idsuspendido) as rojas
							from tbsuspendidos ts 
							where ts.refequipo = ".$idEquipo."
							group by ts.refjugador) ss on ss.refjugador = j.idjugador
					where
						j.idequipo = ".$idEquipo." and j.expulsado = '".$expulsado."' and j.invitado = '".$invitado."'
					group by j.idjugador, j.apyn, j.dni, j.invitado, j.expulsado,aa.amarillas,ss.rojas) as f
				order by f.goles desc,f.apyn";	
		return $this->query($sql,0);
	}
	
	
	function TraerJugadoresPorEquipoPlanillas($idequipo,$reffecha, $idTorneo) {
		/*$sql = "
					select
					*
					from
					(
					select j.idjugador,
						j.apyn,
						j.dni ,
						j.invitado,
						(case when s.refjugador is null then '0' else '1' end) as suspendido
					from dbjugadores j 
					left join (select distinct refjugador from dbsuspendidosfechas where reffecha = ".$reffecha.") s on j.idjugador = s.refjugador
					where j.idequipo = ".$idequipo." and j.invitado = 0 and j.expulsado = 0
					
					union all
					
					
					select j.idjugador,
						j.apyn,
						j.dni ,
						j.invitado,
						(case when s.refjugador is null then '0' else '1' end) as suspendido
					from dbjugadores j 
					inner join (select distinct refjugador from dbsuspendidosfechas where reffecha = ".$reffecha.") s on j.idjugador = s.refjugador
					where j.idequipo = ".$idequipo." and j.invitado = 1 and j.expulsado = 0
					
					union all
					
					
					select j.idjugador,
						j.apyn,
						j.dni ,
						j.invitado,
						1 as suspendido
					from dbjugadores j 
					where j.idequipo = ".$idequipo." and j.expulsado = 1
					) as f
					
					order by f.apyn";*/
		$sql = "select 
					*
				from
					(select 
						j.idjugador,
							j.apyn,
							j.dni,
							j.invitado,
							(case
								when s.refjugador is null then '0'
								else '1'
							end) as suspendido,
							(case
								when s.refjugador is null then 2
								else 1
							end) as orden
					from
						dbjugadores j
					left join (select distinct
						sf.refjugador
					from
						dbsuspendidosfechas sf
						inner join tbsuspendidos ss on ss.idsuspendido = sf.refsuspendido
						inner join (select distinct
										ff.idfixture
									from
										dbfixture ff
									inner join dbtorneoge tge ON tge.idtorneoge = ff.reftorneoge_a
										or tge.idtorneoge = ff.reftorneoge_b
									inner join dbtorneos t ON tge.reftorneo = t.idtorneo
									inner join tbtipotorneo tp ON t.reftipotorneo = tp.idtipotorneo
									where
										t.activo = 1 and t.reftipotorneo in (".$idTorneo.")) d
						on			d.idfixture = ss.reffixture
					where
						reffecha = ".$reffecha.") s ON j.idjugador = s.refjugador
					where
						j.idequipo = ".$idequipo." and j.invitado = 0
							and j.expulsado = 0 union all select 
						j.idjugador,
							j.apyn,
							j.dni,
							j.invitado,
							(case
								when s.refjugador is null then '0'
								else '1'
							end) as suspendido,
							(case
								when s.refjugador is null then 4
								else 1
							end) as orden
					from
						dbjugadores j
					inner join (select distinct
						sf.refjugador
					from
						dbsuspendidosfechas sf
						inner join tbsuspendidos ss on ss.idsuspendido = sf.refsuspendido
						inner join (select distinct
										ff.idfixture
									from
										dbfixture ff
									inner join dbtorneoge tge ON tge.idtorneoge = ff.reftorneoge_a
										or tge.idtorneoge = ff.reftorneoge_b
									inner join dbtorneos t ON tge.reftorneo = t.idtorneo
									inner join tbtipotorneo tp ON t.reftipotorneo = tp.idtipotorneo
									where
										t.activo = 1 and t.reftipotorneo in (".$idTorneo.")) d
						on			d.idfixture = ss.reffixture
					where
						reffecha = ".$reffecha.") s ON j.idjugador = s.refjugador
					where
						j.idequipo = ".$idequipo." and j.invitado = 1
							and j.expulsado = 0 union all select 
						j.idjugador, j.apyn, j.dni, j.invitado, 1 as suspendido,1 as orden
					from
						dbjugadores j
					where
						j.idequipo = ".$idequipo." and j.expulsado = 1) as f
				order by f.orden,f.apyn";
		return $this->query($sql,0);
	}



	function TraerIdJugadoresEquipos($id) {
		$sql = "select * from dbjugadores j
		        inner join dbequipos e
		        on j.idequipo = e.idequipo
		        where idjugador =".$id; 
				
		return $this->query($sql,0);
	}
	
	function TraerIdJugador($id) {
		$sql = "select * from dbjugadores where idjugador =".$id;
		return $this->query($sql,0);
	}
	
	function existeJugador($dni,$idequipo) {
		$sql3 = "select idequipo from dbjugadores where dni = ".$dni;
		$res5 = $this->query($sql3,0);
		if ($dni != '') {
			if (mysql_num_rows($res5)>0) {
				$sqlZonaEquipo = "select refgrupo,reftorneo from dbtorneoge where refequipo = ".$idequipo;
				$resZE = $this->query($sqlZonaEquipo,0);
				$sqlZonaEquipoActual = "select refgrupo,reftorneo from dbtorneoge where refequipo = ".mysql_result($res5,0,0);
				$resZEA = $this->query($sqlZonaEquipoActual,0);
				
				if ((mysql_result($resZE,0,0) == mysql_result($resZEA,0,0)) && (mysql_result($resZE,0,1) == mysql_result($resZEA,0,1))) {
					return 1;	
				} else {
					return 0;
				}
			}
		}
		return 0;
	}
	
    function insertarJugadores($apyn,$dni,$idequipo,$invitado) {
		$sql = "insert into dbjugadores(idjugador,apyn,dni,idequipo,invitado)
										values ('','".utf8_decode(trim($apyn))."',".trim($dni).",".$idequipo.",".$invitado.")";
		if ($this->existeJugador($dni,$idequipo) == 0) {
			$res = $this->query($sql,1);
		} else {
			$res = "El jugador ya existe o no se puede cargar en la misma zona!";	
		}
		return $res;
	}


	
	function modificarJugadores($apyn,$dni,$idequipo,$id,$invitado,$expulsado) {
		$sql = "update dbjugadores set apyn = '".utf8_decode($apyn)."', dni = ".$dni.", idequipo = ".$idequipo." 
				, invitado = ".$invitado." , expulsado = ".$expulsado." 
				where idjugador =".$id;
		//return $sql;
		$this->query($sql,0);
		return 1;
	}
	
	function eliminarJugadores($id) {
		$sql = "delete from dbjugadores where idjugador =".$id;
		$this->query($sql,0);
		return 1;
	}
	

function insertarGoleadores($refequipo,$reffixture,$goles,$refjugador) {
$sql = "insert into tbgoleadores(idgoleador,refequipo,reffixture,goles,refjugador)
values ('',".$refequipo.",".$reffixture.",".$goles.",".$refjugador.")";
$res = $this->query($sql,1);
return $res;
}


function modificarGoleadores($id,$refequipo,$reffixture,$goles,$refjugador) {
$sql = "update tbgoleadores
set
refequipo = ".$refequipo.",reffixture = ".$reffixture.",goles = ".$goles.",refjugador = ".$refjugador."
where idgoleador =".$id;
$res = $this->query($sql,0);
return $res;
}


function eliminarGoleadores($id) {
$sql = "delete from tbgoleadores where idgoleador =".$id;
$res = $this->query($sql,0);
return $res;
} 

function traerGoleadores() {
	$sql = "select g.idgoleador,j.apyn, j.dni, e.nombre, ff.tipofecha, g.goles 
			from tbgoleadores g 
			inner join dbequipos e on g.refequipo = e.idequipo 
			inner join dbjugadores j on g.refjugador = j.idjugador 
			inner join dbfixture f on f.idfixture = g.reffixture
			inner join tbfechas ff on f.reffecha = ff.idfecha 
			order by e.nombre, f.idfixture desc, j.apyn";
	$res = $this->query($sql,0);
	return $res;
}


function traerGoleadoresPorEquipo($equipo) {
	$sql = "select g.idgoleador,j.apyn, j.dni, e.nombre, ff.tipofecha, g.goles, concat(d.nombre,'-',d.descripciontorneo) as torneo
			from tbgoleadores g 
			inner join dbequipos e on g.refequipo = e.idequipo 
			inner join dbjugadores j on g.refjugador = j.idjugador 
			inner join
				(select fix.idfixture,t.nombre, tp.descripciontorneo, fix.reffecha from dbfixture fix inner join dbtorneoge tge
							on			fix.reftorneoge_a = tge.idtorneoge or fix.reftorneoge_b = tge.idtorneoge
							inner
							join		dbtorneos t
							on			t.idtorneo = tge.reftorneo and t.activo = 1
							inner
							join		tbtipotorneo tp
							on			tp.idtipotorneo = t.reftipotorneo
				group by fix.idfixture,t.nombre, tp.descripciontorneo, fix.reffecha) d ON d.idfixture = g.reffixture
			inner join tbfechas ff on d.reffecha = ff.idfecha 
			where e.nombre like '%".$equipo."%' 
			order by e.nombre, d.idfixture desc, j.apyn";
	$res = $this->query($sql,0);
	return $res;
}


function traerGoleadoresPorApyn($apyn) {
	$sql = "select g.idgoleador,j.apyn, j.dni, e.nombre, ff.tipofecha, g.goles ,concat(d.nombre,'-',d.descripciontorneo) as torneo
			from tbgoleadores g 
			inner join dbequipos e on g.refequipo = e.idequipo 
			inner join dbjugadores j on g.refjugador = j.idjugador 
			inner join
				(select fix.idfixture,t.nombre, tp.descripciontorneo, fix.reffecha from dbfixture fix inner join dbtorneoge tge
							on			fix.reftorneoge_a = tge.idtorneoge or fix.reftorneoge_b = tge.idtorneoge
							inner
							join		dbtorneos t
							on			t.idtorneo = tge.reftorneo and t.activo = 1
							inner
							join		tbtipotorneo tp
							on			tp.idtipotorneo = t.reftipotorneo
				group by fix.idfixture,t.nombre, tp.descripciontorneo, fix.reffecha) d ON d.idfixture = g.reffixture
			inner join tbfechas ff on d.reffecha = ff.idfecha 
			where j.apyn like '%".$apyn."%' 
			order by e.nombre, d.idfixture desc, j.apyn";
	$res = $this->query($sql,0);
	return $res;
}


function traerGoleadoresPorDNI($apyn) {
	$sql = "select g.idgoleador,j.apyn, j.dni, e.nombre, ff.tipofecha, g.goles, concat(d.nombre,'-',d.descripciontorneo) as torneo 
			from tbgoleadores g 
			inner join dbequipos e on g.refequipo = e.idequipo 
			inner join dbjugadores j on g.refjugador = j.idjugador 
			inner join
				(select fix.idfixture,t.nombre, tp.descripciontorneo, fix.reffecha from dbfixture fix inner join dbtorneoge tge
							on			fix.reftorneoge_a = tge.idtorneoge or fix.reftorneoge_b = tge.idtorneoge
							inner
							join		dbtorneos t
							on			t.idtorneo = tge.reftorneo and t.activo = 1
							inner
							join		tbtipotorneo tp
							on			tp.idtipotorneo = t.reftipotorneo
				group by fix.idfixture,t.nombre, tp.descripciontorneo, fix.reffecha) d ON d.idfixture = g.reffixture
			inner join tbfechas ff on d.reffecha = ff.idfecha 
			where j.dni like '%".$apyn."%' 
			order by e.nombre, d.idfixture desc, j.apyn";
	$res = $this->query($sql,0);
	return $res;
}


function traerGoleadoresPorFecha($fecha) {
	$sql = "select g.idgoleador,j.apyn, j.dni, e.nombre, ff.tipofecha, g.goles 
			from tbgoleadores g 
			inner join dbequipos e on g.refequipo = e.idequipo 
			inner join dbjugadores j on g.refjugador = j.idjugador 
			inner join dbfixture f on f.idfixture = g.reffixture
			inner join tbfechas ff on f.reffecha = ff.idfecha 
			where ff.idfecha in (".$fecha.") 
			order by e.nombre, f.idfixture desc, j.apyn";
	$res = $this->query($sql,0);
	return $res;
}


function buscarGoleadores($tipobusqueda,$busqueda) {
		switch ($tipobusqueda) {
			case '1':
				return $this->traerGoleadoresPorEquipo($busqueda);
				break;
			case '2':
				return $this->traerGoleadoresPorApyn($busqueda);
				break;
			case '3':
				return $this->traerGoleadoresPorDNI($busqueda);
				break;
		}

	}

function traerEstadisticas() {
	$sql = "select g.idgoleador,j.apyn, j.dni, e.nombre, ff.tipofecha, g.goles , (case when a.amarillas is null then 0 else a.amarillas end) as amarillas
			from tbgoleadores g 
			inner join dbequipos e on g.refequipo = e.idequipo 
			inner join dbjugadores j on g.refjugador = j.idjugador
			inner join tbamonestados a on a.refequipo = e.idequipo and a.refjugador = j.idjugador
			inner join dbfixture f on f.idfixture = g.reffixture
			inner join tbfechas ff on f.reffecha = ff.idfecha";
	$res = $this->query($sql,0);
	return $res;
}

function traerGoleadoresPorId($id) {
	$sql = "select g.idgoleador, j.idjugador,j.apyn, j.dni,e.idequipo, e.nombre,f.idfixture, ff.tipofecha, g.goles 
			from tbgoleadores g 
			inner join dbequipos e on g.refequipo = e.idequipo 
			inner join dbjugadores j on g.refjugador = j.idjugador 
			inner join dbfixture f on f.idfixture = g.reffixture
			inner join tbfechas ff on f.reffecha = ff.idfecha 
			where g.idgoleador = ".$id;
	$res = $this->query($sql,0);
	return $res;
}



function traerAcumuladosAmarillasPorTorneoZonaJugador($idfecha,$idjugador,$idtipoTorneo) {
		
		$sqlTorneo = "select idtorneo from dbtorneos where reftipotorneo = ".$idtipoTorneo." and activo = 1";
		$refTorneo = mysql_result($this->query($sqlTorneo,0),0,0);
					
		$sql = "select
				t.refequipo, t.nombre, t.apyn, t.dni, (case when t.cantidad > 3 then mod(t.cantidad,3) else t.cantidad end) as cantidad,ultimafecha,fecha,t.reemplzado, t.volvio
				from
				(
				select
					a.refequipo, e.nombre, j.apyn, j.dni, count(a.amarillas) as cantidad,max(fi.reffecha) as ultimafecha, max(ff.tipofecha) as fecha
					, (case when rr.idreemplazo is null then false else true end) as reemplzado
					, (case when rrr.idreemplazo is null then 0 else 1 end) as volvio
					from		tbamonestados a
					inner
					join		dbequipos e
					on			e.idequipo = a.refequipo
					inner
					join		dbjugadores j
					on			j.idjugador = a.refjugador
					/*inner
					join		dbfixture fi
					on			fi.idfixture = a.reffixture*/
					inner 
					join 		(select idfixture,reffecha from dbfixture fix
									inner join dbtorneoge tge ON fix.reftorneoge_a = tge.idtorneoge
									or fix.reftorneoge_b = tge.idtorneoge
									inner join dbtorneos tt ON tt.idtorneo = tge.reftorneo
									and tt.reftipotorneo = ".$idtipoTorneo."
									and tt.activo = 1
									group by idfixture,reffecha) fi
					on			fi.idfixture = a.reffixture
					inner
					join		tbfechas ff
					on			ff.idfecha = fi.reffecha
					
left join dbreemplazo rr on rr.refequiporeemplazado = e.idequipo and rr.reffecha <= ".$idfecha."
left join dbreemplazo rrr on rrr.refequipo = e.idequipo and rrr.reffecha <= ".$idfecha." and rrr.reftorneo = ".$refTorneo."
					
					where	j.idjugador = ".$idjugador."
					and a.amarillas <> 2
					and fi.reffecha <= ".$idfecha."
					group by a.refequipo, e.nombre, j.apyn, j.dni
				) t
					where (cantidad <> 3 and ultimafecha < ".$idfecha.") or (cantidad = 3 and ultimafecha = ".$idfecha.") or (cantidad < 3 and ultimafecha = ".$idfecha.") or (cantidad > 3 and ultimafecha = ".$idfecha.")
					
					order by (case when t.cantidad > 3 then mod(t.cantidad,3) else t.cantidad end) desc,t.nombre, t.apyn";
								
		$res = $this-> query($sql,0);
		if (mysql_num_rows($res)>0) {
			return mysql_result($res,0,'cantidad');
		}
		return 0;
	}
	
	
function traerAcumuladosAmarillasPorTorneoZonaJugador4($idfecha,$idjugador,$idtipoTorneo) {
		
		$sqlTorneo = "select idtorneo from dbtorneos where reftipotorneo = ".$idtipoTorneo." and activo = 1";
		$refTorneo = mysql_result($this->query($sqlTorneo,0),0,0);
		
		if (date('Y') == 2016) {
			$sql = "
				select
					g.refequipo,
					g.nombre,
					g.apyn,
					g.dni,
					(CASE
								WHEN sum(g.cantidad) > 4 THEN MOD(sum(g.cantidad), 4)
								ELSE sum(g.cantidad)
							END) as cantidad,
					MAX(g.ultimafecha) as ultimafecha,
					MAX(g.fecha) as fecha,
					g.reemplzado,
					g.volvio
				
				from (
				
				
					select
						l.refequipo,
						l.nombre,
						l.apyn,
						l.dni,
						l.cantidad,
						l.ultimafecha,
						l.fecha,
						l.reemplzado,
						l.volvio
					from (
						SELECT 
							t.refequipo,
							t.nombre,
							t.apyn,
							t.dni,
							(CASE
								WHEN t.cantidad > 3 THEN MOD(t.cantidad, 3)
								ELSE t.cantidad
							END) AS cantidad,
							ultimafecha,
							fecha,
							t.reemplzado,
							t.volvio
						FROM
							(SELECT 
								a.refequipo,
									e.nombre,
									j.apyn,
									j.dni,
									COUNT(a.amarillas) AS cantidad,
									MAX(fi.reffecha) AS ultimafecha,
									MAX(ff.tipofecha) AS fecha,
									(CASE
										WHEN rr.idreemplazo IS NULL THEN FALSE
										ELSE TRUE
									END) AS reemplzado,
									(CASE
										WHEN rrr.idreemplazo IS NULL THEN 0
										ELSE 1
									END) AS volvio
							FROM
								tbamonestados a
							INNER JOIN dbequipos e ON e.idequipo = a.refequipo
							INNER JOIN dbjugadores j ON j.idjugador = a.refjugador
							INNER JOIN (SELECT 
								idfixture, reffecha
							FROM
								dbfixture fix
							INNER JOIN dbtorneoge tge ON fix.reftorneoge_a = tge.idtorneoge
								OR fix.reftorneoge_b = tge.idtorneoge
							INNER JOIN dbtorneos tt ON tt.idtorneo = tge.reftorneo
								AND tt.reftipotorneo = ".$idtipoTorneo."
								AND tt.activo = 1
							GROUP BY idfixture , reffecha) fi ON fi.idfixture = a.reffixture and fi.reffecha < 28
							INNER JOIN tbfechas ff ON ff.idfecha = fi.reffecha
							LEFT JOIN dbreemplazo rr ON rr.refequiporeemplazado = e.idequipo
								AND rr.reffecha <= ".$idfecha."
							LEFT JOIN dbreemplazo rrr ON rrr.refequipo = e.idequipo
								AND rrr.reffecha <= 28
								AND rrr.reftorneo = ".$refTorneo."
							WHERE
								j.idjugador = ".$idjugador."
									AND a.amarillas <> 2
									AND fi.reffecha <= ".$idfecha."
							GROUP BY a.refequipo , e.nombre , j.apyn , j.dni) t
						WHERE
							(cantidad <> 3
								AND ultimafecha < ".$idfecha.")
								OR (cantidad = 3
								AND ultimafecha = ".$idfecha.")
								OR (cantidad < 3
								AND ultimafecha = ".$idfecha.")
								OR (cantidad > 3
								AND ultimafecha = ".$idfecha.")
						ORDER BY (CASE
							WHEN t.cantidad > 3 THEN MOD(t.cantidad, 3)
							ELSE t.cantidad
						END) DESC , t.nombre , t.apyn
					) l
					
					union all
					
					
					select
						l.refequipo,
						l.nombre,
						l.apyn,
						l.dni,
						l.cantidad,
						l.ultimafecha,
						l.fecha,
						l.reemplzado,
						l.volvio
					from (
						SELECT 
							t.refequipo,
							t.nombre,
							t.apyn,
							t.dni,
							(CASE
								WHEN t.cantidad > 4 THEN MOD(t.cantidad, 4)
								ELSE t.cantidad
							END) AS cantidad,
							ultimafecha,
							fecha,
							t.reemplzado,
							t.volvio
						FROM
							(SELECT 
								a.refequipo,
									e.nombre,
									j.apyn,
									j.dni,
									COUNT(a.amarillas) AS cantidad,
									MAX(fi.reffecha) AS ultimafecha,
									MAX(ff.tipofecha) AS fecha,
									(CASE
										WHEN rr.idreemplazo IS NULL THEN FALSE
										ELSE TRUE
									END) AS reemplzado,
									(CASE
										WHEN rrr.idreemplazo IS NULL THEN 0
										ELSE 1
									END) AS volvio
							FROM
								tbamonestados a
							INNER JOIN dbequipos e ON e.idequipo = a.refequipo
							INNER JOIN dbjugadores j ON j.idjugador = a.refjugador
							INNER JOIN (SELECT 
								idfixture, reffecha
							FROM
								dbfixture fix
							INNER JOIN dbtorneoge tge ON fix.reftorneoge_a = tge.idtorneoge
								OR fix.reftorneoge_b = tge.idtorneoge
							INNER JOIN dbtorneos tt ON tt.idtorneo = tge.reftorneo
								AND tt.reftipotorneo = ".$idtipoTorneo."
								AND tt.activo = 1
							GROUP BY idfixture , reffecha) fi ON fi.idfixture = a.reffixture and fi.reffecha >= 28
							INNER JOIN tbfechas ff ON ff.idfecha = fi.reffecha
							LEFT JOIN dbreemplazo rr ON rr.refequiporeemplazado = e.idequipo
								AND rr.reffecha <= ".$idfecha."
							LEFT JOIN dbreemplazo rrr ON rrr.refequipo = e.idequipo
								AND rrr.reffecha <= ".$idfecha."
								AND rrr.reftorneo = ".$refTorneo."
							WHERE
								j.idjugador = ".$idjugador."
									AND a.amarillas <> 2
									AND fi.reffecha <= ".$idfecha."
							GROUP BY a.refequipo , e.nombre , j.apyn , j.dni) t
						WHERE
							(cantidad <> 4
								AND ultimafecha < ".$idfecha.")
								OR (cantidad = 4
								AND ultimafecha = ".$idfecha.")
								OR (cantidad < 4
								AND ultimafecha = ".$idfecha.")
								OR (cantidad > 4
								AND ultimafecha = ".$idfecha.")
						ORDER BY (CASE
							WHEN t.cantidad > 4 THEN MOD(t.cantidad, 4)
							ELSE t.cantidad
						END) DESC , t.nombre , t.apyn
					) l
				) g
				
				group by g.refequipo,
					g.nombre,
					g.apyn,
					g.dni,
					g.reemplzado,
					g.volvio
				having 	(sum(g.cantidad) <> 4
						AND MAX(g.ultimafecha) < ".$idfecha.")
						OR (sum(g.cantidad) = 4
						AND MAX(g.ultimafecha) = ".$idfecha.")
						OR (sum(g.cantidad) < 4
						AND MAX(g.ultimafecha) = ".$idfecha.")
						OR (sum(g.cantidad) > 4
						AND MAX(g.ultimafecha) = ".$idfecha.")
				order by (CASE
							WHEN sum(g.cantidad) > 4 THEN MOD(sum(g.cantidad), 4)
							ELSE sum(g.cantidad)
						END) DESC , g.nombre , g.apyn
			
			";
		} else {
			
			$sql = "select
				t.refequipo, t.nombre, t.apyn, t.dni, (case when t.cantidad > 4 then mod(t.cantidad,4) else t.cantidad end) as cantidad,ultimafecha,fecha,t.reemplzado, t.volvio
				from
				(
				select
					a.refequipo, e.nombre, j.apyn, j.dni, count(a.amarillas) as cantidad,max(fi.reffecha) as ultimafecha, max(ff.tipofecha) as fecha
					, (case when rr.idreemplazo is null then false else true end) as reemplzado
					, (case when rrr.idreemplazo is null then 0 else 1 end) as volvio
					from		tbamonestados a
					inner
					join		dbequipos e
					on			e.idequipo = a.refequipo
					inner
					join		dbjugadores j
					on			j.idjugador = a.refjugador
					/*inner
					join		dbfixture fi
					on			fi.idfixture = a.reffixture*/
					inner 
					join 		(select idfixture,reffecha from dbfixture fix
									inner join dbtorneoge tge ON fix.reftorneoge_a = tge.idtorneoge
									or fix.reftorneoge_b = tge.idtorneoge
									inner join dbtorneos tt ON tt.idtorneo = tge.reftorneo
									and tt.reftipotorneo = ".$idtipoTorneo."
									and tt.activo = 1
									group by idfixture,reffecha) fi
					on			fi.idfixture = a.reffixture
					inner
					join		tbfechas ff
					on			ff.idfecha = fi.reffecha
					
left join dbreemplazo rr on rr.refequiporeemplazado = e.idequipo and rr.reffecha <= ".$idfecha."
left join dbreemplazo rrr on rrr.refequipo = e.idequipo and rrr.reffecha <= ".$idfecha." and rrr.reftorneo = ".$refTorneo."
					
					where	j.idjugador = ".$idjugador."
					and a.amarillas <> 2
					and fi.reffecha <= ".$idfecha."
					group by a.refequipo, e.nombre, j.apyn, j.dni
				) t
					where (cantidad <> 4 and ultimafecha < ".$idfecha.") or (cantidad = 4 and ultimafecha = ".$idfecha.") or (cantidad < 4 and ultimafecha = ".$idfecha.") or (cantidad > 4 and ultimafecha = ".$idfecha.")
					
					order by (case when t.cantidad > 4 then mod(t.cantidad,4) else t.cantidad end) desc,t.nombre, t.apyn";
		
		}
		
		$res = $this-> query($sql,0);
		if (mysql_num_rows($res)>0) {
			return mysql_result($res,0,'cantidad');
		}
		return 0;
	}
	
	
function insertarAmonestados($refjugador,$refequipo,$reffixture,$amarillas) {
	$sql = "insert into tbamonestados(idamonestado,refjugador,refequipo,reffixture,amarillas)
	values ('',".$refjugador.",".$refequipo.",".$reffixture.",".$amarillas.")";
	
	$res = $this->query($sql,1);
	if ((integer)$res > 0) {
		
		$sqlFixFecha = "select fix.reffecha, tge.reftorneo, t.reftipotorneo
						from dbfixture fix 
						inner join dbtorneoge tge
						on  tge.idtorneoge = fix.reftorneoge_a or tge.idtorneoge = fix.reftorneoge_b
						inner join dbtorneos t 
						on  t.idtorneo = tge.reftorneo
						where fix.idfixture = ".$reffixture."
						group by fix.reffecha, tge.reftorneo, t.reftipotorneo";
		$resFixFecha = $this->query($sqlFixFecha,0);
			
		$fechaJuego = mysql_result($resFixFecha,0,0);
		$refTorneo = mysql_result($resFixFecha,0,1);
		$refTipoTorneo = mysql_result($resFixFecha,0,2);
			
		if ($amarillas == 1) {
			
			//// verificar que este en la tabla de conducta  ///
			//// si esta modificar los puntos /////
			if ($refTipoTorneo == 3) {
				$cantidad = $this->traerAcumuladosAmarillasPorTorneoZonaJugador($fechaJuego,$refjugador,$refTipoTorneo);
			} else {
				$cantidad = $this->traerAcumuladosAmarillasPorTorneoZonaJugador4($fechaJuego,$refjugador,$refTipoTorneo);
			}
			
			$sql = "update tbconducta
					set
					puntos = puntos + 1
					where refequipo =".$refequipo." and reffecha =".$fechaJuego." and reftorneo =".$refTorneo;
			$res2 = $this->query($sql,0);	
			
			if ($refTipoTorneo <> 3) {
			if ($cantidad == 4) {
				$sqlSuspendido = "insert into tbsuspendidos(idsuspendido,refequipo,refjugador,motivos,cantidadfechas,fechacreacion,reffixture)
				values ('',".$refequipo.",".$refjugador.",'".utf8_decode('Acumulacion de 4 Amarillas')."','1','".date('Y-m-d H:i:s')."',".$reffixture.")";
				$res4 = $this->query($sqlSuspendido,1);
				
				$sql5 = "insert into dbsuspendidosfechas(idsuspendidofecha,refjugador,refequipo,reffecha,refsuspendido)
				values ('',".$refjugador.",".$refequipo.",".($fechaJuego + 1).",".$res4.")";
				$res5 = $this->query($sql5,1);
			}
			} else {
			if ($cantidad == 3) {
				$sqlSuspendido = "insert into tbsuspendidos(idsuspendido,refequipo,refjugador,motivos,cantidadfechas,fechacreacion,reffixture)
				values ('',".$refequipo.",".$refjugador.",'".utf8_decode('Acumulacion de 3 Amarillas')."','1','".date('Y-m-d H:i:s')."',".$reffixture.")";
				$res4 = $this->query($sqlSuspendido,1);
				
				$sql5 = "insert into dbsuspendidosfechas(idsuspendidofecha,refjugador,refequipo,reffecha,refsuspendido)
				values ('',".$refjugador.",".$refequipo.",".($fechaJuego + 1).",".$res4.")";
				$res5 = $this->query($sql5,1);
			}
            }
		}
		
		if ($amarillas == 2) {

			$sql = "update tbconducta
					set
					puntos = puntos + 3
					where refequipo =".$refequipo." and reffecha =".$fechaJuego." and reftorneo =".$refTorneo;
			$res3 = $this->query($sql,0);
			
			$sqlSuspendido = "insert into tbsuspendidos(idsuspendido,refequipo,refjugador,motivos,cantidadfechas,fechacreacion,reffixture)
			values ('',".$refequipo.",".$refjugador.",'Doble Amarilla','1','".date('Y-m-d H:i:s')."',".$reffixture.")";
			$res4 = $this->query($sqlSuspendido,1);
			
			$sql5 = "insert into dbsuspendidosfechas(idsuspendidofecha,refjugador,refequipo,reffecha,refsuspendido)
			values ('',".$refjugador.",".$refequipo.",".($fechaJuego + 1).",".$res4.")";
			$res5 = $this->query($sql5,1);
		}
	}
	return $res;
}


function modificarAmonestados($id,$refjugador,$refequipo,$reffixture,$amarillas) {
	$sql = "update tbamonestados
	set
	refjugador = ".$refjugador.",refequipo = ".$refequipo.",reffixture = ".$reffixture.",amarillas = ".$amarillas."
	where idamonestado =".$id;
	
	$res = $this->query($sql,0);
	return $res;
}


function eliminarAmonestados($id) {

// Traigo para descontar /////////
$sqlC = "select refequipo,reffixture, amarillas from tbamonestados where idamonestado =".$id;
$resC = $this->query($sqlC,0);
$reffixture = mysql_result($resC,0,1);
$refequipo = mysql_result($resC,0,0);
$resamarillas = mysql_result($resC,0,2);
/*
$sqlFixFecha = "select reffecha from dbfixture where idfixture =".$reffixture;
		$resFixFecha = $this->query($sqlFixFecha,0);
			
		$fechaJuego = mysql_result($resFixFecha,0,0);
*/
$sqlFixFecha = "select fix.reffecha, tge.reftorneo 
				from dbfixture fix 
				inner join dbtorneoge tge
				on  tge.idtorneoge = fix.reftorneoge_a or tge.idtorneoge = fix.reftorneoge_b
				where fix.idfixture = ".$reffixture."
				group by fix.reffecha, tge.reftorneo";
$resFixFecha = $this->query($sqlFixFecha,0);
	
$fechaJuego = mysql_result($resFixFecha,0,0);
$refTorneo = mysql_result($resFixFecha,0,1);
//////////// descuento de la tabla de conducta  ////////////////		
if ($resamarillas == 2) {
	$descuento = 3;	
} else {
	$descuento = 1;
}
$sqlFP = "update tbconducta
					set
					puntos = puntos - ".$descuento."
					where refequipo =".$refequipo." and reffecha =".$fechaJuego." and reftorneo =".$refTorneo;
$this->query($sqlFP,0);



$sql = "delete from tbamonestados where idamonestado =".$id;
$res = $this->query($sql,0);
return $res;
} 

function traerAmonestados($idtipoTorneo) {
	$sql = "select g.idamonestado,j.apyn, j.dni, e.nombre, ff.tipofecha, g.amarillas, concat(f.nombre,'-',f.descripciontorneo) as torneo
			from tbamonestados g 
			inner join dbequipos e on g.refequipo = e.idequipo 
			inner join dbjugadores j on g.refjugador = j.idjugador 
			inner 
			join 		(select fix.idfixture,fix.reffecha, tt.nombre, tp.descripciontorneo from dbfixture fix
							inner join dbtorneoge tge ON fix.reftorneoge_a = tge.idtorneoge
							or fix.reftorneoge_b = tge.idtorneoge
							inner join dbtorneos tt ON tt.idtorneo = tge.reftorneo
							inner
							join		tbtipotorneo tp
							on			tp.idtipotorneo = tt.reftipotorneo
							and tt.reftipotorneo in (".$idtipoTorneo.")
							and tt.activo = 1
							group by fix.idfixture,fix.reffecha, tt.nombre, tp.descripciontorneo) f
			on f.idfixture = g.reffixture
			inner join tbfechas ff on f.reffecha = ff.idfecha";
	$res = $this->query($sql,0);
	return $res;
}

function traerAmonestadosPorFecha($fecha) {
	$sql = "select g.idamonestado,j.apyn, j.dni, e.nombre, ff.tipofecha, g.amarillas 
			from tbamonestados g 
			inner join dbequipos e on g.refequipo = e.idequipo 
			inner join dbjugadores j on g.refjugador = j.idjugador 
			inner join dbfixture f on f.idfixture = g.reffixture
			inner join tbfechas ff on f.reffecha = ff.idfecha
			where ff.idfecha in (".$fecha.") ";
	$res = $this->query($sql,0);
	return $res;
}

function traerAmonestadosPorId($id) {
	$sql = "select g.idamonestado, j.idjugador,j.apyn, j.dni,e.idequipo, e.nombre,f.idfixture, ff.tipofecha, g.amarillas 
			from tbamonestados g 
			inner join dbequipos e on g.refequipo = e.idequipo 
			inner join dbjugadores j on g.refjugador = j.idjugador 
			inner join dbfixture f on f.idfixture = g.reffixture
			inner join tbfechas ff on f.reffecha = ff.idfecha 
			where g.idamonestado = ".$id;
	$res = $this->query($sql,0);
	return $res;
}


function insertarSuspendidos($refequipo,$refjugador,$motivos,$cantidadfechas,$fechacreacion,$reffixture) {
$sql = "insert into tbsuspendidos(idsuspendido,refequipo,refjugador,motivos,cantidadfechas,fechacreacion,reffixture)
values ('',".$refequipo.",".$refjugador.",'".utf8_decode($motivos)."','".utf8_decode($cantidadfechas)."','".$fechacreacion."',".$reffixture.")";
$res = $this->query($sql,1);


	if (strpos($motivos,'Roja Directa') !== false) {
		
		$sqlFixFecha = "select fix.reffecha, tge.reftorneo 
					from dbfixture fix 
					inner join dbtorneoge tge
					on  tge.idtorneoge = fix.reftorneoge_a or tge.idtorneoge = fix.reftorneoge_b
					where fix.idfixture = ".$reffixture."
					group by fix.reffecha, tge.reftorneo";
		$resFixFecha = $this->query($sqlFixFecha,0);
			//return $sqlFixFecha;
		$fechaJuego = mysql_result($resFixFecha,0,0);
		$refTorneo = mysql_result($resFixFecha,0,1);
	
	
		$sql3 = "update tbconducta
				set
				puntos = puntos + 3
				where refequipo =".$refequipo." and reffecha =".$fechaJuego." and reftorneo =".$refTorneo;
		$res3 = $this->query($sql3,0);
		//return $sql3;
			
	}

return $res;
}


function modificarSuspendidos($id,$refequipo,$refjugador,$motivos,$cantidadfechas,$fechacreacion,$reffixture) {

$sqlSF = "update dbsuspendidosfechas set refjugador = ".$refjugador." where refsuspendido =".$id;
$this->query($sqlSF,0);

$sql = "update tbsuspendidos
set
refequipo = ".$refequipo.",
refjugador = ".$refjugador.",
motivos = '".utf8_decode($motivos)."',
cantidadfechas = '".utf8_decode($cantidadfechas)."',
fechacreacion = '".$fechacreacion."',
reffixture = ".$reffixture."
where idsuspendido =".$id;
$res = $this->query($sql,0);
return $res;
}


function eliminarSuspendidos($id) {


$resS = $this->traerSuspendidosPorId($id);
    //////////////////////// TRAIGO los datos para descontar el FairPlay ///////////////////////
	$sqlFixFecha = "select fix.reffecha, tge.reftorneo, t.reftipotorneo
					from dbfixture fix 
					inner join dbtorneoge tge
					on  tge.idtorneoge = fix.reftorneoge_a or tge.idtorneoge = fix.reftorneoge_b
					inner join dbtorneos t 
					on  t.idtorneo = tge.reftorneo
					where fix.idfixture = ".mysql_result($resS,0,8)."
					group by fix.reffecha, tge.reftorneo, t.reftipotorneo";
	$resFixFecha = $this->query($sqlFixFecha,0);
	
	$motivos = mysql_result($resS,0,3);
		
	$fechaJuego = mysql_result($resFixFecha,0,0);
	$refTorneo = mysql_result($resFixFecha,0,1);
	$refTipoTorneo = mysql_result($resFixFecha,0,2);

	if (strpos($motivos,'Amarillas') !== false) {
		$sqlFP = "update tbconducta
					set
					puntos = puntos - 1
					where refequipo =".mysql_result($resS,0,'refequipo')." and reffecha =".$fechaJuego." and reftorneo =".$refTorneo;
	} else {
		$sqlFP = "update tbconducta
					set
					puntos = puntos - 3
					where refequipo =".mysql_result($resS,0,'refequipo')." and reffecha =".$fechaJuego." and reftorneo =".$refTorneo;
	}
	$this->query($sqlFP,0);
///////////////////////////////////////////////////////////////////////////////////////////////////////

$sql2 = "delete from dbsuspendidosfechas where refjugador =".mysql_result($resS,0,'idjugador')." and refequipo = ".mysql_result($resS,0,'refequipo')." and refsuspendido =".$id;
$res2 = $this->query($sql2,0);

$sql = "delete from tbsuspendidos where idsuspendido =".$id;
$res = $this->query($sql,0);
return $res;
} 

function traerSuspendidos() {
	$sql = "select 
    idsuspendido,
    e.nombre,
    j.apyn,
    motivos,
    cantidadfechas,
    fechacreacion,
    concat(d.nombre,'-',d.descripciontorneo) as torneo,
	c.reffixture
from
    tbsuspendidos c
        inner join
    dbjugadores j ON j.idjugador = c.refjugador
        inner join
    dbequipos e ON e.idequipo = c.refequipo
		inner join
	(select fix.idfixture,t.nombre, tp.descripciontorneo from dbfixture fix inner join dbtorneoge tge
							on			fix.reftorneoge_a = tge.idtorneoge or fix.reftorneoge_b = tge.idtorneoge
							inner
							join		dbtorneos t
							on			t.idtorneo = tge.reftorneo and t.activo = 1
							inner
							join		tbtipotorneo tp
							on			tp.idtipotorneo = t.reftipotorneo
				group by fix.idfixture,t.nombre, tp.descripciontorneo) d ON d.idfixture = c.reffixture
order by e.nombre,j.apyn,cantidadfechas desc";
	$res = $this->query($sql,0);
	return $res;
}

function traerSuspendidosPorId($id) {
	$sql = "select idsuspendido,e.nombre,j.apyn,motivos,cantidadfechas,fechacreacion,c.refequipo,j.idjugador,c.reffixture from tbsuspendidos c
			inner join dbjugadores j on j.idjugador = c.refjugador
			inner join dbequipos e on e.idequipo = c.refequipo
			where c.idsuspendido =".$id;
	$res = $this->query($sql,0);
	return $res;
}

	
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
	
	
	
	
	} //fin de servicios


?>