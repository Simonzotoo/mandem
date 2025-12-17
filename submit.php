<?php
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$pass = "";
$db   = "mandem";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode([
        "status" => "error",
        "message" => "Database connection failed"
    ]);
    exit;
}

$full_name  = trim($_POST['full_name']);
$phone      = trim($_POST['phone']);
$email      = trim($_POST['email'] ?? '');
$attendance = $_POST['attendance'];
$message    = trim($_POST['message'] ?? '');

// Prevent duplicate RSVP (phone-based)
$check = $conn->prepare("SELECT id FROM rsvps WHERE phone = ?");
$check->bind_param("s", $phone);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode([
        "status" => "info",
        "message" => "You have already submitted your RSVP. Thank you."
    ]);
    exit;
}
$check->close();

// Insert RSVP (no guests)
$stmt = $conn->prepare("
    INSERT INTO rsvps (full_name, phone, email, attendance, message)
    VALUES (?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "sssss",
    $full_name,
    $phone,
    $email,
    $attendance,
    $message
);

if ($stmt->execute()) {
    $responseMessage = $attendance === "Yes"
        ? "We are excited to celebrate with you."
        : "Thank you for letting us know.";

    echo json_encode([
        "status" => "success",
        "name" => $full_name,
        "message" => $responseMessage
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Unable to save your response. Please try again."
    ]);
}

$stmt->close();
$conn->close();
