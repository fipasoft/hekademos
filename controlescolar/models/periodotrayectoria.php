<?php
 /**
 * periodostrayectoria.php
 *
 * Created on 05/05/2009
 * @package  Modelos
 * @author     J Jonathan Lopez <jlopez@fipasoft.com.mx>
 * @copyright  2009 FiPa Software (contacto at fipasoft.com.mx)
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL)
 *
 */

  class Periodotrayectoria extends ActiveRecord{
		public $nombre;
		public function trayectoriasporperiodo($periodo_id,$order = ""){
			$consulta = "SELECT periodotrayectoria.*,trayectoriaespecializante.nombre
						FROM
						periodotrayectoria
						INNER JOIN trayectoriaespecializante ON periodotrayectoria.trayectoriaespecializante_id = trayectoriaespecializante.id
						WHERE periodotrayectoria.periodo_id=".$periodo_id." ".$order;
			$trayectorias = new Periodotrayectoria();
			$trayectorias = $trayectorias->find_all_by_sql($consulta);
			return $trayectorias;
		}

		public function verTurno(){
			switch($this->turno){
				case "M": return "Matutino";
				case "V": return "Vespertino";
				default: return "";


			}
		}

	  }
?>
