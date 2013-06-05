<?php
/*
 * Created on 23/09/2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class Sistemas extends ActiveRecord{

 public function usuariodelsistema($sys_id,$usr_id){
 	$sistemausuario=new Sistemasusuarios();
 	return $sistemausuario->exists("sistemas_id='$sys_id' AND usuarios_id='$usr_id'");
 }

 public function existslogin($sys_id,$login){
		if($login != ''){
			$usuarios = new Usuarios();
			$sql="SELECT count(*) FROM usuarios
			INNER JOIN sistemasusuarios ON usuarios.id=sistemasusuarios.usuarios_id
			WHERE sistemasusuarios.sistemas_id='$sys_id' AND login = '" . $login . "'";
		if($usuarios->count_by_sql($sql) == 0){
				return false;
			}else{
				return true;
			}
		}else{
			return false;
		}
 }

 }

?>
