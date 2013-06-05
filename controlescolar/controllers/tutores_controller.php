<?php
Kumbia :: import('app.componentes.*');
Kumbia :: import('lib.kumbia.utils');
Kumbia :: import('lib.upload.main');
Kumbia :: import('lib.excel.main');

/** SP5
 * Creado el 03/07/2008
 * Copyright (C) 2008 FiPa Software (contacto at fipasoft.com.mx)
 */

class TutoresController extends ApplicationController {
    public $template = "system";

    public function agregar($id = ''){
        $ciclo=new Ciclos();
        $ciclo=$ciclo->find(Session :: get_data('ciclo.id'));
        if($ciclo->abierto()){
        if($this->post('nombre') == ''){
            $this->option = 'captura';
        }else{
            $this->option = '';
            $this->error = '';
            $tutor = new Tutores();
            $tutor->nombre = $this->post('nombre');
            $tutor->ap = $this->post('ap');
            $tutor->am = $this->post('am');
            $tutor->domicilio = $this->post('domicilio');
            $tutor->tel = $this->post('tel');
            $tutor->cel = $this->post('cel');
            $tutor->mail = $this->post('mail');
            $tutor->fnacimiento = Utils :: fecha_convertir($this->post('fnacimiento'));
            $tutor->sexo = $this->post('sexo');
            $tutor->foto = '';
            if($tutor->save()){
                $tutores = new Tutores();
                $tutor = $tutores->find($tutor->id);
                $this->option = 'exito';
                $this->exito = '';
                // guardando img en el servidor
                if($_FILES['foto']['tmp_name'] != ''){
                    $img = new Upload($_FILES['foto'], 'es_ES');
                    if($img->uploaded){
                        $tutor->foto = $tutor->id . '.jpg';
                        $img->image_convert         =  'jpg';
                        $img->jpeg_quality             =  100;
                        $img->file_new_name_body      =  $tutor->id;
                        $img->image_resize          =  true;
                        $img->image_ratio_y         =  true;
                        $img->image_x               =  175;
                        $img->file_overwrite        =  true;
                        $img->file_auto_rename        =  false;
                        $img->Process('./public/img/tutores');
                        if (!$img->processed){
                            $tutor->foto = '';
                            $this->option = 'error';
                            $this->error .= 'No se pudo procesar el archivo de imagen: ' . $img->error;
                        }
                        // guardando el path de la imagen
                        if(!$tutor->save()){
                            $this->option = 'error';
                            $this->error .= ' Error al guardar la direcci&oacute;n de la imagen en la BD.';
                        }
                    }else{
                        $this->option = 'error';
                        $this->error .= 'No se pudo subir el archivo de imagen: ' . $img->error;
                    }
                }
                // guardando tutorias
                $alumnos_ids = $this->post('alumnos_id');
                $alumnos_ids = array_unique($alumnos_ids);
                if( is_array($alumnos_ids) ){
                    foreach($alumnos_ids as $id){
                        $alumno = new Alumnos();
                        $tutoria = new Tutoria();
                        $alumno = $alumno->find_first("conditions: codigo = '" . $id . "'");
                        if($alumno->id != '' && $tutor->id != ''){
                            $tutoria->tutores_id = $tutor->id;
                            $tutoria->alumnos_id = $alumno->id;
                            if( $tutoria->save() ){
                                $this->exito .= '<br /> Se vincul&oacute; el alumno ' . $alumno->codigo .' a ' .
                                                ' ' . $tutor->ap . ' ' . $tutor->am . ' ' . $tutor->nombre . '.';
                            }else{
                                $this->option = 'error';
                                $this->error .= ' No se pudo vincular el alumno ' . $alumno->codigo .' a ' .
                                                ' ' . $tutor->ap . ' ' . $tutor->am . ' ' . $tutor->nombre . '.';
                            }
                        }
                    }
                }
            }else{
                $this->option = 'error';
                $this->error .= ' Error al guardar en la BD.';
            }
        }
        } else {
                    $this->option = 'error';
                    $this->error = ' El ciclo esta cerrado.';
        }
    }

