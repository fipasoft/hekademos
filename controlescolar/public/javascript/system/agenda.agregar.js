//no instrusivo functions

function cancelarverNoInstrusivo(btnC){
btnC.href="javascript:;";
			btnC.onclick=function(){
			pars=this.id.split('_');
			cancela_ver(pars[2],pars[1]);
}
}

function editaraccionNoInstrusivo(input){
			input.onclick=function(){
			pars=this.id.split('_');
			accion_editar(pars[1],pars[2]);
}
}

function verNoIntrusivo(btnV){
btnV.href="javascript:;";
			btnV.onclick=function(){
			pars=this.id.split('_');
			ver(pars[1],pars[2]);
}
}

function editarNoIntrusivo(btnE){
btnE.href="javascript:;";
			btnE.onclick=function(){
			pars=this.id.split('_');
			editar(pars[1],pars[2]);
}
}

function verJNoIntrusivo(btnV){
btnV.href="javascript:;";
			btnV.onclick=function(){
			pars=this.id.split('_');
			ver(pars[1],'new'+pars[2]);
}
}

function editarJNoIntrusivo(btnE){
btnE.href="javascript:;";
			btnE.onclick=function(){
			pars=this.id.split('_');
			editar(pars[1],'new'+pars[2]);
}
}

function nuevoNoIntrusivo(btnN){
btnN.href="javascript:;";
			btnN.onclick=function(){
			pars=this.id.split('_');
			cambia('p_'+pars[2],'div_'+pars[2]);
}
}

function cambioNoIntrusivo(input){
			input.onchange=function(){;
			pars=this.id.split('_');
			colocaBoton(pars[1],pars[2]);
}
}

function cambioNoIntrusivoClick(input){
			input.onclick=function(){;
			pars=this.id.split('_');
			colocaBoton(pars[1],pars[2]);
}
}

function cambioNoIntrusivoClick2(input,area,categoria){
			input.onclick=function(){
			pars=this.id.split('_');
			colocaBoton(pars[1],pars[2]+'_'+area+'_'+categoria);
}
}

function cancelarNoIntrusivo(input){
			input.onclick=function(){
			pars=this.id.split('_');
			cambia('p_'+pars[2],'div_'+pars[2]);
}
}

function cancelarJavascriptNoIntrusivo(input){
			input.onclick=function(){
			pars=this.id.split('_');
			cambia('area'+pars[3],'areaForma'+pars[3]);
}
}


function aceptarNoIntrusivo(input){
			input.onclick=function(){
			pars=this.id.split('_');
			accion(pars[1],pars[2]);
}
}

function aceptarJavascriptNoIntrusivo(input){
			input.onclick=function(){
			pars=this.id.split('_');
			accionJavascript(pars[2],pars[4],pars[3],pars[1]);
}
}


function agregaNoIntrusivo(btnA){
			btnA.href="javascript:;";
			btnA.onclick=function(){
			pars=this.id.split('_');
			cambia('formAgrega'+pars[2],'btnAgrega'+pars[2]);
}
}

function agreNoIntrusivo(input){
			input.onclick=function(){
			pars=this.id.split('_');
			forma(pars[2],pars[3],pars[2],pars[1]);
}
}

function quitarNoIntrusivo(btnQ){
			btnQ.href="javascript:;";
			btnQ.onclick=function(){
			pars=this.id.split('_');
			cambia('formAgrega'+pars[2],'btnAgrega'+pars[2]);
}
}
//////////////////////////////////////////

function init(){
$$("a.btnVer").each( function(btnV) {
			verNoIntrusivo(btnV)
			});

$$("a.btnEditar").each( function(btnE) {
			editarNoIntrusivo(btnE);
			});

$$("a.btnNuevo").each( function(btnN) {
			nuevoNoIntrusivo(btnN);
			});

$$("input.txtFecha").each( function(input) {
			cambioNoIntrusivo(input);
			});

$$("input.btnActivo").each( function(input) {
			cambioNoIntrusivoClick(input);
			});

$$("input.btnCancelar").each( function(input) {
			cancelarNoIntrusivo(input);
			});

$$("input.btnAceptar").each( function(input) {
			aceptarNoIntrusivo(input);
			});

$$("a.btnAgrega").each( function(btnA) {
			agregaNoIntrusivo(btnA);
			});

$$("input.btnAgrega").each( function(input) {
			agreNoIntrusivo(input);
			});

$$("a.btnQuitar").each( function(btnQ) {
			quitarNoIntrusivo(btnQ);
			});


$$("a.calendar").each( function(calendar) {
calendar.href='javascript:;';
Calendar.setup({
				button: calendar.id,
				electric : false,
				inputField : 't'+calendar.id,
				ifFormat : '%d/%m/%Y'
			});
			});

}

addDOMLoadEvent(init);



