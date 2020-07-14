<?php

function req($url, $headers, $data) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, ''.$url.'');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, ''.$data.'');
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_COOKIEJAR, "bal.txt");
	curl_setopt($ch, CURLOPT_COOKIEFILE, "bal.txt");
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}



function request($url, $data, $headers, $put = null) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	if($put):
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	endif;
	if($data):
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
	curl_setopt($ch, CURLOPT_TIMEOUT, 120);
	endif;
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	if($headers):
    curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	endif;
	curl_setopt($ch, CURLOPT_ENCODING, "GZIP");
	return curl_exec($ch);
}


function addref($reff, $nomor) {
$url = "https://membership.usetada.com/api/referral/add";
$data = '{"referralCode":"'.$reff.'","phone":"'.$nomor.'","country":"ID"}';
$headers = array();
$headers [] = "Host: membership.usetada.com";
$headers [] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:78.0) Gecko/20100101 Firefox/78.0";
//$headers [] = "Content-Length: 399";
$headers [] = "Accept: application/json, text/plain, */*";
$headers [] = "Accept-Language: id,en-US;q=0.7,en;q=0.3";
$headers [] = "Accept-Encoding: gzip, deflate";
$headers [] = "authorization: Bearer wyEUu4Bz2Z9Z29mKyEnr7sA0LcR3i3pS4kRv8NzO9tHLIVI09Xf8G7bry2s1x6zIAJg32m0sMQ5DwLepgsdogB3t0mzIn1LzPA4l8mMlfZ0UNL433VRCFl19I6G884pb1vl4L91hp2U5a6KFc3TdbVIFOrIWuoy0cWuMXPXojMROAFzhc8s2uzqKvaw2Sx6KdkP53ecWNOPOwIf7dc9GOnXgruBdf7sndlUH3016arpGCNcRwqEhNZzEdW7BMfls";
$headers [] = "Content-Type: application/json;charset=utf-8";
$headers [] = "Cookie: _ga=GA1.1.1529192398.1594602676; _ga_B6DQELPYPE=GS1.1.1594602675.1.0.1594602676.0";
$getotp = request($url, $data, $headers);
$json = json_decode($getotp, true);
//var_dump($json);
$a = $json['success'];

if ($a == true) {
	echo "Berhasil Daftar Reff\n";
	} else {
	return FALSE;
	}

}

function kirimotp($nomor) {
$url = "https://tzpromo.usetada.com/api/auth/check-number";
$data = '{"countryCode":"ID","phoneNumber":"'.$nomor.'","merchantId":2309,"senderType":"sms","referenceId":null}';
$headers = array();
$headers [] = "Host: tzpromo.usetada.com";
$headers [] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:78.0) Gecko/20100101 Firefox/78.0";
//$headers [] = "Content-Length: 399";
$headers [] = "Accept: application/json, text/plain, */*";
$headers [] = "Accept-Language: id,en-US;q=0.7,en;q=0.3";
$headers [] = "Accept-Encoding: gzip, deflate";
$headers [] = "Content-Type: application/json;charset=utf-8";
$headers [] = "Origin: https://tzpromo.usetada.com";
$headers [] = "Connection: close";

$getotp = req($url, $headers, $data);
$json = json_decode($getotp, true);
$a = $json['success'];

if ($a == true) {
	echo "Berhasil kirim otp $nomor\n";
	} else {
	return FALSE;
	}

}

function eotp($otp, $nomor) {
$url = "https://tzpromo.usetada.com/api/auth/verify-otp";
$data = '{"phone":"'.$nomor.'","otp":"'.$otp.'","type":"membership"}';
$headers = array();
$headers [] = "Host: tzpromo.usetada.com";
$headers [] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:78.0) Gecko/20100101 Firefox/78.0";
//$headers [] = "Content-Length: 399";
$headers [] = "Accept: application/json, text/plain, */*";
$headers [] = "Accept-Language: id,en-US;q=0.7,en;q=0.3";
$headers [] = "Accept-Encoding: gzip, deflate";
$headers [] = "Content-Type: application/json;charset=utf-8";
$headers [] = "Origin: https://tzpromo.usetada.com";
$headers [] = "Connection: close";

