<?php
Kumbia :: import('lib.kumbia.utils');
Kumbia :: import('lib.upload.main');
/** SP5
 * Creado el 22/02/2009
 * Copyright (C) 2009 FiPa Software (contacto at fipasoft.com.mx)
 */

class ImportarController extends ApplicationController {
    public $template = "system";

    public function fotos($id=''){
        if($id!=''){
            try{
                $this->option='vista';
                $this->directorio=$repositorio='C:\\xampp\\htdocs\\sp5\\public\\importar\\';
                $this->dir = opendir($repositorio);
            }catch(Exception $e){
                $this->option='error';
                $this->error=$e;
            }
        }elseif($this->post('tipo')!=''){
            $this->option='importa';
            switch($this->post('tipo')){
                case 'alumnos':
                    ini_set("memory_limit","64M");
                    ini_set ('max_execution_time', '0' );
                    $this->directorio=$repositorio='C:\\xampp\\htdocs\\sp5\\public\\importar\\';
                    $dir = opendir($repositorio);
                    $this->imagenes=0;
                    mysql_query("BEGIN") or die("ALU_IMP_1");
                    $err=false;
                    $myLog = new Logger('importar.log');
                    $myLog->begin();
                    $myLog->log("Inicio de importacion de imagenes de alumnos.", Logger::DEBUG);
                    while($elemento = readdir($dir)){

                        if( ( substr( $elemento, strlen( $elemento ) - strlen( '.jpg' ) ) == '.jpg' ) ||
                        ( substr( $elemento, strlen( $elemento ) - strlen( '.gif' ) ) == '.gif' ) ||
                        ( substr( $elemento, strlen( $elemento ) - strlen( '.jpeg' ) ) == '.jpeg' ) ||
                        ( substr( $elemento, strlen( $elemento ) - strlen( '.png' ) ) == '.png' )
                        ){
                            $archivo=explode('.',$elemento);
                            $codigo=$archivo[0];
                            $alumno=new Alumnos();
                            if($alumno->count("codigo='$codigo'")==1){

                                $img = new Upload($repositorio.$elemento, 'es_ES');
                                if ($img->uploaded) {
                                    $alumno=$alumno->find_first("codigo='$codigo'");
                                    $alumno->foto = $alumno->codigo . '.jpg';
                                    $img->image_convert = 'jpg';
                                    $img->jpeg_quality = 100;
                                    $img->file_new_name_body = $alumno->codigo;
                                    $img->image_resize = true;
                                    $img->image_ratio_y = true;
                                    $img->image_x = 175;
                                    $img->file_overwrite = true;
                                    $img->file_auto_rename = false;
                                    $img->Process('./public/img/alumnos');
                                    if (!$img->processed) {
                                        $myLog->log('No se pudo procesar el archivo de imagen: ' . $img->error, Logger::ERROR);


                                    }else{
                                        $this->imagenes++;
                                        $alumno->save();
                                    }
                                } else {
                                    $myLog->log('No se encontro el archivo de imagen: ' . $img->error, Logger::ERROR);

                                }
                            } else {
                                $myLog->log('El archivo '.$repositorio.$elemento.' no es valido. El codigo no existe o esta duplicado.', Logger::ERROR);
                            }




                        }else{
                            if($elemento!='.' && $elemento!='..' && !is_dir($repositorio.$elemento) )
                            $myLog->log("El archivo '".$repositorio.$elemento."' no tiene un formato valido", Logger::ERROR);
                        }

                    }

                    mysql_query("COMMIT") or die("ALU_IMP_1");
                    $myLog->log("Final de importacion de imagenes de alumnos.", Logger::DEBUG);
                    $myLog->commit();
                    break;

                case 'profesores':
                    ini_set("memory_limit","64M");
                    ini_set ('max_execution_time', '0' );
                    $this->directorio=$repositorio='C:\\xampp\\htdocs\\sp5\\public\\importar\\';
                    $dir = opendir($repositorio);
                    $this->imagenes=0;
                    mysql_query("BEGIN") or die("ALU_IMP_1");
                    $err=false;
                    $myLog = new Logger('importar.log');
                    $myLog->begin();
                    $myLog->log("Inicio de importacion de imagenes de profesores.", Logger::DEBUG);
                    while($elemento = readdir($dir)){

                        if( ( substr( $elemento, strlen( $elemento ) - strlen( '.jpg' ) ) == '.jpg' ) ||
                        ( substr( $elemento, strlen( $elemento ) - strlen( '.gif' ) ) == '.gif' ) ||
                        ( substr( $elemento, strlen( $elemento ) - strlen( '.jpeg' ) ) == '.jpeg' ) ||
                        ( substr( $elemento, strlen( $elemento ) - strlen( '.png' ) ) == '.png' )
                        ){
                            $archivo=explode('.',$elemento);
                            $codigo=$archivo[0];
                            $profesor=new Profesores();
                            if($profesor->count("codigo='$codigo'")==1 || $profesor->count("CONCAT(TRIM(ap), ' ', TRIM(am),' ',TRIM(nombre)) LIKE '%" . $codigo . "%' ")==1){

                                $img = new Upload($repositorio.$elemento, 'es_ES');
                                if ($img->uploaded) {

                                    $profesor=$profesor->find_first("codigo='$codigo'");
                                    if($profesor->id==''){
                                        $profesor=new Profesores();
                                        $profesor=$profesor->find_first("CONCAT(TRIM(ap), ' ', TRIM(am),' ',TRIM(nombre)) LIKE '%" . $codigo . "%' ");
                                    }
                                    $profesor->foto = $profesor->codigo . '.jpg';
                                    $img->image_convert = 'jpg';
                                    $img->jpeg_quality = 100;
                                    $img->file_new_name_body = $profesor->codigo;
                                    $img->image_resize = true;
                                    $img->image_ratio_y = true;
                                    $img->image_x = 175;
                                    $img->file_overwrite = true;
                                    $img->file_auto_rename = false;
                                    $img->Process('./public/img/profesores');
                                    if (!$img->processed) {
                                        $myLog->log( 'No se pudo procesar el archivo de imagen: ' . $img->error, Logger::ERROR);
                                    }else{
                                        $this->imagenes++;
                                        $profesor->save();
                                    }
                                } else {

                                    $myLog->log( 'No se encontro el archivo de imagen: ' . $img->error, Logger::ERROR);
                                }
                            } else {
                                $myLog->log("El archivo '.$repositorio.$elemento.' no es valido. El codigo no existe o esta duplicado.", Logger::ERROR);
                            }




                        }else{
                            if($elemento!='.' && $elemento!='..' && !is_dir($repositorio.$elemento) )
                            $myLog->log("El archivo '".$repositorio.$elemento."' no tiene un formato valido", Logger::ERROR);
                        }

                    }

                    mysql_query("COMMIT") or die("ALU_IMP_1");
                    $myLog->log("Final de importacion de imagenes de profesores.", Logger::DEBUG);
                    $myLog->commit();
                    break;

            }
        }else{
            $this->option='error';
            $this->error='No tiene permiso para ver la pagina.';
        }
    }

