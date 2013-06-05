<?php

Kumbia :: import('app.componentes.*');


class ArticulosController extends ApplicationController {
	public $template = "system";

	public function agregar($id = ''){
		if($this->post('numero') == ''){
			$this->option = 'captura';
			$this->reglamento_id = $id;
		}else{
			$articulo = new Articulo();
			$articulo->numero = $this->post('numero');
			$articulo->descripcion = $this->post('descripcion');
			if($articulo->save()){
				$reg_art = new Reglamento();
				$r = new Reglamentos();
				$r = $r->find_first($this->post('reglamento'));
				$reg_art->reglamentos_id = $this->post('reglamento');
				$reg_art->articulo_id = $articulo->id;
				if($reg_art->save()){
					$historial=new Historial();
					$historial->ciclos_id= Session :: get_data('ciclo.id');
					$historial->usuario=Session :: get_data('usr.login');
					$historial->descripcion='Agrego el articulo '.$articulo->numero.' al reglamento '.$r->nombre;
					$historial->controlador="articulos";
					$historial->accion="agregar";
					$historial->saved_at=date("Y-m-d H:i:s");
					$historial->save();
					$this->option = 'exito';
				}else{
					$this->option ='error';
					$this->error = 'Error al guardar en la BD';
				}
			}else{
				$this->option = 'error';
				$this->error = 'Error al guardar en la BD';
			}
		}

	}

	public function editar($reglamento = '', $id = ''){
		$articulo = new Articulo();
		if($id != ''){
			$this->option = 'captura';
			$id = intval($id, 10);
			$this->articulo = $articulo->find($id);
			$this->reglamento_id = $reglamento;
			if($this->articulo->id == ''){
				$this->option = 'error';
				$this->error = ' El c&oacute;digo de articulo no existe.';
			}
		}else if($this->post('id') != ''){
			$articulo = $articulo->find($this->post('id'));
			$articulo->numero = $this->post('numero');
			$articulo->descripcion = $this->post('descripcion');
			if($articulo->save()){
				$reglamentos = new Reglamentos();
				$reglamentos = $reglamentos->find_all_by_sql('select reglamentos.* from
															  articulo inner join reglamento on articulo.id = reglamento.articulo_id
  															  inner join reglamentos on reglamento.reglamentos_id = reglamentos.id
															  where articulo.id = '.$articulo->id);
				$historial=new Historial();
				$historial->ciclos_id= Session :: get_data('ciclo.id');
				$historial->usuario=Session :: get_data('usr.login');
				$historial->descripcion='Edito el articulo '.$articulo->numero.' del reglamento '.$reglamentos[0]->nombre;
				$historial->controlador="articulos";
				$historial->accion="editar";
				$historial->saved_at=date("Y-m-d H:i:s");
				$historial->save();
				$this->option = 'exito';
			}else{
				$this->option = 'error';
				$this->error = 'Error al guardar en la BD';
			}
		}
	}

	public function eliminar($reglamento = '' , $id = ''){
		$articulo = new Articulo();
		if($id != ''){
			$this->option = 'captura';
			$id = intval($id,10);
			$this->articulo = $articulo->find($id);
			$this->reglamento = $reglamento;
			if($this->articulo->id == ''){
				$this->option = 'error';
				$this->error = ' El c&oacute;digo de articulo no existe.';
			}
		}else if($this->post('id') != ''){
			$reg_art = new Reglamento();
			$articulo = new Articulo();
			$articulo = $articulo->find($this->post('id'));
			$reg_art = $reg_art->find_first('articulo_id = '.$articulo->id);
			//var_dump($reg_art);exit;
			$reglamentos = new Reglamentos();
			$reglamentos = $reglamentos->find_all_by_sql('select reglamentos.* from
														  articulo inner join reglamento on articulo.id = reglamento.articulo_id
  														  inner join reglamentos on reglamento.reglamentos_id = reglamentos.id
														  where articulo.id = '.$articulo->id);
			if($articulo->delete()){
				$reg_art->delete();
				$historial=new Historial();
				$historial->ciclos_id= Session :: get_data('ciclo.id');
				$historial->usuario=Session :: get_data('usr.login');
				$historial->descripcion='Elimino el articulo '.$articulo->numero.' del reglamento '.$reglamentos[0]->nombre;
				$historial->controlador="articulos";
				$historial->accion="eliminar";
				$historial->saved_at=date("Y-m-d H:i:s");
				$historial->save();
				$this->option = 'exito';
			}else{
				$this->option = 'error';
				$this->error = 'Error al borrar de la BD';
			}
		}

	}

	public function revisar_numero(){
		$this->set_response('view');
		$articulos = new Articulo();
		$inner = 'Select count(*) from articulo '.
				 'inner join reglamento on reglamento.articulo_id = articulo.id '.
				 "where articulo.numero ='".$this->post('numero')."' and reglamento.reglamentos_id = ".$this->post('reglamento');
		($this->post('anterior') != '' ? $inner.=' and articulo.id != '.$this->post('anterior') : '');
		if($articulos->count_by_sql($inner) > 0){
			$this->disponible = false;
		}else{
			$this->disponible = true;
		}
	}

	public function importar($id){
		if (isset ($_FILES["archivo"])) {
			Kumbia :: import('app.scripts.*');
			$this->option = "confirma";
			$this->reglamento = $reglamento = $this->post('id_reg');
			$ext = strtolower(substr($_FILES["archivo"]['name'], strripos($_FILES["archivo"]['name'], ".") + 1));
			if ($ext == "xls" || $ext == "csv") {
				$this->idFile = time() . "." . $ext;
				$nm = $this->idFile;
				$a = htmlspecialchars("public/files/" . $nm);
				move_uploaded_file($_FILES["archivo"]['tmp_name'], $a);
				$this->importar = new ImportarArticulos($reglamento, $a);
				$this->importar->carga();

			} else {
				$this->option = "error";
				$this->error = "El formato del archivo no es correcto." . $ext;
			}
		}
		elseif ($this->post("id_file") != "") {
			$this->option = "error";
			$this->error = "Importado." . $this->post("id_file");
			$file = "public/files/" . $this->post("id_file");
			if (file_exists($file)) {
				Kumbia :: import('app.scripts.*');
				$this->tutor = $this->post('tutor');
				$this->grupo = $this->post('grupo');

				$this->importar = new ImportarArticulos($this->post('id_reg'), $file);
				$this->importar->carga();
				$this->datos = $this->importar->aBD();

				$this->option = "exito";

			} else {
				$this->option = "error";
				$this->error = "El archivo no es valido.";
			}
		} else {
			$reglamento = new Reglamentos();
			$this->reglamento = $reglamento->find($id);
			$this->option = "captura";
		}

	}
	
	public function ver($id = ''){
		$articulo = new Articulo();
		$articulo = $articulo->find(intval($id,10));
		if($articulo->id != ''){
			$this->option = 'vista';
			$this->articulo = $articulo;
			// acl
			$usr_login = Session :: get_data('usr.login');
			$this->acl = array ();
			$acl = new gacl_extra();
			$acos_arr = array (
				'articulos' => array (
					'editar',
					'eliminar',
					)
					);
					$this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);
		}else{
			$this->option = 'error';
			$this->error = 'El id de articulo no es v&aacute;lido.';
		}		
	}

}



?>