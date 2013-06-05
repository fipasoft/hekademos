<?php
// sp5, Creado el 27/09/2008
/**
 * Grupos
 *
 * @package
 * @author     mimeks <mimex@fipasoft.com.mx>
 * @copyright  2008 FiPa Software (contacto at fipasoft.com.mx)
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL)
 *
 */
 Kumbia :: import('app.componentes.*');
 Kumbia :: import('lib.phpgacl.main');

 class GruposController extends ApplicationController{
    public $template = "system";

    public function curso($id = ''){
        if($id != ''){
            $this->option = 'vista';
            $id = intval($id, 10);
            $Cursos = new Cursos();
            $curso = $Cursos->find($id);
            $this->asignado=false;
            $this->estutor=false;

            if(!$curso->id == ''){
                $this->curso           =  $curso;
                $this->grupo           =  $curso->grupo();
                $this->ciclo           =  $this->grupo->ciclo();
                $this->alumnos         =  $curso->alumnosgrupo();
                if($this->curso->aprobado()){
                    $this->asignado=$this->curso->asignado();
                    $this->estutor=$curso->estutor();
                    if($this->asignado || $this->estutor || $curso->tutoradoencurso()){
                        $alumnoscursos=new Alumnoscursos();
                        $alumnoscursos=$alumnoscursos->find("cursos_id=".$this->curso->id);
                        $inscripciones=array();
                        foreach($alumnoscursos as $ac){
                            $inscripciones[$ac->alumnos_id]=$ac->id;
                        }
                        $this->inscripciones=$inscripciones;

                if(count($this->alumnos) > 0){
                    $this->calificaciones  =  $curso->calificaciones();
                    $this->asistencias  =  $curso->asistenciasResumen();
                    $this->parciales        =  $curso->parcialesResumen();
                    $this->nParciales        =  count($this->hdrParciales);

                     // acl
                    $usr_login = Session :: get_data('prof.usr.login');
                    $this->acl_asistencias = array();
                    $this->acl_calificaciones = array();
                    $this->acl_grupos = array();
                    $acl = new gacl_extra();
                     $acos_arr = array(
                            'calificaciones' => array(
                            'ver'
                            )

                    );
                    $this->acl_calificaciones = $acl->acl_check_multiple($acos_arr, $usr_login);

                    $acos_arr=array(
                    'asistencias' => array(
                            'ver'
                            )
                    );

                    $this->acl_asistencias=$acl->acl_check_multiple($acos_arr,$usr_login);


                    $acos_arr=array(
                    'grupos' => array(
                            'index'
                            )
                    );

                    $this->acl_grupos=$acl->acl_check_multiple($acos_arr,$usr_login);

                    $acos_arr=array(
                    'inscripcion' => array(
                        'agregar', 'eliminar', 'articulo'
                    )
                    );

                    $this->acl_inscripcion=$acl->acl_check_multiple($acos_arr,$usr_login);

                    $this->acl_calificaciones=$this->acl_calificaciones['calificaciones'];
                    $this->acl_asistencias=$this->acl_asistencias['asistencias'];
                    $this->acl_grupos=$this->acl_grupos['grupos'];
                    $this->acl_inscripcion=$this->acl_inscripcion['inscripcion'];

                }else{
                    $this->option = 'alert';
                    $this->alert = ' No hay alumnos asignados a este curso.';
                }
                }else{
                    $this->option = 'alert';
                    $this->alert = ' El curso no es de su asignación.';
                }
                }else{
                    $this->option = 'alert';
                    $this->alert = ' El curso no esta aprobado aun.';
                }
            }else{
                $this->option = 'error';
                $this->error = ' El curso no existe.';
            }
        }else{
            $this->option = 'error';
            $this->error = ' No se especific&oacute; el curso.';
        }
    }

    public function horario($id = ''){
        $grupo = new Grupos();
        $grupo->find($id);
        if($grupo->id != ''){
            if($grupo->asignado()){
                $this->grupo = $grupo;
                // ejecuta la consulta
                $Cursos = new Cursos();
                $cursos = $Cursos->find_all_by_sql(
                    "SELECT " .
                        "cursos.id, " .
                        "cursos.grupos_id, " .
                        "cursos.materias_id," .
                        "cursos.profesores_id, " .
                        "cursos.estado_id, ".
                        "materias.nombre AS materia," .
                        "materias.tipo AS materiaTipo," .
                        "CONCAT(profesores.ap, ' ', profesores.am, ', ', profesores.nombre) AS profesor " .
                    "FROM " .
                        "cursos " .
                        "INNER JOIN grupos ON cursos.grupos_id = grupos.id " .
                        "INNER JOIN materias ON cursos.materias_id  = materias.id " .
                        "INNER JOIN profesores ON cursos.profesores_id  = profesores.id ".
                    "WHERE " .
                        "grupos_id = '" . $grupo->id . "' " .
                    "ORDER " .
                        "BY grupos.turno, grupos.grado, grupos.letra, materias.nombre "
                );


                $nCursos = count($cursos);
                if($nCursos > 0){
                    $this->option = 'vista';
                    $this->nCursos = $nCursos;
                    $this->cursos = $cursos;
                }else{
                    $this->option = 'alert';
                    $this->error = 'No hay cursos asignados a este grupo.';
                }
            }else{
                $this->option = 'error';
                $this->error = 'No ha sido asignado al grupo.';
            }
        }else{
            $this->option = 'error';
            $this->error = 'El id de grupo no es v&aacute;lido';
        }
    }

    public function imprimir( $id = '' ){
        if( $id != '' ){
            $this->option = 'selector';
            $grupo = new Grupos();
            $grupo = $grupo->find($id);
            if( $grupo->id != '' ){
                if( $grupo->asignado() ){
                    $this->alumnos = $grupo->alumnos();
                    if(count($this->alumnos) > 0){
                        $this->grupo = $grupo;
                        $cs=$this->grupo->cursos();
                        $this->cursos=array();
                        if($this->grupo->estutor()){
                        $this->cursos=$cs;
                        }else{
                        foreach($cs as $c){
                            if($c->aprobado() && $c->asignado())
                            $this->cursos[]=$c;
                        }
                        }
                    }else{
                        $this->option = 'alert';
                        $this->error = 'No hay alumnos inscritos a este grupo.';
                    }
                }else{
                    $this->option = 'error';
                    $this->error = 'El grupo est&aacute; fuera de su asignaci&oacute;n.';
                }
            }else{
                $this->option = 'error';
                $this->error = 'El id del grupo no es valido.';
            }
        }else{
            $grupo = new Grupos();
            $grupo = $grupo->find( $this->post('grupos_id') );
            $alumnos = $this->post('alumnos');

            if( $grupo->id != '' ){
                if( $grupo->asignado() ){
                        if( count($alumnos) > 0){
                            if( count( array_diff($alumnos, $grupo->alumnos_ids()) ) == 0 ){
                                switch( $this->post('reporte') ){
                                    case 'boletas':
                                            require('app/reportes/xls.boleta.php');
                                            ob_end_clean();
                                            $this->set_response("view");
                                            $reporte = new XLSBoleta( $grupo->id, $alumnos );
                                            $reporte->generar();
                                        break;

                                    case 'resumen':
                                            require('app/reportes/xls.resumen.php');
                                            ob_end_clean();
                                            $this->set_response("view");
                                            $reporte = new XLSResumen( $grupo->id );
                                            $reporte->generar();
                                        break;

                                    case 'lista':
                                            $curso_id=$this->post('cursos_select');
                                            $grupo_id=$this->post('grupos_id');
                                            $curso=new Cursos();
                                            if($curso->exists("id=".$curso_id." AND grupos_id=".$grupo_id)){
                                            //$curso=$curso->find("id=".$curso_id." AND grupos_id=".$grupo_id);
                                            require('app/reportes/xls.lista.php');
                                            ob_end_clean();
                                            $this->set_response("view");
                                            $reporte = new XLSLista( $curso_id );
                                            $reporte->generar();
                                            }else{
                                            $this->option = 'error';
                                            $this->error = 'El curso no pertenece al grupo.';

                                            }

                                        break;

                                        case 'inscritos':
                                            $cursos=$this->post('cursos_chk');
                                            $grupo_id=$this->post('grupos_id');
                                            $curso=new Cursos();
                                            if(is_array($cursos)){
                                            //$curso=$curso->find("id=".$curso_id." AND grupos_id=".$grupo_id);
                                            require('app/reportes/xls.inscritos.php');
                                            ob_end_clean();
                                            $this->set_response("view");
                                            $reporte = new XLSInscritos($cursos);
                                            $reporte->generar();
                                            }else{
                                            $this->option = 'error';
                                            $this->error = 'Ha ocurrido un error.';

                                            }

                                        break;

                                        case 'ast':
                                            ini_set ('max_execution_time', '0' );
                                            $curso_id=$this->post('cursos_select');
                                            $grupo_id=$this->post('grupos_id');
                                            $curso=new Cursos();
                                            if($curso->exists("id=".$curso_id." AND grupos_id=".$grupo_id)){
                                            //$curso=$curso->find("id=".$curso_id." AND grupos_id=".$grupo_id);
                                            require('app/reportes/xls.asistencias.php');
                                            ob_end_clean();
                                            $this->set_response("view");
                                            $reporte = new XLSAsistencias( $curso_id );
                                            $reporte->generar();
                                            }else{
                                            $this->option = 'error';
                                            $this->error = 'El curso no pertenece al grupo.';

                                            }

                                        break;


                                        case 'cal':
                                            $cursos=$this->post('cursos_chk');
                                            $grupo_id=$this->post('grupos_id');
                                            $curso=new Cursos();
                                            if(is_array($cursos)){
                                            //$curso=$curso->find("id=".$curso_id." AND grupos_id=".$grupo_id);
                                            require('app/reportes/xls.calificaciones.php');
                                            ob_end_clean();
                                            $this->set_response("view");
                                            $reporte = new XLSCalificaciones($cursos);
                                            $reporte->generar();
                                            }else{
                                            $this->option = 'error';
                                            $this->error = 'Ha ocurrido un error.';

                                            }

                                        break;


                                    default:
                                            $this->option = 'error';
                                            $this->error = 'El tipo de reporte seleccionado no es v&aacute;lido.';
                                        break;

                                }
                            }else{
                                $this->option = 'error';
                                $this->error = 'El alumno est&aacute; fuera de su asignaci&oacute;n.';
                                exit;
                            }
                    }else{
                        $this->option = 'alert';
                        $this->error = 'No se seleccionaron alumnos.';
                    }
                }else{
                    $this->option = 'error';
                    $this->error = 'El grupo est&aacute; fuera de su asignaci&oacute;n.';
                }
            }else{
                $this->option = 'error';
                $this->error = 'El id del grupo no es valido.';
            }

        }
    }

     public function index($pag = ''){
        $Grupos = new Grupos();
        $ciclo_id = Session :: get_data('prof.ciclo.id');

        $controlador = $this->controlador;
        $accion = $this->accion;
        $path = $this->path = KUMBIA_PATH;

        $this->ofertas=new Oferta();
        $this->ofertas=$this->ofertas->find();

        $this->tutorias=Session :: get_data('prof.usr.tutorias');
        $usr_login = Session :: get_data('prof.usr.login');

        // acl
        $this->acl = array();
        $acl = new gacl_extra();
        $acos_arr = array(
            'grupos' => array(
                'agregar', 'asignar', 'editar', 'eliminar', 'ver'
            )
        );
        $this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);

        // busqueda
        $b = new Busqueda($controlador, $accion);
        $b->campos();

        // genera las condiciones
        $c = $b->condicion(array('oferta_id'));
        $c .= ($c == '' ? '' : 'AND ') . "ciclos_id = '" . $ciclo_id . "'";
        // verifica si el usuario solo puede revisar grupos por asignacion
        $asignaciones = $Grupos->asignados();
        if(count($asignaciones)>0){
        if(!in_array('ALL', $asignaciones)){
            $asg = '';
            foreach($asignaciones as $grupos_id){
                $asg .= ($asg == '' ? '' : 'OR ') . " grupos.id = '" . $grupos_id . "' ";
            }
            $c .= ($c == '' ? '' : ' AND ') . '(' . $asg . ')';
        }

        $this->busqueda = $b;
        // cuenta todos los registros
        $this->registros = $Grupos->count(($c == '' ? '' : $c));
        if($this->registros==0){
            $this->option="error";
            $this->error="No cuenta con ningun grupo asignado.";
        }else{

        // paginacion
        $paginador = new Paginador($controlador, $accion);
        if($pag != ''){
            $paginador->guardarPagina($pag);
        }
        $paginador->estableceRegistros($this->registros);
        $paginador->generar();
        $this->paginador = $paginador;

        // ejecuta la consulta
        $this->grupos = $Grupos->find(
                            'conditions: ' . ($c == "" ? "1" : $c),
                            'order: turno, grado, letra',
                            'limit: ' . ($paginador->pagina() * $paginador->rpp()) . ', '
                                      . $paginador->rpp()
                          );

        $this->option="vista";
        }
        }else{
            $this->option="error";
            $this->error="No cuenta con ningun grupo asignado.";
        }
        $Ciclos = new Ciclos();
        $this->ciclo = $Ciclos->find($ciclo_id);
        $Ciclos = $Ciclos->find("columns: id, numero", "conditions: abierto = '1' ", "order: numero DESC");
        $this->ciclos = $Ciclos;

    }

    public function ver($id = ''){
        $grupo = new Grupos();
        $grupo = $grupo->find($id);
            $this->asignado=false;
            $this->estutor=false;
        if($grupo->id != ''){
            $this->asignado=$grupo->asignado();
            $this->estutor=$grupo->estutor();
            if($grupo->asignado()){

                $ciclo   = $grupo->ciclo();
                $cursos  = $grupo->cursos();
                $alumnos = $grupo->alumnos();
                $this->ciclo     =   $ciclo;
                $this->grupo     =   $grupo;
                if(count($alumnos) > 0){
                    $this->cursos    =   $cursos;
                    $this->option    =   'vista';
                    $this->alumnos   =   $alumnos;
                    $this->asistencias = $grupo->asistencias($cursos);
                    $this->calificaciones = $grupo->calificaciones();

                    $usr_login = Session :: get_data('prof.usr.login');

                    $this->acl_grupos = array();
                    $acl = new gacl_extra();
                    $acos_arr=array(
                    'grupos' => array(
                        'imprimir', 'horario', 'curso'
                            )
                    );

                    $this->acl_grupos=$acl->acl_check_multiple($acos_arr,$usr_login);
                    $this->acl_grupos=$this->acl_grupos['grupos'];
                }else{
                    $this->option = 'alert';
                    $this->alert = ' No hay alumnos inscritos en el grupo.';
                }
            }else{
                $this->option = 'error';
                $this->error = ' No esta asignado al grupo.';
            }
        }else{
            $this->option = 'error';
            $this->error = ' No se especific&oacute; un id de grupo v&aacute;lido.';
        }
    }

 }
?>