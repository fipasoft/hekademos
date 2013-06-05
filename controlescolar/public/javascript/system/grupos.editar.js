function validarGrupo(){
    c = $('ciclos_id');
    g = $('grado');
    l = $('letra');
    t = $('turno');
    o = $('oferta');
    if(g && l && t && o){
        new Ajax.Updater('check', '../disponible/', {
               method: 'post',
               onLoading: function(){ $('spinner').show(); $('check').hide() },
               onComplete: function() { $('spinner').hide(); $('check').show()},
               parameters: {ciclos_id: c.value, grado: g.value, letra: l.value, turno: t.value, oferta: o.value}
            });
    }
}

function init(){
    aceptar = $('aceptar');
    if(aceptar){
    aceptar.onclick = function(){
        f = $('frm_editar');
        if(    frm_validar('frm_editar') ){
            f.submit();
        }
    }
    }

    cancelar = $('cancelar');
    if(cancelar){
    cancelar.onclick = function(){
        if(confirm('Al cancelar se perderan los cambios hechos en este formulario, desea continuar?')){
            document.location.href = '../';
        }
    }
    }

    grado = $('grado');
    if(grado){
        grado.onchange = validarGrupo;
    }

    letra = $('letra');
    if(letra){
        letra.onchange = validarGrupo;
    }

    turno = $('turno');
    if(turno){
        turno.onchange = validarGrupo;
    }

    oferta = $('oferta');
    if(oferta){
        oferta.onchange = validarGrupo;
    }

}
addDOMLoadEvent(init);