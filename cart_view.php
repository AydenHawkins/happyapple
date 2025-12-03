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
                            <div class="quantity-controls-cart">
                                <form action="cart.php" method="post" style="display: inline;">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                                    <input type="hidden" name="qty" value="<?php echo max(0, $item['quantity'] - 1); ?>">
                                    <button type="submit" class="qty-btn">−</button>
                                </form>
                                <span class="qty-display-cart"><?php echo $item['quantity']; ?></span>
                                <form action="cart.php" method="post" style="display: inline;">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                                    <input type="hidden" name="qty" value="<?php echo $item['quantity'] + 1; ?>">
                                    <button type="submit" class="qty-btn">+</button>
                                </form>
                            </div>
                            <p class="item-subtotal">Subtotal: $<?php echo number_format($subtotal, 2); ?></p>
                            <form action="cart.php" method="post" style="display: inline;">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                                <button type="submit" class="remove-btn" onclick="return confirm('Remove this item from your cart?');">Remove</button>
                            </form>
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
                    <form action="checkout.php" method="post">
                        <button type="submit" class="btn-primary checkout-btn">Proceed to Checkout</button>
                    </form>
                    <a href="cart.php?action=empty_cart" class="btn-secondary" onclick="return confirm('Are you sure you want to empty your cart?');">Empty Cart</a>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <?php require('includes/footer.php'); ?>
</body>

</html>
