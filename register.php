<?php
// Author: Ayden Hawkins
$page = 'register';

$errors = [];
$values = ['username' => '', 'fullname' => '', 'email' => '', 'newsletter' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $values['username'] = trim($_POST['username'] ?? '');
    $values['fullname'] = trim($_POST['fullname'] ?? '');
    $values['email'] = trim($_POST['email'] ?? '');
    $values['newsletter'] = isset($_POST['newsletter']) ? true : false;

    $pw = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if ($values['username'] === '') {
        $errors[] = 'Username is required.';
    }

    if ($pw === '' || $confirm === '') {
        $errors[] = 'Both password fields are required.';
    } elseif ($pw !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        $success = true;
        $values = ['username' => '', 'fullname' => '', 'email' => '', 'newsletter' => false];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Happy Apple - Register</title>
    <link rel="stylesheet" href="styles/main.css">
</head>

<body data-page="register">
    <?php require('includes/header.php'); ?>

    <main class="container">
        <section class="main-section card">
            <h2>Create an account</h2>

            <?php if (!empty($errors)) { ?>
                <div id="formErrors">
                    <?php foreach ($errors as $err) { ?>
                        <div class="error"><?php echo htmlspecialchars($err); ?></div>
                    <?php } ?>
                </div>
            <?php } ?>

            <?php if (!empty($success)) { ?>
                <div class="card">
                    <p>Registration successful.</p>
                </div>
            <?php } ?>

            <form id="registerForm" action="register.php" method="post" novalidate>
                <div class="form-row">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required value="<?php echo htmlspecialchars($values['username']); ?>">
                </div>

                <div class="form-row">
                    <label for="fullname">Full name</label>
                    <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($values['fullname']); ?>">
                </div>

                <div class="form-row">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($values['email']); ?>">
                </div>

                <div class="form-row">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-row">
                    <label for="confirm">Confirm Password</label>
                    <input type="password" id="confirm" name="confirm" required>
                </div>

                <div class="form-row">
                    <label for="newsletter"><input type="checkbox" id="newsletter" name="newsletter" <?php echo $values['newsletter'] ? 'checked' : ''; ?>> Subscribe to newsletter</label>
                </div>

                <div class="form-row">
                    <button type="submit">Register</button>
                </div>
            </form>
        </section>
    </main>

    <?php require('includes/footer.php'); ?>
</body>

</html>