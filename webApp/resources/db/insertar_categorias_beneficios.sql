-- 1. Insertar las 5 nuevas categorías
INSERT INTO categoria (nombre) VALUES ('Comida');
SET @cat_comida = LAST_INSERT_ID();

INSERT INTO categoria (nombre) VALUES ('Educación');
SET @cat_educacion = LAST_INSERT_ID();

INSERT INTO categoria (nombre) VALUES ('Servicios');
SET @cat_servicios = LAST_INSERT_ID();

INSERT INTO categoria (nombre) VALUES ('Salud');
SET @cat_salud = LAST_INSERT_ID();

INSERT INTO categoria (nombre) VALUES ('Diversión');
SET @cat_diversion = LAST_INSERT_ID();

-- 2. Insertar los 20 beneficios asignados a sus respectivas categorías
-- Stock = -1 (ilimitado), precio = 0.00, activo = 1

-- COMIDA
INSERT INTO producto (categoria_id, nombre, descripcion, precio, imagen, stock, activo) VALUES
(@cat_comida, 'McDonald''s', '20% de descuento en consumos mínimos de $150 pesos.', 0.00, 'mcdonalds_promo.jpg', -1, 1),
(@cat_comida, 'Vips', '20% de descuento en consumo mínimo de $199.', 0.00, 'vips_promo.jpg', -1, 1);

-- EDUCACIÓN
INSERT INTO producto (categoria_id, nombre, descripcion, precio, imagen, stock, activo) VALUES
(@cat_educacion, 'Porrúa', '10% de descuento en tu compra.', 0.00, 'porrua_promo.jpg', -1, 1);

-- SERVICIOS
INSERT INTO producto (categoria_id, nombre, descripcion, precio, imagen, stock, activo) VALUES
(@cat_servicios, 'DIDI', '50% de descuento en el primer viaje y 30% en los siguientes dos viajes.', 0.00, 'didi_promo.jpg', -1, 1),
(@cat_servicios, 'Walmart Pass', '3 meses de envíos sin costo.', 0.00, 'walmart_promo.jpg', -1, 1),
(@cat_servicios, 'Koofr', 'Almacenamiento en la nube.', 0.00, 'koofr_promo.jpg', -1, 1),
(@cat_servicios, 'Fraiche', '10% de descuento en toda la tienda.', 0.00, 'fraiche_promo.jpg', -1, 1),
(@cat_servicios, 'Ecobici', '10% de descuento en membresía Ecobici+.', 0.00, 'ecobici_promo.jpg', -1, 1),
(@cat_servicios, 'Norton Antivirus', '1 mes gratis en productos de suscripción.', 0.00, 'norton_promo.jpg', -1, 1);

-- SALUD
INSERT INTO producto (categoria_id, nombre, descripcion, precio, imagen, stock, activo) VALUES
(@cat_salud, 'Smart Fit', 'Plan Black 12 meses por $479 por mes.', 0.00, 'smartfit_promo.jpg', -1, 1),
(@cat_salud, 'Devlyn', '20% de descuento en el total de tu compra.', 0.00, 'devlyn_promo.jpg', -1, 1),
(@cat_salud, 'Dentalia', '35% de descuento en limpieza dental.', 0.00, 'dentalia_promo.jpg', -1, 1),
(@cat_salud, 'Terapify', '60% de descuento en primera consulta.', 0.00, 'terapify_promo.jpg', -1, 1);

-- DIVERSIÓN
INSERT INTO producto (categoria_id, nombre, descripcion, precio, imagen, stock, activo) VALUES
(@cat_diversion, 'Mister Tennis', '15% de descuento en tienda o en línea.', 0.00, 'mistertennis_promo.jpg', -1, 1),
(@cat_diversion, 'Crunchyroll', '1 Mes gratis de prueba.', 0.00, 'crunchyroll_promo.jpg', -1, 1),
(@cat_diversion, 'Capital Bus', '10% de descuento en paquetes CDMX.', 0.00, 'capitalbus_promo.jpg', -1, 1),
(@cat_diversion, 'Free Fire', '1 Ticket Luck Royale sin costo.', 0.00, 'freefire_promo.jpg', -1, 1),
(@cat_diversion, 'Hotel Piragua', '15% de descuento en paquetes de viaje.', 0.00, 'hotelpiragua_promo.jpg', -1, 1),
(@cat_diversion, 'Cinemex Platino', 'Combo grande por $130.', 0.00, 'cinemex_promo.jpg', -1, 1),
(@cat_diversion, 'Apple Arcade', 'Hasta 4 meses gratis.', 0.00, 'apple_promo.jpg', -1, 1);
