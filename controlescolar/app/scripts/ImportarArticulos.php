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

 class ImportarArticulos{
 	private $archivo;
 	public $columnas;
 	public $registros;
 	public $formatoNombre;
 	public $reglamento;
	 public function ImportarArticulos($id_reg,$a,$cols=null){
	 $this->reglamento = $id_reg;
	 $this->archivo=$a;
	 $this->formatoNombre=$fn;
	 $this->registros=array();
	 if($cols==null || !is_array($cols)){
	 	$this->columnas=array(
						'Articulo','Descripci&oacute;n'
						);

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
	
	public function aBD(){
		$informe=array();
		$exito=0;
		$reg = new Reglamentos();
		$reg = $reg->find($this->reglamento);
		foreach($this->registros as $registro){
			$sql = "SELECT count(*) FROM articulo INNER JOIN reglamento ON articulo.id = reglamento.articulo_id 
					INNER JOIN reglamentos ON reglamento.reglamentos_id = reglamentos.id
					WHERE articulo.numero = '".$registro[0]."' AND reglamentos.id = ".$this->reglamento;
			$articulo = new Articulo();
			if($articulo->count_by_sql($sql) > 0){ //Si ya esta algun articulo con ese numero
				$informe["w"][]="El articulo ".$registro[0]. " ya esta registrado en el reglamento '".$reg->nombre."'";
			}else{
				if(is_numeric($registro[0])){
				$articulo = new Articulo();
				$articulo->numero = $registro[0];
				$articulo->descripcion = $registro[1];
				if($articulo->save()){
					$reglamento = new Reglamento();
					$reglamento->reglamentos_id = $this->reglamento;
					$reglamento->articulo_id = $articulo->id;
					if($reglamento->save()){
						$exito++;						
					}else{	//Si hubo error al guardar la ligadura
						$informe["e"][]="No se pudo ligar el articulo ".$registro[0]. "con el reglamento '".$reg->nombre."'";			
					}
				}else{	//Si hubo error al guardar el articulo
					$informe["e"][]="No se pudo guardar el articulo numero ".$registro[0];	
				} 
				}else{
					$informe["e"][]="No se pudo guardar el articulo ".$registro[0].'. Debe ser numero.';
				}
			}
		}
		$informe["m"][]=$exito." registros importados con exito.";
		
		return $informe;
	} 



 }


?>
