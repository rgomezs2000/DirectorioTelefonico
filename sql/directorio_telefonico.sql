-- ============================================================
--  BASE DE DATOS: DIRECTORIO TELEFÓNICO COMPLETO
--  Motor: MySQL 8.0+
--  Autor: Generado por Claude (Anthropic)
--  Descripción: Directorio telefónico con usuarios, contactos,
--               divisiones administrativas, profesiones y más.
-- ============================================================

DROP DATABASE IF EXISTS directorio_telefonico;
CREATE DATABASE directorio_telefonico
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE directorio_telefonico;

-- ============================================================
-- SECCIÓN 1: CATÁLOGOS BASE
-- ============================================================

-- ------------------------------------------------------------
-- 1.1 SEXOS / GÉNEROS
-- ------------------------------------------------------------
CREATE TABLE sexos (
    id_sexo        TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    nombre         VARCHAR(50)      NOT NULL,
    abreviatura    CHAR(3)          NOT NULL,
    descripcion    VARCHAR(200)         NULL,
    activo         TINYINT(1)       NOT NULL DEFAULT 1,
    creado_en      DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_sexos PRIMARY KEY (id_sexo),
    CONSTRAINT uq_sexos_nombre       UNIQUE (nombre),
    CONSTRAINT uq_sexos_abreviatura  UNIQUE (abreviatura)
) ENGINE=InnoDB COMMENT='Catálogo de sexos / géneros';

CREATE INDEX idx_sexos_activo ON sexos (activo);

INSERT INTO sexos (nombre, abreviatura, descripcion) VALUES
  ('Masculino',              'M',   'Hombre / varón'),
  ('Femenino',               'F',   'Mujer'),
  ('No binario',             'NB',  'Identidad fuera del binarismo'),
  ('Género fluido',          'GF',  'Género que varía con el tiempo'),
  ('Agénero',                'AG',  'Sin identificación de género'),
  ('Bigénero',               'BG',  'Dos géneros simultáneos'),
  ('Intersexual',            'IS',  'Variación biológica de sexo'),
  ('Prefiero no indicarlo',  'NS',  'El usuario prefiere no especificar');


-- ------------------------------------------------------------
-- 1.2 TIPOS DE USUARIOS
-- ------------------------------------------------------------
CREATE TABLE tipos_usuario (
    id_tipo_usuario  TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    nombre           VARCHAR(60)      NOT NULL,
    descripcion      VARCHAR(300)         NULL,
    nivel_acceso     TINYINT UNSIGNED NOT NULL DEFAULT 1
                       COMMENT '1=básico … 100=superadmin',
    activo           TINYINT(1)       NOT NULL DEFAULT 1,
    creado_en        DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_tipos_usuario PRIMARY KEY (id_tipo_usuario),
    CONSTRAINT uq_tipos_usuario_nombre UNIQUE (nombre)
) ENGINE=InnoDB COMMENT='Roles / perfiles de acceso al sistema';

CREATE INDEX idx_tipos_usuario_nivel  ON tipos_usuario (nivel_acceso);
CREATE INDEX idx_tipos_usuario_activo ON tipos_usuario (activo);

INSERT INTO tipos_usuario (nombre, descripcion, nivel_acceso) VALUES
  ('Superadministrador', 'Control total del sistema',              100),
  ('Administrador',      'Gestión de usuarios y contenido',         80),
  ('Moderador',          'Revisión y aprobación de contenido',      60),
  ('Usuario Premium',    'Acceso extendido con funciones extra',    40),
  ('Usuario Estándar',   'Acceso básico al directorio',             20),
  ('Invitado',           'Solo lectura, sin autenticación',          5);


-- ------------------------------------------------------------
-- 1.3 PROFESIONES / OCUPACIONES
-- ------------------------------------------------------------
CREATE TABLE profesiones (
    id_profesion  INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    nombre        VARCHAR(120)  NOT NULL,
    descripcion   VARCHAR(400)      NULL,
    categoria     VARCHAR(80)       NULL
                    COMMENT 'Ej: Salud, Tecnología, Educación…',
    activo        TINYINT(1)    NOT NULL DEFAULT 1,
    creado_en     DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_profesiones PRIMARY KEY (id_profesion),
    CONSTRAINT uq_profesiones_nombre UNIQUE (nombre)
) ENGINE=InnoDB COMMENT='Catálogo de profesiones u ocupaciones';

CREATE INDEX idx_profesiones_categoria ON profesiones (categoria);
CREATE INDEX idx_profesiones_activo     ON profesiones (activo);

INSERT INTO profesiones (nombre, categoria) VALUES
  ('Médico / Médica',              'Salud'),
  ('Enfermero / Enfermera',        'Salud'),
  ('Odontólogo / Odontóloga',      'Salud'),
  ('Psicólogo / Psicóloga',        'Salud'),
  ('Farmacéutico / Farmacéutica',  'Salud'),
  ('Ingeniero de Software',        'Tecnología'),
  ('Desarrollador Web',            'Tecnología'),
  ('Analista de Datos',            'Tecnología'),
  ('Administrador de Redes',       'Tecnología'),
  ('Diseñador UX/UI',              'Tecnología'),
  ('Docente / Profesor',           'Educación'),
  ('Investigador / Investigadora', 'Educación'),
  ('Rector / Rectora',             'Educación'),
  ('Abogado / Abogada',            'Derecho'),
  ('Juez / Jueza',                 'Derecho'),
  ('Notario / Notaria',            'Derecho'),
  ('Contador / Contadora',         'Finanzas'),
  ('Economista',                   'Finanzas'),
  ('Asesor Financiero',            'Finanzas'),
  ('Arquitecto / Arquitecta',      'Construcción'),
  ('Ingeniero Civil',              'Construcción'),
  ('Electricista',                 'Construcción'),
  ('Periodista',                   'Comunicación'),
  ('Locutor / Locutora',           'Comunicación'),
  ('Fotógrafo / Fotógrafa',        'Arte y Diseño'),
  ('Artista Plástico',             'Arte y Diseño'),
  ('Músico / Música',              'Arte y Diseño'),
  ('Chef / Cocinero Profesional',  'Gastronomía'),
  ('Nutricionista',                'Gastronomía'),
  ('Agricultor / Agricultora',     'Agropecuario'),
  ('Veterinario / Veterinaria',    'Agropecuario'),
  ('Militar',                      'Seguridad'),
  ('Policía',                      'Seguridad'),
  ('Bombero / Bombera',            'Seguridad'),
  ('Empresario / Empresaria',      'Negocios'),
  ('Comerciante',                  'Negocios'),
  ('Estudiante',                   'Educación'),
  ('Ama / Amo de casa',            'Hogar'),
  ('Jubilado / Jubilada',          'Otro'),
  ('Otro / No especificado',       'Otro');


-- ============================================================
-- SECCIÓN 2: GEOGRAFÍA ADMINISTRATIVA
-- ============================================================

