<?php
// sp5, Creado el 01/12/2008
/**
 * Asistencias Controller
 *
 * @package    SP5
 * @author     mimeks <mimex@fipasoft.com.mx>
 * @copyright  2008 FiPa Software (contacto at fipasoft.com.mx)
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL)
 *
 */
class SistemaController extends ApplicationController {
     public $template = "system";

     function ayuda(){
         $grupos = Session :: get_data('prof.usr.grupos');
         ksort($grupos);
         if( !is_array($grupos) ){
             $grupos = array();
         }
         $this->grupos = $grupos;
     }

     function index(){
        $this->redirect('inicio', 0);
     }

     function configuracion(){

     }

     function password(){
         $this->option = '';
         $this->error = '';
         $this->exito = '';
         if(!$this->post('pass')){
             $this->option = 'captura';
         }else{
             $usr_id = Session :: get_data('prof.usr.id');
            $usuario = new Profesorespassword();
            $usuario = $usuario->find_first("profesores_id=".$usr_id );
            if($usuario->id != ''){
                if( $usuario->pass == sha1($this->post('pass')) ){
                    if( $this->post('pass2') == $this->post('pass3') ){
                        if(strlen($this->post('pass')) >= 6){
                            $usuario->pass = sha1($this->post('pass2'));
                            if($usuario->save()){
                                $this->option = 'exito';
                            }else{
                                $this->option = 'error';
                                $this->error .= ' Error al guardar en la BD.';
                            }
                        }else{
                            $this->option = 'error';
                            $this->error .= ' La longitud m&iacute;nima del password es de 6 caracteres.';
                        }
                    }else{
                        $this->option = 'error';
                        $this->error .= ' No coincide la confirmaci&oacute;n del password.';
                    }
                }else{
                    $this->option = 'error';
                    $this->error .= ' La contrase&ntilde;a anterior es incorrecta.';
                }
            }else{
                $this->option = 'error';
                $this->error = ' El usuario no existe.';
            }

         }
     }

    public function seleccionar() {
        Session :: set_data('prof.ciclo.id', $this->post('ciclo'));
        $profesor=new Profesores();
        $profesor=$profesor->find(Session :: get_data( 'prof.usr.id'));
        if($profesor->id!=''){
            Session :: set_data( 'prof.usr.tutorias', $profesor->tutordelosgrupos());
        }
        $this->redirect($this->post('controlador') . '/' . $this->post('accion'),0);
    }
}
?>