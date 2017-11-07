<?php
namespace Eduardokum\CorreiosPhp\Soap;

use Eduardokum\CorreiosPhp\Contracts\Soap\Soap as SoapContract;

class SoapCurl extends Soap implements SoapContract
{
    public function send($url, array $action = [], $request = '', $namespaces = [], $auth = [])
    {
        $this->request = $this->envelop($request, $namespaces);
        $headers = array_filter([
            'Content-Type: text/xml;charset=utf-8',
            array_key_exists('curl', $action) && !empty(trim($action['curl'])) ? sprintf('SOAPAction: "%s"', $action['curl']) : null,
            'Accept: text/xml',
            'Cache-Control: no-cache',
            'Pragma: no-cache',
            sprintf('Content-length: %s', strlen($this->request)),
        ]);
        $curl = curl_init();
        if ($this->proxyIP != '') {
            curl_setopt($curl, CURLOPT_HTTPPROXYTUNNEL, true);
            curl_setopt($curl, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
            curl_setopt($curl, CURLOPT_PROXY, $this->proxyIP.':'.$this->proxyPort);
            if ($this->proxyUser != '') {
                curl_setopt($curl, CURLOPT_PROXYUSERPWD, $this->proxyUser.':'.$this->proxyPass);
                curl_setopt($curl, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);
            }
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->soapTimeout);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->soapTimeout + 20);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSLVERSION, $this->soapProtocol);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        if ($auth) {
            curl_setopt($curl, CURLOPT_USERPWD, $auth['user'] . ":" . $auth['password']);
        }
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->request);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $this->response = $response = curl_exec($curl);
        $this->soapError = curl_error($curl);
        $this->soapInfo = curl_getinfo($curl);
        curl_close($curl);
        return $this->response($response);
    }
}
