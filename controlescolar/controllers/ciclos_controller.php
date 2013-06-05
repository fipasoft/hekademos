<?php
// Creado el 03/07/2008
/**
 * Ciclos
 *
 * @author     mimeks <mimex@fipasoft.com.mx>
 * @copyright  2008 FiPa Software (contacto at fipasoft.com.mx)
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL)
 */

Kumbia :: import('app.componentes.*');
Kumbia :: import('lib.kumbia.utils');
Kumbia :: import('app.scripts.ZipFolder');

class CiclosController extends ApplicationController {
    public $template = "system";

    public function abrir($id = '') {
        if($id != ''){
            $this->option = 'captura';
            $id = intval($id, 10);
            $Ciclos = new Ciclos();
            $this->ciclo = $Ciclos->find($id);
            if($this->ciclo->id == ''){
                $this->option = 'error';
                $this->error = ' El ciclo no existe.';
            }else{
                $agenda=new Agenda();
                if(!$agenda->completa($this->ciclo->id)){
                    $this->option = 'error';
                    $this->error = ' La agenda del ciclo aun no esta completa. Aun no se definen los siguientes eventos: <br/><br/>';
                    foreach($agenda->faltantes as $evento){
                        $this->error.=$evento."<br/>";
                    }
                }

            }
        }else if($this->post('id') != ''){
            $this->option = '';
            $this->error = '';
            $Ciclos = new Ciclos();
            $ciclo = $Ciclos->find($this->post('id'));
            if($ciclo->id != ''){
                $agenda=new Agenda();
                if(!$agenda->completa($ciclo->id)){
                    $this->option = 'error';
                    $this->error = ' La agenda del ciclo aun no esta completa.';
                }else{
                    // cambiando status del ciclo
                    $ac="";
                    if($ciclo->abierto==1){
                        $ciclo->abierto=0;
                        $ac="cerro";
                    }else{
                        $ciclo->abierto=1;
                        $ac="abrio";
                    }
                    if($ciclo->save()){
                        $historial=new Historial();
                        $historial->ciclos_id= Session :: get_data('ciclo.id');
                        $historial->usuario=Session :: get_data('usr.login');
                        $historial->descripcion='Se '.$ac." el ciclo ".$ciclo->numero." con el id ".$ciclo->id;
                        $historial->controlador="ciclos";
                        $historial->accion="abrir";
                        $historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
                        $historial->save();

                        $this->option = 'exito';
                    }else{
                        $this->option = 'error';
                        $this->error .= ' Error al intentar modificar en la BD.';
                    }
                }
            }else{
                $this->option = 'error';
                $this->error = ' El ciclo no existe.';
            }
        }else{
            $this->option = 'error';
            $this->error = ' No se especific&oacute; el ciclo a modificar.';
        }
    }

    public function agregar(){
        if($this->post('numero') == ''){
            $this->option = 'captura';
        }else{
            $this->option = '';
            $this->error = '';
            $ciclo = new Ciclos();
            $ciclo->numero = $this->post('numero');
            $ciclo->inicio = Utils :: fecha_convertir($this->post('inicio'));
            $ciclo->fin = Utils :: fecha_convertir($this->post('fin'));
            $ciclo->activo = ($this->post('activo') == '' ? 0 : $this->post('activo'));
            $ciclo->avance = 0;
            $ciclo->validates_uniqueness_of('numero');
            if($ciclo->save()){
                $this->option = 'exito';
                $historial=new Historial();
                $historial->ciclos_id= Session :: get_data('ciclo.id');
                $historial->usuario=Session :: get_data('usr.login');
                $historial->descripcion="Se agrego el ciclo ".$ciclo->numero." con el id ".$ciclo->id;
                $historial->controlador="ciclos";
                $historial->accion="agregar";
                $historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
                $historial->save();

            }else{
                $this->option = 'error';
                $this->error .= ' Error al guardar en la BD.'. $ciclo->show_message();
            }
        }
    }

