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
            <li><a href="register.php" data-nav="register" class="<?php echo (isset($page) && $page === 'register') ? 'active' : ''; ?>">Register</a></li>
            <li><a href="#" data-nav="shop" class="<?php echo (isset($page) && $page === 'shop') ? 'active' : ''; ?>">Shop</a></li>
        </ul>
    </nav>
</header>