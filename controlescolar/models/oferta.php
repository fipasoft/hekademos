<?php
// Hekademos, Creado el 30/09/2008
/**
 * Oferta
 *
 * @package    Hekademos
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

 public static function bachilleratogeneral(){
 	return Oferta::porclave('GEN');
 }

  public static function bachilleratocompetencias(){
	return Oferta::porclave('COM');
 }

 public static function porclave($clave){
 	$oferta=new Oferta();
 	$oferta=$oferta->find_first("clave='$clave'");
 	if($oferta->id!='')
 	return $oferta->id;
 	else
 	return -1;
 }

 }
?>