    public function avance($id='') {
        if($id != ''){
            $this->option = 'captura';
            $id = intval($id, 10);
            $Ciclos = new Ciclos();
            $this->ciclo = $Ciclos->find($id);
            if($this->ciclo->id == ''){
                $this->option = 'error';
                $this->error = ' El ciclo no existe.';
            }

        }else if($this->post('id') != ''){
        $this->option = '';
        $this->error = '';
        $Ciclos = new Ciclos();
        $ciclo = $Ciclos->find($this->post('id'));
        if($ciclo->id != ''){
            if($ciclo->avance==0 && $ciclo->activo==1){
                try{
                    Session :: set_data('ciclo.id',$ciclo->id);
                    mysql_query("BEGIN") or die("CIERRE_1");
                    $res=$ciclo->cierre();
                    if($res==''){
                        $this->option="exito";
                        $historial=new Historial();
                        $historial->ciclos_id= Session :: get_data('ciclo.id');
                        $historial->usuario=Session :: get_data('usr.login');
                        $historial->descripcion="Se realizo el avance del ciclo ".$ciclo->numero." con el id ".$ciclo->id;
                        $historial->controlador="ciclos";
                        $historial->accion="avance";
                        $historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
                        $historial->save();
                        //mysql_query("ROLLBACK") or die("CIERRE_3");
                        mysql_query("COMMIT") or die("CIERRE_2");
                        try{
                            $origen = $_SERVER['DOCUMENT_ROOT'].KUMBIA_PATH."logs/avance".$ciclo->numero;
                            if(is_dir($origen)){
                                $nombre = "avance".$ciclo->numero."_".time().".zip";
                                $destino = $_SERVER['DOCUMENT_ROOT'].KUMBIA_PATH."public/temp/".$nombre;                    
                                $zip = new ZipFolder($destino, $origen);
                                Utils::delete_directory($origen);
                                $this->archivo = $destino;
                                $this->link = KUMBIA_PATH . "public/temp/".$nombre;
                            }else{
                                $this->option = "error";
                                $this->error = "Ocurrio un error al crear el archivo.";
                            }
                        }catch(Exception $e){
                                $this->archivo = "";
                                $this->link = "";    
                        }
                    }else{
                        mysql_query("ROLLBACK") or die("CIERRE_3");
                        $this->option="error";
                        $this->error=$res;
                    }
                }catch(dbException $e){
                    $this->option = 'error';
                    $this->error .= ' Error al intentar realizar el cierre.'.$e;
                    mysql_query("ROLLBACK") or die("CIERRE_4");
                }
            }else{
                $this->option="error";
                $this->error="Ya se ha realizado el cierre del ciclo con anterioridad.";
            }

        }else{
            $this->option = 'error';
            $this->error = ' El ciclo no existe.';
        }
        }else{
            $this->option = 'error';
            $this->error = ' No se especific&oacute; el ciclo a cerrar.';
        }

    }

