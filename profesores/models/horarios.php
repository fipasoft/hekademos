<?php
// Hekademos, Creado el 11/10/2008
/**
 * Horarios
 *
 * @package    Hekademos
 * @author     mimeks <mimex@fipasoft.com.mx>
 * @copyright  2008 FiPa Software (contacto at fipasoft.com.mx)
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL)
 *
 */
 class Horarios extends ActiveRecord{
    public function aula(){
        $aula = new Aulas();
        $aula = $aula->find($this->aulas_id);
        return $aula;
    }
    public function aulaClave(){
        $aula = new Aulas();
        $aula = $aula->find($this->aulas_id);
        return $aula->clave;
    }
    public function aulaNombre(){
        $aula = new Aulas();
        $aula = $aula->find($this->aulas_id);
        return $aula->nombre;
    }
 }
?>
