-- ============================================================
--  BASE DE DATOS: DIRECTORIO TELEFÓNICO COMPLETO
--  Motor  : MySQL 8.0+
--  Charset: utf8mb4 / utf8mb4_unicode_ci
--  Iconos : Heroicons  (https://heroicons.com)
--           Paquete Laravel: composer require blade-ui-kit/blade-heroicons
--           Uso Blade: <x-heroicon-o-{nombre} class="{{ $icono->clase_css }}" />
--           Uso dinámico: <x-dynamic-component :component="$icono->componente" />
--
--  SECCIONES
--    1. Catálogos base         (sexos, tipos_usuario, profesiones)
--    2. Geografía              (paises, divisiones nivel 1-3)
--    3. Usuarios y auth        (usuarios, credenciales, historial, sesiones)
--    4. Directorio de contactos
--    5. Auditoría y configuración
--    6. Navegación y permisos  (iconos Heroicons, menus, submenus,
--                               modulos, permisos, permisos_usuario)
-- ============================================================

DROP DATABASE IF EXISTS directorio_telefonico;
CREATE DATABASE directorio_telefonico
  CHARACTER SET  utf8mb4
  COLLATE        utf8mb4_unicode_ci;

USE directorio_telefonico;


-- ============================================================
-- SECCIÓN 1: CATÁLOGOS BASE
-- ============================================================

-- ------------------------------------------------------------
-- 1.1  SEXOS / GÉNEROS
-- ------------------------------------------------------------
CREATE TABLE sexos (
    id_sexo        TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    nombre         VARCHAR(50)      NOT NULL,
    abreviatura    CHAR(3)          NOT NULL,
    descripcion    VARCHAR(200)         NULL,
    activo         TINYINT(1)       NOT NULL DEFAULT 1,
    creado_en      DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_sexos              PRIMARY KEY (id_sexo),
    CONSTRAINT uq_sexos_nombre       UNIQUE (nombre),
    CONSTRAINT uq_sexos_abreviatura  UNIQUE (abreviatura)
) ENGINE=InnoDB COMMENT='Catálogo de sexos / géneros';

CREATE INDEX idx_sexos_activo ON sexos (activo);

INSERT INTO sexos (nombre, abreviatura, descripcion) VALUES
  ('Masculino',             'M',  'Hombre / varón'),
  ('Femenino',              'F',  'Mujer'),
  ('No binario',            'NB', 'Identidad fuera del binarismo'),
  ('Género fluido',         'GF', 'Género que varía con el tiempo'),
  ('Agénero',               'AG', 'Sin identificación de género'),
  ('Bigénero',              'BG', 'Dos géneros simultáneos'),
  ('Intersexual',           'IS', 'Variación biológica de sexo'),
  ('Prefiero no indicarlo', 'NS', 'El usuario prefiere no especificar');


-- ------------------------------------------------------------
-- 1.2  TIPOS DE USUARIOS
-- ------------------------------------------------------------
CREATE TABLE tipos_usuario (
    id_tipo_usuario  TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    nombre           VARCHAR(60)      NOT NULL,
    descripcion      VARCHAR(300)         NULL,
    nivel_acceso     TINYINT UNSIGNED NOT NULL DEFAULT 1
                       COMMENT '1=básico … 100=superadmin',
    activo           TINYINT(1)       NOT NULL DEFAULT 1,
    creado_en        DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_tipos_usuario        PRIMARY KEY (id_tipo_usuario),
    CONSTRAINT uq_tipos_usuario_nombre UNIQUE (nombre)
) ENGINE=InnoDB COMMENT='Roles / perfiles de acceso al sistema';

CREATE INDEX idx_tipos_usuario_nivel  ON tipos_usuario (nivel_acceso);
CREATE INDEX idx_tipos_usuario_activo ON tipos_usuario (activo);

INSERT INTO tipos_usuario (nombre, descripcion, nivel_acceso) VALUES
  ('Superadministrador', 'Control total del sistema',             100),
  ('Administrador',      'Gestión de usuarios y contenido',        80),
  ('Moderador',          'Revisión y aprobación de contenido',     60),
  ('Usuario Premium',    'Acceso extendido con funciones extra',   40),
  ('Usuario Estándar',   'Acceso básico al directorio',            20),
  ('Invitado',           'Solo lectura, sin autenticación',         5);


-- ------------------------------------------------------------
-- 1.3  PROFESIONES / OCUPACIONES
-- ------------------------------------------------------------
CREATE TABLE profesiones (
    id_profesion  INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    nombre        VARCHAR(120)  NOT NULL,
    descripcion   VARCHAR(400)      NULL,
    categoria     VARCHAR(80)       NULL COMMENT 'Ej: Salud, Tecnología, Educación…',
    activo        TINYINT(1)    NOT NULL DEFAULT 1,
    creado_en     DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_profesiones        PRIMARY KEY (id_profesion),
    CONSTRAINT uq_profesiones_nombre UNIQUE (nombre)
) ENGINE=InnoDB COMMENT='Catálogo de profesiones u ocupaciones';

CREATE INDEX idx_profesiones_categoria ON profesiones (categoria);
CREATE INDEX idx_profesiones_activo    ON profesiones (activo);

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
-- 2.1  PAÍSES
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
    CONSTRAINT pk_paises      PRIMARY KEY (id_pais),
    CONSTRAINT uq_paises_iso2 UNIQUE (iso2),
    CONSTRAINT uq_paises_iso3 UNIQUE (iso3)
) ENGINE=InnoDB COMMENT='Países del mundo (ISO 3166-1)';

CREATE INDEX idx_paises_continente ON paises (continente);
CREATE INDEX idx_paises_activo     ON paises (activo);

INSERT INTO paises (nombre, nombre_oficial, iso2, iso3, codigo_numerico, codigo_telefono, continente, capital, moneda, idioma_oficial) VALUES
  ('Argentina',           'República Argentina',                  'AR','ARG','032','+54',  'América del Sur',  'Buenos Aires',       'ARS','Español'),
  ('Bolivia',             'Estado Plurinacional de Bolivia',      'BO','BOL','068','+591', 'América del Sur',  'Sucre / La Paz',     'BOB','Español'),
  ('Brasil',              'República Federativa del Brasil',      'BR','BRA','076','+55',  'América del Sur',  'Brasilia',           'BRL','Portugués'),
  ('Chile',               'República de Chile',                   'CL','CHL','152','+56',  'América del Sur',  'Santiago',           'CLP','Español'),
  ('Colombia',            'República de Colombia',                'CO','COL','170','+57',  'América del Sur',  'Bogotá',             'COP','Español'),
  ('Costa Rica',          'República de Costa Rica',              'CR','CRI','188','+506', 'América Central',  'San José',           'CRC','Español'),
  ('Cuba',                'República de Cuba',                    'CU','CUB','192','+53',  'América del Norte','La Habana',          'CUP','Español'),
  ('Ecuador',             'República del Ecuador',                'EC','ECU','218','+593', 'América del Sur',  'Quito',              'USD','Español'),
  ('El Salvador',         'República de El Salvador',             'SV','SLV','222','+503', 'América Central',  'San Salvador',       'USD','Español'),
  ('España',              'Reino de España',                      'ES','ESP','724','+34',  'Europa',           'Madrid',             'EUR','Español'),
  ('Estados Unidos',      'United States of America',             'US','USA','840','+1',   'América del Norte','Washington D.C.',    'USD','Inglés'),
  ('Guatemala',           'República de Guatemala',               'GT','GTM','320','+502', 'América Central',  'Ciudad de Guatemala','GTQ','Español'),
  ('Honduras',            'República de Honduras',                'HN','HND','340','+504', 'América Central',  'Tegucigalpa',        'HNL','Español'),
  ('México',              'Estados Unidos Mexicanos',             'MX','MEX','484','+52',  'América del Norte','Ciudad de México',   'MXN','Español'),
  ('Nicaragua',           'República de Nicaragua',               'NI','NIC','558','+505', 'América Central',  'Managua',            'NIO','Español'),
  ('Panamá',              'República de Panamá',                  'PA','PAN','591','+507', 'América Central',  'Ciudad de Panamá',   'PAB','Español'),
  ('Paraguay',            'República del Paraguay',               'PY','PRY','600','+595', 'América del Sur',  'Asunción',           'PYG','Español'),
  ('Perú',                'República del Perú',                   'PE','PER','604','+51',  'América del Sur',  'Lima',               'PEN','Español'),
  ('Puerto Rico',         'Estado Libre Asociado de Puerto Rico', 'PR','PRI','630','+1787','América del Norte','San Juan',           'USD','Español'),
  ('República Dominicana','República Dominicana',                 'DO','DOM','214','+1809','América del Norte','Santo Domingo',      'DOP','Español'),
  ('Uruguay',             'República Oriental del Uruguay',       'UY','URY','858','+598', 'América del Sur',  'Montevideo',         'UYU','Español'),
  ('Venezuela',           'República Bolivariana de Venezuela',   'VE','VEN','862','+58',  'América del Sur',  'Caracas',            'VES','Español'),
  ('Alemania',            'Bundesrepublik Deutschland',           'DE','DEU','276','+49',  'Europa',           'Berlín',             'EUR','Alemán'),
  ('Francia',             'République française',                 'FR','FRA','250','+33',  'Europa',           'París',              'EUR','Francés'),
  ('Italia',              'Repubblica Italiana',                  'IT','ITA','380','+39',  'Europa',           'Roma',               'EUR','Italiano'),
  ('Canadá',              'Canada',                               'CA','CAN','124','+1',   'América del Norte','Ottawa',             'CAD','Inglés/Francés'),
  ('China',               'República Popular China',              'CN','CHN','156','+86',  'Asia',             'Pekín',              'CNY','Chino mandarín'),
  ('Japón',               'Nihon-koku',                           'JP','JPN','392','+81',  'Asia',             'Tokio',              'JPY','Japonés'),
  ('Reino Unido',         'United Kingdom of Great Britain',      'GB','GBR','826','+44',  'Europa',           'Londres',            'GBP','Inglés'),
  ('Portugal',            'República Portuguesa',                 'PT','PRT','620','+351', 'Europa',           'Lisboa',             'EUR','Portugués');


