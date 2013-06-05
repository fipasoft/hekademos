<?php
 /**
 * periodoscursos.php
 *
 * Created on 05/05/2009
 * @package  Modelos
 * @author     J Jonathan Lopez <jlopez@fipasoft.com.mx>
 * @copyright  2009 FiPa Software (contacto at fipasoft.com.mx)
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL)
 *
 */

  class Periodoscursos extends ActiveRecord{

		public function alumnosinscritos(){
			$alumnos=new Alumnos();
			$alumnos=$alumnos->find_all_by_sql(
						"SELECT alumnos.* FROM " .
						" periodoscursos " .
						" INNER JOIN inscripcion ON periodoscursos.id=inscripcion.periodoscursos_id " .
						" INNER JOIN periodosalumnos ON inscripcion.periodosalumnos_id=periodosalumnos.id " .
						" INNER JOIN alumnos ON periodosalumnos.alumnos_id=alumnos.id " .
						" WHERE periodoscursos.id='".$this->id."'" .
						" ORDER BY alumnos.ap,alumnos.am,alumnos.nombre "
						);
			return $alumnos;
		}

		public function curso(){
			$curso=new Cursos();
			$curso=$curso->find($this->cursos_id);
			return $curso;
		}

		public function inscritosdelGrupo($grupo_id){
			$alumnos=new Alumnos();
			$alumnos=$alumnos->find_all_by_sql(
						"SELECT alumnos.* FROM
						 periodoscursos
						 INNER JOIN inscripcion ON periodoscursos.id=inscripcion.periodoscursos_id
						 INNER JOIN periodosalumnos ON inscripcion.periodosalumnos_id=periodosalumnos.id
						 INNER JOIN alumnos ON periodosalumnos.alumnos_id=alumnos.id
						 INNER JOIN alumnosgrupo ON alumnosgrupo.alumnos_id=alumnos.id
						 WHERE alumnosgrupo.grupos_id='".$grupo_id."'
						 GROUP BY alumnos.id"
						);
			if(is_array($alumnos)){
				return count($alumnos);
			}else{
				return 0;
			}
		}

  }
?>