    public function crear(){
        $alumnos=new Alumnos();
        $alumnos=$alumnos->find_all_by_sql(
            "SELECT alumnos.* " .
            " FROM " .
            " alumnos " .
            " INNER JOIN alumnosgrupo ON alumnos.id=alumnosgrupo.alumnos_id " .
            " INNER JOIN grupos ON alumnosgrupo.grupos_id=grupos.id " .
            " WHERE grupos.ciclos_id=3 "
        );
        $casos=array();
        foreach($alumnos as $alumno){

            $casos[$alumno->id]['a']=$alumno;
            $paterno=new Tutores();
            $paterno->nombre = "Tutor de ".$alumno->codigo;
            $paterno->ap = ($alumno->ap==""?"-":$alumno->ap);
            $paterno->am = ($alumno->am==""?"-":$alumno->am);
            $paterno->domicilio = $alumno->domicilio;
            $paterno->tel = $alumno->tel;
            $paterno->cel = $alumno->cel;
            $paterno->mail = $alumno->mail;
            $paterno->fnacimiento = date("Y-m-d",time());
            $paterno->sexo = $alumno->sexo;
            $paterno->foto = '';
            if($paterno->save()){
                $tutoria = new Tutoria();
                $tutoria->tutores_id = $paterno->id;
                            $tutoria->alumnos_id = $alumno->id;
                            if( $tutoria->save() ){

                            $casos[$alumno->id]['t']=1;
                            $casos[$alumno->id]['e']="Caso exitoso.";
                            }else{

                            $casos[$alumno->id]['t']=-1;
                            $casos[$alumno->id]['e']="No se pudo establecer la tutoria. ";

                            }
            }else{
            $casos[$alumno->id]['t']=-1;
            $casos[$alumno->id]['e']="No se pudo guardar al tutor. ";
            }

            }
            $this->casos=$casos;
    }

