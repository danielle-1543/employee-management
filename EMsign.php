<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
<link href="<?= base_url()?>public/css/style.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<?php 
$error = "";

if($_SERVER['REQUEST_METHOD'] == "POST"){
    try {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $confirm = trim($_POST['confirm_password'] ?? '');

        if(empty($name) || empty($email) || empty($password) || empty($confirm)){
            throw new Exception("All fields are required.");
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            throw new Exception("Invalid email format.");
        }

        if($password !== $confirm){
            throw new Exception("Passwords do not match.");
        }

        // ✅ UNIQUE EMAIL CHECK
        $existing = db()->table('users')->where('email', $email)->get_all();

        if(!empty($existing)){
            throw new Exception("Email already exists.");
        }

        $data = [
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ];

        $res = db()->table('users')->insert($data); 

        if(!$res){
            throw new Exception("Failed to save data.");
        }

        echo "<script>
            window.onload = () => {
                showNotif('Account created successfully!', 'success');
                setTimeout(()=> window.location.href='".url('/EMlogin')."',1500);
            }
        </script>";

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">

    <!-- Notification -->
    <div id="notif" class="hidden text-sm text-center mb-3 p-2 rounded"></div>

    <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">Admin Sign Up</h1>

    <?php if(!empty($error)): ?>
        <script>
            window.onload = () => showNotif("<?= htmlspecialchars($error); ?>", "error");
        </script>
    <?php endif; ?>

    <form method="post" class="space-y-4" onsubmit="return validateSignup()">

        <div>
            <label class="block text-gray-600 mb-1">Name</label>
            <input 
                type="text" 
                id="name"
                name="name" 
                class="w-full border border-gray-300 p-2 rounded-lg"
            >
        </div>

        <div>
            <label class="block text-gray-600 mb-1">Email</label>
            <input 
                type="email" 
                id="email"
                name="email" 
                class="w-full border border-gray-300 p-2 rounded-lg"
            >
        </div>

        <div>
            <label class="block text-gray-600 mb-1">Password</label>
            <input 
                type="password" 
                id="password"
                name="password" 
                class="w-full border border-gray-300 p-2 rounded-lg"
            >
        </div>

        <div>
            <label class="block text-gray-600 mb-1">Confirm Password</label>
            <input 
                type="password" 
                id="confirm_password"
                name="confirm_password" 
                class="w-full border border-gray-300 p-2 rounded-lg"
            >

            <!-- Show/Hide -->
            <div class="text-sm mt-1">
                <input type="checkbox" onclick="togglePassword()"> Show Passwords
            </div>
        </div>

        <button 
            type="submit" 
            class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition"
        >
            Sign Up
        </button>
    </form>

    <p class="text-sm text-center mt-4 text-gray-600">
        Already registered admin?
        <a href="<?php echo url('/EMlogin'); ?>" class="text-blue-500 hover:underline">Log In</a>
    </p>
</div>

<script>
// Notification
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
function validateSignup(){
    const name = document.getElementById("name").value.trim();
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();
    const confirm = document.getElementById("confirm_password").value.trim();

    if(name === "" || email === "" || password === "" || confirm === ""){
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

    if(password !== confirm){
        showNotif("Passwords do not match.", "error");
        return false;
    }

    return true;
}

// Show/Hide Password
function togglePassword(){
    const pass = document.getElementById("password");
    const confirm = document.getElementById("confirm_password");

    const type = pass.type === "password" ? "text" : "password";
    pass.type = type;
    confirm.type = type;
}
</script>

</body>
</html>