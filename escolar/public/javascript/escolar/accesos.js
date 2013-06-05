 function catcalc(cal) {
 var date = cal.date;
 var time = date.getTime();
 var date2 = new Date(time);
 window.location=$("kumbia_path").value+"escolar/accesos/"+date2.print("%Y-%m-%d");
 }




function init(){
    var fecha = $('fecha');
    if(fecha){
    var f=$("dia");
    var fch=f.value.split("-");
     var date=new Date(fch[0],fch[1]-1,fch[2]);
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