var inputMetodos = {
    validar: function (elemento){
        var id = elemento.id.split('_');
        id = id[1];
        var celda = $('td_' + id);
        elemento.onblur = function(){
            if(!esEntero(elemento.value)){
                try{
                    elemento.activate();
                    Effect.Shake(elemento);
                }catch(e){
                    alert('Este elemento acepta solo valores enteros.');
                }
                elemento.value = '';
                celda.removeClassName('FALSE');
                celda.removeClassName('TRUE');
            }else{
                var cal = parseInt(elemento.value);
                if(!isNaN(cal)){
                    if(cal > 100){
                        try{
                            elemento.activate();
                            Effect.Shake(elemento);
                        }catch(e){
                            alert('La calificacion maxima es 100.');
                        }                        
                        elemento.value = '';
                        celda.removeClassName('FALSE');
                        celda.removeClassName('TRUE');
                    }else{
                        if(cal >= 60){
                            celda.addClassName('TRUE');
                            celda.removeClassName('FALSE');
                        }else{
                            celda.addClassName('FALSE');
                            celda.removeClassName('TRUE');
                        }
                    }
                }
            }
        }
        return elemento;
    }
};

var selectMetodos = {
        iluminar: function (elemento){
            var id = elemento.id.split('_');
            id = id[1];
            var celda = $('td_' + id);
            
            elemento.onchange = function(){
                if($F(elemento) == 'A'){
                    celda.addClassName('TRUE');
                    celda.removeClassName('FALSE');
                }else if($F(elemento) == 'NA'){
                    celda.addClassName('FALSE');
                    celda.removeClassName('TRUE');
                }
            }
            
            return elemento;
        }
};

function init(){
    var frm = $('frm_agregar');
    if(frm){
        frm.onsubmit = function(){
            if(frm_validar('frm_agregar')){
                Effect.DropOut('botones');
                enEspera();
            }else{
                return false;
            }
        };
    }
    
    cancelar = $('cancelar');
    if(cancelar){
    cancelar.onclick = function(){
        if(confirm('Al cancelar se perderan los cambios hechos en este formulario, desea continuar?')){
            document.location.href = './ver/' + $F('cursos_id');
        }
    }
    }
    
    Element.addMethods('INPUT', inputMetodos);
    $$('input.calificacion').invoke('validar');

    Element.addMethods('SELECT', selectMetodos);
    $$('select.calificacion').invoke('iluminar');
    
}
addDOMLoadEvent(init);