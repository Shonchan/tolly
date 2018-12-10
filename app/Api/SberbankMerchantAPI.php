<?php

namespace App\Api;

/**
 * Description of SberbankMerchantAPI
 *
 * @author Max
 */
class SberbankMerchantAPI {

    private $apiUrl;
    private $token;

    function __construct($apiUrl, $token) {
        $this->apiUrl = $apiUrl;
        $this->token = $token;
    }

    public function geristerOrder($args = []) {
        
        $action = 'rest/register.do';
        $args['token'] = $this->token;
        
        $result = $this->_sendRequest($this->apiUrl.$action, $args);
        return json_decode($result);

    }
    
    public function getState($args = []) {
        
        $action = 'rest/getOrderStatusExtended.do';
        $args['token'] = $this->token;
        
        $result = $this->_sendRequest($this->apiUrl.$action, $args);
        return json_decode($result);
        
    }

    private function _sendRequest($api_url, $args) {
        
        $this->error = '';

        if ($curl = curl_init()) {
            curl_setopt($curl, CURLOPT_URL, $api_url);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $args);

            $out = curl_exec($curl);
            $this->response = $out;
            $json = json_decode($out);

            if ($json) {
                if (@$json->ErrorCode !== "0") {
                    $this->error = @$json->Details;
                } else {
                    $this->paymentUrl = @$json->PaymentURL;
                    $this->paymentId = @$json->PaymentId;
                    $this->status = @$json->Status;
                }
            }

            curl_close($curl);

            return $out;
        } else {
            throw new HttpException('Can not create connection to ' . $api_url . ' with args ' . $args, 404);
        }
    }

}
