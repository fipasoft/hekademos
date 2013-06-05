var css = {
    reset: function(celda){
        celda.removeClassName('FALSE');
        celda.removeClassName('TRUE');
    },
    verdadero: function(celda){
        celda.addClassName('TRUE');
        celda.removeClassName('FALSE');
    },
    falso: function(celda){
        celda.addClassName('FALSE');
        celda.removeClassName('TRUE');
    }
};

var entrada = {
    abrir: function(id){
        var n = parseInt(id) + 1;
        id = n.toString();
        var e = $('cal_' + n.toString());
        e.show();
        e.enable();
        var sp = $('sp_' + id);
        if(sp){
            sp.hide();
        }
        var a = $('a_' + id);
        if(a){
            a.hide();
        }
        inputMetodos.validar(e);
    },
    cerrar: function(id){
        var n = parseInt(id) + 1;
        id = n.toString();
        var e = $('cal_' + id);
        var td = $('td_' + id);
        var sp = $('sp_' + id);
        if(sp){
            sp.show();
        }
        var a = $('a_' + id);
        if(a){
            a.hide();
        }
        e.disable();
        e.hide();
        e.value = '';
        td.onclick = null;
        css.reset(td);
    },
    limpiar: function(e, msj){
        try{
            e.activate();
            e.value = '';
            Effect.Shake(e);
        }catch(err){
            alert(msj);
        }
    },
    validar: function(e){
        var tipo = e.type;
        if(tipo == 'text'){
            inputMetodos.validar(e);
        }else{
            selectMetodos.validar(e);
        }
    }
};

var inputMetodos = {
    validar: function (elemento){
        var id = elemento.id.split('_');
        id = id[1];
        var celda = $('td_' + id);
        elemento.onblur = function(){
            var cal = parseInt(elemento.value);
            var clase = elemento.className;
            if(!esEntero(elemento.value)){
                entrada.limpiar(elemento, 'Este elemento acepta solo valores enteros.');
                css.reset(celda);
                if(clase == 'ordinario'){
                    entrada.cerrar(id);
                }
            }else if(!isNaN(cal)){
                if(cal > 100){
                    entrada.limpiar(elemento, 'La calificacion maxima es 100.');
                    css.reset(celda);
                    if(clase == 'ordinario'){
                        entrada.cerrar(id);
                    }
                }else if(cal >= 60){
                    css.verdadero(celda);
                    if(clase == 'ordinario'){
                        entrada.cerrar(id);
                    }
                }else{
                    css.falso(celda);
                    if(clase == 'ordinario'){
                        entrada.abrir(id);
                    }
                }
            }
        }
        return elemento;
    }
};

var selectMetodos = {
        validar: function (elemento){
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

var metodos = {
    activar: function (elemento){
        var id = elemento.id.split('_');
        id = id[1];
        var campo = $('cal_' + id);
        var ancla = $('a_' + id);
        elemento.onclick = function(){
            campo.show();
            campo.enable();
            if(ancla){
                ancla.hide();
            }
            entrada.validar(campo);
        }
        return elemento;
    }
};

function init(){
    var frm = $('frm_editar');
    if(frm){
        frm.onsubmit = function(){
            if(frm_validar('frm_editar')){
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

    Element.addMethods('A', metodos);
    Element.addMethods('TD', metodos);
    $$('.switch').invoke('activar');

}
addDOMLoadEvent(init);