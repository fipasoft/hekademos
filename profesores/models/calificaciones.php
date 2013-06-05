<?php
class Calificaciones extends ActiveRecord{
    protected $clave;
    protected $curso;

    public function completas($ciclo_id){
        $cursos=new Cursos();
        $cursos=$cursos->delCiclo($ciclo_id);

        $cond='';
        foreach($cursos as $c){
            $cond.="cursos_id=".$c->id." OR ";
        }

        $cond=substr($cond,0,strlen($cond)-3);

        //obtener alumnos-cursos del ciclo
        $alumCursos=new Alumnoscursos();
        $alumCursos=$alumCursos->find($cond);

        if(count($alumCursos)<=0)return false;

        $cond='';
        foreach($alumCursos as $ac){
            $cond.="( cursos_id=".$ac->cursos_id." AND alumnos_id=".$ac->alumnos_id.") OR ";
        }

        $cond=substr($cond,0,strlen($cond)-3);

        $calificaciones=db::raw_connect();
        $calificaciones=$calificaciones->in_query(
        "SELECT " .
        "calificaciones.*, " .
        "materias.nombre AS materia, " .
        "materias.tipo AS tipo " .
        "FROM " .
        "calificaciones " .
        "INNER JOIN cursos ON calificaciones.cursos_id=cursos.id " .
        "INNER JOIN materias ON cursos.materias_id=materias.id " .
        "WHERE ".
        $cond);

        if(count($calificaciones)<=0)return false;

        $datos=array();
        foreach($calificaciones as $cal){
            $datos[$cal['alumnos_id']][$cal['cursos_id']][$cal['oportunidades_id']]=$cal;
        }

        $ordinario=new Oportunidades();
        $ordinario=$ordinario->ordinario();

        $extra=new Oportunidades();
        $extra=$extra->extraordinario();


        //revisar que esten todas las calificaciones de los alumnos-cursos
        $faltantes=array();
        foreach($alumCursos as $ac){
        $dato=$datos[$ac->alumnos_id][$ac->cursos_id][$ordinario->id];
        if($dato==null){
                    $faltantes[]=$ac;
        }else{

            if(strToUpper($dato["tipo"])=="TLR"){
                 if(strToUpper($dato["valor"])!="SD" && strToUpper($dato["valor"])!="A" && strToUpper($dato["valor"])!="NA"){
                    $faltantes[]=$ac;
                }

            }else{
                 if($dato["valor"]<60 || strToUpper($dato["valor"])=="SD"){
                    $dato=$datos[$ac->alumnos_id][$ac->cursos_id][$extra->id];
                    if($dato==null)
                        $faltantes[]=$ac;
                }
            }

        }

        /*
         *
         *         $dato=$datos[$ac->alumnos_id][$ac->cursos_id][$ordinario->id];
        if($dato==null){
            $faltantes[]=$ac;

        }else{

            if(strToUpper($dato["valor"])=="SD" && strToUpper($dato["tipo"])!="TLR"){
                $dato=$datos[$ac->alumnos_id][$ac->cursos_id][$extra->id];
                if($dato==null)
                    $faltantes[]=$ac;

            }
            else if($dato["valor"]<60  && strToUpper($dato["valor"])!="NA" && strToUpper($dato["valor"])!="A" && strToUpper($dato["valor"])!="SD"){
                $dato=$datos[$ac->alumnos_id][$ac->cursos_id][$extra->id];
                if($dato==null)
                    $faltantes[]=$ac;

            }
         */
        }



        if(count($faltantes)>0)
        return false;

        return true;
    }


    public function deClaveAOportunidad($clave){
        $tmp = explode('-', $clave);
        $oportunidad = $tmp[1];
        return $oportunidad;
    }

    public function editables($calificaciones, $disponibles){
        foreach($calificaciones as $modo => $claves){
            if($modo == 'AGR' || $modo == 'EDI'){
                foreach($claves as $clave => $valores){
                    if(!in_array($clave, $disponibles)){
                        return false;
                    }
                }
            }else{
                return false;
            }
        }
        return true;
    }



    public function oportunidadClaves(){
        $claves = array();
        $oportunidades = new Oportunidades();
        $oportunidades = $oportunidades->find();

        foreach($oportunidades as $oportunidad){
            $claves[$oportunidad->clave] = $oportunidad->id;
        }

        return $claves;
    }

    public function status($c = ''){
        $c = ($c == '' ? $this->valor : $c);
        if($c >= 60 || $c == 'A'){
            return 'aprobado';
        }else{
            return 'reprobado';
        }
    }

    public function valida($tipo = ''){
        if($this->valor == 'SD'){
            return true;
        }else if($tipo == 'TLR' || $tipo == 'PRO'){
            if($this->valor == 'A' || $this->valor == 'NA'){
                return true;
            }
        }else if($this->valor >= 0 && $this->valor <= 100){
            $this->valor = intval($this->valor, 10);
            return true;
        }
        return false;
    }

}
?>