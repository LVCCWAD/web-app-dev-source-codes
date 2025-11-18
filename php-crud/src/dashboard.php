<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}
$user = $_SESSION["user"];

require_once __DIR__ . "/db.php";
$db = get_db();

// Simple routing by ?action=
$action = isset($_GET["action"]) ? $_GET["action"] : "list";
$msg = isset($_GET["msg"]) ? $_GET["msg"] : "";
$error = "";

// Handle create (store)
if ($_SERVER["REQUEST_METHOD"] === "POST" && $action === "store") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $course = trim($_POST["course"] ?? "");
    $age = (int)($_POST["age"] ?? 0);
    if ($name && $email && $course && $age > 0) {
        $stmt = $db->prepare("INSERT INTO students (name, email, course, age) VALUES (?, ?, ?, ?)");
        $stmt->bindValue(1, $name, SQLITE3_TEXT);
        $stmt->bindValue(2, $email, SQLITE3_TEXT);
        $stmt->bindValue(3, $course, SQLITE3_TEXT);
        $stmt->bindValue(4, $age, SQLITE3_INTEGER);
        $stmt->execute();
        header("Location: dashboard.php?msg=Student+added");
        exit;
    } else {
        $error = "Please fill in all fields correctly.";
        $action = "create";
    }
}

// Handle update
if ($_SERVER["REQUEST_METHOD"] === "POST" && $action === "update") {
    $id = (int)($_POST["id"] ?? 0);
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $course = trim($_POST["course"] ?? "");
    $age = (int)($_POST["age"] ?? 0);
    if ($id > 0 && $name && $email && $course && $age > 0) {
        $stmt = $db->prepare("UPDATE students SET name = ?, email = ?, course = ?, age = ? WHERE id = ?");
        $stmt->bindValue(1, $name, SQLITE3_TEXT);
        $stmt->bindValue(2, $email, SQLITE3_TEXT);
        $stmt->bindValue(3, $course, SQLITE3_TEXT);
        $stmt->bindValue(4, $age, SQLITE3_INTEGER);
        $stmt->bindValue(5, $id, SQLITE3_INTEGER);
        $stmt->execute();
        header("Location: dashboard.php?msg=Student+updated");
        exit;
    } else {
        $error = "Please fill in all fields correctly.";
        $action = "edit";
        $_GET["id"] = (string)$id;
    }
}

// Handle delete (GET for teaching simplicity)
if ($action === "delete") {
    $id = (int)($_GET["id"] ?? 0);
    if ($id > 0) {
        $stmt = $db->prepare("DELETE FROM students WHERE id = ?");
        $stmt->bindValue(1, $id, SQLITE3_INTEGER);
        $stmt->execute();
        header("Location: dashboard.php?msg=Student+deleted");
        exit;
    } else {
        $msg = "Invalid student id.";
        $action = "list";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body class="layout-dashboard">
    <div class="page">
        <div class="appbar">
            <h2>Student Portal</h2>
            <div>
                <span style="margin-right: 12px;">Welcome, <?php echo htmlspecialchars($user["name"]); ?></span>
                <a href="logout.php" style="color: black;">Logout</a>
            </div>
        </div>
        <div class="main">
            <div class="sidebar">
                <h4>Menu</h4>
                <div><a href="dashboard.php">Dashboard</a></div>
                <div><a href="?action=create">Add Student</a></div>
            </div>
            <div class="content-card">
                <?php if ($msg): ?>
                    <div class="alert-success"><?php echo htmlspecialchars($msg); ?></div>
                <?php endif; ?>
                <?php if (!empty($error)): ?>
                    <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <?php if ($action === "create"): ?>
                    <h3>Add New Student</h3>
                    <form method="post" action="?action=store">
                        <input type="text" name="name" placeholder="Full Name" required>
                        <input type="email" name="email" placeholder="Email" required>
                        <input type="text" name="course" placeholder="Course" required>
                        <input type="number" name="age" placeholder="Age" min="1" required>
                        <button class="btn" type="submit">Save</button>
                        <a class="btn" href="dashboard.php">Cancel</a>
                    </form>
                    <?php elseif ($action === "edit"):
                    $id = (int)($_GET["id"] ?? 0);
                    $student = null;
                    if ($id > 0) {
                        $stmt = $db->prepare("SELECT * FROM students WHERE id = ?");
                        $stmt->bindValue(1, $id, SQLITE3_INTEGER);
                        $result = $stmt->execute();
                        $student = $result->fetchArray(SQLITE3_ASSOC);
                    }
                    if (!$student): ?>
                        <p>Student not found.</p>
                        <a class="btn" href="dashboard.php">Back</a>
                    <?php else: ?>
                        <h3>Edit Student</h3>
                        <form method="post" action="?action=update">
                            <input type="hidden" name="id" value="<?php echo (int)$student["id"]; ?>">
                            <input type="text" name="name" placeholder="Full Name" value="<?php echo htmlspecialchars($student["name"]); ?>" required>
                            <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($student["email"]); ?>" required>
                            <input type="text" name="course" placeholder="Course" value="<?php echo htmlspecialchars($student["course"]); ?>" required>
                            <input type="number" name="age" placeholder="Age" min="1" value="<?php echo (int)$student["age"]; ?>" required>
                            <button class="btn" type="submit">Update</button>
                            <a class="btn" href="dashboard.php">Cancel</a>
                        </form>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="content">
                        <div class="actions">
                            <a class="btn" href="?action=create">+ Add Student</a>
                        </div>
                        <h3>Students</h3>
                        <?php
                        $result = $db->query("SELECT * FROM students ORDER BY id DESC");
                        $students = [];
                        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                            $students[] = $row;
                        }
                        ?>
                        <?php if (empty($students)): ?>
                            <div class="empty-state">no students record</div>
                        <?php else: ?>
                            <div class="table-wrapper">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Course</th>
                                            <th>Age</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($students as $s): ?>
                                            <tr>
                                                <td><?php echo (int)$s["id"]; ?></td>
                                                <td><?php echo htmlspecialchars($s["name"]); ?></td>
                                                <td><?php echo htmlspecialchars($s["email"]); ?></td>
                                                <td><?php echo htmlspecialchars($s["course"]); ?></td>
                                                <td><?php echo (int)$s["age"]; ?></td>
                                                <td>
                                                    <a href="?action=edit&id=<?php echo (int)$s["id"]; ?>">Edit</a>
                                                    <a href="?action=delete&id=<?php echo (int)$s["id"]; ?>" onclick="return confirm('Delete this student?');">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>