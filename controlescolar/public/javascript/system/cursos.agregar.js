var url_cursos='';
function init(){
	aceptar = $('aceptar');
	if(aceptar){
	aceptar.onclick = function(){
	if($('grupo_oferta').value==2){
	if(	frm_validar_campos(['materias_id', 'profesores_id', 'hcategoria_id']) &&
			validarHorarios()
		){

		if(!valFecha1($('inicio'))){
				alert("Fecha invalida.\nFormato para fechas: 01/01/2007 o 01/01/07...");
				}else{
				$('frm_agregar').submit();
			}
		}
	}else{
		if(	frm_validar_campos(['materias_id', 'profesores_id']) &&
			validarHorarios()
		){
			$('frm_agregar').submit();
		}
	}
	}
	}

	cancelar = $('cancelar');
	if(cancelar){
	cancelar.onclick = function(){
		if(confirm('Al cancelar se perderan los cambios hechos en este formulario, desea continuar?')){
			document.location.href = './';
		}
	}
	}

	if($('materias_id')){
		$('materias_id').onchange = function(){
			obtenFechaInicio(this.value);


		}
	}

	if($('profesores_id')){
	$('profesores_id').onchange = function(){
			$$('select.aula').each(function(campo){
				validarHorario(campo);
			});

		}
	}



	$$('input.hora').each(function(campo){
		$(campo).n = campo.id.charAt(campo.id.length-1);
		$(campo).onblur = function(){
			if(validarTiempo($(campo))){
				if(validarEntradaSalida($F('entrada'+ $(campo).n), $F('salida'+ $(campo).n))){
					$('valido'+ $(campo).n).value ='1';
				}else{
					$(campo).value = '';
				}
			}
			validarHorario(campo);
		};
	});

	$$('select.aula').each(function(campo){
		$(campo).n = campo.id.charAt(campo.id.length-1);
		$(campo).onchange = function(){
			validarHorario(campo);
		};
	});

	calendar=$('calendar');
	if(calendar){
	calendar.href='javascript:;';
	Calendar.setup({
				button: calendar.id,
				electric : false,
				inputField : 'calendario',
				ifFormat : '%d/%m/%Y',
				onUpdate: function(){
							$$('select.aula').each(function(campo){
							validarHorario(campo);
							});

						}
			});

	}
}

function valFecha1(oTxt){
var bOk = true;
if (oTxt.value != ""){
bOk = bOk && (valAno(oTxt));
bOk = bOk && (valMes(oTxt));
bOk = bOk && (valDia(oTxt));
bOk = bOk && (valSep(oTxt));
if (!bOk){
oTxt.value = "";
oTxt.select();
oTxt.focus();
}
}
return bOk;
}


addDOMLoadEvent(init);