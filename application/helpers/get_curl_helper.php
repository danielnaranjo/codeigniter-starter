<?php
// http://stackoverflow.com/a/24854930
// https://www.codeigniter.com/userguide3/general/helpers.html
if ( ! function_exists('is_curl')) {
    function is_curl($url, $token = FALSE, $header = FALSE, $is_post = FALSE, $data = []){

        $curl = curl_init();
        $params = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
        );
        // If header is required..
        if($header === FALSE) {
            $headers = array(
                "accept: application/json",
                "Content-Type: application/json",
            );
            // If token exists..
            if($token === FALSE) {
                $withToken = array(
                    "Authorization: Bearer $token",
                );
                array_push($headers, $withToken);
            }
            array_push($params, array(CURLOPT_HTTPHEADER =>  $headers) );
        }
        // If post will passed bay params
        if($is_post === FALSE){
            $data_string = json_encode($data);
            array_push($params, array(CURLOPT_POSTFIELDS =>  $data_string) );
        }
        // Add to cURL requests
        curl_setopt_array($curl, $params);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
          $message = "cURL Error #:" . $err;
        } else {
          $message = $response;
        }
        // Return Success or Not!
		return $message;
    }
}
