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
****************************************************************************
* Some PHP Utility Functions
****************************************************************************/

/**
 * Merge Two Arrays Overwriting Values $a1
 * from $a2
 *
 * @param array $a1
 * @param array $a2
 * @return array
 */
function array_merge_overwrite($a1, $a2){
    foreach($a2 as $key2 => $value2){
          if(!is_array($value2)){
            $a1[$key2] = $value2;
        } else {
          if(!is_array($a1[$key2]))
                  $a1[$key2] = $value2;
          else $a1[$key2] = array_merge_overwrite($a1[$key2], $a2[$key2]);
        }
    }
    return $a1;
}

/**
 * Inserts a element into a defined position
 * in a array
 *
 * @param array $form
 * @param mixed $index
 * @param mixed $value
 * @param mixed $key
 */
function array_insert(&$form, $index, $value, $key=null){
    $ret = array();
    $n = 0;
    $i = false;
    foreach($form as $keys => $val){
        if($n!=$index){
            $ret[$keys] = $val;
        } else {
              if(!$key){
                $ret[$index] = $value;
                $i = true;
            } else {
                $ret[$key] = $value;
                $i = true;
            }
            $ret[$keys] = $val;
        }
        $n++;
    }
    if(!$i){
        if(!$key){
            $ret[$index] = $value;
            $i = true;
        } else {
            $ret[$key] = $value;
            $i = true;
        }
    }
    $form = $ret;
}

/**
 * Las siguientes funciones son utilizadas para la generaci�n
 * de versi�nes escritas de numeros
 *
 * @param numeric $a
 * @return string
 */
function value_num($a){
  if($a<=21){
    switch ($a){
          case 1: return 'UNO';
          case 2: return 'DOS';
          case 3: return 'TRES';
          case 4: return 'CUATRO';
          case 5: return 'CINCO';
          case 6: return 'SEIS';
          case 7: return 'SIETE';
          case 8: return 'OCHO';
          case 9: return 'NUEVE';
          case 10: return 'DIEZ';
          case 11: return 'ONCE';
          case 12: return 'DOCE';
          case 13: return 'TRECE';
          case 14: return 'CATORCE';
          case 15: return 'QUINCE';
          case 16: return 'DIECISEIS';
          case 17: return 'DIECISIETE';
          case 18: return 'DIECIOCHO';
          case 19: return 'DIECINUEVE';
          case 20: return 'VEINTE';
          case 21: return 'VEINTIUN';
    }
  } else {
    if($a<=99){
        if($a>=22&&$a<=29)
              return "VENTI".value_num($a % 10);
          if($a==30) return  "TREINTA";
          if($a>=31&&$a<=39)
              return "TREINTA Y ".value_num($a % 10);
          if($a==40) $b = "CUARENTA";
          if($a>=41&&$a<=49)
              return "CUARENTA Y ".value_num($a % 10);
          if($a==50) return "CINCUENTA";
          if($a>=51&&$a<=59)
              return "CINCUENTA Y ".value_num($a % 10);
          if($a==60) return "SESENTA";
          if($a>=61&&$a<=69)
              return "SESENTA Y ".value_num($a % 10);
          if($a==70) return "SETENTA";
          if($a>=71&&$a<=79)
              return "SETENTA Y ".value_num($a % 10);
          if($a==80) return "OCHENTA";
          if($a>=81&&$a<=89)
              return "OCHENTA Y ".value_num($a % 10);
          if($a==90) return "NOVENTA";
          if($a>=91&&$a<=99)
              return "NOVENTA Y ".value_num($a % 10);
    } else {
          if($a==100) return "CIEN";
          if($a>=101&&$a<=199)
              return "CIENTO ".value_num($a % 100);
          if($a>=200&&$a<=299)
              return "DOSCIENTOS ".value_num($a % 100);
          if($a>=300&&$a<=399)
              return "TRECIENTOS ".value_num($a % 100);
          if($a>=400&&$a<=499)
              return "CUATROCIENTOS ".value_num($a % 100);
          if($a>=500&&$a<=599)
              return "QUINIENTOS ".value_num($a % 100);
          if($a>=600&&$a<=699)
              return "SEICIENTOS ".value_num($a % 100);
          if($a>=700&&$a<=799)
              return "SETECIENTOS ".value_num($a % 100);
          if($a>=800&&$a<=899)
              return "OCHOCIENTOS ".value_num($a % 100);
          if($a>=901&&$a<=999)
              return "NOVECIENTOS ".value_num($a % 100);
    }
  }
}

