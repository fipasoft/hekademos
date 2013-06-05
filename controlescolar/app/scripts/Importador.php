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

 class Importador{
 	private $archivo;
 	public $columnas;
 	public $registros;
 	public $tutor;
 	public $grupoUnico;
 	public $ciclo_id;
 	public $formatoNombre;

	 public function Importador($arch){
	 $this->registros=array();
	 $this->archivo=$arch;
	 		 $this->columnas=array(
						'grado','letra','turno','codigo','4','5','6','aprobadas','promedio'
						);

	 }

	 public function carga(){
		$ext=strtolower(substr($this->archivo,strripos($this->archivo, ".")+1));
 		if($ext=="xls"){
			return $this->fromXLS();
 		}
 		
 		return false;

	 }



	private function fromXLS(){
		try{
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
			    	if($r!=0){
			    	$registro=array();
			        for($c=1;$c<=count($this->columnas);$c++)
			        {
			        	$cell=$row[$c];
			        	if($cell=="")
			        	$cell="_";
			            $registro[]=utf8_encode($cell);
			        }
			        $this->registros[]=$registro;
			    	}
			    	$r++;
			    }
			 }
			 return true;
		}catch(Exception $e){
			return false;
		}
	}


	 public function aBD(){
		$datos=array();
		$informe=array();
		$exito=0;

		mysql_query("BEGIN") or die("ALU_ELI_1");
		foreach($this->registros as $registro){
			$promedio="";
			$aprobadas="";
			$codigo="";
			$grado="";
			$letra="";
			$turno="";
			$c=0;
			foreach($this->columnas as $col){
				$campo=utf8_encode(strtoLower($col));
				$valor=trim($registro[$c]);

				if($campo=="promedio"){
				$promedio=$valor;
				}elseif($campo=="codigo"){
				$codigo=$valor;
				}elseif($campo=="aprobadas"){
				$aprobadas=$valor;
				}elseif($campo=="grado"){
				$grado=$valor;
				}elseif($campo=="letra"){
				$letra=$valor;
				}elseif($campo=="turno"){
				$turno=$valor;
				}
				$c++;
			}

			$alumno=new Alumnos();
			if($alumno->exists("codigo='".$codigo."'")){
				try{
				$alumno=$alumno->find_first("codigo='".$codigo."'");
				$alumno->aprobadas=$aprobadas;
				$alumno->promedio=$promedio;
				if($aprobadas==0 || $promedio==0 || trim($aprobadas)=='' || trim($promedio)=='' ){
				$informe["w"][]=$grado.$letra.$turno." El alumno con el codigo ".$codigo." le faltan datos promedio: ".$promedio." aprobadas: ".$aprobadas." fue dado de alta.";

				}else{
					if($alumno->save()){
					$exito++;
					$informe["b"][]=" El alumno con el codigo ".$codigo." promedio: ".$promedio." aprobadas: ".$aprobadas." fue dado de alta.";
					}else{
						$informe["w"][]=$grado.$letra.$turno." El alumno con el codigo ".$codigo." no se puedo dar de alta los datos.";
					}
				}
				}catch(Exception $e){
					$informe["e"][]=$grado.$letra.$turno." No se pudo dar de alta el alumno con codigo ".$codigo.".".$e;

				}
			}else{
				$informe["w"][]=$grado.$letra.$turno." El codigo ".$codigo." no esta dado de alta en el sistema.";
			}

		}
		$informe["m"][]=$exito." registros importados con exito.";
		mysql_query("COMMIT") or die("ALU_ELI_1");

	 return $informe;
	 }

 }


?>
