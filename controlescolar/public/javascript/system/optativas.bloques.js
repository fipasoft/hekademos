var bloques_metodos = {
	procesar: function (elemento){
		var total=$('Totalalumnos');
		var respuesta=$("porbloque");
		var r=Math.floor(total.value/elemento.value);
		var s=total.value%elemento.value;

		respuesta.escribe(r);
		$('pporbloque').show();
		if(s>0 && s<total.value){
		respuesta.escribe((r+1));
		$('msj').show();
		}else if(r<1){
		$('msj').escribe('<span class="sub" style="background-color:#F4FF99;color:#FFCC99;">Existen mas bloques que alumnos.</span>');
		$('msj').show();

		}else{
		$('msj').limpiaHTML();
		$('msj').hide();
		}
	},
	escribe: function(elemento,cad){
		elemento.innerHTML=cad;
		elemento.show();
	},
	leer: function(elemento){
	return elemento.innerHTML;
	},
	limpiaHTML: function(elemento){
		elemento.innerHTML='';
	},
	validar: function(elemento,forma){
	if(elemento.value=="" || elemento.value=="0" || !esEntero(elemento.value)){
	try{
			elemento.activate();
			elemento.value = '';
			Effect.Shake(elemento);
		}catch(err){
			alert('Solo acepta valores numericos');
		}
		return false;
		}else if(parseInt(elemento.value)>parseInt($('Totalalumnos').value)){
		try{
			elemento.activate();
			Effect.Shake(elemento);
		}catch(err){
		alert('Es mayor el numero de bloques que de alumnos');
		}
		return !forma;
		}else{
		return true;
		}
	},
	validartiempo: function(elemento){
	if(elemento.value=="" || elemento.value=="0" || !esEntero(elemento.value)){
	try{
			elemento.activate();
			elemento.value = '';
			Effect.Shake(elemento);
		}catch(err){
			alert('Solo acepta valores numericos');
		}
		return false;
		}else{
		return true;
		}
	},
	limpiaForma: function(elemento){
		$('porbloque').limpiaHTML();
		$('pporbloque').hide();
		$('msj').limpiaHTML();
		$('msj').hide();
	},
	muestrabloques: function(elemento){
	$$('.alertabloque').each(function(ele){
		ele.hide();
	});

	$('alerta').innerHTML='<br/>Considere que al crear los bloques se perder&aacute;n los cambios que se hayan realizado de manera manual.';

	$$('.creacion').each(function(ele){
		ele.show();
	});
	}


};

function validahora(){
var hi=$('hora_inicio');
var mi=$('minutos_inicio');

var hf=$('hora_fin');
var mf=$('minutos_fin');

var inicio	=hi.value.toString()+mi.value.toString();
var fin		=hf.value.toString()+mf.value.toString();
//alert(inicio+"<"+fin);
if(inicio<fin){
	return true;
}else{
	try{
				Effect.Shake($('div_tiempo'));
			}catch(err){
				alert('Solo acepta valores numericos');
			}
	return false;
}
}

var sel=false;
function init(){

$$('.select_all').each(function(a){
		a.onclick = function(){
			a.href = 'javascript:;';
			var id = $(a).id;
			var n = id.split('_');
			n = n[1];
			$$('#tbl_' + n + ' .chk_dia').each(function(chk){
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

$$('.chk_dia').each(function (campo){
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



Element.addMethods(bloques_metodos);
var bloques=$('bloques');
if( bloques)
 bloques.onblur=function(){
	if(this.validar(false)){
	this.procesar();
	}else{
	this.limpiaForma();
	}

};
var intervalo=$('intervalo');
if(intervalo)
 intervalo.onblur=function(){
			this.validartiempo();
};

var frm=$('frm_crear');
if(frm){
frm.onsubmit=function(){
	if(!frm_validar('frm_crear') || !bloques.validar(true) )
			{
				return false;
			}
}
}

var btn_avanzadas=$('btn_avanzadas');
if(btn_avanzadas){
btn_avanzadas.onclick=function(){
	Effect.Appear('avanzadas', { duration: 3.0 });
};
}

var frm_avanzadas=$('frm_avanzadas');
if(frm_avanzadas){
frm_avanzadas.onsubmit=function(){
	if(!frm_validar_radiogroup($$('.chk_dia')) || !frm_validar('frm_avanzadas') || !validahora()){
	return false;
	}

};
}

var btn_muestra=$("btn_muestra");
if(btn_muestra){
	btn_muestra.onclick=function(){
	btn_muestra.muestrabloques();
	}
}


}

addDOMLoadEvent(init);