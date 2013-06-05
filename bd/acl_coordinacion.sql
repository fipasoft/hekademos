/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50075
Source Host           : localhost:3306
Source Database       : acl_coordinacion

Target Server Type    : MYSQL
Target Server Version : 50075
File Encoding         : 65001

Date: 2010-07-26 02:28:15
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
INSERT INTO `acl` VALUES ('3437', 'system', '1', '1', '', '', '1254006669');
INSERT INTO `acl` VALUES ('3438', 'system', '1', '1', '', '', '1254006669');
INSERT INTO `acl` VALUES ('3439', 'system', '1', '1', '', '', '1254006669');
INSERT INTO `acl` VALUES ('3440', 'system', '1', '1', '', '', '1254006669');
INSERT INTO `acl` VALUES ('3441', 'system', '1', '1', '', '', '1254006669');
INSERT INTO `acl` VALUES ('3442', 'system', '1', '1', '', '', '1254006669');
INSERT INTO `acl` VALUES ('3443', 'system', '1', '1', '', '', '1254006669');

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
INSERT INTO `acl_sections_seq` VALUES ('57');

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
INSERT INTO `acl_seq` VALUES ('3443');

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
INSERT INTO `aco` VALUES ('10819', 'ALL', 'ALL', '0', 'ALL', '0');
INSERT INTO `aco` VALUES ('10820', 'sesion', 'abrir', '0', 'abrir', '0');
INSERT INTO `aco` VALUES ('10821', 'sesion', 'autenticar', '0', 'autenticar', '0');
INSERT INTO `aco` VALUES ('10822', 'sesion', 'cerrar', '0', 'cerrar', '0');
INSERT INTO `aco` VALUES ('10823', 'sesion', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('10824', 'sesion', 'restringir', '0', 'restringir', '0');
INSERT INTO `aco` VALUES ('10825', 'inicio', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('10826', 'tutoresgrupo', 'asignar', '0', 'asignar', '0');
INSERT INTO `aco` VALUES ('10827', 'tutoresgrupo', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('10828', 'tutoresgrupo', 'horario', '0', 'horario', '0');
INSERT INTO `aco` VALUES ('10829', 'tutoresgrupo', 'ver', '0', 'ver', '0');
INSERT INTO `aco` VALUES ('10830', 'tutoresgrupo', 'horarioexcel', '0', 'horarioexcel', '0');
INSERT INTO `aco` VALUES ('10831', 'grupos', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('10832', 'grupos', 'ver', '0', 'ver', '0');
INSERT INTO `aco` VALUES ('10833', 'usuarios', 'agregar', '0', 'agregar', '0');
INSERT INTO `aco` VALUES ('10834', 'usuarios', 'editar', '0', 'editar', '0');
INSERT INTO `aco` VALUES ('10835', 'usuarios', 'eliminar', '0', 'eliminar', '0');
INSERT INTO `aco` VALUES ('10836', 'usuarios', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('10837', 'usuarios', 'password', '0', 'password', '0');
INSERT INTO `aco` VALUES ('10838', 'usuarios', 'validarLogin', '0', 'validarLogin', '0');
INSERT INTO `aco` VALUES ('10839', 'usuarios', 'verAcceso', '0', 'verAcceso', '0');
INSERT INTO `aco` VALUES ('10840', 'usuarios', 'ver', '0', 'ver', '0');
INSERT INTO `aco` VALUES ('10841', 'sistema', 'ayuda', '0', 'ayuda', '0');
INSERT INTO `aco` VALUES ('10842', 'sistema', 'configuracion', '0', 'configuracion', '0');
INSERT INTO `aco` VALUES ('10843', 'sistema', 'password', '0', 'password', '0');
INSERT INTO `aco` VALUES ('10844', 'sistema', 'seleccionar', '0', 'seleccionar', '0');
INSERT INTO `aco` VALUES ('10845', 'historial', 'exportar', '0', 'exportar', '0');
INSERT INTO `aco` VALUES ('10846', 'historial', 'index', '0', 'index', '0');
INSERT INTO `aco` VALUES ('10847', 'historial', 'buscar', '0', 'buscar', '0');
INSERT INTO `aco` VALUES ('10848', 'historial', 'ver', '0', 'ver', '0');

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
INSERT INTO `aco_map` VALUES ('3437', 'sesion', 'abrir');
INSERT INTO `aco_map` VALUES ('3437', 'sesion', 'autenticar');
INSERT INTO `aco_map` VALUES ('3437', 'sesion', 'cerrar');
INSERT INTO `aco_map` VALUES ('3437', 'sesion', 'index');
INSERT INTO `aco_map` VALUES ('3437', 'sesion', 'restringir');
INSERT INTO `aco_map` VALUES ('3438', 'ALL', 'ALL');
INSERT INTO `aco_map` VALUES ('3439', 'inicio', 'index');
INSERT INTO `aco_map` VALUES ('3440', 'historial', 'buscar');
INSERT INTO `aco_map` VALUES ('3440', 'historial', 'exportar');
INSERT INTO `aco_map` VALUES ('3440', 'historial', 'index');
INSERT INTO `aco_map` VALUES ('3440', 'historial', 'ver');
INSERT INTO `aco_map` VALUES ('3441', 'tutoresgrupo', 'asignar');
INSERT INTO `aco_map` VALUES ('3441', 'tutoresgrupo', 'horario');
INSERT INTO `aco_map` VALUES ('3441', 'tutoresgrupo', 'horarioexcel');
INSERT INTO `aco_map` VALUES ('3441', 'tutoresgrupo', 'index');
INSERT INTO `aco_map` VALUES ('3441', 'tutoresgrupo', 'ver');
INSERT INTO `aco_map` VALUES ('3442', 'usuarios', 'agregar');
INSERT INTO `aco_map` VALUES ('3442', 'usuarios', 'editar');
INSERT INTO `aco_map` VALUES ('3442', 'usuarios', 'eliminar');
INSERT INTO `aco_map` VALUES ('3442', 'usuarios', 'index');
INSERT INTO `aco_map` VALUES ('3442', 'usuarios', 'password');
INSERT INTO `aco_map` VALUES ('3442', 'usuarios', 'validarLogin');
INSERT INTO `aco_map` VALUES ('3442', 'usuarios', 'ver');
INSERT INTO `aco_map` VALUES ('3442', 'usuarios', 'verAcceso');
INSERT INTO `aco_map` VALUES ('3443', 'sistema', 'ayuda');
INSERT INTO `aco_map` VALUES ('3443', 'sistema', 'configuracion');
INSERT INTO `aco_map` VALUES ('3443', 'sistema', 'password');
INSERT INTO `aco_map` VALUES ('3443', 'sistema', 'seleccionar');

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
INSERT INTO `aco_sections` VALUES ('1762', 'ALL', '0', 'ALL', '0');
INSERT INTO `aco_sections` VALUES ('1763', 'sesion', '1', 'sesion', '0');
INSERT INTO `aco_sections` VALUES ('1764', 'inicio', '2', 'inicio', '0');
INSERT INTO `aco_sections` VALUES ('1765', 'tutoresgrupo', '3', 'tutoresgrupo', '0');
INSERT INTO `aco_sections` VALUES ('1766', 'grupos', '4', 'grupos', '0');
INSERT INTO `aco_sections` VALUES ('1767', 'usuarios', '5', 'usuarios', '0');
INSERT INTO `aco_sections` VALUES ('1768', 'sistema', '6', 'sistema', '0');
INSERT INTO `aco_sections` VALUES ('1769', 'historial', '7', 'historial', '0');

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
INSERT INTO `aco_sections_seq` VALUES ('1769');

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
INSERT INTO `aco_seq` VALUES ('10848');

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
INSERT INTO `aro` VALUES ('1013', 'usuarios', 'anonimo', '0', 'anonimo', '0');
INSERT INTO `aro` VALUES ('1014', 'usuarios', 'root', '0', 'root', '0');
INSERT INTO `aro` VALUES ('1016', 'usuarios', 'admin', '0', 'admin', '0');
INSERT INTO `aro` VALUES ('1018', 'usuarios', 'claudia.fregoso', '0', 'claudia.fregoso', '0');

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
INSERT INTO `aro_groups` VALUES ('683', '0', '1', '6', 'usuarios', 'usuarios');
INSERT INTO `aro_groups` VALUES ('684', '683', '2', '3', 'root', 'root');
INSERT INTO `aro_groups` VALUES ('685', '683', '4', '5', 'administradores', 'administradores');

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
INSERT INTO `aro_groups_id_seq` VALUES ('685');

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
INSERT INTO `aro_groups_map` VALUES ('3437', '683');
INSERT INTO `aro_groups_map` VALUES ('3438', '684');
INSERT INTO `aro_groups_map` VALUES ('3439', '685');
INSERT INTO `aro_groups_map` VALUES ('3440', '685');
INSERT INTO `aro_groups_map` VALUES ('3441', '685');
INSERT INTO `aro_groups_map` VALUES ('3442', '685');
INSERT INTO `aro_groups_map` VALUES ('3443', '685');

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
INSERT INTO `aro_sections` VALUES ('57', 'usuarios', '0', 'usuarios', '0');

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
INSERT INTO `aro_sections_seq` VALUES ('57');

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
INSERT INTO `aro_seq` VALUES ('1018');

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
INSERT INTO `groups_aro_map` VALUES ('683', '1013');
INSERT INTO `groups_aro_map` VALUES ('684', '1014');
INSERT INTO `groups_aro_map` VALUES ('685', '1016');
INSERT INTO `groups_aro_map` VALUES ('685', '1018');

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
