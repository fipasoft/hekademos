<?php
/*
 * Created on 23/09/2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class Ciclos extends ActiveRecord{
    public function abierto(){
        if($this->id=='')return false;

        if($this->abierto==1)return true;
        else return false;
    }

    public function activo(){
    $activo=$this->find_first("activo='1'");
    return $activo;
    }

 }
?>
