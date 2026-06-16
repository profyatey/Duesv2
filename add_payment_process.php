<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$fgci_id = intval($_POST['fgci_id'] ?? 0);
$amount  = floatval($_POST['amount'] ?? 0);

if ($fgci_id <= 0 || $amount <= 0) {
    header("Location: index.php?status=error&msg=Invalid member or amount");
    exit;
}

/*
|--------------------------------------------------------------------------
| GET MEMBER DETAILS
|--------------------------------------------------------------------------
*/
$member_stmt = $conn->prepare("
    SELECT id, name, phone
    FROM dues
    WHERE id = ?
");

$member_stmt->bind_param("i", $fgci_id);
$member_stmt->execute();

$result = $member_stmt->get_result();

if ($result->num_rows === 0) {
    $member_stmt->close();
    $conn->close();

    header("Location: index.php?status=error&msg=Member not found");
    exit;
}

$member = $result->fetch_assoc();

$member_name = $member['name'];
$phone       = trim($member['phone']);

$member_stmt->close();

/*
|--------------------------------------------------------------------------
| RECORD PAYMENT
|--------------------------------------------------------------------------
*/
$payment_stmt = $conn->prepare("
    INSERT INTO payments (fgci_id, amount)
    VALUES (?, ?)
");

$payment_stmt->bind_param("id", $fgci_id, $amount);

if (!$payment_stmt->execute()) {

    $payment_stmt->close();
    $conn->close();

    header("Location: index.php?status=error&msg=Failed to record payment");
    exit;
}

$payment_stmt->close();

/*
|--------------------------------------------------------------------------
| FORMAT PHONE NUMBER
|--------------------------------------------------------------------------
| Converts:
| 0241234567 -> 233241234567
|--------------------------------------------------------------------------
*/

$phone = preg_replace('/\D/', '', $phone);

if (substr($phone, 0, 1) == "0") {
    $phone = "233" . substr($phone, 1);
}

/*
|--------------------------------------------------------------------------
| SEND SMS VIA ARKESEL
|--------------------------------------------------------------------------
*/

$apiKey = "XXXXXXXXXXXXXXXXXXXXXX";

$message = "Dear {$member_name}, your payment of GHS " .
number_format($amount, 2) .
" has been received successfully. Thank you.";

$data = [
    "sender" => "Study Group",
    "message" => $message,
    "recipients" => [$phone]
];

$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => "https://sms.arkesel.com/api/v2/sms/send",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        "api-key: {$apiKey}",
        "Content-Type: application/json"
    ],
    CURLOPT_POSTFIELDS => json_encode($data)
]);

$response = curl_exec($ch);
$curl_error = curl_error($ch);

curl_close($ch);

/*
|--------------------------------------------------------------------------
| REDIRECT
|--------------------------------------------------------------------------
*/

if ($curl_error) {
    header("Location: index.php?status=success&msg=Payment recorded but SMS failed");
} else {
    header("Location: index.php?status=success&msg=Payment recorded and SMS sent successfully");
}

$conn->close();
exit;
?>
