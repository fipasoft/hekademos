var Widget = Class.create();
var elementos=Array();
Widget.prototype = {
    initialize: function(id) {
        this.id=id;
        elementos[id]=this;

    },

    activar: function(id){
    var widget=elementos[id];
            $$('#'+this.id+' .chk_tra').each(function (chk){
                chk.onclick = function(){
                    var id = $(chk).id;
                    var n = id.split('_');
                    n = n[1];
                    if($(chk).checked){
                        $$('#'+widget.id+' .chk_tra').each(function (chk1){
                        if(chk1.checked){
                            if(id!=chk1.id){
                                chk1.checked = false;
                                var id2 = $(chk1).id;
                                var n2 = id2.split('_');
                                n2 = n2[1];
                                $$('#row_' + n2 + ' td').each(function(td){
                                $(td).removeClassName('selected');
                                });

                            }
                            }

                        });
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
    }
    ,
    validar: function(id){
    var widget=elementos[id];
    var cuenta = 0;
    $$('#'+widget.id+' .chk_tra').each(function (chk){
                        if(chk.checked){
                                cuenta++;
                                }

                        });

    if(cuenta!=1){
    if(cuenta>1){
    $('mensaje').innerHTML="<br/>Por favor seleccione solo 1 trayectoria.<br/>";
    }else{
    $('mensaje').innerHTML="<br/>Por favor seleccione la trayectoria de su agrado.<br/>";

    }
    new Effect.Appear('mensaje');
    return false;
    }else{
    return true;
    }
        }

    }

function init(){

var frm_inscribir=$('frm_inscribir');
if(frm_inscribir){
var w=new Widget('tblTrayectoria');
w.activar(w.id);

frm_inscribir.onsubmit=function(){
                            return w.validar(w.id);
}
}
}

addDOMLoadEvent(init);