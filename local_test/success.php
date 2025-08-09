<?php
// Log successful payment
$logFile = __DIR__ . '/ipn_log.txt';
$timestamp = date('Y-m-d H:i:s');
$logMessage = "[$timestamp] SUCCESS: Payment completed successfully. " . print_r($_GET, true) . "\n";
file_put_contents($logFile, $logMessage, FILE_APPEND);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment Successful</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
        .success { color: #4CAF50; font-size: 24px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="success">✅ Payment Successful!</div>
    <p>Your payment has been processed successfully.</p>
    <p><a href="/local_test/index.html">Return to test page</a></p>
    <hr>
    <h3>Debug Information:</h3>
    <pre><?php echo htmlspecialchars(print_r($_GET, true)); ?></pre>
</body>
</html>
