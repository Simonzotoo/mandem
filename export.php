<?php
session_start();

// Protect export
if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "mandem");

if ($conn->connect_error) {
    die("Database connection failed");
}

// Fetch RSVP data (explicit date formatting)
$result = $conn->query("
    SELECT 
        full_name,
        phone,
        email,
        attendance,
        message,
        DATE_FORMAT(created_at, '%d %M %Y %H:%i') AS submitted_date
    FROM rsvps
    ORDER BY created_at DESC
");

// Force Excel download
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=wedding_rsvps.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Column headers
echo "Full Name\tPhone\tEmail\tAttendance\tMessage\tDate Submitted\n";

// Rows
while ($row = $result->fetch_assoc()) {
    echo
        $row["full_name"] . "\t" .
        $row["phone"] . "\t" .
        $row["email"] . "\t" .
        $row["attendance"] . "\t" .
        str_replace(["\t", "\n"], " ", $row["message"]) . "\t" .
        $row["submitted_date"] . "\n";
}

$conn->close();
exit;