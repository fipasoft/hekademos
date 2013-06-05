<?php
// bancos2, Creado el 08/02/2009
/**
 * Reportes
 *
 * @package    Controlador
 * @author     mimeks <mimex@fipasoft.com.mx>
 * @copyright  2009 FiPa Software (contacto at fipasoft.com.mx)
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL)
 *
 */

Kumbia :: import('app.componentes.*');
Kumbia :: import('lib.kumbia.utils');
Kumbia :: import('lib.upload.main');
Kumbia :: import('lib.excel.main');

class ReportesController extends ApplicationController{
    public $template = "system";

    public function index(){
        $this->reportes = Config::read('reportes.ini');

        $ciclo = new Ciclos();
        $ciclo_id =  Session :: get_data('ciclo.id');
        $ciclo = $ciclo->find($ciclo_id);

        $grupos=new Grupos();
        $grupos=$grupos->find(
                        "ciclos_id='".$ciclo->id."' " .
                        " ORDER BY turno,oferta_id DESC,grado,letra"
                        );


        $this->ciclo    =   $ciclo;
        $this->grupos    =    $grupos;
        
        $departamentos = new Departamento();
        $departamentos = $departamentos->find();
        $this->datos=array();
        $this->datos["departamentos"] = $departamentos;
        
    }

    public function resumen(){
        $ciclo_id     = $this->post('ciclo_id');
        $grupos        = $this->post('grupos');

        if( count( $grupos ) > 0 ){
             require('app/reportes/xls.resumenreporte.php');
             set_time_limit( 0 );
            ob_end_clean();
            $this->set_response("view");
            $reporte = new  XLSResumenReporte( $grupos );
            $reporte->generar();
         } else {
             $this->_reportes_error = 'No se especificó un grupo válido.';
             $this->route_to( "controller: sesion", "action: restringir" );
         }
    }

    public function derechos(){
        $ciclo_id     = $this->post('ciclo_id');
        $grupos        = $this->post('grupos');

        if( count( $grupos ) > 0 ){
             require('app/reportes/xls.derechos.php');
             set_time_limit( 0 );
            ob_end_clean();
            $this->set_response("view");
            $reporte = new  XLSDerechos( $grupos );
            $reporte->generar();
         } else {
             $this->_reportes_error = 'No se especificó un grupo válido.';
             $this->route_to( "controller: sesion", "action: restringir" );
         }
    }
    
    public function basica(){
        $ciclo_id     = $this->post('ciclo_id');
        if( $ciclo_id != '' ){
             require('app/reportes/xls.basica.php');
             set_time_limit( 0 );
            ob_end_clean();
            $this->set_response("view");
            $reporte = new XLSBasica( $ciclo_id );
            $reporte->generar();
         } else {
             $this->_reportes_error = 'No se especificó un grupo válido.';
             $this->route_to( "controller: sesion", "action: restringir" );
         }
    }

    public function plantilla($id =''){
        if( $id!='' ){
            $departamento = new Departamento();
            $departamento = $departamento->find($id);
            if($departamento->id!=""){
            $ciclo_id = Session::get_data('ciclo.id');
             require('app/reportes/xls.plantilla.php');
             set_time_limit( 0 );
            ob_end_clean();
            $this->set_response("view");
            $reporte = new  XLSPlantilla( $ciclo_id, $departamento );
            $reporte->generar();
            }else{
            $this->_reportes_error = 'No se especificó el departamento.';
             $this->route_to( "controller: sesion", "action: restringir" );
         }
         } else {
             $this->_reportes_error = 'No se especificó un grupo válido.';
             $this->route_to( "controller: sesion", "action: restringir" );
         }
    }

}
?>
