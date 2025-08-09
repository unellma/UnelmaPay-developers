# UnelmaPay Merchant Integration Guide

Thank you for choosing UnelmaPay for your payment processing needs. This guide will walk you through integrating our payment solution into your website.

## Table of Contents
1. [Integration Overview](#integration-overview)
2. [Payment Form Integration](#payment-form-integration)
3. [Required Fields & Validation](#required-fields--validation)
4. [Handling Callbacks](#handling-callbacks)
5. [Testing Procedures](#testing-procedures)
6. [Troubleshooting](#troubleshooting)

## Integration Overview

UnelmaPay provides a simple HTML form integration that can be easily added to your website. The payment flow is as follows:

1. Customer fills out payment form on your site
2. Form submits to UnelmaPay's payment gateway
3. Customer completes payment
4. UnelmaPay sends an IPN (Instant Payment Notification) to your server
5. Customer is redirected back to your success/failure page

### Local Development Setup

For local development and testing, use these endpoints:

| Environment | Payment Endpoint | IPN Callback URL |
|-------------|------------------|------------------|
| Production | `https://unelmapay.com.np/sci/form` | Your production server URL |
| Development | `https://dev.unelmapay.com/sci/form` | Your dev server URL |
| Local Test | `http://localhost:8088/sci/form` | `http://your-local-ip:8000/ipn_handler.php` |

**Note**: Replace `your-local-ip` with your machine's local IP address (e.g., 192.168.0.209) when testing from other devices.

## Payment Form Integration

### Basic Integration

Add this form to your website where you want to accept payments:

```html
<!-- For Production -->
<form method="POST" action="https://unelmapay.com.np/sci/form">
    <input type="hidden" name="merchant" value="YOUR_MERCHANT_ID">
    <input type="hidden" name="item_name" value="Product Name">
    <input type="hidden" name="amount" value="10.00">
    <input type="hidden" name="currency" value="debit_base">
    <input type="hidden" name="custom" value="ORDER_ID">
    <button type="submit">Pay Now</button>
</form>

<!-- For Local Testing -->
<!--
<form method="POST" action="http://localhost:8088/sci/form">
    <input type="hidden" name="merchant" value="7">
    <input type="hidden" name="item_name" value="Test Product">
    <input type="hidden" name="amount" value="2.00">
    <input type="hidden" name="currency" value="debit_base">
    <input type="hidden" name="custom" value="TEST_ORDER_123">
    <button type="submit">Test Payment</button>
</form>
-->

### Customized Form Example

```html
<!-- For Production -->
<form method="POST" action="https://unelmapay.com.np/sci/form" id="paymentForm">
    <div class="form-group">
        <label>Merchant ID:</label>
        <input type="text" name="merchant" value="YOUR_MERCHANT_ID" required>
    </div>
    
    <div class="form-group">
        <label>Item Name (3-100 chars):</label>
        <input type="text" name="item_name" value="Premium Service" minlength="3" maxlength="100" required>
    </div>
    
    <div class="form-group">
        <label>Amount (must be > 1):</label>
        <input type="number" name="amount" min="1.01" step="0.01" value="2.00" required>
    </div>
    
    <div class="form-group">
        <label>Currency:</label>
        <select name="currency" required>
            <option value="debit_base">Base Currency</option>
            <option value="debit_extra1">Extra 1</option>
            <option value="debit_extra2">Extra 2</option>
            <option value="debit_extra3">Extra 3</option>
            <option value="debit_extra4">Extra 4</option>
            <option value="debit_extra5">Extra 5</option>
        </select>
    </div>
    
    <div class="form-group">
        <label>Order/Reference ID:</label>
        <input type="text" name="custom" value="ORDER_123" required>
    </div>
    
    <button type="submit" class="btn-pay">Pay Now</button>
</form>
```

## Required Fields & Validation

### Required Form Fields

| Field    | Type   | Description | Validation |
|----------|--------|-------------|------------|
| merchant | string | Your merchant ID | Must be a valid, active merchant ID |
| item_name| string | Product/service name | 3-100 characters |
| amount   | number | Payment amount | Must be > 0 |
| currency | string | Currency code | Must be one of: debit_base, debit_extra1-5 |
| custom   | string | Your order/reference ID | Max 100 characters |

### Validation Rules

1. All fields are required
2. Amount must be a positive number with up to 2 decimal places
3. Item name must be 3-100 characters
4. Custom field is limited to 100 characters
5. Currency must be one of the allowed values

## Testing with Dev Server

For public testing without sharing source code, use the development server:

1. **Use the Dev Endpoint**:
   ```html
   <form method="POST" action="https://dev.unelmapay.com/sci/form">
       <input type="hidden" name="merchant" value="7">
       <input type="hidden" name="item_name" value="Test Product">
       <input type="hidden" name="amount" value="2.00">
       <input type="hidden" name="currency" value="debit_base">
       <input type="hidden" name="custom" value="TEST_ORDER_123">
       <button type="submit">Test Payment</button>
   </form>
   ```

2. **Set up your IPN Handler**:
   - Create a public endpoint that accepts POST requests
   - Use the IPN handler code from the example above
   - Ensure it's accessible via HTTPS

3. **Update Dev Server Callback URLs**:
   Contact support to set your callback URLs:
   - Status URL: `https://your-server.com/ipn_handler.php`
   - Success URL: `https://your-server.com/success.php`
   - Fail URL: `https://your-server.com/fail.php`

## Local Development Setup

For local development and testing:

1. **Start the local PHP server**:
   ```bash
   cd /path/to/UnelmaPay-developers/local_test
   php -S 0.0.0.0:8000
   ```

2. **Update merchant callback URLs** (run in Docker container):
   ```bash
   docker exec -i unelmanpay_docker_v2-mysql-1 mysql -ujack -pjack -e "
     UPDATE merchants 
     SET 
       status_link = 'http://YOUR_LOCAL_IP:8000/ipn_handler.php',
       success_link = 'http://YOUR_LOCAL_IP:8000/success.php',
       fail_link = 'http://YOUR_LOCAL_IP:8000/fail.php'
     WHERE id = 7" main
   ```
   Replace `YOUR_LOCAL_IP` with your machine's local IP address.

3. **Test the payment flow**:
   - Open: http://localhost:8000/index.html
   - Submit the test payment form
   - Check the logs: `tail -f ipn_log.txt`

## Handling Callbacks

### IPN (Instant Payment Notification)

UnelmaPay will send a POST request to your IPN handler URL with the following parameters:

- `total` - Total amount paid
- `date` - Transaction date (format: YYYYMMDD)
- `id_transfer` - Transaction ID
- `hash` - Verification hash (format: `md5(total:merchant_password:date:id_transfer)`)
- `custom` - Your order/reference ID
- `item_name` - Product/service name
- `currency` - Currency code used

**Example IPN Request**:
```
POST /ipn_handler.php HTTP/1.1
Host: your-server.com
Content-Type: application/x-www-form-urlencoded

total=10.00&date=20230809&id_transfer=TRANS123&hash=34B16A66250092846F0F96824DB35982&custom=ORDER_123&item_name=Test+Product&currency=debit_base
```

### IPN Handler Example (PHP)

Create a file named `ipn_handler.php` on your server:

```php
<?php
// Configuration
$logFile = __DIR__ . '/ipn_log.txt';
$merchantPassword = 'YOUR_MERCHANT_PASSWORD'; // Get this from your dashboard

// Log function
function logIPN($message) {
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $message" . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

// Log the raw POST data
logIPN("Received IPN: " . print_r($_POST, true));

// Get POST data
$total = $_POST['total'] ?? '';
$date = $_POST['date'] ?? '';
$idTransfer = $_POST['id_transfer'] ?? '';
$receivedHash = $_POST['hash'] ?? '';

// Verify the hash
$hashString = $total . ':' . $merchantPassword . ':' . $date . ':' . $idTransfer;
$calculatedHash = strtoupper(md5($hashString));

if ($receivedHash === $calculatedHash) {
    // Payment is verified
    logIPN("Payment verified for transaction: $idTransfer");
    
    // Update your database here
    // Example: mark order as paid
    
    // Send success response
    header('HTTP/1.1 200 OK');
    echo "IPN Received and Verified";
} else {
    // Invalid hash
    logIPN("Invalid hash for transaction: $idTransfer");
    header('HTTP/1.1 400 Bad Request');
    echo "Invalid IPN";
}
?>
```

### Success/Failure Pages

Create these pages to handle customer redirection after payment:

1. `success.php` - Shown when payment is successful
2. `fail.php` - Shown when payment fails or is cancelled

## Testing Procedures

### Test Environment

1. Use the test merchant ID provided in your dashboard
2. Set the form action to the test endpoint: `https://test.unelmapay.com/sci/form`
3. Use test card numbers (provided in your dashboard)

### Test Cases

1. **Successful Payment**
   - Submit form with valid test card
   - Verify IPN is received
   - Check database is updated
   - Confirm success page is shown

2. **Failed Payment**
   - Use a test card that will be declined
   - Verify fail page is shown
   - Check IPN for failure notification

3. **IPN Verification**
   - Send a test IPN from your dashboard
   - Verify your IPN handler processes it correctly
   - Check logs for any errors

4. **Form Validation**
   - Test with missing fields
   - Test with invalid amounts
   - Test with invalid merchant ID

## Troubleshooting

### Common Issues

1. **IPN Not Received**
   - Check your server can receive incoming requests
   - Verify IPN URL is correct in merchant settings
   - Check server error logs

2. **Invalid Hash**
   - Verify merchant password is correct
   - Check the hash calculation
   - Ensure no whitespace in variables

3. **Form Submission Issues**
   - Check browser console for JavaScript errors
   - Verify all required fields are included
   - Ensure form method is POST

### Getting Help

For additional support:
1. Check our [API Documentation](https://docs.unelmapay.com)
2. Contact support@unelmapay.com
3. Include your merchant ID and any error messages

---
*Last Updated: August 2025*
*Version: 1.0*