    public function simulaavance($id='') {
        if($id != ''){
            $this->option = 'captura';
            $id = intval($id, 10);
            $Ciclos = new Ciclos();
            $this->ciclo = $Ciclos->find($id);
            if($this->ciclo->id == ''){
                $this->option = 'error';
                $this->error = ' El ciclo no existe.';
            }

        }else if($this->post('id') != ''){
        $this->option = '';
        $this->error = '';
        $this->archivo = '';
        $Ciclos = new Ciclos();
        $ciclo = $Ciclos->find($this->post('id'));
        if($ciclo->id != ''){
            if($ciclo->avance==0 && $ciclo->activo==1){
                try{
                    Session :: set_data('ciclo.id',$ciclo->id);
                    mysql_query("BEGIN") or die("CIERRE_1");
                    $res=$ciclo->cierre();
                    if($res==''){
                        $this->option="exito";
                        $historial=new Historial();
                        $historial->ciclos_id= Session :: get_data('ciclo.id');
                        $historial->usuario=Session :: get_data('usr.login');
                        $historial->descripcion="Se realizo el avance del ciclo ".$ciclo->numero." con el id ".$ciclo->id;
                        $historial->controlador="ciclos";
                        $historial->accion="avance";
                        $historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
                        $historial->save();
                        mysql_query("ROLLBACK") or die("CIERRE_3");
                        try{
                            $origen = $_SERVER['DOCUMENT_ROOT'].KUMBIA_PATH."logs/avance".$ciclo->numero;
                            if(is_dir($origen)){
                                $nombre = "avance".$ciclo->numero."_".time().".zip";
                                $destino = $_SERVER['DOCUMENT_ROOT'].KUMBIA_PATH."public/temp/".$nombre;                    
                                $zip = new ZipFolder($destino, $origen);
                                Utils::delete_directory($origen);
                                $this->archivo = $destino;
                                $this->link = KUMBIA_PATH . "public/temp/".$nombre;
                            }else{
                                $this->option = "error";
                                $this->error = "Ocurrio un error al crear el archivo.";
                            }
                        }catch(Exception $e){
                                $this->archivo = "";
                                $this->link = "";    
                        }
                    }else{
                        mysql_query("ROLLBACK") or die("CIERRE_3");
                        $this->option="error";
                        $this->error=$res;
                    }
                }catch(dbException $e){
                    $this->option = 'error';
                    $this->error .= ' Error al intentar realizar el cierre.'.$e;
                    mysql_query("ROLLBACK") or die("CIERRE_4");
                }
            }else{
                $this->option="error";
                $this->error="Ya se ha realizado el cierre del ciclo con anterioridad.";
            }

        }else{
            $this->option = 'error';
            $this->error = ' El ciclo no existe.';
        }
        }else{
            $this->option = 'error';
            $this->error = ' No se especific&oacute; el ciclo a cerrar.';
        }

    }


    public function disponible(){
        $this->set_response('view');
        $tabla = $this->post('tabla');
        $campo = $this->post('campo');
        $this->valor = $valor = $this->post('valor');
        $this->invalido = false;
        $this->disponible = false;
        if($valor != ''){
            $registros = new $tabla();
            if($registros->count($campo . " = '" . $valor . "'") == 0){
                $year = explode('-',$valor);
                if(strlen($year[0]) < 4){
                    $this->invalido .= 'A&ntilde;o no v&aacute;lido';
                }else{
                    $this->disponible = true;
                }
            }
        }
    }

    public function editar($id = '') {
        $this->option = 'error';
        $this->error = '';
        if($id != ''){
            $Ciclos = new Ciclos();
            $ciclo = $Ciclos->find($id);
            if($ciclo->id != ''){
                $this->option = 'captura';
                $tmp = explode('-',$ciclo->numero);
                $ciclo->annio = $tmp[0];
                $ciclo->inicio = str_replace('-', '/', Utils :: fecha_convertir($ciclo->inicio));
                $ciclo->fin = str_replace('-', '/', Utils :: fecha_convertir($ciclo->fin));
                $this->ciclo = $ciclo;
            }else{
                $this->error = ' El ciclo especificado no existe.';
            }
        }else if($this->post('id') != ''){
            $Ciclos = new Ciclos();
            $ciclo = $Ciclos->find($this->post('id'));
            if($ciclo->id != ''){
                $ciclo->inicio = Utils :: fecha_convertir($this->post('inicio'));
                $ciclo->fin = Utils :: fecha_convertir($this->post('fin'));
                //$ciclo->validates_date_in('inicio','fin');
                if($ciclo->save()){
                    $this->option = 'exito';
                    $historial=new Historial();
                    $historial->ciclos_id= Session :: get_data('ciclo.id');
                    $historial->usuario=Session :: get_data('usr.login');
                    $historial->descripcion="Se edito el ciclo ".$ciclo->numero." con el id ".$ciclo->id;
                    $historial->controlador="ciclos";
                    $historial->accion="editar";
                    $historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
                    $historial->save();
                }else{
                    $this->error .= ' Error al guardar en la BD.'. $ciclo->show_message();
                }
            }else{
                $this->error = ' El ciclo no existe.';
            }
        }else{
            $this->error .= ' No se especific&oacute; el ciclo.';
        }
    }

