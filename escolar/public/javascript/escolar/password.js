function init(){
    cancelar=$('cancelar');
    if(cancelar){
        cancelar.onclick=function(){
        document.location="./";
        }
    }
    
    if(document.layers)document.captureEvents(Event.KEYPRESS);if($("p1")!=null){new Tooltip($("p1"),{backgroundColor:"#FC9",borderColor:"#C96",textColor:"#000",textShadowColor:"#FFF"});$("p1").onkeypress=checkEnter;new Tooltip($("p2"),{backgroundColor:"#FC9",borderColor:"#C96",textColor:"#000",textShadowColor:"#FFF"});$("p2").onkeypress=checkEnter;new Tooltip($("p3"),{backgroundColor:"#FC9",borderColor:"#C96",textColor:"#000",textShadowColor:"#FFF"});$("p3").onkeypress=checkEnter;new Tooltip($("btn"),{backgroundColor:"#FC9",borderColor:"#C96",textColor:"#000",textShadowColor:"#FFF"});$("btn").onclick=function(){accion();};}}function accion(){if(trim($("p1").value).length>0&&trim($("p2").value).length>0&&trim($("p3").value).length>0){if($("p1").value==$("p2").value)$("form").submit();else
alert("El nuevo password no coincide con su confirmacion.");}else{alert("Todos los campos son obligatorios");}}function checkEnter(e){if(e&&e.which){e=e;characterCode=e.which;}else{if(!e){e=event;characterCode=e.keyCode;}else characterCode=-1;}if(characterCode==13){accion();}}addDOMLoadEvent(init);