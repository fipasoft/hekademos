<?php
class Profesores extends ActiveRecord{
	protected $hAsign;
	protected $hPre;
	protected $hDisp;
	protected $nCursos;
	
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
	
	public function prerregistrocursos(){
		$cursos = new Cursos();
		$cursos = $cursos->find_all_by_sql(
				"SELECT cursos.* FROM cursos
				INNER JOIN prerregistro ON cursos.id = prerregistro.cursos_id
				WHERE prerregistro.profesores_id = '".$this->id."'
				"
		);
		
		return $cursos;
		
	}
	
	
	public function horasasignadasporciclo($ciclo_id){
		$cursos = new Cursos();
		$cursos = $cursos->find_all_by_sql(
		"SELECT cursos.* FROM 
			cursos
			INNER JOIN grupos ON cursos.grupos_id = grupos.id
			WHERE
			cursos.profesores_id='".$this->id."' AND grupos.ciclos_id='".$ciclo_id."'");
		
		$total = 0;
		foreach($cursos as $curso){
				foreach($curso->horarios() as $horario){
					$temp = Utils::timeToMinutes($horario->fin) - Utils::timeToMinutes($horario->inicio);	
					$total += $temp;
				}
		}
		return Utils::minutesToHours($total);
	}
	
	public function laboral(){
		$laboral = new Laboral();
		$laboral = $laboral->find_first("profesores_id = '".$this->id."'");
		return $laboral;
	}
	
	public function lcategoria(){
		$laboral = $this->laboral();
		$lcategoria = new Lcategoria();
		$lcategoria = $lcategoria->find($laboral->lcategoria_id);
		return $lcategoria;
	}
	
}
?>