function accion(id,llave){
if(valida(id,llave)){
div_actual='div_'+llave;
div_spinner='spinner_'+llave;
cambia(div_actual,div_spinner);

ciclo=$('ciclo_id').value;
evento=$('evento_'+id+'_'+llave).value;
ini=$('tcalendario_'+id+'_'+llave).value;
fin=$('tcalendario2_'+id+'_'+llave).value;

if($('activo_'+id+'_'+llave).checked)
activo=$('activo_'+id+'_'+llave).value;
else
activo=0;

new Ajax.Request(path+'agenda/guarda',
						{
							method:'post',
							parameters: parametros(ciclo,evento,ini,fin,activo),
							onSuccess:function(xml){
									r=xml.responseText.split('[');
											if(r[0]==1){
											$('p_'+llave).innerHTML='<p><a id="Eveneditar_'+r[2]+'_'+llave+'" class="btnEditar" style="color:#000000;text-decoration:none;font-size:16px;" >'+$('eventonombre_'+id+'_'+llave).value+' </a></p>';
											$(div_actual).innerHTML=r[1]+'<br/><a id="btncancelar_'+id+'_'+llave+'" class="btnCancelarVer" >Continuar</a>';
											cancelarverNoInstrusivo($('btncancelar_'+id+'_'+llave));

											cambia(div_actual,div_spinner);
												//verNoIntrusivo($('Evenver_'+r[2]+'_'+llave))
												editarNoIntrusivo($('Eveneditar_'+r[2]+'_'+llave))
											//cambia('p_'+llave,div_spinner);
											}else{

											di=$(div_actual);
											cambia(div_actual,div_spinner);
											var newp = document.createElement('p');
											newp.innerHTML=r[1];


											last=di.lastChild;
											if(last.tagName!=null){

											if(last.tagName=='p' || last.tagName=='P')
											di.removeChild(last);
											}
											di.appendChild(newp);
										}

										}
						});

}

}

function parametrosEspecial(grupo,privilegios,usuarios){
usu=usuarios.value;
if(grupo.value.toLowerCase()=='profesores' && usuarios.value=='')
usu='profesores';

return "grupo="+grupo.value+"&usuarios="+usu+"&privilegios="+creaPrivilegios(privilegios);
}

function creaPrivilegios(privilegios){
elementos=privilegios.childNodes;
privi='';
for(index=0;index<elementos.length;index++){
elemento=elementos[index];
if(elemento.type=="checkbox"){
		if(elemento.checked)privi+=elemento.value+"|"
}

}
if(privi.length>0)
return privi.substr(0,privi.length-1);
}



function accionJavascript(llave,categoria,zona,id){
if($('div_usuarios_'+id+'_'+llave))
	tipo='E';
	else tipo='P';


if(valida(id,llave,tipo)){
div_actual='div_'+llave;
div_spinner='spinner_'+llave;
cambia(div_actual,div_spinner);
ciclo=$('ciclo_id').value;
evento=$('evento_'+id+'_'+llave).value;
ini=$('tcalendario_'+id+'_'+llave).value;
fin=$('tcalendario2_'+id+'_'+llave).value;

if($('activo_'+id+'_'+llave).checked)
activo=$('activo_'+id+'_'+llave).value;
else
activo=0;

prms=parametros(ciclo,evento,ini,fin,activo);

if(tipo=='E'){
grupo=$('grupo_'+id+'_'+llave);
privilegios=$('div_privilegios_'+id+'_'+llave);
if($('txt_profesores_'+id+'_'+llave))
usuarios=$('txt_profesores_'+id+'_'+llave);
else
usuarios=$('usuarios_'+id+'_'+llave);

prms+="&"+parametrosEspecial(grupo,privilegios,usuarios);
}

prms+="&tipo="+tipo;

new Ajax.Request(path+'agenda/guarda',
						{
							method:'post',
							parameters: prms,
							onSuccess:function(xml){
									r=xml.responseText.split('[');
											if(r[0]==1){
											var newp = document.createElement('p');
												newp.setAttribute('id','p_new'+llave);
												ids=r[2].split("|");
												newp.innerHTML='<a id="newEveneditar_'+ids[0]+'_'+llave+'" class="btnEditar" style="color:#000000;text-decoration:none;font-size:16px;">'+$('nombreevento'+llave).value+' </a>';

											var newdiv = document.createElement('div');
												newdiv.setAttribute('id','spinner_new'+llave);
												newdiv.setAttribute('style','display:none');

												newdiv.innerHTML='<img id="spinner" src="'+path+'public/img/sp5/spinner.gif" />';

											var newdiv2 = document.createElement('div');
												newdiv2.setAttribute('id','div_new'+llave);
												newdiv2.setAttribute('style','display:none');

												newdiv2.innerHTML='';

												$('categoria_'+categoria).appendChild(newp);
												$('categoria_'+categoria).appendChild(newdiv);
												$('categoria_'+categoria).appendChild(newdiv2);

												//verJNoIntrusivo($('newEvenver_'+r[2]+'_'+llave)); //href="javascript:;" onClick="'+"ver('"+r[2]+"','new"+llave+"');"+'"
												editarJNoIntrusivo($('newEveneditar_'+ids[0]+'_'+llave))  //href="javascript:;" onClick="'+"editar('"+r[2]+"','new"+llave+"');"+'"

											$(div_actual).innerHTML=r[1]+'<br/><a id="btncancelarespecial_'+id+'_'+llave+'" class="btnCancelarVer" >Continuar</a>';
											cambia(div_actual,div_spinner);
											$(div_spinner).style.dislay=='none';
											b=$('btncancelarespecial_'+id+'_'+llave);
											b.href='javascript:;';
											b.zona=zona;
											b.div_actual=div_actual;
											b.onclick=function(){
											if($('area'+this.zona).style.display=="none")
											$('area'+this.zona).style.display="";

											Effect.Appear('areaForma'+this.zona);
											if($('formAgrega'+this.zona).style.display=="none")
											$('formAgrega'+this.zona).style.display="";

											Effect.Appear('formAgrega'+this.zona);
											$(this.div_actual).style.display='none';
											}
											}else{

											di=$(div_actual);
											cambia(div_actual,div_spinner);
											var newp = document.createElement('p');
											newp.innerHTML=r[1];


											last=di.lastChild;
											if(last.tagName!=null){

											if(last.tagName=='p' || last.tagName=='P')
											di.removeChild(last);
											}
											di.appendChild(newp);
											}
										}
						});

}

}



