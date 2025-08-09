<?php
// Configuration
$logFile = __DIR__ . '/ipn_log.txt';
$merchant_password = "t43t43t34t43t34t6545845"; // This should be your actual merchant password

// Enable all error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Function to log messages with detailed information
function logIPN($message, $data = null) {
    global $logFile;
    
    // Create log directory if it doesn't exist
    $logDir = dirname($logFile);
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $remoteAddr = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'CLI';
    $requestUri = $_SERVER['REQUEST_URI'] ?? '';
    
    $logMessage = "[$timestamp] [IP: $remoteAddr] [$requestMethod $requestUri] $message" . PHP_EOL;
    
    if ($data !== null) {
        if (is_array($data) || is_object($data)) {
            $logMessage .= 'Data: ' . print_r($data, true) . PHP_EOL;
        } else {
            $logMessage .= 'Data: ' . $data . PHP_EOL;
        }
    }
    
    file_put_contents($logFile, $logMessage, FILE_APPEND);
    error_log(rtrim($logMessage));
    
    return $logMessage;
}

// Log the start of IPN processing
logIPN("===== IPN PROCESSING STARTED =====");

// Log server and request information
logIPN("Server Info", [
    'PHP_SELF' => $_SERVER['PHP_SELF'] ?? '',
    'SERVER_PROTOCOL' => $_SERVER['SERVER_PROTOCOL'] ?? '',
    'REQUEST_METHOD' => $_SERVER['REQUEST_METHOD'] ?? '',
    'QUERY_STRING' => $_SERVER['QUERY_STRING'] ?? '',
    'HTTP_USER_AGENT' => $_SERVER['HTTP_USER_AGENT'] ?? '',
    'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'] ?? ''
]);

// Log all received data
$requestData = [
    'GET' => $_GET,
    'POST' => $_POST,
    'FILES' => $_FILES,
    'INPUT' => file_get_contents('php://input')
];
logIPN("Received Request Data", $requestData);

try {
    // Check if this is a test request
    $isTest = isset($_GET['test']) || (isset($_POST['test']) && $_POST['test'] == 1);
    
    if ($isTest) {
        logIPN("Test request received");
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'test',
            'message' => 'Test IPN received successfully',
            'data' => $requestData
        ]);
        exit;
    }

    // Get required parameters
    $requiredParams = ['total', 'date', 'id_transfer', 'hash'];
    $missingParams = [];
    $params = [];
    
    foreach ($requiredParams as $param) {
        if (empty($_POST[$param])) {
            $missingParams[] = $param;
        } else {
            $params[$param] = $_POST[$param];
        }
    }
    
    if (!empty($missingParams)) {
        $errorMsg = "Missing required parameters: " . implode(', ', $missingParams);
        logIPN("Validation Error", $errorMsg);
        header('HTTP/1.1 400 Bad Request');
        echo $errorMsg;
        exit;
    }
    
    // Extract parameters
    $total = $params['total'];
    $date = $params['date'];
    $id_transfer = $params['id_transfer'];
    $received_hash = $params['hash'];
    
    // Verify the hash
    $hash_string = $total . ':' . $merchant_password . ':' . $date . ':' . $id_transfer;
    $calculated_hash = strtoupper(md5($hash_string));
    
    logIPN("Hash Verification", [
        'hash_string' => $hash_string,
        'received_hash' => $received_hash,
        'calculated_hash' => $calculated_hash,
        'match' => ($received_hash === $calculated_hash) ? 'YES' : 'NO'
    ]);
    
    if ($received_hash === $calculated_hash) {
        $status = "CONFIRMED";
        logIPN("Payment confirmed", [
            'transaction_id' => $id_transfer,
            'amount' => $total,
            'date' => $date
        ]);
        
        // Process the payment here (update your database, etc.)
        // Example:
        // updateOrderStatus($custom, 'completed');
        
        // Log success
        logIPN("Payment processed successfully");
        
        // Send success response
        header('HTTP/1.1 200 OK');
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'message' => 'IPN Received and Verified',
            'transaction_id' => $id_transfer
        ]);
    } else {
        $status = "INVALID_HASH";
        $errorMsg = "Invalid hash for transaction ID: $id_transfer";
        logIPN("Security Alert: $errorMsg");
        
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => $errorMsg,
            'details' => [
                'received_hash' => $received_hash,
                'calculated_hash' => $calculated_hash
            ]
        ]);
    }
    
} catch (Exception $e) {
    $errorMsg = "Exception: " . $e->getMessage();
    logIPN("Error", [
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ]);
    
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'An error occurred while processing the IPN',
        'error' => $errorMsg
    ]);
}

// Log the end of IPN processing
logIPN("===== IPN PROCESSING COMPLETED =====\n");
?>
