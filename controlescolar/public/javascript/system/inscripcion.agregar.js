function init(){
    agregar = $('agregar');
    if(agregar){
    agregar.onclick = function(){
        ok=false;
        codigos=$$('input.codigo');
        articulos=$$('select.articulo');
        if(codigos.length!=articulos.length){
            alert('El campo codigo y articulo deben ser pares.');
            return ;
        }
        disponibles=$$('input.disponible');

        if(disponibles.length>0){
        disponibles.each(function(campo){
        if(campo.value!=''){ok=true;$('frm_agregar').submit();}
        });
        }else{
        Effect.Shake('alumnos_id');
        }

        if(!ok){
        disponibles=$$('input.disponible');
        disponibles.each(function(campo){
        txt=$(campo.txt);
        if(campo.value=='' || txt.value==''){
        //alert(campo.txt);
        Effect.Shake(campo.txt);
        }
        });
        }


        //if(    frm_validar_campos(['alumnos_id']) ){
        //    f.submit();
        //}
    }
    }

    function frm_validar_alumnos(elementos){
    to=elementos.length;
    for(ind=0;ind<to;ind++){

    }
    }

    cancelar_c = $('cancelar_c');
    if(cancelar_c){
    cancelar_c.onclick = function(){
        if(confirm('Al cancelar se perderan los cambios hechos en este formulario, desea continuar?')){
            document.location.href = '../cursos/';
        }
    }
    }

    cancelar = $('cancelar');
    if(cancelar){
    cancelar.onclick = function(){
        if(confirm('Al cancelar se perderan los cambios hechos en este formulario, desea continuar?')){
            document.location.href = '../../cursos/';
        }
    }
    }
var j = 0;
    var campo = $('alumnos_id');
    if(campo!=null){
    campo.onkeyup = function() {
        /*new Ajax.Updater('check', '../../alumnos/info/', {
           method: 'post',
           onLoading: function(){ $('spinner').show(); $('check').hide(); },
           onComplete: function() { $('spinner').hide(); $('check').show()},
           parameters: {tabla: 'Alumnos', campo: campo.className, valor: campo.value , arreglo : true}
        });*/
        $('spinner').show(); $('check').hide();
        new Ajax.Request('../../alumnos/info/', {
                                            method:'post',
                                            parameters: {tabla: 'Alumnos', campo: campo.className, valor: campo.value , arreglo : true},
                                            onSuccess: function(xml){
                                            pars=xml.responseText.split('|');
                                            j++;
                                            if(pars[1]==null)pars[1]='';
                                            $('check').innerHTML=pars[0]+'<input class="disponible" name="disponible[]" type="hidden" id="disponible'+j+'" value="'+pars[1]+'" />';
                                            $('spinner').hide(); $('check').show();
                                            $('disponible'+j).txt=campo.id;
                                            }
                                            }
                                            );
    }
    }

    mas = $('bMas');
    if(mas){
        var i = 0;
        mas.href = 'javascript:;';
        mas.onclick = function(){
            i++;
            articulos=$('articulo').options;
            var ht = '<br/><label>C&oacute;digo' +
                        '<br /> ' +
                        '<input type="text" name="alumnos_id[]" id="alumnos_id' + i +  '" ' +
                               'size="20" maxlength="20" class="codigo"/>' +
                     '</label><br/>' +
                     '<label>Articulo<br />' +
                     '<select name="articulo[]" id="articulo' + i +  '" class="articulo">';
                     for(ind=0;ind<articulos.length;ind++){
                        art=articulos[ind];
                        ht+='<option value="'+art.value+'">'+art.text+'</option>';
                        }

                       ht+='</select>' +
                       '</label>'+
                     '<img id="spinner' + i +  '" src="../public/img/sp5/spinner.gif" style="display:none"/>' +
                     '<div id="check' + i + '" class="check"></div>' +
                     '<div id="codigos' + (i + 1) + '" class="codigos"></div>';
            $('codigos' + i).update(ht);
            var campo2 = $('alumnos_id' + i);
            campo2.n = i;
            campo2.onkeyup = function() {
                var n = this.n;
                /*ajax = new Ajax.Updater('check' + n, '../alumnos/info/', {
                   method: 'post',
                   onLoading: function(){ $('spinner' + n).show(); $('check' + n).hide(); },
                   onComplete: function() { $('spinner' + n).hide(); $('check' + n).show()},
                   parameters: {tabla: 'Alumnos', campo: campo2.className, valor: campo2.value, arreglo : true}
                });*/
                    $('spinner' + n).show(); $('check' + n).hide();
                    new Ajax.Request('../../alumnos/info/', {
                                            method:'post',
                                            parameters: {tabla: 'Alumnos', campo: this.className, valor: this.value, arreglo : true},
                                            onSuccess: function(xml){
                                            pars=xml.responseText.split('|');
                                            j++;
                                            if(pars[1]==null)pars[1]='';
                                            $('check'+n).innerHTML=pars[0]+'<input class="disponible" name="disponible[]" type="hidden" id="disponible'+j+'" value="'+pars[1]+'" />';
                                            $('spinner'+n).hide(); $('check'+n).show();
                                            $('disponible'+j).txt=campo2.id;

                                            }
                                            }
                                            );
            }
        }
    }

}
addDOMLoadEvent(init);