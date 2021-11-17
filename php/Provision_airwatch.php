<?php
//$basic_auth = base64_encode("jferia:xxx");
$basic_auth='amZlcmlhOkxldHR5b3J0ZWdh';
$ch = curl_init();
$api_key='Zbh2S+e0ejNOibdtwlFDFssflXSeCniu2oh1/7lVg5A=';
$baseurl="https://as257.awmdm.com";
$endpoint="/API/mdm/devices/search";
$url = $baseurl.$endpoint;
curl_setopt($ch, CURLOPT_URL, $url);


$headers = ['aw-tenant-code: '.$api_key,'Authorization: Basic '.$basic_auth,'Accept: application/json'];
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
$ch_result = curl_exec($ch);
$infos = curl_getinfo($ch);
//If http_code is not 200, then there's an error
if ($infos['http_code'] != 200) {
        $result['status'] = AIRWATCH_API_RESULT_ERROR;
        $result['error']  = $infos['http_code'];
} else {
        $result['status'] = AIRWATCH_API_RESULT_OK;
        $result['data'] = $ch_result;
}
echo "<pre>";
print_r($result);
echo "</pre>";
curl_close($ch);
?>
