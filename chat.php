<?php
session_start();

$user_file = 'users.txt';
$chat_file = 'chat.txt';

function read_users($file) {
    $users = file_get_contents($file);
    return $users ? unserialize($users) : [];
}

function save_users($file, $users) {
    file_put_contents($file, serialize($users));
}

function read_chat($file) {
    return file_get_contents($file);
}

function save_chat($file, $message) {
    file_put_contents($file, $message, FILE_APPEND);
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
unset($_SESSION['message']);

if (!isset($_SESSION['username'])) {
    $_SESSION['message'] = "Silakan login terlebih dahulu!";
    header("Location: login.php");
    exit();
}

if (isset($_POST['send'])) {
    $username = htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');
    $message = htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8');
    $profile_picture = isset($_SESSION['profile_picture']) ? htmlspecialchars($_SESSION['profile_picture'], ENT_QUOTES, 'UTF-8') : '';
    $formatted_message = "<div class='message-container'>";
    if (!empty($profile_picture)) {
        $formatted_message .= "<img src='$profile_picture' class='profile-picture'>";
    }
    $formatted_message .= "<div class='message-content'><strong>$username</strong>: $message</div></div>";
    save_chat($chat_file, $formatted_message);
    header("Location: chat.php");
    exit();
}

$chat_content = read_chat($chat_file);

$users = read_users($user_file);
$profile_picture = isset($users[$_SESSION['username']]['profile_picture']) ? htmlspecialchars($users[$_SESSION['username']]['profile_picture'], ENT_QUOTES, 'UTF-8') : '';
$_SESSION['profile_picture'] = $profile_picture;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Simple - Chat Group</title>
<meta name="keywords" content="Simple - Chat Group by Collee & TLS123">
<style>
    body { font-family: Arial, sans-serif; background-color: #333; color: #fff; }
    .container { width: 800px; margin: auto; }
    #chat-box { border: 1px solid #ccc; padding: 10px; height: 400px; overflow-y: scroll; background-color: #222; }
    #chat-box p { margin: 0; }
    .message-container { display: flex; margin-bottom: 10px; }
    .profile-picture { width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; }
    .message-content { background-color: #444; padding: 10px; border-radius: 5px; }
    form { margin-top: 20px; }
    input[type="text"], button { padding: 10px; margin-top: 5px; }
    input[type="text"] { width: 70%; }
    button { width: 28%; }
    .menu { display: flex; justify-content: space-between; align-items: center; position: relative; }
    .menu h2 { margin: 0; }
    .menu-toggle { cursor: pointer; font-size: 24px; }
    .dropdown-content { display: none; position: absolute; right: 0; background-color: #333; min-width: 160px; z-index: 1; }
    .dropdown-content a { color: #fff; padding: 12px 16px; text-decoration: none; display: block; }
    .dropdown-content a:hover { background-color: #555; }
    .dropdown:hover .dropdown-content { display: block; }

    /* Media query for mobile devices */
    @media (max-width: 600px) {
        body { font-size: 18px; }
        .container { width: 100%; padding: 10px; }
        #chat-box { height: 300px; }
        .profile-picture { width: 50px; height: 50px; }
        .message-content { padding: 15px; }
        input[type="text"] { width: 65%; }
        button { width: 33%; }
        h2 { font-size: 24px; }
        .menu { flex-direction: row; justify-content: space-between; align-items: center; }
        .menu-toggle { font-size: 24px; }
    }
</style>
</head>
<body>
<div class="container">
    <div class="menu">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>!</h2>
        <div class="menu-toggle dropdown">
            <span>&#9776;</span>
            <div class="dropdown-content">
                <a href="?logout">Logout</a>
                <a href="profile.php">Profile</a>
            </div>
        </div>
    </div>
    <div id="chat-box">
        <?php echo $chat_content; ?>
    </div>
    <form method="post">
        <div class="message-container">
            <?php if (!empty($_SESSION['profile_picture'])): ?>
            <img src="<?php echo $_SESSION['profile_picture']; ?>" class="profile-picture">
            <?php endif; ?>
            <input type="text" name="message" placeholder="Type your message here..." required>
            <button type="submit" name="send">Send</button>
        </div>
    </form>
    <div class="message"><?php echo !empty($message) ? htmlspecialchars($message, ENT_QUOTES, 'UTF-8') : ''; ?></div>
</div>
</body>
</html>
<script>
    function scrollToBottom() {
        var chatBox = document.getElementById("chat-box");
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    window.onload = function() {
        scrollToBottom();
    };
</script>
