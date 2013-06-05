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
*
*****************************************************************************
* Functional Flickr Search using RSS
* You need to have enabled PHP DOM Extension
*****************************************************************************/
    
	class Flickr {
	  
	  		public $rss;
	  		public $key;
	  		
	  		function Flickr($key=''){
				$this->key = $key;
			}
						 	  	  	
	  		function photos($tags, $num=1){
  		    	$thumbs = array();
	  		    $docXML = new DOMDocument();	  		        			    				  		        
				$this->rss = file_get_contents("http://www.flickr.com/services/feeds/photos_public.gne?tags=$tags&format=rss_200");		    					  	
				$docXML->loadXML($this->rss);																	
				$i = 0;			
				foreach ($docXML->getElementsByTagNameNS('http://search.yahoo.com/mrss/', '*') as $element) {
					if($element->localName=="thumbnail"){
						$thumbs[$i]['thumb'] = $element->getAttribute("url");
						$i++;
					}
					if($element->localName=="content"){
						$thumbs[$i]['content'] = $element->getAttribute("url");
					}
				}							 																	
				return $thumbs;
			}
	  		
	}

?>