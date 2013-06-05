/**************Begin Tabs****************************/
/*-----------------------------------------------------------
    Toggles element's display value
    Input: any number of element id's
    Output: none
    ---------------------------------------------------------*/
function toggleDisp() {
    for (var i=0;i<arguments.length;i++){
        var d = $(arguments[i]);
        if (d.style.display == 'none')
            d.style.display = 'block';
        else
            d.style.display = 'none';
    }
}
/*-----------------------------------------------------------
    Toggles tabs - Closes any open tabs, and then opens current tab
    Input:     1.The number of the current tab
                    2.The number of tabs
                    3.(optional)The number of the tab to leave open
                    4.(optional)Pass in true or false whether or not to animate the open/close of the tabs
    Output: none
    ---------------------------------------------------------*/
function toggleTab(num,numelems,opennum,animate) {
    if ($('tabContent'+num).style.display == 'none'){
        for (var i=1;i<=numelems;i++){
            if ((opennum == null) || (opennum != i)){
                var temph = 'tabHeader'+i;
                var h = $(temph);
                if (!h){
                    var h = $('tabHeaderActive');
                    h.id = temph;
                }
                var tempc = 'tabContent'+i;
                var c = $(tempc);
                if(c.style.display != 'none'){
                    if (animate || typeof animate == 'undefined')
                        Effect.toggle(tempc,'blind',{duration:0.5, queue:{scope:'menus', limit: 3}});
                    else
                        toggleDisp(tempc);
                }
            }
        }
        var h = $('tabHeader'+num);
        if (h)
            h.id = 'tabHeaderActive';
        h.blur();
        var c = $('tabContent'+num);
        c.style.marginTop = '2px';
        if (animate || typeof animate == 'undefined'){
            Effect.toggle('tabContent'+num,'blind',{duration:0.5, queue:{scope:'menus', position:'end', limit: 3}});
        }else{
            toggleDisp('tabContent'+num);
        }
    }
}

/**************End Tabs****************************/


/**************Begin Administracion****************************/
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
        var e = $('cupos_' + n.toString());
        e.show();
        e.enable();

        var a = $('a_' + id);
        if(a){
            a.hide();
        }
        inputMetodos.validar(e);
    },
    cerrar: function(id){
        var n = parseInt(id) + 1;
        id = n.toString();
        var e = $('cupos_' + id);
        var td = $('td_' + id);

        var a = $('ancla_' + id);
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
                    entrada.cerrar(id);

            }else{
            var valor = $('eli_chk_' + id);
            var campo = $('cupos_' + id);
            var ancla = $('ancla_' + id);

            new Ajax.Request($('KUMBIA_PATH').value+'optativas/cupos/', {
               method: 'post',
               onComplete: function(resp) {
               if(resp.responseText=="1"){
                    campo.hide();
                    campo.disable();
                    if(ancla){
                        ancla.innerHTML=campo.value;
                        ancla.show();
                    }
                    }else{
                    alert("ERROR: "+resp.responseText);
                    }

               },
               parameters: {id: valor.value , cupos: campo.value}
            });

            }
        }
        return elemento;
    }
};


var metodos = {
    activar: function (elemento){
        var id = elemento.id.split('_');
        id = id[1];
        var campo = $('cupos_' + id);
        var ancla = $('ancla_' + id);
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

/**************End Administracion****************************/
var sel=false;
function init(){
/**************Begin Tab1****************************/
var frm = $('frm_agregar');
    if(frm){
        frm.onsubmit = function(){
            if(!frm_validar_radiogroup($$('.chk_alu')) )
            {
                return false;
            }
        };
    }

var frm_search = $('frm_search');
    if(frm_search){
        frm_search.onsubmit = function(){
        if(!frm_validar_uno('frm_search'))
            {
                return false;
            }

        };
    }

$$('.select_all').each(function(a){
        a.onclick = function(){
            a.href = 'javascript:;';
            var id = $(a).id;
            var n = id.split('_');
            n = n[1];
            $$('#tbl_' + n + ' .chk_alu').each(function(chk){
                var id2 = $(chk).id;
                var n2 = id2.split('_');
                n2 = n2[1];
                $$('#row_' + n2 + ' td').each(function(td){
                    if(!sel){
                        $(td).addClassName('selected');
                    }else{
                        $(td).removeClassName('selected');

                    }
                }

                );
                if(!sel){
                $(chk).checked = true;

                }else{
                $(chk).checked = false;
                }
            });
            sel=!sel;
        }
    });

$$('.chk_alu').each(function (campo){
        campo.onclick = function(){
            var id = $(campo).id;
            var n = id.split('_');
            n = n[1];
            if($(campo).checked){
                $$('#row_' + n + ' td').each(function(td){
                    $(td).addClassName('selected');
                });
            }else{
                $$('#row_' + n + ' td').each(function(td){
                    $(td).removeClassName('selected');
                });
            }
        }
    });
/**************End Tab1****************************/

/**************Begin Tab2****************************/

var frm1 = $('frm_eliminar');
    if(frm1){
        frm1.onsubmit = function(){
            if(!frm_validar_radiogroup($$('.chk_eli')) )
            {
                return false;
            }
        };
    }

$$('.select_all_crs').each(function(a){
        a.onclick = function(){
            a.href = 'javascript:;';
            var id = $(a).id;
            var n = id.split('_');
            n = n[1];
            $$('#crs_tbl_' + n + ' .chk_eli').each(function(chk){
                var id2 = $(chk).id;
                var n2 = id2.split('_');
                n2 = n2[2];
                $$('#crs_row_' + n2 + ' td').each(function(td){
                    if($(chk).checked){
                        $(td).removeClassName('eliminar');
                    }else{
                        $(td).addClassName('eliminar');
                    }
                });
                $(chk).checked = !$(chk).checked;
            });
        }
    });

$$('.chk_eli').each(function (campo){
        campo.onclick = function(){
            var id = $(campo).id;
            var n = id.split('_');
            n = n[2];
            if($(campo).checked){
                $$('#crs_row_' + n + ' td').each(function(td){
                    $(td).addClassName('eliminar');
                });
            }else{
                $$('#crs_row_' + n + ' td').each(function(td){
                    $(td).removeClassName('eliminar');
                });
            }
        }
    });

var frm_search2 = $('frm_search2');
    if(frm_search2){
        frm_search2.onsubmit = function(){
        if(!frm_validar_uno('frm_search2'))
            {
                return false;
            }

        };
    }

    Element.addMethods('A', metodos);
    Element.addMethods('TD', metodos);
    $$('.switch').invoke('activar');


/**************End Tab2****************************/
}

addDOMLoadEvent(init);
