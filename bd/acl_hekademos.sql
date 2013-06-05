-- MySQL dump 10.13  Distrib 5.1.33, for pc-linux-gnu (i686)
--
-- Host: localhost    Database: acl_hekademos
-- ------------------------------------------------------
-- Server version	5.1.33

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `acl`
--

DROP TABLE IF EXISTS `acl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acl` (
  `id` int(11) NOT NULL DEFAULT '0',
  `section_value` varchar(230) NOT NULL DEFAULT 'system',
  `allow` int(11) NOT NULL DEFAULT '0',
  `enabled` int(11) NOT NULL DEFAULT '0',
  `return_value` text,
  `note` text,
  `updated_date` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `enabled_acl` (`enabled`),
  KEY `section_value_acl` (`section_value`),
  KEY `updated_date_acl` (`updated_date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acl`
--

LOCK TABLES `acl` WRITE;
/*!40000 ALTER TABLE `acl` DISABLE KEYS */;
INSERT INTO `acl` VALUES (10627,'system',1,1,'','',1308168105),(10628,'system',1,1,'','',1308168105),(10629,'system',1,1,'','',1308168105),(10630,'system',1,1,'','',1308168105),(10631,'system',1,1,'','',1308168105),(10632,'system',1,1,'','',1308168105),(10633,'system',1,1,'','',1308168105),(10634,'system',1,1,'','',1308168105),(10635,'system',1,1,'','',1308168105),(10636,'system',1,1,'','',1308168105),(10637,'system',1,1,'','',1308168105),(10638,'system',1,1,'','',1308168105),(10639,'system',1,1,'','',1308168105),(10640,'system',1,1,'','',1308168105),(10641,'system',1,1,'','',1308168105),(10642,'system',1,1,'','',1308168105),(10643,'system',1,1,'','',1308168105),(10644,'system',1,1,'','',1308168105),(10645,'system',1,1,'','',1308168105),(10646,'system',1,1,'','',1308168105),(10647,'system',1,1,'','',1308168105),(10648,'system',1,1,'','',1308168105),(10649,'system',1,1,'','',1308168105),(10650,'system',1,1,'','',1308168105),(10651,'system',1,1,'','',1308168105),(10652,'system',1,1,'','',1308168105),(10653,'system',1,1,'','',1308168105),(10654,'system',1,1,'','',1308168105),(10655,'system',1,1,'','',1308168105),(10656,'system',1,1,'','',1308168105),(10657,'system',1,1,'','',1308168105),(10658,'system',1,1,'','',1308168105),(10659,'system',1,1,'','',1308168105),(10660,'system',1,1,'','',1308168105),(10661,'system',1,1,'','',1308168105),(10662,'system',1,1,'','',1308168105),(10663,'system',1,1,'','',1308168105),(10664,'system',1,1,'','',1308168105),(10665,'system',1,1,'','',1308168105),(10666,'system',1,1,'','',1308168105),(10667,'system',1,1,'','',1308168105),(10668,'system',1,1,'','',1308168105),(10669,'system',1,1,'','',1308168105),(10670,'system',1,1,'','',1308168105),(10671,'system',1,1,'','',1308168105),(10672,'system',1,1,'','',1308168105),(10673,'system',1,1,'','',1308168105),(10674,'system',1,1,'','',1308168105),(10675,'system',1,1,'','',1308168105),(10676,'system',1,1,'','',1308168105),(10677,'system',1,1,'','',1308168105),(10678,'system',1,1,'','',1308168105),(10679,'system',1,1,'','',1308168105),(10680,'system',1,1,'','',1308168105),(10681,'system',1,1,'','',1308168105),(10682,'system',1,1,'','',1308168105),(10683,'system',1,1,'','',1308168105),(10684,'system',1,1,'','',1308168105),(10685,'system',1,1,'','',1308168105),(10686,'system',1,1,'','',1308168105),(10687,'system',1,1,'','',1308168105),(10688,'system',1,1,'','',1308168105),(10689,'system',1,1,'','',1308168105),(10690,'system',1,1,'','',1308168105),(10691,'system',1,1,'','',1308168105),(10692,'system',1,1,'','',1308168105),(10693,'system',1,1,'','',1308168105),(10694,'system',1,1,'','',1308168105),(10695,'system',1,1,'','',1308168105),(10696,'system',1,1,'','',1308168105),(10697,'system',1,1,'','',1308168105),(10698,'system',1,1,'','',1308168105),(10699,'system',1,1,'','',1308168105),(10700,'system',1,1,'','',1308168105),(10701,'system',1,1,'','',1308168105),(10702,'system',1,1,'','',1308168105),(10703,'system',1,1,'','',1308168105),(10704,'system',1,1,'','',1308168105),(10705,'system',1,1,'','',1308168105),(10706,'system',1,1,'','',1308168105),(10707,'system',1,1,'','',1308168105),(10708,'system',1,1,'','',1308168105),(10709,'system',1,1,'','',1308168105),(10710,'system',1,1,'','',1308168105),(10711,'system',1,1,'','',1308168105),(10712,'system',1,1,'','',1308168105),(10713,'system',1,1,'','',1308168105),(10714,'system',1,1,'','',1308168105),(10715,'system',1,1,'','',1308168105),(10716,'system',1,1,'','',1308168105),(10717,'system',1,1,'','',1308168105),(10718,'system',1,1,'','',1308168105),(10719,'system',1,1,'','',1308168105),(10720,'system',1,1,'','',1308168105),(10721,'system',1,1,'','',1308168105),(10722,'system',1,1,'','',1308168105),(10723,'system',1,1,'','',1308168105),(10724,'system',1,1,'','',1308168105),(10725,'system',1,1,'','',1308168105),(10726,'system',1,1,'','',1308168105),(10727,'system',1,1,'','',1308168105),(10728,'system',1,1,'','',1308168105),(10729,'system',1,1,'','',1308168105),(10730,'system',1,1,'','',1308168105),(10731,'system',1,1,'','',1308168105),(10732,'system',1,1,'','',1308168105),(10733,'system',1,1,'','',1308168105),(10734,'system',1,1,'','',1308168105),(10735,'system',1,1,'','',1308168105),(10736,'system',1,1,'','',1308168105),(10737,'system',1,1,'','',1308168105),(10738,'system',1,1,'','',1308168105),(10739,'system',1,1,'','',1308168105),(10740,'system',1,1,'','',1308168105),(10741,'system',1,1,'','',1308168105),(10742,'system',1,1,'','',1308168105),(10743,'system',1,1,'','',1308168105),(10744,'system',1,1,'','',1308168105),(10745,'system',1,1,'','',1308168105),(10746,'system',1,1,'','',1308168105),(10747,'system',1,1,'','',1308168105),(10748,'system',1,1,'','',1308168105),(10749,'system',1,1,'','',1308168106),(10750,'system',1,1,'','',1308168106),(10751,'system',1,1,'','',1308168106),(10752,'system',1,1,'','',1308168106),(10753,'system',1,1,'','',1308168106),(10754,'system',1,1,'','',1308168106),(10755,'system',1,1,'','',1308168106),(10756,'system',1,1,'','',1308168106),(10757,'system',1,1,'','',1308168106),(10758,'system',1,1,'','',1308168106),(10759,'system',1,1,'','',1308168106),(10760,'system',1,1,'','',1308168106),(10761,'system',1,1,'','',1308168106),(10762,'system',1,1,'','',1308168106);
/*!40000 ALTER TABLE `acl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acl_sections`
--

DROP TABLE IF EXISTS `acl_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acl_sections` (
  `id` int(11) NOT NULL DEFAULT '0',
  `value` varchar(230) NOT NULL,
  `order_value` int(11) NOT NULL DEFAULT '0',
  `name` varchar(230) NOT NULL,
  `hidden` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `value_acl_sections` (`value`),
  KEY `hidden_acl_sections` (`hidden`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acl_sections`
--

LOCK TABLES `acl_sections` WRITE;
/*!40000 ALTER TABLE `acl_sections` DISABLE KEYS */;
INSERT INTO `acl_sections` VALUES (1,'system',1,'System',0),(2,'user',2,'User',0),(10,'sistema',0,'sistema',0);
/*!40000 ALTER TABLE `acl_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acl_sections_seq`
--

DROP TABLE IF EXISTS `acl_sections_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acl_sections_seq` (
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acl_sections_seq`
--

LOCK TABLES `acl_sections_seq` WRITE;
/*!40000 ALTER TABLE `acl_sections_seq` DISABLE KEYS */;
INSERT INTO `acl_sections_seq` VALUES (110);
/*!40000 ALTER TABLE `acl_sections_seq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acl_seq`
--

DROP TABLE IF EXISTS `acl_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acl_seq` (
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acl_seq`
--

LOCK TABLES `acl_seq` WRITE;
/*!40000 ALTER TABLE `acl_seq` DISABLE KEYS */;
INSERT INTO `acl_seq` VALUES (10762);
/*!40000 ALTER TABLE `acl_seq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `aco`
--

DROP TABLE IF EXISTS `aco`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aco` (
  `id` int(11) NOT NULL DEFAULT '0',
  `section_value` varchar(240) NOT NULL DEFAULT '0',
  `value` varchar(240) NOT NULL,
  `order_value` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `hidden` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `section_value_value_aco` (`section_value`,`value`),
  KEY `hidden_aco` (`hidden`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aco`
--

LOCK TABLES `aco` WRITE;
/*!40000 ALTER TABLE `aco` DISABLE KEYS */;
INSERT INTO `aco` VALUES (33686,'inicio','index',0,'index',0),(33687,'inicio','administrador',0,'administrador',0),(33688,'inicio','director',0,'director',0),(33689,'inicio','oficial',0,'oficial',0),(33690,'inicio','plantilla',0,'plantilla',0),(33691,'inicio','profesor',0,'profesor',0),(33692,'inicio','secretaria',0,'secretaria',0),(33693,'inicio','secretario',0,'secretario',0),(33694,'catalogos','index',0,'index',0),(33695,'escolar','restringir',0,'restringir',0),(33696,'escolar','accesos',0,'accesos',0),(33697,'escolar','auth',0,'auth',0),(33698,'escolar','abrir',0,'abrir',0),(33699,'escolar','cerrar',0,'cerrar',0),(33700,'escolar','index',0,'index',0),(33701,'escolar','ficha',0,'ficha',0),(33702,'escolar','kardex',0,'kardex',0),(33703,'escolar','asistencias',0,'asistencias',0),(33704,'escolar','calificaciones',0,'calificaciones',0),(33705,'escolar','inicio',0,'inicio',0),(33706,'escolar','obtenAsistencias',0,'obtenAsistencias',0),(33707,'escolar','obtenCalificaciones',0,'obtenCalificaciones',0),(33708,'escolar','password',0,'password',0),(33709,'escolar','pdf',0,'pdf',0),(33710,'escolar','horario',0,'horario',0),(33711,'escolar','agenda',0,'agenda',0),(33712,'escolar','optativas',0,'optativas',0),(33713,'escolar','ver_registro',0,'ver_registro',0),(33714,'escolar','taes',0,'taes',0),(33715,'escolar','amonestaciones',0,'amonestaciones',0),(33716,'disciplina','index',0,'index',0),(33717,'informacion','index',0,'index',0),(33718,'sistema','ayuda',0,'ayuda',0),(33719,'sistema','configuracion',0,'configuracion',0),(33720,'sistema','password',0,'password',0),(33721,'sistema','seleccionar',0,'seleccionar',0),(33722,'sistema','index',0,'index',0),(33723,'ALL','ALL',0,'ALL',0),(33724,'sesion','abrir',0,'abrir',0),(33725,'sesion','autenticar',0,'autenticar',0),(33726,'sesion','cerrar',0,'cerrar',0),(33727,'sesion','index',0,'index',0),(33728,'sesion','restringir',0,'restringir',0),(33729,'aulas','agregar',0,'agregar',0),(33730,'aulas','editar',0,'editar',0),(33731,'aulas','eliminar',0,'eliminar',0),(33732,'aulas','index',0,'index',0),(33733,'aulas','buscar',0,'buscar',0),(33734,'aulas','exportar',0,'exportar',0),(33735,'aulas','disponible',0,'disponible',0),(33736,'alumnos','agregar',0,'agregar',0),(33737,'alumnos','avisos',0,'avisos',0),(33738,'alumnos','comentarios',0,'comentarios',0),(33739,'alumnos','asignar',0,'asignar',0),(33740,'alumnos','buscar',0,'buscar',0),(33741,'alumnos','disponible',0,'disponible',0),(33742,'alumnos','editar',0,'editar',0),(33743,'alumnos','eliminar',0,'eliminar',0),(33744,'alumnos','exportar',0,'exportar',0),(33745,'alumnos','ubicar',0,'ubicar',0),(33746,'alumnos','imprimir',0,'imprimir',0),(33747,'alumnos','index',0,'index',0),(33748,'alumnos','info',0,'info',0),(33749,'alumnos','password',0,'password',0),(33750,'alumnos','ver',0,'ver',0),(33751,'alumnos','kardex',0,'kardex',0),(33752,'alumnos','cursos',0,'cursos',0),(33753,'alumnos','escolar',0,'escolar',0),(33754,'alumnos','importar',0,'importar',0),(33755,'alumnos','trayectoria',0,'trayectoria',0),(33756,'alumnos','amonestaciones',0,'amonestaciones',0),(33757,'alumnos','exportar_amonestaciones',0,'exportar_amonestaciones',0),(33758,'amonestaciones','agregar',0,'agregar',0),(33759,'amonestaciones','aprobar',0,'aprobar',0),(33760,'amonestaciones','cancelar',0,'cancelar',0),(33761,'amonestaciones','editar',0,'editar',0),(33762,'amonestaciones','eliminar',0,'eliminar',0),(33763,'amonestaciones','exportar',0,'exportar',0),(33764,'amonestaciones','index',0,'index',0),(33765,'amonestaciones','ficha',0,'ficha',0),(33766,'amonestaciones','ver',0,'ver',0),(33767,'amonestaciones','obtiene_articulos',0,'obtiene_articulos',0),(33768,'amonestaciones','obtiene_alumnos',0,'obtiene_alumnos',0),(33769,'amonestaciones','cuenta_alumnos',0,'cuenta_alumnos',0),(33770,'accesos','generar',0,'generar',0),(33771,'accesos','exportar',0,'exportar',0),(33772,'accesos','index',0,'index',0),(33773,'importar','index',0,'index',0),(33774,'importar','fotos',0,'fotos',0),(33775,'asistencias','agregar',0,'agregar',0),(33776,'asistencias','editar',0,'editar',0),(33777,'asistencias','eliminar',0,'eliminar',0),(33778,'asistencias','index',0,'index',0),(33779,'asistencias','imprimir',0,'imprimir',0),(33780,'asistencias','justificar',0,'justificar',0),(33781,'asistencias','selector',0,'selector',0),(33782,'asistencias','faltas',0,'faltas',0),(33783,'asistencias','ver',0,'ver',0),(33784,'calificaciones','agregar',0,'agregar',0),(33785,'calificaciones','editar',0,'editar',0),(33786,'calificaciones','eliminar',0,'eliminar',0),(33787,'calificaciones','index',0,'index',0),(33788,'calificaciones','imprimir',0,'imprimir',0),(33789,'calificaciones','selector',0,'selector',0),(33790,'calificaciones','ver',0,'ver',0),(33791,'parciales','agregar',0,'agregar',0),(33792,'parciales','editar',0,'editar',0),(33793,'parciales','eliminar',0,'eliminar',0),(33794,'parciales','index',0,'index',0),(33795,'parciales','imprimir',0,'imprimir',0),(33796,'parciales','ver',0,'ver',0),(33797,'tutores','agregar',0,'agregar',0),(33798,'tutores','editar',0,'editar',0),(33799,'tutores','eliminar',0,'eliminar',0),(33800,'tutores','imprimir',0,'imprimir',0),(33801,'tutores','index',0,'index',0),(33802,'tutores','password',0,'password',0),(33803,'tutores','ver',0,'ver',0),(33804,'tutores','exportar',0,'exportar',0),(33805,'tutores','buscar',0,'buscar',0),(33806,'grupos','asignar',0,'asignar',0),(33807,'grupos','agregar',0,'agregar',0),(33808,'grupos','curso',0,'curso',0),(33809,'grupos','editar',0,'editar',0),(33810,'grupos','eliminar',0,'eliminar',0),(33811,'grupos','disponible',0,'disponible',0),(33812,'grupos','horario',0,'horario',0),(33813,'grupos','imprimir',0,'imprimir',0),(33814,'grupos','index',0,'index',0),(33815,'grupos','ver',0,'ver',0),(33816,'grupos','generar',0,'generar',0),(33817,'grupos','exportaramonestaciones',0,'exportaramonestaciones',0),(33818,'grupos','amonestaciones',0,'amonestaciones',0),(33819,'materias','agregar',0,'agregar',0),(33820,'materias','disponible',0,'disponible',0),(33821,'materias','editar',0,'editar',0),(33822,'materias','eliminar',0,'eliminar',0),(33823,'materias','enlazar',0,'enlazar',0),(33824,'materias','exportar',0,'exportar',0),(33825,'materias','index',0,'index',0),(33826,'materias','series',0,'series',0),(33827,'materias','ver',0,'ver',0),(33828,'materias','academias',0,'academias',0),(33829,'competencias','obtenertipos',0,'obtenertipos',0),(33830,'profesores','agregar',0,'agregar',0),(33831,'profesores','disponible',0,'disponible',0),(33832,'profesores','editar',0,'editar',0),(33833,'profesores','eliminar',0,'eliminar',0),(33834,'profesores','index',0,'index',0),(33835,'profesores','ver',0,'ver',0),(33836,'profesores','exportar',0,'exportar',0),(33837,'profesores','password',0,'password',0),(33838,'profesores','buscar',0,'buscar',0),(33839,'profesores','horario',0,'horario',0),(33840,'profesores','horarioexcel',0,'horarioexcel',0),(33841,'profesores','laboral',0,'laboral',0),(33842,'personal','agregar',0,'agregar',0),(33843,'personal','disponible',0,'disponible',0),(33844,'personal','editar',0,'editar',0),(33845,'personal','eliminar',0,'eliminar',0),(33846,'personal','index',0,'index',0),(33847,'personal','ver',0,'ver',0),(33848,'personal','exportar',0,'exportar',0),(33849,'personal','buscar',0,'buscar',0),(33850,'tipopersonal','agregar',0,'agregar',0),(33851,'tipopersonal','editar',0,'editar',0),(33852,'tipopersonal','eliminar',0,'eliminar',0),(33853,'tipopersonal','index',0,'index',0),(33854,'tipopersonal','buscar',0,'buscar',0),(33855,'cursos','agregar',0,'agregar',0),(33856,'cursos','editar',0,'editar',0),(33857,'cursos','exportar',0,'exportar',0),(33858,'cursos','eliminar',0,'eliminar',0),(33859,'cursos','imprimir',0,'imprimir',0),(33860,'cursos','index',0,'index',0),(33861,'cursos','status',0,'status',0),(33862,'cursos','ver',0,'ver',0),(33863,'cursos','buscar',0,'buscar',0),(33864,'cursos','grupo',0,'grupo',0),(33865,'cursos','fecha',0,'fecha',0),(33866,'cursos','grupoexportar',0,'grupoexportar',0),(33867,'cursos','copiar',0,'copiar',0),(33868,'horarios','agregar',0,'agregar',0),(33869,'horarios','editar',0,'editar',0),(33870,'horarios','eliminar',0,'eliminar',0),(33871,'horarios','index',0,'index',0),(33872,'horarios','ver',0,'ver',0),(33873,'horarios','validar',0,'validar',0),(33874,'agenda','agregar',0,'agregar',0),(33875,'agenda','index',0,'index',0),(33876,'agenda','ver',0,'ver',0),(33877,'agenda','obtengrupos',0,'obtengrupos',0),(33878,'agenda','obtenusuarios',0,'obtenusuarios',0),(33879,'agenda','obtenacos',0,'obtenacos',0),(33880,'agenda','guarda',0,'guarda',0),(33881,'agenda','editar',0,'editar',0),(33882,'ciclos','agregar',0,'agregar',0),(33883,'ciclos','abrir',0,'abrir',0),(33884,'ciclos','avance',0,'avance',0),(33885,'ciclos','editar',0,'editar',0),(33886,'ciclos','eliminar',0,'eliminar',0),(33887,'ciclos','index',0,'index',0),(33888,'ciclos','status',0,'status',0),(33889,'ciclos','buscar',0,'buscar',0),(33890,'ciclos','simulaavance',0,'simulaavance',0),(33891,'ciclos','faltantes',0,'faltantes',0),(33892,'usuarios','agregar',0,'agregar',0),(33893,'usuarios','editar',0,'editar',0),(33894,'usuarios','eliminar',0,'eliminar',0),(33895,'usuarios','index',0,'index',0),(33896,'usuarios','password',0,'password',0),(33897,'usuarios','validarLogin',0,'validarLogin',0),(33898,'usuarios','verAcceso',0,'verAcceso',0),(33899,'usuarios','ver',0,'ver',0),(33900,'inscripcion','agregar',0,'agregar',0),(33901,'inscripcion','confirmar',0,'confirmar',0),(33902,'inscripcion','eliminar',0,'eliminar',0),(33903,'inscripcion','articulo',0,'articulo',0),(33904,'historial','exportar',0,'exportar',0),(33905,'historial','index',0,'index',0),(33906,'historial','buscar',0,'buscar',0),(33907,'historial','ver',0,'ver',0),(33908,'estadisticas','index',0,'index',0),(33909,'estadisticas','asistencias',0,'asistencias',0),(33910,'estadisticas','calificaciones',0,'calificaciones',0),(33911,'estadisticas','promedios',0,'promedios',0),(33912,'estadisticas','aprobadas',0,'aprobadas',0),(33913,'estadisticas','aprobacion',0,'aprobacion',0),(33914,'es','index',0,'index',0),(33915,'es','exportar',0,'exportar',0),(33916,'es','dia',0,'dia',0),(33917,'es','inconsistencias',0,'inconsistencias',0),(33918,'periodos','agregar',0,'agregar',0),(33919,'periodos','editar',0,'editar',0),(33920,'periodos','eliminar',0,'eliminar',0),(33921,'periodos','estadistica',0,'estadistica',0),(33922,'periodos','index',0,'index',0),(33923,'optativas','alumnos',0,'alumnos',0),(33924,'optativas','avanzadas',0,'avanzadas',0),(33925,'optativas','bloques',0,'bloques',0),(33926,'optativas','cupos',0,'cupos',0),(33927,'optativas','eliminar',0,'eliminar',0),(33928,'optativas','configuracion',0,'configuracion',0),(33929,'optativas','cursos',0,'cursos',0),(33930,'optativas','index',0,'index',0),(33931,'optativas','inscritos',0,'inscritos',0),(33932,'optativas','inscritosexportar',0,'inscritosexportar',0),(33933,'optativas','inscribir',0,'inscribir',0),(33934,'optativas','taes',0,'taes',0),(33935,'optativas','taesinfo',0,'taesinfo',0),(33936,'optativas','trayectoria',0,'trayectoria',0),(33937,'optativas','trayectoriasexportar',0,'trayectoriasexportar',0),(33938,'bloques','agregar',0,'agregar',0),(33939,'bloques','editar',0,'editar',0),(33940,'bloques','eliminar',0,'eliminar',0),(33941,'bloques','eliminartodos',0,'eliminartodos',0),(33942,'bloques','index',0,'index',0),(33943,'bloquesalumnos','agregar',0,'agregar',0),(33944,'bloquesalumnos','cambio',0,'cambio',0),(33945,'bloquesalumnos','eliminar',0,'eliminar',0),(33946,'bloquesalumnos','index',0,'index',0),(33947,'reportes','index',0,'index',0),(33948,'reportes','resumen',0,'resumen',0),(33949,'reportes','derechos',0,'derechos',0),(33950,'reportes','plantilla',0,'plantilla',0),(33951,'reportes','basica',0,'basica',0),(33952,'importador','index',0,'index',0),(33953,'importador','taes',0,'taes',0),(33954,'importador','curso',0,'curso',0),(33955,'importador','grupocursos',0,'grupocursos',0),(33956,'plantilla','materias',0,'materias',0),(33957,'plantilla','index',0,'index',0),(33958,'plantilla','prerregistro',0,'prerregistro',0),(33959,'plantilla','profesores',0,'profesores',0),(33960,'plantilla','exportar',0,'exportar',0),(33961,'plantilla','profesoreshoras',0,'profesoreshoras',0),(33962,'visitas','exportar',0,'exportar',0),(33963,'visitas','index',0,'index',0),(33964,'visitas','informacion',0,'informacion',0),(33965,'admin','index',0,'index',0),(33966,'archivo','actualizar',0,'actualizar',0),(33967,'archivo','cambiastatus',0,'cambiastatus',0),(33968,'archivo','descargar',0,'descargar',0),(33969,'archivo','editar',0,'editar',0),(33970,'archivo','eliminar',0,'eliminar',0),(33971,'archivo','guardar',0,'guardar',0),(33972,'archivo','index',0,'index',0),(33973,'archivo','nuevo',0,'nuevo',0),(33974,'blog','index',0,'index',0),(33975,'blog','gestor',0,'gestor',0),(33976,'categoriasdescargas','eliminar',0,'eliminar',0),(33977,'categoriasdescargas','actualizar',0,'actualizar',0),(33978,'categoriasdescargas','editar',0,'editar',0),(33979,'categoriasdescargas','gestor',0,'gestor',0),(33980,'categoriasdescargas','guardar',0,'guardar',0),(33981,'categoriasdescargas','index',0,'index',0),(33982,'categoriasdescargas','nuevo',0,'nuevo',0),(33983,'categoriasmultimedia','actualizar',0,'actualizar',0),(33984,'categoriasmultimedia','editar',0,'editar',0),(33985,'categoriasmultimedia','eliminar',0,'eliminar',0),(33986,'categoriasmultimedia','gestor',0,'gestor',0),(33987,'categoriasmultimedia','guardar',0,'guardar',0),(33988,'categoriasmultimedia','index',0,'index',0),(33989,'categoriasmultimedia','nuevo',0,'nuevo',0),(33990,'contacto','guardar',0,'guardar',0),(33991,'contacto','sugerencias',0,'sugerencias',0),(33992,'contacto','index',0,'index',0),(33993,'contacto','ubicacion',0,'ubicacion',0),(33994,'contenido','gestor',0,'gestor',0),(33995,'contenido','editar',0,'editar',0),(33996,'contenido','guardar',0,'guardar',0),(33997,'contenido','index',0,'index',0),(33998,'contenido','cambiaStatus',0,'cambiaStatus',0),(33999,'director','index',0,'index',0),(34000,'director','informes',0,'informes',0),(34001,'director','trayectoria',0,'trayectoria',0),(34002,'director','video',0,'video',0),(34003,'descargas','gestor',0,'gestor',0),(34004,'descargas','index',0,'index',0),(34005,'modulo','cambiastatus',0,'cambiastatus',0),(34006,'modulo','editar',0,'editar',0),(34007,'modulo','gestor',0,'gestor',0),(34008,'modulo','guardar',0,'guardar',0),(34009,'modulo','index',0,'index',0),(34010,'mmf','actualizar',0,'actualizar',0),(34011,'mmf','cambiastatus',0,'cambiastatus',0),(34012,'mmf','editar',0,'editar',0),(34013,'mmf','eliminar',0,'eliminar',0),(34014,'mmf','guardar',0,'guardar',0),(34015,'mmf','index',0,'index',0),(34016,'mmf','nuevo',0,'nuevo',0),(34017,'mmf','vista_previa',0,'vista_previa',0),(34018,'multimedia','gestor',0,'gestor',0),(34019,'multimedia','index',0,'index',0),(34020,'nuestraprepa','acerca',0,'acerca',0),(34021,'nuestraprepa','agregar_comentarios',0,'agregar_comentarios',0),(34022,'nuestraprepa','blog',0,'blog',0),(34023,'nuestraprepa','blog_comentarios',0,'blog_comentarios',0),(34024,'nuestraprepa','consejo',0,'consejo',0),(34025,'nuestraprepa','directorio',0,'directorio',0),(34026,'nuestraprepa','index',0,'index',0),(34027,'nuestraprepa','inicio',0,'inicio',0),(34028,'nuestraprepa','mision',0,'mision',0),(34029,'nuestraprepa','normatividad',0,'normatividad',0),(34030,'nuestraprepa','organigrama',0,'organigrama',0),(34031,'nuestraprepa','transparencia',0,'transparencia',0),(34032,'nuestraprepa','vision',0,'vision',0),(34033,'nuestraprepa','iso',0,'iso',0),(34034,'post','actualizar',0,'actualizar',0),(34035,'post','cambiaStatus',0,'cambiaStatus',0),(34036,'post','cambiaStatusPost',0,'cambiaStatusPost',0),(34037,'post','editar',0,'editar',0),(34038,'post','eliminar',0,'eliminar',0),(34039,'post','eliminarPost',0,'eliminarPost',0),(34040,'post','guardar',0,'guardar',0),(34041,'post','index',0,'index',0),(34042,'post','nuevo',0,'nuevo',0),(34043,'post','vista_previa',0,'vista_previa',0),(34044,'servicios','agenda',0,'agenda',0),(34045,'servicios','documentos',0,'documentos',0),(34046,'servicios','descargas',0,'descargas',0),(34047,'servicios','radio',0,'radio',0),(34048,'servicios','formatos',0,'formatos',0),(34049,'servicios','index',0,'index',0),(34050,'sugerencias','eliminar',0,'eliminar',0),(34051,'sugerencias','historial',0,'historial',0),(34052,'sugerencias','index',0,'index',0),(34053,'sugerencias','responder',0,'responder',0),(34054,'sugerencias','sugerencia',0,'sugerencia',0),(34055,'texto','editar',0,'editar',0),(34056,'texto','guardar',0,'guardar',0),(34057,'texto','index',0,'index',0),(34058,'controlescolar','index',0,'index',0),(34059,'controlescolar','enviados',0,'enviados',0),(34060,'controlescolar','borradores',0,'borradores',0),(34061,'controlescolar','reenviar',0,'reenviar',0),(34062,'controlescolar','notificacion',0,'notificacion',0),(34063,'controlescolar','eliminar_notificacion',0,'eliminar_notificacion',0),(34064,'controlescolar','gestor',0,'gestor',0),(34065,'controlescolar','guardar',0,'guardar',0),(34066,'controlescolar','actualizar',0,'actualizar',0),(34067,'controlescolar','combo',0,'combo',0),(34068,'reglamentos','index',0,'index',0),(34069,'reglamentos','agregar',0,'agregar',0),(34070,'reglamentos','buscar',0,'buscar',0),(34071,'reglamentos','editar',0,'editar',0),(34072,'reglamentos','eliminar',0,'eliminar',0),(34073,'reglamentos','ver',0,'ver',0),(34074,'reglamentos','revisa_reglamento',0,'revisa_reglamento',0),(34075,'articulos','agregar',0,'agregar',0),(34076,'articulos','editar',0,'editar',0),(34077,'articulos','eliminar',0,'eliminar',0),(34078,'articulos','revisar_numero',0,'revisar_numero',0),(34079,'articulos','buscar',0,'buscar',0),(34080,'articulos','importar',0,'importar',0),(34081,'articulos','ver',0,'ver',0);
/*!40000 ALTER TABLE `aco` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `aco_map`
--

DROP TABLE IF EXISTS `aco_map`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aco_map` (
  `acl_id` int(11) NOT NULL DEFAULT '0',
  `section_value` varchar(230) NOT NULL DEFAULT '0',
  `value` varchar(230) NOT NULL,
  PRIMARY KEY (`acl_id`,`section_value`,`value`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aco_map`
--

LOCK TABLES `aco_map` WRITE;
/*!40000 ALTER TABLE `aco_map` DISABLE KEYS */;
INSERT INTO `aco_map` VALUES (10627,'sesion','abrir'),(10627,'sesion','autenticar'),(10627,'sesion','cerrar'),(10627,'sesion','index'),(10627,'sesion','restringir'),(10628,'ALL','ALL'),(10629,'escolar','abrir'),(10629,'escolar','accesos'),(10629,'escolar','agenda'),(10629,'escolar','amonestaciones'),(10629,'escolar','asistencias'),(10629,'escolar','auth'),(10629,'escolar','calificaciones'),(10629,'escolar','cerrar'),(10629,'escolar','ficha'),(10629,'escolar','horario'),(10629,'escolar','index'),(10629,'escolar','inicio'),(10629,'escolar','kardex'),(10629,'escolar','obtenAsistencias'),(10629,'escolar','obtenCalificaciones'),(10629,'escolar','optativas'),(10629,'escolar','password'),(10629,'escolar','pdf'),(10629,'escolar','restringir'),(10629,'escolar','taes'),(10629,'escolar','ver_registro'),(10630,'informacion','index'),(10631,'catalogos','index'),(10632,'agenda','agregar'),(10632,'agenda','editar'),(10632,'agenda','guarda'),(10632,'agenda','index'),(10632,'agenda','obtenacos'),(10632,'agenda','obtengrupos'),(10632,'agenda','obtenusuarios'),(10632,'agenda','ver'),(10633,'accesos','exportar'),(10633,'accesos','generar'),(10633,'accesos','index'),(10634,'importar','fotos'),(10634,'importar','index'),(10635,'visitas','exportar'),(10635,'visitas','index'),(10635,'visitas','informacion'),(10636,'historial','buscar'),(10636,'historial','exportar'),(10636,'historial','index'),(10636,'historial','ver'),(10637,'aulas','agregar'),(10637,'aulas','buscar'),(10637,'aulas','disponible'),(10637,'aulas','editar'),(10637,'aulas','eliminar'),(10637,'aulas','exportar'),(10637,'aulas','index'),(10638,'alumnos','buscar'),(10638,'alumnos','importar'),(10638,'alumnos','index'),(10638,'alumnos','password'),(10638,'alumnos','ver'),(10639,'ciclos','abrir'),(10639,'ciclos','agregar'),(10639,'ciclos','avance'),(10639,'ciclos','buscar'),(10639,'ciclos','editar'),(10639,'ciclos','eliminar'),(10639,'ciclos','faltantes'),(10639,'ciclos','index'),(10639,'ciclos','simulaavance'),(10639,'ciclos','status'),(10640,'inicio','administrador'),(10640,'inicio','index'),(10641,'profesores','buscar'),(10641,'profesores','index'),(10641,'profesores','password'),(10641,'profesores','ver'),(10642,'personal','agregar'),(10642,'personal','buscar'),(10642,'personal','disponible'),(10642,'personal','editar'),(10642,'personal','eliminar'),(10642,'personal','exportar'),(10642,'personal','index'),(10642,'personal','ver'),(10643,'tipopersonal','agregar'),(10643,'tipopersonal','buscar'),(10643,'tipopersonal','editar'),(10643,'tipopersonal','eliminar'),(10643,'tipopersonal','index'),(10644,'tutores','buscar'),(10644,'tutores','index'),(10644,'tutores','password'),(10644,'tutores','ver'),(10645,'usuarios','agregar'),(10645,'usuarios','editar'),(10645,'usuarios','eliminar'),(10645,'usuarios','index'),(10645,'usuarios','password'),(10645,'usuarios','validarLogin'),(10645,'usuarios','ver'),(10645,'usuarios','verAcceso'),(10646,'sistema','ayuda'),(10646,'sistema','configuracion'),(10646,'sistema','index'),(10646,'sistema','password'),(10646,'sistema','seleccionar'),(10647,'estadisticas','aprobacion'),(10647,'estadisticas','aprobadas'),(10647,'estadisticas','asistencias'),(10647,'estadisticas','calificaciones'),(10647,'estadisticas','index'),(10647,'estadisticas','promedios'),(10648,'es','dia'),(10648,'es','exportar'),(10648,'es','inconsistencias'),(10648,'es','index'),(10649,'periodos','agregar'),(10649,'periodos','editar'),(10649,'periodos','eliminar'),(10649,'periodos','estadistica'),(10649,'periodos','index'),(10650,'optativas','alumnos'),(10650,'optativas','avanzadas'),(10650,'optativas','bloques'),(10650,'optativas','configuracion'),(10650,'optativas','cupos'),(10650,'optativas','cursos'),(10650,'optativas','eliminar'),(10650,'optativas','index'),(10650,'optativas','inscribir'),(10650,'optativas','inscritos'),(10650,'optativas','inscritosexportar'),(10650,'optativas','taes'),(10650,'optativas','taesinfo'),(10650,'optativas','trayectoria'),(10650,'optativas','trayectoriasexportar'),(10651,'bloques','agregar'),(10651,'bloques','editar'),(10651,'bloques','eliminar'),(10651,'bloques','eliminartodos'),(10651,'bloques','index'),(10652,'bloquesalumnos','agregar'),(10652,'bloquesalumnos','cambio'),(10652,'bloquesalumnos','eliminar'),(10652,'bloquesalumnos','index'),(10653,'importador','curso'),(10653,'importador','grupocursos'),(10653,'importador','index'),(10653,'importador','taes'),(10654,'escolar','abrir'),(10654,'escolar','accesos'),(10654,'escolar','agenda'),(10654,'escolar','amonestaciones'),(10654,'escolar','asistencias'),(10654,'escolar','auth'),(10654,'escolar','calificaciones'),(10654,'escolar','cerrar'),(10654,'escolar','ficha'),(10654,'escolar','horario'),(10654,'escolar','index'),(10654,'escolar','inicio'),(10654,'escolar','kardex'),(10654,'escolar','obtenAsistencias'),(10654,'escolar','obtenCalificaciones'),(10654,'escolar','optativas'),(10654,'escolar','password'),(10654,'escolar','pdf'),(10654,'escolar','restringir'),(10654,'escolar','taes'),(10654,'escolar','ver_registro'),(10655,'disciplina','index'),(10656,'catalogos','index'),(10657,'amonestaciones','agregar'),(10657,'amonestaciones','aprobar'),(10657,'amonestaciones','cancelar'),(10657,'amonestaciones','cuenta_alumnos'),(10657,'amonestaciones','editar'),(10657,'amonestaciones','eliminar'),(10657,'amonestaciones','exportar'),(10657,'amonestaciones','ficha'),(10657,'amonestaciones','index'),(10657,'amonestaciones','obtiene_alumnos'),(10657,'amonestaciones','obtiene_articulos'),(10657,'amonestaciones','ver'),(10658,'reglamentos','agregar'),(10658,'reglamentos','buscar'),(10658,'reglamentos','editar'),(10658,'reglamentos','eliminar'),(10658,'reglamentos','index'),(10658,'reglamentos','revisa_reglamento'),(10658,'reglamentos','ver'),(10659,'articulos','agregar'),(10659,'articulos','buscar'),(10659,'articulos','editar'),(10659,'articulos','eliminar'),(10659,'articulos','importar'),(10659,'articulos','revisar_numero'),(10659,'articulos','ver'),(10660,'alumnos','amonestaciones'),(10660,'alumnos','buscar'),(10660,'alumnos','exportar_amonestaciones'),(10660,'alumnos','index'),(10661,'grupos','amonestaciones'),(10661,'grupos','exportaramonestaciones'),(10661,'grupos','index'),(10662,'sistema','ayuda'),(10662,'sistema','configuracion'),(10662,'sistema','index'),(10662,'sistema','password'),(10662,'sistema','seleccionar'),(10663,'historial','buscar'),(10663,'historial','exportar'),(10663,'historial','index'),(10663,'historial','ver'),(10664,'inicio','index'),(10664,'inicio','plantilla'),(10665,'escolar','abrir'),(10665,'escolar','accesos'),(10665,'escolar','agenda'),(10665,'escolar','amonestaciones'),(10665,'escolar','asistencias'),(10665,'escolar','auth'),(10665,'escolar','calificaciones'),(10665,'escolar','cerrar'),(10665,'escolar','ficha'),(10665,'escolar','horario'),(10665,'escolar','index'),(10665,'escolar','inicio'),(10665,'escolar','kardex'),(10665,'escolar','obtenAsistencias'),(10665,'escolar','obtenCalificaciones'),(10665,'escolar','optativas'),(10665,'escolar','password'),(10665,'escolar','pdf'),(10665,'escolar','restringir'),(10665,'escolar','taes'),(10665,'escolar','ver_registro'),(10666,'informacion','index'),(10667,'catalogos','index'),(10668,'visitas','exportar'),(10668,'visitas','index'),(10668,'visitas','informacion'),(10669,'asistencias','editar'),(10669,'asistencias','eliminar'),(10669,'asistencias','selector'),(10669,'asistencias','ver'),(10670,'calificaciones','editar'),(10670,'calificaciones','eliminar'),(10670,'calificaciones','selector'),(10670,'calificaciones','ver'),(10671,'alumnos','buscar'),(10671,'alumnos','cursos'),(10671,'alumnos','eliminar'),(10671,'alumnos','exportar'),(10671,'alumnos','index'),(10671,'alumnos','info'),(10671,'alumnos','kardex'),(10671,'alumnos','ubicar'),(10671,'alumnos','ver'),(10672,'cursos','buscar'),(10672,'cursos','editar'),(10672,'cursos','eliminar'),(10672,'cursos','exportar'),(10672,'cursos','fecha'),(10672,'cursos','grupo'),(10672,'cursos','grupoexportar'),(10672,'cursos','index'),(10672,'cursos','status'),(10672,'cursos','ver'),(10673,'grupos','curso'),(10673,'grupos','horario'),(10673,'grupos','index'),(10673,'grupos','ver'),(10674,'tutores','buscar'),(10674,'tutores','exportar'),(10674,'tutores','index'),(10674,'tutores','ver'),(10675,'sistema','ayuda'),(10675,'sistema','configuracion'),(10675,'sistema','index'),(10675,'sistema','password'),(10675,'sistema','seleccionar'),(10676,'inscripcion','agregar'),(10676,'inscripcion','articulo'),(10676,'inscripcion','confirmar'),(10676,'inscripcion','eliminar'),(10677,'horarios','agregar'),(10677,'horarios','editar'),(10677,'horarios','eliminar'),(10677,'horarios','index'),(10677,'horarios','validar'),(10677,'horarios','ver'),(10678,'estadisticas','aprobacion'),(10678,'estadisticas','aprobadas'),(10678,'estadisticas','asistencias'),(10678,'estadisticas','calificaciones'),(10678,'estadisticas','index'),(10678,'estadisticas','promedios'),(10679,'profesores','buscar'),(10679,'profesores','exportar'),(10679,'profesores','horario'),(10679,'profesores','horarioexcel'),(10679,'profesores','index'),(10679,'profesores','ver'),(10680,'reportes','basica'),(10680,'reportes','derechos'),(10680,'reportes','index'),(10680,'reportes','plantilla'),(10680,'reportes','resumen'),(10681,'inicio','director'),(10681,'inicio','index'),(10682,'historial','buscar'),(10682,'historial','exportar'),(10682,'historial','index'),(10682,'historial','ver'),(10683,'inicio','index'),(10683,'inicio','secretario'),(10684,'historial','buscar'),(10684,'historial','exportar'),(10684,'historial','index'),(10684,'historial','ver'),(10685,'escolar','abrir'),(10685,'escolar','accesos'),(10685,'escolar','agenda'),(10685,'escolar','amonestaciones'),(10685,'escolar','asistencias'),(10685,'escolar','auth'),(10685,'escolar','calificaciones'),(10685,'escolar','cerrar'),(10685,'escolar','ficha'),(10685,'escolar','horario'),(10685,'escolar','index'),(10685,'escolar','inicio'),(10685,'escolar','kardex'),(10685,'escolar','obtenAsistencias'),(10685,'escolar','obtenCalificaciones'),(10685,'escolar','optativas'),(10685,'escolar','password'),(10685,'escolar','pdf'),(10685,'escolar','restringir'),(10685,'escolar','taes'),(10685,'escolar','ver_registro'),(10686,'informacion','index'),(10687,'catalogos','index'),(10688,'alumnos','exportar'),(10688,'alumnos','index'),(10688,'alumnos','ver'),(10689,'asistencias','selector'),(10690,'calificaciones','selector'),(10691,'grupos','curso'),(10691,'grupos','horario'),(10691,'grupos','imprimir'),(10691,'grupos','index'),(10691,'grupos','ver'),(10692,'tutores','index'),(10692,'tutores','ver'),(10693,'alumnos','agregar'),(10693,'alumnos','asignar'),(10693,'alumnos','buscar'),(10693,'alumnos','cursos'),(10693,'alumnos','disponible'),(10693,'alumnos','editar'),(10693,'alumnos','eliminar'),(10693,'alumnos','index'),(10693,'alumnos','info'),(10693,'alumnos','kardex'),(10693,'alumnos','trayectoria'),(10694,'cursos','buscar'),(10694,'cursos','exportar'),(10694,'cursos','index'),(10694,'cursos','ver'),(10695,'grupos','asignar'),(10695,'grupos','index'),(10696,'horarios','index'),(10696,'horarios','ver'),(10697,'inicio','index'),(10697,'inicio','oficial'),(10698,'tutores','agregar'),(10698,'tutores','buscar'),(10698,'tutores','editar'),(10698,'tutores','eliminar'),(10698,'tutores','exportar'),(10699,'sistema','ayuda'),(10699,'sistema','configuracion'),(10699,'sistema','index'),(10699,'sistema','password'),(10699,'sistema','seleccionar'),(10700,'historial','buscar'),(10700,'historial','exportar'),(10700,'historial','index'),(10700,'historial','ver'),(10701,'estadisticas','aprobacion'),(10701,'estadisticas','aprobadas'),(10701,'estadisticas','asistencias'),(10701,'estadisticas','calificaciones'),(10701,'estadisticas','index'),(10701,'estadisticas','promedios'),(10702,'reportes','basica'),(10702,'reportes','derechos'),(10702,'reportes','index'),(10702,'reportes','plantilla'),(10702,'reportes','resumen'),(10703,'materias','enlazar'),(10703,'materias','exportar'),(10703,'materias','index'),(10703,'materias','series'),(10703,'materias','ver'),(10704,'asistencias','agregar'),(10704,'asistencias','editar'),(10704,'asistencias','eliminar'),(10704,'asistencias','faltas'),(10704,'asistencias','imprimir'),(10704,'asistencias','index'),(10704,'asistencias','justificar'),(10704,'asistencias','selector'),(10704,'asistencias','ver'),(10705,'calificaciones','agregar'),(10705,'calificaciones','editar'),(10705,'calificaciones','eliminar'),(10705,'calificaciones','imprimir'),(10705,'calificaciones','index'),(10705,'calificaciones','selector'),(10705,'calificaciones','ver'),(10706,'periodos','estadistica'),(10706,'periodos','index'),(10707,'optativas','index'),(10707,'optativas','inscritos'),(10707,'optativas','taesinfo'),(10707,'optativas','trayectoria'),(10707,'optativas','trayectoriasexportar'),(10708,'inscripcion','agregar'),(10708,'inscripcion','eliminar'),(10709,'alumnos','agregar'),(10709,'alumnos','buscar'),(10709,'alumnos','cursos'),(10709,'alumnos','disponible'),(10709,'alumnos','editar'),(10709,'alumnos','eliminar'),(10709,'alumnos','index'),(10709,'alumnos','info'),(10709,'alumnos','kardex'),(10709,'alumnos','trayectoria'),(10709,'alumnos','ubicar'),(10710,'historial','buscar'),(10710,'historial','exportar'),(10710,'historial','index'),(10710,'historial','ver'),(10711,'asistencias','agregar'),(10711,'asistencias','editar'),(10711,'asistencias','eliminar'),(10711,'asistencias','faltas'),(10711,'asistencias','imprimir'),(10711,'asistencias','index'),(10711,'asistencias','justificar'),(10711,'asistencias','selector'),(10711,'asistencias','ver'),(10712,'calificaciones','agregar'),(10712,'calificaciones','editar'),(10712,'calificaciones','eliminar'),(10712,'calificaciones','imprimir'),(10712,'calificaciones','index'),(10712,'calificaciones','selector'),(10712,'calificaciones','ver'),(10713,'inicio','index'),(10713,'inicio','secretaria'),(10714,'tutores','agregar'),(10714,'tutores','buscar'),(10714,'tutores','editar'),(10714,'tutores','eliminar'),(10714,'tutores','exportar'),(10714,'tutores','index'),(10715,'sistema','ayuda'),(10715,'sistema','configuracion'),(10715,'sistema','index'),(10715,'sistema','password'),(10715,'sistema','seleccionar'),(10716,'reportes','basica'),(10716,'reportes','derechos'),(10716,'reportes','index'),(10716,'reportes','plantilla'),(10716,'reportes','resumen'),(10717,'materias','enlazar'),(10717,'materias','exportar'),(10717,'materias','index'),(10717,'materias','series'),(10717,'materias','ver'),(10718,'cursos','buscar'),(10718,'cursos','index'),(10718,'cursos','ver'),(10719,'inscripcion','agregar'),(10719,'inscripcion','articulo'),(10719,'inscripcion','confirmar'),(10719,'inscripcion','eliminar'),(10720,'asistencias','agregar'),(10720,'asistencias','editar'),(10720,'asistencias','eliminar'),(10720,'asistencias','index'),(10720,'asistencias','selector'),(10720,'asistencias','ver'),(10721,'calificaciones','agregar'),(10721,'calificaciones','editar'),(10721,'calificaciones','eliminar'),(10721,'calificaciones','index'),(10721,'calificaciones','selector'),(10721,'calificaciones','ver'),(10722,'grupos','curso'),(10722,'grupos','horario'),(10722,'grupos','index'),(10722,'grupos','ver'),(10723,'inicio','index'),(10723,'inicio','profesor'),(10724,'escolar','abrir'),(10724,'escolar','accesos'),(10724,'escolar','agenda'),(10724,'escolar','amonestaciones'),(10724,'escolar','asistencias'),(10724,'escolar','auth'),(10724,'escolar','calificaciones'),(10724,'escolar','cerrar'),(10724,'escolar','ficha'),(10724,'escolar','horario'),(10724,'escolar','index'),(10724,'escolar','inicio'),(10724,'escolar','kardex'),(10724,'escolar','obtenAsistencias'),(10724,'escolar','obtenCalificaciones'),(10724,'escolar','optativas'),(10724,'escolar','password'),(10724,'escolar','pdf'),(10724,'escolar','restringir'),(10724,'escolar','taes'),(10724,'escolar','ver_registro'),(10725,'informacion','index'),(10726,'catalogos','index'),(10727,'sistema','ayuda'),(10727,'sistema','configuracion'),(10727,'sistema','index'),(10727,'sistema','password'),(10727,'sistema','seleccionar'),(10728,'plantilla','exportar'),(10728,'plantilla','index'),(10728,'plantilla','materias'),(10728,'plantilla','prerregistro'),(10728,'plantilla','profesores'),(10728,'plantilla','profesoreshoras'),(10729,'historial','buscar'),(10729,'historial','exportar'),(10729,'historial','index'),(10729,'historial','ver'),(10730,'aulas','agregar'),(10730,'aulas','buscar'),(10730,'aulas','disponible'),(10730,'aulas','editar'),(10730,'aulas','eliminar'),(10730,'aulas','exportar'),(10730,'aulas','index'),(10731,'cursos','agregar'),(10731,'cursos','buscar'),(10731,'cursos','copiar'),(10731,'cursos','editar'),(10731,'cursos','eliminar'),(10731,'cursos','exportar'),(10731,'cursos','fecha'),(10731,'cursos','grupo'),(10731,'cursos','grupoexportar'),(10731,'cursos','index'),(10731,'cursos','status'),(10732,'grupos','agregar'),(10732,'grupos','disponible'),(10732,'grupos','editar'),(10732,'grupos','eliminar'),(10732,'grupos','generar'),(10732,'grupos','index'),(10733,'horarios','validar'),(10734,'inicio','index'),(10734,'inicio','plantilla'),(10735,'materias','academias'),(10735,'materias','agregar'),(10735,'materias','disponible'),(10735,'materias','editar'),(10735,'materias','eliminar'),(10735,'materias','enlazar'),(10735,'materias','exportar'),(10735,'materias','index'),(10735,'materias','series'),(10735,'materias','ver'),(10736,'competencias','obtenertipos'),(10737,'profesores','agregar'),(10737,'profesores','buscar'),(10737,'profesores','disponible'),(10737,'profesores','editar'),(10737,'profesores','eliminar'),(10737,'profesores','exportar'),(10737,'profesores','horario'),(10737,'profesores','horarioexcel'),(10737,'profesores','index'),(10737,'profesores','laboral'),(10737,'profesores','ver'),(10738,'periodos','index'),(10739,'reportes','index'),(10739,'reportes','plantilla'),(10740,'optativas','cupos'),(10740,'optativas','cursos'),(10740,'optativas','eliminar'),(10740,'optativas','index'),(10740,'optativas','taes'),(10741,'admin','index'),(10742,'alumnos','avisos'),(10742,'alumnos','comentarios'),(10742,'alumnos','escolar'),(10742,'alumnos','index'),(10743,'archivo','descargar'),(10744,'contacto','guardar'),(10744,'contacto','index'),(10744,'contacto','sugerencias'),(10744,'contacto','ubicacion'),(10745,'director','index'),(10745,'director','informes'),(10745,'director','trayectoria'),(10745,'director','video'),(10746,'escolar','abrir'),(10746,'escolar','auth'),(10746,'escolar','cerrar'),(10746,'escolar','restringir'),(10747,'nuestraprepa','acerca'),(10747,'nuestraprepa','agregar_comentarios'),(10747,'nuestraprepa','blog'),(10747,'nuestraprepa','blog_comentarios'),(10747,'nuestraprepa','consejo'),(10747,'nuestraprepa','directorio'),(10747,'nuestraprepa','index'),(10747,'nuestraprepa','inicio'),(10747,'nuestraprepa','iso'),(10747,'nuestraprepa','mision'),(10747,'nuestraprepa','normatividad'),(10747,'nuestraprepa','organigrama'),(10747,'nuestraprepa','transparencia'),(10747,'nuestraprepa','vision'),(10748,'servicios','agenda'),(10748,'servicios','descargas'),(10748,'servicios','documentos'),(10748,'servicios','formatos'),(10748,'servicios','index'),(10748,'servicios','radio'),(10749,'sugerencias','eliminar'),(10749,'sugerencias','historial'),(10749,'sugerencias','index'),(10749,'sugerencias','responder'),(10749,'sugerencias','sugerencia'),(10750,'archivo','actualizar'),(10750,'archivo','cambiastatus'),(10750,'archivo','descargar'),(10750,'archivo','editar'),(10750,'archivo','eliminar'),(10750,'archivo','guardar'),(10750,'archivo','index'),(10750,'archivo','nuevo'),(10751,'blog','gestor'),(10751,'blog','index'),(10752,'categoriasdescargas','actualizar'),(10752,'categoriasdescargas','editar'),(10752,'categoriasdescargas','eliminar'),(10752,'categoriasdescargas','gestor'),(10752,'categoriasdescargas','guardar'),(10752,'categoriasdescargas','index'),(10752,'categoriasdescargas','nuevo'),(10753,'categoriasmultimedia','actualizar'),(10753,'categoriasmultimedia','editar'),(10753,'categoriasmultimedia','eliminar'),(10753,'categoriasmultimedia','gestor'),(10753,'categoriasmultimedia','guardar'),(10753,'categoriasmultimedia','index'),(10753,'categoriasmultimedia','nuevo'),(10754,'contenido','cambiaStatus'),(10754,'contenido','editar'),(10754,'contenido','gestor'),(10754,'contenido','guardar'),(10754,'contenido','index'),(10755,'controlescolar','actualizar'),(10755,'controlescolar','borradores'),(10755,'controlescolar','combo'),(10755,'controlescolar','eliminar_notificacion'),(10755,'controlescolar','enviados'),(10755,'controlescolar','gestor'),(10755,'controlescolar','guardar'),(10755,'controlescolar','index'),(10755,'controlescolar','notificacion'),(10755,'controlescolar','reenviar'),(10756,'descargas','gestor'),(10756,'descargas','index'),(10757,'mmf','actualizar'),(10757,'mmf','cambiastatus'),(10757,'mmf','editar'),(10757,'mmf','eliminar'),(10757,'mmf','guardar'),(10757,'mmf','index'),(10757,'mmf','nuevo'),(10757,'mmf','vista_previa'),(10758,'modulo','cambiastatus'),(10758,'modulo','editar'),(10758,'modulo','gestor'),(10758,'modulo','guardar'),(10758,'modulo','index'),(10759,'multimedia','gestor'),(10759,'multimedia','index'),(10760,'post','actualizar'),(10760,'post','cambiaStatus'),(10760,'post','cambiaStatusPost'),(10760,'post','editar'),(10760,'post','eliminar'),(10760,'post','eliminarPost'),(10760,'post','guardar'),(10760,'post','index'),(10760,'post','nuevo'),(10760,'post','vista_previa'),(10761,'texto','editar'),(10761,'texto','guardar'),(10761,'texto','index'),(10762,'escolar','accesos'),(10762,'escolar','agenda'),(10762,'escolar','amonestaciones'),(10762,'escolar','asistencias'),(10762,'escolar','calificaciones'),(10762,'escolar','ficha'),(10762,'escolar','horario'),(10762,'escolar','index'),(10762,'escolar','inicio'),(10762,'escolar','kardex'),(10762,'escolar','obtenAsistencias'),(10762,'escolar','obtenCalificaciones'),(10762,'escolar','optativas'),(10762,'escolar','password'),(10762,'escolar','pdf'),(10762,'escolar','taes'),(10762,'escolar','ver_registro');
/*!40000 ALTER TABLE `aco_map` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `aco_sections`
--

DROP TABLE IF EXISTS `aco_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aco_sections` (
  `id` int(11) NOT NULL DEFAULT '0',
  `value` varchar(230) NOT NULL,
  `order_value` int(11) NOT NULL DEFAULT '0',
  `name` varchar(230) NOT NULL,
  `hidden` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `value_aco_sections` (`value`),
  KEY `hidden_aco_sections` (`hidden`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aco_sections`
--

LOCK TABLES `aco_sections` WRITE;
/*!40000 ALTER TABLE `aco_sections` DISABLE KEYS */;
INSERT INTO `aco_sections` VALUES (5156,'inicio',0,'inicio',0),(5157,'catalogos',1,'catalogos',0),(5158,'escolar',2,'escolar',0),(5159,'disciplina',3,'disciplina',0),(5160,'informacion',4,'informacion',0),(5161,'sistema',5,'sistema',0),(5162,'ALL',6,'ALL',0),(5163,'sesion',7,'sesion',0),(5164,'aulas',8,'aulas',0),(5165,'alumnos',9,'alumnos',0),(5166,'amonestaciones',10,'amonestaciones',0),(5167,'accesos',11,'accesos',0),(5168,'importar',12,'importar',0),(5169,'asistencias',13,'asistencias',0),(5170,'calificaciones',14,'calificaciones',0),(5171,'parciales',15,'parciales',0),(5172,'tutores',16,'tutores',0),(5173,'grupos',17,'grupos',0),(5174,'materias',18,'materias',0),(5175,'competencias',19,'competencias',0),(5176,'profesores',20,'profesores',0),(5177,'personal',21,'personal',0),(5178,'tipopersonal',22,'tipopersonal',0),(5179,'cursos',23,'cursos',0),(5180,'horarios',24,'horarios',0),(5181,'agenda',25,'agenda',0),(5182,'ciclos',26,'ciclos',0),(5183,'usuarios',27,'usuarios',0),(5184,'inscripcion',28,'inscripcion',0),(5185,'historial',29,'historial',0),(5186,'estadisticas',30,'estadisticas',0),(5187,'es',31,'es',0),(5188,'periodos',32,'periodos',0),(5189,'optativas',33,'optativas',0),(5190,'bloques',34,'bloques',0),(5191,'bloquesalumnos',35,'bloquesalumnos',0),(5192,'reportes',36,'reportes',0),(5193,'importador',37,'importador',0),(5194,'plantilla',38,'plantilla',0),(5195,'visitas',39,'visitas',0),(5196,'admin',40,'admin',0),(5197,'archivo',41,'archivo',0),(5198,'blog',42,'blog',0),(5199,'categoriasdescargas',43,'categoriasdescargas',0),(5200,'categoriasmultimedia',44,'categoriasmultimedia',0),(5201,'contacto',45,'contacto',0),(5202,'contenido',46,'contenido',0),(5203,'director',47,'director',0),(5204,'descargas',48,'descargas',0),(5205,'modulo',49,'modulo',0),(5206,'mmf',50,'mmf',0),(5207,'multimedia',51,'multimedia',0),(5208,'nuestraprepa',52,'nuestraprepa',0),(5209,'post',53,'post',0),(5210,'servicios',54,'servicios',0),(5211,'sugerencias',55,'sugerencias',0),(5212,'texto',56,'texto',0),(5213,'controlescolar',57,'controlescolar',0),(5214,'reglamentos',58,'reglamentos',0),(5215,'articulos',59,'articulos',0);
/*!40000 ALTER TABLE `aco_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `aco_sections_seq`
--

DROP TABLE IF EXISTS `aco_sections_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aco_sections_seq` (
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aco_sections_seq`
--

LOCK TABLES `aco_sections_seq` WRITE;
/*!40000 ALTER TABLE `aco_sections_seq` DISABLE KEYS */;
INSERT INTO `aco_sections_seq` VALUES (5215);
/*!40000 ALTER TABLE `aco_sections_seq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `aco_seq`
--

DROP TABLE IF EXISTS `aco_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aco_seq` (
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aco_seq`
--

LOCK TABLES `aco_seq` WRITE;
/*!40000 ALTER TABLE `aco_seq` DISABLE KEYS */;
INSERT INTO `aco_seq` VALUES (34081);
/*!40000 ALTER TABLE `aco_seq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `aro`
--

DROP TABLE IF EXISTS `aro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aro` (
  `id` int(11) NOT NULL DEFAULT '0',
  `section_value` varchar(240) NOT NULL DEFAULT '0',
  `value` varchar(240) NOT NULL,
  `order_value` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `hidden` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `section_value_value_aro` (`section_value`,`value`),
  KEY `hidden_aro` (`hidden`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aro`
--

LOCK TABLES `aro` WRITE;
/*!40000 ALTER TABLE `aro` DISABLE KEYS */;
INSERT INTO `aro` VALUES (2582,'usuarios','anonimo',0,'anonimo',0),(2583,'usuarios','root',0,'root',0),(2584,'usuarios','_',0,'_',0),(2585,'usuarios','visitante',0,'visitante',0),(2586,'usuarios','alumno',0,'alumno',0),(2587,'usuarios','tutor',0,'tutor',0),(2588,'usuarios','director',0,'director',0),(2589,'usuarios','oficial',0,'oficial',0),(2590,'usuarios','administrador',0,'administrador',0),(2591,'usuarios','disciplina',0,'disciplina',0),(2592,'usuarios','secretaria',0,'secretaria',0),(2593,'usuarios','plantilla',0,'plantilla',0),(2594,'usuarios','secretario',0,'secretario',0);
/*!40000 ALTER TABLE `aro` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `aro_groups`
--

DROP TABLE IF EXISTS `aro_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aro_groups` (
  `id` int(11) NOT NULL DEFAULT '0',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `lft` int(11) NOT NULL DEFAULT '0',
  `rgt` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`,`value`),
  UNIQUE KEY `value_aro_groups` (`value`),
  KEY `parent_id_aro_groups` (`parent_id`),
  KEY `lft_rgt_aro_groups` (`lft`,`rgt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aro_groups`
--

LOCK TABLES `aro_groups` WRITE;
/*!40000 ALTER TABLE `aro_groups` DISABLE KEYS */;
INSERT INTO `aro_groups` VALUES (1751,0,1,36,'usuarios','usuarios'),(1752,1751,2,3,'root','root'),(1753,1751,4,5,'administradores','administradores'),(1754,1751,6,11,'direccion','direccion'),(1755,1754,7,8,'director','director'),(1756,1754,9,10,'secretario','secretario'),(1757,1751,12,17,'escolar','escolar'),(1758,1757,13,14,'oficial','oficial'),(1759,1757,15,16,'secretarias','secretarias'),(1760,1751,18,19,'profesores','profesores'),(1761,1751,20,21,'plantilla','plantilla'),(1762,1751,22,23,'disciplina','disciplina'),(1763,1751,24,35,'web','web'),(1764,1763,25,28,'webmaster','webmaster'),(1765,1764,26,27,'editores','editores'),(1766,1763,29,34,'wescolar','wescolar'),(1767,1766,30,31,'alumnos','alumnos'),(1768,1766,32,33,'tutores','tutores');
/*!40000 ALTER TABLE `aro_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `aro_groups_id_seq`
--

DROP TABLE IF EXISTS `aro_groups_id_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aro_groups_id_seq` (
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aro_groups_id_seq`
--

LOCK TABLES `aro_groups_id_seq` WRITE;
/*!40000 ALTER TABLE `aro_groups_id_seq` DISABLE KEYS */;
INSERT INTO `aro_groups_id_seq` VALUES (1768);
/*!40000 ALTER TABLE `aro_groups_id_seq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `aro_groups_map`
--

DROP TABLE IF EXISTS `aro_groups_map`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aro_groups_map` (
  `acl_id` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`acl_id`,`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aro_groups_map`
--

LOCK TABLES `aro_groups_map` WRITE;
/*!40000 ALTER TABLE `aro_groups_map` DISABLE KEYS */;
INSERT INTO `aro_groups_map` VALUES (10627,1751),(10628,1752),(10629,1753),(10630,1753),(10631,1753),(10632,1753),(10633,1753),(10634,1753),(10635,1753),(10636,1753),(10637,1753),(10638,1753),(10639,1753),(10640,1753),(10641,1753),(10642,1753),(10643,1753),(10644,1753),(10645,1753),(10646,1753),(10647,1753),(10648,1753),(10649,1753),(10650,1753),(10651,1753),(10652,1753),(10653,1753),(10654,1762),(10655,1762),(10656,1762),(10657,1762),(10658,1762),(10659,1762),(10660,1762),(10661,1762),(10662,1762),(10663,1762),(10664,1762),(10665,1754),(10666,1754),(10667,1754),(10668,1754),(10669,1754),(10670,1754),(10671,1754),(10672,1754),(10673,1754),(10674,1754),(10675,1754),(10676,1754),(10677,1754),(10678,1754),(10679,1754),(10680,1754),(10681,1755),(10682,1755),(10683,1756),(10684,1756),(10685,1757),(10686,1757),(10687,1757),(10688,1757),(10689,1757),(10690,1757),(10691,1757),(10692,1757),(10693,1758),(10694,1758),(10695,1758),(10696,1758),(10697,1758),(10698,1758),(10699,1758),(10700,1758),(10701,1758),(10702,1758),(10703,1758),(10704,1758),(10705,1758),(10706,1758),(10707,1758),(10708,1758),(10709,1759),(10710,1759),(10711,1759),(10712,1759),(10713,1759),(10714,1759),(10715,1759),(10716,1759),(10717,1759),(10718,1759),(10719,1759),(10720,1760),(10721,1760),(10722,1760),(10723,1760),(10724,1761),(10725,1761),(10726,1761),(10727,1761),(10728,1761),(10729,1761),(10730,1761),(10731,1761),(10732,1761),(10733,1761),(10734,1761),(10735,1761),(10736,1761),(10737,1761),(10738,1761),(10739,1761),(10740,1761),(10741,1763),(10742,1763),(10743,1763),(10744,1763),(10745,1763),(10746,1763),(10747,1763),(10748,1763),(10749,1764),(10750,1764),(10751,1764),(10752,1764),(10753,1764),(10754,1764),(10755,1764),(10756,1764),(10757,1764),(10758,1764),(10759,1764),(10760,1764),(10761,1764),(10762,1766);
/*!40000 ALTER TABLE `aro_groups_map` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `aro_map`
--

DROP TABLE IF EXISTS `aro_map`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aro_map` (
  `acl_id` int(11) NOT NULL DEFAULT '0',
  `section_value` varchar(230) NOT NULL DEFAULT '0',
  `value` varchar(230) NOT NULL,
  PRIMARY KEY (`acl_id`,`section_value`,`value`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aro_map`
--

LOCK TABLES `aro_map` WRITE;
/*!40000 ALTER TABLE `aro_map` DISABLE KEYS */;
/*!40000 ALTER TABLE `aro_map` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `aro_sections`
--

DROP TABLE IF EXISTS `aro_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aro_sections` (
  `id` int(11) NOT NULL DEFAULT '0',
  `value` varchar(230) NOT NULL,
  `order_value` int(11) NOT NULL DEFAULT '0',
  `name` varchar(230) NOT NULL,
  `hidden` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `value_aro_sections` (`value`),
  KEY `hidden_aro_sections` (`hidden`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aro_sections`
--

LOCK TABLES `aro_sections` WRITE;
/*!40000 ALTER TABLE `aro_sections` DISABLE KEYS */;
INSERT INTO `aro_sections` VALUES (110,'usuarios',0,'usuarios',0);
/*!40000 ALTER TABLE `aro_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `aro_sections_seq`
--

DROP TABLE IF EXISTS `aro_sections_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aro_sections_seq` (
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aro_sections_seq`
--

LOCK TABLES `aro_sections_seq` WRITE;
/*!40000 ALTER TABLE `aro_sections_seq` DISABLE KEYS */;
INSERT INTO `aro_sections_seq` VALUES (110);
/*!40000 ALTER TABLE `aro_sections_seq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `aro_seq`
--

DROP TABLE IF EXISTS `aro_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aro_seq` (
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aro_seq`
--

LOCK TABLES `aro_seq` WRITE;
/*!40000 ALTER TABLE `aro_seq` DISABLE KEYS */;
INSERT INTO `aro_seq` VALUES (2594);
/*!40000 ALTER TABLE `aro_seq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `axo`
--

DROP TABLE IF EXISTS `axo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `axo` (
  `id` int(11) NOT NULL DEFAULT '0',
  `section_value` varchar(240) NOT NULL DEFAULT '0',
  `value` varchar(240) NOT NULL,
  `order_value` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `hidden` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `section_value_value_axo` (`section_value`,`value`),
  KEY `hidden_axo` (`hidden`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `axo`
--

LOCK TABLES `axo` WRITE;
/*!40000 ALTER TABLE `axo` DISABLE KEYS */;
/*!40000 ALTER TABLE `axo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `axo_groups`
--

DROP TABLE IF EXISTS `axo_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `axo_groups` (
  `id` int(11) NOT NULL DEFAULT '0',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `lft` int(11) NOT NULL DEFAULT '0',
  `rgt` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`,`value`),
  UNIQUE KEY `value_axo_groups` (`value`),
  KEY `parent_id_axo_groups` (`parent_id`),
  KEY `lft_rgt_axo_groups` (`lft`,`rgt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `axo_groups`
--

LOCK TABLES `axo_groups` WRITE;
/*!40000 ALTER TABLE `axo_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `axo_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `axo_groups_map`
--

DROP TABLE IF EXISTS `axo_groups_map`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `axo_groups_map` (
  `acl_id` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`acl_id`,`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `axo_groups_map`
--

LOCK TABLES `axo_groups_map` WRITE;
/*!40000 ALTER TABLE `axo_groups_map` DISABLE KEYS */;
/*!40000 ALTER TABLE `axo_groups_map` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `axo_map`
--

DROP TABLE IF EXISTS `axo_map`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `axo_map` (
  `acl_id` int(11) NOT NULL DEFAULT '0',
  `section_value` varchar(230) NOT NULL DEFAULT '0',
  `value` varchar(230) NOT NULL,
  PRIMARY KEY (`acl_id`,`section_value`,`value`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `axo_map`
--

LOCK TABLES `axo_map` WRITE;
/*!40000 ALTER TABLE `axo_map` DISABLE KEYS */;
/*!40000 ALTER TABLE `axo_map` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `axo_sections`
--

DROP TABLE IF EXISTS `axo_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `axo_sections` (
  `id` int(11) NOT NULL DEFAULT '0',
  `value` varchar(230) NOT NULL,
  `order_value` int(11) NOT NULL DEFAULT '0',
  `name` varchar(230) NOT NULL,
  `hidden` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `value_axo_sections` (`value`),
  KEY `hidden_axo_sections` (`hidden`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `axo_sections`
--

LOCK TABLES `axo_sections` WRITE;
/*!40000 ALTER TABLE `axo_sections` DISABLE KEYS */;
/*!40000 ALTER TABLE `axo_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groups_aro_map`
--

DROP TABLE IF EXISTS `groups_aro_map`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups_aro_map` (
  `group_id` int(11) NOT NULL DEFAULT '0',
  `aro_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`group_id`,`aro_id`),
  KEY `aro_id` (`aro_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups_aro_map`
--

LOCK TABLES `groups_aro_map` WRITE;
/*!40000 ALTER TABLE `groups_aro_map` DISABLE KEYS */;
INSERT INTO `groups_aro_map` VALUES (1751,2582),(1752,2583),(1753,2584),(1753,2590),(1755,2584),(1755,2588),(1756,2584),(1756,2594),(1758,2584),(1758,2589),(1759,2584),(1759,2592),(1761,2584),(1761,2593),(1762,2584),(1762,2591),(1763,2585),(1764,2584),(1767,2586),(1768,2587);
/*!40000 ALTER TABLE `groups_aro_map` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groups_axo_map`
--

DROP TABLE IF EXISTS `groups_axo_map`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups_axo_map` (
  `group_id` int(11) NOT NULL DEFAULT '0',
  `axo_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`group_id`,`axo_id`),
  KEY `axo_id` (`axo_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups_axo_map`
--

LOCK TABLES `groups_axo_map` WRITE;
/*!40000 ALTER TABLE `groups_axo_map` DISABLE KEYS */;
/*!40000 ALTER TABLE `groups_axo_map` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `phpgacl`
--

DROP TABLE IF EXISTS `phpgacl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phpgacl` (
  `name` varchar(230) NOT NULL,
  `value` varchar(230) NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `phpgacl`
--

LOCK TABLES `phpgacl` WRITE;
/*!40000 ALTER TABLE `phpgacl` DISABLE KEYS */;
INSERT INTO `phpgacl` VALUES ('version','3.3.7'),('schema_version','2.1');
/*!40000 ALTER TABLE `phpgacl` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-03-23 19:17:34
