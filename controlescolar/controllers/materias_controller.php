<?php

// sp5, Creado el 24/09/2008
/**
 * Materias
 *
 * @author     mimeks <mimex@fipasoft.com.mx>
 * @copyright  2008 FiPa Software (contacto at fipasoft.com.mx)
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL)
 *
 */

Kumbia :: import('app.componentes.*');
Kumbia :: import('lib.kumbia.utils');
Kumbia :: import('lib.excel.main');

class MateriasController extends ApplicationController {
	public $template = "system";

	public function academias(){
		try{
			$materias = $this->post("materias");
			$academias = $this->post("academias");
			if(is_array($materias) && is_array($academias)){
				mysql_query("BEGIN") or die("ACADEMIAS");
				foreach($materias as $mid){
					$materia = new Materias();
					$materia = $materia->find($mid);
					if($materia->id==""){
						throw new Exception( 'Datos invalidos materias');
					}
						
					$aid = $academias[$mid];
					if($aid!=""){
						$academia = new Academia();
						$academia = $academia->find($aid);
						if($academia->id==""){
							throw new Exception( 'Datos invalidos academias');
						}
							
						$academiamateria = new Academiamateria();
						$academiamateria = $academiamateria->find_first("materias_id='".$materia->id."'");
						if($academiamateria->id==""){
							$academiamateria = new Academiamateria();
							$academiamateria->materias_id = $materia->id;
						}
							
						$academiamateria->academia_id = $academia->id;
							
						if(!$academiamateria->save()){
							throw new Exception( 'Error al guardar');
						}
					}else{
						$academiamateria = new Academiamateria();
						$academiamateria = $academiamateria->find_first("materias_id='".$materia->id."'");
						if($academiamateria->id!=""){
							if(!$academiamateria->delete()){
								throw new Exception( 'Error al eliminar');
							}
						}
					}
						
				}
				mysql_query("COMMIT") or die("ACADEMIAS");
				$this->option = "exito";
			}else{
				$materias = new Materias();
				$materias = $materias->find();
				$this->materias=array();
				foreach($materias as $materia){
					$oferta = new Ofertasmaterias();
					$oferta = $oferta->find_first("materias_id='".$materia->id."'");
					$this->materias[$materia->semestre][$oferta->oferta_id][] = $materia;
				}

				$ofertas = new Oferta();
				$ofertas = $ofertas->find();
				$this->ofertas = array();
				foreach($ofertas as $oferta){
					$this->ofertas[$oferta->id] = $oferta;
				}

				$academias = new Academia();
				$this->academias = $academias->find();
				$this->option = "captura";
			}
		}catch( Exception $e ){
			$this->option = "error";
			$this->error = $e->getMessage();
		}
	}

