/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50075
Source Host           : localhost:3306
Source Database       : hekademos

Target Server Type    : MYSQL
Target Server Version : 50075
File Encoding         : 65001

Date: 2010-07-25 18:51:32
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `academia`
-- ----------------------------
DROP TABLE IF EXISTS `academia`;
CREATE TABLE `academia` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `departamento_id` int(10) unsigned NOT NULL,
  `nombre` varchar(200) character set latin1 default NULL,
  PRIMARY KEY  (`id`),
  KEY `academia_FKIndex1` (`departamento_id`),
  CONSTRAINT `academia_ibfk_1` FOREIGN KEY (`departamento_id`) REFERENCES `departamento` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of academia
-- ----------------------------
INSERT INTO `academia` VALUES ('71', '26', 'FilosofÃ­a');
INSERT INTO `academia` VALUES ('72', '27', 'CÃ³mputo');
INSERT INTO `academia` VALUES ('73', '27', 'MatemÃ¡ticas');
INSERT INTO `academia` VALUES ('74', '28', 'Fisica');
INSERT INTO `academia` VALUES ('75', '29', 'Sociales');
INSERT INTO `academia` VALUES ('76', '30', 'Arte');
INSERT INTO `academia` VALUES ('77', '26', 'PsicologÃ­a');
INSERT INTO `academia` VALUES ('78', '29', 'Historia');
INSERT INTO `academia` VALUES ('79', '30', 'Lengua EspaÃ±ola');
INSERT INTO `academia` VALUES ('80', '30', 'Literatura');
INSERT INTO `academia` VALUES ('81', '30', 'Lengua Extranjera');
INSERT INTO `academia` VALUES ('82', '28', 'BiologÃ­a');
INSERT INTO `academia` VALUES ('83', '28', 'QuÃ­mica');
INSERT INTO `academia` VALUES ('84', '28', 'FÃ­sica');

-- ----------------------------
-- Table structure for `academiamateria`
-- ----------------------------
DROP TABLE IF EXISTS `academiamateria`;
CREATE TABLE `academiamateria` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `materias_id` int(10) unsigned NOT NULL,
  `academia_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `academiamateria_FKIndex1` (`academia_id`),
  KEY `academiamateria_FKIndex2` (`materias_id`),
  CONSTRAINT `academiamateria_ibfk_1` FOREIGN KEY (`academia_id`) REFERENCES `academia` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `academiamateria_ibfk_2` FOREIGN KEY (`materias_id`) REFERENCES `materias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of academiamateria
-- ----------------------------

-- ----------------------------
-- Table structure for `agenda`
-- ----------------------------
DROP TABLE IF EXISTS `agenda`;
CREATE TABLE `agenda` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `ciclos_id` int(10) unsigned NOT NULL,
  `roles_id` int(10) unsigned NOT NULL,
  `inicio` datetime NOT NULL,
  `fin` datetime NOT NULL,
  `activo` varchar(1) collate utf8_spanish_ci NOT NULL,
  `periodo` int(10) unsigned default NULL,
  PRIMARY KEY  (`id`),
  KEY `agenda_FKIndex1` (`ciclos_id`),
  KEY `agenda_FKIndex2` (`roles_id`),
  CONSTRAINT `agenda_ibfk_1` FOREIGN KEY (`ciclos_id`) REFERENCES `ciclos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `agenda_ibfk_2` FOREIGN KEY (`roles_id`) REFERENCES `roles` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1351 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of agenda
-- ----------------------------
INSERT INTO `agenda` VALUES ('1128', '6', '173', '2010-08-01 00:00:00', '2011-01-07 00:00:00', '1', '79');
INSERT INTO `agenda` VALUES ('1129', '6', '247', '2011-01-10 00:00:00', '2011-01-31 00:00:00', '1', '80');
INSERT INTO `agenda` VALUES ('1130', '6', '306', '2010-08-16 00:00:00', '2010-10-02 00:00:00', '1', '81');
INSERT INTO `agenda` VALUES ('1131', '6', '307', '2010-10-04 00:00:00', '2011-01-07 00:00:00', '1', '82');
INSERT INTO `agenda` VALUES ('1132', '6', '308', '2010-12-08 00:00:00', '2011-01-07 00:00:00', '0', '83');
INSERT INTO `agenda` VALUES ('1133', '6', '309', '2010-12-08 00:00:00', '2011-01-07 00:00:00', '0', '83');
INSERT INTO `agenda` VALUES ('1134', '6', '310', '2010-12-08 00:00:00', '2011-01-07 00:00:00', '0', '83');
INSERT INTO `agenda` VALUES ('1135', '6', '311', '2010-12-08 00:00:00', '2011-01-07 00:00:00', '0', '83');
INSERT INTO `agenda` VALUES ('1136', '6', '364', '2010-12-08 00:00:00', '2011-01-07 00:00:00', '0', '83');
INSERT INTO `agenda` VALUES ('1137', '6', '174', '2010-12-08 00:00:00', '2011-01-07 00:00:00', '0', '83');
INSERT INTO `agenda` VALUES ('1138', '6', '175', '2010-12-08 00:00:00', '2011-01-07 00:00:00', '0', '83');
INSERT INTO `agenda` VALUES ('1139', '6', '176', '2010-12-08 00:00:00', '2011-01-07 00:00:00', '0', '83');
INSERT INTO `agenda` VALUES ('1140', '6', '177', '2010-12-08 00:00:00', '2011-01-07 00:00:00', '0', '83');
INSERT INTO `agenda` VALUES ('1141', '6', '178', '2010-12-08 00:00:00', '2011-01-07 00:00:00', '0', '83');
INSERT INTO `agenda` VALUES ('1142', '6', '179', '2010-12-08 00:00:00', '2011-01-07 00:00:00', '0', '83');
INSERT INTO `agenda` VALUES ('1143', '6', '328', '2011-01-03 00:00:00', '2011-01-21 00:00:00', '0', '84');
INSERT INTO `agenda` VALUES ('1144', '6', '329', '2011-01-03 00:00:00', '2011-01-21 00:00:00', '0', '84');
INSERT INTO `agenda` VALUES ('1145', '6', '330', '2011-01-03 00:00:00', '2011-01-21 00:00:00', '0', '84');
INSERT INTO `agenda` VALUES ('1146', '6', '331', '2011-01-03 00:00:00', '2011-01-21 00:00:00', '0', '84');
INSERT INTO `agenda` VALUES ('1147', '6', '365', '2011-01-03 00:00:00', '2011-01-21 00:00:00', '0', '84');
INSERT INTO `agenda` VALUES ('1148', '6', '206', '2011-01-03 00:00:00', '2011-01-21 00:00:00', '0', '84');
INSERT INTO `agenda` VALUES ('1149', '6', '207', '2011-01-03 00:00:00', '2011-01-21 00:00:00', '0', '84');
INSERT INTO `agenda` VALUES ('1150', '6', '208', '2011-01-03 00:00:00', '2011-01-21 00:00:00', '0', '84');
INSERT INTO `agenda` VALUES ('1151', '6', '209', '2011-01-03 00:00:00', '2011-01-21 00:00:00', '0', '84');
INSERT INTO `agenda` VALUES ('1152', '6', '210', '2011-01-03 00:00:00', '2011-01-21 00:00:00', '0', '84');
INSERT INTO `agenda` VALUES ('1153', '6', '211', '2011-01-03 00:00:00', '2011-01-21 00:00:00', '0', '84');
INSERT INTO `agenda` VALUES ('1154', '6', '256', '2010-10-04 00:00:00', '2010-10-15 00:00:00', '0', '85');
INSERT INTO `agenda` VALUES ('1155', '6', '257', '2010-10-04 00:00:00', '2010-10-15 00:00:00', '0', '85');
INSERT INTO `agenda` VALUES ('1156', '6', '258', '2010-10-04 00:00:00', '2010-10-15 00:00:00', '0', '85');
INSERT INTO `agenda` VALUES ('1157', '6', '259', '2010-10-04 00:00:00', '2010-10-15 00:00:00', '0', '85');
INSERT INTO `agenda` VALUES ('1158', '6', '348', '2010-10-04 00:00:00', '2010-10-15 00:00:00', '0', '85');
INSERT INTO `agenda` VALUES ('1159', '6', '349', '2010-10-04 00:00:00', '2010-10-15 00:00:00', '0', '85');
INSERT INTO `agenda` VALUES ('1160', '6', '350', '2010-10-04 00:00:00', '2010-10-15 00:00:00', '0', '85');
INSERT INTO `agenda` VALUES ('1161', '6', '351', '2010-10-04 00:00:00', '2010-10-15 00:00:00', '0', '85');
INSERT INTO `agenda` VALUES ('1162', '6', '366', '2010-10-04 00:00:00', '2010-10-15 00:00:00', '0', '85');
INSERT INTO `agenda` VALUES ('1163', '6', '254', '2010-10-04 00:00:00', '2010-10-15 00:00:00', '0', '85');
INSERT INTO `agenda` VALUES ('1164', '6', '255', '2010-10-04 00:00:00', '2010-10-15 00:00:00', '0', '85');
INSERT INTO `agenda` VALUES ('1165', '6', '284', '2010-10-11 00:00:00', '2010-10-22 00:00:00', '0', '86');
INSERT INTO `agenda` VALUES ('1166', '6', '285', '2010-10-11 00:00:00', '2010-10-22 00:00:00', '0', '86');
INSERT INTO `agenda` VALUES ('1167', '6', '286', '2010-10-11 00:00:00', '2010-10-22 00:00:00', '0', '86');
INSERT INTO `agenda` VALUES ('1168', '6', '287', '2010-10-11 00:00:00', '2010-10-22 00:00:00', '0', '86');
INSERT INTO `agenda` VALUES ('1169', '6', '288', '2010-10-11 00:00:00', '2010-10-22 00:00:00', '0', '86');
INSERT INTO `agenda` VALUES ('1170', '6', '289', '2010-10-11 00:00:00', '2010-10-22 00:00:00', '0', '86');
INSERT INTO `agenda` VALUES ('1171', '6', '352', '2010-10-11 00:00:00', '2010-10-22 00:00:00', '0', '86');
INSERT INTO `agenda` VALUES ('1172', '6', '353', '2010-10-11 00:00:00', '2010-10-22 00:00:00', '0', '86');
INSERT INTO `agenda` VALUES ('1173', '6', '354', '2010-10-11 00:00:00', '2010-10-22 00:00:00', '0', '86');
INSERT INTO `agenda` VALUES ('1174', '6', '355', '2010-10-11 00:00:00', '2010-10-22 00:00:00', '0', '86');
INSERT INTO `agenda` VALUES ('1175', '6', '367', '2010-10-11 00:00:00', '2010-10-22 00:00:00', '0', '86');
INSERT INTO `agenda` VALUES ('1176', '6', '312', '2010-10-04 00:00:00', '2010-10-22 00:00:00', '0', '87');
INSERT INTO `agenda` VALUES ('1177', '6', '313', '2010-10-04 00:00:00', '2010-10-22 00:00:00', '0', '87');
INSERT INTO `agenda` VALUES ('1178', '6', '314', '2010-10-04 00:00:00', '2010-10-22 00:00:00', '0', '87');
INSERT INTO `agenda` VALUES ('1179', '6', '315', '2010-10-04 00:00:00', '2010-10-22 00:00:00', '0', '87');
INSERT INTO `agenda` VALUES ('1180', '6', '356', '2010-10-04 00:00:00', '2010-10-22 00:00:00', '0', '87');
INSERT INTO `agenda` VALUES ('1181', '6', '180', '2010-10-04 00:00:00', '2010-10-22 00:00:00', '0', '87');
INSERT INTO `agenda` VALUES ('1182', '6', '181', '2010-10-04 00:00:00', '2010-10-22 00:00:00', '0', '87');
INSERT INTO `agenda` VALUES ('1183', '6', '182', '2010-10-04 00:00:00', '2010-10-22 00:00:00', '0', '87');
INSERT INTO `agenda` VALUES ('1184', '6', '183', '2010-10-04 00:00:00', '2010-10-22 00:00:00', '0', '87');
INSERT INTO `agenda` VALUES ('1185', '6', '184', '2010-10-04 00:00:00', '2010-10-22 00:00:00', '0', '87');
INSERT INTO `agenda` VALUES ('1186', '6', '185', '2010-10-04 00:00:00', '2010-10-22 00:00:00', '0', '87');
INSERT INTO `agenda` VALUES ('1187', '6', '316', '2010-10-11 00:00:00', '2010-10-22 00:00:00', '0', '88');
INSERT INTO `agenda` VALUES ('1188', '6', '317', '2010-10-11 00:00:00', '2010-10-22 00:00:00', '0', '88');
INSERT INTO `agenda` VALUES ('1189', '6', '318', '2010-10-11 00:00:00', '2010-10-22 00:00:00', '0', '88');
INSERT INTO `agenda` VALUES ('1190', '6', '319', '2010-10-11 00:00:00', '2010-10-22 00:00:00', '0', '88');
INSERT INTO `agenda` VALUES ('1191', '6', '357', '2010-10-11 00:00:00', '2010-10-22 00:00:00', '0', '88');
INSERT INTO `agenda` VALUES ('1192', '6', '186', '2010-10-11 00:00:00', '2010-10-22 00:00:00', '0', '88');
INSERT INTO `agenda` VALUES ('1193', '6', '187', '2010-10-11 00:00:00', '2010-10-22 00:00:00', '0', '88');
INSERT INTO `agenda` VALUES ('1194', '6', '188', '2010-10-11 00:00:00', '2010-10-22 00:00:00', '0', '88');
INSERT INTO `agenda` VALUES ('1195', '6', '189', '2010-10-11 00:00:00', '2010-10-22 00:00:00', '0', '88');
INSERT INTO `agenda` VALUES ('1196', '6', '190', '2010-10-11 00:00:00', '2010-10-22 00:00:00', '0', '88');
INSERT INTO `agenda` VALUES ('1197', '6', '191', '2010-10-11 00:00:00', '2010-10-22 00:00:00', '0', '88');
INSERT INTO `agenda` VALUES ('1198', '6', '316', '2010-09-27 00:00:00', '2010-10-09 00:00:00', '0', '89');
INSERT INTO `agenda` VALUES ('1199', '6', '317', '2010-09-27 00:00:00', '2010-10-09 00:00:00', '0', '89');
INSERT INTO `agenda` VALUES ('1200', '6', '318', '2010-09-27 00:00:00', '2010-10-09 00:00:00', '0', '89');
INSERT INTO `agenda` VALUES ('1201', '6', '319', '2010-09-27 00:00:00', '2010-10-09 00:00:00', '0', '89');
INSERT INTO `agenda` VALUES ('1202', '6', '357', '2010-09-27 00:00:00', '2010-10-09 00:00:00', '0', '89');
INSERT INTO `agenda` VALUES ('1203', '6', '186', '2010-09-27 00:00:00', '2010-10-09 00:00:00', '0', '89');
INSERT INTO `agenda` VALUES ('1204', '6', '187', '2010-09-27 00:00:00', '2010-10-09 00:00:00', '0', '89');
INSERT INTO `agenda` VALUES ('1205', '6', '188', '2010-09-27 00:00:00', '2010-10-09 00:00:00', '0', '89');
INSERT INTO `agenda` VALUES ('1206', '6', '189', '2010-09-27 00:00:00', '2010-10-09 00:00:00', '0', '89');
INSERT INTO `agenda` VALUES ('1207', '6', '190', '2010-09-27 00:00:00', '2010-10-09 00:00:00', '0', '89');
INSERT INTO `agenda` VALUES ('1208', '6', '191', '2010-09-27 00:00:00', '2010-10-09 00:00:00', '0', '89');
INSERT INTO `agenda` VALUES ('1209', '6', '316', '2010-09-27 00:00:00', '2010-10-09 00:00:00', '0', '90');
INSERT INTO `agenda` VALUES ('1210', '6', '317', '2010-09-27 00:00:00', '2010-10-09 00:00:00', '0', '90');
INSERT INTO `agenda` VALUES ('1211', '6', '318', '2010-09-27 00:00:00', '2010-10-09 00:00:00', '0', '90');
INSERT INTO `agenda` VALUES ('1212', '6', '319', '2010-09-27 00:00:00', '2010-10-09 00:00:00', '0', '90');
INSERT INTO `agenda` VALUES ('1213', '6', '357', '2010-09-27 00:00:00', '2010-10-09 00:00:00', '0', '90');
INSERT INTO `agenda` VALUES ('1214', '6', '186', '2010-09-27 00:00:00', '2010-10-09 00:00:00', '0', '90');
INSERT INTO `agenda` VALUES ('1215', '6', '187', '2010-09-27 00:00:00', '2010-10-09 00:00:00', '0', '90');
INSERT INTO `agenda` VALUES ('1216', '6', '188', '2010-09-27 00:00:00', '2010-10-09 00:00:00', '0', '90');
INSERT INTO `agenda` VALUES ('1217', '6', '189', '2010-09-27 00:00:00', '2010-10-09 00:00:00', '0', '90');
INSERT INTO `agenda` VALUES ('1218', '6', '190', '2010-09-27 00:00:00', '2010-10-09 00:00:00', '0', '90');
INSERT INTO `agenda` VALUES ('1219', '6', '191', '2010-09-27 00:00:00', '2010-10-09 00:00:00', '0', '90');
INSERT INTO `agenda` VALUES ('1220', '6', '320', '2010-11-01 00:00:00', '2010-11-12 00:00:00', '0', '91');
INSERT INTO `agenda` VALUES ('1221', '6', '321', '2010-11-01 00:00:00', '2010-11-12 00:00:00', '0', '91');
INSERT INTO `agenda` VALUES ('1222', '6', '322', '2010-11-01 00:00:00', '2010-11-12 00:00:00', '0', '91');
INSERT INTO `agenda` VALUES ('1223', '6', '323', '2010-11-01 00:00:00', '2010-11-12 00:00:00', '0', '91');
INSERT INTO `agenda` VALUES ('1224', '6', '358', '2010-11-01 00:00:00', '2010-11-12 00:00:00', '0', '91');
INSERT INTO `agenda` VALUES ('1225', '6', '192', '2010-11-01 00:00:00', '2010-11-12 00:00:00', '0', '91');
INSERT INTO `agenda` VALUES ('1226', '6', '193', '2010-11-01 00:00:00', '2010-11-12 00:00:00', '0', '91');
INSERT INTO `agenda` VALUES ('1227', '6', '194', '2010-11-01 00:00:00', '2010-11-12 00:00:00', '0', '91');
INSERT INTO `agenda` VALUES ('1228', '6', '195', '2010-11-01 00:00:00', '2010-11-12 00:00:00', '0', '91');
INSERT INTO `agenda` VALUES ('1229', '6', '196', '2010-11-01 00:00:00', '2010-11-12 00:00:00', '0', '91');
INSERT INTO `agenda` VALUES ('1230', '6', '197', '2010-11-01 00:00:00', '2010-11-12 00:00:00', '0', '91');
INSERT INTO `agenda` VALUES ('1231', '6', '324', '2010-11-15 00:00:00', '2010-11-26 00:00:00', '0', '92');
INSERT INTO `agenda` VALUES ('1232', '6', '325', '2010-11-15 00:00:00', '2010-11-26 00:00:00', '0', '92');
INSERT INTO `agenda` VALUES ('1233', '6', '326', '2010-11-15 00:00:00', '2010-11-26 00:00:00', '0', '92');
INSERT INTO `agenda` VALUES ('1234', '6', '327', '2010-11-15 00:00:00', '2010-11-26 00:00:00', '0', '92');
INSERT INTO `agenda` VALUES ('1235', '6', '359', '2010-11-15 00:00:00', '2010-11-26 00:00:00', '0', '92');
INSERT INTO `agenda` VALUES ('1236', '6', '198', '2010-11-15 00:00:00', '2010-11-26 00:00:00', '0', '92');
INSERT INTO `agenda` VALUES ('1237', '6', '199', '2010-11-15 00:00:00', '2010-11-26 00:00:00', '0', '92');
INSERT INTO `agenda` VALUES ('1238', '6', '200', '2010-11-15 00:00:00', '2010-11-26 00:00:00', '0', '92');
INSERT INTO `agenda` VALUES ('1239', '6', '201', '2010-11-15 00:00:00', '2010-11-26 00:00:00', '0', '92');
INSERT INTO `agenda` VALUES ('1240', '6', '202', '2010-11-15 00:00:00', '2010-11-26 00:00:00', '0', '92');
INSERT INTO `agenda` VALUES ('1241', '6', '203', '2010-11-15 00:00:00', '2010-11-26 00:00:00', '0', '92');
INSERT INTO `agenda` VALUES ('1242', '6', '260', '2010-08-16 00:00:00', '2010-08-27 00:00:00', '0', '93');
INSERT INTO `agenda` VALUES ('1243', '6', '261', '2010-08-16 00:00:00', '2010-08-27 00:00:00', '0', '93');
INSERT INTO `agenda` VALUES ('1244', '6', '262', '2010-08-16 00:00:00', '2010-08-27 00:00:00', '0', '93');
INSERT INTO `agenda` VALUES ('1245', '6', '263', '2010-08-16 00:00:00', '2010-08-27 00:00:00', '0', '93');
INSERT INTO `agenda` VALUES ('1246', '6', '264', '2010-08-16 00:00:00', '2010-08-27 00:00:00', '0', '93');
INSERT INTO `agenda` VALUES ('1247', '6', '265', '2010-08-16 00:00:00', '2010-08-27 00:00:00', '0', '93');
INSERT INTO `agenda` VALUES ('1248', '6', '332', '2010-08-16 00:00:00', '2010-08-27 00:00:00', '0', '93');
INSERT INTO `agenda` VALUES ('1249', '6', '333', '2010-08-16 00:00:00', '2010-08-27 00:00:00', '0', '93');
INSERT INTO `agenda` VALUES ('1250', '6', '334', '2010-08-16 00:00:00', '2010-08-27 00:00:00', '0', '93');
INSERT INTO `agenda` VALUES ('1251', '6', '335', '2010-08-16 00:00:00', '2010-08-27 00:00:00', '0', '93');
INSERT INTO `agenda` VALUES ('1252', '6', '360', '2010-08-16 00:00:00', '2010-08-27 00:00:00', '0', '93');
INSERT INTO `agenda` VALUES ('1253', '6', '266', '2010-08-30 00:00:00', '2010-09-03 00:00:00', '0', '94');
INSERT INTO `agenda` VALUES ('1254', '6', '267', '2010-08-30 00:00:00', '2010-09-03 00:00:00', '0', '94');
INSERT INTO `agenda` VALUES ('1255', '6', '268', '2010-08-30 00:00:00', '2010-09-03 00:00:00', '0', '94');
INSERT INTO `agenda` VALUES ('1256', '6', '269', '2010-08-30 00:00:00', '2010-09-03 00:00:00', '0', '94');
INSERT INTO `agenda` VALUES ('1257', '6', '270', '2010-08-30 00:00:00', '2010-09-03 00:00:00', '0', '94');
INSERT INTO `agenda` VALUES ('1258', '6', '271', '2010-08-30 00:00:00', '2010-09-03 00:00:00', '0', '94');
INSERT INTO `agenda` VALUES ('1259', '6', '336', '2010-08-30 00:00:00', '2010-09-03 00:00:00', '0', '94');
INSERT INTO `agenda` VALUES ('1260', '6', '337', '2010-08-30 00:00:00', '2010-09-03 00:00:00', '0', '94');
INSERT INTO `agenda` VALUES ('1261', '6', '338', '2010-08-30 00:00:00', '2010-09-03 00:00:00', '0', '94');
INSERT INTO `agenda` VALUES ('1262', '6', '339', '2010-08-30 00:00:00', '2010-09-03 00:00:00', '0', '94');
INSERT INTO `agenda` VALUES ('1263', '6', '361', '2010-08-30 00:00:00', '2010-09-03 00:00:00', '0', '94');
INSERT INTO `agenda` VALUES ('1264', '6', '272', '2010-09-13 00:00:00', '2010-09-24 00:00:00', '0', '95');
INSERT INTO `agenda` VALUES ('1265', '6', '273', '2010-09-13 00:00:00', '2010-09-24 00:00:00', '0', '95');
INSERT INTO `agenda` VALUES ('1266', '6', '274', '2010-09-13 00:00:00', '2010-09-24 00:00:00', '0', '95');
INSERT INTO `agenda` VALUES ('1267', '6', '275', '2010-09-13 00:00:00', '2010-09-24 00:00:00', '0', '95');
INSERT INTO `agenda` VALUES ('1268', '6', '276', '2010-09-13 00:00:00', '2010-09-24 00:00:00', '0', '95');
INSERT INTO `agenda` VALUES ('1269', '6', '277', '2010-09-13 00:00:00', '2010-09-24 00:00:00', '0', '95');
INSERT INTO `agenda` VALUES ('1270', '6', '340', '2010-09-13 00:00:00', '2010-09-24 00:00:00', '0', '95');
INSERT INTO `agenda` VALUES ('1271', '6', '341', '2010-09-13 00:00:00', '2010-09-24 00:00:00', '0', '95');
INSERT INTO `agenda` VALUES ('1272', '6', '342', '2010-09-13 00:00:00', '2010-09-24 00:00:00', '0', '95');
INSERT INTO `agenda` VALUES ('1273', '6', '343', '2010-09-13 00:00:00', '2010-09-24 00:00:00', '0', '95');
INSERT INTO `agenda` VALUES ('1274', '6', '362', '2010-09-13 00:00:00', '2010-09-24 00:00:00', '0', '95');
INSERT INTO `agenda` VALUES ('1275', '6', '278', '2010-09-06 00:00:00', '2010-09-17 00:00:00', '0', '96');
INSERT INTO `agenda` VALUES ('1276', '6', '279', '2010-09-06 00:00:00', '2010-09-17 00:00:00', '0', '96');
INSERT INTO `agenda` VALUES ('1277', '6', '280', '2010-09-06 00:00:00', '2010-09-17 00:00:00', '0', '96');
INSERT INTO `agenda` VALUES ('1278', '6', '281', '2010-09-06 00:00:00', '2010-09-17 00:00:00', '0', '96');
INSERT INTO `agenda` VALUES ('1279', '6', '282', '2010-09-06 00:00:00', '2010-09-17 00:00:00', '0', '96');
INSERT INTO `agenda` VALUES ('1280', '6', '283', '2010-09-06 00:00:00', '2010-09-17 00:00:00', '0', '96');
INSERT INTO `agenda` VALUES ('1281', '6', '344', '2010-09-06 00:00:00', '2010-09-17 00:00:00', '0', '96');
INSERT INTO `agenda` VALUES ('1282', '6', '345', '2010-09-06 00:00:00', '2010-09-17 00:00:00', '0', '96');
INSERT INTO `agenda` VALUES ('1283', '6', '346', '2010-09-06 00:00:00', '2010-09-17 00:00:00', '0', '96');
INSERT INTO `agenda` VALUES ('1284', '6', '347', '2010-09-06 00:00:00', '2010-09-17 00:00:00', '0', '96');
INSERT INTO `agenda` VALUES ('1285', '6', '363', '2010-09-06 00:00:00', '2010-09-17 00:00:00', '0', '96');
INSERT INTO `agenda` VALUES ('1286', '6', '204', '2010-08-01 00:00:00', '2010-08-15 00:00:00', '1', '97');
INSERT INTO `agenda` VALUES ('1287', '6', '212', '2010-08-16 00:00:00', '2011-01-07 00:00:00', '0', '98');
INSERT INTO `agenda` VALUES ('1288', '6', '213', '2010-08-16 00:00:00', '2011-01-07 00:00:00', '0', '98');
INSERT INTO `agenda` VALUES ('1289', '6', '214', '2010-08-16 00:00:00', '2011-01-07 00:00:00', '0', '98');
INSERT INTO `agenda` VALUES ('1290', '6', '215', '2010-08-16 00:00:00', '2011-01-07 00:00:00', '0', '98');
INSERT INTO `agenda` VALUES ('1291', '6', '216', '2010-08-16 00:00:00', '2011-01-07 00:00:00', '0', '98');
INSERT INTO `agenda` VALUES ('1292', '6', '217', '2010-08-16 00:00:00', '2011-01-07 00:00:00', '0', '98');
INSERT INTO `agenda` VALUES ('1293', '6', '218', '2010-08-16 00:00:00', '2011-01-07 00:00:00', '0', '98');
INSERT INTO `agenda` VALUES ('1294', '6', '219', '2010-08-16 00:00:00', '2011-01-07 00:00:00', '0', '98');
INSERT INTO `agenda` VALUES ('1295', '6', '220', '2010-08-16 00:00:00', '2011-01-07 00:00:00', '0', '98');
INSERT INTO `agenda` VALUES ('1296', '6', '292', '2010-08-16 00:00:00', '2010-10-15 00:00:00', '0', '99');
INSERT INTO `agenda` VALUES ('1297', '6', '293', '2010-08-16 00:00:00', '2010-10-15 00:00:00', '0', '99');
INSERT INTO `agenda` VALUES ('1298', '6', '294', '2010-08-16 00:00:00', '2010-10-15 00:00:00', '0', '99');
INSERT INTO `agenda` VALUES ('1299', '6', '295', '2010-08-16 00:00:00', '2010-10-15 00:00:00', '0', '99');
INSERT INTO `agenda` VALUES ('1300', '6', '296', '2010-08-16 00:00:00', '2010-10-15 00:00:00', '0', '99');
INSERT INTO `agenda` VALUES ('1301', '6', '297', '2010-08-16 00:00:00', '2010-10-15 00:00:00', '0', '99');
INSERT INTO `agenda` VALUES ('1302', '6', '298', '2010-08-16 00:00:00', '2010-10-15 00:00:00', '0', '99');
INSERT INTO `agenda` VALUES ('1303', '6', '299', '2010-08-16 00:00:00', '2010-10-15 00:00:00', '0', '99');
INSERT INTO `agenda` VALUES ('1304', '6', '300', '2010-08-16 00:00:00', '2010-10-15 00:00:00', '0', '99');
INSERT INTO `agenda` VALUES ('1305', '6', '221', '2010-07-28 00:00:00', '2011-01-31 00:00:00', '1', '100');
INSERT INTO `agenda` VALUES ('1306', '6', '222', '2010-07-28 00:00:00', '2011-01-31 00:00:00', '1', '100');
INSERT INTO `agenda` VALUES ('1307', '6', '223', '2010-07-28 00:00:00', '2011-01-31 00:00:00', '1', '101');
INSERT INTO `agenda` VALUES ('1308', '6', '224', '2010-07-28 00:00:00', '2011-01-31 00:00:00', '1', '101');
INSERT INTO `agenda` VALUES ('1309', '6', '225', '2010-07-28 00:00:00', '2011-01-31 00:00:00', '1', '102');
INSERT INTO `agenda` VALUES ('1310', '6', '226', '2010-07-28 00:00:00', '2011-01-31 00:00:00', '1', '102');
INSERT INTO `agenda` VALUES ('1311', '6', '227', '2010-07-28 00:00:00', '2011-01-31 00:00:00', '1', '103');
INSERT INTO `agenda` VALUES ('1312', '6', '228', '2010-07-28 00:00:00', '2011-01-31 00:00:00', '1', '103');
INSERT INTO `agenda` VALUES ('1313', '6', '229', '2010-07-28 00:00:00', '2011-01-31 00:00:00', '1', '103');
INSERT INTO `agenda` VALUES ('1314', '6', '230', '2010-07-28 00:00:00', '2011-01-31 00:00:00', '1', '103');
INSERT INTO `agenda` VALUES ('1315', '6', '231', '2010-07-28 00:00:00', '2011-01-31 00:00:00', '1', '103');
INSERT INTO `agenda` VALUES ('1316', '6', '232', '2010-07-28 00:00:00', '2011-01-31 00:00:00', '1', '103');
INSERT INTO `agenda` VALUES ('1317', '6', '233', '2010-07-28 00:00:00', '2011-01-31 00:00:00', '1', '104');
INSERT INTO `agenda` VALUES ('1318', '6', '234', '2010-07-28 00:00:00', '2011-01-31 00:00:00', '1', '104');
INSERT INTO `agenda` VALUES ('1319', '6', '235', '2010-07-28 00:00:00', '2011-01-31 00:00:00', '1', '104');
INSERT INTO `agenda` VALUES ('1320', '6', '242', '2010-07-28 00:00:00', '2011-01-31 00:00:00', '1', '104');
INSERT INTO `agenda` VALUES ('1321', '6', '243', '2010-07-28 00:00:00', '2011-01-31 00:00:00', '1', '104');
INSERT INTO `agenda` VALUES ('1322', '6', '244', '2010-07-28 00:00:00', '2011-01-31 00:00:00', '1', '104');
INSERT INTO `agenda` VALUES ('1323', '6', '245', '2010-07-28 00:00:00', '2011-01-31 00:00:00', '1', '104');
INSERT INTO `agenda` VALUES ('1324', '6', '239', '2010-07-28 00:00:00', '2011-01-31 00:00:00', '1', '104');
INSERT INTO `agenda` VALUES ('1325', '6', '240', '2010-07-28 00:00:00', '2011-01-31 00:00:00', '1', '104');
INSERT INTO `agenda` VALUES ('1326', '6', '241', '2010-07-28 00:00:00', '2011-01-31 00:00:00', '1', '104');
INSERT INTO `agenda` VALUES ('1327', '6', '236', '2010-07-28 00:00:00', '2011-01-31 00:00:00', '1', '104');
INSERT INTO `agenda` VALUES ('1328', '6', '237', '2010-07-28 00:00:00', '2011-01-31 00:00:00', '1', '104');
INSERT INTO `agenda` VALUES ('1329', '6', '238', '2010-07-28 00:00:00', '2011-01-31 00:00:00', '1', '104');
INSERT INTO `agenda` VALUES ('1330', '6', '246', '2010-07-28 00:00:00', '2011-01-31 00:00:00', '1', '104');

-- ----------------------------
-- Table structure for `alumnos`
-- ----------------------------
DROP TABLE IF EXISTS `alumnos`;
CREATE TABLE `alumnos` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `codigo` varchar(12) collate utf8_spanish_ci NOT NULL,
  `nombre` varchar(30) collate utf8_spanish_ci NOT NULL,
  `ap` varchar(20) collate utf8_spanish_ci NOT NULL,
  `am` varchar(20) collate utf8_spanish_ci NOT NULL,
  `domicilio` varchar(60) collate utf8_spanish_ci default NULL,
  `tel` varchar(20) collate utf8_spanish_ci default NULL,
  `cel` varchar(20) collate utf8_spanish_ci default NULL,
  `mail` varchar(80) collate utf8_spanish_ci default NULL,
  `foto` varchar(20) collate utf8_spanish_ci default NULL,
  `curp` varchar(18) collate utf8_spanish_ci default NULL,
  `fnacimiento` date default NULL,
  `sexo` varchar(1) collate utf8_spanish_ci default NULL,
  `situaciones_id` int(10) unsigned NOT NULL,
  `admision` date default NULL,
  `promedio` float(5,2) default NULL,
  `aprobadas` int(10) unsigned default NULL,
  PRIMARY KEY  (`id`),
  KEY `alumno_FKIndex1` (`situaciones_id`),
  CONSTRAINT `alumnos_ibfk_1` FOREIGN KEY (`situaciones_id`) REFERENCES `situaciones` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5891 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of alumnos
-- ----------------------------

-- ----------------------------
-- Table structure for `alumnosconarticulo`
-- ----------------------------
DROP TABLE IF EXISTS `alumnosconarticulo`;
CREATE TABLE `alumnosconarticulo` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `articulos_id` int(10) unsigned NOT NULL,
  `alumnoscursos_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `alumnosconarticulo_FKIndex1` (`articulos_id`),
  KEY `alumnosconarticulo_FKIndex2` (`alumnoscursos_id`),
  CONSTRAINT `alumnosconarticulo_ibfk_1` FOREIGN KEY (`articulos_id`) REFERENCES `articulos` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `alumnosconarticulo_ibfk_2` FOREIGN KEY (`alumnoscursos_id`) REFERENCES `alumnoscursos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11725 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of alumnosconarticulo
-- ----------------------------

-- ----------------------------
-- Table structure for `alumnoscredencial`
-- ----------------------------
DROP TABLE IF EXISTS `alumnoscredencial`;
CREATE TABLE `alumnoscredencial` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `alumnos_id` int(10) unsigned NOT NULL,
  `credencial_id` varchar(20) default NULL,
  PRIMARY KEY  (`id`),
  KEY `alumnoscredencial_FKIndex1` (`alumnos_id`),
  CONSTRAINT `alumnoscredencial_ibfk_1` FOREIGN KEY (`alumnos_id`) REFERENCES `alumnos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of alumnoscredencial
