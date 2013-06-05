function init(){
    aceptar = $('aceptar');
    if(aceptar){
    aceptar.onclick = function(){
        if(frm_validar('frm_captura')){
            if($('archivo').value.toLowerCase().endsWith(".xls")){
            $('frm_captura').submit();
            $('spinner1').show();
            $("upload_target").onload = function(){
                $('spinner1').hide();
                var respuesta = window.frames["upload_target"].document.getElementsByTagName("body")[0].innerHTML;
                Modalbox.show(respuesta,
                        {title: "<h1>Importar alumnos a TAE</h1>", width: 600, height: 700,
                        afterLoad: function() {
                            $('spinner1').hide();
                                    $("importar").onclick = function(){ 
                                        var valores = "";
                                        $$('.valor').each(function(element) {
                                              if (element.checked){
                                                  valores+= element.value + ",";
                                              }
                                        });
                                        
                                          valores = valores.substring(0,valores.length - 1)
                                        if(valores!=""){
                                        new Ajax.Updater('contenido', $('kumbia_path').value + "importador/taes/", { 
                                               method: 'post',
                                               onLoading: function(){  $('spinner').show();  $('contenido').hide(); },
                                               onComplete: function() { $('spinner').hide(); $('contenido').show()},
                                               parameters: {did: $('did').value, alumnos: valores }
                                            });
                                        }else{
                                            try{
                                                var elemento = $$('.valor')[$$('.valor').length-1];
                                                elemento.focus();
                                                Effect.Shake(elemento);
                                            }catch(e){
                                                alert('No se ha seleccionado ningun alumno.' + e);
                                            }
                                        }
                                        };
                                    

                                    $("cancelar2").onclick = function(){
                                                    Modalbox.hide();
                                        };
                                    }
                        });
                
            }
            }else{
            Modalbox.show('<p style="font-size:16px;">El formato del archivo no es valido.<br/> El archivo tiene que ser del tipo .xls</p>',{title: "Error de formato", width: 300});

            }

        }
    }
    }
    
    cancelar = $('cancelar');
    if(cancelar){
    cancelar.onclick = function(){
            document.location.href = $('kumbia_path').value + "importador"
        }
    
    }
}
addDOMLoadEvent(init);
