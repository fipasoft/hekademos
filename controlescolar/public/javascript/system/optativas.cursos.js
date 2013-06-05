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
		var n = parseInt(id);
		id = n.toString();
		var e = $('cupos_' + id);
		var td = $('td_' + id);

		var a = $('ancla_' + id);
		if(a){
			a.show();
		}
		if(e){
		e.disable();
		e.hide();
		e.value = '';
		}

		if(td){
		td.onclick = null;
		css.verdadero(td);

		}

	},
	limpiar: function(e, msj){
		try{
			e.activate();
			e.value = '0';
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
			inputMetodos.tipos(e);
		}
	}
};

var inputMetodos = {
	validar: function (elemento){
		var id = elemento.id.split('_');
		id = id[1];
		var celda = $('td_' + id);
		elemento.onblur = function(){
			if(elemento.value!=""){
			var cal = parseInt(elemento.value);
			var clase = elemento.className;
			if(!esEntero(elemento.value)){
				entrada.limpiar(elemento, 'Este elemento acepta solo valores enteros.');
				css.verdadero(celda);
				entrada.cerrar(id);
				 $('eli_chk_' + id).focus();

			}else{
			var valor =  $('eli_chk_' + id);
			var campo =  $('cupos_' + id);
			var ancla =  $('ancla_' + id);
			var spinner= $('spinner_' + id);

			new Ajax.Request($('KUMBIA_PATH').value+'optativas/cupos/', {
			   method: 'post',
			   onLoading: function(){ spinner.show(); ancla.hide(); campo.hide(); },
			   onComplete: function(resp) {
			   if(resp.responseText=="1"){
			   		spinner.hide();
					campo.hide();
					campo.disable();
					if(ancla){
						ancla.innerHTML=campo.value;
						ancla.show();
					}
					}else{
					alert("ERROR: "+resp.responseText);
					spinner.hide();
					campo.hide();
					campo.disable();
					ancla.show();
					}

			   },
			   parameters: {id: valor.value , cupos: campo.value}
			});

			}
			}else{
			entrada.limpiar(elemento, 'Este elemento acepta solo valores enteros.');
			}
		}
		return elemento;
	},
	tipos: function (elemento){
		var id = elemento.id.split('_');
		id = id[1];
		var celda = $('td_' + id);
		elemento.onchange = function(){
			var clase = elemento.className;
			var valor =  $('eli_chk_' + id);
			var campo =  $('tipos_' + id);
			var ancla =  $('tancla_' + id);
			var spinner= $('tspinner_' + id);
			
			new Ajax.Request($('KUMBIA_PATH').value+'optativas/tipos/', {
			   method: 'post',
			   onLoading: function(){ spinner.show(); ancla.hide(); campo.hide(); },
			   onComplete: function(resp) {
			   if(resp.responseText=="1"){
			   		spinner.hide();
					campo.hide();
					campo.disable();
					if(ancla){
						if(campo.value==""){
							ancla.innerHTML="N";
						}else{
							ancla.innerHTML=campo.value;
						}
						ancla.show();
					}
					}else{
					alert("ERROR: "+resp.responseText);
					spinner.hide();
					campo.hide();
					campo.disable();
					ancla.show();
					}

			   },
			   parameters: {id: valor.value , tipos: campo.value}
			});

		}
		return elemento;
	}
};


var metodos = {
	cupos: function (elemento){
		var id = elemento.id.split('_');
		id = id[1];
		var campo = $('cupos_' + id);
		var ancla = $('ancla_' + id);
		if(campo){
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
	},
	tipos: function (elemento){
		var id = elemento.id.split('_');
		id = id[1];
		var campo = $('tipos_' + id);
		var ancla = $('tancla_' + id);
		if(campo){
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

	reset = $('reset');
	if(reset){
	reset.onclick = function(){
		Effect.DropOut($('search'));
		frm_reset('frm_search');
	}
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

	reset1 = $('reset1');
	if(reset1){
	reset1.onclick = function(){
		Effect.DropOut($('search1'));
		frm_reset('frm_search1');
	}
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


	Element.addMethods('A', metodos);
	Element.addMethods('TD', metodos);
	$$('.switch').invoke('cupos');
	$$('.tswitch').invoke('tipos');
/**************End Tab2****************************/
}

addDOMLoadEvent(init);
