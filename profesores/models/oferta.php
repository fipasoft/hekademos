<?php
// sp5, Creado el 30/09/2008
/**
 * Oferta
 *
 * @package    sp5
 * @author     mimeks <mimex@fipasoft.com.mx>
 * @copyright  2008 FiPa Software (contacto at fipasoft.com.mx)
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL)
 *
 */
 class Oferta extends ActiveRecord{

 public function todas(){
    $oferta=new Oferta();
    return $oferta->find();
 }

 }
?>
