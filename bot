<?php
 
/* Created By : Gidhan Bagus Algary */
 
$headers = array();
$headers[] = 'Content-Type: application/json';
$headers[] = 'device-type: Android';
$headers[] = 'device-hardware: ASUS_'.random(5,4);
$headers[] = 'device-version: 25';
$headers[] = 'version: 3.1.9';
$headers[] = 'Connection: Keep-Alive';
$headers[] = 'User-Agent: okhttp/3.8.0';
 
// INFO
$reff = "LT0Q1V";
$pass = "Kontol123";
$nam = nama();
$nama = explode(" ", $nam);
 
// MENU
echo "\n======================\n";
echo "QRIKET Account Creator\n";
echo "   By : Gidhan B.A\n";
echo "======================\n";
echo "Email: ";
$email = trim(fgets(STDIN));
$cek = curl('https://goldcloudbluesky.com/app/email-available', '{"email":"'.$email.'"}', $headers);
$data = json_decode($cek[0]);
if ($data->available == 1) {
	echo "Phone Number: ";
	$number = trim(fgets(STDIN));
	$numbers = $number[0].$number[1];
	if ($numbers == "08") { $number = str_replace("08","628",$number); }
	$send = curl('https://goldcloudbluesky.com/app/sms/verify', '{"phoneNumber":"+'.$number.'"}', $headers);
	$sendx = curl('https://goldcloudbluesky.com/auth/refresh', '{"refreshToken":"0"}', $headers);
	echo "Enter OTP: ";
	$otp = trim(fgets(STDIN));
	$headers[] = 'Accept: application/json';
	$headers[] = 'Authorization: Bearer 0';
	$sends = curlx('https://goldcloudbluesky.com/app/sms/verify', '{"code":"'.$otp.'","phoneNumber":"+'.$number.'"}', $headers);
	echo "Please wait for the registration ...\n";
	$regis = curl('https://goldcloudbluesky.com/app/register', '{"email":"'.$email.'","firstName":"'.$nama[0].'","lastName":"'.$nama[1].'","password":"'.$pass.'","phoneNumber":"+'.$number.'","referralCode":"'.$reff.'","utcOffset":420}', $headers);
	$regist = json_decode($regis[0]);
	if (!empty($regist->account)) {
	echo "Success - Name: ".$nam." | Email: ".$email." | Pass: ".$pass."\n";
	}
} else {
	die("Email has already been taken");
}
 
function nama()
	{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://ninjaname.horseridersupply.com/indonesian_name.php");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$ex = curl_exec($ch);
	// $rand = json_decode($rnd_get, true);
	preg_match_all('~(&bull; (.*?)<br/>&bull; )~', $ex, $name);
	return $name[2][mt_rand(0, 14) ];
	}
 
function random($length,$a) 
	{
		$str = "";
		if ($a == 0) {
			$characters = array_merge(range('0','9'));
		}elseif ($a == 1) {
			$characters = array_merge(range('a','z'));
		}elseif ($a == 2) {
			$characters = array_merge(range('A','Z'));
		}elseif ($a == 3) {
			$characters = array_merge(range('0','9'),range('a','z'));
		}elseif ($a == 4) {
			$characters = array_merge(range('0','9'),range('A','Z'));
		}elseif ($a == 5) {
			$characters = array_merge(range('a','z'),range('A','Z'));
		}elseif ($a == 6) {
			$characters = array_merge(range('0','9'),range('a','z'),range('A','Z'));
		}
		$max = count($characters) - 1;
		for ($i = 0; $i < $length; $i++) {
			$rand = mt_rand(0, $max);
			$str .= $characters[$rand];
		}
		return $str;
	}
 
function curl($url, $fields = null, $headers = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if ($fields !== null) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        }
        if ($headers !== null) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        $result   = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return array(
            $result,
            $httpcode
        );
	}
 
function curlx($url, $fields = null, $headers = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if ($fields !== null) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        }
        if ($headers !== null) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        $result   = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return array(
            $result,
            $httpcode
        );
	}
