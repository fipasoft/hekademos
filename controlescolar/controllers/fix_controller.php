<?php
/** SP5
 * Creado el 03/07/2008
 * Copyright (C) 2008 FiPa Software (contacto at fipasoft.com.mx)
 */

class FixController extends ApplicationController {
	public $template = "system";

	public function codigos($t){
	    try{
            if($t == 'a'){
                mysql_query("BEGIN") or die("Error al iniciar la transaccion");
        	    $alumnos = new Alumnos();
        		$alumnos = $alumnos->find();
                foreach ($alumnos as $alumno) {
                    do{
                        
                        $c = rand ( 200000000 , 500000000 );
                        $a = new Alumnos();
                        $a = $a->find_first("codigo = '".$c."'");
                        
                    }while($a->id != "");
                    
                    $alumno->am = ($alumno->am==""? " " : $alumno->am);
                    $alumno->ap = ($alumno->ap==""? " " : $alumno->ap); 
                    $alumno->codigo = $c;
                    
                    if(!$alumno->save()){
                        throw new Exception("Error al guardar el alumno con codigo " . $alumno->codigo);
                    }
                }
                
                mysql_query("COMMIT") or die("Error al terminar la transaccion");
                var_dump("Cambio de codigos en alumnos.");exit;
            }elseif($t == 'p'){
                mysql_query("BEGIN") or die("Error al iniciar la transaccion");
                $profesores = new Profesores();
                $profesores = $profesores->find();
                foreach ($profesores as $profesor) {
                    do{
                        
                        $c = rand ( 2000000 , 5000000 );
                        $p = new Profesores();
                        $p = $p->find_first("codigo = '".$c."'");
                        
                    }while($p->id != "");
                    
                    $profesor->codigo = $c;
                    
                    if(!$profesor->save()){
                        throw new Exception("Error al guardar el alumno con codigo " . $alumno->codigo);
                    }
                }
                
                mysql_query("COMMIT") or die("Error al terminar la transaccion");
                var_dump("Cambio de codigos en profesores.");exit;
            }
        }catch(Exception $e){
            mysql_query("ROLLBACK") or die("Error al finalizar la transaccion");
            var_dump("ERROR: " . $e->getMessage());
            exit;
        }
	}

    public function nombres($t){
        try{
            if($t == 'a'){
                mysql_query("BEGIN") or die("Error al iniciar la transaccion");
                $apellidos = array();
                $alumnos = new Alumnos();
                $alumnos = $alumnos->find();
                
                foreach ($alumnos as $alumno) {
                    $ap = trim(str_replace("_", "",str_replace(".", "",str_replace(",", "", $alumno->am))));
                    if(!in_array($ap, $apellidos)){
                        if($ap!=""){
                            $apellidos[] = $ap;
                        }
                    }
                    
                    $ap = trim(str_replace("_", "",str_replace(".", "",str_replace(",", "", $alumno->ap))));
                    if(!in_array($ap, $apellidos)){
                        if($ap!=""){
                            $apellidos[] = $ap;
                        }
                    }
                    
                }
                
                $total = count($apellidos);
                
                foreach ($alumnos as $alumno) {
                       
                    $alumno->am = strtoupper($apellidos[rand(0, $total-1)]);
                    $alumno->ap = strtoupper($apellidos[rand(0, $total-1)]) ;
                    $alumno->nombre = strtoupper($alumno->nombre);
                    
                    if(!$alumno->save()){
                        throw new Exception("Error al guardar el alumno con codigo " . $alumno->codigo);
                    }
                }
                
                mysql_query("COMMIT") or die("Error al terminar la transaccion");
                var_dump("Cambio de apellidos en alumnos.");exit;
            }elseif($t == 'p'){
                mysql_query("BEGIN") or die("Error al iniciar la transaccion");
                $apellidos = array();
                $alumnos = new Alumnos();
                $alumnos = $alumnos->find();
                
                foreach ($alumnos as $alumno) {
                    $ap = trim(str_replace("_", "",str_replace(".", "",str_replace(",", "", $alumno->am))));
                    if(!in_array($ap, $apellidos)){
                        if($ap!=""){
                            $apellidos[] = $ap;
                        }
                    }
                    
                    $ap = trim(str_replace("_", "",str_replace(".", "",str_replace(",", "", $alumno->ap))));
                    if(!in_array($ap, $apellidos)){
                        if($ap!=""){
                            $apellidos[] = $ap;
                        }
                    }
                    
                }
                
                $total = count($apellidos);
                
                $profesores = new Profesores();
                $profesores = $profesores->find();
                foreach ($profesores as $profesor) {
                   
                    $profesor->am = strtoupper($apellidos[rand(0, $total-1)]);
                    $profesor->ap = strtoupper($apellidos[rand(0, $total-1)]) ;
                    $profesor->nombre = strtoupper($profesor->nombre);
                    
                    if(!$profesor->save()){
                        throw new Exception("Error al guardar el alumno con codigo " . $alumno->codigo);
                    }
                }
                
                mysql_query("COMMIT") or die("Error al terminar la transaccion");
                var_dump("Cambio de apellidos en profesores.");exit;
            }elseif($t == 't'){
                mysql_query("BEGIN") or die("Error al iniciar la transaccion");
                $nombres = array();
                $alumnos = new Alumnos();
                $alumnos = $alumnos->find();
                
                foreach ($alumnos as $alumno) {
                    $ap = $alumno->nombre;
                    if(!in_array($ap, $nombres)){
                        if($ap!=""){
                            $nombres[] = $ap;
                        }
                    }
                }
                    
                $total = count($nombres);
                
                $tutores = new Tutores();
                $tutores = $tutores->find();
                
                foreach ($tutores as $tutor) {
                    $tutoria = new Tutoria();
                    $tutoria = $tutoria->find_first("tutores_id = '".$tutor->id."'");
                    
                    $alumno = new Alumnos();
                    $alumno = $alumno->find_first($tutoria->alumnos_id);
                    
                    $tutor->am = strtoupper($alumno->am);
                    $tutor->ap = strtoupper($alumno->ap) ;
                    $tutor->nombre = strtoupper($nombres[rand(0, $total-1)]) ;
                    
                    if(!$tutor->save()){
                        throw new Exception("Error al guardar el alumno con codigo " . $tutor->id);
                    }
                }
                
                mysql_query("COMMIT") or die("Error al terminar la transaccion");
                var_dump("Cambio de apellidos en tutores.");exit;
            }
        }catch(Exception $e){
            mysql_query("ROLLBACK") or die("Error al finalizar la transaccion");
            var_dump("ERROR: " . $e->getMessage());
            exit;
        }
    }

