<?php
/*
 * Created on 24/11/2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

 /**
 * Categorias
 *
 * @package
 * @author     jona <jlopez@fipasoft.com.mx>
 * @copyright  2008 FiPa Software (contacto at fipasoft.com.mx)
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL)
  *
 */

  Kumbia :: import('lib.kumbia.utils');
 class AFxDoor extends SqlserverRecord {

	private $entradas;
	private	$salidas;

 	public function esEntrada($id){
	if($this->entradas!=null){
			return in_array($id,$this->entradas);
		}else{
			return false;
		}
 	}


 	public function esSalida($id){
	if($this->salidas!=null){
			return in_array($id,$this->salidas);
		}else{
			return false;
		}

 	}

 	public function Entradas(){
		if($this->entradas!=null){
			$entradas=new AfxDoor();
			$entradas=$entradas->find("PodDoorIndex='1'");

			if(is_array($entradas))
			$this->entradas=$entradas;
			else
			$this->entradas=array();

		}

		return $this->entradas;

 	}


 	public function salidas(){
		if($this->salidas!=null){
			$salidas=new AfxDoor();
			$salidas=$salidas->find("PodDoorIndex='2'");

			if(is_array($salidas))
			$this->salidas=$salidas;
			else
			$this->salidas=array();
		}

		return $this->salidas;

 	}

 }
?>
