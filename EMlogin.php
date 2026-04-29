<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Log-in</title>

<link href="<?= base_url()?>public/css/style.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

<div class="bg-white p-8 rounded-xl shadow-md w-full max-w-sm">

<!-- Notification -->
<div id="notif" class="hidden text-sm text-center mb-3 p-2 rounded"></div>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if($_SERVER['REQUEST_METHOD'] == "POST"){

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    try {
        $rows = db()->table('users')->where('email', $email)->get_all();

        if(!empty($rows)){
            $user = $rows[0];

            if(password_verify($password, $user['password'])){
                $_SESSION['user'] = $user;
                echo "<script>window.onload = () => showNotif('Login successful!', 'success'); setTimeout(()=> window.location.href='".url('/EMdash')."',1500);</script>";
            } else {
                echo "<script>window.onload = () => showNotif('Wrong password!', 'error');</script>";
            }

        } else {
            echo "<script>window.onload = () => showNotif('User not found!', 'error');</script>";
        }

    } catch (Exception $e) {
        echo "<script>window.onload = () => showNotif('Database Error', 'error');</script>";
    }
}
?>

<h1 class="text-2xl font-bold text-center mb-6">Login</h1>

<form method="post" class="space-y-4" onsubmit="return validateLogin()">

    <div>
        <label class="block text-sm font-medium">Email</label>
        <input type="text" id="email" name="email"
            value="<?= esc($_POST['email'] ?? '') ?>"
            class="w-full mt-1 p-2 border rounded-lg">
    </div>

    <div>
        <label class="block text-sm font-medium">Password</label>
        <input type="password" id="password" name="password"
            class="w-full mt-1 p-2 border rounded-lg">

        <!-- Show/Hide -->
        <div class="text-sm mt-1">
            <input type="checkbox" onclick="togglePassword()"> Show Password
        </div>
    </div>

    <button type="submit"
        class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition">
        Login
    </button>

</form>

<p class="text-center text-sm mt-4">
    <a href="<?= url('/EMsign') ?>" class="text-blue-500 hover:underline">
        Create Account
    </a>
</p>

</div>

<script>
// Notification function
function showNotif(message, type){
    const notif = document.getElementById("notif");
    notif.innerText = message;
    notif.className = "text-sm text-center mb-3 p-2 rounded";

    if(type === "success"){
        notif.classList.add("bg-green-100","text-green-700");
    } else {
        notif.classList.add("bg-red-100","text-red-700");
    }

    notif.classList.remove("hidden");

    setTimeout(() => {
        notif.classList.add("hidden");
    }, 3000);
}

// Validation
function validateLogin(){
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();

    if(email === "" || password === ""){
        showNotif("All fields are required.", "error");
        return false;
    }

    const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
    if(!email.match(emailPattern)){
        showNotif("Invalid email format.", "error");
        return false;
    }

    if(password.length < 6){
        showNotif("Password must be at least 6 characters.", "error");
        return false;
    }

    return true;
}

// Show/Hide Password
function togglePassword(){
    const pass = document.getElementById("password");
    pass.type = pass.type === "password" ? "text" : "password";
}
</script>

</body>
</html>