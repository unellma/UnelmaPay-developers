# Getting Started with UnelmaPay

Welcome to UnelmaPay! This guide will help you integrate our payment processing system into your application.

## Prerequisites

Before you begin, you'll need:
- A merchant account with UnelmaPay
- Your merchant ID (provided during registration)
- A web server to host your payment forms and callbacks

## Integration Options

UnelmaPay offers multiple integration methods:

1. **Payment Form** - Simple HTML form integration (easiest)
2. **API Integration** - Direct API calls for more control
3. **SDKs** - Coming soon for popular programming languages

## Quick Start: Payment Form

The fastest way to get started is with our payment form integration. Add this code to your website:

```html
<form method="POST" action="https://dev.unelmapay.com.np/sci/form">
    <input type="hidden" name="merchant" value="YOUR_MERCHANT_ID">
    <input type="hidden" name="item_name" value="Product Name">
    <input type="hidden" name="amount" value="10.00">
    <input type="hidden" name="currency" value="debit_base">
    <input type="hidden" name="custom" value="ORDER_ID">
    <button type="submit">Pay Now</button>
</form>
```

## Next Steps

1. Set up IPN callbacks to receive payment notifications - See [IPN Callback Documentation](../api/ipn-callback/)
2. Test your integration in the development environment - See [Testing Guide](../guides/testing/)
3. For production, update the form action to `https://unelmapay.com.np/sci/form`
