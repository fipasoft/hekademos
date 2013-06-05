/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50075
Source Host           : localhost:3306
Source Database       : acl_profesores

Target Server Type    : MYSQL
Target Server Version : 50075
File Encoding         : 65001

Date: 2010-07-26 01:11:16
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
INSERT INTO `acl` VALUES ('3429', 'system', '1', '1', '', '', '1254550349');
INSERT INTO `acl` VALUES ('3430', 'system', '1', '1', '', '', '1254550349');
INSERT INTO `acl` VALUES ('3431', 'system', '1', '1', '', '', '1254550349');
INSERT INTO `acl` VALUES ('3432', 'system', '1', '1', '', '', '1254550349');
INSERT INTO `acl` VALUES ('3433', 'system', '1', '1', '', '', '1254550349');
INSERT INTO `acl` VALUES ('3434', 'system', '1', '1', '', '', '1254550349');
INSERT INTO `acl` VALUES ('3435', 'system', '1', '1', '', '', '1254550349');
INSERT INTO `acl` VALUES ('3436', 'system', '1', '1', '', '', '1254550349');
INSERT INTO `acl` VALUES ('3437', 'system', '1', '1', '', '', '1254550349');
INSERT INTO `acl` VALUES ('3438', 'system', '1', '1', '', '', '1254550349');

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
INSERT INTO `acl_sections_seq` VALUES ('63');

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
INSERT INTO `acl_seq` VALUES ('3438');

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
INSERT INTO `aco` VALUES ('10736', 'ALL', 'ALL', '0', 'ALL', '0');
INSERT INTO `aco` VALUES ('10737', 'sesion', 'abrir', '0', 'abrir', '0');
INSERT INTO `aco` VALUES ('10738', 'sesion', 'autenticar', '0', 'autenticar', '0');
INSERT INTO `aco` VALUES ('10739', 'sesion', 'cerrar', '0', 'cerrar', '0');
INSERT INTO `aco` VALUES ('10740', 'sesion', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('10741', 'sesion', 'restringir', '0', 'restringir', '0');
INSERT INTO `aco` VALUES ('10742', 'inicio', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('10743', 'inicio', 'profesor', '0', 'profesor', '0');
INSERT INTO `aco` VALUES ('10744', 'alumnos', 'buscar', '0', 'buscar', '0');
INSERT INTO `aco` VALUES ('10745', 'alumnos', 'exportar', '0', 'exportar', '0');
INSERT INTO `aco` VALUES ('10746', 'alumnos', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('10747', 'alumnos', 'info', '0', 'info', '0');
INSERT INTO `aco` VALUES ('10748', 'alumnos', 'ver', '0', 'ver', '0');
INSERT INTO `aco` VALUES ('10749', 'alumnos', 'cursos', '0', 'cursos', '0');
INSERT INTO `aco` VALUES ('10750', 'alumnos', 'kardex', '0', 'kardex', '0');
INSERT INTO `aco` VALUES ('10751', 'asistencias', 'agregar', '0', 'agregar', '0');
INSERT INTO `aco` VALUES ('10752', 'asistencias', 'editar', '0', 'editar', '0');
INSERT INTO `aco` VALUES ('10753', 'asistencias', 'eliminar', '0', 'eliminar', '0');
INSERT INTO `aco` VALUES ('10754', 'asistencias', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('10755', 'asistencias', 'selector', '0', 'selector', '0');
INSERT INTO `aco` VALUES ('10756', 'asistencias', 'ver', '0', 'ver', '0');
INSERT INTO `aco` VALUES ('10757', 'calificaciones', 'agregar', '0', 'agregar', '0');
INSERT INTO `aco` VALUES ('10758', 'calificaciones', 'editar', '0', 'editar', '0');
INSERT INTO `aco` VALUES ('10759', 'calificaciones', 'eliminar', '0', 'eliminar', '0');
INSERT INTO `aco` VALUES ('10760', 'calificaciones', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('10761', 'calificaciones', 'imprimir', '0', 'imprimir', '0');
INSERT INTO `aco` VALUES ('10762', 'calificaciones', 'selector', '0', 'selector', '0');
INSERT INTO `aco` VALUES ('10763', 'calificaciones', 'ver', '0', 'ver', '0');
INSERT INTO `aco` VALUES ('10764', 'grupos', 'curso', '0', 'curso', '0');
INSERT INTO `aco` VALUES ('10765', 'grupos', 'horario', '0', 'horario', '0');
INSERT INTO `aco` VALUES ('10766', 'grupos', 'imprimir', '0', 'imprimir', '0');
INSERT INTO `aco` VALUES ('10767', 'grupos', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('10768', 'grupos', 'ver', '0', 'ver', '0');
INSERT INTO `aco` VALUES ('10769', 'profesores', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('10770', 'profesores', 'ver', '0', 'ver', '0');
INSERT INTO `aco` VALUES ('10771', 'profesores', 'horario', '0', 'horario', '0');
INSERT INTO `aco` VALUES ('10772', 'profesores', 'horarioexcel', '0', 'horarioexcel', '0');
INSERT INTO `aco` VALUES ('10773', 'cursos', 'exportar', '0', 'exportar', '0');
INSERT INTO `aco` VALUES ('10774', 'cursos', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('10775', 'cursos', 'ver', '0', 'ver', '0');
INSERT INTO `aco` VALUES ('10776', 'cursos', 'buscar', '0', 'buscar', '0');
INSERT INTO `aco` VALUES ('10777', 'cursos', 'grupo', '0', 'grupo', '0');
INSERT INTO `aco` VALUES ('10778', 'sistema', 'ayuda', '0', 'ayuda', '0');
INSERT INTO `aco` VALUES ('10779', 'sistema', 'configuracion', '0', 'configuracion', '0');
INSERT INTO `aco` VALUES ('10780', 'sistema', 'password', '0', 'password', '0');
INSERT INTO `aco` VALUES ('10781', 'sistema', 'seleccionar', '0', 'seleccionar', '0');
INSERT INTO `aco` VALUES ('10782', 'historial', 'exportar', '0', 'exportar', '0');
INSERT INTO `aco` VALUES ('10783', 'historial', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('10784', 'historial', 'buscar', '0', 'buscar', '0');
INSERT INTO `aco` VALUES ('10785', 'historial', 'ver', '0', 'ver', '0');

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
INSERT INTO `aco_map` VALUES ('3429', 'sesion', 'abrir');
INSERT INTO `aco_map` VALUES ('3429', 'sesion', 'autenticar');
INSERT INTO `aco_map` VALUES ('3429', 'sesion', 'cerrar');
INSERT INTO `aco_map` VALUES ('3429', 'sesion', 'index');
INSERT INTO `aco_map` VALUES ('3429', 'sesion', 'restringir');
INSERT INTO `aco_map` VALUES ('3430', 'sistema', 'ayuda');
INSERT INTO `aco_map` VALUES ('3430', 'sistema', 'configuracion');
INSERT INTO `aco_map` VALUES ('3430', 'sistema', 'password');
INSERT INTO `aco_map` VALUES ('3430', 'sistema', 'seleccionar');
INSERT INTO `aco_map` VALUES ('3431', 'ALL', 'ALL');
INSERT INTO `aco_map` VALUES ('3432', 'alumnos', 'buscar');
INSERT INTO `aco_map` VALUES ('3432', 'alumnos', 'cursos');
INSERT INTO `aco_map` VALUES ('3432', 'alumnos', 'exportar');
INSERT INTO `aco_map` VALUES ('3432', 'alumnos', 'index');
INSERT INTO `aco_map` VALUES ('3432', 'alumnos', 'info');
INSERT INTO `aco_map` VALUES ('3432', 'alumnos', 'kardex');
INSERT INTO `aco_map` VALUES ('3432', 'alumnos', 'ver');
INSERT INTO `aco_map` VALUES ('3433', 'asistencias', 'agregar');
INSERT INTO `aco_map` VALUES ('3433', 'asistencias', 'editar');
INSERT INTO `aco_map` VALUES ('3433', 'asistencias', 'eliminar');
INSERT INTO `aco_map` VALUES ('3433', 'asistencias', 'index');
INSERT INTO `aco_map` VALUES ('3433', 'asistencias', 'selector');
INSERT INTO `aco_map` VALUES ('3433', 'asistencias', 'ver');
INSERT INTO `aco_map` VALUES ('3434', 'calificaciones', 'agregar');
INSERT INTO `aco_map` VALUES ('3434', 'calificaciones', 'editar');
INSERT INTO `aco_map` VALUES ('3434', 'calificaciones', 'eliminar');
INSERT INTO `aco_map` VALUES ('3434', 'calificaciones', 'index');
INSERT INTO `aco_map` VALUES ('3434', 'calificaciones', 'selector');
INSERT INTO `aco_map` VALUES ('3434', 'calificaciones', 'ver');
INSERT INTO `aco_map` VALUES ('3435', 'cursos', 'buscar');
INSERT INTO `aco_map` VALUES ('3435', 'cursos', 'exportar');
INSERT INTO `aco_map` VALUES ('3435', 'cursos', 'grupo');
INSERT INTO `aco_map` VALUES ('3435', 'cursos', 'index');
INSERT INTO `aco_map` VALUES ('3435', 'cursos', 'ver');
INSERT INTO `aco_map` VALUES ('3436', 'grupos', 'curso');
INSERT INTO `aco_map` VALUES ('3436', 'grupos', 'horario');
INSERT INTO `aco_map` VALUES ('3436', 'grupos', 'imprimir');
INSERT INTO `aco_map` VALUES ('3436', 'grupos', 'index');
INSERT INTO `aco_map` VALUES ('3436', 'grupos', 'ver');
INSERT INTO `aco_map` VALUES ('3437', 'inicio', 'index');
INSERT INTO `aco_map` VALUES ('3437', 'inicio', 'profesor');
INSERT INTO `aco_map` VALUES ('3438', 'profesores', 'horario');
INSERT INTO `aco_map` VALUES ('3438', 'profesores', 'horarioexcel');
INSERT INTO `aco_map` VALUES ('3438', 'profesores', 'index');
INSERT INTO `aco_map` VALUES ('3438', 'profesores', 'ver');

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
INSERT INTO `aco_sections` VALUES ('1751', 'ALL', '0', 'ALL', '0');
INSERT INTO `aco_sections` VALUES ('1752', 'sesion', '1', 'sesion', '0');
INSERT INTO `aco_sections` VALUES ('1753', 'inicio', '2', 'inicio', '0');
INSERT INTO `aco_sections` VALUES ('1754', 'alumnos', '3', 'alumnos', '0');
INSERT INTO `aco_sections` VALUES ('1755', 'asistencias', '4', 'asistencias', '0');
INSERT INTO `aco_sections` VALUES ('1756', 'calificaciones', '5', 'calificaciones', '0');
INSERT INTO `aco_sections` VALUES ('1757', 'grupos', '6', 'grupos', '0');
INSERT INTO `aco_sections` VALUES ('1758', 'profesores', '7', 'profesores', '0');
INSERT INTO `aco_sections` VALUES ('1759', 'cursos', '8', 'cursos', '0');
INSERT INTO `aco_sections` VALUES ('1760', 'sistema', '9', 'sistema', '0');
INSERT INTO `aco_sections` VALUES ('1761', 'historial', '10', 'historial', '0');

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
INSERT INTO `aco_sections_seq` VALUES ('1761');

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
INSERT INTO `aco_seq` VALUES ('10785');

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
INSERT INTO `aro` VALUES ('1076', 'usuarios', 'anonimo', '0', 'anonimo', '0');
INSERT INTO `aro` VALUES ('1077', 'usuarios', 'root', '0', 'root', '0');
INSERT INTO `aro` VALUES ('1078', 'usuarios', 'profesor', '0', 'profesor', '0');

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
INSERT INTO `aro_groups` VALUES ('827', '0', '1', '6', 'usuarios', 'usuarios');
INSERT INTO `aro_groups` VALUES ('828', '827', '2', '3', 'root', 'root');
INSERT INTO `aro_groups` VALUES ('829', '827', '4', '5', 'profesores', 'profesores');

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
INSERT INTO `aro_groups_id_seq` VALUES ('829');

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
INSERT INTO `aro_groups_map` VALUES ('3429', '827');
INSERT INTO `aro_groups_map` VALUES ('3430', '827');
INSERT INTO `aro_groups_map` VALUES ('3431', '828');
INSERT INTO `aro_groups_map` VALUES ('3432', '829');
INSERT INTO `aro_groups_map` VALUES ('3433', '829');
INSERT INTO `aro_groups_map` VALUES ('3434', '829');
INSERT INTO `aro_groups_map` VALUES ('3435', '829');
INSERT INTO `aro_groups_map` VALUES ('3436', '829');
INSERT INTO `aro_groups_map` VALUES ('3437', '829');
INSERT INTO `aro_groups_map` VALUES ('3438', '829');

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
INSERT INTO `aro_sections` VALUES ('63', 'usuarios', '0', 'usuarios', '0');

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
INSERT INTO `aro_sections_seq` VALUES ('63');

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
INSERT INTO `aro_seq` VALUES ('1078');

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
INSERT INTO `groups_aro_map` VALUES ('827', '1076');
INSERT INTO `groups_aro_map` VALUES ('828', '1077');
INSERT INTO `groups_aro_map` VALUES ('829', '1078');

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
