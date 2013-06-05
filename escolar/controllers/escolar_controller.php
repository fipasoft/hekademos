<?php
    kumbia::import('app.componentes.*');
    kumbia::import('app.Utils.*');

class escolarController extends ApplicationController {

    public $template = "escolar_layout";

    function ver_registro($id=''){
        $usr = Session :: get_data('usr');
        if($usr['login']=='alumno' ){
        $id=intval($id,10);
        if($id!=''){
        $this->option="captura";
        $this->escolar=new escolarWeb();
            $alumno_id=$this->users->id;
            $this->periodo_id=$id;

            $periodosalumnos_id=$this->escolar->periodoalumnoID($alumno_id,$id);
            $inscritos=$this->escolar->alumnocursos($id,$periodosalumnos_id);
            $this->inscritos=$inscritos;

            $this->trayNombre =  $this->escolar->alumnotrayectorianombre($id,$periodosalumnos_id);

            $this->trayectoria = $this->escolar->alumnotrayectoria($id,$periodosalumnos_id);


        }else{
            $this->option="error";
            $this->error="No tiene permiso para entrar a la pagina.";
        }
        }else{
        $this->option="error";
        $this->error="No tiene permiso para entrar a la pagina.";
        }
    }

    function agenda(){
        $usr = Session :: get_data('usr');
        if($usr['login']=='alumno' ){
        $this->esAlumno=true;
        $this->escolar=new escolarWeb();
        $alumno_id=$this->users->id;
        $this->alumno_id = $alumno_id;
        $this->cicloActivo=$this->escolar->cicloActivo();
        $this->periodos=$this->escolar->agenda($alumno_id,$this->cicloActivo["id"]);
        $this->activos=array();
        if(is_array($this->periodos)){
                foreach($this->periodos as $k =>$p){
                if($this->escolar->agendaActiva($p['bloque_inicio'],$p['periodo_fin'])){
                    $p['abierto']=true;
                    $this->periodos[$k]=$p;
                }
                }
        }
        }else{
        $this->esAlumno=false;
        }
    }

