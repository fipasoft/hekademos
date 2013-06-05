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
* TIPO DE GARANTIA; sin dejar atr�s su LADO MERCANTIL o PARA FAVORECER ALGUN
* FIN EN PARTICULAR. Lee la licencia publica general para m�s detalles.
* 
* Debes recibir una copia de la Licencia P�blica General GNU junto con este
* framework, si no es asi, escribe a Fundaci�n del Software Libre Inc.,
* 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA 
*****************************************************************************/

require_once "lib/excel/main.php";

$file = md5(uniqid());
$config = Config::read();
$workbook = new Spreadsheet_Excel_Writer("public/temp/$file.xls");
$worksheet =& $workbook->addWorksheet();

$titulo_verdana  =& $workbook->addFormat(array('fontfamily' => 'Verdana', 
                                              'size' => 20));
$titulo_verdana2 =& $workbook->addFormat(array('fontfamily' => 'Verdana', 
                                              'size' => 18));
                                              
$workbook->setCustomColor(12, 0xF2, 0xF2, 0xF2);

$column_title =& $workbook->addFormat(array('fontfamily' => 'Verdana', 
                                            'size' => 12,
                                            'fgcolor' => 12,
                                            'border' => 1,
                                            'bordercolor' => 'black',
                                            "halign" => 'center'
                                            ));
                                            
$column =& $workbook->addFormat(array(    'fontfamily' => 'Verdana', 
                                        'size' => 11,                                        
                                        'border' => 1,
                                        'bordercolor' => 'black',                                        
                                        ));
                                        
$column_centered =& $workbook->addFormat(array(    'fontfamily' => 'Verdana', 
                                        'size' => 11,                                        
                                        'border' => 1,
                                        'bordercolor' => 'black',
                                        "halign" => 'center'
                                        ));                                        

$worksheet->write(0, 0, strtoupper($config->project->name), $titulo_verdana);
$worksheet->write(1, 0, "REPORTE DE ".strtoupper($title), $titulo_verdana2);
$worksheet->write(2, 0, "FECHA ".date("Y-m-d"), $titulo_verdana2);

for($i=0;$i<=count($headerArray)-1;$i++){
    $worksheet->setColumn($i, $i, $weightArray[$i]);    
    $worksheet->write(4, $i, $headerArray[$i], $column_title);
}

$l = 5;
foreach($result as $row){
    for($i=0;$i<=count($row)-1;$i++){        
        if(!is_numeric($row[$i])){
            $worksheet->writeString($l, $i, $row[$i], $column);
        } else {
            $worksheet->writeString($l, $i, $row[$i], $column_centered);
        }
    }
    $l++;
}

$workbook->close();

if(isset($raw_output)){
    print "<script type='text/javascript'> window.open('".KUMBIA_PATH."temp/".$file.".xls', null);  </script>";
} else {
    Generator::forms_print("<script type='text/javascript'> window.open('".KUMBIA_PATH."temp/".$file.".xls', null);  </script>");
}


?>