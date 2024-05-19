<?php
session_start();

$user_file = 'users.txt';

// read data pengguna
function read_users($file) {
    $users = file_get_contents($file);
    return $users ? unserialize($users) : [];
}

// handlelogin
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $users = read_users($user_file);
    if (isset($users[$username]) && $users[$username]['password'] === $password) {
        $_SESSION['username'] = $username;
        $_SESSION['message'] = "Login berhasil!";
        header("Location: chat.php");
        exit();
    } else {
        $_SESSION['message'] = "Username atau Password salah!";
    }
}

// proses mesage status
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
unset($_SESSION['message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Simple Chat</title>
<meta name="keywords" content="Simple - Chat Group by Collee & TLS123">
<style>
    body { font-family: Arial, sans-serif; background-color: #333; color: #fff; }
    form { margin-top: 20px; }
    input, button { padding: 10px; margin-top: 5px; }
    .container { width: 300px; margin: auto; }
    .error { color: red; }
    .message { color: yellow; }
</style>
</head>
<body>
<div class="container">
    <h2>Login</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
    <div class="message"><?php echo $message; ?></div>
    <p>Belum punya akun? <a href="register.php">Registrasi disini</a></p>
</div>
</body>
</html>
