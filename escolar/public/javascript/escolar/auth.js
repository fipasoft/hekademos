function init() {
    path = $('path').value;
    btn = $("btn");
    if (btn != null) {
        btn.onclick = function () {
            if (revisa()) accion();
            else alert("Todos los campos son obligatorios.");
        };
        $$("p.formulario input").each(function (input) {
            new Tooltip(input, {
                backgroundColor: "#FC9",
                borderColor: "#C96",
                textColor: "#000",
                textShadowColor: "#FFF"
            });
            if (document.layers) document.captureEvents(Event.KEYPRESS);
            input.onkeypress = checkEnter;
        });
    }
}
function checkEnter(e) {
    if (e && e.which) {
        e = e;
        characterCode = e.which;
    } else {
        if (!e) {
            e = event;
            characterCode = e.keyCode;
        } else characterCode = -1;
    }
    if (characterCode == 13) {
        if (revisa()) accion();
        else alert("Todos los campos son obligatorios.");
    }
}
function accion() {
    colocaCapaLoading("info");
    new Ajax.Request(path + 'escolar/abrir', {
        method: 'post',
        parameters: $('frm_login').serialize(true),
        onSuccess: function (xml) {
            if (xml.responseText == "1") {
                window.location = path + 'escolar/inicio/'
            } else if (xml.responseText == "0") {
                $("info").innerHTML = '<br/><p class="errorBox"><br/>El codigo y/o el password no son correctos.</p>';
            } else {
                //$("info").innerHTML = '<br/><p class="errorBox">Ha ocurrido un error, intentelo de nuevo.' + xml.responseText + '</p>';
            }
        }
    });
}
function revisa() {
    codigo = $("codigo").value;
    pass = $("pass").value;
    if (trim(codigo).length > 0 && trim(pass).length > 0) return 1;
    else return 0;
}
addDOMLoadEvent(init);