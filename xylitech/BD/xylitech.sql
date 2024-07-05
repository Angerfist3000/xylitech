-- Volcando estructura de base de datos para xylitech

CREATE DATABASE IF NOT EXISTS `xylitech`
USE `xylitech`;

-- Volcando estructura para tabla xylitech.tarea
DROP TABLE IF EXISTS `tarea`;
CREATE TABLE IF NOT EXISTS `tarea` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Descripcion` varchar(255) DEFAULT NULL,
  `Fecha` date DEFAULT NULL,
  `Asignado` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- La exportación de datos fue deseleccionada.
