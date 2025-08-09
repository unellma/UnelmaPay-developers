# Testing Your Integration

This guide will help you test your UnelmaPay integration before going live.

## Testing Environment

### Development Environment
- **URL**: `https://dev.unelmapay.com.np/sci/form`
- **Purpose**: Initial testing and development
- **Features**:
  - Test payments (no real money involved)
  - Immediate feedback
  - Test merchant accounts available

## Testing IPN Callbacks

1. **Set up your IPN handler**
   - Ensure it's accessible via HTTPS
   - Enable detailed logging
   - Handle duplicate notifications

2. **Test IPN Endpoint**
   ```bash
   curl -X POST https://your-server.com/ipn_handler.php \
     -d "total=10.00" \
     -d "date=20230809" \
     -d "id_transfer=TEST_123" \
     -d "hash=TEST_HASH" \
     -d "custom=ORDER_123" \
     -d "item_name=Test+Product" \
     -d "currency=debit_base"
   ```

## Common Test Scenarios

### Successful Payment
1. Submit payment form with valid test card
2. Verify IPN received with status=completed
3. Check order status in your system

### Failed Payment
1. Use failing test card (4000 0000 0000 0002)
2. Verify proper error handling
3. Check IPN for failure notification

### IPN Verification
1. Send test IPN to your endpoint
2. Verify hash calculation
3. Check database updates

## Debugging Tips

1. **Check Logs**
   ```bash
   tail -f /path/to/your/ipn_log.txt
   ```

2. **Verify Hash**
   ```php
   $hash = strtoupper(md5($total . ':' . $merchantPassword . ':' . $date . ':' . $idTransfer));
   ```

3. **Test Locally**
   - Use ngrok for local testing
   - Forward requests to localhost
   - Inspect incoming requests

## Going Live

Before going live:
1. Test all payment scenarios
2. Verify IPN handling
3. Remove test credentials
4. Enable production API keys
5. Monitor transactions closely
