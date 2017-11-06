<?php
namespace Eduardokum\CorreiosPhp\Soap;

use Eduardokum\CorreiosPhp\Contracts\Soap\Soap as SoapContract;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

class SoapDebug extends Soap implements SoapContract
{
    public function send($url, array $action = [], $request = '', $namespaces = [], $auth = [])
    {
        $result = new \stdClass();
        $result->args = func_get_args();
        $result->request = $request = $this->envelop($request, $namespaces);
        $result->headers = array_filter([
            'Content-Type: text/xml;charset=utf-8',
            array_key_exists('curl', $action) && !empty(trim($action['curl'])) ? sprintf('SOAPAction: "%s"', $action['curl']) : null,
            'Accept: text/xml',
            'Cache-Control: no-cache',
            'Pragma: no-cache',
            sprintf('Content-length: %s', strlen($request)),
        ]);

        (new HtmlDumper())->dump((new VarCloner())->cloneVar($result));
        die;
    }
}
