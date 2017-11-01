<?php
namespace Eduardokum\CorreiosPhp\Soap;

use Eduardokum\CorreiosPhp\Contracts\Soap\Soap as SoapContract;
use Eduardokum\CorreiosPhp\Exception\InvalidArgumentException;
use Eduardokum\CorreiosPhp\Exception\SoapException;

class SoapNative extends Soap implements SoapContract
{
    public function send($url, array $action = [], $request = '', $namespaces = [])
    {
        $this->request = $request = is_string($request) ? $this->envelop($request, $namespaces) : $request;
        $params     = [
            'encoding'           => 'ISO-8859-1',
            'verifypeer'         => false,
            'verifyhost'         => false,
            'soap_version'       => SOAP_1_1,
            'trace'              => 1,
            'exceptions'         => 0,
            "connection_timeout" => $this->soapTimeout,
        ];

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
        throw new SoapException('Method not implemented yet');
    }
}
