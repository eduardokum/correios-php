<?php
namespace Eduardokum\CorreiosPhp\Soap;

use Eduardokum\CorreiosPhp\Contracts\Soap\Soap as SoapContract;

class SoapNull extends Soap implements SoapContract
{
    public function send($url, $action = '', $request = '', $namespaces = [])
    {
    }
}
