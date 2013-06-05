var url_cursos='../';
function init(){
	aceptar = $('aceptar');
	if(aceptar){
	aceptar.onclick = function(){
	if($('inicio')){

		if(	frm_validar_campos(['materias_id', 'profesores_id','inicio', 'hcategoria_id']) &&
			validarHorarios()
		){

		if(!valFecha1($('inicio'))){
				alert("Fecha invalida.\nFormato para fechas: 01/01/2007 o 01/01/07...");
				}else{
				$('frm_editar').submit();
			}
		}
	}else{
		if(	frm_validar_campos(['materias_id', 'profesores_id']) &&
			validarHorarios()
		){
			$('frm_editar').submit();
		}
		}
	}
	}

	cancelar = $('cancelar');
	if(cancelar){
	cancelar.onclick = function(){
		if(confirm('Al cancelar se perderan los cambios hechos en este formulario, desea continuar?')){
			document.location.href = '../index/' + $F('grupos_id');
		}
	}
	}

	if( $('materias_id') && $('profesores_id') ){
	 $('profesores_id').onchange = function(){
		$$('select.aula').each(function(campo){
			validarHorario(campo);
		});
	}

	$('materias_id').onchange = function(){
		obtenFechaInicio(this.value);

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
		validarHorario(campo);
	});

	calendar=$('calendar');
	if(calendar){
	calendar.href='javascript:;';
	Calendar.setup({
				button: calendar.id,
				electric : false,
				inputField : 'calendario',
				ifFormat : '%d/%m/%Y'
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