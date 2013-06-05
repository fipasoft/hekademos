/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50075
Source Host           : localhost:3306
Source Database       : acl

Target Server Type    : MYSQL
Target Server Version : 50075
File Encoding         : 65001

Date: 2010-06-18 19:12:30
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `acl`
-- ----------------------------
DROP TABLE IF EXISTS `acl`;
CREATE TABLE `acl` (
  `id` int(11) NOT NULL default '0',
  `section_value` varchar(230) NOT NULL default 'system',
  `allow` int(11) NOT NULL default '0',
  `enabled` int(11) NOT NULL default '0',
  `return_value` text,
  `note` text,
  `updated_date` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `enabled_acl` (`enabled`),
  KEY `section_value_acl` (`section_value`),
  KEY `updated_date_acl` (`updated_date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of acl
-- ----------------------------
INSERT INTO `acl` VALUES ('5135', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5136', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5137', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5138', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5139', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5140', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5141', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5142', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5143', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5144', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5145', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5146', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5147', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5148', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5149', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5150', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5151', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5152', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5153', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5154', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5155', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5156', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5157', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5158', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5159', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5160', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5161', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5162', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5163', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5164', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5165', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5166', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5167', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5168', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5169', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5170', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5171', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5172', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5173', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5174', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5175', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5176', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5177', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5178', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5179', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5180', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5181', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5182', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5183', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5184', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5185', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5186', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5187', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5188', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5189', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5190', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5191', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5192', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5193', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5194', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5195', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5196', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5197', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5198', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5199', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5200', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5201', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5202', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5203', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5204', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5205', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5206', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5207', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5208', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5209', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5210', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5211', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5212', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5213', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5214', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5215', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5216', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5217', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5218', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5219', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5220', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5221', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5222', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5223', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5224', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5225', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5226', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5227', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5228', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5229', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5230', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5231', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5232', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5233', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5234', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5235', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5236', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5237', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5238', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5239', 'system', '1', '1', '', '', '1276287647');
INSERT INTO `acl` VALUES ('5240', 'system', '1', '1', '', '', '1276287647');

-- ----------------------------
-- Table structure for `acl_sections`
-- ----------------------------
DROP TABLE IF EXISTS `acl_sections`;
CREATE TABLE `acl_sections` (
  `id` int(11) NOT NULL default '0',
  `value` varchar(230) NOT NULL,
  `order_value` int(11) NOT NULL default '0',
  `name` varchar(230) NOT NULL,
  `hidden` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `value_acl_sections` (`value`),
  KEY `hidden_acl_sections` (`hidden`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of acl_sections
-- ----------------------------
INSERT INTO `acl_sections` VALUES ('1', 'system', '1', 'System', '0');
INSERT INTO `acl_sections` VALUES ('2', 'user', '2', 'User', '0');
INSERT INTO `acl_sections` VALUES ('10', 'sistema', '0', 'sistema', '0');

-- ----------------------------
-- Table structure for `acl_sections_seq`
-- ----------------------------
DROP TABLE IF EXISTS `acl_sections_seq`;
CREATE TABLE `acl_sections_seq` (
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of acl_sections_seq
-- ----------------------------
INSERT INTO `acl_sections_seq` VALUES ('65');

-- ----------------------------
-- Table structure for `acl_seq`
-- ----------------------------
DROP TABLE IF EXISTS `acl_seq`;
CREATE TABLE `acl_seq` (
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of acl_seq
-- ----------------------------
INSERT INTO `acl_seq` VALUES ('5240');

-- ----------------------------
-- Table structure for `aco`
-- ----------------------------
DROP TABLE IF EXISTS `aco`;
CREATE TABLE `aco` (
  `id` int(11) NOT NULL default '0',
  `section_value` varchar(240) NOT NULL default '0',
  `value` varchar(240) NOT NULL,
  `order_value` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL,
  `hidden` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `section_value_value_aco` (`section_value`,`value`),
  KEY `hidden_aco` (`hidden`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of aco
-- ----------------------------
INSERT INTO `aco` VALUES ('16325', 'ALL', 'ALL', '0', 'ALL', '0');
INSERT INTO `aco` VALUES ('16326', 'sesion', 'abrir', '0', 'abrir', '0');
INSERT INTO `aco` VALUES ('16327', 'sesion', 'autenticar', '0', 'autenticar', '0');
INSERT INTO `aco` VALUES ('16328', 'sesion', 'cerrar', '0', 'cerrar', '0');
INSERT INTO `aco` VALUES ('16329', 'sesion', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16330', 'sesion', 'restringir', '0', 'restringir', '0');
INSERT INTO `aco` VALUES ('16331', 'inicio', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16332', 'inicio', 'administrador', '0', 'administrador', '0');
INSERT INTO `aco` VALUES ('16333', 'inicio', 'director', '0', 'director', '0');
INSERT INTO `aco` VALUES ('16334', 'inicio', 'oficial', '0', 'oficial', '0');
INSERT INTO `aco` VALUES ('16335', 'inicio', 'plantilla', '0', 'plantilla', '0');
INSERT INTO `aco` VALUES ('16336', 'inicio', 'profesor', '0', 'profesor', '0');
INSERT INTO `aco` VALUES ('16337', 'inicio', 'secretaria', '0', 'secretaria', '0');
INSERT INTO `aco` VALUES ('16338', 'inicio', 'secretario', '0', 'secretario', '0');
INSERT INTO `aco` VALUES ('16339', 'aulas', 'agregar', '0', 'agregar', '0');
INSERT INTO `aco` VALUES ('16340', 'aulas', 'editar', '0', 'editar', '0');
INSERT INTO `aco` VALUES ('16341', 'aulas', 'eliminar', '0', 'eliminar', '0');
INSERT INTO `aco` VALUES ('16342', 'aulas', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16343', 'aulas', 'buscar', '0', 'buscar', '0');
INSERT INTO `aco` VALUES ('16344', 'aulas', 'exportar', '0', 'exportar', '0');
INSERT INTO `aco` VALUES ('16345', 'aulas', 'disponible', '0', 'disponible', '0');
INSERT INTO `aco` VALUES ('16346', 'alumnos', 'agregar', '0', 'agregar', '0');
INSERT INTO `aco` VALUES ('16347', 'alumnos', 'avisos', '0', 'avisos', '0');
INSERT INTO `aco` VALUES ('16348', 'alumnos', 'comentarios', '0', 'comentarios', '0');
INSERT INTO `aco` VALUES ('16349', 'alumnos', 'asignar', '0', 'asignar', '0');
INSERT INTO `aco` VALUES ('16350', 'alumnos', 'buscar', '0', 'buscar', '0');
INSERT INTO `aco` VALUES ('16351', 'alumnos', 'disponible', '0', 'disponible', '0');
INSERT INTO `aco` VALUES ('16352', 'alumnos', 'editar', '0', 'editar', '0');
INSERT INTO `aco` VALUES ('16353', 'alumnos', 'eliminar', '0', 'eliminar', '0');
INSERT INTO `aco` VALUES ('16354', 'alumnos', 'exportar', '0', 'exportar', '0');
INSERT INTO `aco` VALUES ('16355', 'alumnos', 'ubicar', '0', 'ubicar', '0');
INSERT INTO `aco` VALUES ('16356', 'alumnos', 'imprimir', '0', 'imprimir', '0');
INSERT INTO `aco` VALUES ('16357', 'alumnos', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16358', 'alumnos', 'info', '0', 'info', '0');
INSERT INTO `aco` VALUES ('16359', 'alumnos', 'password', '0', 'password', '0');
INSERT INTO `aco` VALUES ('16360', 'alumnos', 'ver', '0', 'ver', '0');
INSERT INTO `aco` VALUES ('16361', 'alumnos', 'kardex', '0', 'kardex', '0');
INSERT INTO `aco` VALUES ('16362', 'alumnos', 'cursos', '0', 'cursos', '0');
INSERT INTO `aco` VALUES ('16363', 'alumnos', 'escolar', '0', 'escolar', '0');
INSERT INTO `aco` VALUES ('16364', 'alumnos', 'importar', '0', 'importar', '0');
INSERT INTO `aco` VALUES ('16365', 'alumnos', 'trayectoria', '0', 'trayectoria', '0');
INSERT INTO `aco` VALUES ('16366', 'accesos', 'generar', '0', 'generar', '0');
INSERT INTO `aco` VALUES ('16367', 'accesos', 'exportar', '0', 'exportar', '0');
INSERT INTO `aco` VALUES ('16368', 'accesos', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16369', 'importar', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16370', 'importar', 'fotos', '0', 'fotos', '0');
INSERT INTO `aco` VALUES ('16371', 'asistencias', 'agregar', '0', 'agregar', '0');
INSERT INTO `aco` VALUES ('16372', 'asistencias', 'editar', '0', 'editar', '0');
INSERT INTO `aco` VALUES ('16373', 'asistencias', 'eliminar', '0', 'eliminar', '0');
INSERT INTO `aco` VALUES ('16374', 'asistencias', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16375', 'asistencias', 'imprimir', '0', 'imprimir', '0');
INSERT INTO `aco` VALUES ('16376', 'asistencias', 'justificar', '0', 'justificar', '0');
INSERT INTO `aco` VALUES ('16377', 'asistencias', 'selector', '0', 'selector', '0');
INSERT INTO `aco` VALUES ('16378', 'asistencias', 'faltas', '0', 'faltas', '0');
INSERT INTO `aco` VALUES ('16379', 'asistencias', 'ver', '0', 'ver', '0');
INSERT INTO `aco` VALUES ('16380', 'calificaciones', 'agregar', '0', 'agregar', '0');
INSERT INTO `aco` VALUES ('16381', 'calificaciones', 'editar', '0', 'editar', '0');
INSERT INTO `aco` VALUES ('16382', 'calificaciones', 'eliminar', '0', 'eliminar', '0');
INSERT INTO `aco` VALUES ('16383', 'calificaciones', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16384', 'calificaciones', 'imprimir', '0', 'imprimir', '0');
INSERT INTO `aco` VALUES ('16385', 'calificaciones', 'selector', '0', 'selector', '0');
INSERT INTO `aco` VALUES ('16386', 'calificaciones', 'ver', '0', 'ver', '0');
INSERT INTO `aco` VALUES ('16387', 'parciales', 'agregar', '0', 'agregar', '0');
INSERT INTO `aco` VALUES ('16388', 'parciales', 'editar', '0', 'editar', '0');
INSERT INTO `aco` VALUES ('16389', 'parciales', 'eliminar', '0', 'eliminar', '0');
INSERT INTO `aco` VALUES ('16390', 'parciales', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16391', 'parciales', 'imprimir', '0', 'imprimir', '0');
INSERT INTO `aco` VALUES ('16392', 'parciales', 'ver', '0', 'ver', '0');
INSERT INTO `aco` VALUES ('16393', 'tutores', 'agregar', '0', 'agregar', '0');
INSERT INTO `aco` VALUES ('16394', 'tutores', 'editar', '0', 'editar', '0');
INSERT INTO `aco` VALUES ('16395', 'tutores', 'eliminar', '0', 'eliminar', '0');
INSERT INTO `aco` VALUES ('16396', 'tutores', 'imprimir', '0', 'imprimir', '0');
INSERT INTO `aco` VALUES ('16397', 'tutores', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16398', 'tutores', 'password', '0', 'password', '0');
INSERT INTO `aco` VALUES ('16399', 'tutores', 'ver', '0', 'ver', '0');
INSERT INTO `aco` VALUES ('16400', 'tutores', 'exportar', '0', 'exportar', '0');
INSERT INTO `aco` VALUES ('16401', 'tutores', 'buscar', '0', 'buscar', '0');
INSERT INTO `aco` VALUES ('16402', 'grupos', 'asignar', '0', 'asignar', '0');
INSERT INTO `aco` VALUES ('16403', 'grupos', 'agregar', '0', 'agregar', '0');
INSERT INTO `aco` VALUES ('16404', 'grupos', 'curso', '0', 'curso', '0');
INSERT INTO `aco` VALUES ('16405', 'grupos', 'editar', '0', 'editar', '0');
INSERT INTO `aco` VALUES ('16406', 'grupos', 'eliminar', '0', 'eliminar', '0');
INSERT INTO `aco` VALUES ('16407', 'grupos', 'disponible', '0', 'disponible', '0');
INSERT INTO `aco` VALUES ('16408', 'grupos', 'horario', '0', 'horario', '0');
INSERT INTO `aco` VALUES ('16409', 'grupos', 'imprimir', '0', 'imprimir', '0');
INSERT INTO `aco` VALUES ('16410', 'grupos', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16411', 'grupos', 'ver', '0', 'ver', '0');
INSERT INTO `aco` VALUES ('16412', 'grupos', 'generar', '0', 'generar', '0');
INSERT INTO `aco` VALUES ('16413', 'materias', 'agregar', '0', 'agregar', '0');
INSERT INTO `aco` VALUES ('16414', 'materias', 'disponible', '0', 'disponible', '0');
INSERT INTO `aco` VALUES ('16415', 'materias', 'editar', '0', 'editar', '0');
INSERT INTO `aco` VALUES ('16416', 'materias', 'eliminar', '0', 'eliminar', '0');
INSERT INTO `aco` VALUES ('16417', 'materias', 'enlazar', '0', 'enlazar', '0');
INSERT INTO `aco` VALUES ('16418', 'materias', 'exportar', '0', 'exportar', '0');
INSERT INTO `aco` VALUES ('16419', 'materias', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16420', 'materias', 'series', '0', 'series', '0');
INSERT INTO `aco` VALUES ('16421', 'materias', 'ver', '0', 'ver', '0');
INSERT INTO `aco` VALUES ('16422', 'materias', 'academias', '0', 'academias', '0');
INSERT INTO `aco` VALUES ('16423', 'competencias', 'obtenertipos', '0', 'obtenertipos', '0');
INSERT INTO `aco` VALUES ('16424', 'profesores', 'agregar', '0', 'agregar', '0');
INSERT INTO `aco` VALUES ('16425', 'profesores', 'disponible', '0', 'disponible', '0');
INSERT INTO `aco` VALUES ('16426', 'profesores', 'editar', '0', 'editar', '0');
INSERT INTO `aco` VALUES ('16427', 'profesores', 'eliminar', '0', 'eliminar', '0');
INSERT INTO `aco` VALUES ('16428', 'profesores', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16429', 'profesores', 'ver', '0', 'ver', '0');
INSERT INTO `aco` VALUES ('16430', 'profesores', 'exportar', '0', 'exportar', '0');
INSERT INTO `aco` VALUES ('16431', 'profesores', 'password', '0', 'password', '0');
INSERT INTO `aco` VALUES ('16432', 'profesores', 'buscar', '0', 'buscar', '0');
INSERT INTO `aco` VALUES ('16433', 'profesores', 'horario', '0', 'horario', '0');
INSERT INTO `aco` VALUES ('16434', 'profesores', 'horarioexcel', '0', 'horarioexcel', '0');
INSERT INTO `aco` VALUES ('16435', 'personal', 'agregar', '0', 'agregar', '0');
INSERT INTO `aco` VALUES ('16436', 'personal', 'disponible', '0', 'disponible', '0');
INSERT INTO `aco` VALUES ('16437', 'personal', 'editar', '0', 'editar', '0');
INSERT INTO `aco` VALUES ('16438', 'personal', 'eliminar', '0', 'eliminar', '0');
INSERT INTO `aco` VALUES ('16439', 'personal', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16440', 'personal', 'ver', '0', 'ver', '0');
INSERT INTO `aco` VALUES ('16441', 'personal', 'exportar', '0', 'exportar', '0');
INSERT INTO `aco` VALUES ('16442', 'personal', 'buscar', '0', 'buscar', '0');
INSERT INTO `aco` VALUES ('16443', 'tipopersonal', 'agregar', '0', 'agregar', '0');
INSERT INTO `aco` VALUES ('16444', 'tipopersonal', 'editar', '0', 'editar', '0');
INSERT INTO `aco` VALUES ('16445', 'tipopersonal', 'eliminar', '0', 'eliminar', '0');
INSERT INTO `aco` VALUES ('16446', 'tipopersonal', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16447', 'tipopersonal', 'buscar', '0', 'buscar', '0');
INSERT INTO `aco` VALUES ('16448', 'cursos', 'agregar', '0', 'agregar', '0');
INSERT INTO `aco` VALUES ('16449', 'cursos', 'editar', '0', 'editar', '0');
INSERT INTO `aco` VALUES ('16450', 'cursos', 'exportar', '0', 'exportar', '0');
INSERT INTO `aco` VALUES ('16451', 'cursos', 'eliminar', '0', 'eliminar', '0');
INSERT INTO `aco` VALUES ('16452', 'cursos', 'imprimir', '0', 'imprimir', '0');
INSERT INTO `aco` VALUES ('16453', 'cursos', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16454', 'cursos', 'status', '0', 'status', '0');
INSERT INTO `aco` VALUES ('16455', 'cursos', 'ver', '0', 'ver', '0');
INSERT INTO `aco` VALUES ('16456', 'cursos', 'buscar', '0', 'buscar', '0');
INSERT INTO `aco` VALUES ('16457', 'cursos', 'grupo', '0', 'grupo', '0');
INSERT INTO `aco` VALUES ('16458', 'cursos', 'fecha', '0', 'fecha', '0');
INSERT INTO `aco` VALUES ('16459', 'cursos', 'grupoexportar', '0', 'grupoexportar', '0');
INSERT INTO `aco` VALUES ('16460', 'horarios', 'agregar', '0', 'agregar', '0');
INSERT INTO `aco` VALUES ('16461', 'horarios', 'editar', '0', 'editar', '0');
INSERT INTO `aco` VALUES ('16462', 'horarios', 'eliminar', '0', 'eliminar', '0');
INSERT INTO `aco` VALUES ('16463', 'horarios', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16464', 'horarios', 'ver', '0', 'ver', '0');
INSERT INTO `aco` VALUES ('16465', 'horarios', 'validar', '0', 'validar', '0');
INSERT INTO `aco` VALUES ('16466', 'agenda', 'agregar', '0', 'agregar', '0');
INSERT INTO `aco` VALUES ('16467', 'agenda', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16468', 'agenda', 'ver', '0', 'ver', '0');
INSERT INTO `aco` VALUES ('16469', 'agenda', 'obtengrupos', '0', 'obtengrupos', '0');
INSERT INTO `aco` VALUES ('16470', 'agenda', 'obtenusuarios', '0', 'obtenusuarios', '0');
INSERT INTO `aco` VALUES ('16471', 'agenda', 'obtenacos', '0', 'obtenacos', '0');
INSERT INTO `aco` VALUES ('16472', 'agenda', 'guarda', '0', 'guarda', '0');
INSERT INTO `aco` VALUES ('16473', 'agenda', 'editar', '0', 'editar', '0');
INSERT INTO `aco` VALUES ('16474', 'ciclos', 'agregar', '0', 'agregar', '0');
INSERT INTO `aco` VALUES ('16475', 'ciclos', 'abrir', '0', 'abrir', '0');
INSERT INTO `aco` VALUES ('16476', 'ciclos', 'avance', '0', 'avance', '0');
INSERT INTO `aco` VALUES ('16477', 'ciclos', 'editar', '0', 'editar', '0');
INSERT INTO `aco` VALUES ('16478', 'ciclos', 'eliminar', '0', 'eliminar', '0');
INSERT INTO `aco` VALUES ('16479', 'ciclos', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16480', 'ciclos', 'status', '0', 'status', '0');
INSERT INTO `aco` VALUES ('16481', 'ciclos', 'buscar', '0', 'buscar', '0');
INSERT INTO `aco` VALUES ('16482', 'ciclos', 'simulaavance', '0', 'simulaavance', '0');
INSERT INTO `aco` VALUES ('16483', 'ciclos', 'faltantes', '0', 'faltantes', '0');
INSERT INTO `aco` VALUES ('16484', 'usuarios', 'agregar', '0', 'agregar', '0');
INSERT INTO `aco` VALUES ('16485', 'usuarios', 'editar', '0', 'editar', '0');
INSERT INTO `aco` VALUES ('16486', 'usuarios', 'eliminar', '0', 'eliminar', '0');
INSERT INTO `aco` VALUES ('16487', 'usuarios', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16488', 'usuarios', 'password', '0', 'password', '0');
INSERT INTO `aco` VALUES ('16489', 'usuarios', 'validarLogin', '0', 'validarLogin', '0');
INSERT INTO `aco` VALUES ('16490', 'usuarios', 'verAcceso', '0', 'verAcceso', '0');
INSERT INTO `aco` VALUES ('16491', 'usuarios', 'ver', '0', 'ver', '0');
INSERT INTO `aco` VALUES ('16492', 'sistema', 'ayuda', '0', 'ayuda', '0');
INSERT INTO `aco` VALUES ('16493', 'sistema', 'configuracion', '0', 'configuracion', '0');
INSERT INTO `aco` VALUES ('16494', 'sistema', 'password', '0', 'password', '0');
INSERT INTO `aco` VALUES ('16495', 'sistema', 'seleccionar', '0', 'seleccionar', '0');
INSERT INTO `aco` VALUES ('16496', 'inscripcion', 'agregar', '0', 'agregar', '0');
INSERT INTO `aco` VALUES ('16497', 'inscripcion', 'confirmar', '0', 'confirmar', '0');
INSERT INTO `aco` VALUES ('16498', 'inscripcion', 'eliminar', '0', 'eliminar', '0');
INSERT INTO `aco` VALUES ('16499', 'inscripcion', 'articulo', '0', 'articulo', '0');
INSERT INTO `aco` VALUES ('16500', 'historial', 'exportar', '0', 'exportar', '0');
INSERT INTO `aco` VALUES ('16501', 'historial', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16502', 'historial', 'buscar', '0', 'buscar', '0');
INSERT INTO `aco` VALUES ('16503', 'historial', 'ver', '0', 'ver', '0');
INSERT INTO `aco` VALUES ('16504', 'estadisticas', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16505', 'estadisticas', 'asistencias', '0', 'asistencias', '0');
INSERT INTO `aco` VALUES ('16506', 'estadisticas', 'calificaciones', '0', 'calificaciones', '0');
INSERT INTO `aco` VALUES ('16507', 'estadisticas', 'promedios', '0', 'promedios', '0');
INSERT INTO `aco` VALUES ('16508', 'estadisticas', 'aprobadas', '0', 'aprobadas', '0');
INSERT INTO `aco` VALUES ('16509', 'estadisticas', 'aprobacion', '0', 'aprobacion', '0');
INSERT INTO `aco` VALUES ('16510', 'es', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16511', 'es', 'exportar', '0', 'exportar', '0');
INSERT INTO `aco` VALUES ('16512', 'es', 'dia', '0', 'dia', '0');
INSERT INTO `aco` VALUES ('16513', 'es', 'inconsistencias', '0', 'inconsistencias', '0');
INSERT INTO `aco` VALUES ('16514', 'periodos', 'agregar', '0', 'agregar', '0');
INSERT INTO `aco` VALUES ('16515', 'periodos', 'editar', '0', 'editar', '0');
INSERT INTO `aco` VALUES ('16516', 'periodos', 'eliminar', '0', 'eliminar', '0');
INSERT INTO `aco` VALUES ('16517', 'periodos', 'estadistica', '0', 'estadistica', '0');
INSERT INTO `aco` VALUES ('16518', 'periodos', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16519', 'optativas', 'alumnos', '0', 'alumnos', '0');
INSERT INTO `aco` VALUES ('16520', 'optativas', 'avanzadas', '0', 'avanzadas', '0');
INSERT INTO `aco` VALUES ('16521', 'optativas', 'bloques', '0', 'bloques', '0');
INSERT INTO `aco` VALUES ('16522', 'optativas', 'cupos', '0', 'cupos', '0');
INSERT INTO `aco` VALUES ('16523', 'optativas', 'eliminar', '0', 'eliminar', '0');
INSERT INTO `aco` VALUES ('16524', 'optativas', 'configuracion', '0', 'configuracion', '0');
INSERT INTO `aco` VALUES ('16525', 'optativas', 'cursos', '0', 'cursos', '0');
INSERT INTO `aco` VALUES ('16526', 'optativas', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16527', 'optativas', 'inscritos', '0', 'inscritos', '0');
INSERT INTO `aco` VALUES ('16528', 'optativas', 'inscritosexportar', '0', 'inscritosexportar', '0');
INSERT INTO `aco` VALUES ('16529', 'optativas', 'inscribir', '0', 'inscribir', '0');
INSERT INTO `aco` VALUES ('16530', 'optativas', 'taes', '0', 'taes', '0');
INSERT INTO `aco` VALUES ('16531', 'optativas', 'taesinfo', '0', 'taesinfo', '0');
INSERT INTO `aco` VALUES ('16532', 'optativas', 'trayectoria', '0', 'trayectoria', '0');
INSERT INTO `aco` VALUES ('16533', 'optativas', 'trayectoriasexportar', '0', 'trayectoriasexportar', '0');
INSERT INTO `aco` VALUES ('16534', 'bloques', 'agregar', '0', 'agregar', '0');
INSERT INTO `aco` VALUES ('16535', 'bloques', 'editar', '0', 'editar', '0');
INSERT INTO `aco` VALUES ('16536', 'bloques', 'eliminar', '0', 'eliminar', '0');
INSERT INTO `aco` VALUES ('16537', 'bloques', 'eliminartodos', '0', 'eliminartodos', '0');
INSERT INTO `aco` VALUES ('16538', 'bloques', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16539', 'bloquesalumnos', 'agregar', '0', 'agregar', '0');
INSERT INTO `aco` VALUES ('16540', 'bloquesalumnos', 'cambio', '0', 'cambio', '0');
INSERT INTO `aco` VALUES ('16541', 'bloquesalumnos', 'eliminar', '0', 'eliminar', '0');
INSERT INTO `aco` VALUES ('16542', 'bloquesalumnos', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16543', 'reportes', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16544', 'reportes', 'resumen', '0', 'resumen', '0');
INSERT INTO `aco` VALUES ('16545', 'reportes', 'derechos', '0', 'derechos', '0');
INSERT INTO `aco` VALUES ('16546', 'reportes', 'plantilla', '0', 'plantilla', '0');
INSERT INTO `aco` VALUES ('16547', 'importador', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16548', 'importador', 'taes', '0', 'taes', '0');
INSERT INTO `aco` VALUES ('16549', 'importador', 'curso', '0', 'curso', '0');
INSERT INTO `aco` VALUES ('16550', 'importador', 'grupocursos', '0', 'grupocursos', '0');
INSERT INTO `aco` VALUES ('16551', 'admin', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16552', 'archivo', 'actualizar', '0', 'actualizar', '0');
INSERT INTO `aco` VALUES ('16553', 'archivo', 'cambiastatus', '0', 'cambiastatus', '0');
INSERT INTO `aco` VALUES ('16554', 'archivo', 'descargar', '0', 'descargar', '0');
INSERT INTO `aco` VALUES ('16555', 'archivo', 'editar', '0', 'editar', '0');
INSERT INTO `aco` VALUES ('16556', 'archivo', 'eliminar', '0', 'eliminar', '0');
INSERT INTO `aco` VALUES ('16557', 'archivo', 'guardar', '0', 'guardar', '0');
INSERT INTO `aco` VALUES ('16558', 'archivo', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16559', 'archivo', 'nuevo', '0', 'nuevo', '0');
INSERT INTO `aco` VALUES ('16560', 'blog', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16561', 'blog', 'gestor', '0', 'gestor', '0');
INSERT INTO `aco` VALUES ('16562', 'categoriasdescargas', 'eliminar', '0', 'eliminar', '0');
INSERT INTO `aco` VALUES ('16563', 'categoriasdescargas', 'actualizar', '0', 'actualizar', '0');
INSERT INTO `aco` VALUES ('16564', 'categoriasdescargas', 'editar', '0', 'editar', '0');
INSERT INTO `aco` VALUES ('16565', 'categoriasdescargas', 'gestor', '0', 'gestor', '0');
INSERT INTO `aco` VALUES ('16566', 'categoriasdescargas', 'guardar', '0', 'guardar', '0');
INSERT INTO `aco` VALUES ('16567', 'categoriasdescargas', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16568', 'categoriasdescargas', 'nuevo', '0', 'nuevo', '0');
INSERT INTO `aco` VALUES ('16569', 'categoriasmultimedia', 'actualizar', '0', 'actualizar', '0');
INSERT INTO `aco` VALUES ('16570', 'categoriasmultimedia', 'editar', '0', 'editar', '0');
INSERT INTO `aco` VALUES ('16571', 'categoriasmultimedia', 'eliminar', '0', 'eliminar', '0');
INSERT INTO `aco` VALUES ('16572', 'categoriasmultimedia', 'gestor', '0', 'gestor', '0');
INSERT INTO `aco` VALUES ('16573', 'categoriasmultimedia', 'guardar', '0', 'guardar', '0');
INSERT INTO `aco` VALUES ('16574', 'categoriasmultimedia', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16575', 'categoriasmultimedia', 'nuevo', '0', 'nuevo', '0');
INSERT INTO `aco` VALUES ('16576', 'contacto', 'guardar', '0', 'guardar', '0');
INSERT INTO `aco` VALUES ('16577', 'contacto', 'sugerencias', '0', 'sugerencias', '0');
INSERT INTO `aco` VALUES ('16578', 'contacto', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16579', 'contacto', 'ubicacion', '0', 'ubicacion', '0');
INSERT INTO `aco` VALUES ('16580', 'contenido', 'gestor', '0', 'gestor', '0');
INSERT INTO `aco` VALUES ('16581', 'contenido', 'editar', '0', 'editar', '0');
INSERT INTO `aco` VALUES ('16582', 'contenido', 'guardar', '0', 'guardar', '0');
INSERT INTO `aco` VALUES ('16583', 'contenido', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16584', 'contenido', 'cambiaStatus', '0', 'cambiaStatus', '0');
INSERT INTO `aco` VALUES ('16585', 'director', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16586', 'director', 'informes', '0', 'informes', '0');
INSERT INTO `aco` VALUES ('16587', 'director', 'trayectoria', '0', 'trayectoria', '0');
INSERT INTO `aco` VALUES ('16588', 'director', 'video', '0', 'video', '0');
INSERT INTO `aco` VALUES ('16589', 'descargas', 'gestor', '0', 'gestor', '0');
INSERT INTO `aco` VALUES ('16590', 'descargas', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16591', 'modulo', 'cambiastatus', '0', 'cambiastatus', '0');
INSERT INTO `aco` VALUES ('16592', 'modulo', 'editar', '0', 'editar', '0');
INSERT INTO `aco` VALUES ('16593', 'modulo', 'gestor', '0', 'gestor', '0');
INSERT INTO `aco` VALUES ('16594', 'modulo', 'guardar', '0', 'guardar', '0');
INSERT INTO `aco` VALUES ('16595', 'modulo', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16596', 'mmf', 'actualizar', '0', 'actualizar', '0');
INSERT INTO `aco` VALUES ('16597', 'mmf', 'cambiastatus', '0', 'cambiastatus', '0');
INSERT INTO `aco` VALUES ('16598', 'mmf', 'editar', '0', 'editar', '0');
INSERT INTO `aco` VALUES ('16599', 'mmf', 'eliminar', '0', 'eliminar', '0');
INSERT INTO `aco` VALUES ('16600', 'mmf', 'guardar', '0', 'guardar', '0');
INSERT INTO `aco` VALUES ('16601', 'mmf', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16602', 'mmf', 'nuevo', '0', 'nuevo', '0');
INSERT INTO `aco` VALUES ('16603', 'mmf', 'vista_previa', '0', 'vista_previa', '0');
INSERT INTO `aco` VALUES ('16604', 'multimedia', 'gestor', '0', 'gestor', '0');
INSERT INTO `aco` VALUES ('16605', 'multimedia', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16606', 'nuestraprepa', 'acerca', '0', 'acerca', '0');
INSERT INTO `aco` VALUES ('16607', 'nuestraprepa', 'agregar_comentarios', '0', 'agregar_comentarios', '0');
INSERT INTO `aco` VALUES ('16608', 'nuestraprepa', 'blog', '0', 'blog', '0');
INSERT INTO `aco` VALUES ('16609', 'nuestraprepa', 'blog_comentarios', '0', 'blog_comentarios', '0');
INSERT INTO `aco` VALUES ('16610', 'nuestraprepa', 'consejo', '0', 'consejo', '0');
INSERT INTO `aco` VALUES ('16611', 'nuestraprepa', 'directorio', '0', 'directorio', '0');
INSERT INTO `aco` VALUES ('16612', 'nuestraprepa', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16613', 'nuestraprepa', 'inicio', '0', 'inicio', '0');
INSERT INTO `aco` VALUES ('16614', 'nuestraprepa', 'mision', '0', 'mision', '0');
INSERT INTO `aco` VALUES ('16615', 'nuestraprepa', 'normatividad', '0', 'normatividad', '0');
INSERT INTO `aco` VALUES ('16616', 'nuestraprepa', 'organigrama', '0', 'organigrama', '0');
INSERT INTO `aco` VALUES ('16617', 'nuestraprepa', 'transparencia', '0', 'transparencia', '0');
INSERT INTO `aco` VALUES ('16618', 'nuestraprepa', 'vision', '0', 'vision', '0');
INSERT INTO `aco` VALUES ('16619', 'nuestraprepa', 'iso', '0', 'iso', '0');
INSERT INTO `aco` VALUES ('16620', 'post', 'actualizar', '0', 'actualizar', '0');
INSERT INTO `aco` VALUES ('16621', 'post', 'cambiaStatus', '0', 'cambiaStatus', '0');
INSERT INTO `aco` VALUES ('16622', 'post', 'cambiaStatusPost', '0', 'cambiaStatusPost', '0');
INSERT INTO `aco` VALUES ('16623', 'post', 'editar', '0', 'editar', '0');
INSERT INTO `aco` VALUES ('16624', 'post', 'eliminar', '0', 'eliminar', '0');
INSERT INTO `aco` VALUES ('16625', 'post', 'eliminarPost', '0', 'eliminarPost', '0');
INSERT INTO `aco` VALUES ('16626', 'post', 'guardar', '0', 'guardar', '0');
INSERT INTO `aco` VALUES ('16627', 'post', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16628', 'post', 'nuevo', '0', 'nuevo', '0');
INSERT INTO `aco` VALUES ('16629', 'post', 'vista_previa', '0', 'vista_previa', '0');
INSERT INTO `aco` VALUES ('16630', 'servicios', 'agenda', '0', 'agenda', '0');
INSERT INTO `aco` VALUES ('16631', 'servicios', 'documentos', '0', 'documentos', '0');
INSERT INTO `aco` VALUES ('16632', 'servicios', 'descargas', '0', 'descargas', '0');
INSERT INTO `aco` VALUES ('16633', 'servicios', 'radio', '0', 'radio', '0');
INSERT INTO `aco` VALUES ('16634', 'servicios', 'formatos', '0', 'formatos', '0');
INSERT INTO `aco` VALUES ('16635', 'servicios', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16636', 'sugerencias', 'eliminar', '0', 'eliminar', '0');
INSERT INTO `aco` VALUES ('16637', 'sugerencias', 'historial', '0', 'historial', '0');
INSERT INTO `aco` VALUES ('16638', 'sugerencias', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16639', 'sugerencias', 'responder', '0', 'responder', '0');
INSERT INTO `aco` VALUES ('16640', 'sugerencias', 'sugerencia', '0', 'sugerencia', '0');
INSERT INTO `aco` VALUES ('16641', 'texto', 'editar', '0', 'editar', '0');
INSERT INTO `aco` VALUES ('16642', 'texto', 'guardar', '0', 'guardar', '0');
INSERT INTO `aco` VALUES ('16643', 'texto', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16644', 'escolar', 'restringir', '0', 'restringir', '0');
INSERT INTO `aco` VALUES ('16645', 'escolar', 'accesos', '0', 'accesos', '0');
INSERT INTO `aco` VALUES ('16646', 'escolar', 'auth', '0', 'auth', '0');
INSERT INTO `aco` VALUES ('16647', 'escolar', 'abrir', '0', 'abrir', '0');
INSERT INTO `aco` VALUES ('16648', 'escolar', 'cerrar', '0', 'cerrar', '0');
INSERT INTO `aco` VALUES ('16649', 'escolar', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16650', 'escolar', 'ficha', '0', 'ficha', '0');
INSERT INTO `aco` VALUES ('16651', 'escolar', 'kardex', '0', 'kardex', '0');
INSERT INTO `aco` VALUES ('16652', 'escolar', 'asistencias', '0', 'asistencias', '0');
INSERT INTO `aco` VALUES ('16653', 'escolar', 'calificaciones', '0', 'calificaciones', '0');
INSERT INTO `aco` VALUES ('16654', 'escolar', 'inicio', '0', 'inicio', '0');
INSERT INTO `aco` VALUES ('16655', 'escolar', 'obtenAsistencias', '0', 'obtenAsistencias', '0');
INSERT INTO `aco` VALUES ('16656', 'escolar', 'obtenCalificaciones', '0', 'obtenCalificaciones', '0');
INSERT INTO `aco` VALUES ('16657', 'escolar', 'password', '0', 'password', '0');
INSERT INTO `aco` VALUES ('16658', 'escolar', 'pdf', '0', 'pdf', '0');
INSERT INTO `aco` VALUES ('16659', 'escolar', 'horario', '0', 'horario', '0');
INSERT INTO `aco` VALUES ('16660', 'escolar', 'agenda', '0', 'agenda', '0');
INSERT INTO `aco` VALUES ('16661', 'escolar', 'optativas', '0', 'optativas', '0');
INSERT INTO `aco` VALUES ('16662', 'escolar', 'ver_registro', '0', 'ver_registro', '0');
INSERT INTO `aco` VALUES ('16663', 'escolar', 'taes', '0', 'taes', '0');
INSERT INTO `aco` VALUES ('16664', 'controlescolar', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('16665', 'controlescolar', 'enviados', '0', 'enviados', '0');
INSERT INTO `aco` VALUES ('16666', 'controlescolar', 'borradores', '0', 'borradores', '0');
INSERT INTO `aco` VALUES ('16667', 'controlescolar', 'reenviar', '0', 'reenviar', '0');
INSERT INTO `aco` VALUES ('16668', 'controlescolar', 'notificacion', '0', 'notificacion', '0');
INSERT INTO `aco` VALUES ('16669', 'controlescolar', 'eliminar_notificacion', '0', 'eliminar_notificacion', '0');
INSERT INTO `aco` VALUES ('16670', 'controlescolar', 'gestor', '0', 'gestor', '0');
INSERT INTO `aco` VALUES ('16671', 'controlescolar', 'guardar', '0', 'guardar', '0');
INSERT INTO `aco` VALUES ('16672', 'controlescolar', 'actualizar', '0', 'actualizar', '0');
INSERT INTO `aco` VALUES ('16673', 'controlescolar', 'combo', '0', 'combo', '0');

-- ----------------------------
-- Table structure for `aco_map`
-- ----------------------------
DROP TABLE IF EXISTS `aco_map`;
CREATE TABLE `aco_map` (
  `acl_id` int(11) NOT NULL default '0',
  `section_value` varchar(230) NOT NULL default '0',
  `value` varchar(230) NOT NULL,
  PRIMARY KEY  (`acl_id`,`section_value`,`value`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of aco_map
-- ----------------------------
INSERT INTO `aco_map` VALUES ('5135', 'sesion', 'abrir');
INSERT INTO `aco_map` VALUES ('5135', 'sesion', 'autenticar');
INSERT INTO `aco_map` VALUES ('5135', 'sesion', 'cerrar');
INSERT INTO `aco_map` VALUES ('5135', 'sesion', 'index');
INSERT INTO `aco_map` VALUES ('5135', 'sesion', 'restringir');
INSERT INTO `aco_map` VALUES ('5136', 'ALL', 'ALL');
INSERT INTO `aco_map` VALUES ('5137', 'agenda', 'agregar');
INSERT INTO `aco_map` VALUES ('5137', 'agenda', 'editar');
INSERT INTO `aco_map` VALUES ('5137', 'agenda', 'guarda');
INSERT INTO `aco_map` VALUES ('5137', 'agenda', 'index');
INSERT INTO `aco_map` VALUES ('5137', 'agenda', 'obtenacos');
INSERT INTO `aco_map` VALUES ('5137', 'agenda', 'obtengrupos');
INSERT INTO `aco_map` VALUES ('5137', 'agenda', 'obtenusuarios');
INSERT INTO `aco_map` VALUES ('5137', 'agenda', 'ver');
INSERT INTO `aco_map` VALUES ('5138', 'accesos', 'exportar');
INSERT INTO `aco_map` VALUES ('5138', 'accesos', 'generar');
INSERT INTO `aco_map` VALUES ('5138', 'accesos', 'index');
INSERT INTO `aco_map` VALUES ('5139', 'importar', 'fotos');
INSERT INTO `aco_map` VALUES ('5139', 'importar', 'index');
INSERT INTO `aco_map` VALUES ('5140', 'historial', 'buscar');
INSERT INTO `aco_map` VALUES ('5140', 'historial', 'exportar');
INSERT INTO `aco_map` VALUES ('5140', 'historial', 'index');
INSERT INTO `aco_map` VALUES ('5140', 'historial', 'ver');
INSERT INTO `aco_map` VALUES ('5141', 'alumnos', 'buscar');
INSERT INTO `aco_map` VALUES ('5141', 'alumnos', 'importar');
INSERT INTO `aco_map` VALUES ('5141', 'alumnos', 'index');
INSERT INTO `aco_map` VALUES ('5141', 'alumnos', 'password');
INSERT INTO `aco_map` VALUES ('5141', 'alumnos', 'ver');
INSERT INTO `aco_map` VALUES ('5142', 'ciclos', 'abrir');
INSERT INTO `aco_map` VALUES ('5142', 'ciclos', 'agregar');
INSERT INTO `aco_map` VALUES ('5142', 'ciclos', 'avance');
INSERT INTO `aco_map` VALUES ('5142', 'ciclos', 'buscar');
INSERT INTO `aco_map` VALUES ('5142', 'ciclos', 'editar');
INSERT INTO `aco_map` VALUES ('5142', 'ciclos', 'eliminar');
INSERT INTO `aco_map` VALUES ('5142', 'ciclos', 'faltantes');
INSERT INTO `aco_map` VALUES ('5142', 'ciclos', 'index');
INSERT INTO `aco_map` VALUES ('5142', 'ciclos', 'simulaavance');
INSERT INTO `aco_map` VALUES ('5142', 'ciclos', 'status');
INSERT INTO `aco_map` VALUES ('5143', 'inicio', 'administrador');
INSERT INTO `aco_map` VALUES ('5143', 'inicio', 'index');
INSERT INTO `aco_map` VALUES ('5144', 'profesores', 'buscar');
INSERT INTO `aco_map` VALUES ('5144', 'profesores', 'index');
INSERT INTO `aco_map` VALUES ('5144', 'profesores', 'password');
INSERT INTO `aco_map` VALUES ('5144', 'profesores', 'ver');
INSERT INTO `aco_map` VALUES ('5145', 'personal', 'agregar');
INSERT INTO `aco_map` VALUES ('5145', 'personal', 'buscar');
INSERT INTO `aco_map` VALUES ('5145', 'personal', 'disponible');
INSERT INTO `aco_map` VALUES ('5145', 'personal', 'editar');
INSERT INTO `aco_map` VALUES ('5145', 'personal', 'eliminar');
INSERT INTO `aco_map` VALUES ('5145', 'personal', 'exportar');
INSERT INTO `aco_map` VALUES ('5145', 'personal', 'index');
INSERT INTO `aco_map` VALUES ('5145', 'personal', 'ver');
INSERT INTO `aco_map` VALUES ('5146', 'tipopersonal', 'agregar');
INSERT INTO `aco_map` VALUES ('5146', 'tipopersonal', 'buscar');
INSERT INTO `aco_map` VALUES ('5146', 'tipopersonal', 'editar');
INSERT INTO `aco_map` VALUES ('5146', 'tipopersonal', 'eliminar');
INSERT INTO `aco_map` VALUES ('5146', 'tipopersonal', 'index');
INSERT INTO `aco_map` VALUES ('5147', 'tutores', 'buscar');
INSERT INTO `aco_map` VALUES ('5147', 'tutores', 'index');
INSERT INTO `aco_map` VALUES ('5147', 'tutores', 'password');
INSERT INTO `aco_map` VALUES ('5147', 'tutores', 'ver');
INSERT INTO `aco_map` VALUES ('5148', 'usuarios', 'agregar');
INSERT INTO `aco_map` VALUES ('5148', 'usuarios', 'editar');
INSERT INTO `aco_map` VALUES ('5148', 'usuarios', 'eliminar');
INSERT INTO `aco_map` VALUES ('5148', 'usuarios', 'index');
INSERT INTO `aco_map` VALUES ('5148', 'usuarios', 'password');
INSERT INTO `aco_map` VALUES ('5148', 'usuarios', 'validarLogin');
INSERT INTO `aco_map` VALUES ('5148', 'usuarios', 'ver');
INSERT INTO `aco_map` VALUES ('5148', 'usuarios', 'verAcceso');
INSERT INTO `aco_map` VALUES ('5149', 'sistema', 'ayuda');
INSERT INTO `aco_map` VALUES ('5149', 'sistema', 'configuracion');
INSERT INTO `aco_map` VALUES ('5149', 'sistema', 'password');
INSERT INTO `aco_map` VALUES ('5149', 'sistema', 'seleccionar');
INSERT INTO `aco_map` VALUES ('5150', 'estadisticas', 'aprobacion');
INSERT INTO `aco_map` VALUES ('5150', 'estadisticas', 'aprobadas');
INSERT INTO `aco_map` VALUES ('5150', 'estadisticas', 'asistencias');
INSERT INTO `aco_map` VALUES ('5150', 'estadisticas', 'calificaciones');
INSERT INTO `aco_map` VALUES ('5150', 'estadisticas', 'index');
INSERT INTO `aco_map` VALUES ('5150', 'estadisticas', 'promedios');
INSERT INTO `aco_map` VALUES ('5151', 'es', 'dia');
INSERT INTO `aco_map` VALUES ('5151', 'es', 'exportar');
INSERT INTO `aco_map` VALUES ('5151', 'es', 'inconsistencias');
INSERT INTO `aco_map` VALUES ('5151', 'es', 'index');
INSERT INTO `aco_map` VALUES ('5152', 'periodos', 'agregar');
INSERT INTO `aco_map` VALUES ('5152', 'periodos', 'editar');
INSERT INTO `aco_map` VALUES ('5152', 'periodos', 'eliminar');
INSERT INTO `aco_map` VALUES ('5152', 'periodos', 'estadistica');
INSERT INTO `aco_map` VALUES ('5152', 'periodos', 'index');
INSERT INTO `aco_map` VALUES ('5153', 'optativas', 'alumnos');
INSERT INTO `aco_map` VALUES ('5153', 'optativas', 'avanzadas');
INSERT INTO `aco_map` VALUES ('5153', 'optativas', 'bloques');
INSERT INTO `aco_map` VALUES ('5153', 'optativas', 'configuracion');
INSERT INTO `aco_map` VALUES ('5153', 'optativas', 'cupos');
INSERT INTO `aco_map` VALUES ('5153', 'optativas', 'cursos');
INSERT INTO `aco_map` VALUES ('5153', 'optativas', 'eliminar');
INSERT INTO `aco_map` VALUES ('5153', 'optativas', 'index');
INSERT INTO `aco_map` VALUES ('5153', 'optativas', 'inscribir');
INSERT INTO `aco_map` VALUES ('5153', 'optativas', 'inscritos');
INSERT INTO `aco_map` VALUES ('5153', 'optativas', 'inscritosexportar');
INSERT INTO `aco_map` VALUES ('5153', 'optativas', 'taes');
INSERT INTO `aco_map` VALUES ('5153', 'optativas', 'taesinfo');
INSERT INTO `aco_map` VALUES ('5153', 'optativas', 'trayectoria');
INSERT INTO `aco_map` VALUES ('5153', 'optativas', 'trayectoriasexportar');
INSERT INTO `aco_map` VALUES ('5154', 'bloques', 'agregar');
INSERT INTO `aco_map` VALUES ('5154', 'bloques', 'editar');
INSERT INTO `aco_map` VALUES ('5154', 'bloques', 'eliminar');
INSERT INTO `aco_map` VALUES ('5154', 'bloques', 'eliminartodos');
INSERT INTO `aco_map` VALUES ('5154', 'bloques', 'index');
INSERT INTO `aco_map` VALUES ('5155', 'bloquesalumnos', 'agregar');
INSERT INTO `aco_map` VALUES ('5155', 'bloquesalumnos', 'cambio');
INSERT INTO `aco_map` VALUES ('5155', 'bloquesalumnos', 'eliminar');
INSERT INTO `aco_map` VALUES ('5155', 'bloquesalumnos', 'index');
INSERT INTO `aco_map` VALUES ('5156', 'importador', 'curso');
INSERT INTO `aco_map` VALUES ('5156', 'importador', 'grupocursos');
INSERT INTO `aco_map` VALUES ('5156', 'importador', 'index');
INSERT INTO `aco_map` VALUES ('5156', 'importador', 'taes');
INSERT INTO `aco_map` VALUES ('5157', 'asistencias', 'editar');
INSERT INTO `aco_map` VALUES ('5157', 'asistencias', 'eliminar');
INSERT INTO `aco_map` VALUES ('5157', 'asistencias', 'selector');
INSERT INTO `aco_map` VALUES ('5157', 'asistencias', 'ver');
INSERT INTO `aco_map` VALUES ('5158', 'calificaciones', 'editar');
INSERT INTO `aco_map` VALUES ('5158', 'calificaciones', 'eliminar');
INSERT INTO `aco_map` VALUES ('5158', 'calificaciones', 'selector');
INSERT INTO `aco_map` VALUES ('5158', 'calificaciones', 'ver');
INSERT INTO `aco_map` VALUES ('5159', 'alumnos', 'buscar');
INSERT INTO `aco_map` VALUES ('5159', 'alumnos', 'cursos');
INSERT INTO `aco_map` VALUES ('5159', 'alumnos', 'eliminar');
INSERT INTO `aco_map` VALUES ('5159', 'alumnos', 'exportar');
INSERT INTO `aco_map` VALUES ('5159', 'alumnos', 'index');
INSERT INTO `aco_map` VALUES ('5159', 'alumnos', 'info');
INSERT INTO `aco_map` VALUES ('5159', 'alumnos', 'kardex');
INSERT INTO `aco_map` VALUES ('5159', 'alumnos', 'ubicar');
INSERT INTO `aco_map` VALUES ('5159', 'alumnos', 'ver');
INSERT INTO `aco_map` VALUES ('5160', 'cursos', 'buscar');
INSERT INTO `aco_map` VALUES ('5160', 'cursos', 'editar');
INSERT INTO `aco_map` VALUES ('5160', 'cursos', 'eliminar');
INSERT INTO `aco_map` VALUES ('5160', 'cursos', 'exportar');
INSERT INTO `aco_map` VALUES ('5160', 'cursos', 'fecha');
INSERT INTO `aco_map` VALUES ('5160', 'cursos', 'grupo');
INSERT INTO `aco_map` VALUES ('5160', 'cursos', 'grupoexportar');
INSERT INTO `aco_map` VALUES ('5160', 'cursos', 'index');
INSERT INTO `aco_map` VALUES ('5160', 'cursos', 'status');
INSERT INTO `aco_map` VALUES ('5160', 'cursos', 'ver');
INSERT INTO `aco_map` VALUES ('5161', 'grupos', 'curso');
INSERT INTO `aco_map` VALUES ('5161', 'grupos', 'horario');
INSERT INTO `aco_map` VALUES ('5161', 'grupos', 'index');
INSERT INTO `aco_map` VALUES ('5161', 'grupos', 'ver');
INSERT INTO `aco_map` VALUES ('5162', 'tutores', 'buscar');
INSERT INTO `aco_map` VALUES ('5162', 'tutores', 'exportar');
INSERT INTO `aco_map` VALUES ('5162', 'tutores', 'index');
INSERT INTO `aco_map` VALUES ('5162', 'tutores', 'ver');
INSERT INTO `aco_map` VALUES ('5163', 'sistema', 'ayuda');
INSERT INTO `aco_map` VALUES ('5163', 'sistema', 'configuracion');
INSERT INTO `aco_map` VALUES ('5163', 'sistema', 'password');
INSERT INTO `aco_map` VALUES ('5163', 'sistema', 'seleccionar');
INSERT INTO `aco_map` VALUES ('5164', 'inscripcion', 'agregar');
INSERT INTO `aco_map` VALUES ('5164', 'inscripcion', 'articulo');
INSERT INTO `aco_map` VALUES ('5164', 'inscripcion', 'confirmar');
INSERT INTO `aco_map` VALUES ('5164', 'inscripcion', 'eliminar');
INSERT INTO `aco_map` VALUES ('5165', 'horarios', 'agregar');
INSERT INTO `aco_map` VALUES ('5165', 'horarios', 'editar');
INSERT INTO `aco_map` VALUES ('5165', 'horarios', 'eliminar');
INSERT INTO `aco_map` VALUES ('5165', 'horarios', 'index');
INSERT INTO `aco_map` VALUES ('5165', 'horarios', 'validar');
INSERT INTO `aco_map` VALUES ('5165', 'horarios', 'ver');
INSERT INTO `aco_map` VALUES ('5166', 'estadisticas', 'aprobacion');
INSERT INTO `aco_map` VALUES ('5166', 'estadisticas', 'aprobadas');
INSERT INTO `aco_map` VALUES ('5166', 'estadisticas', 'asistencias');
INSERT INTO `aco_map` VALUES ('5166', 'estadisticas', 'calificaciones');
INSERT INTO `aco_map` VALUES ('5166', 'estadisticas', 'index');
INSERT INTO `aco_map` VALUES ('5166', 'estadisticas', 'promedios');
INSERT INTO `aco_map` VALUES ('5167', 'profesores', 'buscar');
INSERT INTO `aco_map` VALUES ('5167', 'profesores', 'exportar');
INSERT INTO `aco_map` VALUES ('5167', 'profesores', 'horario');
INSERT INTO `aco_map` VALUES ('5167', 'profesores', 'horarioexcel');
INSERT INTO `aco_map` VALUES ('5167', 'profesores', 'index');
INSERT INTO `aco_map` VALUES ('5167', 'profesores', 'ver');
INSERT INTO `aco_map` VALUES ('5168', 'reportes', 'derechos');
INSERT INTO `aco_map` VALUES ('5168', 'reportes', 'index');
INSERT INTO `aco_map` VALUES ('5168', 'reportes', 'plantilla');
INSERT INTO `aco_map` VALUES ('5168', 'reportes', 'resumen');
INSERT INTO `aco_map` VALUES ('5169', 'inicio', 'director');
INSERT INTO `aco_map` VALUES ('5169', 'inicio', 'index');
INSERT INTO `aco_map` VALUES ('5170', 'historial', 'buscar');
INSERT INTO `aco_map` VALUES ('5170', 'historial', 'exportar');
INSERT INTO `aco_map` VALUES ('5170', 'historial', 'index');
INSERT INTO `aco_map` VALUES ('5170', 'historial', 'ver');
INSERT INTO `aco_map` VALUES ('5171', 'inicio', 'index');
INSERT INTO `aco_map` VALUES ('5171', 'inicio', 'secretario');
INSERT INTO `aco_map` VALUES ('5172', 'historial', 'buscar');
INSERT INTO `aco_map` VALUES ('5172', 'historial', 'exportar');
INSERT INTO `aco_map` VALUES ('5172', 'historial', 'index');
INSERT INTO `aco_map` VALUES ('5172', 'historial', 'ver');
INSERT INTO `aco_map` VALUES ('5173', 'alumnos', 'exportar');
INSERT INTO `aco_map` VALUES ('5173', 'alumnos', 'index');
INSERT INTO `aco_map` VALUES ('5173', 'alumnos', 'ver');
INSERT INTO `aco_map` VALUES ('5174', 'asistencias', 'selector');
INSERT INTO `aco_map` VALUES ('5175', 'calificaciones', 'selector');
INSERT INTO `aco_map` VALUES ('5176', 'grupos', 'curso');
INSERT INTO `aco_map` VALUES ('5176', 'grupos', 'horario');
INSERT INTO `aco_map` VALUES ('5176', 'grupos', 'imprimir');
INSERT INTO `aco_map` VALUES ('5176', 'grupos', 'index');
INSERT INTO `aco_map` VALUES ('5176', 'grupos', 'ver');
INSERT INTO `aco_map` VALUES ('5177', 'tutores', 'index');
INSERT INTO `aco_map` VALUES ('5177', 'tutores', 'ver');
INSERT INTO `aco_map` VALUES ('5178', 'alumnos', 'agregar');
INSERT INTO `aco_map` VALUES ('5178', 'alumnos', 'asignar');
INSERT INTO `aco_map` VALUES ('5178', 'alumnos', 'buscar');
INSERT INTO `aco_map` VALUES ('5178', 'alumnos', 'cursos');
INSERT INTO `aco_map` VALUES ('5178', 'alumnos', 'disponible');
INSERT INTO `aco_map` VALUES ('5178', 'alumnos', 'editar');
INSERT INTO `aco_map` VALUES ('5178', 'alumnos', 'eliminar');
INSERT INTO `aco_map` VALUES ('5178', 'alumnos', 'index');
INSERT INTO `aco_map` VALUES ('5178', 'alumnos', 'info');
INSERT INTO `aco_map` VALUES ('5178', 'alumnos', 'kardex');
INSERT INTO `aco_map` VALUES ('5178', 'alumnos', 'trayectoria');
INSERT INTO `aco_map` VALUES ('5179', 'cursos', 'buscar');
INSERT INTO `aco_map` VALUES ('5179', 'cursos', 'exportar');
INSERT INTO `aco_map` VALUES ('5179', 'cursos', 'index');
INSERT INTO `aco_map` VALUES ('5180', 'grupos', 'asignar');
INSERT INTO `aco_map` VALUES ('5180', 'grupos', 'index');
INSERT INTO `aco_map` VALUES ('5181', 'horarios', 'index');
INSERT INTO `aco_map` VALUES ('5181', 'horarios', 'ver');
INSERT INTO `aco_map` VALUES ('5182', 'inicio', 'index');
INSERT INTO `aco_map` VALUES ('5182', 'inicio', 'oficial');
INSERT INTO `aco_map` VALUES ('5183', 'tutores', 'agregar');
INSERT INTO `aco_map` VALUES ('5183', 'tutores', 'buscar');
INSERT INTO `aco_map` VALUES ('5183', 'tutores', 'editar');
INSERT INTO `aco_map` VALUES ('5183', 'tutores', 'eliminar');
INSERT INTO `aco_map` VALUES ('5183', 'tutores', 'exportar');
INSERT INTO `aco_map` VALUES ('5184', 'sistema', 'ayuda');
INSERT INTO `aco_map` VALUES ('5184', 'sistema', 'configuracion');
INSERT INTO `aco_map` VALUES ('5184', 'sistema', 'password');
INSERT INTO `aco_map` VALUES ('5184', 'sistema', 'seleccionar');
INSERT INTO `aco_map` VALUES ('5185', 'historial', 'buscar');
INSERT INTO `aco_map` VALUES ('5185', 'historial', 'exportar');
INSERT INTO `aco_map` VALUES ('5185', 'historial', 'index');
INSERT INTO `aco_map` VALUES ('5185', 'historial', 'ver');
INSERT INTO `aco_map` VALUES ('5186', 'estadisticas', 'aprobacion');
INSERT INTO `aco_map` VALUES ('5186', 'estadisticas', 'aprobadas');
INSERT INTO `aco_map` VALUES ('5186', 'estadisticas', 'asistencias');
INSERT INTO `aco_map` VALUES ('5186', 'estadisticas', 'calificaciones');
INSERT INTO `aco_map` VALUES ('5186', 'estadisticas', 'index');
INSERT INTO `aco_map` VALUES ('5186', 'estadisticas', 'promedios');
INSERT INTO `aco_map` VALUES ('5187', 'reportes', 'derechos');
INSERT INTO `aco_map` VALUES ('5187', 'reportes', 'index');
INSERT INTO `aco_map` VALUES ('5187', 'reportes', 'plantilla');
INSERT INTO `aco_map` VALUES ('5187', 'reportes', 'resumen');
INSERT INTO `aco_map` VALUES ('5188', 'materias', 'enlazar');
INSERT INTO `aco_map` VALUES ('5188', 'materias', 'exportar');
INSERT INTO `aco_map` VALUES ('5188', 'materias', 'index');
INSERT INTO `aco_map` VALUES ('5188', 'materias', 'series');
INSERT INTO `aco_map` VALUES ('5188', 'materias', 'ver');
INSERT INTO `aco_map` VALUES ('5189', 'asistencias', 'agregar');
INSERT INTO `aco_map` VALUES ('5189', 'asistencias', 'faltas');
INSERT INTO `aco_map` VALUES ('5189', 'asistencias', 'index');
INSERT INTO `aco_map` VALUES ('5189', 'asistencias', 'ver');
INSERT INTO `aco_map` VALUES ('5190', 'calificaciones', 'agregar');
INSERT INTO `aco_map` VALUES ('5190', 'calificaciones', 'index');
INSERT INTO `aco_map` VALUES ('5190', 'calificaciones', 'ver');
INSERT INTO `aco_map` VALUES ('5191', 'periodos', 'estadistica');
INSERT INTO `aco_map` VALUES ('5191', 'periodos', 'index');
INSERT INTO `aco_map` VALUES ('5192', 'optativas', 'index');
INSERT INTO `aco_map` VALUES ('5192', 'optativas', 'inscritos');
INSERT INTO `aco_map` VALUES ('5192', 'optativas', 'taesinfo');
INSERT INTO `aco_map` VALUES ('5192', 'optativas', 'trayectoria');
INSERT INTO `aco_map` VALUES ('5192', 'optativas', 'trayectoriasexportar');
INSERT INTO `aco_map` VALUES ('5193', 'alumnos', 'agregar');
INSERT INTO `aco_map` VALUES ('5193', 'alumnos', 'buscar');
INSERT INTO `aco_map` VALUES ('5193', 'alumnos', 'cursos');
INSERT INTO `aco_map` VALUES ('5193', 'alumnos', 'disponible');
INSERT INTO `aco_map` VALUES ('5193', 'alumnos', 'editar');
INSERT INTO `aco_map` VALUES ('5193', 'alumnos', 'index');
INSERT INTO `aco_map` VALUES ('5193', 'alumnos', 'info');
INSERT INTO `aco_map` VALUES ('5193', 'alumnos', 'kardex');
INSERT INTO `aco_map` VALUES ('5193', 'alumnos', 'trayectoria');
INSERT INTO `aco_map` VALUES ('5194', 'historial', 'buscar');
INSERT INTO `aco_map` VALUES ('5194', 'historial', 'exportar');
INSERT INTO `aco_map` VALUES ('5194', 'historial', 'index');
INSERT INTO `aco_map` VALUES ('5194', 'historial', 'ver');
INSERT INTO `aco_map` VALUES ('5195', 'asistencias', 'agregar');
INSERT INTO `aco_map` VALUES ('5195', 'asistencias', 'faltas');
INSERT INTO `aco_map` VALUES ('5195', 'asistencias', 'index');
INSERT INTO `aco_map` VALUES ('5195', 'asistencias', 'ver');
INSERT INTO `aco_map` VALUES ('5196', 'calificaciones', 'agregar');
INSERT INTO `aco_map` VALUES ('5196', 'calificaciones', 'index');
INSERT INTO `aco_map` VALUES ('5196', 'calificaciones', 'ver');
INSERT INTO `aco_map` VALUES ('5197', 'inicio', 'index');
INSERT INTO `aco_map` VALUES ('5197', 'inicio', 'secretaria');
INSERT INTO `aco_map` VALUES ('5198', 'tutores', 'agregar');
INSERT INTO `aco_map` VALUES ('5198', 'tutores', 'buscar');
INSERT INTO `aco_map` VALUES ('5198', 'tutores', 'editar');
INSERT INTO `aco_map` VALUES ('5198', 'tutores', 'eliminar');
INSERT INTO `aco_map` VALUES ('5198', 'tutores', 'exportar');
INSERT INTO `aco_map` VALUES ('5198', 'tutores', 'index');
INSERT INTO `aco_map` VALUES ('5199', 'sistema', 'ayuda');
INSERT INTO `aco_map` VALUES ('5199', 'sistema', 'configuracion');
INSERT INTO `aco_map` VALUES ('5199', 'sistema', 'password');
INSERT INTO `aco_map` VALUES ('5199', 'sistema', 'seleccionar');
INSERT INTO `aco_map` VALUES ('5200', 'reportes', 'derechos');
INSERT INTO `aco_map` VALUES ('5200', 'reportes', 'index');
INSERT INTO `aco_map` VALUES ('5200', 'reportes', 'plantilla');
INSERT INTO `aco_map` VALUES ('5200', 'reportes', 'resumen');
INSERT INTO `aco_map` VALUES ('5201', 'materias', 'enlazar');
INSERT INTO `aco_map` VALUES ('5201', 'materias', 'exportar');
INSERT INTO `aco_map` VALUES ('5201', 'materias', 'index');
INSERT INTO `aco_map` VALUES ('5201', 'materias', 'series');
INSERT INTO `aco_map` VALUES ('5201', 'materias', 'ver');
INSERT INTO `aco_map` VALUES ('5202', 'asistencias', 'agregar');
INSERT INTO `aco_map` VALUES ('5202', 'asistencias', 'editar');
INSERT INTO `aco_map` VALUES ('5202', 'asistencias', 'eliminar');
INSERT INTO `aco_map` VALUES ('5202', 'asistencias', 'index');
INSERT INTO `aco_map` VALUES ('5202', 'asistencias', 'selector');
INSERT INTO `aco_map` VALUES ('5202', 'asistencias', 'ver');
INSERT INTO `aco_map` VALUES ('5203', 'calificaciones', 'agregar');
INSERT INTO `aco_map` VALUES ('5203', 'calificaciones', 'editar');
INSERT INTO `aco_map` VALUES ('5203', 'calificaciones', 'eliminar');
INSERT INTO `aco_map` VALUES ('5203', 'calificaciones', 'index');
INSERT INTO `aco_map` VALUES ('5203', 'calificaciones', 'selector');
INSERT INTO `aco_map` VALUES ('5203', 'calificaciones', 'ver');
INSERT INTO `aco_map` VALUES ('5204', 'grupos', 'curso');
INSERT INTO `aco_map` VALUES ('5204', 'grupos', 'horario');
INSERT INTO `aco_map` VALUES ('5204', 'grupos', 'index');
INSERT INTO `aco_map` VALUES ('5204', 'grupos', 'ver');
INSERT INTO `aco_map` VALUES ('5205', 'inicio', 'index');
INSERT INTO `aco_map` VALUES ('5205', 'inicio', 'profesor');
INSERT INTO `aco_map` VALUES ('5206', 'sistema', 'ayuda');
INSERT INTO `aco_map` VALUES ('5206', 'sistema', 'configuracion');
INSERT INTO `aco_map` VALUES ('5206', 'sistema', 'password');
INSERT INTO `aco_map` VALUES ('5206', 'sistema', 'seleccionar');
INSERT INTO `aco_map` VALUES ('5207', 'historial', 'buscar');
INSERT INTO `aco_map` VALUES ('5207', 'historial', 'exportar');
INSERT INTO `aco_map` VALUES ('5207', 'historial', 'index');
INSERT INTO `aco_map` VALUES ('5207', 'historial', 'ver');
INSERT INTO `aco_map` VALUES ('5208', 'aulas', 'agregar');
INSERT INTO `aco_map` VALUES ('5208', 'aulas', 'buscar');
INSERT INTO `aco_map` VALUES ('5208', 'aulas', 'disponible');
INSERT INTO `aco_map` VALUES ('5208', 'aulas', 'editar');
INSERT INTO `aco_map` VALUES ('5208', 'aulas', 'eliminar');
INSERT INTO `aco_map` VALUES ('5208', 'aulas', 'exportar');
INSERT INTO `aco_map` VALUES ('5208', 'aulas', 'index');
INSERT INTO `aco_map` VALUES ('5209', 'cursos', 'agregar');
INSERT INTO `aco_map` VALUES ('5209', 'cursos', 'buscar');
INSERT INTO `aco_map` VALUES ('5209', 'cursos', 'editar');
INSERT INTO `aco_map` VALUES ('5209', 'cursos', 'eliminar');
INSERT INTO `aco_map` VALUES ('5209', 'cursos', 'exportar');
INSERT INTO `aco_map` VALUES ('5209', 'cursos', 'fecha');
INSERT INTO `aco_map` VALUES ('5209', 'cursos', 'grupo');
INSERT INTO `aco_map` VALUES ('5209', 'cursos', 'grupoexportar');
INSERT INTO `aco_map` VALUES ('5209', 'cursos', 'index');
INSERT INTO `aco_map` VALUES ('5209', 'cursos', 'status');
INSERT INTO `aco_map` VALUES ('5210', 'grupos', 'agregar');
INSERT INTO `aco_map` VALUES ('5210', 'grupos', 'disponible');
INSERT INTO `aco_map` VALUES ('5210', 'grupos', 'editar');
INSERT INTO `aco_map` VALUES ('5210', 'grupos', 'eliminar');
INSERT INTO `aco_map` VALUES ('5210', 'grupos', 'generar');
INSERT INTO `aco_map` VALUES ('5210', 'grupos', 'index');
INSERT INTO `aco_map` VALUES ('5211', 'horarios', 'validar');
INSERT INTO `aco_map` VALUES ('5212', 'inicio', 'index');
INSERT INTO `aco_map` VALUES ('5212', 'inicio', 'plantilla');
INSERT INTO `aco_map` VALUES ('5213', 'materias', 'academias');
INSERT INTO `aco_map` VALUES ('5213', 'materias', 'agregar');
INSERT INTO `aco_map` VALUES ('5213', 'materias', 'disponible');
INSERT INTO `aco_map` VALUES ('5213', 'materias', 'editar');
INSERT INTO `aco_map` VALUES ('5213', 'materias', 'eliminar');
INSERT INTO `aco_map` VALUES ('5213', 'materias', 'enlazar');
INSERT INTO `aco_map` VALUES ('5213', 'materias', 'exportar');
INSERT INTO `aco_map` VALUES ('5213', 'materias', 'index');
INSERT INTO `aco_map` VALUES ('5213', 'materias', 'series');
INSERT INTO `aco_map` VALUES ('5213', 'materias', 'ver');
INSERT INTO `aco_map` VALUES ('5214', 'competencias', 'obtenertipos');
INSERT INTO `aco_map` VALUES ('5215', 'profesores', 'agregar');
INSERT INTO `aco_map` VALUES ('5215', 'profesores', 'buscar');
INSERT INTO `aco_map` VALUES ('5215', 'profesores', 'disponible');
INSERT INTO `aco_map` VALUES ('5215', 'profesores', 'editar');
INSERT INTO `aco_map` VALUES ('5215', 'profesores', 'eliminar');
INSERT INTO `aco_map` VALUES ('5215', 'profesores', 'exportar');
INSERT INTO `aco_map` VALUES ('5215', 'profesores', 'horario');
INSERT INTO `aco_map` VALUES ('5215', 'profesores', 'horarioexcel');
INSERT INTO `aco_map` VALUES ('5215', 'profesores', 'index');
INSERT INTO `aco_map` VALUES ('5215', 'profesores', 'ver');
INSERT INTO `aco_map` VALUES ('5216', 'periodos', 'index');
INSERT INTO `aco_map` VALUES ('5217', 'reportes', 'plantilla');
INSERT INTO `aco_map` VALUES ('5218', 'optativas', 'cupos');
INSERT INTO `aco_map` VALUES ('5218', 'optativas', 'cursos');
INSERT INTO `aco_map` VALUES ('5218', 'optativas', 'eliminar');
INSERT INTO `aco_map` VALUES ('5218', 'optativas', 'index');
INSERT INTO `aco_map` VALUES ('5218', 'optativas', 'taes');
INSERT INTO `aco_map` VALUES ('5219', 'admin', 'index');
INSERT INTO `aco_map` VALUES ('5220', 'alumnos', 'avisos');
INSERT INTO `aco_map` VALUES ('5220', 'alumnos', 'comentarios');
INSERT INTO `aco_map` VALUES ('5220', 'alumnos', 'escolar');
INSERT INTO `aco_map` VALUES ('5220', 'alumnos', 'index');
INSERT INTO `aco_map` VALUES ('5221', 'archivo', 'descargar');
INSERT INTO `aco_map` VALUES ('5222', 'contacto', 'guardar');
INSERT INTO `aco_map` VALUES ('5222', 'contacto', 'index');
INSERT INTO `aco_map` VALUES ('5222', 'contacto', 'sugerencias');
INSERT INTO `aco_map` VALUES ('5222', 'contacto', 'ubicacion');
INSERT INTO `aco_map` VALUES ('5223', 'director', 'index');
INSERT INTO `aco_map` VALUES ('5223', 'director', 'informes');
INSERT INTO `aco_map` VALUES ('5223', 'director', 'trayectoria');
INSERT INTO `aco_map` VALUES ('5223', 'director', 'video');
INSERT INTO `aco_map` VALUES ('5224', 'escolar', 'abrir');
INSERT INTO `aco_map` VALUES ('5224', 'escolar', 'auth');
INSERT INTO `aco_map` VALUES ('5224', 'escolar', 'cerrar');
INSERT INTO `aco_map` VALUES ('5224', 'escolar', 'restringir');
INSERT INTO `aco_map` VALUES ('5225', 'nuestraprepa', 'acerca');
INSERT INTO `aco_map` VALUES ('5225', 'nuestraprepa', 'agregar_comentarios');
INSERT INTO `aco_map` VALUES ('5225', 'nuestraprepa', 'blog');
INSERT INTO `aco_map` VALUES ('5225', 'nuestraprepa', 'blog_comentarios');
INSERT INTO `aco_map` VALUES ('5225', 'nuestraprepa', 'consejo');
INSERT INTO `aco_map` VALUES ('5225', 'nuestraprepa', 'directorio');
INSERT INTO `aco_map` VALUES ('5225', 'nuestraprepa', 'index');
INSERT INTO `aco_map` VALUES ('5225', 'nuestraprepa', 'inicio');
INSERT INTO `aco_map` VALUES ('5225', 'nuestraprepa', 'iso');
INSERT INTO `aco_map` VALUES ('5225', 'nuestraprepa', 'mision');
INSERT INTO `aco_map` VALUES ('5225', 'nuestraprepa', 'normatividad');
INSERT INTO `aco_map` VALUES ('5225', 'nuestraprepa', 'organigrama');
INSERT INTO `aco_map` VALUES ('5225', 'nuestraprepa', 'transparencia');
INSERT INTO `aco_map` VALUES ('5225', 'nuestraprepa', 'vision');
INSERT INTO `aco_map` VALUES ('5226', 'servicios', 'agenda');
INSERT INTO `aco_map` VALUES ('5226', 'servicios', 'descargas');
INSERT INTO `aco_map` VALUES ('5226', 'servicios', 'documentos');
INSERT INTO `aco_map` VALUES ('5226', 'servicios', 'formatos');
INSERT INTO `aco_map` VALUES ('5226', 'servicios', 'index');
INSERT INTO `aco_map` VALUES ('5226', 'servicios', 'radio');
INSERT INTO `aco_map` VALUES ('5227', 'sugerencias', 'eliminar');
INSERT INTO `aco_map` VALUES ('5227', 'sugerencias', 'historial');
INSERT INTO `aco_map` VALUES ('5227', 'sugerencias', 'index');
INSERT INTO `aco_map` VALUES ('5227', 'sugerencias', 'responder');
INSERT INTO `aco_map` VALUES ('5227', 'sugerencias', 'sugerencia');
INSERT INTO `aco_map` VALUES ('5228', 'archivo', 'actualizar');
INSERT INTO `aco_map` VALUES ('5228', 'archivo', 'cambiastatus');
INSERT INTO `aco_map` VALUES ('5228', 'archivo', 'descargar');
INSERT INTO `aco_map` VALUES ('5228', 'archivo', 'editar');
INSERT INTO `aco_map` VALUES ('5228', 'archivo', 'eliminar');
INSERT INTO `aco_map` VALUES ('5228', 'archivo', 'guardar');
INSERT INTO `aco_map` VALUES ('5228', 'archivo', 'index');
INSERT INTO `aco_map` VALUES ('5228', 'archivo', 'nuevo');
INSERT INTO `aco_map` VALUES ('5229', 'blog', 'gestor');
INSERT INTO `aco_map` VALUES ('5229', 'blog', 'index');
INSERT INTO `aco_map` VALUES ('5230', 'categoriasdescargas', 'actualizar');
INSERT INTO `aco_map` VALUES ('5230', 'categoriasdescargas', 'editar');
INSERT INTO `aco_map` VALUES ('5230', 'categoriasdescargas', 'eliminar');
INSERT INTO `aco_map` VALUES ('5230', 'categoriasdescargas', 'gestor');
INSERT INTO `aco_map` VALUES ('5230', 'categoriasdescargas', 'guardar');
INSERT INTO `aco_map` VALUES ('5230', 'categoriasdescargas', 'index');
INSERT INTO `aco_map` VALUES ('5230', 'categoriasdescargas', 'nuevo');
INSERT INTO `aco_map` VALUES ('5231', 'categoriasmultimedia', 'actualizar');
INSERT INTO `aco_map` VALUES ('5231', 'categoriasmultimedia', 'editar');
INSERT INTO `aco_map` VALUES ('5231', 'categoriasmultimedia', 'eliminar');
INSERT INTO `aco_map` VALUES ('5231', 'categoriasmultimedia', 'gestor');
INSERT INTO `aco_map` VALUES ('5231', 'categoriasmultimedia', 'guardar');
INSERT INTO `aco_map` VALUES ('5231', 'categoriasmultimedia', 'index');
INSERT INTO `aco_map` VALUES ('5231', 'categoriasmultimedia', 'nuevo');
INSERT INTO `aco_map` VALUES ('5232', 'contenido', 'cambiaStatus');
INSERT INTO `aco_map` VALUES ('5232', 'contenido', 'editar');
INSERT INTO `aco_map` VALUES ('5232', 'contenido', 'gestor');
INSERT INTO `aco_map` VALUES ('5232', 'contenido', 'guardar');
INSERT INTO `aco_map` VALUES ('5232', 'contenido', 'index');
INSERT INTO `aco_map` VALUES ('5233', 'controlescolar', 'actualizar');
INSERT INTO `aco_map` VALUES ('5233', 'controlescolar', 'borradores');
INSERT INTO `aco_map` VALUES ('5233', 'controlescolar', 'combo');
INSERT INTO `aco_map` VALUES ('5233', 'controlescolar', 'eliminar_notificacion');
INSERT INTO `aco_map` VALUES ('5233', 'controlescolar', 'enviados');
INSERT INTO `aco_map` VALUES ('5233', 'controlescolar', 'gestor');
INSERT INTO `aco_map` VALUES ('5233', 'controlescolar', 'guardar');
INSERT INTO `aco_map` VALUES ('5233', 'controlescolar', 'index');
INSERT INTO `aco_map` VALUES ('5233', 'controlescolar', 'notificacion');
INSERT INTO `aco_map` VALUES ('5233', 'controlescolar', 'reenviar');
INSERT INTO `aco_map` VALUES ('5234', 'descargas', 'gestor');
INSERT INTO `aco_map` VALUES ('5234', 'descargas', 'index');
INSERT INTO `aco_map` VALUES ('5235', 'mmf', 'actualizar');
INSERT INTO `aco_map` VALUES ('5235', 'mmf', 'cambiastatus');
INSERT INTO `aco_map` VALUES ('5235', 'mmf', 'editar');
INSERT INTO `aco_map` VALUES ('5235', 'mmf', 'eliminar');
INSERT INTO `aco_map` VALUES ('5235', 'mmf', 'guardar');
INSERT INTO `aco_map` VALUES ('5235', 'mmf', 'index');
INSERT INTO `aco_map` VALUES ('5235', 'mmf', 'nuevo');
INSERT INTO `aco_map` VALUES ('5235', 'mmf', 'vista_previa');
INSERT INTO `aco_map` VALUES ('5236', 'modulo', 'cambiastatus');
INSERT INTO `aco_map` VALUES ('5236', 'modulo', 'editar');
INSERT INTO `aco_map` VALUES ('5236', 'modulo', 'gestor');
INSERT INTO `aco_map` VALUES ('5236', 'modulo', 'guardar');
INSERT INTO `aco_map` VALUES ('5236', 'modulo', 'index');
INSERT INTO `aco_map` VALUES ('5237', 'multimedia', 'gestor');
INSERT INTO `aco_map` VALUES ('5237', 'multimedia', 'index');
INSERT INTO `aco_map` VALUES ('5238', 'post', 'actualizar');
INSERT INTO `aco_map` VALUES ('5238', 'post', 'cambiaStatus');
INSERT INTO `aco_map` VALUES ('5238', 'post', 'cambiaStatusPost');
INSERT INTO `aco_map` VALUES ('5238', 'post', 'editar');
INSERT INTO `aco_map` VALUES ('5238', 'post', 'eliminar');
INSERT INTO `aco_map` VALUES ('5238', 'post', 'eliminarPost');
INSERT INTO `aco_map` VALUES ('5238', 'post', 'guardar');
INSERT INTO `aco_map` VALUES ('5238', 'post', 'index');
INSERT INTO `aco_map` VALUES ('5238', 'post', 'nuevo');
INSERT INTO `aco_map` VALUES ('5238', 'post', 'vista_previa');
INSERT INTO `aco_map` VALUES ('5239', 'texto', 'editar');
INSERT INTO `aco_map` VALUES ('5239', 'texto', 'guardar');
INSERT INTO `aco_map` VALUES ('5239', 'texto', 'index');
INSERT INTO `aco_map` VALUES ('5240', 'escolar', 'accesos');
INSERT INTO `aco_map` VALUES ('5240', 'escolar', 'agenda');
INSERT INTO `aco_map` VALUES ('5240', 'escolar', 'asistencias');
INSERT INTO `aco_map` VALUES ('5240', 'escolar', 'calificaciones');
INSERT INTO `aco_map` VALUES ('5240', 'escolar', 'ficha');
INSERT INTO `aco_map` VALUES ('5240', 'escolar', 'horario');
INSERT INTO `aco_map` VALUES ('5240', 'escolar', 'index');
INSERT INTO `aco_map` VALUES ('5240', 'escolar', 'inicio');
INSERT INTO `aco_map` VALUES ('5240', 'escolar', 'kardex');
INSERT INTO `aco_map` VALUES ('5240', 'escolar', 'obtenAsistencias');
INSERT INTO `aco_map` VALUES ('5240', 'escolar', 'obtenCalificaciones');
INSERT INTO `aco_map` VALUES ('5240', 'escolar', 'optativas');
INSERT INTO `aco_map` VALUES ('5240', 'escolar', 'password');
INSERT INTO `aco_map` VALUES ('5240', 'escolar', 'pdf');
INSERT INTO `aco_map` VALUES ('5240', 'escolar', 'taes');
INSERT INTO `aco_map` VALUES ('5240', 'escolar', 'ver_registro');

-- ----------------------------
-- Table structure for `aco_sections`
-- ----------------------------
DROP TABLE IF EXISTS `aco_sections`;
CREATE TABLE `aco_sections` (
  `id` int(11) NOT NULL default '0',
  `value` varchar(230) NOT NULL,
  `order_value` int(11) NOT NULL default '0',
  `name` varchar(230) NOT NULL,
  `hidden` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `value_aco_sections` (`value`),
  KEY `hidden_aco_sections` (`hidden`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of aco_sections
-- ----------------------------
INSERT INTO `aco_sections` VALUES ('2565', 'ALL', '0', 'ALL', '0');
INSERT INTO `aco_sections` VALUES ('2566', 'sesion', '1', 'sesion', '0');
INSERT INTO `aco_sections` VALUES ('2567', 'inicio', '2', 'inicio', '0');
INSERT INTO `aco_sections` VALUES ('2568', 'aulas', '3', 'aulas', '0');
INSERT INTO `aco_sections` VALUES ('2569', 'alumnos', '4', 'alumnos', '0');
INSERT INTO `aco_sections` VALUES ('2570', 'accesos', '5', 'accesos', '0');
INSERT INTO `aco_sections` VALUES ('2571', 'importar', '6', 'importar', '0');
INSERT INTO `aco_sections` VALUES ('2572', 'asistencias', '7', 'asistencias', '0');
INSERT INTO `aco_sections` VALUES ('2573', 'calificaciones', '8', 'calificaciones', '0');
INSERT INTO `aco_sections` VALUES ('2574', 'parciales', '9', 'parciales', '0');
INSERT INTO `aco_sections` VALUES ('2575', 'tutores', '10', 'tutores', '0');
INSERT INTO `aco_sections` VALUES ('2576', 'grupos', '11', 'grupos', '0');
INSERT INTO `aco_sections` VALUES ('2577', 'materias', '12', 'materias', '0');
INSERT INTO `aco_sections` VALUES ('2578', 'competencias', '13', 'competencias', '0');
INSERT INTO `aco_sections` VALUES ('2579', 'profesores', '14', 'profesores', '0');
INSERT INTO `aco_sections` VALUES ('2580', 'personal', '15', 'personal', '0');
INSERT INTO `aco_sections` VALUES ('2581', 'tipopersonal', '16', 'tipopersonal', '0');
INSERT INTO `aco_sections` VALUES ('2582', 'cursos', '17', 'cursos', '0');
INSERT INTO `aco_sections` VALUES ('2583', 'horarios', '18', 'horarios', '0');
INSERT INTO `aco_sections` VALUES ('2584', 'agenda', '19', 'agenda', '0');
INSERT INTO `aco_sections` VALUES ('2585', 'ciclos', '20', 'ciclos', '0');
INSERT INTO `aco_sections` VALUES ('2586', 'usuarios', '21', 'usuarios', '0');
INSERT INTO `aco_sections` VALUES ('2587', 'sistema', '22', 'sistema', '0');
INSERT INTO `aco_sections` VALUES ('2588', 'inscripcion', '23', 'inscripcion', '0');
INSERT INTO `aco_sections` VALUES ('2589', 'historial', '24', 'historial', '0');
INSERT INTO `aco_sections` VALUES ('2590', 'estadisticas', '25', 'estadisticas', '0');
INSERT INTO `aco_sections` VALUES ('2591', 'es', '26', 'es', '0');
INSERT INTO `aco_sections` VALUES ('2592', 'periodos', '27', 'periodos', '0');
INSERT INTO `aco_sections` VALUES ('2593', 'optativas', '28', 'optativas', '0');
INSERT INTO `aco_sections` VALUES ('2594', 'bloques', '29', 'bloques', '0');
INSERT INTO `aco_sections` VALUES ('2595', 'bloquesalumnos', '30', 'bloquesalumnos', '0');
INSERT INTO `aco_sections` VALUES ('2596', 'reportes', '31', 'reportes', '0');
INSERT INTO `aco_sections` VALUES ('2597', 'importador', '32', 'importador', '0');
INSERT INTO `aco_sections` VALUES ('2598', 'admin', '33', 'admin', '0');
INSERT INTO `aco_sections` VALUES ('2599', 'archivo', '34', 'archivo', '0');
INSERT INTO `aco_sections` VALUES ('2600', 'blog', '35', 'blog', '0');
INSERT INTO `aco_sections` VALUES ('2601', 'categoriasdescargas', '36', 'categoriasdescargas', '0');
INSERT INTO `aco_sections` VALUES ('2602', 'categoriasmultimedia', '37', 'categoriasmultimedia', '0');
INSERT INTO `aco_sections` VALUES ('2603', 'contacto', '38', 'contacto', '0');
INSERT INTO `aco_sections` VALUES ('2604', 'contenido', '39', 'contenido', '0');
INSERT INTO `aco_sections` VALUES ('2605', 'director', '40', 'director', '0');
INSERT INTO `aco_sections` VALUES ('2606', 'descargas', '41', 'descargas', '0');
INSERT INTO `aco_sections` VALUES ('2607', 'modulo', '42', 'modulo', '0');
INSERT INTO `aco_sections` VALUES ('2608', 'mmf', '43', 'mmf', '0');
INSERT INTO `aco_sections` VALUES ('2609', 'multimedia', '44', 'multimedia', '0');
INSERT INTO `aco_sections` VALUES ('2610', 'nuestraprepa', '45', 'nuestraprepa', '0');
INSERT INTO `aco_sections` VALUES ('2611', 'post', '46', 'post', '0');
INSERT INTO `aco_sections` VALUES ('2612', 'servicios', '47', 'servicios', '0');
INSERT INTO `aco_sections` VALUES ('2613', 'sugerencias', '48', 'sugerencias', '0');
INSERT INTO `aco_sections` VALUES ('2614', 'texto', '49', 'texto', '0');
INSERT INTO `aco_sections` VALUES ('2615', 'escolar', '50', 'escolar', '0');
INSERT INTO `aco_sections` VALUES ('2616', 'controlescolar', '51', 'controlescolar', '0');

-- ----------------------------
-- Table structure for `aco_sections_seq`
-- ----------------------------
DROP TABLE IF EXISTS `aco_sections_seq`;
CREATE TABLE `aco_sections_seq` (
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of aco_sections_seq
-- ----------------------------
INSERT INTO `aco_sections_seq` VALUES ('2616');

-- ----------------------------
-- Table structure for `aco_seq`
-- ----------------------------
DROP TABLE IF EXISTS `aco_seq`;
CREATE TABLE `aco_seq` (
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of aco_seq
-- ----------------------------
INSERT INTO `aco_seq` VALUES ('16673');

-- ----------------------------
-- Table structure for `aro`
-- ----------------------------
DROP TABLE IF EXISTS `aro`;
CREATE TABLE `aro` (
  `id` int(11) NOT NULL default '0',
  `section_value` varchar(240) NOT NULL default '0',
  `value` varchar(240) NOT NULL,
  `order_value` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL,
  `hidden` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `section_value_value_aro` (`section_value`,`value`),
  KEY `hidden_aro` (`hidden`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of aro
-- ----------------------------
INSERT INTO `aro` VALUES ('1539', 'usuarios', 'anonimo', '0', 'anonimo', '0');
INSERT INTO `aro` VALUES ('1540', 'usuarios', 'root', '0', 'root', '0');
INSERT INTO `aro` VALUES ('1541', 'usuarios', 'admin', '0', 'admin', '0');
INSERT INTO `aro` VALUES ('1542', 'usuarios', 'claudia.fregoso', '0', 'claudia.fregoso', '0');
INSERT INTO `aro` VALUES ('1543', 'usuarios', 'josep', '0', 'josep', '0');
INSERT INTO `aro` VALUES ('1544', 'usuarios', 'director', '0', 'director', '0');
INSERT INTO `aro` VALUES ('1545', 'usuarios', 'fperez', '0', 'fperez', '0');
INSERT INTO `aro` VALUES ('1546', 'usuarios', 'lorena', '0', 'lorena', '0');
INSERT INTO `aro` VALUES ('1547', 'usuarios', 'secretario', '0', 'secretario', '0');
INSERT INTO `aro` VALUES ('1548', 'usuarios', 'armida.lara', '0', 'armida.lara', '0');
INSERT INTO `aro` VALUES ('1549', 'usuarios', 'oficial', '0', 'oficial', '0');
INSERT INTO `aro` VALUES ('1550', 'usuarios', 'adela.camacho', '0', 'adela.camacho', '0');
INSERT INTO `aro` VALUES ('1551', 'usuarios', 'blanca.zuno', '0', 'blanca.zuno', '0');
INSERT INTO `aro` VALUES ('1552', 'usuarios', 'plantilla', '0', 'plantilla', '0');
INSERT INTO `aro` VALUES ('1553', 'usuarios', 'gianelli', '0', 'gianelli', '0');
INSERT INTO `aro` VALUES ('1554', 'usuarios', 'Antonio', '0', 'Antonio', '0');
INSERT INTO `aro` VALUES ('1555', 'usuarios', 'patricia.mendoza', '0', 'patricia.mendoza', '0');
INSERT INTO `aro` VALUES ('1556', 'usuarios', 'magdalena.vzqz', '0', 'magdalena.vzqz', '0');
INSERT INTO `aro` VALUES ('1557', 'usuarios', 'gloria.serrano', '0', 'gloria.serrano', '0');
INSERT INTO `aro` VALUES ('1558', 'usuarios', 'alma.gutierrez', '0', 'alma.gutierrez', '0');
INSERT INTO `aro` VALUES ('1559', 'usuarios', 'mago.mendez', '0', 'mago.mendez', '0');
INSERT INTO `aro` VALUES ('1560', 'usuarios', 'griselda.gzlz', '0', 'griselda.gzlz', '0');
INSERT INTO `aro` VALUES ('1561', 'usuarios', 'alma.leon', '0', 'alma.leon', '0');
INSERT INTO `aro` VALUES ('1562', 'usuarios', 'maria.fausto', '0', 'maria.fausto', '0');
INSERT INTO `aro` VALUES ('1563', 'usuarios', 'esther.suarez', '0', 'esther.suarez', '0');
INSERT INTO `aro` VALUES ('1564', 'usuarios', 'laura.diaz', '0', 'laura.diaz', '0');
INSERT INTO `aro` VALUES ('1565', 'usuarios', 'dolores.lngrc', '0', 'dolores.lngrc', '0');
INSERT INTO `aro` VALUES ('1566', 'usuarios', 'magdalena.ponce', '0', 'magdalena.ponce', '0');
INSERT INTO `aro` VALUES ('1567', 'usuarios', 'griselda.qui', '0', 'griselda.qui', '0');
INSERT INTO `aro` VALUES ('1568', 'usuarios', 'secretaria', '0', 'secretaria', '0');
INSERT INTO `aro` VALUES ('1569', 'usuarios', 'sec', '0', 'sec', '0');
INSERT INTO `aro` VALUES ('1570', 'usuarios', 'visitante', '0', 'visitante', '0');
INSERT INTO `aro` VALUES ('1571', 'usuarios', 'webmaster', '0', 'webmaster', '0');
INSERT INTO `aro` VALUES ('1572', 'usuarios', 'alumno', '0', 'alumno', '0');
INSERT INTO `aro` VALUES ('1573', 'usuarios', 'tutor', '0', 'tutor', '0');

-- ----------------------------
-- Table structure for `aro_groups`
-- ----------------------------
DROP TABLE IF EXISTS `aro_groups`;
CREATE TABLE `aro_groups` (
  `id` int(11) NOT NULL default '0',
  `parent_id` int(11) NOT NULL default '0',
  `lft` int(11) NOT NULL default '0',
  `rgt` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`,`value`),
  UNIQUE KEY `value_aro_groups` (`value`),
  KEY `parent_id_aro_groups` (`parent_id`),
  KEY `lft_rgt_aro_groups` (`lft`,`rgt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of aro_groups
-- ----------------------------
INSERT INTO `aro_groups` VALUES ('945', '0', '1', '34', 'usuarios', 'usuarios');
INSERT INTO `aro_groups` VALUES ('946', '945', '2', '3', 'root', 'root');
INSERT INTO `aro_groups` VALUES ('947', '945', '4', '5', 'administradores', 'administradores');
INSERT INTO `aro_groups` VALUES ('948', '945', '6', '11', 'direccion', 'direccion');
INSERT INTO `aro_groups` VALUES ('949', '948', '7', '8', 'director', 'director');
INSERT INTO `aro_groups` VALUES ('950', '948', '9', '10', 'secretario', 'secretario');
INSERT INTO `aro_groups` VALUES ('951', '945', '12', '17', 'escolar', 'escolar');
INSERT INTO `aro_groups` VALUES ('952', '951', '13', '14', 'oficial', 'oficial');
INSERT INTO `aro_groups` VALUES ('953', '951', '15', '16', 'secretarias', 'secretarias');
INSERT INTO `aro_groups` VALUES ('954', '945', '18', '19', 'profesores', 'profesores');
INSERT INTO `aro_groups` VALUES ('955', '945', '20', '21', 'plantilla', 'plantilla');
INSERT INTO `aro_groups` VALUES ('956', '945', '22', '33', 'web', 'web');
INSERT INTO `aro_groups` VALUES ('957', '956', '23', '26', 'webmaster', 'webmaster');
INSERT INTO `aro_groups` VALUES ('958', '957', '24', '25', 'editores', 'editores');
INSERT INTO `aro_groups` VALUES ('959', '956', '27', '32', 'wescolar', 'wescolar');
INSERT INTO `aro_groups` VALUES ('960', '959', '28', '29', 'alumnos', 'alumnos');
INSERT INTO `aro_groups` VALUES ('961', '959', '30', '31', 'tutores', 'tutores');

-- ----------------------------
-- Table structure for `aro_groups_id_seq`
-- ----------------------------
DROP TABLE IF EXISTS `aro_groups_id_seq`;
CREATE TABLE `aro_groups_id_seq` (
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of aro_groups_id_seq
-- ----------------------------
INSERT INTO `aro_groups_id_seq` VALUES ('961');

-- ----------------------------
-- Table structure for `aro_groups_map`
-- ----------------------------
DROP TABLE IF EXISTS `aro_groups_map`;
CREATE TABLE `aro_groups_map` (
  `acl_id` int(11) NOT NULL default '0',
  `group_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`acl_id`,`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of aro_groups_map
-- ----------------------------
INSERT INTO `aro_groups_map` VALUES ('5135', '945');
INSERT INTO `aro_groups_map` VALUES ('5136', '946');
INSERT INTO `aro_groups_map` VALUES ('5137', '947');
INSERT INTO `aro_groups_map` VALUES ('5138', '947');
INSERT INTO `aro_groups_map` VALUES ('5139', '947');
INSERT INTO `aro_groups_map` VALUES ('5140', '947');
INSERT INTO `aro_groups_map` VALUES ('5141', '947');
INSERT INTO `aro_groups_map` VALUES ('5142', '947');
INSERT INTO `aro_groups_map` VALUES ('5143', '947');
INSERT INTO `aro_groups_map` VALUES ('5144', '947');
INSERT INTO `aro_groups_map` VALUES ('5145', '947');
INSERT INTO `aro_groups_map` VALUES ('5146', '947');
INSERT INTO `aro_groups_map` VALUES ('5147', '947');
INSERT INTO `aro_groups_map` VALUES ('5148', '947');
INSERT INTO `aro_groups_map` VALUES ('5149', '947');
INSERT INTO `aro_groups_map` VALUES ('5150', '947');
INSERT INTO `aro_groups_map` VALUES ('5151', '947');
INSERT INTO `aro_groups_map` VALUES ('5152', '947');
INSERT INTO `aro_groups_map` VALUES ('5153', '947');
INSERT INTO `aro_groups_map` VALUES ('5154', '947');
INSERT INTO `aro_groups_map` VALUES ('5155', '947');
INSERT INTO `aro_groups_map` VALUES ('5156', '947');
INSERT INTO `aro_groups_map` VALUES ('5157', '948');
INSERT INTO `aro_groups_map` VALUES ('5158', '948');
INSERT INTO `aro_groups_map` VALUES ('5159', '948');
INSERT INTO `aro_groups_map` VALUES ('5160', '948');
INSERT INTO `aro_groups_map` VALUES ('5161', '948');
INSERT INTO `aro_groups_map` VALUES ('5162', '948');
INSERT INTO `aro_groups_map` VALUES ('5163', '948');
INSERT INTO `aro_groups_map` VALUES ('5164', '948');
INSERT INTO `aro_groups_map` VALUES ('5165', '948');
INSERT INTO `aro_groups_map` VALUES ('5166', '948');
INSERT INTO `aro_groups_map` VALUES ('5167', '948');
INSERT INTO `aro_groups_map` VALUES ('5168', '948');
INSERT INTO `aro_groups_map` VALUES ('5169', '949');
INSERT INTO `aro_groups_map` VALUES ('5170', '949');
INSERT INTO `aro_groups_map` VALUES ('5171', '950');
INSERT INTO `aro_groups_map` VALUES ('5172', '950');
INSERT INTO `aro_groups_map` VALUES ('5173', '951');
INSERT INTO `aro_groups_map` VALUES ('5174', '951');
INSERT INTO `aro_groups_map` VALUES ('5175', '951');
INSERT INTO `aro_groups_map` VALUES ('5176', '951');
INSERT INTO `aro_groups_map` VALUES ('5177', '951');
INSERT INTO `aro_groups_map` VALUES ('5178', '952');
INSERT INTO `aro_groups_map` VALUES ('5179', '952');
INSERT INTO `aro_groups_map` VALUES ('5180', '952');
INSERT INTO `aro_groups_map` VALUES ('5181', '952');
INSERT INTO `aro_groups_map` VALUES ('5182', '952');
INSERT INTO `aro_groups_map` VALUES ('5183', '952');
INSERT INTO `aro_groups_map` VALUES ('5184', '952');
INSERT INTO `aro_groups_map` VALUES ('5185', '952');
INSERT INTO `aro_groups_map` VALUES ('5186', '952');
INSERT INTO `aro_groups_map` VALUES ('5187', '952');
INSERT INTO `aro_groups_map` VALUES ('5188', '952');
INSERT INTO `aro_groups_map` VALUES ('5189', '952');
INSERT INTO `aro_groups_map` VALUES ('5190', '952');
INSERT INTO `aro_groups_map` VALUES ('5191', '952');
INSERT INTO `aro_groups_map` VALUES ('5192', '952');
INSERT INTO `aro_groups_map` VALUES ('5193', '953');
INSERT INTO `aro_groups_map` VALUES ('5194', '953');
INSERT INTO `aro_groups_map` VALUES ('5195', '953');
INSERT INTO `aro_groups_map` VALUES ('5196', '953');
INSERT INTO `aro_groups_map` VALUES ('5197', '953');
INSERT INTO `aro_groups_map` VALUES ('5198', '953');
INSERT INTO `aro_groups_map` VALUES ('5199', '953');
INSERT INTO `aro_groups_map` VALUES ('5200', '953');
INSERT INTO `aro_groups_map` VALUES ('5201', '953');
INSERT INTO `aro_groups_map` VALUES ('5202', '954');
INSERT INTO `aro_groups_map` VALUES ('5203', '954');
INSERT INTO `aro_groups_map` VALUES ('5204', '954');
INSERT INTO `aro_groups_map` VALUES ('5205', '954');
INSERT INTO `aro_groups_map` VALUES ('5206', '955');
INSERT INTO `aro_groups_map` VALUES ('5207', '955');
INSERT INTO `aro_groups_map` VALUES ('5208', '955');
INSERT INTO `aro_groups_map` VALUES ('5209', '955');
INSERT INTO `aro_groups_map` VALUES ('5210', '955');
INSERT INTO `aro_groups_map` VALUES ('5211', '955');
INSERT INTO `aro_groups_map` VALUES ('5212', '955');
INSERT INTO `aro_groups_map` VALUES ('5213', '955');
INSERT INTO `aro_groups_map` VALUES ('5214', '955');
INSERT INTO `aro_groups_map` VALUES ('5215', '955');
INSERT INTO `aro_groups_map` VALUES ('5216', '955');
INSERT INTO `aro_groups_map` VALUES ('5217', '955');
INSERT INTO `aro_groups_map` VALUES ('5218', '955');
INSERT INTO `aro_groups_map` VALUES ('5219', '956');
INSERT INTO `aro_groups_map` VALUES ('5220', '956');
INSERT INTO `aro_groups_map` VALUES ('5221', '956');
INSERT INTO `aro_groups_map` VALUES ('5222', '956');
INSERT INTO `aro_groups_map` VALUES ('5223', '956');
INSERT INTO `aro_groups_map` VALUES ('5224', '956');
INSERT INTO `aro_groups_map` VALUES ('5225', '956');
INSERT INTO `aro_groups_map` VALUES ('5226', '956');
INSERT INTO `aro_groups_map` VALUES ('5227', '957');
INSERT INTO `aro_groups_map` VALUES ('5228', '957');
INSERT INTO `aro_groups_map` VALUES ('5229', '957');
INSERT INTO `aro_groups_map` VALUES ('5230', '957');
INSERT INTO `aro_groups_map` VALUES ('5231', '957');
INSERT INTO `aro_groups_map` VALUES ('5232', '957');
INSERT INTO `aro_groups_map` VALUES ('5233', '957');
INSERT INTO `aro_groups_map` VALUES ('5234', '957');
INSERT INTO `aro_groups_map` VALUES ('5235', '957');
INSERT INTO `aro_groups_map` VALUES ('5236', '957');
INSERT INTO `aro_groups_map` VALUES ('5237', '957');
INSERT INTO `aro_groups_map` VALUES ('5238', '957');
INSERT INTO `aro_groups_map` VALUES ('5239', '957');
INSERT INTO `aro_groups_map` VALUES ('5240', '959');

-- ----------------------------
-- Table structure for `aro_map`
-- ----------------------------
DROP TABLE IF EXISTS `aro_map`;
CREATE TABLE `aro_map` (
  `acl_id` int(11) NOT NULL default '0',
  `section_value` varchar(230) NOT NULL default '0',
  `value` varchar(230) NOT NULL,
  PRIMARY KEY  (`acl_id`,`section_value`,`value`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of aro_map
-- ----------------------------

-- ----------------------------
-- Table structure for `aro_sections`
-- ----------------------------
DROP TABLE IF EXISTS `aro_sections`;
CREATE TABLE `aro_sections` (
  `id` int(11) NOT NULL default '0',
  `value` varchar(230) NOT NULL,
  `order_value` int(11) NOT NULL default '0',
  `name` varchar(230) NOT NULL,
  `hidden` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `value_aro_sections` (`value`),
  KEY `hidden_aro_sections` (`hidden`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of aro_sections
-- ----------------------------
INSERT INTO `aro_sections` VALUES ('65', 'usuarios', '0', 'usuarios', '0');

-- ----------------------------
-- Table structure for `aro_sections_seq`
-- ----------------------------
DROP TABLE IF EXISTS `aro_sections_seq`;
CREATE TABLE `aro_sections_seq` (
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of aro_sections_seq
-- ----------------------------
INSERT INTO `aro_sections_seq` VALUES ('65');

-- ----------------------------
-- Table structure for `aro_seq`
-- ----------------------------
DROP TABLE IF EXISTS `aro_seq`;
CREATE TABLE `aro_seq` (
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of aro_seq
-- ----------------------------
INSERT INTO `aro_seq` VALUES ('1573');

-- ----------------------------
-- Table structure for `axo`
-- ----------------------------
DROP TABLE IF EXISTS `axo`;
CREATE TABLE `axo` (
  `id` int(11) NOT NULL default '0',
  `section_value` varchar(240) NOT NULL default '0',
  `value` varchar(240) NOT NULL,
  `order_value` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL,
  `hidden` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `section_value_value_axo` (`section_value`,`value`),
  KEY `hidden_axo` (`hidden`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of axo
-- ----------------------------

-- ----------------------------
-- Table structure for `axo_groups`
-- ----------------------------
DROP TABLE IF EXISTS `axo_groups`;
CREATE TABLE `axo_groups` (
  `id` int(11) NOT NULL default '0',
  `parent_id` int(11) NOT NULL default '0',
  `lft` int(11) NOT NULL default '0',
  `rgt` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`,`value`),
  UNIQUE KEY `value_axo_groups` (`value`),
  KEY `parent_id_axo_groups` (`parent_id`),
  KEY `lft_rgt_axo_groups` (`lft`,`rgt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of axo_groups
-- ----------------------------

-- ----------------------------
-- Table structure for `axo_groups_map`
-- ----------------------------
DROP TABLE IF EXISTS `axo_groups_map`;
CREATE TABLE `axo_groups_map` (
  `acl_id` int(11) NOT NULL default '0',
  `group_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`acl_id`,`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of axo_groups_map
-- ----------------------------

-- ----------------------------
-- Table structure for `axo_map`
-- ----------------------------
DROP TABLE IF EXISTS `axo_map`;
CREATE TABLE `axo_map` (
  `acl_id` int(11) NOT NULL default '0',
  `section_value` varchar(230) NOT NULL default '0',
  `value` varchar(230) NOT NULL,
  PRIMARY KEY  (`acl_id`,`section_value`,`value`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of axo_map
-- ----------------------------

-- ----------------------------
-- Table structure for `axo_sections`
-- ----------------------------
DROP TABLE IF EXISTS `axo_sections`;
CREATE TABLE `axo_sections` (
  `id` int(11) NOT NULL default '0',
  `value` varchar(230) NOT NULL,
  `order_value` int(11) NOT NULL default '0',
  `name` varchar(230) NOT NULL,
  `hidden` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `value_axo_sections` (`value`),
  KEY `hidden_axo_sections` (`hidden`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of axo_sections
-- ----------------------------

-- ----------------------------
-- Table structure for `groups_aro_map`
-- ----------------------------
DROP TABLE IF EXISTS `groups_aro_map`;
CREATE TABLE `groups_aro_map` (
  `group_id` int(11) NOT NULL default '0',
  `aro_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`group_id`,`aro_id`),
  KEY `aro_id` (`aro_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of groups_aro_map
-- ----------------------------
INSERT INTO `groups_aro_map` VALUES ('945', '1539');
INSERT INTO `groups_aro_map` VALUES ('946', '1540');
INSERT INTO `groups_aro_map` VALUES ('947', '1541');
INSERT INTO `groups_aro_map` VALUES ('947', '1542');
INSERT INTO `groups_aro_map` VALUES ('949', '1543');
INSERT INTO `groups_aro_map` VALUES ('949', '1544');
INSERT INTO `groups_aro_map` VALUES ('950', '1545');
INSERT INTO `groups_aro_map` VALUES ('950', '1546');
INSERT INTO `groups_aro_map` VALUES ('950', '1547');
INSERT INTO `groups_aro_map` VALUES ('952', '1548');
INSERT INTO `groups_aro_map` VALUES ('952', '1549');
INSERT INTO `groups_aro_map` VALUES ('953', '1555');
INSERT INTO `groups_aro_map` VALUES ('953', '1556');
INSERT INTO `groups_aro_map` VALUES ('953', '1557');
INSERT INTO `groups_aro_map` VALUES ('953', '1558');
INSERT INTO `groups_aro_map` VALUES ('953', '1559');
INSERT INTO `groups_aro_map` VALUES ('953', '1560');
INSERT INTO `groups_aro_map` VALUES ('953', '1561');
INSERT INTO `groups_aro_map` VALUES ('953', '1562');
INSERT INTO `groups_aro_map` VALUES ('953', '1563');
INSERT INTO `groups_aro_map` VALUES ('953', '1564');
INSERT INTO `groups_aro_map` VALUES ('953', '1565');
INSERT INTO `groups_aro_map` VALUES ('953', '1566');
INSERT INTO `groups_aro_map` VALUES ('953', '1567');
INSERT INTO `groups_aro_map` VALUES ('953', '1568');
INSERT INTO `groups_aro_map` VALUES ('953', '1569');
INSERT INTO `groups_aro_map` VALUES ('955', '1550');
INSERT INTO `groups_aro_map` VALUES ('955', '1551');
INSERT INTO `groups_aro_map` VALUES ('955', '1552');
INSERT INTO `groups_aro_map` VALUES ('955', '1553');
INSERT INTO `groups_aro_map` VALUES ('955', '1554');
INSERT INTO `groups_aro_map` VALUES ('956', '1570');
INSERT INTO `groups_aro_map` VALUES ('957', '1571');
INSERT INTO `groups_aro_map` VALUES ('960', '1572');
INSERT INTO `groups_aro_map` VALUES ('961', '1573');

-- ----------------------------
-- Table structure for `groups_axo_map`
-- ----------------------------
DROP TABLE IF EXISTS `groups_axo_map`;
CREATE TABLE `groups_axo_map` (
  `group_id` int(11) NOT NULL default '0',
  `axo_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`group_id`,`axo_id`),
  KEY `axo_id` (`axo_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of groups_axo_map
-- ----------------------------

-- ----------------------------
-- Table structure for `phpgacl`
-- ----------------------------
DROP TABLE IF EXISTS `phpgacl`;
CREATE TABLE `phpgacl` (
  `name` varchar(230) NOT NULL,
  `value` varchar(230) NOT NULL,
  PRIMARY KEY  (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of phpgacl
-- ----------------------------
INSERT INTO `phpgacl` VALUES ('version', '3.3.7');
INSERT INTO `phpgacl` VALUES ('schema_version', '2.1');
