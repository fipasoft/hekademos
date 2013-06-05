<?php

/*
 * Created on 08/03/2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class Avance {
    private $ciclo;
    private $REPETIDOR = 5;
    private $IRREGULAR = 4;
    private $EGRESADO = 3;
    private $BAJA = 2;
    private $REGULAR = 1;
    private $SIGUIENTE;
    private $ARTICULOS;
    private $CALIFICACIONES;
    private $MATERIASPROMEDIO;
    private $BACHILLERATOGENERAL;
    private $BACHILLERATOCOMPETENCIAS;

    private $ANTERIOR;
    private $CURSADOS;
    private $SITUACIONES;
    private $_CAL;
    private $DATOSARTICULOS;

    function Avance($ciclo) {
        $this->ANTERIOR = array ();
        $this->CURSADOS = array ();
        $this->_CAL = array ();
        $this->SITUACIONES = array ();
        $this->DATOSARTICULOS=array();

        $this->ciclo = $ciclo;

        $situaciones = new Situaciones();
        $situaciones = $situaciones->find_first("clave='IRR'");
        $this->IRREGULAR = $situaciones->id;

        $situaciones = new Situaciones();
        $situaciones = $situaciones->find_first("clave='EGR'");
        $this->EGRESADO = $situaciones->id;

        $situaciones = new Situaciones();
        $situaciones = $situaciones->find_first("clave='BJA'");
        $this->BAJA = $situaciones->id;

        $situaciones = new Situaciones();
        $situaciones = $situaciones->find_first("clave='REG'");
        $this->REGULAR = $situaciones->id;

        $this->BACHILLERATOGENERAL = Oferta :: bachilleratogeneral();
        $this->BACHILLERATOCOMPETENCIAS = Oferta :: bachilleratocompetencias();

        $sig = new Ciclos();
        $this->SIGUIENTE = $sig->find_first("numero='" . $this->ciclo->siguiente() . "'");

        $this->cargaArticulos();
        //$this->cargaConfig();

        $this->CALIFICACIONES = array ();
    }

    /*private function cargaConfig() {
        $promedio = Config :: read("promedio.ini");
        $this->MATERIASPROMEDIO = array ();

        //general
        $this->MATERIASPROMEDIO[1] = array ();
        $this->MATERIASPROMEDIO[1][1] = $promedio->general->primero;
        $this->MATERIASPROMEDIO[1][2] = $promedio->general->segundo;
        $this->MATERIASPROMEDIO[1][3] = $promedio->general->tercero;
        $this->MATERIASPROMEDIO[1][4] = $promedio->general->cuarto;
        $this->MATERIASPROMEDIO[1][5] = $promedio->general->quinto;
        $this->MATERIASPROMEDIO[1][6] = $promedio->general->sexto;

        //competencias
        $this->MATERIASPROMEDIO[2] = array ();
        $this->MATERIASPROMEDIO[2][1] = $promedio->competencias->primero;
        $this->MATERIASPROMEDIO[2][2] = $promedio->competencias->segundo;
        $this->MATERIASPROMEDIO[2][3] = $promedio->competencias->tercero;
        $this->MATERIASPROMEDIO[2][4] = $promedio->competencias->cuarto;
        $this->MATERIASPROMEDIO[2][5] = $promedio->competencias->quinto;
        $this->MATERIASPROMEDIO[2][6] = $promedio->competencias->sexto;

        }
        */

    private function cargaArticulos() {
        $articulos = new Articulos();
        $articulos = $articulos->find();

        foreach ($articulos as $articulo) {
            $this->ARTICULOS[$articulo->clave] = $articulo;
        }

        $a = new Articulos();
        $a->clave = 'baja';

        $this->ARTICULOS['baja'] = $a;

    }

    public function procesar() {
        require ('app/reportes/xls.cierre.php');

        /*$alumno = new Alumnos();
         $alumno = $alumno->find_first("codigo='209141987'");
         $this->avanceAlumno($alumno);


         $alumnos=array($alumno);
         ob_end_clean();
         ob_start();

         $ciclo_id = Session :: get_data('ciclo.id');
         $reporte = new XLSCierre($grupo,$alumnos, $this->ANTERIOR, $this->CURSADOS, $this->_CAL, $this->SITUACIONES,$this->DATOSARTICULOS);
         $n = $reporte->getNombre();
         $f = fopen('./logs/'. $n, "w");
         $reporte->close();
         fwrite($f, ob_get_contents());
         fclose($f);

         ob_end_clean();

         return;*/

        if(!file_exists('./logs/avance'.$this->ciclo->numero.'/')){
            mkdir('./logs/avance'.$this->ciclo->numero.'/');
        }

        if(!file_exists('./logs/avance'.$this->ciclo->numero.'/M/')){
            mkdir('./logs/avance'.$this->ciclo->numero.'/M/');
        }

        if(!file_exists('./logs/avance'.$this->ciclo->numero.'/V/')){
            mkdir('./logs/avance'.$this->ciclo->numero.'/V/');
        }

        if(!file_exists('./logs/avance'.$this->ciclo->numero.'/N/')){
            mkdir('./logs/avance'.$this->ciclo->numero.'/N/');
        }


        $grupos = new Grupos();
        //$grupos = $grupos->find("ciclos_id='" . $this->ciclo->id . "' AND grado='6' AND letra='A' AND turno='M' ORDER BY grado,letra,turno");
        $grupos = $grupos->find("ciclos_id='" . $this->ciclo->id . "' ORDER BY grado,letra,turno");
        
        foreach ($grupos as $grupo) {
            $alumnos = $grupo->alumnos();
            foreach ($alumnos as $alumno) {
                $this->avanceAlumno($alumno);
            }
            if(count($alumnos)>0){
                ob_end_clean();
                ob_start();

                $reporte = new XLSCierre($this->ciclo,$grupo,$alumnos, $this->ANTERIOR, $this->CURSADOS, $this->_CAL, $this->SITUACIONES,$this->DATOSARTICULOS);
                $n = $reporte->getNombre();

                if(!file_exists('./logs/avance'.$this->ciclo->numero.'/'.$grupo->turno."/".$grupo->grado."/")){
                    mkdir('./logs/avance'.$this->ciclo->numero.'/'.$grupo->turno."/".$grupo->grado."/");
                }
                $f = fopen('./logs/avance'.$this->ciclo->numero.'/'.$grupo->turno."/".$grupo->grado."/". $n, "w");
                $reporte->close();
                fwrite($f, ob_get_contents());
                fclose($f);

            }
        }
        ob_end_clean();


    }

    private function obtenArticulo($alumcurso) {
        $articulo = new Alumnosconarticulo();
        $articulo = $articulo->articuloCurso($alumcurso->id);
        if ($articulo->id != '') {
            switch ($articulo->clave) {
                case 'rep' :
                    return $this->ARTICULOS['art33'];
                    break;

                case 'art33' :
                    return $this->ARTICULOS['art34'];
                    break;

                case 'art34' :
                    return $this->ARTICULOS['baja'];
                    break;

                case 'art35' :
                    return $this->ARTICULOS['baja'];
                    break;

                default :
                    return null;
                    break;

            }
        } else {
            return $this->ARTICULOS['rep'];
        }
    }

    private function aprobo($alumno_id, $curso_id) {
        $calificaciones = new Calificaciones();
        $calificaciones = $calificaciones->find("cursos_id='" . $curso_id . "' AND alumnos_id='" . $alumno_id . "' ORDER BY oportunidades_id");
        $this->_CAL[$alumno_id][$curso_id] = $calificaciones;
        foreach ($calificaciones as $calificacion) {
            if ($calificacion->valor > 59 || $calificacion->valor == 'A') {
                $this->CALIFICACIONES[$alumno_id][$curso_id] = $calificacion->valor;
                return true;
            }

        }

        //if (count($calificaciones) == 0)
        //    return false; //para pruebas

        return false;
    }

    /*//Obtiene los proximos cursos del alumno.
        private function proximosCursos1($alumno_id,$reprobados,$grupo){
        $proximos=array();
        $reprob=array_merge(array(),$reprobados);
        if(count($reprob)<3){
        $grupo_siguiente=new Grupos();
        $grupo_siguiente=$grupo_siguiente->find_first(
        "ciclos_id='".$this->SIGUIENTE->id."' AND
        grado='".($grupo->grado+1)."' AND
        letra='".$grupo->letra."' AND
        turno='".$grupo->turno."'  AND oferta_id=".$grupo->oferta_id
        );


        $cc=$grupo_siguiente->cursos();

        $cursos=array();
        foreach($cc as $c){
        $cursos[$c->materia_id]=$c;
        }

        $materias=new Materias();
        $materias=$materias->find("semestre='".($grupo->grado+1)."'");
        foreach($materias as $m){
        if($m->tipo=="OBL" || $m->tipo=="TLR"){
        if(array_key_exists($m->id,$cursos)){
        $proximos[]=$cursos[$m->id];
        }

        }


        }


        }

        $grupo_siguiente=new Grupos();
        $grupo_siguiente=$grupo_siguiente->find_first(
        "ciclos_id='".$this->SIGUIENTE->id."' AND
        grado='".$grupo->grado."' AND
        letra='".$grupo->letra."' AND
        turno='".$grupo->turno."' AND oferta_id=".$grupo->oferta_id
        );
        $cc=$grupo_siguiente->cursos();

        $cursos=array();
        foreach($cc as $c){
        $cursos[$c->materia_id]=$c;
        }

        foreach($reprob as $k=>$reprobado){
        if(array_key_exists($reprobado->materia_id,$cursos)){
        $proximos[]=$cursos[$reprobado->materia_id];
        unset($reprob[$k]);
        }
        }


        foreach($reprob as $reprobado){
        $curso=$this->buscaCurso($reprobado->materia_id,$grupo);
        if($curso->id==''){
        //Posible error
        }else{
        $proximos[]=$curso;
        }
        }



        return $proximos;

        }
        */

    public function proximosCursos($alumno, $aprobados, $reprob, $grupo) {
        if ($alumno->bachillerato() == 2) {
            return $this->proximosCursosCompetencias($alumno->id, $aprobados, $reprob, $grupo);
        } else {
            return $this->proximosCursosGeneral($alumno->id, $aprobados, $reprob, $grupo);
        }
    }

    
    //Obtiene los proximos cursos del alumno para Bachillerato Competencias.
    private function proximosCursosCompetencias($alumno_id, $aprobados, $reprob, $grupo) {
        $proximos = $this->proximosCursosGeneral($alumno_id, $aprobados, $reprob, $grupo);
        
        if(is_array($proximos)){
            //Retiro las materias que pertenen a las TAES
            $prx = array();            
            foreach($proximos as $materia_id => $curso){
                $trayectoriasespecializantesmateria = new Trayectoriasespecializantesmaterias();
                if(!$trayectoriasespecializantesmateria->exists("materias_id='".$materia_id."'")){
                    $prx[$materia_id] = $curso;
    
                }
            }

        return $prx;
        }else{
            return array();    
        }

    }
    private function proximosCursosCompetencias1($alumno_id, $aprobados, $reprob, $grupo) {
        $proximos = array ();
        $grupos = array ();
        $cursosGrupo = array ();

        $apb_array = array();
        //Creo el arreglos de ids de aprobadas para realizar busquedas
        foreach ($aprobados as $ap) {
            $materia_ap = $ap->materia();
            $apb_array[$materia_ap->id] = $materia_ap->id;
        }
        $reprobadas = array();

        $cuentaReprobadas = count($reprob);
        foreach ($reprob as $k => $reprobado) { //por cada reprobada se buscara un curso donde inscibir al alumno
            $g = $reprobado->grupo();
            $grupo_siguiente = $grupos[$g->grado][$g->letra][$g->turno]; //buscamos si el grupo ya esta en memoria
            if ($grupo_siguiente == null) { //sino lo sacamos
                if ($cuentaReprobadas < 5) {
                    if ($g->turno == 'M') {
                        $turno = 'V';
                    } else {
                        $turno = 'M';
                    }
                }else{
                    $turno = $g->turno;
                }

                $grupo_siguiente = new Grupos();
                $grupo_siguiente = $grupo_siguiente->find_first("ciclos_id='" .
                $this->SIGUIENTE->id . "' AND
                                             grado='" . ($g->grado) . "' AND
                                             letra='" . $g->letra . "' AND
                                             turno='" . $turno . "'  AND oferta_id=" . $g->oferta_id);

                $grupos[$grupo_siguiente->grado][$grupo_siguiente->letra][$grupo_siguiente->turno] = $grupo_siguiente;

            }

            if ($grupo_siguiente->id != '') {
                $cursos = $cursosGrupo[$grupo_siguiente->grado][$grupo_siguiente->letra][$grupo_siguiente->turno];
                if ($cursos == null) { //reviso si los cursos del grupo ya estan memoria
                    $cc = $grupo_siguiente->cursos();

                    $cursos = array ();

                    foreach ($cc as $c) {
                        $cursos[$c->materia_id] = $c;
                    }

                    $cursosGrupo[$grupo_siguiente->grado][$grupo_siguiente->letra][$grupo_siguiente->turno] = $cursos;
                }
                    
                if (array_key_exists($reprobado->materia_id, $cursos)) { //obtengo el curso a repetir de los cursos del grupo
                    $proximos[$reprobado->materia_id] = $cursos[$reprobado->materia_id]; //se agrega a los proximos
                    unset ($reprob[$k]); //lo elimino del arreglo de los reprobados
                }
            }

        } //Todos los cursos a cursar uncluidos aquellos a repetir

        if ($grupo->grado < 6) {
            if ($cuentaReprobadas < 5) {
                $grupo_siguiente = new Grupos();
                $grupo_siguiente = $grupo_siguiente->find_first("ciclos_id='" .
                $this->SIGUIENTE->id . "' AND
                                             grado='" . ($grupo->grado + 1) . "' AND
                                             letra='" . $grupo->letra . "' AND
                                             turno='" . $grupo->turno . "'  AND oferta_id=" . $grupo->oferta_id);
                    
                    
                if ($grupo_siguiente->id != '') {
                    //reviso si existen materias sin prerrequisitos para el siguiente nivel
                    $sinprerrequisistos = new Materias();
                    $sinprerrequisistos = $sinprerrequisistos->sinprerrequisitos(($grupo->grado + 1)); //materias si prerrequisitos

                    $cursos = $grupo_siguiente->cursos();
                    $asociativo = array (); //cursos del siguiente grupo con las llaves como id de las materias
                    foreach ($cursos as $c1) {
                        $asociativo[$c1->materia_id] = $c1;
                    }

                    foreach ($sinprerrequisistos as $mat) {
                        if (array_key_exists($mat->id, $asociativo)) {
                            $proximos[$mat->id] = $asociativo[$mat->id];
                        }
                    }

                }
                
                //reviso las reprobadas para obtener las siguientes segun los prerrequisitos
                foreach ($reprob as $ap) {
                    $materia = $ap->materia();
                    $pre = $materia->prerrequisitodemateriasasociativo(); //obtengo las materias de las cuales la materia es prerrequisito
                    foreach ($pre as $pr_id => $pr) {
                        if (!array_key_exists($pr_id, $proximos)) { //la materia no se encuentra en los proximos,se buscara curso de la materia para agregar a los proximos
                            $materiapre = new Materias();
                            $materiapre = $materiapre->find($pr_id);
                            $grupomateria = $ap->grupo(); //grupo de la materia aprobada
                            $grupocurso = new Grupos();
                            $grupocurso = $grupocurso->find_first("ciclos_id='" .
                            $this->SIGUIENTE->id . "' AND
                                                             grado='" . $materiapre->semestre . "' AND
                                                             letra='" . $grupomateria->letra . "' AND
                                                             turno='" . $grupomateria->turno . "'  AND oferta_id=" . $grupomateria->oferta_id);
                            if ($grupocurso->id != "") {
                                $cc1 = $grupocurso->cursos();

                                foreach ($cc1 as $c1) {
                                    if ($materiapre->id == $c1->materia_id) {
                                        //revisar si no esta en las aprobadas
                                        if(!array_key_exists($materiapre->id,$apb_array)){
                                            $proximos[$materiapre->id] = $c1; //se agrega el curso para la materia
                                            break;
                                        }
                                    }
                                }

                            }

                        }
                    }                    
                }
                //reviso las aprobadas para obtener las siguientes segun los prerrequisitos

                foreach ($aprobados as $ap) {
                    $materia = $ap->materia();
                    $pre = $materia->prerrequisitodemateriasasociativo(); //obtengo las materias de las cuales la materia es prerrequisito
                    foreach ($pre as $pr_id => $pr) {
                        if (!array_key_exists($pr_id, $proximos)) { //la materia no se encuentra en los proximos,se buscara curso de la materia para agregar a los proximos
                            $materiapre = new Materias();
                            $materiapre = $materiapre->find($pr_id);
                            $grupomateria = $ap->grupo(); //grupo de la materia aprobada
                            $grupocurso = new Grupos();
                            $grupocurso = $grupocurso->find_first("ciclos_id='" .
                            $this->SIGUIENTE->id . "' AND
                                                             grado='" . $materiapre->semestre . "' AND
                                                             letra='" . $grupomateria->letra . "' AND
                                                             turno='" . $grupomateria->turno . "'  AND oferta_id=" . $grupomateria->oferta_id);
                            if ($grupocurso->id != "") {
                                $cc1 = $grupocurso->cursos();

                                foreach ($cc1 as $c1) {
                                    if ($materiapre->id == $c1->materia_id) {
                                        //revisar si no esta en las aprobadas
                                        if(!array_key_exists($materiapre->id,$apb_array)){
                                            $proximos[$materiapre->id] = $c1; //se agrega el curso para la materia
                                            break;
                                        }
                                    }
                                }

                            }

                        }
                    }
                }

            }
        }

        //Retiro las materias que pertenen a las TAES

        $prx = array();
        foreach($proximos as $materia_id => $curso){
            $trayectoriasespecializantesmateria = new Trayectoriasespecializantesmaterias();
            if(!$trayectoriasespecializantesmateria->exists("materias_id='".$materia_id."'")){
                $prx[$materia_id] = $curso;

            }
        }

        return $prx;
    }

    //Obtiene los proximos cursos del alumno para Bachillerato General.
    private function proximosCursosGeneral($alumno_id, $aprobados, $reprob, $grupo) {
        $proximos = array ();
        if ($grupo->grado < 6) { //Si el grupo es menor a 6
            if (count($reprob) < 3) { //Si las reprobadas es menor a 3 avanza al siguiente grado
                $grupo_siguiente = new Grupos();
                $grupo_siguiente = $grupo_siguiente->find_first("ciclos_id='" .
                $this->SIGUIENTE->id . "' AND
                                             grado='" . ($grupo->grado + 1) . "' AND
                                             letra='" . $grupo->letra . "' AND
                                             turno='" . $grupo->turno . "'  AND oferta_id=" . $grupo->oferta_id);

                //$grupos[$grupo_siguiente->grado][$grupo_siguiente->letra]=$grupo_siguiente;
                $cc = $grupo_siguiente->cursos();

                $cursos = array ();
                foreach ($cc as $c) {
                    $cursos[$c->materia_id] = $c;
                }

                $materias = new Materias();
                $materias = $materias->find("semestre='" . ($grupo->grado + 1) . "'");
                foreach ($materias as $m) {
                    if ( ( $m->semestre<5 && ($m->tipo == "OBL" || $m->tipo == "TLR"))
                    ||
                    ($m->semestre>4 && $m->tipo == "OBL" )
                    ) {
                        if (array_key_exists($m->id, $cursos)) {

                            //$agrega = true;
                            //$pre = $m->prerrequisitosasociativo();
                            //    foreach ($pre as $pr_id => $pr) { //reviso si no se reprobo un prerrequisito

                            //        if (array_key_exists($pr_id, $reprob)) { //si el prerrequisito se encuentra en las reprobadas no se puede agregar a los proxmos
                            //            $agrega = false;
                            //            break;
                            //        }
                            //    }

                            //    if ($agrega) //Sino hace falta ningun prerrequisito se agrega
                            $proximos[$m->id] = $cursos[$m->id];
                    }

                }
            }
            //Revisando los siguientes cursos de las aprobadas , si es prerrequisito de algun curso este se agrega al arreglo de los proximos
            $llavesaprobadas=array_keys($aprobados);
            $llavesreprobadas=array_keys($reprob);
            foreach ($aprobados as $ap) {
                $materia = $ap->materia();
                if (($materia->tipo == "OBL" || $materia->tipo == "TLR") && $materia->semestre < 6) { //Si es de tipo obligatoria o taller y no es de 6 se revisa
                    $pre = $materia->prerrequisitodemateriasasociativo(); //obtengo las materias de las cuales la materia es prerrequisito
                    foreach ($pre as $pr_id => $pr) {
                        $mt=new Materias();
                        $mt=$mt->find($pr_id);
                        if (($grupo->grado+1)==$mt->semestre && !array_key_exists($pr_id, $proximos) && ( !in_array($pr_id,$llavesaprobadas) && !in_array($pr_id,$llavesreprobadas) )) { //la materia no se encuentra en los proximos,se buscara curso de la materia para agregar a los proximos
                            $materiapre = new Materias();
                            $materiapre = $materiapre->find($pr_id);
                            if ($materiapre->tipo == "OBL" || $materiapre->tipo == "TLR") {
                                $grupomateria = $ap->grupo(); //grupo de la materia aprobada
                                $grupocurso = new Grupos();
                                $grupocurso = $grupocurso->find_first("ciclos_id='" .
                                $this->SIGUIENTE->id . "' AND
                                                         grado='" . $materiapre->semestre . "' AND
                                                         letra='" . $grupomateria->letra . "' AND
                                                         turno='" . $grupomateria->turno . "'  AND oferta_id=" . $grupomateria->oferta_id);
                                if ($grupocurso->id != "") {
                                    $cc1 = $grupocurso->cursos();

                                    foreach ($cc1 as $c1) {
                                        if ($materiapre->id == $c1->materia_id) {
                                            $proximos[$materiapre->id] = $c1; //se agrega el curso para la materia
                                            break;
                                        }
                                    }

                                }
                            }
                        }
                    }
                }

            }
        }
    } //Obtengo todos los cursos del siguiente grado, ahora a revisar las reprobadas



    $grupos = array ();
    $cursosGrupo = array ();
    foreach ($reprob as $k => $reprobado) { //por cada reprobada se buscara un curso donde inscibir al alumno
        $g = $reprobado->grupo();
        $grupo_siguiente = $grupos[$g->grado][$g->letra][$g->turno]; //buscamos si el grupo ya esta en memoria
        if ($grupo_siguiente == null) { //sino lo sacamos
            $turno = '';

            if ($g->oferta_id == $this->BACHILLERATOGENERAL) {
                $turno = $g->turno;
            } else {
                if ($g->turno == 'M') {
                    $turno = 'V';
                } else {
                    $turno = 'M';
                }

            }
            $grupo_siguiente = new Grupos();
            $grupo_siguiente = $grupo_siguiente->find_first("ciclos_id='" .
            $this->SIGUIENTE->id . "' AND
                                             grado='" . ($g->grado) . "' AND
                                             letra='" . $g->letra . "' AND
                                             turno='" . $turno . "'  AND oferta_id=" . $g->oferta_id);

            $grupos[$grupo_siguiente->grado][$grupo_siguiente->letra][$grupo_siguiente->turno] = $grupo_siguiente;

        }

        $materia=$reprobado->materia();

        if($g->grado>4 && $materia->tipo!="OBL"){ //Si es optativa,taller o programa de 5 o 6 se tiene que buscar el curso
            $encontrada=false;
            if ($grupo_siguiente->id != '') {
                $cursos = $cursosGrupo[$grupo_siguiente->grado][$grupo_siguiente->letra][$grupo_siguiente->turno];
                if ($cursos == null) { //reviso si los cursos del grupo ya estan memoria
                    $cc = $grupo_siguiente->cursos();

                    $cursos = array ();

                    foreach ($cc as $c) {
                        $cursos[$c->materia_id] = $c;
                    }

                    $cursosGrupo[$grupo_siguiente->grado][$grupo_siguiente->letra][$grupo_siguiente->turno] = $cursos;
                }

                if (array_key_exists($reprobado->materia_id, $cursos)) { //obtengo el curso a repetir de los cursos del grupo
                    $proximos[$reprobado->materia_id] = $cursos[$reprobado->materia_id]; //se agrega a los proximos
                    unset ($reprob[$k]); //lo elimino del arreglo de los reprobados
                    $encontrada=true;
                }
            }

            if(!$encontrada){//No se oferto en el mismo grupo buscarla en los demas
                $cursos=new Cursos();
                $cursos=$cursos->find_all_by_sql(
                        "SELECT cursos.*
                        FROM
                        `cursos`
                        INNER JOIN grupos ON cursos.grupos_id=grupos.id
                         WHERE grupos.ciclos_id='".$this->SIGUIENTE->id."' AND materias_id='".$materia->id."' AND grupos.turno='".$g->turno."';"
                         );
                         if(is_array($cursos) && count($cursos)>0){
                             $curso=$cursos[0];
                             if($curso->id!=''){
                                 $encontrada=true;
                                 $curso->materia_id=$curso->materias_id;
                                 $curso->materia=$materia->nombre;
                                 $proximos[$reprobado->materia_id] = $curso; //se agrega a los proximos
                                 unset ($reprob[$k]); //lo elimino del arreglo de los reprobados
                             }
                         }


            }



        }else{

            if ($grupo_siguiente->id != '') {
                $cursos = $cursosGrupo[$grupo_siguiente->grado][$grupo_siguiente->letra][$grupo_siguiente->turno];
                if ($cursos == null) { //reviso si los cursos del grupo ya estan memoria
                    $cc = $grupo_siguiente->cursos();

                    $cursos = array ();

                    foreach ($cc as $c) {
                        $cursos[$c->materia_id] = $c;
                    }

                    $cursosGrupo[$grupo_siguiente->grado][$grupo_siguiente->letra][$grupo_siguiente->turno] = $cursos;
                }

                if (array_key_exists($reprobado->materia_id, $cursos)) { //obtengo el curso a repetir de los cursos del grupo
                    $proximos[$reprobado->materia_id] = $cursos[$reprobado->materia_id]; //se agrega a los proximos
                    unset ($reprob[$k]); //lo elimino del arreglo de los reprobados
                }else{//como no existe el curso se crea
                    $profesor=new Profesores();
                    $profesor=$profesor->staff();
                    $curso = new Cursos();
                    $curso->estado_id='1';
                    $curso->grupos_id=$grupo_siguiente->id;
                    $curso->materias_id=$reprobado->materia_id;
                    $curso->profesores_id=$profesor->id;
                    $curso->observaciones='El avance de ciclo agrego el curso por que este no se estaba ofertando.';
                    $curso->inicio='0000-00-00';

                    if($curso->save()){
                        $curso->materia=$curso->verMateriaNombre();
                        $curso->materia_id=$reprobado->materia_id;
                        $myLog = new Logger('GruposCierre.log');
                        $myLog->log("Curso creado por necesidad: ".$curso->verGrupo().' '.$curso->verMateriaNombre());
                        $myLog->close();
                        $proximos[$reprobado->materia_id] = $curso; //se agrega a los proximos
                        unset ($reprob[$k]); //lo elimino del arreglo de los reprobados
                    }else{
                        $myLog = new Logger('GruposCierre.log');
                        $myLog->log("ERROR Curso creado por necesidad: ".$curso->verGrupo().' '.$curso->verMateriaNombre());
                        $myLog->close();

                    }
                }
            }
        }

    } //Todos los cursos a cursar uncluidos aquellos a repetir

    /***********
        if(count($reprob)<3){
        $grupo_siguiente=new Grupos();
        $grupo_siguiente=$grupo_siguiente->find_first(
        "ciclos_id='".$this->SIGUIENTE->id."' AND
        grado='".($grupo->grado+1)."' AND
        letra='".$grupo->letra."' AND
        turno='".$grupo->turno."'  AND oferta_id=".$grupo->oferta_id
        );


        $cc=$grupo_siguiente->cursos();

        $cursos=array();
        foreach($cc as $c){
        $cursos[$c->materia_id]=$c;
        }

        $materias=new Materias();
        $materias=$materias->find("semestre='".($grupo->grado+1)."'");
        foreach($materias as $m){
        if($m->tipo=="OBL" || $m->tipo=="TLR"){
        if(array_key_exists($m->id,$cursos)){
        $proximos[]=$cursos[$m->id];
        }

        }
        }


        }

        $grupo_siguiente=new Grupos();
        $grupo_siguiente=$grupo_siguiente->find_first(
        "ciclos_id='".$this->SIGUIENTE->id."' AND
        grado='".$grupo->grado."' AND
        letra='".$grupo->letra."' AND
        turno='".$grupo->turno."' AND oferta_id=".$grupo->oferta_id
        );
        $cc=$grupo_siguiente->cursos();

        $cursos=array();
        foreach($cc as $c){
        $cursos[$c->materia_id]=$c;
        }

        foreach($reprob as $k=>$reprobado){
        if(array_key_exists($reprobado->materia_id,$cursos)){
        $proximos[]=$cursos[$reprobado->materia_id];
        unset($reprob[$k]);
        }
        }


        foreach($reprob as $reprobado){
        $curso=$this->buscaCurso($reprobado->materia_id,$grupo);
        if($curso->id==''){
        //Posible error
        }else{
        $proximos[]=$curso;
        }
        }


        *****************/

    //revisar que no se encuentre algun curso que no cumpla con los prerrequisitos
    //$materias = array_keys($proximos);
    //$prerrequisistos = array ();
    //foreach ($proximos as $proximo) {
    //    $materia = $proximo->materia();
        //    $pre = $materia->prerrequisitosasociativo();
        //    $prerrequisistos[$materia->id] = $pre;
        //}

        //foreach ($proximos as $llave => $proximo) {
        //    foreach ($prerrequisistos as $mat => $pre) {
        //        if (array_key_exists($llave, $pre)) {
        //            unset ($proximos[$mat]);
        //        }
        //    }
        //}

        $myLog = new Logger('GruposCierre.log');
        $myLog->log("PROXIMOS: ".$alumno_id.' '.count($proximos));
        $myLog->close();
        return $proximos;

    }

    public function avanceAlumno($alumno) {
        $this->SITUACIONES[$alumno->id]['situacion'] = $alumno->situacion();
        $this->SITUACIONES[$alumno->id]['promedio'] = $alumno->promedio;
        $this->SITUACIONES[$alumno->id]['aprobadas'] = $alumno->aprobadas;

        if($alumno->situaciones_id!=$this->BAJA){

            $grupo = $alumno->obtenerGrupo();

            $cursos = $alumno->cursos($grupo->ciclos_id);
            $aprobados = array ();
            $reprobados = array ();
            $articulos = array ();
            $baja = false;
            $this->ANTERIOR[$alumno->id] = $cursos;
            foreach ($cursos as $curso) {
                if (!$this->aprobo($alumno->id, $curso->id)) {
                    $reprobados[$curso->materia_id] = $curso;
                    $alumcurso = new Alumnoscursos();
                    $alumcurso = $alumcurso->find_first("cursos_id='" . $curso->id . "' AND alumnos_id='" . $alumno->id . "'");
                    if ($alumcurso->id != "") {
                        $art = $this->obtenArticulo($alumcurso);
                        $articulos[$curso->materia_id] = $art;

                        if ($art->clave == 'baja') {
                            $baja = true;
                        }

                    }
                } else {
                    $aprobados[$curso->materia_id] = $curso;
                }

            }

            $this->DATOSARTICULOS[$alumno->id]=$articulos;

            if (count($reprobados) > 0) {
                if ($baja) {
                    $alumno->situaciones_id = $this->BAJA;

                } else {
                    //cambiar status de alumno a irregular
                    $alumno->situaciones_id = $this->IRREGULAR;
                }
            }
            $alumnbach=$alumno->bachillerato();
            if ( ($alumnbach=="1" && count($reprobados) >= 3 )  || ($alumnbach==2 && count($reprobados) >= 5) ) { //Repite curso
                $alumno->situaciones_id = $this->REPETIDOR;
                $g = $this->inscripcionGrupo($alumno->id, $grupo, $reprobados, true);
                $proximos = $this->proximosCursos($alumno, $aprobados, $reprobados, $grupo);
            } else { //avanza
                if ($grupo->grado == 6) {// es de 6 semestre
                    if (count($reprobados) == 0) { // no reprobo ni un curso por lo tanto egresa
                        //cambiar status de alumno a egresado
                        $alumno->situaciones_id = $this->EGRESADO;
                    } else { //reprobo por lo menos 1 curso repite curso
                        $g = $this->inscripcionGrupo($alumno->id, $grupo, $reprobados, true);
                        $proximos = $this->proximosCursos($alumno, $aprobados, $reprobados, $grupo);
                        $alumno->situaciones_id = $this->REPETIDOR;
                    }
                } else { // no es de 6 grado
                    $g = $this->inscripcionGrupo($alumno->id, $grupo);
                    $proximos = $this->proximosCursos($alumno, $aprobados, $reprobados, $grupo);


                    if (count($reprobados) == 0) {
                        $alumno->situaciones_id = $this->REGULAR;
                    }
                }

            }


            if(is_array($proximos)){
                foreach ($proximos as $proximo) {
                    $this->inscripcionCurso($alumno->id, $proximo->id, $articulos[$proximo->materia_id]);
                    $ccc = new Cursos();
                    $ccc->find($proximo->id);
                }
            }

            //if(true || $grupo->grado==1 || $grupo->grado==4 || $grupo->grado==5){
            $this->promedio($alumno, $aprobados, $proximos);
            //}
            $alumno->save();
            $this->CURSADOS[$alumno->id] = $proximos;
        }else{
                
            $cursos = $alumno->cursos($grupo->ciclos_id);
            $this->ANTERIOR[$alumno->id] = $cursos;
            $this->CURSADOS[$alumno->id] = array();
            
        }

    }

    //Inscribe al alumno al curso indicado
    private function inscripcionCurso($alumno_id, $curso_id, $articulo = null) {
        $inscripcion = new Alumnoscursos();
        $inscripcion->alumnos_id = $alumno_id;
        $inscripcion->cursos_id = $curso_id;
        $inscripcion->save();

        if ($articulo != null && $articulo->id != '') {
            $art = new Alumnosconarticulo();
            $art->articulos_id = $articulo->id;
            $art->alumnoscursos_id = $inscripcion->id;
            $art->save();
        }
    }

    private function alumnoGrupos($alumno) {
        $grupos = new Grupos();
        $grupos->find_all_by_sql("SELECT grupos.* " .
        "FROM " .
        " grupos" .
        " INNER JOIN alumnosgrupo ON alumnosgrupo.grupos_id=grupos.id" .
        " WHERE alumnosgrupo.alumnos_id=" . $alumno->id . " " .
        " ORDER BY grupos.grado ASC,grupos.ciclos_id ASC ");
        $gps = array ();
        foreach ($grupos as $g) {
            $gps[$g->grado] = $g->oferta_id;
        }

        return $gps;
    }

    /*
     * con el archivo promedio.ini
     * private function promedio($alumno,$cursos,$proximos){
        $myLog = new Logger('cierre.log');
        $myLog->begin();
        $grupo=$alumno->obtenerGrupoPorCiclo($this->ciclo->id);

        $gps=$this->alumnoGrupos($alumno);
        $myLog->log("*****************PROMEDIO********************");
        //$myLog->log("alumno_id: ".$alumno->id);
        //$myLog->log("alumno_codigo: ".$alumno->codigo);
        $noCal=0;
        for($i=1;$i<$grupo->grado;$i++){
        $g=$gps[$i];

        if($g!=null)
        $o=$g;
        else
        $o=$grupo->oferta_id;

        $noCal+=$this->MATERIASPROMEDIO[$o][$i];
        $myLog->log("No cal ".$i." : ".$this->MATERIASPROMEDIO[$o][$i]);
        }

        $myLog->log("No cal total: ".$noCal);
        //revisar si esta repitiendo algun curso y si es asi descontarlo de las materia
        foreach($proximos as $prox){
        if($prox->materia_semestre < $grupo->grado && $prox->materiaTipo == 'TLR'){
        $noCal--;
        $myLog->log("Nocal-- ".$prox->materia.", ".$prox->materiaTipo.", ".$prox->materia_semestre.", ".$grupo->grado);
        }
        }
        $myLog->log("No cal totales: ".$noCal);
        $promedio=floatval($alumno->promedio);
        $noCal=intval($noCal,10);

        if($noCal=='')$noCal=0;

        $aprobadas=intval($alumno->aprobadas,10);
        $myLog->log("valor=(".$aprobadas."-".$noCal.") * ".$promedio);

        $valor=($aprobadas-$noCal) * $promedio;
        $myLog->log("promedio: ".$promedio);
        $myLog->log("aprobadas: ".$aprobadas);
        $myLog->log("valor: ".$valor);
        $aprobadas2= $aprobadas;

        $valor2=$valor;
        $cantidad=0;
        foreach($cursos as $c){
        $cal=$this->CALIFICACIONES[$alumno->id][$c->id];
        $myLog->log("curso: ".$c->verMateriaNombre()." ".$c->id.", cal: ".$cal);
        if($cal!='A' && $cal!='NA'){
        $myLog->log($c->verMateriaNombre()." floatval: ".floatval($cal));
        $valor2+=floatval($cal);
        $cantidad++;
        }
        $aprobadas2++;

        }

        $promedio=$valor2/($cantidad+($aprobadas-$noCal));
        $myLog->log("promedio=".$valor2."/"."(".$cantidad."+(".$aprobadas."-".$noCal."))");
        $alumno->promedio=$promedio;
        $alumno->aprobadas=$aprobadas2;

        $myLog->log("Nuevos valores");

        $myLog->log("promedio: ".$promedio);
        $myLog->log("aprobadas: ".$aprobadas2);
        $myLog->log("valor: ".$valor2);
        $myLog->log("cantidad: ".$cantidad);
        $myLog->log("**************PROMEDIOFin******************");
        $myLog->commit();
        $myLog->close();

        }
        */

    private function promedio($alumno, $cursos, $proximos) {
        $myLog = new Logger('PromedioCierre.log');
        $myLog->begin();
        $grupo = $alumno->obtenerGrupoPorCiclo($this->ciclo->id);

        $gps = $this->alumnoGrupos($alumno);
        $myLog->log("*****************PROMEDIO********************");

        $promedio = floatval($alumno->promedio);
        $aprobadas = intval($alumno->aprobadas, 10);
        $myLog->log("valor=" . $aprobadas . " * " . $promedio);

        $valor = $aprobadas * $promedio;
        $myLog->log("promedio: " . $promedio);
        $myLog->log("aprobadas: " . $aprobadas);
        $myLog->log("valor: " . $valor);
        $aprobadas2 = $aprobadas;

        $valor2 = $valor;
        $cantidad = 0;
        foreach ($cursos as $c) {
            $cal = $this->CALIFICACIONES[$alumno->id][$c->id];
            $myLog->log("curso: " . $c->verMateriaNombre() . " " . $c->id . ", cal: " . $cal);
            if ($cal != 'A' && $cal != 'NA') {
                $myLog->log($c->verMateriaNombre() . " floatval: " . floatval($cal));
                $valor2 += floatval($cal);
                $cantidad++;
                $aprobadas2++;
            }

        }

        $tt = $cantidad + $aprobadas;
        if ($tt > 0) {
            $promedio = $valor2 / ($tt);
            $myLog->log("promedio=" . $valor2 . "/" . "(" . $cantidad . "+" . $aprobadas . ")");
        }
        $alumno->promedio = $promedio;
        $alumno->aprobadas = $aprobadas2;

        $myLog->log("Nuevos valores");

        $myLog->log("promedio: " . $promedio);
        $myLog->log("aprobadas: " . $aprobadas2);
        $myLog->log("valor: " . $valor2);
        $myLog->log("cantidad: " . $cantidad);
        $myLog->log("**************PROMEDIOFin******************");
        $myLog->commit();
        $myLog->close();

    }

    private function inscripcionGrupo($alumno_id, $grupo, $reprobados = array (), $rep = false) {

        foreach ($reprobados as $r) {
            $s = $grupo->grado + 1;
            if ($s - $r->materia_semestre >= 2) {
                $rep = true;
                break;
            }
        }

        $grupo_siguiente = new Grupos();
        $grado='';
        if ($rep) {
            $grupo_siguiente = $grupo_siguiente->find_first("ciclos_id='" .
            $this->SIGUIENTE->id . "' AND
                                         grado='" . $grupo->grado . "' AND
                                         letra='" . $grupo->letra . "' AND
                                         turno='" . $grupo->turno . "' AND oferta_id=" . $grupo->oferta_id);
            $grado=$grupo->grado;
        } else {

            $grupo_siguiente = $grupo_siguiente->find_first("ciclos_id='" .
            $this->SIGUIENTE->id . "' AND
                                         grado='" . ($grupo->grado + 1) . "' AND
                                         letra='" . $grupo->letra . "' AND
                                         turno='" . $grupo->turno . "' AND oferta_id=" . $grupo->oferta_id);
            $grado=$grupo->grado+1;
        }

        if(!$grupo_siguiente || $grupo_siguiente->id==''){
            $grupo_siguiente=new Grupos();
            $grupo_siguiente->ciclos_id=$this->SIGUIENTE->id;
            $grupo_siguiente->grado=$grado;
            $grupo_siguiente->letra=$grupo->letra;
            $grupo_siguiente->turno=$grupo->turno;
            $grupo_siguiente->oferta_id=$grupo->oferta_id;
            $grupo_siguiente->save();
            $myLog = new Logger('GruposCierre.log');
            $myLog->log("Creado por necesidad: ". $grupo_siguiente->ver());
            $myLog->close();

        }

        $grupo = new Alumnosgrupo();
        if (!$grupo->exists("alumnos_id=" . $alumno_id . " AND grupos_id=" . $grupo_siguiente->id)) {

            $grupo = new Alumnosgrupo();
            $grupo->alumnos_id = $alumno_id;
            $grupo->grupos_id = $grupo_siguiente->id;
            $grupo->save();

        }

        return $grupo_siguiente;
    }

    private function buscaCurso($materia_id, $grupo) {
        $curso = new Cursos();
        $curso = $curso->find_by_sql("SELECT materias.nombre,grupos.*,cursos.* FROM
                                    cursos
                                    INNER JOIN materias ON cursos.materias_id=materias.id
                                    INNER JOIN grupos ON cursos.grupos_id=grupos.id
                                    WHERE grupos.ciclos_id='" .
        $this->SIGUIENTE->id . "' AND materias.id='" . $materia_id . "' AND grupos.turno='" . $grupo->turno . "' AND grupos.oferta_id=" . $grupo->oferta_id);

        if ($curso->id == '') {
            $curso = new Cursos();
            $curso = $curso->find_by_sql("SELECT materias.nombre,grupos.*,cursos.* FROM
                                        cursos
                                        INNER JOIN materias ON cursos.materias_id=materias.id
                                        INNER JOIN grupos ON cursos.grupos_id=grupos.id
                                        WHERE grupos.ciclos_id='" .
            $this->SIGUIENTE->id . "' AND materias.id='" . $materia_id . "' AND grupos.oferta_id=" . $grupo->oferta_id);
        }

        return $curso;

    }

}
?>
