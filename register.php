<?php
session_start();

$user_file = 'users.txt';
function read_users($file) {
    $users = file_get_contents($file);
    return $users ? unserialize($users) : [];
}
function save_users($file, $users) {
    file_put_contents($file, serialize($users));
}

// handle regist
if (isset($_POST['register'])) {
    $username = $_POST['username']; // Note: ga ada enkripsi, sangat tidak aman! awokwowkk
    $password = $_POST['password']; // Note: ga ada enkripsi, sangat tidak aman! awokwowkk
    $users = read_users($user_file);

    // validate user tidak mengandung unsur perhekean 
    if (!isset($users[$username]) && !strpos($username, ' ') && strlen($password) >= 4 && !ctype_space($password)) {
        $users[$username] = ['password' => $password, 'profile' => ''];
        save_users($user_file, $users);
        $_SESSION['username'] = $username;
        $_SESSION['message'] = "Registrasi berhasil!";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['message'] = "Username sudah terpakai atau sandi tidak valid!";
    }
}

// pros status mesage
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
unset($_SESSION['message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registrasi - Simple Chat</title>
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
    <h2>Registrasi</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="register">Register</button>
    </form>
    <div class="message"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div>
    <p>Sudah punya akun? <a href="login.php">Login disini</a></p>
</div>
</body>
</html>