    public function editar($id = ''){
        $ciclo=new Ciclos();
        $ciclo=$ciclo->find(Session :: get_data('ciclo.id'));
        if($ciclo->abierto()){
        if($id != ''){
            $this->option = 'captura';
            $id = intval($id, 10);
            $tutores = new Tutores();
            $tutor = $tutores->find($id);
            if($tutor->id != ''){
                $tutorias = new Tutoria();
                $tutorias = $tutorias->find_all_by_sql(
                    "SELECT " .
                        "alumnos.id, " .
                        "alumnos.nombre, " .
                        "alumnos.ap, " .
                        "alumnos.am, " .
                        "alumnos.codigo " .
                    "FROM " .
                        "tutoria Inner Join alumnos On tutoria.alumnos_id = alumnos.id " .
                    "WHERE " .
                        "tutoria.tutores_id = '" . $tutor->id . "' " .
                    "ORDER BY " .
                        "alumnos.ap, alumnos.am, alumnos.nombre "
                );
                $this->tutor     =  $tutor;
                $this->tutorias  =  $tutorias;
            }else{
                $this->option = 'error';
                $this->error = ' El id del tutor no existe.';
            }
        //$this->condicion = $c;
        }else if($this->post('id') != ''){
                $this->option = '';
                $this->error = '';
                $tutor = new Tutores();
                $tutor = $tutor->find($this->post('id'));
                if($tutor->id != ''){
                    $tutor->nombre = $this->post('nombre');
                    $tutor->ap = $this->post('ap');
                    $tutor->am = $this->post('am');
                    $tutor->domicilio = $this->post('domicilio');
                    $tutor->tel = $this->post('tel');
                    $tutor->cel = $this->post('cel');
                    $tutor->mail = $this->post('mail');
                    $tutor->fnacimiento = Utils :: fecha_convertir($this->post('fnacimiento'));
                    $tutor->sexo = $this->post('sexo');

                    if($tutor->save()){
                        $this->option = 'exito';
                        // guardando img en el servidor
                        if($this->post('cambiarImagen') == 'true'){
                            $img = new Upload($_FILES['foto'], 'es_ES');
                            if($img->uploaded){
                                $tutor->foto = $tutor->id . '_' . $tutor->alumnos_id . '.jpg';
                                $img->image_convert         =  'jpg';
                                $img->jpeg_quality             =  100;
                                $img->file_new_name_body      =  $tutor->id . '_' . $tutor->alumnos_id;
                                $img->image_resize          =  true;
                                $img->image_ratio_y         =  true;
                                $img->image_x               =  175;
                                $img->file_overwrite        =  true;
                                $img->file_auto_rename        =  false;
                                $img->Process('./public/img/tutores');
                                if (!$img->processed){
                                    $this->option = 'error';
                                    $this->error .= 'No se pudo subir el archivo de imagen: ' . $img->error;
                                }
                            }else{
                                $f = getcwd() . '/public/img/tutores/' . $tutor->foto;
                                if($tutor->foto != '' && file_exists($f)){
                                    if( !unlink($f) ){
                                        $this->option = 'error';
                                        $this->error .= ' Error al eliminar la imagen en la BD.';
                                    }
                                }
                                $tutor->foto = '';
                            }
                            // guardando el path de la imagen
                            if(!$tutor->save()){
                                $this->option = 'error';
                                $this->error .= ' Error al guardar la direcci&oacute;n de la imagen en la BD.';
                            }
                        }

                        // eliminado las asignaciones anteriores
                        $tutorias = new Tutoria();
                        if( !$tutorias->delete("tutores_id = '" . $tutor->id . "' ") ){
                            $this->option = 'error';
                            $this->error .= ' No se pudieron eliminar los vinculos anteriores.';
                        }

                        // creando nuevas asignaciones
                        $alumnos_ids = $this->post('alumnos_id');
                        $alumnos_ids = array_unique($alumnos_ids);
                        if( is_array($alumnos_ids) ){
                            foreach($alumnos_ids as $id){
                                $alumno = new Alumnos();
                                $tutoria = new Tutoria();
                                $alumno = $alumno->find_first("conditions: codigo = '" . $id . "'");
                                if($alumno->id != '' && $tutor->id != ''){
                                    $tutoria->tutores_id = $tutor->id;
                                    $tutoria->alumnos_id = $alumno->id;
                                    if( $tutoria->save() ){
                                        $this->exito .= '<br /> Se vincul&oacute; el alumno ' . $alumno->codigo .' a ' .
                                                        ' ' . $tutor->ap . ' ' . $tutor->am . ' ' . $tutor->nombre . '.';
                                    }else{
                                        $this->option = 'error';
                                        $this->error .= ' No se pudo vincular el alumno ' . $alumno->codigo .' a ' .
                                                        ' ' . $tutor->ap . ' ' . $tutor->am . ' ' . $tutor->nombre . '.';
                                    }
                                }
                            }
                        }

                    }else{
                        $this->option = 'error';
                        $this->error .= ' Error al guardar en la BD.';
                    }
            }else{
                $this->option = 'error';
                $this->error = ' El tutor no existe.';
            }
        }else{
            $this->option = 'error';
            $this->error = ' El tutor no existe.';
        }
        } else {
                    $this->option = 'error';
                    $this->error = ' El ciclo esta cerrado.';
        }
    }