    public function password($t){
        try{
            if($t == 'a'){
                mysql_query("BEGIN") or die("Error al iniciar la transaccion");
                $alumnos = new Alumnos();
                $alumnos = $alumnos->find();
                foreach ($alumnos as $alumno) {
                    $pss = new Alumnospassword();
                    $pss = $pss->find_first("alumnos_id='".$alumno->id."'");
                    
                    $pss->pass = sha1('hekademos');
                    if(!$pss->save()){
                        throw new Exception("Error al guardar el alumno con codigo " . $alumno->codigo);
                    }
                }
                
                mysql_query("COMMIT") or die("Error al terminar la transaccion");
                var_dump("Cambio de password en alumnos.");exit;
            }elseif($t == 'p'){
                mysql_query("BEGIN") or die("Error al iniciar la transaccion");
                $profesores = new Profesores();
                $profesores = $profesores->find();
                foreach ($profesores as $profesor) {
                    $pss = new Profesorespassword();
                    $pss = $pss->find_first("profesores_id='".$profesor->id."'");
                    if($pss->id == ""){
                        $pss = new Profesorespassword();
                        $pss->profesores_id = $profesor->id;
                    }
                    
                    $pss->pass = sha1('hekademos');
                    
                    if(!$pss->save()){
                        throw new Exception("Error al guardar el alumno con codigo " . $alumno->codigo);
                    }
                }
                
                mysql_query("COMMIT") or die("Error al terminar la transaccion");
                var_dump("Cambio de password en profesores.");exit;
            }elseif($t == 't'){
                mysql_query("BEGIN") or die("Error al iniciar la transaccion");
                $tutores = new Tutores();
                $tutores = $tutores->find();
                foreach ($tutores as $tutor){
                    $pss = new Tutorespassword();
                    $pss = $pss->find_first("tutores_id='".$tutor->id."'");
                    
                    $pss->pass = sha1('hekademos');
                    if(!$pss->save()){
                        throw new Exception("Error al guardar el alumno con codigo " . $alumno->codigo);
                    }
                }
                
                mysql_query("COMMIT") or die("Error al terminar la transaccion");
                var_dump("Cambio de password en tutores.");exit;
            }
        }catch(Exception $e){
            mysql_query("ROLLBACK") or die("Error al finalizar la transaccion");
            var_dump("ERROR: " . $e->getMessage());
            exit;
        }
    }

    public function foto($t){
        try{
            if($t == 'a'){
                mysql_query("BEGIN") or die("Error al iniciar la transaccion");
                $alumnos = new Alumnos();
                $alumnos = $alumnos->find();
                foreach ($alumnos as $alumno) {
                    
                    $alumno->foto = "";
                    if(!$alumno->save()){
                        throw new Exception("Error al guardar el alumno con codigo " . $alumno->codigo);
                    }
                }
                
                mysql_query("COMMIT") or die("Error al terminar la transaccion");
                var_dump("Cambio de fotos en alumnos.");exit;
            }elseif($t == 'p'){
                mysql_query("BEGIN") or die("Error al iniciar la transaccion");
                $profesores = new Profesores();
                $profesores = $profesores->find();
                foreach ($profesores as $profesor) {
                    
                    $profesor->foto = "";
                    
                    if(!$profesor->save()){
                        throw new Exception("Error al guardar el alumno con codigo " . $alumno->codigo);
                    }
                }
                
                mysql_query("COMMIT") or die("Error al terminar la transaccion");
                var_dump("Cambio de fotos en profesores.");exit;
            }elseif($t == 't'){
                mysql_query("BEGIN") or die("Error al iniciar la transaccion");
                $tutores = new Tutores();
                $tutores = $tutores->find();
                foreach ($tutores as $tutor){
                    
                    $tutor->foto = "";
                    
                    if(!$tutor->save()){
                        throw new Exception("Error al guardar el alumno con codigo " . $alumno->codigo);
                    }
                }
                
                mysql_query("COMMIT") or die("Error al terminar la transaccion");
                var_dump("Cambio de fotos en tutores.");exit;
            }
        }catch(Exception $e){
            mysql_query("ROLLBACK") or die("Error al finalizar la transaccion");
            var_dump("ERROR: " . $e->getMessage());
            exit;
        }
    }

}
?>
