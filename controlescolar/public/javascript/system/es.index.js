function opciones(){
tipo=$('tipo');
div=$("opciones_alumno");
switch(tipo.value){
    case "A":
            div.show();
        break;
    case "":
            div.hide();
        break;
    default:
            div.hide();
        break;
}
}

function init(){
    frm_search=$('frm_search');
    if(frm_search){
    frm_search.onsubmit = function(){
                if(ValidateDateTime('inicio') && ValidateDateTime('fin')){
                    if(!validaFecha($('inicio').value,$('fin').value)){
                        alert("La fecha de inicio tiene que ser menor a la fecha final.");
                        return false;
                    }
                    }else
                        return false;

                }

    }

    a = $('aSearch');
    if(a){
    a.href = 'javascript:;';
    a.onclick = function(){
                    div_sw('search');

                }
    }
    reset = $('reset');
    if(reset){
    reset.onclick = function(){
        Effect.DropOut($('search'));
        frm_reset('frm_search');
    }
    }
    fecha = $('inicio');
    if(fecha){
            Calendar.setup({
                button: 'inicio',
                electric : false,
                inputField : 'inicio',
                showsTime: true,
                timeFormat: '24',
                ifFormat : '%d/%m/%Y %H:%M'
            });

    }

    fecha = $('fin');
    if(fecha){
            Calendar.setup({
                button: 'fin',
                electric : false,
                inputField : 'fin',
                showsTime: true,
                timeFormat: '24',
                ifFormat : '%d/%m/%Y %H:%M'
            });

    }

        tipo=$('tipo');
        if(tipo){
        tipo.onchange=function(){
        opciones();
        }
        opciones();
        }


    c = $('cicloBtn');
    if(c){
        c.onclick = function(){
            if($('cicloActual').style.display == 'none'){
                $('cicloSel').style.display = 'none';
                Effect.Appear('cicloActual');
            }else{
                $('cicloActual').style.display = 'none';
                Effect.Appear('cicloSel');
            }
        }
    }
    cS = $('cicloSelect');
    if(cS){
        cS.onchange = function (){$('frm_ciclo').submit();}
    }

}

function validaFecha(fecha, fecha2){
fss1=fecha.split(" ");
fs1=fss1[0].split("/");
tt=fss1[1].split(":");
f1=new Date(fs1[2],fs1[1],fs1[0],tt[0],tt[1],0);

fss2=fecha2.split(" ");
fs2=fss2[0].split("/");
tt=fss2[1].split(":");
f2=new Date(fs2[2],fs2[1],fs2[0],tt[0],tt[1],0);

if(f1 < f2)
return true;
else
return false;

}


addDOMLoadEvent(init);