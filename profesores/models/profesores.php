<?php
class Profesores extends ActiveRecord{

    public function cambioaStaff($id_profesor,$staff_id){
        $cursos=new Cursos();
        $cursos=$cursos->find('profesores_id='.$id_profesor);
        foreach($cursos as $c){
            $c->profesores_id=$staff_id;
            $c->save();
        }
    }

    public function nombre(){
        return $this->ap . ' ' . $this->am . ', ' . $this->nombre;
    }

    public function staff(){
        $profesor=$this->find_first("codigo='STAFF'");
        if($profesor==null){
            $profesor=new Profesores();
            $profesor->codigo='STAFF';
            $profesor->nombre='STAFF';
            $profesor->ap='STAFF';
            $profesor->am='STAFF';
            $profesor->save();
        }
        return $profesor;
    }

    public function tutordelosgrupos(){
        $tutoresgrupos=new Tutoresgrupos();
        $tutoresgrupos=$tutoresgrupos->find("profesores_id='".$this->id."'");
        $grupos=array();
        $ciclo_id = Session :: get_data('prof.ciclo.id');
        foreach($tutoresgrupos as $g){
            $grupo=new Grupos();
            $grupo=$grupo->find($g->grupos_id);
            if($grupo->id!='' && $grupo->ciclos_id==$ciclo_id){
                $grupos[$grupo->id]=$grupo;
            }
        }
        return $grupos;
    }
}
?>
