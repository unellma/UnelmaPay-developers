<?php
// Log failed payment
$logFile = __DIR__ . '/ipn_log.txt';
$timestamp = date('Y-m-d H:i:s');
$logMessage = "[$timestamp] FAIL: Payment failed. " . print_r($_GET, true) . "\n";
file_put_contents($logFile, $logMessage, FILE_APPEND);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment Failed</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
        .error { color: #f44336; font-size: 24px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="error">❌ Payment Failed</div>
    <p>There was an issue processing your payment.</p>
    <p><a href="/local_test/index.html">Return to test page</a></p>
    <hr>
    <h3>Debug Information:</h3>
    <pre><?php echo htmlspecialchars(print_r($_GET, true)); ?></pre>
</body>
</html>
