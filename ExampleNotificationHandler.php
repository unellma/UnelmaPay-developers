<?php
// Merchant password
$merchant_password = "t43t43t34t43t34t6545845";

// Check if required POST variables exist
if (!isset($_POST['hash'], $_POST['total'], $_POST['date'], $_POST['id_transfer'])) {
    echo "Error: Missing required data!";
    exit;
}

// Transaction info
$amount = $_POST['amount'] ?? '';
$fee = $_POST['fee'] ?? '';
$total = $_POST['total'] ?? '';
$currency = $_POST['currency'] ?? '';
$payer = $_POST['payer'] ?? '';
$receiver = $_POST['receiver'] ?? '';
$status = $_POST['status'] ?? '';
$date = $_POST['date'] ?? '';
$id_transfer = $_POST['id_transfer'] ?? '';
// Merchant info
$merchant_name = $_POST['merchant_name'] ?? '';
$merchant_id = $_POST['merchant_id'] ?? '';
$balance = $_POST['balance'] ?? '';
// Purchase Information
$item_name = $_POST['item_name'] ?? '';
$custom = $_POST['custom'] ?? '';
// Verification hash
$hash = $_POST['hash'];

// Create hash for verification
$hash_string = $total . ':' . $merchant_password . ':' . $date . ':' . $id_transfer;
$user_hash = strtoupper(md5($hash_string));

// Verify transaction
if ($hash === $user_hash) {
    echo "Confirmed!";
    // Add logic to handle successful transaction (e.g., update database)
} else {
    echo "Disabled!";
    // Add logic to handle failed verification
}
?>
