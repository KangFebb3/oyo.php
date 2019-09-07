<?php
$uniqueid = randStr(16);
echo "?Nope		";
$phonee = get();
$atokgojek = "token_gojek_$phonee.txt";
if(!file_exists($atokgojek)){
	$otptoken = execute("register", $phonee);
	if(empty($otptoken)) exit("Gagal Send OTP! Gunakan Nope Lain!\n");
	echo "# OTP Sended | OTP Token : $otptoken\n";
	VerifyOtp:
	echo "?Otpe		";
	$atok = execute("verifyOtp", $otptoken, get());
	if(!empty($atok)){
		@file_put_contents($atokgojek, $atok);
		echo "# Sukses Daftar!!!\n";
	}else{
		echo "# OTP Salah!!!\n";
		goto VerifyOtp;
	}
}else{
	$atok = @file_get_contents($atokgojek);
}
//echo "?Pinn		";
//$pin = get();
$saldo = execute("getSaldo", $atok);
echo "# Saldo Gojek : $saldo\n";
echo "?Pilihan\n1. Voc Cashback 100% Maks 20k\n2. Send Saldo To Merchant\n3. Hapus Session\n?Number		";
$c = get();
if($c == 1){
	echo "# Claim Voucher Proses...";
	$claim = @json_decode(execute("claimVoc", $atok, "SERIUSMAIN"), true);
	if(@$claim['success']){
		echo " Sukses\n";
	}else
	if(@$claim['errors'][0]['code'] == "GoPromo-CustomerAlreadyEnrolled"){
		echo " Sudah Pernah Redeem!\n";
	}else{
		exit(" Gagal\nMaybe Voucher Habis..\n");
	}
	echo "?Pilih Poduk\n1. GPC 20k\n2. MEGAXUS 20k [Stok Habis]\n?Number		";
	$ddd = get();
	$prod = "";
	if($ddd == 1){
		$prod = "gpc";
	}
	SetPin:
		echo "# SET NEW PIN\n";
		echo "?Pin(6digit)	";
		$pin = get();
		$sendOtp = execute("setPin", $atok, $pin);
		if(strpos($sendOtp, "Masukkan OTP")){
			InputOtp:
				echo "?OTP		";
				$setPin = @json_decode(execute("setPin", $atok, $pin, get()), true)['success'];
				if($setPin){
					echo "# Sukses Set PIN...\n";
				}else{
					echo "# OTP Salah Mek!\n";
					goto InputOtp;
				}
		}else
		if(strpos($sendOtp, "PIN sudah terpasang")){
			echo "# PIN sudah terpasang Sebelumnya\n?Pin		";
			$pin = get();
		}else{
			echo "# Gagal!! Silahkan Gunakan Pin Lain\n";
			goto SetPin;
		}
	echo "?SaveTo		";
	$save = get();
	if($saldo<22000){
		echo("# Saldo Kurang Cukup, Minimal 22k!!\n");
		echo "# Ingin Melanjutkan? (y/n) ";
		$kkk = get();
		if(strtolower($kkk) == "y"){
		}else{
			exit("STOPPED!\n");
		}
	}
	BayarAwal:
		echo "\r[1/4] Membuat Transaksi.. ";
		$getInq = execute("getInquire", $atok, $prod);
		$inqid = @$getInq['data']['meta_data']['inquiry_id'];
		if(empty($inqid)){
			$reason = $getInq['errors'][0]['message'];
			echo "Gagal Membuat Transaksi. Reason : $reason..!\n";
			goto BayarAwal;
		}
	echo "Inquiry Id : $inqid\n";
	$aswww = 0;
	$page = 1;
	echo "[2/4] Mendapatkan Kode Voucher...\n";
	GetVoucher:
		$a = 0;
		$amount = @$getInq['data']['meta_data']['promotion']['applicable_promotions'][$a]['promotion_amount'];
		if($amount == 20000.0){
			$voc = @$getInq['data']['meta_data']['promotion']['applicable_promotions'][$a]['code'];
		}else{
			$a++;
			goto GetVoucher;
		}
	if(strlen($voc)==10){
		echo "\r\tBerhasil Mendapatkan [$voc]\n";
	}else{
		exit("Gagal Mendapatkan, Transaksi Dibatalkan!\n");
	}
	echo "[3/4] Membayar Transaksi... ";
	$pay = execute("pay", $atok, $inqid, $voc, $pin, $prod);
	$saldo = execute("getSaldo", $atok);
	if($pay['success']){
		while(true){
			$gpc = execute("getInfo", $pay['data']['orderId'], $atok);
			if(empty($gpc)){
				continue;
			}else
			if($gpc == "Failed"){
				echo "Payment Refunded\n";
				echo "[4/4] Status : $gpc | Balance : $saldo\n";
				break;
			}else{
				echo "Sukses\n";
				@file_put_contents($save, "$gpc\n", FILE_APPEND);
				@file_put_contents("voc_terpakai.txt", $voc."\n", FILE_APPEND);
				echo "[4/4] Code : $gpc | Balance : $saldo\n";
				echo "Selesai Sudah...\n";
				break;
			}
		}
	}else{
		echo "Gagal\n";
		$reason = $pay['errors'][0]['messageTitle'];
		echo "[4/4] Reason : ".$reason." | Balance : $saldo\n";
	}
	echo "\n";
}else
if($c == 2){
	echo "?Pin		";
	$pin = get();
	echo "?Jumlah		";
	$jum = get();
	echo "?MerchId	";
	$merch = get();
	$merchinfo = execute("getInfoMerch", $merch, $atok);
	if(empty($merchinfo)){
		echo "# MerchId Tidak Ditemukan.";
	}else{
		echo "# Merchant Name		: $merchinfo\n";
		usleep(10000);
		echo "# Jumlah Transfer	: $jum\n";
		echo "# Lanjutkan? (Y/n)	";
		if(strtolower(get()) == "n") exit("# STOPPED\n");
		$send = @json_decode(execute("sendMerchant", $atok,$pin,$merch,$jum), true);
		if($send['success']){
			echo "# Sukses Send $jum ke $merch\n";
		}else{
			$reason = $send['errors'][0]['message_title'];
			echo "# Gagal Send Ke [$merch] Karena $reason\n";
		}
	}
}else{
	unlink($atokgojek);
	echo "# Sukses Hapus Session!\n";
}
function randStr($l){
	$word = "abcdefghijklmnopqrstuvwxyz1234567890";
	$w = "";
	for($a=0;$a<$l;$a++){
		$w .= $word{rand(0,strlen($word)-1)};
	}
	return $w;
}
function get(){
	return trim(fgets(STDIN));
}
function execute($cmd, $a = null, $b = null, $c = null, $d = null, $e = null, $f = null){
	global $uniqueid;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://lodonk.nadiem.arc0de.com/?_cmd=$cmd&a=$a&b=$b&c=$c&d=$d&e=$e&f=$f&uniqueid=$uniqueid");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	$headers = array();
	$headers[] = 'Host: lodonk.nadiem.arc0de.com';
	$headers[] = 'Save-Data: on';
	$headers[] = 'Upgrade-Insecure-Requests: 1';
	$headers[] = 'User-Agent: Mozilla/5.0 (Linux; Android 5.1.1; SM-G935FD) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.101 Safari/537.36';
	$headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3';
	$headers[] = 'Accept-Language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7';
	$headers[] = 'Connection: close';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$result = curl_exec($ch);
	if (curl_errno($ch)) {
		echo 'Error:' . curl_error($ch);
	}
	curl_close($ch);
	return $result;
}