-- ----------------------------

-- ----------------------------
-- Table structure for `alumnoscursos`
-- ----------------------------
DROP TABLE IF EXISTS `alumnoscursos`;
CREATE TABLE `alumnoscursos` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `alumnos_id` int(10) unsigned NOT NULL,
  `cursos_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `alumnoscursos_FKIndex1` (`cursos_id`),
  KEY `alumnoscursos_FKIndex2` (`alumnos_id`),
  CONSTRAINT `alumnoscursos_ibfk_1` FOREIGN KEY (`cursos_id`) REFERENCES `cursos` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `alumnoscursos_ibfk_2` FOREIGN KEY (`alumnos_id`) REFERENCES `alumnos` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=233248 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of alumnoscursos
-- ----------------------------

-- ----------------------------
-- Table structure for `alumnosgrupo`
-- ----------------------------
DROP TABLE IF EXISTS `alumnosgrupo`;
CREATE TABLE `alumnosgrupo` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `grupos_id` int(10) unsigned NOT NULL,
  `alumnos_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `alumnogrupo_FKIndex2` (`alumnos_id`),
  KEY `alumnosgrupo_FKIndex2` (`grupos_id`),
  CONSTRAINT `alumnosgrupo_ibfk_1` FOREIGN KEY (`alumnos_id`) REFERENCES `alumnos` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `alumnosgrupo_ibfk_2` FOREIGN KEY (`grupos_id`) REFERENCES `grupos` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31402 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of alumnosgrupo
-- ----------------------------