    public function eliminar($id = ''){
        $ciclo=new Ciclos();
        $ciclo=$ciclo->find(Session :: get_data('ciclo.id'));
        if($ciclo->abierto()){
        if($id != ''){
            $this->option = 'captura';
            $id = intval($id, 10);
            $tutores = new Tutores();
            $this->tutor = $tutores->find($id);
            if($this->tutor->id == ''){
                $this->option = 'error';
                $this->error = ' El c&oacute;digo de tutor no existe.';
            }
        }else if($this->post('id') != ''){
            $this->option = '';
            $this->error = '';
            $this->exito = '';
            $tutores = new Tutores();
            $tutor = $tutores->find($this->post('id'));
            $f = $tutor->foto;
            if($tutor->id != ''){
                // eliminando el tutor
                if($tutores->delete($this->post('id'))){
                    $this->option = 'exito';
                    $tutor_id = $tutor->id;
                    // eliminando imagen
                    $f = getcwd() . '/public/img/tutores/' . $f;
                    if($tutor->foto != '' && file_exists($f)){
                        if( !unlink($f) ){
                            $this->option = 'error';
                            $this->error .= ' No se pudo eliminar la imagen.';
                        }
                    }
                }else{
                    $this->option = 'error';
                    $this->error .= ' Error al intentar eliminar de la BD.';
                }
            }else{
                $this->option = 'error';
                $this->error = ' El c&oacute;digo de tutor no existe.';
            }
        }else{
            $this->option = 'error';
            $this->error = ' No se especific&oacute; el tutor a eliminar.';
        }
        } else {
                    $this->option = 'error';
                    $this->error = ' El ciclo esta cerrado.';
        }
    }

    public function exportar($grp_id = ''){
        $this->set_response("view");
        require('app/reportes/xls.tutores.php');
        $ciclo_id = Session :: get_data('ciclo.id');
        $reporte = new XLSTutores($ciclo_id);
        $reporte->generar();
     }

    public function index($pag = ''){
        $tutores = new Tutores();
        $controlador = $this->controlador;
        $accion = $this->accion;
        $path = $this->path = KUMBIA_PATH;
        $ciclo_id = Session :: get_data('ciclo.id');

        $_POST['ciclos_id']=$ciclo_id;
        $this->ofertas=new Oferta();
        $this->ofertas=$this->ofertas->find();
        // busqueda
        $b = new Busqueda($controlador, $accion);

        // genera las condiciones
        $b->establecerCondicion(
            'nombre',
            "CONCAT(tutores.nombre, ' ', tutores.ap, ' ', tutores.am) LIKE '%" . $b->campo('nombre') . "%' "
        );
        $b->establecerCondicion('oferta_id', "grupos.oferta_id  = '" . $b->campo('oferta_id') . "' ");
        $b->establecerCondicion('sexo', "tutores.sexo  = '" . $b->campo('sexo') . "' ");
        $c = $b->condicion(array('ciclos_id'));
        $this->busqueda = $b;

        // cuenta todos los registros
        $this->registros = $tutores->count_by_sql(
            "SELECT " .
                "COUNT(*) " .
            "FROM " .
                "(SELECT " .
                    "tutores.id " .
                "FROM " .
                    "tutores " .
                    "Left Join (tutoria Inner Join alumnos ON alumnos.id = tutoria.alumnos_id " .
                                "Inner Join alumnosgrupo ON alumnosgrupo.alumnos_id = alumnos.id " .
                                "Inner Join grupos ON alumnosgrupo.grupos_id = grupos.id )" .
                    "ON tutoria.tutores_id = tutores.id " .
                "WHERE " .
                    ($c == '' ? '1 ' : $c) .
                "GROUP BY ".
                    "tutores.id) " .
                "AS t1"
        );

        // paginacion
        $paginador = new Paginador($controlador, $accion);
        if($pag != ''){
            $paginador->guardarPagina($pag);
        }
        $paginador->estableceRegistros($this->registros);
        $paginador->generar();
        $this->paginador = $paginador;

        // ejecuta la consulta
        $pros = $tutores->find_all_by_sql(
            "SELECT " .
                "tutores.id, " .
                "tutores.nombre, " .
                "tutores.ap, " .
                "tutores.am, " .
                "tutores.foto " .
            "FROM " .
                "tutores " .
                "Left Join (tutoria Inner Join alumnos ON alumnos.id = tutoria.alumnos_id " .
                            "Inner Join alumnosgrupo ON alumnosgrupo.alumnos_id = alumnos.id " .
                            "Inner Join grupos ON alumnosgrupo.grupos_id = grupos.id )" .
                "ON tutoria.tutores_id = tutores.id " .
            "WHERE " .
                ($c == '' ? '1 ' : $c) .
            "GROUP BY ".
                "tutores.id " .
            "ORDER BY " .
                "tutores.ap, tutores.am, tutores.nombre " .
            "LIMIT " .
                ($paginador->pagina() * $paginador->rpp()) . ', ' . $paginador->rpp()
        );
        $this->tutores = array();
        foreach($pros as $p){
            $p->foto = KUMBIA_PATH . 'img/' . ($p->foto == '' ? 'sp5/persona.png' : 'tutores/'.$p->foto .'?d=' . time());
            $this->tutores[] = $p;
        }

          // acl
        $usr_login = Session :: get_data('usr.login');
        $this->acl = array();
        $this->acl_alumnos=array();
        $acl = new gacl_extra();
         $acos_arr = array(
            'tutores' => array(
                'agregar', 'exportar',  'buscar', 'ver', 'editar', 'eliminar','password'
            )
        );
        $this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);
        $this->acl=$this->acl['tutores'];

