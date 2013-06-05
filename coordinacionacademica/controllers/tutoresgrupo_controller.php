<?php
/** SP5
 * Creado el 03/07/2008
 * Copyright (C) 2008 FiPa Software (contacto at fipasoft.com.mx)
 */

Kumbia :: import('app.componentes.*');
Kumbia :: import('lib.phpgacl.main');
Kumbia :: import('lib.excel.main');

class TutoresgrupoController extends ApplicationController {
public $template = "system";

    public function asignar($id = ''){
        if($id != ''){
            $this->option = 'captura';
            $id = intval($id, 10);
            $Grupos = new Grupos();
            $this->grupo = $Grupos->find($id);
            $Ciclos = new Ciclos();
            $this->ciclo = $Ciclos->find($this->grupo->ciclos_id);

            if($this->grupo->id != ''){
                if($this->ciclo->abierto()){
                $profesores=new Profesores();
                $this->profesores=$profesores->find("1 ORDER BY ap,am,nombre");
            } else {
                    $this->option = 'error';
                    $this->error = ' El ciclo esta cerrado.';

                }

            }else{
                $this->option = 'error';
                $this->error = ' El grupo no existe.';
            }
        }else if($this->post('grupos_id') != ''){
            if($this->post('modo') == 'ajax'){
                $this->option = 'ajax';
                $this->set_response('view');
            }else{
                $this->option = '';
                $this->error = '';
                $Grupos = new Grupos();
                $grupo = $Grupos->find($this->post('grupos_id'));
                if($grupo->id != ''){
                    // eliminando las asignaciones anteriores del grupo
                    $tutores = new Tutoresgrupos();
                    if($tutores->delete("grupos_id = '" . $grupo->id . "'")){
                    $historial=new Historial();
                    $historial->ciclos_id= Session :: get_data('ciclo.id');
                    $historial->usuario=Session :: get_data('usr.login');

                    $historial->descripcion='Se eliminaron los tutores del grupo '.$grupo->grado.$grupo->letra.$grupo->turno;
                    $historial->controlador="tutoresgrupo";
                    $historial->accion="asignar";
                    $historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
                    $historial->save();

                        $this->option = 'exito';
                        // asignando el grupo
                        $usuarios = array_unique($this->post('usuarios'));
                        foreach($usuarios as $usuario_id){
                            if($usuario_id != ''){

                                $profesor=new Profesores();
                                $profesor=$profesor->find($usuario_id);
                                if($profesor->id != ''){
                                $tutoria = new Tutoresgrupos();
                                $tutoria->grupos_id = $grupo->id;
                                $tutoria->profesores_id = $usuario_id;
                                if(!$tutoria->save()){
                                    $this->option = 'error';
                                    $this->error .= ' Error al asignar la tutoria al profesor ' . $usuario_id . '.';
                                }else{
                                        $historial=new Historial();
                                        $historial->ciclos_id= Session :: get_data('ciclo.id');
                                        $historial->usuario=Session :: get_data('usr.login');

                                        $historial->descripcion='Se asigno el tutor '.$profesor->codigo." : ".$profesor->nombre()." al grupo ".$grupo->grado.$grupo->letra.$grupo->turno;
                                        $historial->controlador="tutoresgrupo";
                                        $historial->accion="asignar";
                                        $historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
                                        $historial->save();
                                }
                                }else{
                                    $this->option = 'error';
                                    $this->error .= ' Error al intentar asignar tutor al grupo. El profesor fue eliminado. ';
                                    break;
                                }
                            }
                        }
                    }else{
                        $this->option = 'error';
                        $this->error .= ' Error al intentar eliminar las tutorias del grupo ' .
                                        $grupo->verInfo() . ' en la BD.';
                    }
                }else{
                    $this->option = 'error';
                    $this->error = ' El grupo no existe.';
                }
            }
        }else{
            $this->option = 'error';
            $this->error = ' No se especific&oacute; el grupo.';
        }
    }

    function exportar(){
        $this->set_response("view");
        require('app/reportes/xls.tutoresgrupos.php');
        $ciclo_id = Session :: get_data('ciclo.id');
        $reporte = new XLSTutoresgrupos($ciclo_id);
        $reporte->generar();
    }



public function index($pag = ''){
        $Grupos = new Grupos();
        $ciclo_id = Session :: get_data('ciclo.id');

        $controlador = $this->controlador;
        $accion = $this->accion;
        $path = $this->path = KUMBIA_PATH;

        $this->ofertas=new Oferta();
        $this->ofertas=$this->ofertas->find();

        $usr_login = Session :: get_data('usr.login');
                // acl
        $this->acl = array();
        $acl = new gacl_extra();
        $acos_arr = array(
            'tutoresgrupo' => array(
                'asignar','ver','exportar'
            )
        );
        $this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);


        $Ciclos = new Ciclos();
        $this->ciclo = $Ciclos->find($ciclo_id);
        $Ciclos = $Ciclos->find("columns: id, numero", "conditions: abierto = '1' ", "order: numero DESC");
        $this->ciclos = $Ciclos;

        // busqueda
        $b = new Busqueda($controlador, $accion);
        if(trim($b->campo('tutor'))!=''){
            $b->establecerCondicion('tutor', "CONCAT(profesores.nombre, ' ', profesores.ap, ' ', profesores.am) " .
            "LIKE '%" . $b->campo('tutor') . "%'  OR CONCAT(profesores.ap, ' ', profesores.am, ' ', profesores.nombre) " .
            "LIKE '%" . $b->campo('tutor') . "%' ");
        }else{
            $b->establecerCondicion('tutor','');
        }

