<?php


/*
 * Created on 05/01/2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class Avance1 {
    private $alumno;
    private $cursos;
    private $calificaciones;
    private $ciclo;
    private $cicloAvance;
/*
        if(strToUpper($this->grupo->turno)=="M")
        $turno="V";
        else if(strToUpper($this->grupo->turno)=="V")
        $turno="M";
        else if(strToUpper($this->grupo->turno)=="N")
        $turno="V";


        foreach($reprobados as $curso){

            $curso2=$curso;
            $inscribe=new Alumnoscursos();
            $inscribe->alta($this->alumno->id,$curso2->id);
        }
        */    private $grupo;
    public function Avance1($c, $ciclo) {
        $this->ciclo = $ciclo;
        $this->cicloAvance = new Ciclos();
        $this->cicloAvance = $this->cicloAvance->find("numero='" . $this->ciclo->siguiente() . "'");

        $cals = $ciclo->calificacionesTodas();
        $this->calificaciones = array ();
        foreach ($cals as $cal) {
            $this->calificaciones[$cal['alumnos_id']][$cal['cursos_id']][$cal['oportunidades_id']] = $cal;
        }
    }

    public function Alumno($alumno) {
        $this->alumno = $alumno;
        $this->grupo = $this->alumno->obtenerGrupo();
        $this->cursos = $cursos = $alumno->obtenerCursos(2);
        switch ($alumno->situaciones_id) {
            case 1 :
                $this->regular();
                break;

            case 4 :
                $this->irregular();
                break;

            case 5 :
                $this->retenido();
                break;

            case 6 :
                $this->articulo33();
                break;

            case 7 :
                $this->articulo34();
                break;

            default :
                break;

        }
        $this->alumno = null;
        $this->grupo=null;
        $this->cursos = null;
    }

    private function regular() {
        $ordinario = 1;
        $extra = 2;
        $aprobados = array ();
        $reprobados = array ();
        foreach ($this->cursos as $curso) {

            $o = $this->calificaciones[$this->alumno->id][$curso['id']][$ordinario];
            $e = $this->calificaciones[$this->alumno->id][$curso['id']][$extra];
            if ($o['valor'] > 59 || strToUpper($o['valor']) == 'A') {
                //aprobo en ordinario
                $aprobados[] = $curso;
            } else
                if ($e != null && $e['valor'] > 59) {
                    //aprobo en extraordinario
                    $aprobados[] = $curso;
                } else {
                    //no aprobo
                    $reprobados[] = $curso;
                }

        }

        if (count($reprobados) == 0) {
            //avance normal
            if ($this->grupo->grado < 6) {
                $this->avanceNormal();
                $this->situacion_regular();
            } else {
                $this->situacion_egresado();
            }
        } else {
            $this->avanceNormal();
            $this->avanceReprobadas();
            $this->situacion_irregular();

        }
    }

    private function irregular() {
        $ordinario = 1;
        $extra = 2;
        $aprobados = array ();
        $reprobados = array ();
        foreach ($this->cursos as $curso) {

            $o = $this->calificaciones[$this->alumno->id][$curso['id']][$ordinario];
            $e = $this->calificaciones[$this->alumno->id][$curso['id']][$extra];
            if ($o['valor'] > 59 || strToUpper($o['valor']) == 'A') {
                //aprobo en ordinario
                $aprobados[] = $curso;
            } else
                if ($e != null && $e['valor'] > 59) {
                    //aprobo en extraordinario
                    $aprobados[] = $curso;
                } else {
                    //no aprobo
                    $reprobados[] = $curso;
                }

        }

        if (count($reprobados) == 0) {
            //avance normal
            if ($this->grupo->grado < 6) {
                $this->avanceNormal();
                $this->situacion_regular();
            } else {
                $this->situacion_egresado();
            }
        } else {

        }
    }

    private function retenido(){
    $ordinario = 1;
        $extra = 2;
        $aprobados = array ();
        $reprobados = array ();
        foreach ($this->cursos as $curso) {

            $o = $this->calificaciones[$this->alumno->id][$curso['id']][$ordinario];
            $e = $this->calificaciones[$this->alumno->id][$curso['id']][$extra];
            if ($o['valor'] > 59 || strToUpper($o['valor']) == 'A') {
                //aprobo en ordinario
                $aprobados[] = $curso;
            } else
                if ($e != null && $e['valor'] > 59) {
                    //aprobo en extraordinario
                    $aprobados[] = $curso;
                } else {
                    //no aprobo
                    $reprobados[] = $curso;
                }

        }

        if (count($reprobados) == 0) {
            //avance normal
            if ($this->grupo->grado < 6) {
                $this->avanceNormal();
                $this->situacion_regular();
            } else {
                $this->situacion_egresado();
            }
        } else {

        }

    }

    private function articulo33(){
    $ordinario = 1;
        $extra = 2;
        $aprobados = array ();
        $reprobados = array ();
        foreach ($this->cursos as $curso) {

            $o = $this->calificaciones[$this->alumno->id][$curso['id']][$ordinario];
            $e = $this->calificaciones[$this->alumno->id][$curso['id']][$extra];
            if ($o['valor'] > 59 || strToUpper($o['valor']) == 'A') {
                //aprobo en ordinario
                $aprobados[] = $curso;
            } else
                if ($e != null && $e['valor'] > 59) {
                    //aprobo en extraordinario
                    $aprobados[] = $curso;
                } else {
                    //no aprobo
                    $reprobados[] = $curso;
                }

        }

        if (count($reprobados) == 0 ) {
            //avance normal
            if ($this->grupo->grado < 6) {
                $this->avanceNormal();
                $this->situacion_regular();
            } else {
                $this->situacion_egresado();
            }
        } else {
            //revisar si aprobo sus cursos en art33
            $articulos=array();
            $rpb33=array();
            $rpb=array();
            foreach($articulos as $art){
                foreach($reprobados as $r){
                    if($art->cursos_id==$r->id){
                    //reprobo un 33
                    $rpb33[]=$r;

                    }else{
                    $rpb[$r->id]=$r;

                    }

                }

            }
        }

    }

    private function articulo34(){
    $ordinario = 1;
        $extra = 2;
        $aprobados = array ();
        $reprobados = array ();
        foreach ($this->cursos as $curso) {

            $o = $this->calificaciones[$this->alumno->id][$curso['id']][$ordinario];
            $e = $this->calificaciones[$this->alumno->id][$curso['id']][$extra];
            if ($o['valor'] > 59 || strToUpper($o['valor']) == 'A') {
                //aprobo en ordinario
                $aprobados[] = $curso;
            } else
                if ($e != null && $e['valor'] > 59) {
                    //aprobo en extraordinario
                    $aprobados[] = $curso;
                } else {
                    //no aprobo
                    $reprobados[] = $curso;
                }

        }

        if (count($reprobados) == 0) {
            //avance normal
            if ($this->grupo->grado < 6) {
                $this->avanceNormal();
                $this->situacion_regular();
            } else {
                $this->situacion_egresado();
            }
        } else {

            //revisar si aprobo sus cursos en art33
            $articulos=array();
            $rpb33=array();
            $rpb=array();
            foreach($articulos as $art){
                foreach($reprobados as $r){
                    if($art->cursos_id==$r->id){
                    //reprobo un 33
                    $rpb33[]=$r;

                    }else{
                    $rpb[$r->id]=$r;

                    }

                }

            }



            //revisar si aprobo sus cursos en art34
            $articulos=array();
            $rpb34=array();
            foreach($articulos as $art){
                foreach($reprobados as $r){
                    if($art->cursos_id==$r->id){
                    //reprobo un 34
                    $rpb34[]=$r;
                    $rpb[$r->id]==null;
                    }else{
                    $rpb[$r->id]=$r;
                    }

                }

            }

            if(count($rpb34)==0){
            $this->avanceReprobadas();
            $this->avance33A34($rpb33);

            }else{
            $this->situacion_baja();
            }

        }

    }

    private function avanceA33($reprobados){

    }

    private function avance33A34($reprobados){

    }

    private function avanceReprobadas($reprobados) {
    /*
        if(strToUpper($this->grupo->turno)=="M")
        $turno="V";
        else if(strToUpper($this->grupo->turno)=="V")
        $turno="M";
        else if(strToUpper($this->grupo->turno)=="N")
        $turno="V";


        foreach($reprobados as $curso){

            $curso2=$curso;
            $inscribe=new Alumnoscursos();
            $inscribe->alta($this->alumno->id,$curso2->id);
        }
        */
    }

    private function avanceNormal() {

        $grupoAvance = new Grupos();

        $grupoAvance = $grupoAvance->find("ciclos_id=" . $this->cicloAvance->id . " AND grado='" . ($this->grupo->grado + 1) . "' AND letra='" . $this->grupo->letra . "' AND turno='" . $this->grupo->turno . "' AND oferta_id=" . $this->grupo->oferta_id);

        $grp = new Alumnosgrupo();
        $grp->alta($this->alumno_id, $grupoAvance->id);

    }

    private function situacion_regular() {
        $this->alumno->situaciones_id = 1;
        $this->alumno->save();
    }

    private function situacion_baja() {
        $this->alumno->situaciones_id = 2;
        $this->alumno->save();
    }

    private function situacion_irregular() {
        $this->alumno->situaciones_id = 4;
        $this->alumno->save();
    }

    private function situacion_egresado() {
        $this->alumno->situaciones_id = 3;
        $this->alumno->save();
    }
}
?>

