<?php
Kumbia :: import('app.componentes.*');
Kumbia :: import('lib.kumbia.utils');
Kumbia :: import('lib.upload.main');
Kumbia :: import('lib.excel.main');

/*
 * Created on 24/11/2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class HistorialController extends ApplicationController {
	public $persistance = false;
	public $template = "system";

	function exportar(){
		$this->set_response("view");
		require('app/reportes/xls.historial.php');
		$ciclo_id = Session :: get_data('ciclo.id');
		$reporte = new XLSHistorial($ciclo_id);
		$reporte->generar();
	}

	function index($pag = ''){
		$path = $this->path = KUMBIA_PATH;
		$ciclo_id = Session :: get_data('ciclo.id');

		$historial=new Historial();
		// busqueda
		$b = new Busqueda($this->controlador, $this->accion);
		$b->establecerCondicion('saved_at', "saved_at  LIKE '" . Utils::convierteFechaMySql(trim($b->campo('saved_at'))) . "%' ");


		$c = $b->condicion();
		$this->busqueda = $b;
		$this->controladores=$historial->obtenControladores();


		if($this->post('controlador')==''){
			$cons='';
			foreach($this->controladores as $cr){
				$cons.=" controlador LIKE '%$cr%' OR ";

			}
		$cons=substr($cons,0,strlen($cons)-3);
		$c.= ($c == "" ? "" : "AND " . "") ." ($cons) ";
		}else{
		$b->establecerCondicion('controlador', "controlador LIKE '".$b->campo('controlador')."'");
		}

		$this->c=$c;
		// cuenta todos los registros
		$this->registros = $historial->count_by_sql("SELECT " .
		"COUNT(*) " .
		"FROM " .
		"historial " .
		"WHERE " .
		"ciclos_id = '" . $ciclo_id . "'" .
		 ($c == "" ? "" : "AND " . $c) . "  "
		 );



			// paginacion
		$paginador = new Paginador($this->controlador, $this->accion);
		if ($pag != '') {
			$paginador->guardarPagina($pag);
		}
		$paginador->estableceRegistros($this->registros);
		$paginador->generar();
		$this->paginador = $paginador;

		// ejecuta la consulta
		$this->historial = $historial->find_all_by_sql("SELECT " .
		"* " .
		"FROM " .
		"historial " .
		"WHERE " .
		"ciclos_id = '" . $ciclo_id . "'" .
		 ($c == "" ? "" : "AND " . $c) . "  ".
		"ORDER BY " .
		"saved_at DESC,controlador,accion,usuario " .
		"LIMIT " .
		 ($paginador->pagina() * $paginador->rpp()) . ', ' .
		$paginador->rpp() . " ");

		$ciclos = new Ciclos();
		$this->ciclo = $ciclos->find($ciclo_id);
		$this->ciclos = $ciclos->find("columns: id, numero", "conditions: abierto = '1' ", "order: numero DESC");

		$usr_login = Session :: get_data('usr.login');
		$this->acl = array ();
		$acl = new gacl_extra();
		$acos_arr = array (
			'historial' => array (
				'ver',
				'exportar',
				'buscar'
			)
		);
		$this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);
		$this->acl=$this->acl['historial'];

	}

	function ver($id='') {
		if($id!=''){
			$historial=new Historial();
			$this->historial=$historial=$historial->find($id);
			if($historial->id!=''){
				$this->option='vista';
			}else{
			$this->option="error";
			$this->error="No existe el registro.";
		}
		}else{
			$this->option="error";
			$this->error="No tiene permiso para ver la pagina.";
		}

	}

}
?>
