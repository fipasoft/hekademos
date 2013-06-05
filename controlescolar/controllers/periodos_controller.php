<?php
 /**
 * periodos_controller.php
 *
 * Created on 04/05/2009
 * @package  Controladores
 * @author     J Jonathan Lopez <jlopez@fipasoft.com.mx>
 * @copyright  2009 FiPa Software (contacto at fipasoft.com.mx)
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL)
 *
 */

Kumbia :: import('app.componentes.*');
Kumbia :: import('lib.kumbia.utils');

  class PeriodosController extends ApplicationController {
 	public $template = "system";

	public function agregar(){
		if($this->post("ciclos_id")==""){
		$ciclos=new Ciclos();
		$ciclos=$ciclos->find();

		$this->ciclos=array();
		$periodo=new Periodo();
		foreach($ciclos as $c){
			//if(!$periodo->existePeriodoParaElCiclo($c->id))
				$this->ciclos[]=$c;
		}
		$this->option="captura";
		}else{

		$ini = Utils::convierteFechaMySql(substr($this->post('inicio'),0,10))." ".substr($this->post('inicio'),10);
		$fin = Utils::convierteFechaMySql(substr($this->post('fin'),0,10))." ".substr($this->post('fin'),10);


		if (Utils :: comparaDateTime($ini, $fin) <= 0) {
				$ciclo=new Ciclos();
				$ciclo=$ciclo->find($this->post("ciclos_id"));
				if($ciclo->id!=""){
				$siguiente=new Ciclos();
				$siguiente=$siguiente->find_first("numero='".$ciclo->siguiente()."'");
				if($siguiente->id!=""){
				$i = Utils :: convierteFecha($ciclo->inicio);
				$f = Utils :: convierteFecha($ciclo->fin);
				$ip=substr($this->post('inicio'),0,10);
				$fp=substr($this->post('fin'),0,10);

				if (
					(Utils :: compara_fechas($ip, $i) >= 0 && Utils :: compara_fechas($ip, $f) <= 0)
					 &&
					(Utils :: compara_fechas($fp, $i) >= 0 && Utils :: compara_fechas($fp, $f) <= 0)
					) { //comparar fechas

					$periodo=new Periodo();

					$periodo->ciclos_id=$this->post("ciclos_id");
					$periodo->cursosciclos_id=$siguiente->id;
					$periodo->inicio= Utils :: fecha_convertir(substr($this->post('inicio'),0,10)).substr($this->post('inicio'),10);
					$periodo->fin= Utils :: fecha_convertir(substr($this->post('fin'),0,10)).substr($this->post('fin'),10);
					if($this->post("activo")!="")
					$periodo->activo=$this->post("activo");
					else
					$periodo->activo="0";

					$periodo->intervalo="0";

					if($periodo->save()){
					$this->option="exito";
					$dias=new Dias();
					$dias=$dias->find("id!='7'");
					foreach($dias as $d){
					$periodohorario=new Periodohorario();
					$periodohorario->dias_id=$d->id;
					$periodohorario->periodo_id=$periodo->id;
					$periodohorario->inicio="08:00:00";
					$periodohorario->fin="21:00:00";
					if(!$periodohorario->save()){
						$this->option = 'error';
						$this->error = ' Ocurrio un error en la BD.';

					}

					}
						if($this->option=="exito"){

						$historial=new Historial();
						$historial->ciclos_id= Session :: get_data('ciclo.id');
						$historial->usuario=Session :: get_data('usr.login');
						$historial->descripcion='Se agrego periodo de inscripcion para el ciclo '.$ciclo->numero.'. Inicia el '.$this->post('inicio').' y finaliza el '.$this->post('fin').' activo: '.$periodo->activo;
						$historial->controlador=$this->controlador;
						$historial->accion=$this->accion;
						$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
						$historial->save();
						}
					}else{
						$this->option = 'error';
						$this->error = ' Ocurrio un error en la BD.';

					}
				}else{
				$this->option = 'error';
				$this->error = ' El periodo de tiempo no encaja en el ciclo.';
				}
				}else{
				$this->option = 'error';
				$this->error = ' El ciclo '.$ciclo->siguiente().' no existe.';
				}
				}else{
				$this->option = 'error';
				$this->error = ' El ciclo no existe.';
				}

		}else{
				$this->option = 'error';
				$this->error = ' La fecha de inicio es mayor a la fecha final.';
		}

		}
	}

	public function editar($id=""){
	if($id!=""){
		$id=intval($id,10);
		$periodo=new Periodo();
		$periodo=$periodo->find($id);
		if($periodo->id!=""){

		$this->periodo=$periodo;

		$ciclo=new Ciclos();
		$ciclo=$ciclo->find($periodo->ciclos_id);
		if($ciclo->id!=""){
		$this->ciclo=$ciclo;

		$ciclos=new Ciclos();
		$ciclos=$ciclos->find();

		$this->ciclos=array();
		$per=new Periodo();
		foreach($ciclos as $c){
			//if($c->id==$ciclo->id || !$per->existePeriodoParaElCiclo($c->id))
				$this->ciclos[]=$c;
		}

		$inicio=new DateTime(substr($periodo->inicio,0,10));
		$fin=new DateTime(substr($periodo->fin,0,10));


		$this->inicio=$inicio->format("d/m/Y").substr($periodo->inicio,10,6);
		$this->fin=$fin->format("d/m/Y").substr($periodo->fin,10,6);
		$this->option="captura";
		}else{
			$this->option = 'error';
			$this->error = ' El ciclo no existe.';

		}
		}else{
			$this->option = 'error';
			$this->error = ' El periodo no existe.';

		}
		}elseif($this->post("id")!=""){
		$id=intval($this->post("id"),10);
		$periodo=new Periodo();
		$periodo=$periodo->find($id);
		if($periodo->id!=""){
		$ini = Utils::convierteFechaMySql(substr($this->post('inicio'),0,10))." ".substr($this->post('inicio'),10);
		$fin = Utils::convierteFechaMySql(substr($this->post('fin'),0,10))." ".substr($this->post('fin'),10);


		if (Utils :: comparaDateTime($ini, $fin) < 0) {
				$ciclo=new Ciclos();
				$ciclos_id=intval($this->post("ciclos_id"),10);
				$ciclo=$ciclo->find($ciclos_id);
				if($ciclo->id!=""){
				$siguiente=new Ciclos();
				$siguiente=$siguiente->find_first("numero='".$ciclo->siguiente()."'");
				if($siguiente->id!=""){
				$i = Utils :: convierteFecha($ciclo->inicio);
				$f = Utils :: convierteFecha($ciclo->fin);
				$ip=substr($this->post('inicio'),0,10);
				$fp=substr($this->post('fin'),0,10);
				if (
					(Utils :: compara_fechas($ip, $i) >= 0 && Utils :: compara_fechas($ip, $f) <= 0)
					 &&
					(Utils :: compara_fechas($fp, $i) >= 0 && Utils :: compara_fechas($fp, $f) <= 0)
					) { //comparar fechas
					//Los bloques creados esten dentro del periodo

					$bloques=new Bloque();
					$bloques=$bloques->find("periodo_id='".$periodo->id."'");
					$bloque_error=false;
					foreach($bloques as $b){
					if (!(
						(Utils :: comparaDateTime($ini, $b->inicio) <= 0 && Utils :: comparaDateTime($b->inicio,$fin) <= 0)
						 &&
						 (Utils :: comparaDateTime($ini, $b->fin) <= 0 && Utils :: comparaDateTime( $b->fin, $fin) <= 0)
						)) { //comparar fechas
					$bloque_error=true;
					break;
					}
					}

					if(!$bloque_error){
					$periodo->ciclos_id=$ciclos_id;
					$periodo->inicio= Utils :: fecha_convertir(substr($this->post('inicio'),0,10)).substr($this->post('inicio'),10);
					$periodo->fin= Utils :: fecha_convertir(substr($this->post('fin'),0,10)).substr($this->post('fin'),10);
					if($this->post("activo")!="")
					$periodo->activo=$this->post("activo");
					else
					$periodo->activo="0";

					if($periodo->save()){
					$this->option="exito";
					$historial=new Historial();
					$historial->ciclos_id= Session :: get_data('ciclo.id');
					$historial->usuario=Session :: get_data('usr.login');
					$historial->descripcion='Se edito el periodo de inscripcion para el ciclo '.$ciclo->numero.'. Inicia el '.$this->post('inicio').' y finaliza el '.$this->post('fin').' activo: '.$periodo->activo;
					$historial->controlador=$this->controlador;
					$historial->accion=$this->accion;
					$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
					$historial->save();
					}else{
						$this->option = 'error';
						$this->error = ' Ha ocurrido un error en la BD.';
					}
					}else{
					$this->option = 'error';
					$this->error = ' Los bloques existentes no encajan en el periodo establecido.';
					}
				}else{
				$this->option = 'error';
				$this->error = ' El periodo de tiempo no encaja en el ciclo.';
				}
				}else{
				$this->option = 'error';
				$this->error = ' El ciclo '.$ciclo->siguiente().' no existe.';
				}
				}else{
				$this->option = 'error';
				$this->error = ' El ciclo no existe.';
				}

		}else{
				$this->option = 'error';
				$this->error = ' La fecha de inicio es mayor a la fecha final.';
		}
		}else{
			$this->option = 'error';
			$this->error = ' No existe el periodo';
		}

		}else{
			$this->option = 'error';
			$this->error = ' No tiene permiso para ver la pagina.';
		}
	}

	public function eliminar($id=''){
		if ($id != '') {
			$this->option = 'captura';
			$id = intval($id, 10);
			$periodo=new Periodo();
			$periodo=$periodo->find_all_by_sql(
				"SELECT periodo.*,ciclos.numero FROM " .
				" periodo " .
				" INNER JOIN ciclos ON periodo.ciclos_id=ciclos.id " .
				" WHERE periodo.id='".$id."' "
				);

			if ($periodo[0]->id == '') {
				$this->option = 'error';
				$this->error = ' El periodo no existe.';
			}else{
				$this->periodo=$periodo[0];
				$this->option="captura";
			}

		}elseif ($this->post('id') != '') {
			$id=intval($this->post('id'));
			$periodo=new Periodo();
			$periodo=$periodo->find($id);
			if($periodo->id!=""){
			$ciclo=new Ciclos();
			$ciclo=$ciclo->find($periodo->ciclos_id);
			mysql_query("BEGIN") or die("ELIMINA_1");
			try{
			$Periodo=new Periodo();

			if($Periodo->delete($id)){
			$this->option="exito";
			$historial=new Historial();
			$historial->ciclos_id= Session :: get_data('ciclo.id');
			$historial->usuario=Session :: get_data('usr.login');
			$historial->descripcion='Se elimino el periodo de inscripcion para el ciclo '.$ciclo->numero.'. Iniciaba el '.Utils::convierteFecha(substr($periodo->inicio,0,10)).' '.substr($periodo->inicio,10).' y finalizaba el '.Utils::convierteFecha(substr($periodo->fin,0,10)).' '.substr($periodo->fin,10);
			$historial->controlador=$this->controlador;
			$historial->accion=$this->accion;
			$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
			$historial->save();

			mysql_query("COMMIT") or die("ELIMINA_2");

			}else
			mysql_query("ROLLBACK") or die("ELIMINA_4");

			}catch(Exception $e){
			mysql_query("ROLLBACK") or die("ELIMINA_3");
				$this->option = 'error';
				$this->error = ' El periodo cuenta con informacion ligada.';
			}
			}else{
				$this->option = 'error';
				$this->error = ' El periodo no existe.';
			}

		}else{
				$this->option = 'error';
				$this->error = ' El periodo no existe.';
		}
	}

	public function estadistica($id=''){
		if ($id != '') {
			$id = intval($id, 10);
			$periodo=new Periodo();
			$periodo=$periodo->find($id);

			if ($periodo->id == '') {
			$this->option = 'error';
			$this->error = ' El periodo no existe.';
			}else{
			$this->option = 'vista';
			$this->periodo=$periodo;
			$estadistica=array();
			$estadistica['alumnos']=array();
			$estadistica['cursos']=array();

			$periodosalumnos=new Periodosalumnos();
			$periodosalumnos=$periodosalumnos->find_all_by_sql(
				"SELECT periodosalumnos.*,grupos.id as grupos_id " .
				" FROM " .
				" periodosalumnos " .
				" INNER JOIN alumnos ON periodosalumnos.alumnos_id=alumnos.id " .
				" Inner Join alumnosgrupo ON alumnos.id=alumnosgrupo.alumnos_id " .
				" INNER JOIN grupos ON alumnosgrupo.grupos_id=grupos.id " .
				" WHERE periodosalumnos.periodo_id='".$periodo->id."' AND grupos.ciclos_id='".$periodo->ciclos_id."'"
				);

			$this->alumnosTotal=count($periodosalumnos);
			$grupos=array();
			foreach($periodosalumnos as $a){
			$existentes[]=$a->alumnos_id;
				$grupos[$a->grupos_id]=$a->grupos_id;
			}

			$this->grupos=array();
			$alumnosTotal=0;
			$registradosTotal=0;
			foreach($grupos as $g){
				$grupo=new Grupos();
				$grupo=$grupo->find($g);
				$estadistica['alumnos'][$grupo->id]['Grado']=$grupo->grado;
				$estadistica['alumnos'][$grupo->id]['Grupo']=$grupo->letra;
				$estadistica['alumnos'][$grupo->id]['Turno']=$grupo->turno;

				$periodosalumnos=new Periodosalumnos();
				$periodosalumnos=$periodosalumnos->find_all_by_sql(
					"SELECT grupos.id " .
					" FROM " .
					" periodosalumnos " .
					" INNER JOIN alumnos ON periodosalumnos.alumnos_id=alumnos.id " .
					" Inner Join alumnosgrupo ON alumnos.id=alumnosgrupo.alumnos_id " .
					" INNER JOIN grupos ON alumnosgrupo.grupos_id=grupos.id " .
					" WHERE periodosalumnos.periodo_id='".$periodo->id."' AND grupos.id='".$grupo->id."'"
					);

				$alumnos=count($periodosalumnos);
				$estadistica['alumnos'][$grupo->id]['Alumnos']=$alumnos;

				$periodoscursos=new Periodoscursos();
				$registros=$periodoscursos->inscritosdelGrupo($grupo->id);
				$estadistica['alumnos'][$grupo->id]['Registrados']=$registros;

				$porcentaje=0;
				if($alumnos>0){
				$porcentaje=($registros/$alumnos)*100;
				$porcentaje=round($porcentaje * 100) / 100;
				if(!stripos($porcentaje,".")){
					$porcentaje.=".00";
				}

				}

				$estadistica['alumnos'][$grupo->id]['%']=$porcentaje;

				$alumnosTotal+=$alumnos;
				$registradosTotal+=$registros;
			}

			$estadistica['alumnos']["r"]['Grado']='';
			$estadistica['alumnos']["r"]['Grupo']='';
			$estadistica['alumnos']["r"]['Turno']='';
			$estadistica['alumnos']["r"]['Alumnos']=$alumnosTotal;
			$estadistica['alumnos']["r"]['Registrados']=$registradosTotal;

			$porcentaje=0;
				if($alumnosTotal>0){
				$porcentaje=($registradosTotal/$alumnosTotal)*100;
				$porcentaje=round($porcentaje * 100) / 100;
				if(!stripos($porcentaje,".")){
					$porcentaje.=".00";
				}

				}
			$estadistica['alumnos']["r"]['%']=$porcentaje;

			/************************************************************/

			$cursos=new Cursos();
			$cursos=$cursos->find_all_by_sql(
						"SELECT periodoscursos.id AS periodoscursos_id,periodoscursos.cupos,periodoscursos.inscritos,cursos.*,materias.nombre as materia FROM " .
						" periodoscursos " .
						" INNER JOIN cursos ON periodoscursos.cursos_id=cursos.id " .
						" INNER JOIN grupos ON cursos.grupos_id=grupos.id ".
						" INNER JOIN materias ON cursos.materias_id  = materias.id " .
						" WHERE periodoscursos.periodo_id='".$periodo->id."'".
						"ORDER BY grupos.turno, grupos.grado, grupos.letra, materias.nombre "
			);
			$cuposTotal=0;
			$registradosTotal=0;
			foreach($cursos as $curso){
				$periodocurso=new Periodoscursos();
				$periodocurso=$periodocurso->find_first("cursos_id='".$curso->id."' AND periodo_id='".$periodo->id."'");

				$grupo=$curso->grupo();
				$estadistica['cursos'][$curso->id]['Grado']=$grupo->grado;
				$estadistica['cursos'][$curso->id]['Grupo']=$grupo->letra;
				$estadistica['cursos'][$curso->id]['Turno']=$grupo->turno;
				$estadistica['cursos'][$curso->id]['Curso']=$curso->materia;
				$estadistica['cursos'][$curso->id]['Cupos']=$periodocurso->cupos;
				$estadistica['cursos'][$curso->id]['Registrados']=$periodocurso->inscritos;

				$porcentaje=0;
				if($periodocurso->inscritos>0){
				$porcentaje=($periodocurso->inscritos/$periodocurso->cupos)*100;
				$porcentaje=round($porcentaje * 100) / 100;
				if(!stripos($porcentaje,".")){
					$porcentaje.=".00";
				}

				}

				$estadistica['cursos'][$curso->id]['%']=$porcentaje;
				$estadistica['cursos'][$curso->id]['Opciones']=$periodocurso->id;

				$cuposTotal+=$periodocurso->cupos;
				$registradosTotal+=$periodocurso->inscritos;
			}

			$estadistica['cursos']["r"]['Grado']='';
			$estadistica['cursos']["r"]['Grupo']='';
			$estadistica['cursos']["r"]['Turno']='';
			$estadistica['cursos']["r"]['Cupos']=$cuposTotal;
			$estadistica['cursos']["r"]['Registrados']=$registradosTotal;

			$porcentaje=0;
				if($alumnosTotal>0){
				$porcentaje=($registradosTotal/$cuposTotal)*100;
				$porcentaje=round($porcentaje * 100) / 100;
				if(!stripos($porcentaje,".")){
					$porcentaje.=".00";
				}

				}
			$estadistica['cursos']["r"]['%']=$porcentaje;
			$estadistica['cursos']["r"]['Opciones']='';

			$this->estadistica=$estadistica;
			}
		}else{
			$this->option = 'error';
			$this->error = ' El periodo no existe.';
		}
	}

	public function index($pag = ''){
		$controlador = $this->controlador;
		$accion = $this->accion;
		// acl
		$usr_login = Session :: get_data('usr.login');
		$this->acl = array ();
		$acl = new gacl_extra();
		$acos_arr = array (
			'periodos' => array (
				'agregar',
				'editar',
				'eliminar',
				'estadistica'
				),
			'optativas' =>array(
				'index'
			)
		);

		$this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);

		$this->option="vista";
		// busqueda
		$b = new Busqueda($controlador, $accion);
		// genera las condiciones
		$c = $b->condicion(array("ciclos_id"));
		$this->busqueda = $b;

		$periodos=new Periodo();

		$this->registros=$periodos->count(($c == "" ? "" : $c));
		// paginacion
		$paginador = new Paginador($controlador, $accion);
		if ($pag != '') {
			$paginador->guardarPagina($pag);
		}
		$paginador->estableceRegistros($this->registros);
		$paginador->generar();
		$this->paginador = $paginador;

		$periodos=$periodos->find_all_by_sql(
				"SELECT periodo.*,ciclos.numero FROM " .
				" periodo " .
				" INNER JOIN ciclos ON periodo.ciclos_id=ciclos.id ".
				 ($c == "" ? "" : "AND " . $c).
				"ORDER BY ciclos.inicio DESC ".
				"LIMIT " .
		 ($paginador->pagina() * $paginador->rpp()) . ', ' .
		$paginador->rpp() . " "
		);
		$this->periodos=$periodos;
		$ciclos = new Ciclos();
		$this->ciclos = $ciclos->find();
	}

  }
?>
