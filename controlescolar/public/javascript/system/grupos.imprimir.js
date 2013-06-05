function init(){
    var frm = $('frm_imprimir');
    if(frm){
        frm.onsubmit = function(){
        reportes=$('reporte');

            if(reportes.value=='lista' || reportes.value=='ast' || reportes.value=='evaluaciones' ){

            if( !frm_validar_campos( ['reporte', 'cursos_select'] ) )
            {
                return false;
            }

            }else if(reportes.value=='boletas' || reportes.value=='finales'){

            if( !frm_validar_campos( ['reporte'] ) ||
                !frm_validar_radiogroup($$('.chk_alu')) )
            {
                return false;
            }

            }else if(reportes.value=='inscritos' || reportes.value=='cal'){

            if( !frm_validar_campos( ['reporte'] ) ||
                !frm_validar_radiogroup($$('.chk_cur')) )
            {
                return false;
            }
            }else if(reportes.value=='resumen' || reportes.value=='alu' || reportes.value=='reg'){

            if( !frm_validar_campos( ['reporte'] ) )
            {
                return false;
            }
            }else{
            if( !frm_validar_campos( ['reporte'] ) ||
                !frm_validar_radiogroup($$('.chk_alu')) )
            {
                return false;
            }
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

    $$('.chk_alu').each(function (campo){
            var id = $(campo).id;
            var n = id.split('_');
            n = n[1];
            $(campo).checked = true;
            $$('#row_' + n + ' td').each(function(td){
                $(td).addClassName('selected');
            });
    });

    if($('reporte')){
    reportes=$('reporte');
    reportes.onchange=function(){
            if(this.value=='lista' || this.value=='ast' || this.value=='evaluaciones' ){
            $('alumnos_').hide();
            $('inscritoslst').hide();
            $('cursos').show();
            }else if(this.value=='boletas' || this.value=='finales'){
            $('inscritoslst').hide();
            $('cursos').hide();
            $('alumnos_').show();
            }else if(this.value=='inscritos' || this.value=='cal'){
            $('alumnos_').hide();
            $('cursos').hide();
            $('inscritoslst').show();

            }else if(this.value=='resumen' || this.value=='alu' || this.value=='reg'){
            $('alumnos_').hide();
            $('cursos').hide();
            $('inscritoslst').hide();

            }
    }


        $$('.select_all_cur').each(function(a){
        a.onclick = function(){
            a.href = 'javascript:;';
            var id = $(a).id;
            var n = id.split('_');
            n = n[1];
            $$('#tbl_cur .chk_cur').each(function(chk){
                var id2 = $(chk).id;
                var n2 = id2.split('_');
                n2 = n2[1];
                $$('#row_cur_' + n2 + ' td').each(function(td){
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

    $$('.chk_cur').each(function (campo){
            var id = $(campo).id;
            var n = id.split('_');
            n = n[1];
            $(campo).checked = true;
            $$('#row_cur_' + n + ' td').each(function(td){
                $(td).addClassName('selected');
            });
    });

    $$('.chk_cur').each(function (campo){
        campo.onclick = function(){
            var id = $(campo).id;
            var n = id.split('_');
            n = n[1];
            if($(campo).checked){
                $$('#row_cur_' + n + ' td').each(function(td){
                    $(td).addClassName('selected');
                });
            }else{
                $$('#row_cur_' + n + ' td').each(function(td){
                    $(td).removeClassName('selected');
                });
            }
        }
    });

}
}
addDOMLoadEvent(init);