        if(trim($b->campo('codigo'))!=''){
            $b->establecerCondicion('codigo', "profesores.codigo " .
            "LIKE '%" . $b->campo('codigo') . "%' ");
        }else{
            $b->establecerCondicion('codigo','');
        }

        $b->campos();

        // genera las condiciones
        $c = $b->condicion(array('oferta_id'));
        $c .= ($c == '' ? '' : 'AND ') . "ciclos_id = '" . $ciclo_id . "'";

        $this->busqueda = $b;

        // cuenta todos los registros
        if(trim($b->campo('tutor'))!='' || trim($b->campo('codigo'))!=''){
        $from = "grupos " .
        "INNER JOIN tutoresgrupos ON grupos.id = tutoresgrupos.grupos_id " .
        "INNER JOIN profesores ON tutoresgrupos.profesores_id  = profesores.id ";

        $this->registros = $Grupos->count_by_sql("SELECT COUNT(*) " .
        "FROM " . $from .
        "WHERE ".
         ($c == '' ? '' :  $c));
        }else{
        $this->registros = $Grupos->count(($c == '' ? '1' :  $c));

        }

        if($this->registros==0){
            $this->option="error";
            $this->error="No se encontro ningun registro con los parametros especificados.";
        }else{
        // paginacion
        $paginador = new Paginador($controlador, $accion);
        if($pag != ''){
            $paginador->guardarPagina($pag);
        }
        $paginador->estableceRegistros($this->registros);
        $paginador->generar();
        $this->paginador = $paginador;
        if(trim($b->campo('tutor'))!='' || trim($b->campo('codigo'))!=''){
        // ejecuta la consulta
        $this->grupos = $Grupos->find_all_by_sql(
                            "SELECT grupos.* FROM grupos ".$from." WHERE ".
                            ($c == "" ? "1" : $c)." ".
                            'ORDER BY  grupos.turno, grupos.grado, grupos.letra '.
                            'LIMIT ' . ($paginador->pagina() * $paginador->rpp()) . ', '
                                      . $paginador->rpp()
                          );
        }else{
        $this->grupos = $Grupos->find(
                            ($c == "" ? "1" : $c)." ".
                            'ORDER BY  grupos.turno, grupos.grado, grupos.letra '.
                            'LIMIT ' . ($paginador->pagina() * $paginador->rpp()) . ', '
                                      . $paginador->rpp()
                          );

        }

        $this->option="vista";
        }


    }


    public function ver($id = ''){
        $id = intval($id, 10);
        $Profesores = new Profesores();
        $profesor = $Profesores->find($id);
        $profesor->fnacimiento = Utils :: fecha_convertir($profesor->fnacimiento);
        $profesor->fnacimiento = str_replace('-', '/', $profesor->fnacimiento);
        if($profesor->sexo != ''){
            $profesor->sexo = ($profesor->sexo == 'H' ? 'Hombre' : 'Mujer');
        }
        $this->profesor = $profesor;

        $usr_login = Session :: get_data('usr.login');
        $this->acl = array ();
        $acl = new gacl_extra();
        $acos_arr = array (
            'tutoresgrupo' => array (
                'horario'
            )
        );
        $this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);
        $this->acl=$this->acl["tutoresgrupo"];
    }

    public function horarioexcel($id=''){
        $this->set_response("view");
        require('app/reportes/xls.profesoreshorario.php');
        $ciclo_id = Session :: get_data('ciclo.id');
        $reporte = new XLSProfesoreshorario($ciclo_id,$id);
        $reporte->generar();
    }

    public function horario($id=''){
        if($id!=''){
        $profesor=new Profesores();
        $profesor=$profesor->find($id);
        if($profesor->id!=""){

        $ciclo=new Ciclos();
        $ciclo = $ciclo->find(Session :: get_data('ciclo.id'));
        $this->ciclo = $ciclo;
        $cursos = new Cursos();

        $from = "cursos " .
        "INNER JOIN grupos ON cursos.grupos_id = grupos.id " .
        "INNER JOIN materias ON cursos.materias_id  = materias.id " .
        "INNER JOIN profesores ON cursos.profesores_id  = profesores.id ";
        $cursos = new Cursos();
        $cursos = $cursos->find_all_by_sql("SELECT " .
        "materias.nombre as materia, " .
        "cursos.id, cursos.grupos_id, " .
        "cursos.materias_id," .
        "cursos.profesores_id, " .
        "cursos.estado_id, " .
        "cursos.observaciones," .
        "cursos.inicio  " .
        "FROM " . $from .
        "WHERE cursos.profesores_id = '" . $id . "' AND grupos.ciclos_id='".$this->ciclo->id."' AND cursos.estado_id='3' " .
        "ORDER BY cursos.inicio,materias.nombre ");
        $cc=array();
        foreach($cursos as $curso){
            $cc[$curso->id]["c"]=$curso;
            $cc[$curso->id]["h"]=$curso->horarios();
        }
        $this->cursos=$cc;
        $this->profesor=$profesor;
        $dias=new Dias();
        $this->dias=$dias->find("id!='7' ORDER BY id");
        $this->option="vista";

        $usr_login = Session :: get_data('usr.login');
        $this->acl = array ();
        $acl = new gacl_extra();
        $acos_arr = array (
            'tutoresgrupo' => array (
                'horarioexcel'
            )
        );
        $this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);
        $this->acl=$this->acl["tutoresgrupo"];
        }else{
        $this->option="error";
        $this->error="No existe el tutor.";
        }
        }else{
            $this->option="error";
            $this->error="No existe el tutor.";
        }

    }



}
?>