$getotp = req($url, $headers, $data);
$json = json_decode($getotp, true);
$a = $json['success'];
if ($a != TRUE) {
	echo "Otp Salah\n";
	return "mmk";
	} else {

		echo "Berhasil veriv otp\n";
	}

}


function getid() {
$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://tzpromo.usetada.com/api/cards');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_COOKIEJAR, "bal.txt");
	curl_setopt($ch, CURLOPT_COOKIEFILE, "bal.txt");
	$headers = array();
	$headers[] = 'Host: tzpromo.usetada.com';
	$headers[] = 'Origin: https://tzpromo.usetada.com';
	$headers[] = 'Content-Length: 0';
	$headers[] = 'Connection: close';
	$headers[] = 'If-None-Match: W/\"218-3ZW6aFb1w+PAzC1+lDaIN57d2DA\"';
	$headers[] = 'Accept: application/json, text/plain, */*';
	$headers[] = 'User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148';
	$headers[] = 'Accept-Language: en-us';
	$headers[] = 'Referer: https://tzpromo.usetada.com/cards';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$result = curl_exec($ch);
	$json = json_decode($result, true);
	curl_close($ch);
	//var_dump($json);
	$a = $json['nextId'];
	return $a;
	echo "$a\n";
}

function regis($id, $nomor) {
$url = "https://tzpromo.usetada.com/api/cards/register";
$data = '{"cardId":"'.$id.'","data":[{"key":"phone","value":"'.$nomor.'"},{"key":"name","value":"Yahya Mukty"}]}';
$headers = array();
$headers [] = "Host: tzpromo.usetada.com";
$headers [] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:78.0) Gecko/20100101 Firefox/78.0";
//$headers [] = "Content-Length: 399";
$headers [] = "Accept: application/json, text/plain, */*";
$headers [] = "Accept-Language: id,en-US;q=0.7,en;q=0.3";
$headers [] = "Accept-Encoding: gzip, deflate";
$headers [] = "Content-Type: application/json;charset=utf-8";
$headers [] = "Origin: https://tzpromo.usetada.com";
$headers [] = "Connection: close";

$getotp = req($url, $headers, $data);
$json = json_decode($getotp, true);
var_dump($json);

}

function getid1($card){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://tzpromo.usetada.com/api/cards/detail');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"id\":\"$card\"}");
	curl_setopt($ch, CURLOPT_COOKIEJAR, "bal.txt");
	curl_setopt($ch, CURLOPT_COOKIEFILE, "bal.txt");
	$headers = array();
	$headers[] = 'Host: tzpromo.usetada.com';
	$headers[] = 'Content-Type: application/json;charset=utf-8';
	$headers[] = 'Origin: https://tzpromo.usetada.com';
	$headers[] = 'Connection: close';
	$headers[] = 'Accept: application/json, text/plain, */*';
	$headers[] = 'User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148';
	$headers[] = 'Referer: https://tzpromo.usetada.com/cards';
	$headers[] = 'Accept-Language: en-us';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$result = curl_exec($ch);
	curl_close($ch);
	$json = json_decode($result, true);
	$a = $json['id'];
	return $a;
	echo "$a";
}


echo "=========================\n";
echo "Auto reff Timezone\n";
echo "Created by @ikballnh\n";
echo "=========================\n";
echo "Masukan reff: ";
$reff = trim(fgets(STDIN));

awal:
@unlink("bal.txt");
echo "Masukan nomor:(Pake 0) ";
$nomor = trim(fgets(STDIN)); 
addref($reff, $nomor);
kirimotp($nomor);
otp:
echo "Masukan otp: ";
$otp = trim(fgets(STDIN));
$inotp = eotp($otp, $nomor);
if ($inotp == "mmk") {
	goto otp;
} 

	$get = getid();
	$getreg = getid1($get);
	regis($getreg, $nomor);
	goto awal;