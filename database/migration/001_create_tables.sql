CREATE TABLE tipo_identificacion (
    idtipoidentificacion INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE cliente (
    idcliente INT AUTO_INCREMENT PRIMARY KEY,
    idtipoidentificacion INT NULL,
    numero_identificacion VARCHAR(50) NULL,
    nombres VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    FOREIGN KEY (idtipoidentificacion) REFERENCES tipo_identificacion(idtipoidentificacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE categoria (
    idcategoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE producto (
    idproducto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255),
    precio DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL,
    idcategoria INT,
    imagen VARCHAR(255),
    FOREIGN KEY (idcategoria) REFERENCES categoria(idcategoria)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE personalizaciones (
    idpersonalizacion INT AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(255) NOT NULL,
    costo DECIMAL(10,2) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE carrito (
    idcarrito INT AUTO_INCREMENT PRIMARY KEY,
    idcliente INT NOT NULL,
    creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idcliente) REFERENCES cliente(idcliente)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE carrito_detalles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idcarrito INT NOT NULL,
    idproducto INT NOT NULL,
    cantidad INT NOT NULL,
    idpersonalizacion INT DEFAULT NULL,
    FOREIGN KEY (idcarrito) REFERENCES carrito(idcarrito),
    FOREIGN KEY (idproducto) REFERENCES producto(idproducto),
    FOREIGN KEY (idpersonalizacion) REFERENCES personalizaciones(idpersonalizacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE metodos_pago (
    idmetodopago INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(4) NOT NULL UNIQUE,
    nombre VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE estado_pedido (
    idestado INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE pedido (
    idpedido INT AUTO_INCREMENT PRIMARY KEY,
    idcliente INT NOT NULL,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL,
    idestado INT NOT NULL,
    progreso VARCHAR(100),
    idmetodopago INT,
    FOREIGN KEY (idcliente) REFERENCES cliente(idcliente),
    FOREIGN KEY (idmetodopago) REFERENCES metodos_pago(idmetodopago),
    FOREIGN KEY (idestado) REFERENCES estado_pedido(idestado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE pedido_detalles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idpedido INT NOT NULL,
    idproducto INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    idpersonalizacion INT DEFAULT NULL,
    FOREIGN KEY (idpedido) REFERENCES pedido(idpedido),
    FOREIGN KEY (idproducto) REFERENCES producto(idproducto),
    FOREIGN KEY (idpersonalizacion) REFERENCES personalizaciones(idpersonalizacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE detalle_personalizacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idcarrito_detalle INT DEFAULT NULL,
    idpedido_detalle INT DEFAULT NULL,
    idpersonalizacion INT NOT NULL,
    instrucciones VARCHAR(500) NOT NULL,
    precio_extra DECIMAL(10,2) NOT NULL DEFAULT 0,
    FOREIGN KEY (idcarrito_detalle) REFERENCES carrito_detalles(id),
    FOREIGN KEY (idpedido_detalle) REFERENCES pedido_detalles(id),
    FOREIGN KEY (idpersonalizacion) REFERENCES personalizaciones(idpersonalizacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------------
-- Usuarios y Roles
-- ---------------------------------------------------------------------------------
CREATE TABLE opcion (
    idopcion INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion VARCHAR(255),
    ruta VARCHAR(255) NOT NULL,
    estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE rol (
    idrol INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion VARCHAR(255),
    estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE accion (
    idaccion INT AUTO_INCREMENT PRIMARY KEY,
    accion VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE usuario (
    idusuario INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    idrol INT NOT NULL,
    estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
    FOREIGN KEY (idrol) REFERENCES rol(idrol)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE rol_opcion (
    idrol INT NOT NULL,
    idopcion INT NOT NULL,
    idaccion INT NOT NULL,
    estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
    PRIMARY KEY (idrol, idopcion, idaccion),
    FOREIGN KEY (idrol) REFERENCES rol(idrol),
    FOREIGN KEY (idopcion) REFERENCES opcion(idopcion),
    FOREIGN KEY (idaccion) REFERENCES accion(idaccion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;








