// merchant password		
$merchant_password = "t43t43t34t43t34t6545845";

// transaction info
$amount = $_POST['amount'];
$fee = $_POST['fee'];
$total = $_POST['total'];
$currency = $_POST['currency'];
$payer = $_POST['payer'];
$receiver = $_POST['receiver'];
$status = $_POST['status'];
$date = $_POST['date'];
$id_transfer = $_POST['id_transfer'];
// Merchant info
$merchant_name = $_POST['merchant_name'];
$merchant_id = $_POST['merchant_id'];
$balance = $_POST['balance'];
// Purchase Information
$item_name = $_POST['item_name'];
$custom = $_POST['custom'];
// Verification of the transaction
$hash = $_POST['hash'];

$hash_string = $total.':'.$merchant_password.':'.$date.':'.$id_transfer;
		
$user_hash = strtoupper(md5($hash_string));

if ($hash_string == $user_hash) {

	echo "Confirmed!";

} else {

	echo "Dasabled!";

}
