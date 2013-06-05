function init(){
if($("btn_img")){
	$("btn_img").href="javascript:;";
	$("btn_img").onclick=function(){
	div_sw("imagen");
	div_sw("tabla");
	if($("btn_img").innerHTML=="Ver el documento"){
		$("btn_img").innerHTML="Ocultar el documento";
	}else{
		$("btn_img").innerHTML="Ver el documento";
	}
	};
}
}
addDOMLoadEvent(init);