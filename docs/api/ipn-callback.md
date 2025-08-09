# IPN (Instant Payment Notification) Callback

UnelmaPay sends Instant Payment Notifications (IPN) to your server to notify you about payment events. This document explains how to implement and verify IPN callbacks.

## IPN Workflow

1. Customer completes payment
2. UnelmaPay sends a POST request to your IPN URL
3. Your server validates the request
4. Your server processes the payment notification
5. Your server returns a 200 OK response

## IPN Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `total` | decimal | Total amount paid |
| `date` | string | Transaction date (YYYYMMDD) |
| `id_transfer` | string | Unique transaction ID |
| `hash` | string | Verification hash |
| `custom` | string | Your order/reference ID |
| `item_name` | string | Product/service name |
| `currency` | string | Currency code used |
| `status` | string | Payment status (e.g., "completed") |

## Verification Process

1. **Receive the IPN** - Your server receives a POST request
2. **Extract Parameters** - Get all POST parameters
3. **Verify Hash** - Recalculate and compare the hash
4. **Process Payment** - Update your database/records
5. **Send Response** - Return HTTP 200 OK

## PHP Implementation Example

```php
<?php
// Configuration
$logFile = __DIR__ . '/ipn_log.txt';
$merchantPassword = 'YOUR_MERCHANT_PASSWORD';

// Log function
function logIPN($message, $data = null) {
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $message" . PHP_EOL;
    if ($data !== null) {
        $logMessage .= 'Data: ' . print_r($data, true) . PHP_EOL;
    }
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

// Log the request
logIPN('IPN Received', $_POST);

// Get parameters
$total = $_POST['total'] ?? '';
$date = $_POST['date'] ?? '';
$idTransfer = $_POST['id_transfer'] ?? '';
$receivedHash = $_POST['hash'] ?? '';
$custom = $_POST['custom'] ?? '';

// Calculate hash
$hashString = $total . ':' . $merchantPassword . ':' . $date . ':' . $idTransfer;
$calculatedHash = strtoupper(md5($hashString));

// Verify hash
if ($receivedHash === $calculatedHash) {
    // Payment is verified
    logIPN("Payment verified", [
        'transaction_id' => $idTransfer,
        'order_id' => $custom,
        'amount' => $total,
        'status' => 'verified'
    ]);
    
    // TODO: Update your database here
    // Example: mark order as paid
    
    // Send success response
    header('HTTP/1.1 200 OK');
    echo "IPN Received and Verified";
} else {
    // Invalid hash
    logIPN("Invalid hash", [
        'received_hash' => $receivedHash,
        'calculated_hash' => $calculatedHash
    ]);
    header('HTTP/1.1 400 Bad Request');
    echo "Invalid IPN";
}
```

## Testing IPN

1. **Test Endpoint**: `https://dev.unelmapay.com/sci/form`
2. **Test Merchant ID**: Use your test merchant ID
3. **Test Callback**: Ensure your IPN handler is accessible via HTTPS

## Best Practices

1. **Always verify the hash** - Never process payments without hash verification
2. **Handle duplicates** - Implement idempotency in your IPN handler
3. **Log everything** - Maintain detailed logs for debugging
4. **Use HTTPS** - Ensure your IPN endpoint is secure
5. **Set timeouts** - Configure appropriate timeouts for IPN processing

## Troubleshooting

- **No IPN Received**: Check your server's error logs and firewall settings
- **Invalid Hash**: Verify your merchant password and hash calculation
- **Timeout Issues**: Ensure your server responds within 30 seconds
