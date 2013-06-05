String.prototype.endsWith = function(str)
{return (this.match(str+"$")==str)}

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
		e.value = '0';
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
				setTimeout("entrada.cerrar("+id+")", 600);

			}else{

			var tt="vespertino";
			var t="V";
			if(elemento.id.endsWith("M")){
				tt="matutino";
				t="M";
			}

			try{
			var valores =$$("."+tt);
			var total=0;
			for (var i=0;i<valores.length;i++ ){
					var c="incluir_"+''+i+t;
					if($(c).checked)
				   		total+= parseInt(valores[i].value);
				}

			$("total"+t).innerHTML = total;
			}catch(err){
			alert("Ocurrio un error al sumar los cupos "+err);
			}
			}
			}else{
			entrada.limpiar(elemento, 'Este elemento acepta solo valores enteros.');
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
		if(campo){
		elemento.onclick = function(){
			campo.show();
			campo.enable();
			campo.focus();
			if(ancla){
				ancla.hide();
			}
			entrada.validar(campo);
		}
		return elemento;
		}
	}
};

var metodoschk = {
	activar: function (elemento){
		elemento.onclick = function(){
			var tt="vespertino";
			var t="V";
			if(this.id.endsWith("M")){
				tt="matutino";
				t="M";
			}

			try{
			var valores =$$("."+tt);
			var total=0;
			for (var i=0;i<valores.length;i++ ){
					var c="incluir_"+''+i+t;
					if($(c).checked)
				   		total+= parseInt(valores[i].value);
				}

			$("total"+t).innerHTML = total;
			}catch(err){
			alert("Ocurrio un error al sumar los cupos "+err);
			}
			}
	}
};

/**************End Administracion****************************/

function init(){
var frm = $('frm');
	if(frm){
		frm.onsubmit = function(){
			if(!frm_validar_radiogroup($$('.chk_taes')) )
			{
				return false;
			}
		};
	}

	var cancelar = $('cancelar');
	if(cancelar){
		cancelar.onclick = function(){
			window.location = $('path').value+"optativas/index/"+$('periodo_id').value;
		}
	}

	Element.addMethods('A', metodos);
	Element.addMethods('TD', metodos);
	Element.addMethods('INPUT', metodoschk);
	$$('.switch').invoke('activar');
	$$('.chk_taes').invoke('activar');
}

addDOMLoadEvent(init);