-- ------------------------------------------------------------
-- 2.1 PAÍSES
-- ------------------------------------------------------------
CREATE TABLE paises (
    id_pais         SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    nombre          VARCHAR(100)      NOT NULL,
    nombre_oficial  VARCHAR(150)          NULL,
    iso2            CHAR(2)           NOT NULL COMMENT 'ISO 3166-1 alpha-2',
    iso3            CHAR(3)           NOT NULL COMMENT 'ISO 3166-1 alpha-3',
    codigo_numerico CHAR(3)               NULL COMMENT 'ISO 3166-1 numérico',
    codigo_telefono VARCHAR(10)           NULL COMMENT 'Prefijo internacional',
    continente      VARCHAR(30)           NULL,
    capital         VARCHAR(100)          NULL,
    moneda          CHAR(3)               NULL COMMENT 'ISO 4217',
    idioma_oficial  VARCHAR(80)           NULL,
    activo          TINYINT(1)        NOT NULL DEFAULT 1,
    creado_en       DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_paises PRIMARY KEY (id_pais),
    CONSTRAINT uq_paises_iso2 UNIQUE (iso2),
    CONSTRAINT uq_paises_iso3 UNIQUE (iso3)
) ENGINE=InnoDB COMMENT='Países del mundo (ISO 3166-1)';

CREATE INDEX idx_paises_continente ON paises (continente);
CREATE INDEX idx_paises_activo     ON paises (activo);

-- Muestra representativa de países
INSERT INTO paises (nombre, nombre_oficial, iso2, iso3, codigo_numerico, codigo_telefono, continente, capital, moneda, idioma_oficial) VALUES
  ('Argentina',        'República Argentina',                  'AR','ARG','032','+54',  'América del Sur',  'Buenos Aires',    'ARS','Español'),
  ('Bolivia',          'Estado Plurinacional de Bolivia',      'BO','BOL','068','+591', 'América del Sur',  'Sucre / La Paz',  'BOB','Español'),
  ('Brasil',           'República Federativa del Brasil',      'BR','BRA','076','+55',  'América del Sur',  'Brasilia',        'BRL','Portugués'),
  ('Chile',            'República de Chile',                   'CL','CHL','152','+56',  'América del Sur',  'Santiago',        'CLP','Español'),
  ('Colombia',         'República de Colombia',                'CO','COL','170','+57',  'América del Sur',  'Bogotá',          'COP','Español'),
  ('Costa Rica',       'República de Costa Rica',              'CR','CRI','188','+506', 'América Central',  'San José',        'CRC','Español'),
  ('Cuba',             'República de Cuba',                    'CU','CUB','192','+53',  'América del Norte','La Habana',       'CUP','Español'),
  ('Ecuador',          'República del Ecuador',                'EC','ECU','218','+593', 'América del Sur',  'Quito',           'USD','Español'),
  ('El Salvador',      'República de El Salvador',             'SV','SLV','222','+503', 'América Central',  'San Salvador',    'USD','Español'),
  ('España',           'Reino de España',                      'ES','ESP','724','+34',  'Europa',           'Madrid',          'EUR','Español'),
  ('Estados Unidos',   'United States of America',             'US','USA','840','+1',   'América del Norte','Washington D.C.', 'USD','Inglés'),
  ('Guatemala',        'República de Guatemala',               'GT','GTM','320','+502', 'América Central',  'Ciudad de Guatemala','GTQ','Español'),
  ('Honduras',         'República de Honduras',                'HN','HND','340','+504', 'América Central',  'Tegucigalpa',     'HNL','Español'),
  ('México',           'Estados Unidos Mexicanos',             'MX','MEX','484','+52',  'América del Norte','Ciudad de México', 'MXN','Español'),
  ('Nicaragua',        'República de Nicaragua',               'NI','NIC','558','+505', 'América Central',  'Managua',         'NIO','Español'),
  ('Panamá',           'República de Panamá',                  'PA','PAN','591','+507', 'América Central',  'Ciudad de Panamá','PAB','Español'),
  ('Paraguay',         'República del Paraguay',               'PY','PRY','600','+595', 'América del Sur',  'Asunción',        'PYG','Español'),
  ('Perú',             'República del Perú',                   'PE','PER','604','+51',  'América del Sur',  'Lima',            'PEN','Español'),
  ('Puerto Rico',      'Estado Libre Asociado de Puerto Rico', 'PR','PRI','630','+1787','América del Norte','San Juan',        'USD','Español'),
  ('República Dominicana','República Dominicana',              'DO','DOM','214','+1809','América del Norte','Santo Domingo',   'DOP','Español'),
  ('Uruguay',          'República Oriental del Uruguay',       'UY','URY','858','+598', 'América del Sur',  'Montevideo',      'UYU','Español'),
  ('Venezuela',        'República Bolivariana de Venezuela',   'VE','VEN','862','+58',  'América del Sur',  'Caracas',         'VES','Español'),
  ('Alemania',         'Bundesrepublik Deutschland',           'DE','DEU','276','+49',  'Europa',           'Berlín',          'EUR','Alemán'),
  ('Francia',          'République française',                 'FR','FRA','250','+33',  'Europa',           'París',           'EUR','Francés'),
  ('Italia',           'Repubblica Italiana',                  'IT','ITA','380','+39',  'Europa',           'Roma',            'EUR','Italiano'),
  ('Canadá',           'Canada',                               'CA','CAN','124','+1',   'América del Norte','Ottawa',          'CAD','Inglés/Francés'),
  ('China',            'República Popular China',              'CN','CHN','156','+86',  'Asia',             'Pekín',           'CNY','Chino mandarín'),
  ('Japón',            'Nihon-koku',                           'JP','JPN','392','+81',  'Asia',             'Tokio',           'JPY','Japonés'),
  ('Reino Unido',      'United Kingdom of Great Britain',      'GB','GBR','826','+44',  'Europa',           'Londres',         'GBP','Inglés'),
  ('Portugal',         'República Portuguesa',                 'PT','PRT','620','+351', 'Europa',           'Lisboa',          'EUR','Portugués');


