<?php
// sp5, Creado el 11/10/2008
/**
 * Dias
 *
 * @package
 * @author     mimeks <mimex@fipasoft.com.mx>
 * @copyright  2008 FiPa Software (contacto at fipasoft.com.mx)
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL)
 *
 */
 class Historial extends ActiveRecord{

     public function obtenControladores(){
    $usr_grupos = Session :: get_data('prof.usr.grupos');

    if( in_array('root',  $usr_grupos))
    $controladores=array('alumnos','asistencias','calificaciones','grupos','cursos');
    elseif( in_array('administradores',  $usr_grupos) )
    $controladores=array('alumnos','asistencias','calificaciones','grupos','cursos');
    elseif( in_array('oficial',  $usr_grupos) )
    $controladores=array('alumnos','asistencias','calificaciones');
    elseif( in_array('secretarias',  $usr_grupos) )
    $controladores=array('alumnos','asistencias','calificaciones');
    elseif( in_array('secretario',  $usr_grupos) || in_array('director',  $usr_grupos) )
    $controladores=array('alumnos','asistencias','calificaciones','grupos','cursos');
    elseif( in_array('plantilla',  $usr_grupos))
    $controladores=array('grupos','cursos');
    elseif(in_array('profesores',  $usr_grupos)){
    $controladores=array('asistencias','calificaciones');
    }

    return $controladores;
     }

 }
?>
