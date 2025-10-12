<?php
session_start();
require_once 'koneksi.php';

// Jika user sudah login, redirect ke dashboard
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

$error = "";

// Proses login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = escape_string($_POST['username']);
    $password = $_POST['password'];
    
    // Query user dari database
    $query = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Verifikasi password
        // Untuk demo: gunakan plain text comparison
        // Untuk production: gunakan password_verify($password, $user['password'])
        if ($password === 'admin123' || password_verify($password, $user['password'])) {
            // Login berhasil
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['login_time'] = date('Y-m-d H:i:s');
            
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Username atau password salah!";
        }
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Nutribox</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Poppins:wght@600;800&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h1>Nutribox</h1>
            <p>Login ke Dashboard</p>
        </div>
        
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn-login">Login</button>
        </form>
        
        <div class="demo-info">
            <strong>Demo Account:</strong><br>
            Username: admin<br>
            Password: admin123
        </div>
        
        <div style="text-align: center; margin-top: 1rem;">
            <a href="index.php" style="color: #666; text-decoration: none;">← Kembali ke Beranda</a>
        </div>
    </div>
</body>
</html>