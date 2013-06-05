<?php
// Hekademos, Creado el 25/09/2008
/**
 *
 *
 * @package
 * @author     mimeks <mimex@fipasoft.com.mx>
 * @copyright  2008 FiPa Software (contacto at fipasoft.com.mx)
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL)
 *
 */
class Prerrequisitos extends ActiveRecord{

    public function requisito(){
        $materias = new Materias();
        $materias->find($this->requisito);
        return $materias->nombre;
    }

    public function clave(){
        $materias = new Materias();
        $materias->find($this->requisito);
        return $materias->clave;
    }
}
?>
