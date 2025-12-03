<?php
// Author: Ayden Hawkins
session_start();
$page = 'shop';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

try {
    require_once 'pdo_connect.php';
    $sql = 'SELECT * FROM ha_products ORDER BY product_name';
    $stmt = $dbc->query($sql);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Happy Apple - Shop</title>
    <link rel="stylesheet" href="styles/main.css">
</head>

<body data-page="shop">
    <?php require('includes/header.php'); ?>

    <main class="container">
        <h2>Our Products</h2>
        <p class="shop-intro">Discover our collection of premium home fragrances and body care products.</p>

        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card" data-product-id="<?php echo $product['product_id']; ?>">
                    <a href="product_detail.php?id=<?php echo $product['product_id']; ?>" class="product-link">
                        <div class="product-image">
                            <img src="images/products/<?php echo htmlspecialchars($product['image_filename']); ?>"
                                 alt="<?php echo htmlspecialchars($product['product_name'] . ' - ' . $product['scent']); ?>">
                        </div>
                        <div class="product-info">
                            <h3 class="product-name"><?php echo htmlspecialchars($product['product_name']); ?></h3>
                            <p class="product-scent"><?php echo htmlspecialchars($product['scent']); ?></p>
                            <p class="product-category"><?php echo htmlspecialchars($product['category']); ?></p>
                            <p class="product-price">$<?php echo number_format($product['price'], 2); ?></p>
                        </div>
                    </a>

                    <div class="product-actions">
                        <?php
                        $inCart = isset($_SESSION['cart'][$product['product_id']]);
                        $quantity = $inCart ? $_SESSION['cart'][$product['product_id']]['quantity'] : 0;
                        ?>

                        <?php if (!$inCart): ?>
                            <form action="cart.php" method="post">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                <input type="hidden" name="qty" value="1">
                                <button type="submit" class="add-to-cart-btn">Add to Cart</button>
                            </form>
                        <?php else: ?>
                            <div class="quantity-controls">
                                <form action="cart.php" method="post" style="display: inline;">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                    <input type="hidden" name="qty" value="<?php echo max(0, $quantity - 1); ?>">
                                    <button type="submit" class="qty-btn">−</button>
                                </form>
                                <span class="qty-display"><?php echo $quantity; ?></span>
                                <form action="cart.php" method="post" style="display: inline;">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                    <input type="hidden" name="qty" value="<?php echo $quantity + 1; ?>">
                                    <button type="submit" class="qty-btn">+</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <?php require('includes/footer.php'); ?>
</body>

</html>