        $acos_arr = array(
            'alumnos' => array(
                'ver'
            )
        );
        $this->acl_alumnos = $acl->acl_check_multiple($acos_arr, $usr_login);
        $this->acl_alumnos=$this->acl_alumnos['alumnos'];

    }


    public function password($id = ''){
        $ciclo=new Ciclos();
        $ciclo=$ciclo->find(Session :: get_data('ciclo.id'));
        if($ciclo->abierto()){
        if($id != ''){
            $this->option = 'captura';
            $id = intval($id, 10);
            $tutor = new Tutores();
            $this->tutor = $tutor->find($id);
            if($this->tutor->id == ''){
                $this->option = 'error';
                $this->error = ' El usuario no existe.';
            }
        }else if($this->post('pass') != ''){
            $this->option = '';
            $this->error = '';
            $tutor = new Tutores();
            $tutor = $tutor->find($this->post('id'));
            if($tutor->id != ''){
                if($this->post('pass') == $this->post('pass2')){
                    if(strlen($this->post('pass')) >= 6){
                        $password=new Tutorespassword();
                        $password=$password->find_first("tutores_id=".$tutor->id);
                        if($password->id != ''){
                        $password->pass = sha1($this->post('pass'));
                        }else{
                            $password=new Tutorespassword();
                            $password->tutores_id=$tutor->id;
                            $password->pass = sha1($this->post('pass'));
                        }

                        if($password->save()){
                            $this->option = 'exito';
                        }else{
                            $this->option = 'error';
                            $this->error .= ' Error al guardar en la BD.';
                        }
                    }else{
                        $this->option = 'error';
                        $this->error .= ' La longitud m&iacute;nima del password es de 6 caracteres.';
                    }
                }else{
                    $this->option = 'error';
                    $this->error .= ' No coincide la confirmaci&oacute;n del password.';
                }
            }else{
                $this->option = 'error';
                $this->error = ' El tutor no existe.';
            }
        }else{
            $this->option = 'error';
            $this->error = ' No se especific&oacute; el tutor.';
        }
        } else {
                    $this->option = 'error';
                    $this->error = ' El ciclo esta cerrado.';
        }
    }



    public function ver($id = ''){
        $id = intval($id, 10);
        $Tutores = new Tutores();
        $tutor = $Tutores->find($id);
        $this->tutor = $tutor;

          // acl
        $usr_login = Session :: get_data('usr.login');
        $this->acl = array();
        $this->acl_alumnos=array();
        $acl = new gacl_extra();
         $acos_arr = array(
            'tutores' => array(
                'editar', 'eliminar'
            )
        );
        $this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);
        $this->acl=$this->acl['tutores'];


    }
}
?>