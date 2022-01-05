<?php




function QueryToAirwatchAPI($tipo,$serie) {
    $basic_auth = base64_encode("jferia:TP1nghm0R1hM0yz");
    //$basic_auth='amZlcmlhOkxldHR5b3J0ZWdh';
    $ch = curl_init();
    $api_key='Zbh2S+e0ejNOibdtwlFDFssflXSeCniu2oh1/7lVg5A=';
    $baseurl="https://as257.awmdm.com";
   if ($tipo == "ALLDEVS") {
        $endpoint="/API/mdm/devices/search";    
    }
    if ($tipo == "DEVICE") {
        $endpoint="/api/mdm/devices/?searchby=Serialnumber&id=".$serie;
     // $endpoint="/api/mdm/devices/?searchby=Username&id=".$serie;
    }
    if ($tipo == "DEVICEperIMEI") {
        $endpoint="/api/mdm/devices/?searchby=ImeiNumber&id=".$serie;
    }


    $url = $baseurl.$endpoint;
    $headers = ['aw-tenant-code:'.$api_key,'Authorization:Basic '.$basic_auth,'Accept: application/json'];
    //print_r($headers);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

    $ch_result = curl_exec($ch);
    $infos = curl_getinfo($ch);
    //$infos['http_code'];
    ///////////////////////////////////////////////////////////////print_r($infos);
    //If http_code is not 200, then there's an error
    if ($infos['http_code'] != 200) {
        $result['status'] = AIRWATCH_API_RESULT_ERROR;
        $result['error']  = $infos['http_code'];
       // $result['data'] = "ERROR EN API ".$infos['http_code'];

    } else {
        $result['status'] = AIRWATCH_API_RESULT_OK;
        $result['data'] = $ch_result;
    }
    //print_r($result);
    curl_close($ch);
    return $result['data'];
}





?>



