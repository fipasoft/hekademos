<?php
class Lector{
private $archivo;
public $registros;

	 public function Lector($arch){
		 $this->registros=array();
		 $this->archivo=$arch;
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
			    	$registro=array();
			        foreach($row as $cell)
			        {
			            $registro[]=utf8_encode($cell);
			        }
			        $this->registros[]=$registro;
			    
			    	$r++;
			    }
			 }
			 return true;
		}catch(Exception $e){
			return false;
		}
	}
	 	 
	
}
