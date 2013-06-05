<?php
class Usuario {
    public $id;
    public $login;
    public $nombre;
    public $grupo;
    
    function Usuario($id = NULL, $login = 'visitante', $grupo = 'web', $nombre = NULL) {
        $this->id = $id;
        $this->login = $login;
        $this->nombre = $nombre;
        $this->grupo = $grupo;
    }
}
?>