<?php
session_start();
$conn = new mysqli("localhost", "root", "", "mandem");

if ($conn->connect_error) {
    die("Database connection failed");
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            $_SESSION["admin"] = $username;
            header("Location: dashboard.php");
            exit;
        }
    }

    $error = "Invalid login details";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <style>
        body {
            background: #f3eee9;
            font-family: Arial, sans-serif;
        }
        .login-box {
            max-width: 380px;
            margin: 120px auto;
            background: #fffaf6;
            padding: 30px;
            border-radius: 10px;
            border-top: 5px solid #7b2d2d;
        }
        h2 {
            text-align: center;
            color: #7b2d2d;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-top: 15px;
        }
        button {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            background: #7b2d2d;
            color: #fff;
            border: none;
        }
        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Admin Login</h2>

    <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