function accion_editar(id,llave){
if($('div_usuarios_'+id+'_'+llave))
	tipo='E';
	else tipo='P';

if(valida_editar(id,llave,tipo)){
if($(div_actual='div_'+llave+'_'+id)!=null){
div_actual='div_'+llave+'_'+id;
div_spinner='spinner_'+llave+'_'+id;
}else{
div_actual='div_'+llave;
div_spinner='spinner_'+llave;
}
cambia(div_actual,div_spinner);
id_agenda=$("id_agenda_"+id+'_'+llave).value;
ini=$('tcalendarioedit_'+id+'_'+llave).value;
fin=$('tcalendarioedit2_'+id+'_'+llave).value;
ciclo=$('ciclo_id').value;
if($('activoedit_'+id+'_'+llave).checked)
activo=$('activoedit_'+id+'_'+llave).value;
else
activo=0;
prms=parametros_edit(ciclo,ini,fin,activo,id_agenda);
if(tipo=='E'){
grupo=$('grupo_'+id+'_'+llave);
privilegios=$('div_privilegios_'+id+'_'+llave);

if($('txt_profesores_'+id+'_'+llave))
usuarios=$('txt_profesores_'+id+'_'+llave);
else
usuarios=$('usuarios_'+id+'_'+llave);

prms+="&"+parametrosEspecial(grupo,privilegios,usuarios);
}

prms+="&tipo="+tipo


new Ajax.Request(path+'agenda/editar',
						{
							method:'post',
							parameters: prms,
							onSuccess:function(xml){
									r=xml.responseText.split('[');
											if(r[0]==1){
											$(div_actual).innerHTML='<p>'+r[1]+'<br/><a id="btncancelar_'+id+'_'+llave+'" class="btnCancelarVer" >Continuar</a></p>';

											cancelarverNoInstrusivo($('btncancelar_'+id+'_'+llave));

											cambia(div_actual,div_spinner);

											}
										else{

											di=$(div_actual);
											cambia(div_actual,div_spinner);
											var newp = document.createElement('p');
											newp.innerHTML=r[1];


											last=di.lastChild;
											if(last.tagName!=null){

											if(last.tagName=='p' || last.tagName=='P')
											di.removeChild(last);
											}
											di.appendChild(newp);
										}
										}
						});

}

}


 function editar(id,llave){
	new Ajax.Request(path+'agenda/ver',
						{
							method:'post',
							parameters: "id="+id,
							onSuccess:function(xml){
										r=xml.responseText.split('[');
										if(r[0]==1){
										elementos=r[1].split('|');
										if(r.length>2)
										forma_editar_especial(elementos,llave,id,r[2],r[3],r[4].split("|"),elementos[7]);
										else
										forma_editar(elementos,llave,id);

										if($('div_'+llave+'_'+id)!=null){
										if($('div_'+llave+'_'+id).style.display=="none");
 										cambia('p_'+llave+'_'+id,'div_'+llave+'_'+id);
										}else if($('div_'+llave)!=null){
										if($('div_'+llave).style.display=="none");
 										cambia('p_'+llave,'div_'+llave);
										}
										}
										}
						});
 }



 function ver(id,llave){
	new Ajax.Request(path+'/agenda/ver',
						{
							method:'post',
							parameters: "id="+id,
							onSuccess:function(xml){
										r=xml.responseText.split('[');
										if(r[0]==1){
										elementos=r[1].split('|');
										forma_ver(elementos,llave,id);
										cambia('p_'+llave,'div_'+llave);
										}
										}
						});
 }

 function addOption(elSel,elOptNew){
  	try {
    	elSel.add(elOptNew, null); // standards compliant; doesn't work in IE
  	}
  	catch(ex) {
    	elSel.add(elOptNew); // IE only
  	}
 }

function llenaLista(lista,arreglo,seleccionado){
code="";
select=$(lista);

	var elOptNew = document.createElement('option');
  	elOptNew.text = "Elija un grupo";
  	elOptNew.value = "-1";

  	addOption(select,elOptNew);

for(index=0;index<arreglo.length;index++){
ele=arreglo[index];

	var elOptNew = document.createElement('option');
  	elOptNew.text = ele
  	elOptNew.value = ele;
  	if(seleccionado==ele)
	  	elOptNew.selected=true;

  	addOption(select,elOptNew);
}



return code;
}

