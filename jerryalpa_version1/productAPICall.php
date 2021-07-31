<?php
//this file is in server 1: developer.jerryalpa.com
//reason: I can make a product api using this server with API keys.
date_default_timezone_set("America/New_York");
$time = time();

$url = 'https://product.jerryalpa.com/allProducts.php';
// $collection_name = 'products';
$request_url = $url; //. '?' . $collection_name;
$curl = curl_init($request_url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
curl_close($curl);
$content = json_decode($response);
// echo '<pre>';
// for($i = 0; $i < count($content); $i++){
// print_r($content[0]);
// }
// echo '</pre>';

?>