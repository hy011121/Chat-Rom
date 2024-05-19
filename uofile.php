<?php
session_start();

if (!isset($_SESSION['username'])) {
    $_SESSION['message'] = "Silakan login terlebih dahulu!";
    header("Location: login.php");
    exit();
}
$user_file = 'users.txt';
function read_users($file) {
    $users = file_get_contents($file);
    return $users ? unserialize($users) : [];
}
function save_users($file, $users) {
    file_put_contents($file, serialize($users));
}
// read data usr
$users = read_users($user_file);
$username = $_SESSION['username'];
$user_data = isset($users[$username]) ? $users[$username] : null;
// handle upload profile
if (isset($_POST['upload_picture'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    // Chk if image file is a actual image or fake image
    $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
    if($check !== false) {
        // Check file size
        if ($_FILES["profile_picture"]["size"] > 500000) {
            $_SESSION['message'] = "Maaf, file terlalu besar.";
            $uploadOk = 0;
        } 
        // allow certain file formats
        elseif($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            $_SESSION['message'] = "Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.";
            $uploadOk = 0;
        } 
        else {
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                $users[$username]['profile_picture'] = $target_file;
                save_users($user_file, $users);
                $_SESSION['message'] = "Profil berhasil diperbarui!";
            } else {
                $_SESSION['message'] = "Maaf, terjadi kesalahan saat mengunggah file.";
            }
        }
    } else {
        $_SESSION['message'] = "File bukan gambar.";
        $uploadOk = 0;
    }
    header("Location: profile.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Upload Picture - Simple Chat</title>
<style>
    body { font-family: Arial, sans-serif; background-color: #333; color: #fff; }
    .container { width: 500px; margin: auto; }
    form { margin-top: 20px; }
    input, textarea, button { padding: 10px; margin-top: 5px; }
    input[type="file"] { margin-top: 10px; }
    button { width: 100%; }
    .logout { float: right; }
    .logout a { color: #fff; text-decoration: none; }
</style>
</head>
<body>
<div class="container">
    <h2>Upload Profile Picture</h2>
    <p class="logout"><a href="profile.php">Back to Profile</a></p>
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="profile_picture" id="profile_picture" accept="image/*">
        <button type="submit" name="upload_picture">Upload Picture</button>
    </form>
    <div class="message"><?php echo isset($_SESSION['message']) ? $_SESSION['message'] : ''; ?></div>
</div>
</body>
</html>
