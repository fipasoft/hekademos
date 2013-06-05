function campos_requeridos( modo ){
    var pass = false;
    switch (modo){
        case 'resumen':
            pass = frm_validar_campos( ['tipo'] ) && frm_validar_radiogroup( $$('.cnv_grupos') );
            break;
        case 'derechos':
            pass = frm_validar_campos( ['tipo'] ) && frm_validar_radiogroup( $$('.cnv_grupos_der') );
            break;
               default:
            pass = frm_validar_campos( ['tipo'] );
    }
    return pass;
}


function actualizar(modo){
var id='';
 switch (modo){
        case 'resumen':
            id='tblRes';
            break;
        case 'derechos':
            id='tblDer';
            break;
               default:

    }
            if(id!=''){
            $$( '#' + id + ' .chk').each( function( campo ){
                var trId = campo.id.sub('chk', 'tr');
                    var td=$$('#'+trId+' td')[0];
                    if(td.className=='selected'){

                    campo.checked = true;

                    }

                $(campo).removeAttribute('_counted');
            }.bind(this));
            }
}

function init(){
    var aceptar = $('aceptar');
    if(aceptar){
    aceptar.onclick = function(){
        f = document.getElementById('frm_reportes');
        if(    campos_requeridos( $F('tipo') ) ){
            f.submit();
        }
    }
    }

    var cancelar = $('cancelar');
    if(cancelar){
    cancelar.onclick = function(){
        document.location.href = './';
    }
    }

    var frm = $('frm_reportes');
    if(frm){
        frm._action = frm.action;
    }

    var tipo = $('tipo');
    if( tipo ){
        tipo.onchange = function(){
            var frm = $('frm_reportes');
            frm.action = frm._action + '/' + this.value;
            $$('.opciones').each(function( d ){
                d = $(d.id);
                if( d.id != 'div_' + this.value ){
                    elemento.desactivar( d.id );
                }
                d.removeAttribute('_counted');
            });
            if( this.value != '' && $('div_' + this.value) ){
                elemento.activar( 'div_' + this.value );
                actualizar(this.value);
            }
        }
    }

    $$('.chk_all').each(function (boton){
        boton.href = 'javascript:;';
        boton.bandera = false;
        boton.onclick = function(){
            var tblId = $(this).id.sub('all', 'tbl');
            this.bandera = !this.bandera;
            $$( '#' + tblId + ' .chk').each( function( campo ){
                var trId = campo.id.sub('chk', 'tr');
                if( !this.bandera ){
                    campo.checked = false;
                    $(trId).removeClassName('selected');
                    $$('#'+trId+' td').each(function( td){
                        td.removeClassName('selected');
                    });
                }else{
                    campo.checked = true;
                    $(trId).addClassName('selected');

                    $$('#'+trId+' td').each(function( td){
                        td.addClassName('selected');
                    });

                }
                $(campo).removeAttribute('_counted');
            }.bind(this));
        }
        boton.removeAttribute('_counted');
    });

    $$('.chk').each(function (campo){
        campo.onclick = function(){
            var trId = $(campo).id.sub('chk', 'tr');
            if($(campo).checked){
                $(trId).addClassName('selected');
                $$('#'+trId+' td').each(function( td){
                        td.addClassName('selected');
                    });

            }else{
                $(trId).removeClassName('selected');
                $$('#'+trId+' td').each(function( td){
                        td.removeClassName('selected');
                    });

            }
        }
        campo.removeAttribute('_counted');
    });


}
addDOMLoadEvent(init);