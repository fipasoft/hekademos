<?php
Kumbia :: import('app.componentes.*');
Kumbia :: import('lib.kumbia.utils');
Kumbia :: import('lib.excel.main');
Kumbia :: import('lib.upload.main');
Kumbia :: import('app.scripts.Lector');

/** SP5
 * Creado el 22/02/2009
 * Copyright (C) 2009 FiPa Software (contacto at fipasoft.com.mx)
 */

class ImportadorController extends ApplicationController {
    public $template = "system";

    public function grupocursos(){
        $this->set_response("view");
        $id = $this->post("id");
        $cursos = array();
        if($id != ""){
            $id = intval($id);
            $grupo = new Grupos();
            $grupo = $grupo->find($id);
            if($grupo->id!=""){
                $this->cursos = $grupo->cursos();
            }
        }
    }


    public function index(){
        // acl
        $usr_login = Session :: get_data('usr.login');
        $this->acl = array ();
        $acl = new gacl_extra();
        $acos_arr = array (
            'alumnos' => array (
                'importar'
                ),
            'importar' => array (
                'index'
                ),
            'importador' => array(
                'taes',
                'curso'
                )
                );
                $this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);

    }

    public function curso(){
        $this->option = "";
        $this->error = "";
        if($this->post("cursos_id")!="" && $this->post("grupo")!="" && $this->post('articulos_id')!=""){
            $this->set_response("view");
            $arch = $_FILES["archivo"]['tmp_name'];
            if(strlen($arch)>0){
                $repositorio = $_SERVER['DOCUMENT_ROOT'].KUMBIA_PATH."public/files/";
                $nombre = $repositorio . time() .  ".xls";
                move_uploaded_file($_FILES["archivo"]['tmp_name'],htmlspecialchars($nombre));
                $lector = new Lector($nombre);
                if($lector->carga()){
                    $alumnos = array();
                    foreach($lector->registros as $registro){
                        $codigo = $registro[0];
                        $alumnos[] =$codigo;
                    }
                        
                    $curso = new Cursos();
                    $this->curso = $curso->find($this->post("cursos_id"));
                        
                    if($this->curso->id!=""){
                        $alumnos = array_unique($alumnos);
                        $datos = array();
                        foreach($alumnos as $codigo){
                            $dato = array();
                            $alumno = new Alumnos();
                            $alumno = $alumno->find_first("codigo='".$codigo."'");
                            if($alumno->id!=""){
                                $alumnocurso = new Alumnoscursos();
                                $alumnocurso = $alumnocurso->find_first("alumnos_id='".$alumno->id."' AND cursos_id='".$this->curso->id."'");
                                $dato[0]= 1;
                                $dato[1]= $alumno;
                                if($alumnocurso->id != ""){
                                    $dato[0]= -1;
                                    $dato[2]= "El alumno ".$alumno->codigo." ya esta inscrito al curso.";
                                }
                            }else{
                                $dato[0]= -1;
                                $dato[2]= "No existe ningun alumno con el codigo ".$codigo;
                            }
                            $datos[] = $dato;
                        }
                        $this->datos = $datos;
                        $articulo = new Articulos();
                        $this->articulo = $articulo->find_first("id='".$this->post("articulos_id")."'");    
                        $this->option = "confirma";
                    }else{
                        $this->option = "error";
                        $this->error = "El curso no existe.";
                    }
                }else{
                    $this->option = "error";
                    $this->error = "El archivo no es valido. Por favor indique el archivo XLS.";
                }
            }else{
                $this->option = "error";
                $this->error = "El archivo no es valido. Por favor indique el archivo XLS.";
            }
        }elseif($this->post("did")!='' && $this->post("alumnos")!=''){
            try{
                $this->set_response("view");
                $curso = new Cursos();
                $this->curso = $curso->find($this->post("did"));
                mysql_query("BEGIN") or die("importacion_curso");
                $alumnos = $this->post("alumnos");
                $alumnos = split(",",$alumnos);

                $articulo = new Articulos();
                $this->articulo = $articulo->find_first("id='".$this->post("articulos_id")."'");
                
                if($this->articulo->id!="" || $this->post("articulos_id") == "N"){
                    $alumnos = array_unique($alumnos);
                    $datos = array();
                    $error = false;
                    foreach($alumnos as $codigo){
                        $dato = array();
                        $alumno = new Alumnos();
                        $alumno = $alumno->find($codigo);
                        $dato[2]="";
                        if($alumno->id!=""){
                            $alumnocurso = new Alumnoscursos();
                            $alumnocurso = $alumnocurso->find_first("alumnos_id='".$alumno->id."' AND cursos_id='".$this->curso->id."'");
                            $dato[1]= $alumno;
                            if($alumnocurso->id != ""){
                                $dato[0]= -1;
                                $dato[2]= "El alumno ".$alumno->codigo." ya esta inscrito al curso.";
                            }else{
                                $dato[0]= 1;
                                $alumnocurso = new Alumnoscursos();
                                $alumnocurso->alta($alumno->id,$this->curso->id);
                                    
                                $alumnocurso = new Alumnoscursos();
                                $alumnocurso = $alumnocurso->find_first("alumnos_id='".$alumno->id."' AND cursos_id='".$this->curso->id."'");
                                    
                                if($alumnocurso->id!=""){
                                    if($this->articulo->id!=""){
                                        $alumnoArticulo = new Alumnosconarticulo();
                                        $alumnoArticulo->alumnoscursos_id=$alumnocurso->id;
                                        $alumnoArticulo->articulos_id=$this->articulo->id;
                                        if(!$alumnoArticulo->save()){
                                            $error = true;
                                            break;
                                        }
                                    }
                                }else{
                                    $error = true;
                                    break;
                                }
                            }
                        }else{
                            $dato[0]= -1;
                            $dato[2]= "No existe ningun alumno con el codigo ".$codigo;
                        }
                        $datos[] = $dato;
                    }

                    if(!$error){
                        mysql_query("COMMIT") or die("importacion_curso");
                        //mysql_query("ROLLBACK") or die("importacio_TAE");
                        $this->datos = $datos;
                        $this->option = "exito";
                    }else{
                        mysql_query("ROLLBACK") or die("importacion_curso");
                        $this->option = "error";
                        $this->error = "Ocurrio un error en la BD.";
                    }
                }else{
                    $this->option = "error";
                    $this->error = "El articulo no existe.";
                }
            }catch(Exception $e){
                $this->option = "error";
                $this->error = "Ocurrio un error en la BD.";
            }

        }elseif($id==''){
            $ciclo = new Ciclos();
            $ciclo = $ciclo->activo();
            $grupos = new Grupos();
            $this->grupos = $grupos->find("ciclos_id='".$ciclo->id."' ORDER BY grupos.turno,grupos.grado,grupos.letra");
            $articulos = new Articulos();
            $this->articulos = $articulos->find();
            $this->option = "captura";
        }else{
            $this->option = "error";
            $this->error = "No tiene permiso para ingresar.";
        }
    }


    public function taes(){
        $this->option = "";
        $this->error = "";
        if($this->post("id")!=""){
            $this->set_response("view");
            $trayectoria = new Trayectoriaespecializante();
            $this->trayectoria = $trayectoria->find($this->post("id"));
            $arch = $_FILES["archivo"]['tmp_name'];
            //var_dump($arch);exit;
            if(strlen($arch)>0){
                $repositorio = $_SERVER['DOCUMENT_ROOT'].KUMBIA_PATH."public/files/";
                $nombre = $repositorio . time() .  ".xls";
                move_uploaded_file($_FILES["archivo"]['tmp_name'],htmlspecialchars($nombre));
                $lector = new Lector($nombre);
                if($lector->carga()){
                    $alumnos = array();
                    foreach($lector->registros as $registro){
                        $codigo = $registro[0];
                        $alumnos[] =$codigo;
                    }
                        
                    $alumnos = array_unique($alumnos);
                    $datos = array();
                    foreach($alumnos as $codigo){
                        $dato = array();
                        $alumno = new Alumnos();
                        $alumno = $alumno->find_first("codigo='".$codigo."'");
                        if($alumno->id!=""){
                            $alumnotrayectoria = new Alumnotrayectoria();
                            $alumnotrayectoria = $alumnotrayectoria->find_first("alumnos_id='".$alumno->id."'");
                            $dato[0]= 1;
                            $dato[1]= $alumno;
                            if($alumnotrayectoria->id != ""){
                                $trayectoria = new Trayectoriaespecializante();
                                $trayectoria = $trayectoria->find($alumnotrayectoria->trayectoriaespecializante_id);
                                if($trayectoria->id != $this->trayectoria->id){
                                    $dato[2]= "El alumno ya esta inscrito a la TAE ".$trayectoria->nombre.".<br/> Al importar se cambiara la TAE a la que esta inscrito.";
                                }else{
                                    $dato[0]= -1;
                                    $dato[2]= "El alumno ".$codigo." ya esta inscrito a la TAE ".$trayectoria->nombre;
                                }
                            }
                        }else{
                            $dato[0]= -1;
                            $dato[2]= "No existe ningun alumno con el codigo ".$codigo;
                        }
                        $datos[] = $dato;
                    }
                    $this->datos = $datos;
                    $this->option = "confirma";
                }else{
                    $this->option = "error";
                    $this->error = "El archivo no es valido. Por favor indique el archivo XLS.";
                }
            }else{
                $this->option = "error";
                $this->error = "El archivo no es valido. Por favor indique el archivo XLS.";
            }
        }elseif($this->post("did")!='' && $this->post("alumnos")!=''){
            try{
                $this->set_response("view");
                $trayectoria = new Trayectoriaespecializante();
                $this->trayectoria = $trayectoria->find($this->post("did"));
                mysql_query("BEGIN") or die("importacio_TAE");
                $alumnos = $this->post("alumnos");
                $alumnos = split(",",$alumnos);
                $alumnos = array_unique($alumnos);
                $datos = array();
                $error = false;
                foreach($alumnos as $codigo){
                    $guarda = true;
                    $dato = array();
                    $alumno = new Alumnos();
                    $alumno = $alumno->find($codigo);
                    $dato[2]="";
                    if($alumno->id!=""){
                        $alumnotrayectoria = new Alumnotrayectoria();
                        $alumnotrayectoria = $alumnotrayectoria->find_first("alumnos_id='".$alumno->id."'");
                        $dato[0]= 1;
                        $dato[1]= $alumno;
                            
                        if($alumnotrayectoria->id != ""){
                            $trayectoria = new Trayectoriaespecializante();
                            $trayectoria = $trayectoria->find($alumnotrayectoria->trayectoriaespecializante_id);
                            if($trayectoria->id != $this->trayectoria->id){
                                $dato[2]= "El alumno fue cambiado de la TAE ".$trayectoria->nombre.".";
                                if(!$alumnotrayectoria->delete()){
                                    $error = true;
                                    break;
                                }
                            }else{
                                $guarda = false;
                                $dato[2]= "El alumno ".$codigo." ya esta inscrito a la TAE ".$trayectoria->nombre;
                            }
                        }

                        if($guarda){
                            $alumnotrayectoria = new Alumnotrayectoria();
                            $alumnotrayectoria->alumnos_id = $alumno->id;
                            $alumnotrayectoria->trayectoriaespecializante_id = $this->trayectoria->id;
                            if(!$alumnotrayectoria->save()){
                                $error = true;
                                break;
                            }else{
                                if($dato[2]!="")
                                $dato[2]= $dato[2]."<br/>";
                                    
                                $dato[2]= $dato[2]."Importado con exito.";
                            }
                        }

                    }else{
                        $dato[0]= -1;
                        $dato[2]= "No existe ningun alumno con el codigo ".$codigo;
                    }
                    $datos[] = $dato;
                }

                if(!$error){
                    mysql_query("COMMIT") or die("importacion_TAE");
                    //mysql_query("ROLLBACK") or die("importacio_TAE");
                    $this->datos = $datos;
                    $this->option = "exito";
                }else{
                    mysql_query("ROLLBACK") or die("importacion_TAE");
                    $this->option = "error";
                    $this->error = "Ocurrio un error en la BD.";
                }

            }catch(Exception $e){
                $this->option = "error";
                $this->error = "Ocurrio un error en la BD.";
            }

        }elseif($id==''){
            $taes = new Trayectoriaespecializante();
            $this->taes = $taes->find();
            $this->option = "captura";
        }else{
            $this->option = "error";
            $this->error = "No tiene permiso para ingresar.";
        }
    }

    public function cursos($id = ''){

    }


}