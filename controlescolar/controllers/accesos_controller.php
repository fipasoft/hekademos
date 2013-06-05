<?php
Kumbia :: import('app.componentes.*');
Kumbia :: import('lib.kumbia.utils');
Kumbia :: import('lib.excel.main');

/** SP5
 * Creado el 03/07/2008
 * Copyright (C) 2008 FiPa Software (contacto at fipasoft.com.mx)
 */

class AccesosController extends ApplicationController {
	public $template = "system";

	public function generar($id='') {
		if($id!=''){
			$this->option="confirmar";
			$this->tipo=$id;
		}else{
		$id=$this->post("id");
		switch($id){
			case 'alumnos':

						$alu_pass=new Alumnospassword();
						$alu_pass=$alu_pass->find();

						$passwords=array();
						foreach($alu_pass as $p){
						$passwords[$p->alumnos_id]=$p;
						}

						$alumnos=new Alumnos();
						$alumnos=$alumnos->find();

						foreach($alumnos as $alumno){
								$pass=$passwords[$alumno->id];
								if($pass==null){ // No existe el password, entonces se crea.
										$ps=substr(sha1($alumno->id),0,6);

										$alumnospassword=new Alumnospassword();
										$alumnospassword->alumnos_id=$alumno->id;
										$alumnospassword->pass=sha1($ps);
										$alumnospassword->save();
								}
						}

						$this->option="exito";
						$this->exito=" La creación de accesos para alumnos fue exitosa.";
							break;

			case 'padres':

						$tut_pass=new Tutorespassword();
						$tut_pass=$tut_pass->find();

						$passwords=array();
						foreach($tut_pass as $p){
						$passwords[$p->tutores_id]=$p;
						}

						$tutores=new Tutores();
						$tutores=$tutores->find();

						foreach($tutores as $tutor){
								$pass=$passwords[$tutor->id];
								if($pass==null){ // No existe el password, entonces se crea.
										$ps=substr(sha1($tutor->id),0,6);

										$tutorespassword=new Tutorespassword();
										$tutorespassword->tutores_id=$tutor->id;
										$tutorespassword->pass=sha1($ps);
										$tutorespassword->save();
								}
						}

						$this->option="exito";
						$this->exito=" La creación de accesos para padres de familia fue exitosa.";


						break;

			case 'profesores':

						$prof_pass=new Profesorespassword();
						$prof_pass=$prof_pass->find();

						$passwords=array();
						foreach($prof_pass as $p){
						$passwords[$p->profesores_id]=$p;
						}

						$profesores=new Profesores();
						$profesores=$profesores->find();

						foreach($profesores as $profesor){
								$pass=$passwords[$profesor->id];
								if($pass==null){ // No existe el password, entonces se crea.
										$ps=substr(sha1($profesor->id),0,6);

										$profesorespassword=new Profesorespassword();
										$profesorespassword->profesores_id=$profesor->id;
										$profesorespassword->pass=sha1($ps);
										$profesorespassword->save();
								}
						}

						$this->option="exito";
						$this->exito=" La creación de accesos para profesores fue exitosa.";
							break;


			default:
					$this->option="error";
					$this->error=" No tiene permiso para ver la pagina.";
					break;

		}
		}

		}


	public function exportar($tipo,$modo){
		$this->set_response("view");
		require('app/reportes/xls.passwords.class.php');
		$reporte = new XLSPasswords(Session :: get_data('ciclo.id'),$tipo,$modo);
		$reporte->generar();
 	}

	public function index() {
		$usr_login = Session :: get_data('usr.login');
		$this->acl = array ();
		$acl = new gacl_extra();
		$acos_arr = array (
			'accesos' => array (
				'generar',
				'exportar'
			)
		);
		$this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);

	}

}
?>