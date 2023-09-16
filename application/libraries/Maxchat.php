<?php

// ganti dg url api di admin panel,token bisa di check pada email
$GLOBALS["API_URL"] = "https://demo.maxchat.id/demo4/api";
// $GLOBALS["TOKEN"] = "6CWDrFPe5ScFgDzZvaFdT2";
$GLOBALS["TOKEN"] = "CY94HXh7bYDTkcuMPWxUNo";

class Maxchat 
{
	function webhookCapture() {
		$webhookContent = '';
	
		$webhook = fopen('php://input' , 'rb');
		
		while (!feof($webhook)) {
			$webhookContent .= fread($webhook, 4096);
		}
	
		fclose($webhook);
	
		error_log($webhookContent);
	
		return json_decode($webhookContent);
	}
	
	function sendText($to,$text) {
		// kirim pesan ke kontak yang sudah disimpan
		$url = $GLOBALS["API_URL"] . "/messages";
	
		$data = array(
		  "to" => $to,
		  "text" => $text
		);
	
		postCurl($url, $data);
	}
	
	function pushText($to,$text) {
		// kirim pesan ke kontak yang tidak dikenali
		$url = $GLOBALS["API_URL"] . "/messages/push";
		
		$data = array(
		  "to" => $to,
		  "text" => $text,
		);
	
		postCurl($url, $data);
	}
	
	
	function postCurl($url, $data) {
		$curl = curl_init();
	
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $url,
		  CURLOPT_SSL_VERIFYHOST => false,
		  CURLOPT_SSL_VERIFYPEER => false,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => json_encode($data),
		  CURLOPT_HTTPHEADER => array(
			"Authorization: Bearer " . $GLOBALS["TOKEN"],
			"Content-Type: application/json",
			"cache-control: no-cache"
		  ),
		));
	
		$response = curl_exec($curl);
		$err = curl_error($curl);
	
		curl_close($curl);
	
		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}
}