    function optativas($id){
        $usr = Session :: get_data('usr');
        if($usr['login']=='alumno' ){
        $id=intval($id,10);
        $log=new Logger('optativas'.date("Ymd").'.log');
        $log->begin();
        $alu=Session::get_data('escolar.alumno');
        if($id!=""){
        $this->option="captura";
        $this->escolar=new escolarWeb();
        $alumno_id=$this->users->id;
        $periodo=$this->escolar->periodo($id,$this->escolar->periodoalumnoID($alumno_id,$id));
        if($periodo['periodo_activo']=='1' && $this->escolar->agendaActiva($periodo['bloque_inicio'],$periodo['periodo_fin'])){
        $alumno_id=$this->users->id;
        $this->cicloActivo=$this->escolar->cicloActivo();
        $this->cursos=$this->escolar->cursosPeriodo($id,$this->users->id);


        if(count($this->cursos)>0){
        $this->configuracion=$this->escolar->optativasConfiguracion($id);
        $this->mensaje="";
        foreach($this->cursos as $t=>$tipos){
        $cnf=$this->configuracion[$tipos[0]["oferta_id"]][$tipos[0]["turno"]][$tipos[0]["grado"]][$t];
        $this->mensaje.='<span class="sub"  >'.$cnf['maximo'].'</span> curso'.($cnf['maximo']!=1 ? 's' : '').' de tipo <span class="sub"  >'.$this->escolar->materiaTipo($t).'</span>';
        if($cnf['diferente']=='1')
            $this->mensaje.=' de diferente grupo';

        if($cnf['dtipo']=='1')
            $this->mensaje.=' de diferente tipo';
            
        $this->mensaje.='.<br/>';

        }
        $this->periodo_id=$id;

        $periodosalumnos_id=$this->escolar->periodoalumnoID($alumno_id,$id);
        $inscritos=$this->escolar->alumnocursos($id,$periodosalumnos_id);
        $this->inscripcion=array();
        $inscmensajes="";
        foreach($inscritos as $ins){
            $this->inscripcion[$ins['id']]=$ins['id'];
            $inscmensajes.='['.$ins['grado'].$ins['letra'].$ins['turno'].' '.$ins['nombre'].'] ['.$ins['tipo']."] - ";
        }

        if(!is_array($this->configuracion)){
            $this->option="error";
            $this->error="Ha ocurrido un error en la configuracion.";
            $log->log("{Ha entrado a selección de cursos el alumno con codigo ".$alu['codigo'].". Ocurrio un error con la configuracion.}",Logger::ERROR);

        }
        $inscmensajes=substr($inscmensajes,0,strlen($inscmensajes)-3);
        $log->log("{Ha entrado a selección de cursos el alumno con codigo ".$alu['codigo'].(count($inscritos)>0 ? '. Cuenta con '.count($inscritos).' inscripciones.' : '.')." : ".$inscmensajes."}");

        }else{
            $this->option="error";
            $this->error="No hay ningun curso ofertado para el alumno.";
            $log->log("{Ha entrado a selección de cursos el alumno con codigo ".$alu['codigo'].". Ocurrio un error.}",Logger::ERROR);
        }
        }else{
            $this->option="error";
            $this->error="La agenda se encuentra cerrada. ";
            $log->log("{Ha entrado a selección de cursos el alumno con codigo ".$alu['codigo'].". La agenda se encuentra cerrada.}",Logger::ERROR);
        }

        }elseif($this->post("periodo_id")!=""){
        $alumno_id=$this->users->id;
        $id=intval($this->post("periodo_id"),10);
        $this->escolar=new escolarWeb();
        $periodo=$this->escolar->periodo($id,$this->escolar->periodoalumnoID($alumno_id,$id));
        if($periodo['periodo_activo']=='1' && $this->escolar->agendaActiva($periodo['bloque_inicio'],$periodo['periodo_fin'])){
        $msjinscritos="";
        if(is_array($this->post("cursos"))){

            $this->configuracion=$this->escolar->optativasConfiguracion($id);
            if(is_array($this->configuracion)){
            $this->grupo=$grupo=$this->escolar->alumnogrupo($alumno_id,$periodo['ciclos_id']);
            $err=false;
            $this->cursos=$cursos=$this->post("cursos");
            $db = new db("localhost", "hekademos", "hekademos", "hekademos");
            try{
            mysql_query("BEGIN") or die("INSCRIPCION_2");
            $alumno_id=$this->users->id;
            $periodosalumnos_id=$this->escolar->periodoalumnoID($alumno_id,$this->post("periodo_id"));
            $consulta="SELECT * FROM inscripcion WHERE periodosalumnos_id='".$periodosalumnos_id."' ";
            $ins=$db->in_query($consulta);

            if(count($ins)>0){ //revisar si tiene inscripciones
            foreach($ins as $c){

                $consulta=" UPDATE periodoscursos SET inscritos=(inscritos-1) WHERE id='".$c['periodoscursos_id']."'";
                $db->query($consulta); //se actualiza el cupo
                if(mysql_affected_rows()!=1){ //si ocurrio error
                    $err=true;
                    $this->option="error";
                    $this->error="Ha ocurrido un error en la BD. Intentelo de nuevo.";
                    $log->log("Alumno con codigo ".$alu['codigo'].". Ha ocurrido un error en la BD. Intentelo de nuevo.}",Logger::ERROR);
                    mysql_query("ROLLBACK") or die("INSCRIPCION_10");
                }

                }

            $consulta="DELETE FROM inscripcion WHERE periodosalumnos_id='".$periodosalumnos_id."' ";
            $db->query($consulta);
            if(mysql_affected_rows()==0){ //revisar que se hayan eliminado
                    $err=true;
                    $this->option="error";
                    $this->error="Ha ocurrido un error en la BD. Intentelo de nuevo.";

                    $log->log("Alumno con codigo ".$alu['codigo'].". Ha ocurrido un error en la BD. Intentelo de nuevo.}",Logger::ERROR);
                    mysql_query("ROLLBACK") or die("INSCRIPCION_3");

            }

            } //si no tiene no se eliminan

            if(!$err){//si no ha ocurrido ningun error se procede a realizar las inscripciones
            $inscripciones=array();
            $grupos= array();
            $tipos = array();
            foreach($cursos as $c){
                $consulta=" SELECT oferta.nombre AS grupo_oferta, grupos.oferta_id,grupos.grado,grupos.letra,grupos.turno,
                            cursos.*,
                            periodoscursos.tipos_id,periodoscursos.cupos,
                            (periodoscursos.cupos-periodoscursos.inscritos) AS total,
                            materias.nombre,materias.tipo 
                            FROM periodoscursos " .
                          " INNER JOIN cursos ON periodoscursos.cursos_id=cursos.id " .
                          " INNER JOIN materias ON cursos.materias_id=materias.id" .
                          " INNER JOIN grupos ON cursos.grupos_id=grupos.id" .
                          " INNER JOIN oferta ON grupos.oferta_id=oferta.id" .
                          " WHERE periodoscursos.id='".$c."'";
                $cs=$db->fetch_one($consulta);
                if($cs["id"]!=""){//revisar si existe el curso
                    if($cs["total"]>0){//revisar si existe cupo
                        
                        $cnf=$this->configuracion[$cs['oferta_id']][$cs['turno']][$cs['grado']][$cs['tipo']];
                        if($cnf['diferente']=="1"){//revisar si tiene que ser de diferente grupo
                            $existe=$grupos[$cs['tipo'].$cs['oferta_id'].$cs['turno'].$cs['grado'].$cs['letra']];
                            if($existe==null)
                            $grupos[$cs['tipo'].$cs['oferta_id'].$cs['turno'].$cs['grado'].$cs['letra']]=$cs;
                            else{
                                $err=true;
                                $this->option="error";
                                $this->error="Tiene que seleccionar cursos de diferente grupo.";
                                $log->log("Alumno con codigo ".$alu['codigo'].". Tiene que seleccionar cursos de diferente grupo.}",Logger::ERROR);
                                mysql_query("ROLLBACK") or die("INSCRIPCION_9");
                                break;
                            }
                        }
                        
                        if($cnf['dtipo']=="1"){
                            
                            $existe=$tipos[$cs['tipo'].$cs['oferta_id'].$cs['turno'].$cs['grado'].$cs['tipos_id']];
                            if($existe==null){
                                if($cs['tipos_id']!=""){
                                    $tipos[$cs['tipo'].$cs['oferta_id'].$cs['turno'].$cs['grado'].$cs['tipos_id']]=$cs;
                                }
                            }else{
                                    $err=true;
                                    $this->option="error";
                                    $this->error="Tiene que seleccionar cursos de diferente tipo.";
                                    $log->log("Alumno con codigo ".$alu['codigo'].". Tiene que seleccionar cursos de diferente tipo.}",Logger::ERROR);
                                    mysql_query("ROLLBACK") or die("INSCRIPCION_9");
                                    break;
                                }
                        }

                        $consulta="INSERT INTO inscripcion(periodoscursos_id,periodosalumnos_id) VALUES('".$c."','".$periodosalumnos_id."')";
                        $db->query($consulta);
                        if(mysql_affected_rows()!=1){//realiza la inscripcion,si no la realizo lanza error
                            $err=true;
                            $this->option="error";
                            $this->error="Ha ocurrido un error en la BD. Intentelo de nuevo.";
                            $log->log("Alumno con codigo ".$alu['codigo'].". Ha ocurrido un error en la BD. Intentelo de nuevo.}",Logger::ERROR);
                            mysql_query("ROLLBACK") or die("INSCRIPCION_3");
                            break;
                        }else{// se realizo la inscripcion
                            $consulta=" UPDATE periodoscursos SET inscritos=(inscritos+1) WHERE id='".$c."'";
                            $db->query($consulta); //se actualiza el cupo

                            if(mysql_affected_rows()!=1){ //si ocurrio error
                                $err=true;
                                $this->option="error";
                                $this->error="Ha ocurrido un error en la BD. Intentelo de nuevo.";
                                $log->log("Alumno con codigo ".$alu['codigo'].". Ha ocurrido un error en la BD. Intentelo de nuevo.}",Logger::ERROR);
                                mysql_query("ROLLBACK") or die("INSCRIPCION_3");
                                break;
                            }

                            $inscripciones[$cs['oferta_id']][$cs['turno']][$cs['grado']][$cs['tipo']]=($inscripciones[$cs['oferta_id']][$cs['turno']][$cs['grado']][$cs['tipo']])+1;
                            $msjinscritos.='['.$cs['grado'].$cs['letra'].$cs['turno'].' '.$cs['nombre'].'] ['.$cs['tipo']."] - ";
                        }
                    }else{//si no hay cupo se lanza error
                        $err=true;
                        $this->option="error";
                        $this->error="No hay cupo en <br/>".$cs['grado'].$cs['letra'].$cs['turno']." ".$cs['grupo_oferta']."<br/>".$cs['nombre'].".";
                        $log->log("Alumno con codigo ".$alu['codigo'].". No hay cupo en ".$cs['nombre']."}",Logger::ERROR);
                        mysql_query("ROLLBACK") or die("INSCRIPCION_5");
                        break;
                    }

                }else{//sino existe el curso se lanza error
                    $err=true;
                    $this->option="error";
                    $this->error="Ha ocurrido un error en la BD. Intentelo de nuevo.";
                    $log->log("Alumno con codigo ".$alu['codigo'].". Ha ocurrido un error en la BD. Intentelo de nuevo.}",Logger::ERROR);
                    mysql_query("ROLLBACK") or die("INSCRIPCION_3");
                    break;

                }
            }//fin foreach
            }//fin if

        if(!$err){//si no ocurrio error se hace la transaccion
            $this->configuracion=$this->configuracion[$grupo['oferta_id']][$grupo['turno']][($grupo['grado']+1)];
                        foreach($this->configuracion as $tp=>$tipo){
                            $ins=$inscripciones[$grupo['oferta_id']][$grupo['turno']][($grupo['grado']+1)][$tp];
                            if($ins!=$tipo['maximo']){
                                $err=true;
                                $this->option = 'error';
                                $this->error="Los datos no son validos.";
                                mysql_query("ROLLBACK") or die("INSCRIPCION_8");
                                $log->log("Alumno con codigo ".$alu['codigo'].". Los datos no son validos.}",Logger::ERROR);
                                break;
                            }

            }

            if(!$err){
                $this->inscripciones=$inscripciones;
                mysql_query("COMMIT") or die("INSCRIPCION_2");
                $this->option="exito";
                $msjinscritos=substr($msjinscritos,0,strlen($msjinscritos)-3);
                $log->log("{El alumno con codigo ".$alu['codigo']." se inscribio a los siguientes cursos:".$msjinscritos." }");


            }
        }
        }catch(Exception $e){
                        $this->option = 'error';
                        $this->error="Ha ocurrido un error en la BD. Intentelo de nuevo";
                        mysql_query("ROLLBACK") or die("INSCRIPCION_4");
                        $log->log("{Alumno con codigo ".$alu['codigo'].". Ha ocurrido un error en la BD. Intentelo de nuevo.}",Logger::ERROR);

        }
        }else{
        $this->option="error";
        $this->error="Ha ocurrido un error en la configuracion.";
        $log->log("{Alumno con codigo ".$alu['codigo'].". Ocurrio un error con la configuracion.}",Logger::ERROR);

        }

        }else{
        $this->option="error";
        $this->error="Los datos no son validos.";
        $log->log("{Los datos no son validos.}",Logger::ERROR);

        }
        }else{
            $this->option="error";
            $this->error="La agenda se encuentra cerrada. ";
            $log->log("{Ha entrado a selección de cursos el alumno con codigo ".$alu['codigo'].". La agenda se encuentra cerrada.}",Logger::ERROR);
        }

        }else{
            $this->option="error";
            $this->error="No tiene permiso para entrar a la pagina. ";
        }

        $log->commit();
        }else{
        $this->option="error";
        $this->error="No tiene permiso para entrar a la agenda.";
        }
    }


