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

class ImportarPlantilla{
	private $archivo;
	public $ciclo_id;
	public $inicio;
	public function ImportarPlantilla($c_id,$a,$ini){
	 $this->archivo=$a;
	 $this->ciclo_id=$c_id;
	 $this->inicio = $ini;
	 $this->registros=array();
	 /*$this->columnas=array(
						'codigo','departamento','academia','profesor','nombramiento','choraria','hfgrupo','descarga','asignatura','turno',
	 					'perfil','ultima'
	 					);
	*/
	 $this->columnas=array(
						'departamento','codigo','nombre','matutino','vespertino','suplente','categoria','choraria','hfgrupo','asignatura','faltan','status'
	 					);
	
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
		$f = 1;
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			if($f >= $this->inicio){
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
			$f++;
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
			$f = 1;
			foreach($data['cells'] as $row)
			{
				if($f >= $this->inicio){

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
				$f++;
			}

			$r++;
		}

	}
	public function aBD(){
		$informe=array();
		try{
			$datos=array();
			$exito=0;
			mysql_query("BEGIN") or die("IMPORTA");
			foreach($this->registros as $registro){
				$c=0;
				$campos = array();
				foreach($this->columnas as $col){
					$campo=utf8_encode(strtoLower($col));
					
					if($campo!="*"){
						$valor=$registro[$c];
						if($campo=="codigo"){
							$profesor = new Profesores();
							$profesor = $profesor->find_first("codigo='".$valor."'");
							if($profesor->id==""){
								$informe["m"][]="ERROR: Ningun profesor tiene el codigo ".$valor;
							}
						}else{
							$campos[$campo] = $valor;
						}
						$c++;
					}
				}
				
				if($profesor->id!=""){
					$contrato = new Contratoinfo();
					$contrato = $contrato->find_first("profesores_id='".$profesor->id."'");
					if($contrato->id!=""){
						$contrato->hasignadas= $campos['choraria'];
						$contrato->hfgrupo= $campos['hfgrupo'];
						$contrato->asignatura= $campos['asignatura'];
					
						if(!$contrato->save()){
							throw new Exception( 'Error al guardar el contrato del profesor '.$profesor->nombre());
						}else{
							$informe["m"][]="Guardando datos del profesor ".$profesor->nombre();
						}
					}else{
						$informe["m"][]="El profesor no tiene contrato ".$profesor->codigo;
					}
				}else{
					$informe["m"][]="ERROR:: No existe el codigo ".$campos['codigo'];
						
				}
			}
			mysql_query("COMMIT") or die("IMPORTA");
			//mysql_query("ROLLBACK") or die("IMPORTA");
		}catch( Exception $e ){
			$informe["m"][]="ERROR: ".$e->getMessage();
		}
	 return $informe;
	}
	
	public function aBD1(){
		$informe=array();
		try{
			$datos=array();
			$exito=0;
			mysql_query("BEGIN") or die("IMPORTA");
			foreach($this->registros as $registro){
				$c=0;
				$campos = array();
				foreach($this->columnas as $col){
					$campo=utf8_encode(strtoLower($col));
					if($campo!="*"){
						$valor=$registro[$c];
						if($campo=="departamento" && $valor!="_"){
							$departamento = new Departamento();
							$departamento = $departamento->find_first("nombre='".$valor."'");
							if($departamento->id==""){
								$departamento = new Departamento();
								$departamento->nombre = $valor;
								if(!$departamento->save()){
									throw new Exception( 'Error al guardar el departamento '.$valor);
								}else{
									$informe["m"][]="Departamento ".$valor." guardado con exito";
								}
							}

						}elseif($campo=="academia"  && $valor!="_"){
							$academia = new Academia();
							$academia = $academia->find_first("nombre='".$valor."'");
							if($academia->id==""){
								$academia = new Academia();
								$academia->nombre = $valor;
								$academia->departamento_id = $departamento->id;
								if(!$academia->save()){
									throw new Exception( 'Error al guardar la academia '.$valor);
								}else{
									$informe["m"][]="La academia ".$valor."  se guardo con el departamento ".$departamento->nombre;
								}
							}

						}elseif($campo=="codigo"){
							$profesor = new Profesores();
							$profesor = $profesor->find_first("codigo='".$valor."'");
							if($profesor->id==""){
								$informe["m"][]="ERROR: Ningun profesor tiene el codigo ".$valor;
							}
						}else{
							$campos[$campo] = $valor;
						}
						$c++;
					}
				}
				
				if($profesor->id!=""){
					$contrato = new Contratoinfo();
					$contrato = $contrato->find_first("profesores_id='".$profesor->id."'");
					if($contrato->id==""){
						$contrato = new Contratoinfo();
						$contrato->profesores_id = $profesor->id;
						//array(
						//'codigo','departamento','academia','profesor','nombramiento','choraria','hfgrupo','descarga','asignatura','turno',
	 					//'perfil','ultima'
	 					//);
						
	 					$contrato->hasignadas= $campos['choraria'];
						$contrato->hfgrupo= $campos['hfgrupo'];
						$contrato->hdescarga= $campos['descarga'];
						$contrato->perfil= $campos['perfil'];
						$contrato->turno=substr($campos['turno'],0,1);
						$contrato->gradoestudio=$campos['ultima'];
						//$contrato->tipo=;
						$contrato->nombramiento= $campos['nombramiento'];
						$contrato->asignatura= $campos['asignatura'];
					
						if(!$contrato->save()){
							throw new Exception( 'Error al guardar el contrato del profesor '.$profesor->nombre());
						}else{
							$informe["m"][]="Guardando datos del profesor ".$profesor->nombre();
						}
					}
				}
			}
			//mysql_query("COMMIT") or die("IMPORTA");
			mysql_query("ROLLBACK") or die("IMPORTA");
		}catch( Exception $e ){
			$informe["m"][]="ERROR: ".$e->getMessage();
		}
	 return $informe;
	}
	

}


?>
