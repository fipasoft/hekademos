
function init(){
if($("td_status")!=null)
new Tooltip($("td_status"),{backgroundColor:"#FC9",borderColor:"#C96",textColor:"#000",textShadowColor:"#FFF"});
if($("taes")!=null)
new Tooltip($("taes"),{backgroundColor:"#FC9",borderColor:"#C96",textColor:"#000",textShadowColor:"#FFF"});
if($("btnVer")!=null)
new Tooltip($("btnVer"),{backgroundColor:"#FC9",borderColor:"#C96",textColor:"#000",textShadowColor:"#FFF"});
}

addDOMLoadEvent(init);