function millones($a){
      $a = $a / 1000000;
      if($a==1)
        return "UN MILLON ";
    else
        return value_num($a)." MILLONES ";
}

function miles($a){
      $a = $a / 1000;
      if($a==1)
        return "MIL";
    else
        return value_num($a)."MIL ";
}

function numlet($a, $p, $c){
      $val = "";
      $v = $a;
      $a = (int) $a;
      $d = round($v - $a, 2);
    if($a>=1000000){
          $val = millones($a - ($a % 1000000));
          $a = $a % 1000000;
    }
    if($a>=1000){
          $val.= miles($a - ($a % 1000));
          $a = $a % 1000;
    }
    $val.= value_num($a)." $p ";
    if($d){
        $d*=100;
        $val.=" CON ".value_num($d)." $c ";
    }
    return $val;
}

function money_letter($valor, $moneda, $centavos){
    return numlet($valor, $moneda, $centavos);
}


function to_human($num){
    if($num<1024){
        return $num." bytes";
    } else {
        if($num<1024*1024){
            return round($num/1024, 2)." kb";
        } else {
            return round($num/1024/1024, 2)." mb";
        }
    }
}

function generar_fecha_hora(){
    $dia_espanol = dia_espanol(date('w'));
    $fecha_espanol = fecha_espanol(date('Y-m-j'));
    $hora = date('H:i');
    return '<b>'.$dia_espanol.',</b> '.$fecha_espanol.'<br />';
}// fin de generar_fecha_hora

function convierteFecha($fecha){
    $i=explode("-",$fecha);
     $i=$i[2]."/".$i[1]."/".$i[0];
     return $i;
}

function fecha_espanol($f){
    $fecha = explode('-',$f);
    switch($fecha[1]){
        case '01':    $mes = 'Enero';    break;
        case '02':    $mes = 'Febrero'; break;
        case '03':    $mes = 'Marzo'; break;
        case '04':    $mes = 'Abril'; break;
        case '05':    $mes = 'Mayo'; break;
        case '06':    $mes = 'Junio'; break;
        case '07':    $mes = 'Julio'; break;
        case '08':    $mes = 'Agosto'; break;
        case '09':    $mes = 'Septiembre'; break;
        case '10':    $mes = 'Octubre'; break;
        case '11':    $mes = 'Noviembre'; break;
        case '12':    $mes = 'Diciembre'; break;
        default : $mes='';
    }

    if(trim($f)!=""){
    if(substr($fecha[2],0,1)=='0')
    $fecha[2]=substr($fecha[2],1);
    $f = $fecha[2]." de ".$mes." de ".$fecha[0];
    }else
    $f="";

    return $f;
}

function mes_espanol($f){
    switch($f){
        case '01':    $mes = 'Enero';    break;
        case '02':    $mes = 'Febrero'; break;
        case '03':    $mes = 'Marzo'; break;
        case '04':    $mes = 'Abril'; break;
        case '05':    $mes = 'Mayo'; break;
        case '06':    $mes = 'Junio'; break;
        case '07':    $mes = 'Julio'; break;
        case '08':    $mes = 'Agosto'; break;
        case '09':    $mes = 'Septiembre'; break;
        case '10':    $mes = 'Octubre'; break;
        case '11':    $mes = 'Noviembre'; break;
        case '12':    $mes = 'Diciembre'; break;
        default : $mes='';
    }
    return $mes;
}

function dia_espanol($d){
    switch($d){
        case '0':    $dia = 'Domingo'; break;
        case '1':    $dia = 'Lunes';    break;
        case '2':    $dia = 'Martes'; break;
        case '3':    $dia = 'Mi&eacute;rcoles'; break;
        case '4':    $dia = 'Jueves'; break;
        case '5':    $dia = 'Viernes'; break;
        case '6':    $dia = 'S&aacute;bado'; break;
        default : $dia='';
    }
    return $dia;
}

function dia_semana($datetime){
$arr = explode("-", $datetime);
$var= date("w", mktime(0,0,0, $arr[1], $arr[2], $arr[0]));
switch($var) {
   case 0: return "Domingo";
              break;
   case 1: return "Lunes";
              break;
   case 2: return "Martes";
              break;
   case 3: return "Miercoles";
              break;
   case 4: return "Jueves";
              break;
   case 5: return "Viernes";
              break;
   case 6: return "Sábado";
              break;
}
}

