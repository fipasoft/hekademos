<?php
Kumbia :: import('app.componentes.*');
Kumbia :: import('lib.kumbia.utils');
Kumbia :: import('lib.upload.main');
 Kumbia :: import('lib.excel.main');

/** SP5
 * Creado el 03/07/2008
 * Copyright (C) 2008 FiPa Software (contacto at fipasoft.com.mx)
 */

class ProfesoresController extends ApplicationController {
    public $template = "system";
    public function exportar($grp_id = ''){
        $this->set_response("view");
        require('app/reportes/xls.profesores.php');
        $ciclo_id = Session :: get_data('prof.ciclo.id');
        $reporte = new XLSProfesores($ciclo_id);
        $reporte->generar();
     }


    public function index($pag = ''){
        $this->redirect("profesores/ver");
    }


    public function ver(){
        $id=Session :: get_data('prof.usr.id');
        $id = intval($id, 10);
        $Profesores = new Profesores();
        $profesor = $Profesores->find($id);
        $profesor->fnacimiento = Utils :: fecha_convertir($profesor->fnacimiento);
        $profesor->fnacimiento = str_replace('-', '/', $profesor->fnacimiento);
        if($profesor->sexo != ''){
            $profesor->sexo = ($profesor->sexo == 'H' ? 'Hombre' : 'Mujer');
        }
        $this->profesor = $profesor;

        $usr_login = Session :: get_data('prof.usr.login');
        $this->acl = array ();
        $acl = new gacl_extra();
        $acos_arr = array (
            'profesores' => array (
                'editar',
                'eliminar',
                'password'
            )
        );
        $this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);
        $this->acl=$this->acl["profesores"];
    }

    public function horarioexcel(){
        $this->set_response("view");
        require('app/reportes/xls.profesoreshorario.php');
        $ciclo_id = Session :: get_data('prof.ciclo.id');
        $id=Session :: get_data('prof.usr.id');
        $reporte = new XLSProfesoreshorario($ciclo_id,$id);
        $reporte->generar();
    }

    public function horario(){
        $id=Session :: get_data('prof.usr.id');
        if($id!=''){
        $profesor=new Profesores();
        $profesor=$profesor->find($id);
        if($profesor->id!=""){

        $ciclo=new Ciclos();
        $ciclo = $ciclo->find(Session :: get_data('prof.ciclo.id'));
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
        "ORDER BY materias.nombre ");
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

        }else{
        $this->option="error";
        $this->error="No existe el profesor.";
        }
        }else{
            $this->option="error";
            $this->error="No existe el profesor.";
        }

    }



}
?>