    function accesos($dia=""){
    $this->escolar=new escolarWeb();
    if(!$this->escolar->abierto())
            $this->redirect('escolar/restringir');
    $afxUser=new AFxUser();
    if($afxUser->db==true){
     $alumno_id=$this->users->id;
     $this->alumno=$this->escolar->getAlumno($alumno_id);

    $this->accesos=$this->escolar->accesos($this->alumno["codigo"],$dia);
    }else{
        $this->accesos=array();
        $this->accesos["valido"]=false;
        $this->accesos["error"]="No se encuentra disponible la informacion.";
    }
    }

    function index(){
    $this->escolar=new escolarWeb();
    if(!$this->escolar->abierto())
            $this->redirect('escolar/restringir');

    $this->redirect('escolar/auth');
     }

     function restringir(){

     }

     function auth(){

    $this->escolar=new escolarWeb();
    if(!$this->escolar->abierto())
            $this->redirect('escolar/restringir');

    $usr = Session :: get_data('usr');
    if ($usr['login']=='tutor' || $usr['login']=='alumno' ) {
            $this->redirect('escolar/inicio/');
        }
     }

     function abrir(){

    $this->escolar=new escolarWeb();
    if(!$this->escolar->abierto())
            $this->redirect('escolar/restringir');

    $usuario=$this->post("tipo");
    $codigo=$this->post("codigo");
    $pass=$this->post("pass");
    $db = new db("localhost", "hekademos", "hekademos", "hekademos");
      session_start();
      $this->set_response("view");    
      $db = new db("localhost", "hekademos", "hekademos", "hekademos");

    if(isset($usuario) && isset($codigo) && isset($pass)){
        if($usuario=="A"){    $db = new db("localhost", "hekademos", "hekademos", "hekademos");
        $consulta="SELECT alumnos.codigo,alumnos.nombre,alumnos.ap,alumnos.am,alumnospassword.alumnos_id " .
            "FROM alumnos,alumnospassword " .
            "WHERE alumnos.id=alumnospassword.alumnos_id and alumnos.codigo='".$codigo."' AND alumnospassword.pass='".sha1($pass)."'";

        $usuario=$db->fetch_one($consulta);
        if($db->num_rows()==1){

            $variables=array();
            $variables['id']=$usuario['alumnos_id'];
            $variables['login']="alumno";//$usuario['codigo'];
            $variables['grupo']="alumnos";
            $variables['nombre']=$usuario['nombre']." ".$usuario['ap']." ".$usuario['am'];

            Session :: set_data('usr',$variables);

            $this->escolar=new escolarWeb();
            $ciclo=$this->escolar->cicloActivo();
            Session :: set_data('escolar.ciclo',$ciclo["id"]);
            Session :: set_data('escolar.alumno',array("alumno_id"=>$variables['id'],"codigo"=>$usuario['codigo'],"nombre"=>$variables['nombre']));
            $this->resp=1;
        }else{
            $this->resp=0;
        }
        }else if($usuario=="T"){
            $consulta="SELECT alumnos.codigo,alumnos.id AS alumno_id,alumnos.codigo AS alumno_codigo,alumnos.nombre AS alumno_nombre,alumnos.ap AS alumno_ap,alumnos.am AS alumno_am," .
            "tutores.id AS tutores_id, tutores.nombre AS tutores_nombre,tutores.ap AS tutores_ap,tutores.am AS tutores_am," .
            "tutorespassword.pass AS tutores_pass" .
            " FROM alumnos,tutores,tutorespassword,tutoria " .
            "WHERE tutoria.alumnos_id=alumnos.id AND tutoria.tutores_id=tutores.id AND tutorespassword.tutores_id=tutores.id AND tutorespassword.pass='".sha1($pass)."' AND alumnos.codigo='".$codigo."'";
            $usuario=$db->fetch_one($consulta);
            if($db->num_rows()==1){
                $this->resp=1;
                $variables=array();
            $variables['id']=$usuario['alumno_id'];
            $variables['login']="tutor";
            $variables['grupo']="tutores";
            $variables['nombre']=$usuario['tutores_nombre']." ".$usuario['tutores_ap']." ".$usuario['tutores_am'];
            $variables['tutor_id']=$usuario["tutores_id"];
            $variables['codigo']=$usuario['codigo'];
            Session :: set_data('usr',$variables);
            $this->escolar=new escolarWeb();
            $ciclo=$this->escolar->cicloActivo();
            Session :: set_data('escolar.ciclo',$ciclo["id"]);
            Session :: set_data('escolar.alumno',array("alumno_id"=>$variables['id'],"codigo"=>$usuario['codigo'],"nombre"=>$usuario['alumno_ap']." ".$usuario['alumno_am']." ".$usuario['alumno_nombre']));

            }else{
                $this->resp=0;
            }

        }else{
            $this->resp=-1;

         }

     }else{
            $this->resp=-11;

     }
     }

