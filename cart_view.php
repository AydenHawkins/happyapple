<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Happy Apple - Shopping Cart</title>
    <link rel="stylesheet" href="styles/main.css">
</head>

<body data-page="cart">
    <?php require('includes/header.php'); ?>

    <main class="container">
        <div class="cart-header">
            <a href="products.php" class="continue-shopping">← Continue Shopping</a>
            <h2>Your Shopping Cart</h2>
        </div>

        <?php if (empty($_SESSION['cart']) || count($_SESSION['cart']) == 0): ?>
            <div class="empty-cart card">
                <p>Your cart is empty.</p>
                <p><a href="products.php" class="btn-primary">Start Shopping</a></p>
            </div>
        <?php else: ?>
            <div class="cart-items">
                <?php
                $total = 0;
                foreach ($_SESSION['cart'] as $productId => $item):
                    $subtotal = $item['quantity'] * $item['price'];
                    $total += $subtotal;
                ?>
                    <div class="cart-item card" data-product-id="<?php echo $productId; ?>">
                        <div class="cart-item-image">
                            <img src="images/products/<?php echo htmlspecialchars($item['image_filename']); ?>"
                                 alt="<?php echo htmlspecialchars($item['product_name'] . ' - ' . $item['scent']); ?>">
                        </div>

                        <div class="cart-item-details">
                            <h3><?php echo htmlspecialchars($item['product_name']); ?></h3>
                            <p class="item-scent"><?php echo htmlspecialchars($item['scent']); ?></p>
                            <p class="item-category"><?php echo htmlspecialchars($item['category']); ?></p>
                            <p class="item-price">$<?php echo number_format($item['price'], 2); ?> each</p>
                        </div>

                        <div class="cart-item-actions">
                            <div class="quantity-controls-cart" data-product-id="<?php echo $productId; ?>">
                                <button class="qty-btn qty-decrease-cart">−</button>
                                <span class="qty-display-cart"><?php echo $item['quantity']; ?></span>
                                <button class="qty-btn qty-increase-cart">+</button>
                            </div>
                            <p class="item-subtotal">Subtotal: $<?php echo number_format($subtotal, 2); ?></p>
                            <button class="remove-btn" data-product-id="<?php echo $productId; ?>">Remove</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-summary card">
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>$<?php echo number_format($total, 2); ?></span>
                </div>
                <div class="summary-row">
                    <span>Tax (estimated):</span>
                    <span>$<?php echo number_format($total * 0.08, 2); ?></span>
                </div>
                <div class="summary-row total-row">
                    <span><strong>Total:</strong></span>
                    <span><strong>$<?php echo number_format($total * 1.08, 2); ?></strong></span>
                </div>

                <div class="cart-actions">
                    <button class="btn-primary checkout-btn">Proceed to Checkout</button>
                    <a href="cart.php?action=empty_cart" class="btn-secondary" onclick="return confirm('Are you sure you want to empty your cart?');">Empty Cart</a>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <?php require('includes/footer.php'); ?>

    <script src="scripts/cart.js"></script>
</body>

</html>
