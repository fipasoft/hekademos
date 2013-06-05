<?php
// Hekademos, Creado el 11/10/2008
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
    $usr_grupos = Session :: get_data('usr.grupos');

    if( in_array('root',  $usr_grupos))
    $controladores=array('agenda','alumnos','asistencias','calificaciones','ciclos','grupos','cursos','usuarios','inscripcion','periodos','optativas','bloques','bloquesalumnos','amonestaciones','reglamentos','articulos');
    elseif( in_array('administradores',  $usr_grupos) )
    $controladores=array('agenda','alumnos','asistencias','calificaciones','ciclos','grupos','cursos','usuarios','inscripcion','periodos','optativas','bloques','bloquesalumnos','amonestaciones','reglamentos','articulos');
    elseif( in_array('oficial',  $usr_grupos) )
    $controladores=array('agenda','alumnos','asistencias','calificaciones','periodos','optativas');
    elseif( in_array('secretarias',  $usr_grupos) )
    $controladores=array('agenda','alumnos','asistencias','calificaciones');
    elseif( in_array('secretario',  $usr_grupos) || in_array('director',  $usr_grupos) )
    $controladores=array('agenda','alumnos','asistencias','calificaciones','grupos','cursos','inscripcion','periodos','optativas','bloques','bloquesalumnos');
    elseif( in_array('plantilla',  $usr_grupos))
    $controladores=array('agenda','grupos','cursos','periodos','optativas');
    elseif( in_array('disciplina',  $usr_grupos))
    $controladores=array('amonestaciones','reglamentos','articulos');
    
    return $controladores;
     }

 }
?>
