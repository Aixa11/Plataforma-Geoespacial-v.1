CREATE DATABASE `detecciones_database`.`detecciones` (
    `IdDeteccion` INT(100) NOT NULL , 
    `Fecha` DATETIME NOT NULL , 
    `IdProducto` VARCHAR(255) NOT NULL , 
    PRIMARY KEY (`IdDeteccion`)) 
    ENGINE = InnoDB;

--
-- Volcado de datos para la tabla `Detecciones`
--

INSERT INTO `detecciones` (`IdDeteccion`,`Fecha`, `IdProducto`) VALUES
(1, '23/01/03', 'PRODUCTO_PRUEBA'),
(6, '23/01/03', 'PRODUCTO PRUEBA'),

--
ALTER TABLE `detecciones`
  ADD PRIMARY KEY (`IdDetecciones`);

CREATE TABLE `detecciones_database`.`manchas` (
    `IdMancha` INT(100) NOT NULL , 
    `IdDeteccion` INT(100) NOT NULL , 
    `Mancha` POLYGON(255) NOT NULL , 
    `Centro` POINT(255) NOT NULL , 
    `Superficie` DECIMAL(5) NOT NULL , 
    PRIMARY KEY (`IdMancha`)) ENGINE = InnoDB;

ALTER TABLE `manchas`
  ADD FOREIGN KEY (`IdManchas`);

--Insertar Datos en la Tabla manchas

INSERT INTO `detecciones_database`.`manchas` (`IdMancha`, `IdDeteccion`, `Mancha`, `Centro`, `Superficie`)
VALUES (1, 1, 'POLYGON((10 10, 20 10, 20 20, 10 20, 10 10))', 'POINT(15 15)', 25.5);


-- Insertar Poligonos en Mancha de la tabla manchas

SELECT Mancha FROM manchas;
SET @json = '{ "type": "Polygon", "coordinates": [[[-71.1776585052917,
42.3902909739571],[-71.1776820268866, 42.3903701743239],[-71.1776063012595,
42.3903825660754],[-71.1775826583081, 42.3903033653531],[-71.1776585052917, 
42.3902909739571],[-71.1776585052917,42.3902909739571]]]}';
SELECT ST_AsText(ST_GeomFromGeoJSON(@json));


#con mysql SELECT * 

#  -> FROM Manchas a 

# -> LEFT JOIN Detecciones c 

#-> ON a.idDeteccion = c.idDeteccion; 

ALTER TABLE `detecciones`
  ADD PRIMARY KEY (`IdDetecciones`); REFERENCES `manchas` (IdManchas)
COMMIT;