-- ----------------------------
-- Table structure for `alumnospassword`
-- ----------------------------
DROP TABLE IF EXISTS `alumnospassword`;
CREATE TABLE `alumnospassword` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `alumnos_id` int(10) unsigned NOT NULL,
  `pass` varchar(50) collate utf8_spanish_ci NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `alumnopassword_FKIndex1` (`alumnos_id`),
  CONSTRAINT `alumnospassword_ibfk_1` FOREIGN KEY (`alumnos_id`) REFERENCES `alumnos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4432 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of alumnospassword
-- ----------------------------

-- ----------------------------
-- Table structure for `alumnotrayectoria`
-- ----------------------------
DROP TABLE IF EXISTS `alumnotrayectoria`;
CREATE TABLE `alumnotrayectoria` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `alumnos_id` int(10) unsigned NOT NULL,
  `trayectoriaespecializante_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `Table_55_FKIndex1` (`trayectoriaespecializante_id`),
  KEY `Table_55_FKIndex2` (`alumnos_id`),
  CONSTRAINT `alumnotrayectoria_ibfk_1` FOREIGN KEY (`trayectoriaespecializante_id`) REFERENCES `trayectoriaespecializante` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `alumnotrayectoria_ibfk_2` FOREIGN KEY (`alumnos_id`) REFERENCES `alumnos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=437 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of alumnotrayectoria
-- ----------------------------

-- ----------------------------
-- Table structure for `articulos`
-- ----------------------------
DROP TABLE IF EXISTS `articulos`;
CREATE TABLE `articulos` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `clave` varchar(5) collate utf8_spanish_ci NOT NULL,
  `descripcion` varchar(255) collate utf8_spanish_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of articulos
-- ----------------------------
INSERT INTO `articulos` VALUES ('1', 'art33', 'Articulo 33');
INSERT INTO `articulos` VALUES ('2', 'art34', 'Articulo 34');
INSERT INTO `articulos` VALUES ('3', 'art35', 'Articulo 35');
INSERT INTO `articulos` VALUES ('4', 'rep', 'Repitiendo');

-- ----------------------------
-- Table structure for `asignar`
-- ----------------------------
DROP TABLE IF EXISTS `asignar`;
CREATE TABLE `asignar` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `usuarios_id` int(10) unsigned NOT NULL,
  `grupos_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `asignar_FKIndex1` (`usuarios_id`),
  KEY `asignar_FKIndex2` (`grupos_id`),
  CONSTRAINT `asignar_ibfk_1` FOREIGN KEY (`usuarios_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `asignar_ibfk_2` FOREIGN KEY (`grupos_id`) REFERENCES `grupos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=609 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of asignar
-- ----------------------------

-- ----------------------------
-- Table structure for `asistencias`
-- ----------------------------
DROP TABLE IF EXISTS `asistencias`;
CREATE TABLE `asistencias` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `alumnos_id` int(10) unsigned NOT NULL,
  `cursos_id` int(10) unsigned NOT NULL,
  `asistenciasvalor_id` int(10) unsigned NOT NULL,
  `dia` date NOT NULL,
  `saved_at` datetime default NULL,
  `modified_in` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `asistencia_FKIndex1` (`cursos_id`),
  KEY `asistencia_FKIndex2` (`alumnos_id`),
  KEY `asistencia_FKIndex3` (`asistenciasvalor_id`),
  CONSTRAINT `asistencias_ibfk_1` FOREIGN KEY (`cursos_id`) REFERENCES `cursos` (`id`),
  CONSTRAINT `asistencias_ibfk_2` FOREIGN KEY (`alumnos_id`) REFERENCES `alumnos` (`id`),
  CONSTRAINT `asistencias_ibfk_3` FOREIGN KEY (`asistenciasvalor_id`) REFERENCES `asistenciasvalor` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1875935 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of asistencias
-- ----------------------------

-- ----------------------------
-- Table structure for `asistenciastemporal`
-- ----------------------------
DROP TABLE IF EXISTS `asistenciastemporal`;
CREATE TABLE `asistenciastemporal` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `alumnos_id` int(10) unsigned NOT NULL,
  `cursos_id` int(10) unsigned NOT NULL,
  `asistenciasvalor_id` int(10) unsigned NOT NULL,
  `dia` date NOT NULL,
  `saved_at` datetime default NULL,
  `modified_in` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `asistenciastemporal_FKIndex1` (`cursos_id`),
  KEY `asistenciastemporal_FKIndex2` (`alumnos_id`),
  KEY `asistenciastemporal_FKIndex3` (`asistenciasvalor_id`),
  CONSTRAINT `asistenciastemporal_ibfk_1` FOREIGN KEY (`cursos_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `asistenciastemporal_ibfk_2` FOREIGN KEY (`alumnos_id`) REFERENCES `alumnos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `asistenciastemporal_ibfk_3` FOREIGN KEY (`asistenciasvalor_id`) REFERENCES `asistenciasvalor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of asistenciastemporal
-- ----------------------------

-- ----------------------------
-- Table structure for `asistenciasvalor`
-- ----------------------------
DROP TABLE IF EXISTS `asistenciasvalor`;
CREATE TABLE `asistenciasvalor` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `clave` varchar(3) collate utf8_spanish_ci NOT NULL,
  `nombre` varchar(30) collate utf8_spanish_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of asistenciasvalor
-- ----------------------------
INSERT INTO `asistenciasvalor` VALUES ('0', 'FTA', 'Falta');
INSERT INTO `asistenciasvalor` VALUES ('1', 'AST', 'Asistencia');

-- ----------------------------
-- Table structure for `aulas`
-- ----------------------------
DROP TABLE IF EXISTS `aulas`;
CREATE TABLE `aulas` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `clave` varchar(6) collate utf8_spanish_ci NOT NULL,
  `nombre` varchar(30) collate utf8_spanish_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of aulas
-- ----------------------------
INSERT INTO `aulas` VALUES ('2', 'A26', 'Aula 26');
INSERT INTO `aulas` VALUES ('3', 'A27', 'Aula 27');
INSERT INTO `aulas` VALUES ('4', 'A28', 'Aula 28');
INSERT INTO `aulas` VALUES ('5', 'A29', 'Aula 29');
INSERT INTO `aulas` VALUES ('6', 'A30', 'Aula 30');
INSERT INTO `aulas` VALUES ('7', 'A01', 'Aula 01');
INSERT INTO `aulas` VALUES ('8', 'A02', 'Aula 02');
INSERT INTO `aulas` VALUES ('9', 'A03', 'Aula 03');
INSERT INTO `aulas` VALUES ('10', 'A04', 'Aula 04');
INSERT INTO `aulas` VALUES ('11', 'A05', 'Aula 05');
INSERT INTO `aulas` VALUES ('12', 'A06', 'Aula 06');
INSERT INTO `aulas` VALUES ('13', 'A07', 'Aula 07');
INSERT INTO `aulas` VALUES ('14', 'A08', 'Aula 08');
INSERT INTO `aulas` VALUES ('15', 'A09', 'Aula 09');
INSERT INTO `aulas` VALUES ('16', 'A10', 'Aula 10');
INSERT INTO `aulas` VALUES ('17', 'A11', 'Aula 11');
INSERT INTO `aulas` VALUES ('18', 'A12', 'Aula 12');
INSERT INTO `aulas` VALUES ('19', 'A13', 'Aula 13');
INSERT INTO `aulas` VALUES ('20', 'A14', 'Aula 14');
INSERT INTO `aulas` VALUES ('21', 'A15', 'Aula 15');
INSERT INTO `aulas` VALUES ('22', 'A16', 'Aula 16');
INSERT INTO `aulas` VALUES ('23', 'A17', 'Aula 17');
INSERT INTO `aulas` VALUES ('24', 'A18', 'Aula 18');
INSERT INTO `aulas` VALUES ('25', 'A19', 'Aula 19');
INSERT INTO `aulas` VALUES ('26', 'A20', 'Aula 20');
INSERT INTO `aulas` VALUES ('27', 'A21', 'Aula 21');
INSERT INTO `aulas` VALUES ('28', 'A22', 'Aula 22');
INSERT INTO `aulas` VALUES ('29', 'A23', 'Aula 23');
INSERT INTO `aulas` VALUES ('30', 'A24', 'Aula 24');
INSERT INTO `aulas` VALUES ('31', 'A25', 'Aula 25');
INSERT INTO `aulas` VALUES ('32', 'A31', 'Aula 31');

-- ----------------------------
-- Table structure for `bloque`
-- ----------------------------
DROP TABLE IF EXISTS `bloque`;
CREATE TABLE `bloque` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `periodo_id` int(10) unsigned NOT NULL,
  `inicio` datetime default NULL,
  `fin` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `bloque_FKIndex1` (`periodo_id`),
  CONSTRAINT `bloque_ibfk_1` FOREIGN KEY (`periodo_id`) REFERENCES `periodo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=250 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bloque
-- ----------------------------

-- ----------------------------
-- Table structure for `bloquehorario`
-- ----------------------------
DROP TABLE IF EXISTS `bloquehorario`;
CREATE TABLE `bloquehorario` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `departamento_id` int(10) unsigned NOT NULL,
  `inicio` time default NULL,
  `fin` time default NULL,
  `nombre` varchar(50) character set latin1 default NULL,
  PRIMARY KEY  (`id`),
  KEY `bloques_FKIndex1` (`departamento_id`),
  CONSTRAINT `bloquehorario_ibfk_1` FOREIGN KEY (`departamento_id`) REFERENCES `departamento` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bloquehorario
-- ----------------------------

-- ----------------------------
-- Table structure for `bloquesalumnos`
-- ----------------------------
DROP TABLE IF EXISTS `bloquesalumnos`;
CREATE TABLE `bloquesalumnos` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `bloque_id` int(10) unsigned NOT NULL,
  `periodosalumnos_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `bloquesalumnos_FKIndex1` (`periodosalumnos_id`),
  KEY `bloquesalumnos_FKIndex2` (`bloque_id`),
  CONSTRAINT `bloquesalumnos_ibfk_2` FOREIGN KEY (`bloque_id`) REFERENCES `bloque` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bloquesalumnos_ibfk_3` FOREIGN KEY (`periodosalumnos_id`) REFERENCES `periodosalumnos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16569 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bloquesalumnos
-- ----------------------------

-- ----------------------------
-- Table structure for `calificaciones`
-- ----------------------------
DROP TABLE IF EXISTS `calificaciones`;
CREATE TABLE `calificaciones` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `oportunidades_id` int(10) unsigned NOT NULL,
  `cursos_id` int(10) unsigned NOT NULL,
  `alumnos_id` int(10) unsigned NOT NULL,
  `valor` varchar(3) collate utf8_spanish_ci NOT NULL,
  `saved_at` datetime default NULL,
  `modified_in` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `calificacion_FKIndex1` (`cursos_id`),
  KEY `calificacion_FKIndex2` (`alumnos_id`),
  KEY `calificacion_FKIndex3` (`oportunidades_id`),
  CONSTRAINT `calificaciones_ibfk_1` FOREIGN KEY (`cursos_id`) REFERENCES `cursos` (`id`),
  CONSTRAINT `calificaciones_ibfk_2` FOREIGN KEY (`alumnos_id`) REFERENCES `alumnos` (`id`),
  CONSTRAINT `calificaciones_ibfk_3` FOREIGN KEY (`oportunidades_id`) REFERENCES `oportunidades` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=82990 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of calificaciones
-- ----------------------------

-- ----------------------------
-- Table structure for `calificacionesparciales`
-- ----------------------------
DROP TABLE IF EXISTS `calificacionesparciales`;
CREATE TABLE `calificacionesparciales` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cursos_id` int(10) unsigned NOT NULL,
  `alumnos_id` int(10) unsigned NOT NULL,
  `periodo` int(10) unsigned NOT NULL,
  `valor` varchar(3) collate utf8_spanish_ci NOT NULL,
  `saved_at` datetime default NULL,
  `modified_in` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `calificacionparcial_FKIndex1` (`cursos_id`),
  KEY `calificacionparcial_FKIndex2` (`alumnos_id`),
  CONSTRAINT `calificacionesparciales_ibfk_1` FOREIGN KEY (`cursos_id`) REFERENCES `cursos` (`id`),
  CONSTRAINT `calificacionesparciales_ibfk_2` FOREIGN KEY (`alumnos_id`) REFERENCES `alumnos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54590 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of calificacionesparciales
-- ----------------------------

-- ----------------------------
-- Table structure for `categorias`
-- ----------------------------
DROP TABLE IF EXISTS `categorias`;
CREATE TABLE `categorias` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `acronimo` varchar(3) collate utf8_spanish_ci NOT NULL,
  `nombre` varchar(30) collate utf8_spanish_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of categorias
-- ----------------------------
INSERT INTO `categorias` VALUES ('1', 'PRC', 'PARCIALES');
INSERT INTO `categorias` VALUES ('2', 'CAL', 'CALIFICACIONES');
INSERT INTO `categorias` VALUES ('3', 'AST', 'ASISTENCIAS');
INSERT INTO `categorias` VALUES ('4', 'DSC', 'DESCANSOS');
INSERT INTO `categorias` VALUES ('5', 'CRS', 'CURSOS');
INSERT INTO `categorias` VALUES ('6', 'ALU', 'ALUMNOS');
INSERT INTO `categorias` VALUES ('7', 'PLN', 'PLANTILLA');
INSERT INTO `categorias` VALUES ('8', 'PRF', 'PROFESORES');

-- ----------------------------
-- Table structure for `ciclos`
-- ----------------------------
DROP TABLE IF EXISTS `ciclos`;
CREATE TABLE `ciclos` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `numero` varchar(6) collate utf8_spanish_ci NOT NULL,
  `inicio` date NOT NULL,
  `fin` date NOT NULL,
  `activo` varchar(1) collate utf8_spanish_ci NOT NULL,
  `avance` varchar(1) collate utf8_spanish_ci NOT NULL,
  `abierto` varchar(1) collate utf8_spanish_ci default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of ciclos
-- ----------------------------
INSERT INTO `ciclos` VALUES ('6', '2010-B', '2010-07-28', '2011-01-31', '1', '0', '1');

-- ----------------------------
-- Table structure for `competenciagenerica`
-- ----------------------------
DROP TABLE IF EXISTS `competenciagenerica`;
CREATE TABLE `competenciagenerica` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `nombre` varchar(50) character set latin1 NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of competenciagenerica
-- ----------------------------
INSERT INTO `competenciagenerica` VALUES ('1', 'ComunicaciÃ³n');
INSERT INTO `competenciagenerica` VALUES ('2', 'Pensamiento matemÃ¡tico');
INSERT INTO `competenciagenerica` VALUES ('3', 'ComprensiÃ³n de la naturaleza');
INSERT INTO `competenciagenerica` VALUES ('4', 'ComprensiÃ³n del ser humano y ciudadania');
INSERT INTO `competenciagenerica` VALUES ('5', 'FormaciÃ³n para el bienestar');

-- ----------------------------
-- Table structure for `competenciasgenericasmaterias`
-- ----------------------------
DROP TABLE IF EXISTS `competenciasgenericasmaterias`;
CREATE TABLE `competenciasgenericasmaterias` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `competenciagenerica_id` int(10) unsigned NOT NULL,
  `materias_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `competenciasgenericasmaterias_FKIndex1` (`materias_id`),
  KEY `competenciasgenericasmaterias_FKIndex2` (`competenciagenerica_id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of competenciasgenericasmaterias
-- ----------------------------
INSERT INTO `competenciasgenericasmaterias` VALUES ('1', '2', '111');
INSERT INTO `competenciasgenericasmaterias` VALUES ('2', '1', '113');
INSERT INTO `competenciasgenericasmaterias` VALUES ('3', '1', '114');
INSERT INTO `competenciasgenericasmaterias` VALUES ('4', '3', '115');
INSERT INTO `competenciasgenericasmaterias` VALUES ('5', '3', '116');
INSERT INTO `competenciasgenericasmaterias` VALUES ('6', '4', '117');
INSERT INTO `competenciasgenericasmaterias` VALUES ('7', '4', '118');
INSERT INTO `competenciasgenericasmaterias` VALUES ('8', '5', '119');
INSERT INTO `competenciasgenericasmaterias` VALUES ('9', '5', '120');
INSERT INTO `competenciasgenericasmaterias` VALUES ('10', '1', '121');
INSERT INTO `competenciasgenericasmaterias` VALUES ('13', '1', '124');
INSERT INTO `competenciasgenericasmaterias` VALUES ('14', '1', '125');
INSERT INTO `competenciasgenericasmaterias` VALUES ('15', '1', '126');
INSERT INTO `competenciasgenericasmaterias` VALUES ('16', '2', '127');
INSERT INTO `competenciasgenericasmaterias` VALUES ('17', '4', '128');
INSERT INTO `competenciasgenericasmaterias` VALUES ('18', '3', '129');
INSERT INTO `competenciasgenericasmaterias` VALUES ('19', '3', '130');
INSERT INTO `competenciasgenericasmaterias` VALUES ('20', '5', '131');
INSERT INTO `competenciasgenericasmaterias` VALUES ('21', '1', '134');
INSERT INTO `competenciasgenericasmaterias` VALUES ('22', '1', '135');
INSERT INTO `competenciasgenericasmaterias` VALUES ('23', '2', '136');
INSERT INTO `competenciasgenericasmaterias` VALUES ('24', '4', '137');
INSERT INTO `competenciasgenericasmaterias` VALUES ('25', '3', '138');
INSERT INTO `competenciasgenericasmaterias` VALUES ('26', '5', '139');
INSERT INTO `competenciasgenericasmaterias` VALUES ('27', '1', '158');
INSERT INTO `competenciasgenericasmaterias` VALUES ('28', '1', '159');
INSERT INTO `competenciasgenericasmaterias` VALUES ('29', '2', '160');
INSERT INTO `competenciasgenericasmaterias` VALUES ('30', '4', '161');
INSERT INTO `competenciasgenericasmaterias` VALUES ('31', '4', '162');
INSERT INTO `competenciasgenericasmaterias` VALUES ('32', '3', '163');
INSERT INTO `competenciasgenericasmaterias` VALUES ('33', '5', '164');

-- ----------------------------
-- Table structure for `configuracionasistencias`
-- ----------------------------
DROP TABLE IF EXISTS `configuracionasistencias`;
CREATE TABLE `configuracionasistencias` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `campo` varchar(50) character set latin1 NOT NULL,
  `valor` varchar(255) character set latin1 default NULL,
  `titulo` varchar(100) character set latin1 default ' titulo',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of configuracionasistencias
-- ----------------------------

-- ----------------------------
-- Table structure for `contratoinfo`
-- ----------------------------
DROP TABLE IF EXISTS `contratoinfo`;
CREATE TABLE `contratoinfo` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `profesores_id` int(10) unsigned NOT NULL,
  `hasignadas` varchar(10) default NULL,
  `hfgrupo` varchar(10) default NULL,
  `hdescarga` varchar(10) default NULL,
  `perfil` varchar(250) character set latin1 default NULL,
  `turno` varchar(1) character set latin1 default NULL,
  `gradoestudio` varchar(250) character set latin1 default NULL,
  `tipo` varchar(40) character set latin1 default NULL,
  `nombramiento` varchar(200) character set latin1 default NULL,
  `asignatura` int(10) unsigned default NULL,
  PRIMARY KEY  (`id`),
  KEY `contrato_info_FKIndex1` (`profesores_id`),
  CONSTRAINT `contratoinfo_ibfk_1` FOREIGN KEY (`profesores_id`) REFERENCES `profesores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of contratoinfo
-- ----------------------------

-- ----------------------------
-- Table structure for `cursoprovisional`
-- ----------------------------
DROP TABLE IF EXISTS `cursoprovisional`;
CREATE TABLE `cursoprovisional` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `profesores_id` int(10) unsigned NOT NULL,
  `cursos_id` int(10) unsigned NOT NULL,
  `inicio` date default NULL,
  `fin` date default NULL,
  PRIMARY KEY  (`id`),
  KEY `cursoprovisional_FKIndex1` (`cursos_id`),
  KEY `cursoprovisional_FKIndex2` (`profesores_id`),
  CONSTRAINT `cursoprovisional_ibfk_1` FOREIGN KEY (`cursos_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `cursoprovisional_ibfk_2` FOREIGN KEY (`profesores_id`) REFERENCES `profesores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cursoprovisional
-- ----------------------------

-- ----------------------------
-- Table structure for `cursos`
-- ----------------------------
DROP TABLE IF EXISTS `cursos`;
CREATE TABLE `cursos` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `estado_id` int(10) unsigned NOT NULL,
  `grupos_id` int(10) unsigned NOT NULL,
  `materias_id` int(10) unsigned NOT NULL,
  `profesores_id` int(10) unsigned NOT NULL,
  `observaciones` text collate utf8_spanish_ci,
  `inicio` date default NULL,
  PRIMARY KEY  (`id`),
  KEY `curso_FKIndex1` (`grupos_id`),
  KEY `curso_FKIndex2` (`materias_id`),
  KEY `curso_FKIndex3` (`profesores_id`),
  KEY `cursos_FKIndex4` (`estado_id`),
  CONSTRAINT `cursos_ibfk_1` FOREIGN KEY (`grupos_id`) REFERENCES `grupos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cursos_ibfk_2` FOREIGN KEY (`materias_id`) REFERENCES `materias` (`id`),
  CONSTRAINT `cursos_ibfk_3` FOREIGN KEY (`profesores_id`) REFERENCES `profesores` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4596 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of cursos
-- ----------------------------

-- ----------------------------
-- Table structure for `departamento`
-- ----------------------------
DROP TABLE IF EXISTS `departamento`;
CREATE TABLE `departamento` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `nombre` varchar(200) character set latin1 default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of departamento
-- ----------------------------
INSERT INTO `departamento` VALUES ('26', 'Ciencias HumanÃ­sticas');
INSERT INTO `departamento` VALUES ('27', 'Ciencias Formales');
INSERT INTO `departamento` VALUES ('28', 'Ciencias Experimentales');
INSERT INTO `departamento` VALUES ('29', 'Ciencias HistÃ³rico Sociales');
INSERT INTO `departamento` VALUES ('30', 'Lengua y Literatura');

-- ----------------------------
-- Table structure for `dias`
-- ----------------------------
DROP TABLE IF EXISTS `dias`;
CREATE TABLE `dias` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `nombre` varchar(12) collate utf8_spanish_ci default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of dias
-- ----------------------------
INSERT INTO `dias` VALUES ('1', 'Lunes');
INSERT INTO `dias` VALUES ('2', 'Martes');
INSERT INTO `dias` VALUES ('3', 'MiÃ©rcoles');
INSERT INTO `dias` VALUES ('4', 'Jueves');
INSERT INTO `dias` VALUES ('5', 'Viernes');
INSERT INTO `dias` VALUES ('6', 'SÃ¡bado');
INSERT INTO `dias` VALUES ('7', 'Domingo');

-- ----------------------------
-- Table structure for `distribucion`
-- ----------------------------
DROP TABLE IF EXISTS `distribucion`;
CREATE TABLE `distribucion` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `aulas_id` int(10) unsigned NOT NULL,
  `grado` varchar(1) character set latin1 default NULL,
  `letra` varchar(1) character set latin1 default NULL,
  PRIMARY KEY  (`id`),
  KEY `distribucion_FKIndex1` (`aulas_id`),
  CONSTRAINT `distribucion_ibfk_1` FOREIGN KEY (`aulas_id`) REFERENCES `aulas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of distribucion
-- ----------------------------

-- ----------------------------
-- Table structure for `estado`
-- ----------------------------
DROP TABLE IF EXISTS `estado`;
CREATE TABLE `estado` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `clave` varchar(3) collate utf8_spanish_ci default NULL,
  `nombre` varchar(30) collate utf8_spanish_ci default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of estado
-- ----------------------------
INSERT INTO `estado` VALUES ('1', 'pro', 'En Proceso');
INSERT INTO `estado` VALUES ('2', 'rev', 'En Revision');
INSERT INTO `estado` VALUES ('3', 'apr', 'Aprobado');
INSERT INTO `estado` VALUES ('4', 'rec', 'Rechazado');

-- ----------------------------
-- Table structure for `eventos`
-- ----------------------------
DROP TABLE IF EXISTS `eventos`;
CREATE TABLE `eventos` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `clave` varchar(11) collate utf8_spanish_ci NOT NULL,
  `nombre` varchar(64) collate utf8_spanish_ci NOT NULL,
  `cardinalidad` varchar(1) collate utf8_spanish_ci NOT NULL,
  `tipo` varchar(1) collate utf8_spanish_ci NOT NULL,
  `categorias_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `eventos_FKIndex1` (`categorias_id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of eventos
-- ----------------------------
INSERT INTO `eventos` VALUES ('1', 'CRS-PER', 'Periodo ordinario de cursos', '1', 'P', '5');
INSERT INTO `eventos` VALUES ('2', 'CAL-ORD', 'Captura de ordinarios', '1', 'P', '2');
INSERT INTO `eventos` VALUES ('3', 'PRC-001', 'Captura de parciales I', '1', 'P', '1');
INSERT INTO `eventos` VALUES ('4', 'PRC-002', 'Captura de parciales II', '1', 'P', '1');
INSERT INTO `eventos` VALUES ('5', 'PRC-003', 'Captura de parciales III', '1', 'P', '1');
INSERT INTO `eventos` VALUES ('6', 'PRC-004', 'Captura de parciales IV', '1', 'P', '1');
INSERT INTO `eventos` VALUES ('7', 'DSC-VAC', 'Periodo vacacional', '+', 'P', '4');
INSERT INTO `eventos` VALUES ('8', 'DSC-SUS', 'Suspension de labores', '*', 'P', '4');
INSERT INTO `eventos` VALUES ('9', 'CAL-EXT', 'Captura de extraordinarios', '1', 'P', '2');
INSERT INTO `eventos` VALUES ('11', 'AST-CAP', 'Captura de asistencias', '1', 'P', '3');
INSERT INTO `eventos` VALUES ('13', 'ALU-NVO', 'Inscripcion de alumnos de nuevo ingreso', '1', 'P', '6');
INSERT INTO `eventos` VALUES ('14', 'ALU-GRP', 'Ubicacion de alumnos en los grupos', '1', 'P', '6');
INSERT INTO `eventos` VALUES ('17', 'ALU-INS', 'Inscripcion de alumnos', '1', 'P', '6');
INSERT INTO `eventos` VALUES ('18', 'ALU-CRS', 'Ubicacion de cursos para alumnos irregulares', '1', 'P', '6');
INSERT INTO `eventos` VALUES ('19', 'PLN-GEN', 'Generacion de la plantilla de cursos del ciclo escolar', '1', 'P', '7');
INSERT INTO `eventos` VALUES ('20', 'CRS-EXT', 'Periodo extraordinario de cursos', '1', 'P', '5');
INSERT INTO `eventos` VALUES ('21', 'ALU-CRS-ESP', 'Ubicacion de cursos para alumnos irregulares (ESPECIAL)', '*', 'E', '6');
INSERT INTO `eventos` VALUES ('22', 'ALU-INS-ESP', 'Inscripcion de alumnos (ESPECIAL)', '*', 'E', '6');
INSERT INTO `eventos` VALUES ('23', 'ALU-GRP-ESP', 'Ubicacion de alumnos en los grupos (ESPECIAL)', '*', 'E', '6');
INSERT INTO `eventos` VALUES ('24', 'ALU-NVO-ESP', 'Inscripcion de alumnos de nuevo ingreso (ESPECIAL)', '*', 'E', '6');
INSERT INTO `eventos` VALUES ('25', 'AST-CAP-ESP', 'Captura de asistencias (ESPECIAL)', '*', 'E', '3');
INSERT INTO `eventos` VALUES ('26', 'CAL-EXT-ESP', 'Captura de extraordinarios (ESPECIAL)', '*', 'E', '2');
INSERT INTO `eventos` VALUES ('27', 'PRC-004-ESP', 'Captura de parciales IV (ESPECIAL)', '*', 'E', '1');
INSERT INTO `eventos` VALUES ('28', 'PRC-003-ESP', 'Captura de parciales III (ESPECIAL)', '*', 'E', '1');
INSERT INTO `eventos` VALUES ('29', 'PRC-002-ESP', 'Captura de parciales II (ESPECIAL)', '*', 'E', '1');
INSERT INTO `eventos` VALUES ('30', 'PRC-001-ESP', 'Captura de parciales I (ESPECIAL)', '*', 'E', '1');
INSERT INTO `eventos` VALUES ('31', 'CAL-ORD-ESP', 'Captura de ordinarios (ESPECIAL)', '*', 'E', '2');
INSERT INTO `eventos` VALUES ('32', 'PLN-GEN-ESP', 'Generacion de la plantilla de cursos del ciclo escolar (ESPECIAL', '*', 'E', '7');
INSERT INTO `eventos` VALUES ('33', 'CPC-001', 'Captura de parciales I Materia Introductoria', '1', 'P', '1');
INSERT INTO `eventos` VALUES ('34', 'CPC-002', 'Captura de parciales II Materia Introductoria', '1', 'P', '1');
INSERT INTO `eventos` VALUES ('35', 'CPC-003', 'Captura de parciales III Materia Introductoria', '1', 'P', '1');
INSERT INTO `eventos` VALUES ('36', 'CPC-004', 'Captura de parciales IV Materia Introductoria', '1', 'P', '1');
INSERT INTO `eventos` VALUES ('37', 'CCA-ORD', 'Captura de ordinarios Materia Introductoria', '1', 'P', '2');
INSERT INTO `eventos` VALUES ('38', 'CCA-EXT', 'Captura de extraordinarios Materia Introductoria', '1', 'P', '2');
INSERT INTO `eventos` VALUES ('39', 'CPC-004-ESP', 'Captura de parciales IV Materia Introductoria (ESPECIAL)', '*', 'E', '1');
INSERT INTO `eventos` VALUES ('40', 'CPC-003-ESP', 'Captura de parciales III Materia Introductoria (ESPECIAL)', '*', 'E', '1');
INSERT INTO `eventos` VALUES ('41', 'CPC-002-ESP', 'Captura de parciales II Materia Introductoria(ESPECIAL)', '*', 'E', '1');
INSERT INTO `eventos` VALUES ('42', 'CPC-001-ESP', 'Captura de parciales I Materia Introductoria(ESPECIAL) ', '*', 'E', '1');
INSERT INTO `eventos` VALUES ('43', 'CCA-ORD-ESP', 'Captura de ordinarios Materia Introductoria(ESPECIAL)', '*', 'E', '2');
INSERT INTO `eventos` VALUES ('44', 'CCA-EXT-ESP', 'Captura de extraordinarios Materia Introductoria(ESPECIAL)', '*', 'E', '2');
INSERT INTO `eventos` VALUES ('45', 'CAS-CAP', 'Captura de asistencias Materia Introductoria', '1', 'P', '3');
INSERT INTO `eventos` VALUES ('46', 'CAS-CAP-ESP', 'Captura de asistenciass Materia Introductoria (ESPECIAL)', '*', 'E', '3');
INSERT INTO `eventos` VALUES ('47', 'CRS-INT-INI', 'Inicio de cursos de 1 semestre.Tipo Introductoria obligatoria', '1', 'P', '5');
INSERT INTO `eventos` VALUES ('48', 'CRS-PER-INI', 'Inicio de cursos de 1 semestre.Segundo periodo', '1', 'P', '5');

-- ----------------------------
-- Table structure for `faltas`
-- ----------------------------
DROP TABLE IF EXISTS `faltas`;
CREATE TABLE `faltas` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `alumnoscursos_id` int(10) unsigned NOT NULL,
  `cantidad` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `faltas_FKIndex1` (`alumnoscursos_id`),
  CONSTRAINT `faltas_ibfk_1` FOREIGN KEY (`alumnoscursos_id`) REFERENCES `alumnoscursos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=20493 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of faltas
-- ----------------------------

-- ----------------------------
-- Table structure for `grupos`
-- ----------------------------
DROP TABLE IF EXISTS `grupos`;
CREATE TABLE `grupos` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `oferta_id` int(10) unsigned NOT NULL,
  `ciclos_id` int(10) unsigned NOT NULL,
  `grado` int(10) unsigned NOT NULL,
  `letra` varchar(1) collate utf8_spanish_ci NOT NULL,
  `turno` varchar(1) collate utf8_spanish_ci NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `grupos_FKIndex1` (`ciclos_id`),
  KEY `grupos_FKIndex2` (`oferta_id`),
  CONSTRAINT `grupos_ibfk_1` FOREIGN KEY (`ciclos_id`) REFERENCES `ciclos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=579 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of grupos
-- ----------------------------
INSERT INTO `grupos` VALUES ('512', '2', '6', '1', 'A', 'M');
INSERT INTO `grupos` VALUES ('513', '2', '6', '1', 'B', 'M');
INSERT INTO `grupos` VALUES ('514', '2', '6', '1', 'C', 'M');
INSERT INTO `grupos` VALUES ('515', '2', '6', '1', 'D', 'M');
INSERT INTO `grupos` VALUES ('516', '2', '6', '1', 'E', 'M');
INSERT INTO `grupos` VALUES ('517', '2', '6', '2', 'A', 'M');
INSERT INTO `grupos` VALUES ('518', '2', '6', '2', 'B', 'M');
INSERT INTO `grupos` VALUES ('519', '2', '6', '2', 'C', 'M');
INSERT INTO `grupos` VALUES ('520', '2', '6', '2', 'D', 'M');
INSERT INTO `grupos` VALUES ('521', '2', '6', '2', 'E', 'M');
INSERT INTO `grupos` VALUES ('522', '2', '6', '3', 'A', 'M');
INSERT INTO `grupos` VALUES ('523', '1', '6', '3', 'A', 'M');
INSERT INTO `grupos` VALUES ('524', '2', '6', '3', 'B', 'M');
INSERT INTO `grupos` VALUES ('525', '1', '6', '3', 'C', 'M');
INSERT INTO `grupos` VALUES ('526', '2', '6', '3', 'C', 'M');
INSERT INTO `grupos` VALUES ('527', '2', '6', '3', 'D', 'M');
INSERT INTO `grupos` VALUES ('528', '1', '6', '3', 'D', 'M');
INSERT INTO `grupos` VALUES ('529', '2', '6', '3', 'E', 'M');
INSERT INTO `grupos` VALUES ('530', '1', '6', '3', 'E', 'M');
INSERT INTO `grupos` VALUES ('531', '1', '6', '5', 'A', 'M');
INSERT INTO `grupos` VALUES ('532', '1', '6', '5', 'B', 'M');
INSERT INTO `grupos` VALUES ('533', '1', '6', '5', 'C', 'M');
INSERT INTO `grupos` VALUES ('534', '1', '6', '5', 'D', 'M');
INSERT INTO `grupos` VALUES ('535', '1', '6', '5', 'E', 'M');
INSERT INTO `grupos` VALUES ('536', '1', '6', '6', 'A', 'M');
INSERT INTO `grupos` VALUES ('537', '1', '6', '6', 'B', 'M');
INSERT INTO `grupos` VALUES ('538', '1', '6', '6', 'C', 'M');
INSERT INTO `grupos` VALUES ('539', '1', '6', '6', 'D', 'M');
INSERT INTO `grupos` VALUES ('540', '1', '6', '6', 'E', 'M');
INSERT INTO `grupos` VALUES ('541', '2', '6', '1', 'A', 'V');
INSERT INTO `grupos` VALUES ('542', '2', '6', '1', 'B', 'V');
INSERT INTO `grupos` VALUES ('543', '2', '6', '1', 'C', 'V');
INSERT INTO `grupos` VALUES ('544', '2', '6', '1', 'D', 'V');
INSERT INTO `grupos` VALUES ('545', '2', '6', '1', 'E', 'V');
INSERT INTO `grupos` VALUES ('546', '2', '6', '2', 'A', 'V');
INSERT INTO `grupos` VALUES ('547', '2', '6', '2', 'B', 'V');
INSERT INTO `grupos` VALUES ('548', '2', '6', '2', 'C', 'V');
INSERT INTO `grupos` VALUES ('549', '2', '6', '2', 'D', 'V');
INSERT INTO `grupos` VALUES ('550', '2', '6', '2', 'E', 'V');
INSERT INTO `grupos` VALUES ('551', '2', '6', '3', 'A', 'V');
INSERT INTO `grupos` VALUES ('552', '2', '6', '3', 'B', 'V');
INSERT INTO `grupos` VALUES ('553', '1', '6', '3', 'C', 'V');
INSERT INTO `grupos` VALUES ('554', '2', '6', '3', 'C', 'V');
INSERT INTO `grupos` VALUES ('555', '1', '6', '3', 'D', 'V');
INSERT INTO `grupos` VALUES ('556', '2', '6', '3', 'D', 'V');
INSERT INTO `grupos` VALUES ('557', '1', '6', '3', 'E', 'V');
INSERT INTO `grupos` VALUES ('558', '2', '6', '3', 'E', 'V');
INSERT INTO `grupos` VALUES ('559', '1', '6', '5', 'A', 'V');
INSERT INTO `grupos` VALUES ('560', '1', '6', '5', 'B', 'V');
INSERT INTO `grupos` VALUES ('561', '1', '6', '5', 'C', 'V');
INSERT INTO `grupos` VALUES ('562', '1', '6', '5', 'D', 'V');
INSERT INTO `grupos` VALUES ('563', '1', '6', '5', 'E', 'V');
INSERT INTO `grupos` VALUES ('564', '1', '6', '6', 'A', 'V');
INSERT INTO `grupos` VALUES ('565', '1', '6', '6', 'B', 'V');
INSERT INTO `grupos` VALUES ('566', '1', '6', '6', 'C', 'V');
INSERT INTO `grupos` VALUES ('567', '1', '6', '6', 'D', 'V');
INSERT INTO `grupos` VALUES ('568', '1', '6', '6', 'E', 'V');
INSERT INTO `grupos` VALUES ('569', '2', '6', '4', 'A', 'M');
INSERT INTO `grupos` VALUES ('570', '2', '6', '4', 'B', 'M');
INSERT INTO `grupos` VALUES ('571', '2', '6', '4', 'C', 'M');
INSERT INTO `grupos` VALUES ('572', '2', '6', '4', 'D', 'M');
INSERT INTO `grupos` VALUES ('573', '2', '6', '4', 'E', 'M');
INSERT INTO `grupos` VALUES ('574', '2', '6', '4', 'A', 'V');
INSERT INTO `grupos` VALUES ('575', '2', '6', '4', 'B', 'V');
INSERT INTO `grupos` VALUES ('576', '2', '6', '4', 'C', 'V');
INSERT INTO `grupos` VALUES ('577', '2', '6', '4', 'D', 'V');
INSERT INTO `grupos` VALUES ('578', '2', '6', '4', 'E', 'V');

-- ----------------------------
-- Table structure for `hcriterio`
-- ----------------------------
DROP TABLE IF EXISTS `hcriterio`;
CREATE TABLE `hcriterio` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `inicio` time default NULL,
  `fin` time default NULL,
  `nombre` varchar(50) character set latin1 default NULL,
  `turno` varchar(1) character set latin1 default NULL,
  `numero` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of hcriterio
-- ----------------------------

-- ----------------------------
-- Table structure for `historial`
-- ----------------------------
DROP TABLE IF EXISTS `historial`;
CREATE TABLE `historial` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `ciclos_id` int(10) unsigned default NULL,
  `usuario` varchar(16) collate utf8_spanish_ci NOT NULL,
  `descripcion` varchar(255) collate utf8_spanish_ci NOT NULL,
  `controlador` varchar(32) collate utf8_spanish_ci NOT NULL,
  `accion` varchar(32) collate utf8_spanish_ci NOT NULL,
  `saved_at` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `historial_FKIndex1` (`ciclos_id`),
  CONSTRAINT `historial_ibfk_2` FOREIGN KEY (`ciclos_id`) REFERENCES `ciclos` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19285 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of historial
-- ----------------------------
INSERT INTO `historial` VALUES ('19260', null, 'root', 'Se elimino el ciclo 2008-B con el id 2', 'ciclos', 'eliminar', '2010-07-25 15:41:57');
INSERT INTO `historial` VALUES ('19261', null, 'root', 'Se elimino el ciclo 2009-A con el id 3', 'ciclos', 'eliminar', '2010-07-25 15:42:02');
INSERT INTO `historial` VALUES ('19262', null, 'root', 'Se elimino el ciclo 2009-B con el id 4', 'ciclos', 'eliminar', '2010-07-25 15:42:05');
INSERT INTO `historial` VALUES ('19263', null, 'root', 'Se activo el ciclo 2010-B con el id 6', 'ciclos', 'status', '2010-07-25 15:42:58');
INSERT INTO `historial` VALUES ('19264', '6', 'root', 'Se edito el evento Generacion de la plantilla de cursos del ciclo escolar para el ciclo 2010-B con el id 6 con los siguientes datos inicio:01/11/2010 fin:17/12/2010 activo:1', 'agenda', 'editar', '2010-07-25 15:52:34');
INSERT INTO `historial` VALUES ('19265', '6', 'root', 'Se edito el ciclo 2010-B con el id 6', 'ciclos', 'editar', '2010-07-25 15:54:30');
INSERT INTO `historial` VALUES ('19266', '6', 'root', 'Se edito el evento Generacion de la plantilla de cursos del ciclo escolar para el ciclo 2010-B con el id 6 con los siguientes datos inicio:06/07/2010 fin:17/12/2010 activo:1', 'agenda', 'editar', '2010-07-25 15:54:49');
INSERT INTO `historial` VALUES ('19267', '6', 'root', 'Se edito el ciclo 2010-B con el id 6', 'ciclos', 'editar', '2010-07-25 15:58:22');
INSERT INTO `historial` VALUES ('19268', '6', 'root', 'Se edito el evento Inscripcion de alumnos de nuevo ingreso para el ciclo 2010-B con el id 6 con los siguientes datos inicio:28/07/2010 fin:31/01/2011 activo:1', 'agenda', 'editar', '2010-07-25 15:59:30');
INSERT INTO `historial` VALUES ('19269', '6', 'root', 'Se edito el evento Ubicacion de alumnos en los grupos para el ciclo 2010-B con el id 6 con los siguientes datos inicio:28/07/2010 fin:31/01/2011 activo:1', 'agenda', 'editar', '2010-07-25 16:00:01');
INSERT INTO `historial` VALUES ('19270', '6', 'root', 'Se edito el evento Inscripcion de alumnos para el ciclo 2010-B con el id 6 con los siguientes datos inicio:28/07/2010 fin:31/01/2011 activo:1', 'agenda', 'editar', '2010-07-25 16:00:31');
INSERT INTO `historial` VALUES ('19271', '6', 'root', 'Se edito el evento Ubicacion de cursos para alumnos irregulares para el ciclo 2010-B con el id 6 con los siguientes datos inicio:28/07/2010 fin:31/01/2011 activo:1', 'agenda', 'editar', '2010-07-25 16:00:53');
INSERT INTO `historial` VALUES ('19272', '6', 'root', 'Se edito el evento Generacion de la plantilla de cursos del ciclo escolar para el ciclo 2010-B con el id 6 con los siguientes datos inicio:28/07/2010 fin:31/01/2011 activo:1', 'agenda', 'editar', '2010-07-25 16:01:22');
INSERT INTO `historial` VALUES ('19273', '6', 'root', 'Se edito el evento Captura de ordinarios para el ciclo 2010-B con el id 6 con los siguientes datos inicio:08/12/2010 fin:07/01/2011 activo:1', 'agenda', 'editar', '2010-07-25 16:03:28');
INSERT INTO `historial` VALUES ('19274', '6', 'root', 'Se edito el evento Captura de extraordinarios para el ciclo 2010-B con el id 6 con los siguientes datos inicio:03/01/2011 fin:21/01/2011 activo:1', 'agenda', 'editar', '2010-07-25 16:03:36');
INSERT INTO `historial` VALUES ('19275', '6', 'root', 'Se edito el evento Captura de ordinarios Materia Introductoria para el ciclo 2010-B con el id 6 con los siguientes datos inicio:04/10/2010 fin:15/10/2010 activo:1', 'agenda', 'editar', '2010-07-25 16:03:45');
INSERT INTO `historial` VALUES ('19276', '6', 'root', 'Se edito el evento Captura de extraordinarios Materia Introductoria para el ciclo 2010-B con el id 6 con los siguientes datos inicio:11/10/2010 fin:22/10/2010 activo:1', 'agenda', 'editar', '2010-07-25 16:03:59');
INSERT INTO `historial` VALUES ('19277', '6', 'root', 'Se edito el evento Captura de parciales I para el ciclo 2010-B con el id 6 con los siguientes datos inicio:04/10/2010 fin:22/10/2010 activo:1', 'agenda', 'editar', '2010-07-25 16:05:38');
INSERT INTO `historial` VALUES ('19278', '6', 'root', 'Se edito el evento Captura de parciales I para el ciclo 2010-B con el id 6 con los siguientes datos inicio:04/10/2010 fin:22/10/2010 activo:0', 'agenda', 'editar', '2010-07-25 16:05:52');
INSERT INTO `historial` VALUES ('19279', '6', 'root', 'Se edito el evento Captura de ordinarios para el ciclo 2010-B con el id 6 con los siguientes datos inicio:08/12/2010 fin:07/01/2011 activo:0', 'agenda', 'editar', '2010-07-25 16:06:13');
INSERT INTO `historial` VALUES ('19280', '6', 'root', 'Se edito el evento Captura de extraordinarios para el ciclo 2010-B con el id 6 con los siguientes datos inicio:03/01/2011 fin:21/01/2011 activo:0', 'agenda', 'editar', '2010-07-25 16:06:21');
INSERT INTO `historial` VALUES ('19281', '6', 'root', 'Se edito el evento Captura de ordinarios Materia Introductoria para el ciclo 2010-B con el id 6 con los siguientes datos inicio:04/10/2010 fin:15/10/2010 activo:0', 'agenda', 'editar', '2010-07-25 16:06:27');
INSERT INTO `historial` VALUES ('19282', '6', 'root', 'Se edito el evento Captura de extraordinarios Materia Introductoria para el ciclo 2010-B con el id 6 con los siguientes datos inicio:11/10/2010 fin:22/10/2010 activo:0', 'agenda', 'editar', '2010-07-25 16:06:34');
INSERT INTO `historial` VALUES ('19283', '6', 'root', 'Se edito el evento Periodo vacacional para el ciclo 2010-B con el id 6 con los siguientes datos inicio:01/08/2010 fin:15/08/2010 activo:1', 'agenda', 'editar', '2010-07-25 16:07:33');
INSERT INTO `historial` VALUES ('19284', null, 'root', 'Se agrego el usuario admin a los grupos: administradores', 'usuarioscoordinacion', 'agregar', '2010-07-25 16:15:25');

-- ----------------------------
-- Table structure for `horarioprovisional`
-- ----------------------------
DROP TABLE IF EXISTS `horarioprovisional`;
CREATE TABLE `horarioprovisional` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `dias_id` int(10) unsigned NOT NULL,
  `aulas_id` int(10) unsigned NOT NULL,
  `cursoprovisional_id` int(10) unsigned NOT NULL,
  `inicio` time default NULL,
  `fin` time default NULL,
  PRIMARY KEY  (`id`),
  KEY `horarioprovisional_FKIndex1` (`cursoprovisional_id`),
  KEY `horarioprovisional_FKIndex2` (`aulas_id`),
  KEY `horarioprovisional_FKIndex3` (`dias_id`),
  CONSTRAINT `horarioprovisional_ibfk_1` FOREIGN KEY (`cursoprovisional_id`) REFERENCES `cursoprovisional` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `horarioprovisional_ibfk_2` FOREIGN KEY (`aulas_id`) REFERENCES `aulas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `horarioprovisional_ibfk_3` FOREIGN KEY (`dias_id`) REFERENCES `dias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of horarioprovisional
-- ----------------------------

-- ----------------------------
-- Table structure for `horarios`
-- ----------------------------
DROP TABLE IF EXISTS `horarios`;
CREATE TABLE `horarios` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cursos_id` int(10) unsigned NOT NULL,
  `dias_id` int(10) unsigned NOT NULL,
  `inicio` time NOT NULL,
  `fin` time NOT NULL,
  `aulas_id` int(10) unsigned default NULL,
  PRIMARY KEY  (`id`),
  KEY `horario_FKIndex1` (`cursos_id`),
  KEY `horario_FKIndex2` (`aulas_id`),
  KEY `horario_FKIndex3` (`dias_id`),
  CONSTRAINT `horarios_ibfk_2` FOREIGN KEY (`aulas_id`) REFERENCES `aulas` (`id`),
  CONSTRAINT `horarios_ibfk_3` FOREIGN KEY (`dias_id`) REFERENCES `dias` (`id`),
  CONSTRAINT `horarios_ibfk_5` FOREIGN KEY (`cursos_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8549 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of horarios
-- ----------------------------

-- ----------------------------
-- Table structure for `horariotemporal`
-- ----------------------------
DROP TABLE IF EXISTS `horariotemporal`;
CREATE TABLE `horariotemporal` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cursos_id` int(10) unsigned NOT NULL,
  `dias_id` int(10) unsigned NOT NULL,
  `inicio` time NOT NULL,
  `fin` time NOT NULL,
  `aulas_id` int(10) unsigned default NULL,
  PRIMARY KEY  (`id`),
  KEY `horariotemporal_FKIndex1` (`cursos_id`),
  KEY `horariotemporal_FKIndex3` (`dias_id`),
  CONSTRAINT `horariotemporal_ibfk_3` FOREIGN KEY (`dias_id`) REFERENCES `dias` (`id`),
  CONSTRAINT `horariotemporal_ibfk_5` FOREIGN KEY (`cursos_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of horariotemporal
-- ----------------------------

-- ----------------------------
-- Table structure for `inscripcion`
-- ----------------------------
DROP TABLE IF EXISTS `inscripcion`;
CREATE TABLE `inscripcion` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `periodoscursos_id` int(10) unsigned NOT NULL,
  `periodosalumnos_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `Table_51_FKIndex1` (`periodosalumnos_id`),
  KEY `Table_51_FKIndex2` (`periodoscursos_id`),
  CONSTRAINT `inscripcion_ibfk_1` FOREIGN KEY (`periodosalumnos_id`) REFERENCES `periodosalumnos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `inscripcion_ibfk_2` FOREIGN KEY (`periodoscursos_id`) REFERENCES `periodoscursos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13145 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of inscripcion
-- ----------------------------

-- ----------------------------
-- Table structure for `materiacriterio`
-- ----------------------------
DROP TABLE IF EXISTS `materiacriterio`;
CREATE TABLE `materiacriterio` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `materias_id` int(10) unsigned NOT NULL,
  `opcionA` int(10) unsigned default NULL,
  `opcionB` int(10) unsigned default NULL,
  PRIMARY KEY  (`id`),
  KEY `materiacriterio_FKIndex1` (`materias_id`),
  CONSTRAINT `materiacriterio_ibfk_1` FOREIGN KEY (`materias_id`) REFERENCES `materias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of materiacriterio
-- ----------------------------

-- ----------------------------
-- Table structure for `materiahrs`
-- ----------------------------
DROP TABLE IF EXISTS `materiahrs`;
CREATE TABLE `materiahrs` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `materias_id` int(10) unsigned NOT NULL,
  `horas` float default NULL,
  PRIMARY KEY  (`id`),
  KEY `materiahrs_FKIndex1` (`materias_id`),
  CONSTRAINT `materiahrs_ibfk_1` FOREIGN KEY (`materias_id`) REFERENCES `materias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of materiahrs
-- ----------------------------

-- ----------------------------
-- Table structure for `materias`
-- ----------------------------
DROP TABLE IF EXISTS `materias`;
CREATE TABLE `materias` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `clave` varchar(12) collate utf8_spanish_ci NOT NULL,
  `nombre` varchar(60) collate utf8_spanish_ci NOT NULL,
  `descripcion` text collate utf8_spanish_ci,
  `semestre` int(10) unsigned NOT NULL,
  `tipo` varchar(3) collate utf8_spanish_ci NOT NULL,
  `creditos` int(10) unsigned default '0',
  `duracion` int(10) unsigned default '0',
  `horassemana` int(10) unsigned default '0',
  `competencia` varchar(3) collate utf8_spanish_ci default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=177 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of materias
-- ----------------------------
INSERT INTO `materias` VALUES ('5', 'G000104', 'MATEMATICAS I', '', '1', 'OBL', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('6', 'G000105', 'TALL DE LOGICA', '', '1', 'OBL', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('7', 'G000106', 'TALL DE PROG Y COMPUTO', '', '1', 'OBL', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('8', 'G000107', 'SEM DE APREN Y DESARROLLO', '', '1', 'OBL', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('9', 'G000102', 'LENGUA ESPAÃ‘OLA I', '', '1', 'OBL', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('10', 'G000103', 'LENGUA EXTRANJERA I', '', '1', 'OBL', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('11', 'G000101', 'INTRODUCCION AL ARTE', '', '1', 'OBL', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('12', 'G000205', 'MATEMATICAS II', '', '2', 'OBL', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('13', 'G000201', 'FISICA I', '', '2', 'OBL', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('14', 'G000206', 'QUIMICA I', '', '2', 'OBL', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('15', 'G000202', 'GEOGRAFIA', '', '2', 'OBL', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('16', 'G000203', 'LENGUA ESPAÃ‘OLA II', '', '2', 'OBL', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('17', 'G000204', 'LENGUA EXTRANJERA II', '', '2', 'OBL', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('18', 'G000207', 'SOCIOLOGIA', '', '2', 'OBL', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('19', 'G000208', 'TALL DE ARTE', '', '2', 'TLR', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('20', 'G000209', 'TALL DE EDUCACION FISICA', '', '2', 'TLR', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('21', 'G000306', 'MATEMATICAS III', '', '3', 'OBL', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('22', 'G000303', 'FISICA II', '', '3', 'OBL', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('23', 'G000307', 'QUIMICA II', '', '3', 'OBL', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('24', 'G000301', 'BIOLOGIA I', '', '3', 'OBL', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('25', 'G000304', 'LENGUA ESPAÃ‘OLA III', '', '3', 'OBL', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('26', 'G000305', 'LENGUA EXTRANJERA III', '', '3', 'OBL', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('27', 'G000302', 'ECONOMIA', '', '3', 'OBL', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('28', 'G000308', 'TALL DE ARTE', '', '3', 'TLR', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('29', 'G000309', 'TALL DE EDUCACION FISICA', '', '3', 'TLR', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('30', 'G000405', 'MATEMATICAS IV', '', '4', 'OBL', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('31', 'G000403', 'FISICA III', '', '4', 'OBL', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('32', 'G000406', 'QUIMICA III', '', '4', 'OBL', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('33', 'G000401', 'BIOLOGIA II', '', '4', 'OBL', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('34', 'G000402', 'FILOSOFIA I', '', '4', 'OBL', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('35', 'G000404', 'HISTORIA INTERNACIONAL', '', '4', 'OBL', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('36', 'G000407', 'TALL DE ARTE', '', '4', 'TLR', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('37', 'G000408', 'TALL DE EDUCACION FISICA', '', '4', 'TLR', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('38', 'G000502', 'FILOSOFIA II', '', '5', 'OBL', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('39', 'G000504', 'LITERATURA I', '', '5', 'OBL', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('40', 'G000503', 'HISTORIA NACIONAL', '', '5', 'OBL', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('41', 'G000501', 'ECOLOGIA', '', '5', 'OBL', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('42', 'G000506', 'PSICOLOGIA', '', '5', 'OBL', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('43', 'G0005056', 'TEORIA DE LA ORGANIZACION', '', '5', 'OPT', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('44', 'G0005052', 'SEMINARIO DE INV. SOCIAL', '', '5', 'OPT', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('45', 'G0005055', 'FISICA MODERNA', '', '5', 'OPT', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('46', 'G0005051', 'TEOR DE LA INFOR DOCUMENTAL', '', '5', 'OPT', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('47', 'G0005054', 'DIBUJO DE EXPRESION ', '', '5', 'OPT', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('48', 'G0005053', 'FISIOLOGIA', '', '5', 'OPT', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('49', 'G0005086', 'PROBLEMAS SOCIOECONOMICOS Y POLITICOS DE MEXICO', '', '5', 'TLR', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('50', 'G0005081', 'EXPRESION ESCRITA Y CREACION LITERARIA', '', '5', 'TLR', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('51', 'G0005083', 'INVESTIGACION DOCUMENTAL', '', '5', 'TLR', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('52', 'G0005085', 'ESTRATEGIAS P/SOLUCION DE PROBLEMAS', '', '5', 'TLR', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('53', 'G0005084', 'CREATIVIDAD ', '', '5', 'TLR', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('54', 'G000507', 'PRO DE EXTENSION Y DIFUSION CULTURAL', '', '5', 'PRO', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('55', 'G000601', 'FILOSOFIA III', '', '6', 'OBL', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('56', 'G000602', 'LITERATURA II', '', '6', 'OBL', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('57', 'G000603', 'HISTORIA REGIONAL', '', '6', 'OBL', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('58', 'G000607', 'SEMINARIO DE EDUCACION AMBIENTAL', '', '6', 'OBL', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('59', 'G0006047', 'RELACIONES HUM. Y DES. HUM.', '', '6', 'OPT', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('60', 'G000605B', 'ENFOQUES ADMINISTRATIVOS Y CONTEMPORANEOS', '', '6', 'OPT', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('61', 'G0006048', 'ANATOMIA', '', '6', 'OPT', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('62', 'G0006041', 'SOC. CIVIL Y CS. SOC.', '', '6', 'OPT', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('63', 'G000605C', 'BOTANICA', '', '6', 'OPT', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('64', 'G0006045', 'FUNDAMENTOS A LA QCA APLICADA', '', '6', 'OPT', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('65', 'G0006049', 'HISTOLOGIA', '', '6', 'OPT', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('66', 'G0006056', 'INTROD. A LA CONTABILIDAD', '', '6', 'OPT', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('67', 'G0006044', 'COMPUTACION', '', '6', 'OPT', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('68', 'G0006052', 'INTRODUCCION AL CALCULO', '', '6', 'OPT', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('69', 'G000605A', 'APRECIACION ARTISTICA', '', '6', 'OPT', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('70', 'G0006081', 'EXPRESION ESCRITA Y CREACION LITERARIA', '', '6', 'TLR', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('71', 'G0006085', 'INV. APL. A LAS CS. ECO-ADM.', '', '6', 'TLR', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('72', 'G0006082', 'MICROBIOLOGIA', '', '6', 'TLR', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('73', 'G0006083', 'MAQUETAS', '', '6', 'TLR', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('74', 'G000606', 'PRO DE EXTENSION Y DIFUSION CULTURAL', '', '6', 'PRO', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('75', 'G000507', 'ECOLOGIA', '', '5', 'PRO', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('76', 'G000507', 'HABILIDADES PARA CONTROLAR EL ENOJO', '', '5', 'PRO', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('77', 'G000507', 'EST DE GESTION Y DIF INSTITUCIONAL', '', '5', 'PRO', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('78', 'G000507', 'PROTECCION CIVIL', '', '5', 'PRO', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('79', 'G000507', 'DERECHOS HUMANOS', '', '5', 'PRO', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('80', 'G000507', 'COMUNICACION SOCIAL', '', '5', 'PRO', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('81', 'G000507', 'SALUD PUBLICA', '', '5', 'PRO', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('82', 'G000507', 'MANEJO INVERNADEROS', '', '5', 'PRO', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('83', 'G000507', 'ARTE Y CULTURA', '', '5', 'PRO', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('84', 'G000606', 'PRO DE EXTENSION Y DIFUSION CULTURAL', '', '6', 'PRO', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('85', 'G000606', 'MANEJO DE INVERNADEROS', '', '6', 'PRO', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('86', 'G000606', 'INTEGRACION CORAL', '', '6', 'PRO', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('87', 'G000606', 'EDUCACION AMBIENTAL', '', '6', 'PRO', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('88', 'G000606', 'LABOR SOCIAL', '', '6', 'PRO', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('89', 'G000606', 'COMUNICACION SOCIAL ', '', '6', 'PRO', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('90', 'G000606', 'SALUD PUBLICA', '', '6', 'PRO', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('91', 'sop_DERECHOS', 'DERECHOS HUMANOS', 'materia importada del sop.', '6', 'PRO', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('92', 'sop_INVERNAD', 'INVERNADERO', 'materia importada del sop.', '6', 'PRO', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('93', 'sop_ECOLOGIA', 'ECOLOGIA', 'materia importada del sop.', '6', 'PRO', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('94', 'sop_DE LA CU', 'DE LA CULTURA Y EL ARTE', 'materia importada del sop.', '6', 'PRO', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('95', 'sop_COMPUTO', 'COMPUTO', 'materia importada del sop.', '6', 'OPT', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('96', 'sop_CISSE', 'CISSE', 'materia importada del sop.', '6', 'PRO', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('97', 'sop_PROTECCI', 'PROTECCION CIVIL', 'materia importada del sop.', '6', 'PRO', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('98', 'sop_TECN. DE', 'TECN. DE LA INV. DOCUMENTAL', 'materia importada del sop.', '5', 'TLR', '0', '0', '0', '');
INSERT INTO `materias` VALUES ('111', 'C000107', 'MatemÃ¡tica y vida cotidiana I', 'DescripciÃ³n  	En la unidad de aprendizaje MatemÃ¡tica y vida cotidiana, se propone como estrategia de aprendizaje un modelo de proyectos diseÃ±ados, implementados y desarrollados a partir de situaciones y problemas de la vida diaria del estudiante.', '1', 'OBL', '5', '12', '4', 'gen');
INSERT INTO `materias` VALUES ('113', 'C000106', 'TecnologÃ­as de la informaciÃ³n I', 'La Unidad de Aprendizaje TecnologÃ­as de la InformaciÃ³n I, propicia el desarrollo de habilidades para la aplicaciÃ³n prÃ¡ctica de los sistemas de informaciÃ³n, en un esquema que permite gestionar el conocimiento a partir de su anÃ¡lisis e interpretaciÃ³n.', '1', 'OBL', '5', '12', '5', 'gen');
INSERT INTO `materias` VALUES ('114', 'C000105', 'Lengua extranjera I', 'La unidad de aprendizaje Lengua Extranjera I, pondrÃ¡ al alumno en contacto con las herramientas necesarias para poder expresarse y construir significados en el idioma inglÃ©s.', '1', 'OBL', '3', '12', '3', 'gen');
INSERT INTO `materias` VALUES ('115', 'C000101', 'ComprensiÃ³n de la ciencia', 'Esta unidad pretende que el estudiante desarrolle su pensamiento cientÃ­fico al analizar la realidad, sea social o natural, lo que exige procesos de reflexiÃ³n y abstracciÃ³n en aquello que despierta la curiosidad e interÃ©s humano universal.', '1', 'TLR', '5', '7', '8', 'gen');
INSERT INTO `materias` VALUES ('116', 'C000109', 'FÃ­sica I', 'Los aprendizajes de FÃ­sica contribuyen al desarrollo integral del joven en relaciÃ³n con la naturaleza, la tecnologÃ­a y con su ambiente, en el marco de una cultura cientÃ­fica.', '1', 'OBL', '7', '12', '5', 'gen');
INSERT INTO `materias` VALUES ('117', 'C000103', 'Taller de habilidades para el aprendizaje', 'La unidad Taller de habilidades para el aprendizaje, pretende que los alumnos desarrollen habilidades en la construcciÃ³n de sus aprendizajes, a travÃ©s de la sistematizaciÃ³n de tÃ©cnicas y estrategias que puedan ser aplicadas en diferentes contextos.', '1', 'TLR', '5', '7', '8', 'gen');
INSERT INTO `materias` VALUES ('118', 'C000108', 'ApreciaciÃ³n del arte', 'Este curso taller pretende desarrollar la capacidad de apreciaciÃ³n estÃ©tica y la comprensiÃ³n de los elementos constitutivos del arte, concebido como producto de un contexto social.', '1', 'TLR', '4', '12', '4', 'gen');
INSERT INTO `materias` VALUES ('119', 'C000102', 'Sexualidad responsable', 'En el transcurso de la presente unidad, el joven adolescente comprenderÃ¡ que la sexualidad es parte esencial de su identidad, la cual se ve influenciada por el contexto social, moldeando su expresiÃ³n y los roles, de acuerdo con el gÃ©nero.', '1', 'TLR', '5', '7', '8', 'gen');
INSERT INTO `materias` VALUES ('120', 'C000110', 'EducaciÃ³n para la salud', 'La relaciÃ³n del individuo con su ecosistema social, determinante del proceso de salud-enfermedad personal, hace pertinente el desarrollo de una cultura de autocuidado responsable que sustente las relaciones interpersonales y de grupo.', '1', 'OBL', '3', '12', '3', 'gen');
INSERT INTO `materias` VALUES ('121', 'C000104', 'DescripciÃ³n y comunicaciÃ³n', 'En esta unidad de aprendizaje el alumno encontrarÃ¡ los contenidos que dan continuidad a los aprendizajes de la vida escolar previa.', '1', 'OBL', '4', '12', '4', 'gen');
INSERT INTO `materias` VALUES ('124', 'C000205', 'Lengua Extranjera II', null, '2', 'OBL', '5', '19', '3', 'gen');
INSERT INTO `materias` VALUES ('125', 'C000204', 'ComprensiÃ³n y ExposiciÃ³n', null, '2', 'OBL', '5', '19', '3', 'gen');
INSERT INTO `materias` VALUES ('126', 'C000206', 'TecnologÃ­as de la InformaciÃ³n II', null, '2', 'OBL', '7', '19', '4', 'gen');
INSERT INTO `materias` VALUES ('127', 'C000207', 'MatemÃ¡ticas y Vida Cotidiana II', null, '2', 'OBL', '5', '19', '3', 'gen');
INSERT INTO `materias` VALUES ('128', 'C000208', 'Autoconocimiento y Personalidad', null, '2', 'OBL', '7', '19', '4', 'gen');
INSERT INTO `materias` VALUES ('129', 'C000201', 'QuÃ­mica I', null, '2', 'OBL', '7', '19', '4', 'gen');
INSERT INTO `materias` VALUES ('130', 'C000209', 'FÃ­sica II', null, '2', 'OBL', '7', '19', '4', 'gen');
INSERT INTO `materias` VALUES ('131', 'C000210', 'Acercamiento al Desarrollo Deportivo', null, '2', 'TLR', '3', '19', '2', 'gen');
INSERT INTO `materias` VALUES ('132', '00000', 'FÃSICA MODERNA', null, '5', 'OPT', null, null, null, null);
INSERT INTO `materias` VALUES ('133', '0000', 'TRADUCCIÃ’N DEL INGLÃ‰S', null, '5', 'TLR', null, null, null, null);
INSERT INTO `materias` VALUES ('134', 'c00301', 'ANALISIS Y ARGUMENTO', null, '3', 'OBL', '5', '19', '3', 'gen');
INSERT INTO `materias` VALUES ('135', 'C00302', 'LENGUA EXTRANJERA III', null, '3', 'OBL', '5', '19', '3', 'gen');
INSERT INTO `materias` VALUES ('136', 'C000303', 'MATEMATICAS Y CIENCIA I', null, '3', 'OBL', '5', '19', '3', 'gen');
INSERT INTO `materias` VALUES ('137', 'C00304', 'RAICES CULTURALES', null, '3', 'OBL', '5', '19', '3', 'gen');
INSERT INTO `materias` VALUES ('138', 'C00305', 'QUIMICA II', null, '3', 'OBL', '7', '19', '4', 'gen');
INSERT INTO `materias` VALUES ('139', 'C00306', 'RECREACION Y APROVECHAMIENTO DEL TIEMPO LIBRE', null, '3', 'OBL', '3', '19', '2', 'gen');
INSERT INTO `materias` VALUES ('140', 'G000507-1', 'INTEGRACION CORAL', null, '5', 'PRO', null, null, null, null);
INSERT INTO `materias` VALUES ('153', 'C000131', 'MatemÃ¡ticas Recreativas', 'TAE de Pensamiento MatemÃ¡tico', '3', 'OPT', '3', '19', '3', 'esp');
INSERT INTO `materias` VALUES ('158', 'C0001', 'CrÃ­tica y Propuesta', null, '4', 'OBL', '5', '19', '3', 'gen');
INSERT INTO `materias` VALUES ('159', 'C0002', 'Lengua Extranjera IV', null, '4', 'OBL', '5', '19', '3', 'gen');
INSERT INTO `materias` VALUES ('160', 'C0003', 'MatemÃ¡ticas y ciencia II', null, '4', 'OBL', '5', '19', '3', 'gen');
INSERT INTO `materias` VALUES ('161', 'C0004', 'Democracia y soberanÃ­a nacional', null, '4', 'OBL', '5', '19', '3', 'gen');
INSERT INTO `materias` VALUES ('162', 'C0005', 'FormaciÃ³n ciudadana', null, '4', 'OBL', '5', '19', '3', 'gen');
INSERT INTO `materias` VALUES ('163', 'C0006', 'BiologÃ­a I', null, '4', 'OBL', '7', '19', '4', 'gen');
INSERT INTO `materias` VALUES ('164', 'C0007', 'Actividad fÃ­sica y desarrollo personal', null, '4', 'OBL', '3', '19', '2', 'gen');

-- ----------------------------
-- Table structure for `oferta`
-- ----------------------------
DROP TABLE IF EXISTS `oferta`;
CREATE TABLE `oferta` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `nombre` varchar(40) collate utf8_spanish_ci default NULL,
  `clave` varchar(3) collate utf8_spanish_ci default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of oferta
-- ----------------------------
INSERT INTO `oferta` VALUES ('1', 'Bachillerato General', 'GEN');
INSERT INTO `oferta` VALUES ('2', 'Bachillerato por Competencias', 'COM');

-- ----------------------------
-- Table structure for `ofertasmaterias`
-- ----------------------------
DROP TABLE IF EXISTS `ofertasmaterias`;
CREATE TABLE `ofertasmaterias` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `oferta_id` int(10) unsigned NOT NULL,
  `materias_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `ofertasmaterias_FKIndex1` (`materias_id`),
  KEY `ofertasmaterias_FKIndex2` (`oferta_id`),
  CONSTRAINT `ofertasmaterias_ibfk_1` FOREIGN KEY (`materias_id`) REFERENCES `materias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ofertasmaterias_ibfk_2` FOREIGN KEY (`oferta_id`) REFERENCES `oferta` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=179 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of ofertasmaterias
-- ----------------------------
INSERT INTO `ofertasmaterias` VALUES ('1', '1', '5');
INSERT INTO `ofertasmaterias` VALUES ('2', '1', '6');
INSERT INTO `ofertasmaterias` VALUES ('3', '1', '7');
INSERT INTO `ofertasmaterias` VALUES ('4', '1', '8');
INSERT INTO `ofertasmaterias` VALUES ('5', '1', '9');
INSERT INTO `ofertasmaterias` VALUES ('6', '1', '10');
INSERT INTO `ofertasmaterias` VALUES ('7', '1', '11');
INSERT INTO `ofertasmaterias` VALUES ('8', '1', '12');
INSERT INTO `ofertasmaterias` VALUES ('9', '1', '13');
INSERT INTO `ofertasmaterias` VALUES ('10', '1', '14');
INSERT INTO `ofertasmaterias` VALUES ('11', '1', '15');
INSERT INTO `ofertasmaterias` VALUES ('12', '1', '16');
INSERT INTO `ofertasmaterias` VALUES ('13', '1', '17');
INSERT INTO `ofertasmaterias` VALUES ('14', '1', '18');
INSERT INTO `ofertasmaterias` VALUES ('15', '1', '19');
INSERT INTO `ofertasmaterias` VALUES ('16', '1', '20');
INSERT INTO `ofertasmaterias` VALUES ('17', '1', '21');
INSERT INTO `ofertasmaterias` VALUES ('18', '1', '22');
INSERT INTO `ofertasmaterias` VALUES ('19', '1', '23');
INSERT INTO `ofertasmaterias` VALUES ('20', '1', '24');
INSERT INTO `ofertasmaterias` VALUES ('21', '1', '25');
INSERT INTO `ofertasmaterias` VALUES ('22', '1', '26');
INSERT INTO `ofertasmaterias` VALUES ('23', '1', '27');
INSERT INTO `ofertasmaterias` VALUES ('24', '1', '28');
INSERT INTO `ofertasmaterias` VALUES ('25', '1', '29');
INSERT INTO `ofertasmaterias` VALUES ('26', '1', '30');
INSERT INTO `ofertasmaterias` VALUES ('27', '1', '31');
INSERT INTO `ofertasmaterias` VALUES ('28', '1', '32');
INSERT INTO `ofertasmaterias` VALUES ('29', '1', '33');
INSERT INTO `ofertasmaterias` VALUES ('30', '1', '34');
INSERT INTO `ofertasmaterias` VALUES ('31', '1', '35');
INSERT INTO `ofertasmaterias` VALUES ('32', '1', '36');
INSERT INTO `ofertasmaterias` VALUES ('33', '1', '37');
INSERT INTO `ofertasmaterias` VALUES ('34', '1', '38');
INSERT INTO `ofertasmaterias` VALUES ('35', '1', '39');
INSERT INTO `ofertasmaterias` VALUES ('36', '1', '40');
INSERT INTO `ofertasmaterias` VALUES ('37', '1', '41');
INSERT INTO `ofertasmaterias` VALUES ('38', '1', '42');
INSERT INTO `ofertasmaterias` VALUES ('39', '1', '43');
INSERT INTO `ofertasmaterias` VALUES ('40', '1', '44');
INSERT INTO `ofertasmaterias` VALUES ('41', '1', '45');
INSERT INTO `ofertasmaterias` VALUES ('42', '1', '46');
INSERT INTO `ofertasmaterias` VALUES ('43', '1', '47');
INSERT INTO `ofertasmaterias` VALUES ('44', '1', '48');
INSERT INTO `ofertasmaterias` VALUES ('45', '1', '49');
INSERT INTO `ofertasmaterias` VALUES ('46', '1', '50');
INSERT INTO `ofertasmaterias` VALUES ('47', '1', '51');
INSERT INTO `ofertasmaterias` VALUES ('48', '1', '52');
INSERT INTO `ofertasmaterias` VALUES ('49', '1', '53');
INSERT INTO `ofertasmaterias` VALUES ('50', '1', '54');
INSERT INTO `ofertasmaterias` VALUES ('51', '1', '55');
INSERT INTO `ofertasmaterias` VALUES ('52', '1', '56');
INSERT INTO `ofertasmaterias` VALUES ('53', '1', '57');
INSERT INTO `ofertasmaterias` VALUES ('54', '1', '58');
INSERT INTO `ofertasmaterias` VALUES ('55', '1', '59');
INSERT INTO `ofertasmaterias` VALUES ('56', '1', '60');
INSERT INTO `ofertasmaterias` VALUES ('57', '1', '61');
INSERT INTO `ofertasmaterias` VALUES ('58', '1', '62');
INSERT INTO `ofertasmaterias` VALUES ('59', '1', '63');
INSERT INTO `ofertasmaterias` VALUES ('60', '1', '64');
INSERT INTO `ofertasmaterias` VALUES ('61', '1', '65');
INSERT INTO `ofertasmaterias` VALUES ('62', '1', '66');
INSERT INTO `ofertasmaterias` VALUES ('63', '1', '67');
INSERT INTO `ofertasmaterias` VALUES ('64', '1', '68');
INSERT INTO `ofertasmaterias` VALUES ('65', '1', '69');
INSERT INTO `ofertasmaterias` VALUES ('66', '1', '70');
INSERT INTO `ofertasmaterias` VALUES ('67', '1', '71');
INSERT INTO `ofertasmaterias` VALUES ('68', '1', '72');
INSERT INTO `ofertasmaterias` VALUES ('69', '1', '73');
INSERT INTO `ofertasmaterias` VALUES ('70', '1', '74');
INSERT INTO `ofertasmaterias` VALUES ('83', '2', '111');
INSERT INTO `ofertasmaterias` VALUES ('85', '2', '113');
INSERT INTO `ofertasmaterias` VALUES ('86', '2', '114');
INSERT INTO `ofertasmaterias` VALUES ('87', '2', '115');
INSERT INTO `ofertasmaterias` VALUES ('88', '2', '116');
INSERT INTO `ofertasmaterias` VALUES ('89', '2', '117');
INSERT INTO `ofertasmaterias` VALUES ('90', '2', '118');
INSERT INTO `ofertasmaterias` VALUES ('93', '2', '121');
INSERT INTO `ofertasmaterias` VALUES ('97', '2', '119');
INSERT INTO `ofertasmaterias` VALUES ('98', '2', '120');
INSERT INTO `ofertasmaterias` VALUES ('100', '1', '75');
INSERT INTO `ofertasmaterias` VALUES ('101', '1', '76');
INSERT INTO `ofertasmaterias` VALUES ('102', '1', '77');
INSERT INTO `ofertasmaterias` VALUES ('103', '1', '78');
INSERT INTO `ofertasmaterias` VALUES ('104', '1', '79');
INSERT INTO `ofertasmaterias` VALUES ('105', '1', '80');
INSERT INTO `ofertasmaterias` VALUES ('106', '1', '81');
INSERT INTO `ofertasmaterias` VALUES ('107', '1', '82');
INSERT INTO `ofertasmaterias` VALUES ('108', '1', '83');
INSERT INTO `ofertasmaterias` VALUES ('109', '1', '84');
INSERT INTO `ofertasmaterias` VALUES ('110', '1', '85');
INSERT INTO `ofertasmaterias` VALUES ('111', '1', '86');
INSERT INTO `ofertasmaterias` VALUES ('112', '1', '87');
INSERT INTO `ofertasmaterias` VALUES ('113', '1', '88');
INSERT INTO `ofertasmaterias` VALUES ('114', '1', '89');
INSERT INTO `ofertasmaterias` VALUES ('115', '1', '90');
INSERT INTO `ofertasmaterias` VALUES ('116', '1', '91');
INSERT INTO `ofertasmaterias` VALUES ('117', '1', '92');
INSERT INTO `ofertasmaterias` VALUES ('118', '1', '93');
INSERT INTO `ofertasmaterias` VALUES ('119', '1', '94');
INSERT INTO `ofertasmaterias` VALUES ('120', '1', '95');
INSERT INTO `ofertasmaterias` VALUES ('121', '1', '96');
INSERT INTO `ofertasmaterias` VALUES ('122', '1', '97');
INSERT INTO `ofertasmaterias` VALUES ('123', '1', '98');
INSERT INTO `ofertasmaterias` VALUES ('126', '2', '124');
INSERT INTO `ofertasmaterias` VALUES ('127', '2', '125');
INSERT INTO `ofertasmaterias` VALUES ('128', '2', '126');
INSERT INTO `ofertasmaterias` VALUES ('129', '2', '127');
INSERT INTO `ofertasmaterias` VALUES ('130', '2', '128');
INSERT INTO `ofertasmaterias` VALUES ('131', '2', '129');
INSERT INTO `ofertasmaterias` VALUES ('132', '2', '130');
INSERT INTO `ofertasmaterias` VALUES ('133', '2', '131');
INSERT INTO `ofertasmaterias` VALUES ('134', '1', '132');
INSERT INTO `ofertasmaterias` VALUES ('135', '1', '133');
INSERT INTO `ofertasmaterias` VALUES ('136', '2', '134');
INSERT INTO `ofertasmaterias` VALUES ('137', '2', '135');
INSERT INTO `ofertasmaterias` VALUES ('138', '2', '136');
INSERT INTO `ofertasmaterias` VALUES ('139', '2', '137');
INSERT INTO `ofertasmaterias` VALUES ('140', '2', '138');
INSERT INTO `ofertasmaterias` VALUES ('141', '2', '139');
INSERT INTO `ofertasmaterias` VALUES ('142', '1', '140');
INSERT INTO `ofertasmaterias` VALUES ('155', '2', '153');
INSERT INTO `ofertasmaterias` VALUES ('160', '2', '158');
INSERT INTO `ofertasmaterias` VALUES ('161', '2', '159');
INSERT INTO `ofertasmaterias` VALUES ('162', '2', '160');
INSERT INTO `ofertasmaterias` VALUES ('163', '2', '161');
INSERT INTO `ofertasmaterias` VALUES ('164', '2', '162');
INSERT INTO `ofertasmaterias` VALUES ('165', '2', '163');
INSERT INTO `ofertasmaterias` VALUES ('166', '2', '164');

-- ----------------------------
-- Table structure for `oportunidades`
-- ----------------------------
DROP TABLE IF EXISTS `oportunidades`;
CREATE TABLE `oportunidades` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `clave` varchar(3) collate utf8_spanish_ci NOT NULL,
  `nombre` varchar(30) collate utf8_spanish_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of oportunidades
-- ----------------------------
INSERT INTO `oportunidades` VALUES ('1', 'ORD', 'Ordinario');
INSERT INTO `oportunidades` VALUES ('2', 'EXT', 'Extraordinario');
INSERT INTO `oportunidades` VALUES ('3', 'SD', 'Sin derecho');

-- ----------------------------
-- Table structure for `periodo`
-- ----------------------------
DROP TABLE IF EXISTS `periodo`;
CREATE TABLE `periodo` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `ciclos_id` int(10) unsigned NOT NULL,
  `inicio` datetime NOT NULL,
  `fin` datetime NOT NULL,
  `intervalo` int(11) default NULL,
  `activo` int(11) default NULL,
  `cursosciclos_id` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `periodo_FKIndex1` (`ciclos_id`),
  CONSTRAINT `periodo_ibfk_1` FOREIGN KEY (`ciclos_id`) REFERENCES `ciclos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of periodo
-- ----------------------------

-- ----------------------------
-- Table structure for `periodoconfiguracion`
-- ----------------------------
DROP TABLE IF EXISTS `periodoconfiguracion`;
CREATE TABLE `periodoconfiguracion` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `oferta_id` int(10) unsigned NOT NULL,
  `periodo_id` int(10) unsigned NOT NULL,
  `turno` varchar(1) NOT NULL,
  `grado` varchar(1) NOT NULL,
  `tipo` varchar(3) NOT NULL,
  `maximo` int(10) unsigned NOT NULL,
  `diferente` tinyint(1) NOT NULL,
  `dtipo` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `periodoconfiguracion_FKIndex1` (`periodo_id`),
  KEY `periodoconfiguracion_FKIndex2` (`oferta_id`),
  CONSTRAINT `periodoconfiguracion_ibfk_1` FOREIGN KEY (`periodo_id`) REFERENCES `periodo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `periodoconfiguracion_ibfk_2` FOREIGN KEY (`oferta_id`) REFERENCES `oferta` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of periodoconfiguracion
-- ----------------------------

-- ----------------------------
-- Table structure for `periodohorario`
-- ----------------------------
DROP TABLE IF EXISTS `periodohorario`;
CREATE TABLE `periodohorario` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `dias_id` int(10) unsigned NOT NULL,
  `periodo_id` int(10) unsigned NOT NULL,
  `inicio` time NOT NULL,
  `fin` time NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `periodohorario_FKIndex1` (`periodo_id`),
  KEY `periodohorario_FKIndex2` (`dias_id`),
  CONSTRAINT `periodohorario_ibfk_1` FOREIGN KEY (`periodo_id`) REFERENCES `periodo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `periodohorario_ibfk_2` FOREIGN KEY (`dias_id`) REFERENCES `dias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of periodohorario
-- ----------------------------

-- ----------------------------
-- Table structure for `periodosalumnos`
-- ----------------------------
DROP TABLE IF EXISTS `periodosalumnos`;
CREATE TABLE `periodosalumnos` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `alumnos_id` int(10) unsigned NOT NULL,
  `periodo_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `alumnoscursos_2_FKIndex1` (`periodo_id`),
  KEY `alumnoscursos_2_FKIndex2` (`alumnos_id`),
  CONSTRAINT `periodosalumnos_ibfk_1` FOREIGN KEY (`periodo_id`) REFERENCES `periodo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `periodosalumnos_ibfk_2` FOREIGN KEY (`alumnos_id`) REFERENCES `alumnos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6179 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of periodosalumnos
-- ----------------------------

-- ----------------------------
-- Table structure for `periodoscursos`
-- ----------------------------
DROP TABLE IF EXISTS `periodoscursos`;
CREATE TABLE `periodoscursos` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cursos_id` int(10) unsigned NOT NULL,
  `periodo_id` int(10) unsigned NOT NULL,
  `cupos` int(10) unsigned default NULL,
  `inscritos` int(10) NOT NULL default '0',
  `tipos_id` varchar(1) default NULL,
  PRIMARY KEY  (`id`),
  KEY `periodoscursos_FKIndex1` (`periodo_id`),
  KEY `periodoscursos_FKIndex2` (`cursos_id`),
  CONSTRAINT `periodoscursos_ibfk_1` FOREIGN KEY (`periodo_id`) REFERENCES `periodo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `periodoscursos_ibfk_2` FOREIGN KEY (`cursos_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=471 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of periodoscursos
-- ----------------------------

-- ----------------------------
-- Table structure for `periodotrayectoria`
-- ----------------------------
DROP TABLE IF EXISTS `periodotrayectoria`;
CREATE TABLE `periodotrayectoria` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `trayectoriaespecializante_id` int(10) unsigned NOT NULL,
  `periodo_id` int(10) unsigned NOT NULL,
  `cupos` int(10) unsigned default '0',
  `inscritos` int(11) default '0',
  `turno` varchar(1) character set utf8 NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `periodotrayectoria_FKIndex1` (`periodo_id`),
  KEY `periodotrayectoria_FKIndex2` (`trayectoriaespecializante_id`),
  CONSTRAINT `periodotrayectoria_ibfk_1` FOREIGN KEY (`periodo_id`) REFERENCES `periodo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `periodotrayectoria_ibfk_2` FOREIGN KEY (`trayectoriaespecializante_id`) REFERENCES `trayectoriaespecializante` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of periodotrayectoria
-- ----------------------------

-- ----------------------------
-- Table structure for `periodotrayectoriaalumno`
-- ----------------------------
DROP TABLE IF EXISTS `periodotrayectoriaalumno`;
CREATE TABLE `periodotrayectoriaalumno` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `periodosalumnos_id` int(10) unsigned NOT NULL,
  `periodotrayectoria_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `periodotrayectoriaalumno_FKIndex1` (`periodotrayectoria_id`),
  KEY `periodotrayectoriaalumno_FKIndex2` (`periodosalumnos_id`),
  CONSTRAINT `periodotrayectoriaalumno_ibfk_1` FOREIGN KEY (`periodotrayectoria_id`) REFERENCES `periodotrayectoria` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `periodotrayectoriaalumno_ibfk_2` FOREIGN KEY (`periodosalumnos_id`) REFERENCES `periodosalumnos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1289 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of periodotrayectoriaalumno
-- ----------------------------

-- ----------------------------
-- Table structure for `personal`
-- ----------------------------
DROP TABLE IF EXISTS `personal`;
CREATE TABLE `personal` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tipopersonal_id` int(10) unsigned NOT NULL,
  `codigo` varchar(12) character set latin1 NOT NULL,
  `nombre` varchar(30) character set latin1 NOT NULL,
  `ap` varchar(20) character set latin1 NOT NULL,
  `am` varchar(20) character set latin1 NOT NULL,
  `domicilio` varchar(60) character set latin1 default NULL,
  `tel` varchar(20) character set latin1 default NULL,
  `cel` varchar(20) character set latin1 default NULL,
  `mail` varchar(80) character set latin1 default NULL,
  `rfc` varchar(20) character set latin1 default NULL,
  `curp` varchar(20) character set latin1 default NULL,
  `fnacimiento` date default NULL,
  `sexo` varchar(1) character set latin1 default NULL,
  `foto` varchar(20) character set latin1 default NULL,
  PRIMARY KEY  (`id`),
  KEY `profesores_FKIndex1` (`tipopersonal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of personal
-- ----------------------------

-- ----------------------------
-- Table structure for `prerregistro`
-- ----------------------------
DROP TABLE IF EXISTS `prerregistro`;
CREATE TABLE `prerregistro` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `profesores_id` int(10) unsigned NOT NULL,
  `cursos_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `prerregistro_FKIndex1` (`cursos_id`),
  KEY `prerregistro_FKIndex2` (`profesores_id`),
  CONSTRAINT `prerregistro_ibfk_1` FOREIGN KEY (`cursos_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `prerregistro_ibfk_2` FOREIGN KEY (`profesores_id`) REFERENCES `profesores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=702 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of prerregistro
-- ----------------------------
INSERT INTO `prerregistro` VALUES ('1', '271', '4040');
INSERT INTO `prerregistro` VALUES ('3', '258', '4042');
INSERT INTO `prerregistro` VALUES ('4', '298', '4043');
INSERT INTO `prerregistro` VALUES ('5', '271', '4044');
INSERT INTO `prerregistro` VALUES ('11', '238', '4050');
INSERT INTO `prerregistro` VALUES ('13', '287', '4052');
INSERT INTO `prerregistro` VALUES ('15', '274', '4054');
INSERT INTO `prerregistro` VALUES ('19', '235', '4058');
INSERT INTO `prerregistro` VALUES ('21', '271', '4060');
INSERT INTO `prerregistro` VALUES ('22', '292', '4061');
INSERT INTO `prerregistro` VALUES ('23', '298', '4062');
INSERT INTO `prerregistro` VALUES ('24', '238', '4063');
INSERT INTO `prerregistro` VALUES ('25', '274', '4064');
INSERT INTO `prerregistro` VALUES ('31', '238', '4070');
INSERT INTO `prerregistro` VALUES ('32', '258', '4071');
INSERT INTO `prerregistro` VALUES ('33', '287', '4072');
INSERT INTO `prerregistro` VALUES ('36', '292', '4075');
INSERT INTO `prerregistro` VALUES ('39', '287', '4078');
INSERT INTO `prerregistro` VALUES ('41', '238', '4080');
INSERT INTO `prerregistro` VALUES ('42', '292', '4081');
INSERT INTO `prerregistro` VALUES ('43', '287', '4082');
INSERT INTO `prerregistro` VALUES ('44', '274', '4083');
INSERT INTO `prerregistro` VALUES ('52', '289', '4272');
INSERT INTO `prerregistro` VALUES ('53', '282', '4273');
INSERT INTO `prerregistro` VALUES ('55', '282', '4275');
INSERT INTO `prerregistro` VALUES ('56', '199', '4276');
INSERT INTO `prerregistro` VALUES ('57', '274', '4277');
INSERT INTO `prerregistro` VALUES ('60', '235', '4280');
INSERT INTO `prerregistro` VALUES ('62', '164', '4282');
INSERT INTO `prerregistro` VALUES ('63', '199', '4283');
INSERT INTO `prerregistro` VALUES ('65', '282', '4285');
INSERT INTO `prerregistro` VALUES ('66', '199', '4286');
INSERT INTO `prerregistro` VALUES ('69', '235', '4290');
INSERT INTO `prerregistro` VALUES ('71', '258', '4292');
INSERT INTO `prerregistro` VALUES ('72', '235', '4293');
INSERT INTO `prerregistro` VALUES ('74', '258', '4295');
INSERT INTO `prerregistro` VALUES ('75', '199', '4296');
INSERT INTO `prerregistro` VALUES ('76', '164', '4297');
INSERT INTO `prerregistro` VALUES ('79', '235', '4300');
INSERT INTO `prerregistro` VALUES ('80', '258', '4301');
INSERT INTO `prerregistro` VALUES ('81', '213', '4302');
INSERT INTO `prerregistro` VALUES ('82', '247', '4303');
INSERT INTO `prerregistro` VALUES ('84', '258', '4305');
INSERT INTO `prerregistro` VALUES ('85', '199', '4306');
INSERT INTO `prerregistro` VALUES ('86', '164', '4307');
INSERT INTO `prerregistro` VALUES ('89', '247', '4310');
INSERT INTO `prerregistro` VALUES ('93', '274', '4314');
INSERT INTO `prerregistro` VALUES ('94', '258', '4315');
INSERT INTO `prerregistro` VALUES ('95', '199', '4316');
INSERT INTO `prerregistro` VALUES ('96', '164', '4317');
INSERT INTO `prerregistro` VALUES ('99', '289', '4320');
INSERT INTO `prerregistro` VALUES ('101', '262', '4099');
INSERT INTO `prerregistro` VALUES ('102', '258', '4100');
INSERT INTO `prerregistro` VALUES ('103', '243', '4101');
INSERT INTO `prerregistro` VALUES ('105', '202', '4103');
INSERT INTO `prerregistro` VALUES ('107', '245', '4105');
INSERT INTO `prerregistro` VALUES ('110', '258', '4092');
INSERT INTO `prerregistro` VALUES ('111', '243', '4093');
INSERT INTO `prerregistro` VALUES ('112', '213', '4094');
INSERT INTO `prerregistro` VALUES ('115', '292', '4097');
INSERT INTO `prerregistro` VALUES ('118', '258', '4108');
INSERT INTO `prerregistro` VALUES ('119', '243', '4109');
INSERT INTO `prerregistro` VALUES ('120', '213', '4110');
INSERT INTO `prerregistro` VALUES ('121', '202', '4111');
INSERT INTO `prerregistro` VALUES ('123', '292', '4113');
INSERT INTO `prerregistro` VALUES ('125', '183', '4115');
INSERT INTO `prerregistro` VALUES ('127', '243', '4117');
INSERT INTO `prerregistro` VALUES ('128', '285', '4118');
INSERT INTO `prerregistro` VALUES ('131', '292', '4121');
INSERT INTO `prerregistro` VALUES ('132', '298', '4122');
INSERT INTO `prerregistro` VALUES ('133', '262', '4123');
INSERT INTO `prerregistro` VALUES ('135', '243', '4125');
INSERT INTO `prerregistro` VALUES ('137', '202', '4127');
INSERT INTO `prerregistro` VALUES ('139', '292', '4129');
INSERT INTO `prerregistro` VALUES ('140', '174', '4321');
INSERT INTO `prerregistro` VALUES ('143', '274', '4324');
INSERT INTO `prerregistro` VALUES ('146', '280', '4327');
INSERT INTO `prerregistro` VALUES ('147', '287', '4328');
INSERT INTO `prerregistro` VALUES ('149', '283', '4330');
INSERT INTO `prerregistro` VALUES ('150', '249', '4331');
INSERT INTO `prerregistro` VALUES ('154', '280', '4335');
INSERT INTO `prerregistro` VALUES ('155', '287', '4336');
INSERT INTO `prerregistro` VALUES ('156', '199', '4337');
INSERT INTO `prerregistro` VALUES ('158', '249', '4339');
INSERT INTO `prerregistro` VALUES ('160', '208', '4341');
INSERT INTO `prerregistro` VALUES ('162', '280', '4343');
INSERT INTO `prerregistro` VALUES ('163', '292', '4344');
INSERT INTO `prerregistro` VALUES ('164', '267', '4345');
INSERT INTO `prerregistro` VALUES ('168', '208', '4349');
INSERT INTO `prerregistro` VALUES ('170', '234', '4351');
INSERT INTO `prerregistro` VALUES ('171', '287', '4352');
INSERT INTO `prerregistro` VALUES ('172', '267', '4353');
INSERT INTO `prerregistro` VALUES ('173', '283', '4354');
INSERT INTO `prerregistro` VALUES ('176', '208', '4357');
INSERT INTO `prerregistro` VALUES ('178', '280', '4359');
INSERT INTO `prerregistro` VALUES ('179', '292', '4360');
INSERT INTO `prerregistro` VALUES ('180', '183', '4130');
INSERT INTO `prerregistro` VALUES ('182', '276', '4132');
INSERT INTO `prerregistro` VALUES ('183', '237', '4133');
INSERT INTO `prerregistro` VALUES ('185', '203', '4135');
INSERT INTO `prerregistro` VALUES ('188', '322', '4146');
INSERT INTO `prerregistro` VALUES ('189', '174', '4147');
INSERT INTO `prerregistro` VALUES ('190', '238', '4148');
INSERT INTO `prerregistro` VALUES ('191', '285', '4149');
INSERT INTO `prerregistro` VALUES ('193', '272', '4151');
INSERT INTO `prerregistro` VALUES ('196', '183', '4158');
INSERT INTO `prerregistro` VALUES ('198', '276', '4160');
INSERT INTO `prerregistro` VALUES ('199', '237', '4161');
INSERT INTO `prerregistro` VALUES ('200', '290', '4162');
INSERT INTO `prerregistro` VALUES ('201', '203', '4163');
INSERT INTO `prerregistro` VALUES ('202', '238', '4164');
INSERT INTO `prerregistro` VALUES ('203', '238', '4165');
INSERT INTO `prerregistro` VALUES ('204', '183', '4166');
INSERT INTO `prerregistro` VALUES ('205', '174', '4167');
INSERT INTO `prerregistro` VALUES ('206', '238', '4168');
INSERT INTO `prerregistro` VALUES ('207', '237', '4169');
INSERT INTO `prerregistro` VALUES ('208', '202', '4170');
INSERT INTO `prerregistro` VALUES ('209', '203', '4171');
INSERT INTO `prerregistro` VALUES ('214', '238', '4180');
INSERT INTO `prerregistro` VALUES ('215', '237', '4181');
INSERT INTO `prerregistro` VALUES ('217', '292', '4183');
INSERT INTO `prerregistro` VALUES ('218', '264', '4184');
INSERT INTO `prerregistro` VALUES ('219', '264', '4185');
INSERT INTO `prerregistro` VALUES ('220', '229', '4361');
INSERT INTO `prerregistro` VALUES ('221', '174', '4362');
INSERT INTO `prerregistro` VALUES ('222', '280', '4363');
INSERT INTO `prerregistro` VALUES ('225', '203', '4366');
INSERT INTO `prerregistro` VALUES ('227', '260', '4368');
INSERT INTO `prerregistro` VALUES ('228', '229', '4369');
INSERT INTO `prerregistro` VALUES ('229', '174', '4370');
INSERT INTO `prerregistro` VALUES ('230', '181', '4371');
INSERT INTO `prerregistro` VALUES ('231', '237', '4372');
INSERT INTO `prerregistro` VALUES ('233', '203', '4374');
INSERT INTO `prerregistro` VALUES ('235', '251', '4376');
INSERT INTO `prerregistro` VALUES ('236', '229', '4384');
INSERT INTO `prerregistro` VALUES ('237', '188', '4385');
INSERT INTO `prerregistro` VALUES ('238', '181', '4386');
INSERT INTO `prerregistro` VALUES ('241', '203', '4389');
INSERT INTO `prerregistro` VALUES ('244', '229', '4399');
INSERT INTO `prerregistro` VALUES ('245', '188', '4400');
INSERT INTO `prerregistro` VALUES ('247', '185', '4402');
INSERT INTO `prerregistro` VALUES ('249', '203', '4404');
INSERT INTO `prerregistro` VALUES ('252', '229', '4409');
INSERT INTO `prerregistro` VALUES ('253', '188', '4410');
INSERT INTO `prerregistro` VALUES ('254', '274', '4411');
INSERT INTO `prerregistro` VALUES ('255', '237', '4412');
INSERT INTO `prerregistro` VALUES ('257', '203', '4414');
INSERT INTO `prerregistro` VALUES ('260', '250', '4191');
INSERT INTO `prerregistro` VALUES ('261', '165', '4192');
INSERT INTO `prerregistro` VALUES ('262', '251', '4193');
INSERT INTO `prerregistro` VALUES ('263', '268', '4194');
INSERT INTO `prerregistro` VALUES ('265', '255', '4196');
INSERT INTO `prerregistro` VALUES ('266', '289', '4197');
INSERT INTO `prerregistro` VALUES ('267', '263', '4198');
INSERT INTO `prerregistro` VALUES ('270', '256', '4201');
INSERT INTO `prerregistro` VALUES ('271', '165', '4202');
INSERT INTO `prerregistro` VALUES ('272', '262', '4203');
INSERT INTO `prerregistro` VALUES ('273', '255', '4204');
INSERT INTO `prerregistro` VALUES ('274', '256', '4205');
INSERT INTO `prerregistro` VALUES ('281', '287', '4214');
INSERT INTO `prerregistro` VALUES ('284', '268', '4218');
INSERT INTO `prerregistro` VALUES ('286', '255', '4220');
INSERT INTO `prerregistro` VALUES ('287', '289', '4221');
INSERT INTO `prerregistro` VALUES ('288', '263', '4222');
INSERT INTO `prerregistro` VALUES ('291', '268', '4226');
INSERT INTO `prerregistro` VALUES ('294', '289', '4229');
INSERT INTO `prerregistro` VALUES ('296', '165', '4417');
INSERT INTO `prerregistro` VALUES ('297', '250', '4418');
INSERT INTO `prerregistro` VALUES ('300', '165', '4421');
INSERT INTO `prerregistro` VALUES ('301', '183', '4422');
INSERT INTO `prerregistro` VALUES ('302', '293', '4423');
INSERT INTO `prerregistro` VALUES ('303', '256', '4424');
INSERT INTO `prerregistro` VALUES ('304', '284', '4425');
INSERT INTO `prerregistro` VALUES ('305', '293', '4426');
INSERT INTO `prerregistro` VALUES ('309', '185', '4430');
INSERT INTO `prerregistro` VALUES ('310', '246', '4431');
INSERT INTO `prerregistro` VALUES ('311', '229', '4432');
INSERT INTO `prerregistro` VALUES ('312', '174', '4433');
INSERT INTO `prerregistro` VALUES ('313', '284', '4434');
INSERT INTO `prerregistro` VALUES ('314', '284', '4435');
INSERT INTO `prerregistro` VALUES ('317', '268', '4438');
INSERT INTO `prerregistro` VALUES ('318', '246', '4439');
INSERT INTO `prerregistro` VALUES ('319', '208', '4440');
INSERT INTO `prerregistro` VALUES ('320', '193', '4443');
INSERT INTO `prerregistro` VALUES ('322', '193', '4445');
INSERT INTO `prerregistro` VALUES ('324', '183', '4447');
INSERT INTO `prerregistro` VALUES ('325', '284', '4448');
INSERT INTO `prerregistro` VALUES ('326', '164', '4451');
INSERT INTO `prerregistro` VALUES ('327', '229', '4452');
INSERT INTO `prerregistro` VALUES ('329', '293', '4454');
INSERT INTO `prerregistro` VALUES ('330', '231', '4455');
INSERT INTO `prerregistro` VALUES ('332', '285', '4232');
INSERT INTO `prerregistro` VALUES ('334', '256', '4234');
INSERT INTO `prerregistro` VALUES ('335', '268', '4235');
INSERT INTO `prerregistro` VALUES ('336', '262', '4236');
INSERT INTO `prerregistro` VALUES ('338', '260', '4238');
INSERT INTO `prerregistro` VALUES ('339', '274', '4239');
INSERT INTO `prerregistro` VALUES ('342', '259', '4242');
INSERT INTO `prerregistro` VALUES ('343', '199', '4243');
INSERT INTO `prerregistro` VALUES ('344', '183', '4244');
INSERT INTO `prerregistro` VALUES ('345', '210', '4245');
INSERT INTO `prerregistro` VALUES ('346', '174', '4246');
INSERT INTO `prerregistro` VALUES ('348', '165', '4248');
INSERT INTO `prerregistro` VALUES ('350', '263', '4250');
INSERT INTO `prerregistro` VALUES ('351', '250', '4251');
INSERT INTO `prerregistro` VALUES ('353', '210', '4253');
INSERT INTO `prerregistro` VALUES ('354', '259', '4254');
INSERT INTO `prerregistro` VALUES ('360', '263', '4261');
INSERT INTO `prerregistro` VALUES ('361', '259', '4262');
INSERT INTO `prerregistro` VALUES ('362', '259', '4263');
INSERT INTO `prerregistro` VALUES ('363', '202', '4264');
INSERT INTO `prerregistro` VALUES ('364', '237', '4265');
INSERT INTO `prerregistro` VALUES ('369', '259', '4270');
INSERT INTO `prerregistro` VALUES ('372', '251', '4459');
INSERT INTO `prerregistro` VALUES ('373', '183', '4460');
INSERT INTO `prerregistro` VALUES ('374', '246', '4461');
INSERT INTO `prerregistro` VALUES ('375', '293', '4462');
INSERT INTO `prerregistro` VALUES ('376', '231', '4463');
INSERT INTO `prerregistro` VALUES ('381', '193', '4468');
INSERT INTO `prerregistro` VALUES ('383', '246', '4470');
INSERT INTO `prerregistro` VALUES ('386', '174', '4473');
INSERT INTO `prerregistro` VALUES ('387', '165', '4474');
INSERT INTO `prerregistro` VALUES ('388', '231', '4475');
INSERT INTO `prerregistro` VALUES ('390', '183', '4477');
INSERT INTO `prerregistro` VALUES ('391', '250', '4478');
INSERT INTO `prerregistro` VALUES ('394', '165', '4481');
INSERT INTO `prerregistro` VALUES ('397', '231', '4484');
INSERT INTO `prerregistro` VALUES ('399', '294', '4486');
INSERT INTO `prerregistro` VALUES ('400', '293', '4487');
INSERT INTO `prerregistro` VALUES ('402', '259', '4489');
INSERT INTO `prerregistro` VALUES ('404', '165', '4491');
INSERT INTO `prerregistro` VALUES ('406', '183', '4493');
INSERT INTO `prerregistro` VALUES ('409', '213', '4496');
INSERT INTO `prerregistro` VALUES ('410', '295', '4138');
INSERT INTO `prerregistro` VALUES ('411', '295', '4139');
INSERT INTO `prerregistro` VALUES ('412', '295', '4140');
INSERT INTO `prerregistro` VALUES ('413', '295', '4141');
INSERT INTO `prerregistro` VALUES ('414', '295', '4142');
INSERT INTO `prerregistro` VALUES ('415', '295', '4143');
INSERT INTO `prerregistro` VALUES ('416', '295', '4144');
INSERT INTO `prerregistro` VALUES ('417', '295', '4145');
INSERT INTO `prerregistro` VALUES ('418', '295', '4154');
INSERT INTO `prerregistro` VALUES ('419', '295', '4155');
INSERT INTO `prerregistro` VALUES ('420', '295', '4156');
INSERT INTO `prerregistro` VALUES ('421', '295', '4157');
INSERT INTO `prerregistro` VALUES ('422', '295', '4377');
INSERT INTO `prerregistro` VALUES ('423', '295', '4378');
INSERT INTO `prerregistro` VALUES ('424', '295', '4379');
INSERT INTO `prerregistro` VALUES ('425', '295', '4380');
INSERT INTO `prerregistro` VALUES ('426', '295', '4381');
INSERT INTO `prerregistro` VALUES ('427', '295', '4382');
INSERT INTO `prerregistro` VALUES ('428', '295', '4383');
INSERT INTO `prerregistro` VALUES ('429', '295', '4174');
INSERT INTO `prerregistro` VALUES ('430', '295', '4175');
INSERT INTO `prerregistro` VALUES ('431', '295', '4176');
INSERT INTO `prerregistro` VALUES ('432', '295', '4177');
INSERT INTO `prerregistro` VALUES ('433', '295', '4392');
INSERT INTO `prerregistro` VALUES ('434', '295', '4393');
INSERT INTO `prerregistro` VALUES ('435', '295', '4394');
INSERT INTO `prerregistro` VALUES ('436', '295', '4395');
INSERT INTO `prerregistro` VALUES ('437', '295', '4396');
INSERT INTO `prerregistro` VALUES ('438', '295', '4397');
INSERT INTO `prerregistro` VALUES ('439', '295', '4398');
INSERT INTO `prerregistro` VALUES ('440', '295', '4186');
INSERT INTO `prerregistro` VALUES ('441', '295', '4187');
INSERT INTO `prerregistro` VALUES ('442', '295', '4188');
INSERT INTO `prerregistro` VALUES ('443', '295', '4189');
INSERT INTO `prerregistro` VALUES ('444', '295', '4190');
INSERT INTO `prerregistro` VALUES ('445', '295', '4407');
INSERT INTO `prerregistro` VALUES ('446', '295', '4408');
INSERT INTO `prerregistro` VALUES ('459', '162', '4051');
INSERT INTO `prerregistro` VALUES ('460', '162', '4116');
INSERT INTO `prerregistro` VALUES ('461', '162', '4499');
INSERT INTO `prerregistro` VALUES ('462', '162', '4449');
INSERT INTO `prerregistro` VALUES ('463', '162', '4287');
INSERT INTO `prerregistro` VALUES ('464', '162', '4333');
INSERT INTO `prerregistro` VALUES ('465', '162', '4441');
INSERT INTO `prerregistro` VALUES ('466', '162', '4500');
INSERT INTO `prerregistro` VALUES ('467', '162', '4501');
INSERT INTO `prerregistro` VALUES ('468', '162', '4504');
INSERT INTO `prerregistro` VALUES ('469', '162', '4255');
INSERT INTO `prerregistro` VALUES ('470', '162', '4216');
INSERT INTO `prerregistro` VALUES ('471', '168', '4053');
INSERT INTO `prerregistro` VALUES ('472', '168', '4065');
INSERT INTO `prerregistro` VALUES ('473', '168', '4076');
INSERT INTO `prerregistro` VALUES ('474', '168', '4153');
INSERT INTO `prerregistro` VALUES ('475', '168', '4288');
INSERT INTO `prerregistro` VALUES ('476', '168', '4086');
INSERT INTO `prerregistro` VALUES ('477', '168', '4055');
INSERT INTO `prerregistro` VALUES ('478', '171', '4298');
INSERT INTO `prerregistro` VALUES ('479', '171', '4311');
INSERT INTO `prerregistro` VALUES ('480', '171', '4318');
INSERT INTO `prerregistro` VALUES ('481', '171', '4046');
INSERT INTO `prerregistro` VALUES ('482', '171', '4308');
INSERT INTO `prerregistro` VALUES ('483', '171', '4079');
INSERT INTO `prerregistro` VALUES ('484', '171', '4102');
INSERT INTO `prerregistro` VALUES ('485', '172', '4199');
INSERT INTO `prerregistro` VALUES ('486', '172', '4208');
INSERT INTO `prerregistro` VALUES ('487', '172', '4228');
INSERT INTO `prerregistro` VALUES ('488', '172', '4240');
INSERT INTO `prerregistro` VALUES ('489', '172', '4269');
INSERT INTO `prerregistro` VALUES ('490', '172', '4495');
INSERT INTO `prerregistro` VALUES ('491', '172', '4237');
INSERT INTO `prerregistro` VALUES ('492', '172', '4479');
INSERT INTO `prerregistro` VALUES ('493', '172', '4450');
INSERT INTO `prerregistro` VALUES ('494', '172', '4471');
INSERT INTO `prerregistro` VALUES ('495', '172', '4387');
INSERT INTO `prerregistro` VALUES ('496', '172', '4223');
INSERT INTO `prerregistro` VALUES ('497', '172', '4364');
INSERT INTO `prerregistro` VALUES ('498', '173', '4091');
INSERT INTO `prerregistro` VALUES ('499', '173', '4107');
INSERT INTO `prerregistro` VALUES ('500', '173', '4195');
INSERT INTO `prerregistro` VALUES ('501', '173', '4227');
INSERT INTO `prerregistro` VALUES ('502', '173', '4256');
INSERT INTO `prerregistro` VALUES ('503', '173', '4467');
INSERT INTO `prerregistro` VALUES ('504', '173', '4069');
INSERT INTO `prerregistro` VALUES ('505', '176', '4271');
INSERT INTO `prerregistro` VALUES ('506', '176', '4274');
INSERT INTO `prerregistro` VALUES ('507', '176', '4284');
INSERT INTO `prerregistro` VALUES ('508', '176', '4294');
INSERT INTO `prerregistro` VALUES ('509', '176', '4304');
INSERT INTO `prerregistro` VALUES ('510', '176', '4313');
INSERT INTO `prerregistro` VALUES ('511', '176', '4348');
INSERT INTO `prerregistro` VALUES ('512', '176', '4178');
INSERT INTO `prerregistro` VALUES ('513', '176', '4104');
INSERT INTO `prerregistro` VALUES ('514', '176', '4112');
INSERT INTO `prerregistro` VALUES ('515', '176', '4120');
INSERT INTO `prerregistro` VALUES ('516', '176', '4291');
INSERT INTO `prerregistro` VALUES ('517', '176', '4390');
INSERT INTO `prerregistro` VALUES ('518', '177', '4049');
INSERT INTO `prerregistro` VALUES ('519', '177', '4068');
INSERT INTO `prerregistro` VALUES ('520', '177', '4085');
INSERT INTO `prerregistro` VALUES ('521', '177', '4089');
INSERT INTO `prerregistro` VALUES ('522', '177', '4137');
INSERT INTO `prerregistro` VALUES ('523', '177', '4258');
INSERT INTO `prerregistro` VALUES ('524', '177', '4367');
INSERT INTO `prerregistro` VALUES ('525', '177', '4077');
INSERT INTO `prerregistro` VALUES ('526', '177', '4048');
INSERT INTO `prerregistro` VALUES ('527', '177', '4503');
INSERT INTO `prerregistro` VALUES ('528', '177', '4517');
INSERT INTO `prerregistro` VALUES ('529', '177', '4087');
INSERT INTO `prerregistro` VALUES ('530', '177', '4067');
INSERT INTO `prerregistro` VALUES ('531', '177', '4126');
INSERT INTO `prerregistro` VALUES ('532', '178', '4045');
INSERT INTO `prerregistro` VALUES ('533', '178', '4073');
INSERT INTO `prerregistro` VALUES ('534', '178', '4074');
INSERT INTO `prerregistro` VALUES ('535', '178', '4084');
INSERT INTO `prerregistro` VALUES ('536', '178', '4215');
INSERT INTO `prerregistro` VALUES ('537', '178', '4128');
INSERT INTO `prerregistro` VALUES ('538', '178', '4391');
INSERT INTO `prerregistro` VALUES ('539', '178', '4401');
INSERT INTO `prerregistro` VALUES ('540', '178', '4466');
INSERT INTO `prerregistro` VALUES ('541', '178', '4522');
INSERT INTO `prerregistro` VALUES ('542', '178', '4523');
INSERT INTO `prerregistro` VALUES ('543', '178', '4340');
INSERT INTO `prerregistro` VALUES ('544', '178', '4056');
INSERT INTO `prerregistro` VALUES ('545', '179', '4480');
INSERT INTO `prerregistro` VALUES ('546', '179', '4059');
INSERT INTO `prerregistro` VALUES ('547', '179', '4088');
INSERT INTO `prerregistro` VALUES ('548', '179', '4095');
INSERT INTO `prerregistro` VALUES ('549', '179', '4134');
INSERT INTO `prerregistro` VALUES ('550', '179', '4182');
INSERT INTO `prerregistro` VALUES ('551', '179', '4511');
INSERT INTO `prerregistro` VALUES ('552', '179', '4502');
INSERT INTO `prerregistro` VALUES ('553', '179', '4206');
INSERT INTO `prerregistro` VALUES ('554', '179', '4514');
INSERT INTO `prerregistro` VALUES ('555', '179', '4510');
INSERT INTO `prerregistro` VALUES ('556', '179', '4057');
INSERT INTO `prerregistro` VALUES ('557', '180', '4464');
INSERT INTO `prerregistro` VALUES ('558', '180', '4516');
INSERT INTO `prerregistro` VALUES ('559', '180', '4524');
INSERT INTO `prerregistro` VALUES ('560', '180', '4267');
INSERT INTO `prerregistro` VALUES ('561', '180', '4210');
INSERT INTO `prerregistro` VALUES ('562', '180', '4446');
INSERT INTO `prerregistro` VALUES ('563', '180', '4538');
INSERT INTO `prerregistro` VALUES ('564', '180', '4539');
INSERT INTO `prerregistro` VALUES ('565', '180', '4546');
INSERT INTO `prerregistro` VALUES ('566', '180', '4490');
INSERT INTO `prerregistro` VALUES ('567', '180', '4509');
INSERT INTO `prerregistro` VALUES ('568', '180', '4531');
INSERT INTO `prerregistro` VALUES ('570', '186', '4213');
INSERT INTO `prerregistro` VALUES ('571', '186', '4266');
INSERT INTO `prerregistro` VALUES ('572', '186', '4420');
INSERT INTO `prerregistro` VALUES ('573', '186', '4465');
INSERT INTO `prerregistro` VALUES ('574', '186', '4476');
INSERT INTO `prerregistro` VALUES ('575', '186', '4526');
INSERT INTO `prerregistro` VALUES ('576', '186', '4505');
INSERT INTO `prerregistro` VALUES ('577', '186', '4519');
INSERT INTO `prerregistro` VALUES ('578', '186', '4533');
INSERT INTO `prerregistro` VALUES ('579', '186', '4225');
INSERT INTO `prerregistro` VALUES ('580', '186', '4527');
INSERT INTO `prerregistro` VALUES ('581', '189', '4098');
INSERT INTO `prerregistro` VALUES ('582', '189', '4131');
INSERT INTO `prerregistro` VALUES ('583', '189', '4159');
INSERT INTO `prerregistro` VALUES ('584', '189', '4179');
INSERT INTO `prerregistro` VALUES ('585', '189', '4247');
INSERT INTO `prerregistro` VALUES ('586', '189', '4249');
INSERT INTO `prerregistro` VALUES ('587', '189', '4512');
INSERT INTO `prerregistro` VALUES ('588', '191', '4233');
INSERT INTO `prerregistro` VALUES ('589', '191', '4260');
INSERT INTO `prerregistro` VALUES ('590', '191', '4268');
INSERT INTO `prerregistro` VALUES ('591', '191', '4525');
INSERT INTO `prerregistro` VALUES ('592', '191', '4200');
INSERT INTO `prerregistro` VALUES ('593', '191', '4375');
INSERT INTO `prerregistro` VALUES ('594', '191', '4279');
INSERT INTO `prerregistro` VALUES ('595', '191', '4532');
INSERT INTO `prerregistro` VALUES ('596', '191', '4528');
INSERT INTO `prerregistro` VALUES ('597', '191', '4453');
INSERT INTO `prerregistro` VALUES ('598', '191', '4485');
INSERT INTO `prerregistro` VALUES ('599', '191', '4542');
INSERT INTO `prerregistro` VALUES ('600', '191', '4506');
INSERT INTO `prerregistro` VALUES ('601', '192', '4096');
INSERT INTO `prerregistro` VALUES ('602', '192', '4332');
INSERT INTO `prerregistro` VALUES ('603', '192', '4530');
INSERT INTO `prerregistro` VALUES ('604', '192', '4124');
INSERT INTO `prerregistro` VALUES ('605', '192', '4508');
INSERT INTO `prerregistro` VALUES ('606', '192', '4513');
INSERT INTO `prerregistro` VALUES ('607', '192', '4515');
INSERT INTO `prerregistro` VALUES ('608', '192', '4535');
INSERT INTO `prerregistro` VALUES ('609', '192', '4537');
INSERT INTO `prerregistro` VALUES ('610', '192', '4458');
INSERT INTO `prerregistro` VALUES ('611', '192', '4323');
INSERT INTO `prerregistro` VALUES ('612', '192', '4347');
INSERT INTO `prerregistro` VALUES ('613', '194', '4047');
INSERT INTO `prerregistro` VALUES ('614', '194', '4066');
INSERT INTO `prerregistro` VALUES ('615', '194', '4230');
INSERT INTO `prerregistro` VALUES ('616', '194', '4419');
INSERT INTO `prerregistro` VALUES ('617', '194', '4492');
INSERT INTO `prerregistro` VALUES ('618', '194', '4319');
INSERT INTO `prerregistro` VALUES ('619', '200', '4212');
INSERT INTO `prerregistro` VALUES ('620', '200', '4520');
INSERT INTO `prerregistro` VALUES ('621', '200', '4545');
INSERT INTO `prerregistro` VALUES ('622', '200', '4488');
INSERT INTO `prerregistro` VALUES ('623', '200', '4041');
INSERT INTO `prerregistro` VALUES ('624', '200', '4211');
INSERT INTO `prerregistro` VALUES ('625', '200', '4224');
INSERT INTO `prerregistro` VALUES ('626', '200', '4472');
INSERT INTO `prerregistro` VALUES ('627', '200', '4241');
INSERT INTO `prerregistro` VALUES ('628', '200', '4544');
INSERT INTO `prerregistro` VALUES ('629', '200', '4427');
INSERT INTO `prerregistro` VALUES ('630', '200', '4252');
INSERT INTO `prerregistro` VALUES ('631', '200', '4219');
INSERT INTO `prerregistro` VALUES ('632', '206', '4119');
INSERT INTO `prerregistro` VALUES ('633', '206', '4540');
INSERT INTO `prerregistro` VALUES ('634', '206', '4547');
INSERT INTO `prerregistro` VALUES ('635', '206', '4358');
INSERT INTO `prerregistro` VALUES ('636', '206', '4365');
INSERT INTO `prerregistro` VALUES ('637', '206', '4373');
INSERT INTO `prerregistro` VALUES ('638', '206', '4403');
INSERT INTO `prerregistro` VALUES ('639', '206', '4388');
INSERT INTO `prerregistro` VALUES ('640', '206', '4518');
INSERT INTO `prerregistro` VALUES ('641', '206', '4231');
INSERT INTO `prerregistro` VALUES ('642', '211', '4090');
INSERT INTO `prerregistro` VALUES ('643', '211', '4106');
INSERT INTO `prerregistro` VALUES ('644', '211', '4114');
INSERT INTO `prerregistro` VALUES ('645', '211', '4329');
INSERT INTO `prerregistro` VALUES ('646', '211', '4507');
INSERT INTO `prerregistro` VALUES ('647', '211', '4521');
INSERT INTO `prerregistro` VALUES ('648', '215', '4413');
INSERT INTO `prerregistro` VALUES ('649', '215', '4257');
INSERT INTO `prerregistro` VALUES ('650', '215', '4312');
INSERT INTO `prerregistro` VALUES ('651', '215', '4322');
INSERT INTO `prerregistro` VALUES ('652', '215', '4356');
INSERT INTO `prerregistro` VALUES ('653', '215', '4355');
INSERT INTO `prerregistro` VALUES ('654', '215', '4483');
INSERT INTO `prerregistro` VALUES ('655', '215', '4209');
INSERT INTO `prerregistro` VALUES ('656', '215', '4415');
INSERT INTO `prerregistro` VALUES ('657', '215', '4338');
INSERT INTO `prerregistro` VALUES ('658', '215', '4346');
INSERT INTO `prerregistro` VALUES ('659', '215', '4217');
INSERT INTO `prerregistro` VALUES ('660', '215', '4416');
INSERT INTO `prerregistro` VALUES ('661', '223', '4444');
INSERT INTO `prerregistro` VALUES ('662', '223', '4456');
INSERT INTO `prerregistro` VALUES ('663', '223', '4325');
INSERT INTO `prerregistro` VALUES ('664', '223', '4428');
INSERT INTO `prerregistro` VALUES ('665', '223', '4442');
INSERT INTO `prerregistro` VALUES ('666', '223', '4207');
INSERT INTO `prerregistro` VALUES ('667', '223', '4278');
INSERT INTO `prerregistro` VALUES ('668', '223', '4548');
INSERT INTO `prerregistro` VALUES ('669', '223', '4541');
INSERT INTO `prerregistro` VALUES ('670', '223', '4534');
INSERT INTO `prerregistro` VALUES ('671', '223', '4482');
INSERT INTO `prerregistro` VALUES ('672', '223', '4405');
INSERT INTO `prerregistro` VALUES ('673', '223', '4406');
INSERT INTO `prerregistro` VALUES ('680', '230', '4152');
INSERT INTO `prerregistro` VALUES ('681', '230', '4281');
INSERT INTO `prerregistro` VALUES ('682', '230', '4289');
INSERT INTO `prerregistro` VALUES ('683', '230', '4299');
INSERT INTO `prerregistro` VALUES ('684', '230', '4309');
INSERT INTO `prerregistro` VALUES ('685', '230', '4429');
INSERT INTO `prerregistro` VALUES ('686', '227', '4172');
INSERT INTO `prerregistro` VALUES ('687', '227', '4173');
INSERT INTO `prerregistro` VALUES ('688', '227', '4259');
INSERT INTO `prerregistro` VALUES ('689', '227', '4469');
INSERT INTO `prerregistro` VALUES ('690', '227', '4494');
INSERT INTO `prerregistro` VALUES ('691', '227', '4436');
INSERT INTO `prerregistro` VALUES ('692', '227', '4437');
INSERT INTO `prerregistro` VALUES ('693', '227', '4529');
INSERT INTO `prerregistro` VALUES ('694', '227', '4536');
INSERT INTO `prerregistro` VALUES ('695', '227', '4543');
INSERT INTO `prerregistro` VALUES ('696', '233', '4150');
INSERT INTO `prerregistro` VALUES ('697', '233', '4326');
INSERT INTO `prerregistro` VALUES ('698', '233', '4334');
INSERT INTO `prerregistro` VALUES ('699', '233', '4342');
INSERT INTO `prerregistro` VALUES ('700', '233', '4350');
INSERT INTO `prerregistro` VALUES ('701', '233', '4457');

-- ----------------------------
-- Table structure for `prerrequisitos`
-- ----------------------------
DROP TABLE IF EXISTS `prerrequisitos`;
CREATE TABLE `prerrequisitos` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `materia` int(10) unsigned NOT NULL,
  `requisito` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `prerrequisitos_FKIndex1` (`materia`),
  KEY `prerrequisitos_FKIndex2` (`requisito`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of prerrequisitos
-- ----------------------------
INSERT INTO `prerrequisitos` VALUES ('3', '125', '121');
INSERT INTO `prerrequisitos` VALUES ('6', '128', '118');
INSERT INTO `prerrequisitos` VALUES ('8', '131', '120');
INSERT INTO `prerrequisitos` VALUES ('13', '130', '116');
INSERT INTO `prerrequisitos` VALUES ('14', '127', '111');
INSERT INTO `prerrequisitos` VALUES ('15', '126', '113');
INSERT INTO `prerrequisitos` VALUES ('16', '124', '114');
INSERT INTO `prerrequisitos` VALUES ('17', '22', '13');
INSERT INTO `prerrequisitos` VALUES ('18', '25', '16');
INSERT INTO `prerrequisitos` VALUES ('19', '26', '17');
INSERT INTO `prerrequisitos` VALUES ('20', '21', '12');
INSERT INTO `prerrequisitos` VALUES ('21', '23', '14');
INSERT INTO `prerrequisitos` VALUES ('22', '33', '24');
INSERT INTO `prerrequisitos` VALUES ('23', '31', '22');
INSERT INTO `prerrequisitos` VALUES ('24', '30', '21');
INSERT INTO `prerrequisitos` VALUES ('25', '32', '23');
INSERT INTO `prerrequisitos` VALUES ('26', '38', '34');
INSERT INTO `prerrequisitos` VALUES ('27', '55', '38');
INSERT INTO `prerrequisitos` VALUES ('28', '56', '39');

-- ----------------------------
-- Table structure for `profesores`
-- ----------------------------
DROP TABLE IF EXISTS `profesores`;
CREATE TABLE `profesores` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `codigo` varchar(12) collate utf8_spanish_ci NOT NULL,
  `nombre` varchar(30) collate utf8_spanish_ci NOT NULL,
  `ap` varchar(20) collate utf8_spanish_ci NOT NULL,
  `am` varchar(20) collate utf8_spanish_ci NOT NULL,
  `domicilio` varchar(60) collate utf8_spanish_ci default NULL,
  `tel` varchar(20) collate utf8_spanish_ci default NULL,
  `cel` varchar(20) collate utf8_spanish_ci default NULL,
  `mail` varchar(80) collate utf8_spanish_ci default NULL,
  `rfc` varchar(20) collate utf8_spanish_ci default NULL,
  `curp` varchar(20) collate utf8_spanish_ci default NULL,
  `fnacimiento` date default NULL,
  `sexo` varchar(1) collate utf8_spanish_ci default NULL,
  `foto` varchar(20) collate utf8_spanish_ci default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=331 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of profesores
-- ----------------------------

-- ----------------------------
-- Table structure for `profesorescredencial`
-- ----------------------------
DROP TABLE IF EXISTS `profesorescredencial`;
CREATE TABLE `profesorescredencial` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `profesores_id` int(10) unsigned NOT NULL,
  `credencial_id` varchar(20) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `profesorescredencial_FKIndex1` (`profesores_id`),
  CONSTRAINT `profesorescredencial_ibfk_1` FOREIGN KEY (`profesores_id`) REFERENCES `profesores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of profesorescredencial
-- ----------------------------

-- ----------------------------
-- Table structure for `profesoresinfo`
-- ----------------------------
DROP TABLE IF EXISTS `profesoresinfo`;
CREATE TABLE `profesoresinfo` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `codigo` varchar(12) NOT NULL,
  `categoria` varchar(30) NOT NULL,
  `plaza` varchar(50) NOT NULL,
  `horario` varchar(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of profesoresinfo
-- ----------------------------

-- ----------------------------
-- Table structure for `profesorespassword`
-- ----------------------------
DROP TABLE IF EXISTS `profesorespassword`;
CREATE TABLE `profesorespassword` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `profesores_id` int(10) unsigned NOT NULL,
  `pass` varchar(50) collate utf8_spanish_ci NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `profesorespassword_FKIndex1` (`profesores_id`),
  CONSTRAINT `profesorespassword_ibfk_1` FOREIGN KEY (`profesores_id`) REFERENCES `profesores` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=156 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of profesorespassword
-- ----------------------------

-- ----------------------------
-- Table structure for `roles`
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `eventos_id` int(10) unsigned NOT NULL,
  `aco_section` varchar(240) collate utf8_spanish_ci NOT NULL,
  `aco` varchar(240) collate utf8_spanish_ci NOT NULL,
  `aro` varchar(240) collate utf8_spanish_ci NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `roles_FKIndex2` (`eventos_id`),
  CONSTRAINT `roles_ibfk_1` FOREIGN KEY (`eventos_id`) REFERENCES `eventos` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=385 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES ('173', '1', 'ALL', 'ALL', 'ALL');
INSERT INTO `roles` VALUES ('174', '2', 'calificaciones', 'agregar', 'secretarias');
INSERT INTO `roles` VALUES ('175', '2', 'calificaciones', 'agregar', 'oficial');
INSERT INTO `roles` VALUES ('176', '2', 'calificaciones', 'editar', 'oficial');
INSERT INTO `roles` VALUES ('177', '2', 'calificaciones', 'eliminar', 'oficial');
INSERT INTO `roles` VALUES ('178', '2', 'calificaciones', 'editar', 'secretarias');
INSERT INTO `roles` VALUES ('179', '2', 'calificaciones', 'eliminar', 'secretarias');
INSERT INTO `roles` VALUES ('180', '3', 'calificaciones', 'agregar', 'secretarias');
INSERT INTO `roles` VALUES ('181', '3', 'calificaciones', 'agregar', 'oficial');
INSERT INTO `roles` VALUES ('182', '3', 'calificaciones', 'editar', 'oficial');
INSERT INTO `roles` VALUES ('183', '3', 'calificaciones', 'eliminar', 'oficial');
INSERT INTO `roles` VALUES ('184', '3', 'calificaciones', 'editar', 'secretarias');
INSERT INTO `roles` VALUES ('185', '3', 'calificaciones', 'eliminar', 'secretarias');
INSERT INTO `roles` VALUES ('186', '4', 'calificaciones', 'agregar', 'secretarias');
INSERT INTO `roles` VALUES ('187', '4', 'calificaciones', 'agregar', 'oficial');
INSERT INTO `roles` VALUES ('188', '4', 'calificaciones', 'editar', 'oficial');
INSERT INTO `roles` VALUES ('189', '4', 'calificaciones', 'eliminar', 'oficial');
INSERT INTO `roles` VALUES ('190', '4', 'calificaciones', 'editar', 'secretarias');
INSERT INTO `roles` VALUES ('191', '4', 'calificaciones', 'eliminar', 'secretarias');
INSERT INTO `roles` VALUES ('192', '5', 'calificaciones', 'agregar', 'secretarias');
INSERT INTO `roles` VALUES ('193', '5', 'calificaciones', 'agregar', 'oficial');
INSERT INTO `roles` VALUES ('194', '5', 'calificaciones', 'editar', 'oficial');
INSERT INTO `roles` VALUES ('195', '5', 'calificaciones', 'eliminar', 'oficial');
INSERT INTO `roles` VALUES ('196', '5', 'calificaciones', 'editar', 'secretarias');
INSERT INTO `roles` VALUES ('197', '5', 'calificaciones', 'eliminar', 'secretarias');
INSERT INTO `roles` VALUES ('198', '6', 'calificaciones', 'agregar', 'secretarias');
INSERT INTO `roles` VALUES ('199', '6', 'calificaciones', 'agregar', 'oficial');
INSERT INTO `roles` VALUES ('200', '6', 'calificaciones', 'editar', 'oficial');
INSERT INTO `roles` VALUES ('201', '6', 'calificaciones', 'eliminar', 'oficial');
INSERT INTO `roles` VALUES ('202', '6', 'calificaciones', 'editar', 'secretarias');
INSERT INTO `roles` VALUES ('203', '6', 'calificaciones', 'eliminar', 'secretarias');
INSERT INTO `roles` VALUES ('204', '7', 'ALL', 'ALL', 'ALL');
INSERT INTO `roles` VALUES ('205', '8', 'ALL', 'ALL', 'ALL');
INSERT INTO `roles` VALUES ('206', '9', 'calificaciones', 'agregar', 'secretarias');
INSERT INTO `roles` VALUES ('207', '9', 'calificaciones', 'agregar', 'oficial');
INSERT INTO `roles` VALUES ('208', '9', 'calificaciones', 'editar', 'secretarias');
INSERT INTO `roles` VALUES ('209', '9', 'calificaciones', 'editar', 'oficial');
INSERT INTO `roles` VALUES ('210', '9', 'calificaciones', 'eliminar', 'secretarias');
INSERT INTO `roles` VALUES ('211', '9', 'calificaciones', 'eliminar', 'oficial');
INSERT INTO `roles` VALUES ('212', '11', 'asistencias', 'agregar', 'secretarias');
INSERT INTO `roles` VALUES ('213', '11', 'asistencias', 'agregar', 'oficial');
INSERT INTO `roles` VALUES ('214', '11', 'asistencias', 'agregar', 'director');
INSERT INTO `roles` VALUES ('215', '11', 'asistencias', 'agregar', 'profesores');
INSERT INTO `roles` VALUES ('216', '11', 'asistencias', 'agregar', 'secretario');
INSERT INTO `roles` VALUES ('217', '11', 'asistencias', 'eliminar', 'director');
INSERT INTO `roles` VALUES ('218', '11', 'asistencias', 'editar', 'director');
INSERT INTO `roles` VALUES ('219', '11', 'asistencias', 'editar', 'secretario');
INSERT INTO `roles` VALUES ('220', '11', 'asistencias', 'eliminar', 'secretario');
INSERT INTO `roles` VALUES ('221', '13', 'alumnos', 'agregar', 'secretarias');
INSERT INTO `roles` VALUES ('222', '13', 'alumnos', 'agregar', 'oficial');
INSERT INTO `roles` VALUES ('223', '14', 'alumnos', 'ubicar', 'director');
INSERT INTO `roles` VALUES ('224', '14', 'alumnos', 'ubicar', 'secretario');
INSERT INTO `roles` VALUES ('225', '17', 'alumnos', 'agregar', 'oficial');
INSERT INTO `roles` VALUES ('226', '17', 'alumnos', 'agregar', 'secretarias');
INSERT INTO `roles` VALUES ('227', '18', 'inscripcion', 'agregar', 'director');
INSERT INTO `roles` VALUES ('228', '18', 'inscripcion', 'agregar', 'secretario');
INSERT INTO `roles` VALUES ('229', '18', 'inscripcion', 'eliminar', 'director');
INSERT INTO `roles` VALUES ('230', '18', 'inscripcion', 'eliminar', 'secretario');
INSERT INTO `roles` VALUES ('231', '18', 'inscripcion', 'articulo', 'director');
INSERT INTO `roles` VALUES ('232', '18', 'inscripcion', 'articulo', 'secretario');
INSERT INTO `roles` VALUES ('233', '19', 'cursos', 'agregar', 'plantilla');
INSERT INTO `roles` VALUES ('234', '19', 'cursos', 'eliminar', 'plantilla');
INSERT INTO `roles` VALUES ('235', '19', 'cursos', 'editar', 'plantilla');
INSERT INTO `roles` VALUES ('236', '19', 'materias', 'agregar', 'plantilla');
INSERT INTO `roles` VALUES ('237', '19', 'materias', 'eliminar', 'plantilla');
INSERT INTO `roles` VALUES ('238', '19', 'materias', 'editar', 'plantilla');
INSERT INTO `roles` VALUES ('239', '19', 'grupos', 'agregar', 'plantilla');
INSERT INTO `roles` VALUES ('240', '19', 'grupos', 'eliminar', 'plantilla');
INSERT INTO `roles` VALUES ('241', '19', 'grupos', 'editar', 'plantilla');
INSERT INTO `roles` VALUES ('242', '19', 'cursos', 'eliminar', 'director');
INSERT INTO `roles` VALUES ('243', '19', 'cursos', 'editar', 'director');
INSERT INTO `roles` VALUES ('244', '19', 'cursos', 'eliminar', 'secretario');
INSERT INTO `roles` VALUES ('245', '19', 'cursos', 'editar', 'secretario');
INSERT INTO `roles` VALUES ('246', '19', 'materias', 'enlazar', 'plantilla');
INSERT INTO `roles` VALUES ('247', '20', 'ALL', 'ALL', 'ALL');
INSERT INTO `roles` VALUES ('248', '30', 'calificaciones', 'editar', 'secretaria');
INSERT INTO `roles` VALUES ('249', '30', 'calificaciones', 'eliminar', 'alma.gutierrez');
INSERT INTO `roles` VALUES ('250', '29', 'calificaciones', 'editar', 'alma.gutierrez');
INSERT INTO `roles` VALUES ('251', '29', 'calificaciones', 'eliminar', 'alma.gutierrez');
INSERT INTO `roles` VALUES ('252', '26', 'calificaciones', 'agregar', 'alma.gutierrez');
INSERT INTO `roles` VALUES ('253', '30', 'calificaciones', 'agregar', 'secretarias');
INSERT INTO `roles` VALUES ('254', '37', 'calificaciones', 'agregar', 'secretarias');
INSERT INTO `roles` VALUES ('255', '37', 'calificaciones', 'agregar', 'oficial');
INSERT INTO `roles` VALUES ('256', '37', 'calificaciones', 'editar', 'oficial');
INSERT INTO `roles` VALUES ('257', '37', 'calificaciones', 'eliminar', 'oficial');
INSERT INTO `roles` VALUES ('258', '37', 'calificaciones', 'editar', 'secretarias');
INSERT INTO `roles` VALUES ('259', '37', 'calificaciones', 'eliminar', 'secretarias');
INSERT INTO `roles` VALUES ('260', '33', 'calificaciones', 'agregar', 'secretarias');
INSERT INTO `roles` VALUES ('261', '33', 'calificaciones', 'agregar', 'oficial');
INSERT INTO `roles` VALUES ('262', '33', 'calificaciones', 'editar', 'oficial');
INSERT INTO `roles` VALUES ('263', '33', 'calificaciones', 'eliminar', 'oficial');
INSERT INTO `roles` VALUES ('264', '33', 'calificaciones', 'editar', 'secretarias');
INSERT INTO `roles` VALUES ('265', '33', 'calificaciones', 'eliminar', 'secretarias');
INSERT INTO `roles` VALUES ('266', '34', 'calificaciones', 'agregar', 'secretarias');
INSERT INTO `roles` VALUES ('267', '34', 'calificaciones', 'agregar', 'oficial');
INSERT INTO `roles` VALUES ('268', '34', 'calificaciones', 'editar', 'oficial');
INSERT INTO `roles` VALUES ('269', '34', 'calificaciones', 'eliminar', 'oficial');
INSERT INTO `roles` VALUES ('270', '34', 'calificaciones', 'editar', 'secretarias');
INSERT INTO `roles` VALUES ('271', '34', 'calificaciones', 'eliminar', 'secretarias');
INSERT INTO `roles` VALUES ('272', '35', 'calificaciones', 'agregar', 'secretarias');
INSERT INTO `roles` VALUES ('273', '35', 'calificaciones', 'agregar', 'oficial');
INSERT INTO `roles` VALUES ('274', '35', 'calificaciones', 'editar', 'oficial');
INSERT INTO `roles` VALUES ('275', '35', 'calificaciones', 'eliminar', 'oficial');
INSERT INTO `roles` VALUES ('276', '35', 'calificaciones', 'editar', 'secretarias');
INSERT INTO `roles` VALUES ('277', '35', 'calificaciones', 'eliminar', 'secretarias');
INSERT INTO `roles` VALUES ('278', '36', 'calificaciones', 'agregar', 'secretarias');
INSERT INTO `roles` VALUES ('279', '36', 'calificaciones', 'agregar', 'oficial');
INSERT INTO `roles` VALUES ('280', '36', 'calificaciones', 'editar', 'oficial');
INSERT INTO `roles` VALUES ('281', '36', 'calificaciones', 'eliminar', 'oficial');
INSERT INTO `roles` VALUES ('282', '36', 'calificaciones', 'editar', 'secretarias');
INSERT INTO `roles` VALUES ('283', '36', 'calificaciones', 'eliminar', 'secretarias');
INSERT INTO `roles` VALUES ('284', '38', 'calificaciones', 'agregar', 'secretarias');
INSERT INTO `roles` VALUES ('285', '38', 'calificaciones', 'agregar', 'oficial');
INSERT INTO `roles` VALUES ('286', '38', 'calificaciones', 'editar', 'secretarias');
INSERT INTO `roles` VALUES ('287', '38', 'calificaciones', 'editar', 'oficial');
INSERT INTO `roles` VALUES ('288', '38', 'calificaciones', 'eliminar', 'secretarias');
INSERT INTO `roles` VALUES ('289', '38', 'calificaciones', 'eliminar', 'oficial');
INSERT INTO `roles` VALUES ('290', '42', 'calificaciones', 'agregar', 'secretarias');
INSERT INTO `roles` VALUES ('292', '45', 'asistencias', 'agregar', 'secretarias');
INSERT INTO `roles` VALUES ('293', '45', 'asistencias', 'agregar', 'oficial');
INSERT INTO `roles` VALUES ('294', '45', 'asistencias', 'agregar', 'director');
INSERT INTO `roles` VALUES ('295', '45', 'asistencias', 'agregar', 'profesores');
INSERT INTO `roles` VALUES ('296', '45', 'asistencias', 'agregar', 'secretario');
INSERT INTO `roles` VALUES ('297', '45', 'asistencias', 'eliminar', 'director');
INSERT INTO `roles` VALUES ('298', '45', 'asistencias', 'editar', 'director');
INSERT INTO `roles` VALUES ('299', '45', 'asistencias', 'editar', 'secretario');
INSERT INTO `roles` VALUES ('300', '45', 'asistencias', 'eliminar', 'secretario');
INSERT INTO `roles` VALUES ('302', '46', 'asistencias', 'agregar', 'secretarias');
INSERT INTO `roles` VALUES ('303', '46', 'asistencias', 'agregar', '0');
INSERT INTO `roles` VALUES ('304', '46', 'asistencias', 'eliminar', '0');
INSERT INTO `roles` VALUES ('305', '46', 'asistencias', 'editar', '0');
INSERT INTO `roles` VALUES ('306', '47', 'ALL', 'ALL', 'ALL');
INSERT INTO `roles` VALUES ('307', '48', 'ALL', 'ALL', 'ALL');
INSERT INTO `roles` VALUES ('308', '2', 'calificaciones', 'editar', 'secretario');
INSERT INTO `roles` VALUES ('309', '2', 'calificaciones', 'eliminar', 'secretario');
INSERT INTO `roles` VALUES ('310', '2', 'calificaciones', 'editar', 'director');
INSERT INTO `roles` VALUES ('311', '2', 'calificaciones', 'eliminar', 'director');
INSERT INTO `roles` VALUES ('312', '3', 'calificaciones', 'editar', 'secretario');
INSERT INTO `roles` VALUES ('313', '3', 'calificaciones', 'eliminar', 'secretario');
INSERT INTO `roles` VALUES ('314', '3', 'calificaciones', 'editar', 'director');
INSERT INTO `roles` VALUES ('315', '3', 'calificaciones', 'eliminar', 'director');
INSERT INTO `roles` VALUES ('316', '4', 'calificaciones', 'editar', 'secretario');
INSERT INTO `roles` VALUES ('317', '4', 'calificaciones', 'eliminar', 'secretario');
INSERT INTO `roles` VALUES ('318', '4', 'calificaciones', 'editar', 'director');
INSERT INTO `roles` VALUES ('319', '4', 'calificaciones', 'eliminar', 'director');
INSERT INTO `roles` VALUES ('320', '5', 'calificaciones', 'editar', 'secretario');
INSERT INTO `roles` VALUES ('321', '5', 'calificaciones', 'eliminar', 'secretario');
INSERT INTO `roles` VALUES ('322', '5', 'calificaciones', 'editar', 'director');
INSERT INTO `roles` VALUES ('323', '5', 'calificaciones', 'eliminar', 'director');
INSERT INTO `roles` VALUES ('324', '6', 'calificaciones', 'editar', 'secretario');
INSERT INTO `roles` VALUES ('325', '6', 'calificaciones', 'eliminar', 'secretario');
INSERT INTO `roles` VALUES ('326', '6', 'calificaciones', 'editar', 'director');
INSERT INTO `roles` VALUES ('327', '6', 'calificaciones', 'eliminar', 'director');
INSERT INTO `roles` VALUES ('328', '9', 'calificaciones', 'editar', 'secretario');
INSERT INTO `roles` VALUES ('329', '9', 'calificaciones', 'eliminar', 'secretario');
INSERT INTO `roles` VALUES ('330', '9', 'calificaciones', 'editar', 'director');
INSERT INTO `roles` VALUES ('331', '9', 'calificaciones', 'eliminar', 'director');
INSERT INTO `roles` VALUES ('332', '33', 'calificaciones', 'editar', 'secretario');
INSERT INTO `roles` VALUES ('333', '33', 'calificaciones', 'eliminar', 'secretario');
INSERT INTO `roles` VALUES ('334', '33', 'calificaciones', 'editar', 'director');
INSERT INTO `roles` VALUES ('335', '33', 'calificaciones', 'eliminar', 'director');
INSERT INTO `roles` VALUES ('336', '34', 'calificaciones', 'editar', 'secretario');
INSERT INTO `roles` VALUES ('337', '34', 'calificaciones', 'eliminar', 'secretario');
INSERT INTO `roles` VALUES ('338', '34', 'calificaciones', 'editar', 'director');
INSERT INTO `roles` VALUES ('339', '34', 'calificaciones', 'eliminar', 'director');
INSERT INTO `roles` VALUES ('340', '35', 'calificaciones', 'editar', 'secretario');
INSERT INTO `roles` VALUES ('341', '35', 'calificaciones', 'eliminar', 'secretario');
INSERT INTO `roles` VALUES ('342', '35', 'calificaciones', 'editar', 'director');
INSERT INTO `roles` VALUES ('343', '35', 'calificaciones', 'eliminar', 'director');
INSERT INTO `roles` VALUES ('344', '36', 'calificaciones', 'editar', 'secretario');
INSERT INTO `roles` VALUES ('345', '36', 'calificaciones', 'eliminar', 'secretario');
INSERT INTO `roles` VALUES ('346', '36', 'calificaciones', 'editar', 'director');
INSERT INTO `roles` VALUES ('347', '36', 'calificaciones', 'eliminar', 'director');
INSERT INTO `roles` VALUES ('348', '37', 'calificaciones', 'editar', 'secretario');
INSERT INTO `roles` VALUES ('349', '37', 'calificaciones', 'eliminar', 'secretario');
INSERT INTO `roles` VALUES ('350', '37', 'calificaciones', 'editar', 'director');
INSERT INTO `roles` VALUES ('351', '37', 'calificaciones', 'eliminar', 'director');
INSERT INTO `roles` VALUES ('352', '38', 'calificaciones', 'editar', 'secretario');
INSERT INTO `roles` VALUES ('353', '38', 'calificaciones', 'eliminar', 'secretario');
INSERT INTO `roles` VALUES ('354', '38', 'calificaciones', 'editar', 'director');
INSERT INTO `roles` VALUES ('355', '38', 'calificaciones', 'eliminar', 'director');
INSERT INTO `roles` VALUES ('356', '3', 'calificaciones', 'agregar', 'profesores');
INSERT INTO `roles` VALUES ('357', '4', 'calificaciones', 'agregar', 'profesores');
INSERT INTO `roles` VALUES ('358', '5', 'calificaciones', 'agregar', 'profesores');
INSERT INTO `roles` VALUES ('359', '6', 'calificaciones', 'agregar', 'profesores');
INSERT INTO `roles` VALUES ('360', '33', 'calificaciones', 'agregar', 'profesores');
INSERT INTO `roles` VALUES ('361', '34', 'calificaciones', 'agregar', 'profesores');
INSERT INTO `roles` VALUES ('362', '35', 'calificaciones', 'agregar', 'profesores');
INSERT INTO `roles` VALUES ('363', '36', 'calificaciones', 'agregar', 'profesores');
INSERT INTO `roles` VALUES ('364', '2', 'calificaciones', 'agregar', 'profesores');
INSERT INTO `roles` VALUES ('365', '9', 'calificaciones', 'agregar', 'profesores');
INSERT INTO `roles` VALUES ('366', '37', 'calificaciones', 'agregar', 'profesores');
INSERT INTO `roles` VALUES ('367', '38', 'calificaciones', 'agregar', 'profesores');
INSERT INTO `roles` VALUES ('368', '31', 'calificaciones', 'agregar', '8414548');
INSERT INTO `roles` VALUES ('369', '31', 'calificaciones', 'agregar', 'profesores');
INSERT INTO `roles` VALUES ('370', '25', 'asistencias', 'agregar', '8414548');
INSERT INTO `roles` VALUES ('371', '25', 'asistencias', 'editar', '8414548');
INSERT INTO `roles` VALUES ('372', '31', 'calificaciones', 'agregar', '7610173');
INSERT INTO `roles` VALUES ('373', '25', 'asistencias', 'agregar', '7610173');
INSERT INTO `roles` VALUES ('374', '31', 'calificaciones', 'editar', '7610173');
INSERT INTO `roles` VALUES ('375', '32', 'cursos', 'agregar', 'plantilla');
INSERT INTO `roles` VALUES ('376', '32', 'cursos', 'eliminar', 'plantilla');
INSERT INTO `roles` VALUES ('377', '32', 'cursos', 'editar', 'plantilla');
INSERT INTO `roles` VALUES ('378', '32', 'grupos', 'agregar', 'plantilla');
INSERT INTO `roles` VALUES ('379', '32', 'grupos', 'eliminar', 'plantilla');
INSERT INTO `roles` VALUES ('380', '32', 'grupos', 'editar', 'plantilla');
INSERT INTO `roles` VALUES ('381', '32', 'materias', 'agregar', 'plantilla');
INSERT INTO `roles` VALUES ('382', '32', 'materias', 'eliminar', 'plantilla');
INSERT INTO `roles` VALUES ('383', '32', 'materias', 'editar', 'plantilla');
INSERT INTO `roles` VALUES ('384', '32', 'materias', 'enlazar', 'plantilla');

-- ----------------------------
-- Table structure for `sistemas`
-- ----------------------------
DROP TABLE IF EXISTS `sistemas`;
CREATE TABLE `sistemas` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `nombre` varchar(50) character set latin1 default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sistemas
-- ----------------------------
INSERT INTO `sistemas` VALUES ('1', 'Control escolar');
INSERT INTO `sistemas` VALUES ('2', 'Coordinacion academica');

-- ----------------------------
-- Table structure for `sistemasusuarios`
-- ----------------------------
DROP TABLE IF EXISTS `sistemasusuarios`;
CREATE TABLE `sistemasusuarios` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `sistemas_id` int(10) unsigned NOT NULL,
  `usuarios_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `sistemasusuarios_FKIndex1` (`usuarios_id`),
  KEY `sistemasusuarios_FKIndex2` (`sistemas_id`),
  CONSTRAINT `sistemasusuarios_ibfk_1` FOREIGN KEY (`usuarios_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sistemasusuarios_ibfk_2` FOREIGN KEY (`sistemas_id`) REFERENCES `sistemas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sistemasusuarios
-- ----------------------------
INSERT INTO `sistemasusuarios` VALUES ('40', '1', '1');
INSERT INTO `sistemasusuarios` VALUES ('79', '1', '43');

-- ----------------------------
-- Table structure for `situaciones`
-- ----------------------------
DROP TABLE IF EXISTS `situaciones`;
CREATE TABLE `situaciones` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `clave` varchar(3) collate utf8_spanish_ci NOT NULL,
  `nombre` varchar(20) collate utf8_spanish_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of situaciones
-- ----------------------------
INSERT INTO `situaciones` VALUES ('1', 'REG', 'Regular');
INSERT INTO `situaciones` VALUES ('2', 'BJA', 'Baja administrativa');
INSERT INTO `situaciones` VALUES ('3', 'EGR', 'Egresado');
INSERT INTO `situaciones` VALUES ('4', 'IRR', 'Irregular');
INSERT INTO `situaciones` VALUES ('5', 'REP', 'Repetidor');

-- ----------------------------
-- Table structure for `terminales`
-- ----------------------------
DROP TABLE IF EXISTS `terminales`;
CREATE TABLE `terminales` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `aulas_id` int(10) unsigned NOT NULL,
  `ip` varchar(15) character set latin1 default NULL,
  `noserie` varchar(50) character set latin1 default NULL,
  PRIMARY KEY  (`id`),
  KEY `terminales_FKIndex1` (`aulas_id`),
  CONSTRAINT `terminales_ibfk_1` FOREIGN KEY (`aulas_id`) REFERENCES `aulas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of terminales
-- ----------------------------
INSERT INTO `terminales` VALUES ('1', '7', '148.202.65.103', '----');

-- ----------------------------
-- Table structure for `tipopersonal`
-- ----------------------------
DROP TABLE IF EXISTS `tipopersonal`;
CREATE TABLE `tipopersonal` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `nombre` varchar(50) character set latin1 default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tipopersonal
-- ----------------------------
INSERT INTO `tipopersonal` VALUES ('1', 'Guardias');
INSERT INTO `tipopersonal` VALUES ('2', 'Personal administrativo');
INSERT INTO `tipopersonal` VALUES ('3', 'Prestadores de servicio');

-- ----------------------------
-- Table structure for `trayectoriaespecializante`
-- ----------------------------
DROP TABLE IF EXISTS `trayectoriaespecializante`;
CREATE TABLE `trayectoriaespecializante` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `nombre` varchar(50) character set latin1 NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of trayectoriaespecializante
-- ----------------------------

-- ----------------------------
-- Table structure for `trayectoriasespecializantesmaterias`
-- ----------------------------
DROP TABLE IF EXISTS `trayectoriasespecializantesmaterias`;
CREATE TABLE `trayectoriasespecializantesmaterias` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `trayectoriaespecializante_id` int(10) unsigned NOT NULL,
  `materias_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `trayectoriasespecializantesmaterias_FKIndex1` (`materias_id`),
  KEY `trayectoriasespecializantesmaterias_FKIndex2` (`trayectoriaespecializante_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of trayectoriasespecializantesmaterias
-- ----------------------------

-- ----------------------------
-- Table structure for `tutores`
-- ----------------------------
DROP TABLE IF EXISTS `tutores`;
CREATE TABLE `tutores` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `nombre` varchar(30) collate utf8_spanish_ci NOT NULL,
  `ap` varchar(20) collate utf8_spanish_ci NOT NULL,
  `am` varchar(20) collate utf8_spanish_ci NOT NULL,
  `domicilio` varchar(60) collate utf8_spanish_ci default NULL,
  `tel` varchar(20) collate utf8_spanish_ci default NULL,
  `cel` varchar(20) collate utf8_spanish_ci default NULL,
  `mail` varchar(80) collate utf8_spanish_ci default NULL,
  `foto` varchar(20) collate utf8_spanish_ci default NULL,
  `fnacimiento` date default NULL,
  `sexo` varchar(1) collate utf8_spanish_ci default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5453 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of tutores
-- ----------------------------

-- ----------------------------
-- Table structure for `tutoresgrupos`
-- ----------------------------
DROP TABLE IF EXISTS `tutoresgrupos`;
CREATE TABLE `tutoresgrupos` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `profesores_id` int(10) unsigned NOT NULL,
  `grupos_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `tutoresgrupos_FKIndex1` (`grupos_id`),
  KEY `tutoresgrupos_FKIndex2` (`profesores_id`),
  CONSTRAINT `tutoresgrupos_ibfk_1` FOREIGN KEY (`grupos_id`) REFERENCES `grupos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tutoresgrupos_ibfk_2` FOREIGN KEY (`profesores_id`) REFERENCES `profesores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tutoresgrupos
-- ----------------------------

-- ----------------------------
-- Table structure for `tutorespassword`
-- ----------------------------
DROP TABLE IF EXISTS `tutorespassword`;
CREATE TABLE `tutorespassword` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tutores_id` int(10) unsigned NOT NULL,
  `pass` varchar(50) collate utf8_spanish_ci NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `tutorpassword_FKIndex1` (`tutores_id`),
  CONSTRAINT `tutorespassword_ibfk_1` FOREIGN KEY (`tutores_id`) REFERENCES `tutores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4081 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of tutorespassword
-- ----------------------------

-- ----------------------------
-- Table structure for `tutoria`
-- ----------------------------
DROP TABLE IF EXISTS `tutoria`;
CREATE TABLE `tutoria` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `alumnos_id` int(10) unsigned NOT NULL,
  `tutores_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `tutoria_FKIndex1` (`tutores_id`),
  KEY `tutoria_FKIndex2` (`alumnos_id`),
  CONSTRAINT `tutoria_ibfk_1` FOREIGN KEY (`tutores_id`) REFERENCES `tutores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tutoria_ibfk_2` FOREIGN KEY (`alumnos_id`) REFERENCES `alumnos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5455 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of tutoria
-- ----------------------------

-- ----------------------------
-- Table structure for `usuarios`
-- ----------------------------
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `login` varchar(16) collate utf8_spanish_ci NOT NULL,
  `pass` varchar(50) collate utf8_spanish_ci NOT NULL,
  `nombre` varchar(30) collate utf8_spanish_ci NOT NULL,
  `ap` varchar(20) collate utf8_spanish_ci NOT NULL,
  `am` varchar(20) collate utf8_spanish_ci NOT NULL,
  `mail` varchar(80) collate utf8_spanish_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of usuarios
-- ----------------------------
INSERT INTO `usuarios` VALUES ('1', 'root', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'Nombre', 'Paterno', 'Materno', '');
INSERT INTO `usuarios` VALUES ('43', 'admin', '7c2696b6a4f629b4a6e7c4ed269a5bbb8d714d9d', 'admin', '_', '_', '_');
