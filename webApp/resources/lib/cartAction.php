<?php

include '../db/ProductoDB.php';
include '../db/PedidoDB.php';
include '../db/ItemPedidoDB.php';

require_once '../db/CarroDB.php';
$cart = new Cart;

$redirectURL = '../../public/catalogo.php';

// Process request based on the specified action
if (isset($_REQUEST['action']) && !empty($_REQUEST['action'])) {
    if ($_REQUEST['action'] == 'addToCart' && !empty($_REQUEST['id'])) {
        $product_id = $_REQUEST['id'];
        $product = ProductoDB::getProductoPorId($product_id);
        
        $itemData = array(
            'id' => $product['id'],
            'image' => $product['imagen'],
            'name' => $product['nombre'],
            'price' => $product['precio'],
            'qty' => 1
        );

        $is_membresia = ($product['categoria_id'] == 1);
        $is_beneficio = ($product['precio'] == 0 && !$is_membresia);
        $cartItems = $cart->contents();
        
        $current_qty_in_cart = 0;
        $has_membership_in_cart = false;
        $membership_id_in_cart = 0;
        $beneficios_in_cart = 0;

        if (!empty($cartItems)) {
            foreach ($cartItems as $citem) {
                $p_db = ProductoDB::getProductoPorId($citem['id']);
                if ($p_db['categoria_id'] == 1) {
                    $has_membership_in_cart = true;
                    $membership_id_in_cart = $citem['id'];
                } elseif ($p_db['precio'] == 0) {
                    $beneficios_in_cart += $citem['qty'];
                }
                
                if ($citem['id'] == $product_id) {
                    $current_qty_in_cart = $citem['qty'];
                }
            }
        }

        // Regla 1: Un usuario NO puede tener más de 1 membresía distinta o cantidad
        if ($is_membresia && ($has_membership_in_cart || $current_qty_in_cart > 0)) {
            $_SESSION['sessData']['status']['type'] = 'error';
            $_SESSION['sessData']['status']['msg'] = 'No puedes tener múltiples membresías simultáneas en tu carrito.';
            $redirectURL = isset($_REQUEST['return']) && $_REQUEST['return'] == 'catalogo' ? '../../public/catalogo.php' : '../../public/carro_ver.php';
            header("Location: $redirectURL");
            exit();
        }

        // Regla 2: No exceder la suma del stock disponible para hardware
        if (!$is_membresia && !$is_beneficio && ($current_qty_in_cart + 1) > $product['stock']) {
            $_SESSION['sessData']['status']['type'] = 'error';
            $_SESSION['sessData']['status']['msg'] = 'Estás intentando agregar más cantidad de la que tenemos en inventario.';
            $redirectURL = isset($_REQUEST['return']) && $_REQUEST['return'] == 'catalogo' ? '../../public/catalogo.php' : '../../public/carro_ver.php';
            header("Location: $redirectURL");
            exit();
        }

        // Regla 3: Si es un beneficio, validar límites
        if ($is_beneficio) {
            $active_membership_db = 0;
            $beneficios_usados_db = 0;

            if (isset($_SESSION['id_usuario'])) {
                $active_membership_db = PedidoDB::getMembresiaActiva($_SESSION['id_usuario']);
                $beneficios_usados_db = PedidoDB::getCantidadBeneficiosUsados($_SESSION['id_usuario']);
                
                // Regla 3.1: No agregar si ya lo tiene activo en la BD
                if (PedidoDB::hasBeneficioActivo($_SESSION['id_usuario'], $product_id)) {
                    $_SESSION['sessData']['status']['type'] = 'error';
                    $_SESSION['sessData']['status']['msg'] = 'Ya cuentas con este beneficio activo en tu membresía actual.';
                    $redirectURL = isset($_REQUEST['return']) && $_REQUEST['return'] == 'catalogo' ? '../../public/catalogo.php' : '../../public/carro_ver.php';
                    header("Location: $redirectURL");
                    exit();
                }
            }

            // Regla 3.2: No agregar si ya lo tiene en el carrito
            if ($current_qty_in_cart > 0) {
                $_SESSION['sessData']['status']['type'] = 'error';
                $_SESSION['sessData']['status']['msg'] = 'No puedes elegir el mismo beneficio múltiples veces.';
                $redirectURL = isset($_REQUEST['return']) && $_REQUEST['return'] == 'catalogo' ? '../../public/catalogo.php' : '../../public/carro_ver.php';
                header("Location: $redirectURL");
                exit();
            }

            // Usamos la membresía del carrito si la hay, sino la de la BD
            $eval_membership_id = $has_membership_in_cart ? $membership_id_in_cart : $active_membership_db;

            if ($eval_membership_id == 0) {
                $_SESSION['sessData']['status']['type'] = 'error';
                $_SESSION['sessData']['status']['msg'] = 'Para elegir beneficios, primero debes añadir una membresía a tu carrito o tener una activa.';
                header("Location: ../../public/index.php#plans");
                exit();
            }

            // Establecer límites
            $limit = 0;
            if ($eval_membership_id == 1) $limit = 5;       // Bronce
            elseif ($eval_membership_id == 2) $limit = 10;  // Plata
            elseif ($eval_membership_id == 3) $limit = 15;  // Oro

            $total_beneficios_intentados = $beneficios_in_cart + $beneficios_usados_db + 1;

            if ($total_beneficios_intentados > $limit) {
                $_SESSION['sessData']['status']['type'] = 'error';
                $_SESSION['sessData']['status']['msg'] = 'Has alcanzado el límite de beneficios para tu membresía (Límite: ' . $limit . ').';
                $redirectURL = isset($_REQUEST['return']) && $_REQUEST['return'] == 'catalogo' ? '../../public/catalogo.php' : '../../public/carro_ver.php';
                header("Location: $redirectURL");
                exit();
            }
        }

        // Insert item to cart
        $insertItem = $cart->insert($itemData);

        // Redirect to cart page
        if (isset($_REQUEST['return']) && $_REQUEST['return'] == 'catalogo') {
            $redirectURL = '../../public/catalogo.php';
        } else {
            $redirectURL = $insertItem ? '../../public/carro_ver.php' : '../../public/catalogo.php';
        }
    } elseif ($_REQUEST['action'] == 'updateCartItem' && !empty($_REQUEST['id'])) {
        // Update item data in cart
        $rowid = $_REQUEST['id'];
        $qty = $_REQUEST['qty'];
        
        $item = $cart->get_item($rowid);
        if ($item) {
            $product = ProductoDB::getProductoPorId($item['id']);
            
            // Si es membresia y la intentan cambiar de cantidad a huevos con requests
            if ($product['categoria_id'] == 1 && $qty != 1) {
                echo 'err_membresia';
                die;
            }
            // Si no es membresia y excede stock
            if ($product['categoria_id'] != 1 && $qty > $product['stock']) {
                echo 'err_stock';
                die;
            }
        }

        $itemData = array(
            'rowid' => $rowid,
            'qty' => $qty
        );
        $updateItem = $cart->update($itemData);

        // Return status
        echo $updateItem ? 'ok' : 'err';
        die;
    } elseif ($_REQUEST['action'] == 'removeCartItem' && !empty($_REQUEST['id'])) {
        // Remove item from cart
        $deleteItem = $cart->remove($_REQUEST['id']);

        // Redirect to cart page
        $redirectURL = '../../public/carro_ver.php';
    } elseif ($_REQUEST['action'] == 'placeOrderWHOOP' && $cart->total_items() > 0) {
        
        $errorMsg = '';
        
        // 1. Si no hay sesión, crear la cuenta con los datos de Persona y Usuario
        if (!isset($_SESSION['usuario'])) {
            require_once '../db/PersonaDB.php';
            require_once '../db/UsuarioDB.php';
            require_once 'sanitizacion.php';
            
            $personaDB = new PersonaDB();
            $usuarioDB = new UsuarioDB();
            
            $nombre = sanitizacion($_POST['nombre']);
            $apellidos = sanitizacion($_POST['apellidos']);
            $telefono = sanitizacion($_POST['telefono']);
            $correo = sanitizacion($_POST['correo']);
            $usuarioStr = sanitizacion($_POST['usuario']);
            $password = $_POST['pwd'];
            
            // Creamos Persona
            $persona_id = $personaDB->registrar($nombre, $apellidos, $telefono, $correo);
            if ($persona_id) {
                // Creamos Usuario
                $usuario_id = $usuarioDB->registrar($persona_id, $usuarioStr, $password);
                if ($usuario_id) {
                    // Auto-activar al usuario porque acaba de hacer un pago validado
                    UsuarioDB::activaUsuarioById($usuario_id);
                    // Inyectar sesión
                    $_SESSION['usuario'] = $usuarioStr;
                    $_SESSION['id_usuario'] = $usuario_id;
                    $_SESSION['tipo_usuario'] = 'cliente';
                } else {
                    $errorMsg = "Error: El usuario o correo ya existen. Inicia sesión.";
                }
            } else {
                $errorMsg = "Error al crear tu identidad.";
            }
        }
        if (empty($errorMsg)) {
            // Actualizar datos de dirección en base de datos para futuras compras
            require_once '../db/PersonaDB.php';
            require_once 'sanitizacion.php';
            $personaDB = new PersonaDB();
            
            $calle = sanitizacion($_POST['calle'] ?? '');
            $noExt = sanitizacion($_POST['noExterior'] ?? '');
            $noInt = sanitizacion($_POST['noInterior'] ?? '');
            $colonia = sanitizacion($_POST['colonia'] ?? '');
            $ciudad = sanitizacion($_POST['ciudad'] ?? '');
            $estado = sanitizacion($_POST['estado'] ?? '');
            $cp = sanitizacion($_POST['cp'] ?? '');
            
            $personaDB->actualizarDireccionPorIdUsuario($_SESSION['id_usuario'], $calle, $noExt, $noInt, $colonia, $ciudad, $estado, $cp);

            // 2. Procesamos el Pago e Insertamos la Orden
            $resInsertaOrden = PedidoDB::insertaOrden($_SESSION['id_usuario'], $cart->total());

            if ($resInsertaOrden) {
                $ordenId = PedidoDB::getUltimaIdInsertada();
                $cartItems = $cart->contents();

                if (!empty($cartItems)) {
                    foreach ($cartItems as $item) {
                        ItemPedidoDB::insertaOrden($ordenId, $item['id'], $item['qty'], $item['price'] ?? 0);
                        ProductoDB::sustraerStockProducto($item['id'], $item['qty']);
                    }
                    $cart->destroy();
                    $redirectURL = '../../public/pago_validacion.php?id=' . base64_encode($ordenId);
                    
                    // Aseguramos que la redirección sea forzada internamente
                    header("Location: $redirectURL");
                    exit();
                } else {
                    $_SESSION['sessData']['status']['type'] = 'error';
                    $_SESSION['sessData']['status']['msg'] = 'Hubo un problema con tu carrito. Intenta nuevamente.';
                    $redirectURL = '../../public/orden_pago.php';
                }
            } else {
                $_SESSION['sessData']['status']['type'] = 'error';
                $_SESSION['sessData']['status']['msg'] = 'No se pudo crear la orden en la BD.';
                $redirectURL = '../../public/orden_pago.php';
            }
        } else {
            $_SESSION['sessData']['status']['type'] = 'error';
            $_SESSION['sessData']['status']['msg'] = $errorMsg;
            $redirectURL = '../../public/orden_pago.php';
        }
    }
}

// Redirect to the specific page
header("Location: $redirectURL");
exit();
