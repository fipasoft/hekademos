<?php
// sp5, Creado el 30/11/2008
/**
 *
 *
 * @package
 * @author     mimeks <mimex@fipasoft.com.mx>
 * @copyright  2008 FiPa Software (contacto at fipasoft.com.mx)
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL)
 *
 */
 class Alumnosgrupo extends ActiveRecord{

     public function baja(){
        $cursos=new Cursos();
        $cursos=$cursos->find("grupos_id=".$this->grupos_id);

        foreach($cursos as $curso){
            $alumnosCurso=new Alumnoscursos();
            if($alumnosCurso->exists('alumnos_id='.$this->alumnos_id.' AND cursos_id='.$curso->id)){
                $alumnosCurso=$alumnosCurso->find_first('alumnos_id='.$this->alumnos_id.' AND cursos_id='.$curso->id);
                $alumnosCurso->baja();
            }
        }

         $this->delete();


     }

     public function alta($alumno,$grupo){
         $this->alumnos_id=$alumno;
        $this->grupos_id=$grupo;
        $this->save();

        $grupos=new Grupos();
        $grupos=$grupos->find_first($grupo);
        $cursos=$grupos->cursosInfo();

        foreach($cursos as $curso){

            if($grupos->grado>4){
            $tipo=$curso->materiaTipo();
                if(strToUpper($tipo)=="OBL"){
                    $alumnosCurso=new Alumnoscursos();
                    $alumnosCurso->alta($alumno,$curso->id);

                }
            }else{
            $alumnosCurso=new Alumnoscursos();
            $alumnosCurso->alta($alumno,$curso->id);
            }
        }

     }



        public function inscribe($alumno,$grupo){
            $this->alumnos_id=$alumno;
            $this->grupos_id=$grupo;
            $this->save();
        }

 }
?>
