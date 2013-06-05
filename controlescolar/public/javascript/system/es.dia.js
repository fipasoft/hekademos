 function catcalc(cal) {
 var date = cal.date;
 var time = date.getTime();
 var date2 = new Date(time);
 window.location=$("kumbia_path").value+"es/dia/"+$("card").value+"/"+date2.print("%Y-%m-%d");
 }




function init(){
    fecha = $('fecha');
    if(fecha){
    var f=$("date");
    fch=f.value.split("-");

     date=new Date(fch[0],fch[1]-1,fch[2]);
        //fecha.onblur = function(){ valFecha($('inicio')) };
            Calendar.setup({
                button: 'fecha',
                electric : false,
                 onUpdate : catcalc,
                 date: date,
                ifFormat : '%d/%m/%Y'
            });




    }


}


addDOMLoadEvent(init);