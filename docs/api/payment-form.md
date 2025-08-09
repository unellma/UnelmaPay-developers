# Payment Form Integration

UnelmaPay's payment form integration allows you to quickly add payment functionality to your website with minimal coding.

## Form Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `merchant` | string | Yes | Your merchant ID |
| `item_name` | string | Yes | Name of the product/service (3-100 chars) |
| `amount` | decimal | Yes | Payment amount (must be > 0) |
| `currency` | string | Yes | Currency code (e.g., `debit_base`) |
| `custom` | string | Yes | Your order/reference ID (max 100 chars) |
| `return_url` | URL | No | URL to redirect after payment (overrides merchant setting) |
| `cancel_url` | URL | No | URL to redirect if payment is cancelled |
| `notify_url` | URL | No | URL for IPN callbacks (overrides merchant setting) |

## Example Form

```html
<form method="POST" action="https://dev.unelmapay.com.np/sci/form" id="paymentForm">
    <div class="form-group">
        <label>Merchant ID:</label>
        <input type="text" name="merchant" value="YOUR_MERCHANT_ID" required>
    </div>
    
    <div class="form-group">
        <label>Item Name:</label>
        <input type="text" name="item_name" value="Premium Service" required>
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

## Response Handling

After payment processing, the user will be redirected to:
- Success URL: If payment is successful
- Cancel URL: If payment is cancelled
- Fail URL: If payment fails

## Security Considerations

1. Always validate form input on the server side
2. Use HTTPS for all requests
3. Verify IPN callbacks using the provided hash
4. Never expose your merchant password in client-side code
