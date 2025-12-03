<?php
// Author: Ayden Hawkins
?>
<header class="site-header">
    <div class="logo">
        <img src="images/logo.png" alt="Happy Apple logo" width="64" height="64">
        <h1>Happy Apple</h1>
    </div>
    <nav>
        <ul>
            <li><a href="index.php" data-nav="home" class="<?php echo (isset($page) && $page === 'home') ? 'active' : ''; ?>">Home</a></li>
            <li><a href="products.php" data-nav="shop" class="<?php echo (isset($page) && $page === 'shop') ? 'active' : ''; ?>">Shop</a></li>
            <li><a href="cart.php?action=show_cart" data-nav="cart" class="<?php echo (isset($page) && $page === 'cart') ? 'active' : ''; ?>">Cart<?php if (!empty($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?><span class="cart-badge"><?php echo array_sum(array_column($_SESSION['cart'], 'quantity')); ?></span><?php endif; ?></a></li>
            <?php if (!empty($_SESSION['user_id'])) { ?>
                <li><a href="logout.php" data-nav="logout">Logout</a></li>
            <?php } else { ?>
                <li><a href="register.php" data-nav="register" class="<?php echo (isset($page) && $page === 'register') ? 'active' : ''; ?>">Register</a></li>
                <li><a href="login.php" data-nav="login" class="<?php echo (isset($page) && $page === 'login') ? 'active' : ''; ?>">Login</a></li>
            <?php } ?>
        </ul>
    </nav>
</header>