<?php
/*********************************************************************
* All controllers will be extended by this class by a parent level
* due to this methods defined here will available to any controller.
**********************************************************************
* Todas las controladores heredan de esta clase en un nivel superior
* por lo tanto los metodos aqui definidos estan disponibles para
* cualquier controlador.
**********************************************************************/

Kumbia :: import('lib.phpgacl.main');
Kumbia :: import('app.usuario.*');

class ApplicationControllerBase {

    function init() {
        $this->redirect('sesion/autenticar');
    }


    public function before_filter($controlador, $accion, $evento) {
        $this->controlador  =  strtolower($controlador);
        $this->accion       =  strtolower($accion);
        $this->evento = $evento =  strtolower($evento);

        $usr = new Usuario();
        if (Session :: isset_data('usr.id') &&
            Session :: isset_data('usr.login')&&
            Session :: isset_data('usr.nombre'))
        {
            $usr->id = Session :: get_data('usr.id');
            $usr->login = Session :: get_data('usr.login');
            $usr->nombre = Session :: get_data('usr.nombre');
        }

        $this->usr = $usr;
        $acl = new gacl();
        if( !$acl->acl_check('ALL',         'ALL',   'usuarios',  $usr->login) &&
            !$acl->acl_check($controlador,  'ALL',   'usuarios',  $usr->login) &&
            !$acl->acl_check($controlador,  $accion, 'usuarios',  $usr->login)
        ){
            $myLog = new Logger("auth");
            $myLog->log(
                $usr->login . " " .
                Utils :: getRealIP() .
                " " . $controlador . "/" . $accion . " ",
                Logger :: WARNING
            );
            $myLog->close();
            $gacl_x = new gacl_extra();
                  $gacl_x->dbReset();
            $this->redirect('sesion/restringir', 0);
        }
         $gacl_x = new gacl_extra();
          $gacl_x->dbReset();
    }
    
    
    function error( $msg = '', $obj = '', $exc = '' ){
        $controlador = $this->controlador;
        $accion = $this->accion;
        
        $this->option = 'error';
        
        if( $msg != '' ){
            $this->error = $this->_msg = $msg;
        }else{
            $var = '_' . $controlador . '_error';
            $this->error = $this->_msg = $this->$var ;        
        }
        
        if( $obj != '' && is_object( $obj ) ){
            
            try{
                $obj->show_message();
            }catch( Exception $ex ){
                $this->_msg  .= ob_get_contents();
            }
            
        }
        
        if( $exc != '' ){
            $this->_msg .= $exc;
        }

        $myLog = new Logger($controlador . '.' . $accion);
        $myLog->log(
            Session :: get_data( 'usr.login' ) . " " .
            Utils :: getRealIP() .
            " " . $controlador . "/" . $accion . " " .
            $this->_msg
            ,
            Logger :: WARNING
        );
        $myLog->close();
    }
    
    
    function exito( $msj = '', $type = 'html' ){
        
        $this->modo( 'exito' );
        
        if( $type == 'html' ){
            
            $msj = htmlentities( $msj  );
            
        }
        
        $this->exito = $msj;
        
    }

    
    function guardarEnHistorial( $msj, $ciclo_id = '' ){
        
        /*$historial = new Historial();
        $historial->ejercicio_id    =   ( $eje_id != '' ? $eje_id : Session :: get_data( 'eje.id' ) );
        $historial->usuario         =   Session :: get_data( 'usr.login' );
        $historial->descripcion     =   $msj;
        $historial->controlador     =   $this->controlador;
        $historial->accion          =   $this->accion;
        $historial->save();*/
        
    }
    
    
    function modo( $s ){
        
        $this->option = $s;
        
    }

    
    function not_found(){
        $this->route_to('controller: sesion', 'action: restringir');
    }
    
    
}
?>
