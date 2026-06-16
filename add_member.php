<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if (empty($name) || empty($phone)) {
        header("Location: index.php?status=error&msg=Name+and+phone+are+required");
        exit;
    }

    // Validate phone: digits only, 10–15 chars
    if (!preg_match('/^\d{10,15}$/', $phone)) {
        header("Location: index.php?status=error&msg=Invalid+phone+number+format");
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO dues (name, phone) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $phone);

    if ($stmt->execute()) {
        header("Location: index.php?status=success&msg=Member+added+successfully");
    } else {
        // Duplicate phone check
        if ($conn->errno === 1062) {
            header("Location: index.php?status=error&msg=Phone+number+already+exists");
        } else {
            header("Location: index.php?status=error&msg=Failed+to+add+member");
        }
    }

    $stmt->close();
} else {
    header("Location: index.php");
}

$conn->close();
exit;
?>
