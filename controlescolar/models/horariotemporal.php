<?php
// Hekademos, Creado el 11/10/2008
/**
 * Horarios
 *
 * @package    Hekademos
 * @author     mimeks <mimex@fipasoft.com.mx>
 * @copyright  2008 FiPa Software (contacto at fipasoft.com.mx)
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL)
 *
 */
class Horariotemporal extends ActiveRecord{
    public function aula(){
        $aula = new Aulas();
        $aula = $aula->find($this->aulas_id);
        return $aula;
    }
    public function aulaClave(){
        $aula = new Aulas();
        $aula = $aula->find($this->aulas_id);
        return $aula->clave;
    }
    public function aulaNombre(){
        $aula = new Aulas();
        $aula = $aula->find($this->aulas_id);
        return $aula->nombre;
    }

    public function valida($curso_id,$grupos_id,$materias_id,$dia,$entrada,$salida,$inicio){
        $conflictos = array();
        $disponible = false;

        $grupo = new Grupos();
        $grupo = $grupo->find($grupos_id);

        $db = db::raw_connect();
        $registros = null;

        if($grupo->id != ''){
            $materia=new Materias();
            $materia=$materia->find($materias_id);
            $materiaoferta=$materia->Oferta('id');
            $prf=new Profesores();
            if($prf->esStaff($profesores_id))
            $profesores_id='-1';  //para evitar colisiones por profesor staff

            if($materiaoferta==1){
                $ciclos_id = Session :: get_data('ciclo.id');
                // restriccion de grupo y aula
                $registros = $db->query(
                            "SELECT
                                horariotemporal.inicio,
                                horariotemporal.fin,
                                materias.nombre AS materia,
                                grupos.grado,
                                grupos.letra,
                                grupos.turno,
                                profesores.nombre AS prof_n,
                                profesores.ap AS prof_ap,
                                profesores.am AS prof_am,
                                dias.nombre AS dia
                            FROM
                                cursos
                                Inner Join horariotemporal ON horariotemporal.cursos_id = cursos.id
                                Inner Join materias ON cursos.materias_id = materias.id
                                Inner Join grupos ON cursos.grupos_id = grupos.id
                                Inner Join profesores ON cursos.profesores_id = profesores.id
                                Inner Join dias ON horariotemporal.dias_id = dias.id
                            WHERE" .
                            "    (" .
                            "    grupos.ciclos_id = '" . $ciclos_id . "' AND
                                grupos.id = '".$grupo->id."' AND
                                horariotemporal.dias_id = '" . $dia . "' AND " .
                                "((horariotemporal.inicio >  '" . $entrada . "' AND  horariotemporal.inicio < '" . $salida . "') OR " .
                                "(horariotemporal.fin >  '" . $entrada . "' AND  horariotemporal.fin < '" . $salida . "') OR " .
                                "(horariotemporal.inicio <=  '" . $entrada . "' AND  horariotemporal.fin >= '" . $salida . "')) " .
                ($curso_id != "" ? "AND cursos.id != '" . $curso_id . "'" : "").
                                ")"
                                );
                                if($db->num_rows($registros) > 0){
                                    while($reg = $db->fetch_array($registros, db :: DB_ASSOC)){
                                        $conflictos[] = $reg['grado'] . '&deg;' . $reg['letra'] . ' ' . $reg['turno'] . ', ' .
                                        $reg['materia'] . ', ' .
                                        $reg['prof_n'] . ' ' . $reg['prof_ap'] . ' ' .$reg['prof_am'] . ', ' .
                                        $reg['dia'] . ' ' . $reg['inicio'] . '-' . $reg['fin'] . ', ' .
                                          'Aula ' . $reg['aula'];
                                    }
                                }else{
                                    $disponible = true;
                                }
            }elseif($materiaoferta==2){
                $ciclos_id = Session :: get_data('ciclo.id');

                // restriccion de grupo y aula
                $registros = $db->query(
                            "SELECT
                                cursos.id as cursos_id,
                                cursos.inicio as curso_inicio,
                                horariotemporal.inicio,
                                horariotemporal.fin,
                                materias.nombre AS materia,
                                grupos.grado,
                                grupos.letra,
                                grupos.turno,
                                grupos.oferta_id,
                                profesores.nombre AS prof_n,
                                profesores.ap AS prof_ap,
                                profesores.am AS prof_am,
                                dias.nombre AS dia
                            FROM
                                cursos
                                Inner Join horariotemporal ON horariotemporal.cursos_id = cursos.id
                                Inner Join materias ON cursos.materias_id = materias.id
                                Inner Join grupos ON cursos.grupos_id = grupos.id
                                Inner Join profesores ON cursos.profesores_id = profesores.id
                                Inner Join dias ON horariotemporal.dias_id = dias.id
                            WHERE" .
                            "    (" .
                            "    grupos.ciclos_id = '" . $ciclos_id . "' AND
                                grupos.id = '".$grupo->id."' AND
                                horariotemporal.dias_id = '" . $dia . "' AND " .
                                "((horariotemporal.inicio >  '" . $entrada . "' AND  horariotemporal.inicio < '" . $salida . "') OR " .
                                "(horariotemporal.fin >  '" . $entrada . "' AND  horariotemporal.fin < '" . $salida . "') OR " .
                                "(horariotemporal.inicio <=  '" . $entrada . "' AND  horariotemporal.fin >= '" . $salida . "')) " .
                ($curso_id != "" ? "AND cursos.id != '" . $curso_id . "'" : "").
                                ")"
                                );

                                if($db->num_rows($registros) > 0){
                                    while($reg = $db->fetch_array($registros, db :: DB_ASSOC)){
                                        if($reg['oferta_id']==2){
                                            $cursos=new Cursos();
                                            $cursos=$cursos->find($reg['cursos_id']);
                                            $materia2 = $cursos->materia();
                                            $fin=$cursos->fechaFin($cursos->inicio);
                                            if($materia->duracion < 19 && $materia2->duracion < 19){
                                                $fc=$fin->format('U');
                                                $ic=  new DateTime( $cursos->inicio );
                                                $ic=$ic->format('U');
                                                $ini  =  new DateTime( Utils::convierteFechaMySql($inicio) );
                                                $ini=$ini->format('U');
                                                //$fn=$cursos->fechaFin2(Utils::convierteFechaMySql($inicio),$materia);
                                                //$fn=$fn->format('U');

                                                if(
                                                !(
                                                ($ic<$ini && $fc<$ini)
                                                ||
                                                ($ini<$ic && $ini<$fc)
                                                )
                                                ){

                                                    $fin=Utils::fecha_espanol(date('Y-m-d',$fin->format('U')));

                                                    $conflictos[] = 'Inicio: '.Utils::fecha_espanol($cursos->inicio).',Duracion: '.$cursos->materia()->duracion.' semanas, Fin: '.$fin.', '.$reg['grado'] . '&deg;' . $reg['letra'] . ' ' . $reg['turno'] . ', ' .
                                              $reg['materia'] . ', ' .
                                              $reg['prof_n'] . ' ' . $reg['prof_ap'] . ' ' .$reg['prof_am'] . ', ' .
                                              $reg['dia'] . ' ' . $reg['inicio'] . '-' . $reg['fin'] . ', ' .
                                              'Aula ' . $reg['aula'];
                                                }

                                            }else{
                                                $fin=Utils::fecha_espanol(date('Y-m-d',$fin->format('U')));

                                                $conflictos[] = 'Inicio: '.Utils::fecha_espanol($cursos->inicio).',Duracion: '.$cursos->materia()->duracion.' semanas, Fin: '.$fin.', '.$reg['grado'] . '&deg;' . $reg['letra'] . ' ' . $reg['turno'] . ', ' .
                                          $reg['materia'] . ', ' .
                                          $reg['prof_n'] . ' ' . $reg['prof_ap'] . ' ' .$reg['prof_am'] . ', ' .
                                          $reg['dia'] . ' ' . $reg['inicio'] . '-' . $reg['fin'] . ', ' .
                                          'Aula ' . $reg['aula'];
                                            }
                                        }else{
                                            $conflictos[] = $reg['grado'] . '&deg;' . $reg['letra'] . ' ' . $reg['turno'] . ', ' .
                                            $reg['materia'] . ', ' .
                                            $reg['prof_n'] . ' ' . $reg['prof_ap'] . ' ' .$reg['prof_am'] . ', ' .
                                            $reg['dia'] . ' ' . $reg['inicio'] . '-' . $reg['fin'] . ', ' .
                                          'Aula ' . $reg['aula'];
                                        }
                                    }
                                    if(count($conflictos)==0)
                                    $disponible = true;
                                }else{
                                    $disponible = true;
                                }

            }
        }

        return $disponible;

    }

