# e-Khalti-developers

# Documentation

## POST form for payment

|Variable     |	Description  |	Conditions    |	Necessarily|
| ------------- | ------------- | ------------- |------------ |
|merchant|	Merchant ID in the system e-Khalti. Assigned to the merchant automatically after adding a store to your account. | Your store must be moderated	Not equal to 0|	Yes|
|item_name|	Product name or purpose of payment. This item is visible to the payer|	Minimum number of characters 3, maximum - 100|	Yes|
|amount	| The sum in any form. The sum is rounded to decimal places. The total amount including commission will be calculated automatically|	Sum is more than 1|	Yes
|currency|	Currency of payment. Available currencies debit_base, debit_extra1, debit_extra2, debit_extra3, debit_extra4, debit_extra5	|Only values from the list	|Yes|
|custom|	Comment to the payment. For example, the order number in your store. This item is not displayed to the buyer|	Maximum - 100|	Yes|




## IPN notifications

| Variable      | Description   | Example    |
| ------------- | ------------- | ------------- |
| $POST['amount']  | The received amount without commissions  | 100.00  |
| $POST['fee']  | Fee for payment. Paid by the buyer or merchant according to the settings  | 0.20  |
| $POST['total'] | Total transaction amount including commission  | 100.20  |
| $POST['currency'] | 	Transaction currency for which payment was made  | 	debit_base  |
| $POST['payer']  | Buyer username in the system e-Khalti  | 	johndoe  |
| $POST['receiver']  | Merchant username in the system e-Khalti  | Yeti Airlines  |
| $POST['status']  | The status of the transaction. Always Value "Confirmed"  | Confirmed |
| $POST['date']  | Transaction date  | 2018-01-09 03:11:07  |
| $POST['id_transfer'] | Unique transaction number in the system e-Khalti  | 58954  |	
| $POST['merchant_name'] | Merchant store name in the system e-Khalti  | Google Inc  |			
| $POST['merchant_id']] | Unique number of merchant in the system e-Khalti | 	21 |	
| $POST['balance'] | Available merchant balance in transaction currency | 	2100.56 |	
| $POST['item_name'] | Name of paid goods | 	Test payment |	
| $POST['custom'] | Comment on payment, formed by the merchant in the HTML form | 	INV 1452485 |	
| $POST['hash'] | 	A unique signature that is used to verify the validity of a notification. A string join is created of the total amount, merchant password, date transaction and transaction ID. The string is encrypted using an algorithm MD5. | 	C93D3BF7A7C4AFE94B64E30C2CE39F4F |	
		

	
		
		
		
		
		

		
		
		
