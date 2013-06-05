<?php

// sp5, Creado el 30/09/2008
/**
 * Cursos Controller
 *
 * @package
 * @author     mimeks <mimex@fipasoft.com.mx>
 * @copyright  2008 FiPa Software (contacto at fipasoft.com.mx)
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL)
 *
 */
Kumbia :: import('app.componentes.*');
Kumbia :: import('lib.excel.main');

class CursosController extends ApplicationController {
    public $template = "system";

    public function exportar($grp_id = '') {
        $this->set_response("view");
        require ('app/reportes/xls.cursos.php');
        $ciclo_id = Session :: get_data('prof.ciclo.id');
        $reporte = new XLSCursos($ciclo_id, $grp_id);
        $reporte->generar();

    }

    public function grupo($id, $pag) {
        if (isset ($id) && trim($id) != '') {
            $Cursos = new Cursos();
            $controlador = $this->controlador;
            $accion = $this->accion;
            $path = $this->path = KUMBIA_PATH;
            $ciclo_id = Session :: get_data('prof.ciclo.id');
            $this->acl = array ();
            $this->grp = $id;
            $this->usr_grupos = Session :: get_data('prof.usr.grupos');

            $usr_id = Session :: get_data('prof.usr.id');


            $acceso = Session :: get_data('prof.usr.acceso');
            $acl = new gacl();

            // acl
            $usr_login = Session :: get_data('prof.usr.login');
            $this->acl = array ();
            $acl = new gacl_extra();
            $acos_arr = array (
                'cursos' => array (
                    'agregar',
                    'exportar',
                    'generar',
                    'buscar',
                    'ver',
                    'grupo',
                    'editar',
                    'eliminar'
                )
            );
            $this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);

            $acos_arr = array (
                'inscripcion' => array (
                    'agregar',
                    'eliminar'
                )
            );

            $this->acl_inscripcion = $acl->acl_check_multiple($acos_arr, $usr_login);
            $grupos = new Grupos();
            $grupo = $this->grupo = $grupos->find($id);
            if ($this->acl['agregar']) {
                if ($grupo->nCursos() <= 0) {
                    $this->acl['agregar'] = false;
                }
            }

            // busqueda
            $b = new Busqueda($controlador, $accion);
            $b->establecerCondicion('profesor', "CONCAT(profesores.nombre, ' ', profesores.ap, ' ', profesores.am) " .
            "LIKE '%" . $b->campo('profesor') . "%' ");

            $c .= ($c == '' ? '' : 'AND ') . "grupos_id = '" . $grupo->id . "'";

            $this->busqueda = $b;

            // cuenta todos los registros
            $from = "cursos " .
            "INNER JOIN grupos ON cursos.grupos_id = grupos.id " .
            "INNER JOIN materias ON cursos.materias_id  = materias.id " .
            "INNER JOIN profesores ON cursos.profesores_id  = profesores.id ";


            $this->tutorias=Session :: get_data('prof.usr.tutorias');
            $gps='';
            foreach($this->tutorias as $id=>$t){
                $gps.="grupos_id='".$id."' OR ";
            }
            $gps=substr($gps,0,strlen($gps)-3);

            $condicion=" grupos.ciclos_id = '" . $ciclo_id . "' AND profesores_id=".$usr_id ."   AND cursos.estado_id='3' ";

            $this->registros = $Cursos->count_by_sql("SELECT COUNT(*) " .
            "FROM " . $from .
             "WHERE (( ".$condicion.") ".($gps == '' ? '' : " OR ( ($gps) AND grupos.ciclos_id = '" . $ciclo_id . "' AND cursos.estado_id='3' ) ").")".
             ($c == '' ? '' : ' AND ' . $c));

            // paginacion
            $paginador = new Paginador($controlador, $accion, $id);

            if ($pag != '') {
                $paginador->guardarPagina($pag);
            }

            $paginador->estableceRegistros($this->registros);
            $paginador->generar();
            $this->paginador = $paginador;

            // ejecuta la consulta
            $this->cursos = $Cursos->find_all_by_sql("SELECT " .
            "materias.nombre as materia, " .
            "cursos.id, cursos.grupos_id, " .
            "cursos.materias_id," .
            "cursos.estado_id, " .
            "cursos.profesores_id, " .
            "cursos.inicio " .
            "FROM " . $from .
            "WHERE (( ".$condicion.") ".($gps == '' ? '' : " OR (($gps) AND grupos.ciclos_id = '" . $ciclo_id . "' AND cursos.estado_id='3' ) ").")".
             ($c == '' ? '' : ' AND ' . $c).
            "ORDER BY grupos.turno, grupos.grado, grupos.letra, materias.nombre " .
            "LIMIT " . ($paginador->pagina() * $paginador->rpp()) . ', ' . $paginador->rpp());
            $Ciclos = new Ciclos();
            $this->ciclo = $Ciclos->find($ciclo_id);
            $Ciclos = $Ciclos->find('order: numero DESC');
            $this->ciclos = $Ciclos;
            $this->acceso = $acceso;
            $this->option = 'vista';
            $this->acl = $this->acl['cursos'];
            $this->acl_inscripcion = $this->acl_inscripcion['inscripcion'];

        } else {
            $this->option = 'error';
            $this->error = 'No tiene permiso para accesar a la pagina.';
        }

    }

    public function index($pag = '') {
        $Cursos = new Cursos();
        $controlador = $this->controlador;
        $accion = $this->accion;
        $path = $this->path = KUMBIA_PATH;
        $ciclo_id = Session :: get_data('prof.ciclo.id');
        $this->acl = array ();
        $acceso = Session :: get_data('prof.usr.acceso');
        $acl = new gacl();
        $usr_id = Session :: get_data('prof.usr.id');

        $this->usr_grupos = Session :: get_data('prof.usr.grupos');

        $this->obs = 'no_';
        if (in_array('director', $this->usr_grupos) || in_array('secretario', $this->usr_grupos) || in_array('plantilla', $this->usr_grupos))
            $this->obs = '';
        // acl
        $this->login = $usr_login = Session :: get_data('prof.usr.login');
        $this->acl = array ();
        $acl = new gacl_extra();
        $acos_arr = array (
            'cursos' => array (
                'agregar',
                'exportar',
                'generar',
                'buscar',
                'ver',
                'grupo',
                'editar',
                'eliminar'
            )
        );
        $this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);

        $acos_arr = array (
            'inscripcion' => array (
                'agregar',
                'eliminar'
            )
        );

        $this->acl_inscripcion = $acl->acl_check_multiple($acos_arr, $usr_login);
        // busqueda
        $b = new Busqueda($controlador, $accion);

        // genera las condiciones
        $c = $b->condicion(array (
            'estado_id',
            'oferta_id'
        ));

        $b->guardar();
        $this->busqueda = $b;

        // cuenta todos los registros
        if(in_array("root",$this->usr_grupos) || in_array("administradores",$this->usr_grupos)){
            $qp="";
        }else{
            $qp="AND cursos.profesores_id= ".$usr_id;

        }

        $this->tutorias=Session :: get_data('prof.usr.tutorias');
        $condicionTutor = '';
        if(count($this->tutorias)>0){
        $gps='';
        foreach($this->tutorias as $id=>$t){
            $gps.="grupos_id='".$id."' OR ";
        }

        $gps=substr($gps,0,strlen($gps)-3);
        $condicionTutor = " OR  ( ($gps) AND grupos.ciclos_id = '" . $ciclo_id . "' AND cursos.estado_id='3' ) ";
        }
        $from = "cursos " .
        "INNER JOIN grupos ON cursos.grupos_id = grupos.id " .
        "INNER JOIN materias ON cursos.materias_id  = materias.id " .
        "INNER JOIN profesores ON cursos.profesores_id  = profesores.id ";
        $this->registros = $Cursos->count_by_sql("SELECT COUNT(*) " .
        "FROM " . $from .
        "WHERE (grupos.ciclos_id = '" . $ciclo_id . "' ".$qp."  AND cursos.estado_id='3' ) " .
            $condicionTutor.
         ($c == '' ? '' : ' AND ' . $c));

        // paginacion
        $paginador = new Paginador($controlador, $accion);
        if ($pag != '') {
            $paginador->guardarPagina($pag);
        }
        $paginador->estableceRegistros($this->registros);
        $paginador->generar();
        $this->paginador = $paginador;

        // ejecuta la consulta
        $this->cursos = $Cursos->find_all_by_sql("SELECT " .
        "materias.nombre as materia, " .
        "cursos.id, cursos.grupos_id, " .
        "cursos.materias_id," .
        "cursos.profesores_id, " .
        "cursos.estado_id, " .
        "cursos.observaciones, " .
        "cursos.inicio, " .
        "grupos.id as grupos_id " .
        "FROM " . $from .
        "WHERE (grupos.ciclos_id = '" . $ciclo_id . "' ".$qp."  AND cursos.estado_id='3' )" .
            $condicionTutor .
         ($c == '' ? '' : ' AND ' . $c).
        "ORDER BY grupos.turno, grupos.grado, grupos.letra, materias.nombre " .
        "LIMIT " . ($paginador->pagina() * $paginador->rpp()) . ', ' . $paginador->rpp());
        $Ciclos = new Ciclos();
        $this->ciclo = $Ciclos->find($ciclo_id);
        $Ciclos = $Ciclos->find("columns: id, numero", "conditions: abierto = '1' ", "order: numero DESC");
        $this->ciclos = $Ciclos;
        $this->acceso = $acceso;
        $this->acl = $this->acl['cursos'];
        $this->acl_inscripcion = $this->acl_inscripcion['inscripcion'];
        $this->estados = new Estado();
        $this->estados = $this->estados->find();
        $this->ofertas = new Oferta();
        $this->ofertas = $this->ofertas->find();

    }

    public function not_found($params = '') {
        $this->redirect('cursos');
    }

    public function ver($id = '') {
        if ($id != '') {
            $this->option = 'vista';
            $id = intval($id, 10);
            $Cursos = new Cursos();
            $curso = $Cursos->find($id);
            $this->asignado=false;
            $this->estutor=false;

            if (!$curso->id == '') {
                    $this->asignado=$curso->asignado();
                    $this->estutor=$curso->estutor();
                    if($this->asignado || $this->estutor || $curso->tutoradoencurso()){

                    $this->curso = $curso;
                    $this->grupo = $curso->grupo();
                    $this->ciclo = $this->grupo->ciclo();
                    $this->alumnos = $curso->alumnosgrupo();
                    if (count($this->alumnos) > 0) {
                        $this->calificaciones = $curso->calificaciones();
                        $this->parciales = $curso->parcialesResumen();
                        $this->nParciales = count($this->hdrParciales);
                        $alumnoscursos = new Alumnoscursos();
                        $alumnoscursos = $alumnoscursos->find("cursos_id=" . $this->curso->id);
                        $inscripciones = array ();
                        foreach ($alumnoscursos as $ac) {
                            $inscripciones[$ac->alumnos_id] = $ac->id;
                        }
                        $this->inscripciones = $inscripciones;

                        $usr_login = Session :: get_data('prof.usr.login');
                        $this->acl_asistencias = array ();
                        $this->acl_calificaciones = array ();
                        $this->acl_grupos = array ();
                        $acl = new gacl_extra();
                        $acos_arr = array (
                            'calificaciones' => array (
                                'ver'
                            )
                        );
                        $this->acl_calificaciones = $acl->acl_check_multiple($acos_arr, $usr_login);

                        $acos_arr = array (
                            'asistencias' => array (
                                'ver'
                            )
                        );

                        $this->acl_asistencias = $acl->acl_check_multiple($acos_arr, $usr_login);

                        $acos_arr = array (
                            'grupos' => array (
                                'index'
                            )
                        );

                        $this->acl_grupos = $acl->acl_check_multiple($acos_arr, $usr_login);

                        $acos_arr = array (
                            'inscripcion' => array (
                                'agregar',
                                'eliminar',
                                'articulo'
                            )
                        );

                        $this->acl_inscripcion = $acl->acl_check_multiple($acos_arr, $usr_login);

                        $this->acl_calificaciones = $this->acl_calificaciones['calificaciones'];
                        $this->acl_asistencias = $this->acl_asistencias['asistencias'];
                        $this->acl_grupos = $this->acl_grupos['grupos'];
                        $this->acl_inscripcion = $this->acl_inscripcion['inscripcion'];

                    } else {
                        $this->option = 'alert';
                        $this->alert = ' No hay alumnos asignados a este curso.';
                    }


                } else {
                    $this->option = 'error';
                    $this->error = ' No tiene asignado este curso.';

                }
            } else {
                $this->option = 'error';
                $this->error = ' El curso no existe.';
            }
        } else {
            $this->option = 'error';
            $this->error = ' No se especific&oacute; el curso.';
        }
    }
}
?>