function textoPlano($text){
$tofind = "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ";
$replac = "AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn";
$text=(strtr($text,$tofind,$replac));

$acentos=array("á","é","í","ó","ú","Ã¡","Ã©","Ã","Ã³","Ãº");
$rem=array("a","e","i","o","u","a","e","i","o","u");
$text=str_replace($acentos,$rem,$text);
return $text;
}

function idValido($text){
$text=textoPlano($text);
$text=str_replace(" ","_",$text);
return $text;
}

function TemplateAdministrativo() {
    $dir = $_SERVER["REDIRECT_URL"];
    $segmentos = split('[/.-]', $dir);
    $c=strtolower($segmentos[3]);
    $a=strtolower($segmentos[4]);
    switch ($c) {
        case "modulo" :
            return 1;
        case "contenido" :
            return 1;
        case "archivo" :
            return 1;
        case "categoriasdescargas" :
            return 1;
        case "categoriasmultimedia" :
            return 1;
        case "descargas" :
            return 1;
        case "mmf" :
            return 1;
        case "multimedia" :
            return 1;
        case "sugerencias" :
            return 1;
        case "texto" :
            return 1;
        case "sesion":
            if($a=="restringir")
            return 0;
            else
            return 1;

        case "admin":
            return 1;
        case "blog":
            return 1;
        case "post":
            return 1;
        default :

            return 0;
    }
}

function TemplateAdministrativo1($c) {
    switch ($c) {
        case "modulo" :
            return 1;
        case "contenido" :
            return 1;
        case "archivo" :
            return 1;
        case "categoriasdescargas" :
            return 1;
        case "categoriasmultimedia" :
            return 1;
        case "descargas" :
            return 1;
        case "mmf" :
            return 1;
        case "multimedia" :
            return 1;
        case "sugerencias" :
            return 1;
        case "texto" :
            return 1;
        case "sesion":
            return 0;


        case "admin":
            return 1;
        case "blog":
            return 1;
        case "post":
            return 1;
        default :

            return 0;
    }
}

function getRealIP()

{



   if( $_SERVER['HTTP_X_FORWARDED_FOR'] != '' )

   {

      $client_ip =

         ( !empty($_SERVER['REMOTE_ADDR']) ) ?

            $_SERVER['REMOTE_ADDR']

            :

            ( ( !empty($_ENV['REMOTE_ADDR']) ) ?

               $_ENV['REMOTE_ADDR']

               :

               "unknown" );



      // los proxys van añadiendo al final de esta cabecera

      // las direcciones ip que van "ocultando". Para localizar la ip real

      // del usuario se comienza a mirar por el principio hasta encontrar

      // una dirección ip que no sea del rango privado. En caso de no

      // encontrarse ninguna se toma como valor el REMOTE_ADDR



      $entries = split('[, ]', $_SERVER['HTTP_X_FORWARDED_FOR']);



      reset($entries);

      while (list(, $entry) = each($entries))

      {

         $entry = trim($entry);

         if ( preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $ip_list) )

         {

            // http://www.faqs.org/rfcs/rfc1918.html

            $private_ip = array(

                  '/^0\./',

                  '/^127\.0\.0\.1/',

                  '/^192\.168\..*/',

                  '/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/',

                  '/^10\..*/');



            $found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);



            if ($client_ip != $found_ip)

            {

               $client_ip = $found_ip;

               break;

            }

         }

      }

   }

   else

   {

      $client_ip =

         ( !empty($_SERVER['REMOTE_ADDR']) ) ?

            $_SERVER['REMOTE_ADDR']

            :

            ( ( !empty($_ENV['REMOTE_ADDR']) ) ?

               $_ENV['REMOTE_ADDR']

               :

               "unknown" );

   }



   return $client_ip;



}

function separar_nombre($nom){
    $arr = explode(' ', $nom);
    $nombre = array();
    $i = 0;
    foreach($arr as $palabra){
        $nombre[$i] .= ($nombre[$i] != '' ? ' ' : '').$palabra;
        if($i < 2 && $palabra != 'DEL' && $palabra != 'DE' && $palabra != 'LA'  && $palabra != 'LOS'){
            $i++;
        }
    }
    return $nombre;
}
function endsWith( $str, $sub ) {
return ( substr( $str, strlen( $str ) - strlen( $sub ) ) == $sub );
}
?>