    public function eliminar($id = '') {
        if($id != ''){
            $this->option = 'captura';
            $id = intval($id, 10);
            $Ciclos = new Ciclos();
            $this->ciclo = $Ciclos->find($id);
            if($this->ciclo->id == ''){
                $this->option = 'error';
                $this->error = ' El ciclo no existe.';
            }
        }else if($this->post('id') != ''){
            $this->option = '';
            $this->error = '';
            $Ciclos = new Ciclos();
            $ciclo = $Ciclos->find($this->post('id'));
            if($ciclo->id != ''){
                // eliminado el ciclo
                try{
                    mysql_query("BEGIN") or die("CIERRE_1");
                    if($Ciclos->delete($this->post('id'))){
                        $this->option = 'exito';
                        mysql_query("COMMIT") or die("CIERRE_2");
                        $historial=new Historial();
                        $historial->ciclos_id= Session :: get_data('ciclo.id');
                        $historial->usuario=Session :: get_data('usr.login');
                        $historial->descripcion="Se elimino el ciclo ".$ciclo->numero." con el id ".$ciclo->id;
                        $historial->controlador="ciclos";
                        $historial->accion="eliminar";
                        $historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
                        $historial->save();
                    }else{
                        $this->option = 'error';
                        $this->error .= ' Error al intentar eliminar de la BD.';
                        mysql_query("ROLLBACK") or die("ELIM_1");
                    }
                }catch(dbException $e){
                    $this->option = 'error';
                    $this->error .= ' Error al intentar eliminar el ciclo. Esta ligado a grupos,cursos,alumnos.';
                    mysql_query("ROLLBACK") or die("ELIM_1");
                }
            }else{
                $this->option = 'error';
                $this->error = ' El ciclo no existe.';
            }
        }else{
            $this->option = 'error';
            $this->error = ' No se especific&oacute; el ciclo a eliminar.';
        }
    }

    public function faltantes($id=''){
        if($id!=""){
            $this->option = '';
            $this->error = '';
            $Ciclos = new Ciclos();
            $ciclo = $Ciclos->find($id);
            if($ciclo->id != ''){
                if($ciclo->avance==0 && $ciclo->activo==1){
                    try{
                        $calificaciones=new Calificaciones();
                        $archivo=$calificaciones->faltantes($ciclo->id);
                        $origen = $_SERVER['DOCUMENT_ROOT'].KUMBIA_PATH."logs/".$archivo;
                        if(file_exists($origen)){
                            $destino = $_SERVER['DOCUMENT_ROOT'].KUMBIA_PATH."public/temp/".$archivo;
                            if(Utils::cortarArchivo($origen,$destino)){
                                $this->archivo = "public/temp/".$archivo;
                                $this->option = 'exito';
                            }else{
                                $this->option = "error";
                                $this->error = "Ocurrio un error al crear el archivo.";
                            }
                        }else{
                            $this->option = "error";
                            $this->error = "Ocurrio un error al crear el archivo.";
                        }
                    }catch(Exception $e){
                        $this->option = "error";
                        $this->error = "Ocurrio un error.";
                    }
                }else{
                    $this->option = 'error';
                    $this->error = ' No se puede realizar el reporte.';
                }
            }else{
                $this->option = 'error';
                $this->error = ' No se especific&oacute; el ciclo.';
            }
        }else{
            $this->option = 'error';
            $this->error = ' No se especific&oacute; el ciclo.';
        }
    }


    public function index($pag = ''){
        $Ciclos = new Ciclos();
        $controlador = $this->controlador;
        $accion = $this->accion;
        $path = $this->path = KUMBIA_PATH;

        // busqueda
        $b = new Busqueda($controlador, $accion);
        $b->campos();

        // genera las condiciones
        $c = $b->condicion();
        $this->busqueda = $b;

        // cuenta todos los registros
        $this->registros = $Ciclos->count(($c == '' ? '' : $c));

        // paginacion
        $paginador = new Paginador($controlador, $accion);
        if($pag != ''){
            $paginador->guardarPagina($pag);
        }
        $paginador->estableceRegistros($this->registros);
        $paginador->generar();
        $this->paginador = $paginador;

        // ejecuta la consulta
        $this->ciclos = $Ciclos->find(
                            'conditions: ' . ($c == "" ? "1" : $c),
                            'order: numero DESC',
                            'limit: ' . ($paginador->pagina() * $paginador->rpp()) . ', '
                            . $paginador->rpp()
                            );


                            $usr_login = Session :: get_data('usr.login');
                            $this->acl_ciclos = array();
                            $acl = new gacl_extra();
                            $acos_arr = array(
                            'ciclos' => array(
                            'buscar', 'agregar', 'status', 'abrir', 'editar', 'eliminar', 'avance'
                            )

                            );
                            $this->acl_ciclos = $acl->acl_check_multiple($acos_arr, $usr_login);
                            $this->acl_ciclos=$this->acl_ciclos['ciclos'];
    }



