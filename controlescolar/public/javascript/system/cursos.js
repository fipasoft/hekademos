function validarHorarioAjax(n){
if($('inicio'))
 ini=$('inicio').value;
 else
 ini='';

new Ajax.Updater('check' + n, $("KUMBIA_PATH").value + 'horarios/validar/', {
   method: 'post',
   onLoading: function() { $('spinner' + n).show(); $('check' + n).hide(); },
   onComplete: function() { $('spinner' + n).hide(); $('check' + n).show(); },
   parameters: {profesores_id: $F('profesores_id'), materias_id: $F('materias_id'),
	   			grupos_id: $F('grupos_id'), dia: $F('dia' + n),
	   			entrada: $F('entrada' + n), salida: $F('salida' + n),
	   			aula: $F('aula' + n), n: n,
	   			curso_id: $F('id'),
	   			inicio: ini
   }
});
}

function validarEntradaSalida(ent, sal){
ent = new Date("January 1, 2008 " + ent + ":00");
sal = new Date("January 1, 2008 " + sal + ":00");
if(ent.getTime() > sal.getTime()){
alert('La hora de entrada debe ser anterior a la salida');
return false;
}
return true;
}
function validarHorario(campo){
var n = $(campo).n;
if($F('entrada'+ n) == '' && $F('salida'+ n) == '' && $F('aula'+ n) == ''){
	$('valido'+ n).value = 0;
	$('status' + n).update('-');
	$('status' + n).className = '';
}else if($F('entrada'+ n) != '' && $F('salida'+ n) != '' && $F('aula'+ n) != ''){
	if($F('profesores_id') != '' && $F('materias_id') != ''){
		validarHorarioAjax(n);
	}else{
		if($F('materias_id') == ''){
			$('status' + n).update('Seleccione una materia');
		}else{
			$('status' + n).update('Seleccione un profesor');
		}
		$('valido'+ n).value = -1;
		$('status' + n).className = 'advert';
	}
}else{
	$('valido' + n).value = -1;
	$('status' + n).update('Complete la informaci&oacute;n del horario');
	$('status' + n).className = 'alert';}
}
function validarHorarios(){
	var valido = true;
	$$('input.status').each(function(campo){
		if($F(campo) < 0){
			valido = false;
			Effect.Shake($('check' + campo.id.charAt(campo.id.length-1)));
			throw $break;
		}
	});
	var v = valido;
	return v;
}
function validarTiempo(fld) {
timeStr = fld.value;
if(timeStr != ''){
var timePat = /^(\d{1,2}):(\d{2})?$/;
if(timeStr.length<=3){
timeStr=timeStr.replace(/:/, "");
}
var matchArray = timeStr.match(timePat);

if (matchArray == null) {

if(timeStr.length <= 4 && esEntero(timeStr)){
if(timeStr.length <= 2){
fld.value = timeStr + ":00";
}else{
var newTime = '';
for (var i = timeStr.length - 1; i >= 0; i--) {
newTime = timeStr.charAt(i) + (timeStr.length - i == 3 ? ':' : '') + newTime;
}
fld.value = newTime;
}
return validarTiempo(fld);;
}else{
alert("Valor incorrecto, por favor especifique HH:MM en formato de 24 horas");
fld.value = '';
return false;
}
}
hour = matchArray[1];
minute = matchArray[2];
if (hour < 0  || hour > 23) {
alert("Las horas solo pueden tomar un valor entre 0 y 23");
fld.value = '';
return false;
}
if (minute<0 || minute > 59) {
alert ("Los minutos solo pueden tomar un valor entre 0 y 59");
fld.value = '';
return false;
}
return true;
}
}

function obtenFechaInicio(value){
if($('grupo_oferta').value==2 && $('ciclos_id')){

new Ajax.Updater('div_fecha', $("KUMBIA_PATH").value + 'cursos/fecha/', {
   method: 'post',
   onLoading: function() { /*$('spinner' + n).show(); $('check' + n).hide();*/ },
   onComplete: function() {
    /*$('spinner' + n).hide(); $('check' + n).show();*/
    $$('select.aula').each(function(campo){
				validarHorario(campo);
			});
     },
   parameters: { materias_id: value, ciclos_id: $('ciclos_id').value }
});
}
}