function cargaUsuarios(div,id_aco,llave,id,usuario,div_priv,btn){
if(id_aco.toLowerCase()!='profesores'){
new Ajax.Request(path+'agenda/obtenusuarios',
						{
							method:'post',
							parameters: "id="+id_aco,
							onSuccess:function(xml){
										r=xml.responseText.split('['); //alert("usuarios: "+xml.responseText);
										if(r[0]==1){
										us=r[1].split("|");
										elementos=div.split("_");
										code='<label for="usuarios_'+elementos[2]+'_'+elementos[3]+'">Usuario</label><br/>';
										code+='<select name="usuarios_'+elementos[2]+'_'+elementos[3]+'" id="usuarios_'+elementos[2]+'_'+elementos[3]+'">';
										code+='<option value="-2">Seleccione una opcion</option>';

										chk='';
										if(usuario=='-1')
										chk=' selected="selected" ';

										code+='<option value="-1" '+chk+'>Todos</option>';

										for(index=0;index<us.length;index++){
											chk='';
											if(us[index]==usuario)
											chk=' selected="selected" '
											code+='<option value="'+us[index]+'" '+chk+'>'+us[index]+'</option>'
										}
										code+='</select>';
										$(div).innerHTML=code;
										uss=$('usuarios_'+elementos[2]+'_'+elementos[3]);
										grupos=$('grupo_'+elementos[2]+'_'+elementos[3]);
										grupo_sel=grupos.options[grupos.selectedIndex].value;
										uss.n=btn;
										uss.onchange=function (){
										Effect.Appear(this.n);
										seleccionado=this.options[this.selectedIndex];
										valor=seleccionado.value;
										if(valor!='-2'){
										if(seleccionado.value=='-1')
												valor=grupo_sel;

											cargaPrivilegios(div_priv,valor,id,null,btn);
										}
											}

										}
							}
						});
}else{
	prof='';
	if(usuario!=null)
		prof=usuario;
	elementos=div.split("_");
	if(prof.toLowerCase()=='profesores')
	prof='';

	code='Codigo<br/><input type="text" size="12" maxlength="12" id="txt_'+id_aco+'_'+elementos[2]+'_'+elementos[3]+'" name="txt_'+id_aco+'_'+elementos[2]+'_'+elementos[3]+'" value="'+prof+'">';
	code+='<img id="spinner_'+id_aco+'_'+elementos[2]+'_'+elementos[3]+'" style="display: none;" src="'+path+'public/img/sp5/spinner.gif"/>';
			code+='<div id="check_'+id_aco+'_'+elementos[2]+'_'+elementos[3]+'" class="check" style="margin-right:0;" >';
			//code+='<span class="true">Todos los profesores</span><input id="disponible_profesores_'+elementos[2]+'_'+elementos[3]+'" type="hidden" value="1"/>';
			code+='</div>';
	$(div).innerHTML=code;
	txt=$('txt_'+id_aco+'_'+elementos[2]+'_'+elementos[3]);
	txt.espacio='check_'+id_aco+'_'+elementos[2]+'_'+elementos[3];
	txt.div_priv=div_priv;
	txt.id2=id;
	txt.privilegios=null;
	txt.btn=btn;
	txt.onchange=function(){
	Effect.Appear(this.btn);
	}
	txt.onkeyup=function(){
		revisaProfesor(this,true);
	}
		//if(usuario!=null)
				revisaProfesor(txt,false);
}
}

function revisaProfesor(txtProf,rcg){
	elements=txtProf.id.split('_');
	div=$(txtProf.espacio);
	if(txtProf.value!=''){

	Effect.Appear('spinner_'+elements[1]+'_'+elements[2]+'_'+elements[3]);
	new Ajax.Request(path+'profesores/info',
						{
							method:'post',
							parameters: "valor="+txtProf.value+'&info=1',
							onSuccess:function(xml){
										if(xml.responseText!=-1){

										nombre=xml.responseText;
										valor=1;
										clase='true';
										if(rcg)
										cargaPrivilegios(txtProf.div_priv,'profesores',txtProf.id2,txtProf.privilegios,txtProf.btn);

										}else{
										nombre='No disponible';
										clase='false';

										valor=0;
										if(rcg)
										$(txtProf.div_priv).innerHTML='';
										}
										Effect.Fade('spinner_'+elements[1]+'_'+elements[2]+'_'+elements[3]);


										div.innerHTML='<span class="'+clase+'">'+nombre+'</span><input id="disponible_profesores_'+elements[2]+'_'+elements[3]+'" type="hidden" value="'+valor+'"/>';

							}
						});
	}else{
		cargaPrivilegios(txtProf.div_priv,'profesores',txtProf.id2,txtProf.privilegios,txtProf.btn);
		div.innerHTML='<span class="true">Todos los profesores</span><input id="disponible_profesores_'+elements[2]+'_'+elements[3]+'" type="hidden" value="1"/>';
	}
}

 function obtenGrupos(div,lista,eve_id,div_us,id,llave,grupo,usuario,privilegios,btn){
 select=$(lista+id+'_'+llave);
 if(select!=null){

	new Ajax.Request(path+'agenda/obtengrupos',
						{
							method:'post',
							parameters: "id="+eve_id,
							onSuccess:function(xml){
										r=xml.responseText.split('['); //alert("grupos: "+xml.responseText);
										if(r[0]==1){
											grupos=r[1].split('|');
											llenaLista(lista+id+'_'+llave,grupos,grupo);

											if(grupo!=null){
												us=usuario;
												if(grupo==usuario && grupo.toLowerCase()!='profesores')us='-1';

												if(grupo.toLowerCase()=='profesores')usuario=grupo;

												cargaUsuarios(div_us+id+'_'+llave,grupo,llave,eve_id,us,div+id+'_'+llave,btn);
												cargaPrivilegios(div+id+'_'+llave,usuario,eve_id,privilegios,btn);

											}
											select.n=btn;
											select.onchange=function(){
											Effect.Appear(this.n);
											seleccionado=this.options[this.selectedIndex];
											$(div+id+'_'+llave).innerHTML='';
											if(seleccionado.value!="-1"){
												//cargaPrivilegios(div+id+'_'+llave,seleccionado.value,eve_id,null);
												cargaUsuarios(div_us+id+'_'+llave,seleccionado.value,llave,eve_id,null,div+id+'_'+llave,btn);
											}
											};
										}
										}

						});

 }
}

