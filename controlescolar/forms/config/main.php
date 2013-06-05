<?php

/** KumbiaForms - PHP Rapid Development Framework *****************************
*    
* Copyright (C) 2005-2007 Andr�s Felipe Guti�rrez (andresfelipe at vagoogle.net)
*     
* This framework is free software; you can redistribute it and/or
* modify it under the terms of the GNU Lesser General Public
* License as published by the Free Software Foundation; either
* version 2.1 of the License, or (at your option) any later version.
* 
* This framework is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
* Lesser General Public License for more details.
* 
* You should have received a copy of the GNU Lesser General Public
* License along with this framework; if not, write to the Free Software
* Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
* 
* Este framework es software libre; puedes redistribuirlo y/o modificarlo
* bajo los terminos de la licencia p�blica general GNU tal y como fue publicada
* por la Fundaci�n del Software Libre; desde la versi�n 2.1 o cualquier
* versi�n superior.
* 
* Este framework es distribuido con la esperanza de ser util pero SIN NINGUN 
* TIPO DE GARANTIA; dejando atr�s su LADO MERCANTIL o PARA FAVORECER ALGUN
* FIN EN PARTICULAR. Lee la licencia publica general para m�s detalles.
* 
* Debes recibir una copia de la Licencia P�blica General GNU junto con este
* framework, si no es asi, escribe a Fundaci�n del Software Libre Inc.,
* 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA 
*****************************************************************************/

/**
 * Clase para la carga de Archivos .INI y de configuraci�n
 *
 * Aplica el patr�n Singleton que utiliza un array 
 * indexado por el nombre del archivo para evitar que 
 * un .ini de configuraci�n sea leido m�s de una
 * vez en runtime con lo que aumentamos la velocidad.
 * 
 */
class Config {

    static private $instance = array();

    /**
     * El constructor privado impide q la clase sea 
     * instanciada y obliga a usar el metodo read
     * para obtener la instancia del objeto
     *
     */
    private function __construct(){

    }

    /**
     * Constructor de la Clase Config
     *
     * @return Config
     */
    static public function read($file="config.ini"){

        if(isset(self::$instance[$file])){
            return self::$instance[$file];
        }

        $config = new Config();
        $file = escapeshellcmd($file);
        foreach(parse_ini_file('forms/config/'.$file, true) as $conf => $value){
            $config->$conf = new stdClass();
            foreach($value as $cf => $val){                
                $config->$conf->$cf = $val;
            }
        }

        if($file=="config.ini"){
            if(!isset($config->project->mode)){
                if(!isset($config->project)){
                    $config->project = new stdClass();
                }
                $config->project->mode = "production";
            }

            //Carga las variables db del modo indicado
            if(isset($config->{$config->project->mode})){
                foreach($config->{$config->project->mode} as $conf => $value){
                    if(ereg("([a-z0-9A-Z]+)\.([a-z0-9A-Z]+)", $conf, $registers)){
                        if(!isset($config->{$registers[1]})){
                            $config->{$registers[1]} = new stdClass();
                        }
                        $config->{$registers[1]}->{$registers[2]} = $value;
                    } else {
                        $config->$conf = $value;
                    }
                }
            }

            //Carga las variables de [project]
            if(isset($config->project)){
                foreach($config->project as $conf => $value){
                    if(ereg("([a-z0-9A-Z]+)\.([a-z0-9A-Z]+)", $conf, $registers)){
                        if(!isset($config->{$registers[1]})){
                            $config->{$registers[1]} = new stdClass();
                        }
                        $config->{$registers[1]}->{$registers[2]} = $value;
                    } else {
                        $config->$conf = $value;
                    }
                }
            }
        }

        self::$instance[$file] = $config;
        return $config;
    }

}

?>
