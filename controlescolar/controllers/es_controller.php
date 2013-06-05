<?php
/*
 * Created on 04/04/2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
Kumbia :: import('app.componentes.*');
Kumbia :: import('lib.excel.main');
 Kumbia :: import('forms.db.sqlserver_record');
Kumbia :: import('models_sqlserver.AFxDoor');
 Kumbia :: import('models_sqlserver.AFxUser');
 Kumbia :: import('models_sqlserver.ViewEvents');
 Kumbia :: import('models_sqlserver.ViewUser');
  class EsController extends ApplicationController {
	public $template = "system";

	public function exportar(){
		set_time_limit(0);
		$this->set_response("view");
		require('app/reportes/xls.es.php');

		$reporte = new XLSEs("accesos");
		$reporte->generar();
 	}

	public function dia($card="",$fecha=""){
		$this->date=$fecha;
		$this->card=$card;
		$f=split("-",$fecha);

		$card=intval($card);
		$user=new ViewUser();
		if($user->db==true){
		$this->user=$user=$user->find_first("CardNumber='".$card."'");
		if($user->CardNumber!=''){
			if(checkdate($f[1],$f[2],$f[0])){
				try{
				$date=new DateTime($fecha);
				$this->fecha=$date->format("j")." de ".Utils :: mes_espanol($date->format("m"))." de ".$date->format("Y");
				$eventos=new ViewEvents();
				//se resta 2.041666667 por que la fecha esta adelantada con 2 dias y 1, .041666667 igual a 1 hora en el formato
				// de fecha de SQL Server
				$this->eventos=$eventos->find_all_by_sql(
				"SELECT ViewUser.UserInfo3,ViewEvents.UniqueID,CAST((ViewEvents.PanelLocalDT - 2.041666667) AS datetime) AS fecha,ViewEvents.DoorNumberText,
								ViewEvents.CardNumber,ViewEvents.UserNumberText,ViewEvents.AreaNumberText,ViewDoor.DoorNumber,ViewDoor.PodDoorIndex
		              FROM [Director].[dbo].[ViewUser]
		  				INNER JOIN [Director].[dbo].[ViewEvents] ON ViewUser.CardNumber=ViewEvents.CardNumber
						INNER JOIN [Director].[dbo].[ViewDoor] ON ViewEvents.DoorNumber=ViewDoor.DoorNumber
		  				WHERE PanelLocalDT > CAST(CAST('".$fecha." 00:00:01' AS datetime) AS float)+2.041666667 AND
		  			PanelLocalDT < CAST(CAST('".$fecha." 23:59:59' AS datetime) AS float)+2.041666667 AND (ViewUser.CardNumber='".$card."')"
				);
				$this->sql="SELECT ViewUser.UserInfo3,ViewEvents.UniqueID,CAST((ViewEvents.PanelLocalDT - 2) AS datetime) AS fecha,ViewEvents.DoorNumberText,
								ViewEvents.CardNumber,ViewEvents.UserNumberText,ViewEvents.AreaNumberText
		              FROM [Director].[dbo].[ViewUser]
		  				INNER JOIN [Director].[dbo].[ViewEvents] ON ViewUser.CardNumber=ViewEvents.CardNumber
		  				WHERE PanelLocalDT > CAST(CAST('".$fecha." 00:00:01' AS datetime) AS float)+2 AND
		  			PanelLocalDT < CAST(CAST('".$fecha." 23:59:59' AS datetime) AS float)+2 AND (ViewUser.CardNumber='".$card."')"
				;
				$this->entradas=0;
				$this->salidas=0;

				foreach($this->eventos as $e){
					if($e->esEntrada()==true)
						$this->entradas++;
						elseif($e->esSalida()==true)
						$this->salidas++;
				}

				$alumno=new Alumnos();
				$this->elemento=$alumno->find_first("codigo='".$user->UserInfo3."'");
				$this->usuario="alumno";

				if($this->elemento->id==''){
					$profesor=new Profesores();
					$this->elemento=$profesor->find_first("codigo='".$user->UserInfo3."'");
					$this->usuario="profesor";
				}

				if($this->elemento->id==''){
					$personal=new Personal();
					$this->elemento=$personal->find_first("codigo='".$user->UserInfo3."'");
					$this->usuario="usuario";


				}



				if($this->elemento->id==''){
					$this->option="error";
					$this->error="No existe el elemento";
				}else
				$this->option="vista";

				}catch(ActiveRecordException $e){
					$this->option="error";
					$this->error="Ocurrio un error en la base de datos.";

				}catch(Exception $e){
					$this->option="error";
					$this->error="La fecha no es valida.";

				}
		}else{
			$this->option="error";
			$this->error="La fecha no es valida.";
		}
		}else{
			$this->option="error";
			$this->error="No existen registros del alumno.";
		}
		}else{
			$this->option="error";
			$this->error="No se puedo establecer comunicacion con el servidor VEREX.";
		}

	}

	public function importar($id){
		$this->elementos=array();
		set_time_limit(0);
		if($id=='a'){

		$alumnos=new Alumnos();
		$alumnos=$alumnos->find(" 1 ORDER BY ap,am,nombre");

		foreach($alumnos as $a){
			$this->elementos[]=$a;
		}
		}else{
		$profesores=new Profesores();
		$profesores=$profesores->find();

		foreach($profesores as $a){
			$this->elementos[]=$a;
		}
		}
		$this->id=$id;
		$this->option="vista";

		$this->usuarios=new AFxUser();
		$this->usuarios=$this->usuarios->find_all_by_sql(
				"SELECT [UserNumber]
      				,[FirstName]
      				,[LastName]
      				,[CardNumber]
  					FROM [Director].[dbo].[AFxUser] ");

	}

	public function inconsistencias_exportar(){
		$this->set_response("view");
		require('app/reportes/xls.es.php');
		$reporte = new XLSEs("inconsistencias",Session :: get_data("es.inconsistencias.fecha"));
		$reporte->generar();
 	}

	public function inconsistencias($fecha='',$pag=1){
		Kumbia :: import('app.scripts.Inconsistencias');
		$this->option="vista";
		$this->ciclo=new Ciclos();
		$this->ciclo=$this->ciclo->find(Session :: get_data("ciclo.id"));
		$this->option="vista";
		if($fecha==''){
			$date=new DateTime();
			$this->fecha=$date->format("j")." de ".Utils :: mes_espanol($date->format("m"))." de ".$date->format("Y");
			$this->date=$date->format("Y")."-".$date->format("m")."-".$date->format("d");

					if(!$this->ciclo->fechaValida($this->date)){
					$agenda = new Agenda();
					$evento = new Eventos();
					$rol = new Roles();
					$evento = $evento->find_first(
						"conditions: clave = 'CRS-PER'"
						);
					$rol = $rol->find_first(
						"conditions: eventos_id = '" . $evento->id . "' "
					);
					$periodo = $agenda->find_first(
						"conditions: " .
						    "ciclos_id = '" . $this->ciclo->id . "' " .
							"AND roles_id = '" . $rol->id . "' "
					);

					$date=new DateTime($periodo->fin);
					$this->date=substr($periodo->fin,0,10);
					$this->fecha=$date->format("j")." de ".Utils :: mes_espanol($date->format("m"))." de ".$date->format("Y");

					}

		}else{
			$f=split("-",$fecha);
			if(checkdate($f[1],$f[2],$f[0])){
			try{
			$date=new DateTime($fecha);
			$this->fecha=$date->format("j")." de ".Utils :: mes_espanol($date->format("m"))." de ".$date->format("Y");
			$this->date=$fecha;
			}catch(Exception $e){
						$this->option="error";
						$this->error="La fecha no es valida.";

			}
			}else{
				$this->option="error";
				$this->error="La fecha no es valida.";

			}

		}
		$hoy=new DateTime();
		$controlador = $this->controlador;
		$accion = $this->accion;
		// busqueda
		$b = new Busqueda($controlador, $accion);
		$this->ofertas=new Oferta();
		$this->ofertas=$this->ofertas->find();
		if($this->option!="error"){
		if($date->format("U")<=$hoy->format("U")){
		if($this->ciclo->fechaValida($this->date)){

		$b->quitarCondicion('kumbia_path');
		$b->quitarCondicion('date');
		$b->quitarCondicion('inconsistencia');
		$this->usuarios=array();
		$tabla="";
		if($b->campo("tipo")=="" || $b->campo("tipo")=="P"){
		$b->quitarCondicion('tipo');
		$b->quitarCondicion('grado');
		$b->quitarCondicion('letra');
		$b->quitarCondicion('turno');
		$b->quitarCondicion('oferta_id');
		$b->establecerCondicion('nombre', "CONCAT(TRIM(profesores.nombre), ' ', TRIM(profesores.ap), ' ', TRIM(profesores.am)) LIKE '%" . $b->campo('nombre') . "%' ");
		$c = $b->condicion();
		$profesores=new Profesores();
		$profesores=$profesores->find_all_by_sql(
		"SELECT * FROM profesores WHERE ".
		($c==""? "1" : $c)." ORDER BY ap,am,nombre");

		foreach($profesores as $a){
			$a->foto = KUMBIA_PATH . 'img/' . ($a->foto == '' ? 'sp5/persona.png' : 'profesores/' . $a->foto . '?d=' . time());
			$a->tipo_entidad="profesores";
			$this->usuarios[$a->codigo] = $a;

		}
		$tabla="profesores";
		}elseif($b->campo("tipo")=="A"){
		$b->quitarCondicion('tipo');
		$b->establecerCondicion('nombre', "CONCAT(TRIM(alumnos.nombre), ' ', TRIM(alumnos.ap), ' ', TRIM(alumnos.am)) LIKE '%" . $b->campo('nombre') . "%' ");
		$b->establecerCondicion('oferta_id', "grupos.oferta_id  = '" . $b->campo('oferta_id') . "' ");
		$c = $b->condicion();
		$alumnos=new Alumnos();
		$alumnos=$alumnos->find_all_by_sql(
		"SELECT " .
		"alumnos.codigo, " .
		"alumnos.id, " .
		"alumnos.nombre, " .
		"alumnos.am, " .
		"alumnos.ap, " .
		"grupos.id AS grupos_id, " .
		"grupos.grado, " .
		"grupos.letra," .
		"grupos.turno, " .
		"alumnos.foto, " .
		"situaciones.nombre AS situacion " .
		"FROM " .
		"ciclos " .
		"Inner Join grupos ON ciclos.id = grupos.ciclos_id " .
		"Inner Join alumnosgrupo ON grupos.id = alumnosgrupo.grupos_id " .
		"Inner Join alumnos ON alumnosgrupo.alumnos_id = alumnos.id " .
		"Inner Join situaciones ON alumnos.situaciones_id = situaciones.id " .
		"WHERE " .
		"ciclos.id = '" . Session :: get_data("ciclo.id") . "' " .
		 ($c == "" ? "" : "AND " . $c) . " " .
		"ORDER BY " .
		"grupos.turno, grupos.grado, grupos.letra, alumnos.ap, alumnos.am, alumnos.nombre " );

		foreach($alumnos as $a){
			$a->foto = KUMBIA_PATH . 'img/' . ($a->foto == '' ? 'sp5/persona.png' : 'alumnos/' . $a->foto . '?d=' . time());
			$a->tipo_entidad="alumnos";
			$this->usuarios[$a->codigo] = $a;

		}
		$tabla="alumnos";
		}elseif($b->campo("tipo")!=""){
				$b->quitarCondicion('inicio');
				$b->quitarCondicion('fin');
				$b->quitarCondicion('tipo');
				$b->quitarCondicion('grado');
				$b->quitarCondicion('letra');
				$b->quitarCondicion('turno');
				$b->quitarCondicion('oferta_id');
				$b->establecerCondicion('nombre', "CONCAT(TRIM(personal.nombre), ' ', TRIM(personal.ap), ' ', TRIM(personal.am)) LIKE '%" . $b->campo('nombre') . "%' ");
				$c = $b->condicion();
				$personal=new Personal();
				$personal=$personal->find_all_by_sql(
				"SELECT * FROM personal WHERE ".
				($c==""? "tipopersonal_id='".$b->campo('tipo')."'" : $c." AND tipopersonal_id='".$b->campo('tipo')."'"));
				foreach($personal as $p){
					$p->foto = KUMBIA_PATH . 'img/' . ($p->foto == '' ? 'sp5/persona.png' : 'personal/' . $p->foto . '?d=' . time());
					$p->tipo_entidad="personal";
					$this->usuarios[$p->codigo] = $p;
				}

				$tabla="personal";
				}

			$pagina=new Inconsistencias();
			$pagina->colocaUsuarios($this->usuarios);
			$pagina->colocaTabla($tabla);
			$pagina->colocaTipos($b->campo('inconsistencia'));
			$pagina->colocaPagina($pag);
			$pagina->colocaFecha($this->date);
			Session :: set_data("es.inconsistencias.fecha",$this->date);
			$this->inconsistencias=$pagina->obtenInconsistencias();
			if($pagina->estaConectado()){
			$this->registros=count($this->inconsistencias);
			$this->pagina=$pagina;
			}else{
				$this->option="error";
				$this->error="No se puedo establecer comunicacion con el servidor VEREX.";

			}
		}else{
			$this->option="error";
			$this->error="La fecha no es valida para el ciclo ".$this->ciclo->numero;
		}
		}else{
			$this->option="error";
			$this->error="La fecha es mayor al dia de hoy. <br/> No existe ninguna inconsistencia.";
		}
		}

		$this->busqueda=$b;
			$this->tipos=new Tipopersonal();
			$this->tipos=$this->tipos->find();


	}

	public function index($pagina=1){
		set_time_limit(0);
		$pagina=intval($pagina);
		if($pagina=="" || $pagina<1)
			$pagina=1;

		$this->cond="";
		$usr_login = Session :: get_data('usr.login');
		$this->acl = array ();
		$acl = new gacl_extra();
		$acos_arr = array (
			'es' => array (
				'inconsistencias','exportar',
				'dia',
				'inconsistencias'
			),
			'alumnos' => array(
				'ver'
			),
			'profesores' => array(
				'ver'
			),
			'personal' => array(
				'ver'
			)
		);
		$this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);

		$this->ciclo=new Ciclos();
		$this->ciclo=$this->ciclo->find(Session::get_data("ciclo.id"));
		$ciclos = new Ciclos();
		$this->ciclos = $ciclos->find("columns: id, numero", "conditions: abierto = '1' ", "order: numero DESC");
			$this->option="";
				$this->inicioV="";
				$this->finV="";
				$this->inicio="";
				$this->fin="";
				$this->p=0;
				$this->a=0;
				$this->c="";

			$controlador = $this->controlador;
			$accion = $this->accion;
			// busqueda
			$b = new Busqueda($controlador, $accion);

				if($b->campo('inicio')==""){
					$this->inicio=date("Y-m-d H:i",time()).":00";
					$this->inicioV=date("d/m/Y H:i",time());
					$inicio=new DateTime($this->inicio);
					$inicio->modify("-1 hour");
					$this->inicio=$inicio->format("Y-m-d H:i").":00";//date("Y-m-d H:i",time()).":00";
					$this->inicioV=$inicio->format("d/m/Y H:i");//date("d/m/Y H:i",time());

					if(!$this->ciclo->fechaValida($this->inicio)){
					$agenda = new Agenda();
					$evento = new Eventos();
					$rol = new Roles();
					$evento = $evento->find_first(
						"conditions: clave = 'CRS-PER'"
						);
					$rol = $rol->find_first(
						"conditions: eventos_id = '" . $evento->id . "' "
					);
					$periodo = $agenda->find_first(
						"conditions: " .
						    "ciclos_id = '" . $this->ciclo->id . "' " .
							"AND roles_id = '" . $rol->id . "' "
					);

					$this->inicio=substr($periodo->fin,0,10).date(" H:i",time());
					$inicio=new DateTime($this->inicio);
					$this->inicioV=$inicio->format("d/m/Y H:i");
					$b->establecerCampo("inicio",$this->inicioV);
					}

				}else{
					$i=split(' ',$b->campo('inicio'));
					$in=split('/',$i[0]);
					$this->inicio=$in[2]."-".$in[1]."-".$in[0]." ".$i[1].":00";
					$this->inicioV=$b->campo('inicio');
				}

				if($b->campo('fin')==""){
					$inicio->modify("+1 hour");
					$this->fin=$inicio->format("Y-m-d H:i").":00";//date("Y-m-d H:i",time()).":00";
					$this->finV=$inicio->format("d/m/Y H:i");//date("d/m/Y H:i",time());
					$b->establecerCampo("fin",$this->finV);
				}else{
					$i=split(' ',$b->campo('fin'));
					$in=split('/',$i[0]);
					$this->fin=$in[2]."-".$in[1]."-".$in[0]." ".$i[1].":00";
					$this->finV=$b->campo('fin');
				}

				$this->ofertas=new Oferta();
				$this->ofertas=$this->ofertas->find();
				$this->alumnos=array();
				$cond="";
				if($this->ciclo->fechaValida($this->inicio) && $this->ciclo->fechaValida($this->fin)){

				if($b->campo("tipo")=="" || $b->campo("tipo")=="A"){
				$b->establecerCondicion('nombre', "CONCAT(TRIM(alumnos.nombre), ' ', TRIM(ap), ' ', TRIM(am)) LIKE '%" . $b->campo('nombre') . "%' ");
				$b->establecerCondicion('oferta_id', "grupos.oferta_id  = '" . $b->campo('oferta_id') . "' ");
				$b->quitarCondicion('inicio');
				$b->quitarCondicion('fin');
				$b->quitarCondicion('tipo');
				// genera las condiciones
				$this->c = $c = $b->condicion();
				$alumnos=new Alumnos();
				$this->a=$alumnos = $alumnos->find_all_by_sql("SELECT " .
				"alumnos.codigo, " .
				"alumnos.id, " .
				"alumnos.ap, " .
				"alumnos.am, " .
				"alumnos.nombre, " .
				"alumnos.foto " .
				"FROM " .
				"ciclos " .
				"Inner Join grupos ON ciclos.id = grupos.ciclos_id " .
				"Inner Join alumnosgrupo ON grupos.id = alumnosgrupo.grupos_id " .
				"Inner Join alumnos ON alumnosgrupo.alumnos_id = alumnos.id " .
				"WHERE " .
				"ciclos.id = '" . Session::get_data("ciclo.id") . "'" .
				 ($c == "" ? "" : "AND " . $c) . " " );

				foreach($alumnos as $a){
					$a->foto = KUMBIA_PATH . 'img/' . ($a->foto == '' ? 'sp5/persona.png' : 'alumnos/' . $a->foto . '?d=' . time());
					$a->tipo_entidad="alumnos";
					$this->alumnos[$a->codigo] = $a;
					$cond.=" (ViewUser.UserInfo3='".$a->codigo."') OR";
				}

				}elseif( $b->campo("tipo")=="P"){
				$b->quitarCondicion('inicio');
				$b->quitarCondicion('fin');
				$b->quitarCondicion('tipo');
				$b->quitarCondicion('grado');
				$b->quitarCondicion('letra');
				$b->quitarCondicion('turno');
				$b->quitarCondicion('oferta_id');
				$b->establecerCondicion('nombre', "CONCAT(TRIM(profesores.nombre), ' ', TRIM(profesores.ap), ' ', TRIM(profesores.am)) LIKE '%" . $b->campo('nombre') . "%' ");
				$c = $b->condicion();
				$profesores=new Profesores();
				$profesores=$profesores->find_all_by_sql(
				"SELECT * FROM profesores WHERE ".
				($c==""? "1" : $c));
				$this->p=$profesores;
				foreach($profesores as $a){
					$a->foto = KUMBIA_PATH . 'img/' . ($a->foto == '' ? 'sp5/persona.png' : 'profesores/' . $a->foto . '?d=' . time());
					$a->tipo_entidad="profesores";
					$this->alumnos[$a->codigo] = $a;
					$cond.=" (ViewUser.UserInfo3='".$a->codigo."') OR";

				}
				}elseif($b->campo("tipo")!=""){
				$b->quitarCondicion('inicio');
				$b->quitarCondicion('fin');
				$b->quitarCondicion('tipo');
				$b->quitarCondicion('grado');
				$b->quitarCondicion('letra');
				$b->quitarCondicion('turno');
				$b->quitarCondicion('oferta_id');
				$b->establecerCondicion('nombre', "CONCAT(TRIM(personal.nombre), ' ', TRIM(personal.ap), ' ', TRIM(personal.am)) LIKE '%" . $b->campo('nombre') . "%' ");
				$this->c=$c = $b->condicion();
				$personal=new Personal();
				$personal=$personal->find_all_by_sql(
				"SELECT * FROM personal WHERE ".
				($c==""? "tipopersonal_id='".$b->campo('tipo')."'" : $c." AND tipopersonal_id='".$b->campo('tipo')."'"));
				$this->sql=	"SELECT * FROM personal WHERE ".
				($c==""? "tipopersonal_id='".$b->campo('tipo')."'" : $c." AND tipopersonal_id='".$b->campo('tipo')."'");
				foreach($personal as $p){
					$p->foto = KUMBIA_PATH . 'img/' . ($p->foto == '' ? 'sp5/persona.png' : 'personal/' . $p->foto . '?d=' . time());
					$p->tipo_entidad="personal";
					$this->alumnos[$p->codigo] = $p;
					$cond.=" (ViewUser.UserInfo3='".$p->codigo."') OR";
				}


				}

				$this->cond=$cond;
				$eventos=new ViewEvents();
				if($eventos->db==true){
				$this->conectado=true;

				if( $cond!="" ){

					$cond=substr($cond,0,strlen($cond)-2);

					$cant=20;
					$pag=$pagina-1;
					//se resta 2.041666667 por que la fecha esta adelantada con 2 dias y 1, .041666667 igual a 1 hora en el formato
					// de fecha de SQL Server
					$eventos=$eventos->find_all_by_sql(
				"SELECT TOP ".(($pag*$cant)+$cant)."
								ViewUser.UserInfo3,ViewEvents.UniqueID,CAST(([PanelLocalDT]-2.041666667 ) AS datetime) AS fecha,ViewEvents.DoorNumberText,
								ViewEvents.CardNumber,ViewEvents.UserNumberText,ViewEvents.AreaNumberText,ViewDoor.DoorNumber,ViewDoor.PodDoorIndex
		              FROM [Director].[dbo].[ViewUser]
		  				INNER JOIN [Director].[dbo].[ViewEvents] ON ViewUser.CardNumber=ViewEvents.CardNumber
						INNER JOIN [Director].[dbo].[ViewDoor] ON ViewEvents.DoorNumber=ViewDoor.DoorNumber
		  				WHERE PanelLocalDT > CAST(CAST('".$this->inicio."' AS datetime) AS float)+2.041666667 AND
		  			PanelLocalDT < CAST(CAST('".$this->fin."' AS datetime) AS float)+2.041666667 AND (".$cond.") ORDER BY PanelLocalDT DESC"
		  					//"(($pag*$cant)+1)." AND ".(($pag*$cant)+$cant).""
				);
				$this->c=			"SELECT TOP ".(($pag*$cant)+$cant)."
								ViewUser.UserInfo3,ViewEvents.UniqueID,CAST(([PanelLocalDT] - 2) AS datetime) AS fecha,ViewEvents.DoorNumberText,
								ViewEvents.CardNumber,ViewEvents.UserNumberText,ViewEvents.AreaNumberText,ViewDoor.DoorNumber,ViewDoor.PodDoorIndex
		              FROM [Director].[dbo].[ViewUser]
		  				INNER JOIN [Director].[dbo].[ViewEvents] ON ViewUser.CardNumber=ViewEvents.CardNumber
						INNER JOIN [Director].[dbo].[ViewDoor] ON ViewEvents.DoorNumber=ViewDoor.DoorNumber
		  				WHERE PanelLocalDT > CAST(CAST('".$this->inicio."' AS datetime) AS float)+2 AND
		  			PanelLocalDT < CAST(CAST('".$this->fin."' AS datetime) AS float)+2 AND (".$cond.") ORDER BY PanelLocalDT DESC";

		  			$this->eventos=array();
		  			$ini=($pag*$cant)+1;
		  			$fin=(($pag*$cant)+$cant);
		  			$index=1;
		  			foreach($eventos as $e){
					if($ini<=$index && $index<=$fin)
						$this->eventos[]=$e;

					$index++;
		  			}

				$this->registros=count($this->eventos);
				$this->option="vista";

					}else{
						$this->registros=0;
						$this->eventos=array();
						$this->registros=count($this->eventos);
						$this->option="vista";
				}
				}else{
					$this->option="error";
					$this->error="No se puedo establecer comunicacion con el servidor VEREX.";
					$this->conectado=false;
				}


				}else{
					$this->option="error";
					$this->error="La fecha no es valida para el ciclo ".$this->ciclo->numero;

				}

				$this->busqueda = $b;
				$this->pagina=$pagina;
				$this->tipos=new Tipopersonal();
				$this->tipos=$this->tipos->find();

	}


	function importacredenciales($id = ''){
		if($id == 'profesores'){
			$this->option="vista";

			$datos = array();
			$sql = "SELECT profesores.* FROM profesores
					INNER JOIN cursos ON profesores.id = cursos.profesores_id
					INNER JOIN grupos ON cursos.grupos_id = grupos.id
					WHERE grupos.ciclos_id = 4
					GROUP BY profesores.id";
			$profesorcredencial = new Profesorescredencial();
			$profesorcredencial->delete_all();
			$profesores = new Profesores();
			$profesores = $profesores->find_all_by_sql($sql);
			foreach($profesores as $profesor){
				$user=new ViewUser();
				if($user->db==true){
				$user=$user->find_first("UserInfo3='".$profesor->codigo."'");
				if($user->CardNumber!=''){
				$datos[$profesor->codigo] = $profesor->codigo." ".$profesor->nombre()." ".$user->CardNumber." ".$user->UserInfo3;
				$profesorcredencial = new Profesorescredencial();
				$profesorcredencial->profesores_id = $profesor->id;
				$profesorcredencial->credencial_id = $user->CardNumber;
				$profesorcredencial->save();
				}
				}else{
					$this->option="error";
					$this->error="No se puedo establecer comunicacion con el servidor VEREX.";
					break;
				}

			}
			$this->datos = $datos;

		}elseif($id == 'alumnos'){
			$this->option="vista";

			$datos = array();
			$sql = "SELECT alumnos.* FROM alumnos
					INNER JOIN alumnosgrupo ON alumnos.id = alumnosgrupo.alumnos_id
					INNER JOIN grupos ON alumnosgrupo.grupos_id = grupos.id
					WHERE grupos.ciclos_id = 4
					GROUP BY alumnos.id";
			$alumnocredencial = new Alumnoscredencial();
			$alumnocredencial->delete_all();
			$alumnos = new Alumnos();
			$alumnos = $alumnos->find_all_by_sql($sql);
			foreach($alumnos as $alumno){
				$user=new ViewUser();
				if($user->db==true){
				$user=$user->find_first("UserInfo3='".$alumno->codigo."'");
				if($user->CardNumber!=''){
				$datos[$alumno->codigo] = $alumno->codigo." ".$alumno->nombre()." ".$user->CardNumber." ".$user->UserInfo3;
				$alumnocredencial = new Alumnoscredencial();
				$alumnocredencial->alumnos_id = $alumno->id;
				$alumnocredencial->credencial_id = $user->CardNumber;
				$alumnocredencial->save();
				}
				}else{
					$this->option="error";
					$this->error="No se puedo establecer comunicacion con el servidor VEREX.";
					break;
				}

			}
			$this->datos = $datos;


		}
	}



 }
?>