function formaPrivilegios(privilegios,actuales){
code='';
if(actuales!=null){
for(index=0;index<privilegios.length;index++){
chk='';
if(existePrivilegio(privilegios[index],actuales))
chk=' checked="checked" ';

code+='<INPUT TYPE="CHECKBOX" '+chk+' NAME="privilegio_'+index+'" id="privilegio_'+index+'" value="'+privilegios[index]+'">'+privilegios[index]+"<br/>";

}
}else{
for(index=0;index<privilegios.length;index++){
code+='<INPUT TYPE="CHECKBOX" NAME="privilegio_'+index+'" id="privilegio_'+index+'" value="'+privilegios[index]+'">'+privilegios[index]+"<br/>";

}
}
return code;
}

function existePrivilegio(priv,privilegios){
for(ind=0;ind<privilegios.length;ind++){
if(privilegios[ind]==priv)return true;

}

return false;
}

function cargaPrivilegios(div,id,evento_id,privilegios,btn){
 div=$(div);

 if(div!=null){
 grupos=new Array();
	new Ajax.Request(path+'agenda/obtenacos',
						{
							method:'post',
							parameters: "id="+evento_id+'&aro='+id,
							onSuccess:function(xml){
										r=xml.responseText.split('[');
										if(r[0]==1){
										if(r[1].length==0){
										alert("No fue posible obtener los privilegios intentelo mas tarde.");
										div.innerHTML='';
										}else{
										div.innerHTML=formaPrivilegios(r[1].split("|"),privilegios);
										chks=div.childNodes;
										to=chks.length;
										for(iy=0;iy<to;iy++){
										if(chks[iy].type=='checkbox'){
											chks[iy].n=btn;
											chks[iy].onclick=function(){
											Effect.Appear(this.n);
											}
										}
										}
										Effect.Appear(div);
										}


										}
										}

						});

 }
}

function valida_editar(id,llave){

ini=$('tcalendarioedit_'+id+'_'+llave);
fin=$('tcalendarioedit2_'+id+'_'+llave);

iniciclo=$('ciclo_inicio');
finciclo=$('ciclo_fin');


if(!valFecha1(ini)){
alert("Fecha invalida en inicio.\nFormato para fechas: 01/01/2007 o 01/01/07...");
return false;
}


if(!valFecha1(fin)){
alert("Fecha invalida en fin.\nFormato para fechas: 01/01/2007 o 01/01/07...");
return false;
}

if(!validaFecha(ini.value,fin.value)){
alert("La fecha de inicio debe ser menor a la fecha final");
return false;
}

if(!valInicioPeriodo(ini.value,iniciclo.value)){
alert("La fecha de inicio del evento es menor a la fecha de inicio del ciclo");
return false;
}


if(!valFinPeriodo(fin.value,finciclo.value)){
alert("La fecha de fin del evento es mayor a la fecha de fin del ciclo");
return false;
}

if(tipo=='E')
return valida_especial(id,llave);


return true;
}



function valida(id,llave,tipo){
ciclo=$('ciclo_id');
evento=$('evento_'+id+'_'+llave);
ini=$('tcalendario_'+id+'_'+llave);
fin=$('tcalendario2_'+id+'_'+llave);

iniciclo=$('ciclo_inicio');
finciclo=$('ciclo_fin');

if(ciclo.value==''){
alert('El ciclo no es valido');
return false;
}

if(evento.value==''){
alert('El evento no es valido');
return false;
}

if(!valFecha1(ini)){
alert("Fecha invalida en inicio.\nFormato para fechas: 01/01/2007 o 01/01/07...");
return false;
}


if(!valFecha1(fin)){
alert("Fecha invalida en fin.\nFormato para fechas: 01/01/2007 o 01/01/07...");
return false;
}

if(!validaFecha(ini.value,fin.value)){
alert("La fecha de inicio debe ser menor a la fecha final");
return false;
}

if(!valInicioPeriodo(ini.value,iniciclo.value)){
alert("La fecha de inicio del evento es menor a la fecha de inicio del ciclo");
return false;
}


if(!valFinPeriodo(fin.value,finciclo.value)){
alert("La fecha de fin del evento es mayor a la fecha de fin del ciclo");
return false;
}

if(tipo=='E')
return valida_especial(id,llave);

return true;
}

