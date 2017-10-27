<?php
namespace Eduardokum\CorreiosPhp\Soap;

use Eduardokum\CorreiosPhp\Contracts\Soap\Soap as SoapContract;

class SoapCurl extends Soap implements SoapContract
{
    public function send($url, $action = '', $request = '', $namespaces = [])
    {
        $this->request = $request = is_string($request) ? $this->envelop($request, $namespaces) : $request;
        $headers = [
            'Content-Type: text/xml;charset=utf-8',
            sprintf('SOAPAction: "%s"', $action),
            'Accept: text/xml',
            'Cache-Control: no-cache',
            'Pragma: no-cache',
            sprintf('Content-length: %s', strlen($request)),
        ];
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
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $this->response = $response = curl_exec($curl);
        $this->soapError = curl_error($curl);
        $this->soapInfo = curl_getinfo($curl);
        curl_close($curl);
        return $this->response($response);
    }
}
