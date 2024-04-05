<html>
<head>
<title> Custom Form Kit </title>
</head>
<body>
<center>

<?php include('Crypto.php')?>
<?php 

	error_reporting(E_ALL);
	
	$merchant_data='';
	//$working_key='';//Shared by CCAVENUES
	//$access_code='';//Shared by CCAVENUES
	
	$working_key='0AF6CF4375336EDC0C31877E78AA7AF6';//Shared by CCAVENUES
	$access_code='AVCV12KI44AY33VCYA';//Shared by CCAVENUES
	
	foreach ($_POST as $key => $value){
		$merchant_data.=$key.'='.urlencode($value).'&';
	}
	print_r($merchant_data);
	$encrypted_data=encrypt($merchant_data,$working_key); // Method for encrypting the data.

?>
<form method="post" name="redirect" action="https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction"> 
<?php
echo "<input type=hidden name=encRequest value=$encrypted_data>";
echo "<input type=hidden name=access_code value=$access_code>";
?>
</form>
</center>
<script language='javascript'>//document.redirect.submit();</script>
</body>
</html>

