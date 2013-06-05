<?php 
class Departamento extends ActiveRecord{

    public function materias(){
        $academias = $this->academias();
        $materias = array();
        foreach($academias as $academia){
            foreach($academia->materias() as $materia){
                $materias[$materia->id] = $materia;
            }
        }
        
        return $materias;
    }
    
    public function academias(){
        $academias = new Academia();
        $academias = $academias->find("departamento_id = '".$this->id."'");
        return $academias;
        
    }

}

?>
