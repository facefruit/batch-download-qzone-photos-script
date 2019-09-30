<?php

function request($url, $header = array()) {
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, '1'); 
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, '2'); 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
  $output = curl_exec($ch);
  curl_close($ch);
  return $output;
}
