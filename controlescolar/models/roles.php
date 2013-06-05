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

class Roles extends ActiveRecord {
	public function porEvento($evento_id) {

		$roles = $this->find('eventos_id=' . $evento_id.' GROUP BY aro');
		return $roles;
	}

	public function porEventoEspecial($evento_id) {
		$evento = new Eventos();
		if ($evento->exists($evento_id)) {
			$evento = $evento->find_first($evento_id);
			$clave=str_replace("-ESP","",$evento->clave);
			$sql = "SELECT * FROM eventos WHERE clave='$clave'";
			$evento = $evento->find_by_sql($sql);

			if($evento["id"]!=null)
			return $this->porEvento($evento["id"]);

		}
		return array ();
	}

	public function idEventoPadreEspecial($evento_id) {
		$evento = new Eventos();
		if ($evento->exists($evento_id)) {
			$evento = $evento->find_first($evento_id);
			$clave=str_replace("-ESP","",$evento->clave);
			$sql = "SELECT * FROM eventos WHERE clave='$clave'";
			$evento = $evento->find_by_sql($sql);

			if($evento["id"]!=null)
			return $evento["id"];

		}
		return -1;
	}
}
?>
