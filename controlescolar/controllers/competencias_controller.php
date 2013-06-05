<?php
/*
 * Created on 11/02/2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
  class CompetenciasController extends ApplicationController {

   	public function obtenertipos(){
 		$this->set_response('view');
		$tipo=$this->post('tipo');
		$this->titulo="";

		if($tipo=='esp'){
		$this->titulo="Trayectoria";
		$datos=new Trayectoriaespecializante();
		$datos=$datos->find();

		}elseif($tipo=='gen'){
		$this->titulo="Competencia";
		$datos=new Competenciagenerica();
		$datos=$datos->find();
		}else{
		$datos=null;
		}
		$this->datos=$datos;

   	}
  }
?>