    public function status($id = '') {
        if($id != ''){
            $this->option = 'captura';
            $id = intval($id, 10);
            $Ciclos = new Ciclos();
            $this->ciclo = $Ciclos->find($id);
            if($this->ciclo->id == ''){
                $this->option = 'error';
                $this->error = ' El ciclo no existe.';
            }else{
                if($this->ciclo->activo==0){
                    $agenda=new Agenda();
                    if(!$agenda->completa($this->ciclo->id)){
                        $this->option = 'error';
                        $this->error = ' La agenda del ciclo aun no esta completa. Aun no se definen los siguientes eventos: <br/><br/>';
                        foreach($agenda->faltantes as $evento){
                            $this->error.=$evento."<br/>";
                        }
                    }
                }else{
                    $this->option = 'error';
                    $this->error = ' El status del ciclo es activo. Los ciclos solo se pueden activar. <br/><br/>';

                }
            }
        }else if($this->post('id') != ''){
            $this->option = '';
            $this->error = '';
            $Ciclos = new Ciclos();
            $ciclo = $Ciclos->find($this->post('id'));
            if($ciclo->id != ''){
                if($ciclo->activo==0){
                    $agenda=new Agenda();
                    if(!$agenda->completa($ciclo->id)){
                        $this->option = 'error';
                        $this->error = ' La agenda del ciclo aun no esta completa. Aun no se definen los siguientes eventos: <br/><br/>';
                        foreach($agenda->faltantes as $evento){
                            $this->error.=$evento."<br/>";
                        }
                    }else{
                        // cambiando status del ciclo
                        $ac="";
                        if($ciclo->activo==1){
                            $ciclo->activo=0;
                            $ac="desactivo";
                        }else{
                            $ciclo->activo=1;
                            $ac="activo";
                            $desactivar=new Ciclos();
                            $desactivar->update_all("activo=0");
                        }
                        if($ciclo->save()){
                            $this->option = 'exito';
                            $historial=new Historial();
                            $historial->ciclos_id= Session :: get_data('ciclo.id');
                            $historial->usuario=Session :: get_data('usr.login');
                            $historial->descripcion="Se ".$ac." el ciclo ".$ciclo->numero." con el id ".$ciclo->id;
                            $historial->controlador="ciclos";
                            $historial->accion="status";
                            $historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
                            $historial->save();
                        }else{
                            $this->option = 'error';
                            $this->error .= ' Error al intentar modificar en la BD.';
                        }
                    }
                }else{
                    $this->option = 'error';
                    $this->error = ' El status del ciclo es activo. Los ciclos solo se pueden activar. <br/><br/>';

                }
            }else{
                $this->option = 'error';
                $this->error = ' El ciclo no existe.';
            }
        }else{
            $this->option = 'error';
            $this->error = ' No se especific&oacute; el ciclo a modificar.';
        }
    }


