<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DAFTAR - LEEP'S STORE</title>
    <link rel="stylesheet" href="css/login_signup_styles.css">
</head>

<body>
    <div class="card">
        <div class="card2">
            <form class="form" method="POST" action="sign_up_action.php">
                <p id="heading">SIGN UP</p>
                <i id="sub-heading">Buat Akun Dulu Yuk!</i>
                <div class="field">
                    <input type="text" class="input-field" name="username" placeholder="Username" autocomplete="off" required />
                </div>
                <?php if (isset($_GET['error']) && strpos($_GET['error'], 'Username hanya boleh mengandung huruf dan angka!') !== false) : ?>
                    <div class="error-message">
                        <p style='color:red;'><?php echo $_GET['error']; ?></p>
                    </div>
                <?php endif; ?>

                <div class="field">
                    <input type="email" class="input-field" name="email" placeholder="Email" required />
                    <?php if (!empty($errors) && in_array('Email tidak valid!', $errors)) echo "<p class='error-message'>Email tidak valid!</p>"; ?>
                </div>
                <?php if (isset($_GET['error']) && strpos($_GET['error'], 'Email tidak valid!') !== false) : ?>
                    <div class="error-message">
                        <p style='color:red;'><?php echo $_GET['error']; ?></p>
                    </div>
                <?php endif; ?>

                <div class="field">
                    <input type="password" class="input-field" name="password" placeholder="Password" required />
                    <?php if (!empty($errors) && in_array('Password harus kombinasi huruf dan angka dan minimal 6 karakter!', $errors)) echo "<p class='error-message'>Password harus kombinasi huruf dan angka dan minimal 6 karakter!</p>"; ?>
                </div>
                <?php if (isset($_GET['error']) && strpos($_GET['error'], 'Password harus kombinasi huruf dan angka dan minimal 6 karakter!') !== false) : ?>
                    <div class="error-message">
                        <p style='color:red;'><?php echo $_GET['error']; ?></p>
                    </div>
                <?php endif; ?>

                <div class="btn">
                    <button type="submit" class="button1">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sign Up&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </button>
                </div>
                <div class="btn2">
                    <button type="button" class="button3" onclick="location.href='login.php'">Back</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>