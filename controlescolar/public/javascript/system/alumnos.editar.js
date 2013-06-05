function init(){
	promedio=$('promedio');
	if(promedio){
	promedio.onblur = function(){
		 validacampoFloat(promedio);
	}
	}
	aprobadas=$('aprobadas');
	if(aprobadas){
	aprobadas.onblur = function(){
	 validacampoEntero(aprobadas);
	}
	}

	aceptar = $('aceptar');
	if(aceptar){
	aceptar.onclick = function(){
		f = $('frm_editar');
		if(	frm_validar_campos(['codigo', 'nombre', 'ap', 'am', 'situacion']) &&
			frm_validar_radiogroup(['hombre', 'mujer'])
		){
		promedio=$('promedio');
		aprobadas=$('aprobadas');
		if(validacampoFloat(promedio) && validacampoEntero(aprobadas))
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

	campo = $('codigo');
	if(campo){
		campo.onkeyup = function() {
			new Ajax.Updater('check', '../disponible/', {
			   method: 'post',
			   onLoading: function(){ $('spinner').show(); $('check').hide(); },
			   onComplete: function() { $('spinner').hide(); $('check').show()},
			   parameters: { tabla: 'Alumnos', campo: campo.id, valor: campo.value, id: $('id').value }
			});
		}
	}

	fecha = $('fnacimiento');
	calendario = $('cal');
	if(fecha){
		fecha.onblur = function(){ valFecha(fecha) };
		calendario.href = 'javascript:;';
		if(calendario){
			Calendar.setup({
				button: calendario.id,
				electric : false,
				inputField : fecha.id,
				ifFormat : '%d/%m/%Y'
			});
		}
	}

	fecha2 = $('admision');
	calendario2 = $('cal2');
	if(fecha2){
		fecha2.onblur = function(){ valFecha(fecha2) };
		calendario2.href = 'javascript:;';
		if(calendario2){
			Calendar.setup({
				button: calendario2.id,
				electric : false,
				inputField : fecha2.id,
				ifFormat : '%d/%m/%Y'
			});
		}
	}

	b1 = $('iCambiar');
	if(b1){
		b1.onclick = function(){
			$('cambiar').value = true;
			Effect.Appear('examinar');
			Effect.BlindUp('imagen');
		}
	}

	b2 = $('iCancelar');
	if(b2){
		b2.onclick = function(){
			$('cambiar').value = false;
			Effect.BlindDown('imagen');
			Effect.DropOut('examinar');
		}
	}
}
addDOMLoadEvent(init);