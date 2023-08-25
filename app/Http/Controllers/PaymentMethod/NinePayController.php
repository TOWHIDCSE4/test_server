<?php

namespace App\Http\Controllers\PaymentMethod;

use App\Helper\Helper;
use App\Helper\TypeFCM;
use App\Http\Controllers\Controller;
use App\Jobs\PushNotificationUserJob;
use App\Models\Order;
use App\Models\OrderRecord;
use App\Models\StatusPaymentHistory;
use Exception;
use HMACSignature;
use Illuminate\Http\Request;
use MessageBuilder;

/**
 * @group  Customer/thanh toán onpay
 */
class NinePayController extends Controller
{


    public function refundCreate(Request $request)
    {

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
    }

    public function invoiceInquire(Request $request, $request_id)
    {

        const MERCHANT_KEY = '';
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
    }


    public function paymentCreate(Request $request)
    {
        const MERCHANT_KEY = 'y1C0Nm'; // thông tin key của merchant wallet
    const MERCHANT_SECRET_KEY = '7mebyCRGt0lKM1vHuEhdveDX8wkiGkJ5D3W';  // thông tin secret key của merchant
    const END_POINT = 'https://sand-payment.9pay.vn';

    $invoiceNo = time() + rand(0,999999);
    $amount = rand(10000,99999);
    $description = "Mô tả giao dịch";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $http = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https://' : 'http://';
        $backUrl = "$http$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $returnUrl = str_replace('index.php', '', $backUrl);
        $time = time();
        //$time = 1648111631;

        $data = array(
            'merchantKey' => MERCHANT_KEY,           
            'time' => $time,
            'invoice_no' => $_POST['invoice_no'],
            'amount' => $_POST['amount'],
            'description' => $_POST['description'],
			
            'back_url' => $backUrl,
            'return_url' => "{$returnUrl}result.php",
        );		

        $message = MessageBuilder::instance()
            ->with($time, END_POINT . '/payments/create', 'POST')
            ->withParams($data)
            ->build();
			

        $hmacs = new HMACSignature();
        $signature = $hmacs->sign($message, MERCHANT_SECRET_KEY);

        $httpData = [
            'baseEncode' => base64_encode(json_encode($data, JSON_UNESCAPED_UNICODE)),
            'signature' => $signature,
        ];
        $redirectUrl = END_POINT . '/portal?' . http_build_query($httpData);
		echo '<pre>';
		print_r($data);	
		echo '<br/>';	
		echo '<hr/>';			
		print_r($message);			
		echo '<br/>';	
		echo '<hr/>';	
		var_dump($httpData);	
		echo '<br/>';	
		echo '<hr/>';	
		print_r($redirectUrl);	
		exit();
        //return header('Location: ' . $redirectUrl);
    }
    }


    public function inquire(Request $request)
    {

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
    }


    public function result(Request $request)
    {
        $secretKeyChecksum = 's6KiGBywWbxAhHmXI5jesx4QHM2YWzLC';
        $result = 'eyJhbW91bnQiOjExMDAwMCwiYW1vdW50X2ZvcmVpZ24iOm51bGwsImFtb3VudF9vcmlnaW5hbCI6bnVsbCwiYW1vdW50X3JlcXVlc3QiOjExMDAwMCwiYmFuayI6bnVsbCwiY2FyZF9icmFuZCI6IlZJU0EiLCJjYXJkX2luZm8iOnsidG9rZW4iOiIzMDQ2NWEwMDRiZjU0NWQxNjkyMWEyY2ZhMjAwNmVlYyIsImNhcmRfbmFtZSI6Ik5HVVlFTiBWQU4gQSIsImhhc2hfY2FyZCI6ImI1NDdjNjdhZmJkODM0N2Y2ZWY0YmFhMGViOGFkZDkyIiwiY2FyZF9icmFuZCI6IlZJU0EiLCJjYXJkX251bWJlciI6IjQwMDU1NXh4eHh4eDAwMDkifSwiY3JlYXRlZF9hdCI6IjIwMjItMDYtMDNUMDI6MDY6MjguMDAwMDAwWiIsImN1cnJlbmN5IjoiVk5EIiwiZGVzY3JpcHRpb24iOiJUaGFuaCB0b8OhbiDEkcahbiBow6BuZyBRUDE2NTQyNDcxNzE1MjMwMzU4IiwiZXhjX3JhdGUiOm51bGwsImZhaWx1cmVfcmVhc29uIjpudWxsLCJmb3JlaWduX2N1cnJlbmN5IjpudWxsLCJpbnZvaWNlX25vIjoiUVAxNjU0MjQ3MTcxNTIzMDM1OCIsImxhbmciOm51bGwsIm1ldGhvZCI6IkNSRURJVF9DQVJEIiwicGF5bWVudF9ubyI6Mjk5Nzc4NTIyODk1LCJzdGF0dXMiOjUsInRlbm9yIjpudWxsfQ';
        $checksum= '8FD0C7C97ACE326DA44F7571CA223903E98552688BD6AB798F818B8FCB049B6A';
    
        
        $hashChecksum = strtoupper(hash('sha256', $result. $secretKeyChecksum));
        if($hashChecksum === $checksum){
            echo 'Dữ liệu đúng';
        }else{
            echo 'Dữ liệu không hợp lệ';
        }
        // if hashChecksum === $ninePayResult['checksum'] mean correct data
        print_r($this->urlsafeB64Decode($result));
    }

    function urlsafeB64Decode($input)
{
        $remainder = \strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= \str_repeat('=', $padlen);
        }
        return \base64_decode(\strtr($input, '-_', '+/'));
 }
}