    public function index(){

    }

    public function promedio(){


    }

    public function grupos(){
        $grupos=new Grupos();
        $grupos=$grupos->find("ciclos_id=3 ORDER BY turno,grado,letra");
        $gps=array();
        foreach($grupos as $g){
            $n=new Grupos();
            $n->oferta_id=$g->oferta_id;
            $n->ciclos_id=4;
            $n->grado=$g->grado;
            $n->letra=$g->letra;
            $n->turno=$g->turno;
            $n->save();

            $gps[]=$n;
        }
        $this->gps=$gps;

    }

    public function plantilla(){
        $ciclo_id = Session :: get_data('ciclo.id');
        $this->ciclo = new Ciclos();
        $this->ciclo = $this->ciclo->find($ciclo_id);

        if (isset ($_FILES["archivo"])) {
            $this->option = "error";
            $this->error = "Importado." . $this->post("id_file");
            $file = "public/files/" . $this->post("id_file");
                Kumbia :: import('app.scripts.*');
                $ext = strtolower(substr($_FILES["archivo"]['name'], strripos($_FILES["archivo"]['name'], ".") + 1));
                if ($ext == "xls" || $ext == "csv") {
                    $this->idFile = time() . "." . $ext;
                    $nm = $this->idFile;
                    $a = htmlspecialchars("public/files/" . $nm);
                    move_uploaded_file($_FILES["archivo"]['tmp_name'], $a);

                    $this->importar = new ImportarPlantilla($ciclo_id, $a, 2);
                    $this->importar->carga();
                    $this->datos = $this->importar->aBD();

                    $this->option = "exito";
                        
                } else {
                    $this->option = "error";
                    $this->error = "El formato del archivo no es correcto." . $ext;
                }

            
        } else {
            $this->option = "captura";
        }

            
    }

}
?>