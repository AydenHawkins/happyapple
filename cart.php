<?php
// Author: Ayden Hawkins
session_start();
$page = 'cart';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Determine the action to perform
if (isset($_GET['action'])) {
    $action = $_GET['action'];
} elseif (isset($_POST['action'])) {
    $action = $_POST['action'];
} else {
    $action = 'show_cart';
}

// Add or update cart as needed
switch ($action) {
    case 'add':
        $productId = filter_var($_POST['product_id'], FILTER_SANITIZE_NUMBER_INT);
        $qty = filter_var($_POST['qty'] ?? 1, FILTER_SANITIZE_NUMBER_INT);

        if (isset($_SESSION['cart'][$productId])) {
            // Item already in cart - update quantity
            $_SESSION['cart'][$productId]['quantity'] += $qty;
        } else {
            // New product - get details from database
            require_once '../../pdo_connect.php';
            try {
                $stmt = $dbc->prepare('SELECT * FROM ha_products WHERE product_id = :id LIMIT 1');
                $stmt->execute([':id' => $productId]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($product) {
                    // Add to cart
                    $_SESSION['cart'][$productId] = array(
                        'product_id' => $product['product_id'],
                        'product_name' => $product['product_name'],
                        'scent' => $product['scent'],
                        'category' => $product['category'],
                        'price' => $product['price'],
                        'image_filename' => $product['image_filename'],
                        'quantity' => $qty
                    );
                }
            } catch (PDOException $e) {
                // Error handling - could set error message in session
            }
        }

        // Redirect back to referring page or products page
        $redirect = $_SERVER['HTTP_REFERER'] ?? 'products.php';
        header("Location: $redirect");
        exit;
        break;

    case 'update':
        $productId = filter_var($_POST['product_id'], FILTER_SANITIZE_NUMBER_INT);
        $qty = filter_var($_POST['qty'], FILTER_SANITIZE_NUMBER_INT);

        if (isset($_SESSION['cart'][$productId])) {
            if ($qty <= 0) {
                unset($_SESSION['cart'][$productId]);
            } else {
                $_SESSION['cart'][$productId]['quantity'] = $qty;
            }
        }

        // Redirect back to referring page or products page
        $redirect = $_SERVER['HTTP_REFERER'] ?? 'products.php';
        header("Location: $redirect");
        exit;
        break;

    case 'update_all':
        $updates = $_POST['updates'] ?? [];
        foreach ($updates as $update) {
            $productId = filter_var($update['product_id'], FILTER_SANITIZE_NUMBER_INT);
            $qty = filter_var($update['qty'], FILTER_SANITIZE_NUMBER_INT);

            if (isset($_SESSION['cart'][$productId])) {
                if ($qty <= 0) {
                    unset($_SESSION['cart'][$productId]);
                } else {
                    $_SESSION['cart'][$productId]['quantity'] = $qty;
                }
            }
        }
        header('Location: cart.php?action=show_cart');
        exit;
        break;

    case 'remove':
        $productId = filter_var($_POST['product_id'], FILTER_SANITIZE_NUMBER_INT);
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }
        header('Location: cart.php?action=show_cart');
        exit;
        break;

    case 'empty_cart':
        unset($_SESSION['cart']);
        $_SESSION['cart'] = array();
        header('Location: cart.php?action=show_cart');
        exit;
        break;

    case 'show_cart':
    default:
        include('cart_view.php');
        break;
}
?>