     function cerrar(){

    $this->escolar=new escolarWeb();
    if(!$this->escolar->abierto())
            $this->redirect('escolar/restringir');

    $this->set_response("view");
        Session::unset_data('usr');
        Session::unset_data('escolar.ciclo');
        Session::unset_data('escolar.alumno');

        $this->redirect('escolar/auth');

     }

     function kardex(){
     $this->escolar=new escolarWeb();
    if(!$this->escolar->abierto())
            $this->redirect('escolar/restringir');

     $alumno_id=$this->users->id;
     $this->ciclos=$this->escolar->kardex($alumno_id);
    $this->alumno=$this->escolar->getAlumno($alumno_id);
     }

     function ficha(){
      $this->escolar=new escolarWeb();
    if(!$this->escolar->abierto())
            $this->redirect('escolar/restringir');

     $alumno_id=$this->users->id;
         $this->alumno=$this->escolar->getAlumnoGrupo($alumno_id);
        if($this->alumno==null){
        $this->alumno=$this->escolar->getAlumno($alumno_id);
        }
    $this->tutores=$this->escolar->getTutoresDeAlumno($alumno_id);
     }

     function asistencias(){

      $this->escolar=new escolarWeb();
    if(!$this->escolar->abierto())
            $this->redirect('escolar/restringir');

     $alumno_id=$this->users->id;
     $this->ciclos=$this->escolar->ciclosAlumno($alumno_id);

     $this->cicloActual=$this->escolar->cicloActivo();
    $this->cicloActivo=Session :: get_data('escolar.ciclo');
    $alumno=Session :: get_data('escolar.alumno');
    $this->alumno=array("nombre"=>$alumno['nombre'],"codigo"=>$alumno['codigo']);
    }

