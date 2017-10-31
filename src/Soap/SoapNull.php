<?php
namespace Eduardokum\CorreiosPhp\Soap;

use Eduardokum\CorreiosPhp\Contracts\Soap\Soap as SoapContract;

class SoapNull extends Soap implements SoapContract
{
    public function send($url, $action = '', $request = '', $namespaces = [])
    {
        $result = new \stdClass();
        $result->args = func_get_args();
        $result->request = $request = is_string($request) ? $this->envelop($request, $namespaces) : $request;
        $result->headers = array_filter([
            'Content-Type: text/xml;charset=utf-8',
            !empty(trim($action)) ? sprintf('SOAPAction: "%s"', $action) : null,
            'Accept: text/xml',
            'Cache-Control: no-cache',
            'Pragma: no-cache',
            sprintf('Content-length: %s', strlen($request)),
        ]);

        print_r($result);
        die;
    }
}
