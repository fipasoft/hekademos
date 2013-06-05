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

function esImagen(img) {
    return (img.toLowerCase().endsWith(".jpg")
            || img.toLowerCase().endsWith(".jpeg")
            || img.toLowerCase().endsWith(".gif") || img.toLowerCase()
            .endsWith(".png"));

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

function add_select(num){
    var div = document.createElement('div');
    div.innerHTML += '<label for = "sel'+num+'">Reglamento</label><br />';
    var div1 = document.createElement('div');
    var select = $("sel").cloneNode(true);
    select.id = 'sel'+num;    //
    div.appendChild(select);
    div.appendChild(div1);
    $("contenedor").appendChild(div);
    select.onchange = function(){
        new Ajax.Request('./obtiene_articulos/' ,{
            method : 'post',
            onLoading : function(){
                            $('spinner1').show();
                        },
            onComplete : function(respuesta){
                                respuesta = (respuesta.responseText).split('|');
                                div1.innerHTML = options(respuesta);
                                $('spinner1').hide();
                        },
            parameters : { reglamento : select.value }
        } );
    }
}

function options(res){
    var options = '<label>Articulos</label> <br />';
    options += '<select name = "articulos[]" id = "art"> ';
    options += '<option></option> ';
    for(i = 0 ; i < res.length ; i++){
        articulo = res[i].split('/');
        if(articulo[1] != undefined){
            options += '<option value = "'+articulo[0]+'">Articulo '+articulo[1]+'</option>';
        }else{
            options += '<option></option>';
        }
    }
    options += '</select>';
    return options;
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

function obtiene_fecha() {  
       var fecha_actual = new Date()   
       var dia = fecha_actual.getDate()  
       var mes = fecha_actual.getMonth() + 1  
       var anio = fecha_actual.getFullYear()  
     
       if (mes < 10)  
      mes = '0' + mes  
       
         if (dia < 10)  
             dia = '0' + dia  
       
         return (dia + "/" + mes + "/" + anio)  
     }  

function init() {
    var i = 1;
    campo = $('codigo');
    if (campo) {
        campo.onkeyup = function() {
            if (campo.value != "") {
                new Ajax.Updater('ficha', './ficha/', {
                    method : 'post',
                    onLoading : function() {
                        $('spinner').show();
                        $('ficha').hide();
                        $('datos').hide();
                    },
                    onComplete : function() {
                        $('spinner').hide();
                        $('ficha').show();
                        if (!$('ficha').innerHTML
                                .startsWith('<span class="false">')) {
                            $('datos').show();
                            if($('amonestacion').value == $('suspension').value){        //        Muestra fieldset vigencia
                                $('vigencia').show();
                            }
                        } else {
                            $('datos').hide();
                        }
                    },
                    parameters : {
                        codigo : campo.value
                    }
                });
            } else {
                $('ficha').innerHTML = '<span class="false">No disponible</span><input type="hidden" id="disponible" value="false" />';
            }
        }
    }

    cancelar = $('cancelar');
    if (cancelar) {
        cancelar.onclick = function() {
            if (confirm('Al cancelar se perderan los cambios hechos en este formulario, desea continuar?')) {
                document.location.href = './';
            }
        }
    }

    agregar = $('agregar');
    if (agregar) {
        agregar.onclick = function() {
            
            if($('amonestacion').value != $('grupal').value){
            if (trim($('codigo').value) != ""
                    && trim($('descripcion').value) != ""
                    && isDate($('fecha').value)
                    && trim($('disponible').value) == "true") {
                
                if(validacion()){
                    if(validaFecha($('fecha').value,obtiene_fecha() )){ 
                if (trim($('imagen').value) == '') {
                    $('frm_agregar').submit();
                } else {
                    if (esImagen($('imagen').value)) {
                        $('frm_agregar').submit();
                    } else {
                        try {
                            Effect.Shake('imagen');
                        } catch (e) {
                        }
                        $('emsj').show();
                        setTimeout("div_sw('emsj');",7000);

                    }
                }
                //
                    }else{
                        $('fecha').focus();
                        Effect.Shake('fecha');
                    }
                }else{
                    Effect.Shake('inicio');
                    Effect.Shake('fin');
                }
            } else {
                try {
                    Effect.Shake('codigo');
                    Effect.Shake('fecha');
                    Effect.Shake('descripcion');

                } catch (e) {
                    alert('Faltan campos por llenar ' + e);
                }
            }
            //
            }else{
                if ($('grupos').value != ""
                    && trim($('descripcion').value) != ""
                    && isDate($('fecha').value)) {
                if (trim($('imagen').value) == '') {
                    $('frm_agregar').submit();
                } else {
                    if (esImagen($('imagen').value)) {
                        $('frm_agregar').submit();
                    } else {
                        try {
                            Effect.Shake('imagen');
                        } catch (e) {
                        }
                        $('emsj').show();
                        setTimeout("div_sw('emsj');",7000);

                    }
                }
            } else {
                try {
                    Effect.Shake('grupos');
                    Effect.Shake('fecha');
                    Effect.Shake('descripcion');

                } catch (e) {
                    alert('Faltan campos por llenar ' + e);
                }
            }
            }
        }
    }
    fecha = $('fecha');
    if (fecha) {
        Calendar.setup( {
            button : 'fecha',
            electric : false,
            inputField : 'fecha',
            ifFormat : '%d/%m/%Y'
        });

    }
    
    tipo = $('amonestacion');
    if(tipo){
        tipo.onchange = function(){
            if(tipo.value != $('grupal').value && tipo.value != ''){
                $('grupo').hide();
                $('alumno').show();
                //$('datos').show();
                if(tipo.value == $('suspension').value && $('codigo').value != ''){  //Muestra el fieldset de vigencia
                    $('vigencia').show();
                }else{
                    $('vigencia').hide();
                }
            }else if(tipo == ''){
                $('grupo').hide();
                $('alumno').hide();
                $('datos').hide();
                $('vigencia').hide();
            }else{                //Grupal
                $('alumno').hide();
                $('grupo').show();
                $('vigencia').hide();
            }
        }
    }
    
    mostrar = $('mostrar');
    if(mostrar){
        mostrar.href = 'javascript:;';
        mostrar.onclick = function(){
            $('mostrar').hide();
            $('ocultar').show();
            div_sw('alumnos');
        }
    }
    
    ocultar = $('ocultar');
    if(ocultar){
        ocultar.href = 'javascript:;';
        ocultar.onclick = function(){
            $('ocultar').hide();
            $('mostrar').show();
            Effect.DropOut($('alumnos'));
        }
    }
    
    grupos = $('grupos');
    if(grupos){
        grupos.onchange = function(){
            if(grupos.value != ''){
                new Ajax.Updater('alumnos', './obtiene_alumnos',{
                    method : 'post',
                    onComplete: function(){
                        new Ajax.Updater('num_alumnos','./cuenta_alumnos',{
                            method : 'post',
                            parameters : {grupo : grupos.value }
                        });
                        if($('ocultar').style.display == 'none')
                            $('mostrar').show();
                            $('datos').show();
                            $('num_alumnos').show();
                        },
                    parameters : { grupo : grupos.value }
                });
            }else{
                $('datos').hide();
                $('alumnos').hide();
                $('mostrar').hide();
                $('ocultar').hide();
                $('num_alumnos').hide();
            }
        }
    }

    select = $('sel')
    if(select){
        select.onchange = function(){
            if(select.value != ''){
            new Ajax.Request('./obtiene_articulos/' ,{
                method : 'post',
                onLoading : function(){
                                    $('spinner1').show();
                            },
                onComplete : function(respuesta){
                                    respuesta = (respuesta.responseText).split('|');
                                    $('div').innerHTML = options(respuesta);
                                    $('spinner1').hide();
                            },
                parameters : { reglamento : select.value }
            } );
            }
            $('mas').show();
        }
    }
    
    mas = $('a_mas');
    if(mas){
        mas.href = 'javascript:;';
        mas.onclick = function(){
            add_select(i);
            i++;
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

}
addDOMLoadEvent(init);