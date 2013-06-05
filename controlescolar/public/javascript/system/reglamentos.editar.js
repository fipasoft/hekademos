var dtCh = "/";
var minYear = 2005;
var now = new Date();
var maxYear = now.getFullYear();
function isInteger(s) {
	var i;
	for (i = 0; i < s.length; i++) {
		// Check that current character is number.
		var c = s.charAt(i);
		if (((c < "0") || (c > "9")))
			return false;
	}
	// All characters are numbers.
	return true;
}

function stripCharsInBag(s, bag) {
	var i;
	var returnString = "";
	// Search through string's characters one by one.
	// If character is not in bag, append to returnString.
	for (i = 0; i < s.length; i++) {
		var c = s.charAt(i);
		if (bag.indexOf(c) == -1)
			returnString += c;
	}
	return returnString;
}

function daysInFebruary(year) {
	// February has 29 days in any year evenly divisible by four,
	// EXCEPT for centurial years which are not also divisible by 400.
	return (((year % 4 == 0) && ((!(year % 100 == 0)) || (year % 400 == 0))) ? 29
			: 28);
}
function DaysArray(n) {
	for ( var i = 1; i <= n; i++) {
		this[i] = 31
		if (i == 4 || i == 6 || i == 9 || i == 11) {
			this[i] = 30
		}
		if (i == 2) {
			this[i] = 29
		}
	}
	return this
}

function isDate(dtStr) {
	var daysInMonth = DaysArray(12)
	var pos1 = dtStr.indexOf(dtCh)
	var pos2 = dtStr.indexOf(dtCh, pos1 + 1)
	var strDay = dtStr.substring(0, pos1)
	var strMonth = dtStr.substring(pos1 + 1, pos2)
	var strYear = dtStr.substring(pos2 + 1)
	strYr = strYear
	if (strDay.charAt(0) == "0" && strDay.length > 1)
		strDay = strDay.substring(1)
	if (strMonth.charAt(0) == "0" && strMonth.length > 1)
		strMonth = strMonth.substring(1)
	for ( var i = 1; i <= 3; i++) {
		if (strYr.charAt(0) == "0" && strYr.length > 1)
			strYr = strYr.substring(1)
	}
	month = parseInt(strMonth)
	day = parseInt(strDay)
	year = parseInt(strYr)
	if (pos1 == -1 || pos2 == -1) {
		// alert("The date format should be : mm/dd/yyyy")
		return false
	}
	if (strMonth.length < 1 || month < 1 || month > 12) {
		// alert("Please enter a valid month")
		return false
	}
	if (strDay.length < 1 || day < 1 || day > 31
			|| (month == 2 && day > daysInFebruary(year))
			|| day > daysInMonth[month]) {
		// alert("Please enter a valid day")
		return false
	}
	if (strYear.length != 4 || year == 0 || year < minYear || year > maxYear) {
		// alert("Please enter a valid 4 digit year between "+minYear+" and
		// "+maxYear)
		return false
	}
	if (dtStr.indexOf(dtCh, pos2 + 1) != -1
			|| isInteger(stripCharsInBag(dtStr, dtCh)) == false) {
		// alert("Please enter a valid date")
		return false
	}
	return true
}

function validacion(){
	if($('inicio').value != '' && $('fin').value != ''){
		if(isDate($('inicio').value) && isDate($('fin').value) ){
			if(validaFecha($('inicio').value, $('fin').value)){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}else if($('inicio').value == ''){
		if($('fin').value == ''){
			return true;
		}else{
			if(isDate($('fin').value)){
				return true;
			}else{
				return false;
			}
		}
	}else if($('fin').value == ''){
		if($('inicio').value == ''){
			return true;
		}else{
			if(isDate($('inicio').value)){
				return true;
			}else{
				return false;
			}
		}
	}
}

function validaFecha(fecha, fecha2){
	fs1=fecha.split("/");
	f1=new Date(fs1[2],fs1[1],fs1[0]);

	fs2=fecha2.split("/");
	f2=new Date(fs2[2],fs2[1],fs2[0]);

	if(f1 <= f2)
	return true;
	else
	return false;

	}


function init(){
	aceptar = $('aceptar');
	if(aceptar){
	aceptar.onclick = function(){
		f = $('frm_editar');
		if(	frm_validar_campos(['nombre']) ){
			if($('descripcion').value != ''){
				if($('disponible')){
					if($('disponible').value == '1'){
						f.submit();
					}else{
						$('nombre').focus();
						Effect.Shake($('nombre'));
					}
				}else{
					f.submit();
				}
			}else{
				Effect.Shake($('descripcion'))
			}
		}
	}
	}

	cancelar = $('cancelar');
	if(cancelar){
	cancelar.onclick = function(){
		if(confirm('Al cancelar se perderan los cambios hechos en este formulario, desea continuar?')){
			document.location.href = $('path').value+'reglamentos/';
		}
	}
	}

	fecha = $('inicio');
	if(fecha){
		if($('calendario')){
			Calendar.setup({
				button: 'calendario',
				electric : false,
				inputField : 'inicio',
				showsTime: false,
				ifFormat : '%d/%m/%Y'
			});
		}
	}
	
	fecha1 = $('fin');
	if(fecha1){
		if($('calendario2')){
			Calendar.setup({
				button: 'calendario2',
				electric : false,
				inputField : 'fin',
				showsTime: false,
				ifFormat : '%d/%m/%Y'
			});
		}
	}
	
	campo = $('nombre');
	if(campo){
		campo.onkeyup = function(){
			if(campo.value != ''){
					new Ajax.Updater('check', '../revisa_reglamento/', {
						method: 'post',
						onLoading: function(){ $('spinner').show(); $('check').hide(); },
						onComplete: function() { $('spinner').hide(); $('check').show()},
						parameters: {reglamento : campo.value , anterior : $('id').value }
					});
			}else{
				$('check').hide();
			}
		}
	}
	
}


addDOMLoadEvent(init);