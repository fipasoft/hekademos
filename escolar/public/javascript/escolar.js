var host_gbl = "/hekademos/escolar/";
function firefoxXSLT(div, xmlD, xsltD) {
	try{
	var parser = new DOMParser();
	var xml = parser.parseFromString(xmlD, "text/xml");
	xml.async = false;
	var xslt = document.implementation.createDocument("", "", null);
	xslt.async = false;
	xslt.load(xsltD);
	var processor = new XSLTProcessor();
	processor.importStylesheet(xslt);
	var XmlDom = processor.transformToDocument(xml);
	var serializer = new XMLSerializer();
	var output = serializer.serializeToString(XmlDom.documentElement);
	var outputDiv = document.getElementById(div);
	outputDiv.innerHTML = output;
	} 
	catch(err)
	{
		var parser = new DOMParser();
		var xml = parser.parseFromString(xmlD, "text/xml");
		xml.async = false;
		var xmlhttp = new window.XMLHttpRequest();
		xmlhttp.open("GET",xsltD,false);
		xmlhttp.send(null);
		xslt = xmlhttp.responseXML.documentElement;
		var processor = new XSLTProcessor();
		processor.importStylesheet(xslt);
		var XmlDom = processor.transformToDocument(xml);
		var serializer = new XMLSerializer();
		var output = serializer.serializeToString(XmlDom.documentElement);
		var outputDiv = document.getElementById(div);
		outputDiv.innerHTML = output;
	}
}
function trim(cadena) {
	for (i = 0; i < cadena.length;) {
		if (cadena.charAt(i) == " ")
			cadena = cadena.substring(i + 1, cadena.length);
		else
			break
	}
	for (i = cadena.length - 1; i >= 0; i = cadena.length - 1) {
		if (cadena.charAt(i) == " ")
			cadena = cadena.substring(0, i);
		else
			break
	}
	return cadena
}
function onlytext(box) {
	var ValidChars = "0123456789abcdefghijklmn�opqrstvuwxyz ";
	var Char;
	for (i = 0; i < box.value.length; i++) {
		Char = box.value.charAt(i);
		Char = new String(Char);
		if (ValidChars.indexOf(Char.toLowerCase()) == -1) {
			box.value = box.value.replace(Char, '')
		}
	}
}
addDOMLoadEvent = (function() {
	var e = [], t, s, n, i, o, d = document, w = window, r = 'readyState', c = 'onreadystatechange', x = function() {
		n = 1;
		clearInterval(t);
		while (i = e.shift())
			i();
		if (s)
			s[c] = ''
	};
	return function(f) {
		if (n)
			return f();
		if (!e[0]) {
			d.addEventListener
					&& d.addEventListener("DOMContentLoaded", x, false);
			if (/WebKit/i.test(navigator.userAgent))
				t = setInterval(function() {
					/loaded|complete/.test(d[r]) && x()
				}, 10);
			o = w.onload;
			w.onload = function() {
				x();
				o && o()
			}
		}
		e.push(f)
	}
})();
addDOMLoadEvent = (function() {
	var e = [], t, s, n, i, o, d = document, w = window, r = 'readyState', c = 'onreadystatechange', x = function() {
		n = 1;
		clearInterval(t);
		while (i = e.shift())
			i();
		if (s)
			s[c] = ''
	};
	return function(f) {
		if (n)
			return f();
		if (!e[0]) {
			d.addEventListener
					&& d.addEventListener("DOMContentLoaded", x, false);
			if (/WebKit/i.test(navigator.userAgent))
				t = setInterval(function() {
					/loaded|complete/.test(d[r]) && x()
				}, 10);
			o = w.onload;
			w.onload = function() {
				x();
				o && o()
			}
		}
		e.push(f)
	}
})();
function transforma(div, xml, file) {
	var browserName = navigator.appName;
	xslt = host_gbl + 'public/xsl/' + file;
	if (browserName == "Microsoft Internet Explorer") {
		explorerXSLT(div, xml, xslt);
	} else {
		firefoxXSLT(div, xml, xslt);
	}
}
function colocaCapaLoading(div) {
	$(div).innerHTML = '<p style="margin: 0 auto;text-align:center;width:100%;"><img src="'
			+ host_gbl + 'public/img/sp5/spin.gif" alt="Cargando..." /></p>'
}
function escolar_init() {
	if ($("closeSesion") != null)
		new Tooltip($("closeSesion"), {
			backgroundColor : "#FC9",
			borderColor : "#C96",
			textColor : "#000",
			textShadowColor : "#FFF"
		});
	if ($("ayuda") != null)
		new Tooltip($("ayuda"), {
			backgroundColor : "#FC9",
			borderColor : "#C96",
			textColor : "#000",
			textShadowColor : "#FFF"
		});
	if ($("codigo") != null)
		new Tooltip($("codigo"), {
			backgroundColor : "#FC9",
			borderColor : "#C96",
			textColor : "#000",
			textShadowColor : "#FFF"
		});
	if ($("cambia_password") != null)
		new Tooltip($("cambia_password"), {
			backgroundColor : "#FC9",
			borderColor : "#C96",
			textColor : "#000",
			textShadowColor : "#FFF"
		});
	if ($("menu_inicio") != null)
		new Tooltip($("menu_inicio"), {
			backgroundColor : "#FC9",
			borderColor : "#C96",
			textColor : "#000",
			textShadowColor : "#FFF"
		});
	if ($("menu_ficha") != null)
		new Tooltip($("menu_ficha"), {
			backgroundColor : "#FC9",
			borderColor : "#C96",
			textColor : "#000",
			textShadowColor : "#FFF"
		});
	if ($("menu_asistencias") != null)
		new Tooltip($("menu_asistencias"), {
			backgroundColor : "#FC9",
			borderColor : "#C96",
			textColor : "#000",
			textShadowColor : "#FFF"
		});
	if ($("menu_calificaciones") != null)
		new Tooltip($("menu_calificaciones"), {
			backgroundColor : "#FC9",
			borderColor : "#C96",
			textColor : "#000",
			textShadowColor : "#FFF"
		});
	if ($("menu_kardex") != null)
		new Tooltip($("menu_kardex"), {
			backgroundColor : "#FC9",
			borderColor : "#C96",
			textColor : "#000",
			textShadowColor : "#FFF"
		});
	if ($("menu_horario") != null)
		new Tooltip($("menu_horario"), {
			backgroundColor : "#FC9",
			borderColor : "#C96",
			textColor : "#000",
			textShadowColor : "#FFF"
		});
	if ($("menu_accesos") != null)
		new Tooltip($("menu_accesos"), {
			backgroundColor : "#FC9",
			borderColor : "#C96",
			textColor : "#000",
			textShadowColor : "#FFF"
		});
	if ($("menu_normatividad") != null)
		new Tooltip($("menu_normatividad"), {
			backgroundColor : "#FC9",
			borderColor : "#C96",
			textColor : "#000",
			textShadowColor : "#FFF"
		});
	if ($("menu_agenda") != null)
		new Tooltip($("menu_agenda"), {
			backgroundColor : "#FC9",
			borderColor : "#C96",
			textColor : "#000",
			textShadowColor : "#FFF"
		});
	$$(".submenu").each(function(input) {
		new Tooltip(input, {
			backgroundColor : "#FC9",
			borderColor : "#C96",
			textColor : "#000",
			textShadowColor : "#FFF"
		})
	})
}
addDOMLoadEvent(escolar_init);
function explorerXSLT(div, xmlD, xsltD) {
	var xml = new ActiveXObject("Microsoft.XMLDOM");
	var xslt = new ActiveXObject("Microsoft.XMLDOM");
	xml.async = false;
	xslt.async = false;
	xml.loadXML(xmlD);
	xslt.load(xsltD);
	var output = xml.transformNode(xslt);
	var outputDiv = document.getElementById(div);
	outputDiv.innerHTML = output;
}