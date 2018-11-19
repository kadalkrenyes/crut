<?php
session_start();
set_time_limit(0);
error_reporting(0);
$refcode = "PSFDXG";
$jml = 100;
include 'Signature.php';
	if(isset($refcode))
	{
		ob_implicit_flush(1);
		header("Content-Type: text");
		header("access-control-allow-origin: *");
		header("pragma: no-cache");
		if($jml > 1000)
		{
			die;
		}
		ob_clean();
		for ($i=0; $i < (int)$jml; $i++) { 
			$uuid = Signatures::generateUUID();
			curl_x("https://api.watchoona.com/api/v1/register-device", "udid=" . $uuid . "&deviceOS=android&deviceBrand=samsung&deviceModel=SM-J500H&appVersion=1.7.3&timeZone=+1&timeZoneName=Europe/Belgrade&osVersion=4.4.2&osLanguage=en&appLanguage=en&carrier=T-Mobile&carrierId=31016&screenWidth=1280&screenHeight=720&appLanguage=en", $uuid);

			$json = json_decode(curl_x("https://api.watchoona.com/api/v1/verify-invite-code", "inviteCode=" . $refcode, $uuid));
			if($json->status == "success")
			{
				echo "SUKSES\n";
				str_repeat(" ", 1024*1024*4);
				echo "\n";
			} else {
				echo "GAGAL\n";
			}
			flush();
    		ob_flush();
		}
		
	} else {
		?>
		<!DOCTYPE html>
		<html>
		<head>
			<title>OONA</title>
		</head>
		<body>
			<form method="POST">
				<input type="text" name="refcode" placeholder="REFERALCODE" maxlength="6" /><input type="number" name="jml" placeholder="JUMLAH (MAX:1000)" max="1000" /> <button>Submit</button>
			</form>
		</body>
		</html>
		<?php
	}


function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function get_between($string, $start, $end) {
    $string = " ".$string;
    $ini = strpos($string,$start);
    if ($ini == 0) return "";
    $ini += strlen($start);
    $len = strpos($string,$end,$ini) - $ini;
    return substr($string,$ini,$len);
}
function curl_y($url, $fields="", $auth="", $ssl = 0, $followLocation = 0, $referer = '', $optUrl = '',  $deleteOldCookies=1, $sock = false, $usecookie=false, $geturl=false, $sesid=false,$cosid=false,$locale=false,$marketplace=false,$widget=false) {
    $ch = curl_init($url);
    $header = array();
    $header[]  = "uuid: d8c19a44-107a-4bf3-b351-c3212b0a556e";
    $header[]  = "Authorization: Bearer " . $auth;
    $header[]  = "Connection: close";
    $header[]  = "Content-Type: application/x-www-form-urlencoded";
    $header[]  = "Accept-Encoding: gzip";
    curl_setopt($ch, CURLOPT_USERAGENT, "okhttp/3.10.0");
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($ch,CURLOPT_ENCODING , "gzip");
	curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	if ($usecookie) { 
	curl_setopt($ch, CURLOPT_COOKIEJAR, $usecookie); 
	curl_setopt($ch, CURLOPT_COOKIEFILE, $usecookie);    
	} 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    if($followLocation){
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    if($fields){
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    }else{
        curl_setopt($ch, CURLOPT_POST, false);
    }
    if($referer){
        curl_setopt($ch, CURLOPT_REFERER, $referer);
    }else{
        curl_setopt($ch, CURLOPT_REFERER, $url);
    }
    if($ssl){
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    }else{
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    }
    if($optUrl){
        curl_setopt ($ch, CURLOPT_URL, $optUrl);
    }
	if($sock){
		curl_setopt($ch, CURLOPT_PROXY, $sock);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
	}
	if($geturl == true){
		$xd1 = curl_exec($ch);
		$xd2 = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
		return $xd1."|".$xd2;
	} else {
		$a = curl_exec($ch);
		if(curl_error($ch)){
			return curl_error($ch);
		} else {
			return $a;
		}
	}
}
function curl_x($url, $fields="", $uuid="", $ssl = 0, $followLocation = 0, $referer = '', $optUrl = '',  $deleteOldCookies=1, $sock = false, $usecookie=false, $geturl=false, $sesid=false,$cosid=false,$locale=false,$marketplace=false,$widget=false) {
    $ch = curl_init($url);
    $header = array();
    $header[]  = "uuid: " . $uuid;
    $header[]  = "Connection: close";
    $header[]  = "Content-Type: application/x-www-form-urlencoded";
    $header[]  = "Accept-Encoding: gzip";
    curl_setopt($ch, CURLOPT_USERAGENT, "okhttp/3.10.0");
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($ch,CURLOPT_ENCODING , "gzip");
	curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	if ($usecookie) { 
	curl_setopt($ch, CURLOPT_COOKIEJAR, $usecookie); 
	curl_setopt($ch, CURLOPT_COOKIEFILE, $usecookie);    
	} 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    if($followLocation){
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    if($fields){
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    }else{
        curl_setopt($ch, CURLOPT_POST, false);
    }
    if($referer){
        curl_setopt($ch, CURLOPT_REFERER, $referer);
    }else{
        curl_setopt($ch, CURLOPT_REFERER, $url);
    }
    if($ssl){
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    }else{
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    }
    if($optUrl){
        curl_setopt ($ch, CURLOPT_URL, $optUrl);
    }
	if($sock){
		curl_setopt($ch, CURLOPT_PROXY, $sock);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
	}
	if($geturl == true){
		$xd1 = curl_exec($ch);
		$xd2 = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
		return $xd1."|".$xd2;
	} else {
		$a = curl_exec($ch);
		if(curl_error($ch)){
			return curl_error($ch);
		} else {
			return $a;
		}
	}
}
function curl($url, $fields="", $ssl = 0, $followLocation = 1, $referer = '', $optUrl = '',  $deleteOldCookies=1, $sock = false, $usecookie=false, $geturl=false, $sesid=false,$cosid=false,$locale=false,$marketplace=false,$widget=false) {
    $ch = curl_init($url);

	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($ch,CURLOPT_ENCODING , "gzip");
	curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	if ($usecookie) { 
	curl_setopt($ch, CURLOPT_COOKIEJAR, $usecookie); 
	curl_setopt($ch, CURLOPT_COOKIEFILE, $usecookie);    
	} 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    if($followLocation){
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    if($fields){
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    }else{
        curl_setopt($ch, CURLOPT_POST, false);
    }
    if($referer){
        curl_setopt($ch, CURLOPT_REFERER, $referer);
    }else{
        curl_setopt($ch, CURLOPT_REFERER, $url);
    }
    if($ssl){
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    }else{
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    }
    if($optUrl){
        curl_setopt ($ch, CURLOPT_URL, $optUrl);
    }
	if($sock){
		curl_setopt($ch, CURLOPT_PROXY, $sock);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
	}
	if($geturl == true){
		$xd1 = curl_exec($ch);
		$xd2 = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
		return $xd1."|".$xd2;
	} else {
		$a = curl_exec($ch);
		if(curl_error($ch)){
			return curl_error($ch);
		} else {
			return $a;
		}
	}
}
