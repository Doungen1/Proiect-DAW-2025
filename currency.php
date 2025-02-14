<?php
function getUsdToRonRate($apiKey) {
    $url = "https://openexchangerates.org/api/latest.json?app_id={$apiKey}";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_URL, $url);
    $result = curl_exec($curl);
    curl_close($curl);

    $rates = json_decode($result, true);
    return isset($rates['rates']['RON']) ? $rates['rates']['RON'] : null; // Extracting the RON rate
}
