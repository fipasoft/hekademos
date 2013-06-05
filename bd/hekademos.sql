/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50075
Source Host           : localhost:3306
Source Database       : hekademos

Target Server Type    : MYSQL
Target Server Version : 50075
File Encoding         : 65001

Date: 2010-07-25 18:24:17
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of asistenciasvalor
-- ----------------------------

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
  CONSTRAINT `bloquesalumnos_ibfk_1` FOREIGN KEY (`bloque_id`) REFERENCES `bloque` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bloquesalumnos_ibfk_2` FOREIGN KEY (`periodosalumnos_id`) REFERENCES `periodosalumnos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
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
  CONSTRAINT `historial_ibfk_1` FOREIGN KEY (`ciclos_id`) REFERENCES `ciclos` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19285 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of historial
-- ----------------------------

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
  CONSTRAINT `horarios_ibfk_1` FOREIGN KEY (`aulas_id`) REFERENCES `aulas` (`id`),
  CONSTRAINT `horarios_ibfk_2` FOREIGN KEY (`dias_id`) REFERENCES `dias` (`id`),
  CONSTRAINT `horarios_ibfk_3` FOREIGN KEY (`cursos_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
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
  CONSTRAINT `horariotemporal_ibfk_1` FOREIGN KEY (`dias_id`) REFERENCES `dias` (`id`),
  CONSTRAINT `horariotemporal_ibfk_2` FOREIGN KEY (`cursos_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
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
