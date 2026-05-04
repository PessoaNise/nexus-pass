-- Insertar la nueva categoría 'Beneficios y Cupones'
INSERT INTO categoria (nombre) VALUES ('Beneficios y Cupones');

-- Obtener el ID de la categoría recién insertada (esto funciona si se ejecuta todo el script de corrido en la consola o phpMyAdmin)
SET @cat_id = LAST_INSERT_ID();

-- Insertar los 20 elementos bajo esta nueva categoría
-- Stock = -1 (ilimitado), precio = 0.00, activo = 1
INSERT INTO producto (categoria_id, nombre, descripcion, precio, imagen, stock, activo) VALUES
(@cat_id, 'DIDI', '50% de descuento en el primer viaje y 30% en los siguientes dos viajes.', 0.00, 'didi_promo.jpg', -1, 1),
(@cat_id, 'McDonald''s', '20% de descuento en consumos mínimos de $150 pesos.', 0.00, 'mcdonalds_promo.jpg', -1, 1),
(@cat_id, 'Walmart Pass', '3 meses de envíos sin costo.', 0.00, 'walmart_promo.jpg', -1, 1),
(@cat_id, 'Smart Fit', 'Plan Black 12 meses por $479 por mes.', 0.00, 'smartfit_promo.jpg', -1, 1),
(@cat_id, 'Devlyn', '20% de descuento en el total de tu compra.', 0.00, 'devlyn_promo.jpg', -1, 1),
(@cat_id, 'Koofr', 'Almacenamiento en la nube.', 0.00, 'koofr_promo.jpg', -1, 1),
(@cat_id, 'Mister Tennis', '15% de descuento en tienda o en línea.', 0.00, 'mistertennis_promo.jpg', -1, 1),
(@cat_id, 'Crunchyroll', '1 Mes gratis de prueba.', 0.00, 'crunchyroll_promo.jpg', -1, 1),
(@cat_id, 'Capital Bus', '10% de descuento en paquetes CDMX.', 0.00, 'capitalbus_promo.jpg', -1, 1),
(@cat_id, 'Free Fire', '1 Ticket Luck Royale sin costo.', 0.00, 'freefire_promo.jpg', -1, 1),
(@cat_id, 'Hotel Piragua', '15% de descuento en paquetes de viaje.', 0.00, 'hotelpiragua_promo.jpg', -1, 1),
(@cat_id, 'Cinemex Platino', 'Combo grande por $130.', 0.00, 'cinemex_promo.jpg', -1, 1),
(@cat_id, 'Dentalia', '35% de descuento en limpieza dental.', 0.00, 'dentalia_promo.jpg', -1, 1),
(@cat_id, 'Fraiche', '10% de descuento en toda la tienda.', 0.00, 'fraiche_promo.jpg', -1, 1),
(@cat_id, 'Ecobici', '10% de descuento en membresía Ecobici+.', 0.00, 'ecobici_promo.jpg', -1, 1),
(@cat_id, 'Vips', '20% de descuento en consumo mínimo de $199.', 0.00, 'vips_promo.jpg', -1, 1),
(@cat_id, 'Apple Arcade', 'Hasta 4 meses gratis.', 0.00, 'apple_promo.jpg', -1, 1),
(@cat_id, 'Norton Antivirus', '1 mes gratis en productos de suscripción.', 0.00, 'norton_promo.jpg', -1, 1),
(@cat_id, 'Terapify', '60% de descuento en primera consulta.', 0.00, 'terapify_promo.jpg', -1, 1),
(@cat_id, 'Porrúa', '10% de descuento en tu compra.', 0.00, 'porrua_promo.jpg', -1, 1);
