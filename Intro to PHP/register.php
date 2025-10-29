<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (!$name || !$email || !$password) {
        $error = "All fields are required.";
    } else {
        $usersFile = "users.json";
        $users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];
        foreach ($users as $user) {
            if ($user["email"] === $email) {
                $error = "Email already registered.";
                break;
            }
        }
        if (!isset($error)) {
            $users[] = ["name" => $name, "email" => $email, "password" => password_hash($password, PASSWORD_DEFAULT)];
            file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
            header("Location: login.php?registered=true");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>Create an Account</h2>
        <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST" action="">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button class="btn" type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>

</html>