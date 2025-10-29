<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $usersFile = "users.json";
    $users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

    foreach ($users as $user) {
        if ($user["email"] === $email && password_verify($password, $user["password"])) {
            $_SESSION["user"] = $user;
            header("Location: dashboard.php");
            exit;
        }
    }
    $error = "Invalid email or password.";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>Login to Student Portal</h2>
        <?php if (isset($_GET["registered"])) echo "<p style='color:green;'>Registration successful! Please login.</p>"; ?>
        <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button class="btn" type="submit">Login</button>
        </form>
        <p>Don’t have an account? <a href="register.php">Register</a></p>
    </div>
</body>

</html>