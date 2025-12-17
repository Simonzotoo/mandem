<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "mandem");
$result = $conn->query("SELECT * FROM rsvps ORDER BY created_at DESC");

$total = $conn->query("SELECT COUNT(*) AS c FROM rsvps")->fetch_assoc()["c"];
$yes   = $conn->query("SELECT COUNT(*) AS c FROM rsvps WHERE attendance='Yes'")->fetch_assoc()["c"];
$no    = $conn->query("SELECT COUNT(*) AS c FROM rsvps WHERE attendance='No'")->fetch_assoc()["c"];
?>

<!DOCTYPE html>
<html>
<head>
    <title>RSVP Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3eee9;
        }
        .container {
            max-width: 1000px;
            margin: 40px auto;
            background: #fffaf6;
            padding: 25px;
            border-radius: 10px;
        }
        h2 {
            color: #7b2d2d;
        }
        .stats {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        .card {
            flex: 1;
            background: #ffffff;
            padding: 15px;
            border-left: 5px solid #7b2d2d;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #7b2d2d;
            color: #fff;
        }
        a {
            color: #7b2d2d;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Wedding RSVP Dashboard</h2>
    <div style="display:flex; justify-content: space-between; align-items:center;">
    <a href="logout.php">Logout</a>
    <a href="export.php" style="
        padding: 8px 14px;
        background: #7b2d2d;
        color: #fff;
        border-radius: 5px;
        text-decoration: none;
        font-size: 14px;
    ">
        Export to Excel
    </a>
</div>

    <div class="stats">
        <div class="card">Total Responses: <strong><?= $total ?></strong></div>
        <div class="card">Attending: <strong><?= $yes ?></strong></div>
        <div class="card">Not Attending: <strong><?= $no ?></strong></div>
    </div>

    <table>
        <tr>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Attendance</th>
            <th>Message</th>
            <th>Date</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row["full_name"]) ?></td>
            <td><?= htmlspecialchars($row["phone"]) ?></td>
            <td><?= htmlspecialchars($row["email"]) ?></td>
            <td><?= $row["attendance"] ?></td>
            <td><?= htmlspecialchars($row["message"]) ?></td>
            <td><?= $row["created_at"] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
