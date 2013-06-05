<?php
class Asignar extends ActiveRecord{
    
    public function nombre(){
        $usuarios = new Usuarios();
        $usuario = $usuarios->find($this->usuarios_id);
        return $usuario->nombre . ' ' . $usuario->ap;
    }
    
    public function login(){
        $usuarios = new Usuarios();
        $usuario = $usuarios->find($this->usuarios_id);
        return $usuario->login;
    }
}
?>
