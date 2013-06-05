function actualizarNumero(){
    n = $('numero');
    l = $('letra');
    a = $('annio');
    if(n && l && a){
        if(a.value != ''){
            a.value = parseInt(a.value);
        }
        if(isNaN(a.value)){
            alert('Este campo solo acepta valores numericos.');
            a.value = '';
        }
        if(l.value != '' && a.value != ''){
            n.value = a.value + '-' + l.value;
        }else{
            n.value = '';
        }
        new Ajax.Updater('check', './disponible/', {
               method: 'post',
               onLoading: function(){ $('spinner').show(); $('check').hide(); },
               onComplete: function() { $('spinner').hide(); $('check').show()},
               parameters: {tabla: 'Ciclos', campo: 'numero', valor: n.value}
            });
    }
}

function init(){
    aceptar = $('aceptar');
    if(aceptar){
    aceptar.onclick = function(){
        f = $('frm_agregar');
        if(    frm_validar('frm_agregar') &&
            valPeriodo($('inicio').value, $('fin').value) &&
            valEjercicio($('inicio').value, $('annio').value)
        ){
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

    annio = $('annio');
    if(annio){
        annio.onkeyup = actualizarNumero;
    }

    letra = $('letra');
    if(letra){
        letra.onchange = actualizarNumero;
    }

    fecha = $('inicio');
    if(fecha){
        fecha.onblur = function(){ valFecha($('inicio')) };
        if($('calendario')){
            Calendar.setup({
                button: 'calendario',
                electric : false,
                inputField : 'inicio',
                ifFormat : '%d/%m/%Y'
            });
        }
    }

    fecha = $('fin');
    if(fecha){
        fecha.onblur = function(){ valFecha($('fin')) };
        if($('calendario2')){
            Calendar.setup({
                button: 'calendario2',
                electric : false,
                inputField : 'fin',
                ifFormat : '%d/%m/%Y'
            });
        }
    }
}
addDOMLoadEvent(init);