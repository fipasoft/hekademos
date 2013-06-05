<?php
class Materias extends ActiveRecord{
    public function descripcion(){
        return ($this->descripcion == '' ? '-' : $this->descripcion);
    }
    
    public function horasporsemana(){
        if($this->horassemana!=0){
            return $this->horassemana;
        }else{
            $cursos = new Cursos();
            $cursos = $cursos->find("materias_id='".$this->id."'");
            foreach($cursos as $curso){
                $horarios = $curso->horarios();
                $total = 0;
                    
                foreach($horarios as $horario){
                    $inicio = Utils::timeToMinutes(substr($horario->inicio,0,5));
                    $fin = Utils::timeToMinutes(substr($horario->fin,0,5));
                    $mins = $fin-$inicio;
                    $h = floor($mins / 60);
                    $total += $h; 
                    
                }
                
                if($total>0){
                    return $total;
                }
            }
            return 0;
        }
    }

    public function tipo(){
        switch($this->tipo){
            case 'OBL': return 'Obligatoria';
            case 'OPT': return 'Optativa';
            case 'TLR': return 'Taller';
            case 'PRO': return 'Programa de extensi&oacute;n y difusi&oacute;n cultural';
        }
        return;
    }

    public function cuentaPrerrequisitos(){
        $pre = new Prerrequisitos();
        return $pre->count(" materia = '" . $this->id . "'");

    }

    public function prerrequisitos(){
        $pre = new Prerrequisitos();
        return $pre->find("conditions: materia = '" . $this->id . "'");
    }

    public function prerrequisitosasociativo(){
        $pre=$this->prerrequisitos();
        $pq=array();
        if(is_array($pre)){
            foreach($pre as $p){
                $pq[$p->requisito]=$p;
            }
        }
        return $pq;
    }

    public function profesores(){
        $ciclo_id = Session :: get_data('ciclo.id');
        $ciclo = new Ciclos();
        $ciclo = $ciclo->find($ciclo_id);
        
        $anterior = new Ciclos();
        $anterior = $anterior->find_first("numero='".$ciclo->anterior()."'");
        $profesores = new Profesores();
        $profesores = $profesores->find_all_by_sql(
                    "SELECT profesores.*
                    FROM profesores
                    INNER JOIN cursos ON profesores.id = cursos.profesores_id
                    INNER JOIN grupos ON cursos.grupos_id = grupos.id
                    WHERE grupos.ciclos_id='".$anterior->id."' AND cursos.materias_id='".$this->id."'
                    ORDER BY profesores.id "
        );
        $prs = array();
        foreach($profesores as $pro){
            $prs[$pro->id] = $pro;
        }
        
        return $prs;
    }
    
    public function semestre(){
        switch($this->semestre){
            case 1: return 'Primer';
            case 2: return 'Segundo';
            case 3: return 'Tercer';
            case 4: return 'Cuarto';
            case 5: return 'Quinto';
            case 6: return 'Sexto';
        }
        return;
    }

    public function porOferta($oferta,$semestre=null,$excluyentes=null){
        $sql="SELECT ".
            " materias.*".
            " FROM " .
            " materias,oferta,ofertasmaterias " .
            " WHERE " .
            " oferta.id='$oferta' AND ";
            if($semestre!=null)
            $sql.=" materias.semestre='$semestre' AND";

            $sql.=" materias.id=ofertasmaterias.materias_id AND " .
            " oferta.id=ofertasmaterias.oferta_id " ;
            if($excluyentes!=null)
            $sql.=$excluyentes;

            $materias = new Materias();
            return $materias->find_all_by_sql($sql);
    }

    public function Oferta($campo){
        $ofertaMaterias=new Ofertasmaterias();
        $ofertaMaterias=$ofertaMaterias->find_first('materias_id='.$this->id);
        $oferta=new Oferta();
        $oferta=$oferta->find($ofertaMaterias->oferta_id);
        if($campo=='nombre')
        return $oferta->nombre;
        else if($campo=='clave')
        return $oferta->clave;
        else if($campo=='id')
        return $oferta->id;
        else return '';
    }

    public function prerrequisitodematerias(){
            $pre = new Prerrequisitos();
            return $pre->find("conditions: requisito = '" . $this->id . "'");

    }

    public function prerrequisitodemateriasasociativo(){
        $pre=$this->prerrequisitodematerias();
            $pq=array();
            if(is_array($pre)){
                foreach($pre as $p){
                    $pq[$p->materia]=$p;
                }
            }
            return $pq;
    }


    public function sinprerrequisitos($semestre,$oferta=2){
        $inp=array();
        $materias=new Materias();
        $materias=$materias->find_all_by_sql(
                            "SELECT materias.* FROM " .
                            "materias " .
                            "INNER JOIN ofertasmaterias ON materias.id=ofertasmaterias.materias_id " .
                            "WHERE materias.semestre='$semestre' AND ofertasmaterias.oferta_id='$oferta' "
        );

        foreach($materias as $mat){
            if($mat->cuentaPrerrequisitos()==0)
                $sinp[$mat->id]=$mat;
        }
        return $sinp;

    }
}
?>