-- ------------------------------------------------------------
-- 2.2 PRIMERA DIVISIÓN ADMINISTRATIVA (Estado / Departamento / Provincia)
-- ------------------------------------------------------------
CREATE TABLE divisiones_nivel1 (
    id_nivel1   INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    id_pais     SMALLINT UNSIGNED NOT NULL,
    nombre      VARCHAR(120)     NOT NULL,
    codigo      VARCHAR(10)          NULL COMMENT 'Código ISO o interno',
    tipo        VARCHAR(50)          NULL COMMENT 'Estado, Departamento, Provincia…',
    capital     VARCHAR(100)         NULL,
    activo      TINYINT(1)       NOT NULL DEFAULT 1,
    creado_en   DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_divisiones_nivel1    PRIMARY KEY (id_nivel1),
    CONSTRAINT fk_dnivel1_pais
        FOREIGN KEY (id_pais) REFERENCES paises (id_pais)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB COMMENT='Primera división administrativa (dpto/estado/provincia)';

CREATE INDEX idx_dnivel1_pais   ON divisiones_nivel1 (id_pais);
CREATE INDEX idx_dnivel1_activo ON divisiones_nivel1 (activo);
CREATE INDEX idx_dnivel1_nombre ON divisiones_nivel1 (nombre);

-- Ejemplo: Colombia (departamentos)
INSERT INTO divisiones_nivel1 (id_pais, nombre, codigo, tipo, capital)
SELECT id_pais, col.nombre, col.codigo, 'Departamento', col.capital
FROM paises p
CROSS JOIN (
  SELECT 'Amazonas'          AS nombre,'CO-AMA' AS codigo,'Leticia'            AS capital UNION ALL
  SELECT 'Antioquia',               'CO-ANT','Medellín' UNION ALL
  SELECT 'Arauca',                  'CO-ARA','Arauca' UNION ALL
  SELECT 'Atlántico',               'CO-ATL','Barranquilla' UNION ALL
  SELECT 'Bolívar',                 'CO-BOL','Cartagena de Indias' UNION ALL
  SELECT 'Boyacá',                  'CO-BOY','Tunja' UNION ALL
  SELECT 'Caldas',                  'CO-CAL','Manizales' UNION ALL
  SELECT 'Caquetá',                 'CO-CAQ','Florencia' UNION ALL
  SELECT 'Casanare',                'CO-CAS','Yopal' UNION ALL
  SELECT 'Cauca',                   'CO-CAU','Popayán' UNION ALL
  SELECT 'Cesar',                   'CO-CES','Valledupar' UNION ALL
  SELECT 'Chocó',                   'CO-CHO','Quibdó' UNION ALL
  SELECT 'Córdoba',                 'CO-COR','Montería' UNION ALL
  SELECT 'Cundinamarca',            'CO-CUN','Bogotá D.C.' UNION ALL
  SELECT 'Bogotá D.C.',             'CO-DC', 'Bogotá D.C.' UNION ALL
  SELECT 'Guainía',                 'CO-GUA','Inírida' UNION ALL
  SELECT 'Guaviare',                'CO-GUV','San José del Guaviare' UNION ALL
  SELECT 'Huila',                   'CO-HUI','Neiva' UNION ALL
  SELECT 'La Guajira',              'CO-LAG','Riohacha' UNION ALL
  SELECT 'Magdalena',               'CO-MAG','Santa Marta' UNION ALL
  SELECT 'Meta',                    'CO-MET','Villavicencio' UNION ALL
  SELECT 'Nariño',                  'CO-NAR','Pasto' UNION ALL
  SELECT 'Norte de Santander',      'CO-NSA','Cúcuta' UNION ALL
  SELECT 'Putumayo',                'CO-PUT','Mocoa' UNION ALL
  SELECT 'Quindío',                 'CO-QUI','Armenia' UNION ALL
  SELECT 'Risaralda',               'CO-RIS','Pereira' UNION ALL
  SELECT 'San Andrés y Providencia','CO-SAP','San Andrés' UNION ALL
  SELECT 'Santander',               'CO-SAN','Bucaramanga' UNION ALL
  SELECT 'Sucre',                   'CO-SUC','Sincelejo' UNION ALL
  SELECT 'Tolima',                  'CO-TOL','Ibagué' UNION ALL
  SELECT 'Valle del Cauca',         'CO-VAC','Cali' UNION ALL
  SELECT 'Vaupés',                  'CO-VAU','Mitú' UNION ALL
  SELECT 'Vichada',                 'CO-VID','Puerto Carreño'
) AS col
WHERE p.iso2 = 'CO';

-- Ejemplo: México (estados)
INSERT INTO divisiones_nivel1 (id_pais, nombre, codigo, tipo, capital)
SELECT id_pais, mx.nombre, mx.codigo, 'Estado', mx.capital
FROM paises p
CROSS JOIN (
  SELECT 'Aguascalientes'     AS nombre,'MX-AGU' AS codigo,'Aguascalientes'   AS capital UNION ALL
  SELECT 'Baja California',         'MX-BCN','Mexicali' UNION ALL
  SELECT 'Baja California Sur',     'MX-BCS','La Paz' UNION ALL
  SELECT 'Campeche',                'MX-CAM','Campeche' UNION ALL
  SELECT 'Chiapas',                 'MX-CHP','Tuxtla Gutiérrez' UNION ALL
  SELECT 'Chihuahua',               'MX-CHH','Chihuahua' UNION ALL
  SELECT 'Ciudad de México',        'MX-CMX','Ciudad de México' UNION ALL
  SELECT 'Coahuila',                'MX-COA','Saltillo' UNION ALL
  SELECT 'Colima',                  'MX-COL','Colima' UNION ALL
  SELECT 'Durango',                 'MX-DUR','Durango' UNION ALL
  SELECT 'Guanajuato',              'MX-GUA','Guanajuato' UNION ALL
  SELECT 'Guerrero',                'MX-GRO','Chilpancingo' UNION ALL
  SELECT 'Hidalgo',                 'MX-HID','Pachuca' UNION ALL
  SELECT 'Jalisco',                 'MX-JAL','Guadalajara' UNION ALL
  SELECT 'México',                  'MX-MEX','Toluca' UNION ALL
  SELECT 'Michoacán',               'MX-MIC','Morelia' UNION ALL
  SELECT 'Morelos',                 'MX-MOR','Cuernavaca' UNION ALL
  SELECT 'Nayarit',                 'MX-NAY','Tepic' UNION ALL
  SELECT 'Nuevo León',              'MX-NLE','Monterrey' UNION ALL
  SELECT 'Oaxaca',                  'MX-OAX','Oaxaca de Juárez' UNION ALL
  SELECT 'Puebla',                  'MX-PUE','Puebla de Zaragoza' UNION ALL
  SELECT 'Querétaro',               'MX-QUE','Querétaro' UNION ALL
  SELECT 'Quintana Roo',            'MX-ROO','Chetumal' UNION ALL
  SELECT 'San Luis Potosí',         'MX-SLP','San Luis Potosí' UNION ALL
  SELECT 'Sinaloa',                 'MX-SIN','Culiacán' UNION ALL
  SELECT 'Sonora',                  'MX-SON','Hermosillo' UNION ALL
  SELECT 'Tabasco',                 'MX-TAB','Villahermosa' UNION ALL
  SELECT 'Tamaulipas',              'MX-TAM','Ciudad Victoria' UNION ALL
  SELECT 'Tlaxcala',                'MX-TLA','Tlaxcala' UNION ALL
  SELECT 'Veracruz',                'MX-VER','Xalapa' UNION ALL
  SELECT 'Yucatán',                 'MX-YUC','Mérida' UNION ALL
  SELECT 'Zacatecas',               'MX-ZAC','Zacatecas'
) AS mx
WHERE p.iso2 = 'MX';


-- ------------------------------------------------------------
-- 2.3 SEGUNDA DIVISIÓN ADMINISTRATIVA (Municipio / Ciudad / Cantón)
-- ------------------------------------------------------------
CREATE TABLE divisiones_nivel2 (
    id_nivel2    INT UNSIGNED NOT NULL AUTO_INCREMENT,
    id_nivel1    INT UNSIGNED NOT NULL,
    nombre       VARCHAR(120) NOT NULL,
    codigo       VARCHAR(15)      NULL,
    tipo         VARCHAR(50)      NULL COMMENT 'Municipio, Ciudad, Cantón…',
    capital      VARCHAR(100)     NULL,
    poblacion    INT UNSIGNED     NULL,
    activo       TINYINT(1)   NOT NULL DEFAULT 1,
    creado_en    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_divisiones_nivel2  PRIMARY KEY (id_nivel2),
    CONSTRAINT fk_dnivel2_nivel1
        FOREIGN KEY (id_nivel1) REFERENCES divisiones_nivel1 (id_nivel1)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB COMMENT='Segunda división administrativa (municipio/ciudad/cantón)';

CREATE INDEX idx_dnivel2_nivel1 ON divisiones_nivel2 (id_nivel1);
CREATE INDEX idx_dnivel2_activo ON divisiones_nivel2 (activo);
CREATE INDEX idx_dnivel2_nombre ON divisiones_nivel2 (nombre);

-- Municipios de Antioquia (Colombia) – muestra representativa
INSERT INTO divisiones_nivel2 (id_nivel1, nombre, codigo, tipo)
SELECT dn1.id_nivel1, m.nombre, m.codigo, 'Municipio'
FROM divisiones_nivel1 dn1
JOIN paises p ON dn1.id_pais = p.id_pais
CROSS JOIN (
  SELECT 'Medellín'          AS nombre,'05001' AS codigo UNION ALL
  SELECT 'Bello',                   '05088' UNION ALL
  SELECT 'Itagüí',                  '05360' UNION ALL
  SELECT 'Envigado',                '05266' UNION ALL
  SELECT 'Apartadó',                '05045' UNION ALL
  SELECT 'Turbo',                   '05837' UNION ALL
  SELECT 'Rionegro',                '05615' UNION ALL
  SELECT 'Caucasia',                '05154' UNION ALL
  SELECT 'Sabaneta',                '05631' UNION ALL
  SELECT 'Copacabana',              '05212'
) AS m
WHERE p.iso2 = 'CO' AND dn1.nombre = 'Antioquia';

-- Municipios de Valle del Cauca (Colombia)
INSERT INTO divisiones_nivel2 (id_nivel1, nombre, codigo, tipo)
SELECT dn1.id_nivel1, m.nombre, m.codigo, 'Municipio'
FROM divisiones_nivel1 dn1
JOIN paises p ON dn1.id_pais = p.id_pais
CROSS JOIN (
  SELECT 'Cali'              AS nombre,'76001' AS codigo UNION ALL
  SELECT 'Buenaventura',            '76109' UNION ALL
  SELECT 'Palmira',                 '76520' UNION ALL
  SELECT 'Tuluá',                   '76834' UNION ALL
  SELECT 'Cartago',                 '76147' UNION ALL
  SELECT 'Buga',                    '76111' UNION ALL
  SELECT 'Jamundí',                 '76364' UNION ALL
  SELECT 'Yumbo',                   '76892'
) AS m
WHERE p.iso2 = 'CO' AND dn1.nombre = 'Valle del Cauca';

-- Municipios de Jalisco (México)
INSERT INTO divisiones_nivel2 (id_nivel1, nombre, codigo, tipo)
SELECT dn1.id_nivel1, m.nombre, m.codigo, 'Municipio'
FROM divisiones_nivel1 dn1
JOIN paises p ON dn1.id_pais = p.id_pais
CROSS JOIN (
  SELECT 'Guadalajara'       AS nombre,'14039' AS codigo UNION ALL
  SELECT 'Zapopan',                 '14120' UNION ALL
  SELECT 'Tlaquepaque',             '14097' UNION ALL
  SELECT 'Tonalá',                  '14101' UNION ALL
  SELECT 'Puerto Vallarta',         '14067' UNION ALL
  SELECT 'Lagos de Moreno',         '14051' UNION ALL
  SELECT 'Tepatitlán',              '14089'
) AS m
WHERE p.iso2 = 'MX' AND dn1.nombre = 'Jalisco';


-- ------------------------------------------------------------
-- 2.4 TERCERA DIVISIÓN ADMINISTRATIVA (Barrio / Parroquia / Localidad)
-- ------------------------------------------------------------
CREATE TABLE divisiones_nivel3 (
    id_nivel3   INT UNSIGNED NOT NULL AUTO_INCREMENT,
    id_nivel2   INT UNSIGNED NOT NULL,
    nombre      VARCHAR(120) NOT NULL,
    codigo      VARCHAR(20)      NULL,
    tipo        VARCHAR(50)      NULL COMMENT 'Barrio, Vereda, Parroquia, Localidad…',
    codigo_postal VARCHAR(15)    NULL,
    activo      TINYINT(1)   NOT NULL DEFAULT 1,
    creado_en   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_divisiones_nivel3  PRIMARY KEY (id_nivel3),
    CONSTRAINT fk_dnivel3_nivel2
        FOREIGN KEY (id_nivel2) REFERENCES divisiones_nivel2 (id_nivel2)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB COMMENT='Tercera división administrativa (barrio/parroquia/vereda)';

CREATE INDEX idx_dnivel3_nivel2        ON divisiones_nivel3 (id_nivel2);
CREATE INDEX idx_dnivel3_activo        ON divisiones_nivel3 (activo);
CREATE INDEX idx_dnivel3_nombre        ON divisiones_nivel3 (nombre);
CREATE INDEX idx_dnivel3_codigo_postal ON divisiones_nivel3 (codigo_postal);

-- Comunas / barrios de Medellín
INSERT INTO divisiones_nivel3 (id_nivel2, nombre, tipo, codigo_postal)
SELECT dn2.id_nivel2, b.nombre, 'Comuna', b.cp
FROM divisiones_nivel2 dn2
JOIN divisiones_nivel1 dn1 ON dn2.id_nivel1 = dn1.id_nivel1
JOIN paises p ON dn1.id_pais = p.id_pais
CROSS JOIN (
  SELECT 'El Poblado'        AS nombre,'050022' AS cp UNION ALL
  SELECT 'Laureles-Estadio',         '050034' UNION ALL
  SELECT 'Belén',                    '050036' UNION ALL
  SELECT 'Robledo',                  '050011' UNION ALL
  SELECT 'Aranjuez',                 '050013' UNION ALL
  SELECT 'La Candelaria',            '050010' UNION ALL
  SELECT 'Buenos Aires',             '050024' UNION ALL
  SELECT 'La América',               '050035' UNION ALL
  SELECT 'Guayabal',                 '050037' UNION ALL
  SELECT 'Villa Hermosa',            '050023'
) AS b
WHERE p.iso2 = 'CO' AND dn1.nombre = 'Antioquia' AND dn2.nombre = 'Medellín';

-- Barrios de Cali
INSERT INTO divisiones_nivel3 (id_nivel2, nombre, tipo, codigo_postal)
SELECT dn2.id_nivel2, b.nombre, 'Barrio', b.cp
FROM divisiones_nivel2 dn2
JOIN divisiones_nivel1 dn1 ON dn2.id_nivel1 = dn1.id_nivel1
JOIN paises p ON dn1.id_pais = p.id_pais
CROSS JOIN (
  SELECT 'Granada'           AS nombre,'760020' AS cp UNION ALL
  SELECT 'San Antonio',              '760001' UNION ALL
  SELECT 'El Peñón',                 '760003' UNION ALL
  SELECT 'Ciudad Jardín',            '760031' UNION ALL
  SELECT 'Chapinero',                '760004' UNION ALL
  SELECT 'Menga',                    '760040' UNION ALL
  SELECT 'Versalles',                '760025'
) AS b
WHERE p.iso2 = 'CO' AND dn1.nombre = 'Valle del Cauca' AND dn2.nombre = 'Cali';


-- ============================================================
-- SECCIÓN 3: USUARIOS Y AUTENTICACIÓN
-- ============================================================

-- ------------------------------------------------------------
-- 3.1 USUARIOS
-- ------------------------------------------------------------
CREATE TABLE usuarios (
    id_usuario        BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    id_tipo_usuario   TINYINT UNSIGNED NOT NULL,
    id_sexo           TINYINT UNSIGNED     NULL,
    id_profesion      INT UNSIGNED         NULL,
    -- Ubicación de residencia
    id_nivel3         INT UNSIGNED         NULL COMMENT 'Barrio/parroquia',
    id_nivel2         INT UNSIGNED         NULL COMMENT 'Ciudad/municipio',
    id_nivel1         INT UNSIGNED         NULL COMMENT 'Departamento/estado',
    id_pais           SMALLINT UNSIGNED    NULL COMMENT 'País de residencia',
    -- Datos de acceso
    username          VARCHAR(60)      NOT NULL,
    email             VARCHAR(180)     NOT NULL,
    -- Datos personales
    nombres           VARCHAR(100)     NOT NULL,
    apellidos         VARCHAR(100)         NULL,
    fecha_nacimiento  DATE                 NULL,
    foto_perfil_url   VARCHAR(500)         NULL,
    bio               TEXT                 NULL,
    -- Estado
    activo            TINYINT(1)       NOT NULL DEFAULT 1,
    email_verificado  TINYINT(1)       NOT NULL DEFAULT 0,
    bloqueado         TINYINT(1)       NOT NULL DEFAULT 0,
    -- Auditoría
    ultimo_acceso     DATETIME             NULL,
    creado_en         DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en    DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP
                        ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT pk_usuarios                PRIMARY KEY (id_usuario),
    CONSTRAINT uq_usuarios_username       UNIQUE (username),
    CONSTRAINT uq_usuarios_email          UNIQUE (email),
    CONSTRAINT fk_usuarios_tipo
        FOREIGN KEY (id_tipo_usuario) REFERENCES tipos_usuario (id_tipo_usuario)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_usuarios_sexo
        FOREIGN KEY (id_sexo) REFERENCES sexos (id_sexo)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_usuarios_profesion
        FOREIGN KEY (id_profesion) REFERENCES profesiones (id_profesion)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_usuarios_nivel3
        FOREIGN KEY (id_nivel3) REFERENCES divisiones_nivel3 (id_nivel3)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_usuarios_nivel2
        FOREIGN KEY (id_nivel2) REFERENCES divisiones_nivel2 (id_nivel2)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_usuarios_nivel1
        FOREIGN KEY (id_nivel1) REFERENCES divisiones_nivel1 (id_nivel1)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_usuarios_pais
        FOREIGN KEY (id_pais) REFERENCES paises (id_pais)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB COMMENT='Usuarios registrados en el sistema';

CREATE INDEX idx_usuarios_tipo         ON usuarios (id_tipo_usuario);
CREATE INDEX idx_usuarios_sexo         ON usuarios (id_sexo);
CREATE INDEX idx_usuarios_profesion    ON usuarios (id_profesion);
CREATE INDEX idx_usuarios_pais         ON usuarios (id_pais);
CREATE INDEX idx_usuarios_nivel1       ON usuarios (id_nivel1);
CREATE INDEX idx_usuarios_nivel2       ON usuarios (id_nivel2);
CREATE INDEX idx_usuarios_nivel3       ON usuarios (id_nivel3);
CREATE INDEX idx_usuarios_activo       ON usuarios (activo);
CREATE INDEX idx_usuarios_email_ver    ON usuarios (email_verificado);
CREATE INDEX idx_usuarios_nombres      ON usuarios (nombres, apellidos);
CREATE INDEX idx_usuarios_ult_acceso   ON usuarios (ultimo_acceso);


-- ------------------------------------------------------------
-- 3.2 CLAVES / CREDENCIALES
-- (contraseña hasheada + tokens de activación y recuperación)
-- ------------------------------------------------------------
CREATE TABLE credenciales (
    id_credencial          BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    id_usuario             BIGINT UNSIGNED  NOT NULL,
    -- Hash de contraseña (bcrypt / argon2 / etc.)
    password_hash          VARCHAR(255)     NOT NULL,
    algoritmo              VARCHAR(30)      NOT NULL DEFAULT 'bcrypt'
                             COMMENT 'bcrypt, argon2id, sha256…',
    -- Token de activación de cuenta
    token_activacion       VARCHAR(255)         NULL,
    token_activacion_exp   DATETIME             NULL,
    token_activacion_usado TINYINT(1)       NOT NULL DEFAULT 0,
    -- Token de recuperación de contraseña
    token_recuperacion     VARCHAR(255)         NULL,
    token_recuperacion_exp DATETIME             NULL,
    token_recuperacion_uso TINYINT(1)       NOT NULL DEFAULT 0,
    -- Token de sesión / refresh (opcional)
    token_refresh          VARCHAR(512)         NULL,
    token_refresh_exp      DATETIME             NULL,
    -- Estado
    debe_cambiar_pass      TINYINT(1)       NOT NULL DEFAULT 0,
    intentos_fallidos      TINYINT UNSIGNED NOT NULL DEFAULT 0,
    bloqueado_hasta        DATETIME             NULL,
    -- Auditoría
    ultimo_cambio_pass     DATETIME             NULL,
    creado_en              DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en         DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP
                             ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT pk_credenciales            PRIMARY KEY (id_credencial),
    CONSTRAINT uq_credenciales_usuario    UNIQUE (id_usuario),
    CONSTRAINT fk_credenciales_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB COMMENT='Contraseñas hasheadas y tokens de seguridad por usuario';

CREATE INDEX idx_cred_token_act  ON credenciales (token_activacion(50));
CREATE INDEX idx_cred_token_rec  ON credenciales (token_recuperacion(50));
CREATE INDEX idx_cred_bloqueado  ON credenciales (bloqueado_hasta);


-- ------------------------------------------------------------
-- 3.3 HISTORIAL DE CONTRASEÑAS
-- ------------------------------------------------------------
CREATE TABLE historial_passwords (
    id_historial   BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    id_usuario     BIGINT UNSIGNED NOT NULL,
    password_hash  VARCHAR(255)    NOT NULL,
    algoritmo      VARCHAR(30)     NOT NULL DEFAULT 'bcrypt',
    creado_en      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_historial_passwords PRIMARY KEY (id_historial),
    CONSTRAINT fk_histpass_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB COMMENT='Historial de contraseñas para evitar reutilización';

CREATE INDEX idx_histpass_usuario ON historial_passwords (id_usuario);
CREATE INDEX idx_histpass_fecha   ON historial_passwords (creado_en);


-- ------------------------------------------------------------
-- 3.4 SESIONES / LOG DE ACCESO
-- ------------------------------------------------------------
CREATE TABLE sesiones (
    id_sesion      BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    id_usuario     BIGINT UNSIGNED  NOT NULL,
    token_sesion   VARCHAR(512)     NOT NULL,
    ip_origen      VARCHAR(45)          NULL COMMENT 'IPv4 o IPv6',
    user_agent     VARCHAR(500)         NULL,
    dispositivo    VARCHAR(100)         NULL,
    activa         TINYINT(1)       NOT NULL DEFAULT 1,
    expira_en      DATETIME             NULL,
    cerrada_en     DATETIME             NULL,
    creado_en      DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_sesiones PRIMARY KEY (id_sesion),
    CONSTRAINT fk_sesiones_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB COMMENT='Control de sesiones activas por usuario';

CREATE INDEX idx_sesiones_usuario ON sesiones (id_usuario);
CREATE INDEX idx_sesiones_token   ON sesiones (token_sesion(80));
CREATE INDEX idx_sesiones_activa  ON sesiones (activa);
CREATE INDEX idx_sesiones_expira  ON sesiones (expira_en);


-- ============================================================
-- SECCIÓN 4: DIRECTORIO DE CONTACTOS
-- ============================================================

-- ------------------------------------------------------------
-- 4.1 TIPOS DE TELÉFONO
-- ------------------------------------------------------------
CREATE TABLE tipos_telefono (
    id_tipo_telefono  TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    nombre            VARCHAR(50)      NOT NULL,
    descripcion       VARCHAR(200)         NULL,
    CONSTRAINT pk_tipos_telefono        PRIMARY KEY (id_tipo_telefono),
    CONSTRAINT uq_tipos_telefono_nombre UNIQUE (nombre)
) ENGINE=InnoDB COMMENT='Catálogo: Celular, Casa, Trabajo, Fax…';

INSERT INTO tipos_telefono (nombre, descripcion) VALUES
  ('Celular',       'Teléfono móvil personal'),
  ('Casa',          'Teléfono fijo residencial'),
  ('Trabajo',       'Teléfono fijo laboral'),
  ('Fax',           'Número de fax'),
  ('WhatsApp',      'Número con cuenta WhatsApp'),
  ('Telegram',      'Número con cuenta Telegram'),
  ('Emergencias',   'Contacto de emergencia'),
  ('Otro',          'Otro tipo de número');


-- ------------------------------------------------------------
-- 4.2 TIPOS DE DIRECCIÓN
-- ------------------------------------------------------------
CREATE TABLE tipos_direccion (
    id_tipo_direccion TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    nombre            VARCHAR(50)      NOT NULL,
    descripcion       VARCHAR(200)         NULL,
    CONSTRAINT pk_tipos_direccion        PRIMARY KEY (id_tipo_direccion),
    CONSTRAINT uq_tipos_direccion_nombre UNIQUE (nombre)
) ENGINE=InnoDB COMMENT='Catálogo: Residencia, Trabajo, Correspondencia…';

INSERT INTO tipos_direccion (nombre) VALUES
  ('Residencia'),('Trabajo'),('Correspondencia'),('Facturación'),('Otro');


-- ------------------------------------------------------------
-- 4.3 TIPOS DE EMAIL ADICIONAL
-- ------------------------------------------------------------
CREATE TABLE tipos_email (
    id_tipo_email TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    nombre        VARCHAR(50)      NOT NULL,
    CONSTRAINT pk_tipos_email        PRIMARY KEY (id_tipo_email),
    CONSTRAINT uq_tipos_email_nombre UNIQUE (nombre)
) ENGINE=InnoDB;

INSERT INTO tipos_email (nombre) VALUES
  ('Personal'),('Trabajo'),('Académico'),('Alternativo'),('Otro');


-- ------------------------------------------------------------
-- 4.4 CATEGORÍAS DE CONTACTO
-- ------------------------------------------------------------
CREATE TABLE categorias_contacto (
    id_categoria   SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    nombre         VARCHAR(80)       NOT NULL,
    descripcion    VARCHAR(300)          NULL,
    color_hex      CHAR(7)               NULL COMMENT 'Ej: #FF5733',
    icono          VARCHAR(80)           NULL,
    activo         TINYINT(1)        NOT NULL DEFAULT 1,
    CONSTRAINT pk_categorias_contacto        PRIMARY KEY (id_categoria),
    CONSTRAINT uq_categorias_contacto_nombre UNIQUE (nombre)
) ENGINE=InnoDB COMMENT='Clasificación de contactos: Familia, Amigos, Negocios…';

INSERT INTO categorias_contacto (nombre, color_hex) VALUES
  ('Familia',        '#FF6B6B'),
  ('Amigos',         '#4ECDC4'),
  ('Trabajo',        '#45B7D1'),
  ('Negocios',       '#96CEB4'),
  ('Médico',         '#FF9999'),
  ('Educación',      '#FFCC02'),
  ('Emergencias',    '#FF4444'),
  ('Gobierno',       '#6C757D'),
  ('Proveedor',      '#8B4513'),
  ('Cliente',        '#2196F3'),
  ('Otro',           '#9E9E9E');


-- ------------------------------------------------------------
-- 4.5 CONTACTOS  (tabla central del directorio)
-- ------------------------------------------------------------
CREATE TABLE contactos (
    id_contacto      BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    id_usuario       BIGINT UNSIGNED  NOT NULL COMMENT 'Propietario del contacto',
    id_sexo          TINYINT UNSIGNED     NULL,
    id_profesion     INT UNSIGNED         NULL,
    id_categoria     SMALLINT UNSIGNED    NULL,
    -- Geografía del contacto
    id_pais          SMALLINT UNSIGNED    NULL,
    id_nivel1        INT UNSIGNED         NULL,
    id_nivel2        INT UNSIGNED         NULL,
    id_nivel3        INT UNSIGNED         NULL,
    -- Datos personales
    nombres          VARCHAR(100)     NOT NULL,
    apellidos        VARCHAR(100)         NULL,
    empresa          VARCHAR(150)         NULL,
    cargo            VARCHAR(100)         NULL,
    fecha_nacimiento DATE                 NULL,
    sitio_web        VARCHAR(300)         NULL,
    notas            TEXT                 NULL,
    foto_url         VARCHAR(500)         NULL,
    -- Estado
    favorito         TINYINT(1)       NOT NULL DEFAULT 0,
    activo           TINYINT(1)       NOT NULL DEFAULT 1,
    -- Auditoría
    creado_en        DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en   DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP
                       ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT pk_contactos PRIMARY KEY (id_contacto),
    CONSTRAINT fk_contactos_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_contactos_sexo
        FOREIGN KEY (id_sexo) REFERENCES sexos (id_sexo)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_contactos_profesion
        FOREIGN KEY (id_profesion) REFERENCES profesiones (id_profesion)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_contactos_categoria
        FOREIGN KEY (id_categoria) REFERENCES categorias_contacto (id_categoria)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_contactos_pais
        FOREIGN KEY (id_pais) REFERENCES paises (id_pais)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_contactos_nivel1
        FOREIGN KEY (id_nivel1) REFERENCES divisiones_nivel1 (id_nivel1)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_contactos_nivel2
        FOREIGN KEY (id_nivel2) REFERENCES divisiones_nivel2 (id_nivel2)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_contactos_nivel3
        FOREIGN KEY (id_nivel3) REFERENCES divisiones_nivel3 (id_nivel3)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB COMMENT='Contactos del directorio telefónico';

CREATE INDEX idx_contactos_usuario   ON contactos (id_usuario);
CREATE INDEX idx_contactos_nombres   ON contactos (nombres, apellidos);
CREATE INDEX idx_contactos_empresa   ON contactos (empresa);
CREATE INDEX idx_contactos_categoria ON contactos (id_categoria);
CREATE INDEX idx_contactos_pais      ON contactos (id_pais);
CREATE INDEX idx_contactos_nivel2    ON contactos (id_nivel2);
CREATE INDEX idx_contactos_favorito  ON contactos (favorito);
CREATE INDEX idx_contactos_activo    ON contactos (activo);


-- ------------------------------------------------------------
-- 4.6 TELÉFONOS DE CONTACTOS
-- ------------------------------------------------------------
CREATE TABLE telefonos_contacto (
    id_telefono      BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    id_contacto      BIGINT UNSIGNED  NOT NULL,
    id_tipo_telefono TINYINT UNSIGNED NOT NULL,
    id_pais          SMALLINT UNSIGNED    NULL COMMENT 'País del prefijo',
    numero           VARCHAR(30)      NOT NULL,
    extension        VARCHAR(10)          NULL,
    es_principal     TINYINT(1)       NOT NULL DEFAULT 0,
    activo           TINYINT(1)       NOT NULL DEFAULT 1,
    creado_en        DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_telefonos_contacto PRIMARY KEY (id_telefono),
    CONSTRAINT fk_tel_contacto
        FOREIGN KEY (id_contacto) REFERENCES contactos (id_contacto)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_tel_tipo
        FOREIGN KEY (id_tipo_telefono) REFERENCES tipos_telefono (id_tipo_telefono)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_tel_pais
        FOREIGN KEY (id_pais) REFERENCES paises (id_pais)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB COMMENT='Números telefónicos asociados a contactos';

CREATE INDEX idx_tel_contacto ON telefonos_contacto (id_contacto);
CREATE INDEX idx_tel_numero   ON telefonos_contacto (numero);
CREATE INDEX idx_tel_tipo     ON telefonos_contacto (id_tipo_telefono);


-- ------------------------------------------------------------
-- 4.7 EMAILS ADICIONALES DE CONTACTOS
-- ------------------------------------------------------------
CREATE TABLE emails_contacto (
    id_email       BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    id_contacto    BIGINT UNSIGNED  NOT NULL,
    id_tipo_email  TINYINT UNSIGNED NOT NULL,
    email          VARCHAR(180)     NOT NULL,
    es_principal   TINYINT(1)       NOT NULL DEFAULT 0,
    activo         TINYINT(1)       NOT NULL DEFAULT 1,
    creado_en      DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_emails_contacto PRIMARY KEY (id_email),
    CONSTRAINT fk_email_contacto
        FOREIGN KEY (id_contacto) REFERENCES contactos (id_contacto)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_email_tipo
        FOREIGN KEY (id_tipo_email) REFERENCES tipos_email (id_tipo_email)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB COMMENT='Correos electrónicos asociados a contactos';

CREATE INDEX idx_email_contacto ON emails_contacto (id_contacto);
CREATE INDEX idx_email_valor    ON emails_contacto (email);


-- ------------------------------------------------------------
-- 4.8 DIRECCIONES FÍSICAS DE CONTACTOS
-- ------------------------------------------------------------
CREATE TABLE direcciones_contacto (
    id_direccion      BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    id_contacto       BIGINT UNSIGNED  NOT NULL,
    id_tipo_direccion TINYINT UNSIGNED NOT NULL,
    id_pais           SMALLINT UNSIGNED    NULL,
    id_nivel1         INT UNSIGNED         NULL,
    id_nivel2         INT UNSIGNED         NULL,
    id_nivel3         INT UNSIGNED         NULL,
    direccion_linea1  VARCHAR(200)     NOT NULL,
    direccion_linea2  VARCHAR(200)         NULL,
    codigo_postal     VARCHAR(15)          NULL,
    referencia        VARCHAR(300)         NULL,
    es_principal      TINYINT(1)       NOT NULL DEFAULT 0,
    activo            TINYINT(1)       NOT NULL DEFAULT 1,
    creado_en         DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_direcciones_contacto PRIMARY KEY (id_direccion),
    CONSTRAINT fk_dir_contacto
        FOREIGN KEY (id_contacto) REFERENCES contactos (id_contacto)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_dir_tipo
        FOREIGN KEY (id_tipo_direccion) REFERENCES tipos_direccion (id_tipo_direccion)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_dir_pais
        FOREIGN KEY (id_pais) REFERENCES paises (id_pais)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_dir_nivel1
        FOREIGN KEY (id_nivel1) REFERENCES divisiones_nivel1 (id_nivel1)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_dir_nivel2
        FOREIGN KEY (id_nivel2) REFERENCES divisiones_nivel2 (id_nivel2)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_dir_nivel3
        FOREIGN KEY (id_nivel3) REFERENCES divisiones_nivel3 (id_nivel3)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB COMMENT='Direcciones físicas de contactos';

CREATE INDEX idx_dir_contacto ON direcciones_contacto (id_contacto);
CREATE INDEX idx_dir_nivel2   ON direcciones_contacto (id_nivel2);
CREATE INDEX idx_dir_postal   ON direcciones_contacto (codigo_postal);


-- ------------------------------------------------------------
-- 4.9 REDES SOCIALES DE CONTACTOS
-- ------------------------------------------------------------
CREATE TABLE redes_sociales (
    id_red_social  TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    nombre         VARCHAR(50)      NOT NULL,
    url_base       VARCHAR(200)         NULL,
    icono          VARCHAR(80)          NULL,
    CONSTRAINT pk_redes_sociales        PRIMARY KEY (id_red_social),
    CONSTRAINT uq_redes_sociales_nombre UNIQUE (nombre)
) ENGINE=InnoDB;

INSERT INTO redes_sociales (nombre, url_base) VALUES
  ('Facebook',   'https://facebook.com/'),
  ('Instagram',  'https://instagram.com/'),
  ('Twitter/X',  'https://x.com/'),
  ('LinkedIn',   'https://linkedin.com/in/'),
  ('TikTok',     'https://tiktok.com/@'),
  ('YouTube',    'https://youtube.com/@'),
  ('Snapchat',   'https://snapchat.com/add/'),
  ('Pinterest',  'https://pinterest.com/'),
  ('GitHub',     'https://github.com/'),
  ('Telegram',   'https://t.me/'),
  ('Otro',       NULL);

CREATE TABLE redes_contacto (
    id_red_contacto BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    id_contacto     BIGINT UNSIGNED  NOT NULL,
    id_red_social   TINYINT UNSIGNED NOT NULL,
    usuario_red     VARCHAR(150)     NOT NULL COMMENT 'Handle / nick en la red',
    url_perfil      VARCHAR(400)         NULL,
    es_principal    TINYINT(1)       NOT NULL DEFAULT 0,
    activo          TINYINT(1)       NOT NULL DEFAULT 1,
    creado_en       DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_redes_contacto PRIMARY KEY (id_red_contacto),
    CONSTRAINT fk_rc_contacto
        FOREIGN KEY (id_contacto) REFERENCES contactos (id_contacto)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_rc_red
        FOREIGN KEY (id_red_social) REFERENCES redes_sociales (id_red_social)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB COMMENT='Perfiles en redes sociales por contacto';

CREATE INDEX idx_rc_contacto ON redes_contacto (id_contacto);
CREATE INDEX idx_rc_red      ON redes_contacto (id_red_social);


-- ------------------------------------------------------------
-- 4.10 GRUPOS / ETIQUETAS DE CONTACTOS (N:M)
-- ------------------------------------------------------------
CREATE TABLE grupos_contacto (
    id_grupo     BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    id_usuario   BIGINT UNSIGNED NOT NULL COMMENT 'Propietario del grupo',
    nombre       VARCHAR(80)     NOT NULL,
    descripcion  VARCHAR(300)        NULL,
    color_hex    CHAR(7)             NULL,
    activo       TINYINT(1)      NOT NULL DEFAULT 1,
    creado_en    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_grupos_contacto PRIMARY KEY (id_grupo),
    CONSTRAINT fk_grupo_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB COMMENT='Grupos / etiquetas creadas por el usuario';

CREATE INDEX idx_grupo_usuario ON grupos_contacto (id_usuario);

CREATE TABLE contacto_grupo (
    id_contacto  BIGINT UNSIGNED NOT NULL,
    id_grupo     BIGINT UNSIGNED NOT NULL,
    agregado_en  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_contacto_grupo PRIMARY KEY (id_contacto, id_grupo),
    CONSTRAINT fk_cg_contacto
        FOREIGN KEY (id_contacto) REFERENCES contactos (id_contacto)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_cg_grupo
        FOREIGN KEY (id_grupo) REFERENCES grupos_contacto (id_grupo)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB COMMENT='Tabla pivote contacto ↔ grupo (N:M)';

CREATE INDEX idx_cg_grupo    ON contacto_grupo (id_grupo);
CREATE INDEX idx_cg_contacto ON contacto_grupo (id_contacto);


-- ============================================================
-- SECCIÓN 5: AUDITORÍA Y CONFIGURACIÓN
-- ============================================================

-- ------------------------------------------------------------
-- 5.1 LOG DE AUDITORÍA GENERAL
-- ------------------------------------------------------------
CREATE TABLE auditoria_log (
    id_log        BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    id_usuario    BIGINT UNSIGNED     NULL COMMENT 'NULL si acción del sistema',
    tabla_afectada VARCHAR(80)    NOT NULL,
    id_registro   BIGINT UNSIGNED     NULL,
    accion        ENUM('INSERT','UPDATE','DELETE','LOGIN','LOGOUT',
                       'ACTIVATE','PASSWORD_CHANGE','PASSWORD_RESET') NOT NULL,
    datos_previos JSON                NULL,
    datos_nuevos  JSON                NULL,
    ip_origen     VARCHAR(45)         NULL,
    descripcion   VARCHAR(500)        NULL,
    creado_en     DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_auditoria_log PRIMARY KEY (id_log),
    CONSTRAINT fk_audit_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB COMMENT='Registro de auditoría de acciones en el sistema';

CREATE INDEX idx_audit_usuario ON auditoria_log (id_usuario);
CREATE INDEX idx_audit_tabla   ON auditoria_log (tabla_afectada);
CREATE INDEX idx_audit_accion  ON auditoria_log (accion);
CREATE INDEX idx_audit_fecha   ON auditoria_log (creado_en);


-- ------------------------------------------------------------
-- 5.2 CONFIGURACIÓN DE USUARIO
-- ------------------------------------------------------------
CREATE TABLE configuracion_usuario (
    id_config      BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    id_usuario     BIGINT UNSIGNED NOT NULL,
    clave          VARCHAR(80)     NOT NULL,
    valor          VARCHAR(500)        NULL,
    descripcion    VARCHAR(200)        NULL,
    creado_en      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
                     ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT pk_configuracion_usuario         PRIMARY KEY (id_config),
    CONSTRAINT uq_config_usuario_clave          UNIQUE (id_usuario, clave),
    CONSTRAINT fk_config_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB COMMENT='Preferencias y ajustes personales de cada usuario';

CREATE INDEX idx_config_usuario ON configuracion_usuario (id_usuario);


-- ============================================================
-- FIN DE LA BASE DE DATOS
-- ============================================================

-- Vista rápida de tablas creadas
SELECT table_name AS 'Tabla',
       table_rows AS 'Filas aprox.',
       table_comment AS 'Descripción'
FROM information_schema.tables
WHERE table_schema = 'directorio_telefonico'
ORDER BY table_name;
