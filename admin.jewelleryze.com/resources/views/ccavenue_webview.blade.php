<p style="text-align: center">{{__('Please wait. Your payment is processing....')}}</p>
<p style="text-align: center">{{__('Do not press browser back or forward button while you are in payment page')}}</p>

<html>
<body>
<center>
<?php
function encryptCC($plainText,$key)
{
  $key = hextobinCC(md5($key));
  $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
  $openMode = openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
  $encryptedText = bin2hex($openMode);
  return $encryptedText;
}

function decryptCC($encryptedText,$key)
{
  $key = hextobinCC(md5($key));
  $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
  $encryptedText = hextobin($encryptedText);
  $decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
  return $decryptedText;
}

function hextobinCC($hexString) 
 { 
  $length = strlen($hexString); 
  $binString="";   
  $count=0; 
  while($count<$length) 
  {       
    $subString =substr($hexString,$count,2);           
    $packedString = pack("H*",$subString); 
    if ($count==0)
    {
      $binString=$packedString;
    } 
    
    else 
    {
      $binString.=$packedString;
    } 
    
    $count+=2; 
  } 
    return $binString; 
} 
?>
<?php 
  error_reporting(E_ALL);
  
  $merchant_data='';
  
  $working_key="$workingKey";//Shared by CCAVENUES
  $access_code="$accessCode";//Shared by CCAVENUES
  
  $dataPost = Array ( '_token' => csrf_token(), 'tid' => "$UniqueTid", 'merchant_id' => "$merchantId", 'order_id' => "$orderId", 'amount' => $payable_amount, 'currency' => "$currencyCode", 'redirect_url'=> $payment_success_url,'frontend_success_url'=> $frontend_success_url, 'cancel_url' => $frontend_faild_url, 'language' => 'EN','billing_name' => "$billing_name", 'billing_address' => "$billing_address", 'billing_city' => "$billing_city", 'billing_state' => "$billing_state", 'billing_country' => "$billing_country", 'billing_tel' => "$billing_phone", 'billing_email' =>"$billing_email","billing_zip" => "$billing_pincode" );
  
//print_r($dataPost);
//exit();

  foreach ($dataPost as $key => $value){
    $merchant_data.=$key.'='.urlencode($value).'&';
  }

  $encrypted_data=encryptCC($merchant_data,$working_key); // Method for encrypting the data.

?>
<form method="post" name="redirect" action="https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction"> 
<?php
echo "<input type=hidden name='encRequest' value='".$encrypted_data."'>";
echo "<input type=hidden name='access_code' value='".$access_code."'>";
?>
</form>
</center>
<script language='javascript'>
 document.redirect.submit();
</script>



</body>
</html>



