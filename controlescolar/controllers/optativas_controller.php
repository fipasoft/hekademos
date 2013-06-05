<?php
 /**
 * optativas_controller.php
 *
 * Created on 04/05/2009
 * @package  Controladores
 * @author     J Jonathan Lopez <jlopez@fipasoft.com.mx>
 * @copyright  2009 FiPa Software (contacto at fipasoft.com.mx)
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL)
 *
 */

Kumbia :: import('app.componentes.*');
 class OptativasController extends ApplicationController {
     public $template = "system";

    public function alumnos($id=''){
    $controlador = $this->controlador;
        $accion = $this->accion;
        $this->alumnosTotal="";
        if($this->post("periodo_id")!=""){
            $this->option="agregar";
            $agregados=array();
            $periodo=new Periodo();
            $periodo=$periodo->find($this->post("periodo_id"));
            if($periodo->id!=""){
            if(is_array($this->post("alumnos"))){
            foreach($this->post("alumnos") as $id){
                $agregar=new Periodosalumnos();
                $alumno=new Alumnos();

                $alumno=$alumno->find($id);
                if($alumno->id!=""){
                    if(!$agregar->exists("alumnos_id='".$id."' AND periodo_id='".$this->post("periodo_id")."'")){
                    $agregar->alumnos_id=$id;
                    $agregar->periodo_id=$this->post('periodo_id');
                    $agregar->save();
                    $agregados[]=array('alumno'=>$alumno,"msj"=>"Alumno agregado con exito","clase"=>"info");
                    }else{
                    $agregados[]=array('alumno'=>$alumno,"msj"=>"El Alumno ya estaba agregado","clase"=>"warning");
                    }
                }else{
                    $agregados[]=array('alumno'=>$id,"msj"=>"El alumno ha sido eliminado. No se pudo agregar","clase"=>"error");
                }

            }
            $ciclo=new Ciclos();
            $ciclo=$ciclo->find($periodo->ciclos_id);
            $tt=count($this->post('alumnos'));
            $this->agregados=$agregados;
            $historial=new Historial();
            $historial->ciclos_id= Session :: get_data('ciclo.id');
            $historial->usuario=Session :: get_data('usr.login');
            $historial->descripcion='Se agreg'.($tt!=1 ? 'aron ' : 'o ' ).$tt.' alumno'.($tt!=1 ? 's' : '' ).' para el periodo del ciclo '.$ciclo->numero.' que inicia el '.Utils::convierteFecha(substr($periodo->inicio,0,10)).' '.substr($periodo->inicio,10).' y finaliza el '.Utils::convierteFecha(substr($periodo->fin,0,10)).' '.substr($periodo->fin,10);
            $historial->controlador=$this->controlador;
            $historial->accion=$this->accion;
            $historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
            $historial->save();
            }else{
                $this->option="error";
                $this->error="Los datos no son congruentes";
            }

            }else{
                $this->option="error";
                $this->error="El periodo no existe";
            }
        }elseif($id!=""){
        //acl
                $usr_login = Session :: get_data('usr.login');
                $this->acl = array ();
                $acl = new gacl_extra();
                $acos_arr = array (
                    'optativas' =>array(
                        'eliminar'
                    )

                );

                $this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);

        $id=intval($id,10);
        $this->option_cursos="alumnos";
        $periodo=new Periodo();
        $periodo=$periodo->find($id);
        if($periodo->id!=""){
        if(Session::get_data("optativas.alumnos.tab")!="3" && $this->post('codigo1')=="" && $this->post('nombre1')==""){
        $this->tab="1";
        Session::set_data("optativas.alumnos.tab","1");
        }else{
        $this->tab="2";
        Session::set_data("optativas.alumnos.tab","2");

        }

        $this->periodo=$periodo;

        // busqueda
        $b = new Busqueda($controlador, $accion);
        $b->establecerCondicion('oferta_id', "grupos.oferta_id  = '" . $b->campo('oferta_id') . "' ");
        $b->establecerCondicion('codigo', "alumnos.codigo  LIKE '%" . $b->campo('codigo') . "%' ");
        $b->establecerCondicion('nombre', "CONCAT(TRIM(alumnos.nombre), ' ', TRIM(alumnos.ap), ' ', TRIM(alumnos.am)) LIKE '%" . $b->campo('nombre') . "%' ");
        $b->quitarCondicion('periodo_id');
        $b->quitarCondicion('cursos');
        $b->quitarCondicion('grupos_id');
        $b->quitarCondicion('codigo1');
        $b->quitarCondicion('nombre1');
            // genera las condiciones
        $c = $b->condicion(array (
            'oferta_id'
        ));
        $b->guardar();
        $this->busqueda = $b;


        if($c!=""){
        $alumnos = new Alumnos();
            // cuenta todos los registros
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
        "WHERE " .
        "ciclos.id = '" . $periodo->ciclos_id . "'" .
         ($c == "" ? "" : "AND " . $c) . " ) " .
        "AS t1 ");

        // ejecuta la consulta
        $this->alumnos = $alumnos->find_all_by_sql("SELECT " .
        "alumnos.codigo, " .
        "alumnos.id, " .
        "alumnos.nombre, " .
        "alumnos.am, " .
        "alumnos.ap, " .
        "grupos.id AS grupos_id, " .
        "grupos.grado, " .
        "grupos.letra," .
        "grupos.turno, " .
        "alumnos.foto " .
        "FROM " .
        "ciclos " .
        "Inner Join grupos ON ciclos.id = grupos.ciclos_id " .
        "Inner Join alumnosgrupo ON grupos.id = alumnosgrupo.grupos_id " .
        "Inner Join alumnos ON alumnosgrupo.alumnos_id = alumnos.id " .
        "WHERE " .
        "ciclos.id = '" . $periodo->ciclos_id . "'" .
         ($c == "" ? "" : "AND " . $c) . " " .
        "ORDER BY " .
        "grupos.turno, grupos.grado, grupos.letra, alumnos.ap, alumnos.am, alumnos.nombre " );
        $this->option_cursos="alumnos";
        }else{
            $this->alumnos = array();
            $this->registros = 0;
            $this->option_cursos="alumnos";
        }

        $this->option="captura";

        $this->ofertas = new Oferta();
        $this->ofertas = $this->ofertas->find();

        $existentes=array();
        $periodosalumnos=new Periodosalumnos();
        $periodosalumnos=$periodosalumnos->find_all_by_sql(
                "SELECT periodosalumnos.*,grupos.id as grupos_id " .
                " FROM " .
                " periodosalumnos " .
                " INNER JOIN alumnos ON periodosalumnos.alumnos_id=alumnos.id " .
                " Inner Join alumnosgrupo ON alumnos.id=alumnosgrupo.alumnos_id " .
                " INNER JOIN grupos ON alumnosgrupo.grupos_id=grupos.id " .
                " WHERE periodosalumnos.periodo_id='".$periodo->id."' AND grupos.ciclos_id='".$periodo->ciclos_id."'"
                );



        $this->alumnosTotal=count($periodosalumnos);
        $grupos=array();
        foreach($periodosalumnos as $a){
        $existentes[]=$a->alumnos_id;
            $grupos[$a->grupos_id]=$a->grupos_id;
        }

        $this->grupos=array();
        foreach($grupos as $g){
        $grupo=new Grupos();
        $grupo=$grupo->find($g);

        $this->grupos[$grupo->grado.$grupo->letra.$grupo->id]=$grupo;
        }
        sort($this->grupos);

        $b1 = new Busqueda($controlador, $accion."panel2");
        // genera las condiciones
        $b1->establecerCondicion('codigo1', "alumnos.codigo  LIKE '%" . $b1->campo('codigo1') . "%' ");
        $b1->establecerCondicion('nombre1', "CONCAT(TRIM(alumnos.nombre), ' ', TRIM(alumnos.ap), ' ', TRIM(alumnos.am)) LIKE '%" . $b1->campo('nombre1') . "%' ");
        $b1->establecerCondicion('grupos_id', "grupos.id  = '" . $b1->campo('grupos_id') . "' ");
        $b1->quitarCondicion('grado');
        $b1->quitarCondicion('letra');
        $b1->quitarCondicion('turno');
        $b1->quitarCondicion('oferta_id');
        $b1->quitarCondicion('codigo');
        $b1->quitarCondicion('nombre');
        $c = $b1->condicion();
        $b1->guardar();
        $this->busqueda1 = $b1;

        if($this->post("grupos_id")!=""){
        Session::set_data("optativas.alumnos.tab","2");
        $this->tab="2";
        }

        if($c!=""){
        $alumnos_admin=new Alumnos();
        $alumnos_admin=$alumnos_admin->find_all_by_sql(
                    "SELECT periodosalumnos.id AS periodosalumnos_id,alumnos.*,grupos.id AS grupos_id FROM " .
                    " periodosalumnos " .
                    " INNER JOIN alumnos ON periodosalumnos.alumnos_id=alumnos.id " .
                    " Inner Join alumnosgrupo ON alumnos.id=alumnosgrupo.alumnos_id " .
                    " INNER JOIN grupos ON alumnosgrupo.grupos_id=grupos.id " .
                    " WHERE periodosalumnos.periodo_id='".$periodo->id."' AND ".$c." AND grupos.ciclos_id='".$periodo->ciclos_id."' " .
                    " ORDER BY grupos.turno, grupos.grado, grupos.letra, alumnos.ap, alumnos.am, alumnos.nombre"
        );
        }else{
            $alumnos_admin=array();
        }
        $this->alumnos_admin=$alumnos_admin;

        $this->registrados=count($alumnos_admin);

        $this->existentes=$existentes;


        }else{
            $this->option="error";
            $this->error="No existe el periodo especificado.";

        }

        }else{
            $this->option="error";
            $this->error="No existe el periodo especificado.";

        }


    }

    public function avanzadas(){
        if($this->post("periodo_id")!=""){
            $this->periodo_id=intval($this->post("periodo_id"),10);
            $this->periodo=new Periodo();
            $this->periodo=$this->periodo->find($this->periodo_id);
            if($this->periodo->id!=""){
            $dias=$this->post("dias");
            if(is_array($dias) && $this->post("hora_inicio")!="" && $this->post("minutos_inicio")!="" && $this->post("hora_fin")!="" && $this->post("minutos_fin")!=""){

                $inicio=$this->post("hora_inicio").$this->post("minutos_inicio");
                $fin=$this->post("hora_fin").$this->post("minutos_fin");

                if(strlen($inicio)==4 && strlen($fin)==4 && $inicio<$fin){
                $horarios=new Periodohorario();
                $horarios=$horarios->find("periodo_id='".$this->post("periodo_id")."'");
                foreach($horarios as $h){
                    if(in_array($h->dias_id,$dias)){
                    $h->inicio=intval($this->post("hora_inicio"),10).":".intval($this->post("minutos_inicio"),10).":00";
                    $h->fin=intval($this->post("hora_fin"),10).":".intval($this->post("minutos_fin"),10).":00";
                    $h->save();
                    }else{
                    $h->delete();
                    }
                }

            foreach($dias as $d){
                    $periodohorario=new Periodohorario();
                    $periodohorario=$periodohorario->find_first("periodo_id='".$this->post("periodo_id")."' AND dias_id='".$d."'");
                    if($periodohorario->id==""){
                    $periodohorario=new Periodohorario();
                    $periodohorario->dias_id=$d;
                    $periodohorario->periodo_id=$this->post("periodo_id");
                    $periodohorario->inicio=$this->post("hora_inicio").":".$this->post("minutos_inicio").":00";
                    $periodohorario->fin=$this->post("hora_fin").":".$this->post("minutos_fin").":00";
                    $periodohorario->save();
                    }
            }


                $this->option="exito";
                $ciclo=new Ciclos();
                $ciclo=$ciclo->find($this->periodo->ciclos_id);
                $historial=new Historial();
                $historial->ciclos_id= Session :: get_data('ciclo.id');
                $historial->usuario=Session :: get_data('usr.login');
                $historial->descripcion='Se editaron las opciones avanzadas de bloques para el periodo del ciclo '.$ciclo->numero.' que inicia el '.Utils::convierteFecha(substr($this->periodo->inicio,0,10)).' '.substr($this->periodo->inicio,10).' y finaliza el '.Utils::convierteFecha(substr($this->periodo->fin,0,10)).' '.substr($this->periodo->fin,10);
                $historial->controlador=$this->controlador;
                $historial->accion=$this->accion;
                $historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
                $historial->save();
            }else{
            $this->option="error";
            $this->error="Los datos de hora no son correctos.";
            }
            }else{
            $this->option="error";
            $this->error="Los datos no son correctos.";
            }
        }else{
            $this->option="error";
            $this->error="No existe el periodo.";
        }
        }else{
            $this->option="error";
            $this->error="No existe el periodo.";
        }
    }

    public function bloques($id=''){
        if($id!=""){
                //acl
                $usr_login = Session :: get_data('usr.login');
                $this->acl = array ();
                $acl = new gacl_extra();
                $acos_arr = array (
                    'optativas' =>array(
                        'avanzadas'
                    )

                );

            $this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);
            $id=intval($id,10);
            $periodo=new Periodo();
            $periodo=$periodo->find($id);
            if($periodo->id!=""){
                if(!$periodo->actual()){
                    $this->periodo=$periodo;

                    $alumnos=new Periodosalumnos();
                    $this->totalAlumnos=$alumnos->count("periodo_id='".$periodo->id."'");
                    if($this->totalAlumnos>0){

                    $this->option="vista";
                    $dias=new Dias();
                    $this->dias=$dias->find();

                    $periodohorarios=new Periodohorario();
                    $periodohorarios=$periodohorarios->find("periodo_id='".$periodo->id."'");

                    $horarios=array();
                    $dh=array();
                    foreach($periodohorarios as $h){
                        $horarios[$h->dias_id]=$h;
                        $dh[]=$h->dias_id;
                    }

                    $hh=split(":",$h->inicio);
                    $this->hora_inicio=intval($hh[0],10);
                    $this->minutos_inicio=intval($hh[1],10);

                    $hh=split(":",$h->fin);
                    $this->hora_fin=intval($hh[0],10);
                    $this->minutos_fin=intval($hh[1],10);

                    $this->horarios=$horarios;
                    $this->dh=$dh;
                    $bloques=new Bloque();
                    $this->existenbloques=$bloques->exists("periodo_id='".$periodo->id."'");

                    }else{
                    $this->option="warning";
                    $this->warning="Primero seleccione los alumnos para el periodo.";
                    }
                }else{
                    $this->option = 'warning';
                    $this->warning = ' No se pueden volver a crear los bloques.<br/> Se esta llevando actualmente el registro.';
                }
            }else{
            $this->option="error";
            $this->error="No existe el periodo.";
            }


        }elseif($this->post("periodo_id")!=""){
            $periodo=new Periodo();
            $id=intval($this->post("periodo_id"),10);
            $periodo=$periodo->find($id);
            if($periodo->id!=""){
                if(!$periodo->actual()){
                    $this->periodo=$periodo;
                    mysql_query("BEGIN") or die("BLOQUES_1");
                    $bloques=new Bloque();
                    if($bloques->delete("periodo_id='".$periodo->id."'")==true){
                    $bloquesTotal=intval($this->post("bloques"));
                    if($this->post('orden')=="p"){
                        $orden=" ORDER BY promedio DESC";
                        $or="promedio";
                    }elseif($this->post('orden')=="a"){
                        $orden=" ORDER BY ap,am,nombre";
                        $or="orden alfabetico";
                    }else{
                        $orden=" ORDER BY promedio DESC";
                        $or="promedio";
                    }

                    $alumnos=new Alumnos();
                    $alumnos=$alumnos->find_all_by_sql(
                        "SELECT periodosalumnos.id as periodosalumnos_id,alumnos.* FROM " .
                        " periodosalumnos " .
                        " INNER JOIN alumnos ON periodosalumnos.alumnos_id=alumnos.id " .
                        " WHERE periodosalumnos.periodo_id='".$periodo->id."' ".
                        $orden
                    );

                    $alumnostotal=count($alumnos);
                    if($alumnostotal>0){
                        if($alumnostotal>=$bloquesTotal){
                        $periodo->intervalo=intval($this->post('intervalo'),10);
                        $periodo->save();
                        $periodosalumnos=new Periodosalumnos();
                        $total=$periodosalumnos->count("periodo_id='".$periodo->id."'");
                        $axb=intval($total/$bloquesTotal);
                        $r=$total%$bloquesTotal;

                        $periodoInicio= new DateTime( $periodo->inicio );

                        $inicio_periodo  =  new DateTime( $periodo->inicio );
                        $fin_periodo  =  new DateTime( $periodo->fin );

                        $horario = new Periodohorario();
                        $horario = $horario->find("periodo_id='".$periodo->id."'");
                        $dias=array();
                        $horarios=array();
                        foreach($horario as $h){
                            $dias[]=$h->dias_id;
                            $horarios[$h->dias_id]=$h;
                        }

                        $index=0;
                        $log=new Logger('bloques.log');

                        for($i=0;$i<$bloquesTotal;$i++){
                            $log->log("1 ".$inicio_periodo->format("Y-m-d H:i:s")." <= ".$fin_periodo->format("Y-m-d H:i:s"));
                            $log->log("2 ".$inicio_periodo->format("N"));
                            if($inicio_periodo->format("U") <= $fin_periodo->format('U')){
                            if(!in_array($inicio_periodo->format('N'),$dias)){
                                $inicio_periodo->modify('+ 1 day');
                                while(!in_array($inicio_periodo->format('N'),$dias)){
                                    $inicio_periodo->modify('+ 1 day');
                                }
                                $h=$horarios[$inicio_periodo->format('N')];
                                $inicio_periodo=new DateTime($inicio_periodo->format("Y-m-d ").$h->inicio);
                                $log->log("3 ".$inicio_periodo->format("Y-m-d H:i:s"));

                            }else{
                                $h=$horarios[$inicio_periodo->format('N')];
                                $inicio_tempo=new DateTime($inicio_periodo->format("Y-m-d ").$h->inicio);
                                if($inicio_periodo->format('U')<$inicio_tempo->format('U'))
                                    $inicio_periodo=new DateTime($inicio_periodo->format("Y-m-d ").$h->inicio);
                            }





                        $finT=new DateTime($inicio_periodo->format("Y-m-d ").$h->fin);
                        $inicioT=new DateTime($inicio_periodo->format("Y-m-d ").$h->inicio);

                        $log->log("5 ".$inicio_periodo->format("Y-m-d H:i:s"));
                        $log->log("6 ".$finT->format("Y-m-d H:i:s"));
                        $tempo=new DateTime($inicio_periodo->format("Y-m-d H:i:s"));
                        $tempo->modify('+'.$periodo->intervalo.' minutes');

                        if($tempo->format("U")<=$fin_periodo->format('U')){
                        if( $tempo->format('U')>$finT->format('U')){
                            $inicio_periodo->modify('+ 1 day');
                            if(!in_array($inicio_periodo->format('N'),$dias)){
                            $inicio_periodo->modify('+ 1 day');
                            while(!in_array($inicio_periodo->format('N'),$dias)){
                                $inicio_periodo->modify('+ 1 day');
                            }

                        }
                            $h=$horarios[$inicio_periodo->format('N')];
                            $inicio_periodo=new DateTime($inicio_periodo->format("Y-m-d ").$h->inicio);
                            $log->log("7 ".$inicio_periodo->format("Y-m-d H:i:s"));

                            $inicio = $inicio_periodo->format("Y-m-d H:i:s");
                                      $inicio_periodo->modify('+'.$periodo->intervalo.' minutes');
                            $fin     = $inicio_periodo->format("Y-m-d H:i:s");
                            $log->log("8 ".$inicio_periodo->format("Y-m-d H:i:s"));

                        }else{
                        $log->log("4 ".$inicio_periodo->format("Y-m-d H:i:s"));
                        $inicio = $inicio_periodo->format("Y-m-d H:i:s");
                        $inicio_periodo->modify('+'.$periodo->intervalo.' minutes');
                        $fin     = $inicio_periodo->format("Y-m-d H:i:s");
                        }

                        if($inicio_periodo->format("U") <= $fin_periodo->format('U')){

                        $bloque=new Bloque();
                        $bloque->periodo_id=$periodo->id;
                        $bloque->inicio=$inicio;
                        $bloque->fin=$fin;

                        if($bloque->save()){
                            $max=$index+$axb;
                            if($r>0){
                                $max++;
                                $r--;
                            }

                            for($index=$index;$index<$max;$index++){
                            $alumno=$alumnos[$index];
                            $log->log("9 max: ".$max." index: ".$index);
                            $bloquealumno=new Bloquesalumnos();
                            $bloquealumno->bloque_id=$bloque->id;
                            $bloquealumno->periodosalumnos_id=$alumno->periodosalumnos_id;
                            if(!$bloquealumno->save()){
                                mysql_query("ROLLBACK") or die("BLOQUES_4");
                                $this->option="error";
                                $this->error="Ocurrio un error en la bd.";
                                return;                    }
                            }
                        }else{
                            mysql_query("ROLLBACK") or die("BLOQUES_3");
                            $this->option="error";
                            $this->error="Ocurrio un error en la bd.";
                            return;
                        }
                        }else{
                            mysql_query("ROLLBACK") or die("BLOQUES_4");
                                $this->option="error";
                                $this->error="Los bloques no pueden ser creados en el periodo.Reduzca el numero de bloques o aumente el tiempo del periodo.";
                                return;
                        }
                        }else{
                            mysql_query("ROLLBACK") or die("BLOQUES_4");
                                $this->option="error";
                                $this->error="Los bloques no pueden ser creados en el periodo.Reduzca el numero de bloques o aumente el tiempo del periodo.";
                                return;
                        }
                        }else{
                            mysql_query("ROLLBACK") or die("BLOQUES_5");
                                $this->option="error";
                                $this->error="Los bloques no pueden ser creados en el periodo.Reduzca el numero de bloques o aumente el tiempo del periodo.";
                                return;
                        }

                    }
                    $log->close();
                    $this->option="exito";
                    $ciclo=new Ciclos();
                        $ciclo=$ciclo->find($this->periodo->ciclos_id);
                        $historial=new Historial();
                        $historial->ciclos_id= Session :: get_data('ciclo.id');
                        $historial->usuario=Session :: get_data('usr.login');
                        $historial->descripcion='Se cre'.($bloquesTotal!=1 ? 'aron' : 'o').' '.$bloquesTotal.' bloque'.($bloquesTotal!=1 ? 's' : '').' con un intervalo de '.$periodo->intervalo.' minuto'.($periodo->intervalo!=1 ? 's': '').' ordenado por '.$or.' para el periodo del ciclo '.$ciclo->numero.' que inicia el '.Utils::convierteFecha(substr($this->periodo->inicio,0,10)).' '.substr($this->periodo->inicio,10).' y finaliza el '.Utils::convierteFecha(substr($this->periodo->fin,0,10)).' '.substr($this->periodo->fin,10);
                        $historial->controlador=$this->controlador;
                        $historial->accion=$this->accion;
                        $historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
                        $historial->save();
                    mysql_query("COMMIT") or die("BLOQUES_2");

                    }else{
                    $this->option="error";
                    $this->error="Hay mas bloques que alumnos.";
                    }
                    }else{
                    $this->option="warning";
                    $this->warning="Primero seleccione los alumnos para el periodo.";
                    }
                }else{
                    mysql_query("ROLLBACK") or die("BLOQUES_5");
                    $this->option="error";
                    $this->error="Ocurrio un error en la bd.";
                    }
            }else{
                    $this->option = 'warning';
                    $this->warning = ' No se pueden volver a crear los bloques.<br/> Se esta llevando actualmente el registro.';
            }
        }else{
            $this->option="error";
            $this->error="No existe el periodo.";
        }
        }else{
            $this->option="error";
            $this->error="No existe el periodo.";
        }
    }


    public function cupos(){
        $this->set_response('view');
        $id = $this->post('id');
        $cupos = $this->post('cupos');
        $periodoscursos=new Periodoscursos();
        $periodoscursos=$periodoscursos->find_first($id);
        if($periodoscursos->id!=""){
            if($cupos>=$periodoscursos->inscritos){
            $periodoscursos->cupos=$cupos;
            if($periodoscursos->save()){
                $curso=new Cursos();
                $curso=$curso->find($periodoscursos->cursos_id);
                $materia=$curso->materia();
                $grupo=$curso->grupo();

                $periodo=new Periodo();
                $periodo=$periodo->find($periodoscursos->periodo_id);

                $ciclo=new Ciclos();
                $ciclo=$ciclo->find($periodo->ciclos_id);

                $historial=new Historial();
                $historial->ciclos_id= Session :: get_data('ciclo.id');
                $historial->usuario=Session :: get_data('usr.login');
                $historial->descripcion='Se coloco un cupo de '.$cupos.' para el curso '.$materia->nombre.' del grupo '.$grupo->ver().'. Perteneciente al periodo del ciclo '.$ciclo->numero.' que inicia el '.Utils::convierteFecha(substr($periodo->inicio,0,10)).' '.substr($periodo->inicio,10).' y finaliza el '.Utils::convierteFecha(substr($periodo->fin,0,10)).' '.substr($periodo->fin,10);
                $historial->controlador=$this->controlador;
                $historial->accion=$this->accion;
                $historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
                $historial->save();
                $this->resp="1";
            }else{
                $this->resp="No se pudo guardar el cupo intentelo de nuevo.";
            }
            }else{
                $this->resp="El cupo minimo es ".$periodoscursos->inscritos.".";
            }
        }else{
                $this->resp="El curso no se encuentra en el periodo.";
        }

    }

    public function eliminar($tipo=''){
        $this->tipo=$tipo;
        if($tipo=="cursos"){
            if(is_array($this->post("elimina"))){
                $this->periodo=new Periodo();
                $this->periodo=$this->periodo->find(intval($this->post('periodo_id'),10));
                $ciclo=new Ciclos();
                $this->ciclo=$ciclo->find($this->periodo->ciclos_id);
                foreach($this->post("elimina") as $id){
                    $periodoscursos=new Periodoscursos();
                    $periodoscursos->delete($id);
                }

                $tt=count($this->post("elimina"));
                $this->option="cursos";
                $historial=new Historial();
                $historial->ciclos_id= Session :: get_data('ciclo.id');
                $historial->usuario=Session :: get_data('usr.login');
                $historial->descripcion='Eliminación de '.$tt.' curso'.($tt!=1 ? 's' : '').' .Perteneciente al periodo del ciclo '.$this->ciclo->numero.' que inicia el '.Utils::convierteFecha(substr($this->periodo->inicio,0,10)).' '.substr($this->periodo->inicio,10).' y finaliza el '.Utils::convierteFecha(substr($this->periodo->fin,0,10)).' '.substr($this->periodo->fin,10);
                $historial->controlador=$this->controlador;
                $historial->accion=$this->accion;
                $historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
                $historial->save();
            }else{
            $this->option="error";
            $this->error="Los datos no son validos.";

            }
            }elseif($tipo=="alumnos"){
                if(is_array($this->post("elimina"))){
                Session::set_data("optativas.alumnos.tab","3");
                $this->periodo=new Periodo();
                $this->periodo=$this->periodo->find(intval($this->post('periodo_id'),10));
                $ciclo=new Ciclos();
                $this->ciclo=$ciclo->find($this->periodo->ciclos_id);
                foreach($this->post("elimina") as $id){
                    $periodosalumnos=new Periodosalumnos();
                    $periodosalumnos->delete($id);
                }



                $this->option="alumnos";
                $tt=count($this->post("elimina"));
                $historial=new Historial();
                $historial->ciclos_id= Session :: get_data('ciclo.id');
                $historial->usuario=Session :: get_data('usr.login');
                $historial->descripcion='Eliminación de '.$tt.' alumno'.($tt!=1 ? 's' : '').' .Perteneciente al periodo del ciclo '.$this->ciclo->numero.' que inicia el '.Utils::convierteFecha(substr($this->periodo->inicio,0,10)).' '.substr($this->periodo->inicio,10).' y finaliza el '.Utils::convierteFecha(substr($this->periodo->fin,0,10)).' '.substr($this->periodo->fin,10);
                $historial->controlador=$this->controlador;
                $historial->accion=$this->accion;
                $historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
                $historial->save();
            }else{
            $this->option="error";
            $this->error="Los datos no son validos.";

            }
            }else{
            $this->option="error";
            $this->error="No tiene permiso para ver la pagina.";
        }
    }

    public function configuracion($id=""){
        $controlador = $this->controlador;
        $accion = $this->accion;
        if($id!=""){
        $id=intval($id,10);
        $periodo=new Periodo();
        $periodo=$periodo->find($id);
            if($periodo->id!=""){
            $this->periodo=$periodo;
            if(!$periodo->actual()){
            $cursos_admin=new Cursos();
            $cursos_admin=$cursos_admin->find_all_by_sql(
                    "SELECT periodoscursos.id AS periodoscursos_id,periodoscursos.cupos,cursos.*,materias.nombre as materia,materias.tipo AS materiaTipo FROM " .
                    " periodoscursos " .
                    " INNER JOIN cursos ON periodoscursos.cursos_id=cursos.id " .
                    " INNER JOIN grupos ON cursos.grupos_id=grupos.id ".
                    " INNER JOIN materias ON cursos.materias_id  = materias.id " .
                    " WHERE periodoscursos.periodo_id='".$periodo->id."'".
                    "ORDER BY grupos.turno, grupos.grado, grupos.letra,materias.tipo "
            );

            $info=array();
            $config=array();
            $configuracion=new Periodoconfiguracion();
            $configuracion=$configuracion->find("periodo_id='".$periodo->id."' " .
                                                " ORDER BY turno,grado,tipo");

            foreach($cursos_admin as $c){
                $g=$c->grupo();
                $info[$g->oferta_id][$g->turno][$g->grado][$c->materiaTipo][]=$c;
            }

            foreach($configuracion as $cn){
                $config[$cn->oferta_id][$cn->turno][$cn->grado][$cn->tipo]=$cn;
            }

            $this->config=$config;
            $this->info=$info;
            $this->option="vista";

            if(count($this->info)==0){
                $this->option="warning";
                $this->warning="Primero seleccione los cursos para el periodo.";

            }
            }else{
                    $this->option = 'warning';
                    $this->warning = ' No se pueden volver a configurar.<br/> Se esta llevando actualmente el registro.';
            }

            }else{
                $this->option="error";
                $this->error="No existe el periodo especificado.";

            }
        }elseif($this->post('periodo_id')!=""){
                $this->diferente=$this->post('diferente');
                $this->maximo=$this->post('maximo');
                $this->dtipo=$this->post('dtipo');
                
                $id=intval($this->post('periodo_id'),10);
                $periodo=new Periodo();
                $periodo=$periodo->find($id);
                if($periodo->id!=""){
                    if(!$periodo->actual()){
                    if(is_array($this->post('maximo'))){

                        $this->periodo=$periodo;
                        $this->option="exito";
                        $turno=$this->post('turno');
                        $grado=$this->post('grado');
                        $tipo=$this->post('tipo');
                        $oferta=$this->post('oferta');
                        mysql_query("BEGIN") or die("CIERRE_1");
                        try{
                        $err=false;
                        foreach($this->post('maximo') as $k=>$v){
                            $configuracion=new Periodoconfiguracion();
                            $configuracion->find_first("periodo_id='".$id."' AND oferta_id='".$oferta[$k]."' AND turno='".$turno[$k]."'  AND grado='".$grado[$k]."' " .
                                                "  AND tipo='".$tipo[$k]."'");

                            if($configuracion->id=="")
                            $configuracion=new Periodoconfiguracion();

                            $configuracion->periodo_id=$id;
                            $configuracion->oferta_id=$oferta[$k];
                            $configuracion->turno=$turno[$k];
                            $configuracion->grado=$grado[$k];
                            $configuracion->tipo=$tipo[$k];
                            $configuracion->maximo=$this->maximo[$k];
                            
                            if($this->diferente[$k]!='1')
                            $this->diferente[$k]="0";

                            if($this->dtipo[$k]!='1')
                            $this->dtipo[$k]="0";
                            
                            
                            $configuracion->diferente=$this->diferente[$k];
                            $configuracion->dtipo=$this->dtipo[$k];
                            
                            if(!$configuracion->save()){
                            mysql_query("ROLLBACK") or die("CIERRE_3");
                            $err=true;
                            $this->option = 'error';
                            $this->error .= 'Ocurrio un error en la bd.';
                            break;
                            }
                        }

                        if(!$err){
                        $ciclo=new Ciclos();
                        $ciclo=$ciclo->find($periodo->ciclos_id);
                        $historial=new Historial();
                        $historial->ciclos_id= Session :: get_data('ciclo.id');
                        $historial->usuario=Session :: get_data('usr.login');
                        $historial->descripcion='Se configuro la selección de cursos para el periodo del ciclo '.$ciclo->numero.' que inicia el '.Utils::convierteFecha(substr($periodo->inicio,0,10)).' '.substr($periodo->inicio,10).' y finaliza el '.Utils::convierteFecha(substr($periodo->fin,0,10)).' '.substr($periodo->fin,10);
                        $historial->controlador=$this->controlador;
                        $historial->accion=$this->accion;
                        $historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
                        $historial->save();

                        mysql_query("COMMIT") or die("CIERRE_2");
                        }

                        }catch(Exception $e){
                        $this->option = 'error';
                        $this->error .= 'Ocurrio un error en la bd.';
                        mysql_query("ROLLBACK") or die("CIERRE_3");
                        }
                    }else{
                    $this->option="error";
                    $this->error="Los datos no son validos.".count($this->post('maximo'))."==".count($this->post('diferente'));

                    }
                    }else{
                    $this->option = 'warning';
                    $this->warning = ' No se pueden volver a configurar.<br/> Se esta llevando actualmente el registro.';
                    }
            }else{
                $this->option="error";
                $this->error="No existe el periodo especificado.";

            }
        }else{
            $this->option="error";
            $this->error="No existe el periodo especificado.";

        }

    }

    public function cursos($id=""){
        $controlador = $this->controlador;
        $accion = $this->accion;
        if($this->post("periodo_id")!=""){
            $this->option="agregar";
            $agregados=array();
            $periodo=new Periodo();
            $periodo=$periodo->find($this->post("periodo_id"));
            if($periodo->id!=""){
            $ciclo=new Ciclos();
            $ciclo=$ciclo->find($periodo->ciclos_id);
            if(is_array($this->post("cursos"))){
            foreach($this->post("cursos") as $id){
                $agregar=new Periodoscursos();
                $curso=new Cursos();

                $curso=$curso->find($id);
                if($curso->id!=""){
                    if(!$agregar->exists("cursos_id='".$id."' AND periodo_id='".$this->post("periodo_id")."'")){
                    $agregar->cursos_id=$id;
                    $agregar->periodo_id=$this->post('periodo_id');
                    $agregar->cupos="0";
                    $agregar->inscritos="0";
                    $agregar->save();
                    $agregados[]=array('curso'=>$curso,"msj"=>"Curso agregado con exito","clase"=>"info");
                    }else{
                    $agregados[]=array('curso'=>$curso,"msj"=>"El curso ya estaba agregado","clase"=>"warning");
                    }
                }else{
                    $agregados[]=array('curso'=>$id,"msj"=>"El curso ha sido eliminado. No se pudo agregar","clase"=>"error");
                }

            }
            $tt=count($this->post("cursos"));
            $this->agregados=$agregados;
            $historial=new Historial();
            $historial->ciclos_id= Session :: get_data('ciclo.id');
            $historial->usuario=Session :: get_data('usr.login');
            $historial->descripcion='Se agreg'.($tt!=1 ? 'aron ' : 'o ' ).$tt.' curso'.($tt!=1 ? 's' : '' ).' para el periodo del ciclo '.$ciclo->numero.' que inicia el '.Utils::convierteFecha(substr($periodo->inicio,0,10)).' '.substr($periodo->inicio,10).' y finaliza el '.Utils::convierteFecha(substr($periodo->fin,0,10)).' '.substr($periodo->fin,10);
            $historial->controlador=$this->controlador;
            $historial->accion=$this->accion;
            $historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
            $historial->save();
            }else{
                $this->option="error";
                $this->error="Los datos no son congruentes";
            }
            }else{
                $this->option="error";
                $this->error="El periodo no existe";
            }
        }elseif($id!=""){

        $id=intval($id,10);
        $periodo=new Periodo();
        $periodo=$periodo->find($id);

        $this->tab="1";
        if($this->post("grado1")!="" || $this->post("letra1")!="" || $this->post("turno1")!=""||
             $this->post("oferta_id1")!="" || $this->post("tipo1")!=""){
        $this->tab="2";
        }
        if($periodo->id!=""){
                //acl
                $usr_login = Session :: get_data('usr.login');
                $this->acl = array ();
                $acl = new gacl_extra();
                $acos_arr = array (
                    'optativas' =>array(
                        'cupos',
                        'eliminar',
                        'inscritos'
                    )

                );

                $this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);

        $this->periodo=$periodo;

        // busqueda
        $b = new Busqueda($controlador, $accion);
        $b->establecerCondicion('tipo', "materias.tipo='".$b->campo('tipo')."'");
        $b->quitarCondicion('cursos');
        $b->quitarCondicion('periodo_id');
        $b->quitarCondicion('grado1');
        $b->quitarCondicion('letra1');
        $b->quitarCondicion('turno1');
        $b->quitarCondicion('oferta_id1');
        $b->quitarCondicion('tipo1');

            // genera las condiciones
        $c = $b->condicion(array (
            'estado_id',
            'oferta_id'
        ));
        $b->guardar();
        $this->busqueda = $b;


            // busqueda
        $b1 = new Busqueda($controlador, $accion."tab2");
        $b1->establecerCondicion('tipo1', "materias.tipo='".$b1->campo('tipo1')."'");
        $b1->establecerCondicion('grado1', "grupos.grado='".$b1->campo('grado1')."'");
        $b1->establecerCondicion('letra1', "grupos.letra='".$b1->campo('letra1')."'");
        $b1->establecerCondicion('turno1', "grupos.turno='".$b1->campo('turno1')."'");
        $b1->establecerCondicion('oferta_id1', "grupos.oferta_id='".$b1->campo('oferta_id1')."'");

        $b1->quitarCondicion('cursos');
        $b1->quitarCondicion('periodo_id');
        $b1->quitarCondicion('grado');
        $b1->quitarCondicion('letra');
        $b1->quitarCondicion('turno');
        $b1->quitarCondicion('oferta_id');
        $b1->quitarCondicion('tipo');

            // genera las condiciones
        $c1 = $b1->condicion(array (
            'estado_id',
            'oferta_id'
        ));
        $b1->guardar();
        $this->busqueda1 = $b1;

        $this->option="captura";

        $this->ofertas = new Oferta();
        $this->ofertas = $this->ofertas->find();
        $this->tipos=new Materias();
        $this->tipos=$this->tipos->find_all_by_sql("SELECT tipo FROM materias group by tipo");

        if($c==""){
            $this->option_cursos="info";
            $this->info_cursos="Seleccione algun filtro para obtener cursos. ";
        }else{
            $this->option_cursos="cursos";
            $this->c=$c;
            $Cursos = new Cursos();
            // cuenta todos los registros
            $from = "cursos " .
                "INNER JOIN grupos ON cursos.grupos_id = grupos.id " .
                "INNER JOIN materias ON cursos.materias_id  = materias.id " .
                "INNER JOIN profesores ON cursos.profesores_id  = profesores.id ";
            $this->registros = $Cursos->count_by_sql("SELECT COUNT(*) " .
            "FROM " . $from .
            "WHERE grupos.ciclos_id = '" . $periodo->cursosciclos_id. "' " .
             ($c == '' ? '' : 'AND ' . $c));

            // ejecuta la consulta
            $this->cursos = $Cursos->find_all_by_sql("SELECT " .
        "materias.nombre as materia, " .
        "cursos.id, cursos.grupos_id, " .
        "cursos.materias_id," .
        "cursos.profesores_id, " .
        "cursos.estado_id, " .
        "cursos.observaciones, " .
        "cursos.inicio " .
        "FROM " . $from .
        "WHERE grupos.ciclos_id = '" . $periodo->cursosciclos_id . "' " .
         ($c == "" ? "" : "AND " . $c . " ") .
        "ORDER BY grupos.turno, grupos.grado, grupos.letra, materias.nombre ");


        }
        $existentes=array();
        $periodoscursos=new Periodoscursos();
        $periodoscursos=$periodoscursos->find("periodo_id='".$periodo->id."'");

        foreach($periodoscursos as $p){
        $existentes[]=$p->cursos_id;
        }

        $cursos_admin=new Cursos();
        $cursos_admin=$cursos_admin->find_all_by_sql(
                    "SELECT periodoscursos.id AS periodoscursos_id,periodoscursos.cupos,periodoscursos.inscritos,periodoscursos.tipos_id,cursos.*,materias.nombre as materia FROM " .
                    " periodoscursos " .
                    " INNER JOIN cursos ON periodoscursos.cursos_id=cursos.id " .
                    " INNER JOIN grupos ON cursos.grupos_id=grupos.id ".
                    " INNER JOIN materias ON cursos.materias_id  = materias.id " .
                    " WHERE periodoscursos.periodo_id='".$periodo->id."'".
                    ($c1 == "" ? "" : "AND " . $c1 . " ") .
                    "ORDER BY grupos.turno, grupos.grado, grupos.letra, materias.nombre "
        );

        $this->cursos_admin=$cursos_admin;

        $this->registrados=count($cursos_admin);

        $this->existentes=$existentes;
        $this->ciclo=new Ciclos();
        $this->ciclo=$this->ciclo->find($periodo->cursosciclos_id);
        }else{
            $this->option="error";
            $this->error="No existe el periodo especificado.";

        }

        }else{
            $this->option="error";
            $this->error="No existe el periodo especificado.";

        }

            }

    public function index($id=""){
        if($id!=""){

            $periodo=new Periodo();
            $id=intval($id,10);
            $periodo=$periodo->find($id);
            if($periodo->id!=''){
                $fases=0;
                $periodoscursos=new Periodoscursos();
                $periodoconfiguracion=new Periodoconfiguracion();

                $clase[1]="faltante";
                if($periodoscursos->exists("periodo_id='".$periodo->id."'") && $periodoconfiguracion->exists("periodo_id='".$periodo->id."'")){
                    $fases++;
                    $clase[1]="hecho";
                }

                $clase[2]="faltante";
                $periodotrayectoria = new Periodotrayectoria();
                if($periodotrayectoria->exists("periodo_id = '".$periodo->id."'")){
                    $clase[2]="hecho";
                }


                $periodosalumnos=new Periodosalumnos();
                $clase[3]="faltante";
                if($periodosalumnos->exists("periodo_id='".$periodo->id."'")){
                    $fases++;
                    $clase[3]="hecho";
                }

                $bloques=new Bloque();
                $clase[4]="faltante";
                $this->bloques=false;
                if($bloques->exists("periodo_id='".$periodo->id."'")){
                    $fases++;
                    $clase[4]="hecho";
                    $this->bloques=true;
                }


                $etapas=array(
                        1 => array('nombre'=>'cursos','descripcion'=>'Selección de cursos que se ofertaran en el periodo de inscripcion', 'clase'=>$clase[1]),
                        2 => array('nombre'=>'taes','descripcion'=>'Selección y configuracion de las trayectorias especializantes que se ofertaran', 'clase'=>$clase[2]),
                        3 => array('nombre'=>'alumnos','descripcion'=>'Selección de alumnos que se inscribiran en el sistema de optativas', 'clase'=>$clase[3]),
                        4 => array('nombre'=>'bloques','descripcion'=>'Configuracion de los bloques', 'clase'=>$clase[4])
                );

                $this->fases=$fases;
                $alertas=array();
                if($fases==3){
                $periodosalumnos=new Periodosalumnos();
                $alumnoscantidad=$periodosalumnos->count("periodo_id='".$periodo->id."'");

                $periodoscursos=new Periodoscursos();
                $cursos=$periodoscursos->find("periodo_id='".$periodo->id."'");
                $cuposcantidad=0;
                foreach($cursos as $c){
                    $cuposcantidad+=$c->cupos;
                }

                if($alumnoscantidad>$cuposcantidad){
                $alertas[]='No existen cupos suficientes para todos los alumnos';
                }

                }
                $this->alertas=$alertas;


                $this->periodo=$periodo;
                $ciclo=new Ciclos();
                $this->ciclo=$ciclo->find($periodo->ciclos_id);
                $this->etapas=$etapas;
                $this->option="vista";
                // acl
                $usr_login = Session :: get_data('usr.login');
                $this->acl = array ();
                $acl = new gacl_extra();
                $acos_arr = array (
                    'optativas' =>array(
                        'cursos',
                        'configuracion',
                        'alumnos',
                        'bloques',
                        'inscribir',
                        'taes',
                        'taesinfo'
                    ),
                    'bloques' =>array(
                        'index'
                    )

                );

                $this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);


            }else{
            $this->option="error";
            $this->error="El periodo no existe.";
        }
        }else{
            $this->option="error";
            $this->error="El periodo no existe.";
        }
    }

    public function inscritos($id=''){
        if($id!=""){
            $id=intval($id,10);
            $periodocurso=new Periodoscursos();
            $this->periodocurso=$periodocurso=$periodocurso->find($id);
            if($periodocurso->id!=""){
            $this->curso=$periodocurso->curso();
            $this->grupo=$this->curso->grupo();
            $this->materia=$this->curso->materia();

            $periodo=new Periodo();
            $this->periodo=$periodo->find($periodocurso->periodo_id);


            $this->alumnos=$periodocurso->alumnosinscritos();
            $this->registros=count($this->alumnos);
            $this->ciclo=$this->curso->ciclo();
            $this->option="vista";
            }else{
            $this->option="error";
            $this->error="El curso no existe.";
            }
        }else{
            $this->option="error";
            $this->error="El curso no existe.";
        }

    }

    public function inscritosexportar($id = ''){
        $this->set_response("view");
        require('app/reportes/xls.inscritosoptativas.php');
        $reporte = new XLSInscritosoptativas($id);
        $reporte->generar();
     }

     public function inscripciones($id=''){
         $this->error="";
        if($id!=""){
            $id=intval($id,10);
            $periodo=new Periodo();
            $periodo=$periodo->find($id);
            if($periodo->id!=""){
                $this->periodo = $periodo;
                $this->option = "vista";
            }else{
            $this->option="error";
            $this->error="El periodo no existe.";
            }
        }else{
            $this->option="error";
            $this->error="El periodo no existe.";
        }

     }

    public function inscribir($id=''){
        if($id!=""){
            $id=intval($id,10);
            $periodo=new Periodo();
            $periodo=$periodo->find($id);
            if($periodo->id!=""){
                if($periodo->cicloparaalta()){
                $hoy=new DateTime();
                $inicio=new DateTime($periodo->inicio);
                $fin=new DateTime($periodo->fin);
                if($hoy->format('U')>$fin->format('U')){
                    $inscripciones=new Inscripcion();
                    $inscripciones=$inscripciones->find_all_by_sql(
                            "SELECT inscripcion.* FROM periodoscursos
                            INNER JOIN inscripcion ON periodoscursos.id=inscripcion.periodoscursos_id
                            WHERE periodoscursos.periodo_id='".$periodo->id."';"
                            );
                    $inscripciones=count($inscripciones);
                    if($inscripciones>0){
                    $this->periodo=$periodo;
                    $this->option="vista";
                    }else{
                    $this->option="warning";
                    $this->warning="No existe ninguna inscripcion.<br/> No se pueden dar de alta.";
                    }
                }else{
                    $this->option="warning";
                    $this->warning="Aun no finaliza el periodo de selección.<br/> No se pueden dar de alta.";
                }
            }else{
                $this->option="warning";
                $this->warning="No se pueden dar de alta las inscripciones debido a que el ciclo de los cursos no esta activo.";
            }
            }else{
                $this->option="error";
                $this->error="El periodo no existe.";
            }
        }elseif($this->post('id')!=""){
            $id=intval($this->post('id'),10);
            $periodo=new Periodo();
            $periodo=$periodo->find($id);
            if($periodo->id!=""){
                if($periodo->cicloparaalta()){
                $hoy=new DateTime();
                $inicio=new DateTime($periodo->inicio);
                $fin=new DateTime($periodo->fin);
                if($hoy->format('U')>$fin->format('U')){
                    $registros=new Inscripcion();
                    $registros=$registros->find_all_by_sql(
                            "SELECT inscripcion.* FROM periodoscursos
                            INNER JOIN inscripcion ON periodoscursos.id=inscripcion.periodoscursos_id
                            WHERE periodoscursos.periodo_id='".$periodo->id."';"
                            );
                    $registros=count($registros);
                    if($registros>0){

                        try{
                            mysql_query("BEGIN") or die("inscr_1");
                                $periodocursos=new Periodoscursos();
                                $periodocursos=$periodocursos->find("periodo_id='".$periodo->id."'");
                                $datos=array();
                                $invalidos=array();
                                $periodoCiclo=new Ciclos();
                                $periodoCiclo=$periodoCiclo->find($periodo->ciclos_id);

                                $sig=new Ciclos();
                                $sig=$sig->find_first("numero='".$periodoCiclo->siguiente()."'");


                                foreach($periodocursos as $periodocurso){
                                $inscripciones=new Inscripcion();
                                $inscripciones=$inscripciones->find("periodoscursos_id='".$periodocurso->id."'");
                                $datos[$periodocurso->cursos_id]=array();
                                $invalidos[$periodocurso->cursos_id]=array();
                                    foreach($inscripciones as $inscripcion){
                                        $periodoalumno=new Periodosalumnos();
                                        $periodoalumno=$periodoalumno->find_first($inscripcion->periodosalumnos_id);
                                        $alumno=new Alumnos();
                                        $alumno=$alumno->find($periodoalumno->alumnos_id);

                                        $grupo=$alumno->obtenerGrupoPorCiclo($sig->id);

                                        $curso=new Cursos();
                                        $curso=$curso->find($periodocurso->cursos_id);

                                        $grupocurso=$curso->grupo();
                                        if($grupo->grado==$grupocurso->grado){
                                        $i=new Alumnoscursos();
                                        if(!$i->exists("alumnos_id='".$alumno->id."' AND cursos_id='".$curso->id."'")){
                                        //$i=new Alumnoscursos();
                                        //$i->alta($alumnos->id,$periodocurso->cursos_id);

                                        $alumnosCurso=new Alumnoscursos();
                                        $alumnosCurso->alta($alumno->id,$curso->id);
                                        $datos[$periodocurso->cursos_id][]=$alumno->id;

                                        }else{
                                            $invalidos[$periodocurso->cursos_id][]=$alumno->id;
                                        }
                                        }else{
                                            $invalidos[$periodocurso->cursos_id][]=$alumno->id;
                                        }
                                    }

                                }

                                $this->datos=$datos;
                                $this->invalidos=$invalidos;
                                $this->periodo=$periodo;
                                $this->option="exito";
                                mysql_query("ROLLBACK") or die("inscr_11");//mysql_query("COMMIT") or die("inscr_10");
                        }catch(Exception $e){
                                mysql_query("ROLLBACK") or die("inscr_11");
                                $this->option="error";
                                $this->error="Ocurrio un error en la BD.";
                        }
                }else{
                    $this->option="warning";
                    $this->warning="No existe ninguna inscripcion.<br/> No se pueden dar de alta.";
                }
            }else{
                    $this->option="warning";
                    $this->warning="Aun no finaliza el periodo de selección.<br/> No se pueden dar de alta.";
            }
            }else{
                $this->option="warning";
                $this->warning="No se pueden dar de alta las inscripciones debido a que el ciclo de los cursos no esta activo.";
            }
            }else{
                $this->option="error";
                $this->error="El periodo no existe.";
            }
        }else{
            $this->option="error";
            $this->error="El periodo no existe.";
        }

    }

        public function inscribirtrayectoria($id=''){
        if($id!=""){
            $id=intval($id,10);
            $periodo=new Periodo();
            $periodo=$periodo->find($id);
            if($periodo->id!=""){
                $hoy=new DateTime();
                $inicio=new DateTime($periodo->inicio);
                $fin=new DateTime($periodo->fin);
                if($hoy->format('U')>$fin->format('U')){
                    $inscripciones=new Periodotrayectoriaalumno();
                    $inscripciones=$inscripciones->find_all_by_sql(
                            "SELECT periodotrayectoriaalumno.* FROM periodotrayectoriaalumno
                            INNER JOIN periodotrayectoria ON periodotrayectoriaalumno.periodotrayectoria_id=periodotrayectoria.id
                            WHERE periodotrayectoria.periodo_id='".$periodo->id."';"
                            );
                    $inscripciones=count($inscripciones);
                    if($inscripciones>0){
                    $this->periodo=$periodo;
                    $this->option="vista";
                    }else{
                    $this->option="warning";
                    $this->warning="No existe ninguna inscripcion.<br/> No se pueden dar de alta.";
                    }
                }else{
                    $this->option="warning";
                    $this->warning="Aun no finaliza el periodo de selección.<br/> No se pueden dar de alta.";
                }

            }else{
                $this->option="error";
                $this->error="El periodo no existe.";
            }
        }elseif($this->post('id')!=""){
            $id=intval($this->post('id'),10);
            $periodo=new Periodo();
            $periodo=$periodo->find($id);
            if($periodo->id!=""){
                $hoy=new DateTime();
                $inicio=new DateTime($periodo->inicio);
                $fin=new DateTime($periodo->fin);
                if($hoy->format('U')>$fin->format('U')){

                    $registros=new Periodotrayectoriaalumno();
                    $registros=$registros->find_all_by_sql(
                            "SELECT periodotrayectoriaalumno.* FROM periodotrayectoriaalumno
                            INNER JOIN periodotrayectoria ON periodotrayectoriaalumno.periodotrayectoria_id=periodotrayectoria.id
                            WHERE periodotrayectoria.periodo_id='".$periodo->id."' ORDER BY periodotrayectoria.trayectoriaespecializante_id,periodotrayectoria.turno;"
                            );
                    $registros=count($registros);
                    if($registros>0){

                        try{
                            mysql_query("BEGIN") or die("inscr_1");
                                $periodotrayectorias=new Periodotrayectoria();
                                $periodotrayectorias=$periodotrayectorias->find("periodo_id='".$periodo->id."'");
                                $datos=array();
                                $invalidos=array();
                                $periodoCiclo=new Ciclos();
                                $periodoCiclo=$periodoCiclo->find($periodo->ciclos_id);

                                $sig=new Ciclos();
                                $sig=$sig->find_first("numero='".$periodoCiclo->siguiente()."'");


                                foreach($periodotrayectorias as $periodotrayectoria){
                                $inscripciones=new Periodotrayectoriaalumno();
                                $inscripciones=$inscripciones->find("periodotrayectoria_id='".$periodotrayectoria->id."'");
                                $datos[$periodotrayectoria->trayectoriaespecializante_id][$periodotrayectoria->turno]=array();
                                $invalidos[$periodotrayectoria->trayectoriaespecializante_id][$periodotrayectoria->turno]=array();
                                    foreach($inscripciones as $inscripcion){
                                        $periodoalumno=new Periodosalumnos();
                                        $periodoalumno=$periodoalumno->find_first($inscripcion->periodosalumnos_id);
                                        $alumno=new Alumnos();
                                        $alumno=$alumno->find($periodoalumno->alumnos_id);

                                        $grupo=$alumno->obtenerGrupoPorCiclo($periodo->ciclos_id);

                                        if($grupo->turno==$periodotrayectoria->turno){
                                        $trayectoria=new Trayectoriaespecializante();
                                        $trayectoria=$trayectoria->find($periodotrayectoria->trayectoriaespecializante_id);

                                        $alumnotrayectoria = new Alumnotrayectoria();

                                        if(!$alumnotrayectoria->exists("alumnos_id='".$alumno->id."'")){
                                        $alumnotrayectoria = new Alumnotrayectoria();
                                        $alumnotrayectoria->alumnos_id = $alumno->id;
                                        $alumnotrayectoria->trayectoriaespecializante_id = $trayectoria->id;
                                        $alumnotrayectoria->save();
                                        $datos[$periodotrayectoria->trayectoriaespecializante_id][$periodotrayectoria->turno][]=$alumno->id;

                                        }else{
                                            $invalidos[$periodotrayectoria->trayectoriaespecializante_id][$periodotrayectoria->turno][]=$alumno->id;
                                        }
                                        }else{
                                            $invalidos[$periodotrayectoria->trayectoriaespecializante_id][$periodotrayectoria->turno][]=$alumno->id;
                                        }
                                    }

                                }

                                $this->datos=$datos;
                                $this->invalidos=$invalidos;
                                $this->periodo=$periodo;
                                $this->option="exito";
                                mysql_query("ROLLBACK") or die("inscr_11");//mysql_query("COMMIT") or die("inscr_10");
                        }catch(Exception $e){
                                mysql_query("ROLLBACK") or die("inscr_11");
                                $this->option="error";
                                $this->error="Ocurrio un error en la BD.";
                        }
                }else{
                    $this->option="warning";
                    $this->warning="No existe ninguna inscripcion.<br/> No se pueden dar de alta.";
                }
            }else{
                    $this->option="warning";
                    $this->warning="Aun no finaliza el periodo de selección.<br/> No se pueden dar de alta.";
            }
            }else{
                $this->option="error";
                $this->error="El periodo no existe.";
            }
        }else{
            $this->option="error";
            $this->error="El periodo no existe.";
        }

    }

    public function taes($id = ''){
        $this->option = 'vista';
        if($id!=""){
            $id=intval($id,10);
            $periodo=new Periodo();
            $periodo=$periodo->find($id);
            if($periodo->id!=""){
                $this->periodo = $periodo;
                $trayectorias = new Trayectoriaespecializante();
                $this->trayectorias = $trayectorias->find();

                $periodotrayectoria = new Periodotrayectoria();
                $periodotrayectorias = $periodotrayectoria->find("periodo_id = '".$periodo->id."'");

                $datos = array();
                foreach($periodotrayectorias as $pt){
                    $datos[$pt->trayectoriaespecializante_id][$pt->turno] = $pt;
                }

                $periodosalumnos=new Periodosalumnos();
                $this->totalmatutino=count($periodosalumnos->find_all_by_sql(
                    "SELECT periodosalumnos.* FROM
                    periodosalumnos
                    INNER JOIN alumnos ON periodosalumnos.alumnos_id = alumnos.id
                    INNER JOIN alumnosgrupo ON alumnos.id = alumnosgrupo.alumnos_id
                    INNER JOIN grupos ON alumnosgrupo.grupos_id = grupos.id
                    WHERE periodosalumnos.periodo_id='".$periodo->id."' AND grupos.ciclos_id='".$periodo->ciclos_id."' AND grupos.turno='M'"
                                        ));

                $this->totalvespertino=count($periodosalumnos->find_all_by_sql(
                    "SELECT periodosalumnos.* FROM
                    periodosalumnos
                    INNER JOIN alumnos ON periodosalumnos.alumnos_id = alumnos.id
                    INNER JOIN alumnosgrupo ON alumnos.id = alumnosgrupo.alumnos_id
                    INNER JOIN grupos ON alumnosgrupo.grupos_id = grupos.id
                    WHERE periodosalumnos.periodo_id='".$periodo->id."' AND grupos.ciclos_id='".$periodo->ciclos_id."' AND grupos.turno='V'"
                                        ));

                $this->datos = $datos;
            }else{
                $this->option="error";
                $this->error="No existe el periodo especificado.";

            }

        }elseif($this->post("periodo_id")){
            $id=intval($this->post("periodo_id"),10);
            $periodo=new Periodo();
            $periodo=$periodo->find($id);
            if($periodo->id!=""){
                $this->periodo = $periodo;

                $incluir = $this->post("incluir");
                if(is_array($incluir) && count($incluir)>0){
                try{
                $this->option="exito";

                $cupos = $this->post("cupos");
                mysql_query("BEGIN") or die("TRAYECTORIA_1");


                $periodotrayectoria = new Periodotrayectoria();
                $periodotrayectorias = $periodotrayectoria->find("periodo_id = '".$periodo->id."'");

                foreach($periodotrayectorias as $pt){
                    if(!in_array($pt->trayectoriaespecializante_id,$incluir[$pt->turno])){
                        $pt->delete();
                    }
                }

                foreach($incluir as $t => $turno){
                    foreach($turno as $k =>$valor){
                    $c = $cupos[$t][$k];
                    $periodotrayectoria = new Periodotrayectoria();
                    $periodotrayectoria = $periodotrayectoria->find_first("trayectoriaespecializante_id = '".$valor."' AND periodo_id = '".$periodo->id."' AND turno='".$t."'");
                    if($periodotrayectoria->id==""){
                        $periodotrayectoria = new Periodotrayectoria();
                        $periodotrayectoria->turno = $t;
                        $periodotrayectoria->trayectoriaespecializante_id = $valor;
                        $periodotrayectoria->periodo_id = $periodo->id;
                        $periodotrayectoria->inscritos = 0;
                        if($c==''){
                            $c=0;
                        }
                    }else{
                        if($c=='')
                        $c = $periodotrayectoria->cupos;

                    }

                        $periodotrayectoria->cupos = $c;
                        if(!$periodotrayectoria->save()){
                            $this->option="error";
                            $this->error="Intentelo de nuevo.";
                            mysql_query("ROLLBACK") or die("TRAYECTORIA");
                            break;
                        }
                    }

                    }

                mysql_query("COMMIT") or die("TRAYECTORIA_1");
                }catch(Exception $e){
                $this->option="error";
                $this->error="Intentelo de nuevo.";
                mysql_query("ROLLBACK") or die("TRAYECTORIA");
                }
                }else{
                $this->option="error";
                $this->error="No se incluyo ninguna trayectoria.";

                }
                }else{
                $this->option="error";
                $this->error="No existe el periodo especificado.";

            }


            }else{
                $this->option="error";
                $this->error="No existe el periodo especificado.";
            }
    }

    public function taesinfo($id = ''){
        $this->option = 'vista';
        if($id!=""){
            $id=intval($id,10);
            $periodo=new Periodo();
            $periodo=$periodo->find($id);
            if($periodo->id!=""){
                $this->periodo = $periodo;
                $trayectorias = new Periodotrayectoria();
                $this->trayectorias = $trayectorias->trayectoriasporperiodo($id,"ORDER BY trayectoriaespecializante_id,turno");

            }else{
                $this->option="error";
                $this->error="No existe el periodo especificado.";

            }

        }else{
                $this->option="error";
                $this->error="No existe el periodo especificado.";
            }
    }

    public function trayectoria($id = ''){
        $this->option = 'vista';
        if($id!=""){
            $id=intval($id,10);
            $periodotrayectoria=new Periodotrayectoria();
            $periodotrayectoria=$periodotrayectoria->find($id);
            $this->periodotrayectoria = $periodotrayectoria;
            if($periodotrayectoria->id!=""){
                $this->periodotrayectoria = $periodotrayectoria;
                $trayectoria = new Trayectoriaespecializante();
                $this->trayectoria = $trayectoria->find($periodotrayectoria->trayectoriaespecializante_id);

                $alumnos = new Alumnos();
                $alumnos = $alumnos->find_all_by_sql(
                "SELECT alumnos.*
                            FROM
                            alumnos
                            INNER JOIN periodosalumnos ON alumnos.id=periodosalumnos.alumnos_id
                            INNER JOIN periodotrayectoriaalumno ON periodosalumnos.id = periodotrayectoriaalumno.periodosalumnos_id
                            WHERE periodotrayectoriaalumno.periodotrayectoria_id=".$periodotrayectoria->id
                );
                $this->alumnos = $alumnos;
                $this->registros = count($this->alumnos);

            }else{
                $this->option="error";
                $this->error="No existe el periodo especificado.";

            }

        }else{
                $this->option="error";
                $this->error="No existe el periodo especificado.";
            }
    }

    public function trayectoriasexportar($id = ''){
        $this->set_response("view");
        require('app/reportes/xls.inscritostrayectorias.php');
        $reporte = new XLSInscritostrayectorias($id);
        $reporte->generar();
     }


     public function tipos(){
        $this->set_response('view');
        $id = $this->post('id');
        $tipos  = $this->post('tipos');
        $periodoscursos=new Periodoscursos();
        $periodoscursos=$periodoscursos->find_first($id);
        if($periodoscursos->id!=""){
            $periodoscursos->tipos_id = $tipos;
            if($periodoscursos->save()){
                $this->resp="1";        
            }else{
                $this->resp="Ocurrio un error al guardar.";
            }
        }else{
            $this->resp="El curso no se encuentra en el periodo.";
        }
    }
     
 }
?>
