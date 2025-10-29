<?php
// Author: Ayden Hawkins
session_start();
$page = 'home';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Happy Apple - Home</title>
    <link rel="stylesheet" href="styles/main.css">
</head>

<body data-page="home">
    <?php require('includes/header.php'); ?>

    <main class="container">
        <section class="main-section card">
            <h2>Welcome to Happy Apple</h2>
            <p>Happy Apple is your online self-care boutique. We make hand soaps, body lotions, bath bombs, and comforting scents to turn everyday routines into small rituals of joy. Our products are lovingly crafted to nourish your hands, body, and mind.</p>
            <p>Explore our seasonal collections and subscribe for offers and tips about self-care.</p>
        </section>

        <section class="container">
            <div class="card">
                <h3>Featured</h3>
                <p>New: Autumn Orchard Collection — warm, cozy, and apple-scented.</p>
            </div>
        </section>
    </main>

    <?php require('includes/footer.php'); ?>
</body>

</html>