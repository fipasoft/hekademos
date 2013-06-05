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
Kumbia :: import('app.permisos.*');

class ApplicationControllerBase {

    function init() {
        $this->redirect('escolar/index');
    }

    public function before_filter($controlador, $accion) {
    $controlador=strtolower($controlador);
    $accion=strtolower($accion);


         if (Session :: isset_data('usr')) {
            $usr = Session :: get_data('usr');

            try
                 {

                     if($usr['id']=="")
                      {

                      throw new Exception("");
                      }
                 }catch (Exception $e){
                 //display custom message
                 $u=new Usuario();
                $usr=array();
                $usr['id']=$u->id;
                $usr['login']=$u->login;
                $usr['grupo']=$u->grupo;
                $usr['nombre']=$u->nombre;
                Session :: set_data('usr',$usr );
                $usr = Session :: get_data('usr');

                 }

        }else{
                $u=new Usuario();
                $usr['id']=$u->id;
                $usr['login']=$u->login;
                $usr['grupo']=$u->grupo;
                $usr['nombre']=$u->nombre;
                Session :: set_data('usr',$usr );
                $usr = Session :: get_data('usr');
        }

        $usr=new Usuario($usr['id'],$usr['login'], $usr['grupo'], $usr['nombre']);


     $acl = new gacl();
        if( !$acl->acl_check('ALL', 'ALL', 'usuarios', $usr->login) &&
            !$acl->acl_check($controlador, 'ALL', 'usuarios', $usr->login) &&
            !$acl->acl_check($controlador, $accion, 'usuarios', $usr->login)
        ){

            $myLog = new Logger("Incidencias");
            //Inicia una transacci�n
            $myLog->begin();
            //Esto queda pendiente hasta que se llame a commit para guardar
            //� rollback para cancelar
            $myLog->log("Intento de acceso no autorizado [Usuario[".$usr->login."]] [Ip[".getRealIP()."]] [Controlador[".$controlador."]] [Accion[".$accion."]]", Logger::WARNING);
            //Se guarda al log
            $myLog->commit();
            //Cierra el Log
            $myLog->close();
            if($controlador=='escolar'){
            //$this->redirect('escolar/restringir');
            }else{
            //$this->redirect('sesion/restringir');
            exit;
            }
        }

        /*$verificador=new Verificador();

          if($verificador->contenidoPublicado($controlador,$accion)==0){

            if($controlador=='escolar'){
            //$this->redirect('escolar/restringir');
            }else{
            //$this->redirect('sesion/restringir');
            }
          }
         * 
         */



        $this->controlador_global=$controlador;
        $this->accion_global=$accion;


        $dir = $_SERVER["REDIRECT_URL"];
        $segmentos = split('[/.-]', $dir);
        $id=$segmentos[5];
        $this->id_global=$id;
        $this->users=$usr;
        if(Session :: isset_data('escolar.ciclo'))
        $this->cicloActivo=Session :: get_data('escolar.ciclo');

    }

    public function not_found(){
            //if($this->controlador_global=='escolar')
            //$this->redirect('escolar/restringir');
            //else
            //$this->redirect('sesion/restringir');
    }
}
?>
