<?php
namespace Eduardokum\CorreiosPhp\Soap;

use Eduardokum\CorreiosPhp\Contracts\Soap\Soap as SoapContract;
use Eduardokum\CorreiosPhp\Exception\InvalidArgumentException;
use Eduardokum\CorreiosPhp\Exception\SoapException;

class SoapNative extends Soap implements SoapContract
{
    public function send($url, array $action = [], $request = '', $namespaces = [], $auth = [])
    {
        $this->request = $request = $this->xmlToStd($this->envelop($request, $namespaces));
        $params     = [
            'encoding'           => 'UTF-8',
            'verifypeer'         => false,
            'verifyhost'         => false,
            'soap_version'       => SOAP_1_1,
            'trace'              => 1,
            'exceptions'         => 0,
            "connection_timeout" => $this->soapTimeout,
        ];

        if (array_key_exists('user', $auth) && array_key_exists('user', $auth)) {
            $params['login'] = $auth['user'];
            $params['password'] = $auth['password'];
        }

        if (!array_key_exists('native', $action)) {
            throw new InvalidArgumentException('action for native not defined.');
        }

        $action = $action['native'];
        try {
            $soapClient = new \SoapClient("$url?WSDL", $params);
            $this->response = $response = $soapClient->$action($this->request);
            $this->soapInfo = $soapClient->__getLastResponseHeaders();
        } catch (\SoapFault $e) {
            throw new SoapException($e->getMessage());
        } catch (\Exception $e) {
            throw new SoapException($e->getMessage());
        }

        if (is_soap_fault($response)) {
            $this->soapError = vsprintf('%s - %s', [
                $response->faltcode,
                $response->faultstring,
            ]);
        }
        return $response;
    }

    /**
     * @param $xml
     *
     * @return \stdClass
     */
    private function xmlToStd($xml)
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = false;
        $dom->loadXML($xml);

        $response = $dom->getElementsByTagName('Body')
            ->item(0) // Get Body
            ->childNodes->item(0); // Get Result Object;
        $response = $dom->saveXML($response);
        $response = preg_replace('/\<(\/?)\w+:(\w+\/?)\>/', '<$1$2>', $response);
        $response  = simplexml_load_string($response);
        $response = json_encode($response, JSON_PRETTY_PRINT);
        return json_decode($response);
    }
}
