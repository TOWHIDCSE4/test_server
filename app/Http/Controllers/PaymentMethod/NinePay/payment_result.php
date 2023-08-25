<?php

function urlsafeB64Decode($input)
{
        $remainder = \strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= \str_repeat('=', $padlen);
        }
        return \base64_decode(\strtr($input, '-_', '+/'));
 }


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
	print_r(urlsafeB64Decode($result));
?>