     function calificaciones(){
     $this->escolar=new escolarWeb();
    if(!$this->escolar->abierto())
            $this->redirect('escolar/restringir');

    $alumno_id=$this->users->id;
     $this->ciclos=$this->escolar->ciclosAlumno($alumno_id);
     $this->cicloActivo=Session :: get_data('escolar.ciclo');
    $variables=Session :: get_data('usr');
    $alumno=Session :: get_data('escolar.alumno');
    $this->alumno=array("nombre"=>$alumno['nombre'],"codigo"=>$alumno['codigo']);
     }

     function inicio(){
      $this->escolar=new escolarWeb();
    if(!$this->escolar->abierto())
            $this->redirect('escolar/restringir');

    $alumno_id=$this->users->id;
    $this->datos=$this->escolar->inicio($alumno_id);
    $alumno=Session :: get_data('escolar.alumno');
    $this->alumno=array("nombre"=>$alumno['nombre'],"codigo"=>$alumno['codigo']);
     }

     public function obtenAsistencias($id_ciclo){
     $this->escolar=new escolarWeb();
    if(!$this->escolar->abierto())
            $this->redirect('escolar/restringir');

        //Indicamos que es una salida xml
        Session :: set_data('escolar.ciclo',$id_ciclo);
         $this->set_response("xml");
        $alumno_id=$this->users->id;

         $xml=$this->escolar->obtenAsistencias($alumno_id,$id_ciclo);


         //Generamos la salida
         //header('Content-Type: text/xml');
        //header("Pragma: no-cache");
        //header("Expires: 0");
        print $xml;
    }

     public function obtenHorario($id_ciclo){
     $this->escolar=new escolarWeb();
     if(!$this->escolar->abierto())
            $this->redirect('escolar/restringir');

        //Indicamos que es una salida xml
        Session :: set_data('escolar.ciclo',$id_ciclo);
         $this->set_response("xml");
        $alumno_id=$this->users->id;
         $xml=$this->escolar->horario($alumno_id,$id_ciclo);


         //Generamos la salida
         header('Content-Type: text/xml');
        header("Pragma: no-cache");
        header("Expires: 0");
        print $xml;
    }


     public function obtenCalificaciones($id_ciclo){
     $this->escolar=new escolarWeb();
    if(!$this->escolar->abierto())
            $this->redirect('escolar/restringir');

        //Indicamos que es una salida xml
        Session :: set_data('escolar.ciclo',$id_ciclo);
         $this->set_response("xml");
        $alumno_id=$this->users->id;
         $xml=$this->escolar->obtenCalificaciones($alumno_id,$id_ciclo);


         //Generamos la salida
         //header('Content-Type: text/xml');
        //header("Pragma: no-cache");
        //header("Expires: 0");
        print $xml;
    }

