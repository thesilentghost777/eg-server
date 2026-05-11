<?php
$curl = curl_init();

$data = array(
    "api_key" => "TLWJgKTVYYvkYRhkEUJmqdoySkjssesjXiFhZOTCwTTsolxFkLtvLVWkXgOFtN",
    "pin_type" => "NUMERIC",
    "phone_number" => "00237654903473",
    "pin_attempts" => 2,
    "pin_time_to_live" => 1,
    "pin_length" => 4
);

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://v3.api.termii.com/api/sms/otp/generate",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => array("Content-Type: application/json"),
));

$response = curl_exec($curl);
curl_close($curl);

echo $response;
?>