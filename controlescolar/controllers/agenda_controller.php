<?php


/*
 * Created on 24/11/2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class AgendaController extends ApplicationController {
	public $persistance = false;
	public $template = "system";

	function agregar($id) {
	if(isset($id) && trim($id)!=''){
		$this->ciclo = new Ciclos();
		$this->ciclo = $this->ciclo->find($id);

		$eventos = new Eventos();
		$this->eventos = $eventos->todosPorCategoria();

		$agenda = new Agenda();
		$this->miAgenda = $agenda->eventosPorCiclo($id);
		$this->option = 'exito';
		}else{
		$this->option = 'error';
			$this->error = ' El profesor no existe.';

		}
	}

	function ver() {
		$this->set_response("view");
		$id = $this->post('id');
		$agenda = new Agenda();
		if ($agenda->exists("periodo=" . $id)) {
			$agenda = $agenda->find_first("periodo=" . $id);
			$rol = new Roles();
			$rol = $rol->find_first($agenda->roles_id);

			$evento = new Eventos();
			$evento = $evento->find_first($rol->eventos_id);

			$this->resp = "1[" . $agenda->id . "|" . $agenda->ciclos_id . "|" . $agenda->roles_id . "|" . Utils :: convierteFecha(substr($agenda->inicio, 0, 10)) . "|" . Utils :: convierteFecha(substr($agenda->fin, 0, 10)) . "|" . $agenda->activo . "|" . $evento->nombre . "|" . $evento->id . "|" . $agenda->periodo;
			if($evento->tipo=='E'){

			$rol=new Roles();
			if($rol->exists("eventos_id=".$evento->id." AND id=".$agenda->roles_id)){
			$agenda=new Agenda();
			$privilegios='';
			$agendas = $agenda->find("periodo=" . $id);
			foreach($agendas as $agenda){
				$rol=new Roles();
				$rol=$rol->find($agenda->roles_id);
					$privilegios.=$rol->aco_section.'/'.$rol->aco."|";
			}
			$privilegios=substr($privilegios,0,strlen($privilegios)-1);

			$usuario=$rol->aro;

			$profesor=new Profesores();
			if($profesor->exists("codigo='".$usuario."'")){
			$profesor=$profesor->find_first("codigo='".$usuario."'");
			$grupo='profesores';
			}
			if($grupo!='profesores'){
			$gacl=new gacl_extra();
			if($gacl->is_group($usuario)){
				$grupo=$usuario;
			}else{
			$grupos=$gacl->get_user_groups($usuario);
			$grupo=$grupos[0];
			}
			}

			$this->resp .="[".$grupo."[".$usuario."[".$privilegios;
			}

			}

		} else {
			$this->resp = "-1[No existe el evento";
		}
	}

	function obtengrupos() {
		$this->set_response("view");
		$evento_id = $this->post('id');
		$this->resp = '';
		if (isset ($evento_id) && trim($evento_id) != '') {
			$roles = new Roles();
			$roles = $roles->porEventoEspecial($evento_id);
			$grupos=array();
			foreach ($roles as $rol) {
				//$this->resp .= $rol->aro . '|';
				$grupos[$rol->aro]=$rol->aro;
			}



			$gacl=new gacl_extra();
			$grupos=$gacl->get_childs_groups($grupos);
			foreach($grupos as $grupo){
				if(trim($grupo)!='')
				$this->resp .= $grupo . '|';
			}

			$this->resp = "1[" . substr($this->resp, 0, strlen($this->resp) - 1);
		} else
			$this->resp = "1[";

	}

	function obtenusuarios() {
		$this->set_response("view");
		$gacl=new gacl_extra();
		$id=$this->post('id');
		if(isset($id) && trim($id)!=''){
		//$gacl->get_group_acos2("ESCOLAR")
		$uss=$gacl->get_users($id);
		$this->resp='';
		//$this->resp.=count($uss);
		foreach($uss as $us){
			//foreach($us as $u)
			$this->resp.=$us['name']."|";
		}
		$this->resp = "1[".substr($this->resp,0,strlen($this->resp)-1);
		}else $this->resp='-1[Se necesita el grupo.';
	}

	function obtenacos() {
		$this->set_response("view");
		$evento_id = $this->post('id');
		$aro = $this->post('aro');
		if(isset($evento_id) && trim($evento_id)!='' && isset($aro) && trim($aro)!=''){
		$this->resp = '';
		$gacl = new gacl_extra();
		$datos = $gacl->get_acos($aro);
		$rol = new Roles();
		$roles = $rol->find("eventos_id=" . $rol->idEventoPadreEspecial($evento_id)." order by aco_section");
		$aco_section = $roles[0]->aco_section;

		$priv=array();
		foreach($roles as $rol){

		foreach ($datos[$rol->aco_section] as $section) {
			if($rol->aco==$section){
				if(!in_array($rol->aco_section.'/'.$section,$priv)){
				$this->resp .= $rol->aco_section.'/'.$section . "|";
				$priv[]=$rol->aco_section.'/'.$section;
				}
			}
		}
		}

		$this->resp = "1[" . substr($this->resp, 0, strlen($this->resp) - 1);
		}else $this->resp='-1[Faltan parametros.';
	}

	function guarda() {
		$id = $this->post('id');
		$id_evento = $this->post('id_evento');
		$ini = $this->post('ini');
		$fin = $this->post('fin');
		$activo = $this->post('activo');
		$tipo = $this->post('tipo');

		$grupo = $this->post('grupo');
		$usuario = $this->post('usuarios');

		if ($usuario == "-1") {
			$usuario = $grupo;
		}
		$privs = $this->post('privilegios');
		$privilegios = array ();
		if (trim($privs) != '')
			$privilegios = explode("|", $privs);

		$this->resp = "";
		$this->set_response("view");
		$ciclo = new Ciclos();
		$evento = new Eventos();

		if (trim($id) == '' || trim($id_evento) == '' || trim($ini) == '' || trim($fin) == '' || trim($activo) == '') {
			$this->resp = "-1[Parametros no validos.";
			return;
		}

		if ($ciclo->exists($id) && $evento->exists($id_evento)) {
			$ciclo = $ciclo->find($id);

			if (Utils :: compara_fechas($ini, $fin) <= 0) {


				$i = Utils :: convierteFecha($ciclo->inicio);
				$f = Utils :: convierteFecha($ciclo->fin);

				if (Utils :: compara_fechas($ini, $i) >= 0 && Utils :: compara_fechas($fin, $f) <= 0) { //comparar fechas

					$roles = new Roles();

					if ($tipo == 'E'){
					$id_padre=$roles->idEventoPadreEspecial($id_evento);
					$roles = $roles->find("eventos_id=" . $id_padre."  order by aco_section");
					}else
						$roles = $roles->find("eventos_id=" . $id_evento." order by aco_section");

					if(count($roles)>0){
					$cad_rol = "";
					$agenda = new Agenda();
					$periodo = $agenda->maximum('periodo');
					$periodo++;

					if ($tipo == 'E') {
						if (trim($privs) != '' && trim($usuario) != '' && trim($grupo) != '') {
							$msj='';

							$pvs=array();
							foreach($roles as $rol){
							foreach ($privilegios as $privilegio) {

								if(!in_array($rol->aco_section.'/'.$rol->aco,$pvs)){
								if($rol->aco_section.'/'.$rol->aco==$privilegio){
								$pvs[]=$rol->aco_section.'/'.$rol->aco;
								$sql="eventos_id=".$id_evento." AND aco_section='".$rol->aco_section."' AND aco='".$rol->aco."' AND aro='$usuario'";
								$nuevoRol = new Roles();
								if(!$nuevoRol->exists($sql)){

								$nuevoRol->eventos_id = $id_evento;
								$nuevoRol->aco_section = $rol->aco_section;
								$nuevoRol->aco = $rol->aco;
								$nuevoRol->aro = $usuario;
								$nuevoRol->save();
								}else{
									$nuevoRol=$nuevoRol->find_first($sql);
								}

								$agenda = new Agenda();
								$agenda->ciclos_id = $id;

								$agenda->roles_id = $nuevoRol->id;

								$agenda->inicio = Utils :: convierteFechaMySql($ini);
								$agenda->fin = Utils :: convierteFechaMySql($fin);

								$agenda->activo = $activo;
								$agenda->periodo = $periodo;

								$agenda->save();
								$msj.=$rol->id.' Acceso a '.$privilegio.'<br/>';
								}else{
									$msj.='No tiene acceso a '.$privilegio."<br/>";
									}

							}
							}

							}
						$this->resp = "1[Evento Especial agregado con exito.[" . $periodo."[".$msj;
						$evento=new Eventos();
						$evento=$evento->find($id_evento);

						$historial=new Historial();
						$historial->ciclos_id= Session :: get_data('ciclo.id');
						$historial->usuario=Session :: get_data('usr.login');
						$historial->descripcion="Se guardo el evento ".$evento->nombre." para el ciclo ".$ciclo->numero." con el id ".$ciclo->id." con los siguientes datos inicio:".$ini." fin:".$fin." activo:".$activo;
						$historial->controlador="agenda";
						$historial->accion="guarda";
						$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
						$historial->save();

						} else {
							$this->resp = "-1[Faltan parametros Especiales. ";

						}


					} else {
						foreach ($roles as $rol) {

							$agenda = new Agenda();
							$agenda->ciclos_id = $id;

							$agenda->roles_id = $rol->id;

							$agenda->inicio = Utils :: convierteFechaMySql($ini);
							$agenda->fin = Utils :: convierteFechaMySql($fin);

							$agenda->activo = $activo;
							$agenda->periodo = $periodo;
							$agenda->save();

						}
						$this->resp = "1[Evento agregado con exito.[" . $periodo;
						$evento=new Eventos();
						$evento=$evento->find($id_evento);

						$historial=new Historial();
						$historial->ciclos_id= Session :: get_data('ciclo.id');
						$historial->usuario=Session :: get_data('usr.login');
						$historial->descripcion="Se guardo el evento ".$evento->nombre." para el ciclo ".$ciclo->numero." con el id ".$ciclo->id." con los siguientes datos inicio:".$ini." fin:".$fin." activo:".$activo;
						$historial->controlador="agenda";
						$historial->accion="guarda";
						$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
						$historial->save();
					}

					/*$cad_rol=" (".substr($cad_rol,0,strlen($cad_rol)-3).") ";
					$sql="ciclos_id='".$agenda->ciclos_id."' AND $cad_rol AND inicio='".$agenda->inicio."' AND" .
						" fin='".$agenda->fin."' AND activo='".$agenda->activo."'";
					$agenda=$agenda->find($sql);
					$ids='';
					foreach($agenda as $a){
						$ids.=$a->id."|";
					}
					$ids=substr($ids,0,strlen($ids)-1);
					*/

					}else{
						$this->resp = "-1[Ocurrio un error no existen roles. ".$id_evento;

							}

				} else {
					$this->resp = "-1[El periodo de tiempo no encaja en el ciclo. ";

				}

			} else {
				$this->resp = "-1[La fecha de inicio es mayor a la fecha final.";
			}

		} else {
			$this->resp = "-1[Ocurrio un error el ciclo y/o el evento no existen.";
		}
	}

	function editar() {
		$id = $this->post('id');

		$ini = $this->post('ini');
		$fin = $this->post('fin');
		$activo = $this->post('activo');
		$id_agenda = $this->post('id_agenda');
		$tipo = $this->post('tipo');

		$grupo = $this->post('grupo');
		$usuario = $this->post('usuarios');

		if ($usuario == "-1") {
			$usuario = $grupo;
		}
		$privs = $this->post('privilegios');
		$privilegios = array ();
		if (trim($privs) != '')
			$privilegios = explode("|", $privs);

		$this->resp = "";
		$this->set_response("view");

		if (trim($id) == '' || trim($ini) == '' || trim($fin) == '' || trim($activo) == '') {
			$this->resp = "-1[Parametros no validos.";
			return;
		}

		$evento = new Eventos();
		$ciclo = new Ciclos();
		$agenda = new Agenda();

		if ($agenda->exists($id_agenda)) {

			if ($ciclo->exists($id)) {
				$ciclo = $ciclo->find($id);

				if (Utils :: compara_fechas($ini, $fin) <= 0) {



					$i = Utils :: convierteFecha($ciclo->inicio);
					$f = Utils :: convierteFecha($ciclo->fin);

					if (Utils :: compara_fechas($ini, $i) >= 0 && Utils :: compara_fechas($fin, $f) <= 0) { //comparar fechas

						$agenda = $agenda->find($id_agenda);
						$periodo= $agenda->periodo;
						$agendas = $agenda->find("periodo=" . $periodo);
						$sql="";
						foreach($agendas as $ag){
						$nuevoRol = new Roles();
						$nuevoRol=$nuevoRol->find($ag->roles_id);
						$id_evento=$nuevoRol->eventos_id;
						$aco_section=$nuevoRol->aco_section;
						if ($tipo == 'E') {
						$ag->delete();
						//$nuevoRol->delete();
						}
						}

						$roles = new Roles();
							if ($tipo == 'E'){
							$id_padre=$roles->idEventoPadreEspecial($id_evento);
							$roles = $roles->find("eventos_id=" . $id_padre." order by aco_section");
							}else
								$roles = $roles->find("eventos_id=" . $id_evento." order by aco_section");


						if ($tipo == 'E') {
						if (trim($privs) != '' && trim($usuario) != '' && trim($grupo) != '') {
							$pvs=array();
							foreach($roles as $rol){
							foreach ($privilegios as $privilegio) {
							if(!in_array($rol->aco_section.'/'.$rol->aco,$pvs)){
								if($rol->aco_section.'/'.$rol->aco==$privilegio){
								$pvs[]=$rol->aco_section.'/'.$rol->aco;
								$sql="eventos_id=".$id_evento." AND aco_section='".$rol->aco_section."' AND aco='".$rol->aco."' AND aro='".$usuario."'";

								$nuevoRol = new Roles();
								if(!$nuevoRol->exists($sql)){
								$nuevoRol->eventos_id = $id_evento;
								$nuevoRol->aco_section = $rol->aco_section;
								$nuevoRol->aco = $rol->aco;
								$nuevoRol->aro = $usuario;
								$nuevoRol->save();
								}else{
									$nuevoRol=$nuevoRol->find_first($sql);
								}

								$agenda = new Agenda();
								$agenda->ciclos_id = $id;

								$agenda->roles_id = $nuevoRol->id;

								$agenda->inicio = Utils :: convierteFechaMySql($ini);
								$agenda->fin = Utils :: convierteFechaMySql($fin);

								$agenda->activo = $activo;
								$agenda->periodo = $periodo;

								$agenda->save();
							}
							}
							}
							}
						$this->resp = "1[Evento Especial editado con exito.[" . $periodo;

						$evento=new Eventos();
						$evento=$evento->find($id_evento);
						$historial=new Historial();
						$historial->ciclos_id= Session :: get_data('ciclo.id');
						$historial->usuario=Session :: get_data('usr.login');
						$historial->descripcion="Se edito el evento ".$evento->nombre." para el ciclo ".$ciclo->numero." con el id ".$ciclo->id." con los siguientes datos inicio:".$ini." fin:".$fin." activo:".$activo;
						$historial->controlador="agenda";
						$historial->accion="editar";
						$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
						$historial->save();
						} else {
							$this->resp = "-1[Faltan parametros Especiales. ";

						}

					} else {
						foreach ($agendas as $agenda) {
							$agenda->inicio = Utils :: convierteFechaMySql($ini);
							$agenda->fin = Utils :: convierteFechaMySql($fin);

							$agenda->activo = $activo;
							$agenda->update();
						}
						//$sql="ciclos_id='".$agenda->ciclos_id."' AND roles_id='".$agenda->roles_id."' AND inicio='".$agenda->inicio."' AND" .
						//" fin='".$agenda->fin."' AND activo='".$agenda->activo."'";
						//$agenda=$agenda->find_first($sql);

						$this->resp = "1[Evento editado con exito.[" . $agenda->periodo;

						$evento=new Eventos();
						$evento=$evento->find($id_evento);
						$historial=new Historial();
						$historial->ciclos_id= Session :: get_data('ciclo.id');
						$historial->usuario=Session :: get_data('usr.login');
						$historial->descripcion="Se edito el evento ".$evento->nombre." para el ciclo ".$ciclo->numero." con el id ".$ciclo->id." con los siguientes datos inicio:".$ini." fin:".$fin." activo:".$activo;
						$historial->controlador="agenda";
						$historial->accion="editar";
						$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
						$historial->save();
						}
					} else {
						$this->resp = "-1[El periodo de tiempo no encaja en el ciclo. ";

					}

				} else {
					$this->resp = "-1[La fecha de inicio es mayor a la fecha final.";
				}

			} else {
				$this->resp = "-1[El ciclo no existe. ";

			}

		} else {
			$this->resp = "-1[Ocurrio un error recarge la pagina.";
		}

	}


}
?>
