<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="container">
        <form id="loginForm" action="index.php?route=login" method="post">
            <h2>Login</h2>
            <?php if (isset($_SESSION['error'])): ?>
                <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
            <?php endif; ?>

            <input type="email" id="email" name="email" placeholder="Email" required>
            <span id="emailError" class="error-message"></span>

            <input type="password" id="password" name="password" placeholder="Password" required>
            <span id="passwordError" class="error-message"></span>
            
            <button type="submit">Login</button>
            <p>Don't have an account? <a href="index.php?route=signup">Create New Account</a></p>
        </form>
        <div id="message">

        </div>
    </div>
    <script src="assets/js/validation.js"></script>
</body>
</html>