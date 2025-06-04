-- Insertar datos en tipo_identificacion
INSERT INTO tipo_identificacion (nombre) VALUES
('DNI'),
('Carnet de Extranjería'),
('Pasaporte'),
('RUC'),
('Licencia de Conducir'),
('Documento Nacional Extranjero');

-- Insertar datos en categoria
INSERT INTO categoria (nombre, descripcion) VALUES
('Anillos', 'Anillos de oro, plata, acero y otros materiales preciosos.'),
('Collares', 'Collares y cadenas de diferentes estilos y materiales.'),
('Pulseras', 'Pulseras elegantes y casuales para toda ocasión.'),
('Aretes', 'Aretes y pendientes de diversos diseños.'),
('Relojes', 'Relojes de pulsera para hombre y mujer.'),
('Broches', 'Broches decorativos y funcionales.'),
('Tobilleras', 'Tobilleras de moda y personalizadas.'),
('Joyería para niños', 'Joyería segura y divertida para niños.'),
('Joyería personalizada', 'Piezas personalizadas con nombres, iniciales o mensajes.'),
('Sets de joyería', 'Conjuntos de joyas combinadas.'),
('Piercings', 'Piercings de diferentes materiales y estilos.'),
('Alianzas', 'Alianzas matrimoniales y de compromiso.');

-- Insertar datos en personalizaciones
INSERT INTO personalizaciones (descripcion,costo) VALUES
('Grabado de iniciales', 10.00),
('Grabado de fecha especial', 12.00),
('Bordado de nombre', 15.00),
('Envoltura de regalo', 5.00),
('Mensaje personalizado en tarjeta', 3.00),
('Cambio de tamaño', 20.00),
('Piedra adicional', 25.00);

-- Insertar datos en metodos_pago
INSERT INTO metodos_pago (codigo, nombre) VALUES
('TADC', 'Tarjeta de Crédito/Débito'),
('YAPE', 'Yape'),
('PLIN', 'Plin'),
('PYAP', 'PayPal'),
('TBAN', 'Transferencia Bancaria'),
('PAGE', 'PagoEfectivo'),
('BITC', 'Bitcoin');

INSERT INTO estado_pedido (nombre, descripcion) VALUES
('Pendiente', 'El pedido ha sido recibido pero no procesado'),
('Pagado', 'El pedido ha sido pagado'),
('Enviado', 'El pedido ha sido enviado al cliente'),
('Entregado', 'El pedido ha sido entregado al cliente'),
('Cancelado', 'El pedido ha sido cancelado'),
('En Proceso', 'El pedido está siendo preparado');

-- Insertar datos en cliente
-- NOTA: Las contraseñas deben ser almacenadas de forma segura (hashed) en una aplicación real.
-- Aquí se usan placeholders.
INSERT INTO cliente (nombres, email, contrasena) VALUES
('Ana Sofía Rojas Vidal', 'ana.rojas@example.com', '123456'),
('John Michael Doe Smith', 'johndoe@example.com', '123456'),
('Carlos Alberto Pérez Gómez', 'carlos.perez@example.com', '123456'),
('Isabelle Dubois Moreau', 'isabelle.dubois@example.com', '123456'),
('Comercializadora ABC S.A.C.', 'contacto@comercialabc.com', '123456'),
('Luis Fernando Torres', 'luis.torres@example.com', '123456'),
('Maria Elena Sánchez', 'maria.sanchez@example.com', '123456');

