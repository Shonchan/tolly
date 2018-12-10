<?php

namespace App\Api;

class Client {

    protected $request;
    protected $BXServer;
    protected $params;
    protected $url;
    protected $pass;
    private $hash;

    /**
     * Client constructor.
     * @param array $params
     */
    public function __construct() {

        $this->request = $_REQUEST;
        $this->BXServer = $_SERVER;
        $root = $_SERVER['DOCUMENT_ROOT'];

        $this->url = 'http://odeyalaoptom.ru/sst/api/catalog/';
        $this->pass = 'S15BIN3gM33FDE5';
        
    }

    /**
     * @method makeRequest
     * @return mixed
     * @throws \Exception
     */
    public function makeRequest() {
        $paramsJson = json_encode($this->getParams());
        self::PR($paramsJson);
        $paramsJson = base64_encode($paramsJson);
        $signature = '';
        foreach ($this->getParams() as $val) {
            $signature .= $val;
        }
        $signature .= $this->pass;
        $signature = md5($signature);

        $ch = curl_init($this->getUrl());
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, 0); // используем локальный днс кеш
        curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 0); // отключаем днс-кеширование
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//		curl_setopt($ch, CURLOPT_NOBODY ,1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'RS=' . $signature . '&data=' . $paramsJson);
        $res = curl_exec($ch);

        $errNo = curl_errno($ch);
        $err = curl_error($ch);
        $http_response_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        $json = base64_decode($res);
        self::PR($json);
        $arResult = json_decode($json, 1);
        if ($arResult && $errNo == 0 && !$err && $http_response_status < 400) {
            if ($arResult['STATUS'] == 0) {
                $strError = '';
                foreach ($arResult['ERRORS'] as $arError) {
                    $strError .= $arError['MSG'] . ". code: " . $arError['CODE'] . "\r\n";
                }
                self::PR($strError);
            }
        } else {
            $msg = sprintf(
                    "Ошибка соединения.\r\ncurl_errno=%d\r\ncurl_error=%s\r\nстатус=%s", $errNo, $err, $http_response_status
            );
            self::PR($msg);
        }
        return $arResult;
    }

    /**
     * @method PR
     * @param $o
     */
    public static function PR($o) {
        $bt = debug_backtrace();
        $bt = $bt[0];
        $dRoot = $_SERVER["DOCUMENT_ROOT"];
        $dRoot = str_replace("/", "\\", $dRoot);
        $bt["file"] = str_replace($dRoot, "", $bt["file"]);
        $dRoot = str_replace("\\", "/", $dRoot);
        $bt["file"] = str_replace($dRoot, "", $bt["file"]);
    }

    /* =========================================== getters/setters ================================================== */

    /**
     * @method getRequest - get param request
     * @return mixed
     */
    public function getRequest() {
        return $this->request;
    }

    /**
     * @method setRequest - set param Request
     * @param mixed $request
     */
    public function setRequest($request) {
        $this->request = $request;
    }

    /**
     * @method getBXServer - get param BXServer
     * @return mixed
     */
    public function getBXServer() {
        return $this->BXServer;
    }

    /**
     * @method setBXServer - set param BXServer
     * @param mixed $BXServer
     */
    public function setBXServer($BXServer) {
        $this->BXServer = $BXServer;
    }

    /**
     * @method getParams - get param params
     * @return array
     */
    public function getParams() {
        return $this->params;
    }

    /**
     * @method setParams - set param Params
     * @param array $params
     */
    public function setParams($params) {
        $this->params = $params;
    }

    /**
     * @method getUrl - get param url
     * @return mixed
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @method setUrl - set param Url
     * @param mixed $url
     */
    public function setUrl($url) {
        $this->url = $url;
    }

}