-- ------------------------------------------------------------
-- 2.2  PRIMERA DIVISIÓN ADMINISTRATIVA  (Departamento / Estado / Provincia)
-- ------------------------------------------------------------
CREATE TABLE divisiones_nivel1 (
    id_nivel1   INT UNSIGNED      NOT NULL AUTO_INCREMENT,
    id_pais     SMALLINT UNSIGNED NOT NULL,
    nombre      VARCHAR(120)      NOT NULL,
    codigo      VARCHAR(10)           NULL COMMENT 'Código ISO o interno',
    tipo        VARCHAR(50)           NULL COMMENT 'Estado, Departamento, Provincia…',
    capital     VARCHAR(100)          NULL,
    activo      TINYINT(1)        NOT NULL DEFAULT 1,
    creado_en   DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_divisiones_nivel1 PRIMARY KEY (id_nivel1),
    CONSTRAINT fk_dnivel1_pais
        FOREIGN KEY (id_pais) REFERENCES paises (id_pais)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB COMMENT='Primera división administrativa (dpto/estado/provincia)';

CREATE INDEX idx_dnivel1_pais   ON divisiones_nivel1 (id_pais);
CREATE INDEX idx_dnivel1_activo ON divisiones_nivel1 (activo);
CREATE INDEX idx_dnivel1_nombre ON divisiones_nivel1 (nombre);

-- Colombia – Departamentos
INSERT INTO divisiones_nivel1 (id_pais, nombre, codigo, tipo, capital)
SELECT p.id_pais, col.nombre, col.codigo, 'Departamento', col.capital
FROM paises p
CROSS JOIN (
  SELECT 'Amazonas'           AS nombre,'CO-AMA' AS codigo,'Leticia'               AS capital UNION ALL
  SELECT 'Antioquia',                   'CO-ANT','Medellín'                                   UNION ALL
  SELECT 'Arauca',                      'CO-ARA','Arauca'                                     UNION ALL
  SELECT 'Atlántico',                   'CO-ATL','Barranquilla'                               UNION ALL
  SELECT 'Bolívar',                     'CO-BOL','Cartagena de Indias'                        UNION ALL
  SELECT 'Boyacá',                      'CO-BOY','Tunja'                                      UNION ALL
  SELECT 'Caldas',                      'CO-CAL','Manizales'                                  UNION ALL
  SELECT 'Caquetá',                     'CO-CAQ','Florencia'                                  UNION ALL
  SELECT 'Casanare',                    'CO-CAS','Yopal'                                      UNION ALL
  SELECT 'Cauca',                       'CO-CAU','Popayán'                                    UNION ALL
  SELECT 'Cesar',                       'CO-CES','Valledupar'                                 UNION ALL
  SELECT 'Chocó',                       'CO-CHO','Quibdó'                                     UNION ALL
  SELECT 'Córdoba',                     'CO-COR','Montería'                                   UNION ALL
  SELECT 'Cundinamarca',                'CO-CUN','Bogotá D.C.'                                UNION ALL
  SELECT 'Bogotá D.C.',                 'CO-DC', 'Bogotá D.C.'                               UNION ALL
  SELECT 'Guainía',                     'CO-GUA','Inírida'                                    UNION ALL
  SELECT 'Guaviare',                    'CO-GUV','San José del Guaviare'                      UNION ALL
  SELECT 'Huila',                       'CO-HUI','Neiva'                                      UNION ALL
  SELECT 'La Guajira',                  'CO-LAG','Riohacha'                                   UNION ALL
  SELECT 'Magdalena',                   'CO-MAG','Santa Marta'                                UNION ALL
  SELECT 'Meta',                        'CO-MET','Villavicencio'                              UNION ALL
  SELECT 'Nariño',                      'CO-NAR','Pasto'                                      UNION ALL
  SELECT 'Norte de Santander',          'CO-NSA','Cúcuta'                                     UNION ALL
  SELECT 'Putumayo',                    'CO-PUT','Mocoa'                                      UNION ALL
  SELECT 'Quindío',                     'CO-QUI','Armenia'                                    UNION ALL
  SELECT 'Risaralda',                   'CO-RIS','Pereira'                                    UNION ALL
  SELECT 'San Andrés y Providencia',    'CO-SAP','San Andrés'                                 UNION ALL
  SELECT 'Santander',                   'CO-SAN','Bucaramanga'                                UNION ALL
  SELECT 'Sucre',                       'CO-SUC','Sincelejo'                                  UNION ALL
  SELECT 'Tolima',                      'CO-TOL','Ibagué'                                     UNION ALL
  SELECT 'Valle del Cauca',             'CO-VAC','Cali'                                       UNION ALL
  SELECT 'Vaupés',                      'CO-VAU','Mitú'                                       UNION ALL
  SELECT 'Vichada',                     'CO-VID','Puerto Carreño'
) AS col WHERE p.iso2 = 'CO';

-- México – Estados
INSERT INTO divisiones_nivel1 (id_pais, nombre, codigo, tipo, capital)
SELECT p.id_pais, mx.nombre, mx.codigo, 'Estado', mx.capital
FROM paises p
CROSS JOIN (
  SELECT 'Aguascalientes'      AS nombre,'MX-AGU' AS codigo,'Aguascalientes'    AS capital UNION ALL
  SELECT 'Baja California',             'MX-BCN','Mexicali'                               UNION ALL
  SELECT 'Baja California Sur',         'MX-BCS','La Paz'                                 UNION ALL
  SELECT 'Campeche',                    'MX-CAM','Campeche'                               UNION ALL
  SELECT 'Chiapas',                     'MX-CHP','Tuxtla Gutiérrez'                       UNION ALL
  SELECT 'Chihuahua',                   'MX-CHH','Chihuahua'                              UNION ALL
  SELECT 'Ciudad de México',            'MX-CMX','Ciudad de México'                       UNION ALL
  SELECT 'Coahuila',                    'MX-COA','Saltillo'                               UNION ALL
  SELECT 'Colima',                      'MX-COL','Colima'                                 UNION ALL
  SELECT 'Durango',                     'MX-DUR','Durango'                                UNION ALL
  SELECT 'Guanajuato',                  'MX-GUA','Guanajuato'                             UNION ALL
  SELECT 'Guerrero',                    'MX-GRO','Chilpancingo'                           UNION ALL
  SELECT 'Hidalgo',                     'MX-HID','Pachuca'                                UNION ALL
  SELECT 'Jalisco',                     'MX-JAL','Guadalajara'                            UNION ALL
  SELECT 'México',                      'MX-MEX','Toluca'                                 UNION ALL
  SELECT 'Michoacán',                   'MX-MIC','Morelia'                                UNION ALL
  SELECT 'Morelos',                     'MX-MOR','Cuernavaca'                             UNION ALL
  SELECT 'Nayarit',                     'MX-NAY','Tepic'                                  UNION ALL
  SELECT 'Nuevo León',                  'MX-NLE','Monterrey'                              UNION ALL
  SELECT 'Oaxaca',                      'MX-OAX','Oaxaca de Juárez'                       UNION ALL
  SELECT 'Puebla',                      'MX-PUE','Puebla de Zaragoza'                     UNION ALL
  SELECT 'Querétaro',                   'MX-QUE','Querétaro'                              UNION ALL
  SELECT 'Quintana Roo',                'MX-ROO','Chetumal'                               UNION ALL
  SELECT 'San Luis Potosí',             'MX-SLP','San Luis Potosí'                        UNION ALL
  SELECT 'Sinaloa',                     'MX-SIN','Culiacán'                               UNION ALL
  SELECT 'Sonora',                      'MX-SON','Hermosillo'                             UNION ALL
  SELECT 'Tabasco',                     'MX-TAB','Villahermosa'                           UNION ALL
  SELECT 'Tamaulipas',                  'MX-TAM','Ciudad Victoria'                        UNION ALL
  SELECT 'Tlaxcala',                    'MX-TLA','Tlaxcala'                               UNION ALL
  SELECT 'Veracruz',                    'MX-VER','Xalapa'                                 UNION ALL
  SELECT 'Yucatán',                     'MX-YUC','Mérida'                                 UNION ALL
  SELECT 'Zacatecas',                   'MX-ZAC','Zacatecas'
) AS mx WHERE p.iso2 = 'MX';


-- ------------------------------------------------------------
-- 2.3  SEGUNDA DIVISIÓN ADMINISTRATIVA  (Municipio / Ciudad / Cantón)
-- ------------------------------------------------------------
CREATE TABLE divisiones_nivel2 (
    id_nivel2  INT UNSIGNED NOT NULL AUTO_INCREMENT,
    id_nivel1  INT UNSIGNED NOT NULL,
    nombre     VARCHAR(120) NOT NULL,
    codigo     VARCHAR(15)      NULL,
    tipo       VARCHAR(50)      NULL COMMENT 'Municipio, Ciudad, Cantón…',
    capital    VARCHAR(100)     NULL,
    poblacion  INT UNSIGNED     NULL,
    activo     TINYINT(1)   NOT NULL DEFAULT 1,
    creado_en  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_divisiones_nivel2 PRIMARY KEY (id_nivel2),
    CONSTRAINT fk_dnivel2_nivel1
        FOREIGN KEY (id_nivel1) REFERENCES divisiones_nivel1 (id_nivel1)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB COMMENT='Segunda división administrativa (municipio/ciudad/cantón)';

CREATE INDEX idx_dnivel2_nivel1 ON divisiones_nivel2 (id_nivel1);
CREATE INDEX idx_dnivel2_activo ON divisiones_nivel2 (activo);
CREATE INDEX idx_dnivel2_nombre ON divisiones_nivel2 (nombre);

-- Antioquia – Municipios
INSERT INTO divisiones_nivel2 (id_nivel1, nombre, codigo, tipo)
SELECT dn1.id_nivel1, m.nombre, m.codigo, 'Municipio'
FROM divisiones_nivel1 dn1
JOIN paises p ON dn1.id_pais = p.id_pais
CROSS JOIN (
  SELECT 'Medellín'  AS nombre,'05001' AS codigo UNION ALL
  SELECT 'Bello',             '05088'            UNION ALL
  SELECT 'Itagüí',            '05360'            UNION ALL
  SELECT 'Envigado',          '05266'            UNION ALL
  SELECT 'Apartadó',          '05045'            UNION ALL
  SELECT 'Turbo',             '05837'            UNION ALL
  SELECT 'Rionegro',          '05615'            UNION ALL
  SELECT 'Caucasia',          '05154'            UNION ALL
  SELECT 'Sabaneta',          '05631'            UNION ALL
  SELECT 'Copacabana',        '05212'
) AS m
WHERE p.iso2 = 'CO' AND dn1.nombre = 'Antioquia';

-- Valle del Cauca – Municipios
INSERT INTO divisiones_nivel2 (id_nivel1, nombre, codigo, tipo)
SELECT dn1.id_nivel1, m.nombre, m.codigo, 'Municipio'
FROM divisiones_nivel1 dn1
JOIN paises p ON dn1.id_pais = p.id_pais
CROSS JOIN (
  SELECT 'Cali'          AS nombre,'76001' AS codigo UNION ALL
  SELECT 'Buenaventura',          '76109'            UNION ALL
  SELECT 'Palmira',               '76520'            UNION ALL
  SELECT 'Tuluá',                 '76834'            UNION ALL
  SELECT 'Cartago',               '76147'            UNION ALL
  SELECT 'Buga',                  '76111'            UNION ALL
  SELECT 'Jamundí',               '76364'            UNION ALL
  SELECT 'Yumbo',                 '76892'
) AS m
WHERE p.iso2 = 'CO' AND dn1.nombre = 'Valle del Cauca';

-- Jalisco – Municipios
INSERT INTO divisiones_nivel2 (id_nivel1, nombre, codigo, tipo)
SELECT dn1.id_nivel1, m.nombre, m.codigo, 'Municipio'
FROM divisiones_nivel1 dn1
JOIN paises p ON dn1.id_pais = p.id_pais
CROSS JOIN (
  SELECT 'Guadalajara'     AS nombre,'14039' AS codigo UNION ALL
  SELECT 'Zapopan',                 '14120'            UNION ALL
  SELECT 'Tlaquepaque',             '14097'            UNION ALL
  SELECT 'Tonalá',                  '14101'            UNION ALL
  SELECT 'Puerto Vallarta',         '14067'            UNION ALL
  SELECT 'Lagos de Moreno',         '14051'            UNION ALL
  SELECT 'Tepatitlán',              '14089'
) AS m
WHERE p.iso2 = 'MX' AND dn1.nombre = 'Jalisco';


-- ------------------------------------------------------------
-- 2.4  TERCERA DIVISIÓN ADMINISTRATIVA  (Barrio / Parroquia / Localidad)
-- ------------------------------------------------------------
CREATE TABLE divisiones_nivel3 (
    id_nivel3     INT UNSIGNED NOT NULL AUTO_INCREMENT,
    id_nivel2     INT UNSIGNED NOT NULL,
    nombre        VARCHAR(120) NOT NULL,
    codigo        VARCHAR(20)      NULL,
    tipo          VARCHAR(50)      NULL COMMENT 'Barrio, Vereda, Parroquia, Localidad…',
    codigo_postal VARCHAR(15)      NULL,
    activo        TINYINT(1)   NOT NULL DEFAULT 1,
    creado_en     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_divisiones_nivel3 PRIMARY KEY (id_nivel3),
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
  SELECT 'El Poblado'       AS nombre,'050022' AS cp UNION ALL
  SELECT 'Laureles-Estadio',          '050034'       UNION ALL
  SELECT 'Belén',                     '050036'       UNION ALL
  SELECT 'Robledo',                   '050011'       UNION ALL
  SELECT 'Aranjuez',                  '050013'       UNION ALL
  SELECT 'La Candelaria',             '050010'       UNION ALL
  SELECT 'Buenos Aires',              '050024'       UNION ALL
  SELECT 'La América',                '050035'       UNION ALL
  SELECT 'Guayabal',                  '050037'       UNION ALL
  SELECT 'Villa Hermosa',             '050023'
) AS b
WHERE p.iso2 = 'CO' AND dn1.nombre = 'Antioquia' AND dn2.nombre = 'Medellín';

-- Barrios de Cali
INSERT INTO divisiones_nivel3 (id_nivel2, nombre, tipo, codigo_postal)
SELECT dn2.id_nivel2, b.nombre, 'Barrio', b.cp
FROM divisiones_nivel2 dn2
JOIN divisiones_nivel1 dn1 ON dn2.id_nivel1 = dn1.id_nivel1
JOIN paises p ON dn1.id_pais = p.id_pais
CROSS JOIN (
  SELECT 'Granada'       AS nombre,'760020' AS cp UNION ALL
  SELECT 'San Antonio',             '760001'       UNION ALL
  SELECT 'El Peñón',                '760003'       UNION ALL
  SELECT 'Ciudad Jardín',           '760031'       UNION ALL
  SELECT 'Chapinero',               '760004'       UNION ALL
  SELECT 'Menga',                   '760040'       UNION ALL
  SELECT 'Versalles',               '760025'
) AS b
WHERE p.iso2 = 'CO' AND dn1.nombre = 'Valle del Cauca' AND dn2.nombre = 'Cali';


-- ============================================================
-- SECCIÓN 3: USUARIOS Y AUTENTICACIÓN
-- ============================================================

-- ------------------------------------------------------------
-- 3.1  USUARIOS
-- ------------------------------------------------------------
CREATE TABLE usuarios (
    id_usuario        BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    id_tipo_usuario   TINYINT UNSIGNED NOT NULL,
    id_sexo           TINYINT UNSIGNED     NULL,
    id_profesion      INT UNSIGNED         NULL,
    id_nivel3         INT UNSIGNED         NULL COMMENT 'Barrio/parroquia',
    id_nivel2         INT UNSIGNED         NULL COMMENT 'Ciudad/municipio',
    id_nivel1         INT UNSIGNED         NULL COMMENT 'Departamento/estado',
    id_pais           SMALLINT UNSIGNED    NULL COMMENT 'País de residencia',
    username          VARCHAR(60)      NOT NULL,
    email             VARCHAR(180)     NOT NULL,
    nombres           VARCHAR(100)     NOT NULL,
    apellidos         VARCHAR(100)         NULL,
    fecha_nacimiento  DATE                 NULL,
    foto_perfil_url   VARCHAR(500)         NULL,
    bio               TEXT                 NULL,
    activo            TINYINT(1)       NOT NULL DEFAULT 1,
    email_verificado  TINYINT(1)       NOT NULL DEFAULT 0,
    bloqueado         TINYINT(1)       NOT NULL DEFAULT 0,
    ultimo_acceso     DATETIME             NULL,
    creado_en         DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en    DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP
                        ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT pk_usuarios           PRIMARY KEY (id_usuario),
    CONSTRAINT uq_usuarios_username  UNIQUE (username),
    CONSTRAINT uq_usuarios_email     UNIQUE (email),
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

CREATE INDEX idx_usuarios_tipo       ON usuarios (id_tipo_usuario);
CREATE INDEX idx_usuarios_sexo       ON usuarios (id_sexo);
CREATE INDEX idx_usuarios_profesion  ON usuarios (id_profesion);
CREATE INDEX idx_usuarios_pais       ON usuarios (id_pais);
CREATE INDEX idx_usuarios_nivel1     ON usuarios (id_nivel1);
CREATE INDEX idx_usuarios_nivel2     ON usuarios (id_nivel2);
CREATE INDEX idx_usuarios_nivel3     ON usuarios (id_nivel3);
CREATE INDEX idx_usuarios_activo     ON usuarios (activo);
CREATE INDEX idx_usuarios_email_ver  ON usuarios (email_verificado);
CREATE INDEX idx_usuarios_nombres    ON usuarios (nombres, apellidos);
CREATE INDEX idx_usuarios_ult_acceso ON usuarios (ultimo_acceso);


-- ------------------------------------------------------------
-- 3.2  CREDENCIALES
-- ------------------------------------------------------------
CREATE TABLE credenciales (
    id_credencial          BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    id_usuario             BIGINT UNSIGNED  NOT NULL,
    password_hash          VARCHAR(255)     NOT NULL,
    algoritmo              VARCHAR(30)      NOT NULL DEFAULT 'bcrypt'
                             COMMENT 'bcrypt, argon2id, sha256…',
    token_activacion       VARCHAR(255)         NULL,
    token_activacion_exp   DATETIME             NULL,
    token_activacion_usado TINYINT(1)       NOT NULL DEFAULT 0,
    token_recuperacion     VARCHAR(255)         NULL,
    token_recuperacion_exp DATETIME             NULL,
    token_recuperacion_uso TINYINT(1)       NOT NULL DEFAULT 0,
    token_refresh          VARCHAR(512)         NULL,
    token_refresh_exp      DATETIME             NULL,
    debe_cambiar_pass      TINYINT(1)       NOT NULL DEFAULT 0,
    intentos_fallidos      TINYINT UNSIGNED NOT NULL DEFAULT 0,
    bloqueado_hasta        DATETIME             NULL,
    ultimo_cambio_pass     DATETIME             NULL,
    creado_en              DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en         DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP
                             ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT pk_credenciales           PRIMARY KEY (id_credencial),
    CONSTRAINT uq_credenciales_usuario   UNIQUE (id_usuario),
    CONSTRAINT fk_credenciales_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB COMMENT='Contraseñas hasheadas y tokens de seguridad por usuario';

CREATE INDEX idx_cred_token_act ON credenciales (token_activacion(50));
CREATE INDEX idx_cred_token_rec ON credenciales (token_recuperacion(50));
CREATE INDEX idx_cred_bloqueado ON credenciales (bloqueado_hasta);


-- ------------------------------------------------------------
-- 3.3  HISTORIAL DE CONTRASEÑAS
-- ------------------------------------------------------------
CREATE TABLE historial_passwords (
    id_historial  BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    id_usuario    BIGINT UNSIGNED NOT NULL,
    password_hash VARCHAR(255)    NOT NULL,
    algoritmo     VARCHAR(30)     NOT NULL DEFAULT 'bcrypt',
    creado_en     DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_historial_passwords PRIMARY KEY (id_historial),
    CONSTRAINT fk_histpass_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB COMMENT='Historial de contraseñas para evitar reutilización';

CREATE INDEX idx_histpass_usuario ON historial_passwords (id_usuario);
CREATE INDEX idx_histpass_fecha   ON historial_passwords (creado_en);


-- ------------------------------------------------------------
-- 3.4  SESIONES
-- ------------------------------------------------------------
CREATE TABLE sesiones (
    id_sesion    BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    id_usuario   BIGINT UNSIGNED  NOT NULL,
    token_sesion VARCHAR(512)     NOT NULL,
    ip_origen    VARCHAR(45)          NULL COMMENT 'IPv4 o IPv6',
    user_agent   VARCHAR(500)         NULL,
    dispositivo  VARCHAR(100)         NULL,
    activa       TINYINT(1)       NOT NULL DEFAULT 1,
    expira_en    DATETIME             NULL,
    cerrada_en   DATETIME             NULL,
    creado_en    DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
-- 4.1  TIPOS DE TELÉFONO
-- ------------------------------------------------------------
CREATE TABLE tipos_telefono (
    id_tipo_telefono TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    nombre           VARCHAR(50)      NOT NULL,
    descripcion      VARCHAR(200)         NULL,
    CONSTRAINT pk_tipos_telefono        PRIMARY KEY (id_tipo_telefono),
    CONSTRAINT uq_tipos_telefono_nombre UNIQUE (nombre)
) ENGINE=InnoDB COMMENT='Catálogo: Celular, Casa, Trabajo, Fax…';

INSERT INTO tipos_telefono (nombre, descripcion) VALUES
  ('Celular',     'Teléfono móvil personal'),
  ('Casa',        'Teléfono fijo residencial'),
  ('Trabajo',     'Teléfono fijo laboral'),
  ('Fax',         'Número de fax'),
  ('WhatsApp',    'Número con cuenta WhatsApp'),
  ('Telegram',    'Número con cuenta Telegram'),
  ('Emergencias', 'Contacto de emergencia'),
  ('Otro',        'Otro tipo de número');


-- ------------------------------------------------------------
-- 4.2  TIPOS DE DIRECCIÓN
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
-- 4.3  TIPOS DE EMAIL
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
-- 4.4  CATEGORÍAS DE CONTACTO
-- ------------------------------------------------------------
CREATE TABLE categorias_contacto (
    id_categoria SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    nombre       VARCHAR(80)       NOT NULL,
    descripcion  VARCHAR(300)          NULL,
    color_hex    CHAR(7)               NULL COMMENT 'Ej: #FF5733',
    icono        VARCHAR(80)           NULL,
    activo       TINYINT(1)        NOT NULL DEFAULT 1,
    CONSTRAINT pk_categorias_contacto        PRIMARY KEY (id_categoria),
    CONSTRAINT uq_categorias_contacto_nombre UNIQUE (nombre)
) ENGINE=InnoDB COMMENT='Clasificación de contactos: Familia, Amigos, Negocios…';

INSERT INTO categorias_contacto (nombre, color_hex) VALUES
  ('Familia',     '#FF6B6B'),
  ('Amigos',      '#4ECDC4'),
  ('Trabajo',     '#45B7D1'),
  ('Negocios',    '#96CEB4'),
  ('Médico',      '#FF9999'),
  ('Educación',   '#FFCC02'),
  ('Emergencias', '#FF4444'),
  ('Gobierno',    '#6C757D'),
  ('Proveedor',   '#8B4513'),
  ('Cliente',     '#2196F3'),
  ('Otro',        '#9E9E9E');


-- ------------------------------------------------------------
-- 4.5  CONTACTOS  (tabla central del directorio)
-- ------------------------------------------------------------
CREATE TABLE contactos (
    id_contacto      BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
    id_usuario       BIGINT UNSIGNED   NOT NULL COMMENT 'Propietario del contacto',
    id_sexo          TINYINT UNSIGNED      NULL,
    id_profesion     INT UNSIGNED          NULL,
    id_categoria     SMALLINT UNSIGNED     NULL,
    id_pais          SMALLINT UNSIGNED     NULL,
    id_nivel1        INT UNSIGNED          NULL,
    id_nivel2        INT UNSIGNED          NULL,
    id_nivel3        INT UNSIGNED          NULL,
    nombres          VARCHAR(100)      NOT NULL,
    apellidos        VARCHAR(100)          NULL,
    empresa          VARCHAR(150)          NULL,
    cargo            VARCHAR(100)          NULL,
    fecha_nacimiento DATE                  NULL,
    sitio_web        VARCHAR(300)          NULL,
    notas            TEXT                  NULL,
    foto_url         VARCHAR(500)          NULL,
    favorito         TINYINT(1)        NOT NULL DEFAULT 0,
    activo           TINYINT(1)        NOT NULL DEFAULT 1,
    creado_en        DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en   DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP
                       ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT pk_contactos PRIMARY KEY (id_contacto),
    CONSTRAINT fk_contactos_usuario
        FOREIGN KEY (id_usuario)   REFERENCES usuarios           (id_usuario)   ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_contactos_sexo
        FOREIGN KEY (id_sexo)      REFERENCES sexos               (id_sexo)      ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_contactos_profesion
        FOREIGN KEY (id_profesion) REFERENCES profesiones         (id_profesion) ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_contactos_categoria
        FOREIGN KEY (id_categoria) REFERENCES categorias_contacto (id_categoria) ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_contactos_pais
        FOREIGN KEY (id_pais)      REFERENCES paises              (id_pais)      ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_contactos_nivel1
        FOREIGN KEY (id_nivel1)    REFERENCES divisiones_nivel1   (id_nivel1)    ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_contactos_nivel2
        FOREIGN KEY (id_nivel2)    REFERENCES divisiones_nivel2   (id_nivel2)    ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_contactos_nivel3
        FOREIGN KEY (id_nivel3)    REFERENCES divisiones_nivel3   (id_nivel3)    ON UPDATE CASCADE ON DELETE SET NULL
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
-- 4.6  TELÉFONOS DE CONTACTOS
-- ------------------------------------------------------------
CREATE TABLE telefonos_contacto (
    id_telefono      BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
    id_contacto      BIGINT UNSIGNED   NOT NULL,
    id_tipo_telefono TINYINT UNSIGNED  NOT NULL,
    id_pais          SMALLINT UNSIGNED     NULL COMMENT 'País del prefijo',
    numero           VARCHAR(30)       NOT NULL,
    extension        VARCHAR(10)           NULL,
    es_principal     TINYINT(1)        NOT NULL DEFAULT 0,
    activo           TINYINT(1)        NOT NULL DEFAULT 1,
    creado_en        DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_telefonos_contacto PRIMARY KEY (id_telefono),
    CONSTRAINT fk_tel_contacto
        FOREIGN KEY (id_contacto)      REFERENCES contactos     (id_contacto)      ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_tel_tipo
        FOREIGN KEY (id_tipo_telefono) REFERENCES tipos_telefono (id_tipo_telefono) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_tel_pais
        FOREIGN KEY (id_pais)          REFERENCES paises         (id_pais)          ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB COMMENT='Números telefónicos asociados a contactos';

CREATE INDEX idx_tel_contacto ON telefonos_contacto (id_contacto);
CREATE INDEX idx_tel_numero   ON telefonos_contacto (numero);
CREATE INDEX idx_tel_tipo     ON telefonos_contacto (id_tipo_telefono);


-- ------------------------------------------------------------
-- 4.7  EMAILS DE CONTACTOS
-- ------------------------------------------------------------
CREATE TABLE emails_contacto (
    id_email      BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    id_contacto   BIGINT UNSIGNED  NOT NULL,
    id_tipo_email TINYINT UNSIGNED NOT NULL,
    email         VARCHAR(180)     NOT NULL,
    es_principal  TINYINT(1)       NOT NULL DEFAULT 0,
    activo        TINYINT(1)       NOT NULL DEFAULT 1,
    creado_en     DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_emails_contacto PRIMARY KEY (id_email),
    CONSTRAINT fk_email_contacto
        FOREIGN KEY (id_contacto)  REFERENCES contactos   (id_contacto)  ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_email_tipo
        FOREIGN KEY (id_tipo_email) REFERENCES tipos_email (id_tipo_email) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB COMMENT='Correos electrónicos asociados a contactos';

CREATE INDEX idx_email_contacto ON emails_contacto (id_contacto);
CREATE INDEX idx_email_valor    ON emails_contacto (email);


-- ------------------------------------------------------------
-- 4.8  DIRECCIONES FÍSICAS DE CONTACTOS
-- ------------------------------------------------------------
CREATE TABLE direcciones_contacto (
    id_direccion      BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
    id_contacto       BIGINT UNSIGNED   NOT NULL,
    id_tipo_direccion TINYINT UNSIGNED  NOT NULL,
    id_pais           SMALLINT UNSIGNED     NULL,
    id_nivel1         INT UNSIGNED          NULL,
    id_nivel2         INT UNSIGNED          NULL,
    id_nivel3         INT UNSIGNED          NULL,
    direccion_linea1  VARCHAR(200)      NOT NULL,
    direccion_linea2  VARCHAR(200)          NULL,
    codigo_postal     VARCHAR(15)           NULL,
    referencia        VARCHAR(300)          NULL,
    es_principal      TINYINT(1)        NOT NULL DEFAULT 0,
    activo            TINYINT(1)        NOT NULL DEFAULT 1,
    creado_en         DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_direcciones_contacto PRIMARY KEY (id_direccion),
    CONSTRAINT fk_dir_contacto
        FOREIGN KEY (id_contacto)       REFERENCES contactos          (id_contacto)       ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_dir_tipo
        FOREIGN KEY (id_tipo_direccion) REFERENCES tipos_direccion     (id_tipo_direccion) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_dir_pais
        FOREIGN KEY (id_pais)           REFERENCES paises              (id_pais)           ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_dir_nivel1
        FOREIGN KEY (id_nivel1)         REFERENCES divisiones_nivel1   (id_nivel1)         ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_dir_nivel2
        FOREIGN KEY (id_nivel2)         REFERENCES divisiones_nivel2   (id_nivel2)         ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_dir_nivel3
        FOREIGN KEY (id_nivel3)         REFERENCES divisiones_nivel3   (id_nivel3)         ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB COMMENT='Direcciones físicas de contactos';

CREATE INDEX idx_dir_contacto ON direcciones_contacto (id_contacto);
CREATE INDEX idx_dir_nivel2   ON direcciones_contacto (id_nivel2);
CREATE INDEX idx_dir_postal   ON direcciones_contacto (codigo_postal);


-- ------------------------------------------------------------
-- 4.9  REDES SOCIALES DE CONTACTOS
-- ------------------------------------------------------------
CREATE TABLE redes_sociales (
    id_red_social TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    nombre        VARCHAR(50)      NOT NULL,
    url_base      VARCHAR(200)         NULL,
    icono         VARCHAR(80)          NULL,
    CONSTRAINT pk_redes_sociales        PRIMARY KEY (id_red_social),
    CONSTRAINT uq_redes_sociales_nombre UNIQUE (nombre)
) ENGINE=InnoDB;

INSERT INTO redes_sociales (nombre, url_base) VALUES
  ('Facebook',  'https://facebook.com/'),
  ('Instagram', 'https://instagram.com/'),
  ('Twitter/X', 'https://x.com/'),
  ('LinkedIn',  'https://linkedin.com/in/'),
  ('TikTok',    'https://tiktok.com/@'),
  ('YouTube',   'https://youtube.com/@'),
  ('Snapchat',  'https://snapchat.com/add/'),
  ('Pinterest', 'https://pinterest.com/'),
  ('GitHub',    'https://github.com/'),
  ('Telegram',  'https://t.me/'),
  ('Otro',      NULL);

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
        FOREIGN KEY (id_contacto)   REFERENCES contactos     (id_contacto)   ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_rc_red
        FOREIGN KEY (id_red_social) REFERENCES redes_sociales (id_red_social) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB COMMENT='Perfiles en redes sociales por contacto';

CREATE INDEX idx_rc_contacto ON redes_contacto (id_contacto);
CREATE INDEX idx_rc_red      ON redes_contacto (id_red_social);


-- ------------------------------------------------------------
-- 4.10  GRUPOS / ETIQUETAS DE CONTACTOS  (N:M)
-- ------------------------------------------------------------
CREATE TABLE grupos_contacto (
    id_grupo    BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    id_usuario  BIGINT UNSIGNED NOT NULL COMMENT 'Propietario del grupo',
    nombre      VARCHAR(80)     NOT NULL,
    descripcion VARCHAR(300)        NULL,
    color_hex   CHAR(7)             NULL,
    activo      TINYINT(1)      NOT NULL DEFAULT 1,
    creado_en   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_grupos_contacto PRIMARY KEY (id_grupo),
    CONSTRAINT fk_grupo_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB COMMENT='Grupos / etiquetas creadas por el usuario';

CREATE INDEX idx_grupo_usuario ON grupos_contacto (id_usuario);

CREATE TABLE contacto_grupo (
    id_contacto BIGINT UNSIGNED NOT NULL,
    id_grupo    BIGINT UNSIGNED NOT NULL,
    agregado_en DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_contacto_grupo PRIMARY KEY (id_contacto, id_grupo),
    CONSTRAINT fk_cg_contacto
        FOREIGN KEY (id_contacto) REFERENCES contactos      (id_contacto) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_cg_grupo
        FOREIGN KEY (id_grupo)    REFERENCES grupos_contacto (id_grupo)    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB COMMENT='Tabla pivote contacto ↔ grupo (N:M)';

CREATE INDEX idx_cg_grupo    ON contacto_grupo (id_grupo);
CREATE INDEX idx_cg_contacto ON contacto_grupo (id_contacto);


-- ============================================================
-- SECCIÓN 5: AUDITORÍA Y CONFIGURACIÓN
-- ============================================================

-- ------------------------------------------------------------
-- 5.1  LOG DE AUDITORÍA GENERAL
-- ------------------------------------------------------------
CREATE TABLE auditoria_log (
    id_log         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    id_usuario     BIGINT UNSIGNED     NULL COMMENT 'NULL si acción del sistema',
    tabla_afectada VARCHAR(80)     NOT NULL,
    id_registro    BIGINT UNSIGNED     NULL,
    accion         ENUM('INSERT','UPDATE','DELETE','LOGIN','LOGOUT',
                        'ACTIVATE','PASSWORD_CHANGE','PASSWORD_RESET') NOT NULL,
    datos_previos  JSON                NULL,
    datos_nuevos   JSON                NULL,
    ip_origen      VARCHAR(45)         NULL,
    descripcion    VARCHAR(500)        NULL,
    creado_en      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
-- 5.2  CONFIGURACIÓN DE USUARIO
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
    CONSTRAINT pk_configuracion_usuario PRIMARY KEY (id_config),
    CONSTRAINT uq_config_usuario_clave  UNIQUE (id_usuario, clave),
    CONSTRAINT fk_config_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB COMMENT='Preferencias y ajustes personales de cada usuario';

CREATE INDEX idx_config_usuario ON configuracion_usuario (id_usuario);


-- ============================================================
-- SECCIÓN 6: NAVEGACIÓN Y CONTROL DE ACCESO
-- ============================================================
-- Iconos   : Heroicons  (https://heroicons.com)
-- Paquete  : composer require blade-ui-kit/blade-heroicons
-- Variantes: outline (o) | solid (s) | mini (m)
-- Blade    : <x-heroicon-o-home class="w-5 h-5" />
--            <x-dynamic-component :component="$icono->componente"
--                                 :class="$icono->clase_css" />
-- ============================================================

-- ------------------------------------------------------------
-- 6.1  ICONOS
--       · nombre     → clave SVG de Heroicons  (ej: "home")
--       · libreria   → siempre "heroicons"
--       · variante   → outline | solid | mini
--       · componente → componente Blade completo (ej: heroicon-o-home)
--       · clase_css  → clases Tailwind por defecto para el icono
-- ------------------------------------------------------------
CREATE TABLE iconos (
    id_icono    SMALLINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    nombre      VARCHAR(80)        NOT NULL  COMMENT 'Clave SVG de Heroicons (ej: home, users)',
    libreria    VARCHAR(40)        NOT NULL  DEFAULT 'heroicons',
    variante    ENUM('outline','solid','mini')
                                   NOT NULL  DEFAULT 'outline',
    componente  VARCHAR(120)       NOT NULL  COMMENT 'Componente Blade completo: heroicon-o-home',
    clase_css   VARCHAR(200)       NOT NULL  DEFAULT 'w-5 h-5'
                                   COMMENT 'Clases Tailwind listas para HTML',
    activo      TINYINT(1)         NOT NULL  DEFAULT 1,
    CONSTRAINT pk_iconos       PRIMARY KEY (id_icono),
    CONSTRAINT uq_iconos_comp  UNIQUE      (componente)
) ENGINE=InnoDB COMMENT='Catálogo de iconos Heroicons para menús, submenús y módulos';

CREATE INDEX idx_iconos_libreria ON iconos (libreria);
CREATE INDEX idx_iconos_variante ON iconos (variante);
CREATE INDEX idx_iconos_activo   ON iconos (activo);

INSERT INTO iconos (nombre, variante, componente, clase_css) VALUES
-- ── Menús principales ──────────────────────────────────────────
  ('cog-6-tooth',              'outline','heroicon-o-cog-6-tooth',              'w-5 h-5'),
  ('list-bullet',              'outline','heroicon-o-list-bullet',              'w-5 h-5'),
  ('book-open',                'outline','heroicon-o-book-open',                'w-5 h-5'),
  ('chart-bar',                'outline','heroicon-o-chart-bar',                'w-5 h-5'),
  ('arrow-right-on-rectangle', 'outline','heroicon-o-arrow-right-on-rectangle', 'w-5 h-5'),
-- ── Administración ─────────────────────────────────────────────
  ('users',                    'outline','heroicon-o-users',                    'w-5 h-5'),
  ('user-circle',              'outline','heroicon-o-user-circle',              'w-5 h-5'),
  ('shield-check',             'outline','heroicon-o-shield-check',             'w-5 h-5'),
  ('squares-2x2',              'outline','heroicon-o-squares-2x2',              'w-5 h-5'),
  ('clipboard-document-list',  'outline','heroicon-o-clipboard-document-list',  'w-5 h-5'),
-- ── Maestros / Geografía ───────────────────────────────────────
  ('globe-alt',                'outline','heroicon-o-globe-alt',                'w-5 h-5'),
  ('map-pin',                  'outline','heroicon-o-map-pin',                  'w-5 h-5'),
  ('building-office',          'outline','heroicon-o-building-office',          'w-5 h-5'),
  ('briefcase',                'outline','heroicon-o-briefcase',                'w-5 h-5'),
  ('user',                     'outline','heroicon-o-user',                     'w-5 h-5'),
  ('tag',                      'outline','heroicon-o-tag',                      'w-5 h-5'),
  ('share',                    'outline','heroicon-o-share',                    'w-5 h-5'),
-- ── Directorio ─────────────────────────────────────────────────
  ('phone',                    'outline','heroicon-o-phone',                    'w-5 h-5'),
  ('user-plus',                'outline','heroicon-o-user-plus',                'w-5 h-5'),
  ('user-group',               'outline','heroicon-o-user-group',               'w-5 h-5'),
  ('magnifying-glass',         'outline','heroicon-o-magnifying-glass',         'w-5 h-5'),
-- ── Reportes ───────────────────────────────────────────────────
  ('document-text',            'outline','heroicon-o-document-text',            'w-5 h-5'),
  ('printer',                  'outline','heroicon-o-printer',                  'w-5 h-5'),
  ('arrow-down-tray',          'outline','heroicon-o-arrow-down-tray',          'w-5 h-5'),
-- ── Acciones CRUD ──────────────────────────────────────────────
  ('pencil-square',            'outline','heroicon-o-pencil-square',            'w-4 h-4'),
  ('trash',                    'outline','heroicon-o-trash',                    'w-4 h-4'),
  ('eye',                      'outline','heroicon-o-eye',                      'w-4 h-4'),
  ('plus-circle',              'outline','heroicon-o-plus-circle',              'w-4 h-4'),
  ('lock-closed',              'outline','heroicon-o-lock-closed',              'w-5 h-5'),
  ('key',                      'outline','heroicon-o-key',                      'w-5 h-5');


-- ------------------------------------------------------------
-- 6.2  MENÚS PRINCIPALES
-- ------------------------------------------------------------
CREATE TABLE menus (
    id_menu        SMALLINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    nombre         VARCHAR(80)        NOT NULL,
    descripcion    VARCHAR(255)           NULL,
    id_icono       SMALLINT UNSIGNED      NULL,
    ruta           VARCHAR(200)           NULL  COMMENT 'Named route o URL',
    orden          TINYINT UNSIGNED   NOT NULL DEFAULT 0,
    activo         TINYINT(1)         NOT NULL DEFAULT 1,
    creado_en      DATETIME           NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en DATETIME           NOT NULL DEFAULT CURRENT_TIMESTAMP
                                        ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT pk_menus        PRIMARY KEY (id_menu),
    CONSTRAINT uq_menus_nombre UNIQUE      (nombre),
    CONSTRAINT fk_menus_icono
        FOREIGN KEY (id_icono) REFERENCES iconos (id_icono)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB COMMENT='Menús principales de la aplicación';

CREATE INDEX idx_menus_icono  ON menus (id_icono);
CREATE INDEX idx_menus_orden  ON menus (orden);
CREATE INDEX idx_menus_activo ON menus (activo);

INSERT INTO menus (nombre, descripcion, id_icono, ruta, orden) VALUES
  ('Administración', 'Gestión del sistema, usuarios y configuraciones',
      (SELECT id_icono FROM iconos WHERE componente = 'heroicon-o-cog-6-tooth'),              '/admin',      1),
  ('Maestros',       'Catálogos y tablas maestras del sistema',
      (SELECT id_icono FROM iconos WHERE componente = 'heroicon-o-list-bullet'),              '/maestros',   2),
  ('Directorio',     'Gestión de contactos y directorio telefónico',
      (SELECT id_icono FROM iconos WHERE componente = 'heroicon-o-book-open'),               '/directorio', 3),
  ('Reportes',       'Informes y estadísticas del sistema',
      (SELECT id_icono FROM iconos WHERE componente = 'heroicon-o-chart-bar'),               '/reportes',   4),
  ('Cerrar Sesión',  'Salir de la aplicación',
      (SELECT id_icono FROM iconos WHERE componente = 'heroicon-o-arrow-right-on-rectangle'),'/logout',     99);


-- ------------------------------------------------------------
-- 6.3  SUBMENÚS
-- ------------------------------------------------------------
CREATE TABLE submenus (
    id_submenu     SMALLINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    id_menu        SMALLINT UNSIGNED  NOT NULL,
    nombre         VARCHAR(80)        NOT NULL,
    descripcion    VARCHAR(255)           NULL,
    id_icono       SMALLINT UNSIGNED      NULL,
    ruta           VARCHAR(200)           NULL,
    orden          TINYINT UNSIGNED   NOT NULL DEFAULT 0,
    activo         TINYINT(1)         NOT NULL DEFAULT 1,
    creado_en      DATETIME           NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en DATETIME           NOT NULL DEFAULT CURRENT_TIMESTAMP
                                        ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT pk_submenus PRIMARY KEY (id_submenu),
    CONSTRAINT fk_submenus_menu
        FOREIGN KEY (id_menu)  REFERENCES menus  (id_menu)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_submenus_icono
        FOREIGN KEY (id_icono) REFERENCES iconos (id_icono)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB COMMENT='Submenús dependientes de un menú principal';

CREATE INDEX idx_submenus_menu   ON submenus (id_menu);
CREATE INDEX idx_submenus_icono  ON submenus (id_icono);
CREATE INDEX idx_submenus_orden  ON submenus (orden);
CREATE INDEX idx_submenus_activo ON submenus (activo);

-- ── Administración ────────────────────────────────────────────
INSERT INTO submenus (id_menu, nombre, ruta, id_icono, orden)
SELECT m.id_menu, d.nombre, d.ruta,
       (SELECT id_icono FROM iconos WHERE componente = d.comp), d.orden
FROM menus m
CROSS JOIN (
  SELECT 'Usuarios'        AS nombre, '/admin/usuarios'       AS ruta, 'heroicon-o-users'                   AS comp, 1 AS orden
  UNION ALL SELECT 'Tipos de Usuario', '/admin/tipos-usuario',          'heroicon-o-user-circle',                    2
  UNION ALL SELECT 'Permisos',         '/admin/permisos',               'heroicon-o-shield-check',                   3
  UNION ALL SELECT 'Menús y Módulos',  '/admin/menus',                  'heroicon-o-squares-2x2',                    4
  UNION ALL SELECT 'Auditoría',        '/admin/auditoria',              'heroicon-o-clipboard-document-list',        5
) AS d WHERE m.nombre = 'Administración';

-- ── Maestros ──────────────────────────────────────────────────
INSERT INTO submenus (id_menu, nombre, ruta, id_icono, orden)
SELECT m.id_menu, d.nombre, d.ruta,
       (SELECT id_icono FROM iconos WHERE componente = d.comp), d.orden
FROM menus m
CROSS JOIN (
  SELECT 'Países'          AS nombre, '/maestros/paises'          AS ruta, 'heroicon-o-globe-alt'      AS comp, 1 AS orden
  UNION ALL SELECT 'Departamentos',   '/maestros/nivel1',                  'heroicon-o-map-pin',                2
  UNION ALL SELECT 'Municipios',      '/maestros/nivel2',                  'heroicon-o-building-office',        3
  UNION ALL SELECT 'Barrios',         '/maestros/nivel3',                  'heroicon-o-map-pin',                4
  UNION ALL SELECT 'Profesiones',     '/maestros/profesiones',             'heroicon-o-briefcase',              5
  UNION ALL SELECT 'Sexos / Géneros', '/maestros/sexos',                   'heroicon-o-user',                   6
  UNION ALL SELECT 'Categorías',      '/maestros/categorias',              'heroicon-o-tag',                    7
  UNION ALL SELECT 'Redes Sociales',  '/maestros/redes-sociales',          'heroicon-o-share',                  8
) AS d WHERE m.nombre = 'Maestros';

-- ── Directorio ────────────────────────────────────────────────
INSERT INTO submenus (id_menu, nombre, ruta, id_icono, orden)
SELECT m.id_menu, d.nombre, d.ruta,
       (SELECT id_icono FROM iconos WHERE componente = d.comp), d.orden
FROM menus m
CROSS JOIN (
  SELECT 'Mis Contactos'   AS nombre, '/directorio/contactos'       AS ruta, 'heroicon-o-phone'            AS comp, 1 AS orden
  UNION ALL SELECT 'Nuevo Contacto',  '/directorio/contactos/nuevo',          'heroicon-o-user-plus',               2
  UNION ALL SELECT 'Mis Grupos',      '/directorio/grupos',                   'heroicon-o-user-group',              3
  UNION ALL SELECT 'Buscar Contacto', '/directorio/buscar',                   'heroicon-o-magnifying-glass',        4
) AS d WHERE m.nombre = 'Directorio';

-- ── Reportes ──────────────────────────────────────────────────
INSERT INTO submenus (id_menu, nombre, ruta, id_icono, orden)
SELECT m.id_menu, d.nombre, d.ruta,
       (SELECT id_icono FROM iconos WHERE componente = d.comp), d.orden
FROM menus m
CROSS JOIN (
  SELECT 'Contactos por País'    AS nombre, '/reportes/contactos-pais' AS ruta, 'heroicon-o-chart-bar'      AS comp, 1 AS orden
  UNION ALL SELECT 'Usuarios del Sistema',  '/reportes/usuarios',               'heroicon-o-users',                 2
  UNION ALL SELECT 'Log de Auditoría',      '/reportes/auditoria',              'heroicon-o-document-text',         3
  UNION ALL SELECT 'Exportar Directorio',   '/reportes/exportar',               'heroicon-o-arrow-down-tray',       4
) AS d WHERE m.nombre = 'Reportes';


-- ------------------------------------------------------------
-- 6.4  MÓDULOS  (mapean a controlador@acción)
-- ------------------------------------------------------------
CREATE TABLE modulos (
    id_modulo      SMALLINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    id_submenu     SMALLINT UNSIGNED      NULL  COMMENT 'NULL si depende directamente de un menú',
    id_menu        SMALLINT UNSIGNED      NULL  COMMENT 'Menú padre directo sin submenu',
    nombre         VARCHAR(100)       NOT NULL,
    descripcion    VARCHAR(300)           NULL,
    id_icono       SMALLINT UNSIGNED      NULL,
    ruta           VARCHAR(200)           NULL,
    controlador    VARCHAR(150)           NULL  COMMENT 'App\\Http\\Controllers\\...',
    accion         VARCHAR(80)            NULL  COMMENT 'index|create|store|show|edit|update|destroy',
    orden          TINYINT UNSIGNED   NOT NULL DEFAULT 0,
    visible_menu   TINYINT(1)         NOT NULL DEFAULT 1
                                        COMMENT '¿Aparece en la barra lateral?',
    activo         TINYINT(1)         NOT NULL DEFAULT 1,
    creado_en      DATETIME           NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en DATETIME           NOT NULL DEFAULT CURRENT_TIMESTAMP
                                        ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT pk_modulos PRIMARY KEY (id_modulo),
    CONSTRAINT fk_modulos_submenu
        FOREIGN KEY (id_submenu) REFERENCES submenus (id_submenu)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_modulos_menu
        FOREIGN KEY (id_menu)    REFERENCES menus    (id_menu)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_modulos_icono
        FOREIGN KEY (id_icono)   REFERENCES iconos   (id_icono)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB COMMENT='Módulos funcionales del sistema';

CREATE INDEX idx_modulos_submenu ON modulos (id_submenu);
CREATE INDEX idx_modulos_menu    ON modulos (id_menu);
CREATE INDEX idx_modulos_icono   ON modulos (id_icono);
CREATE INDEX idx_modulos_orden   ON modulos (orden);
CREATE INDEX idx_modulos_visible ON modulos (visible_menu);
CREATE INDEX idx_modulos_activo  ON modulos (activo);
CREATE INDEX idx_modulos_ctrl    ON modulos (controlador(80), accion);

-- Módulos: Usuarios
INSERT INTO modulos (id_submenu, nombre, descripcion, ruta, controlador, accion, visible_menu, orden)
SELECT s.id_submenu, d.nombre, d.desc_, d.ruta, d.ctrl, d.accion, d.vis, d.orden
FROM submenus s
CROSS JOIN (
  SELECT 'Listar Usuarios'  AS nombre, 'Ver todos los usuarios'         AS desc_, '/admin/usuarios'             AS ruta, 'UsuarioController' AS ctrl, 'index'         AS accion, 1 AS vis, 1 AS orden
  UNION ALL SELECT 'Crear Usuario',    'Registrar nuevo usuario',                  '/admin/usuarios/crear',               'UsuarioController',          'create',                 1,         2
  UNION ALL SELECT 'Editar Usuario',   'Modificar datos de usuario',               '/admin/usuarios/{id}/editar',         'UsuarioController',          'edit',                   0,         3
  UNION ALL SELECT 'Eliminar Usuario', 'Dar de baja usuario',                       NULL,                                 'UsuarioController',          'destroy',                0,         4
  UNION ALL SELECT 'Restablecer Clave','Resetear contraseña de usuario',             NULL,                                 'UsuarioController',          'resetPassword',           0,         5
) AS d
WHERE s.nombre = 'Usuarios'
  AND s.id_menu = (SELECT id_menu FROM menus WHERE nombre = 'Administración');

-- Módulos: Permisos
INSERT INTO modulos (id_submenu, nombre, ruta, controlador, accion, visible_menu, orden)
SELECT s.id_submenu, d.nombre, d.ruta, d.ctrl, d.accion, d.vis, d.orden
FROM submenus s
CROSS JOIN (
  SELECT 'Ver Permisos'    AS nombre, '/admin/permisos'          AS ruta, 'PermisoController' AS ctrl, 'index'   AS accion, 1 AS vis, 1 AS orden
  UNION ALL SELECT 'Asignar Permiso', '/admin/permisos/asignar',          'PermisoController',          'store',            1,         2
  UNION ALL SELECT 'Revocar Permiso',  NULL,                               'PermisoController',          'destroy',          0,         3
) AS d WHERE s.nombre = 'Permisos';

-- Módulos: Menús y Módulos
INSERT INTO modulos (id_submenu, nombre, ruta, controlador, accion, visible_menu, orden)
SELECT s.id_submenu, d.nombre, d.ruta, d.ctrl, d.accion, d.vis, d.orden
FROM submenus s
CROSS JOIN (
  SELECT 'Gestionar Menús'    AS nombre, '/admin/menus'    AS ruta, 'MenuController'    AS ctrl, 'index' AS accion, 1 AS vis, 1 AS orden
  UNION ALL SELECT 'Gestionar Submenús', '/admin/submenus',          'SubmenuController',          'index',           1,         2
  UNION ALL SELECT 'Gestionar Módulos',  '/admin/modulos',            'ModuloController',           'index',           1,         3
) AS d WHERE s.nombre = 'Menús y Módulos';

-- Módulos: Mis Contactos
INSERT INTO modulos (id_submenu, nombre, descripcion, ruta, controlador, accion, visible_menu, orden)
SELECT s.id_submenu, d.nombre, d.desc_, d.ruta, d.ctrl, d.accion, d.vis, d.orden
FROM submenus s
CROSS JOIN (
  SELECT 'Listar Contactos'   AS nombre, 'Ver todos los contactos' AS desc_, '/directorio/contactos'              AS ruta, 'ContactoController' AS ctrl, 'index'   AS accion, 1 AS vis, 1 AS orden
  UNION ALL SELECT 'Ver Contacto',       'Detalle de contacto',              '/directorio/contactos/{id}',                 'ContactoController',          'show',             0,         2
  UNION ALL SELECT 'Editar Contacto',    'Modificar contacto',               '/directorio/contactos/{id}/editar',          'ContactoController',          'edit',             0,         3
  UNION ALL SELECT 'Eliminar Contacto',  'Borrar contacto',                   NULL,                                         'ContactoController',          'destroy',          0,         4
) AS d WHERE s.nombre = 'Mis Contactos';

-- Módulos: Nuevo Contacto
INSERT INTO modulos (id_submenu, nombre, descripcion, ruta, controlador, accion, visible_menu, orden)
SELECT s.id_submenu,'Crear Contacto','Agregar nuevo contacto',
       '/directorio/contactos/nuevo','ContactoController','create',1,1
FROM submenus s WHERE s.nombre = 'Nuevo Contacto';

-- Módulos: Maestros (CRUD genérico)
INSERT INTO modulos (id_submenu, nombre, ruta, controlador, accion, orden)
SELECT s.id_submenu,
       CONCAT('Gestionar ', s.nombre),
       s.ruta, c.ctrl, 'index', 1
FROM submenus s
JOIN (
  SELECT 'Países'         AS sub,'PaisController'       AS ctrl UNION ALL
  SELECT 'Departamentos',        'Nivel1Controller'             UNION ALL
  SELECT 'Municipios',           'Nivel2Controller'             UNION ALL
  SELECT 'Barrios',              'Nivel3Controller'             UNION ALL
  SELECT 'Profesiones',          'ProfesionController'          UNION ALL
  SELECT 'Sexos / Géneros',      'SexoController'               UNION ALL
  SELECT 'Categorías',           'CategoriaController'          UNION ALL
  SELECT 'Redes Sociales',       'RedSocialController'
) AS c ON c.sub = s.nombre
WHERE s.id_menu = (SELECT id_menu FROM menus WHERE nombre = 'Maestros');

-- Módulos: Reportes
INSERT INTO modulos (id_submenu, nombre, ruta, controlador, accion, orden)
SELECT s.id_submenu,
       CONCAT('Reporte: ', s.nombre),
       s.ruta, 'ReporteController', c.accion, 1
FROM submenus s
JOIN (
  SELECT 'Contactos por País'    AS sub,'porPais'   AS accion UNION ALL
  SELECT 'Usuarios del Sistema',        'usuarios'            UNION ALL
  SELECT 'Log de Auditoría',            'auditoria'           UNION ALL
  SELECT 'Exportar Directorio',         'exportar'
) AS c ON c.sub = s.nombre
WHERE s.id_menu = (SELECT id_menu FROM menus WHERE nombre = 'Reportes');


-- ------------------------------------------------------------
-- 6.5  PERMISOS  (tipo_usuario × menú / submenú / módulo)
-- ------------------------------------------------------------
CREATE TABLE permisos (
    id_permiso      INT UNSIGNED      NOT NULL AUTO_INCREMENT,
    id_tipo_usuario TINYINT UNSIGNED  NOT NULL,
    id_menu         SMALLINT UNSIGNED     NULL,
    id_submenu      SMALLINT UNSIGNED     NULL,
    id_modulo       SMALLINT UNSIGNED     NULL,
    puede_ver       TINYINT(1)        NOT NULL DEFAULT 0,
    puede_crear     TINYINT(1)        NOT NULL DEFAULT 0,
    puede_editar    TINYINT(1)        NOT NULL DEFAULT 0,
    puede_eliminar  TINYINT(1)        NOT NULL DEFAULT 0,
    puede_exportar  TINYINT(1)        NOT NULL DEFAULT 0,
    puede_imprimir  TINYINT(1)        NOT NULL DEFAULT 0,
    creado_en       DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en  DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP
                                        ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT pk_permisos       PRIMARY KEY (id_permiso),
    CONSTRAINT uq_perm_recurso   UNIQUE (id_tipo_usuario, id_menu, id_submenu, id_modulo),
    CONSTRAINT fk_perm_tipo
        FOREIGN KEY (id_tipo_usuario) REFERENCES tipos_usuario (id_tipo_usuario)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_perm_menu
        FOREIGN KEY (id_menu)    REFERENCES menus    (id_menu)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_perm_submenu
        FOREIGN KEY (id_submenu) REFERENCES submenus (id_submenu)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_perm_modulo
        FOREIGN KEY (id_modulo)  REFERENCES modulos  (id_modulo)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB COMMENT='Permisos CRUD por tipo de usuario sobre menú/submenú/módulo';

CREATE INDEX idx_perm_tipo    ON permisos (id_tipo_usuario);
CREATE INDEX idx_perm_menu    ON permisos (id_menu);
CREATE INDEX idx_perm_submenu ON permisos (id_submenu);
CREATE INDEX idx_perm_modulo  ON permisos (id_modulo);
CREATE INDEX idx_perm_ver     ON permisos (puede_ver);


-- ------------------------------------------------------------
-- 6.6  PERMISOS POR USUARIO  (sobreescritura individual)
-- ------------------------------------------------------------
CREATE TABLE permisos_usuario (
    id_permiso_usuario INT UNSIGNED      NOT NULL AUTO_INCREMENT,
    id_usuario         BIGINT UNSIGNED   NOT NULL,
    id_menu            SMALLINT UNSIGNED     NULL,
    id_submenu         SMALLINT UNSIGNED     NULL,
    id_modulo          SMALLINT UNSIGNED     NULL,
    puede_ver          TINYINT(1)        NOT NULL DEFAULT 0,
    puede_crear        TINYINT(1)        NOT NULL DEFAULT 0,
    puede_editar       TINYINT(1)        NOT NULL DEFAULT 0,
    puede_eliminar     TINYINT(1)        NOT NULL DEFAULT 0,
    puede_exportar     TINYINT(1)        NOT NULL DEFAULT 0,
    puede_imprimir     TINYINT(1)        NOT NULL DEFAULT 0,
    concedido          TINYINT(1)        NOT NULL DEFAULT 1
                         COMMENT '1=conceder acceso extra | 0=revocar acceso',
    motivo             VARCHAR(300)          NULL,
    creado_en          DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en     DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP
                                           ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT pk_permisos_usuario PRIMARY KEY (id_permiso_usuario),
    CONSTRAINT uq_pu_recurso       UNIQUE (id_usuario, id_menu, id_submenu, id_modulo),
    CONSTRAINT fk_pu_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_pu_menu
        FOREIGN KEY (id_menu)    REFERENCES menus    (id_menu)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_pu_submenu
        FOREIGN KEY (id_submenu) REFERENCES submenus (id_submenu)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_pu_modulo
        FOREIGN KEY (id_modulo)  REFERENCES modulos  (id_modulo)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB COMMENT='Permisos individuales por usuario (sobreescribe al tipo)';

CREATE INDEX idx_pu_usuario   ON permisos_usuario (id_usuario);
CREATE INDEX idx_pu_menu      ON permisos_usuario (id_menu);
CREATE INDEX idx_pu_submenu   ON permisos_usuario (id_submenu);
CREATE INDEX idx_pu_modulo    ON permisos_usuario (id_modulo);
CREATE INDEX idx_pu_concedido ON permisos_usuario (concedido);


-- ============================================================
-- PERMISOS INICIALES POR ROL
-- ============================================================

-- Superadministrador → acceso total a todos los menús
INSERT INTO permisos (id_tipo_usuario, id_menu, puede_ver, puede_crear, puede_editar, puede_eliminar, puede_exportar, puede_imprimir)
SELECT tu.id_tipo_usuario, m.id_menu, 1,1,1,1,1,1
FROM tipos_usuario tu CROSS JOIN menus m
WHERE tu.nombre = 'Superadministrador';

-- Administrador → todo excepto Cerrar Sesión
INSERT INTO permisos (id_tipo_usuario, id_menu, puede_ver, puede_crear, puede_editar, puede_eliminar, puede_exportar, puede_imprimir)
SELECT tu.id_tipo_usuario, m.id_menu, 1,1,1,1,1,1
FROM tipos_usuario tu CROSS JOIN menus m
WHERE tu.nombre = 'Administrador' AND m.nombre <> 'Cerrar Sesión';

-- Moderador → Directorio + Maestros + Reportes (sin eliminar)
INSERT INTO permisos (id_tipo_usuario, id_menu, puede_ver, puede_crear, puede_editar, puede_eliminar, puede_exportar, puede_imprimir)
SELECT tu.id_tipo_usuario, m.id_menu, 1,1,1,0,1,1
FROM tipos_usuario tu CROSS JOIN menus m
WHERE tu.nombre = 'Moderador' AND m.nombre IN ('Directorio','Maestros','Reportes');

-- Usuario Premium → Directorio + Reportes
INSERT INTO permisos (id_tipo_usuario, id_menu, puede_ver, puede_crear, puede_editar, puede_eliminar, puede_exportar, puede_imprimir)
SELECT tu.id_tipo_usuario, m.id_menu, 1,1,1,0,1,0
FROM tipos_usuario tu CROSS JOIN menus m
WHERE tu.nombre = 'Usuario Premium' AND m.nombre IN ('Directorio','Reportes');

-- Usuario Estándar → solo Directorio
INSERT INTO permisos (id_tipo_usuario, id_menu, puede_ver, puede_crear, puede_editar, puede_eliminar, puede_exportar, puede_imprimir)
SELECT tu.id_tipo_usuario, m.id_menu, 1,1,1,0,0,0
FROM tipos_usuario tu CROSS JOIN menus m
WHERE tu.nombre = 'Usuario Estándar' AND m.nombre = 'Directorio';

-- Invitado → solo ver Directorio
INSERT INTO permisos (id_tipo_usuario, id_menu, puede_ver, puede_crear, puede_editar, puede_eliminar, puede_exportar, puede_imprimir)
SELECT tu.id_tipo_usuario, m.id_menu, 1,0,0,0,0,0
FROM tipos_usuario tu CROSS JOIN menus m
WHERE tu.nombre = 'Invitado' AND m.nombre = 'Directorio';


-- ============================================================
-- RESUMEN DE TABLAS CREADAS
-- ============================================================
SELECT
    t.TABLE_NAME                             AS `Tabla`,
    t.TABLE_ROWS                             AS `Filas aprox.`,
    t.TABLE_COMMENT                          AS `Descripción`
FROM information_schema.TABLES t
WHERE t.TABLE_SCHEMA = 'directorio_telefonico'
ORDER BY t.TABLE_NAME;

-- ============================================================
-- FIN  –  directorio_telefonico  (28 tablas)
--
--   SECCIÓN 1  Catálogos    : sexos · tipos_usuario · profesiones
--   SECCIÓN 2  Geografía    : paises · divisiones_nivel1/2/3
--   SECCIÓN 3  Usuarios     : usuarios · credenciales ·
--                             historial_passwords · sesiones
--   SECCIÓN 4  Directorio   : tipos_telefono · tipos_direccion ·
--                             tipos_email · categorias_contacto ·
--                             contactos · telefonos_contacto ·
--                             emails_contacto · direcciones_contacto ·
--                             redes_sociales · redes_contacto ·
--                             grupos_contacto · contacto_grupo
--   SECCIÓN 5  Auditoría    : auditoria_log · configuracion_usuario
--   SECCIÓN 6  Navegación   : iconos (Heroicons) · menus · submenus ·
--                             modulos · permisos · permisos_usuario
-- ============================================================