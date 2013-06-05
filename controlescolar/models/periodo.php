<?php
 /**
 * periodo.php
 *
 * Created on 04/05/2009
 * @package  Modelos
 * @author     J Jonathan Lopez <jlopez@fipasoft.com.mx>
 * @copyright  2009 FiPa Software (contacto at fipasoft.com.mx)
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL)
 *
 */

  class Periodo extends ActiveRecord{
  	protected $numero;

	public function cicloparaalta(){
		$activo=new Ciclos();
		$periodociclo=new Ciclos();

		$periodociclo=$periodociclo->find($this->ciclos_id);
		$activo=$activo->activo();

		return ($periodociclo->avance=="1" && $activo->id==$this->cursosciclos_id);

	}

	public function existePeriodoParaElCiclo($ciclo_id){
		$periodo=new Periodo();
		return $periodo->exists("ciclos_id='".$ciclo_id."'");

	}

	public function estado(){
	if($this->activo=="1")return "activo";
	else return "inactivo";
	}

	public function actual(){
		$hoy=new DateTime();
		$inicio=new DateTime($this->inicio);
		$fin=new DateTime($this->fin);

		return ($inicio->format('U')<=$hoy->format('U') && $hoy->format('U')<=$fin->format('U'));
	}

  }
?>
