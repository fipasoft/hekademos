<?php
Kumbia :: import('app.componentes.*');
Kumbia :: import('lib.kumbia.utils');
Kumbia :: import('lib.upload.main');
Kumbia :: import('lib.excel.main');

/** SP5
 * Creado el 03/07/2008
 * Copyright (C) 2008 FiPa Software (contacto at fipasoft.com.mx)
 */

class AlumnosController extends ApplicationController {
    public $template = "system";

    public function cursos($id=''){
        $this->estutor=false;
        if($id!=''){
            $alumno=new Alumnos();
            $this->alumno=$alumno=$alumno->find($id);
            if($alumno->id!=''){
                $this->estutor=$alumno->estutor();
                if($this->estutor){
                $this->option='vista';
                $this->cursos=$alumno->cursos(Session::get_data("prof.ciclo.id"));

                $this->registros=count($this->cursos);
                $usr_login = Session :: get_data('prof.usr.login');
                $this->acl = array ();
                $acl = new gacl_extra();
                $acos_arr = array (
                    'cursos' => array (
                    'ver',
                    'grupo',
                    )
                    );
            $this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);
            $this->acl=$this->acl['cursos'];

                $this->articulos=array();
                $articulos=new Alumnosconarticulo();
                                $articulos=$articulos->articulosAlumno($alumno->id);
                                    foreach($articulos as $art){
                                        $this->articulos[$art->cursos_id]=$art;
                                    }

            }else{
            $this->option='error';
            $this->error="No tiene acceso a la informacion";
            }

            }else{
            $this->option='error';
            $this->error="El alumno no existe";

            }

        }else{
            $this->option='error';
            $this->error="No tiene permiso para ver la pagina";

        }
    }

    public function exportar($grp_id = ''){
        $this->set_response("view");
        require('app/reportes/xls.alumnos.php');
        $ciclo_id = Session :: get_data('prof.ciclo.id');
        $reporte = new XLSAlumnos($ciclo_id);
        $reporte->generar();
     }

         public function kardex($id=''){
        $this->estutor=false;
        if($id != ''){
            $alumno = new Alumnos();
            $this->alumno = $alumno->find($id);
            if($this->alumno->id != ''){
                $this->estutor=$alumno->estutor();
                if($this->estutor){
                $this->option="vista";
            $db = $db = db::raw_connect();
            $calificaciones=$db->in_query(
                        " SELECT
                            ciclos.id as ciclo_id,ciclos.numero,ciclos.inicio,ciclos.fin,
                             grupos.id AS grupos_id,grupos.grado,grupos.letra,grupos.turno,
                             cursos.id AS cursos_id,cursos.materias_id,cursos.profesores_id,
                            materias.clave,materias.nombre AS nombre_materia,materias.tipo,
                             profesores.id AS profesores_id,profesores.nombre AS nombre_profesor,profesores.ap AS ap_profesor,profesores.am AS am_profesor,
                             calificaciones.alumnos_id,calificaciones.valor,calificaciones.oportunidades_id,
                             oportunidades.nombre AS oportunidad
                             FROM
                            ciclos
                            INNER JOIN grupos ON grupos.ciclos_id=ciclos.id
                            INNER JOIN cursos ON cursos.grupos_id=grupos.id
                            INNER JOIN alumnoscursos ON alumnoscursos.cursos_id=cursos.id
                            INNER JOIN calificaciones ON calificaciones.cursos_id=alumnoscursos.cursos_id
                            INNER JOIN materias ON cursos.materias_id=materias.id
                            INNER JOIN profesores ON cursos.profesores_id=profesores.id
                            INNER JOIN oportunidades ON oportunidades.id=calificaciones.oportunidades_id
                            WHERE alumnoscursos.alumnos_id=".$this->alumno->id." AND calificaciones.alumnos_id=".$this->alumno->id."
                            ORDER BY ciclos.inicio,grupos.grado,materias.nombre,calificaciones.oportunidades_id"
            );
            $datos=array();
            $resumen=array();
            $resumen['extra']=false;
            $resumen['aprobadas']=0;
            $resumen['promedio']=0;

            $puntaje=0;

            foreach($calificaciones as $cal){
                $datos[$cal['numero']][$cal['cursos_id']][$cal['oportunidades_id']]=$cal;

                if($cal['oportunidades_id']==1){
                        if(strToUpper($cal['tipo'])!='TLR'){
                            if($cal['valor']>59){
                                $resumen['aprobadas']++;
                                $puntaje+=$cal['valor'];
                            }
                        }
                }elseif($cal['oportunidades_id']==2){
                $resumen[$cal['numero']]['extra']=true;
                if(strToUpper($cal['tipo'])!='TLR'){
                            if($cal['valor']>59){
                                $resumen['aprobadas']++;
                                $puntaje+=$cal['valor'];
                            }
                        }
                }
            }

            $resumen['promedio']=$this->alumno->promedio;
            $resumen['aprobadas']=$this->alumno->aprobadas;
            $this->datos=$datos;
            $this->resumen=$resumen;

            $this->acl_cursos = array ();
            $acl = new gacl_extra();
            $acos_arr = array (
            'cursos' => array (
                'ver'
            )
            );
            $usr_login = Session :: get_data('usr.login');
            $this->acl_cursos = $acl->acl_check_multiple($acos_arr, $usr_login);
            $this->acl_cursos=$this->acl_cursos['cursos'];

            $this->acl_grupos = array ();
            $acos_arr = array (
            'grupos' => array (
                'ver','curso'
            )
            );
            $this->acl_grupos = $acl->acl_check_multiple($acos_arr, $usr_login);
            $this->acl_grupos=$this->acl_grupos['grupos'];
            }else{
            $this->option='error';
            $this->error="No tiene acceso a la informacion";
            }

            }else{
                $this->option = 'error';
                $this->error = ' El alumno no existe.';
            }
        }else{
                $this->option = 'error';
                $this->error = ' No tiene permiso para ver la pagina.';
            }
    }

    public function index($pag = '') {
        $alumnos = new Alumnos();
        $controlador = $this->controlador;
        $accion = $this->accion;
        $path = $this->path = KUMBIA_PATH;
        $ciclo_id = Session :: get_data('prof.ciclo.id');
        $profesor_id=Session :: get_data('prof.usr.id');
        $this->ofertas=new Oferta();
        $this->ofertas=$this->ofertas->find();
        $this->usr_grupos = Session :: get_data('prof.usr.grupos');

        // busqueda
        $b = new Busqueda($controlador, $accion);
        $b->establecerCondicion('nombre', "CONCAT(TRIM(alumnos.nombre), ' ', TRIM(ap), ' ', TRIM(am)) LIKE '%" . $b->campo('nombre') . "%' ");
        $b->establecerCondicion('situacion', "situaciones.id  = '" . $b->campo('situacion') . "' ");
        $b->establecerCondicion('oferta_id', "grupos.oferta_id  = '" . $b->campo('oferta_id') . "' ");
        // acl
        $usr_login = Session :: get_data('prof.usr.login');
        $this->acl = array ();
        $acl = new gacl_extra();
        $acos_arr = array (
            'alumnos' => array (
                'cursos',
                'ver',
                'exportar',
                'kardex',
                'buscar'
            )
        );
        $this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);
        //$this->acl['exportar']=true; //quitar al dar de alta en acl

        // asignados
        $grupo = new Grupos();
        $this->asignados = $grupo->asignados();

        // genera las condiciones
        $c = $b->condicion();
        $this->busqueda = $b;

        $Grupos = new Grupos();
        $asignaciones = $Grupos->asignados();


        $tutorias=Session :: get_data('prof.usr.tutorias');
        if(count($asignaciones)>0){
        if(!in_array('ALL', $asignaciones)){
            $asg = '';
            foreach($asignaciones as $grupos_id){
                $asg .= ($asg == '' ? '' : 'OR ') . " grupos.id = '" . $grupos_id . "' ";

            }
            $asg = " AND ($asg) ";
        }
        }

            // cuenta todos los registros
        if(in_array("root",$this->usr_grupos) || in_array("administradores",$this->usr_grupos)){
        $this->registros = $alumnos->count_by_sql("SELECT " .
        "COUNT(*) " .
        "FROM " .
        "(SELECT " .
        "alumnos.id " .
        "FROM " .
        "ciclos " .
        "Inner Join grupos ON ciclos.id = grupos.ciclos_id " .
        "Inner Join alumnosgrupo ON grupos.id = alumnosgrupo.grupos_id " .
        "Inner Join alumnos ON alumnosgrupo.alumnos_id = alumnos.id " .
        "Inner Join situaciones ON alumnos.situaciones_id = situaciones.id " .
        "WHERE " .
        "ciclos.id = '" . $ciclo_id . "'" .
         ($c == "" ? "" : "AND " . $c) . " ) " .
        "AS t1 ");
        }else{
                $this->registros = $alumnos->count_by_sql(
                "SELECT count(*)  FROM (
                ((
                    SELECT
                    DISTINCT(alumnos.id), alumnos.codigo, alumnos.nombre, alumnos.am, alumnos.ap,  alumnos.foto, situaciones.nombre AS situacion
                    FROM
                    grupos
                    INNER JOIN alumnosgrupo ON grupos.id=alumnosgrupo.grupos_id
                    INNER JOIN alumnos ON alumnosgrupo.alumnos_id=alumnos.id
                    Inner Join situaciones ON alumnos.situaciones_id = situaciones.id
                    WHERE
                    (grupos.ciclos_id='" . $ciclo_id . "' ".$asg." ". ($c == "" ? "" : "AND " . $c) .")
                    UNION
                    (
                    SELECT
                    DISTINCT(alumnos.id), alumnos.codigo, alumnos.nombre, alumnos.am, alumnos.ap,  alumnos.foto, situaciones.nombre AS situacion
                    FROM
                    cursos
                    INNER JOIN alumnoscursos ON cursos.id=alumnoscursos.cursos_id
                    INNER JOIN alumnos ON alumnoscursos.alumnos_id=alumnos.id
                    INNER JOIN alumnosgrupo ON alumnos.id=alumnosgrupo.alumnos_id
                    INNER JOIN grupos ON alumnosgrupo.grupos_id=grupos.id
                    INNER JOIN situaciones ON alumnos.situaciones_id = situaciones.id
                    WHERE " .
                    "(grupos.ciclos_id = '" . $ciclo_id . "' AND cursos.profesores_id=".$profesor_id." ".
                    ($c == "" ? "" : "AND " . $c) . " ) ORDER BY grupos.turno, grupos.grado, grupos.letra, alumnos.ap, alumnos.am, alumnos.nombre ) )
                    ) AS t1
                    )");

        }


        // paginacion
        $paginador = new Paginador($controlador, $accion);
        if ($pag != '') {
            $paginador->guardarPagina($pag);
        }

        $paginador->estableceRegistros($this->registros);
        $paginador->generar();
        $this->paginador = $paginador;

        if(in_array("root",$this->usr_grupos) || in_array("administradores",$this->usr_grupos)){
        // ejecuta la consulta
        $alos = $alumnos->find_all_by_sql("SELECT " .
        "alumnos.codigo, " .
        "alumnos.id, " .
        "alumnos.nombre, " .
        "alumnos.am, " .
        "alumnos.ap, " .
        "grupos.id AS grupos_id, " .
        "grupos.grado, " .
        "grupos.letra," .
        "grupos.turno, " .
        "alumnos.foto, " .
        "situaciones.nombre AS situacion " .
        "FROM " .
                "ciclos " .
                "Inner Join grupos ON ciclos.id = grupos.ciclos_id " .
                "INNER JOIN cursos ON grupos.id=cursos.grupos_id " .
                "INNER JOIN alumnoscursos ON cursos.id=alumnoscursos.cursos_id " .
                "INNER JOIN alumnos ON alumnoscursos.alumnos_id=alumnos.id " .
                "Inner Join situaciones ON alumnos.situaciones_id = situaciones.id " .
                "WHERE " .
                "(grupos.ciclos_id = '" . $ciclo_id . "' AND cursos.profesores_id=".$profesor_id." ".
                ($c == "" ? "" : "AND " . $c) . " ) ".
        "ORDER BY " .
        "grupos.turno, grupos.grado, grupos.letra, alumnos.ap, alumnos.am, alumnos.nombre " .
        "LIMIT " .
         ($paginador->pagina() * $paginador->rpp()) . ', ' .
        $paginador->rpp() . " ");
        }else{

        // ejecuta la consulta
        $alos = $alumnos->find_all_by_sql(
                    "SELECT * FROM (
                    (
                    (
                    (
                    SELECT
                    DISTINCT(alumnos.id), alumnos.codigo, alumnos.nombre, alumnos.am, alumnos.ap,  alumnos.foto, situaciones.nombre AS situacion
                    FROM
                    grupos
                    INNER JOIN alumnosgrupo ON grupos.id=alumnosgrupo.grupos_id
                    INNER JOIN alumnos ON alumnosgrupo.alumnos_id=alumnos.id
                    Inner Join situaciones ON alumnos.situaciones_id = situaciones.id
                    WHERE     (grupos.ciclos_id='" . $ciclo_id . "' ".$asg." ". ($c == "" ? "" : "AND " . $c) .")".
                    ")
                    UNION
                    (
                    SELECT
                    DISTINCT(alumnos.id), alumnos.codigo, alumnos.nombre, alumnos.am, alumnos.ap,  alumnos.foto, situaciones.nombre AS situacion
                    FROM
                    cursos
                    INNER JOIN alumnoscursos ON cursos.id=alumnoscursos.cursos_id
                    INNER JOIN alumnos ON alumnoscursos.alumnos_id=alumnos.id
                    INNER JOIN alumnosgrupo ON alumnos.id=alumnosgrupo.alumnos_id
                    INNER JOIN grupos ON alumnosgrupo.grupos_id=grupos.id
                    INNER JOIN situaciones ON alumnos.situaciones_id = situaciones.id
                    WHERE
                    (grupos.ciclos_id = '" . $ciclo_id . "' AND cursos.profesores_id=".$profesor_id." ".
                    ($c == "" ? "" : "AND " . $c) . " ) " .
                    ")
                    )ORDER BY  ap,am,nombre)AS t1
                    ) LIMIT " .
                     ($paginador->pagina() * $paginador->rpp()) . ', ' .
                    $paginador->rpp() . " ");
        }
        $this->alumnos = array ();

        foreach ($alos as $a) {
            $a->foto = '/hekademos/controlescolar/img/' . ($a->foto == '' ? 'sp5/persona.png' : 'alumnos/' . $a->foto . '?d=' . time());
            $this->alumnos[] = $a;
        }

        $ciclos = new Ciclos();
        $this->ciclo = $ciclos->find($ciclo_id);
        $this->ciclos = $ciclos->find("columns: id, numero", "conditions: abierto = '1' ", "order: numero DESC");
        $situaciones = new Situaciones();
        $this->situaciones = $situaciones->find("conditions: clave != '' AND clave != 'EGR' ");
        $this->opciones=false;
        $tutorias=Session :: get_data('prof.usr.tutorias');
        if(is_array($tutorias)){
            if(count($tutorias)>0)
                    $this->opciones=true;
        }
    }

    public function info() {
        $this->set_response('view');
        $tabla = $this->post('tabla');
        $campo = $this->post('campo');
        $this->valor = $valor = $this->post('valor');
        $this->arreglo = $this->post('arreglo');
        $this->invalido = false;
        $this->disponible = false;
        if ($valor != '') {
            $registros = new $tabla ();
            $registro = $registros->find_first($campo . " = '" . $valor . "'");
            if ($registro->id != '') {
                $this->registro = $registro;
                $this->disponible = true;
            }
        }
    }


    public function ver($id = '') {
        $Alumnos = new Alumnos();
        $alumno = $Alumnos->find(intval($id, 10));
        if ($alumno->id != '') {
            if($alumno->asignadoprofesor() || $alumno->estutor()){
            $this->option = 'vista';
            $this->alumno = $alumno;
            $this->tutores=$this->alumno->tutores();

            // acl
            $usr_login = Session :: get_data('prof.usr.login');
            $this->acl = array ();
            $acl = new gacl_extra();
            $acos_arr = array (
            'alumnos' => array (
                'cursos',
                'kardex'
            )
            );
            $this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);
        } else {
            $this->option = 'error';
            $this->error = 'El alumno no esta asignado.';
        }
        } else {
            $this->option = 'error';
            $this->error = 'El id de alumno no es v&aacute;lido.';
        }
    }
}
?>