function valida_especial(id,llave){
grupo=$('grupo_'+id+'_'+llave)
if(grupo==null){
alert('No existe la lista de grupo.');
return false;
}

if(grupo.value=='-1'){
alert('Seleccione el grupo');
return false;
}

if(grupo.value.toLowerCase()=='profesores'){
profesor=$('txt_profesores_'+id+'_'+llave);
if(profesor==null){
	alert('No ha elegido a el profesor.');
	return false;
}


if($('disponible_profesores_'+id+'_'+llave).value!='1'){
alert('Introduzca un codigo de profeosr valido');
return false;
}

}else{

usuarios=$('usuarios_'+id+'_'+llave)
if(usuarios==null){
alert('No existe la lista de usuarios.');
return false;
}

if(usuarios.value=='-2'){
alert('Seleccione un usuario');
return false;
}

}


privilegios=$('div_privilegios_'+id+'_'+llave);
if(privilegios==null){
alert('No existe la lista de privilegios.');
return false;
}

elementos=privilegios.childNodes;
b=false;
for(index=0;index<elementos.length && !b;index++){
elemento=elementos[index];
if(elemento.type=="checkbox"){
		if(elemento.checked)b=true;
}
}

if(!b){
alert("Seleccione al menos un privilegio");
return false;
}


return true;
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

function valInicioPeriodo(fecha, fecha2){
fs1=fecha2.split("-");
f1=new Date(fs1[0],fs1[1],fs1[2] );

fs2=fecha.split("/");
f2=new Date(fs2[2],fs2[1],fs2[0]);

if(f1 <= f2)
return true;
else
return false;

}


function valFinPeriodo(fecha, fecha2){

fs1=fecha2.split("-");
f1=new Date(fs1[0],fs1[1],fs1[2] );

fs2=fecha.split("/");
f2=new Date(fs2[2],fs2[1],fs2[0]);

if(f1 >= f2)
return true;
else
return false;

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


function parametros(ciclo,evento,ini,fin,activo){

return 'id='+ciclo+'&id_evento='+evento+'&ini='+ini+'&fin='+fin+'&activo='+activo;
}

function parametros_edit(ciclo,ini,fin,activo,id_agenda){

return 'id='+ciclo+'&ini='+ini+'&fin='+fin+'&activo='+activo+"&id_agenda="+id_agenda;
}

 function cambia(ele1,ele2){
 if($(ele1).style.display == 'none'){
 $(ele2).style.display = 'none';
 Effect.Appear(ele1);
 }else{
 $(ele1).style.display = 'none';
 Effect.Appear(ele2);
 }
 }

 function cancela(llave){
 	cambia('area'+llave,'areaForma'+llave);
 }




 function forma_editar(elementos,llave,id){
 code="";
 	code+='<form>';
					code+='<fieldset>';
						code+='<legend>'+elementos[6]+'</legend>';
						code+='<label for="inicio">Inicio</label><br />';
						code+='<input type="text" name="inicio" class="txtFecha" id="tcalendarioedit_'+id+'_'+llave+'" size="10" maxlength="10" value="'+elementos[3]+'" />';
						code+=' <a class="calendar" id="calendarioedit_'+id+'_'+llave+'"><img src="'+path+'public/img/sp5/calendario.png" /></a>';
						code+='<br />';
						code+='<label for="fin">Fin</label><br />';
						code+='<input type="text" name="fin" class="txtFecha" id="tcalendarioedit2_'+id+'_'+llave+'" size="10" maxlength="10" value="'+elementos[4]+'"  />';
						code+=' <a class="calendar" id="calendarioedit2_'+id+'_'+llave+'"><img src="'+path+'public/img/sp5/calendario.png" /></a>';
						code+='<br />';
						code+='<label for="activo">Activo</label><br />';
						check="";
						if(elementos[5]==1)
						check=' checked="checked" ';

						code+='<input class="btnActivo" type="checkbox" '+check+' id="activoedit_'+id+'_'+llave+'" name="activoedit_'+id+'_'+llave+'" value="1" />';
						code+='<input type="hidden"  id="id_agenda_'+id+'_'+llave+'" name="id_agenda_'+id+'_'+llave+'" value="'+elementos[0]+'"/>';
						code+='<input type="hidden"  id="eventoedit'+llave+'" name="evento'+llave+'" value="'+elementos[6]+'"/>';
						code+='<input type="hidden"  id="nombreeventoedit'+llave+'" name="nombreevento'+llave+'" value="'+elementos[6]+'"/>';
					code+='</fieldset>';
					code+='<div id="botones">';
						code+='<input type="button" class="btnCacelarVer" id="btncancelar_'+id+'_'+llave+'" value="Cancelar" >';
						code+='<input type="button" class="btnEditarB" id="btneditar_'+id+'_'+llave+'" value="Editar" style="display:none;">';
					code+='</div>';
				code+='</form>';

				if($('div_'+llave+'_'+id)!=null )
				$('div_'+llave+'_'+id).innerHTML=code;
				else
				$('div_'+llave).innerHTML=code;

	$('calendarioedit_'+id+'_'+llave).href='javascript:;';
		Calendar.setup({
				button: 'calendarioedit_'+id+'_'+llave,
				electric : false,
				inputField : 'tcalendarioedit_'+id+'_'+llave,
				ifFormat : '%d/%m/%Y'
			});
	$('calendarioedit2_'+id+'_'+llave).href='javascript:;';
		Calendar.setup({
				button: 'calendarioedit2_'+id+'_'+llave,
				electric : false,
				inputField : 'tcalendarioedit2_'+id+'_'+llave,
				ifFormat : '%d/%m/%Y'
			});

				cambioNoIntrusivoClick($('activoedit_'+id+'_'+llave));
				cambioNoIntrusivo($('tcalendarioedit_'+id+'_'+llave));
				cambioNoIntrusivo($('tcalendarioedit2_'+id+'_'+llave));

		editaraccionNoInstrusivo($('btneditar_'+id+'_'+llave));
 		cancelarverNoInstrusivo($('btncancelar_'+id+'_'+llave));

 }


function forma_editar_especial(elementos,llave,id,grupo,usuarios,privilegios,evento_id){
 code="";//alert(evento_id);
 	code+='<form>';
					code+='<div><fieldset>';
						code+='<legend>'+elementos[6]+'</legend>';
						code+='<label for="inicio">Inicio</label><br />';
						code+='<input type="text" name="inicio" class="txtFecha" id="tcalendarioedit_'+id+'_'+llave+'" size="10" maxlength="10" value="'+elementos[3]+'" />';
						code+=' <a class="calendar" id="calendarioedit_'+id+'_'+llave+'"> <img src="'+path+'public/img/sp5/calendario.png" /></a>';
						code+='<br />';
						code+='<label for="fin">Fin</label><br />';
						code+='<input type="text" name="fin" class="txtFecha" id="tcalendarioedit2_'+id+'_'+llave+'" size="10" maxlength="10" value="'+elementos[4]+'"  />';
						code+=' <a class="calendar" id="calendarioedit2_'+id+'_'+llave+'"><img src="'+path+'public/img/sp5/calendario.png" /></a>';
						code+='<br />';
						code+='<label for="activo">Activo</label><br />';
						check="";
						if(elementos[5]==1)
						check=' checked="checked" ';

						code+='<input class="btnActivo" type="checkbox" '+check+' id="activoedit_'+id+'_'+llave+'" name="activoedit_'+id+'_'+llave+'" value="1" />';
						code+='<input type="hidden"  id="id_agenda_'+id+'_'+llave+'" name="id_agenda_'+id+'_'+llave+'" value="'+elementos[0]+'"/>';
						code+='<input type="hidden"  id="eventoedit'+llave+'" name="evento'+llave+'" value="'+elementos[6]+'"/>';
						code+='<input type="hidden"  id="nombreeventoedit'+llave+'" name="nombreevento'+llave+'" value="'+elementos[6]+'"/>';
					code+='</fieldset></div>';
					code+='<div><fieldset>';
							code+='<legend>Privilegios</legend>';
							code+='<label for="grupo_'+id+'_'+llave+'">Grupo</label><br/>';
							code+='<select name="grupo_'+id+'_'+llave+'" id="grupo_'+id+'_'+llave+'" >';
							code+='</select>';
							code+='<div id="div_usuarios_'+id+'_'+llave+'"></div>';
							code+='<div id="div_privilegios_'+id+'_'+llave+'"></div>';


							code+='</fieldset></div>';
					code+='<div id="botones">';
						code+='<input type="button" class="btnCacelarVer" id="btncancelar_'+id+'_'+llave+'" value="Cancelar" >';
						code+='<input type="button" class="btnEditarB" id="btneditar_'+id+'_'+llave+'" value="Editar" style="display:none;">';
					code+='</div>';
				code+='</form>';

				if($('div_'+llave+'_'+id)!=null )
				$('div_'+llave+'_'+id).innerHTML=code;
				else
				$('div_'+llave).innerHTML=code;

				obtenGrupos('div_privilegios_','grupo_',evento_id,'div_usuarios_',id,llave,grupo,usuarios,privilegios,$('btneditar_'+id+'_'+llave));
		$('calendarioedit_'+id+'_'+llave).href='javascript:;';
		Calendar.setup({
				button: 'calendarioedit_'+id+'_'+llave,
				electric : false,
				inputField : 'tcalendarioedit_'+id+'_'+llave,
				ifFormat : '%d/%m/%Y'
			});
		$('calendarioedit2_'+id+'_'+llave).href='javascript:;';
		Calendar.setup({
				button: 'calendarioedit2_'+id+'_'+llave,
				electric : false,
				inputField : 'tcalendarioedit2_'+id+'_'+llave,
				ifFormat : '%d/%m/%Y'
			});

				cambioNoIntrusivoClick($('activoedit_'+id+'_'+llave));
				cambioNoIntrusivo($('tcalendarioedit_'+id+'_'+llave));
				cambioNoIntrusivo($('tcalendarioedit2_'+id+'_'+llave));

		editaraccionNoInstrusivo($('btneditar_'+id+'_'+llave));
 		cancelarverNoInstrusivo($('btncancelar_'+id+'_'+llave));

 }


 function forma_ver(elementos,llave,id){
 code="";
 code+='<fieldset>';
code+='<legend>'+elementos[6]+'</legend>';
 code+='<div style="text-align:right;">';
 code+='<a  class="btnEditar" id="feditar_'+id+'_'+llave+'"  ><img title="Editar" alt="Editar" src="'+path+'public/img/sp5/editar.png"  /></a>';
 code+='<a class="btnCancelar" id="fcancelar_'+id+'_'+llave+'" ><img title="Cerrar" alt="Cerrar" src="'+path+'public/img/lightbox/close.gif"  /></a>';
 code+='</div>';

 code+='<table>';
 		code+='<tr>';
 			code+='<td>';
 			code+='Inicio';
 			code+='</td>';
 			code+='<td>';
 			code+=elementos[3];
 			code+='</td>';
 		code+='</tr>';
 		code+='<tr>';
 			code+='<td>';
 			code+='Fin';
 			code+='</td>';
 			code+='<td>';
 			code+=elementos[4];
 			code+='</td>';
 		code+='</tr>';
 		code+='<tr>';
 			code+='<td>';
 			code+='Activo';
 			code+='</td>';
 			code+='<td>';
 			code+=elementos[5];
 			code+='</td>';
 		code+='</tr>';
 code+='</table>';
 code+='</fieldset>';


	$('div_'+llave).innerHTML=code;

	editarNoIntrusivo($('feditar_'+id+'_'+llave));
 	cancelarverNoInstrusivo($('fcancelar_'+id+'_'+llave));
 }


var key=100;
 function forma(llave,categoria,area,id){
 	evento=$('select'+llave).options[$('select'+llave).selectedIndex];
 	eventoProp=evento.value.split('-');

 	evento_id=eventoProp[0];
 	evento_tipo=eventoProp[1];

 llave1=key+"j"+llave;
 key++;
 	code='<div style="display:none" id="spinner_'+llave1+'">';
				code+='<img id="spinner" src="'+path+'public/img/sp5/spinner.gif" />';
	code+='</div>';
 	code+='<div id="div_'+llave1+'"><form>';
					code+='<div><fieldset>';
						code+='<legend>'+evento.text+'</legend>';
						code+='<label for="inicio">Inicio</label><br />';
						code+='<input type="text" name="inicio" id="tcalendario_'+id+'_'+llave1+'" size="10" maxlength="10" />';
						code+=' <a class="calendar" id="calendario_'+id+'_'+llave1+'" ><img src="'+path+'public/img/sp5/calendario.png" /></a>';
						code+='<br />';
						code+='<label for="fin">Fin</label><br />';
						code+='<input type="text" name="fin" id="tcalendario2_'+id+'_'+llave1+'" size="10" maxlength="10" />';
						code+=' <a class="calendar" id="calendario2_'+id+'_'+llave1+'"><img src="'+path+'public/img/sp5/calendario.png" /></a>';
						code+='<br />';
						code+='<label for="activo">Activo</label><br />';
						code+='<input class="btnActivo" type="checkbox" id="activo_'+id+'_'+llave1+'" name="activo_'+id+'_'+llave1+'" value="1" />';
						code+='<input type="hidden"  id="evento_'+id+'_'+llave1+'" name="evento_'+id+'_'+llave1+'" value="'+evento_id+'"/>';
						code+='<input type="hidden"  id="nombreevento'+llave1+'" name="nombreevento'+llave1+'" value="'+evento.text+'"/>';
					code+='</fieldset></div>';
					if(evento_tipo=='E'){
							code+='<div><fieldset>';
							code+='<legend>Privilegios</legend>';
							code+='<label for="grupo_'+id+'_'+llave1+'">Grupo</label><br/>';
							code+='<select name="grupo_'+id+'_'+llave1+'" id="grupo_'+id+'_'+llave1+'" >';
							code+='</select>';
							code+='<div id="div_usuarios_'+id+'_'+llave1+'"></div>';
							code+='<div id="div_privilegios_'+id+'_'+llave1+'"></div>';


							code+='</fieldset></div>';
					}

					code+='<div id="botones">';
						code+='<input type="button" id="btncancelar_'+id+'_'+llave1+'_'+llave+'" value="Cancelar" >';
						code+='<input type="button" id="btnaceptar_'+id+'_'+llave1+'_'+area+'_'+categoria+'" value="Agregar" style="display:none" >';
					code+='</div>';
				code+='</form></div>';
 	$('areaForma'+llave).innerHTML=code;
	btn=$('btnaceptar_'+id+'_'+llave1+'_'+area+'_'+categoria);
 	obtenGrupos('div_privilegios_','grupo_',evento_id,'div_usuarios_',id,llave1,null,null,null,btn);

				$('calendario_'+id+'_'+llave1).href='javascript:;';
				Calendar.setup({
				button: 'calendario_'+id+'_'+llave1,
				electric : false,
				inputField : 'tcalendario_'+id+'_'+llave1,
				ifFormat : '%d/%m/%Y'
			});
$('calendario2_'+id+'_'+llave1).href='javascript:;';
		Calendar.setup({
				button: 'calendario2_'+id+'_'+llave1,
				electric : false,
				inputField : 'tcalendario2_'+id+'_'+llave1,
				ifFormat : '%d/%m/%Y'
			});



				//cambioNoIntrusivoClick2($('activo_'+id+'_'+llave1),area,categoria);
				//cambioNoIntrusivo($('tcalendario_'+id+'_'+llave1));
				//cambioNoIntrusivo($('tcalendario2_'+id+'_'+llave1));
				$('activo_'+id+'_'+llave1).n=btn;
				$('activo_'+id+'_'+llave1).onclick=function(){
					Effect.Appear(this.n);
				}

				$('tcalendario_'+id+'_'+llave1).n=btn;
				$('tcalendario_'+id+'_'+llave1).onchange=function(){
				Effect.Appear(this.n);
				}

				$('tcalendario2_'+id+'_'+llave1).n=btn;
				$('tcalendario2_'+id+'_'+llave1).onchange=function(){
				Effect.Appear(this.n);
				}


		aceptarJavascriptNoIntrusivo($('btnaceptar_'+id+'_'+llave1+'_'+area+'_'+categoria));
 		cancelarJavascriptNoIntrusivo($('btncancelar_'+id+'_'+llave1+'_'+llave));

 	cambia('area'+llave,'areaForma'+llave);
 }


 function colocaBoton(id,llave){
 if($('aceptar_'+id+'_'+llave)!=null)
 Effect.Appear('aceptar_'+id+'_'+llave);

 if($('editar_'+id+'_'+llave)!=null)
 Effect.Appear('btneditar_'+id+'_'+llave);


 if($('btneditar_'+id+'_'+llave)!=null)
 Effect.Appear('btneditar_'+id+'_'+llave);
 }

 function cancela_ver(llave,id){
 	if($('p_'+llave+'_'+id))
 	cambia('p_'+llave+'_'+id,'div_'+llave+'_'+id);
 	else if($('p_'+llave))
 	cambia('p_'+llave,'div_'+llave);

 }

