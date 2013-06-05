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
    $controladores=array('usuarioscoordinacion','tutoresgrupo');
    elseif( in_array('administradores',  $usr_grupos) )
    $controladores=array('usuarioscoordinacion','tutoresgrupo');

    return $controladores;
     }

 }
?>
