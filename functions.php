<?php

$user_file = 'users.txt';

function read_users($file) {
    $users = file_get_contents($file);
    return $users ? unserialize($users) : [];
}

function save_users($file, $users) {
    file_put_contents($file, serialize($users));
}

function register_user($username, $password) {
    global $user_file;
    $users = read_users($user_file);
    if (!isset($users[$username])) {
        $users[$username] = ['password' => $password, 'profile' => ''];
        save_users($user_file, $users);
        $_SESSION['username'] = $username;
        $_SESSION['message'] = "Registrasi berhasil!";
        header("Location: chat.php");
        exit();
    } else {
        $_SESSION['message'] = "Username sudah terpakai!";
    }
}

function login_user($username, $password) {
    global $user_file;
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

function edit_profile($username, $profile) {
    global $user_file;
    $users = read_users($user_file);
    if (isset($users[$username])) {
        $users[$username]['profile'] = $profile;
        save_users($user_file, $users);
        $_SESSION['message'] = "Profil berhasil diperbarui!";
    }
}

?>
