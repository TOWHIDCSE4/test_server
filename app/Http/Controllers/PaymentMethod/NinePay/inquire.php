<?php
    require_once('lib/HMACSignature.php');
    require_once('lib/MessageBuilder.php');

	function callAPI($method, $url, $data, $headers = false){
	   $curl = curl_init();
	   switch ($method){
		  case "POST":
			 curl_setopt($curl, CURLOPT_POST, 1);
			 if ($data)
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			 break;
		  case "PUT":
			 curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
			 if ($data)
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
			 break;
		  default:
			 if ($data)
				$url = sprintf("%s?%s", $url, http_build_query($data));
	   }
	   // OPTIONS:
	   curl_setopt($curl, CURLOPT_URL, $url);
	   curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	   // EXECUTE:
	   $result = curl_exec($curl);
	   if(!$result){die("Connection Failure");}
	   curl_close($curl);
	   return $result;
	}

    #Thông tin cấu hình
		
    const MERCHANT_KEY = 'Nrx9wW'; // thông tin key của merchant
    const MERCHANT_SECRET_KEY = '37eXAlepnGLz5tMdWTmTFtXbNWWIJgWMdHm';  // thông tin secret key của merchant
    const END_POINT = 'https://sand-payment.9pay.vn';
	
	$time = time();
	$data = [];
	$message = MessageBuilder::instance()
		->with($time, END_POINT . '/v2/payments/'.$invoice_no.'/inquire', 'GET')
		->withParams($data)
		->build();	
	$hmacs = new HMACSignature();
    $signature = $hmacs->sign($message, MERCHANT_SECRET_KEY);

	$headers = array(
		'Date: '.$time,
		'Authorization: Signature Algorithm=HS256,Credential='.MERCHANT_KEY.',SignedHeaders=,Signature='.$signature
	);

	var_dump($headers);

	echo 'RESPONSE<br/>';
	$response = callAPI('GET', END_POINT . '/v2/payments/'.$invoice_no.'/inquire', false, $headers);
	var_dump($response);

?>
