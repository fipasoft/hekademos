<?php
class Profesores extends ActiveRecord{
    public $tarjeta;
    public $tipo_entidad;

    public function cambioaStaff($id_profesor,$staff_id){
        $cursos=new Cursos();
        $cursos=$cursos->find('profesores_id='.$id_profesor);
        foreach($cursos as $c){
            $c->profesores_id=$staff_id;
            $c->save();
        }
    }

    public function nombre(){
        return trim($this->ap) . ' ' . trim($this->am) . ', ' . trim($this->nombre);
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

    public function esStaff($id){
        $staff=new Profesores();
        $staff=$staff->staff();
        if($id==$staff->id)return true;
        else return false;
    }
}
?>
