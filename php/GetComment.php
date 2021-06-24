<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://as257.awmdm.com/api/mdm/devices/notes?searchby=SerialNumber&id=MXRNU19720108085',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'aw-tenant-code: Zbh2S+e0ejNOibdtwlFDFssflXSeCniu2oh1/7lVg5A=',
    'Accept: application/json',
    'Authorization: Basic amZlcmlhOkxldHR5b3J0ZWdhMTIz'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
?>