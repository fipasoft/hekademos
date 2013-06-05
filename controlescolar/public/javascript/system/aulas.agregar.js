function validarAula(){
    c = $('clave');
    n = $('nombre');
    if(c && n){
        new Ajax.Updater('check', './disponible/', {
               method: 'post',
               onLoading: function(){ $('spinner').show(); $('check').hide() },
               onComplete: function() { $('spinner').hide(); $('check').show()},
               parameters: {clave: c.value}
            });
    }
}

function validarDisponible(){
    var valido = true;
    $$('input.disponible').each(function(campo){
        if($F(campo) <= 0){
            valido = false;
            Effect.Shake($('check'));
            throw $break;
        }
    });
    var v = valido;
    return v;
}

function init(){
    aceptar = $('aceptar');
    if(aceptar){
    aceptar.onclick = function(){
        f = $('frm_agregar');
        if(validarDisponible() && frm_validar_campos(["clave","nombre"])){
            f.submit();
        }
    }
    }

    cancelar = $('cancelar');
    if(cancelar){
    cancelar.onclick = function(){
        if(confirm('Al cancelar se perderan los cambios hechos en este formulario, desea continuar?')){
            document.location.href = './';
        }
    }
    }

    clave = $('clave');
    if(clave){
        clave.onkeyup= validarAula;
    }
}
addDOMLoadEvent(init);
