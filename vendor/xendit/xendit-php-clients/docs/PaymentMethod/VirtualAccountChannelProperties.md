# # VirtualAccountChannelProperties
Virtual Account Channel Properties

```php
use Xendit\PaymentMethod\VirtualAccountChannelProperties;
```

## Properties

| Name | Type | Required | Description | Examples |
|------------|:-------------:|:-------------:|-------------|:-------------:|
| **customer_name** | **string** |  | Name of customer. | Rika Sutanto |
| **virtual_account_number** | **string** |  | You can assign specific Virtual Account number using this parameter. If you do not send one, one will be picked at random. Make sure the number you specify is within your Virtual Account range. | 262159999999999 |
| **expires_at** | **\DateTime** |  | The date and time in ISO 8601 UTC+0 when the virtual account number will be expired. Default: The default expiration date will be 31 years from creation date. | 2024-01-01T00:00Z |
| **suggested_amount** | **float** |  | The suggested amount you want to assign. Note: Suggested amounts is the amounts that can see as a suggestion, but user can still put any numbers (only supported for Mandiri and BRI) | 100000 |


[[Back to README]](../../README.md)
