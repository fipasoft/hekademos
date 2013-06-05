function init(){
    agregar = $('agregar');
    if(agregar){
    agregar.onclick = function(){
        f = $('frm_agregar');
        if(    frm_validar_campos(['nombre', 'ap', 'am']) ){
            f.submit();
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

    fecha = $('fnacimiento');
    calendario = $('b1');
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

    var campo = $('alumnos_id');
    if(campo){
    campo.onkeyup = function() {
        new Ajax.Updater('check', '../alumnos/info/', {
           method: 'post',
           onLoading: function(){ $('spinner').show(); $('check').hide(); },
           onComplete: function() { $('spinner').hide(); $('check').show()},
           parameters: {tabla: 'Alumnos', campo: campo.className, valor: campo.value}
        });
    }
    }

    mas = $('bMas');
    if(mas){
        var i = 0;
        mas.href = 'javascript:;';
        mas.onclick = function(){
            i++;
            var ht = '<label>C&oacute;digo' +
                        '<br /> ' +
                        '<input type="text" name="alumnos_id[]" id="alumnos_id' + i +  '" ' +
                               'size="10" maxlength="12" class="codigo"/>' +
                     '</label>' +
                     '<img id="spinner' + i +  '" src="../public/img/sp5/spinner.gif" style="display:none"/>' +
                     '<br />' +
                     '<div id="check' + i + '" class="check"></div>' +
                     '<div id="codigos' + (i + 1) + '" class="codigos"></div>';
            $('codigos' + i).update(ht);
            var campo2 = $('alumnos_id' + i);
            campo2.n = i;
            campo2.onkeyup = function() {
                var n = campo2.n;
                ajax = new Ajax.Updater('check' + n, '../alumnos/info/', {
                   method: 'post',
                   onLoading: function(){ $('spinner' + n).show(); $('check' + n).hide(); },
                   onComplete: function() { $('spinner' + n).hide(); $('check' + n).show()},
                   parameters: {tabla: 'Alumnos', campo: campo2.className, valor: campo2.value}
                });
            }
        }
    }

}
addDOMLoadEvent(init);