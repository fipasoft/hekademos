<?php

Kumbia :: import('app.*');
class escolarAdmin extends Componente{
public $id;
public $contenido;
public $alumnos;
public $notificaciones;
public $numReg;
public $num_rows;
public $elemento_indice;

    public function escolarAdmin($nm,$ttl,$id_com,$elemento_indice=0,$nr=10){
          //parent::componente($nm,$ttl);
          $this->numReg=$nr;
          $this->elemento_indice=$elemento_indice;
         $this->setTable('escolar');
         $this->setId($id_com);
         $this->setName($nm);
         $this->setTitle($ttl);
         $this->getMyDat();
         parent::Componente();
     }


     private function getMyDat(){
        $cmp_escolar=new comescolar();
        $cmp_escolar=$cmp_escolar->find_first('componente_id='.$this->getId());
        $this->id=$cmp_escolar->id;

     }

     public function getNotificaciones($enviados){
         $this->notificaciones= new notificacion();

         $this->num_rows = $this->notificaciones->count("escolar_id=".$this->id." AND enviado=".$enviados);


         $this->notificaciones=$this->notificaciones->find("escolar_id=".$this->id." AND enviado=".$enviados);

     }

     public function getAlumnos(){
        $consulta="SELECT id,codigo,nombre,ap,am FROM alumnos";
        $db = new db("localhost", "hekademos", "hekademos", "hekademos");
           $this->alumnos=$db->in_query($consulta);

     }

     public function getTutores(){
        $consulta="SELECT id,alumno_id,nombre,ap,am FROM tutores";
        $db = new db("localhost", "hekademos", "hekademos", "hekademos");
           $this->elementos=$db->in_query($consulta);


     }

     public function cicloActivo(){
        $db = new db("localhost", "hekademos", "hekademos", "hekademos");
        $elementos=$db->in_query("SELECT * FROM ciclos WHERE activo='1'");
        $db->close();

        return $elementos[0];

    }

     public function enviaNotificacion($notificacion){
         $lista=explode("|",$notificacion->lista);
         $tipo=$lista[0];
         $turno=$lista[1];
         $grado=$lista[2];
         $grupo=$lista[3];
         if(strlen($tipo)>0 && strlen($turno)>0 && strlen($grado)>0 && strlen($grupo)>0){
             $cond="";
             if($turno!="T"){
                 $cond.=" AND grupos.turno='".$turno."' ";
             }

             if($grado!="T"){
                 $cond.=" AND grupos.grado='".$grado."' ";
             }

             if($grupo!="T"){
                 $cond.=" AND grupos.letra='".$grupo."' ";
             }

             $ciclo=$this->cicloActivo();
             if($ciclo["id"]!=null){

            $qA="SELECT alumnos.codigo,alumnos.nombre,alumnos.ap,alumnos.am,alumnos.mail ";
            $qA.=" FROM grupos,alumnos,alumnosgrupo ";
            $qA.= " WHERE alumnosgrupo.alumnos_id=alumnos.id AND alumnosgrupo.grupos_id=grupos.id AND grupos.ciclos_id=".$ciclo["id"]." ";
            $qA.=$cond;

            $qT='SELECT alumnos.codigo,alumnos.nombre,alumnos.ap, alumnos.am, tutores.nombre AS nombreT, tutores.ap AS apT, tutores.am AS amT, tutores.mail
                    FROM  grupos, alumnos, alumnosgrupo, tutores, tutoria
                    WHERE
                     alumnosgrupo.alumnos_id= alumnos.id AND
                     alumnosgrupo.grupos_id= grupos.id AND
                     grupos.ciclos_id='.$ciclo["id"].' AND
                     tutoria.tutores_id= tutores.id AND
                     tutoria.alumnos_id= alumnos.id ';
            $qT.=$cond;

            $bA=false;
            $bT=false;

            if($tipo=="T"){
            $bA=true;
            $bT=true;
            }else if($tipo=="2")
            $bT=true;
            else if($tipo=="3")
            $bA=true;

            $db = new db("localhost", "hekademos", "hekademos", "hekademos");
            $total=0;
            if($bA){
                $alumnos=$db->in_query($qA);
                $total+=sizeof($alumnos);
                $batch=new EmailBatch($notificacion->id,$alumnos,$notificacion->titulo,$notificacion->contenido,"A");
                $batch->inicia();
            }

            if($bT){
            $tutores=$db->in_query($qT);
                $total+=sizeof($tutores);
                $batch=new EmailBatch($notificacion->id,$tutores,$notificacion->titulo,$notificacion->contenido,"T");
                $batch->inicia();
            }
            return $total;

         }else return -1;

         }else{
             return -1;

         }
     }


     public function combo($campo,$valor1,$valor2){
         $resp="";
         $db = new db("localhost", "hekademos", "hekademos", "hekademos");
             switch($campo){
                 case "grado":
                             $cond="";
                             if($valor1!="T")
                                 $cond="WHERE turno='".$valor1."'";

                             $consulta="SELECT DISTINCT(grado) FROM grupos ".$cond;
                             $grados=$db->in_query($consulta);
                            foreach($grados as $g){
                                $resp.=$g["grado"].",";

                            }

                             $resp=substr($resp,0,strlen($resp)-1);
                             break;

                 case "grupo":
                             $cond="WHERE ";
                             $condiciones=array();
                             if($valor1!="T")
                                 $condiciones[sizeof($condiciones)]=" grado='".$valor1."' ";

                             if($valor2!="T")
                                 $condiciones[sizeof($condiciones)]=" turno='".$valor2."' ";

                            for($in=0;$in<sizeof($condiciones);$in++){
                                $cond.=$condiciones[$in]." AND ";
                            }

                            if(sizeof($condiciones)==0)$cond="";
                            else $cond=substr($cond,0,strlen($cond)-4);


                             $consulta="SELECT DISTINCT(letra) FROM grupos ".$cond." order by letra";
                             $grupos=$db->in_query($consulta);
                            foreach($grupos as $g){
                                $resp.=$g["letra"].",";

                            }

                             $resp=substr($resp,0,strlen($resp)-1);
                             break;

             }
             return $resp;

     }


}



?>
