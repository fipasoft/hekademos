<?php
 /**
 * ImportarAlumnos.php
 *
 * Created on 21/07/2009
 * @package  Controladores
 * @author     J Jonathan Lopez <jlopez@fipasoft.com.mx>
 * @copyright  2009 FiPa Software (contacto at fipasoft.com.mx)
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL)
 *
 */

 class ImportarAlumnos{
     private $archivo;
     public $columnas;
     public $registros;
     public $tutor;
     public $grupoUnico;
     public $ciclo_id;
     public $formatoNombre;
     public function ImportarAlumnos($c_id,$a,$t,$g,$fn,$cols=null){
     $this->archivo=$a;
     $this->tutor=$t;
     $this->grupoUnico=$g;
     $this->ciclo_id=$c_id;
     $this->formatoNombre=$fn;
     $this->registros=array();
     if($cols==null || !is_array($cols)){
         $this->columnas=array(
                        'codigo','ap','am','nombre','grado','letra','turno'
                        );

              /*$this->columnas=array(
                        'codigo','nombre','domicilio','tel','cel','mail','curp',
                        'fnacimiento','sexo','situaciones_id','admision','promedio','aprobadas','grupo'
                        );*/
     }

     }

     public function carga(){
        $ext=strtolower(substr($this->archivo,strripos($this->archivo, ".")+1));
         if($ext=="xls"){
            $this->fromXLS();
         }elseif($ext=="csv"){
            $this->fromCSV();
         }
     }



    private function fromCSV(){

        $handle = fopen($this->archivo, "r");
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
               $registro=array();
               $num = count($this->columnas);

            for ($c=0; $c < $num; $c++) {
                $col=$this->columnas[$c];
                if($col!="*"){
                $cell= $data[$c];
                if($cell=="")
                    $cell="_";

                    $registro[]=$cell;
                }

            }
            $this->registros[]=$registro;
        }
        fclose($handle);
    }



    private function fromXLS(){
        require_once('lib/excel/reader.php');
         $reader=new Spreadsheet_Excel_Reader();
        $reader->setUTFEncoder('iconv');
        $reader->setOutputEncoding('UTF-8');
        $reader->read($this->archivo);

        foreach($reader->sheets as $k=>$data)
         {
            $r=0;
            foreach($data['cells'] as $row)
            {
                $registro=array();
                for($c=1;$c<=count($this->columnas);$c++)
                {
                    $cell=$row[$c];
                    if($cell=="")
                    $cell="_";
                    $registro[]=utf8_encode($cell);
                }
                $this->registros[]=$registro;
                $r++;
            }
         }

    }

    private function creaTutor($alumno){
            $paterno=new Tutores();
            $paterno->nombre = "Tutor de ".$alumno->codigo;
            $paterno->ap = ($alumno->ap==""?"-":$alumno->ap);
            $paterno->am = ($alumno->am==""?"-":$alumno->am);
            $paterno->domicilio = $alumno->domicilio;
            $paterno->tel = $alumno->tel;
            $paterno->cel = $alumno->cel;
            $paterno->mail = $alumno->mail;
            $paterno->fnacimiento = date("Y-m-d",time());
            $paterno->sexo = $alumno->sexo;
            $paterno->foto = '';
            if($paterno->save()){
                $tutoria = new Tutoria();
                $tutoria->tutores_id = $paterno->id;
                $tutoria->alumnos_id = $alumno->id;
                            if( !$tutoria->save() ){
                            return false;
                            }
            }else{
            return false;
            }

            return true;
    }

    private function inscribe($alumno,$grupo){
        $ag=new Alumnosgrupo();
        $ag->alta($alumno->id,$grupo->id);

        $ag=new Alumnosgrupo();
        return $ag->exists("alumnos_id='".$alumno->id."' AND grupos_id='".$grupo->id."'");
    }

    private function obtenGrupo($grado,$letra,$turno){
        $grupo=new Grupos();
        if($this->grupoUnico!=""){
            $grupo=$grupo->find($this->grupoUnico);
        }else{
            $grupo=$grupo->find_first("ciclos_id='".$this->ciclo_id."' AND grado='".$grado."' AND letra='".$letra."' AND turno='".$turno."'");
        }
        return $grupo;
    }


    private function separar_nombre($nom){

        $arr = explode(' ', $nom);
        $nombre = array();
        $i = 0;
        foreach($arr as $palabra){
            $nombre[$i] .= ($nombre[$i] != '' ? ' ' : '').$palabra;
            if($i < 2 && $palabra != 'DEL' && $palabra != 'DE' && $palabra != 'LA'  && $palabra != 'LOS'){
                $i++;
            }
        }

        return $nombre;
    }

     public function aBD(){
        $datos=array();
        $informe=array();
        $exito=0;

        foreach($this->registros as $registro){
            mysql_query("BEGIN") or die("ALU_ELI_1");
            $inserta="INSERT INTO alumnos(%campos%) VALUES(%valores%)";
            $campos="";
            $valores="";
            $select="SELECT * FROM alumnos WHERE codigo='%CODIGO%'";
            $codigo="";
            $grado="";
            $letra="";
            $turno="";
            $situacion="";
            $c=0;
            foreach($this->columnas as $col){
                $campo=utf8_encode(strtoLower($col));
                if($campo!="*"){
                $valor=$registro[$c];
                if($campo=="grado"){
                $grado=$valor;
                }elseif($campo=="letra"){
                $letra=$valor;
                }elseif($campo=="turno"){
                $turno=$valor;
                }elseif($campo!="nombre"){
                $campos.=$campo.",";
                $valores.="'".$valor."',";

                }else{
                    if($this->formatoNombre==3){
                        $campos.=$campo.",";
                        $valores.="'".utf8_encode($valor)."',";
                    }else{

                    $nombres=$this->separar_nombre($valor);
                    $campos.="nombre,ap,am,";
                    if($this->formatoNombre==1){
                    $valores.="'".$nombres[0]."','".$nombres[1]."','".$nombres[2]."',";
                    }else{
                    $valores.="'".$nombres[2]."','".$nombres[0]."','".$nombres[1]."',";

                    }

                    }
                }


                if($campo=="situaciones_id"){
                $situacion=$valor;
                }

                if($campo=="codigo"){
                $codigo=$valor;
                }

                $c++;
                }
            }

            if($situacion==""){
                $campos.="situaciones_id,";
                $valores.="'1',";
                }

            $grupo=$this->obtenGrupo($grado,$letra,$turno);
            if($grupo->id!=""){
            $select=str_replace("%CODIGO%",$codigo,$select);

            $inserta=str_replace("%campos%",substr($campos,0,strlen($campos)-1),$inserta);
            $inserta=str_replace("%valores%",substr($valores,0,strlen($valores)-1),$inserta);
            $entrada=array("i"=>$inserta,"s"=>$select);
            $datos[]=$entrada;

            $alumno=new Alumnos();
            if(!$alumno->exists("codigo='".$codigo."'")){
                try{
                $db = db::raw_connect();
                $db->query($inserta);
                if(mysql_affected_rows()==0){
                    $informe["e"][]="No se pudo dar de alta el alumno con codigo ".$codigo.".";
                }else{
                    $alumno=new Alumnos();
                    $alumno=$alumno->find_first("codigo='".$codigo."'");
                    if($alumno->id!=""){
                        $e=0;
                    if($this->tutor=="1"){
                    if(!$this->creaTutor($alumno)){
                    $informe["e"][]="No se pudo dar de alta el tutor para el alumno con codigo ".$codigo.".";
                    mysql_query("ROLLBACK") or die("ALU_ELI_1");
                    $e=1;
                    }
                    }

                    if(!$this->inscribe($alumno,$grupo)){
                    $informe["e"][]="No se pudo inscribir al grupo a el alumno con codigo ".$codigo.".".$this->grupoUnico;
                    mysql_query("ROLLBACK") or die("ALU_ELI_1");
                    $e=1;
                    }
                     $exito++;
                     if($e==0)
                        mysql_query("COMMIT") or die("ALU_ELI_1");//mysql_query("ROLLBACK") or die("ALU_ELI_1");

                    }
                }
                }catch(Exception $e){
                    $informe["e"][]="No se pudo dar de alta el alumno con codigo ".$codigo.".".$e;
                    mysql_query("ROLLBACK") or die("ALU_ELI_1");
                }
            }else{
                $informe["w"][]="El codigo ".$codigo." ya esta dado de alta en el sistema.";
            }
            }else{
                $informe["e"][]="No se pudo dar de alta el alumno con codigo ".$codigo.". El grupo no existe.";

            }
        }
        $informe["m"][]=$exito." registros importados con exito.";

     return $informe;
     }

 }


?>
