<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}
$user = $_SESSION["user"];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($user["name"]); ?>!</h2>
        <p>You are now logged into the Student Portal.</p>
        <a href="logout.php" class="btn">Logout</a>
    </div>
</body>

</html>