<?php
// Author: Ayden Hawkins
session_start();
$page = 'login';

$errors = [];
$values = ['email' => ''];
$pw = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values['email'] = trim($_POST['email'] ?? '');
    $pw = trim($_POST['password'] ?? '');

    if ($values['email'] === '') {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email address.';
    }

    if ($pw === '') {
        $errors[] = 'Password is required.';
    }

    if (empty($errors)) {
        require_once '../../pdo_connect.php';
        try {
            $stmt = $dbc->prepare('SELECT id, username, fullname, email, password_hash FROM Users WHERE email = :email LIMIT 1');
            $stmt->execute([':email' => $values['email']]);
            $user = $stmt->fetch();

            if (!$user) {
                $errors[] = 'No account found for that email.';
            } else {
                if (!password_verify($pw, $user['password_hash'])) {
                    $errors[] = 'Incorrect password.';
                } else {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $success = true;
                    $values['email'] = '';
                    $pw = '';
                }
            }
        } catch (Exception $e) {
            $errors[] = 'Login failed due to a server error. Please try again later.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Happy Apple - Login</title>
    <link rel="stylesheet" href="styles/main.css">
</head>

<body data-page="login">
    <?php require('includes/header.php'); ?>

    <main class="container">
        <section class="main-section card">
            <h2>Login</h2>

            <?php if (!empty($success)) { ?>
                <div class="card">
                    <p>Login successful.</p>
                </div>
            <?php } ?>

            <form id="loginForm" action="login.php" method="post" novalidate>
                <p>
                    <?php if (!empty($errors) && in_array('Email is required.', $errors)) { ?>
                        <div class="error">Email is required.</div>
                    <?php } elseif (!empty($errors) && in_array('Invalid email address.', $errors)) { ?>
                        <div class="error">Invalid email address.</div>
                    <?php } elseif (!empty($errors) && in_array('No account found for that email.', $errors)) { ?>
                        <div class="error">No account found for that email.</div>
                    <?php } ?>
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($values['email']); ?>" required>
                </p>

                <p>
                    <?php if (!empty($errors) && in_array('Password is required.', $errors)) { ?>
                        <div class="error">Password is required.</div>
                    <?php } elseif (!empty($errors) && in_array('Incorrect password.', $errors)) { ?>
                        <div class="error">Incorrect password.</div>
                    <?php } ?>
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </p>

                <p>
                    <button type="submit">Login</button>
                </p>
            </form>
        </section>
    </main>

    <?php require('includes/footer.php'); ?>
</body>

</html>
