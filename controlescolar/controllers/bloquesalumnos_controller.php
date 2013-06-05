<?php
 /**
 * bloquesalumnos_controller.php
 *
 * Created on 13/05/2009
 * @package  Controladores
 * @author     J Jonathan Lopez <jlopez@fipasoft.com.mx>
 * @copyright  2009 FiPa Software (contacto at fipasoft.com.mx)
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL)
 *
 */

 Kumbia :: import('app.componentes.*');
Kumbia :: import('lib.kumbia.utils');

 class BloquesalumnosController extends ApplicationController {
	public $template = "system";

	public function agregar($id=''){
		if($id!=''){
			$id=intval($id,10);
			$bloque=new Bloque();
			$bloque=$bloque->find($id);
			if($bloque->id!=''){
				$this->bloque=$bloque;

				$periodoalumnos=new Alumnos();
				$periodoalumnos=$periodoalumnos->find_all_by_sql(
							"SELECT periodosalumnos.id AS periodosalumnos_id ,alumnos.* FROM ".
							" periodosalumnos " .
							" INNER JOIN alumnos ON periodosalumnos.alumnos_id=alumnos.id ".
							" WHERE periodosalumnos.periodo_id='".$bloque->periodo_id."'");

				$bloquesalumnos=new Bloquesalumnos();
				$bloquesalumnos=$bloquesalumnos->find_all_by_sql(
							"SELECT bloquesalumnos.* FROM " .
							" bloque " .
							" INNER JOIN bloquesalumnos ON bloquesalumnos.bloque_id=bloque.id " .
							" WHERE bloque.periodo_id='".$bloque->periodo_id."'"
				);

				$alumnosconbloque=array();
				foreach($bloquesalumnos as $ba){
				$alumnosconbloque[]=$ba->periodosalumnos_id;
				}

				$sinbloque=array();
				foreach($periodoalumnos as $pa){
					if(!in_array($pa->periodosalumnos_id,$alumnosconbloque)){
						$sinbloque[]=$pa;
					}
				}
				$this->registros=count($sinbloque);
				$this->sinbloque=$sinbloque;
				$this->periodo=new Periodo();
				$this->periodo=$this->periodo->find($bloque->periodo_id);

				$ciclo=new Ciclos();
				$ciclo=$ciclo->find($this->periodo->ciclos_id);
				$this->ciclo=$ciclo;

				$this->option="captura";
			}else{
			$this->option = 'error';
			$this->error = ' El bloque no existe.';
			}

		}elseif($this->post('bloque_id')!=''){
			$id=intval($this->post('bloque_id'),10);
			$bloque=new Bloque();
			$bloque=$bloque->find($id);
			if($bloque->id!=''){
				$this->bloque=$bloque;
				$alumnos=$this->post('alumnos');

				if(is_array($alumnos)){
					$err=false;
					mysql_query("BEGIN") or die("AGR_1");
					foreach($alumnos as $a){
						//Revisar que el alumno no este inscrito en algun bloque del periodo
						$bloquealumno=new Bloquesalumnos();
						if(!$bloquealumno->enBloque($bloque->periodo_id,$a)){
						$bloquealumno->find_first("bloque_id='".$bloque->id."' AND periodosalumnos_id='".$a."' ");
						if($bloquealumno->id==''){
						$bloquealumno->bloque_id=$bloque->id;
						$bloquealumno->periodosalumnos_id=$a;
						if(!$bloquealumno->save()){
							$this->option = 'error';
							$this->error = ' Ocurrio un erro en la BD al agregar.';
							$err=true;
							mysql_query("ROLLBACK") or die("AGR_2");
						}
						}
						}else{
							$this->option = 'error';
							$this->error = ' Uno de los alumnos ya se encuentra inscrito en un bloque.';
							mysql_query("ROLLBACK") or die("AGR_2");
							return;
						}

					}

					if(!$err){
						$this->option="exito";
						mysql_query("COMMIT") or die("AGR_3");
							$this->option="exito";
							$tt=count($alumnos);
							$historial=new Historial();
							$historial->ciclos_id= Session :: get_data('ciclo.id');
							$historial->usuario=Session :: get_data('usr.login');
							$historial->descripcion='Se agreg'.($tt!=1 ? 'arón' : 'o').' '.$tt.' alumno'.($tt!=1 ? 's' : '').' al bloque'.$bloquealumno->bloque_id;
							$historial->controlador=$this->controlador;
							$historial->accion=$this->accion;
							$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
							$historial->save();

					}
				}else{
					$this->option = 'error';
					$this->error = ' Los datos no son validos.';
				}

			}else{
			$this->option = 'error';
			$this->error = ' El bloque no existe.';
			}
		}else{
			$this->option = 'error';
			$this->error = ' No tiene permiso para ver la pagina.';

		}
	}


 	public function cambio($id){
 		if($id!=""){
			$this->option = 'captura';
			$id = intval($id, 10);
			$bloquealumno=new Bloquesalumnos();
 			$bloquealumno=$bloquealumno->find($id);
 			if($bloquealumno->id!=""){
 			$periodoalumno_id=$bloquealumno->periodosalumnos_id;
 			$periodoalumno=new Periodosalumnos();
			$periodoalumno=$periodoalumno->find($periodoalumno_id);
			if($periodoalumno->id!=""){
				$alumno=new Alumnos();
				$alumno=$alumno->find($periodoalumno->alumnos_id);
				if($alumno->id!=""){
					$this->alumno=$alumno;
					$this->bloquealumno=$bloquealumno;
					$this->periodoalumno=$periodoalumno;

					$this->bloques=new Bloque();
					$this->bloques=$this->bloques->find("periodo_id='".$periodoalumno->periodo_id."' AND id!='".$bloquealumno->bloque_id."'");

					$periodo=new Periodo();
					$periodo=$periodo->find($periodoalumno->periodo_id);

					$ciclo=new Ciclos();
					$ciclo=$ciclo->find($periodo->ciclos_id);
					$this->ciclo=$ciclo;

					$bloque=new Bloque();
					$bloque=$bloque->find($bloquealumno->bloque_id);

					$this->bloque=$bloque;

				}else{
					$this->option = 'error';
					$this->error = ' El alumno no existe.';
				}
			}else{
			$this->option = 'error';
			$this->error = ' El alumno no existe en el bloque.';
			}

 			}else{
				$this->option = 'error';
				$this->error = ' El alumno no existe en el bloque.';
			}

 		}elseif($this->post("id")!="" && $this->post("bloque_id")!=""){
 			$id=intval($this->post("id"),10);
 			$bloquealumno=new Bloquesalumnos();
 			$bloquealumno=$bloquealumno->find($id);
 			if($bloquealumno->id!=""){
				$bloque=new Bloque();
				$bloque=$bloque->find(intval($this->post("bloque_id")));
				if($bloque->id!=""){
 				if($bloquealumno->bloque_id!=$this->post("bloque_id")){
				$periodoalumno_id=$bloquealumno->periodosalumnos_id;
				$bloqueanterior=$bloquealumno->bloque_id;
				$periodoalumno=new Periodosalumnos();
				$periodoalumno=$periodoalumno->find($periodoalumno_id);
				$bloquealumno->bloque_id=intval($this->post("bloque_id"));
				if($bloquealumno->save()){
					$alumno=new Alumnos();
							$alumno=$alumno->find($periodoalumno->alumnos_id);
							$this->option="exito";
							$historial=new Historial();
							$historial->ciclos_id= Session :: get_data('ciclo.id');
							$historial->usuario=Session :: get_data('usr.login');
							$historial->descripcion='Se cambio el alumno '.$alumno->nombre().' con el codigo '.$alumno->codigo.'' .
													' del bloque'.$bloqueanterior." al bloque".$bloquealumno->bloque_id;
							$historial->controlador=$this->controlador;
							$historial->accion=$this->accion;
							$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
							$historial->save();
				}else{
					$this->option="error";
 					$this->error="Ocurrio un error al cambiar en la bd.";

				}

				$this->option="exito";
 				}else{
					$this->option="error";
 					$this->error="El alumno ya pertenece al bloque".$this->post("bloque_id");
				}

			}else{
					$this->option="error";
 					$this->error="El bloque no existe.";
				}
			}else{
					$this->option="error";
 					$this->error="Ocurrio un error al cambiar en la bd.";
				}


 			}else{
 			$this->option="error";
 			$this->error="No existe el alumno en el bloque.";
 		}

 	}


	public function eliminar($id=""){
 		if($id!=""){
			$this->option = 'captura';
			$id = intval($id, 10);
			$bloquealumno=new Bloquesalumnos();
 			$bloquealumno=$bloquealumno->find($id);
 			if($bloquealumno->id!=""){
 			$periodoalumno_id=$bloquealumno->periodosalumnos_id;
 			$periodoalumno=new Periodosalumnos();
			$periodoalumno=$periodoalumno->find($periodoalumno_id);
			if($periodoalumno->id!=""){
				$alumno=new Alumnos();
				$alumno=$alumno->find($periodoalumno->alumnos_id);
				if($alumno->id!=""){
					$this->alumno=$alumno;
					$this->bloquealumno=$bloquealumno;
					$this->periodoalumno=$periodoalumno;
					$this->periodo=new Periodo();
					$this->periodo=$this->periodo->find($periodoalumno->periodo_id);
					$ciclo=new Ciclos();
					$ciclo=$ciclo->find($this->periodo->ciclos_id);
					$this->ciclo=$ciclo;
				}else{
					$this->option = 'error';
					$this->error = ' El alumno no existe.';
				}
			}else{
			$this->option = 'error';
			$this->error = ' El alumno no existe en el bloque.';
			}

 			}else{
				$this->option = 'error';
				$this->error = ' El alumno no existe en el bloque.';
			}

 		}elseif($this->post("id")!=""){
 			$id=intval($this->post("id"),10);
 			$bloquealumno=new Bloquesalumnos();
 			$bloquealumno=$bloquealumno->find($id);
 			if($bloquealumno->id!=""){
				$periodoalumno_id=$bloquealumno->periodosalumnos_id;

				if($bloquealumno->delete()){
					$periodoalumno=new Periodosalumnos();
					$periodoalumno=$periodoalumno->find($periodoalumno_id);
					if($periodoalumno->id!=""){
							$alumno=new Alumnos();
							$alumno=$alumno->find($periodoalumno->alumnos_id);
							$this->option="exito";
							$historial=new Historial();
							$historial->ciclos_id= Session :: get_data('ciclo.id');
							$historial->usuario=Session :: get_data('usr.login');
							$historial->descripcion='Se elimino el alumno '.$alumno->nombre().' con el codigo '.$alumno->codigo.'' .
													' del bloque'.$bloquealumno->bloque_id;
							$historial->controlador=$this->controlador;
							$historial->accion=$this->accion;
							$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
							$historial->save();

					}else{
					$this->option="error";
 					$this->error="Ocurrio un error al eliminar en la bd.";

					}
				}else{
					$this->option="error";
 					$this->error="Ocurrio un error al eliminar en la bd.";

				}


 			}else{
 			$this->option="error";
 			$this->error="No existe el alumno en el bloque.";
 		}

 		}else{
 			$this->option="error";
 			$this->error="No existe el alumno en el bloque.";
 		}

 	}


	function index($id='',$pag=''){
		if($id!=""){
 		$controlador = $this->controlador;
		$accion = $this->accion;
 		$this->option="vista";
 		$bloque=new Bloque();
 		$id=intval($id,10);
 		$bloque=$bloque->find($id);
 		if($bloque->id!=""){
			// acl
			$usr_login = Session :: get_data('usr.login');
			$this->acl = array ();
			$acl = new gacl_extra();
			$acos_arr = array (
				'bloquesalumnos' => array (
					'agregar',
					'cambio',
					'eliminar'
					)
			);

			$this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);
	 		$alumnos=new Alumnos();
	 		$this->registros=$alumnos->count_by_sql(
 				"SELECT count(*) FROM " .
 				" bloquesalumnos " .
 				" INNER JOIN periodosalumnos ON bloquesalumnos.periodosalumnos_id=periodosalumnos.id " .
 				" INNER JOIN alumnos ON periodosalumnos.alumnos_id=alumnos.id " .
 				" WHERE bloquesalumnos.bloque_id='".$bloque->id."' AND periodosalumnos.periodo_id='".$bloque->periodo_id."'"
 			);

	 		// paginacion
			$paginador = new Paginador($controlador, $accion . '/' . $bloque->id);
			if ($pag != '') {
				$paginador->guardarPagina($pag);
			}
			$paginador->establecePath($controlador . '/' . $accion . '/' . $bloque->id );
			$paginador->estableceRegistros($this->registros);
			$paginador->generar();
			$this->paginador = $paginador;


 			$alumnos=new Alumnos();
 			$alumnos=$alumnos->find_all_by_sql(
 				"SELECT bloquesalumnos.id AS bloquesalumnos_id,alumnos.* FROM " .
 				" bloquesalumnos " .
 				" INNER JOIN periodosalumnos ON bloquesalumnos.periodosalumnos_id=periodosalumnos.id " .
 				" INNER JOIN alumnos ON periodosalumnos.alumnos_id=alumnos.id " .
 				" WHERE bloquesalumnos.bloque_id='".$bloque->id."' AND periodosalumnos.periodo_id='".$bloque->periodo_id."' " .
 				" ORDER BY ap,am,nombre ".
 				"LIMIT " .
		 		($paginador->pagina() * $paginador->rpp()) . ', ' .
				$paginador->rpp() . " "
 			);
 			$this->alumnos=$alumnos;
 			$this->bloque=$bloque;

 			$periodo=new Periodo();
 			$periodo=$periodo->find($bloque->periodo_id);
 			$this->periodo=$periodo;
			$ciclo=new Ciclos();
			$ciclo=$ciclo->find($this->periodo->ciclos_id);
			$this->ciclo=$ciclo;

			$this->option="vista";


 		}else{
 			$this->option="error";
 			$this->error="No existe el bloque.";
 		}

 		}else{
 			$this->option="error";
 			$this->error="No existe el bloque.";
 		}
 	}

 }
?>