    public function password($tipo){
     $this->escolar=new escolarWeb();
    if(!$this->escolar->abierto())
            $this->redirect('escolar/restringir');

        $this->msj='';
        $this->cambia=false;
        if(isset($tipo) AND $tipo=="cambia"){
            $p1=$this->post('p1');
            $p2=$this->post('p2');
            $p3=$this->post('p3');
            if(isset($p1) && isset($p2) && isset($p3) && strlen(trim($p1))>0 && strlen(trim($p2))>0 && strlen(trim($p3))>0 ){
                $error=false;
                $this->cambia=true;
                if($p1!=$p2){
                $error=true;
                $this->msj='<br/><br/><p class="errorBox"><br/>El password nuevo no coincide con su confirmacion.</p>';
                }

                $variables=Session :: get_data("usr");
                $this->escolar=new escolarWeb();

                if(!$this->escolar->verificaPassword($this->users->id,$this->users->grupo,$p3,$variables["tutor_id"])){
                $error=true;
                $this->msj='<br/><br/><p class="errorBox"><br/>El password actual no es correcto.</p>';
                }

                if(!$error){
                $val=$this->escolar->cambiaPassword($this->users->id,$this->users->grupo,$p1,$variables["tutor_id"]);

                if($val)
                $this->msj='<br/><br/><p class="infoBox"><br/>El password ha sido cambiado.</p>';
                else if(!$val)
                $this->msj='<br/><br/><p class="infoBox"><br/>El password no es valido.Intentelo con otro password.</p>';

                }


            }else{

            }


        }

    }

    public function pdf($tipo,$par1=""){
     $this->escolar=new escolarWeb();
        if(!$this->escolar->abierto())
            $this->redirect('escolar/restringir');

    $alumno_id=$this->users->id;
      $this->set_response("view");
      $alumno=Session :: get_data('escolar.alumno');
    $reporte=new PDFReportes();
    $reporte->setDatos($alumno['codigo'],$alumno['nombre']);
    
    $dir = $_SERVER['DOCUMENT_ROOT'].KUMBIA_PATH."lib/dompdf/dompdf_config.inc.php";
    require_once($dir);
    switch($tipo){
        case "kardex":$this->html=@$reporte->kardexPDF($alumno_id); break;
        case "ficha":$this->html=@$reporte->fichaPDF($alumno_id); break;
        case "calificaciones": $this->html=@$reporte->calificacionesPDF($alumno_id,Session :: get_data('escolar.ciclo')); break;
        case "asistencias": $this->html=@$reporte->asistenciasPDF($alumno_id,Session :: get_data('escolar.ciclo')); break;
        case "horario": $this->html=@$reporte->horarioPDF($alumno_id,Session :: get_data('escolar.ciclo')); break;
           case "accesos":$this->html=@$reporte->accesosPDF($alumno,$par1); break;
           default:   $this->redirect('escolar/restringir');;
    }


    $dompdf = new DOMPDF();
    $this->html = preg_replace('/<tbody>|<\/tbody>/', '', $this->html);
    $this->html = preg_replace('/<thead>|<\/thead>/', '', $this->html);
    $dompdf->load_html($this->html);
    $dompdf->render();

    $dompdf->stream($tipo.".pdf");

    }


    function horario(){
     $this->escolar=new escolarWeb();
    if(!$this->escolar->abierto())
            $this->redirect('escolar/restringir');

    $alumno_id=$this->users->id;

     $this->ciclos=$this->escolar->ciclosAlumno($alumno_id);
     $this->cicloActivo=$this->escolar->    cicloActivo    ();
     $dat=$this->escolar->horario($alumno_id,$this->cicloActivo["id"]);
     $this->aux=$dat;
     $this->horarios=$dat["horario"];
     $this->informacion=$dat["informacion"];
    $variables=Session :: get_data('usr');
    $alumno=Session :: get_data('escolar.alumno');
    $this->alumno=array("alumno_id"=>$alumno["alumno_id"],"nombre"=>$alumno['nombre'],"codigo"=>$alumno['codigo']);
    $this->grupo=$this->escolar->getAlumnoGrupo($alumno["alumno_id"]);
    }