-- Insertar datos en producto
INSERT INTO producto (nombre, descripcion, precio, stock, idcategoria, imagen) VALUES
('Anillo de Oro 18k', 'Anillo clásico de oro amarillo de 18 quilates.', 1200.00, 10, 1, 'anillo_01.png'),
('Collar de Plata', 'Collar elegante de plata esterlina.', 350.00, 15, 2, 'collar_01.png'),
('Pulsera de Acero', 'Pulsera resistente de acero inoxidable.', 75.90, 25, 3, 'pulsera_01.png'),
('Aretes de Perlas', 'Aretes con perlas naturales.', 180.00, 20, 4, 'aretes_01.png'),
('Reloj Clásico', 'Reloj de pulsera con correa de cuero.', 950.00, 8, 5, 'reloj_clasico.png'),
('Broche Flor', 'Broche decorativo en forma de flor.', 60.00, 30, 6, 'broche_flor.png'),
('Tobillera de Cuentas', 'Tobillera hecha a mano con cuentas de colores.', 95.00, 18, 7, 'tobillera_cuentas.png'),
('Set Infantil', 'Set de collar y pulsera para niños.', 110.00, 12, 8, 'set_infantil.png'),
('Collar Personalizado', 'Collar con nombre grabado.', 110.20, 14, 9, 'collar_02.png'),
('Set de Joyería Elegante', 'Conjunto de collar, aretes y pulsera.', 350.00, 6, 10, 'set_joyeria_elegante.png'),
('Piercing de Oro', 'Piercing de oro para oreja.', 80.00, 20, 11, 'pendientes_01.png'),
('Alianza de Plata', 'Alianza de plata para compromiso.', 200.00, 10, 12, 'pendientes_02.png');

INSERT INTO carrito (idcliente, creado_en) VALUES
(1, '2025-05-10 10:00:00'),
(2, '2025-05-12 15:30:00'),
(3, NOW()),
(4, '2025-05-14 09:00:00');

-- Carrito 1
INSERT INTO carrito_detalles (idcarrito, idproducto, cantidad, idpersonalizacion) VALUES
(1, 1, 2, 1),
(1, 3, 1, NULL);

-- Carrito 2
INSERT INTO carrito_detalles (idcarrito, idproducto, cantidad, idpersonalizacion) VALUES
(2, 2, 1, 2);

-- Carrito 3
INSERT INTO carrito_detalles (idcarrito, idproducto, cantidad, idpersonalizacion) VALUES
(3, 4, 1, NULL),
(3, 11, 2, 6);

-- Carrito 4
INSERT INTO carrito_detalles (idcarrito, idproducto, cantidad, idpersonalizacion) VALUES
(4, 12, 1, 7);

INSERT INTO pedido (idcliente, fecha, total, idestado, progreso, idmetodopago) VALUES
(1, '2025-05-11 12:00:00', 1275.90, 2, 50, 1),
(2, '2025-05-13 16:00:00', 350.00, 1, 10, 2),
(5, '2025-05-15 18:00:00', 200.00, 3, 80, 3);

INSERT INTO pedido_detalles (idpedido, idproducto, cantidad, precio_unitario, idpersonalizacion) VALUES 
(1, 1, 1, 1200.00, 1);

INSERT INTO pedido_detalles (idpedido, idproducto, cantidad, precio_unitario, idpersonalizacion) VALUES
(1, 3, 1, 75.90, NULL);

INSERT INTO pedido_detalles (idpedido, idproducto, cantidad, precio_unitario, idpersonalizacion) VALUES
(2, 2, 1, 350.00, 2);

INSERT INTO pedido_detalles (idpedido, idproducto, cantidad, precio_unitario, idpersonalizacion) VALUES
(3, 12, 1, 200.00, 7);



INSERT INTO accion (accion) VALUES
('CREAR'),
('LEER'),
('ACTUALIZAR'),
('ELIMINAR');

INSERT INTO rol (nombre) VALUES
('Administrador'),
('Soporte'),
('Logistica'),
('Vendedor');

INSERT INTO opcion (nombre, descripcion, ruta) VALUES
('Usuarios', 'Administrar usuarios del sistema' ,'user'),
('Productos', 'Administrar productos disponibles', 'product'),
('Pedidos', 'Gestionar pedidos realizados por los clientes', 'order'),
('Reportes', 'Generar reportes de ventas y actividad del sistema', 'report'),
('Configuración', 'Configurar opciones generales del sistema','config');

INSERT INTO usuario (nombres, email, contrasena, idrol) VALUES
('Akira Naganoma', 'naganoma15@gmail.com','123456', 1),
('Personal 01', '123456@gmail.com','123456', 2);


INSERT INTO rol_opcion (idrol, idopcion, idaccion) VALUES
(1, 1, 1),
(1, 1, 2),
(1, 1, 3),
(1, 1, 4),
(1, 2, 1),
(1, 2, 2),
(1, 2, 3),
(1, 2, 4),
(1, 3, 1),
(1, 3, 2),
(1, 3, 3),
(1, 3, 4),
(2, 1, 2),
(2, 2, 2),
(2, 3, 2);