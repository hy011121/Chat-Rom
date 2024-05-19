<?php
session_start();
$user_file = 'users.txt';
// read data user
function read_users($file) {
    $users = file_get_contents($file);
    return $users ? unserialize($users) : [];
}
// save usr data
function save_users($file, $users) {
    file_put_contents($file, serialize($users));
}
// handlelogout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
// chk user log
if (!isset($_SESSION['username'])) {
    $_SESSION['message'] = "Silakan login terlebih dahulu!";
    header("Location: login.php");
    exit();
}
// read data user
$users = read_users($user_file);
$username = $_SESSION['username'];
$user_data = isset($users[$username]) ? $users[$username] : null;
if (isset($_POST['edit_profile'])) {
    $new_username = trim($_POST['new_username']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);
    $errors = [];
    if (!empty($new_username) && $new_username !== $username) {
        if (isset($users[$new_username])) {
            $errors[] = "Username sudah terpakai!";
        } else {
            $users[$new_username] = $users[$username];
            unset($users[$username]);
            $_SESSION['username'] = $new_username;
            $username = $new_username;
        }
    }
    if (!empty($new_password)) {
        if ($new_password === $confirm_password) {
            $users[$username]['password'] = $new_password;
        } else {
            $errors[] = "Password tidak cocok!";
        }
    }
    if (empty($errors)) {
        save_users($user_file, $users);
        $_SESSION['message'] = "Profile berhasil diperbarui!";
        header("Location: chat.php");
        exit();
    } else {
        $_SESSION['message'] = implode('<br>', $errors);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Profile - Simple Chat</title>
<meta name="keywords" content="Simple - Chat Group by Collee & TLS123">
<style>
    body { font-family: Arial, sans-serif; background-color: #333; color: #fff; }
    .container { width: 90%; max-width: 500px; margin: auto; }
    form { margin-top: 20px; }
    input, button { padding: 10px; margin-top: 5px; width: 100%; box-sizing: border-box; }
    .menu { display: flex; justify-content: space-between; align-items: center; position: relative; }
    .menu h2 { margin: 0; }
    .menu-toggle { cursor: pointer; font-size: 24px; }
    .dropdown-content { display: none; position: absolute; right: 0; background-color: #333; min-width: 160px; z-index: 1; }
    .dropdown-content a { color: #fff; padding: 12px 16px; text-decoration: none; display: block; }
    .dropdown-content a:hover { background-color: #555; }
    .dropdown:hover .dropdown-content { display: block; }
    .message { color: yellow; margin-top: 10px; }
    @media (max-width: 600px) {
        body { font-size: 18px; }
        .container { width: 95%; }
        input, button { padding: 15px; }
        .menu h2 { font-size: 24px; }
        .menu-toggle { font-size: 28px; }
    }
</style>
</head>
<body>
<div class="container">
    <div class="menu">
        <h2>Edit Profile</h2>
        <div class="menu-toggle dropdown">
            <span>&#9776;</span>
            <div class="dropdown-content">
                <a href="?logout">Logout</a>
                <a href="chat.php">Chat</a>
            </div>
        </div>
    </div>
    <form method="post">
        <label for="new_username">New Username:</label>
        <input type="text" name="new_username" id="new_username" placeholder="New Username" value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>">
        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" id="new_password" placeholder="New Password">
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password">
        <button type="submit" name="edit_profile">Save Profile</button>
    </form>
    <div class="message"><?php echo isset($_SESSION['message']) ? $_SESSION['message'] : ''; ?></div>
</div>
</body>
</html>
