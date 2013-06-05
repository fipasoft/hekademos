<?php
class Materias extends ActiveRecord{
    public function descripcion(){
        return ($this->descripcion == '' ? '-' : $this->descripcion);
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

    public function prerrequisitos(){
        $pre = new Prerrequisitos();
        return $pre->find("conditions: materia = '" . $this->id . "'");
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
}
?>
