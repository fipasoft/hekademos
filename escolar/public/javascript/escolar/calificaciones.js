function init(){selector=$("selector_ciclos");if(selector!=null){selector.onchange=function(){cargaInfo(this.value);};}}function cargaInfo(value){if(value!=-1&&value.length>0){colocaCapaLoading("infoPrincipal");new Ajax.Request(host_gbl+'escolar/obtenCalificaciones/'+value,{method:'get',onSuccess:function(xml){transforma("infoPrincipal",xml.responseText,'calificaciones.xsl');$$("tr.odd").each(function(input){new Tooltip(input,{backgroundColor:"#FC9",borderColor:"#C96",textColor:"#000",textShadowColor:"#FFF"});});$$("tr.no_odd").each(function(input){new Tooltip(input,{backgroundColor:"#FC9",borderColor:"#C96",textColor:"#000",textShadowColor:"#FFF"});});}});}else alert("Seleccione un ciclo.");}addDOMLoadEvent(init);