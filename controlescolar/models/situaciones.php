<?php
class Situaciones extends ActiveRecord{
	
	/**
 	* @method array Retorna un arreglo de objetos Situaciones de acuerdo a los privilegios ACL establecidos en la agenda del SP5
 	* @return array Arreglo de objetos ActiveRecord de la clase Situaciones
 	*/
	public function disponiblesEnAgenda(){
		$s = new Situaciones();
		$usr_id = Session :: get_data('usr.id');
		$usr_grupos = Session :: get_data('usr.grupos');
		$situaciones = array();
		
		if( in_array('direccion',  $usr_grupos) || 
			in_array('oficial',    $usr_grupos) ||
			in_array('plantilla',  $usr_grupos)    )
 		{
			$situaciones = $s->find("order: nombre");	
 		}else{
 			$situaciones = $s->find("order: nombre", "conditions: clave = 'REG' ");
 		}
 		
 		return $situaciones;
	}
}
?>
