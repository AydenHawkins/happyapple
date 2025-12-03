<?php
// Author: Ayden Hawkins
session_start();
$page = 'shop';

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

$error = null;
$product = null;

if (isset($_GET['id'])) {
    $productId = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    try {
        require_once 'pdo_connect.php';
        $stmt = $dbc->prepare('SELECT * FROM ha_products WHERE product_id = :id LIMIT 1');
        $stmt->execute([':id' => $productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            $error = 'Product not found.';
        }
    } catch (PDOException $e) {
        $error = 'Database error occurred.';
    }
} else {
    $error = 'No product specified.';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Happy Apple - <?php echo $product ? htmlspecialchars($product['product_name'] . ' - ' . $product['scent']) : 'Product'; ?></title>
    <link rel="stylesheet" href="styles/main.css">
</head>

<body data-page="shop">
    <?php require('includes/header.php'); ?>

    <main class="container">
        <a href="products.php" class="back-link">← Back to Products</a>

        <?php if ($error): ?>
            <div class="error-message card">
                <h2>Error</h2>
                <p><?php echo htmlspecialchars($error); ?></p>
                <p><a href="products.php" class="btn-primary">Browse Products</a></p>
            </div>
        <?php else: ?>
            <div class="product-detail">
                <div class="product-detail-image">
                    <img src="images/products/<?php echo htmlspecialchars($product['image_filename']); ?>"
                         alt="<?php echo htmlspecialchars($product['product_name'] . ' - ' . $product['scent']); ?>">
                </div>

                <div class="product-detail-info">
                    <h1><?php echo htmlspecialchars($product['product_name']); ?></h1>
                    <p class="detail-scent"><?php echo htmlspecialchars($product['scent']); ?></p>
                    <p class="detail-category"><?php echo htmlspecialchars($product['category']); ?></p>
                    <p class="detail-price">$<?php echo number_format($product['price'], 2); ?></p>

                    <?php if (!empty($product['description'])): ?>
                        <div class="product-description">
                            <h3>Description</h3>
                            <p><?php echo htmlspecialchars($product['description']); ?></p>
                        </div>
                    <?php endif; ?>

                    <div class="detail-actions">
                        <?php
                        $inCart = isset($_SESSION['cart'][$product['product_id']]);
                        $quantity = $inCart ? $_SESSION['cart'][$product['product_id']]['quantity'] : 0;
                        ?>

                        <?php if (!$inCart): ?>
                            <form action="cart.php" method="post">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                <input type="hidden" name="qty" value="1">
                                <button type="submit" class="add-to-cart-btn btn-large">Add to Cart</button>
                            </form>
                        <?php else: ?>
                            <div class="quantity-controls-detail">
                                <form action="cart.php" method="post" style="display: inline;">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                    <input type="hidden" name="qty" value="<?php echo max(0, $quantity - 1); ?>">
                                    <button type="submit" class="qty-btn">−</button>
                                </form>
                                <span class="qty-display-detail"><?php echo $quantity; ?></span>
                                <form action="cart.php" method="post" style="display: inline;">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                    <input type="hidden" name="qty" value="<?php echo $quantity + 1; ?>">
                                    <button type="submit" class="qty-btn">+</button>
                                </form>
                            </div>
                            <p class="in-cart-message">✓ In your cart</p>
                        <?php endif; ?>

                        <a href="cart.php?action=show_cart" class="btn-secondary btn-large">View Cart</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <?php require('includes/footer.php'); ?>
</body>

</html>
