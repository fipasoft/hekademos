<?php
/*
 * Created on 02/01/2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

 class Situacion{
    public $alumnos;
    private $ciclos_id;
     public function Situacion($c){
    $this->alumnos=array();
    $this->ciclos_id=$c;
    $this->ejecuta();
     }


     private function ejecuta(){
         $alums=new Alumnos();
         $alums=$alums->find();

         foreach($alums as $a){
             $grupo= $a->obtenerGrupo();
             $cursos=$a->obtenerCursos($this->ciclos_id);
             $situacion=1;
             if(count($cursos)<5){
             $situacion=5;
             }else{
             foreach($cursos as $c){
                 if($c['grado'] < $grupo->grado){
                                 $situacion = 4;
                                  break;
                 }
             }
             }

         $this->alumnos[$situacion][]=$a;
         }

     }


 }

?>