    public function optativas(){
        ini_set("memory_limit","128M");
        set_time_limit(0);
        $this->errores=array();
        $db=new db("localhost","sop","elsopsopsop","sop");
        $registros=$db->in_query(
                "SELECT alu_id,alu_codigo,cur_id,cur_materia,cur_grupo,cur_tipo,cur_turno FROM
                sop_alumnos
                INNER JOIN sop_lista_curso ON sop_alumnos.alu_id=sop_lista_curso.lis_id_alumno
                INNER JOIN sop_cursos ON sop_lista_curso.lis_id_curso=sop_cursos.cur_id
                ");
        $alumnos=array();
        foreach($registros as $r){
            $alumnos[$r['alu_id']][$r['alu_codigo']][$r['cur_id']]=$r;
        }
        $this->lista=array();
        $this->alertas=array();
        foreach($alumnos as $k => $alumno){
            foreach($alumno as $ka => $cursos){

                foreach($cursos as $kc => $reg){
                    $alumno=new Alumnos();
                    $alumno=$alumno->find_first("codigo='".$reg['alu_codigo']."'");
                    if($alumno->id!=''){
                        $cuenta=$alumno->count("codigo='".$reg['alu_codigo']."'");
                        if($cuenta==1){

                            $grado=$reg['cur_grupo'][0];
                            $letra=$reg['cur_grupo'][1];
                            $turno=$reg['cur_turno'];
                            $grupo=new Grupos();
                            $grupo=$grupo->find_first("ciclos_id='3' AND grado='$grado' AND letra='$letra' AND turno='$turno' AND oferta_id=1");
                            if($grupo->id!=''){
                                $curso=new Cursos();

                                $curso=$curso->find_by_sql(
                                "SELECT cursos.* FROM
                                    grupos
                                    INNER JOIN cursos ON grupos.id=cursos.grupos_id
                                    INNER JOIN  materias ON cursos.materias_id=materias.id
                                    WHERE grupos.id=".$grupo->id." AND materias.nombre='".$reg['cur_materia']."'
                                    AND (materias.tipo='TLR' OR  materias.tipo='PRO' OR materias.tipo='OPT')"
                                    );
                                    $curso_id=$curso['id'];
                                    if($curso['id']==''){
                                        $materia=new Materias();
                                        $materia=$materia->find_first("nombre= '".$reg['cur_materia']."' AND semestre='".$grado."' AND (materias.tipo='TLR' OR  materias.tipo='PRO' OR materias.tipo='OPT') ");
                                        //$this->errores[]="SELECT *  FROM materias WHERE nombre LIKE '%".$reg['cur_materia']."%'";
                                        if($materia->id==''){

                                            $materia=new Materias();
                                            $materia->clave='sop_'.$reg['cur_materia'];
                                            $materia->nombre=$reg['cur_materia'];
                                            $materia->descripcion='materia importada del sop.';
                                            $materia->semestre=$grado;
                                            $t='';
                                            if($reg['cur_tipo']=='op1' || $reg['cur_tipo']=='op2')
                                            $t="OPT";
                                            else if($reg['cur_tipo']=='tal')
                                            $t="TLR";
                                            else
                                            $t="PRO";

                                            $materia->tipo=$t;
                                            $materia->save();

                                            $this->alertas[]=" Se creo la materia ".$reg['cur_materia'];
                                        }
                                        $curso=new Cursos();
                                        $curso->estado_id="1";
                                        $curso->grupos_id=$grupo->id;
                                        $curso->materias_id=$materia->id;
                                        $curso->profesores_id="295";
                                        $curso->observaciones='';
                                        $curso->save();
                                        $curso_id=$curso->id;
                                        $this->alertas[]=" Se creo el curso ".$grado.$letra.$turno.' '.$reg['cur_materia'];
                                    }
                                    $inscibir=new Alumnoscursos();
                                    $inscibir->alumnos_id=$alumno->id;
                                    $inscibir->cursos_id=$curso_id;
                                    $inscibir->save();
                                    $this->lista[]=$alumno->id."-".$curso_id." xx ".$reg['alu_codigo']. "-".$reg['cur_grupo'].'-'.$reg['cur_turno']."-".$reg['cur_materia'];
                         }else{
                             $this->errores[]="No existe el grupo ".$grado.$letra.$turno;
                         }

                        }else{
                            $this->errores[]="Existen ".$cuenta." alumnos con el codigo ".$reg['alu_codigo'];
                        }

                    }else{
                        $this->errores[]="No existe el alumno con el codigo ".$reg['alu_codigo'];
                    }
                }

            }
        }

    }



}
?>