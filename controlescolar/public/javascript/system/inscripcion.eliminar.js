function init(){
    var frm = $('frm_imprimir');
    if(frm){
        frm.onsubmit = function(){
            if(!frm_validar_radiogroup($$('.chk_alu')) )
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
                    if($(chk).checked){
                        $(td).removeClassName('selected');
                    }else{
                        $(td).addClassName('selected');
                    }
                });
                $(chk).checked = !$(chk).checked;
            });
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
/*
    $$('.chk_alu').each(function (campo){
            var id = $(campo).id;
            var n = id.split('_');
            n = n[1];
            $(campo).checked = true;
            $$('#row_' + n + ' td').each(function(td){
                $(td).addClassName('selected');
            });
    });*/
        cancelar = $('cancelar');
    if(cancelar){
    cancelar.onclick = function(){
        if(confirm('Al cancelar se perderan los cambios hechos en este formulario, desea continuar?')){
            document.location.href = '../../cursos/';
        }
    }
    }
}
addDOMLoadEvent(init);