    public function primero($grupo,$curso,$materia){
        $myLog = new Logger('GeneraHorario.log');
        $asig = 0;
        $academiamateria = new Academiamateria();
        $academiamateria = $academiamateria->find_first("materias_id='".$materia->id."'");
        //$myLog->log("academiamateria ".$academiamateria->id , Logger::ERROR);
        $academia = new Academia();
        $academia = $academia->find($academiamateria->academia_id);
        //$myLog->log("academia ".$academia->id , Logger::ERROR);
        $departamento = new Departamento();
        $departamento = $departamento->find($academia->departamento_id);
        //$myLog->log("departamento ".$departamento->id , Logger::ERROR);
        //var_dump($departamento);exit;
        $hora = "07:00:00";
        if($grupo->turno!="M"){
            $hora = "13:00:00";
        }

        $materiacriterio = new Materiacriterio();
        $materiacriterio = $materiacriterio->find_first("materias_id='".$materia->id."'");
        $hcriterio = new Hcriterio();
        $bloqueh = $hcriterio->find_first("turno='".$grupo->turno."' AND numero='".$materiacriterio->opcionA."' ");

        $hcriterio2 = new Hcriterio();
        $bloqueb = $hcriterio2->find_first("turno='".$grupo->turno."' AND numero='".$materiacriterio->opcionB."' ");

        $myLog->log($curso->materia.", departamento: ".$departamento->nombre.", horarionombre: ".$bloqueh->nombre." ,horario: ".$bloqueh->inicio."-".$bloqueh->fin." ".$curso->inicio , Logger::ERROR);
        $dia_id = 1;
        $horassemana = $materia->horasporsemana();
        $valido = false;
        if($horassemana == 4){
            if($bloqueb->id!=""){
                $bloqueh = $bloqueb;
            }
            do{
                $ht = new Horariotemporal();
                $ht->cursos_id = $curso->id;
                $ht->dias_id = $dia_id;
                $ht->inicio = $bloqueh->inicio;
                $ht->fin = $bloqueh->fin;
                $hv = new Horariotemporal();
                if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                    $dia_id2 = $dia_id + 2;
                    if($dia_id2>5){
                        $valido = true;
                        $myLog->log("4 No se pudo ".$curso->materia, Logger::ERROR);
                    }else{
                        $ht1 = new Horariotemporal();
                        $ht1->cursos_id = $curso->id;
                        $ht1->dias_id = $dia_id2;
                        $ht1->inicio = $bloqueh->inicio;
                        $ht1->fin = $bloqueh->fin;

                        if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                            $ht->save();
                            $ht1->save();
                            $myLog->log("Horario guardado: ".$ht->dias_id.", ".$ht->inicio.", ".$ht->fin." ".$curso->inicio , Logger::ERROR);
                            $myLog->log("Horario guardado: ".$ht1->dias_id.", ".$ht1->inicio.", ".$ht1->fin." ".$curso->inicio , Logger::ERROR);
                            $asig++;
                            $valido = true;
                        }else{
                            $dia_id += 1;
                            if($dia_id>5){
                                $valido = true;
                                $myLog->log("4 No se pudo ".$curso->materia, Logger::ERROR);
                            }
                        }
                    }
                }else{
                    $dia_id += 1;
                    if($dia_id>5){
                        $valido = true;
                        $myLog->log("4 No se pudo ".$curso->materia, Logger::ERROR);
                    }
                }
            }while(!$valido);
        }elseif($horassemana == 3){
            do{
                if($materia->id == "114"){
                    if($bloqueb->id!=""){
                        $bloqueh = $bloqueb;
                    }
                    $dia_id = 4;
                    $ht = new Horariotemporal();
                    $ht->cursos_id = $curso->id;
                    $ht->dias_id = $dia_id;
                    $ht->inicio = $bloqueh->inicio;
                    $ht->fin = $bloqueh->fin;
                    $hv = new Horariotemporal();
                    //var_dump($ht);exit;
                    if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                        $dia_id2 = 5;
                        //var_dump($dia_id2);exit;
                        if($dia_id2>5){
                            $valido = true;
                            $myLog->log("3 No se pudo ".$curso->materia, Logger::ERROR);
                        }else{
                            $ht1 = new Horariotemporal();
                            $ht1->cursos_id = $curso->id;
                            $ht1->dias_id = $dia_id2;
                            $ht1->inicio = $bloqueh->inicio;
                            $ht1->fin = Utils::minutesToHours(Utils::timeToMinutes($bloqueh->fin) - 60);
                            $hv = new Horariotemporal();
                            if(!$hv->valida($ht1->cursos_id,$grupo->id,$materia->id,$ht1->dias_id,$ht1->inicio,$ht1->fin,Utils::convierteFecha($curso->inicio))){
                                $ht1->inicio = Utils::minutesToHours(Utils::timeToMinutes($bloqueh->inicio) + 60);
                                $ht1->fin = $bloqueh->fin;

                            }

                            $hv = new Horariotemporal();
                            if($hv->valida($ht1->cursos_id,$grupo->id,$materia->id,$ht1->dias_id,$ht1->inicio,$ht1->fin,Utils::convierteFecha($curso->inicio))){
                                $ht->save();
                                $ht1->save();
                                $myLog->log("Horario guardado: ".$ht->dias_id.", ".$ht->inicio.", ".$ht->fin." ".$curso->inicio , Logger::ERROR);
                                $myLog->log("Horario guardado: ".$ht1->dias_id.", ".$ht1->inicio.", ".$ht1->fin." ".$curso->inicio , Logger::ERROR);
                                $asig++;
                                $valido = true;
                            }else{

                                $dia_id += 1;
                                if($dia_id>5){
                                    $valido = true;
                                    $myLog->log("3 No se pudo ".$curso->materia, Logger::ERROR);
                                }
                            }
                        }
                    }else{
                        $dia_id += 1;
                        if($dia_id>5){
                            $valido = true;
                            $myLog->log("3 No se pudo ".$curso->materia, Logger::ERROR);
                        }
                    }
                }else{
                    $ht = new Horariotemporal();
                    $ht->cursos_id = $curso->id;
                    $ht->dias_id = $dia_id;
                    $ht->inicio = $bloqueh->inicio;
                    $ht->fin = $bloqueh->fin;
                    $hv = new Horariotemporal();
                    if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                        $dia_id2 = 5;
                        if($dia_id2>5){
                            $valido = true;
                            $myLog->log("3 No se pudo ".$curso->materia, Logger::ERROR);
                        }else{
                            $ht1 = new Horariotemporal();
                            $ht1->cursos_id = $curso->id;
                            $ht1->dias_id = $dia_id2;
                            $ht1->inicio = $bloqueh->inicio;
                            $ht1->fin = Utils::minutesToHours(Utils::timeToMinutes($bloqueh->fin) - 60);

                            if(!$hv->valida($ht1->cursos_id,$grupo->id,$materia->id,$ht1->dias_id,$ht1->inicio,$ht1->fin,Utils::convierteFecha($curso->inicio))){

                                $ht1->inicio = Utils::minutesToHours(Utils::timeToMinutes($bloqueh->inicio) + 60);
                                $ht1->fin = $bloqueh->fin;

                            }
                            if($hv->valida($ht1->cursos_id,$grupo->id,$materia->id,$ht1->dias_id,$ht1->inicio,$ht1->fin,Utils::convierteFecha($curso->inicio))){
                                $ht->save();
                                $ht1->save();
                                $myLog->log("Horario guardado: ".$ht->dias_id.", ".$ht->inicio.", ".$ht->fin." ".$curso->inicio , Logger::ERROR);
                                $myLog->log("Horario guardado: ".$ht1->dias_id.", ".$ht1->inicio.", ".$ht1->fin." ".$curso->inicio , Logger::ERROR);
                                $asig++;
                                $valido = true;
                            }else{
                                $dia_id += 1;
                                if($dia_id>5){
                                    $valido = true;
                                    $myLog->log("3 No se pudo ".$curso->materia, Logger::ERROR);
                                }
                            }
                        }
                    }else{
                        $dia_id += 1;
                        if($dia_id>5){
                            $valido = true;
                            $myLog->log("3 No se pudo ".$curso->materia, Logger::ERROR);
                        }
                    }
                }
            }while(!$valido);
        }elseif($horassemana == 5){
            $cuenta = 0;
            $guardar = array();

            if($bloqueb->id!=""){
                $bloqueh = $bloqueb;
            }

            if($materia->id=="113"){
                $dia_id = 2;
            }
            do{
                $ht = new Horariotemporal();
                $ht->cursos_id = $curso->id;
                if($dia_id>5 || $cuenta==2){
                    $dia_id=5;
                }

                $ht->dias_id = $dia_id;
                $ht->inicio = $bloqueh->inicio;
                if($cuenta==2){
                    $ht->fin = Utils::minutesToHours(Utils::timeToMinutes($bloqueh->fin) - 60);
                }else{
                    $ht->fin = $bloqueh->fin;
                }


                $hv = new Horariotemporal();
                    
                if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                    $guardar[] = $ht;
                    $cuenta++;
                    if($cuenta==3){
                        $valido = true;
                        foreach($guardar as $g){

                            $g->save();
                            $myLog->log("Horario guardado: ".$g->dias_id.", ".$g->inicio.", ".$g->fin." ".$curso->inicio , Logger::ERROR);
                        }
                        $asig++;
                    }elseif($cuenta==1){
                        $dia_id+=2;
                    }

                }else{
                    if($cuenta==0 && $dia_id==1){
                        $dia_id+=1;
                    }else{
                        $valido = true;
                        $myLog->log("5 No se pudo ".$curso->materia, Logger::ERROR);
                    }
                }

                if($dia_id>5 && !$valido){

                    $valido = true;
                    $myLog->log("5 No se pudo ".$curso->materia, Logger::ERROR);
                }

            }while(!$valido);

        }elseif($horassemana == 8){
            $cuenta = 0;
            $guardar = array();
            do{
                $ht = new Horariotemporal();
                $ht->cursos_id = $curso->id;
                $ht->dias_id = $dia_id;
                $ht->inicio = $bloqueh->inicio;
                $ht->fin = $bloqueh->fin;
                $hv = new Horariotemporal();
                if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                    $guardar[] = $ht;
                    $cuenta++;
                    $dia_id+=1;
                    if($cuenta==4){
                        $valido = true;
                        foreach($guardar as $g){

                            $g->save();
                            $myLog->log("Horario guardado: ".$g->dias_id.", ".$g->inicio.", ".$g->fin." ".$curso->inicio , Logger::ERROR);
                        }
                        $asig++;
                    }

                }else{
                    if($cuenta==0){
                        $dia_id+=1;
                    }else{
                        $valido = true;
                        $myLog->log("8 No se pudo ".$curso->materia, Logger::ERROR);
                    }
                }
                if($dia_id>5){
                    $valido = true;
                    $myLog->log("8 No se pudo ".$curso->materia, Logger::ERROR);
                }
            }while(!$valido);
        }elseif($horassemana == 2){
            do{
                $ht = new Horariotemporal();
                $ht->cursos_id = $curso->id;
                $ht->dias_id = $dia_id;
                $ht->inicio = $bloqueh->inicio;
                $ht->fin = $bloqueh->fin;
                $hv = new Horariotemporal();
                if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                    $ht->save();
                    $myLog->log("Horario guardado: ".$ht->dias_id.", ".$ht->inicio.", ".$ht->fin." ".$curso->inicio , Logger::ERROR);
                    $asig++;
                    $valido = true;
                }else{
                    $dia_id=5;
                    if($dia_id>5){
                        $valido = true;
                        $myLog->log("2 No se pudo ".$curso->materia, Logger::ERROR);
                    }
                }
            }while(!$valido);
        }else{
            $myLog->log("Horas nuevas ".$horassemana, Logger::ERROR);
        }
        $myLog->close();
        return $asig;
    }

    public function segundo($grupo,$curso,$materia){
        $myLog = new Logger('GeneraHorario.log');
        $asig = 0;
        $academiamateria = new Academiamateria();
        $academiamateria = $academiamateria->find_first("materias_id='".$materia->id."'");
        //$myLog->log("academiamateria ".$academiamateria->id , Logger::ERROR);
        $academia = new Academia();
        $academia = $academia->find($academiamateria->academia_id);
        //$myLog->log("academia ".$academia->id , Logger::ERROR);
        $departamento = new Departamento();
        $departamento = $departamento->find($academia->departamento_id);
        //$myLog->log("departamento ".$departamento->id , Logger::ERROR);
        //var_dump($departamento);exit;
        $hora = "07:00:00";
        if($grupo->turno!="M"){
            $hora = "13:00:00";
        }

        $materiacriterio = new Materiacriterio();
        $materiacriterio = $materiacriterio->find_first("materias_id='".$materia->id."'");
        $hcriterio = new Hcriterio();
        $bloqueh = $hcriterio->find_first("turno='".$grupo->turno."' AND numero='".$materiacriterio->opcionA."' ");

        $hcriterio2 = new Hcriterio();
        $bloqueb = $hcriterio2->find_first("turno='".$grupo->turno."' AND numero='".$materiacriterio->opcionB."' ");

        $myLog->log($curso->materia.", departamento: ".$departamento->nombre.", horarionombre: ".$bloqueh->nombre." ,horario: ".$bloqueh->inicio."-".$bloqueh->fin." ".$curso->inicio , Logger::ERROR);
        $dia_id = 1;
        $horassemana = $materia->horasporsemana();
        $valido = false;
        if($horassemana == 4){
            if($materia->id=="129" && $bloqueb->id!=""){
                $bloqueh = $bloqueb;
            }
            do{
                $ht = new Horariotemporal();
                $ht->cursos_id = $curso->id;
                $ht->dias_id = $dia_id;
                $ht->inicio = $bloqueh->inicio;
                $ht->fin = $bloqueh->fin;
                $hv = new Horariotemporal();
                if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                    $dia_id2 = $dia_id + 2;
                    if($dia_id2>5){
                        $valido = true;
                        $myLog->log("4 No se pudo ".$curso->materia, Logger::ERROR);
                    }else{
                        $ht1 = new Horariotemporal();
                        $ht1->cursos_id = $curso->id;
                        $ht1->dias_id = $dia_id2;
                        $ht1->inicio = $bloqueh->inicio;
                        $ht1->fin = $bloqueh->fin;

                        if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                            $ht->save();
                            $ht1->save();
                            $myLog->log("Horario guardado: ".$ht->dias_id.", ".$ht->inicio.", ".$ht->fin." ".$curso->inicio , Logger::ERROR);
                            $myLog->log("Horario guardado: ".$ht1->dias_id.", ".$ht1->inicio.", ".$ht1->fin." ".$curso->inicio , Logger::ERROR);
                            $asig++;
                            $valido = true;
                        }else{
                            $dia_id += 1;
                            if($dia_id>5){
                                $valido = true;
                                $myLog->log("4 No se pudo ".$curso->materia, Logger::ERROR);
                            }
                        }
                    }
                }else{
                    $dia_id += 1;
                    if($dia_id>5){
                        $valido = true;
                        $myLog->log("4 No se pudo ".$curso->materia, Logger::ERROR);
                    }
                }
            }while(!$valido);
        }elseif($horassemana == 3){

            do{
                if($materia->id == "124"){
                    if($bloqueb->id!=""){
                        $bloqueh = $bloqueb;
                    }
                    $dia_id = 4;
                    $ht = new Horariotemporal();
                    $ht->cursos_id = $curso->id;
                    $ht->dias_id = $dia_id;
                    $ht->inicio = $bloqueh->inicio;
                    $ht->fin = $bloqueh->fin;
                    $hv = new Horariotemporal();
                    //var_dump($ht);exit;
                    if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                        $dia_id2 = 5;
                        //var_dump($dia_id2);exit;
                        if($dia_id2>5){
                            $valido = true;
                            $myLog->log("3 No se pudo ".$curso->materia, Logger::ERROR);
                        }else{
                            $ht1 = new Horariotemporal();
                            $ht1->cursos_id = $curso->id;
                            $ht1->dias_id = $dia_id2;
                            $ht1->inicio = $bloqueh->inicio;
                            $ht1->fin = Utils::minutesToHours(Utils::timeToMinutes($bloqueh->fin) - 60);
                            $hv = new Horariotemporal();
                            if(!$hv->valida($ht1->cursos_id,$grupo->id,$materia->id,$ht1->dias_id,$ht1->inicio,$ht1->fin,Utils::convierteFecha($curso->inicio))){
                                $ht1->inicio = Utils::minutesToHours(Utils::timeToMinutes($bloqueh->inicio) + 60);
                                $ht1->fin = $bloqueh->fin;

                            }

                            $hv = new Horariotemporal();
                            if($hv->valida($ht1->cursos_id,$grupo->id,$materia->id,$ht1->dias_id,$ht1->inicio,$ht1->fin,Utils::convierteFecha($curso->inicio))){
                                $ht->save();
                                $ht1->save();
                                $myLog->log("Horario guardado: ".$ht->dias_id.", ".$ht->inicio.", ".$ht->fin." ".$curso->inicio , Logger::ERROR);
                                $myLog->log("Horario guardado: ".$ht1->dias_id.", ".$ht1->inicio.", ".$ht1->fin." ".$curso->inicio , Logger::ERROR);
                                $asig++;
                                $valido = true;
                            }else{

                                $dia_id += 1;
                                if($dia_id>5){
                                    $valido = true;
                                    $myLog->log("3 No se pudo ".$curso->materia, Logger::ERROR);
                                }
                            }
                        }
                    }else{
                        $dia_id += 1;
                        if($dia_id>5){
                            $valido = true;
                            $myLog->log("3 No se pudo ".$curso->materia, Logger::ERROR);
                        }
                    }
                }else{
                    if($materia->id=="125" && $bloqueb->id!=""){
                        $bloquet = $bloqueh;
                        $bloqueh = $bloqueb;
                        $dia_id = 2;
                    }
                    if($materia->id=="127"){
                        $dia_id = 2;
                    }

                    $ht = new Horariotemporal();
                    $ht->cursos_id = $curso->id;
                    $ht->dias_id = $dia_id;
                    $ht->inicio = $bloqueh->inicio;
                    $ht->fin = $bloqueh->fin;
                    $hv = new Horariotemporal();
                    if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                        $dia_id2 = 5;
                        if($dia_id2>5){
                            $valido = true;
                            $myLog->log("3 No se pudo ".$curso->materia, Logger::ERROR);
                        }else{
                            if($materia->id=="125" && $bloquet->id!=""){
                                $bloqueh = $bloquet;
                            }
                            $ht1 = new Horariotemporal();
                            $ht1->cursos_id = $curso->id;
                            $ht1->dias_id = $dia_id2;
                            $ht1->inicio = $bloqueh->inicio;
                            $ht1->fin = Utils::minutesToHours(Utils::timeToMinutes($bloqueh->fin) - 60);


                            //
                            if(!$hv->valida($ht1->cursos_id,$grupo->id,$materia->id,$ht1->dias_id,$ht1->inicio,$ht1->fin,Utils::convierteFecha($curso->inicio))){

                                $ht1->inicio = Utils::minutesToHours(Utils::timeToMinutes($bloqueh->inicio) + 60);
                                $ht1->fin = $bloqueh->fin;

                            }

                            if($hv->valida($ht1->cursos_id,$grupo->id,$materia->id,$ht1->dias_id,$ht1->inicio,$ht1->fin,Utils::convierteFecha($curso->inicio))){
                                $ht->save();
                                $ht1->save();
                                $myLog->log("Horario guardado: ".$ht->dias_id.", ".$ht->inicio.", ".$ht->fin." ".$curso->inicio , Logger::ERROR);
                                $myLog->log("Horario guardado: ".$ht1->dias_id.", ".$ht1->inicio.", ".$ht1->fin." ".$curso->inicio , Logger::ERROR);
                                $asig++;
                                $valido = true;
                            }else{
                                $dia_id += 1;
                                if($dia_id>5){
                                    $valido = true;
                                    $myLog->log("3 No se pudo ".$curso->materia, Logger::ERROR);
                                }
                            }
                        }
                    }else{
                        $dia_id += 1;
                        if($dia_id>5){
                            $valido = true;
                            $myLog->log("3 No se pudo ".$curso->materia, Logger::ERROR);
                        }
                    }
                }
            }while(!$valido);
        }elseif($horassemana == 5){
            $cuenta = 0;
            $guardar = array();


            if($materia->id=="113"){
                $dia_id = 2;
            }
            do{
                $ht = new Horariotemporal();
                $ht->cursos_id = $curso->id;
                if($dia_id>5 || $cuenta==2){
                    $dia_id=5;
                }

                $ht->dias_id = $dia_id;
                $ht->inicio = $bloqueh->inicio;
                if($cuenta==2){
                    $ht->fin = Utils::minutesToHours(Utils::timeToMinutes($bloqueh->fin) - 60);
                }else{
                    $ht->fin = $bloqueh->fin;
                }


                $hv = new Horariotemporal();
                    
                if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                    $guardar[] = $ht;
                    $cuenta++;
                    if($cuenta==3){
                        $valido = true;
                        foreach($guardar as $g){

                            $g->save();
                            $myLog->log("Horario guardado: ".$g->dias_id.", ".$g->inicio.", ".$g->fin." ".$curso->inicio , Logger::ERROR);
                        }
                        $asig++;
                    }elseif($cuenta==1){
                        $dia_id+=2;
                    }

                }else{
                    if($cuenta==0 && $dia_id==1){
                        $dia_id+=1;
                    }else{
                        $valido = true;
                        $myLog->log("5 No se pudo ".$curso->materia, Logger::ERROR);
                    }
                }

                if($dia_id>5 && !$valido){

                    $valido = true;
                    $myLog->log("5 No se pudo ".$curso->materia, Logger::ERROR);
                }

            }while(!$valido);

        }elseif($horassemana == 8){
            $cuenta = 0;
            $guardar = array();
            do{
                $ht = new Horariotemporal();
                $ht->cursos_id = $curso->id;
                $ht->dias_id = $dia_id;
                $ht->inicio = $bloqueh->inicio;
                $ht->fin = $bloqueh->fin;
                $hv = new Horariotemporal();
                if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                    $guardar[] = $ht;
                    $cuenta++;
                    $dia_id+=1;
                    if($cuenta==4){
                        $valido = true;
                        foreach($guardar as $g){

                            $g->save();
                            $myLog->log("Horario guardado: ".$g->dias_id.", ".$g->inicio.", ".$g->fin." ".$curso->inicio , Logger::ERROR);
                        }
                        $asig++;
                    }

                }else{
                    if($cuenta==0){
                        $dia_id+=1;
                    }else{
                        $valido = true;
                        $myLog->log("8 No se pudo ".$curso->materia, Logger::ERROR);
                    }
                }
                if($dia_id>5){
                    $valido = true;
                    $myLog->log("8 No se pudo ".$curso->materia, Logger::ERROR);
                }
            }while(!$valido);
        }elseif($horassemana == 2){
            $dia_id=5;
            do{
                $ht = new Horariotemporal();
                $ht->cursos_id = $curso->id;
                $ht->dias_id = $dia_id;
                $ht->inicio = $bloqueh->inicio;
                $ht->fin = $bloqueh->fin;
                $hv = new Horariotemporal();
                if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                    $ht->save();
                    $myLog->log("Horario guardado: ".$ht->dias_id.", ".$ht->inicio.", ".$ht->fin." ".$curso->inicio , Logger::ERROR);
                    $asig++;
                    $valido = true;
                }else{
                    $dia_id++;
                    if($dia_id>5){
                        $valido = true;
                        $myLog->log("2 No se pudo ".$curso->materia, Logger::ERROR);
                    }
                }
            }while(!$valido);
        }else{
            $myLog->log("Horas nuevas ".$horassemana, Logger::ERROR);
        }
        $myLog->close();
        return $asig;
    }


    public function tercero($grupo,$curso,$materia){
        $myLog = new Logger('GeneraHorario.log');
        $asig = 0;
        $academiamateria = new Academiamateria();
        $academiamateria = $academiamateria->find_first("materias_id='".$materia->id."'");
        //$myLog->log("academiamateria ".$academiamateria->id , Logger::ERROR);
        $academia = new Academia();
        $academia = $academia->find($academiamateria->academia_id);
        //$myLog->log("academia ".$academia->id , Logger::ERROR);
        $departamento = new Departamento();
        $departamento = $departamento->find($academia->departamento_id);
        //$myLog->log("departamento ".$departamento->id , Logger::ERROR);
        //var_dump($departamento);exit;
        $hora = "07:00:00";
        if($grupo->turno!="M"){
            $hora = "13:00:00";
        }

        $materiacriterio = new Materiacriterio();
        $materiacriterio = $materiacriterio->find_first("materias_id='".$materia->id."'");
        $hcriterio = new Hcriterio();
        $bloqueh = $hcriterio->find_first("turno='".$grupo->turno."' AND numero='".$materiacriterio->opcionA."' ");

        $hcriterio2 = new Hcriterio();
        $bloqueb = $hcriterio2->find_first("turno='".$grupo->turno."' AND numero='".$materiacriterio->opcionB."' ");

        $myLog->log($curso->materia.", departamento: ".$departamento->nombre.", horarionombre: ".$bloqueh->nombre." ,horario: ".$bloqueh->inicio."-".$bloqueh->fin." ".$curso->inicio , Logger::ERROR);
        $dia_id = 1;
        $horassemana = $materia->horasporsemana();
        $valido = false;
        if($horassemana == 4){
            if($materia->id=="129" && $bloqueb->id!=""){
                $bloqueh = $bloqueb;
            }
            do{
                $ht = new Horariotemporal();
                $ht->cursos_id = $curso->id;
                $ht->dias_id = $dia_id;
                $ht->inicio = $bloqueh->inicio;
                $ht->fin = $bloqueh->fin;
                $hv = new Horariotemporal();
                if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                    $dia_id2 = $dia_id + 2;
                    if($dia_id2>5){
                        $valido = true;
                        $myLog->log("4 No se pudo ".$curso->materia, Logger::ERROR);
                    }else{
                        $ht1 = new Horariotemporal();
                        $ht1->cursos_id = $curso->id;
                        $ht1->dias_id = $dia_id2;
                        $ht1->inicio = $bloqueh->inicio;
                        $ht1->fin = $bloqueh->fin;

                        if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                            $ht->save();
                            $ht1->save();
                            $myLog->log("Horario guardado: ".$ht->dias_id.", ".$ht->inicio.", ".$ht->fin." ".$curso->inicio , Logger::ERROR);
                            $myLog->log("Horario guardado: ".$ht1->dias_id.", ".$ht1->inicio.", ".$ht1->fin." ".$curso->inicio , Logger::ERROR);
                            $asig++;
                            $valido = true;
                        }else{
                            $dia_id += 1;
                            if($dia_id>5){
                                $valido = true;
                                $myLog->log("4 No se pudo ".$curso->materia, Logger::ERROR);
                            }
                        }
                    }
                }else{
                    $dia_id += 1;
                    if($dia_id>5){
                        $valido = true;
                        $myLog->log("4 No se pudo ".$curso->materia, Logger::ERROR);
                    }
                }
            }while(!$valido);
        }elseif($horassemana == 3){

            do{
                if($materia->id == "124"){
                    if($bloqueb->id!=""){
                        $bloqueh = $bloqueb;
                    }
                    $dia_id = 4;
                    $ht = new Horariotemporal();
                    $ht->cursos_id = $curso->id;
                    $ht->dias_id = $dia_id;
                    $ht->inicio = $bloqueh->inicio;
                    $ht->fin = $bloqueh->fin;
                    $hv = new Horariotemporal();
                    //var_dump($ht);exit;
                    if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                        $dia_id2 = 4;
                        //var_dump($dia_id2);exit;
                        if($dia_id2>5){
                            $valido = true;
                            $myLog->log("3 No se pudo ".$curso->materia, Logger::ERROR);
                        }else{
                            $ht1 = new Horariotemporal();
                            $ht1->cursos_id = $curso->id;
                            $ht1->dias_id = $dia_id2;
                            $ht1->inicio = $bloqueh->inicio;
                            $ht1->fin = Utils::minutesToHours(Utils::timeToMinutes($bloqueh->fin) - 60);
                            $hv = new Horariotemporal();
                            if(!$hv->valida($ht1->cursos_id,$grupo->id,$materia->id,$ht1->dias_id,$ht1->inicio,$ht1->fin,Utils::convierteFecha($curso->inicio))){
                                $ht1->inicio = Utils::minutesToHours(Utils::timeToMinutes($bloqueh->inicio) + 60);
                                $ht1->fin = $bloqueh->fin;

                            }

                            $hv = new Horariotemporal();
                            if($hv->valida($ht1->cursos_id,$grupo->id,$materia->id,$ht1->dias_id,$ht1->inicio,$ht1->fin,Utils::convierteFecha($curso->inicio))){
                                $ht->save();
                                $ht1->save();
                                $myLog->log("Horario guardado: ".$ht->dias_id.", ".$ht->inicio.", ".$ht->fin." ".$curso->inicio , Logger::ERROR);
                                $myLog->log("Horario guardado: ".$ht1->dias_id.", ".$ht1->inicio.", ".$ht1->fin." ".$curso->inicio , Logger::ERROR);
                                $asig++;
                                $valido = true;
                            }else{

                                $dia_id += 1;
                                if($dia_id>5){
                                    $valido = true;
                                    $myLog->log("3 No se pudo ".$curso->materia, Logger::ERROR);
                                }
                            }
                        }
                    }else{
                        $dia_id += 1;
                        if($dia_id>5){
                            $valido = true;
                            $myLog->log("3 No se pudo ".$curso->materia, Logger::ERROR);
                        }
                    }
                }else{
                    if($materia->id=="125" && $bloqueb->id!=""){
                        $bloquet = $bloqueh;
                        $bloqueh = $bloqueb;
                        $dia_id = 2;
                    }
                    if($materia->id=="127"){
                        $dia_id = 2;
                    }

                    $ht = new Horariotemporal();
                    $ht->cursos_id = $curso->id;
                    $ht->dias_id = $dia_id;
                    $ht->inicio = $bloqueh->inicio;
                    $ht->fin = $bloqueh->fin;
                    $hv = new Horariotemporal();
                    if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                        $dia_id2 = 4;
                        if($dia_id2>5){
                            $valido = true;
                            $myLog->log("3 No se pudo ".$curso->materia, Logger::ERROR);
                        }else{
                            if($materia->id=="125" && $bloquet->id!=""){
                                $bloqueh = $bloquet;
                            }
                            $ht1 = new Horariotemporal();
                            $ht1->cursos_id = $curso->id;
                            $ht1->dias_id = $dia_id2;
                            $ht1->inicio = $bloqueh->inicio;
                            $ht1->fin = Utils::minutesToHours(Utils::timeToMinutes($bloqueh->fin) - 60);


                            //
                            if(!$hv->valida($ht1->cursos_id,$grupo->id,$materia->id,$ht1->dias_id,$ht1->inicio,$ht1->fin,Utils::convierteFecha($curso->inicio))){

                                $ht1->inicio = Utils::minutesToHours(Utils::timeToMinutes($bloqueh->inicio) + 60);
                                $ht1->fin = $bloqueh->fin;

                            }

                            if($hv->valida($ht1->cursos_id,$grupo->id,$materia->id,$ht1->dias_id,$ht1->inicio,$ht1->fin,Utils::convierteFecha($curso->inicio))){
                                $ht->save();
                                $ht1->save();
                                $myLog->log("Horario guardado: ".$ht->dias_id.", ".$ht->inicio.", ".$ht->fin." ".$curso->inicio , Logger::ERROR);
                                $myLog->log("Horario guardado: ".$ht1->dias_id.", ".$ht1->inicio.", ".$ht1->fin." ".$curso->inicio , Logger::ERROR);
                                $asig++;
                                $valido = true;
                            }else{
                                $dia_id += 1;
                                if($dia_id>5){
                                    $valido = true;
                                    $myLog->log("3 No se pudo ".$curso->materia, Logger::ERROR);
                                }
                            }
                        }
                    }else{
                        $dia_id += 1;
                        if($dia_id>5){
                            $valido = true;
                            $myLog->log("3 No se pudo ".$curso->materia, Logger::ERROR);
                        }
                    }
                }
            }while(!$valido);
        }elseif($horassemana == 5){
            $cuenta = 0;
            $guardar = array();


            if($materia->id=="113"){
                $dia_id = 2;
            }
            do{
                $ht = new Horariotemporal();
                $ht->cursos_id = $curso->id;
                if($dia_id>5 || $cuenta==2){
                    $dia_id=4;
                }

                $ht->dias_id = $dia_id;
                $ht->inicio = $bloqueh->inicio;
                if($cuenta==2){
                    $ht->fin = Utils::minutesToHours(Utils::timeToMinutes($bloqueh->fin) - 60);
                }else{
                    $ht->fin = $bloqueh->fin;
                }


                $hv = new Horariotemporal();
                    
                if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                    $guardar[] = $ht;
                    $cuenta++;
                    if($cuenta==3){
                        $valido = true;
                        foreach($guardar as $g){

                            $g->save();
                            $myLog->log("Horario guardado: ".$g->dias_id.", ".$g->inicio.", ".$g->fin." ".$curso->inicio , Logger::ERROR);
                        }
                        $asig++;
                    }elseif($cuenta==1){
                        $dia_id+=2;
                    }

                }else{
                    if($cuenta==0 && $dia_id==1){
                        $dia_id+=1;
                    }else{
                        $valido = true;
                        $myLog->log("5 No se pudo ".$curso->materia, Logger::ERROR);
                    }
                }

                if($dia_id>5 && !$valido){

                    $valido = true;
                    $myLog->log("5 No se pudo ".$curso->materia, Logger::ERROR);
                }

            }while(!$valido);

        }elseif($horassemana == 8){
            $cuenta = 0;
            $guardar = array();
            do{
                $ht = new Horariotemporal();
                $ht->cursos_id = $curso->id;
                $ht->dias_id = $dia_id;
                $ht->inicio = $bloqueh->inicio;
                $ht->fin = $bloqueh->fin;
                $hv = new Horariotemporal();
                if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                    $guardar[] = $ht;
                    $cuenta++;
                    $dia_id+=1;
                    if($cuenta==4){
                        $valido = true;
                        foreach($guardar as $g){

                            $g->save();
                            $myLog->log("Horario guardado: ".$g->dias_id.", ".$g->inicio.", ".$g->fin." ".$curso->inicio , Logger::ERROR);
                        }
                        $asig++;
                    }

                }else{
                    if($cuenta==0){
                        $dia_id+=1;
                    }else{
                        $valido = true;
                        $myLog->log("8 No se pudo ".$curso->materia, Logger::ERROR);
                    }
                }
                if($dia_id>5){
                    $valido = true;
                    $myLog->log("8 No se pudo ".$curso->materia, Logger::ERROR);
                }
            }while(!$valido);
        }elseif($horassemana == 2){
            $dia_id=4;
            do{
                $ht = new Horariotemporal();
                $ht->cursos_id = $curso->id;
                $ht->dias_id = $dia_id;
                $ht->inicio = $bloqueh->inicio;
                $ht->fin = $bloqueh->fin;
                $hv = new Horariotemporal();
                if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                    $ht->save();
                    $myLog->log("Horario guardado: ".$ht->dias_id.", ".$ht->inicio.", ".$ht->fin." ".$curso->inicio , Logger::ERROR);
                    $asig++;
                    $valido = true;
                }else{
                    $dia_id++;
                    if($dia_id>5){
                        $valido = true;
                        $myLog->log("2 No se pudo ".$curso->materia, Logger::ERROR);
                    }
                }
            }while(!$valido);
        }else{
            $myLog->log("Horas nuevas ".$horassemana, Logger::ERROR);
        }
        $myLog->close();
        return $asig;
    }


    public function cuarto($grupo,$curso,$materia){
        $myLog = new Logger('GeneraHorario.log');
        $asig = 0;
        $academiamateria = new Academiamateria();
        $academiamateria = $academiamateria->find_first("materias_id='".$materia->id."'");
        //$myLog->log("academiamateria ".$academiamateria->id , Logger::ERROR);
        $academia = new Academia();
        $academia = $academia->find($academiamateria->academia_id);
        //$myLog->log("academia ".$academia->id , Logger::ERROR);
        $departamento = new Departamento();
        $departamento = $departamento->find($academia->departamento_id);
        //$myLog->log("departamento ".$departamento->id , Logger::ERROR);
        //var_dump($departamento);exit;
        $hora = "07:00:00";
        if($grupo->turno!="M"){
            $hora = "13:00:00";
        }

        $materiacriterio = new Materiacriterio();
        $materiacriterio = $materiacriterio->find_first("materias_id='".$materia->id."'");
        $hcriterio = new Hcriterio();
        $bloqueh = $hcriterio->find_first("turno='".$grupo->turno."' AND numero='".$materiacriterio->opcionA."' ");

        $hcriterio2 = new Hcriterio();
        $bloqueb = $hcriterio2->find_first("turno='".$grupo->turno."' AND numero='".$materiacriterio->opcionB."' ");

        $myLog->log($curso->materia.", departamento: ".$departamento->nombre.", horarionombre: ".$bloqueh->nombre." ,horario: ".$bloqueh->inicio."-".$bloqueh->fin." ".$curso->inicio , Logger::ERROR);
        $dia_id = 1;
        $horassemana = $materia->horasporsemana();
        $valido = false;
        if($horassemana == 4){
            if($materia->id=="129" && $bloqueb->id!=""){
                $bloqueh = $bloqueb;
            }
            do{
                $ht = new Horariotemporal();
                $ht->cursos_id = $curso->id;
                $ht->dias_id = $dia_id;
                $ht->inicio = $bloqueh->inicio;
                $ht->fin = $bloqueh->fin;
                $hv = new Horariotemporal();
                if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                    $dia_id2 = $dia_id + 2;
                    if($dia_id2>5){
                        $valido = true;
                        $myLog->log("4 No se pudo ".$curso->materia, Logger::ERROR);
                    }else{
                        $ht1 = new Horariotemporal();
                        $ht1->cursos_id = $curso->id;
                        $ht1->dias_id = $dia_id2;
                        $ht1->inicio = $bloqueh->inicio;
                        $ht1->fin = $bloqueh->fin;

                        if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                            $ht->save();
                            $ht1->save();
                            $myLog->log("Horario guardado: ".$ht->dias_id.", ".$ht->inicio.", ".$ht->fin." ".$curso->inicio , Logger::ERROR);
                            $myLog->log("Horario guardado: ".$ht1->dias_id.", ".$ht1->inicio.", ".$ht1->fin." ".$curso->inicio , Logger::ERROR);
                            $asig++;
                            $valido = true;
                        }else{
                            $dia_id += 1;
                            if($dia_id>5){
                                $valido = true;
                                $myLog->log("4 No se pudo ".$curso->materia, Logger::ERROR);
                            }
                        }
                    }
                }else{
                    $dia_id += 1;
                    if($dia_id>5){
                        $valido = true;
                        $myLog->log("4 No se pudo ".$curso->materia, Logger::ERROR);
                    }
                }
            }while(!$valido);
        }elseif($horassemana == 3){

            do{
                if($materia->id == "124"){
                    if($bloqueb->id!=""){
                        $bloqueh = $bloqueb;
                    }
                    $dia_id = 4;
                    $ht = new Horariotemporal();
                    $ht->cursos_id = $curso->id;
                    $ht->dias_id = $dia_id;
                    $ht->inicio = $bloqueh->inicio;
                    $ht->fin = $bloqueh->fin;
                    $hv = new Horariotemporal();
                    //var_dump($ht);exit;
                    if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                        $dia_id2 = 4;
                        //var_dump($dia_id2);exit;
                        if($dia_id2>5){
                            $valido = true;
                            $myLog->log("3 No se pudo ".$curso->materia, Logger::ERROR);
                        }else{
                            $ht1 = new Horariotemporal();
                            $ht1->cursos_id = $curso->id;
                            $ht1->dias_id = $dia_id2;
                            $ht1->inicio = $bloqueh->inicio;
                            $ht1->fin = Utils::minutesToHours(Utils::timeToMinutes($bloqueh->fin) - 60);
                            $hv = new Horariotemporal();
                            if(!$hv->valida($ht1->cursos_id,$grupo->id,$materia->id,$ht1->dias_id,$ht1->inicio,$ht1->fin,Utils::convierteFecha($curso->inicio))){
                                $ht1->inicio = Utils::minutesToHours(Utils::timeToMinutes($bloqueh->inicio) + 60);
                                $ht1->fin = $bloqueh->fin;

                            }

                            $hv = new Horariotemporal();
                            if($hv->valida($ht1->cursos_id,$grupo->id,$materia->id,$ht1->dias_id,$ht1->inicio,$ht1->fin,Utils::convierteFecha($curso->inicio))){
                                $ht->save();
                                $ht1->save();
                                $myLog->log("Horario guardado: ".$ht->dias_id.", ".$ht->inicio.", ".$ht->fin." ".$curso->inicio , Logger::ERROR);
                                $myLog->log("Horario guardado: ".$ht1->dias_id.", ".$ht1->inicio.", ".$ht1->fin." ".$curso->inicio , Logger::ERROR);
                                $asig++;
                                $valido = true;
                            }else{

                                $dia_id += 1;
                                if($dia_id>5){
                                    $valido = true;
                                    $myLog->log("3 No se pudo ".$curso->materia, Logger::ERROR);
                                }
                            }
                        }
                    }else{
                        $dia_id += 1;
                        if($dia_id>5){
                            $valido = true;
                            $myLog->log("3 No se pudo ".$curso->materia, Logger::ERROR);
                        }
                    }
                }else{

                    if($materia->id=="159"){
                        $dia_id = 3;
                    }

                    $ht = new Horariotemporal();
                    $ht->cursos_id = $curso->id;
                    $ht->dias_id = $dia_id;
                    $ht->inicio = $bloqueh->inicio;
                    $ht->fin = $bloqueh->fin;
                    $hv = new Horariotemporal();
                    if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                        $dia_id2 = 4;
                        if($materia->id=="159"){
                            $dia_id2 = 5;
                        }
                        if($dia_id2>5){
                            $valido = true;
                            $myLog->log("3 No se pudo ".$curso->materia, Logger::ERROR);
                        }else{
                            if($materia->id=="125" && $bloquet->id!=""){
                                $bloqueh = $bloquet;
                            }
                            $ht1 = new Horariotemporal();
                            $ht1->cursos_id = $curso->id;
                            $ht1->dias_id = $dia_id2;
                            $ht1->inicio = $bloqueh->inicio;
                            $ht1->fin = Utils::minutesToHours(Utils::timeToMinutes($bloqueh->fin) - 60);
                            //
                            if(!$hv->valida($ht1->cursos_id,$grupo->id,$materia->id,$ht1->dias_id,$ht1->inicio,$ht1->fin,Utils::convierteFecha($curso->inicio))){

                                $ht1->inicio = Utils::minutesToHours(Utils::timeToMinutes($bloqueh->inicio) + 60);
                                $ht1->fin = $bloqueh->fin;

                            }

                            if($hv->valida($ht1->cursos_id,$grupo->id,$materia->id,$ht1->dias_id,$ht1->inicio,$ht1->fin,Utils::convierteFecha($curso->inicio))){
                                $ht->save();
                                $ht1->save();
                                $myLog->log("Horario guardado: ".$ht->dias_id.", ".$ht->inicio.", ".$ht->fin." ".$curso->inicio , Logger::ERROR);
                                $myLog->log("Horario guardado: ".$ht1->dias_id.", ".$ht1->inicio.", ".$ht1->fin." ".$curso->inicio , Logger::ERROR);
                                $asig++;
                                $valido = true;
                            }else{
                                $dia_id += 1;
                                if($dia_id>5){
                                    $valido = true;
                                    $myLog->log("3 No se pudo ".$curso->materia, Logger::ERROR);
                                }
                            }
                        }
                    }else{
                        $dia_id += 1;
                        if($dia_id>5){
                            $valido = true;
                            $myLog->log("3 No se pudo ".$curso->materia, Logger::ERROR);
                        }
                    }
                }
            }while(!$valido);
        }elseif($horassemana == 5){
            $cuenta = 0;
            $guardar = array();


            if($materia->id=="113"){
                $dia_id = 2;
            }
            do{
                $ht = new Horariotemporal();
                $ht->cursos_id = $curso->id;
                if($dia_id>5 || $cuenta==2){
                    $dia_id=4;
                }

                $ht->dias_id = $dia_id;
                $ht->inicio = $bloqueh->inicio;
                if($cuenta==2){
                    $ht->fin = Utils::minutesToHours(Utils::timeToMinutes($bloqueh->fin) - 60);
                }else{
                    $ht->fin = $bloqueh->fin;
                }


                $hv = new Horariotemporal();
                    
                if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                    $guardar[] = $ht;
                    $cuenta++;
                    if($cuenta==3){
                        $valido = true;
                        foreach($guardar as $g){

                            $g->save();
                            $myLog->log("Horario guardado: ".$g->dias_id.", ".$g->inicio.", ".$g->fin." ".$curso->inicio , Logger::ERROR);
                        }
                        $asig++;
                    }elseif($cuenta==1){
                        $dia_id+=2;
                    }

                }else{
                    if($cuenta==0 && $dia_id==1){
                        $dia_id+=1;
                    }else{
                        $valido = true;
                        $myLog->log("5 No se pudo ".$curso->materia, Logger::ERROR);
                    }
                }

                if($dia_id>5 && !$valido){

                    $valido = true;
                    $myLog->log("5 No se pudo ".$curso->materia, Logger::ERROR);
                }

            }while(!$valido);

        }elseif($horassemana == 8){
            $cuenta = 0;
            $guardar = array();
            do{
                $ht = new Horariotemporal();
                $ht->cursos_id = $curso->id;
                $ht->dias_id = $dia_id;
                $ht->inicio = $bloqueh->inicio;
                $ht->fin = $bloqueh->fin;
                $hv = new Horariotemporal();
                if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                    $guardar[] = $ht;
                    $cuenta++;
                    $dia_id+=1;
                    if($cuenta==4){
                        $valido = true;
                        foreach($guardar as $g){

                            $g->save();
                            $myLog->log("Horario guardado: ".$g->dias_id.", ".$g->inicio.", ".$g->fin." ".$curso->inicio , Logger::ERROR);
                        }
                        $asig++;
                    }

                }else{
                    if($cuenta==0){
                        $dia_id+=1;
                    }else{
                        $valido = true;
                        $myLog->log("8 No se pudo ".$curso->materia, Logger::ERROR);
                    }
                }
                if($dia_id>5){
                    $valido = true;
                    $myLog->log("8 No se pudo ".$curso->materia, Logger::ERROR);
                }
            }while(!$valido);
        }elseif($horassemana == 2){
            $dia_id=4;
            do{
                $ht = new Horariotemporal();
                $ht->cursos_id = $curso->id;
                $ht->dias_id = $dia_id;
                $ht->inicio = $bloqueh->inicio;
                $ht->fin = $bloqueh->fin;
                $hv = new Horariotemporal();
                if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                    $ht->save();
                    $myLog->log("Horario guardado: ".$ht->dias_id.", ".$ht->inicio.", ".$ht->fin." ".$curso->inicio , Logger::ERROR);
                    $asig++;
                    $valido = true;
                }else{
                    $dia_id++;
                    if($dia_id>5){
                        $valido = true;
                        $myLog->log("2 No se pudo ".$curso->materia, Logger::ERROR);
                    }
                }
            }while(!$valido);
        }else{
            $myLog->log("Horas nuevas ".$horassemana, Logger::ERROR);
        }
        $myLog->close();
        return $asig;
    }

    public function quinto($grupo,$curso,$materia){
        $myLog = new Logger('GeneraHorario.log');
        $asig = 0;
        $academiamateria = new Academiamateria();
        $academiamateria = $academiamateria->find_first("materias_id='".$materia->id."'");
        //$myLog->log("academiamateria ".$academiamateria->id , Logger::ERROR);
        $academia = new Academia();
        $academia = $academia->find($academiamateria->academia_id);
        //$myLog->log("academia ".$academia->id , Logger::ERROR);
        $departamento = new Departamento();
        $departamento = $departamento->find($academia->departamento_id);
        //$myLog->log("departamento ".$departamento->id , Logger::ERROR);
        //var_dump($departamento);exit;

        $materiacriterio = new Materiacriterio();
        $materiacriterio = $materiacriterio->find_first("materias_id='".$materia->id."'");
        $hcriterio = new Hcriterio();
        $bloqueh = $hcriterio->find_first("turno='".$grupo->turno."' AND numero='".$materiacriterio->opcionA."' ");

        $hcriterio2 = new Hcriterio();
        $bloqueb = $hcriterio2->find_first("turno='".$grupo->turno."' AND numero='".$materiacriterio->opcionB."' ");

        if($grupo->turno=="V"){
            $bloqueh->inicio = Utils::minutesToHours(Utils::timeToMinutes($bloqueh->inicio) - 60);
            $bloqueh->fin = Utils::minutesToHours(Utils::timeToMinutes($bloqueh->fin) - 60);
            if($bloqueb->id!=""){
                $bloqueb->inicio = Utils::minutesToHours(Utils::timeToMinutes($bloqueb->inicio) - 60);
                $bloqueb->fin = Utils::minutesToHours(Utils::timeToMinutes($bloqueb->fin) - 60);
            }
        }


        $myLog->log($curso->materia.", departamento: ".$departamento->nombre.", horarionombre: ".$bloqueh->nombre." ,horario: ".$bloqueh->inicio."-".$bloqueh->fin." ".$curso->inicio , Logger::ERROR);
        $dia_id = 1;
        $horassemana = $materia->horasporsemana();
        $valido = false;
        if($horassemana == 4){
            if($materia->id=="42" && $bloqueb->id!=""){
                $bloqueh = $bloqueb;
                $dia_id = 3;
            }
            do{
                $ht = new Horariotemporal();
                $ht->cursos_id = $curso->id;
                $ht->dias_id = $dia_id;
                $ht->inicio = $bloqueh->inicio;
                $ht->fin = $bloqueh->fin;
                $hv = new Horariotemporal();
                if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                    $dia_id2 = $dia_id + 2;
                    if($dia_id2>5){
                        $valido = true;
                        $myLog->log("4 No se pudo ".$curso->materia, Logger::ERROR);
                    }else{
                        $ht1 = new Horariotemporal();
                        $ht1->cursos_id = $curso->id;
                        $ht1->dias_id = $dia_id2;
                        $ht1->inicio = $bloqueh->inicio;
                        $ht1->fin = $bloqueh->fin;

                        if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                            $ht->save();
                            $ht1->save();
                            $myLog->log("Horario guardado: ".$ht->dias_id.", ".$ht->inicio.", ".$ht->fin." ".$curso->inicio , Logger::ERROR);
                            $myLog->log("Horario guardado: ".$ht1->dias_id.", ".$ht1->inicio.", ".$ht1->fin." ".$curso->inicio , Logger::ERROR);
                            $asig++;
                            $valido = true;
                        }else{
                            $dia_id += 1;
                            if($dia_id>5){
                                $valido = true;
                                $myLog->log("4 No se pudo ".$curso->materia, Logger::ERROR);
                            }
                        }
                    }
                }else{
                    $dia_id += 1;
                    if($dia_id>5){
                        $valido = true;
                        $myLog->log("4 No se pudo ".$curso->materia, Logger::ERROR);
                    }
                }
            }while(!$valido);
        }elseif($horassemana == 3){

            do{
                if($materia->id == "124"){
                    if($bloqueb->id!=""){
                        $bloqueh = $bloqueb;
                    }
                    $dia_id = 4;
                    $ht = new Horariotemporal();
                    $ht->cursos_id = $curso->id;
                    $ht->dias_id = $dia_id;
                    $ht->inicio = $bloqueh->inicio;
                    $ht->fin = $bloqueh->fin;
                    $hv = new Horariotemporal();
                    //var_dump($ht);exit;
                    if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                        $dia_id2 = 4;
                        //var_dump($dia_id2);exit;
                        if($dia_id2>5){
                            $valido = true;
                            $myLog->log("3 No se pudo ".$curso->materia, Logger::ERROR);
                        }else{
                            $ht1 = new Horariotemporal();
                            $ht1->cursos_id = $curso->id;
                            $ht1->dias_id = $dia_id2;
                            $ht1->inicio = $bloqueh->inicio;
                            $ht1->fin = Utils::minutesToHours(Utils::timeToMinutes($bloqueh->fin) - 60);
                            $hv = new Horariotemporal();
                            if(!$hv->valida($ht1->cursos_id,$grupo->id,$materia->id,$ht1->dias_id,$ht1->inicio,$ht1->fin,Utils::convierteFecha($curso->inicio))){
                                $ht1->inicio = Utils::minutesToHours(Utils::timeToMinutes($bloqueh->inicio) + 60);
                                $ht1->fin = $bloqueh->fin;

                            }

                            $hv = new Horariotemporal();
                            if($hv->valida($ht1->cursos_id,$grupo->id,$materia->id,$ht1->dias_id,$ht1->inicio,$ht1->fin,Utils::convierteFecha($curso->inicio))){
                                $ht->save();
                                $ht1->save();
                                $myLog->log("Horario guardado: ".$ht->dias_id.", ".$ht->inicio.", ".$ht->fin." ".$curso->inicio , Logger::ERROR);
                                $myLog->log("Horario guardado: ".$ht1->dias_id.", ".$ht1->inicio.", ".$ht1->fin." ".$curso->inicio , Logger::ERROR);
                                $asig++;
                                $valido = true;
                            }else{

                                $dia_id += 1;
                                if($dia_id>5){
                                    $valido = true;
                                    $myLog->log("3 No se pudo ".$curso->materia, Logger::ERROR);
                                }
                            }
                        }
                    }else{
                        $dia_id += 1;
                        if($dia_id>5){
                            $valido = true;
                            $myLog->log("3 No se pudo ".$curso->materia, Logger::ERROR);
                        }
                    }
                }else{

                    if($materia->id=="159"){
                        $dia_id = 3;
                    }

                    $ht = new Horariotemporal();
                    $ht->cursos_id = $curso->id;
                    $ht->dias_id = $dia_id;
                    $ht->inicio = $bloqueh->inicio;
                    $ht->fin = $bloqueh->fin;
                    $hv = new Horariotemporal();
                    if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                        $dia_id2 = 4;
                        if($materia->id=="159"){
                            $dia_id2 = 5;
                        }
                        if($dia_id2>5){
                            $valido = true;
                            $myLog->log("3 No se pudo ".$curso->materia, Logger::ERROR);
                        }else{
                            if($materia->id=="125" && $bloquet->id!=""){
                                $bloqueh = $bloquet;
                            }
                            $ht1 = new Horariotemporal();
                            $ht1->cursos_id = $curso->id;
                            $ht1->dias_id = $dia_id2;
                            $ht1->inicio = $bloqueh->inicio;
                            $ht1->fin = Utils::minutesToHours(Utils::timeToMinutes($bloqueh->fin) - 60);
                            //
                            if(!$hv->valida($ht1->cursos_id,$grupo->id,$materia->id,$ht1->dias_id,$ht1->inicio,$ht1->fin,Utils::convierteFecha($curso->inicio))){

                                $ht1->inicio = Utils::minutesToHours(Utils::timeToMinutes($bloqueh->inicio) + 60);
                                $ht1->fin = $bloqueh->fin;

                            }

                            if($hv->valida($ht1->cursos_id,$grupo->id,$materia->id,$ht1->dias_id,$ht1->inicio,$ht1->fin,Utils::convierteFecha($curso->inicio))){
                                $ht->save();
                                $ht1->save();
                                $myLog->log("Horario guardado: ".$ht->dias_id.", ".$ht->inicio.", ".$ht->fin." ".$curso->inicio , Logger::ERROR);
                                $myLog->log("Horario guardado: ".$ht1->dias_id.", ".$ht1->inicio.", ".$ht1->fin." ".$curso->inicio , Logger::ERROR);
                                $asig++;
                                $valido = true;
                            }else{
                                $dia_id += 1;
                                if($dia_id>5){
                                    $valido = true;
                                    $myLog->log("3 No se pudo ".$curso->materia, Logger::ERROR);
                                }
                            }
                        }
                    }else{
                        $dia_id += 1;
                        if($dia_id>5){
                            $valido = true;
                            $myLog->log("3 No se pudo ".$curso->materia, Logger::ERROR);
                        }
                    }
                }
            }while(!$valido);
        }elseif($horassemana == 5){
            $cuenta = 0;
            $guardar = array();


            if($materia->id=="113"){
                $dia_id = 2;
            }
            do{
                $ht = new Horariotemporal();
                $ht->cursos_id = $curso->id;
                if($dia_id>5 || $cuenta==2){
                    $dia_id=4;
                }

                $ht->dias_id = $dia_id;
                $ht->inicio = $bloqueh->inicio;
                if($cuenta==2){
                    $ht->fin = Utils::minutesToHours(Utils::timeToMinutes($bloqueh->fin) - 60);
                }else{
                    $ht->fin = $bloqueh->fin;
                }


                $hv = new Horariotemporal();
                    
                if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                    $guardar[] = $ht;
                    $cuenta++;
                    if($cuenta==3){
                        $valido = true;
                        foreach($guardar as $g){

                            $g->save();
                            $myLog->log("Horario guardado: ".$g->dias_id.", ".$g->inicio.", ".$g->fin." ".$curso->inicio , Logger::ERROR);
                        }
                        $asig++;
                    }elseif($cuenta==1){
                        $dia_id+=2;
                    }

                }else{
                    if($cuenta==0 && $dia_id==1){
                        $dia_id+=1;
                    }else{
                        $valido = true;
                        $myLog->log("5 No se pudo ".$curso->materia, Logger::ERROR);
                    }
                }

                if($dia_id>5 && !$valido){

                    $valido = true;
                    $myLog->log("5 No se pudo ".$curso->materia, Logger::ERROR);
                }

            }while(!$valido);

        }elseif($horassemana == 8){
            $cuenta = 0;
            $guardar = array();
            do{
                $ht = new Horariotemporal();
                $ht->cursos_id = $curso->id;
                $ht->dias_id = $dia_id;
                $ht->inicio = $bloqueh->inicio;
                $ht->fin = $bloqueh->fin;
                $hv = new Horariotemporal();
                if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                    $guardar[] = $ht;
                    $cuenta++;
                    $dia_id+=1;
                    if($cuenta==4){
                        $valido = true;
                        foreach($guardar as $g){

                            $g->save();
                            $myLog->log("Horario guardado: ".$g->dias_id.", ".$g->inicio.", ".$g->fin." ".$curso->inicio , Logger::ERROR);
                        }
                        $asig++;
                    }

                }else{
                    if($cuenta==0){
                        $dia_id+=1;
                    }else{
                        $valido = true;
                        $myLog->log("8 No se pudo ".$curso->materia, Logger::ERROR);
                    }
                }
                if($dia_id>5){
                    $valido = true;
                    $myLog->log("8 No se pudo ".$curso->materia, Logger::ERROR);
                }
            }while(!$valido);
        }elseif($horassemana == 2){
            $dia_id=4;
            do{
                $ht = new Horariotemporal();
                $ht->cursos_id = $curso->id;
                $ht->dias_id = $dia_id;
                $ht->inicio = $bloqueh->inicio;
                $ht->fin = $bloqueh->fin;
                $hv = new Horariotemporal();
                if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                    $ht->save();
                    $myLog->log("Horario guardado: ".$ht->dias_id.", ".$ht->inicio.", ".$ht->fin." ".$curso->inicio , Logger::ERROR);
                    $asig++;
                    $valido = true;
                }else{
                    $dia_id++;
                    if($dia_id>5){
                        $valido = true;
                        $myLog->log("2 No se pudo ".$curso->materia, Logger::ERROR);
                    }
                }
            }while(!$valido);
        }else{
            $myLog->log("Horas nuevas ".$horassemana, Logger::ERROR);
        }
        $myLog->close();
        return $asig;
    }

    public function sexto($grupo,$curso,$materia){
        $myLog = new Logger('GeneraHorario.log');
        $asig = 0;
        $academiamateria = new Academiamateria();
        $academiamateria = $academiamateria->find_first("materias_id='".$materia->id."'");
        //$myLog->log("academiamateria ".$academiamateria->id , Logger::ERROR);
        $academia = new Academia();
        $academia = $academia->find($academiamateria->academia_id);
        //$myLog->log("academia ".$academia->id , Logger::ERROR);
        $departamento = new Departamento();
        $departamento = $departamento->find($academia->departamento_id);
        //$myLog->log("departamento ".$departamento->id , Logger::ERROR);
        //var_dump($departamento);exit;
        $materiacriterio = new Materiacriterio();
        $materiacriterio = $materiacriterio->find_first("materias_id='".$materia->id."'");
        if($materiacriterio->id==""){
            if($materia->tipo=="TLR" || $materia->tipo=="OPT" ){
                $hcriterio = new Hcriterio();
                $bloqueh = $hcriterio->find_first("turno='".$grupo->turno."' AND numero='3' ");

                $bloqueb = new Hcriterio();
                    
            }elseif($materia->tipo=="PRO"){
                $hcriterio = new Hcriterio();
                $bloqueh = $hcriterio->find_first("turno='".$grupo->turno."' AND numero='2' ");

                $bloqueb = new Hcriterio();
                    
            }
        }else{
            $hcriterio = new Hcriterio();
            $bloqueh = $hcriterio->find_first("turno='".$grupo->turno."' AND numero='".$materiacriterio->opcionA."' ");

            $hcriterio2 = new Hcriterio();
            $bloqueb = $hcriterio2->find_first("turno='".$grupo->turno."' AND numero='".$materiacriterio->opcionB."' ");
                
        }
        if($grupo->turno=="V"){
            $bloqueh->inicio = Utils::minutesToHours(Utils::timeToMinutes($bloqueh->inicio) - 60);
            $bloqueh->fin = Utils::minutesToHours(Utils::timeToMinutes($bloqueh->fin) - 60);
            if($bloqueb->id!=""){
                $bloqueb->inicio = Utils::minutesToHours(Utils::timeToMinutes($bloqueb->inicio) - 60);
                $bloqueb->fin = Utils::minutesToHours(Utils::timeToMinutes($bloqueb->fin) - 60);
            }
        }


        $myLog->log($curso->materia.", departamento: ".$departamento->nombre.", horarionombre: ".$bloqueh->nombre." ,horario: ".$bloqueh->inicio."-".$bloqueh->fin." ".$curso->inicio , Logger::ERROR);
        $dia_id = 1;
        $horassemana = $materia->horasporsemana();
        $valido = false;
        if($horassemana == 4){
            if($materia->id=="42" && $bloqueb->id!=""){
                $bloqueh = $bloqueb;
                $dia_id = 3;
            }
            do{
                $ht = new Horariotemporal();
                $ht->cursos_id = $curso->id;
                $ht->dias_id = $dia_id;
                $ht->inicio = $bloqueh->inicio;
                $ht->fin = $bloqueh->fin;
                $hv = new Horariotemporal();
                if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                    $dia_id2 = $dia_id + 2;
                    if($dia_id2>5){
                        $valido = true;
                        $myLog->log("4 No se pudo ".$curso->materia, Logger::ERROR);
                    }else{
                        $ht1 = new Horariotemporal();
                        $ht1->cursos_id = $curso->id;
                        $ht1->dias_id = $dia_id2;
                        $ht1->inicio = $bloqueh->inicio;
                        $ht1->fin = $bloqueh->fin;

                        if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                            $ht->save();
                            $ht1->save();
                            $myLog->log("Horario guardado: ".$ht->dias_id.", ".$ht->inicio.", ".$ht->fin." ".$curso->inicio , Logger::ERROR);
                            $myLog->log("Horario guardado: ".$ht1->dias_id.", ".$ht1->inicio.", ".$ht1->fin." ".$curso->inicio , Logger::ERROR);
                            $asig++;
                            $valido = true;
                        }else{
                            $dia_id += 1;
                            if($dia_id>5){
                                $valido = true;
                                $myLog->log("4 No se pudo ".$curso->materia, Logger::ERROR);
                            }
                        }
                    }
                }else{
                    $dia_id += 1;
                    if($dia_id>5){
                        $valido = true;
                        $myLog->log("4 No se pudo ".$curso->materia, Logger::ERROR);
                    }
                }
            }while(!$valido);
        }elseif($horassemana == 3){

            do{
                if($materia->id == "124"){
                    if($bloqueb->id!=""){
                        $bloqueh = $bloqueb;
                    }
                    $dia_id = 4;
                    $ht = new Horariotemporal();
                    $ht->cursos_id = $curso->id;
                    $ht->dias_id = $dia_id;
                    $ht->inicio = $bloqueh->inicio;
                    $ht->fin = $bloqueh->fin;
                    $hv = new Horariotemporal();
                    //var_dump($ht);exit;
                    if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                        $dia_id2 = 4;
                        //var_dump($dia_id2);exit;
                        if($dia_id2>5){
                            $valido = true;
                            $myLog->log("3 No se pudo ".$curso->materia, Logger::ERROR);
                        }else{
                            $ht1 = new Horariotemporal();
                            $ht1->cursos_id = $curso->id;
                            $ht1->dias_id = $dia_id2;
                            $ht1->inicio = $bloqueh->inicio;
                            $ht1->fin = Utils::minutesToHours(Utils::timeToMinutes($bloqueh->fin) - 60);
                            $hv = new Horariotemporal();
                            if(!$hv->valida($ht1->cursos_id,$grupo->id,$materia->id,$ht1->dias_id,$ht1->inicio,$ht1->fin,Utils::convierteFecha($curso->inicio))){
                                $ht1->inicio = Utils::minutesToHours(Utils::timeToMinutes($bloqueh->inicio) + 60);
                                $ht1->fin = $bloqueh->fin;

                            }

                            $hv = new Horariotemporal();
                            if($hv->valida($ht1->cursos_id,$grupo->id,$materia->id,$ht1->dias_id,$ht1->inicio,$ht1->fin,Utils::convierteFecha($curso->inicio))){
                                $ht->save();
                                $ht1->save();
                                $myLog->log("Horario guardado: ".$ht->dias_id.", ".$ht->inicio.", ".$ht->fin." ".$curso->inicio , Logger::ERROR);
                                $myLog->log("Horario guardado: ".$ht1->dias_id.", ".$ht1->inicio.", ".$ht1->fin." ".$curso->inicio , Logger::ERROR);
                                $asig++;
                                $valido = true;
                            }else{

                                $dia_id += 1;
                                if($dia_id>5){
                                    $valido = true;
                                    $myLog->log("3 No se pudo ".$curso->materia, Logger::ERROR);
                                }
                            }
                        }
                    }else{
                        $dia_id += 1;
                        if($dia_id>5){
                            $valido = true;
                            $myLog->log("3 No se pudo ".$curso->materia, Logger::ERROR);
                        }
                    }
                }else{

                    if($materia->id=="58"){
                        $dia_id = 3;
                    }

                    $ht = new Horariotemporal();
                    $ht->cursos_id = $curso->id;
                    $ht->dias_id = $dia_id;
                    $ht->inicio = $bloqueh->inicio;
                    $ht->fin = $bloqueh->fin;
                    $hv = new Horariotemporal();
                    if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                        $dia_id2 = 4;
                        if($materia->id=="58"){
                            $dia_id2 = 5;
                        }
                        if($dia_id2>5){
                            $valido = true;
                            $myLog->log("3 No se pudo ".$curso->materia, Logger::ERROR);
                        }else{
                            if($materia->id=="125" && $bloquet->id!=""){
                                $bloqueh = $bloquet;
                            }
                            $ht1 = new Horariotemporal();
                            $ht1->cursos_id = $curso->id;
                            $ht1->dias_id = $dia_id2;
                            $ht1->inicio = $bloqueh->inicio;
                            $ht1->fin = Utils::minutesToHours(Utils::timeToMinutes($bloqueh->fin) - 60);
                            //
                            if(!$hv->valida($ht1->cursos_id,$grupo->id,$materia->id,$ht1->dias_id,$ht1->inicio,$ht1->fin,Utils::convierteFecha($curso->inicio))){

                                $ht1->inicio = Utils::minutesToHours(Utils::timeToMinutes($bloqueh->inicio) + 60);
                                $ht1->fin = $bloqueh->fin;

                            }

                            if($hv->valida($ht1->cursos_id,$grupo->id,$materia->id,$ht1->dias_id,$ht1->inicio,$ht1->fin,Utils::convierteFecha($curso->inicio))){
                                $ht->save();
                                $ht1->save();
                                $myLog->log("Horario guardado: ".$ht->dias_id.", ".$ht->inicio.", ".$ht->fin." ".$curso->inicio , Logger::ERROR);
                                $myLog->log("Horario guardado: ".$ht1->dias_id.", ".$ht1->inicio.", ".$ht1->fin." ".$curso->inicio , Logger::ERROR);
                                $asig++;
                                $valido = true;
                            }else{
                                $dia_id += 1;
                                if($dia_id>5){
                                    $valido = true;
                                    $myLog->log("3 No se pudo ".$curso->materia, Logger::ERROR);
                                }
                            }
                        }
                    }else{
                        $dia_id += 1;
                        if($dia_id>5){
                            $valido = true;
                            $myLog->log("3 No se pudo ".$curso->materia, Logger::ERROR);
                        }
                    }
                }
            }while(!$valido);
        }elseif($horassemana == 5){
            $cuenta = 0;
            $guardar = array();
            do{
                $ht = new Horariotemporal();
                $ht->cursos_id = $curso->id;
                if($dia_id>5 || $cuenta==2 ){
                    $dia_id=5;
                }

                $ht->dias_id = $dia_id;
                $ht->fin = $bloqueh->fin;
                if($cuenta==2){
                    $ht->inicio = Utils::minutesToHours(Utils::timeToMinutes($bloqueh->inicio) + 60);
                }else{
                    $ht->inicio = $bloqueh->inicio;
                }


                $hv = new Horariotemporal();
                    
                if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                    var_dump($ht);
                    $guardar[] = $ht;
                    $cuenta++;
                    if($cuenta==3){
                        $valido = true;
                        foreach($guardar as $g){
                            $g->save();
                            $myLog->log("Horario guardado: ".$g->dias_id.", ".$g->inicio.", ".$g->fin." ".$curso->inicio , Logger::ERROR);
                        }
                        $asig++;
                    }elseif($cuenta==1){
                        $dia_id+=2;
                    }

                }else{
                    if($cuenta==0 && $dia_id==1){
                        $dia_id+=1;
                    }else{
                        $valido = true;
                        $myLog->log("5 No se pudo ".$curso->materia, Logger::ERROR);
                    }
                }

                if($dia_id>5 && !$valido){

                    $valido = true;
                    $myLog->log("5 No se pudo ".$curso->materia, Logger::ERROR);
                }

            }while(!$valido);

        }elseif($horassemana == 8){
            $cuenta = 0;
            $guardar = array();
            do{
                $ht = new Horariotemporal();
                $ht->cursos_id = $curso->id;
                $ht->dias_id = $dia_id;
                $ht->inicio = $bloqueh->inicio;
                $ht->fin = $bloqueh->fin;
                $hv = new Horariotemporal();
                if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                    $guardar[] = $ht;
                    $cuenta++;
                    $dia_id+=1;
                    if($cuenta==4){
                        $valido = true;
                        foreach($guardar as $g){

                            $g->save();
                            $myLog->log("Horario guardado: ".$g->dias_id.", ".$g->inicio.", ".$g->fin." ".$curso->inicio , Logger::ERROR);
                        }
                        $asig++;
                    }

                }else{
                    if($cuenta==0){
                        $dia_id+=1;
                    }else{
                        $valido = true;
                        $myLog->log("8 No se pudo ".$curso->materia, Logger::ERROR);
                    }
                }
                if($dia_id>5){
                    $valido = true;
                    $myLog->log("8 No se pudo ".$curso->materia, Logger::ERROR);
                }
            }while(!$valido);
        }elseif($horassemana == 2){
            $dia_id=4;
            do{
                $ht = new Horariotemporal();
                $ht->cursos_id = $curso->id;
                $ht->dias_id = $dia_id;
                $ht->inicio = $bloqueh->inicio;
                $ht->fin = $bloqueh->fin;
                $hv = new Horariotemporal();
                if($hv->valida($ht->cursos_id,$grupo->id,$materia->id,$ht->dias_id,$ht->inicio,$ht->fin,Utils::convierteFecha($curso->inicio))){
                    $ht->save();
                    $myLog->log("Horario guardado: ".$ht->dias_id.", ".$ht->inicio.", ".$ht->fin." ".$curso->inicio , Logger::ERROR);
                    $asig++;
                    $valido = true;
                }else{
                    $dia_id++;
                    if($dia_id>5){
                        $valido = true;
                        $myLog->log("2 No se pudo ".$curso->materia, Logger::ERROR);
                    }
                }
            }while(!$valido);
        }else{
            $myLog->log("Horas nuevas ".$horassemana, Logger::ERROR);
        }
        $myLog->close();
        return $asig;
    }



}
?>
