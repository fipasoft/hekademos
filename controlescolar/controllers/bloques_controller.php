<?php
 /**
 * bloques_controller.php
 *
 * Created on 08/05/2009
 * @package  Controladores
 * @author     J Jonathan Lopez <jlopez@fipasoft.com.mx>
 * @copyright  2009 FiPa Software (contacto at fipasoft.com.mx)
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL)
 *
 */
Kumbia :: import('app.componentes.*');
Kumbia :: import('lib.kumbia.utils');

 class BloquesController extends ApplicationController {
     public $template = "system";

    public function agregar($id=''){
        $this->bloque=null;
        if($id!=''){
            $id=intval($id,10);
            $periodo=new Periodo();
            $this->periodo=$periodo->find($id);
            if($this->periodo->id!=""){
            $this->option="captura";
                }else{
                    $this->option="error";
                    $this->error="No existe el periodo.";
                }
        }elseif($this->post('periodo_id')!=""){
                $id=intval($this->post('periodo_id'),10);
                $periodo=new Periodo();
                $periodo=$periodo->find($id);
                if($periodo->id!=""){
                $bloque=new Bloque();
                $bloque->periodo_id=$id;
                $ini =  Utils::convierteFechaMySql(substr($this->post('inicio'),0,10))." ".substr($this->post('inicio'),10);
                $fin =  Utils::convierteFechaMySql(substr($this->post('fin'),0,10))." ".substr($this->post('fin'),10);

                if (
                    (Utils :: comparaDateTime($periodo->inicio, $ini) <= 0 && Utils :: comparaDateTime($ini, $periodo->fin) <= 0)
                         &&
                    (Utils :: comparaDateTime($periodo->inicio, $fin) <= 0 && Utils :: comparaDateTime( $fin, $periodo->fin) <= 0)
                        ) {
                        $bloque->inicio= Utils :: fecha_convertir(substr($this->post('inicio'),0,10)).substr($this->post('inicio'),10);
                        $bloque->fin= Utils :: fecha_convertir(substr($this->post('fin'),0,10)).substr($this->post('fin'),10);
                        if($bloque->save()){
                        $this->option="exito";
                        $ciclo=new Ciclos();
                        $ciclo=$ciclo->find($periodo->ciclos_id);
                        $historial=new Historial();
                        $historial->ciclos_id= Session :: get_data('ciclo.id');
                        $historial->usuario=Session :: get_data('usr.login');
                        $historial->descripcion='Se agrego un bloque que inicia el '.$this->post('inicio').' y finaliza el '.$this->post('fin').' al periodo del ciclo '.$ciclo->numero.' que inicia el '.Utils::convierteFecha(substr($periodo->inicio,0,10)).' '.substr($periodo->inicio,10).' y finaliza el '.Utils::convierteFecha(substr($periodo->fin,0,10)).' '.substr($periodo->fin,10);
                        $historial->controlador=$this->controlador;
                        $historial->accion=$this->accion;
                        $historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
                        $historial->save();
                        }else{
                        $this->option="error";
                        $this->error="Ha ocurrido un error en la BD.";
                        }
                }else{
                        $this->option="error";
                        $this->error="El bloque no encaja en el periodo.";
                }
                }else{
                    $this->option="error";
                    $this->error="No existe el periodo.";
                }
                }else{
                    $this->option="error";
                    $this->error="No tiene permiso para ver la pagina.";
                }
        }

     public function editar($id=""){
         $this->bloque=null;
        if($id!=""){
            $id=intval($id,10);
            $bloque=new Bloque();
            $bloque=$bloque->find($id);
            if($bloque->id!=""){
            $this->option="captura";
            $this->bloque=$bloque;
            $this->inicio="";
            if($bloque->inicio!=""){
            $inicio=new DateTime(substr($bloque->inicio,0,10));
            $this->inicio=$inicio->format("d/m/Y").substr($bloque->inicio,10,6);
            }

            $this->fin="";
            if($bloque->fin!=""){
            $fin=new DateTime(substr($bloque->fin,0,10));
            $this->fin=$fin->format("d/m/Y").substr($bloque->fin,10,6);
            }

            }else{
                $this->option="error";
                $this->error="No existe el bloque.";
            }
        }elseif($this->post("id")!=""){
            $id=intval($this->post("id"),10);
            $bloque=new Bloque();
            $bloque=$bloque->find($id);
            if($bloque->id!=""){
                $this->bloque=$bloque;

                $periodo=new Periodo();
                $periodo=$periodo->find($bloque->periodo_id);
                $ini =  Utils::convierteFechaMySql(substr($this->post('inicio'),0,10))." ".substr($this->post('inicio'),10);
                $fin =  Utils::convierteFechaMySql(substr($this->post('fin'),0,10))." ".substr($this->post('fin'),10);

                if (
                    (Utils :: comparaDateTime($periodo->inicio, $ini) <= 0 && Utils :: comparaDateTime($ini, $periodo->fin) <= 0)
                         &&
                    (Utils :: comparaDateTime($periodo->inicio, $fin) <= 0 && Utils :: comparaDateTime( $fin, $periodo->fin) <= 0)
                        ) {
                $bloque->inicio= Utils :: fecha_convertir(substr($this->post('inicio'),0,10)).substr($this->post('inicio'),10);
                $bloque->fin= Utils :: fecha_convertir(substr($this->post('fin'),0,10)).substr($this->post('fin'),10);
                if($bloque->save()){
                        $ciclo=new Ciclos();
                        $ciclo=$ciclo->find($periodo->ciclos_id);
                        $historial=new Historial();
                        $historial->ciclos_id= Session :: get_data('ciclo.id');
                        $historial->usuario=Session :: get_data('usr.login');
                        $historial->descripcion='Se edito un bloque inicia el '.$this->post('inicio').' y finaliza el '.$this->post('fin').' perteneciente ' .
                                                ' al periodo del ciclo '.$ciclo->numero.' que inicia el '.Utils::convierteFecha(substr($periodo->inicio,0,10)).' '.substr($periodo->inicio,10).' ' .
                                                'y finaliza el '.Utils::convierteFecha(substr($periodo->fin,0,10)).' '.substr($periodo->fin,10);
                        $historial->controlador=$this->controlador;
                        $historial->accion=$this->accion;
                        $historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
                        $historial->save();
                        $this->option="exito";
                }else{
                    $this->option="error";
                    $this->error="Ha ocurrido un error en la BD.";
                }

                }else{
                $this->option = 'error';
                $this->error = ' El periodo de tiempo no encaja en el periodo.';
                }

            }else{
                $this->option="error";
                $this->error="No existe el bloque.";
            }

        }else{
        $this->option="error";
         $this->error="No existe el bloque.";
        }
     }

     public function eliminar($id=''){
        if ($id != '') {
            $this->option = 'captura';
            $id = intval($id, 10);
            $bloque = new Bloque();
            $bloque=$bloque->find($id);
            $this->bloque=$bloque;

            $periodo=new Periodo();
            $this->periodo=$periodo->find($bloque->periodo_id);

            if ($bloque->id == '') {
                $this->option = 'error';
                $this->error = ' El bloque no existe.';
            }
        } elseif ($this->post('id') != '') {
                $this->option = '';
                $this->error = '';
                $id = intval($this->post('id'), 10);
                $bloque = new Bloque();
                $bloque=$bloque->find($id);
                $this->bloque=$bloque;

                $periodo=new Periodo();
                $this->periodo=$periodo->find_first($bloque->periodo_id);

                if($bloque->id != ''){
                        try {
                            mysql_query("BEGIN") or die("BLO_ELI_1");
                                $Bloque=new Bloque();
                                if($Bloque->delete($bloque->id)){
                                mysql_query("COMMIT") or die("BLO_ELI_2");
                                $this->option="exito";
                                $ciclo=new Ciclos();
                                $ciclo=$ciclo->find($this->periodo->ciclos_id);
                                $historial=new Historial();
                                $historial->ciclos_id= Session :: get_data('ciclo.id');
                                $historial->usuario=Session :: get_data('usr.login');
                                $historial->descripcion='Se elimino el bloque que iniciaba el '.Utils::convierteFecha(substr($bloque->inicio,0,10)).' '.substr($bloque->inicio,10).'' .
                                                        ' y finalizaba el '.Utils::convierteFecha(substr($bloque->fin,0,10)).' '.substr($bloque->fin,10).' perteneciente ' .
                                                        ' al periodo del ciclo '.$ciclo->numero.' que inicia el '.Utils::convierteFecha(substr($this->periodo->inicio,0,10)).' '.substr($this->periodo->inicio,10).'' .
                                                        ' y finaliza el '.Utils::convierteFecha(substr($this->periodo->fin,0,10)).' '.substr($this->periodo->fin,10);
                                $historial->controlador=$this->controlador;
                                $historial->accion=$this->accion;
                                $historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
                                $historial->save();

                                }else{
                                mysql_query("ROLLBACK") or die("BLO_ELI_3");
                                $this->option = 'error';
                                $this->error = ' Error al intentar eliminar de la BD.';
                                }

                        } catch (Exception $e) {
                            mysql_query("ROLLBACK") or die("BLO_ELI_4");
                            $this->option = 'error';
                            $this->error = ' Error al intentar eliminar de la BD.';
                        }
                } else {
                    $this->option = 'error';
                    $this->error = ' El bloque no existe.';
                }
            } else {
                $this->option = 'error';
                $this->error = ' No se especific&oacute; el bloque a eliminar.';
            }
     }

    public function eliminartodos($id=''){
        if ($id != '') {
            $this->option = 'captura';
            $id = intval($id, 10);
            $periodo = new Periodo();
            $periodo = $periodo->find($id);

            $this->periodo=$periodo;
            if ($periodo->id != '') {
                    if($periodo->actual()){
                        $this->option = 'warning';
                        $this->warning = ' No se pueden eliminar los bloques.<br/> Se esta llevando actualmente el registro.';

                    }
            }else{
                $this->option = 'error';
                $this->error = ' El periodo no existe.';
            }

        } elseif ($this->post('id') != '') {
                $this->option = '';
                $this->error = '';
                $id = intval($this->post('id'), 10);
                $periodo = new Periodo();
                $periodo=$periodo->find($id);
                $this->periodo=$periodo;
                if($periodo->id != ''){
                if(!$periodo->actual()){
                        try {
                            mysql_query("BEGIN") or die("BLO_ELI_1");
                                $Bloque=new Bloque();
                                if($Bloque->delete("periodo_id='".$periodo->id."'")){
                                $ciclo=new Ciclos();
                                $ciclo=$ciclo->find($periodo->ciclos_id);
                                $historial=new Historial();
                                $historial->ciclos_id= Session :: get_data('ciclo.id');
                                $historial->usuario=Session :: get_data('usr.login');
                                $historial->descripcion='Se eliminarón todos los bloques para el periodo del ciclo '.$ciclo->numero.' que inicia el '.Utils::convierteFecha(substr($periodo->inicio,0,10)).' '.substr($periodo->inicio,10).' y finaliza el '.Utils::convierteFecha(substr($periodo->fin,0,10)).' '.substr($periodo->fin,10);
                                $historial->controlador=$this->controlador;
                                $historial->accion=$this->accion;
                                $historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
                                $historial->save();
                                mysql_query("COMMIT") or die("BLO_ELI_2");
                                $this->option="exito";
                                }else{
                                mysql_query("ROLLBACK") or die("BLO_ELI_3");
                                $this->option = 'error';
                                $this->error = ' Error al intentar eliminar de la BD.';
                                }

                        } catch (Exception $e) {
                            mysql_query("ROLLBACK") or die("BLO_ELI_4");
                            $this->option = 'error';
                            $this->error = ' Error al intentar eliminar de la BD.';
                        }
                }else{

                    $this->option = 'warning';
                    $this->warning = ' No se pueden eliminar los bloques.<br/> Se esta llevando actualmente el registro.';
                }
                } else {
                    $this->option = 'error';
                    $this->error = ' El periodo no existe.';
                }
            } else {
                $this->option = 'error';
                $this->error = ' No se especific&oacute; el periodo.';
            }
     }


     public function index($id="",$pag=""){
         if($id!=""){

            $periodo=new Periodo();
            $id=intval($id,10);
            $periodo=$periodo->find($id);
            if($periodo->id!=""){
                // acl
                $usr_login = Session :: get_data('usr.login');
                $this->acl = array ();
                $acl = new gacl_extra();
                $acos_arr = array (
                    'bloques' => array (
                        'agregar',
                        'editar',
                        'eliminar',
                        'eliminartodos'
                        ),
                    'bloquesalumnos' =>array (
                        'index'
                    )
                );

                $this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);
                $controlador = $this->controlador;
                $accion = $this->accion;
                $bloques=new Bloque();
                $this->registros=$bloques->count(
                    "periodo_id='".$periodo->id."' "
                    );
                // paginacion
                $paginador = new Paginador($controlador, $accion.$periodo->id);
                if ($pag != '') {
                    $paginador->guardarPagina($pag);
                }
                $paginador->establecePath($controlador . '/' . $accion . '/' . $periodo->id );
                $paginador->estableceRegistros($this->registros);
                $paginador->generar();
                $this->paginador = $paginador;

                $bloques=new Bloque();
                $bloques=$bloques->find(
                        "periodo_id='".$periodo->id."'".
                    " ORDER BY inicio,fin ".
                    "LIMIT " .
                     ($paginador->pagina() * $paginador->rpp()) . ', ' .
                    $paginador->rpp() . " ");

                $this->option="vista";
                $this->periodo=$periodo;
                $this->bloques=$bloques;
                $ciclo=new Ciclos();
                $ciclo=$ciclo->find($periodo->ciclos_id);
                $this->ciclo=$ciclo;
            }else{
             $this->option="error";
             $this->error="No existe el periodo.";
             }
         }else{
             $this->option="error";
             $this->error="No existe el periodo.";
         }
     }

 }
?>
