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

 class ViewEvents extends SqlserverRecord {

    public function tipo(){
        if($this->PodDoorIndex!=null){
            if($this->PodDoorIndex=="1")return "Entrada";
            elseif($this->PodDoorIndex=="2") return "Salida";
            else return "";
        }else return "";
    }

        public function esEntrada(){
        if($this->PodDoorIndex!=null){
            if($this->PodDoorIndex=="1")return true;
            else return false;

        }else return false;
    }

        public function esSalida(){
        if($this->PodDoorIndex!=null){
            if($this->PodDoorIndex=="2")return true;
            else return false;

        }else return false;
    }
 }
?>
