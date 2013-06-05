<?php
 /**
 * bloqueslumnos.php
 *
 * Created on 07/05/2009
 * @package  Modelos
 * @author     J Jonathan Lopez <jlopez@fipasoft.com.mx>
 * @copyright  2009 FiPa Software (contacto at fipasoft.com.mx)
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL)
 *
 */

  class Bloquesalumnos extends ActiveRecord{

        public function enBloque($periodo_id,$periodoalumno_id){
            $periodo_id=intval($periodo_id,10);
            $periodoalumno_id=intval($periodoalumno_id,10);
            $alumnobloque=new Bloquesalumnos();
            $alumnobloque=$alumnobloque->find_all_by_sql(
                    "SELECT * FROM
                    periodosalumnos
                    INNER JOIN bloquesalumnos ON periodosalumnos.id=bloquesalumnos.periodosalumnos_id
                    WHERE periodosalumnos.id='".$periodoalumno_id."'  AND periodosalumnos.periodo_id='".$periodo_id."'"
            );

            $registros=count($alumnobloque);
            if($registros>0)return true;
            else
            return false;
        }
  }
?>