    function taes($id = ""){
        $usr = Session :: get_data('usr');
        if($usr['login']=='alumno' ){
        $id=intval($id,10);
        $log=new Logger('optativas'.date("Ymd").'.log');
        $log->begin();
        $alu=Session::get_data('escolar.alumno');
        if($id!=""){
        $this->option="captura";
        $this->escolar=new escolarWeb();
        $alumno_id=$this->users->id;
        if(!$this->escolar->alumnotienetrayectoria($alumno_id)){ // no tiene trayectoria
        $periodo=$this->escolar->periodo($id,$this->escolar->periodoalumnoID($alumno_id,$id));
        if($periodo['periodo_activo']=='1' && $this->escolar->agendaActiva($periodo['bloque_inicio'],$periodo['periodo_fin'])){
        $alumno_id=$this->users->id;
        $this->cicloActivo=$this->escolar->cicloActivo();
        $this->trayectorias=$this->escolar->trayectoriasPeriodo($id,$this->users->id);


        if(count($this->trayectorias)>0){
        $this->configuracion=$this->escolar->optativasConfiguracion($id);
        $this->mensaje="";


        $this->periodo_id=$id;


        if(!is_array($this->configuracion)){
            $this->option="error";
            $this->error="Ha ocurrido un error en la configuracion.";
            $log->log("{Ha entrado a selección de cursos el alumno con codigo ".$alu['codigo'].". Ocurrio un error con la configuracion.}",Logger::ERROR);

        }

        $periodosalumnos_id=$this->escolar->periodoalumnoID($alumno_id,$id);
        $inscritos=$this->escolar->alumnotrayectoria($id,$periodosalumnos_id);
        $this->inscripcion=array();
        foreach($inscritos as $ins){
            $this->inscripcion[$ins['id']]=$ins['id'];
        }
        $log->log("{Ha entrado a selección de cursos el alumno con codigo ".$alu['codigo']."}");

        }else{
            $this->option="error";
            $this->error="No hay ningun curso ofertado para el alumno.";
            $log->log("{Ha entrado a selección de cursos el alumno con codigo ".$alu['codigo'].". Ocurrio un error.}",Logger::ERROR);
        }
        }else{
            $this->option="error";
            $this->error="La agenda se encuentra cerrada. ";
            $log->log("{Ha entrado a selección de cursos el alumno con codigo ".$alu['codigo'].". La agenda se encuentra cerrada.}",Logger::ERROR);
        }
        }else{
            $this->option="error";
            $this->error="El usuario ya esta inscrito a una trayectoria. ";
            $log->log("{Ha entrado a selección de cursos el alumno con codigo ".$alu['codigo'].". El usuario ya esta inscrito a una trayectoria.}",Logger::ERROR);
        }
        }elseif($this->post("periodo_id")!=""){
        $alumno_id=$this->users->id;
        $id=intval($this->post("periodo_id"),10);
        $this->escolar=new escolarWeb();
        if(!$this->escolar->alumnotienetrayectoria($alumno_id)){ // no tiene trayectoria
        $periodo=$this->escolar->periodo($id,$this->escolar->periodoalumnoID($alumno_id,$id));
        if($periodo['periodo_activo']=='1' && $this->escolar->agendaActiva($periodo['bloque_inicio'],$periodo['periodo_fin'])){
        if(is_array($this->post("trayectorias")) && count($this->post("trayectorias")) == 1){
            $this->configuracion=$this->escolar->optativasConfiguracion($id);
            if(is_array($this->configuracion)){
            $err=false;
            $this->trayectorias = $this->post("trayectorias");
            $db = new db("localhost", "hekademos", "hekademos", "hekademos");
            try{
            mysql_query("BEGIN") or die("INSCRIPCION_2");
            $alumno_id=$this->users->id;

            $grupo=$this->escolar->alumnogrupo($alumno_id,$periodo["ciclos_id"]);

            $periodosalumnos_id=$this->escolar->periodoalumnoID($alumno_id,$this->post("periodo_id"));
            $consulta="SELECT * FROM periodotrayectoriaalumno WHERE periodosalumnos_id='".$periodosalumnos_id."' ";
            $ins=$db->in_query($consulta);
            if(count($ins)>0){ //revisar si tiene inscripciones
            foreach($ins as $t){
                $consulta=" UPDATE periodotrayectoria SET inscritos=(inscritos-1) WHERE id='".$t['periodotrayectoria_id']."'";
                $db->query($consulta); //se actualiza el cupo
                if(mysql_affected_rows()!=1){ //si ocurrio error
                    $err=true;
                    $this->option="error";
                    $this->error="Ha ocurrido un error en la BD al eliminar inscripcion. Intentelo de nuevo.";
                    $log->log("Alumno con codigo ".$alu['codigo'].". Ha ocurrido un error en la BD al eliminar inscripcion. Intentelo de nuevo.}",Logger::ERROR);
                    mysql_query("ROLLBACK") or die("INSCRIPCION_10");
                }

                }

                $consulta="DELETE FROM periodotrayectoriaalumno WHERE periodosalumnos_id='".$periodosalumnos_id."' ";
                $db->query($consulta);
                if(mysql_affected_rows()==0){ //revisar que se hayan eliminado
                        $err=true;
                        $this->option="error";
                        $this->error="Ha ocurrido un error en la BD al eliminar. Intentelo de nuevo.";

                        $log->log("Alumno con codigo ".$alu['codigo'].". Ha ocurrido un error en la BD al eliminar. Intentelo de nuevo.}",Logger::ERROR);
                        mysql_query("ROLLBACK") or die("INSCRIPCION_3");

                }

                //eliminar los cursos del alumno[TODO]

            } //si no tiene no se eliminan



            if(!$err){//si no ha ocurrido ningun error se procede a realizar las inscripciones
                $consulta = "SELECT trayectoriaespecializante.nombre, periodotrayectoria.*,
                            (periodotrayectoria.cupos - periodotrayectoria.inscritos) AS total,
                            periodotrayectoria.turno
                            FROM
                            trayectoriaespecializante
                            INNER JOIN periodotrayectoria ON trayectoriaespecializante.id = periodotrayectoria.trayectoriaespecializante_id
                            WHERE periodotrayectoria.periodo_id = ".$this->post("periodo_id")."  AND periodotrayectoria.id=".$this->trayectorias[0];

                $trayectoria=$db->fetch_one($consulta);
                if($trayectoria["id"]!=""){ //existe la trayectoria
                if(strToUpper($trayectoria["turno"])==strToUpper($grupo["turno"])){
                    if($trayectoria["total"]>0){ //existe cupo
                        $consulta="INSERT INTO periodotrayectoriaalumno(periodosalumnos_id,periodotrayectoria_id) VALUES('".$periodosalumnos_id."','".$trayectoria["id"]."')";
                        $db->query($consulta);
                        if(mysql_affected_rows()!=1){//realiza la inscripcion,si no la realizo lanza error
                            $err=true;
                            $this->option="error";
                            $this->error="Ha ocurrido un error en la BD al inscribir. Intentelo de nuevo.";
                            $log->log("Alumno con codigo ".$alu['codigo'].". Ha ocurrido un error en la BD al inscribir. Intentelo de nuevo.}",Logger::ERROR);
                            mysql_query("ROLLBACK") or die("INSCRIPCION_3");

                        }else{// se realizo la inscripcion
                            $consulta=" UPDATE periodotrayectoria SET inscritos=(inscritos+1) WHERE id='".$trayectoria["id"]."'";
                            $db->query($consulta); //se actualiza el cupo
                            if(mysql_affected_rows()!=1){ //si ocurrio error
                                $err=true;
                                $this->option="error";
                                $this->error="Ha ocurrido un error en la BD al sumar la inscripcion. Intentelo de nuevo.";
                                $log->log("Alumno con codigo ".$alu['codigo'].". Ha ocurrido un error en la BD al sumar la inscripcion. Intentelo de nuevo.}",Logger::ERROR);
                                mysql_query("ROLLBACK") or die("INSCRIPCION_3");

                            }
                        }

                    }else{//si no hay cupo se lanza error
                        $err=true;
                        $this->option="error";
                        $this->error="No hay cupo en  la trayectoria<br/>".$trayectoria["nombre"].".";
                        $log->log("Alumno con codigo ".$alu['codigo'].". No hay cupo en  la trayectoria<br/>".$trayectoria["nombre"]."}",Logger::ERROR);
                        mysql_query("ROLLBACK") or die("INSCRIPCION_5");

                    }

                }else{//si la trayectoria no corresponde al turno
                    $err=true;
                    $this->option="error";
                    $this->error="La trayectoria seleccionada no corresponde a su turno";
                    $log->log("Alumno con codigo ".$alu['codigo'].". Ha ocurrido un error. La trayectoria seleccionada no corresponde a su turno.}",Logger::ERROR);
                    mysql_query("ROLLBACK") or die("INSCRIPCION_3");

                }

                }else{//sino existe la trayectria se lanza error
                    $err=true;
                    $this->option="error";
                    $this->error="Ha ocurrido un error en la BD. No existe la trayectoria seleccionada.";
                    $log->log("Alumno con codigo ".$alu['codigo'].". Ha ocurrido un error en la BD. No existe la trayectoria seleccionada.}",Logger::ERROR);
                    mysql_query("ROLLBACK") or die("INSCRIPCION_3");

                }
            }//fin if

            if(!$err){
                mysql_query("COMMIT") or die("INSCRIPCION_2");
                $this->option="exito";
                $log->log("{El alumno con codigo ".$alu['codigo']." se inscribio en la trayectoria: ".$trayectoria["nombre"]." }");
            }
        }catch(Exception $e){
            $this->option="error";
            $this->error="Ha ocurrido un error en la BD.".mysql_error();
            $log->log("{Alumno con codigo ".$alu['codigo'].". Ocurrio un error con la BD.".mysql_error()."}",Logger::ERROR);

        }
        }else{
        $this->option="error";
        $this->error="Ha ocurrido un error en la configuracion.";
        $log->log("{Alumno con codigo ".$alu['codigo'].". Ocurrio un error con la configuracion.}",Logger::ERROR);

        }

        }else{
        $this->option="error";
        $this->error="Los datos no son validos.";
        $log->log("{Los datos no son validos.}",Logger::ERROR);
        }
        }else{
            $this->option="error";
            $this->error="La agenda se encuentra cerrada. ";
            $log->log("{Ha entrado a selección de cursos el alumno con codigo ".$alu['codigo'].". La agenda se encuentra cerrada.}",Logger::ERROR);
        }
        }else{
            $this->option="error";
            $this->error="El usuario ya esta inscrito a una trayectoria. ";
            $log->log("{Ha entrado a selección de cursos el alumno con codigo ".$alu['codigo'].". El usuario ya esta inscrito a una trayectoria.}",Logger::ERROR);
        }
        }else{
            $this->option="error";
            $this->error="No tiene permiso para entrar a la pagina. ";
        }

        $log->commit();
        }else{
        $this->option="error";
        $this->error="No tiene permiso para entrar a la agenda.";
        }
    }

}

?>
