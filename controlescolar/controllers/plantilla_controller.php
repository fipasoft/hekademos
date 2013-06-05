<?php
// sp5, Creado el 18/06/2010
/**
 * Plantilla
 * 
 * @package    Controladores
 * @author     mimeks <mimex@fipasoft.com.mx>
 * @copyright  2010 FiPa Software (contacto at fipasoft.com.mx)
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL)
 *
 */

Kumbia :: import('app.componentes.*');
Kumbia :: import('lib.kumbia.utils');
Kumbia :: import('lib.upload.main');
Kumbia :: import('lib.excel.main');

 class PlantillaController extends ApplicationController{
 	
	public $template = "system";
	 	
	
	public function exportar(){
		
		$this->set_response( 'view' );
		require ( 'app/reportes/xls.prerregistro.php' );
		$ciclo_id = Session :: get_data( 'ciclo.id' );
		$reporte = new XLSPlantilla( $ciclo_id );
		$reporte->generar();
		
	}
	
	
	public function index(){
		
		
		
	}
	
	public function inicializar(){
		
		try{
			
			mysql_query("BEGIN") or die("PLN_PRE_1");
			
			/*
			 * Obtiene los ciclos actual y siguiente
			 */
			$ciclo = new Ciclos();
			$ciclo = $ciclo->find( Session :: get_data( 'ciclo.id' ) );
			
			if( $ciclo->id == '' ){
				
				$errvar = $ciclo;
				throw new Exception( 'No se pudo obtener el ciclo actual.' );
				
			}
			
			$cicloSig = new Ciclos();
			$cicloSig = $cicloSig->find( 
				"conditions: numero = '" . $ciclo->siguiente() . "' "
			);
			$cicloSig = $cicloSig[ 0 ];
			
			if( strcmp( $cicloSig->id, '' ) == 0 ){
				
				$errvar = $cicloSig;
				throw new Exception( 'No se pudo obtener el ciclo que sigue a ' . $ciclo->numero . '.' );
				
			}
			
			/*
			 * Obtiene los cursos del ciclo actual
			 */
			$cursos = new Cursos();
			$cursos = $cursos->find_all_by_sql(
				"SELECT " .
					"cursos.id, " .
					"cursos.materias_id, ".
					"cursos.grupos_id, ".
					"cursos.profesores_id " .
				"FROM " .
					"cursos " .
					"Inner Join grupos ON cursos.grupos_id = grupos.id " .
				"WHERE " . 
					"grupos.ciclos_id = '" . $ciclo->id . "' "
			);
			
			if( count( $cursos ) <= 0 ){
				
				$errvar = $cursos;
				throw new Exception( 'No hay informacion de cursos para el ciclo ' . $ciclo->clave . '.' );
				
			}
			
			/*
			 * Para cada curso del ciclo actual crea un prerregistro en el ciclo siguiente
			 */
			$count = 0;
			$sinr  = 0;
			$exst  = 0;
			
			foreach( $cursos as $c ){
				
				$g = $c->grupo();
				
				// obtiene el mismo curso pero del ciclo siguiente
				$precurso = new Cursos();
				$precurso = $precurso->find_all_by_sql(
					"SELECT " .
						"cursos.id, " .
						"cursos.materias_id, ".
						"cursos.grupos_id, ".
						"cursos.profesores_id " .
					"FROM " .
						"cursos " .
						"Inner Join grupos ON cursos.grupos_id = grupos.id " .
					"WHERE " . 
						"cursos.materias_id = '" . $c->materias_id . "' " .
						"AND grupos.oferta_id = '" . $g->oferta_id . "' " .
						"AND grupos.grado = '" . $g->grado . "' " .
						"AND grupos.letra = '" . $g->letra . "' " .
						"AND grupos.turno = '" . $g->turno . "' " .
						"AND cursos.profesores_id = '" . $c->profesores_id . "' " .
						"AND grupos.ciclos_id = '" . $cicloSig->id . "' "
				
				);
				$precurso = $precurso[ 0 ];
				
				if( $precurso->id != '' ){
					
					$pre = new Prerregistro();
					$pre->profesores_id  =  $precurso->profesores_id;
					$pre->cursos_id      =  $precurso->id;
					
					$existentes = new Prerregistro();
					if( 
						$existentes->count(
							"profesores_id = '" . $precurso->profesores_id . "' " .
							"AND cursos_id = '" . $precurso->id . "' "
						) > 0
					){
						
						$exst ++;
						
					}else{
					
						if( !$pre->save() ){
							
							$errvar = $pre;
							throw new Exception( 'No se pudo guardar el prerregistro.' );
							
						}
						$count ++;
						
					}
					
				}else{

					$sinr++;
					$sinReemplazo[ $c->verGrupo() ][] = $c->verMateria() . ' ' . $c->verProfesor();
					
				}
				
				
			}
			
			
			/*
			 * Se guardan los resultados en el LOG
			 */
			var_dump( $sinReemplazo );
			$sin_reemplazo  = ob_get_contents();
			
			$log = new Logger( 'plantilla.inicializar' );
			$log->log(
				Session :: get_data( 'usr.login' ) . " " .
				Utils :: getRealIP() .
				" plantilla/inicializar " .
				'Prerregistro para el ciclo ' . $cicloSig->numero . '; ' .
				$count . ' cursos prerregistrados, ' .
				$exst . ' existentes y ' .
				$sinr . ' sin reemplazo ' .
				( count( $sinReemplazo ) > 0 ? "\n" . $sin_reemplazo : '' )
				,
				Logger :: WARNING
			);
			$log->close();
			
			mysql_query("COMMIT") or die("PLN_PRE_2");
			
			$this->exito( 
				$count . ' cursos prerregistrados, ' .
				$exst . ' se mantienen igual y ' .
				$sinr . ' no tienen reemplazo.'				  
			);
			
		}catch( Exception $e ){
			
			mysql_query("ROLLBACK") or die("PLN_PRE_3");
			$this->error( $e->getMessage(), $errvar, $e );
			
		}
		
	}
	
	
	
 	public function materias(){
			try{
			$materias = $this->post("materias");
			$horas = $this->post("horas");
			if(is_array($materias) && is_array($horas)){
				mysql_query("BEGIN") or die("ACADEMIAS");
				foreach($materias as $mid){
					$materia = new Materias();
					$materia = $materia->find($mid);
					if($materia->id==""){
						throw new Exception( 'Datos invalidos materias');
					}
						
					$hora = $horas[$materia->id];
					if($hora!=""){
							
						$materiahrs = new Materiahrs();
						$materiahrs = $materiahrs->find_first("materias_id='".$materia->id."'");
						
						if($materiahrs->id==""){
							$materiahrs = new Materiahrs();
							$materiahrs->materias_id = $materia->id;
						}
							
						$materiahrs->horas = $hora;
							
						if(!$materiahrs->save()){
							throw new Exception( 'Error al guardar');
						}
					}else{
						$materiahrs = new Materiahrs();
						$materiahrs = $materiahrs->find_first("materias_id='".$materia->id."'");
						if($materiahrs->id!=""){
							if(!$materiahrs->delete()){
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
			
				$this->option = "captura";
			}
		}catch( Exception $e ){
			$this->option = "error";
			$this->error = $e->getMessage();
		}
	}
	
	
 	public function prerregistro( $profesores_id = '' ){
		
 		try{
 			
 			$this->error = '';
 			$this->modo( 'captura' );
 			
 			/*
 			 * Modo captura
 			 */
 			if( $this->post( 'profesores_id' ) == ''){
 				
	 			if( $profesores_id == '' ){
	 				
	 				throw new Exception( 'No se especific� el id del profesor.' );
	 				
	 			}
 				
	 			/*
	 			 * Obtiene los datos del profesor
	 			 */
	 			$profesor   =  new Profesores();
	 			$profesor   =  $profesor->find_all_by_sql(
					"SELECT " .
						"profesores.id, " .
						"profesores.codigo, " .
						"profesores.nombre, " .
						"profesores.ap, " .
						"profesores.am, " .
						"profesores.foto, " .
	 					"profesores.rfc, " .
	 					"(SELECT hasignadas FROM contratoinfo WHERE profesores.id = contratoinfo.profesores_id ) AS hAsign, " .
	 					"profesores.curp " .
					"FROM " .
						"profesores " .
						"Left Join ( "  .
							"prerregistro " .
							"Inner Join cursos ON cursos.id = prerregistro.cursos_id " .
							"Inner Join materiahrs ON materiahrs.materias_id = cursos.materias_id " .
						") " .
						"ON prerregistro.profesores_id = profesores.id " .
					"WHERE " .
						 "profesores.id = '" . $profesores_id . "' " .
					"GROUP BY " .
						 "profesores.id "
				);
				
				$profesor = $profesor[ 0 ];
				
 				if( $profesor->id == '' ){
					
					throw new Exception( 'No se pudo obtener la informaci�n del profesor.' );
					
				}
				
	 			/*
				 * Obtiene los ciclos actual y siguiente
				 */
				$ciclo = new Ciclos();
				$ciclo = $ciclo->find( Session :: get_data( 'ciclo.id' ) );
				
				if( $ciclo->id == '' ){
					
					$errvar = $ciclo;
					throw new Exception( 'No se pudo obtener el ciclo actual.' );
					
				}
				
				$cicloSig = new Ciclos();
				$cicloSig = $cicloSig->find( 
					"conditions: numero = '" . $ciclo->siguiente() . "' "
				);
				$cicloSig = $cicloSig[ 0 ];
				
				if( strcmp( $cicloSig->id, '' ) == 0 ){
					
					$errvar = $cicloSig;
					throw new Exception( 'No se pudo obtener el ciclo que sigue a ' . $ciclo->numero . '.' );
					
				}
				
				/*
				 * Obtiene los datos de los cursos disponibles
				 */
				$cursos = new Cursos();
				$cursos = $cursos->find_all_by_sql(
					"SELECT " .
						"cursos.id, " .
						"cursos.materias_id, ".
						"cursos.grupos_id, ".
						"cursos.profesores_id, " .
						"materiahrs.horas " .
					"FROM " .
						"cursos " .
						"Inner Join grupos ON cursos.grupos_id = grupos.id " .
						"Left Join materiahrs ON materiahrs.materias_id = cursos.materias_id " .
						"Left Join prerregistro ON cursos.id = prerregistro.cursos_id " .
					"WHERE " . 
						"grupos.ciclos_id = '" . $cicloSig->id . "' " .
						"AND ( " . 
							"prerregistro.id IS NULL " . 
							"OR  prerregistro.profesores_id = '" .$profesor->id . "'  " . 
						")" . 
					"ORDER BY " . 
						"grupos.turno, grupos.grado, grupos.letra "
				);
				
				/*
				 * Obtiene los datos de los cursos asignados al profesor
				 */
				$asignados = new Cursos();
				$asignados = $asignados->find_all_by_sql(
					"SELECT " .
						"cursos.id, " .
						"cursos.materias_id, ".
						"cursos.grupos_id, ".
						"cursos.profesores_id, " .
						"materiahrs.horas " .
					"FROM " .
						"cursos " .
						"Inner Join grupos ON cursos.grupos_id = grupos.id " .
						"Inner Join prerregistro ON cursos.id = prerregistro.cursos_id " .
						"Left Join materiahrs ON materiahrs.materias_id = cursos.materias_id " .
					"WHERE " . 
						"grupos.ciclos_id = '" . $cicloSig->id . "' " .
						"AND prerregistro.profesores_id = '" . $profesor->id . "' " .
					"ORDER BY " .
						"grupos.turno, grupos.grado, grupos.letra "
				);
				
				/*
				 * Salida
				 */
				$this->asignados  =  $asignados;
				$this->cursos     =  $cursos;
				$this->profesor   =  $profesor;
 				
 			}
 			
 			/*
 			 * Modo edici�n
 			 */
 			
 			else{
 				
 				/*
 				 * Entrada
 				 */
 				mysql_query( "BEGIN" ) or die( "PLN_PRE_1" );
 				
 				$cursos = ( $this->post( 'cursos' ) ? $this->post( 'cursos' ) : array() );
 				$profesores_id = $this->post( 'profesores_id' );
 				
 				
 				/*
 				 * Obtiene los datos del profesor
 				 */
 				$profesor   =  new Profesores();
	 			$profesor   =  $profesor->find( $profesores_id );
				
 				if( !$profesor || is_array( $profesor ) || $profesor->id == '' ){
					
					throw new Exception( 'No se pudo obtener la informaci�n del profesor.' );
					
				}
				
				$this->profesor = $profesor;
				
 				/*
 				 * Elimina los registros anteriores
 				 */
 				$anteriores = new Prerregistro();
 				if( !$anteriores->delete( "profesores_id = '" . $profesor->id . "'" ) ){
 					
 					$errvar = $anteriores;
 					throw new Exception( 'No se pudo eliminar el prerregistro anterior' );
 					
 				}
 				
 				
	 			$this->exito( 'La informacion se guardo de forma correcta.' );
	 			
 				/*
 				 * Guarda el nuevo registro
 				 */
 				foreach( $cursos as $id ){
 					
 					if( $id != '' ){
 						
	 					$curso = new Cursos();
	 					$curso = $curso->find( $id );
		 				if( !$curso || is_array( $curso ) || $curso->id == '' ){
							
							throw new Exception( 'No se pudo obtener la informaci�n del curso.' );
							
						}
	 					
	 					$existente = new Prerregistro();
	 					if( $existente->count( "cursos_id = '" . $curso->id . "'" ) == 0 ){
	 						
		 					$pre                  =  new Prerregistro();
		 					$pre->cursos_id       =  $curso->id;
		 					$pre->profesores_id   =  $profesor->id;
		 					if( !$pre->save() ){
		 						
		 						$errvar = $pre;
		 						throw new Exception( 'No se pudo guardar el nuevo prerregistro' );
		 						
		 					}
		 					
	 					}else{
	 					
	 						$this->option  = 'error';
	 						$this->error .= 
	 							'El curso ' . $curso->verGrupo() . ' ' . $curso->verMateriaNombre() . 
	 							' no se guardo debido a que ya fue prerregistrado <br />';
		 					
	 					}
	 					
 					}
 					
 				}
 				
 				/*
 				 * Verifica que la carga horaria no se exceda
 				 */				
 				$profesor   =  $profesor->find_all_by_sql(
					"SELECT " .
						"profesores.id AS id, " . 
						"(SELECT hasignadas FROM contratoinfo WHERE profesores.id = contratoinfo.profesores_id ) AS hAsign, " . 
						"SUM( materiahrs.horas ) AS hPre " . 
					"FROM " . 
						"profesores " .
 						"Left Join prerregistro ON profesores.id = prerregistro.profesores_id " .
						"Left Join cursos ON cursos.id = prerregistro.cursos_id " .
						"Left Join grupos ON cursos.grupos_id = grupos.id " . 
						"Left Join materiahrs ON materiahrs.materias_id = cursos.materias_id " . 
					"WHERE " . 
						"profesores.id = '" . $profesor->id . "' " . 
					"GROUP BY " . 
						"profesores.id " 
				);
				
				$profesor = $profesor[ 0 ];
				
 				if( $profesor->id == '' ){
					
					throw new Exception( 'No se pudo obtener la informaci�n para calcular la carga horaria del profesor.' );
					
				}
				
				if( ( $profesor->hAsign - $profesor->hPre ) < 0 ){
					
					throw new Exception( 'No se debe exceder la carga horaria del profesor.' );
					
				}
				
				mysql_query( "COMMIT" ) or die( "PLN_PRE_2" );
 				
 			}
 			
 			
 		}catch( Exception $e ){
 			
 			mysql_query( "ROLLBACK" ) or die( "PLN_PRE_3" );
 			$this->error( $e->getMessage(), $errvar, $e );
 			
 		}
 		
	}
	
	public function profesores( $pag = '' ){
		
		$profesores = new Profesores();
		$controlador = $this->controlador;
		$accion = $this->accion;
		$path = $this->path = KUMBIA_PATH;

		// busqueda
		$b = new Busqueda($controlador, $accion);

		// genera las condiciones
		$b->establecerCondicion(
			'nombre',
			"CONCAT(nombre, ' ', ap, ' ', am) LIKE '%" . $b->campo('nombre') . "%' "
		);

		$c = $b->condicion();
		$this->busqueda = $b;

		// cuenta todos los registros
		$this->registros = $profesores->count(($c == '' ? '' : $c));

		// paginacion
		$paginador = new Paginador($controlador, $accion);
		if($pag != ''){
			$paginador->guardarPagina($pag);
		}
		$paginador->estableceRegistros($this->registros);
		$paginador->generar();
		$this->paginador = $paginador;

		// ejecuta la consulta
		$pros = $profesores->find_all_by_sql(
			"SELECT " .
				"profesores.id, " .
				"profesores.codigo, " .
				"profesores.nombre, " .
				"profesores.ap, " .
				"profesores.am, " .
				"profesores.foto, " .
				"(SELECT hasignadas FROM contratoinfo WHERE profesores.id = contratoinfo.profesores_id ) AS hAsign, " .
				"SUM( materiahrs.horas ) AS hPre, " .
				"COUNT(prerregistro.cursos_id) AS nCursos " .
			"FROM " .
				"profesores " .
				"Left Join ( "  .
					"prerregistro " .
					"Inner Join cursos ON cursos.id = prerregistro.cursos_id " .
					"Left Join materiahrs ON materiahrs.materias_id = cursos.materias_id " .
				") " .
				"ON prerregistro.profesores_id = profesores.id " .
			"WHERE " .
				 ($c == "" ? "1" : $c) . " " .
			"GROUP BY " .
				 "profesores.id " .
			"ORDER BY " . 
				"ap, am, nombre " .
			"LIMIT " . 
				 ($paginador->pagina() * $paginador->rpp()) . ', ' . 
				 $paginador->rpp()
		);
		$this->profesores = array();
		foreach($pros as $p){
			$p->foto = KUMBIA_PATH . 'img/' . ($p->foto == '' ? 'sp5/persona.png' : 'profesores/'.$p->foto .'?d=' . time());
			$this->profesores[] = $p;
		}
		
	}
	
	public function profesoreshoras($grp_id = ''){
		$this->set_response("view");
		require('app/reportes/xls.profesoreshoras.php');
		$ciclo_id = Session :: get_data('ciclo.id');
		$reporte = new XLSProfesoreshoras($ciclo_id);
		$reporte->generar();
 	}
	
 }
 ?>