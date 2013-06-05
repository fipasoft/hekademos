<?php
/**
 * ACL
 * Creado el 03/07/2008
 * Copyright (C) 2008 FiPa Software (contacto at fipasoft.com.mx)
 */

Kumbia :: import('lib.phpgacl.main');
Kumbia :: import('app.componentes.*');

class AclController extends ApplicationController {

	public function crear() {
		$acl = new gacl_api();

		$this->privilegios = array ();
		$this->acl_section = array ();
		$this->aco = array ();
		$this->aco_section = array ();
		$this->aro = array ();
		$this->aro_section = array ();
		$this->grupos = array ();
		$this->privilegios = array ();
		$this->lista_acl = array ();
		$this->grupos_asignados = array ();

		/* BD reset
		 * Limpia la base de datos ACL, los registros de la tabla ACLSection no se eliminan...
		 */
		$acl->clear_database();

		$val = $this->acl_section['value'] = 'sistema';
		$this->acl_section['id'] = $acl->add_object_section($val, $val, 0, 0, 'ACL');

		/* ACOs, Creacion de los Access Control Objects
		 * El arreglo posee la siguiente forma
		 * array( ACO_Section1 => array(ACO_1, ..., ACO_N), ... ACO_SectionN => array(...))
		 */
		 // El menu
		$acos = array (
			//menu
			'inicio' => array (
				'index',
				'administrador',
				'director',
				'oficial',
				'plantilla',
				'profesor',
				'secretaria',
				'secretario'
			),
			'catalogos' => array(
				'index'
			),
			'escolar' => array(
				'index'
			),
			'disciplina' => array(
				'index'
			),
			'informacion' => array(
				'index'
			),
			'sistema' => array(
				'ayuda',
				'configuracion',
				'password',
				'seleccionar',
				'index'
			),
		
			// comunes
			'ALL' => array (
				'ALL'
			),
			'sesion' => array (
				'abrir',
				'autenticar',
				'cerrar',
				'index',
				'restringir'
			),
			// SP5
			
			'aulas' => array(
				'agregar',
				'editar',
				'eliminar',
				'index',
				'buscar',
				'exportar',
				'disponible'
			),
			'alumnos' => array (
				'agregar',
				'avisos',
				'comentarios',
				'asignar',
				'buscar',
				'disponible',
				'editar',
				'eliminar',
				'exportar',
				'ubicar',
				'imprimir',
				'index',
				'info',
				'password',
				'ver',
				'kardex',
				'cursos',
				'escolar',
				'importar',
				'trayectoria',
				'amonestaciones',
				'exportar_amonestaciones'
			),
			'amonestaciones' => array(
				'agregar',
				'aprobar',
				'cancelar',
				'editar',
				'eliminar',
				'exportar',
				'index',
				'ficha',
				'ver',
				'obtiene_articulos',
				'obtiene_alumnos',
				'cuenta_alumnos'
			),
			'accesos' => array(
				'generar',
				'exportar',
				'index'
			),
			'importar' => array(
				'index',
				'fotos'
			),
			'asistencias' => array (
				'agregar',
				'editar',
				'eliminar',
				'index',
				'imprimir',
				'justificar',
				'selector',
				'faltas',
				'ver'
			),
			'calificaciones' => array (
				'agregar',
				'editar',
				'eliminar',
				'index',
				'imprimir',
				'selector',
				'ver'
			),
			'parciales' => array (
				'agregar',
				'editar',
				'eliminar',
				'index',
				'imprimir',
				'ver'
			),
			'tutores' => array (
				'agregar',
				'editar',
				'eliminar',
				'imprimir',
				'index',
				'password',
				'ver',
				'exportar',
				'buscar'
			),
			'grupos' => array (
				'asignar',
				'agregar',
				'curso',
				'editar',
				'eliminar',
				'disponible',
				'horario',
				'imprimir',
				'index',
				'ver',
				'generar',
				'exportaramonestaciones',
				'amonestaciones'
			),
			'materias' => array (
				'agregar',
				'disponible',
				'editar',
				'eliminar',
				'enlazar',
				'exportar',
				'index',
				'series',
				'ver',
				'academias'
			),
			'competencias' => array(
				'obtenertipos'
			),
			'profesores' => array (
				'agregar',
				'disponible',
				'editar',
				'eliminar',
				'index',
				'ver',
				'exportar',
				'password',
				'buscar',
				'horario',
				'horarioexcel',
				'laboral'
			),
			'personal' => array (
				'agregar',
				'disponible',
				'editar',
				'eliminar',
				'index',
				'ver',
				'exportar',
				'buscar',
			),
			'tipopersonal' => array(
				'agregar',
				'editar',
				'eliminar',
				'index',
				'buscar',
			),
			'cursos' => array (
				'agregar',
				'editar',
				'exportar',
				'eliminar',
				'imprimir',
				'index',
				'status',
				'ver',
				'buscar',
				'grupo',
				'fecha',
				'grupoexportar',
			 	'copiar'
			),
			'horarios' => array (
				'agregar',
				'editar',
				'eliminar',
				'index',
				'ver',
				'validar'
			),
			'agenda' => array (
				'agregar',
				'index',
				'ver',
				'obtengrupos',
				'obtenusuarios',
				'obtenacos',
				'guarda',
				'editar',
			),
			'ciclos' => array (
				'agregar',
				'abrir',
				'avance',
				'editar',
				'eliminar',
				'index',
				'status',
				'buscar',
				'simulaavance',
				'faltantes'
			),
			'usuarios' => array (
				'agregar',
				'editar',
				'eliminar',
				'index',
				'password',
				'validarLogin',
				'verAcceso',
				'ver',
			),
			'inscripcion' => array(
			'agregar',
			'confirmar',
			'eliminar',
			'articulo'
			),
			'historial'=>array (
				'exportar',
				'index',
				'buscar',
				'ver'
			),
			'estadisticas'=>array(
				'index',
				'asistencias',
				'calificaciones',
				'promedios',
				'aprobadas',
				'aprobacion'
			),
			'es' =>array(
				'index',
				'exportar',
				'dia',
				'inconsistencias'
			),
			'periodos' => array(
			'agregar',
			'editar',
			'eliminar',
			'estadistica',
			'index'
			),
			'optativas' =>array(
			'alumnos',
			'avanzadas',
			'bloques',
			'cupos',
			'eliminar',
			'configuracion',
			'cursos',
			'index',
			'inscritos',
			'inscritosexportar',
			'inscribir',
			'taes',
			'taesinfo',
			'trayectoria',
			'trayectoriasexportar'
			),
			'bloques' => array(
			'agregar',
			'editar',
			'eliminar',
			'eliminartodos',
			'index'
			),
			'bloquesalumnos' => array(
			'agregar',
			'cambio',
			'eliminar',
			'index'
			),
			'reportes' => array(
			'index',
			'resumen',
			'derechos',
			'plantilla',
			'basica'
			),
			'importador' => array(
			'index',
			'taes',
			'curso',
			'grupocursos'
			),
			'plantilla' => array(
			'materias',
			'index',
			'prerregistro',
			'profesores',
			'exportar',
			'profesoreshoras'
			),
			'visitas'=>array (
				'exportar',
				'index',
				'informacion'
			),
			// WP5
			'admin' => array(
				'index'
			),
			'archivo' => array(
				'actualizar',
				'cambiastatus',
				'descargar',
				'editar',
				'eliminar',
				'guardar',
				'index',
				'nuevo'
			),
			'blog' => array(
				'index',
				'gestor'
			),
			'categoriasdescargas' => array(
				'eliminar',
				'actualizar',
				'editar',
				'gestor',
				'guardar',
				'index',
				'nuevo'
			),
			'categoriasmultimedia' => array(
				'actualizar',
				'editar',
				'eliminar',
				'gestor',
				'guardar',
				'index',
				'nuevo'
			),
			'contacto' => array(
				'guardar',
				'sugerencias',
				'index',
				'ubicacion'
			),
			'contenido' => array(
				'gestor',
				'editar',
				'guardar',
				'index',
				'cambiaStatus'
			),
			'director' => array(
				'index',
				'informes',
				'trayectoria',
				'video'
			),
			'descargas' => array(
				'gestor',
				'index'
			),
			'modulo' => array(
				'cambiastatus',
				'editar',
				'gestor',
				'guardar',
				'index'
			),
			'mmf' => array(
				'actualizar',
				'cambiastatus',
				'editar',
				'eliminar',
				'guardar',
				'index',
				'nuevo',
				'vista_previa'
			),
			'multimedia' => array(
				'gestor',
				'index'
			),
			'nuestraprepa' => array(
				'acerca',
				'agregar_comentarios',
				'blog',
				'blog_comentarios',
				'consejo',
				'directorio',
				'index',
				'inicio',
				'mision',
				'normatividad',
				'organigrama',
				'transparencia',
				'vision',
				'iso'
			),
			'post' => array(
				'actualizar',
				'cambiaStatus',
				'cambiaStatusPost',
				'editar',
				'eliminar',
				'eliminarPost',
				'guardar',
				'index',
				'nuevo',
				'vista_previa'
			),
			'servicios' => array(
				'agenda',
				'documentos',
				'descargas',
				'radio',
				'formatos',
				'index'
			),
			'sugerencias' => array(
				'eliminar',
				'historial',
				'index',
				'responder',
				'sugerencia'
			),
			'texto' => array(
				'editar',
				'guardar',
				'index'
			),
			// WEscolar
			'escolar' => array(
				'restringir',
				'accesos',
				'auth',
				'abrir',
				'cerrar',
				'index',
				'ficha',
				'kardex',
				'asistencias',
				'calificaciones',
				'inicio',
				'obtenAsistencias',
				'obtenCalificaciones',
				'password',
				'pdf',
				'horario',
				'agenda',
				'optativas',
				'ver_registro',
				'taes',
				'amonestaciones'
			),
			'controlescolar' => array(
				'index',
				'enviados',
				'borradores',
				'reenviar',
				'notificacion',
				'eliminar_notificacion',
				'gestor',
				'guardar',
				'actualizar',
				'combo'
			),
			'reglamentos' => array(
				'index',
				'agregar',
				'buscar',
				'editar',
				'eliminar',
				'ver',
				'revisa_reglamento'
			),
			'articulos' => array(
				'agregar',
				'editar',
				'eliminar',
				'revisar_numero',
				'buscar',
				'importar',
				'ver'
			)
		);
		$i = 0;
		foreach ($acos as $section => $objects) {
			$this->aco_section[$section] = $acl->add_object_section($section, $section, $i, 0, 'ACO');
			$j = 0;
			foreach ($objects as $sect => $obj) {
				$this->aco[$section][$obj] = $acl->add_object($section, $obj, $obj, $j, 0, 'ACO');
			}
			$i++;
		}

		/* AROs creacion de los Access Request Objects
		 * El arreglo tiene la siguiente forma:
		 * array(ARO_Section1 => array(GROUP => ARO, ..., GROUP_N => ARO_N), ..., ARO_SectionN => array(..))
		 */
		$aros = $lista = array (
			'usuarios' => array (
				'usuarios' => array(
					'anonimo'
				),
				'root' => array(
					'root'
				),
				'administradores' => array(
					'_'
				),
				'disciplina' => array(
					'_'
				),
				'director' => array(
					'_'
				),
				'secretario' => array(
					'_'
				),
				'oficial' => array(
					'_'
				),
				'plantilla' => array(
					'_'
				),
				'secretarias' => array(
					'_'
				),
				'web' => array(
					'visitante'
				),
				'webmaster' => array(
					'_'
				),
				'alumnos' => array(
					'alumno'
				),
				'tutores' => array(
					'tutor'
				)
			)
		);
		$i = 0;
		foreach ($lista as $section => $objects) {
			$this->aro_section[$section] = $acl->add_object_section($section, $section, $i, 0, 'ARO');
			$j = 0;
			foreach ($objects as $objs) {
				foreach($objs as $obj){
					$this->aro[$obj] = $acl->add_object($section, $obj, $obj, $j, 0, 'ARO');
				}
			}
			$i++;
		}

		// Grupos
		/*
		 * Usuarios
		 *  |-Root
		 *  |-Administradores
		 *  |-Direccion
		 * 	| |-Director
		 * 	| '-Secretario
		 *  |-Escolar
		 *  | |-Oficial
		 *  | '-Secretarias
		 *  |-Profesores
		 *  |-Disciplina
		 * 	|-Plantilla
		 *  '-Web
		 *    |-Escolar
		 *    |  |-Alumnos
		 *    |  '-Tutores
		 *    '-Webmaster
		 */
		$lista = array (
			'usuarios' => 0,
			'root' => 'usuarios',
			'administradores' => 'usuarios',
			'direccion' => 'usuarios',
			'director' => 'direccion',
			'secretario' => 'direccion',
			'escolar' => 'usuarios',
			'oficial' => 'escolar',
			'secretarias' => 'escolar',
			'profesores' => 'usuarios',
			'plantilla' => 'usuarios',
			'disciplina' => 'usuarios',
			'web' => 'usuarios',
			'webmaster' => 'web',
			'editores' => 'webmaster',
			'wescolar' => 'web',
			'alumnos' => 'wescolar',
			'tutores' => 'wescolar'
		);
		$this->grupos[0] = 0; // trick para generar la raiz
		foreach ($lista as $group => $parent) {
			$this->grupos[$group] = $acl->add_group($group, $group, $this->grupos[$parent], 'aro');
		}

		/* Privilegios
		 *
		 * Se establece un arreglo con los privilegios por grupo
		 * $this->privilegios[GRUPO][] = array(ACO_SECCION, array(ACOS))
		 */
	 	// grupo usuarios
		$this->privilegios['usuarios'][] = array (
			'sesion' => array(
				'abrir',
				'autenticar',
				'cerrar',
				'index',
				'restringir'
			)
		);
		// grupo root
		$this->privilegios['root'][] = array (
			'ALL' => $acos['ALL']
		);
		// grupo administradores
		$this->privilegios['administradores'][] = array (
			'escolar' => $acos['escolar']
		);
		$this->privilegios['administradores'][] = array (
			'informacion' => $acos['informacion']
		);
		$this->privilegios['administradores'][] = array (
			'catalogos' => $acos['catalogos']
		);
		
		$this->privilegios['administradores'][] = array (
			'agenda' => $acos['agenda']
		);
		$this->privilegios['administradores'][] = array (
			'accesos' => $acos['accesos']
		);
		$this->privilegios['administradores'][] = array (
			'importar' => $acos['importar']
		);
		$this->privilegios['administradores'][] = array (
			'visitas' => $acos['visitas']
		);
		$this->privilegios['administradores'][] = array (
			'historial' => $acos['historial']
		);
		$this->privilegios['administradores'][] = array (
			'aulas' => $acos['aulas']
		);
		$this->privilegios['administradores'][] = array (
			'alumnos' => array (
				'password',
				'index',
				'ver',
				'buscar',
				'importar'
			)
		);
		$this->privilegios['administradores'][] = array (
			'ciclos' => $acos['ciclos']
		);
		$this->privilegios['administradores'][] = array (
			'inicio' => array (
				'administrador',
				'index'
			)
		);
		$this->privilegios['administradores'][] = array (
			'profesores' => array (
				'index',
				'ver',
				'password',
				'buscar'
			)
		);
		$this->privilegios['administradores'][] = array (
			'personal' => $acos['personal']
		);
		$this->privilegios['administradores'][] = array (
			'tipopersonal' => $acos['tipopersonal']
		);
		$this->privilegios['administradores'][] = array (
			'tutores' => array (
				'password',
				'ver',
				'index',
				'buscar'
			)
		);
		$this->privilegios['administradores'][] = array (
			'usuarios' => $acos['usuarios']
		);
		$this->privilegios['administradores'][] = array (
			'sistema' => array (
				'ayuda',
				'configuracion',
				'password',
				'seleccionar',
				'index'
			)
		);

		$this->privilegios['administradores'][] = array (
			'estadisticas' => $acos['estadisticas']
		);

		$this->privilegios['administradores'][] = array (
			'es' => $acos['es']
		);

		$this->privilegios['administradores'][] = array (
			'periodos' => $acos['periodos']
		);

		$this->privilegios['administradores'][] = array (
			'optativas' => $acos['optativas']
		);

		$this->privilegios['administradores'][] = array (
			'bloques' => $acos['bloques']
		);

		$this->privilegios['administradores'][] = array (
			'bloquesalumnos' => $acos['bloquesalumnos']
		);
		
		$this->privilegios['administradores'][] = array (
			'importador' => $acos['importador']
		);
		
		//grupo disciplina
		$this->privilegios['disciplina'][] = array (
			'escolar' => $acos['escolar']
		);
		
		$this->privilegios['disciplina'][] = array (
			'disciplina' => $acos['informacion']
		);
		$this->privilegios['disciplina'][] = array (
			'catalogos' => $acos['catalogos']
		);
		
		$this->privilegios['disciplina'][] = array (
			'amonestaciones' => $acos['amonestaciones']
		);
		$this->privilegios['disciplina'][] = array (
			'reglamentos' => $acos['reglamentos']
		);
		$this->privilegios['disciplina'][] = array (
			'articulos' => $acos['articulos']
		);
		$this->privilegios['disciplina'][] = array (
			'alumnos' => array (
					'index',
					'amonestaciones',
					'exportar_amonestaciones',
					'buscar'
					)	
		);
		$this->privilegios['disciplina'][] = array (
			'grupos' => array (
					'index',
					'amonestaciones',
					'exportaramonestaciones'
					)	
		);
		$this->privilegios['disciplina'][] = array (
			'sistema' => array (
				'ayuda',
				'configuracion',
				'password',
				'seleccionar',
				'index'
			)
		);
		$this->privilegios['disciplina'][] = array (
			'historial' => $acos['historial']
		);

		$this->privilegios['disciplina'][] = array (
			'inicio' => array (
				'index',
				'plantilla'
			)
		);
		
		// grupo direccion
		$this->privilegios['direccion'][] = array (
			'escolar' => $acos['escolar']
		);
		$this->privilegios['direccion'][] = array (
			'informacion' => $acos['informacion']
		);
		$this->privilegios['direccion'][] = array (
			'catalogos' => $acos['catalogos']
		);
		
		$this->privilegios['direccion'][] = array (
			'visitas' => $acos['visitas']
		);
		$this->privilegios['direccion'][] = array (
			'asistencias' => array (
				'ver',
				'editar',
				'eliminar',
				'selector'
				)
		);
		$this->privilegios['direccion'][] = array (
			'calificaciones' => array (
				'ver',
				'editar',
				'eliminar',
				'selector'
				)
		);
		$this->privilegios['direccion'][] = array (
			'alumnos' => array (
				'ver',
				'exportar',
				'index',
				'info',
				'eliminar',
				'kardex',
				'buscar',
				'cursos',
				'ubicar'
			)
		);
		$this->privilegios['direccion'][] = array (
			'cursos' => array (
				'index',
				'status',
				'exportar',
				'buscar',
				'ver',
				'grupo',
				'editar',
				'eliminar',
				'fecha',
				'grupoexportar'

			)
		);
		$this->privilegios['direccion'][] = array (
			'grupos' => array (
				'horario',
				'index',
				'ver',
				'curso'
			)
		);
		$this->privilegios['direccion'][] = array (
			'tutores' => array (
				'index',
				'ver',
				'exportar',
				'buscar'
			)
		);
		$this->privilegios['direccion'][] = array (
			'sistema' => array (
				'ayuda',
				'configuracion',
				'password',
				'seleccionar',
				'index'
			)
		);

		$this->privilegios['direccion'][] = array (
			'inscripcion' => array (
				'agregar',
				'confirmar',
				'eliminar',
				'articulo'
			)
		);

		$this->privilegios['direccion'][] = array (
			'horarios' => $acos['horarios']
		);

		$this->privilegios['direccion'][] = array (
			'estadisticas' => $acos['estadisticas']
		);

		$this->privilegios['direccion'][] = array (
			'profesores' => array(
					'index',
					'ver',
					'exportar',
					'buscar',
					'horario',
				    'horarioexcel'
			)
		);

		$this->privilegios['direccion'][] = array (
			'reportes' => $acos['reportes']
		);


		// grupo director

		$this->privilegios['director'][] = array (
			'inicio' => array (
				'index',
				'director'
			)
		);

		$this->privilegios['director'][] = array (
			'historial' => $acos['historial']
		);

		// grupo secretario
		$this->privilegios['secretario'][] = array (
			'inicio' => array (
				'index',
				'secretario'
			)
		);
		$this->privilegios['secretario'][] = array (
			'historial' => $acos['historial']
		);
		// grupo escolar
		
		$this->privilegios['escolar'][] = array (
			'escolar' => $acos['escolar']
		);
		$this->privilegios['escolar'][] = array (
			'informacion' => $acos['informacion']
		);
		$this->privilegios['escolar'][] = array (
			'catalogos' => $acos['catalogos']
		);
		
		$this->privilegios['escolar'][] = array (
			'alumnos' => array (
				'index',
				'ver',
				'exportar'
			)
		);
		$this->privilegios['escolar'][] = array (
			'asistencias' => array (
				'selector'
			)
		);
		$this->privilegios['escolar'][] = array (
			'calificaciones' => array (
				'selector'
			)
		);
		$this->privilegios['escolar'][] = array (
			'grupos' => array (
				'curso',
				'horario',
				'imprimir',
				'index',
				'ver'
			)
		);
		$this->privilegios['escolar'][] = array (
			'tutores' => array (
				'index',
				'ver'
			)
		);
		// grupo oficial
		$this->privilegios['oficial'][] = array (
			'alumnos' => array (
				'index',
				'asignar',
				'eliminar',
				'agregar',
				'editar',
				'disponible',
				'info',
				'buscar',
				'kardex',
				'cursos',
				'trayectoria'
			)
		);
		$this->privilegios['oficial'][] = array (
			'cursos' => array (
				'index',
				'buscar',
				'ver',
				'exportar'
			)
		);
		$this->privilegios['oficial'][] = array (
			'grupos' => array (
				'asignar',
				'index'
			)
		);
		$this->privilegios['oficial'][] = array (
			'horarios' => array (
				'index',
				'ver'
			)
		);
		$this->privilegios['oficial'][] = array (
			'inicio' => array (
				'index',
				'oficial'
			)
		);
		$this->privilegios['oficial'][] = array (
			'tutores' => array (
				'agregar',
				'editar',
				'eliminar',
				'buscar',
				'exportar'
			)
		);
		$this->privilegios['oficial'][] = array (
			'sistema' => array (
				'ayuda',
				'configuracion',
				'password',
				'seleccionar',
				'index'
			)
		);
		$this->privilegios['oficial'][] = array (
			'historial' => $acos['historial']
		);

		$this->privilegios['oficial'][] = array (
			'estadisticas' => $acos['estadisticas']
		);

		$this->privilegios['oficial'][] = array (
			'reportes' => $acos['reportes']
		);

		$this->privilegios['oficial'][] = array (
			'materias' => array (
				'enlazar',
				'exportar',
				'index',
				'series',
				'ver'
			)
		);
		$this->privilegios['oficial'][] = array (
			'asistencias' => $acos['asistencias']
		);
		$this->privilegios['oficial'][] = array (
			'calificaciones' => $acos['calificaciones']
		);

		$this->privilegios['oficial'][] = array (
			'periodos' => array(
					'index',
					'estadistica'
			)
		);

		$this->privilegios['oficial'][] = array (
			'optativas' => array(
					'index',
					'taesinfo',
					'trayectoria',
					'trayectoriasexportar',
					'inscritos'
			)
		);
		
		
		$this->privilegios['oficial'][] = array(
			'inscripcion' => array(
				'agregar',
				'eliminar'
			)
		);

		// grupo secretarias
		
		$this->privilegios['secretarias'][] = array (
			'alumnos' => array (
				'agregar',
				'buscar',
				'disponible',
				'editar',
				'index',
				'info',
				'kardex',
				'cursos',
				'trayectoria',
				'ubicar',
				'eliminar'
			)
		);
		$this->privilegios['secretarias'][] = array (
			'historial' => $acos['historial']
		);
		$this->privilegios['secretarias'][] = array (
			'asistencias' => $acos['asistencias']
		);
		$this->privilegios['secretarias'][] = array (
			'calificaciones' => $acos['calificaciones']
		);
		$this->privilegios['secretarias'][] = array (
			'inicio' => array (
				'index',
				'secretaria'
			)
		);
		$this->privilegios['secretarias'][] = array (
			'tutores' => array (
				'agregar',
				'editar',
				'eliminar',
				'index',
				'buscar',
				'exportar'
			)
		);
		$this->privilegios['secretarias'][] = array (
			'sistema' => array (
				'ayuda',
				'configuracion',
				'seleccionar',
				'password',
				'index'
			)
		);
		$this->privilegios['secretarias'][] = array (
			'reportes' => $acos['reportes']
		);

		$this->privilegios['secretarias'][] = array (
			'materias' => array (
				'enlazar',
				'exportar',
				'index',
				'series',
				'ver'
			)
		);
		
		$this->privilegios['secretarias'][] = array(
			'cursos' => array(
				'index',
				'ver',
				'buscar'
			)
		);
		
		
		$this->privilegios['secretarias'][] = array (
			'inscripcion' => array (
				'agregar',
				'confirmar',
				'eliminar',
				'articulo'
			)
		);
		
		// grupo profesores
		$this->privilegios['profesores'][] = array (
			'asistencias' => array (
				'agregar',
				'editar',
				'eliminar',
				'index',
				'selector',
				'ver'
			)
		);
		$this->privilegios['profesores'][] = array (
			'calificaciones' => array (
				'agregar',
				'editar',
				'eliminar',
				'index',
				'selector',
				'ver'
			)
		);
		$this->privilegios['profesores'][] = array (
			'grupos' => array (
				'curso',
				'horario',
				'index',
				'ver'
			)
		);
		$this->privilegios['profesores'][] = array (
			'inicio' => array (
				'index',
				'profesor'
			)
		);
		// grupo plantilla
		
		$this->privilegios['plantilla'][] = array (
			'escolar' => $acos['escolar']
		);
		$this->privilegios['plantilla'][] = array (
			'informacion' => $acos['informacion']
		);
		$this->privilegios['plantilla'][] = array (
			'catalogos' => $acos['catalogos']
		);
		
		$this->privilegios['plantilla'][] = array (
			'sistema' => array (
				'ayuda',
				'configuracion',
				'password',
				'seleccionar',
				'index'
			)
		);
		$this->privilegios['plantilla'][] = array (
			'plantilla' => $acos['plantilla']
		);
		$this->privilegios['plantilla'][] = array (
			'historial' => $acos['historial']
		);
		$this->privilegios['plantilla'][] = array (
			'aulas' => $acos['aulas']
		);
		$this->privilegios['plantilla'][] = array (
			'cursos' => array (
				'agregar',
				'exportar',
				'buscar',
				'editar',
				'eliminar',
				'index',
				'status',
				'grupo',
				'fecha',
				'grupoexportar',
				'copiar'

			)
		);
		$this->privilegios['plantilla'][] = array (
			'grupos' => array (
				'agregar',
				'editar',
				'eliminar',
				'disponible',
				'generar',
				'index'
			)
		);
		$this->privilegios['plantilla'][] = array (
			'horarios' => array (
				'validar'
			)
		);
		$this->privilegios['plantilla'][] = array (
			'inicio' => array (
				'index',
				'plantilla'
			)
		);
		$this->privilegios['plantilla'][] = array (
			'materias' => $acos['materias']
		);
		$this->privilegios['plantilla'][] = array (
			'competencias' => $acos['competencias']
		);
		$this->privilegios['plantilla'][] = array (
			'profesores' => array (
				'agregar',
				'editar',
				'eliminar',
				'index',
				'ver',
				'exportar',
				'disponible',
				'buscar',
				'horario',
				'horarioexcel',
				'laboral'
			)
		);
		$this->privilegios['plantilla'][] = array (
			'periodos' => array(
				'index'
			)
		);
		$this->privilegios['plantilla'][] = array (
			'reportes' => array(
				'index',
				'plantilla'
			)
		);

		$this->privilegios['plantilla'][] = array (
			'optativas' => array(
				'index',
				'cursos',
				'cupos',
				'eliminar',
				'taes'
			)
		);

		// grupo web
		$this->privilegios['web'][] = array (
			'admin' => $acos['admin']
		);
		$this->privilegios['web'][] = array (
			'alumnos' => array(
				'comentarios',
				'avisos',
				'index',
				'escolar'
			)
		);
		$this->privilegios['web'][] = array (
			'archivo' => array(
				'descargar'
			)
		);
		$this->privilegios['web'][] = array (
			'contacto' => $acos['contacto']
		);
		$this->privilegios['web'][] = array (
			'director' => $acos['director']
		);
		$this->privilegios['web'][] = array (
			'escolar' => array(
				'restringir',
				'auth',
				'abrir',
				'cerrar'
			)
		);
		$this->privilegios['web'][] = array (
			'nuestraprepa' => $acos['nuestraprepa']
		);
		$this->privilegios['web'][] = array (
			'servicios' => $acos['servicios']
		);
		// grupo webmaster
		$this->privilegios['webmaster'][] = array (
			'sugerencias' => $acos['sugerencias']
		);
		$this->privilegios['webmaster'][] = array (
			'archivo' => $acos['archivo']
		);
		$this->privilegios['webmaster'][] = array (
			'blog' => $acos['blog']
		);
		$this->privilegios['webmaster'][] = array (
			'categoriasdescargas' => $acos['categoriasdescargas']
		);
		$this->privilegios['webmaster'][] = array (
			'categoriasmultimedia' => $acos['categoriasmultimedia']
		);
		$this->privilegios['webmaster'][] = array (
			'contenido' => $acos['contenido']
		);
		$this->privilegios['webmaster'][] = array (
			'controlescolar' => array(
				'index',
				'enviados',
				'borradores',
				'reenviar',
				'notificacion',
				'eliminar_notificacion',
				'gestor',
				'guardar',
				'actualizar',
				'combo'
			)
		);
		$this->privilegios['webmaster'][] = array (
			'descargas' => $acos['descargas']
		);
		$this->privilegios['webmaster'][] = array (
			'mmf' => $acos['mmf']
		);
		$this->privilegios['webmaster'][] = array (
			'modulo' => $acos['modulo']
		);
		$this->privilegios['webmaster'][] = array (
			'multimedia' => $acos['multimedia']
		);
		$this->privilegios['webmaster'][] = array (
			'post' => $acos['post']
		);
		$this->privilegios['webmaster'][] = array (
			'texto' => $acos['texto']
		);
		// grupo editores
		// grupo wescolar
		$this->privilegios['wescolar'][] = array (
			'escolar' => array(
				'accesos',
				'index',
				'ficha',
				'kardex',
				'asistencias',
				'calificaciones',
				'inicio',
				'obtenAsistencias',
				'obtenCalificaciones',
				'password',
				'pdf',
				'horario',
				'agenda',
				'optativas',
				'ver_registro',
				'taes',
				'amonestaciones'
			)
		);
		// grupo alumnos
		// grupo tutores

		// carga los permisos en la lista acl
		$i = 0;
		foreach ($this->privilegios as $grupo => $lst) {
			foreach ($lst as $_acos) {
				$id = $acl->add_acl($_acos, NULL, array (
					$this->grupos[$grupo]
				));
				if ($id !== FALSE) {
					$this->lista_acl[$id] = $grupo . ' ACL ' . $id;
				} else {
					$this->lista_acl[$i] = 'ERROR!';
					$i++;
				}
			}
		}

		// asigna usuarios a los grupos
		foreach ($aros as $sec => $lista) {
			foreach ($lista as $grp => $aros) {
				foreach($aros as $aro){
					$this->grupos_asignados[$grp . ' ' . $sec . '-' . $aro] = $acl->add_group_object($this->grupos[$grp], $sec, $aro);
				}
			}
		}

	}

	public function actualizar(){

	}
}
?>
