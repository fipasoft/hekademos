function firefoxXSLT(div,xmlD,xsltD){

var parser=new DOMParser();
var xml = parser.parseFromString(xmlD,"text/xml");
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