	public function agregar() {
		$ciclo=new Ciclos();
		$ciclo=$ciclo->find(Session :: get_data('ciclo.id'));
		if($ciclo->abierto()){

			$ciclo_id = Session :: get_data('ciclo.id');
			$Ciclos = new Ciclos();
			$this->ciclo = $Ciclos->find($ciclo_id);
			$sigCiclo = new Ciclos();
			$sigCiclo = $sigCiclo->find_first("numero='" . $this->ciclo->anterior() . "'");
			$agenda = new Agenda();
			if ($agenda->eventoValido('PLN-GEN', Session :: get_data('ciclo.id'), $this->controlador, $this->accion, Session :: get_data('usr.login')) || $agenda->eventoValido('PLN-GEN-ESP', Session :: get_data('ciclo.id'), $this->controlador, $this->accion, Session :: get_data('usr.login')) || ($sigCiclo->id != '' && ($agenda->eventoValido('PLN-GEN', $sigCiclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login')) || $agenda->eventoValido('PLN-GEN-ESP', $sigCiclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login'))))) {

				if ($this->post('clave') == '') {
					$this->option = 'captura';
					$oferta = new Oferta();
					$this->ofertas = $oferta->todas();
				} else {
					$this->option = '';
					$this->error = '';
					$materia = new Materias();
					$materia->clave = $this->post('clave');
					$materia->nombre = $this->post('nombre');
					$materia->descripcion = $this->post('descripcion');
					$materia->semestre = $this->post('semestre');
					$materia->tipo = $this->post('tipo');
					if($this->post('oferta')==2){
						$materia->creditos = $this->post('creditos');
						$materia->duracion = $this->post('duracion');
						$materia->horassemana = $this->post('horas');
						$materia->competencia = $this->post('competencia');
					}
					$materia->validates_uniqueness_of('clave');
					if ($materia->save()) {
						$this->option = 'exito';
						$ofertasMaterias = new Ofertasmaterias();
						$ofertasMaterias->materias_id = $materia->id;
						$ofertasMaterias->oferta_id = $this->post('oferta');
						$ofertasMaterias->save();

						if($this->post('oferta')==2){
							if($this->post('competencia')=='esp'){
								$trayectoria=new Trayectoriasespecializantesmaterias();
								$trayectoria->materias_id=$materia->id;
								$trayectoria->trayectoriaespecializante_id=$this->post('tipo_competencia');
								$trayectoria->save();
							}else if($this->post('competencia')=='gen'){
								$generica=new Competenciasgenericasmaterias();
								$generica->materias_id=$materia->id;
								$generica->competenciagenerica_id=$this->post('tipo_competencia');
								$generica->save();
							}
						}

					} else {
						$this->option = 'error';
						$this->error .= ' Error al guardar en la BD.' . $materia->show_message();
					}
				}
			} else {
				$this->option = 'error';
				$this->error = ' La agenda no permite agregar materias.';
			}
		} else {
			$this->option = 'error';
			$this->error = ' El ciclo esta cerrado.';
		}
	}

	public function oferta() {
		$Materias = new Materias();
		$this->ofertas = array ();
		foreach ($Materias as $materia) {
			$o = new Ofertasmaterias();
			$o->oferta_id = 1;
			$o->materias_id = $materia->id;
			$o->save();
			$this->ofertas[] = $o;
		}
	}

	public function disponible() {
		$this->set_response('view');
		$tabla = $this->post('tabla');
		$campo = $this->post('campo');
		$this->valor = $valor = $this->post('valor');
		$this->invalido = false;
		$this->disponible = false;
		if ($valor != '') {
			$registros = new $tabla ();
			if ($registros->count($campo . " = '" . $valor . "'") == 0) {
				$this->disponible = true;
			}
		}
	}

	public function editar($id) {
		$ciclo=new Ciclos();
		$ciclo=$ciclo->find(Session :: get_data('ciclo.id'));
		if($ciclo->abierto()){

			$ciclo_id = Session :: get_data('ciclo.id');
			$Ciclos = new Ciclos();
			$this->ciclo = $Ciclos->find($ciclo_id);
			$sigCiclo = new Ciclos();
			$sigCiclo = $sigCiclo->find_first("numero='" . $this->ciclo->anterior() . "'");
			$agenda = new Agenda();
			if ($agenda->eventoValido('PLN-GEN', Session :: get_data('ciclo.id'), $this->controlador, $this->accion, Session :: get_data('usr.login')) || $agenda->eventoValido('PLN-GEN-ESP', Session :: get_data('ciclo.id'), $this->controlador, $this->accion, Session :: get_data('usr.login')) || ($sigCiclo->id != '' && ($agenda->eventoValido('PLN-GEN', $sigCiclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login')) || $agenda->eventoValido('PLN-GEN-ESP', $sigCiclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login'))))) {

				if ($id != '') {
					$oferta = new Oferta();
					$this->ofertas = $oferta->todas();
					$this->option = 'captura';
					$id = intval($id, 10);
					$materias = new Materias();
					$this->materia = $materias->find($id);

					if ($this->materia->id == '') {
						$this->option = 'error';
						$this->error = ' El id de la materia no existe.';
					}else{
						$this->ofertasMaterias = new Ofertasmaterias();
						$this->ofertasMaterias->find_first('materias_id=' . $this->materia->id);

						if($this->materia->competencia=='esp'){
							$trayectoria=new Trayectoriasespecializantesmaterias();
							$trayectoria=$trayectoria->find_first("materias_id=".$this->materia->id);

							$tray=new Trayectoriaespecializante();
							$tray=$tray->find($trayectoria->trayectoriaespecializante_id);
							$this->tp_competencia=$tray->nombre;
						}elseif($this->materia->competencia=='gen'){
							$generica=new Competenciasgenericasmaterias();
							$generica=$generica->find_first("materias_id=".$this->materia->id);

							$com=new Competenciagenerica();
							$com=$com->find($generica->competenciagenerica_id);
							$this->tp_competencia=$com->nombre;
						}

					}
				} else
				if ($this->post('id') != '') {
					$this->option = '';
					$this->error = '';
					$materia = new Materias();
					$materia = $materia->find($this->post('id'));
					if ($materia->id != '') {
						$materia->numero = $this->post('numero');
						$materia->nombre = $this->post('nombre');
						$materia->descripcion = $this->post('descripcion');
						$materia->semestre = $this->post('semestre');
						$materia->tipo = $this->post('tipo');

						if ($materia->save()) {
							$this->option = 'exito';
							//$ofertasMaterias = new Ofertasmaterias();
							//$ofertasMaterias = $ofertasMaterias->find_first('materias_id=' . $materia->id);
							//$ofertasMaterias->oferta_id = $this->post('oferta');
							//$ofertasMaterias->save();

						} else {
							$this->option = 'error';
							$this->error .= ' Error al guardar en la BD.';
						}
					} else {
						$this->option = 'error';
						$this->error = ' La materia no existe.';
					}
				} else {
					$this->option = 'error';
					$this->error = ' La materia no existe.';
				}
			} else {
				$this->option = 'error';
				$this->error = ' La agenda no permite editar materias.';
			}
		} else {
			$this->option = 'error';
			$this->error = ' El ciclo esta cerrado.';
		}
	}

	public function eliminar($id = '') {
		$ciclo=new Ciclos();
		$ciclo=$ciclo->find(Session :: get_data('ciclo.id'));
		if($ciclo->abierto()){

			$ciclo_id = Session :: get_data('ciclo.id');
			$Ciclos = new Ciclos();
			$this->ciclo = $Ciclos->find($ciclo_id);
			$sigCiclo = new Ciclos();
			$sigCiclo = $sigCiclo->find_first("numero='" . $this->ciclo->anterior() . "'");
			$agenda = new Agenda();
			if ($agenda->eventoValido('PLN-GEN', Session :: get_data('ciclo.id'), $this->controlador, $this->accion, Session :: get_data('usr.login')) || $agenda->eventoValido('PLN-GEN-ESP', Session :: get_data('ciclo.id'), $this->controlador, $this->accion, Session :: get_data('usr.login')) || ($sigCiclo->id != '' && ($agenda->eventoValido('PLN-GEN', $sigCiclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login')) || $agenda->eventoValido('PLN-GEN-ESP', $sigCiclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login'))))) {

				if ($id != '') {
					$this->option = 'captura';
					$id = intval($id, 10);
					$materias = new Materias();
					$this->materia = $materias->find($id);
					if ($this->materia->id == '') {
						$this->option = 'error';
						$this->error = ' El c&oacute;digo de materia no existe.';
					}
				} else
				if ($this->post('id') != '') {
					$this->option = '';
					$this->error = '';
					$materias = new Materias();
					$materia = $materias->find($this->post('id'));
					if ($materia->id != '') {
						// eliminado la materia
						try{
							if ($materias->delete($this->post('id'))) {

				    if($materia->competencia=='esp'){
				    	$trayectoria=new Trayectoriasespecializantesmaterias();
				    	$trayectoria->delete("materias_id=".$materia->id);

				    }elseif($materia->competencia=='gen'){
				    	$generica=new Competenciasgenericasmaterias();
				    	$generica->delete("materias_id=".$materia->id);

				    }

				    $this->option = 'exito';
				    $series = new Prerrequisitos();

				    if (!$series->delete("materia = '" . $materia->id . "'")) {
				    	$this->option = 'error';
				    	$this->error .= ' Error al intentar eliminar los prerrequisitos de la materia en la BD.';
				    }

							} else {
								$this->option = 'error';
								$this->error .= ' Error al intentar eliminar de la BD.';
							}
						} catch (Exception $e) {
							$this->option = 'error';
							$this->error .= ' La materia esta ligada a un curso.';
						}
					} else {
						$this->option = 'error';
						$this->error = ' El c&oacute;digo de materia no existe.';
					}
				} else {
					$this->option = 'error';
					$this->error = ' No se especific&oacute; la materia a eliminar.';
				}
			} else {
				$this->option = 'error';
				$this->error = ' La agenda no permite eliminar materias.';
			}
		} else {
			$this->option = 'error';
			$this->error = ' El ciclo esta cerrado.';
		}
	}

	public function enlazar($id = '') {
		$ciclo=new Ciclos();
		$ciclo=$ciclo->find(Session :: get_data('ciclo.id'));
		if($ciclo->abierto()){

			$ciclo_id = Session :: get_data('ciclo.id');
			$Ciclos = new Ciclos();
			$this->ciclo = $Ciclos->find($ciclo_id);
			$sigCiclo = new Ciclos();
			$sigCiclo = $sigCiclo->find_first("numero='" . $this->ciclo->anterior() . "'");
			$agenda = new Agenda();
			if (true || $agenda->eventoValido('PLN-GEN', Session :: get_data('ciclo.id'), $this->controlador, $this->accion, Session :: get_data('usr.login')) || $agenda->eventoValido('PLN-GEN-ESP', Session :: get_data('ciclo.id'), $this->controlador, $this->accion, Session :: get_data('usr.login')) || ($sigCiclo->id != '' && ($agenda->eventoValido('PLN-GEN', $sigCiclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login')) || $agenda->eventoValido('PLN-GEN-ESP', $sigCiclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login'))))) {

				if ($id != '') {
					$this->option = 'captura';
					$id = intval($id, 10);
					$materias = new Materias();
					$this->materia = $materias->find($id);
					if ($this->materia->id == '') {
						$this->option = 'error';
						$this->error = ' El id de la materia no existe.';
					}
				} else
				if ($this->post('id') != '') {
					$this->option = '';
					$this->error = '';
					$materia = new Materias();
					$materia = $materia->find($this->post('id'));
					if ($materia->id != '') {
						$series = new Prerrequisitos();
						$series->delete("materia = '" . $materia->id . "'");
						$series = array_unique($this->post('series'));
						$this->option = 'exito';
						foreach ($series as $s) {
							if ($s != '' && intval($s)) {
								$serie = new Prerrequisitos();
								$serie->materia = $materia->id;
								$serie->requisito = $s;
								if (!$serie->save()) {
									$this->option = 'error';
									$this->error .= ' Error al guardar el requisito en la BD.';
								}
							}
						}
					} else {
						$this->option = 'error';
						$this->error = ' La materia no existe.';
					}
				} else {
					$this->option = 'error';
					$this->error = ' La materia no existe.';
				}
			} else {
				$this->option = 'error';
				$this->error = ' La agenda no permite enlazar materias.';
			}
		} else {
			$this->option = 'error';
			$this->error = ' El ciclo esta cerrado.';
		}
	}

	public function exportar($grp_id = '') {
		$this->set_response("view");
		require ('app/reportes/xls.materias.php');
		$ciclo_id = Session :: get_data('ciclo.id');
		$reporte = new XLSMaterias($ciclo_id);
		$reporte->generar();
	}

	public function index($pag = '') {
		$Materias = new Materias();
		$controlador = $this->controlador;
		$accion = $this->accion;
		$path = $this->path = KUMBIA_PATH;

		$this->ofertas = new Oferta();
		$this->ofertas = $this->ofertas->find();

		$usr_login = Session :: get_data('usr.login');
		$this->acl = array ();
		$acl = new gacl_extra();
		$acos_arr = array (
			'materias' => array (
				'agregar',
				'disponible',
				'editar',
				'eliminar',
				'enlazar',
				'exportar',
				'index',
				'series',
				'ver'
				)
				);
				$this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);
				$this->acl=$this->acl['materias'];

				// busqueda
				$b = new Busqueda($controlador, $accion);
				$b->campos();

				// genera las condiciones
				$c = $b->condicion(array (
			'oferta_id'
			));
			$this->busqueda = $b;

			// cuenta todos los registros
			//$this->registros = $Materias->count(($c == '' ? '' : $c));
			$this->registros = $Materias->count_by_sql("SELECT " .
		"COUNT(*) " .
		"FROM " .
		"(	SELECT materias.* FROM
									materias
									Inner Join ofertasmaterias ON ofertasmaterias.materias_id=materias.id
									 " .
		"WHERE " .
		 ($c == '' ? '1 ' : $c) .
		")AS t1");
		 // paginacion
		 $paginador = new Paginador($controlador, $accion);
		 if ($pag != '') {
		 	$paginador->guardarPagina($pag);
		 }
		 $paginador->estableceRegistros($this->registros);
		 $paginador->generar();
		 $this->paginador = $paginador;

		 // ejecuta la consulta
		 /*$this->materias = $Materias->find(
		  'conditions: ' . ($c == "" ? "1" : $c),
		  'order: semestre, tipo, nombre',
		  'limit: ' . ($paginador->pagina() * $paginador->rpp()) . ', '
		  . $paginador->rpp()
		  );*/
		 $this->materias = $Materias->find_all_by_sql("SELECT materias.*,ofertasmaterias.id as oferta_id FROM
									materias
									Inner Join ofertasmaterias ON ofertasmaterias.materias_id=materias.id
									 " .
		"WHERE " .
		 ($c == '' ? '1 ' : $c) .
		"ORDER BY " .
		"materias.semestre,materias.tipo,materias.nombre " .
		"LIMIT " .
		 ($paginador->pagina() * $paginador->rpp()) . ', ' . $paginador->rpp());
	}

	public function series() {
		$this->id = $id = $this->post('id');
		$this->set_response('view');
		$materias = new Materias();
		$materia = $materias->find($id);
		$ofertasMaterias=new Ofertasmaterias();
		$ofertasMaterias=$ofertasMaterias->find_first("materias_id=".$materia->id);
		$this->materias = $materias->find_all_by_sql(
			"SELECT materias.* " .
			" FROM materias " .
			" INNER JOIN " .
			" ofertasmaterias ON materias.id=ofertasmaterias.materias_id" .
			" WHERE materias.semestre < '" . $materia->semestre . "' AND materias.id != '" . $id . "' AND ofertasmaterias.oferta_id='".$ofertasMaterias->oferta_id."'" .
			" ORDER BY semestre, tipo, nombre");
	}

	public function ver($id = '') {
		$id = intval($id, 10);
		$Materias = new Materias();
		$materia = $Materias->find($id);
		$this->materia = $materia;


		$usr_login = Session :: get_data('usr.login');
		$this->acl = array ();
		$acl = new gacl_extra();
		$acos_arr = array (
			'materias' => array (
				'agregar',
				'disponible',
				'editar',
				'eliminar',
				'enlazar',
				'exportar',
				'index',
				'series',
				'ver'
				)
				);
				$this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);
				$this->acl=$this->acl['materias'];

				if($this->materia->competencia=='esp'){
					$trayectoria=new Trayectoriasespecializantesmaterias();
					$trayectoria=$trayectoria->find_first("materias_id=".$this->materia->id);

					$tray=new Trayectoriaespecializante();
					$tray=$tray->find($trayectoria->trayectoriaespecializante_id);
					$this->tp_competencia=$tray->nombre;
				}elseif($this->materia->competencia=='gen'){
					$generica=new Competenciasgenericasmaterias();
					$generica=$generica->find_first("materias_id=".$this->materia->id);

					$com=new Competenciagenerica();
					$com=$com->find($generica->competenciagenerica_id);
					$this->tp_competencia=$com->nombre;
				}

	}

}
?>