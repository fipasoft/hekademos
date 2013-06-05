<?php
// Hekademos, Creado el 02/11/2008
/**
 * Agenda
 *
 * @package
 * @author     mimeks <mimex@fipasoft.com.mx>
 * @copyright  2008 FiPa Software (contacto at fipasoft.com.mx)
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL)
 *
 */
 Kumbia :: import('lib.kumbia.utils');

 class Agenda extends ActiveRecord{
 	protected $nombre;
 	protected $clave;
 	public $faltantes;
 	public $eventos_id;

 	public function completa($ciclo_id){
		$this->faltantes=null;
		$eventos=new Eventos();
		$eventos=$eventos->todosPorCategoria();
		$miAgenda=new Agenda();
		$miAgenda=$miAgenda->eventosPorCiclo($ciclo_id);
		$faltantes=array();
		foreach($eventos as $categorias){
		foreach($categorias as $evento){
					if($evento->cardinalidad!="*"){
					$miEvento=$miAgenda[$evento->id];
					if($miEvento==null){
						$faltantes[]=$evento->nombre;
					}

					}
		}
		}
		$this->faltantes=$faltantes;

		if(count($faltantes)==0)
		return true;
		else
		return false;


	}

	public function eventoValido($clave,$ciclo_id,$aco_section,$aco,$aro){
		$evento=new Eventos();
		$evento=$evento->find_first("clave='".$clave."'");
		$usr_grupos = Session :: get_data('usr.grupos');
		$grupos='';
		foreach($usr_grupos as $u)
		$grupos.=$u.':';

		if($evento->id!=""){
			$roles=new Roles();
			$roles=$roles->find("eventos_id=".$evento->id." AND aco_section='".$aco_section."' AND aco='".$aco."'");
			foreach($roles as $rol){
			$agenda=new Agenda();
			$agenda=$agenda->find_first("ciclos_id=".$ciclo_id." AND roles_id=".$rol->id." AND activo=1");
			if($agenda->id!=''){
			$hoy=time();
			$hoy=mktime(date("H"),date("i"), date("s")  ,date("n",$hoy)  ,date("j",$hoy)  , date("Y",$hoy) );

			//mktime (hora, minuto, segundo, mes, dia, año) 2008-12-01 00:00:00
			$i=split(' ',$agenda->inicio);
			$f=split(' ',$agenda->fin);
			$inicio=split('-',$i[0]);
			$fin=split('-',$f[0]);

			$i=split(":",$i[1]);
			$f=split(":",$f[1]);


			$inicio = mktime(00, 00, 01, $inicio[1], $inicio[2], $inicio[0]);
			$fin= mktime(23,59,59, $fin[1], $fin[2], $fin[0]);
			if($inicio<=$hoy && $hoy<=$fin){
				if(strtoLower($aro)==strtoLower($rol->aro) || $rol->aro=="ALL" || in_array(strtoLower($rol->aro),$usr_grupos)){
					return true;
				}
			}
			}

			}



		}
		return false;

	}

 	public function verInicio(){
 		return  str_replace(' ', ' a las ', Utils :: fecha_hora_convertir($this->inicio));
 	}

 	public function verFin(){
 		return  str_replace(' ', ' a las ', Utils :: fecha_hora_convertir($this->fin));
 	}

 	 public function eventosPorCiclo($id_ciclo){
		$ciclo=new Ciclos();
		if($ciclo->exists($id_ciclo)){
 		$eventos=$this->find_all_by_sql("SELECT agenda.*,roles.eventos_id FROM agenda,roles WHERE agenda.ciclos_id='".$id_ciclo."' AND roles.id=agenda.roles_id GROUP BY agenda.periodo");
		$events=array();

		foreach($eventos as $evento){
			$events[$evento->eventos_id][$evento->id]=$evento;
		}
		return $events;

		}else return array();
	}


 }
?>
