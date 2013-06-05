<?php
// Hekademos, Creado el 13/10/2008
/**
 * Controlador de horarios
 *
 * @package    Hekademos
 * @author     mimeks <mimex@fipasoft.com.mx>
 * @copyright  2008 FiPa Software (contacto at fipasoft.com.mx)
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL)
 *
 */
 Kumbia :: import('app.componentes.*');
 Kumbia :: import('lib.phpgacl.main');
 Kumbia :: import('lib.excel.main');

 class AulasController extends ApplicationController {
    public $template = "system";

    public function agregar(){
    $ciclo_id = Session :: get_data('ciclo.id');
        $Ciclos = new Ciclos();
        $this->ciclo = $Ciclos->find($ciclo_id);
        if($this->ciclo->abierto()){

            if($this->post('nombre') == ''){
                $this->option = 'captura';
            }else{
                $this->option = '';
                $this->error = '';
                $aula = new Aulas();
                if(!$aula->exists("clave='".trim($this->post('clave')."'"))){
                $aula->clave = trim($this->post('clave'));
                $aula->nombre = trim($this->post('nombre'));

                if($aula->save()){
                    $this->option = 'exito';
                }else{
                    $this->option = 'error';
                    $this->error .= ' Error al guardar en la BD.';
                }
                }else{
                    $this->option = 'error';
                    $this->error .= ' La clave ya existe.';
                }

            }
            } else {
                    $this->option = 'error';
                    $this->error = ' El ciclo esta cerrado.';

        }
    }

    public function disponible(){
        $this->set_response('view');
        $clave=$this->post("clave");
        $disponible=false;
        if($clave!=''){
            $aula=new Aulas();
            $c=$aula->count("clave='".$clave."'");
            if($c==0)
                $disponible=true;
        }
        $this->disponible=$disponible;
        $this->clave=trim($clave);

    }

    public function editar($id = ''){
    $ciclo_id = Session :: get_data('ciclo.id');
        $Ciclos = new Ciclos();
        $this->ciclo = $Ciclos->find($ciclo_id);
        if($this->ciclo->abierto()){

            if($this->post('nombre') == ''){
                $this->option = 'captura';
                $this->aula=new Aulas();
                $this->aula=$this->aula->find($id);
                if($this->aula->id==""){
                    $this->option = 'error';
                    $this->error .= ' El aula no existe.';
                }
            }else{
                $this->option = '';
                $this->error = '';
                $aula = new Aulas();
                if(!$aula->exists("clave='".trim($this->post('clave')."'"))){
                $aula->clave = trim($this->post('clave'));
                $aula->nombre = trim($this->post('nombre'));

                if($aula->save()){
                    $this->option = 'exito';
                }else{
                    $this->option = 'error';
                    $this->error .= ' Error al guardar en la BD.';
                }
                }else{
                    $this->option = 'error';
                    $this->error .= ' La clave ya existe.';
                }

            }
            } else {
                    $this->option = 'error';
                    $this->error = ' El ciclo esta cerrado.';

        }

    }

    public function eliminar($id = ''){
        if($id != ''){
            $this->option = 'captura';
            $id = intval($id, 10);
            $aulas = new Aulas();
            $this->aula = $aulas->find($id);
            $Ciclos = new Ciclos();
            if($this->aula->id != ''){
                $Ciclos = new Ciclos();
                $this->ciclo = $Ciclos->find(Session::get_data("ciclo.id"));
                if(!$this->ciclo->abierto()){
                    $this->option = 'error';
                    $this->error = ' El ciclo esta cerrado.';

                }

            }else{
                $this->option = 'error';
                $this->error = ' El aula no existe.';

            }
        }else if($this->post('id') != ''){
            $this->option = '';
            $this->error = '';
            $aulas = new Aulas();
            $aula = $aulas->find($this->post('id'));
            if($aula->id != ''){
                $Ciclos = new Ciclos();
                $this->ciclo = $Ciclos->find(Session::get_data("ciclo.id"));
                if($this->ciclo->abierto()){
                $horarios=new Horarios();
                $horarios=$horarios->find_first("aulas_id='".$aula->id."'");
                if($horarios->id==""){
                // eliminando el aula
                try{
                    $aulas->delete( $aula->id );
                    $this->option = 'exito';
                }catch(dbException $e){
                    $this->option = 'error';
                    $this->error .= ' Error al intentar eliminar de la BD. ' .
                                    'Posiblemente existan datos vinculados al aula (horarios).';
                }
                } else {
                    $this->option = 'error';
                    $this->error .= ' Error al intentar eliminar de la BD. ' .
                                    'Posiblemente existan datos vinculados al aula (horarios).';

                }
            } else {
                    $this->option = 'error';
                    $this->error = ' El ciclo esta cerrado.';

                }

            }else{
                $this->option = 'error';
                $this->error = ' El aula no existe.';
            }
        }else{
            $this->option = 'error';
            $this->error = ' No se especific&oacute; el aula a eliminar.';
        }

    }

    public function exportar($grp_id = ''){
        $this->set_response("view");
        require('app/reportes/xls.aulas.php');
        $reporte = new XLSAulas();
        $reporte->generar();
     }

    public function index($pag = ''){
        $controlador = $this->controlador;
        $accion = $this->accion;
        $usr_login = Session :: get_data('usr.login');

        // acl
        $this->acl = array();
        $acl = new gacl_extra();
        $acos_arr = array(
            'aulas' => array(
                'agregar', 'buscar', 'editar', 'eliminar', 'exportar'
            )
        );
        $this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);

        $b = new Busqueda($controlador, $accion);
        $b->campos();
        // genera las condiciones
        $c = $b->condicion();
        $aulas=new Aulas();

        $this->registros=$aulas->count(($c == "" ? "" : $c));

        // paginacion
        $paginador = new Paginador($controlador, $accion);
        if($pag != ''){
            $paginador->guardarPagina($pag);
        }
        $paginador->estableceRegistros($this->registros);
        $paginador->generar();


        $this->busqueda = $b;

        $this->aulas=$aulas->find(
                            ($c == "" ? "1" : $c)." ORDER BY clave ".
                            'limit ' . ($paginador->pagina() * $paginador->rpp()) . ', '
                                      . $paginador->rpp()
        );

        $this->paginador = $paginador;
        $this->option="vista";

    }


    public function cursos(){
    mysql_query("BEGIN") or die("ALU_ELI_1");
    $datos=array();
    $ciclos_id=4;
    $this->ciclo=new Ciclos();
    $this->ciclo=$this->ciclo->find($ciclos_id);
    /*$grupos=array("M"=>array(
                "1A"=>"A16","1B"=>"A17","1C"=>"A177")
                );
*/

    $grupos=array("M"=>array(
                "1A"=>"A16","1B"=>"A17","1C"=>"A18","1D"=>"A19","1E"=>"A20",
                "2A"=>"A26","2B"=>"A27","2C"=>"A28","2D"=>"A29","2E"=>"A30",
                "3A"=>"A11","3B"=>"A12","3C"=>"A13","3D"=>"A14","3E"=>"A15",
                "4A"=>"A06","4B"=>"A07","4C"=>"A08","4D"=>"A09","4E"=>"A10",
                "5A"=>"A01","5B"=>"A02","5C"=>"A03","5D"=>"A04","5E"=>"A05",
                "6A"=>"A21","6B"=>"A22","6C"=>"A23"
                ),
                "V"=>array(
                "1A"=>"A16","1B"=>"A17","1C"=>"A18","1D"=>"A19","1E"=>"A20",
                "2A"=>"A26","2B"=>"A27","2C"=>"A28","2D"=>"A29","2E"=>"A30",
                "3A"=>"A11","3B"=>"A12","3C"=>"A13","3D"=>"A14","3E"=>"A15",
                "4A"=>"A06","4B"=>"A07","4C"=>"A08","4D"=>"A09","4E"=>"A10",
                "5A"=>"A01","5B"=>"A02","5C"=>"A03","5D"=>"A04","5E"=>"A05",
                "6A"=>"A21","6B"=>"A22","6C"=>"A23"
                )
                );



$e=0;
try{
    foreach($grupos as $t=>$turno){
        foreach($turno as $g=>$al){

            $grupo=new Grupos();
            $grupo=$grupo->find_first("ciclos_id='".$ciclos_id."' AND grado='".$g[0]."' AND letra='".$g[1]."' AND turno='".$t."' ");
            if($grupo->id!=""){
            $aula=new Aulas();
            $aula=$aula->find_first("clave='".$al."'");

            if($aula->id!=""){
            $cursos=$grupo->cursos();
            $datos[]=$g.$t." ".count($cursos)." cursos.";
            $i=1;
            foreach($cursos as $curso){
                $horarios=new Horarios();
                $horarios=$horarios->find("cursos_id='".$curso->id."'");
                $datos[]=$i." ".$curso->id;
                foreach($horarios as $ho){
                    $ho->aulas_id=$aula->id;
                    if(!$ho->save()){
                    $datos[]="ERROR: ".$g.$t.", ".$curso->materia.", id:".$ho->id." inicio:".$ho->inicio." fin:".$ho->fin." dia:".$ho->dias_id." no se pudo cambiar el aula a ".$aula->clave;
                    $e=1;
                    }else{
                    $datos[]=$g.$t.", ".$curso->materia.", id:".$ho->id." inicio:".$ho->inicio." fin:".$ho->fin." dia:".$ho->dias_id." cambio de aula a ".$aula->clave;

                    }
                }
                $i++;
            }
            }else{
            $datos[]="ERROR: El aula ".$al." no existe.";
            }
            }else{
            $datos[]="ERROR: El grupo ".$g.$t." no existe.";
            }

        }
    }

    if($e==0){
     mysql_query("COMMIT") or die("ALU_ELI_1");
     $datos[]="Datos guardados con exito.";
    }
    else{
        mysql_query("ROLLBACK") or die("ALU_ELI_1");
        $datos[]="no se guardaron los cambios.";
    }

    }catch(Exception $e){
                    $datos[]="Ocurrio una exception.";
                    mysql_query("ROLLBACK") or die("ALU_ELI_1");
    }
    $this->datos=$datos;

    }

 }


?>