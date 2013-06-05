var Widget = Class.create();
var elementos = Array();
Widget.prototype = {
    initialize : function(id, max, diferente, label,dtipo) {
        this.id = id;
        var tempo = this.id.split("_");
        this.maximo = max;
        this.diferente = diferente;
        this.grado = tempo[1];
        this.tipo = tempo[3];
        this.label = label;
        this.dtipo = dtipo;
        elementos[id] = this;

    },

    activar : function(id) {
        var widget = elementos[id];
        $$('#' + this.id + ' .chk_cur').each(function(chk) {
            chk.onclick = function() {
                var id = $(chk).id;
                var n = id.split('_');
                n = n[2];
                if ($(chk).checked) {
                    var cuenta = 0;
                    var grupos = Array();
                    var tipos = Array();
                    var grupoactual = chk.id.split('_')[0];
                    var tipoactual = chk.id.split('_')[3];
                    $$('#' + widget.id + ' .chk_cur').each(function(chk) {
                        if (chk.checked) {
                            cuenta++;
                            if (id != chk.id) {
                                var grupo = chk.id.split('_')[0];

                                grupos[grupo] = grupo;
                                
                                var tipo = chk.id.split('_')[3];
                                
                                if(tipo!="N")
                                    tipos[tipo] = tipo;
                            }
                            
                        }

                    });

                    // alert(cuenta+ "<="+widget.maximo);
                    if (cuenta <= widget.maximo) {
                        var gok = true;
                        if (widget.diferente == '1') {
                            if (grupos[grupoactual] == null) {
                                gok = true;    
                            } else {
                                gok = false;
                            }
                        } else {
                            gok = true;
                        }
                        
                        var tok = true;
                        if(widget.dtipo=='1' && gok == true){
                                if (tipos[tipoactual] == null) {
                                    tok = true;
                                } else {
                                    tok = false;
                                }
                            } else {
                                tok = gok;
                            }

                        if(gok && tok){
                            $$('#crs_row_' + n + ' td').each(function(td) {
                                $(td).addClassName('selected');
                            });
                        }else{
                            chk.checked = false;
                        }
                        
                    } else {
                        chk.checked = false;
                    }
                } else {
                    $$('#crs_row_' + n + ' td').each(function(td) {
                        $(td).removeClassName('selected');
                    });
                }
            }
        });
    },
    toString : function() {
        return "[" + this.id + "]" + "[" + this.grado + "]" + "[" + this.tipo
                + "]" + "[" + this.maximo + "]" + "[" + this.diferente + "]";
    },
    validar : function(id) {
        var widget = elementos[id];
        var cuenta = 0;
        var grupos = Array();
        var diferenteerror = false;
        $$('#' + widget.id + ' .chk_cur').each(function(chk) {
            if (chk.checked) {
                cuenta++;
                if (widget.diferente == '1') {
                    var grupo = chk.id.split('_')[0];
                    if (grupos[grupo] == null)
                        grupos[grupo] = grupo;
                    else
                        diferenteerror = true;
                }
            }

        });
        if (diferenteerror) {
            var error = "<br/>Debe seleccionar cursos de<br/>" + widget.label
                    + " de diferente grupo ";
            $('mensaje').innerHTML = error;
            new Effect.Appear('mensaje');
            return false;

        } else if (cuenta == widget.maximo) {
            return true;
        } else {
            var error = "<br/>Debe seleccionar " + widget.maximo + " curso";
            if (widget.maximo != 1)
                error += "s";

            error += " de <br/>" + widget.label;
            $('mensaje').innerHTML = error;
            new Effect.Appear('mensaje');
            return false;
        }

    }

}

function init() {
    var frm_inscribir = $('frm_inscribir');
    if (frm_inscribir) {
        frm_inscribir.onsubmit = function() {
            submit = true;
            $$('#frm_inscribir .widget').each(function(wid) {
                if (submit) {
                    wg = elementos[wid.id];
                    if (!wg.validar(wid.id))
                        submit = !submit;
                }
            });

            return submit;

        }
    }
}

addDOMLoadEvent(init);