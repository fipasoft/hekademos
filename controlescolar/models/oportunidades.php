<?php
// Hekademos, Creado el 20/11/2008
/**
 * Oportunidades
 *
 * @package    SP5
 * @author     mimeks <mimex@fipasoft.com.mx>
 * @copyright  2008 FiPa Software (contacto at fipasoft.com.mx)
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL)
 *
 */

 class Oportunidades extends ActiveRecord{

     public function ordinario(){
         $oportunidad=$this->find_first("nombre='ordinario'");
         return $oportunidad;
     }

     public function extraordinario(){
         $oportunidad=$this->find_first("nombre='extraordinario'");
         return $oportunidad;
     }

 }
?>
