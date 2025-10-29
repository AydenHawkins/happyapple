<?php
// Author: Ayden Hawkins
session_start();
$page = 'register';

$errors = [];
$values = ['username' => '', 'fullname' => '', 'email' => '', 'newsletter' => false];

$pw = '';
$confirm = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $values['username'] = trim($_POST['username'] ?? '');
    $values['fullname'] = trim($_POST['fullname'] ?? '');
    $values['email'] = trim($_POST['email'] ?? '');
    $values['newsletter'] = isset($_POST['newsletter']) ? true : false;

    $pw = trim($_POST['password'] ?? '');
    $confirm = trim($_POST['confirm'] ?? '');

    if ($values['username'] === '') {
        $errors[] = 'Username is required.';
    }

    if ($values['email'] !== '' && !filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email address.';
    }

    if ($pw === '' || $confirm === '') {
        $errors[] = 'Both password fields are required.';
    } elseif ($pw !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        require_once '../../pdo_connect.php';

        try {
            $stmt = $dbc->prepare('SELECT 1 FROM Users WHERE username = :username LIMIT 1');
            $stmt->execute([':username' => $values['username']]);
            if ($stmt->fetchColumn()) {
                $errors[] = 'Username already exists.';
            }

            if ($values['email'] !== '') {
                $stmt = $dbc->prepare('SELECT 1 FROM Users WHERE email = :email LIMIT 1');
                $stmt->execute([':email' => $values['email']]);
                if ($stmt->fetchColumn()) {
                    $errors[] = 'Email already in use.';
                }
            }

            if (empty($errors)) {
                $password_hash = password_hash($pw, PASSWORD_DEFAULT);
                $stmt = $dbc->prepare('INSERT INTO Users (username, fullname, email, password_hash, newsletter) VALUES (:username, :fullname, :email, :password_hash, :newsletter)');
                $stmt->execute([
                    ':username' => $values['username'],
                    ':fullname' => $values['fullname'],
                    ':email' => $values['email'] !== '' ? $values['email'] : null,
                    ':password_hash' => $password_hash,
                    ':newsletter' => $values['newsletter'] ? 1 : 0,
                ]);

                $success = true;
                $values = ['username' => '', 'fullname' => '', 'email' => '', 'newsletter' => false];
                $pw = '';
                $confirm = '';
            }
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
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

            <?php if (!empty($success)) { ?>
                <div class="card">
                    <p>Registration successful.</p>
                </div>
            <?php } ?>

            <form id="registerForm" action="register.php" method="post" novalidate>
                <p>
                    <?php if (!empty($errors) && in_array('Username is required.', $errors)) { ?>
                        <div class="error">Username is required.</div>
                    <?php } elseif (!empty($errors) && in_array('Username already exists.', $errors)) { ?>
                        <div class="error">Username already exists.</div>
                    <?php } ?>
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required value="<?php echo htmlspecialchars($values['username']); ?>">
                </p>

                <p>
                    <label for="fullname">Full name</label>
                    <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($values['fullname']); ?>">
                </p>

                <p>
                    <?php if (!empty($errors) && in_array('Invalid email address.', $errors)) { ?>
                        <div class="error">Invalid email address.</div>
                    <?php } elseif (!empty($errors) && in_array('Email already in use.', $errors)) { ?>
                        <div class="error">Email already in use.</div>
                    <?php } ?>
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($values['email']); ?>">
                </p>

                <?php
                $error_pw_required = !empty($errors) && in_array('Both password fields are required.', $errors);
                $error_pw_mismatch = !empty($errors) && in_array('Passwords do not match.', $errors);
                ?>

                <p>
                    <?php if ($error_pw_required && ($pw === '')) { ?>
                        <div class="error">Password is required.</div>
                    <?php } ?>
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </p>

                <p>
                    <?php if ($error_pw_required && ($confirm === '')) { ?>
                        <div class="error">Confirmation is required.</div>
                    <?php } elseif ($error_pw_mismatch) { ?>
                        <div class="error">Passwords do not match.</div>
                    <?php } ?>
                    <label for="confirm">Confirm Password</label>
                    <input type="password" id="confirm" name="confirm" required>
                </p>

                <p>
                    <label for="newsletter" class="checkbox"><input type="checkbox" id="newsletter" name="newsletter" <?php echo $values['newsletter'] ? 'checked' : ''; ?>> Subscribe to newsletter</label>
                </p>

                <p>
                    <button type="submit">Register</button>
                </p>
            </form>
        </section>
    </main>

    <?php require('includes/footer.php'); ?>
</body>

</html>