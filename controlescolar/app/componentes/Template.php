<?php
/*
 * Created on 11/04/2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class Template{
	private $config;

	public function Template(){
	$this->config=Config :: read("template.ini");
	}

 	public function banner(){
	return $this->config->template->banner;
 	}

 	public function footer(){
 	$year = date('Y');
	return $this->config->template->footer." ".$year;
 	}

 	public function powered(){
 		if($this->config->template->powered==1)
 		return '<div>'.$this->config->template->text_powered.'</div>';
 	}

 	public function title(){
 		return $this->config->template->title;
 	}

 	public function excel_escuela(){
 		return $this->config->excel->escuela;
 	}

 	public function excel_subtitulo(){
 		return $this->config->excel->subtitulo;
 	}


 }
?>
