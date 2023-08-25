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
    const MERCHANT_KEY = ''; // thông tin key của merchant
    const MERCHANT_SECRET_KEY = '';  // thông tin secret key của merchant
    const END_POINT = 'https://sand-payment.9pay.vn';

    $request_id = time() + rand(0,999999);
    $amount = '79000';
	$payment_no = '304181223691';
    $description = "Mô tả giao dịch";
	$time = time();
	$refund_param = array(
		'request_id' => $request_id,           
		'payment_no' => $payment_no,
		'amount' => $amount,
		'description' => "Test Refund ".$payment_no,
	);
	$message = MessageBuilder::instance()
		->with($time, END_POINT . '/refunds/create', 'POST')
		->withParams($refund_param)
		->build();
	$hmacs = new HMACSignature();
	$signature = $hmacs->sign($message, MERCHANT_SECRET_KEY);

	$headers = array(
		'Date: '.$time,
		'Authorization: Signature Algorithm=HS256,Credential='.MERCHANT_KEY.',SignedHeaders=,Signature='.$signature
	);

	$response = callAPI('POST', END_POINT . '/refunds/create', $refund_param, $headers);

	echo 'HEADERs:';
	print_r($headers);
	echo '<hr>RESULT:';
	print_r($response);
?>